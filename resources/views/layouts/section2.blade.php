<div role="tabpanel" class="tab-pane fade" id="Section2">
                            <h3> Edit Profile</h3>
                            <?php
                                $user = session()->get('user');
                            ?>
                            @if($user)
                                @if($user->user_type == 4 || $user->user_type == 5)
                                    {{ Form::open(array('url' => 'edit_profile','class'=>'gj_user_form ahnkozlqwrty','files' => true)) }}
                                        @if($user)
                                            {{ Form::hidden('user_id', $user->id, array('class' => 'form-control gj_user_id')) }}
                                        @endif

                                        <div class="gj_box dark gj_inside_box">
                                            <header>
                                                <h5 class="gj_heading"> Users Account  </h5>
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
                                                @if($user->user_type == 5)
                                            <div class="form-group" id="company_div">
                                                {{ Form::label('gst', 'Company Name') }}
                                                <span class="error">* 
                                                    @if ($errors->has('company_name'))
                                                        {{ $errors->first('company_name') }}
                                                    @endif
                                                </span>
                    
                                                {{ Form::text('company_name', $user->bussiness_name?$user->bussiness_name:Input::old('company_name'), array('class' => 'form-control company_name','placeholder' => 'Enter Company Name')) }}
                                            </div>
                                            <div class="form-group" id="company_gst_div">
                                        {{ Form::label('gst', 'Company GSTIN No.') }}
                                        <span class="error">* 
                                            @if ($errors->has('company_gst_no'))
                                                {{ $errors->first('company_gst_no') }}
                                            @endif
                                        </span>
                                    <input type="text" name="company_gst_no" value="{{$user->gstn_no?$user->gstn_no:Input::old('company_gst_no')}}" maxlength="15" placeholder="Enter Company GSTIN No." class="form-control company_gst_no" id="company_gst_no" pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" title="Invalid GST Number." />
                                    </div>
                                                <div class="gj_ban_img_whole">
                                    <?php 
                                    $file_path = 'images/gst';
                                    $ext = pathinfo($user->gst_document , PATHINFO_EXTENSION);

                                    ?>
                                    @if(isset($user))
                                        @if($user->gst_document != '')
                                        <div class="form-group">
                                            {{ Form::label('current_profile_img', 'GST Verification Document') }}
                                            @if($ext=='png'|| $ext=='jpg'||$ext=='jpeg')
                                            <div class="gj_mc_div">
                                               <img src="{{ asset($file_path.'/'.$user->gst_document)}}" class="img-responsive"> 
                                            </div>
                                            @else
                                            <div class="gj_mc_div">
                                              <a href="{{url('download_file?slug='.$user->gst_document)}}" ><i class="fa fa-download"></i> {{$user->gst_document}}</a> 
                                            </div>
                                            @endif
                                            {{ Form::hidden('old_gst_document', ($user->gst_document ? $user->gst_document : ''), array('class' => 'form-control')) }}
                                        </div>
                                        @endif
                                    @endif

                                    <div class="form-group">
                                        {{ Form::label('verification_document', 'GST Verification Document') }}
                            <span class="error">* 
                                @if ($errors->has('verification_document'))
                                    {{ $errors->first('verification_document') }}
                                @endif
                            </span>

                            <input type="file" name="verification_document" id="verification_document" accept="pdf/*,doc/*" class="verification_document">
                   
                                                 </div>
                                </div>
                                                @endif
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