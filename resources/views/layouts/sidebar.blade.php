<div class="gj_left_menu">
    <div id="gj_left" class="slider-parent active">
        <!-- <div class="media user-media well-small">
            <div class="media-body">
                <h5 class="media-heading">Settings</h5>
            </div>
            <br>
        </div> -->

        <?php $log = session()->get('user'); ?>

        @if($log)
            @if($log->user_type == 1)
                <ul id="gj_menu" class="">
                    <li class="panel active">
                        <a href="{{ route('create_general_setting') }}">
                            <i class="fa fa-cog"></i> General Settings</a>
                    </li>

                    <li class="panel">
                        <a href="{{ route('create_email_setting') }}">
                            <i class="fa fa-envelope"></i> Email & Contact Settings
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('create_widget_setting') }}">
                            <i class="fa fa-connectdevelop"></i> Widget Settings
                        </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('create_social_media_setting') }}">
                            <i class="fa fa-facebook"></i> Social Media Page Settings
                       </a>                   
                    </li>

                    <li class="panel">
                        <a href="{{ route('create_payment_setting') }}">
                            <i class="fa fa-credit-card"></i> Payment settings
                       </a>                   
                    </li>

                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#delivery-nav">
                            <i class="fa fa-clock-o"> </i> Delivery Time Settings  
                            <span class="pull-right">
                              <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <ul class="collapse" id="delivery-nav">
                            <li class=""><a href="{{ route('add_delivery') }}"><i class="fa fa-angle-right"></i> Add Delivery Time </a></li>

                            <li class=""><a href="{{ route('delivery_manage') }}"><i class="fa fa-angle-right"></i> Manage Delivery Time</a></li>

                        
                        </ul>
                    </li>

                   <!-- <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#roles-nav">
                            <i class="fa fa-picture-o"> </i> Roles & Previlages  
                            <span class="pull-right">
                              <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <ul class="collapse" id="roles-nav">
                            <li class=""><a href="{{ route('manage_role') }}"><i class="fa fa-angle-right"></i> Manage Roles</a></li>

                            <li class=""><a href="{{ route('add_role') }}"><i class="fa fa-angle-right"></i> Add Role </a></li>

                            <li class=""><a href="{{ route('manage_modules') }}"><i class="fa fa-angle-right"></i> Manage Modules</a></li>

                            <li class=""><a href="{{ route('add_modules') }}"><i class="fa fa-angle-right"></i> Add Module </a></li>

                            <li class=""><a href="{{ route('user_previl') }}"><i class="fa fa-angle-right"></i> Manage Roles & Privileges </a></li>
                        </ul>
                    </li>-->
                    
                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-nav">
                            <i class="fa fa-picture-o"> </i> Image Settings   
                            <span class="pull-right">
                              <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <ul class="collapse" id="component-nav">
                            <li class=""><a href="{{ route('create_logo_setting') }}"><i class="fa fa-angle-right"></i> Logo Settings </a></li>

                            <li class=""><a href="{{ route('create_favicon_setting') }}"><i class="fa fa-angle-right"></i> Favicon Settings </a></li>

                            <li class=""><a href="{{ route('create_noimage_setting') }}"><i class="fa fa-angle-right"></i> No-Image Settings </a></li>
                        </ul>
                    </li>

                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#form-nav">
                            <i class="fa fa-camera"></i> Banner Image Settings
           
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                             
                        </a>
                        
                        <ul class="collapse" id="form-nav">
                            <li class=""><a href="{{ route('add_banner_image') }}"><i class="fa fa-angle-right"></i> Add Banner Image</a></li>
                            <li class=""><a href="{{ route('manage_banner_image') }}"><i class="fa fa-angle-right"></i> Manage Banner Images</a></li>
                         <!-- <li class=""><a href="{{ route('add_side_banner_image') }}"><i class="fa fa-angle-right"></i> Add Side Banner Image</a></li>
                          <li class=""><a href="{{ route('manage_side_banner_image') }}"><i class="fa fa-angle-right"></i> Manage Side Banner Image</a></li>-->

                        </ul>
                    </li>

                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#ad-nav">
                            <i class="fa fa-camera-retro"></i> Category Advertisement
           
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                             
                        </a>
                        <ul class="collapse" id="ad-nav">
                            <li class=""><a href="{{ route('add_advertisement') }}"><i class="fa fa-angle-right"></i> Add Advertisement </a></li>
                            <li class=""><a href="{{ route('manage_advertisement') }}"><i class="fa fa-angle-right"></i>  Manage Advertisement </a></li>
                            
                        </ul>
                    </li>
                    
                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#pagesr-nav">
                            <i class="fa fa-anchor"></i> Attributes Management
           
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                             
                        </a>
                        <ul class="collapse" id="pagesr-nav">
                            <li class=""><a href="{{ route('add_att_fields') }}"><i class="fa fa-angle-right"></i> Add Attributes Fields </a></li>
                            <li class=""><a href="{{ route('manage_att_fields') }}"><i class="fa fa-angle-right"></i> Manage Attributes Fields </a></li>

                            <li class=""><a href="{{ route('add_attributes') }}"><i class="fa fa-angle-right"></i> Add Attributes </a></li>
                            <li class=""><a href="{{ route('manage_attributes') }}"><i class="fa fa-angle-right"></i> Manage Attributes </a></li>

                            <!-- <li class=""><a href="{{ route('add_color') }}"><i class="fa fa-angle-right"></i> Add Color </a></li>
                            <li class=""><a href="{{ route('manage_color') }}"><i class="fa fa-angle-right"></i> Manage Colors </a></li>
                            <li class=""><a href="{{ route('add_size') }}"><i class="fa fa-angle-right"></i> Add Size </a></li>
                            <li class=""><a href="{{ route('manage_size') }}"><i class="fa fa-angle-right"></i> Manage Size </a></li>
                            <li class=""><a href="{{ route('add_capacity') }}"><i class="fa fa-angle-right"></i> Add Capacity </a></li>
                            <li class=""><a href="{{ route('manage_capacity') }}"><i class="fa fa-angle-right"></i> Manage Capacity </a></li> -->                    
                        </ul>
                    </li>
                    

                    <!--<li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#DDL-nav">
                            <i class=" fa fa-globe"></i> Countries Management
           
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>

                        <ul class="collapse" id="DDL-nav">
                            
                            <li class=""><a href="{{ route('create_add_country') }}"><i class="fa fa-angle-right"></i>Add Country </a></li>
                            <li class=""><a href="{{ route('index_manage_country') }}"><i class="fa fa-angle-right"></i> Manage Country </a></li>
                            <li class=""><a href="{{ route('create_new_country') }}"><i class="fa fa-angle-right"></i>New Country </a></li>
                            <li class=""><a href="{{ route('index_manage_all_country') }}"><i class="fa fa-angle-right"></i> Manage All Countries </a></li>
                        </ul>
                    </li>-->

                    <!--<li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#DDL4-nav">
                            <i class="fa fa-building"></i> States Management
           
                        <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <ul class="collapse" id="DDL4-nav">
                            
                            <li class=""><a href="{{ route('add_state') }}"><i class="fa fa-angle-right"></i> Add State  </a></li>
                            <li class=""><a href="{{ route('manage_state') }}"><i class="fa fa-angle-right"></i> Manage States</a></li>
                        </ul>
                    </li>-->

                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#DDL5-nav">
                            <i class=" fa fa-hospital-o"></i> Districts Management
           
                        <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <ul class="collapse" id="DDL5-nav">
                            
                            <li class=""><a href="{{ route('add_city') }}"><i class="fa fa-angle-right"></i> Add District  </a></li>
                            <li class=""><a href="{{ route('manage_city') }}"><i class="fa fa-angle-right"></i> Manage Districts</a></li>
                        </ul>
                    </li>

                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#error-nav">
                            <i class="fa fa-plus"></i> Category Management
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <ul class="collapse" id="error-nav">
                            <li class=""><a href="{{ route('add_category') }}"><i class="fa fa-angle-right"></i> Add Category </a></li>
                            <li class=""><a href="{{ route('manage_category') }}"><i class="fa fa-angle-right"></i> Manage Categories </a></li>
                           
                        </ul>
                    </li>

                    <!-- <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#tax-nav">
                            <i class="fa fa-percent"></i> Tax Management
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <ul class="collapse" id="tax-nav">
                            <li class=""><a href="{{ route('manage_tax') }}"><i class="fa fa-angle-right"></i> Manage GST Tax </a></li>
                            <li class=""><a href="{{ route('add_tax') }}"><i class="fa fa-angle-right"></i> Add GST Tax </a></li>
                        </ul>
                    </li> -->

                   <!-- <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#cut-nav">
                            <i class="fa fa-scissors"></i> Cutoff Management
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <ul class="collapse" id="cut-nav">
                            <li class=""><a href="{{ route('manage_cutoff') }}"><i class="fa fa-angle-right"></i> Manage Cut-Off </a></li>                   
                            <li class=""><a href="{{ route('add_cutoff') }}"><i class="fa fa-angle-right"></i> Add Cut-Off </a></li>
                        </ul>
                    </li>-->

                    <!--<li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#cod-nav">
                            <i class="fa fa-dashcube"></i> COD Management
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <ul class="collapse" id="cod-nav">
                            <li class=""><a href="{{ route('manage_cod') }}"><i class="fa fa-angle-right"></i> Manage COD Settings </a></li>                   
                            <li class=""><a href="{{ route('add_cod') }}"><i class="fa fa-angle-right"></i> Add COD Setting </a></li>
                        </ul>
                    </li>-->

                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#chart-nav2">
                            <i class="fa fa-pencil"></i> CMS Management
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                                <span class="label label-danger"></span> 
                        </a>
                        <ul class="collapse" id="chart-nav2">
                            <li class=""><a href="{{ route('add_cms_page') }}"><i class="fa fa-angle-right"></i> Add Page</a></li>
                            <li class=""><a href="{{ route('manage_cms_page') }}"><i class="fa fa-angle-right"></i> Manage CMS Pages</a></li>
                            <li class=""><a href="{{ route('manage_headings') }}"><i class="fa fa-angle-right"></i> Manage Headings</a></li>
                             <li class=""><a href="{{ route('widget1') }}"><i class="fa fa-angle-right"></i> Deal Of The Day Widget</a></li>
                            <li class=""><a href="{{ route('add_about_page') }}"><i class="fa fa-angle-right"></i> Manage About Us</a></li>  
                            <li class=""><a href="{{ route('manage_about_1') }}"><i class="fa fa-angle-right"></i> Manage About Us Widget 1</a></li>                
                            <li class=""><a href="{{ route('manage_about_2') }}"><i class="fa fa-angle-right"></i> Manage About Us Widget 2</a></li>                
                            <li class=""><a href="{{ route('add_disclaimers') }}"><i class="fa fa-angle-right"></i> Disclaimers</a></li>                
                             <li class=""><a href="{{ route('terms') }}"><i class="fa fa-angle-right"></i>  Terms & Conditions</a></li> 
                          <li class=""><a href="{{ url('get_settings/privacy') }}"><i class="fa fa-angle-right"></i>Privacy Policy</a></li>                   

                        </ul>
                    </li>
                    <!--home page widgets-->
                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#chart-nav2">
                            <i class="fa fa-home"></i> Home Page Widgets
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                                <span class="label label-danger"></span> 
                        </a>
                        <ul class="collapse" id="chart-nav2">
                            <li class=""><a href="{{ route('add_cms_page') }}"><i class="fa fa-angle-right"></i> </a></li>              
                        </ul>
                    </li>
{{--
                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#chart-ls">
                            <i class="fa fa-question-circle" aria-hidden="true"></i> Build PC Settings
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                              <span class="label label-danger"></span> 
                        </a>
                        <ul class="collapse" id="chart-ls">
                            <li class=""><a href="{{ route('admin_build_pc') }}"><i class="fa fa-angle-right"></i> Manage  Settings</a></li>

                        </ul>
                    </li>
                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#chart-lsv">
                            <i class="fa fa-dashcube" aria-hidden="true"></i> Package Dimension
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                              <span class="label label-danger"></span> 
                        </a>
                        <ul class="collapse" id="chart-lsv">
                            <li class=""><a href="{{ route('manage_package_dimension') }}"><i class="fa fa-angle-right"></i> Manage  Package Dimension</a></li>
                             <li class=""><a href="{{ route('add_package_dimension') }}"><i class="fa fa-angle-right"></i> Add  Package Dimension</a></li>

                        </ul>
                    </li>
                    --}}
                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#chart-pin">
                            <i class="fa fa-dashcube" aria-hidden="true"></i>Pincode/Zipcode
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                              <span class="label label-danger"></span> 
                        </a>
                        <ul class="collapse" id="chart-pin">
                            <li class=""><a href="{{ route('manage_pincode') }}"><i class="fa fa-angle-right"></i> Manage  Pincodes</a></li>
                             <li class=""><a href="{{ route('add_pincode') }}"><i class="fa fa-angle-right"></i> Add Pincode</a></li>

                        </ul>
                    </li>
                    
                </ul>
            @elseif($log->user_type == 3 || $log->user_type == 2)
                <ul id="gj_menu" class="">
                    <li class="panel">
                        <a href="#/" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#pagesr-nav">
                            <i class="fa fa-anchor"></i> Attributes Management
           
                            <span class="pull-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                             
                        </a>
                        <ul class="collapse" id="pagesr-nav">
                            <li class=""><a href="{{ route('add_att_fields') }}"><i class="fa fa-angle-right"></i> Add Attributes Fields </a></li>
                            <li class=""><a href="{{ route('manage_att_fields') }}"><i class="fa fa-angle-right"></i> Manage Attributes Fields </a></li>

                            <li class=""><a href="{{ route('add_attributes') }}"><i class="fa fa-angle-right"></i> Add Attributes </a></li>
                            <li class=""><a href="{{ route('manage_attributes') }}"><i class="fa fa-angle-right"></i> Manage Attributes </a></li>

                            <!-- <li class=""><a href="{{ route('add_color') }}"><i class="fa fa-angle-right"></i> Add Color </a></li>
                            <li class=""><a href="{{ route('manage_color') }}"><i class="fa fa-angle-right"></i> Manage Colors </a></li>
                            <li class=""><a href="{{ route('add_size') }}"><i class="fa fa-angle-right"></i> Add Size </a></li>
                            <li class=""><a href="{{ route('manage_size') }}"><i class="fa fa-angle-right"></i> Manage Size </a></li>
                            <li class=""><a href="{{ route('add_capacity') }}"><i class="fa fa-angle-right"></i> Add Capacity </a></li>
                            <li class=""><a href="{{ route('manage_capacity') }}"><i class="fa fa-angle-right"></i> Manage Capacity </a></li> -->                    
                        </ul>
                    </li>
                </ul>
            @endif
        @endif  
    </div>
</div>