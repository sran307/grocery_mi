@extends('layouts.master')
@section('title', 'View Shipments')
@section('content')
<section class="gj_vw_shipments_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.courier_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View Shipments  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark" id="gj_svw_shipodr_tbl">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View Shipments  </h5>
                </header>

                <div class="col-md-12">
                    @if($shipodr) 
                        <div class="gj_shipodr table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th colspan="1">Order Code</th>
                                    <td colspan="3">{{$shipodr->order_code}}</td>
                                </tr>

                                <tr>
                                    <th colspan="1">Shipment ID</th>
                                    <td colspan="3">{{ $shipodr->shipment_id }}</td>
                                </tr>

                                <tr>
                                    <th colspan="1">Shipment Date</th>
                                    <td colspan="3">{{ date('d-m-Y', strtotime($shipodr->shipment_date)) }}</td>
                                </tr>

                                <tr>
                                    <th colspan="1">Shipment Value</th>
                                    <td colspan="3">Rs. {{$shipodr->value}}</td>
                                </tr>

                                <tr>
                                    <th colspan="1">Shipment Items Weight</th>
                                    <td colspan="3">{{$shipodr->weight}} Kg</td>
                                </tr>
                                
                                <tr>
                                    <th colspan="1">Shipping Type</th>
                                    <td colspan="3">{{$shipodr->type}}</td>
                                </tr>

                                <tr>
                                    <th colspan="1">Shipping Mode Type</th>
                                    <td colspan="3">{{$shipodr->mode_type}}</td>
                                </tr>

                                <tr>
                                    <th colspan="1">Shipping Carrier</th>
                                    <td colspan="3">{{$shipodr->carrier}}</td>
                                </tr>

                                <tr>
                                    <th colspan="1">Tracking ID</th>
                                    <td colspan="3">{{$shipodr->awb}}</td>
                                </tr>

                                <tr>
                                    <th colspan="1">Shiping Status</th>
                                    <td colspan="3">{{$shipodr->shiping_status}}</td>
                                </tr>

                                <tr>
                                    <th colspan="1">Delivery Charges</th>
                                    <td colspan="3">
                                        @if($shipodr->delivery_charges)
                                            {{ 'Rs. '.$shipodr->delivery_charges }}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="1">Delivery Date</th>
                                    <td colspan="3">
                                        @if($shipodr->delivery_date)
                                            {{ date('d-m-Y', strtotime($shipodr->delivery_date)) }}
                                        @else
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="1">Remarks</th>
                                    <td colspan="3">
                                        @if($shipodr->ship_remarks)
                                            {{$shipodr->ship_remarks}}
                                        @else 
                                            {{'------'}}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="gj_exp_but text-right">
                        <button class="btn btn-primary" onclick="Export()">Export</button>
                        
                        <a href="{{ route('shipment_order') }}"><button class="btn btn-info">Back</button></a>
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
        html2canvas(document.getElementById('gj_svw_shipodr_tbl'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download("view_shipment.pdf");
            }
        });
    }
</script>
@endsection
