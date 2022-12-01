@extends('layouts.frontend')
@section('title', 'Resend Activation URL')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
<section class="gj_resend_bk">
    <div class="main-content maxil" id="MainContent">
        <div class="container">
            <div class="wraper-inner sn">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="formaccount formlogin">
                            <div id="login">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <h1 class="page-title">Resend Activation URL</h1>
                                {{ Form::open(array('url'=>'resend_url','class'=>'gj_acturl_form','id'=>'customer_resend_url','files' => true)) }}
                                    <div class="form-group">
                                        <label for="email">Email or Mobile number <sup>*</sup></label>
                                        <span class="error">* 
                                            @if ($errors->has('email'))
                                                {{ $errors->first('email') }}
                                            @endif
                                        </span>
                                        <input type="text" name="email" class="form-control" id="email">
                                    </div>
                                 
                                    <div class="form-group">
                                        <label for="password">Password <sup>*</sup></label>
                                        <span class="error">* 
                                            @if ($errors->has('password'))
                                                {{ $errors->first('password') }}
                                            @endif
                                        </span>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-sign-in" aria-hidden="true"></i>Login</button>
                                        </div>
                                    </div>
                                    <br>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
    });
</script>
@endsection
