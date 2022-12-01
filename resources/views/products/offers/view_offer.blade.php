@extends('layouts.master')
@section('title', 'View Offer')
@section('content')
<section class="gj_vw_offer">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.product_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View Offer  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View Offer  </h5>
                </header>

                <div class="col-md-12">
                    <div class="gj_box dark gj_inside_box">
                        <header>
                            <h5 class="gj_heading"> Offer Details  </h5>
                        </header>
                        
                        <div class="col-md-12">
                            @if($deals)
                                <div class="table-responsive gj_vw_p_res">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Offer Title</th>
                                            <td>{{$deals->offer_title}}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{$deals->description}}</td>
                                        </tr>
                                        <tr>
                                            <th>Offer Type</th>
                                            <td>
                                                @if($deals->offer_type)
                                                    {{$deals->offer_type}}
                                                @else
                                                    {{'---------'}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>X-Product Count</th>
                                            <td>
                                                @if($deals->x_pro_cnt)
                                                    {{$deals->x_pro_cnt}}
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Y-Product Count</th>
                                            <td>
                                                @if($deals->y_pro_cnt)
                                                    {{$deals->y_pro_cnt}}
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Start Date</th>
                                            <td>{{date('d-F-Y h:i:s a', strtotime($deals->offer_start))}}</td>
                                        </tr>
                                        <tr>
                                            <th>End Date</th>
                                            <td>{{date('d-F-Y h:i:s a', strtotime($deals->offer_end))}}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($deals->is_block == 1)
                                                    Active
                                                @else
                                                    Deactive
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Grab Offer</th>
                                            <td><?php echo $deals->grab_offer; ?></td>
                                        </tr>

                                        <tr>
                                            <th>Featured Offer Image</th>
                                            <?php 
                                            $file_path = 'images/offer_products';
                                            ?>
                                            <td>
                                                @if($deals->image)
                                                    <div class="gj_vw_off_img">
                                                        <img src="{{ asset($file_path.'/'.$deals->image)}}" class="img-responsive">
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($deals)
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Offer Products  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                @if($deals['subs'] && count($deals['subs']) != 0)
                                    <div class="table-responsive gj_vw_att_res">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Product</th>
                                                <th>Assign Quantity</th>
                                                <th>Type</th>
                                            </tr>
                                            @foreach($deals['subs'] as $key => $value)
                                                <tr>
                                                    <td>
                                                        @if($value->product_id)
                                                            {{$value->OfferProducts->product_title}}

                                                            @if(isset($value->att_name) && $value->att_name != 0)
                                                                @if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) 
                                                                    <span>({{$value->AttName->att_name}} : {{$value->AttValue->att_value}})</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            -----
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->qty)
                                                            {{$value->qty}}
                                                        @else
                                                            -----
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->type == 1)
                                                            {{'X-Product'}}
                                                        @elseif($value->type == 2)
                                                            {{'Y-Product'}}
                                                        @else
                                                            -----
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

@endsection
