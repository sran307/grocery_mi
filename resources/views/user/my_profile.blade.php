@extends('layouts.master')
@section('title', 'My Profile')
@section('content')
<section class="gj_profile_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.user_sidebar')
        </div>
        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> My Profile  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> My Profile  </h5>
                </header>

                @if($profile)
                    <p class="gj_pfe_hd">{{$profile->first_name}} Details</p>
                @endif

                <div class="col-md-12">
                    <div class="gj_vw_profile">
                        <div class="gj_vw_pfe_res table-responsive">
                            <table class="gj_vw_pfe_tbl table table-bordered">
                                @if($profile)
                                    <tr>
                                        <th>First Name</th>
                                        <td>{{$profile->first_name}}</td>
                                    </tr>

                                    @if($profile->last_name)
                                        <tr>
                                            <th>Last Name</th>
                                            <td>{{$profile->last_name}}</td>
                                        </tr>
                                    @endif

                                    @if($profile->bussiness_name)
                                        <tr>
                                            <th>Bussiness Name</th>
                                            <td>{{$profile->bussiness_name}}</td>
                                        </tr>
                                    @endif

                                    @if($profile->buss_reg_no)
                                        <tr>
                                            <th>Bussiness Register No</th>
                                            <td>{{$profile->buss_reg_no}}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th>E-mail Id</th>
                                        <td>{{$profile->email}}</td>
                                    </tr>

                                    <tr>
                                        <th>Phone Number</th>
                                        <td>{{$profile->phone}}</td>
                                    </tr>
                                    
                                    @if($profile->phone2)
                                        <tr>
                                            <th>Phone Number-2</th>
                                            <td>{{$profile->phone2}}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th>Gender</th>
                                        <td>{{$profile->gender}}</td>
                                    </tr>

                                    <tr>
                                        <th>Address</th>
                                        <td>{{$profile->address1}}</td>
                                    </tr>

                                    <tr>
                                        <th>City</th>
                                        <td>{{$profile->address2}}</td>
                                    </tr>

                                    <tr>
                                        <th>Pincode</th>
                                        <td>{{$profile->pincode}}</td>
                                    </tr>

                                    <tr>
                                        <th>Country</th>
                                        @if($profile->country)
                                            <td>{{$profile->Country->country_name}}</td>
                                        @else
                                            <td>{{'------'}}</td>
                                        @endif
                                    </tr>

                                    <tr>
                                        <th>State</th>
                                        @if($profile->state)
                                            <td>{{$profile->State->state}}</td>
                                        @else
                                            <td>{{'------'}}</td>
                                        @endif
                                    </tr>

                                    <tr>
                                        <th>District</th>
                                        @if($profile->city)
                                            <td>{{$profile->City->city_name}}</td>
                                        @else
                                            <td>{{'------'}}</td>
                                        @endif
                                    </tr>
                                @else
                                    <tr>
                                        <th>Data Not Found</th>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="gj_edit_pfe_div">
                        <a href="{{ route('edit_profile') }}" class="gj_edit_pfe">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
        $("#country").select2();
        $("#city").select2();
        $("#profile_type").select2();
    });

    $('#country').on('change',function() {
        var country = $(this).val();
        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_citys')}}',
                data: {country: country, type: 'city'},
                success: function(data){
                    if(data){
                        $("#city").html(data);
                        $("#city").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Another Country!',
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
                }
            });
        }
    });
</script>
@endsection