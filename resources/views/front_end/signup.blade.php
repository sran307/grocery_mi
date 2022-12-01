@extends('groceryView.layouts.headerFooter')
@section('title', 'Register')
<meta name="google-signin-client_id" content="1057896517156-0ojbp6ahoom9cnsli28v3rt03nad1tnk.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')

<div class="sign-inup">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="sign-form">
                    <div class="sign-inner">
                        <!--<div class="sign-logo" id="logo">
                            <a href="index.html"><img src="images/logo.svg" alt=""></a>
                            <a href="index.html"><img class="logo-inverse" src="images/dark-logo.svg" alt=""></a>
                        </div>-->
                        <div class="form-dt">
                            <div class="form-inpts checout-address-step">
                                <form method="post" action="{{route('register')}}">
                                    @if(Session::has('message'))
                                       <p class="alert alert-{{session::get('alert-class')}} sr_alert">{{session::get('message')}}</p>
                                    @endif
                                    <div class="form-title"><h6> Register </h6></div>
                                    <input type="hidden" name="user_type" value="4">
                                    <input type="hidden" name="id" id="user_id">
                                    <div class="form-group pos_rel">
                                    <span class="error">* 
                                    @if ($errors->has('first_name'))
                                        {{ $errors->first('first_name') }}
                                    @endif
                                </span>
                                        <input id="full[name]" name="first_name" type="text" placeholder="Full name" class="form-control lgn_input" required>
                                        <i class="uil uil-user-circle lgn_icon"></i>
                                    </div>
                                    <div class="form-group pos_rel">
                                    <span class="error">* 
                                    @if ($errors->has('email'))
                                        {{ $errors->first('email') }}
                                    @endif
                                </span>
                                        <input id="email[address]" name="email" type="email" placeholder="Email Address" class="form-control lgn_input" required>
                                        <i class="uil uil-envelope lgn_icon"></i>
                                    </div>
                                    <div class="form-group pos_rel">
                                    <span class="error">* 
                                    @if ($errors->has('phone'))
                                        {{ $errors->first('phone') }}
                                    @endif
                                </span>
                                        <input id="phone_number" name="phone" type="number" placeholder="Phone Number" class="form-control lgn_input" required>
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
                                                <a class="chck-btn hover-btn code-btn145 srj_send_otp">Send</a>
                                                <a class="chck-btn hover-btn code-btn145 srj_check_otp " style="display:none" >Verify</a>
                                                 <span id="btn_span">Click For Send OTP</span>
                                            </li>
                                        </ul>
                                        <a class="resend-link srj_resend_otp">Resend Code</a>
                                    </div>

                                    <!--<div class="form-group pos_rel">
                                    <span class="error">* 
                                    @if ($errors->has('password'))
                                        {{ $errors->first('password') }}
                                    @endif
                                </span>
                                        <input id="password1" name="password" type="password" placeholder="New Password" class="form-control lgn_input" required>
                                        <i class="uil uil-padlock lgn_icon"></i>
                                    </div>-->
                                    <button class="login-btn " id="register_button" type="submit" disabled> Register </button>
                                </form>
                            </div>
                        <div class="signup-link">
                    <p>I have an account? - <a href="{{url('signin')}}"> Login </a></p>
                </div>
            </div>
        </div>
    </div>
 </div>
</div>
</div>
</div>




@endsection

@section("script")

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
    });
</script>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
    });
</script>

<script>
    $(document).ready(function() { 
      

    $(document).on('click', '.srj_send_otp', function() {
        var phone=$("#phone_number").val();
        if(phone.length==10){

            $.ajax({
                type: 'post',
                url: '{{url('/send_otp')}}',
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
                      $(".srj_send_otp").hide();
                      $("#btn_span").html("Click For Verify OTP");
                      $("#phone_number").prop('readonly', 'readonly');
                        $(".srj_check_otp").show();
                        $("#user_id").val(data['id']);
                    } else if(data['status']==400) {
                       
                        $.confirm({
                            title: '',
                            content: 'Mobile number already taken!',
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

    $(document).on('click', '.srj_resend_otp', function() {
        var phone=$("#phone_number").val();
        if(phone.length==10){

            $.ajax({
                type: 'post',
                url: '{{url('/send_otp')}}',
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
                      $(".srj_send_otp").hide();
                      $("#btn_span").html("Click For Verify OTP");
                      $("#phone_number").prop('readonly', 'readonly');
                        $(".srj_check_otp").show();
                        $("#user_id").val(data['id']);
                    } else if(data['status']==400) {
                       
                        $.confirm({
                            title: '',
                            content: 'Mobile number already taken!',
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
            var phone=$("#phone_number").val();
            var id=$("#user_id").val();
            if(code.length==4){
                $.ajax({
                type: 'post',
                url: '{{url('/verify_otp')}}',
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

                        $("#register_button").removeAttr("disabled");
                        $("#register_button").addClass("hover-btn");

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