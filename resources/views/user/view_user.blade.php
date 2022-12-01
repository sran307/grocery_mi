@extends('layouts.master')
@section('title', 'View User')
@section('content')
<section class="gj_vw_user_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.user_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View User  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View User  </h5>
                </header>

                <div class="col-md-12">
                    <div class="gj_box dark gj_inside_box">
                        <header>
                            <h5 class="gj_heading"> User Details  </h5>
                        </header>
                        
                        <div class="col-md-12">
                            @if($user)
                                <div class="table-responsive gj_vw_p_res">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>First Name</th>
                                            <td>{{$user->first_name}}</td>
                                        </tr>

                                        <tr>
                                            <th>Last Name</th>
                                            <td>
                                                @if($user->last_name)
                                                    {{$user->last_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        @if($user->bussiness_name)
                                            <tr>
                                                <th>Business Name</th>
                                                <td>
                                                    {{$user->bussiness_name}}
                                                </td>
                                            </tr>
                                        @endif

                                        @if($user->buss_reg_no)
                                            <tr>
                                                <th>Business Reg.No</th>
                                                <td>
                                                    {{$user->buss_reg_no}}
                                                </td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <th>Email-ID</th>
                                            <td>
                                                @if($user->email)
                                                    {{$user->email}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Contact Number-1</th>
                                            <td>
                                                @if($user->phone)
                                                    {{$user->phone}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Contact Number-2</th>
                                            <td>
                                                @if($user->phone2)
                                                    {{$user->phone2}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Gender</th>
                                            <td>
                                                @if($user->gender)
                                                    {{$user->gender}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Address</th>
                                            <td>
                                                @if($user->address1)
                                                    {{$user->address1}}
                                                @else
                                                    {{'------'}}
                                                @endif

                                                @if($user->address2)
                                                    {{', '.$user->address2}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Pincode</th>
                                            <td>
                                                @if($user->pincode)
                                                    {{$user->pincode}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>District</th>
                                            <td>
                                                @if($user->city)
                                                    {{$user->City->city_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>State</th>
                                            <td>
                                                @if($user->state)
                                                    {{$user->State->state}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Country</th>
                                            <td>
                                                @if($user->country)
                                                    {{$user->Country->country_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Landmark</th>
                                            <td>
                                                @if($user->landmark)
                                                    {{$user->landmark}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        @if($user->commission)
                                            <tr>
                                                <th>Commision</th>
                                                <td>
                                                    {{$user->commission}}%
                                                </td>
                                            </tr>
                                        @endif

                                        @if($user->return_commission)
                                            <tr>
                                                <th>Return Commision</th>
                                                <td>
                                                    {{$user->return_commission}}%
                                                </td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <th>Approved Status</th>
                                            <td>
                                                @if($user->is_approved == 1)
                                                    {{"Approved"}}
                                                @else
                                                    {{"Not Approved"}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Approved Date</th>
                                            <td>
                                                @if($user->approved_date)
                                                    {{date('d-m-Y', strtotime($user->approved_date))}}
                                                @else
                                                    {{'---------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>User Type</th>
                                            <td>
                                                @if($user->user_type == 1)
                                                    {{'Admin'}}
                                                @elseif($user->user_type == 2)
                                                    {{'Admin Add Merchant'}}
                                                @elseif($user->user_type == 3)
                                                    {{'Website Merchant'}}
                                                @elseif($user->user_type == 4)
                                                    {{'Customer'}}
                                                @else
                                                    {{'---------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Login Type</th>
                                            <td>
                                                @if($user->login_type == 1)
                                                    {{'Website Login'}}
                                                @elseif($user->login_type == 2)
                                                    {{'Social Login'}}
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Create Date</th>
                                            <td>{{date('d-m-Y', strtotime($user->created_at))}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Profile Image</th>
                                            <?php 
                                            $file_path = 'images/profile_img';
                                            $noimage = \DB::table('noimage_settings')->first();
                                            $noimage_path = 'images/noimage';
                                            ?>
                                            <td>
                                                @if($user->profile_img)
                                                    <div class="gj_vw_up_img">
                                                        <img src="{{ asset($file_path.'/'.$user->profile_img)}}" class="img-responsive">
                                                    </div>
                                                @else
                                                    <div class="gj_vw_up_img">
                                                        <img src="{{ asset($noimage_path.'/'.$noimage->profile_no_img)}}" class="img-responsive">
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($user)
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> User Documents  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                @if($user['docs'] && count($user['docs']) != 0)
                                    <div class="table-responsive gj_vw_att_res">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Name</th>
                                                <th>Document</th>
                                            </tr>
                                            @foreach($user['docs'] as $key => $value)
                                                <tr>
                                                    <td>
                                                        @if($value->d_name)
                                                            {{$value->d_name}}
                                                        @else
                                                            {{'-------'}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        $doc_path = 'documents';
                                                        ?>

                                                        @if($value->image)
                                                            <a href="{{ asset($doc_path.'/'.$value->image)}}" target="_blank" title="{{$value->image}}" class="gj_vw_user_doc" download><embed src="{{ asset($doc_path.'/'.$value->image)}}"/></a>
                                                        @else
                                                            {{'-------'}}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @else
                                    <div class="gj_vw_not_att">
                                        <p>No Data Available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
    });
</script>
@endsection
