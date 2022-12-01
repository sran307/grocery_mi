@extends('layouts.master')
@section('title', 'View Return Order')
@section('content')
<section class="gj_rtn_odr_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View Return Order  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark" id="gj_svw_odr_tbl">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View Return Order  </h5>
                </header>

                <div class="col-md-12">
                    @if($orders) 
                        <div class="gj_res_odr table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th colspan="4">Order Code</th>
                                    <td colspan="11">{{$orders->order_code}}</td>
                                </tr>

                                <tr>
                                    <th colspan="4">Order Date</th>
                                    <td colspan="11">{{ date('d-m-Y', strtotime($orders->order_date)) }}</td>
                                </tr>

                                <tr>
                                    <th colspan="4">Return Date</th>
                                    <td colspan="11">{{ date('d-m-Y', strtotime($orders->return_date)) }}</td>
                                </tr>

                                <tr>
                                    <th colspan="4">Return Order Status</th>
                                    <td colspan="11">
                                        @if($orders->Orders->return_order_status == 1)
                                            {{'Order Return Initialized'}}
                                        @elseif ($orders->Orders->return_order_status == 2)
                                            {{'Order Return Confirmed'}}
                                        @elseif ($orders->Orders->return_order_status == 3)
                                            {{'Order Return Cancelled'}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th colspan="4">Contact Person</th>
                                    <td colspan="11">
                                        @if($orders->order_id)
                                            @if($orders->Orders->contact_person)
                                                {{$orders->Orders->contact_person}}
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
                                    <td colspan="11">
                                        @if($orders->order_id)
                                            @if($orders->Orders->contact_no)
                                                {{$orders->Orders->contact_no}}
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
                                    <td colspan="11">
                                        @if($orders->order_id)
                                            @if($orders->Orders->shipping_address)
                                                {{$orders->Orders->shipping_address}}
                                            @else
                                                {{'------'}}
                                            @endif
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="4">Total Items</th>
                                    <td colspan="11">{{$orders->total_items}}</td>
                                </tr>

                                <tr>
                                    <th colspan="4">Net Amount</th>
                                    <td colspan="11">
                                        @if($orders->net_amount)
                                            {{'₹ '.$orders->net_amount}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="4">Return Total Items</th>
                                    <td colspan="11">{{$orders->return_total_items}}</td>
                                </tr>

                                <tr>
                                    <th colspan="4">Return Net Amount</th>
                                    <td colspan="11">
                                        @if($orders->return_net_amount)
                                            {{'₹ '.$orders->return_net_amount}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="15"></th>
                                </tr>

                                @if(sizeof($orders['details']) != 0) 
                                    <tr>
                                        <th>Title</th>
                                        <th>Product Code</th>
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
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="gj_exp_but text-right">
                        <button class="btn btn-primary" onclick="Export()">Export</button>
                        
                        <a href="{{ route('return_all_orders') }}"><button class="btn btn-info">Back</button></a>
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
