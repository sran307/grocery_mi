@extends('layouts.master')
@section('title', 'View Cashout')
@section('content')

<?php $log = session()->get('user'); ?>

<section class="gj_vw_cashouts">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View Cashout  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark" id="gj_svw_cash_tbl">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View Cashout  </h5>
                </header>

                <div class="col-md-12">
                    @if($log)
                        @if($log->user_type == 1)
                            @if($cash)
                                <div class="gj_make_pay">
                                    <a href="{{ route('make_pay', ['id' => $cash->id]) }}" title="View"><button type="button" class="btn btn-success">Make Payment</button></a>
                                </div>
                            @endif
                        @endif
                    @endif
                    
                    <div class="gj_box dark gj_inside_box">
                        <header>
                            <h5 class="gj_heading"> Cashout Details  </h5>
                        </header>
                        
                        <div class="col-md-12">
                            @if($cash)
                                <div class="table-responsive gj_vw_cash_mn_res">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Request Code</th>
                                            <td>{{$cash->request_code}}</td>
                                        </tr>
                                        <tr>
                                            <th>Request Date</th>
                                            <td>{{date('d-m-Y', strtotime($cash->request_date))}}</td>
                                        </tr>
                                        <tr>
                                            <th>Request Amount</th>
                                            <td>Rs. {{$cash->request_amount}}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount Paid</th>
                                            <td>
                                                @if($cash->amount_paid)
                                                    Rs. {{$cash->amount_paid}}
                                                @else
                                                    Rs. {{'0.00'}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Balance Amount</th>
                                            <td>
                                                @if($cash->balance)
                                                    Rs. {{$cash->balance}}
                                                @else
                                                    Rs. {{'0.00'}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>No.of Invoice</th>
                                            <td>
                                                @if($cash->invoice)
                                                    {{$cash->invoice}}
                                                @else
                                                    {{'0'}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Merchants</th>
                                            <td>
                                                @if($cash->merchant_id)
                                                    @if($cash->CashMerchants->first_name)
                                                        {{$cash->CashMerchants->first_name.' '.$cash->CashMerchants->last_name}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($cash->paid_status)
                                                    {{$cash->paid_status}}
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Remarks</th>
                                            <td>
                                                @if($cash->remarks)
                                                    {{$cash->remarks}}
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($cash)
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Cashout Payment Details  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                @if(sizeof($cash->cash_pay) != 0)
                                    <div class="table-responsive gj_vw_cash_pay_res">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Bank Details</th>
                                                <th>Payment Details</th>
                                            </tr>
                                            @foreach($cash->cash_pay as $key => $value)
                                                <tr>
                                                    <td>
                                                        @if($value->bank)
                                                            <p>Merchant-Name : {{$value->CashoutBank->Merchants->first_name.' '.$value->CashoutBank->Merchants->last_name}}</p>

                                                            <p>A/C No : {{$value->CashoutBank->ac_no}}</p>

                                                            <p>A/C Holder Name : {{$value->CashoutBank->ac_name}}</p>

                                                            <p>Bank Name : {{$value->CashoutBank->bank_name}}</p>

                                                            <p>Branch Name : {{$value->CashoutBank->bank_branch}}</p>

                                                            <p>IFSC : {{$value->CashoutBank->bank_ifsc}}</p>
                                                        @else
                                                            <p>{{'-------'}}</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->pay_mode == 1)
                                                            <p>{{'Payment-Mode : Cheque'}}</p>
                                                            
                                                            @if($value->cheque_no)
                                                                <p>{{'Cheque - No : '.$value->cheque_no}}</p>
                                                            @endif

                                                            @if($value->bank_name)
                                                                <p>{{'Bank - Name : '.$value->bank_name}}</p>
                                                            @endif

                                                            @if($value->branch_name)
                                                                <p>{{'Branch - Name : '.$value->branch_name}}</p>
                                                            @endif

                                                            @if($value->cheque_img)
                                                                <?php $doc_file_path = 'cheque_img'; ?>
                                                                <p>
                                                                    <a href="{{ asset($doc_file_path.'/'.$value->cheque_img)}}" target="_blank" class="gj_receipt_doc"><embed src="{{ asset($doc_file_path.'/'.$value->cheque_img)}}"/></a>
                                                                </p>
                                                            @endif
                                                        @elseif($value->pay_mode == 2)
                                                            <p>{{'Payment-Mode : Bank Transfer'}}</p>

                                                            @if($value->receipt)
                                                                <?php $doc_file_path = 'receipt'; ?>
                                                                <p>
                                                                    <a href="{{ asset($doc_file_path.'/'.$value->receipt)}}" target="_blank" class="gj_receipt_doc"><embed src="{{ asset($doc_file_path.'/'.$value->receipt)}}"/></a>
                                                                </p>
                                                            @endif
                                                        @else
                                                            <p>{{'-------'}}</p>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @else
                                    <div class="gj_vw_not_att">
                                        <p>Data Not Available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($cash)
                        <div class="gj_box dark gj_inside_box">
                            <header>
                                <h5 class="gj_heading"> Cashout Requests  </h5>
                            </header>
                            
                            <div class="col-md-12">
                                @if($cash->cash && sizeof($cash->cash) != 0)
                                    <div class="table-responsive gj_vw_cash_res">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Request Code</th>
                                                <th>Order Code/Credit Notes Code</th>
                                                <th>Quantity</th>
                                                <th>Total Amount</th>
                                                <th>Product</th>
                                                <th>Merchant</th>
                                                <th>Admin Commision</th>
                                                <th>Vendor Amount</th>
                                            </tr>
                                            @foreach($cash->cash as $key => $value)
                                                <tr>
                                                    <td>
                                                        @if($value->request_code)
                                                            {{$value->Cashouts->request_code}}
                                                        @else
                                                            -----
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->type == "Order")
                                                            @if($value->order_code)
                                                                @if(isset($value->CashOrders->id))
                                                                    <a target="_blank" href="{{ route('view_orders', ['id' => $value->CashOrders->id]) }}">{{$value->order_code}}</a>
                                                                @else
                                                                    {{$value->order_code}}
                                                                @endif
                                                            @else
                                                                {{'------'}}
                                                            @endif
                                                        @elseif($value->type == "Credit Notes")
                                                            @if($value->cn_id)
                                                                @if(isset($value->CNotes->cn_code))
                                                                    <a target="_blank" href="{{ route('view_credit_notes', ['id' => $value->cn_id]) }}">{{$value->CNotes->cn_code}}</a>
                                                                @else
                                                                    {{'------'}}
                                                                @endif
                                                            @else
                                                                {{'------'}}
                                                            @endif
                                                        @else
                                                            {{'------'}}
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
                                                        @if($value->totalprice)
                                                            Rs. {{$value->totalprice}}
                                                        @else
                                                            Rs. 0.00
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->product_id)
                                                            @if(isset($value->CashProducts->product_title))
                                                                <a href="{{ route('view_product', ['id' => $value->product_id]) }}">
                                                                    {{$value->CashProducts->product_title}}

                                                                    @if(isset($value->att_name) && $value->att_name != 0)
                                                                        @if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) 
                                                                            <span>({{$value->AttName->att_name}} : {{$value->AttValue->att_value}})</span>
                                                                        @endif
                                                                    @endif

                                                                    <p>Product Code : {{$value->CashProducts->product_code}}</p>
                                                                </a>
                                                            @else
                                                                {{'------'}}
                                                            @endif
                                                        @else
                                                            {{'------'}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->merchant_id)
                                                            {{$value->CashMerchants->first_name.' '.$value->CashMerchants->last_name}}
                                                        @else
                                                            -----
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->comis_amount)
                                                            Rs. {{$value->comis_amount}}
                                                        @else
                                                            Rs. 0.00
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->vendor_amount)
                                                            Rs. {{$value->vendor_amount}}
                                                        @else
                                                            Rs. 0.00
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @else
                                    <div class="gj_vw_not_att">
                                        <p>Data Not Available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="gj_exp_but text-right">
                        <button class="btn btn-primary" onclick="Export()">Export</button>
                        
                        <a href="{{ route('manage_cashout') }}"><button class="btn btn-info">Back</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('p.alert').delay(5000).slideUp(500);
    });
</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript">
    function Export() {
        $('.gj_exp_but').hide();
        $('.gj_make_pay').hide();
        $('.gj_receipt_doc').hide();
        html2canvas(document.getElementById('gj_svw_cash_tbl'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download("view_cash.pdf");
            }
        });
    }
</script>
@endsection
