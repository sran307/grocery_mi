@extends('groceryView.layouts.headerFooter')
@section('title', 'SignIn')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
<div class="sign-inup">
     
    <div class="container">
    
        <div class="row justify-content-center">
       
            <div class="col-lg-5">
                <div class="sign-form">
                    <div id="recover-password" style="display:none;" class="wrap">
                        <div class="block">
                            <h1 class="page-title">Reset your password</h1>
                            <p>We will send you an email to reset your password.</p>
                        </div>
                                
                        <div class="form-vertical">
                            <form method="post" action="{{ route('forgot') }}" accept-charset="UTF-8">
                                <div class="form-group">
                                    <label for="RecoverEmail"> Registered Email</label>
                                    <span class="error">* 
                                        @if ($errors->has('email_id'))
                                            {{ $errors->first('email_id') }}
                                        @endif
                                    </span>
                                    <input type="email" value="" name="email_id" id="RecoverEmail" class="form-control" autocorrect="off" autocapitalize="off">
                                </div>
                                <div class="submit">
                                    <p>
                                        <input type="submit" class="btn btn-default" value="Submit">
                                    </p>
                                </div>
                                    
                                        <!--  <h2> OR </h2>
                                        <div class="block">
                                            <p>We will send you verified mobile number via OTP</p>
                                        </div>

                                        <div class="form-group">
                                            <label for="RecoverEmail">Registered Mobile Number</label>
                                            <span class="error">* 
                                                @if ($errors->has('mobnumber'))
                                                    {{ $errors->first('mobnumber') }}
                                                @endif
                                            </span>
                                            <input type="number" value="" name="mobnumber" id="Recovernumber" class="form-control" autocorrect="off" autocapitalize="off">
                                        </div>
                                        
                                        <div class="submit">
                                            <p>
                                                <input type="submit" class="btn btn-default" value="Send OTP">
                                            </p>
                                        </div>-->
                                        or
                                <a class="" href="#" onclick="hideRecoverPasswordForm();return false;">Cancel</a>
                            </form>
                        </div>
                    </div>
                    <div class="sign-inner" id="login">
                       
                       <!-- <div class="sign-logo"  id="logo">
                            <a href="{{url('/')}}">
                                <img src="{{asset('assets')}}/logo.svg" alt=""></a>
                            <a href="{{url('/')}}">
                                <img class="logo-inverse" src="{{asset('assets')}}/dark-logo.svg" alt=""></a>
                        </div>-->
                        <div class="form-dt">
                            <div class="form-inpts checout-address-step">
                                    @if(Session::has('message'))
                                       <p class="alert alert-{{session::get('alert-class')}} sr_alert">{{session::get('message')}}</p>
                                    @endif
                                <form method="post" action="{{ route('check_signin_mob') }}" id="customer_login_email" accept-charset="UTF-8">
                                    <input type="hidden" name="form_type" value="customer_login">
                                    <input type="hidden" name="utf8" value="✓">
                                    <div class="form-title"><h6> Login </h6></div>
                                    <div class="form-group pos_rel">
                                        <span class="error">* 
                                            @if ($errors->has('phone'))
                                                {{ $errors->first('phone') }}
                                            @endif
                                        </span>
                                        <input type="number" placeholder="Enter Phone NUmber" name="phone" class="form-control lgn_input" id="phone" required>
                                        <input type="hidden" name="bk_log_with" class="bk_log_with">
                                        <i class="uil uil-mobile-android-alt lgn_icon"></i>
                                    </div>

                                    <div class="form-group pos_rel">
                                        <label class="control-label">Enter Code</label>
                                        <ul class="code-alrt-inputs signup-code-list">
                                            <li>
                                                <input id="code_1" name="number" type="text" placeholder="" class="form-control input-md" maxlength="1">
                                            </li>
                                            <li>
                                                <input id="code_2" name="number" type="text" placeholder="" class="form-control input-md" maxlength="1" >
                                            </li>
                                            <li>
                                                <input id="code_3" name="number" type="text" placeholder="" class="form-control input-md" maxlength="1" >
                                            </li>
                                            <li>
                                                <input id="code_4" name="number" type="text" placeholder="" class="form-control input-md" maxlength="1">
                                            </li>
                                            <li>
                                                <a class="chck-btn hover-btn code-btn145 srj_send_login_otp">Send</a>
                                                <a class="chck-btn hover-btn code-btn145 srj_check_otp " style="display:none" >Verify</a>
                                                 <span id="btn_span">Click For Send OTP</span>
                                            </li>
                                        </ul>
                                        <a class="resend-link srj_resend_login_otp">Resend Code</a>
                                    </div>



                                   <!-- <div class="form-group pos_rel">
                                        <span class="error">* 
                                        @if ($errors->has('password'))
                                            {{ $errors->first('password') }}
                                        @endif
                                        </span>
                                        <input type="password" name="password" placeholder="Enter Password" class="form-control lgn_input" id="password">
                                        <i class="uil uil-padlock lgn_icon"></i>
                                    </div>
                                    <div class="form-group pos_rel">
                                        <label for="password">Remember me</label>
                                                                                    
                                        <input type="checkbox" name="remember" id="remember" <?php if(isset($_COOKIE["user"])) { echo "checked"; } ?> />
                                    </div>  -->                                    
                                    <button class="login-btn" type="submit" disabled id="login_button"> Login  </button>
                                </form>
                            </div>
                           <!-- <div class="password-forgor">
                                <a href="#" onclick="showRecoverPasswordForm();return false;">Forgot Password?</a>
                            </div>-->
                            <div class="signup-link">
                                <p>Don't have an account? - <a href="{{ route('signup') }}"> Register </a></p>
                            </div>
                        </div>
                    </div>
                </div>
 
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script type="text/javascript">
            function showRecoverPasswordForm() {
                document.getElementById('recover-password').style.display = 'block';
                document.getElementById('login').style.display='none';
            }
               
            function hideRecoverPasswordForm() {
                document.getElementById('recover-password').style.display = 'none';
                document.getElementById('login').style.display = 'block';
            }
               
            if (window.location.hash == '#recover') { showRecoverPasswordForm() }
        </script>

