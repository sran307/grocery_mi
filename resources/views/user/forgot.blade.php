@extends('layouts.master')
@section('title', 'Forgot Password')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
<section class="gj_login_bk">
    <div class="row gj_row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="gj_login_box">
                <div class="col-md-12">
                    <div class="limiter">
                        <div class="container-login100">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                            @endif
                            <div class="wrap-login100">
                                <div class="login100-pic js-tilt" data-tilt>
                                    <img src="{{ asset('login/forgot.jpg')}}" alt="IMG">
                                </div>

                                <form class="login100-form validate-form" method="post" action="{{ route('forgot') }}">
                                    <span class="login100-form-title">
                                        Forgot Password
                                    </span>

                                    <div class="wrap-input100 validate-input">
                                        <input class="input100" type="text" name="email_id" placeholder="Email">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <p class="error gj_l_err"> 
                                        @if ($errors->has('email_id'))
                                            {{ $errors->first('email_id') }}
                                        @endif
                                    </p>

                                    <p class="gj_fp_or">or</p>

                                    <div class="wrap-input100 validate-input">
                                        <input class="input100" type="number" name="mobnumber" placeholder="Moblile Number">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <p class="error gj_l_err"> 
                                        @if ($errors->has('mobnumber'))
                                            {{ $errors->first('mobnumber') }}
                                        @endif
                                    </p>
                                    
                                    <div class="container-login100-form-btn">
                                        <button class="login100-form-btn" type="submit">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
<!-- <script src="{{ asset('login/main.js')}}"></script> -->

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
    });
</script>
@endsection
