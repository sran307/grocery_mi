@extends('layouts.master')
@section('title', 'General Settings')
@section('content')
<section class="gj_general_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <div class="col-lg-12">
                    <!-- <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> General Settings  </a></li>
                    </ul> -->
                    
                </div>
            </div>

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> General Settings  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'general_setting','class'=>'gj_geneal_form')) }}
                        <div class="form-group">
                            {{ Form::label('site_name', 'Site Name') }}
                            <span class="error">* 
                                @if ($errors->has('site_name'))
                                    {{ $errors->first('site_name') }}
                                @endif
                            </span>
                            @if(isset($general))
                                {{ Form::hidden('id', ($general->id ? $general->id : ''), array('class' => 'form-control')) }}

                                {{ Form::text('site_name', ($general->site_name ? $general->site_name : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::hidden('id', Input::old('id'), array('class' => 'form-control')) }}

                                {{ Form::text('site_name', Input::old('site_name'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <!-- <div class="form-group">
                            {{ Form::label('site_description', 'Site Description') }}
                            <span class="error">* 
                                @if ($errors->has('site_description'))
                                    {{ $errors->first('site_description') }}
                                @endif
                            </span>

                            @if(isset($general))
                                {{ Form::text('site_description', ($general->site_description ? $general->site_description : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('site_description', Input::old('site_description'), array('class' => 'form-control')) }}
                            @endif
                        </div> -->

                        <div class="form-group">
                            {{ Form::label('meta_title', 'Meta Title') }}
                            <span class="error">* 
                                @if ($errors->has('meta_title'))
                                    {{ $errors->first('meta_title') }}
                                @endif
                            </span>

                            @if(isset($general))
                                {{ Form::text('meta_title', ($general->meta_title ? $general->meta_title : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('meta_title', Input::old('meta_title'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('meta_keywords', 'Meta Keywords') }}
                            <span class="error">* 
                                @if ($errors->has('meta_keywords'))
                                    {{ $errors->first('meta_keywords') }}
                                @endif
                            </span>

                            @if(isset($general))
                                {{ Form::text('meta_keywords', ($general->meta_keywords ? $general->meta_keywords : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('meta_keywords', Input::old('meta_keywords'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('meta_description', 'Meta Description') }}
                            <span class="error">* 
                                @if ($errors->has('meta_description'))
                                    {{ $errors->first('meta_description') }}
                                @endif
                            </span>

                            @if(isset($general))
                                {{ Form::text('meta_description', ($general->meta_description ? $general->meta_description : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('meta_description', Input::old('meta_description'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('cod', 'Enable / Disable COD') }}
                            <span class="error">
                                @if ($errors->has('cod'))
                                    {{ $errors->first('cod') }}
                                @endif
                            </span>

                            @if(isset($general))
                                <input class="checkbox" type="checkbox" @if($general->cod == 1)  {{'checked'}} value="1" @else {{''}} value="0" @endif name="cod" id="cod" onclick="$(this).attr('value', this.checked ? 1 : 0)"/>
                            @else
                                <input class="checkbox" type="checkbox" value="" name="cod" id="cod" onclick="$(this).attr('value', this.checked ? 1 : 0)"/>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('paypal', 'Enable / Disable Online Payment') }}
                            <span class="error">
                                @if ($errors->has('paypal'))
                                    {{ $errors->first('paypal') }}
                                @endif
                            </span>

                            @if(isset($general))
                                <input class="checkbox" type="checkbox" @if($general->paypal == 1)  {{'checked'}} value="1" @else {{''}} value="0" @endif name="paypal" id="paypal" onclick="$(this).attr('value', this.checked ? 1 : 0)"/>
                            @else
                                <input class="checkbox" type="checkbox" name="paypal" value="" id="paypal" onclick="$(this).attr('value', this.checked ? 1 : 0)"/>
                            @endif
                        </div>

                        <!-- <div class="form-group">
                            {{ Form::label('pay_Umoney', 'Enable / Disable PayUmoney') }}
                            <span class="error">
                                @if ($errors->has('pay_Umoney'))
                                    {{ $errors->first('pay_Umoney') }}
                                @endif
                            </span>

                            @if(isset($general))
                                <input class="checkbox" type="checkbox" @if($general->pay_Umoney == 1)  {{'checked'}} value="1" @else {{''}} value="0" @endif name="pay_Umoney" id="pay_Umoney" onclick="$(this).attr('value', this.checked ? 1 : 0)"/>
                            @else
                                <input class="checkbox" type="checkbox" name="pay_Umoney" id="pay_Umoney" onclick="$(this).attr('value', this.checked ? 1 : 0)"/>
                            @endif
                        </div> -->
                        <p class="gj_not" style="color:red"><em>Note : Enable/ Disable COD and Online Payment Changes is affected for Checkout Page</em></p>

                        <div class="form-group">
                            {{ Form::label('frontend_url', 'Front End Url') }}
                            <span class="error">*
                                @if ($errors->has('frontend_url'))
                                    {{ $errors->first('frontend_url') }}
                                @endif
                            </span>

                            @if(isset($general))
                                {{ Form::text('frontend_url', ($general->frontend_url ? $general->frontend_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('frontend_url', Input::old('frontend_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('backend_url', 'Back End Url') }}
                            <span class="error">*
                                @if ($errors->has('backend_url'))
                                    {{ $errors->first('backend_url') }}
                                @endif
                            </span>

                            @if(isset($general))
                                {{ Form::text('backend_url', ($general->backend_url ? $general->backend_url : ''), array('class' => 'form-control', 'readonly')) }}
                            @else
                                {{ Form::text('backend_url', Input::old('backend_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('play_store_url', 'Play Store Url') }}
                            <span class="error">
                                @if ($errors->has('play_store_url'))
                                    {{ $errors->first('play_store_url') }}
                                @endif
                            </span>

                            @if(isset($general))
                                {{ Form::text('play_store_url', ($general->play_store_url ? $general->play_store_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('play_store_url', Input::old('play_store_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('ios_store_url', 'App Store (iOS)') }}
                            <span class="error">
                                @if ($errors->has('ios_store_url'))
                                    {{ $errors->first('ios_store_url') }}
                                @endif                                
                            </span>

                            @if(isset($general))
                                {{ Form::text('ios_store_url', ($general->ios_store_url ? $general->ios_store_url : ''), array('class' => 'form-control')) }}
                            @else
                                {{ Form::text('ios_store_url', Input::old('ios_store_url'), array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('cancel_terms', 'Terms & Conditions for Cancel Order') }}
                            <span class="error">
                                @if ($errors->has('cancel_terms'))
                                    {{ $errors->first('cancel_terms') }}
                                @endif                                
                            </span>

                            @if(isset($general))
                                {{ Form::textarea('cancel_terms', ($general->cancel_terms ? $general->cancel_terms : ''), array('class' => 'form-control', 'id' => 'gj_cancel_terms', 'rows' => '5')) }}
                            @else
                                {{ Form::textarea('cancel_terms', Input::old('cancel_terms'), array('class' => 'form-control', 'rows' => '5')) }}
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('return_terms', 'Terms & Conditions for Return/Replace Order') }}
                            <span class="error">
                                @if ($errors->has('return_terms'))
                                    {{ $errors->first('return_terms') }}
                                @endif                                
                            </span>

                            @if(isset($general))
                                {{ Form::textarea('return_terms', ($general->return_terms ? $general->return_terms : ''), array('class' => 'form-control', 'id' => 'gj_return_terms', 'rows' => '5')) }}
                            @else
                                {{ Form::textarea('return_terms', Input::old('return_terms'), array('class' => 'form-control', 'rows' => '5')) }}
                            @endif
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $('p.alert').delay(5000).slideUp(700);
</script>

<!-- Editor Script Start -->
    <script src="https://cdn.ckeditor.com/4.11.4/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace( 'gj_cancel_terms' );
        CKEDITOR.replace( 'gj_return_terms' );
    </script>
<!-- Editor Script End -->
@endsection
