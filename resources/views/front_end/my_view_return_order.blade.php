@extends('layouts.frontend')
@section('title', 'Return View Orders')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
<section class="accountz">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="vertical-tab" role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#Section1" aria-controls="home" role="tab" data-toggle="tab">My Profile</a></li>

                        <li role="presentation"><a href="#Section2" aria-controls="profile" role="tab" data-toggle="tab">Edit Profile</a></li>

                        <li role="presentation"><a href="#Section3" aria-controls="messages" role="tab" data-toggle="tab"> Change Password</a></li>

                        <li role="presentation"><a href="#Section4" aria-controls="orders" role="tab" data-toggle="tab">My Orders</a></li>

                        <li role="presentation"><a href="#Section5" aria-controls="past_orders" role="tab" data-toggle="tab">Complete Orders</a></li>

                        <li role="presentation"><a href="#Section6" aria-controls="cancel_orders" role="tab" data-toggle="tab">Cancel Orders</a></li>

                        <li role="presentation"><a href="#rtn_odr" aria-controls="cancel_orders" role="tab" data-toggle="tab">Return Orders</a></li>

                        <li role="presentation"><a href="#Section7" aria-controls="feed_back" role="tab" data-toggle="tab">Feed Back</a></li>

                        <li role="presentation" id="logout"><a href="{{ route('logout') }}" aria-controls="logout" role="tab" data-toggle="tab">Logout</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content tabs">
                        <div role="tabpanel" class="tab-pane fade" id="Section1">
                            <h3>My Profile</h3>
                            <div class="prof">
                                <?php 
                                    $value = session()->get('user');
                                ?>
                                @if($value)
                                    @if($value->user_type == 4)
                                        <h4>Name  
                                            <span>
                                                @if($value->first_name)
                                                    {{$value->first_name}} {{$value->last_name}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4>Mobile  
                                            <span>
                                                @if($value->phone)
                                                    <gjspan>{{$value->phone}}</gjspan>
                                                    @if($value->mobile_verify == 1)
                                                        <gjspan class="gj_verify"><i class="fa fa-check-circle"></i><b>Verified</b></gjspan>
                                                    @else
                                                        <a href="{{ route('verify', ['on' => 'mobile', 'id' => $value->id]) }}" class="gj_verf_but1"><button type="button" class="btn btn-info gj_verf_but2">Verify Now</button></a>
                                                        <gjspan class="gj_unverify"><i class="fa fa-times"></i><b>Unverified</b></gjspan>
                                                    @endif  
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4>Email Id  
                                            <span>
                                                @if($value->email)
                                                    <gjspan>{{$value->email}}</gjspan>
                                                    @if($value->email_verify == 1)
                                                        <gjspan class="gj_verify"><i class="fa fa-check-circle"></i><b>Verified</b></gjspan>
                                                    @else
                                                        <a href="{{ route('verify', ['on' => 'email', 'id' => $value->id]) }}" class="gj_verf_but1"><button type="button" class="btn btn-info gj_verf_but2">Verify Now</button></a>
                                                        <gjspan class="gj_unverify"><i class="fa fa-times"></i><b>Unverified</b></gjspan>
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4>Gender  
                                            <span>
                                                @if($value->gender)
                                                    {{$value->gender}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> Country 
                                            <span> 
                                                @if($value->country)
                                                    {{$value->Country->country_name}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> State 
                                            <span> 
                                                @if($value->state)
                                                    {{$value->State->state}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> District 
                                            <span> 
                                                @if($value->city)
                                                    {{$value->City->city_name}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> Address - 1
                                            <span> 
                                                @if($value->address1)
                                                    {{$value->address1}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> Address - 2
                                            <span> 
                                                @if($value->address2)
                                                    {{$value->address2}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> Pincode 
                                            <span> 
                                                @if($value->pincode)
                                                    {{$value->pincode}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>

                                        <?php 
                                            $file_path = 'images/profile_img';
                                        ?>
                                        @if($value->profile_img)
                                            <h4> Profile Image 
                                                <span> 
                                                    <img src="{{ asset($file_path.'/'.$value->profile_img)}}" class="img-responsive">  
                                                </span> 
                                            </h4>
                                        @endif
                                    @else
                                        <p class="gj_no_data">No More Details to Edit!</p>
                                    @endif
                                @else
                                    <p class="gj_no_data">No More Details to Edit!</p>
                                @endif
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="Section2">
                            <h3> Edit Profile</h3>
                            <?php
                                $user = session()->get('user');
                            ?>
                            @if($user)
                                @if($user->user_type == 4)
                                    {{ Form::open(array('url' => 'edit_profile','class'=>'gj_user_form','files' => true)) }}
                                        @if($user)
                                            {{ Form::hidden('user_id', $user->id, array('class' => 'form-control gj_user_id')) }}
                                        @endif

                                        <div class="gj_box dark editprofixz gj_inside_box">
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

                                                    {{ Form::hidden('bussiness_name', ($user->bussiness_name ? $user->bussiness_name : Input::old('bussiness_name')), array('class' => 'form-control gj_bussiness_name','placeholder' => 'Enter Name')) }}

                                                    {{ Form::hidden('buss_reg_no', ($user->buss_reg_no ? $user->buss_reg_no : Input::old('buss_reg_no')), array('class' => 'form-control gj_buss_reg_no','placeholder' => 'Enter Name')) }}
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
                                                    {{ Form::label('phone', 'Phone') }}
                                                    <span class="error">* 
                                                        @if ($errors->has('phone'))
                                                            {{ $errors->first('phone') }}
                                                        @endif
                                                    </span>

                                                    {{ Form::number('phone', ($user->phone ? $user->phone : Input::old('phone')), array('class' => 'form-control gj_phone','placeholder' => 'Enter user Phone Number')) }}
                                                </div>

                                                <div class="form-group">
                                                    {{ Form::label('phone2', 'Alternate Phone No') }}
                                                    <span class="error"> 
                                                        @if ($errors->has('phone2'))
                                                            {{ $errors->first('phone2') }}
                                                        @endif
                                                    </span>

                                                    {{ Form::number('phone2', ($user->phone2 ? $user->phone2 : Input::old('phone2')), array('class' => 'form-control gj_phone2','placeholder' => 'Enter user Phone Number')) }}
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
                                                    
                                                    {{ Form::hidden('user_type', ($user->user_type ? $user->user_type : Input::old('user_type')), array('class' => 'form-control gj_user_type','placeholder' => 'Enter User user_type')) }}
                                                </div>

                                                <div class="form-group">
                                                    <label for="question">Select Your Security Question</label>
                                                    <span class="error">* 
                                                        @if ($errors->has('question'))
                                                            {{ $errors->first('question') }}
                                                        @endif
                                                    </span>

                                                    @php ($opt = '<option value=""> Select Your Security Question </option>')
                                                    @if(isset($secure) && sizeof($secure) != 0)
                                                        @foreach($secure as $skey => $sval)
                                                            @if($sval->id == $user->question) 
                                                                <?php
                                                                    $opt.= '<option selected value="'.$sval->id.'"> '.$sval->question.' </option>';    
                                                                ?>
                                                            @else 
                                                                <?php
                                                                    $opt.= '<option value="'.$sval->id.'"> '.$sval->question.' </option>';    
                                                                ?>
                                                            @endif 
                                                        @endforeach
                                                    @endif
                                                    <select name="question" id="question" class="form-control gj_s_question">
                                                        <?php echo $opt; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="answer">Security Answer</label>
                                                    <span class="error">* 
                                                        @if ($errors->has('answer'))
                                                            {{ $errors->first('answer') }}
                                                        @endif
                                                    </span>

                                                    {{ Form::text('answer', ($user->answer ? $user->answer : Input::old('answer')), array('class' => 'form-control gj_s_answer','placeholder' => 'Enter Your Security Answer')) }}
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
                                            </div>
                                        </div>

                                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                                    {{ Form::close() }}
                                @else
                                    <p class="gj_no_data">No More Details to Edit!</p>
                                @endif
                            @else
                                <p class="gj_no_data">No More Details to Edit!</p>
                            @endif
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="Section3">
                            <h3> Change Password </h3>
                            {{ Form::open(array('url' => 'forgot','class'=>'login100-form validate-form gj_ui_fp', 'files' => true)) }}
                                <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
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
                                
                                <div class="container-login100-form-btn">
                                    <button class="login100-form-btn" type="submit">
                                        Submit
                                    </button>
                                </div>
                            {{ Form::close() }}
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="Section4">
                            <h3> My Orders   </h3>

                            @if(isset($orders) && count($orders) != 0)
                                <div class= "table-responsive">
                                    <table class="table text-center">
                                        <tr>
                                            <th> Order ID </th>
                                            <th> Order Date </th>
                                            <th> Order Status </th>
                                            <th> Quantity </th>
                                            <th> Total Amount </th>
                                            <th> Action </th>
                                        </tr>
                                        
                                        @foreach ($orders as $key => $value)
                                            <tr>
                                                <td> 
                                                    {{$value->order_code}}
                                                    @if($value->ref_order_id)
                                                        @if($value->Reference->order_code)
                                                            <p class="gj_fd_ref_odr">Reference Order : {{$value->Reference->order_code}}</p>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td> {{$value->order_date ? date('d-m-Y', strtotime($value->order_date)) : '------'}} </td>
                                                <td> 
                                                    @if($value->order_status == 1)
                                                        {{'Order Placed'}}
                                                    @elseif($value->order_status == 2)
                                                        Order Dispatched
                                                    @elseif($value->order_status == 3)
                                                        Order Delivered
                                                    @elseif($value->order_status == 4)
                                                        Order Complete
                                                    @elseif($value->order_status == 5)
                                                        Order Cancelled
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                </td>
                                                <td> {{$value->total_items}} </td>
                                                <td> <i class="fa fa-inr"></i> {{$value->net_amount}} </td>
                                                <td class="stat"> 
                                                    <a href="{{ route('my_track_orders', ['id' => $value->id]) }}" class="gj_my_todr"> Track Order </a>

                                                    <a href="{{ route('my_view_orders', ['id' => $value->id]) }}" class="gj_my_vodr"> View Order </a>

                                                    <a href="#" data-toggle="modal" data-target="#myModal{{$value->id}}" @if($value->order_status != 1) style="pointer-events: none;     background-color: #ffae42 !important;" title="Order Cancel Not Possible" @endif @if($value->cancel_approved == 2) style="pointer-events: none;     background-color: #7c1111 !important;" title="Order Cancel Request Rejected" @endif @if($value->cancel_approved == 3) style="pointer-events: none;     background-color: #FA8072 !important;" title="Order Cancel Request Processed" @endif class="gj_my_codr_req" data-id="{{$value->id}}"> Cancel Order </a>

                                                    <div class="modal fade" id="myModal{{$value->id}}" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Term & Condition For Cancel Order</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($general)
                                                                        @if($general->cancel_terms)
                                                                            <p>{{$general->cancel_terms}}</p>
                                                                        @else
                                                                            <p>Please Click Accept Button</p>
                                                                        @endif
                                                                    @else
                                                                        <p>Please Click Accept Button</p>
                                                                    @endif
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <a href="#" @if($value->order_status != 1) style="pointer-events: none;     background-color: #ffae42 !important;" title="Order Cancel Not Possible" @endif @if($value->cancel_approved == 2) style="pointer-events: none;     background-color: #7c1111 !important;" title="Order Cancel Request Rejected" @endif @if($value->cancel_approved == 3) style="pointer-events: none;     background-color: #FA8072 !important;" title="Order Cancel Request Processed" @endif class="gj_my_codr" data-id="{{$value->id}}"> Accept </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php  
                                                        $n_date = date('Y-m-d');
                                                        $r_date = date('Y-m-d', strtotime($value->delivery_date. ' + 2 days'));
                                                    ?>
                                                    @if($value->order_status == 3 && $value->return_order_status == 0 && ($r_date >= $n_date))
                                                        <a href="#" data-toggle="modal" data-target="#rn_odr{{$value->id}}" class="gj_my_rodr_req" data-id="{{$value->id}}"> Return / Replace Order </a>

                                                        <div class="modal gj_trms fade" id="rn_odr{{$value->id}}" role="dialog">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Term & Condition For Return/Replace Order</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @if($general)
                                                                            @if($general->return_terms)
                                                                                <p>{{$general->return_terms}}</p>
                                                                            @else
                                                                                <p>Please Click Accept Button</p>
                                                                            @endif
                                                                        @else
                                                                            <p>Please Click Accept Button</p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a href="{{ route('customer_return_order', ['id' => $value->id]) }}" @if($value->order_status != 3) style="pointer-events: none;     background-color: #ffae42 !important;" @endif  class="gj_my_rodr"> Accept </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="gj_myacc_pge">
                                        {{$orders->links()}}
                                    </div>
                                </div>
                            @else
                                <p class="gj_no_data">Orders is Empty</p>
                            @endif
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="Section5">
                            <h3> Complete Orders   </h3>

                            @if(isset($past_orders) && count($past_orders) != 0)
                                <div class= "table-responsive">
                                    <table class="table text-center">
                                        <tr>
                                            <th> Order ID </th>
                                            <th> Order Date </th>
                                            <th> Order Status </th>
                                            <th> Quantity </th>
                                            <th> Total Amount </th>
                                            <th> Action </th>
                                        </tr>
                                        
                                        @foreach ($past_orders as $key => $value)
                                            <tr>
                                                <td> {{$value->order_code}} </td>
                                                <td> {{$value->order_date ? date('d-m-Y', strtotime($value->order_date)) : '------'}} </td>
                                                <td> 
                                                    @if($value->order_status == 1)
                                                        {{'Order Placed'}}
                                                    @elseif($value->order_status == 2)
                                                        Order Dispatched
                                                    @elseif($value->order_status == 3)
                                                        Order Delivered
                                                    @elseif($value->order_status == 4)
                                                        Order Complete
                                                    @elseif($value->order_status == 5)
                                                        Order Cancelled
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                </td>
                                                <td> {{$value->total_items}} </td>
                                                <td> INR {{$value->net_amount}} </td>
                                                <td class="stat"> 
                                                    <a href="{{ route('my_track_orders', ['id' => $value->id]) }}" class="gj_my_todr"> Track Order </a>
                                                    <a href="{{ route('my_review_orders', ['id' => $value->id]) }}" class="gj_my_rodr"> Review Order</a>
                                                    <a href="{{ route('my_view_orders', ['id' => $value->id]) }}" class="gj_my_vodr"> View Order </a>                   
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="gj_myacc_pge">
                                        {{$past_orders->links()}}
                                    </div>
                                </div>
                            @else
                                <p class="gj_no_data">Orders is Empty</p>
                            @endif
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="Section6">
                            <h3> Cancel Orders   </h3>

                            @if(isset($cancel_orders) && count($cancel_orders) != 0)
                                <div class= "table-responsive">
                                    <table class="table text-center">
                                        <tr>
                                            <th> Order ID </th>
                                            <th> Order Date </th>
                                            <th> Cancel Date </th>
                                            <th> Remarks </th>
                                            <th> Order Status </th>
                                            <th> Status </th>
                                            <th> Quantity </th>
                                            <th> Total Amount </th>
                                        </tr>
                                        
                                        @foreach ($cancel_orders as $key => $value)
                                            <tr>
                                                <td> {{$value->order_code}} </td>
                                                <td> {{$value->order_date ? date('d-m-Y', strtotime($value->order_date)) : '------'}} </td>
                                                <td> {{$value->cancel_date ? date('d-m-Y', strtotime($value->cancel_date)) : '------'}} </td>
                                                <td> {{$value->cancel_remarks}} </td>
                                                <td> 
                                                    @if($value->order_status == 1)
                                                        {{'Order Placed'}}
                                                    @elseif($value->order_status == 2)
                                                        Order Dispatched
                                                    @elseif($value->order_status == 3)
                                                        Order Delivered
                                                    @elseif($value->order_status == 4)
                                                        Order Complete
                                                    @elseif($value->order_status == 5)
                                                        Order Cancelled
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                </td>
                                                <td> 
                                                    @if($value->cancel_approved == 1)
                                                        {{'Accept'}}
                                                    @elseif($value->cancel_approved == 2)
                                                        Reject
                                                    @elseif($value->cancel_approved == 3)
                                                        Process
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                </td>
                                                <td> {{$value->total_items}} </td>
                                                <td> <i class="fa fa-inr"></i> {{$value->net_amount}} </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="gj_myacc_pge">
                                        {{$cancel_orders->links()}}
                                    </div>
                                </div>
                            @else
                                <p class="gj_no_data">Orders is Empty</p>
                            @endif
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="rtn_odr">
                            <h3> Return Orders   </h3>

                            @if(isset($re_orders) && $re_orders)
                                <div class= "table-responsive gj_my_vwro">
                                    <table class="table table-bordered table-hover">
                                        <tr>
                                            <th colspan="4">Order Code</th>
                                            <td colspan="10">{{$re_orders->order_code}}</td>
                                        </tr>

                                        <tr>
                                            <th colspan="4">Order Date</th>
                                            <td colspan="10">{{ date('d-m-Y', strtotime($re_orders->order_date)) }}</td>
                                        </tr>

                                        <tr>
                                            <th colspan="4">Return Date</th>
                                            <td colspan="10">{{ date('d-m-Y', strtotime($re_orders->return_date)) }}</td>
                                        </tr>

                                        <tr>
                                            <th colspan="4">Return Order Status</th>
                                            <td colspan="10">
                                                @if($re_orders->Orders->return_order_status == 1)
                                                    {{'Order Return Initialized'}}
                                                @elseif ($re_orders->Orders->return_order_status == 2)
                                                    {{'Order Return Confirmed'}}
                                                @elseif ($re_orders->Orders->return_order_status == 3)
                                                    {{'Order Return Cancelled'}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th colspan="4">Contact Person</th>
                                            <td colspan="10">
                                                @if($re_orders->order_id)
                                                    @if($re_orders->Orders->contact_person)
                                                        {{$re_orders->Orders->contact_person}}
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th colspan="4">Contact Number</th>
                                            <td colspan="10">
                                                @if($re_orders->order_id)
                                                    @if($re_orders->Orders->contact_no)
                                                        {{$re_orders->Orders->contact_no}}
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th colspan="4">Shipping Address</th>
                                            <td colspan="10">
                                                @if($re_orders->order_id)
                                                    <p class="gj_vw_ship">
                                                        @if($re_orders->Orders->shipping_address)
                                                            {{$re_orders->Orders->shipping_address}}
                                                        @else
                                                            {{'------'}}
                                                        @endif
                                                    </p>
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th colspan="4">Total Items</th>
                                            <td colspan="10">{{$re_orders->total_items}}</td>
                                        </tr>

                                        <tr>
                                            <th colspan="4">Net Amount</th>
                                            <td colspan="10">
                                                @if($re_orders->net_amount)
                                                    <i class="fa fa-inr"></i> {{' '.$re_orders->net_amount}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th colspan="14"></th>
                                        </tr>

                                        @if(sizeof($re_orders['details']) != 0) 
                                            <tr>
                                                <th>Title</th>
                                                <th>Product Add</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <!-- <th>Tax</th> -->
                                                <th>Total</th>
                                                <th>Type</th>
                                                <th>Return Qty</th>
                                                <th>Return Amount</th>
                                                <!-- <th>Return Tax</th> -->
                                                <th>Order Returned</th>
                                                <th>Reason</th>
                                                <th>Remarks</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                                <th>Admin Remarks</th>
                                            </tr>
                                            @foreach ($re_orders['details'] as $key => $value)
                                                <tr>
                                                    <td>
                                                        {{$value->product_title}}

                                                        @if(isset($value->att_name) && $value->att_name != 0)
                                                            @if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) 
                                                                <span>({{$value->AttName->att_name}} : {{$value->AttValue->att_value}})</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>{{$value->Products->Creatier->first_name.' '.$value->Products->Creatier->last_name}}</td>
                                                    <td>{{$value->order_qty}}</td>
                                                    <td>Rs. {{$value->unitprice}}</td>
                                                    <!-- <td>Rs. {{$value->tax_amount}}</td> -->
                                                    <td>Rs. {{$value->totalprice}}</td>
                                                    <td>{{$value->return_type}}</td>
                                                    <td>{{$value->return_qty}}</td>
                                                    <td>Rs. {{$value->return_amount}}</td>
                                                    <!-- <td>Rs. {{$value->return_tax_amount}}</td> -->
                                                    <td>{{$value->order_returned}}</td>
                                                    <td>{{$value->reason}}</td>
                                                    <td>{{$value->remarks}}</td>
                                                    <td>
                                                        <?php 
                                                            $file_path = 'images/return_order_image';
                                                        ?>
                                                        @if($value->rtn_image)
                                                            <a href="{{ asset($file_path.'/'.$value->rtn_image)}}" target ="_blank"><img src="{{ asset($file_path.'/'.$value->rtn_image)}}" class="img-responsive"></a>
                                                        @else
                                                            {{'-----'}}
                                                        @endif
                                                    </td>
                                                    <td>{{$value->status}}</td>
                                                    <td>{{$value->admin_remarks}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                            @else
                                <p class="gj_no_data">Orders is Empty</p>
                            @endif

                            <div class="gj_back_div text-right">
                                <a href="{{ route('my_account') }}#rtn_odr"><button type="button" class="gj_bck_btn btn btn-primary">Back</button></a>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="Section7">
                            <h3> Feed Back</h3>
                            <?php
                                $u_log = session()->get('user');
                            ?>
                            @if($u_log)
                                @if($u_log->user_type == 4)
                                    {{ Form::open(array('url' => 'send_feedback','class'=>'gj_fuser_form','files' => true)) }}
                                        @if($u_log)
                                            {{ Form::hidden('user_id', $u_log->id, array('class' => 'form-control gj_fuser_id')) }}
                                        @endif

                                        <div class="gj_box dark gj_inside_box">
                                            <header>
                                                <h5 class="gj_heading"> User Feed Back  </h5>
                                            </header>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {{ Form::label('subject', 'Subject') }}
                                                    <span class="error">* 
                                                        @if ($errors->has('subject'))
                                                            {{ $errors->first('subject') }}
                                                        @endif
                                                    </span>

                                                    {{ Form::text('subject', ($user->subject ? $user->subject : Input::old('subject')), array('class' => 'form-control gj_subject','placeholder' => 'Enter Subject in English')) }}
                                                </div>

                                                <div class="form-group">
                                                    {{ Form::label('message', 'Message') }}
                                                    <span class="error">* 
                                                        @if ($errors->has('message'))
                                                            {{ $errors->first('message') }}
                                                        @endif
                                                    </span>

                                                    {{ Form::textarea('message', ($user->message ? $user->message : Input::old('message')), array('class' => 'form-control gj_message', 'rows' => '5','placeholder' => 'Enter Message in English')) }}
                                                </div>
                                            </div>
                                        </div>

                                        {{ Form::submit('Send', array('class' => 'btn btn-primary')) }}

                                    {{ Form::close() }}
                                @else
                                    <p class="gj_no_data">Sorry!, You can not send the Feed Back!</p>
                                @endif
                            @else
                                <p class="gj_no_data">Sorry!, You can not send the Feed Back!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>     
    $(document).ready(function(){
        $('.vertical-tab .nav-tabs li a[href="#rtn_odr"]').tab('show');
        $('.vertical-tab .nav-tabs li').removeClass('active'); 
        $('.vertical-tab .nav-tabs li a[href="#rtn_odr"]').parent().addClass('active');

        $('.vertical-tab .nav-tabs li').click(function(){ 
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $(this).addClass('active'); 
        });

        $('#logout').click(function(){ 
            window.location.href = "{{ route('logout') }}";
        });

        $('.buzin').click(function(){ 
            $(".buzzacc").toggle(); 
        })
    });
</script>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(7000).slideUp(500); 
        $("#country").select2();
    });
</script>

<script>     
    $(document).ready(function() {
        <?php if(isset($_GET['tab_id']) && $_GET['tab_id'] == 'Section4') { ?>
            $('.vertical-tab .nav-tabs li a[href="#Section4"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#Section4"]').parent().addClass('active');
        <?php } ?>

        $('.vertical-tab .nav-tabs li').click(function(){ 
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $(this).addClass('active'); 
        });

        $('#logout').click(function(){ 
            window.location.href = "{{ route('logout') }}";
        });

        $('.buzin').click(function(){ 
            $(".buzzacc").toggle(); 
        })

    });
</script>

<script>
    // $('body').on('click','.gj_myacc_pge ul.pagination li',function() {
    //     $('a[href="#Section4"]').trigger();                                                                      
    // });
    function getUrlVars() {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(800); 
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();

        var trgr = false;
        var url = document.location.href;
        var res = url.toString().split('#');

        if(res[1]) {
            var trgr = res[1];
        }

        if(trgr) {
            $('.vertical-tab .nav-tabs li a[href="#' + trgr + '"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#' + trgr + '"]').parent().addClass('active');
        }

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

        @if(isset($user['docs']))
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
</script>
@endsection