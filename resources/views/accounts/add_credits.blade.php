@extends('layouts.master')
@section('title', 'Add Credits')
@section('content')
<section class="gj_crd_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>
        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add Credits  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Credits  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_credits','class'=>'gj_credit_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('merchant_id', 'Merchant') }}
                            <span class="error">* 
                                @if ($errors->has('merchant_id'))
                                    {{ $errors->first('merchant_id') }}
                                @endif
                            </span>

                            {{ Form::text('d_merchant_id', ($credits->first_name ? $credits->first_name.' '.$credits->last_name : Input::old('d_merchant_id')), array('class' => 'form-control gj_d_merchant_id', 'disabled','placeholder' => 'Enter Merchant')) }}

                            {{ Form::hidden('merchant_id', ($credits->id ? $credits->id : Input::old('merchant_id')), array('class' => 'form-control gj_merchant_id', 'placeholder' => 'Enter Merchant')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('current_credits', 'Current Credits') }}
                            <span class="error">* 
                                @if ($errors->has('current_credits'))
                                    {{ $errors->first('current_credits') }}
                                @endif
                            </span>

                            {{ Form::text('d_current_credits', ($credits->credits ? $credits->credits : 0.00), array('class' => 'form-control gj_current_credits', 'disabled','placeholder' => 'Enter Current Credits in rupees')) }}

                            {{ Form::hidden('current_credits', ($credits->credits ? $credits->credits : 0.00), array('class' => 'form-control gj_current_credits', 'placeholder' => 'Enter Current Credits in rupees')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('add_credits', 'Add Credits') }}
                            <span class="error">* 
                                @if ($errors->has('add_credits'))
                                    {{ $errors->first('add_credits') }}
                                @endif
                            </span>

                            {{ Form::text('add_credits', Input::old('add_credits'), array('class' => 'form-control gj_add_credits', 'placeholder' => 'Enter Add Credits in rupees')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('remarks', 'Remarks') }}
                            <span class="error">* 
                                @if ($errors->has('remarks'))
                                    {{ $errors->first('remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('remarks', Input::old('remarks'), array('class' => 'form-control gj_remarks', 'rows' => '5', 'placeholder' => 'Enter Your Remarks')) }}
                        </div>

                        {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();
        $("#credit_type").select2();

        var cnt = 2;
        $("#img_addButton").click(function () {
            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'gj_tr_m_doc_' + cnt);
            newTextBoxDiv.after().html('<td><input class="form-control gj_d_name" placeholder="Enter Product Name" name="d_name[]" type="text" id="d_name_' + cnt + '"></td><td><input type="file" name="d_image[]" id="d_image_' + cnt + '" class="gj_d_image form-control"></td><td><button type="button" id="img_removeButton_' + cnt + '" class="gj_m_doc_rem"><i class="fa fa-trash"></i></button></td>');
            newTextBoxDiv.appendTo("#gj_m_doc_bdy");
            cnt++;
        });

        $('body').on('click','.gj_m_doc_rem',function() {
            if(cnt==1){
                $.confirm({
                    title: '',
                    content: 'No more textbox to remove!',
                    icon: 'fa fa-ban',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                            
                        }
                    }
                });
                return false;
            }   
        
            cnt--;
            $(this).closest('tr').remove();
        });
    });

    $('#country').on('change',function() {
        var country = $(this).val();
        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Country!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                    
                                }
                            }
                        });
                        $("#state").prop("disabled", true);
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Country!',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                        
                    }
                }
            });
        }
    });

    $('#state').on('change',function() {
        var st = $(this).val();
        if(st) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_city')}}',
                data: {st: st, type: 'city'},
                success: function(data){
                    if(data){
                        $("#city").html(data);
                        $("#city").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select State!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                    
                                },
                                Cancel:function() {
                                }
                            }
                        });
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select State!',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                        
                    },
                    Cancel:function() {
                    }
                }
            });
        }
    });
</script>
@endsection
