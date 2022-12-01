@extends('groceryView.layouts.headerFooter')
@section('title', 'Edit Profile')
@section('content')
 @include('layouts.normal_user_sidebar')

       <div class="col-lg-9 col-md-8">
<div class="dashboard-right">
<div class="row">

                <div class="col-md-12">
                    @if(Session::has("message"))
                        <p class="alert alert-{{Session::get('alert-class')}}">{{$Session::get("message")}}</p>
                    @endif
                    {{ Form::open(array('url' => 'edit_profile','class'=>'gj_user_form','files' => true)) }}
                        @if($user)
                            {{ Form::hidden('user_id', $user->id, array('class' => 'form-control gj_user_id')) }}
                        @endif

                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> users Account  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('first_name', 'First Name') }}
                                    <span class="error">* 
                                        @if ($errors->has('first_name'))
                                            {{ $errors->first('first_name') }}
                                        @endif
                                    </span>

                                    {{ Form::text('first_name', ($user->first_name ? $user->first_name : Input::old('first_name')), array('class' => 'form-control gj_first_name','placeholder' => 'Enter user First Name')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('last_name', 'Last Name') }}
                                    <span class="error"> 
                                        @if ($errors->has('last_name'))
                                            {{ $errors->first('last_name') }}
                                        @endif
                                    </span>

                                    {{ Form::text('last_name', ($user->last_name ? $user->last_name : Input::old('last_name')), array('class' => 'form-control gj_last_name','placeholder' => 'Enter user Last Name')) }}
                                </div>

                               

                               

                                <div class="form-group">
                                    {{ Form::label('email', 'E-mail Id') }}
                                    <span class="error">* 
                                        @if ($errors->has('email'))
                                            {{ $errors->first('email') }}
                                        @endif
                                    </span>

                                    {{ Form::email('email', ($user->email ? $user->email : Input::old('email')), array('class' => 'form-control gj_email','placeholder' => 'Enter user E-mail Id')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('country', 'Select Country') }}
                                    <span class="error">* 
                                        @if ($errors->has('country'))
                                            {{ $errors->first('country') }}
                                        @endif
                                    </span>

                                    <?php 
                                        $opt = '';
                                        $ctys = \DB::table('countries_managements')->where('is_block',1)->get();
                                        if(($ctys) && (count($ctys) != 0)){
                                            foreach ($ctys as $key => $value) {
                                                if ($value->id == $user->country) {
                                                    $opt.='<option selected value="'.$value->id.'">'.$value->country_name.'</option>';
                                                } else {
                                                    $opt.='<option value="'.$value->id.'">'.$value->country_name.'</option>';
                                                }
                                            }
                                        } 
                                    ?>
                                    <select id="country" name="country" class="form-control">
                                        <option value="0" selected disabled>Select Country</option>
                                        <?php echo $opt; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('state', 'Select State') }}
                                    <span class="error">* 
                                        @if ($errors->has('state'))
                                            {{ $errors->first('state') }}
                                        @endif
                                    </span>

                                    <select id="state" name="state" disabled class="form-control">
                                        <option value="0" selected disabled>Select State</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('city', 'Select District') }}
                                    <span class="error">* 
                                        @if ($errors->has('city'))
                                            {{ $errors->first('city') }}
                                        @endif
                                    </span>

                                    <select id="city" name="city" disabled class="form-control">
                                        <option value="0" selected disabled>Select District</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('phone', 'Phone-1') }}
                                    <span class="error">* 
                                        @if ($errors->has('phone'))
                                            {{ $errors->first('phone') }}
                                        @endif
                                    </span>

                                    {{ Form::number('phone', ($user->phone ? $user->phone : Input::old('phone')), array('class' => 'form-control gj_phone','placeholder' => 'Enter user Phone Number')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('phone2', 'Phone-2') }}
                                    <span class="error">
                                        @if ($errors->has('phone2'))
                                            {{ $errors->first('phone2') }}
                                        @endif
                                    </span>

                                    {{ Form::number('phone2', ($user->phone2 ? $user->phone2 : Input::old('phone2')), array('class' => 'form-control gj_phone2','placeholder' => 'Enter user Optional Phone Number')) }}
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
                                            <input type="radio" <?php if($user->gender == "Male"){ echo "checked"; } ?> name="gender" value="Male"> Male
                                        </span>
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($user->gender == "Female"){ echo "checked"; } ?> name="gender" value="Female"> Female
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('address1', 'Address') }}
                                    <span class="error">* 
                                        @if ($errors->has('address1'))
                                            {{ $errors->first('address1') }}
                                        @endif
                                    </span>

                                    {{ Form::text('address1', ($user->address1 ? $user->address1 : Input::old('address1')), array('class' => 'form-control gj_address1','placeholder' => 'Enter user Address')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('address2', 'City') }}
                                    <span class="error">* 
                                        @if ($errors->has('address2'))
                                            {{ $errors->first('address2') }}
                                        @endif
                                    </span>

                                    {{ Form::text('address2', ($user->address2 ? $user->address2 : Input::old('address2')), array('class' => 'form-control gj_address2','placeholder' => 'Enter User City')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('pincode', 'Pincode') }}
                                    <span class="error">* 
                                        @if ($errors->has('pincode'))
                                            {{ $errors->first('pincode') }}
                                        @endif
                                    </span>

                                    {{ Form::number('pincode', ($user->pincode ? $user->pincode : Input::old('pincode')), array('class' => 'form-control gj_pincode','placeholder' => 'Enter User Pincode')) }}
                                </div>

                                <div class="form-group">
                                    <!-- {{ Form::label('user_type', 'user_type') }} -->
                                    <span class="error"> 
                                        @if ($errors->has('user_type'))
                                            {{ $errors->first('user_type') }}
                                        @endif
                                    </span>

                                    {{ Form::hidden('user_type', ($user->user_type ? $user->user_type : Input::old('user_type')), array('class' => 'form-control gj_user_type','placeholder' => 'Enter Admin user_type')) }}
                                </div>

                                <div class="gj_ban_img_whole">
                                    <?php 
                                    $file_path = 'images/profile_img';
                                    ?>
                                    @if(isset($user))
                                        @if($user->profile_img != '')
                                        <div class="form-group">
                                            {{ Form::label('current_profile_img', 'Current Profile Image') }}
                                            <div class="gj_mc_div">
                                               <img src="{{ asset($file_path.'/'.$user->profile_img)}}" class="img-responsive"> 
                                            </div>
                                            {{ Form::hidden('old_profile_img', ($user->profile_img ? $user->profile_img : ''), array('class' => 'form-control')) }}
                                        </div>
                                        @endif
                                    @endif

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
                                </div>

                               

                                <div class="form-group">
                                    {{ Form::label('document', 'Documents') }}

                                    <div class="gj_m_doc_div">
                                        <div class="gj_tot_err">
                                            @if ($errors->has('d_name'))
                                                <p class="error"> 
                                                    {{ $errors->first('d_name') }}
                                                </p>
                                            @endif

                                            @if ($errors->has('d_image'))
                                                <p class="error"> 
                                                    {{ $errors->first('d_image') }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="gj_m_doc_resp table-responsive">
                                            <table class="table table-stripped table-bordered gj_tab_m_doc">
                                                <thead>
                                                    <tr>
                                                        <th>Document Name</th>
                                                        <th>Document File</th>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="gj_m_doc_bdy">
                                                    @if($user)
                                                        @if($user['docs'] && (count($user['docs']) != 0))
                                                            @foreach($user['docs'] as $key => $value)
                                                                <tr id="gj_tr_m_doc_{{$key+1}}">
                                                                    <td>
                                                                        <input class="form-control gj_d_name" placeholder="Enter Product Name" name="d_name[]" type="text" id="d_name_{{$key+1}}" value="{{$value->d_name}}">
                                                                    </td>
                                                                    <td>
                                                                        <?php  
                                                                            $doc_file_path = 'documents';
                                                                        ?>
                                                                        @if($value->image)
                                                                            <!-- <img src="{{ asset($doc_file_path.'/'.$value->image)}}" class="img-responsive gj_old_doc_img">  -->
                                                                            <a href="{{ asset($doc_file_path.'/'.$value->image)}}" target="_blank" class="gj_old_doc"><embed src="{{ asset($doc_file_path.'/'.$value->image)}}"/></a>
                                                                            {{ Form::hidden('old_d_image[]', $value->image, array('class' => 'form-control')) }}
                                                                        @endif
                                                                        <input type="file" name="d_image[]" id="d_image_{{$key+1}}" accept="image/*" class="gj_d_image gj_edit_d_image form-control">
                                                                    </td>
                                                                    <td>
                                                                        <button type='button' id='img_removeButton_{{$key+1}}' class="gj_m_doc_rem"><i class="fa fa-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr id="gj_tr_m_doc_1">
                                                                <td>
                                                                    <input class="form-control gj_d_name" placeholder="Enter Product Name" name="d_name[]" type="text" id="d_name_1">
                                                                </td>
                                                                <td>
                                                                    <input type="file" name="d_image[]" id="d_image_1" class="gj_d_image form-control">
                                                                </td>
                                                                <td>
                                                                    <button type='button' id='img_removeButton_1' class="gj_m_doc_rem"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @else
                                                        <tr id="gj_tr_m_doc_1">
                                                            <td>
                                                                <input class="form-control gj_d_name" placeholder="Enter Product Name" name="d_name[]" type="text" id="d_name_1">
                                                            </td>
                                                            <td>
                                                                <input type="file" name="d_image[]" id="d_image_1" class="gj_d_image form-control">
                                                            </td>
                                                            <td>
                                                                <button type='button' id='img_removeButton_1' class="gj_m_doc_rem"><i class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>

                                            <input type='button' value='Add New' id='img_addButton'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
      </div>
</div>
</div>
</div>
</div>
</div>
</div>     
<script src="{{ asset('assetsGrocery/js/jquery-3.3.1.min.js')}}"></script>
<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();

        var country = $('#country').select2('val');
        @if($user->state)
            var state = <?php echo $user->state; ?>;
        @else
            var state = 0;
        @endif

        @if($user->city)
            var city = <?php echo $user->city; ?>;
        @else
            var city = 0;
        @endif

        if(city) {
            city = city;          
        } else {
            city = 0;
        }

        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, state: state, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");

                        var st = $('#state').val();
                        if(st) {
                            $.ajax({
                                type: 'post',
                                url: '{{url('/select_city')}}',
                                data: {st: st, city: city, type: 'city'},
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
            // $.confirm({
            //     title: '',
            //     content: 'Please Select Country!',
            //     icon: 'fa fa-exclamation',
            //     theme: 'modern',
            //     closeIcon: true,
            //     animation: 'scale',
            //     type: 'blue',
            //     buttons: {
            //         Ok: function(){
            //         }
            //     }
            // });
        }

        @if(count($user['docs']) != 0)
            var cnt = <?php echo count($user['docs']) + 1;?>;
        @else
            var cnt = 2;
        @endif

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
</script>
@endsection