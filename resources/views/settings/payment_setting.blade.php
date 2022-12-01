@extends('layouts.master')
@section('title', 'Payment Settings')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Payment Settings  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Payment Settings  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'payment_setting','class'=>'gj_payment_form')) }}
                        <div class="form-group">
                            {{ Form::label('country_name', 'Country Name') }}
                            <span class="error">* 
                                @if ($errors->has('country_name'))
                                    {{ $errors->first('country_name') }}
                                @endif
                            </span>
                            @if(isset($payment))
                                {{ Form::hidden('id', ($payment->id ? $payment->id : ''), array('class' => 'form-control')) }}

                                <?php 
                                    $opt = '';
                                    $ctys = \DB::table('countries_managements')->where('is_block',1)->get();
                                    if(($ctys) && (count($ctys) != 0)){
                                        foreach ($ctys as $key => $value) {
                                            if($value->id == $payment->country_id) {
                                                $opt.='<option selected value="'.$value->id.'">'.$value->country_name.'</option>';
                                            } else {
                                                $opt.='<option value="'.$value->id.'">'.$value->country_name.'</option>';
                                            }
                                        }
                                    } 
                                ?>
                                <select id="country_name" name="country_name" class="form-control">
                                    <option value="0" selected disabled>Select Country</option>
                                    <?php echo $opt; ?>
                                </select>
                                {{ Form::hidden('h_country_name', $payment->country_name, array('class' => 'form-control gj_h_country_name')) }}
                            @else
                                {{ Form::hidden('id', Input::old('id'), array('class' => 'form-control')) }}

                                <?php 
                                    $opt = '';
                                    $ctys = \DB::table('countries_managements')->where('is_block',1)->get();
                                    if(($ctys) && (count($ctys) != 0)){
                                        foreach ($ctys as $key => $value) {
                                            $opt.='<option value="'.$value->id.'">'.$value->country_name.'</option>';
                                        }
                                    } 
                                ?>
                                <select id="country_name" name="country_name" class="form-control">
                                    <option value="0" selected disabled>Select Country</option>
                                    <?php echo $opt; ?>
                                </select>
                                {{ Form::hidden('h_country_name', Input::old('h_country_name'), array('class' => 'form-control gj_h_country_name')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('country_code', 'Country Code') }}
                            <span class="error">* 
                                @if ($errors->has('country_code'))
                                    {{ $errors->first('country_code') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                <!-- {{ Form::text('d_country_code', ($payment->country_code ? $payment->country_code : ''), array('class' => 'form-control gj_country_code','disabled')) }} -->

                                {{ Form::text('d_country_code', '', array('class' => 'form-control gj_country_code','disabled')) }}
                                {{ Form::hidden('country_code', ($payment->country_code ? $payment->country_code : ''), array('class' => 'form-control gj_country_code')) }}
                            @else
                                {{ Form::text('d_country_code', Input::old('d_country_code'), array('class' => 'form-control gj_country_code','disabled')) }}
                                {{ Form::hidden('country_code', Input::old('country_code'), array('class' => 'form-control gj_country_code')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('currency_symbol', 'Currency Symbol') }}
                            <span class="error">* 
                                @if ($errors->has('currency_symbol'))
                                    {{ $errors->first('currency_symbol') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                <!-- {{ Form::text('d_currency_symbol', ($payment->currency_symbol ? $payment->currency_symbol : ''), array('class' => 'form-control gj_currency_symbol','disabled')) }} -->

                                {{ Form::text('d_currency_symbol', '', array('class' => 'form-control gj_currency_symbol','disabled')) }}
                                {{ Form::hidden('currency_symbol', ($payment->currency_symbol ? $payment->currency_symbol : ''), array('class' => 'form-control gj_currency_symbol')) }}
                            @else
                                {{ Form::text('d_currency_symbol', Input::old('d_currency_symbol'), array('class' => 'form-control gj_currency_symbol','disabled')) }}
                                {{ Form::hidden('currency_symbol', Input::old('currency_symbol'), array('class' => 'form-control gj_currency_symbol')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('currency_code', 'Currency Code') }}
                            <span class="error">* 
                                @if ($errors->has('currency_code'))
                                    {{ $errors->first('currency_code') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                <!-- {{ Form::text('d_currency_code', ($payment->currency_code ? $payment->currency_code : ''), array('class' => 'form-control gj_currency_code','disabled')) }} -->

                                {{ Form::text('d_currency_code', '', array('class' => 'form-control gj_currency_code','disabled')) }}
                                {{ Form::hidden('currency_code', ($payment->currency_code ? $payment->currency_code : ''), array('class' => 'form-control gj_currency_code')) }}
                            @else
                                {{ Form::text('d_currency_code', Input::old('d_currency_code'), array('class' => 'form-control gj_currency_code','disabled')) }}
                                {{ Form::hidden('currency_code', Input::old('currency_code'), array('class' => 'form-control gj_currency_code')) }}
                            @endif
                        </div>

                        <!-- <div class="form-group">
                            {{ Form::label('paypal_account', 'Paypal Account') }}
                            <span class="error"> 
                                @if ($errors->has('paypal_account'))
                                    {{ $errors->first('paypal_account') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                {{ Form::text('paypal_account', ($payment->paypal_account ? $payment->paypal_account : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('paypal_account', Input::old('paypal_account'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('paypal_api_password', 'Paypal Api Password') }}
                            <span class="error"> 
                                @if ($errors->has('paypal_api_password'))
                                    {{ $errors->first('paypal_api_password') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                {{ Form::text('paypal_api_password', ($payment->paypal_api_password ? $payment->paypal_api_password : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('paypal_api_password', Input::old('paypal_api_password'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('paypal_api_signature', 'Paypal Api Signature') }}
                            <span class="error"> 
                                @if ($errors->has('paypal_api_signature'))
                                    {{ $errors->first('paypal_api_signature') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                {{ Form::text('paypal_api_signature', ($payment->paypal_api_signature ? $payment->paypal_api_signature : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('paypal_api_signature', Input::old('paypal_api_signature'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('payUmoney_key', 'PayUmoney Key') }}
                            <span class="error"> 
                                @if ($errors->has('payUmoney_key'))
                                    {{ $errors->first('payUmoney_key') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                {{ Form::text('payUmoney_key', ($payment->payUmoney_key ? $payment->payUmoney_key : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('payUmoney_key', Input::old('payUmoney_key'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('payUmoney_salt', 'PayUmoney Salt') }}
                            <span class="error"> 
                                @if ($errors->has('payUmoney_salt'))
                                    {{ $errors->first('payUmoney_salt') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                {{ Form::text('payUmoney_salt', ($payment->payUmoney_salt ? $payment->payUmoney_salt : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('payUmoney_salt', Input::old('payUmoney_salt'), array('class' => 'form-control')) }}
                            @endif
                        </div> -->

                        <div class="form-group">
                            {{ Form::label('cash_free_api', 'Cask Free Api Key') }}
                            <span class="error"> 
                                @if ($errors->has('cash_free_api'))
                                    {{ $errors->first('cash_free_api') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                {{ Form::text('cash_free_api', ($payment->cash_free_api ? $payment->cash_free_api : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('cash_free_api', Input::old('cash_free_api'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('cash_free_secret', 'Cask Free Secret Key') }}
                            <span class="error"> 
                                @if ($errors->has('cash_free_secret'))
                                    {{ $errors->first('cash_free_secret') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                {{ Form::text('cash_free_secret', ($payment->cash_free_secret ? $payment->cash_free_secret : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('cash_free_secret', Input::old('cash_free_secret'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('payment_mode', 'Payment Mode') }}
                            <span class="error">* 
                                @if ($errors->has('payment_mode'))
                                    {{ $errors->first('payment_mode') }}
                                @endif
                            </span>

                            @if(isset($payment))
                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" <?php if($payment->payment_mode == 0) { echo 'checked'; } ?> name="payment_mode" value="0"> Test Account
                                    </span>
                                    <span class="gj_py_ro">
                                        <input type="radio" <?php if($payment->payment_mode == 1) { echo 'checked'; } ?> name="payment_mode" value="1"> Live Account
                                    </span>
                                </div>
                            @else
                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" name="payment_mode" value="0"> Test Account
                                    </span>
                                    <span class="gj_py_ro">
                                        <input type="radio" name="payment_mode" value="1"> Live Account
                                    </span>
                                </div>
                            @endif
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
        $('p.alert').delay(1000).slideUp(300); 

        var c_id = $('#country_name').select2('val');
        if(c_id){
            $.ajax({
                type: 'post',
                url: '{{url('/pcountry_details')}}',
                data: {c_id: c_id, type: 'details'},
                success: function(data){
                    if(data != ""){
                        var data = $.parseJSON(data);
                        $('.gj_h_country_name').val(data.country_name);
                        $('.gj_country_code').val(data.country_code);
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
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        // window.location.reload();
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Country Name!',
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

    $('#country_name').on('change',function(){
        var c_id = $(this).select2('val');
        if(c_id) {
            $.ajax({
                type: 'post',
                url: '{{url('/pcountry_details')}}',
                data: {c_id: c_id, type: 'details'},
                success: function(data){
                    if(data != ""){
                        var data = $.parseJSON(data);
                        $('.gj_h_country_name').val(data.country_name);
                        $('.gj_country_code').val(data.country_code);
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
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });     
                        // window.location.reload();
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Country Name!',
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
