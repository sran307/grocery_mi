@extends('layouts.master')
@section('title', 'View cashoutout')
@section('content')

<?php $log = session()->get('user'); ?>

<section class="gj_vw_cashoutouts">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View cashoutout  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark" id="gj_svw_cashout_tbl">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View cashoutout  </h5>
                </header>

                <div class="col-md-12">                    
                    <div class="gj_box dark gj_inside_box">
                        <header>
                            <h5 class="gj_heading"> cashoutout Details  </h5>
                        </header>
                        
                        <div class="col-md-12">
                            @if($cashout)
                                <div class="table-responsive gj_vw_cashout_mn_res">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Vendor</th>
                                            <td>
                                                @if($cashout->vendor)
                                                    @if(isset($cashout->Vendors->first_name))
                                                        {{$cashout->Vendors->first_name.' '.$cashout->Vendors->last_name}}
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Process Type</th>
                                            <td>{{$cashout->process_type}}</td>
                                        </tr>

                                        <tr>
                                            <th>Amount</th>
                                            <td>Rs. {{$cashout->amount}}</td>
                                        </tr>

                                        <tr>
                                            <th>Credits Notes</th>
                                            <td>
                                                @if($cashout->credit_note)
                                                    @if(isset($cashout->CNotes->cn_code))
                                                        {{$cashout->CNotes->cn_code}}
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Reasons</th>
                                            <td>{{$cashout->reasons}}</td>
                                        </tr>

                                        <tr>
                                            <th>Others</th>
                                            <td>{{$cashout->others}}</td>
                                        </tr>

                                        <tr>
                                            <th>Remarks</th>
                                            <td>{{$cashout->remarks}}</td>
                                        </tr>

                                        <tr>
                                            <th>Vendor Remarks</th>
                                            <td>{{$cashout->vendor_remarks}}</td>
                                        </tr>

                                        <tr>
                                            <th>Created Date</th>
                                            <td>{{date('d-m-Y', strtotime($cashout->created_at))}}</td>
                                        </tr>

                                        @if(isset($credits) && $credits)
                                            <tr>
                                                <th colspan="2" class="text-center"></th>
                                            </tr>

                                            <tr>
                                                <th colspan="2" class="text-center">Credits Details</th>
                                            </tr>

                                            <tr>
                                                <th>Vendor Previous Credits</th>
                                                <td>
                                                    @if($credits->previous_credits)
                                                        {{'Rs. '.$credits->previous_credits}}
                                                    @else
                                                        {{'Rs. '.'0.00'}}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Vendor Add / Deduct on Credits</th>
                                                <td>
                                                    @if($credits->add_credits)
                                                        {{'Rs. '.$credits->add_credits}}
                                                    @else
                                                        {{'Rs. '.'0.00'}}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Vendor Current Credits</th>
                                                <td>
                                                    @if($credits->current_credits)
                                                        {{'Rs. '.$credits->current_credits}}
                                                    @else
                                                        {{'Rs. '.'0.00'}}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Vendor Credits Remarks</th>
                                                <td>
                                                    @if($credits->remarks)
                                                        {{$credits->remarks}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="gj_exp_but text-right">
                        <button class="btn btn-primary" onclick="Export()">Export</button>
                        
                        <a href="{{ route('manage_admin_cashout') }}"><button class="btn btn-info">Back</button></a>
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
        html2canvas(document.getElementById('gj_svw_cashout_tbl'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download("view_cashout.pdf");
            }
        });
    }
</script>
@endsection
