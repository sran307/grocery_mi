@extends('layouts.frontend')
@section('title', 'Verification')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
<section class="gj_buss_signin_bk">
    <div class="main-content maxil" id="MainContent">
        <div class="container">
            <div class="wraper-inner sn">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="formaccount formlogin gj_verf_main">
                            <div id="login">
                                {{-- @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif --}}
                                <h1 class="page-title">@if($verf) {{$verf}} @endif Verification</h1>

                                <div class="gj_verf_em">
                                    <form method="post" action="{{ route('checkverify') }}" id="verf_mob_email" accept-charset="UTF-8">
                                        <div class="form-group">
                                            <label for="otp">Enter OTP</label>
                                            <span class="error">* 
                                                @if ($errors->has('otp'))
                                                    {{ $errors->first('otp') }}
                                                @endif
                                            </span>
                                            <input type="text" name="otp" class="form-control" id="otp">
                                        </div>
                                     
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-default"><i class="fa fa-sign-in" aria-hidden="true"></i>Verify</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection