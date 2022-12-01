@extends('layouts.master')
@section('title', 'Edit Merchants')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.merchant_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Merchants  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Merchants  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_merchant','class'=>'gj_merchant_form','files' => true)) }}
                        @if($merchant)
                            {{ Form::hidden('merchant_id', $merchant->id, array('class' => 'form-control gj_merchant_id')) }}
                        @endif

                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Merchants Account  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('first_name', 'First Name') }}
                                    <span class="error">* 
                                        @if ($errors->has('first_name'))
                                            {{ $errors->first('first_name') }}
                                        @endif
                                    </span>

                                    {{ Form::text('first_name', ($merchant->first_name ? $merchant->first_name : Input::old('first_name')), array('class' => 'form-control gj_first_name','placeholder' => 'Enter Merchant First Name')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('last_name', 'Last Name') }}
                                    <span class="error"> 
                                        @if ($errors->has('last_name'))
                                            {{ $errors->first('last_name') }}
                                        @endif
                                    </span>

                                    {{ Form::text('last_name', ($merchant->last_name ? $merchant->last_name : Input::old('last_name')), array('class' => 'form-control gj_last_name','placeholder' => 'Enter Merchant Last Name')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('bussiness_name', 'Bussiness Name') }}
                                    <span class="error">* 
                                        @if ($errors->has('bussiness_name'))
                                            {{ $errors->first('bussiness_name') }}
                                        @endif
                                    </span>

                                    {{ Form::text('bussiness_name', ($merchant->bussiness_name ? $merchant->bussiness_name : Input::old('bussiness_name')), array('class' => 'form-control gj_bussiness_name','placeholder' => 'Enter Merchant Bussiness Name')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('buss_reg_no', 'Bussiness Register Number') }}
                                    <span class="error"> 
                                        @if ($errors->has('buss_reg_no'))
                                            {{ $errors->first('buss_reg_no') }}
                                        @endif
                                    </span>

                                    {{ Form::text('buss_reg_no', ($merchant->buss_reg_no ? $merchant->buss_reg_no : Input::old('buss_reg_no')), array('class' => 'form-control gj_buss_reg_no','placeholder' => 'Enter Merchant Bussiness Register Number')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('is_gst', 'Is GST') }}
                                    <span class="error">* 
                                        @if ($errors->has('is_gst'))
                                            {{ $errors->first('is_gst') }}
                                        @endif
                                    </span>

                                    <div class="gj_py_ro_div">
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if ($merchant->is_gst == 1) { echo 'checked'; } ?> name="is_gst" value="1"> Yes
                                        </span>
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if ($merchant->is_gst == 0) { echo 'checked'; } ?> name="is_gst" value="0"> No
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group gj_s_gst">
                                    {{ Form::label('gstn_no', 'GST Number') }}
                                    <span class="error">* 
                                        @if ($errors->has('gstn_no'))
                                            {{ $errors->first('gstn_no') }}
                                        @endif
                                    </span>

                                    {{ Form::text('gstn_no', ($merchant->gstn_no ? $merchant->gstn_no : Input::old('gstn_no')), array('class' => 'form-control gj_gstn_no','placeholder' => 'Enter GST Number')) }} 
                                </div>

                                <div class="form-group">
                                    {{ Form::label('email', 'E-mail Id') }}
                                    <span class="error">* 
                                        @if ($errors->has('email'))
                                            {{ $errors->first('email') }}
                                        @endif
                                    </span>

                                    {{ Form::email('email', ($merchant->email ? $merchant->email : Input::old('email')), array('class' => 'form-control gj_email','placeholder' => 'Enter Merchant E-mail Id')) }}
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
                                                if ($value->id == $merchant->country) {
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
                                    {{ Form::label('phone', 'Phone') }}
                                    <span class="error">* 
                                        @if ($errors->has('phone'))
                                            {{ $errors->first('phone') }}
                                        @endif
                                    </span>

                                    {{ Form::number('phone', ($merchant->phone ? $merchant->phone : Input::old('phone')), array('class' => 'form-control gj_phone','placeholder' => 'Enter Merchant Phone Number')) }}
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
                                            <input type="radio" <?php if($merchant->gender == "Male"){ echo "checked"; } ?> name="gender" value="Male"> Male
                                        </span>
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($merchant->gender == "Female"){ echo "checked"; } ?> name="gender" value="Female"> Female
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

                                    {{ Form::text('address1', ($merchant->address1 ? $merchant->address1 : Input::old('address1')), array('class' => 'form-control gj_address1','placeholder' => 'Enter Merchant Address')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('address2', 'City') }}
                                    <span class="error">* 
                                        @if ($errors->has('address2'))
                                            {{ $errors->first('address2') }}
                                        @endif
                                    </span>

                                    {{ Form::text('address2', ($merchant->address2 ? $merchant->address2 : Input::old('address2')), array('class' => 'form-control gj_address2','placeholder' => 'Enter Merchant City')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('pincode', 'Pincode') }}
                                    <span class="error">* 
                                        @if ($errors->has('pincode'))
                                            {{ $errors->first('pincode') }}
                                        @endif
                                    </span>

                                    {{ Form::number('pincode', ($merchant->pincode ? $merchant->pincode : Input::old('pincode')), array('class' => 'form-control gj_pincode','placeholder' => 'Enter Merchant Pincode')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('commission', 'Commission (%)') }}
                                    <span class="error">* 
                                        @if ($errors->has('commission'))
                                            {{ $errors->first('commission') }}
                                        @endif
                                    </span>

                                    {{ Form::text('commission', ($merchant->commission ? $merchant->commission : Input::old('commission')), array('class' => 'form-control gj_commission','placeholder' => 'Enter Admin Commission')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('return_commission', 'Return Commission (%)') }}
                                    <span class="error"> 
                                        @if ($errors->has('return_commission'))
                                            {{ $errors->first('return_commission') }}
                                        @endif
                                    </span>

                                    {{ Form::text('return_commission', ($merchant->return_commission ? $merchant->return_commission : Input::old('return_commission')), array('class' => 'form-control gj_return_commission','placeholder' => 'Enter Return Commission')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('payment_account_details', 'Payment Account Details') }}
                                    <span class="error"> 
                                        @if ($errors->has('payment_account_details'))
                                            {{ $errors->first('payment_account_details') }}
                                        @endif
                                    </span>

                                    {{ Form::text('payment_account_details', ($merchant->payment_account_details ? $merchant->payment_account_details : Input::old('payment_account_details')), array('class' => 'form-control gj_p_acc_d','placeholder' => 'Paypal  EMail-ID  Payment Bank Details')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('user_type', 'Select User Type') }}
                                    <span class="error"> 
                                        @if ($errors->has('user_type'))
                                            {{ $errors->first('user_type') }}
                                        @endif
                                    </span>

                                    <select id="user_type" name="user_type" class="form-control">
                                        <option value="2" <?php if($merchant->user_type == 2) { echo "Selected"; } ?>>Admin Merchant</option>
                                        <option value="3" <?php if($merchant->user_type == 3) { echo "Selected"; } ?>>Website Merchant</option>
                                    </select>
                                </div>

                                <div class="gj_ban_img_whole">
                                    <?php 
                                    $file_path = 'images/profile_img';
                                    ?>
                                    @if(isset($merchant))
                                        @if($merchant->profile_img != '')
                                        <div class="form-group">
                                            {{ Form::label('current_profile_img', 'Current Profile Featured Image') }}
                                            <div class="gj_mc_div">
                                               <img src="{{ asset($file_path.'/'.$merchant->profile_img)}}" class="img-responsive"> 
                                            </div>
                                            {{ Form::hidden('old_profile_img', ($merchant->profile_img ? $merchant->profile_img : ''), array('class' => 'form-control')) }}
                                        </div>
                                        @endif
                                    @endif

                                    <div class="form-group">
                                        {{ Form::label('profile_img', 'Upload Featured Profile Image') }}
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
                                    {{ Form::label('is_approved', 'Approved') }}
                                    <span class="error">* 
                                        @if ($errors->has('is_approved'))
                                            {{ $errors->first('is_approved') }}
                                        @endif
                                    </span>

                                    <div class="gj_py_ro_div">
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($merchant->is_approved == 1){ echo "checked"; } ?> name="is_approved" value="1"> Active
                                        </span>
                                        <span class="gj_py_ro">
                                            <input type="radio" <?php if($merchant->is_approved == 0){ echo "checked"; } ?> name="is_approved" value="0"> Deactive
                                        </span>
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
                                                    @if($merchant)
                                                        @if($merchant['docs'] && (count($merchant['docs']) != 0))
                                                            @foreach($merchant['docs'] as $key => $value)
                                                                <tr id="gj_tr_m_doc_{{$key+1}}">
                                                                    <td>
                                                                        <input class="form-control gj_d_name" placeholder="Enter Document Name" name="d_name[]" type="text" id="d_name_{{$key+1}}" value="{{$value->d_name}}">
                                                                    </td>
                                                                    <td>
                                                                        <?php  
                                                                            $doc_file_path = 'documents';
                                                                            $file = asset($doc_file_path.'/'.$value->image);

                                                                            // $docs = explode(".",$value->image);
                                                                            // print_r($docs);
                                                                            // $pos = strrpos($value->image, '.');
                                                                            // $extension = strtolower(substr($file, $pos + 1));
                                                                            // print_r($extension);
                                                                        ?>
                                                                        @if($value->image)
                                                                            <!-- <a href="{{ asset($doc_file_path.'/'.$value->image)}}" target="_blank" class="gj_old_doc"><iframe src="https://docs.google.com/gview?url={{asset($doc_file_path.'/'.$value->image)}}&embedded=true"></iframe></a> -->

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
                                                                    <input class="form-control gj_d_name" placeholder="Enter Document Name" name="d_name[]" type="text" id="d_name_1">
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
                                                                <input class="form-control gj_d_name" placeholder="Enter Document Name" name="d_name[]" type="text" id="d_name_1">
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
</section>


<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();
        $("#user_type").select2();

        var country = $('#country').select2('val');
        @if($merchant->state)
            var state = <?php echo $merchant->state; ?>;
        @else
            var state = 0;
        @endif

        @if($merchant->city)
            var city = <?php echo $merchant->city; ?>;
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
                                            icon: 'fa fa-ban',
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
                                icon: 'fa fa-ban',
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
                            icon: 'fa fa-ban',
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
                icon: 'fa fa-ban',
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

        @if(count($merchant['docs']) != 0)
            var cnt = <?php echo count($merchant['docs']) + 1;?>;
        @else
            var cnt = 2;
        @endif
        
        $("#img_addButton").click(function () {
            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'gj_tr_m_doc_' + cnt);
            newTextBoxDiv.after().html('<td><input class="form-control gj_d_name" placeholder="Enter Document Name" name="d_name[]" type="text" id="d_name_' + cnt + '"></td><td><input type="file" name="d_image[]" id="d_image_' + cnt + '" class="gj_d_image form-control"></td><td><button type="button" id="img_removeButton_' + cnt + '" class="gj_m_doc_rem"><i class="fa fa-trash"></i></button></td>');
            newTextBoxDiv.appendTo("#gj_m_doc_bdy");
            cnt++;
        });

        $('body').on('click','.gj_m_doc_rem',function() {
            if(cnt==1){
                $.confirm({
                    title: '',
                    content: 'No more textbox to remove!',
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
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        $("#state").val(0);
                        $("#city").val(0);
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
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });
                        $("#city").val(0);
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
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });

    $(document).ready(function(){
        $('.gj_s_gst').hide();

        if($("input[name='is_gst']:checked").val() == 1) {
            $('.gj_s_gst').slideDown();
        } else {
            $('.gj_s_gst').slideUp();
        }

        $('body').on('change','input[name="is_gst"]',function() {
            if($("input[name='is_gst']:checked").val() == 1) {
                $('.gj_s_gst').slideDown();
            } else {
                $('.gj_s_gst').slideUp();
            }
        });
    });
</script>
@endsection