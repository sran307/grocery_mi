@extends('layouts.master')
@section('title', 'View Transaction')
@section('content')
<section class="gj_vw_trans_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View Transaction  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark" id="gj_svw_trans_tbl">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View Transaction  </h5>
                </header>

                <div class="col-md-12">
                    @if($trans) 
                        <div class="gj_res_trans table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th colspan="2">Transaction Code</th>
                                    <td colspan="5">{{$trans->trans_code}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Order Code</th>
                                    <td colspan="5">{{$trans->orders->order_code}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Transaction Date</th>
                                    <td colspan="5">{{ date('d-m-Y H:i:s', strtotime($trans->trans_date)) }}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Transaction ID</th>
                                    <td colspan="5">
                                        @if ($trans->gatewaytransactionid)
                                            {{$trans->gatewaytransactionid}}
                                        @else
                                            {{'-------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Amount Paid</th>
                                    <td colspan="5">
                                        @if ($trans->amountpaid)
                                            {{$trans->amountpaid}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Order Status</th>
                                    <td colspan="5">
                                        @if($trans->orders->order_status == 0)
                                            {{'------'}}
                                        @elseif($trans->orders->order_status == 1)
                                            {{'Order Placed'}}
                                        @elseif ($trans->orders->order_status == 2)
                                            {{'Order Dispatched'}}
                                        @elseif ($trans->orders->order_status == 3)
                                            {{'Order Delivered'}}
                                        @elseif ($trans->orders->order_status == 4)
                                            {{'Order Complete'}}
                                        @elseif ($trans->orders->order_status == 5)
                                            {{'Order Cancelled'}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th colspan="2">Contact Person</th>
                                    <td colspan="5">{{$trans->orders->contact_person}}</td>
                                </tr>
                                
                                <tr>
                                    <th colspan="2">Contact Number</th>
                                    <td colspan="5">{{$trans->orders->contact_no}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Shipping Address</th>
                                    <td colspan="5">{{$trans->orders->shipping_address}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Total Items</th>
                                    <td colspan="5">{{$trans->orders->total_items}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Sub Total</th>
                                    <td colspan="5">Rs. {{$trans->orders->total_amount}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Payment Mode</th>
                                    <td colspan="5">
                                        @if($trans->paymentmode == 1)
                                            {{'COD'}}
                                        @elseif($trans->paymentmode == 2)
                                            {{'Online Payment'}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Payment Method</th>
                                    <td colspan="5">
                                        @if($trans->paymentmode == 1)
                                            @if($trans->pay_method)
                                                {{$trans->pay_method}}
                                            @else
                                                {{'COD'}}
                                            @endif
                                        @elseif($trans->paymentmode == 2)
                                            @if($trans->pay_method)
                                                {{$trans->pay_method}}
                                            @else
                                                {{'------'}}
                                            @endif
                                        @else
                                            @if($trans->pay_method)
                                                {{$trans->pay_method}}
                                            @else
                                                {{'------'}}
                                            @endif
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Shipping Charge</th>
                                    <td colspan="5">
                                        @if($trans->orders->shipping_charge)
                                            {{'₹ '.$trans->orders->shipping_charge}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Net Amount</th>
                                    <td colspan="5">
                                        @if($trans->net_amount)
                                            {{'₹ '.$trans->net_amount}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Transaction Status</th>
                                    <td colspan="5">
                                        @if($trans->trans_status)
                                            {{$trans->trans_status}}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Remarks</th>
                                    <td colspan="5">
                                        @if($trans->remarks)
                                            {{$trans->remarks}}
                                        @else 
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="7"></th>
                                </tr>

                                @if(count($trans['orders_dets']) != 0) 
                                    <tr>
                                        <th>Title</th>
                                        <th>Product Code</th>
                                        <th>Product Add</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Tax</th>
                                        <th>Total</th>
                                    </tr>
                                    @foreach ($trans['orders_dets'] as $key => $value)
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
                                            <td>{{$value->unitprice}}</td>
                                            <td>{{$value->tax}}%</td>
                                            <td>{{$value->totalprice}}</td>
                                        </tr>
                                    @endforeach
                                   
                                    <tr>
                                        <th class="text-right" colspan="6"> Total</th>
                                        <td colspan="1">{{'₹ '.$trans->orders->total_amount}}</td>
                                    </tr>
                                     <tr>
                                        <th class="text-right" colspan="6">Shipping Charge</th>
                                        <td colspan="1">@if($trans->orders->shipping_charge)
                                            {{'₹ '.$trans->orders->shipping_charge}}
                                        @else
                                            {{'------'}}
                                        @endif</td>
                                    </tr>
                                     <tr>
                                        <th class="text-right" colspan="6">Sub Total</th>
                                        <td colspan="1">{{'₹ '.$trans->orders->net_amount}}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="gj_exp_but text-right">
                        <button class="btn btn-primary" onclick="Export()">Export</button>
                        
                        <a href="{{ route('all_transaction') }}"><button class="btn btn-info">Back</button></a>
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
        html2canvas(document.getElementById('gj_svw_trans_tbl'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download("view_transaction.pdf");
            }
        });
    }
</script>
@endsection
