@extends('layouts.master')
@section('title', 'Login')
@section('hdr_class', 'gj_login_header')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
<section class="gj_login_bk">
    <div class="row gj_row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="gj_login_box">
                <div class="col-md-1"> </div>
                <div class="col-md-10">
                    <div class="limiter">
                        <div class="container-login100">
                            <div class="wrap-login100">
                                <div class="login100-pic js-tilt" data-tilt>
                                    <img src="{{ asset('login/login.png')}}" alt="IMG">
                                </div>

                                <form class="login100-form validate-form" method="post" action="{{ route('admin') }}">
                                    @if(Session::has('message'))
                                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                    @endif
                                
                                    <span class="login100-form-title">
                                        Admin Login
                                    </span>

                                    <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                                        <input class="input100" type="text" name="email" placeholder="Email">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <p class="error gj_l_err"> 
                                        @if ($errors->has('email'))
                                            {{ $errors->first('email') }}
                                        @endif
                                    </p>

                                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                                        <input class="input100" type="password" name="password" placeholder="Password">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <p class="error gj_l_err"> 
                                        @if ($errors->has('password'))
                                            {{ $errors->first('password') }}
                                        @endif
                                    </p>

                                    <div class="form-group">
                                        <label for="password">Remember me</label>
                                        
                                        <input type="checkbox" name="remember" id="remember" <?php if(isset($_COOKIE["user"])) { echo "checked"; } ?> />
                                    </div>
                                    
                                    <div class="container-login100-form-btn">
                                        <button class="login100-form-btn" type="submit">
                                            Login
                                        </button>
                                    </div>

                                    <div class="text-center p-t-12">
                                        <span class="txt1">
                                            Forgot
                                        </span>
                                        <a class="txt2" href="{{ route('forgot') }}">
                                            Password?
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-1"> </div>
                
                
            </div>
        </div>
    </div>
</section>

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