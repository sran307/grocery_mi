@extends('layouts.master')
@section('title', 'Edit Country')
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
                        <li class="active"><a> Edit Country  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Country  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_all_country','class'=>'gj_et_cnt_form')) }}
                        @if($country)
                            {{ Form::hidden('c_id', $country->ID, array('class' => 'form-control gj_cm_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('name', 'Country Name') }}
                            <span class="error">* 
                                @if ($errors->has('name'))
                                    {{ $errors->first('name') }}
                                @endif
                            </span>

                            {{ Form::text('name', ($country->name ? $country->name : Input::old('name')), array('class' => 'form-control gj_name','')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('code', 'Country Code') }}
                            <span class="error">* 
                                @if ($errors->has('code'))
                                    {{ $errors->first('code') }}
                                @endif
                            </span>

                            {{ Form::text('code', ($country->code ? $country->code : Input::old('code')), array('class' => 'form-control gj_code','')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('dial_code', 'Country Dial Code') }}
                            <span class="error">* 
                                @if ($errors->has('dial_code'))
                                    {{ $errors->first('dial_code') }}
                                @endif
                            </span>

                            {{ Form::text('dial_code', ($country->dial_code ? $country->dial_code : Input::old('dial_code')), array('class' => 'form-control gj_dial_code','')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('currency_name', 'Currency Name') }}
                            <span class="error">* 
                                @if ($errors->has('currency_name'))
                                    {{ $errors->first('currency_name') }}
                                @endif
                            </span>

                            {{ Form::text('currency_name', ($country->currency_name ? $country->currency_name : Input::old('currency_name')), array('class' => 'form-control gj_currency_name','')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('currency_symbol', 'Currency Symbol') }}
                            <span class="error">* 
                                @if ($errors->has('currency_symbol'))
                                    {{ $errors->first('currency_symbol') }}
                                @endif
                            </span>

                            {{ Form::text('currency_symbol', ($country->currency_symbol ? $country->currency_symbol : Input::old('currency_symbol')), array('class' => 'form-control gj_currency_symbol','')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('currency_code', 'Currency Code') }}
                            <span class="error">* 
                                @if ($errors->has('currency_code'))
                                    {{ $errors->first('currency_code') }}
                                @endif
                            </span>

                            {{ Form::text('currency_code', ($country->currency_code ? $country->currency_code : Input::old('currency_code')), array('class' => 'form-control gj_currency_code','')) }}
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
    });
</script>
@endsection
