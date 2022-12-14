@extends('layouts.master')
@section('title', 'View Orders')
@section('content')
<section class="gj_brands_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View Orders  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark" id="gj_svw_odr_tbl">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View Orders  </h5>
                </header>

                <div class="col-md-12">
                    @if($orders) 
                        <div class="gj_res_odr table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th colspan="2">Order Code</th>
                                    <td colspan="5">{{$orders->order_code}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Order Date</th>
                                    <td colspan="5">{{ date('d-m-Y', strtotime($orders->order_date)) }}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Payment Mode</th>
                                    <td colspan="5">
                                        @if($orders->payment_mode == 0)
                                            {{'------'}}
                                        @elseif ($orders->payment_mode == 1)
                                            {{'Cash On Delivery'}}
                                        @elseif ($orders->payment_mode == 2)
                                            {{'Online Payment'}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Delivery Date</th>
                                    <td colspan="5">
                                        @if($orders->delivery_date)
                                            {{ date('d-m-Y', strtotime($orders->delivery_date)) }}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Order Status</th>
                                    <td colspan="5">
                                        @if($orders->order_status == 0)
                                            {{'------'}}
                                        @elseif($orders->order_status == 1)
                                            {{'Order Placed'}}
                                        @elseif ($orders->order_status == 2)
                                            {{'Order Dispatched'}}
                                        @elseif ($orders->order_status == 3)
                                            {{'Order Delivered'}}
                                        @elseif ($orders->order_status == 4)
                                            {{'Order Complete'}}
                                        @elseif ($orders->order_status == 5)
                                            {{'Order Cancelled'}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th colspan="2">Contact Person</th>
                                    <td colspan="5">{{$orders->contact_person}}</td>
                                </tr>
                                
                                <tr>
                                    <th colspan="2">Contact Number</th>
                                    <td colspan="5">{{$orders->contact_no}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Shipping Address</th>
                                    <td colspan="5">{{$orders->shipping_address}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Total Items</th>
                                    <td colspan="5">{{$orders->total_items}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Sub Total</th>
                                    <td colspan="5">Rs. {{$orders->total_amount}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Discount</th>
                                    <td colspan="5">
                                        @if($orders->discount_flag)
                                            {{$orders->discount_flag}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Discount Rate</th>
                                    <td colspan="5">
                                        @if($orders->discount)
                                            {{'??? '.$orders->discount}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Shipping Charge</th>
                                    <td colspan="5">
                                        @if($orders->shipping_charge)
                                            {{'??? '.$orders->shipping_charge}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                               <!-- <tr>
                                    <th colspan="2">COD Charge</th>
                                    <td colspan="5">
                                        @if($orders->cod_charge)
                                            {{'??? '.$orders->cod_charge}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>-->

                                <tr>
                                    <th colspan="2">Tax Amount</th>
                                    <td colspan="5">
                                        @if($orders->tax_amount)
                                            {{'??? '.$orders->tax_amount}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Net Amount</th>
                                    <td colspan="5">
                                        @if($orders->net_amount)
                                            {{'??? '.$orders->net_amount}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Payment Status</th>
                                    <td colspan="5">
                                        @if($orders->payment_status == 0)
                                            {{'Pending'}}
                                        @elseif($orders->payment_status == 1)
                                            {{'Success'}}
                                        @elseif ($orders->payment_status == 2)
                                            {{'Failed'}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Delivery Status</th>
                                    <td colspan="5">
                                        @if($orders->delivery_status == 0)
                                            {{'------'}}
                                        @elseif ($orders->delivery_status == 1)
                                            {{'Success'}}
                                        @elseif ($orders->delivery_status == 2)
                                            {{'Failed'}}
                                        @else 
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Return Order Status</th>
                                    <td colspan="5">
                                        @if($orders->return_order_status == 0)
                                            {{'------'}}
                                        @elseif ($orders->return_order_status == 1)
                                            {{'Order Return Initialized'}}
                                        @elseif ($orders->return_order_status == 2)
                                            {{'Order Return Confirmed'}}
                                        @elseif ($orders->return_order_status == 3)
                                            {{'Order Return Cancelled'}}
                                        @else 
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">RePlace Order</th>
                                    <td colspan="5">
                                        {{$orders->replace_order}}
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Reference Order</th>
                                    <td colspan="5">
                                        @if($orders->ref_order_id)
                                            @if($orders->Reference->order_code)
                                                {{$orders->Reference->order_code}}
                                            @else 
                                                {{'------'}}
                                            @endif
                                        @else 
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Remarks</th>
                                    <td colspan="5">
                                        @if($orders->remarks)
                                            {{$orders->remarks}}
                                        @else 
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="6"></th>
                                </tr>

                                @if(sizeof($orders['details']) != 0) 
                                    <tr>
                                        <th>Title</th>
                                        <th>Product Code</th>
                                        <th>Product Add</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Tax(INCL)</th>
                                        <th>Total</th>
                                    </tr>
                                    @foreach ($orders['details'] as $key => $value)
                                        <tr>
                                            <td>
                                                {{$value->product_title}}

                                                @if(isset($value->att_name) && $value->att_name != 0)
                                                    @if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) 
                                                        <span>({{$value->AttName->att_name}} : {{$value->AttValue->att_value}})</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($value->Products->product_code))
                                                    {{$value->Products->product_code}}
                                                @else
                                                    {{'-----'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($value->Products->Creatier->first_name))
                                                    {{$value->Products->Creatier->first_name.' '.$value->Products->Creatier->last_name}}
                                                @else
                                                    {{'-----'}}
                                                @endif
                                            </td>
                                            <td>{{$value->order_qty}}</td>
                                            <td>{{'??? '.$value->unitprice}}</td>
                                            <td>{{$value->tax}}%</td>
                                            <td>{{'??? '.$value->totalprice}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th class="text-right" colspan="6">Shipping Charge</th>
                                        <td>
                                             @if($orders->shipping_charge)
                                            {{'??? '.$orders->shipping_charge}}
                                        @else
                                            {{'------'}}
                                        @endif
                                        </td>
                                       
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="6">Total(Including Tax)</th>
                                        
                                        <td colspan="1">{{'??? '.$orders->total_amount}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="6">Total</th>
                                        
                                        <td colspan="1">{{'??? '.$orders->net_amount}}</td>
                                    </tr>
                                    
                                @endif
                            </table>
                        </div>
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="gj_exp_but text-right">
                        <button class="btn btn-primary" onclick="Export()">Export</button>
                        
                        <a href="{{ route('all_orders') }}"><button class="btn btn-info">Back</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript">
    function Export() {
        $('.gj_exp_but').hide();
        html2canvas(document.getElementById('gj_svw_odr_tbl'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download("view_order.pdf");
            }
        });
    }
</script>
@endsection
