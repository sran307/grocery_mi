 <div role="tabpanel" class="tab-pane fadein active" id="Section1">
                            <h3>My Profile</h3>
                            <div class="prof">
                                <?php 
                                    $value = session()->get('user');
                                ?>
                                @if($value)
                                    @if($value->user_type == 4 || $value->user_type == 5)
                                        <h4>Name : 
                                            <span>
                                                @if($value->first_name)
                                                    {{$value->first_name}} {{$value->last_name}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4>Mobile  :
                                            <span>
                                                @if($value->phone)
                                                    <gjspan>{{$value->phone}}</gjspan>
                                                   <!-- @if($value->mobile_verify == 1)
                                                        <gjspan class="gj_verify"><i class="fa fa-check-circle"></i><b>Verified</b></gjspan>
                                                    @else
                                                        <a href="{{ route('verify', ['on' => 'mobile', 'id' => $value->id]) }}" class="gj_verf_but1">
                                                            <button type="button" class="btn btn-success btn-sm gj_verf_but2">Verify Now</button></a>
                                                        <gjspan class="gj_unverify"><i class="fa fa-times"></i><b>Unverified</b></gjspan>
                                                    @endif  -->
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4>Email Id  :
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
                                        <h4>Gender  :
                                            <span>
                                                @if($value->gender)
                                                    {{$value->gender}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> Country :
                                            <span> 
                                                @if($value->country)
                                                    {{$value->Country->country_name}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> State :
                                            <span> 
                                                @if($value->state)
                                                    {{$value->State->state}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> District :
                                            <span> 
                                                @if($value->city)
                                                    {{$value->City->city_name}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> Address :
                                            <span> 
                                                @if($value->address1)
                                                    {{$value->address1}}, {{$value->address2}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> Pincode :
                                            <span> 
                                                @if($value->pincode)
                                                    {{$value->pincode}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        @if($value->user_type==5)
                                        <h4> Company :
                                            <span> 
                                                @if($value->bussiness_name)
                                                    {{$value->bussiness_name}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                        <h4> Company GSTIN No. :
                                            <span> 
                                                @if($value->gstn_no)
                                                    {{$value->gstn_no}}
                                                @else
                                                    {{'------'}}
                                                @endif  
                                            </span> 
                                        </h4>
                                         <?php 
                                            $file_path = 'images/gst';
                                         $ext = pathinfo($value->gst_document , PATHINFO_EXTENSION);

                                        ?>
                                       
                                                    @if($value->gst_document!='')
                                                    <h4> GST Verification Document
                                                        @if($ext=='png'|| $ext=='jpg'||$ext=='jpeg')
                                                        <span> 
                                                            <img src="{{ asset($file_path.'/'.$value->gst_document)}}" class="img-responsive">  
                                                        </span> 
                                                        @else
                                                        <span> 
                                                      <a href="{{url('download_file?slug='.$value->gst_document)}}" >{{$value->gst_document}}</a> 
                                                     </span> 
                                                        
                                                        @endif
                                                    </h4>
                                                    @else
                                                <p class="gj_no_data">No More Details to Edit!</p>
                                                    @endif
                                            @endif
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