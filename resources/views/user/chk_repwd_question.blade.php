@extends('layouts.master')
@section('title', 'Reset Password')
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
                            <div class="gj_skl_alt">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                            </div>
                            <div class="wrap-login100">
                                <div class="login100-pic js-tilt" data-tilt>
                                    <img src="{{ asset('login/reset.jpg')}}" alt="IMG">
                                </div>

                                {{ Form::open(array('url' => 'chk_repwd_answer','class'=>'login100-form validate-form','files' => true)) }}
                                    <span class="login100-form-title">
                                        Reset Password
                                    </span>

                                    <div class="wrap-input100 validate-input" data-validate = "Enter Your Mobile Number">
                                        <input class="input100" type="text" name="mobno" placeholder="Email or Mobile Number">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-address-book" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <p class="error gj_l_err"> 
                                        @if ($errors->has('mobno'))
                                            {{ $errors->first('mobno') }}
                                        @endif
                                    </p>

                                    <div class="wrap-input100 validate-input gj_bk_supw" data-validate = "Select Your Security Question">
                                        @php ($opt = '<option value="">Select Your Security Question </option>')
                                        @if(isset($secure) && sizeof($secure) != 0)
                                            @foreach($secure as $skey => $sval)
                                                <?php 
                                                    $opt.= '<option value="'.$sval->id.'"> '.$sval->question.' </option>';    
                                                ?>
                                            @endforeach
                                        @endif
                                        <select name="question" id="question" class="input100 gj_bk_s_question">
                                            <?php echo $opt; ?>
                                        </select>
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-address-book" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <p class="error gj_l_err"> 
                                        @if ($errors->has('question'))
                                            {{ $errors->first('question') }}
                                        @endif
                                    </p>

                                    <div class="wrap-input100 validate-input" data-validate = "Select Your Security Answer">
                                        <input class="input100 gj_bk_s_answer" type="text" name="answer" autocomplete="new-answer" placeholder="Enter Your Security Answer">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-address-book" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input type="hidden" name="check" value="reset">
                                    <p class="error gj_l_err"> 
                                        @if ($errors->has('answer'))
                                            {{ $errors->first('answer') }}
                                        @endif
                                    </p>

                                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                                        <input class="input100" type="password" name="password" placeholder="New Password">
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

                                    <div class="wrap-input100 validate-input" data-validate = "Confirm Password is required">
                                        <input class="input100" type="password" name="password_salt" placeholder="Confirm Password">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <p class="error gj_l_err"> 
                                        @if ($errors->has('password_salt'))
                                            {{ $errors->first('password_salt') }}
                                        @endif
                                    </p>
                                    
                                    <div class="container-login100-form-btn">
                                        <button class="login100-form-btn" type="submit">
                                            Submit
                                        </button>
                                    </div>
                                {{ Form::close() }}
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
<script src="{{ asset('login/main.js')}}"></script>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(7000).slideUp(500); 
    });
</script>
@endsection
