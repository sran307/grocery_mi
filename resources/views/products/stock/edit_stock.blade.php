@extends('layouts.master')
@section('title', 'Edit Stock Details')
@section('content')
<?php $log = session()->get('user'); ?>

<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.product_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Stock Details  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Stock Details  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_stock','class'=>'gj_stock_form','files' => true)) }}
                        @if($stock)
                            {{ Form::hidden('stock_id', $stock->id, array('class' => 'form-control gj_stock_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('product_id', 'Select Products') }}
                            <span class="error">* 
                                @if ($errors->has('product_id'))
                                    {{ $errors->first('product_id') }}
                                @endif
                            </span>

                            <?php 
                                $opt = '';
                                $product = '';
                                if($log) {
                                    if($log->user_type == 1) {
                                        $product = \DB::table('products')->where('is_block',1)->get();
                                    } elseif ($log->user_type == 2 || $log->user_type == 3) {
                                        $product = \DB::table('products')->where('is_block',1)->where('created_user', $log->id)->get();
                                    }
                                }

                                if(($product) && (count($product) != 0)){
                                    foreach ($product as $key => $value) {
                                        if ($value->id == $stock->product_id) {
                                            $opt.='<option selected value="'.$value->id.'">'.$value->product_title.'</option>';
                                        } else {
                                            $opt.='<option value="'.$value->id.'">'.$value->product_title.'</option>';
                                        }
                                    }
                                } 
                            ?>
                            <select id="product_id" name="product_id" class="form-control">
                                <option value="0" selected disabled>Select Products</option>
                                <?php echo $opt; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('current_qty', 'Current Quantity') }}
                            <span class="error">* 
                                @if ($errors->has('current_qty'))
                                    {{ $errors->first('current_qty') }}
                                @endif
                            </span>

                            {{ Form::number('d_current_qty', Input::old('d_current_qty'), array('class' => 'form-control gj_d_current_qty','placeholder' => 'Current Quantity', 'disabled')) }}

                            {{ Form::hidden('current_qty', Input::old('current_qty'), array('class' => 'form-control gj_current_qty','placeholder' => 'Current Quantity')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('addon_qty', 'Add On Quantity') }}
                            <span class="error">* 
                                @if ($errors->has('addon_qty'))
                                    {{ $errors->first('addon_qty') }}
                                @endif
                            </span>

                            <!-- {{ Form::number('addon_qty', ($stock->addon_qty ? $stock->addon_qty : Input::old('addon_qty')), array('class' => 'form-control gj_addon_qty','placeholder' => 'Add On Quantity')) }} -->
                            {{ Form::number('addon_qty', Input::old('addon_qty'), array('class' => 'form-control gj_addon_qty','placeholder' => 'Add On Quantity')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('date', 'Date') }}
                            <span class="error">* 
                                @if ($errors->has('date'))
                                    {{ $errors->first('date') }}
                                @endif
                            </span>

                            {{ Form::date('date', ($stock->date ? $stock->date : Input::old('date')), array('class' => 'form-control gj_stk_date','placeholder' => 'Date')) }}
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
        $("#product_id").select2();

        var p_id = $('#product_id').select2('val');
        if(p_id) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_qty')}}',
                data: {p_id: p_id, type: 'qty'},
                success: function(data){
                    if(data){
                        $("#current_qty").val(data);
                        $(".gj_d_current_qty").val(data);
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Quantity Not Available!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        $("#current_qty").val('0');
                        $(".gj_d_current_qty").val('0');
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Another Time!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });

    $('#product_id').on('change',function(){
        var p_id = $(this).select2('val');
        if(p_id) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_qty')}}',
                data: {p_id: p_id, type: 'qty'},
                success: function(data){
                    if(data){
                        $("#current_qty").val(data);
                        $(".gj_d_current_qty").val(data);
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Quantity Not Available!',
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        $("#current_qty").val('0');
                        $(".gj_d_current_qty").val('0');
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Another Time!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });

</script>
@endsection