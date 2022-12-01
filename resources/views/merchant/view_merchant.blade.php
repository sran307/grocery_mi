@extends('layouts.master')
@section('title', 'View Merchant')
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
                        <li class="active"><a> View Merchant  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View Merchant  </h5>
                </header>

                <div class="col-md-12">
                    <div class="gj_box dark gj_inside_box">
                        <header>
                            <h5 class="gj_heading"> Merchant Details  </h5>
                        </header>
                        
                        <div class="col-md-12">
                            @if($merchant)
                                <div class="table-responsive gj_vw_p_res">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>First Name</th>
                                            <td>{{$merchant->first_name}}</td>
                                        </tr>

                                        <tr>
                                            <th>Last Name</th>
                                            <td>
                                                @if($merchant->last_name)
                                                    {{$merchant->last_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Business Name</th>
                                            <td>
                                                @if($merchant->bussiness_name)
                                                    {{$merchant->bussiness_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Business Reg.No</th>
                                            <td>
                                                @if($merchant->buss_reg_no)
                                                    {{$merchant->buss_reg_no}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Email-ID</th>
                                            <td>
                                                @if($merchant->email)
                                                    {{$merchant->email}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Contact Number-1</th>
                                            <td>
                                                @if($merchant->phone)
                                                    {{$merchant->phone}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Contact Number-2</th>
                                            <td>
                                                @if($merchant->phone2)
                                                    {{$merchant->phone2}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Gender</th>
                                            <td>
                                                @if($merchant->gender)
                                                    {{$merchant->gender}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Address</th>
                                            <td>
                                                @if($merchant->address1)
                                                    {{$merchant->address1}}
                                                @else
                                                    {{'------'}}
                                                @endif

                                                @if($merchant->address2)
                                                    {{', '.$merchant->address2}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Pincode</th>
                                            <td>
                                                @if($merchant->pincode)
                                                    {{$merchant->pincode}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>District</th>
                                            <td>
                                                @if($merchant->city)
                                                    {{$merchant->City->city_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>State</th>
                                            <td>
                                                @if($merchant->state)
                                                    {{$merchant->State->state}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Country</th>
                                            <td>
                                                @if($merchant->country)
                                                    {{$merchant->Country->country_name}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Landmark</th>
                                            <td>
                                                @if($merchant->landmark)
                                                    {{$merchant->landmark}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Commision</th>
                                            <td>
                                                @if($merchant->commission)
                                                    {{$merchant->commission}}%
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Return Commision</th>
                                            <td>
                                                @if($merchant->return_commission)
                                                    {{$merchant->return_commission}}%
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Approved Status</th>
                                            <td>
                                                @if($merchant->is_approved == 1)
                                                    {{"Approved"}}
                                                @else
                                                    {{"Not Approved"}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Approved Date</th>
                                            <td>
                                                @if($merchant->approved_date)
                                                    {{date('d-m-Y', strtotime($merchant->approved_date))}}
                                                @else
                                                    {{'---------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>User Type</th>
                                            <td>
                                                @if($merchant->user_type == 1)
                                                    {{'Admin'}}
                                                @elseif($merchant->user_type == 2)
                                                    {{'Admin Add Merchant'}}
                                                @elseif($merchant->user_type == 3)
                                                    {{'Website Merchant'}}
                                                @elseif($merchant->user_type == 4)
                                                    {{'Customer'}}
                                                @else
                                                    {{'---------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Login Type</th>
                                            <td>
                                                @if($merchant->login_type == 1)
                                                    {{'Website Login'}}
                                                @elseif($merchant->login_type == 2)
                                                    {{'Social Login'}}
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Create Date</th>
                                            <td>{{date('d-m-Y', strtotime($merchant->created_at))}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Profile Image</th>
                                            <?php 
                                            $file_path = 'images/profile_img';
                                            $noimage = \DB::table('noimage_settings')->first();
                                            $noimage_path = 'images/noimage';
                                            ?>
                                            <td>
                                                @if($merchant->profile_img)
                                                    <div class="gj_vw_up_img">
                                                        <img src="{{ asset($file_path.'/'.$merchant->profile_img)}}" class="img-responsive">
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

                    @if($merchant)
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Merchant Documents  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                @if($merchant['docs'] && count($merchant['docs']) != 0)
                                    <div class="table-responsive gj_vw_att_res">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Name</th>
                                                <th>Document</th>
                                            </tr>
                                            @foreach($merchant['docs'] as $key => $value)
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