@endsection

@section("script")

<script>
    $(document).ready(function () {
        $(document).on('click', '.srj_send_login_otp', function() {
        var phone=$("#phone").val();
        if(phone.length==10){

            $.ajax({
                type: 'post',
                url: '{{url('/login_otp')}}',
                data: {phone: phone},
                success: function(data){
                    //console.log(data);
                    if(data['status']==200){
                        $.confirm({
                            title: '',
                            content: 'Otp send successfully!',
                            icon: 'fa fa-check',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'green',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                      $(".srj_send_login_otp").hide();
                      $("#btn_span").html("Click For Verify OTP");
                      $("#phone").prop('readonly', 'readonly');
                        $(".srj_check_otp").show();
                    } else if(data['status']==400) {
                       
                        $.confirm({
                            title: '',
                            content: 'Please Register First!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                       
                    }else{
                        $.confirm({
                            title: '',
                            content: 'Cannot send otp!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    }
                }
            });

        }else{
            $.confirm({
                title: '',
                content: 'Invalid phone number',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
        
    });

    $(document).on('click', '.srj_resend_login_otp', function() {
        var phone=$("#phone").val();
        if(phone.length==10){

            $.ajax({
                type: 'post',
                url: '{{url('/login_otp')}}',
                data: {phone: phone},
                success: function(data){
                    //console.log(data);
                    if(data['status']==200){
                        $.confirm({
                            title: '',
                            content: 'Otp send successfully!',
                            icon: 'fa fa-check',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'green',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                      $(".srj_send_login_otp").hide();
                      $("#btn_span").html("Click For Verify OTP");
                      $("#phone").prop('readonly', 'readonly');
                        $(".srj_check_otp").show();
                    } else if(data['status']==400) {
                       
                        $.confirm({
                            title: '',
                            content: 'Please Register First!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                       
                    }else{
                        $.confirm({
                            title: '',
                            content: 'Cannot send otp!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    }
                }
            });

        }else{
            $.confirm({
                title: '',
                content: 'Invalid phone number',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
        
    });

    //otp verification
    $(document).on('click', '.srj_check_otp', function() {
        var val=$(this).val();
        var code=$("#code_1").val()+$("#code_2").val()+$("#code_3").val()+$("#code_4").val();
       // alert(code);
            var phone=$("#phone").val();
            var id=$("#user_id").val();
            if(code.length==4){
                $.ajax({
                type: 'post',
                url: '{{url('/verify_login_otp')}}',
                data: {phone: phone, code:code, id: id},
                success: function(data){
                    //console.log(data);
                    if(data['status']==200){
                        $.confirm({
                            title: '',
                            content: 'Otp verified!',
                            icon: 'fa fa-check',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'green',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });

                        $("#login_button").removeAttr("disabled");
                        $("#login_button").addClass("hover-btn");

                    } else if(data['status']==404) {
                       
                        $.confirm({
                            title: '',
                            content: 'OTP already used!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                       
                    }else{
                        $.confirm({
                            title: '',
                            content: 'Wrong OTP!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                    }
                    }
                });
            }else{
                $.confirm({
                            title: '',
                            content: 'Invalid OTP!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
            }
           
        
             
    });
});
</script>

@endsection