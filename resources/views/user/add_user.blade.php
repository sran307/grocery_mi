@extends('layouts.master')
@section('title', 'Add User')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.user_sidebar')
        </div>
        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add User  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add User  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'add_user','class'=>'gj_user_form','files' => true)) }}
                    <div class="form-group">
                            {{ Form::label('user_type', 'User Type') }}
                            <span class="error">* 
                                @if ($errors->has('user_type'))
                                    {{ $errors->first('user_type') }}
                                @endif
                            </span>

                            {{ Form::select('user_type',$roles,Input::old('user_type'), array('class' => 'form-control user_type','id'=>'user_type','onchange'=>'check_user()')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('first_name', 'First Name') }}
                            <span class="error">* 
                                @if ($errors->has('first_name'))
                                    {{ $errors->first('first_name') }}
                                @endif
                            </span>

                            {{ Form::text('first_name', Input::old('first_name'), array('class' => 'form-control gj_first_name','placeholder' => 'Enter User First Name')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('last_name', 'Last Name') }}
                            <span class="error"> 
                                @if ($errors->has('last_name'))
                                    {{ $errors->first('last_name') }}
                                @endif
                            </span>

                            {{ Form::text('last_name', Input::old('last_name'), array('class' => 'form-control gj_last_name','placeholder' => 'Enter User Last Name')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('email', 'E-mail Id') }}
                            <span class="error">* 
                                @if ($errors->has('email'))
                                    {{ $errors->first('email') }}
                                @endif
                            </span>

                            {{ Form::email('email', Input::old('email'), array('class' => 'form-control gj_email','placeholder' => 'Enter User E-mail Id')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('password', 'Password') }}
                            <span class="error">* 
                                @if ($errors->has('password'))
                                    {{ $errors->first('password') }}
                                @endif
                            </span>

                            <input class="form-control gj_password" placeholder="Enter User Password" name="password" type="password" id="password">
                        </div>

                        <div class="form-group">
                            {{ Form::label('password_salt', 'Confirm Password') }}
                            <span class="error">* 
                                @if ($errors->has('password_salt'))
                                    {{ $errors->first('password_salt') }}
                                @endif
                            </span>

                            <input class="form-control gj_password_salt" placeholder="Enter User Confirm Password" name="password_salt" type="password" id="password_salt">
                        </div>

                        <div class="form-group">
                            {{ Form::label('phone', 'Phone-1') }}
                            <span class="error">* 
                                @if ($errors->has('phone'))
                                    {{ $errors->first('phone') }}
                                @endif
                            </span>

                            {{ Form::number('phone', Input::old('phone'), array('class' => 'form-control gj_phone','placeholder' => 'Enter User Phone Number')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('phone2', 'Phone-2') }}
                            <span class="error"> 
                                @if ($errors->has('phone2'))
                                    {{ $errors->first('phone2') }}
                                @endif
                            </span>

                            {{ Form::number('phone2', Input::old('phone2'), array('class' => 'form-control gj_phone2','placeholder' => 'Enter User Optional Phone Number')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('gender', 'Gender') }}
                            <span class="error">* 
                                @if ($errors->has('gender'))
                                    {{ $errors->first('gender') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" name="gender" value="Male"> Male
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" name="gender" value="Female"> Female
                                </span>
                            </div>
                        </div>
                        <div class="form-group" id="company_div" style="display:none">
                            {{ Form::label('gst', 'Company Name') }}
                            <span class="error">* 
                                @if ($errors->has('company_name'))
                                    {{ $errors->first('company_name') }}
                                @endif
                            </span>

                            {{ Form::text('company_name', Input::old('company_name'), array('class' => 'form-control company_name','placeholder' => 'Enter Company Name')) }}
                        </div>
                        <div class="form-group" id="company_gst_div" style="display:none">
                            {{ Form::label('gst', 'Company GSTIN No.') }}
                            <span class="error">* 
                                @if ($errors->has('company_gst_no'))
                                    {{ $errors->first('company_gst_no') }}
                                @endif
                            </span>
                        <input type="text" name="company_gst_no" value="{{Input::old('company_gst_no')}}" maxlength="15" placeholder="Enter Company GSTIN No." class="form-control company_gst_no" id="company_gst_no" pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" title="Invalid GST Number." />
                        </div>
                        <div class="form-group" id="verification_div" style="display:none">
                            {{ Form::label('verification_document', 'GST Verification Document') }}
                            <span class="error">* 
                                @if ($errors->has('verification_document'))
                                    {{ $errors->first('verification_document') }}
                                @endif
                            </span>

                            <input type="file" name="verification_document" id="verification_document" accept="pdf/*,doc/*" class="verification_document">
                        </div>
                        <div class="form-group">
                            {{ Form::label('profile_img', 'Upload Profile Image') }}
                            <span class="error"> 
                                @if ($errors->has('profile_img'))
                                    {{ $errors->first('profile_img') }}
                                @endif
                            </span>
                            <p class="gj_not" style="color:red"><em>image size must be 250 x 200 pixels</em></p>

                            <input type="file" name="profile_img" id="profile_img" accept="image/*" class="gj_profile_img">
                        </div>

                        <div class="form-group">
                            {{ Form::label('is_approved', 'Approved') }}
                            <span class="error">* 
                                @if ($errors->has('is_approved'))
                                    {{ $errors->first('is_approved') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" name="is_approved" value="1"> Active
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" checked name="is_approved" value="0"> Deactive
                                </span>
                            </div>
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        check_user();
        $('p.alert').delay(5000).slideUp(500); 
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();
        $("#user_type").select2();

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
        var country = $(this).val();
        if(country) {
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
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
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
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });

    $('#state').on('change',function() {
        var st = $(this).val();
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
                            icon: 'fa fa-exclamation',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
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
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
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