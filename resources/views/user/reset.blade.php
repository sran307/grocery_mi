@extends('layouts.frontend')
@section('title', 'Reset Password')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
<div class="sign-inup">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-5">
<div class="sign-form">
<div class="sign-inner">
<div class="sign-logo" id="logo">
<a href="{{url('/')}}"><img src="{{asset('assets')}}/logo.svg" alt=""></a>
<a href="{{url('/')}}"><img class="logo-inverse" src="{{asset('assets')}}/images/dark-logo.svg" alt=""></a>
</div>
<div class="form-dt">
<div class="form-inpts checout-address-step">
 {{ Form::open(array('url' => 'reset_password','class'=>'login100-form validate-form','files' => true)) }}

<div class="form-title"><h6>  Reset Password </h6></div>
<div class="form-group pos_rel">
     <input type="text" name="remember_token" placeholder="Reset Code" class="form-control lgn_input" required="">
      <p class="error gj_l_err"> 
                                        @if ($errors->has('remember_token'))
                                            {{ $errors->first('remember_token') }}
                                        @endif
                                    </p>
<i class="uil uil-envelope lgn_icon"></i>
</div>
<div class="form-group pos_rel">
    <input type="password" name="password" placeholder="New Password" class="form-control lgn_input" required="">
    <p class="error gj_l_err"> 
                                        @if ($errors->has('password'))
                                            {{ $errors->first('password') }}
                                        @endif
                                    </p>

<i class="uil uil-padlock lgn_icon"></i>
</div>
<div class="form-group pos_rel">
<input type="password" name="password_salt" placeholder="Confirm Password" class="form-control lgn_input" required="">
 <p class="error gj_l_err"> 
    @if ($errors->has('password_salt'))
        {{ $errors->first('password_salt') }}
    @endif
</p>
<i class="uil uil-padlock lgn_icon"></i>
</div>
<div class="form-group pos_rel">
 <p class="gj_taw_pwd">If Didn't receive any reset code <a href="{{ route('chk_repwd_question') }}" class="link-color">Try Another Way?</a></p>

</div>
<button class="login-btn hover-btn" type="submit">Reset Password</button>
 {{ Form::close() }}
</div>
<div class="signup-link">
<p>Go Back - <a href="{{url('signin')}}"> Login </a></p>
</div>
</div>
</div>
</div>
 
</div>
</div>
</div>
</div>

<script src="{{ asset('login/tilt.jquery.min.js')}}"></script>
<script >
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>
<script src="{{ asset('login/main.js')}}"></script>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
    });
</script>
@endsection