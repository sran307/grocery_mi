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
                        <div class="sign-logo"  id="logo">
                            <a href="{{url('/')}}">
                                <img src="{{asset('assets')}}/logo.svg" alt=""></a>
                            <a href="{{url('/')}}">
                                <img class="logo-inverse" src="{{asset('assets')}}/dark-logo.svg" alt=""></a>
                        </div>
                        <div class="form-dt">
                        <div class="form-inpts checout-address-step">
                            {{ Form::open(array('url' => 'register','class'=>'show gj_user_register', 'id' => 'create_customer', 'files' => true)) }}
                            <input type="hidden" name="form_type" value="create_customer"><input type="hidden" name="utf8" value="✓">
                                 
                            <div class="form-title"><h6> Register </h6></div>
                            <div class="form-group pos_rel">
                                <span class="error">* 
                                    @if ($errors->has('first_name'))
                                        {{ $errors->first('first_name') }}
                                    @endif
                                </span>

                                {{ Form::text('first_name', Input::old('first_name'), array('class' => 'form-control lgn_input', 'autocomplete' => 'new-first','placeholder' => 'Enter First Name')) }}
                                                                    
                                <i class="uil uil-user-circle lgn_icon"></i>
                            </div>
                            <input type="hidden" name="user_type" value="4">
                            <div class="form-group pos_rel">
   
                                <span class="error">*
                                    @if ($errors->has('last_name'))
                                        {{ $errors->first('last_name') }}
                                    @endif
                                </span>

                                {{ Form::text('last_name', Input::old('last_name'), array('class' => 'form-control lgn_input', 'autocomplete' => 'new-last','placeholder' => 'Enter Last Name')) }}
                                <i class="uil uil-user-circle lgn_icon"></i>                         
                            </div>
                            <div class="form-group pos_rel">
                                    
                                <span class="error">* 
                                    @if ($errors->has('email'))
                                        {{ $errors->first('email') }}
                                    @endif
                                </span>

                                {{ Form::email('email', Input::old('email'), array('class' => 'form-control lgn_input', 'autocomplete' => 'off','placeholder' => 'Enter Your E-Mail')) }}
                                <i class="uil uil-envelope lgn_icon"></i>                                

                            </div>
                            <div class="form-group pos_rel">

                                <span class="error">* 
                                    @if ($errors->has('phone'))
                                        {{ $errors->first('phone') }}
                                    @endif
                                </span>

                                {{ Form::text('phone', Input::old('phone'), array('class' => 'form-control lgn_input sr_lgn_input', 'autocomplete' => 'new-phone', 'autocapitalize' => 'off','placeholder' => 'Enter Your Phone Number')) }}

                                <i class="uil uil-mobile-android-alt lgn_icon">+91</i>                                 

                            </div>
                            <div class="form-group pos_rel">
                                
                                <span class="error">* 
                                    @if ($errors->has('password'))
                                        {{ $errors->first('password') }}
                                    @endif
                                </span>
                                <input class="form-control lgn_input" type="password" name="password" autocomplete="new-password" placeholder="Enter Your Password">
                                <input type="hidden" name="is_approved" value="1"><i class="uil uil-padlock lgn_icon"></i>
                            </div>
                            <div class="form-group pos_rel">
                                <span class="error">*
                                    @if ($errors->has('g-recaptcha-response'))
                                        {{ $errors->first('g-recaptcha-response') }}
                                    @endif
                                </span>
                                <div class="g-recaptcha" data-sitekey="6LfXZpAeAAAAAIih9t1OrNFUfLZTWOdorGf1B6ov"></div>
                            </div>
                           

                            <button class="login-btn hover-btn" type="submit" id="sr_register"> Register </button>
                            {{Form::close()}}
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

<script>     
    $(document).ready(function(){
        // check_user();
        $('.buzin').click(function(){ 
            $(".buzzacc").toggle(); 
        })
    });  
</script>
      
      
<script>
    $(document).ready(function(){
        $('.sellzz').click(function(){  
            $('.tabiz').addClass('active');
            $('.tabiz').addClass('show');
            $('.hizz').removeClass('active');
            $('.hizz').removeClass('show');
        });
    })
</script>

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
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();
        $("#user_type").select2();
        $("#store_country").select2();
        $("#store_state").select2();
        $("#store_city").select2();
        $("#question").select2();

        var cnt = 2;
        $("#img_addButton").click(function () {
            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'gj_tr_m_doc_' + cnt);
            newTextBoxDiv.after().html('<td><input class="form-control gj_d_name" placeholder="Enter Product Name" name="d_name[]" type="text" id="d_name_' + cnt + '"></td><td><input type="file" name="d_image[]" id="d_image_' + cnt + '" class="gj_d_image form-control"></td><td><button type="button" id="img_removeButton_' + cnt + '" class="gj_m_doc_rem"><i class="fa fa-trash"></i></button></td>');
            newTextBoxDiv.appendTo("#gj_m_doc_bdy");
            cnt++;
        });

        $('body').on('click','.gj_m_doc_rem',function() {
            if(cnt==1){
                $.confirm({
                    title: '',
                    content: 'No more textbox to remove!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
                return false;
            }   
        
            cnt--;
            $(this).closest('tr').remove();
        });
    });

    $('#country').on('change',function() {
        if($(this).select2('val')) {
            var country = $(this).select2('val');

            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Country!',
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
                        $("#state").prop("disabled", true);
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Country!',
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

    $('#state').on('change',function() {
        var st = $(this).select2('val');
        if(st) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_city')}}',
                data: {st: st, type: 'city'},
                success: function(data){
                    if(data){
                        $("#city").html(data);
                        $("#city").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select State!',
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
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select State!',
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

    $('#store_country').on('change',function() {
        var country = $(this).select2('val');
        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, type: 'store_state'},
                success: function(data){
                    if(data){
                        $("#store_state").html(data);
                        $("#store_state").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Store Country!',
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
                        $("#store_state").prop("disabled", true);
                        $("#store_city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Store Country!',
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

    $('#store_state').on('change',function() {
        var st = $(this).select2('val');
        if(st) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_city')}}',
                data: {st: st, type: 'store_city'},
                success: function(data){
                    if(data){
                        $("#store_city").html(data);
                        $("#store_city").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Store State!',
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
                        $("#store_city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Store State!',
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
     function check_user()
    {
        var type=$('#user_type').val();
        if(type==5)
        {
            $('#company_div').show();  
            $('#company_gst_div').show();  
            $('#verification_div').show();  
        }
        else
        {
             $('#company_div').hide();  
            $('#company_gst_div').hide();  
            $('#verification_div').hide();
        }
    }
</script>
@endsection