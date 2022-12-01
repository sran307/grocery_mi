@extends('layouts.master')
@section('title', 'Edit Country')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Country  </a></li>
                    </ul>
                </div>
            </div>

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Country  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_country','class'=>'gj_geneal_form')) }}
                        <div class="form-group">
                            
                            {{ Form::hidden('cm_id', $country->id, array('class' => 'form-control gj_cm_id')) }}

                            {{ Form::label('country_name', 'Country Name') }}
                            <span class="error">* 
                                @if ($errors->has('country_name'))
                                    {{ $errors->first('country_name') }}
                                @endif
                            </span>

                            <?php 
                                $opt = '';
                                $ctys = \DB::table('countries')->get();
                                if(($ctys) && (count($ctys) != 0)){
                                    foreach ($ctys as $key => $value) {
                                        if($country->country_id == $value->ID) {
                                            $opt.='<option selected value="'.$value->ID.'">'.$value->name.'</option>';
                                        } else {
                                            $opt.='<option value="'.$value->ID.'">'.$value->name.'</option>';
                                        }
                                    }
                                } 
                            ?>
                            <select id="country_name" name="country_name" class="form-control">
                                <option value="0" selected disabled>Select Country</option>
                                <?php echo $opt; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('country_code', 'Country Code') }}
                            <span class="error">* 
                                @if ($errors->has('country_code'))
                                    {{ $errors->first('country_code') }}
                                @endif
                            </span>

                            {{ Form::text('d_country_code', Input::old('d_country_code'), array('class' => 'form-control gj_country_code','disabled')) }}
                            {{ Form::hidden('country_code', Input::old('country_code'), array('class' => 'form-control gj_country_code')) }}
                            {{ Form::hidden('h_country_name', Input::old('h_country_name'), array('class' => 'form-control gj_h_country_name')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('currency_symbol', 'Currency Symbol') }}
                            <span class="error">* 
                                @if ($errors->has('currency_symbol'))
                                    {{ $errors->first('currency_symbol') }}
                                @endif
                            </span>

                            {{ Form::text('d_currency_symbol', Input::old('d_currency_symbol'), array('class' => 'form-control gj_currency_symbol','disabled')) }}
                            {{ Form::hidden('currency_symbol', Input::old('currency_symbol'), array('class' => 'form-control gj_currency_symbol')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('currency_code', 'Currency Code') }}
                            <span class="error">* 
                                @if ($errors->has('currency_code'))
                                    {{ $errors->first('currency_code') }}
                                @endif
                            </span>

                            {{ Form::text('d_currency_code', Input::old('d_currency_code'), array('class' => 'form-control gj_currency_code','disabled')) }}
                            {{ Form::hidden('currency_code', Input::old('currency_code'), array('class' => 'form-control gj_currency_code')) }}
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
        $("#country_name").select2();

        var c_id = $('#country_name').select2('val');

        $.ajax({
            type: 'post',
            url: '{{url('/country_details')}}',
            data: {c_id: c_id, type: 'details'},
            success: function(data){
                if(data != ""){
                    var data = $.parseJSON(data);
                    $('.gj_h_country_name').val(data.name);
                    $('.gj_country_code').val(data.code);
                    $('.gj_currency_symbol').val(data.currency_symbol);
                    $('.gj_currency_code').val(data.currency_code);
                } else {
                    $.confirm({
                        title: '',
                        content: 'No More Data Here!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'purple',
                        buttons: {
                            Ok: function(){
                            }
                        }
                    });
                }
            }
        });
    });

    $('#country_name').on('change',function(){
        var c_id = $(this).select2('val');

        $.ajax({
            type: 'post',
            url: '{{url('/country_details')}}',
            data: {c_id: c_id, type: 'details'},
            success: function(data){
                if(data != ""){
                    var data = $.parseJSON(data);
                    $('.gj_h_country_name').val(data.name);
                    $('.gj_country_code').val(data.code);
                    $('.gj_currency_symbol').val(data.currency_symbol);
                    $('.gj_currency_code').val(data.currency_code);
                } else {
                    $.confirm({
                        title: '',
                        content: 'No More Data Here!',
                        icon: 'fa fa-exclamation',
                        theme: 'modern',
                        closeIcon: true,
                        animation: 'scale',
                        type: 'purple',
                        buttons: {
                            Ok: function(){
                            }
                        }
                    });
                }
            }
        });
    });
</script>
@endsection
