@extends('layouts.master')
@section('title', 'View Credit Notes')
@section('content')
<link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/inv_style.css')}}">
<section class="gj_cn_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> View Credit Notes  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark" id="gj_svw_cn_tbl">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> View Credit Notes  </h5>
                </header>

                <div class="col-md-12">
                    @if($cn) 
                        <div id="container">
                            <div id="gj_svw_odr_tbl">
                                <section id="memo">
                                    <div class="logo">
                                        @if($logo)
                                            @if($logo->logo_image)
                                                <img data-logo="company_logo" src="{{ asset('images/logo/'.$logo->logo_image)}}" alt="Logo">
                                            @else
                                                <img data-logo="company_logo" src="{{ asset('images/logo.png')}}" alt="Logo">
                                            @endif
                                        @else
                                            <img data-logo="company_logo" src="{{ asset('images/logo.png')}}" alt="Logo">
                                        @endif
                                    </div>

                                    <div class="company-info">
                                        @if($contact)
                                            <span class="ibcl_company_name">{{$contact->contact_name}}</span>

                                            <div class="separator less"></div>

                                            <span class="ibcl_company_address">{{$contact->address1}}, {{$contact->address2}}</span>

                                            <span class="ibcl_company_city_zip_state">{{$contact->City->city_name}}, {{$contact->State->state}}, {{$contact->Country->country_name}} - {{$contact->pincode}}</span>

                                            <br>

                                            <span class="ibcl_company_email_web"><a href="{{route('home')}}" target="_blank">{{route('home')}}</a></span>

                                            <span class="ibcl_company_phone_fax">{{$contact->contact_phone1}},  {{$contact->contact_phone2}}</span>
                                        @else
                                            <span class="ibcl_company_name">ECambiar</span>

                                            <div class="separator less"></div>

                                            <span class="ibcl_company_address">www.ecambiar.com, Kerala</span>

                                            <span class="ibcl_company_city_zip_state">Kollam, Kerala, India - 560087</span>

                                            <br>

                                            <span class="ibcl_company_email_web">https://www.ecambiar.com/</span>

                                            <span class="ibcl_company_phone_fax">+91 7902506918, +916282467126</span>
                                        @endif
                                    </div>
                                </section>

                                <section id="invoice-title-number">
                                    <span id="title" class="ibcl_invoice_title">INVOICE</span>
                                    <div class="separator"></div>
                                    <span id="number" class="ibcl_invoice_number">{{$cn->cn_code}}</span>
                                </section>
                                  
                                <div class="clearfix"></div>
          
                                <section id="invoice-info">
                                    <div>
                                        <span class="ibcl_issue_date_label">Date:</span>
                                        <span class="ibcl_due_date_label">Issue Date:</span>
                                        <span class="ibcl_net_term_label">Net:</span>
                                        <span class="ibcl_po_number_label">GRV Code</span>
                                        <span class="ibcl_po_number_label">Order Code</span>
                                        <span class="ibcl_po_number_label">Status</span>
                                        <span class="ibcl_po_number_label">Remarks</span>
                                    </div>
                                    
                                    <div>
                                        <span class="ibcl_issue_date">{{date('d-F-Y')}}</span>
                                        <span class="ibcl_due_date">{{date('d-F-Y', Strtotime($cn->date))}}</span>
                                        <span class="ibcl_net_term">Rs. {{$cn->amount}}</span>
                                        <span class="ibcl_po_number">
                                            @if($cn->grv_id)
                                                @if($cn->GRV->grv_code)
                                                    {{$cn->GRV->grv_code}}
                                                @else 
                                                    ######
                                                @endif
                                            @else 
                                                ######
                                            @endif
                                        </span>
                                        <span class="ibcl_po_number">
                                            @if($cn->grv_id)
                                                @if($cn->GRV->Orders->order_code)
                                                    {{$cn->GRV->Orders->order_code}}
                                                @else 
                                                    ######
                                                @endif
                                            @else 
                                                ######
                                            @endif
                                        </span>
                                        <span class="ibcl_net_term">{{$cn->is_paid}}</span>
                                        <span class="ibcl_remark_term">{{$cn->remarks}}</span>
                                    </div>
                                </section>
          
                                <section id="client-info">
                                    <span class="ibcl_bill_to_label">Bill to:</span>
                                    @if($cn->grv_id)
                                        <div>
                                            <span class="client-name ibcl_client_name">
                                                @if($cn->GRV->Orders->contact_person)
                                                    {{$cn->GRV->Orders->contact_person}}
                                                @else 
                                                    ######
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div>
                                            <span class="ibcl_client_address">
                                                @if($cn->GRV->Orders->contact_email)
                                                    {{$cn->GRV->Orders->contact_email}}
                                                @else 
                                                    ######
                                                @endif
                                            </span>
                                        </div>

                                        <div>
                                            <span class="ibcl_client_phone_fax">
                                                @if($cn->GRV->Orders->contact_no)
                                                    {{$cn->GRV->Orders->contact_no}}
                                                @else 
                                                    ######
                                                @endif
                                            </span>
                                        </div>

                                        <div>
                                            <span class="ibcl_client_address">
                                                @if($cn->GRV->Orders->shipping_address)
                                                    {{$cn->GRV->Orders->shipping_address}}
                                                @else 
                                                    ######
                                                @endif
                                            </span>
                                        </div>

                                        <div>
                                            <span class="ibcl_client_city_zip_state">
                                                @if($cn->GRV->Orders->pincode)
                                                    {{$cn->GRV->Orders->pincode}}
                                                @else 
                                                    ######
                                                @endif
                                            </span>
                                        </div>
                                    @else 
                                        ######
                                    @endif
                                </section>
                                  
                                <div class="clearfix"></div>
                                
                                @if(sizeof($grv_details) != 0)
                                    <section id="items">
                                        <table cellpadding="0" cellspacing="0">        
                                            <tbody>
                                                <tr>
                                                    <th class="ibcl_item_row_number_label"></th>
                                                    <th class="ibcl_item_description_label">Item</th>
                                                    <th class="ibcl_item_description_label">Item Code</th>
                                                    <th class="ibcl_item_quantity_label">Quantity</th>
                                                    <th class="ibcl_item_price_label">Price</th>
                                                    <th class="ibcl_item_price_label">Tax</th>
                                                    <th class="ibcl_item_price_label">Total</th>
                                                </tr>

                                                @php ($i = 1)
                                                @foreach ($grv_details as $grvkey => $grvval)
                                                    <tr data-iterate="item">
                                                        <td class="ibcl_item_row_number">{{$i}}</td>

                                                        <td><span class="ibcl_item_description">
                                                            {{$grvval->product_title}}

                                                            @if(isset($grvval->att_name) && $grvval->att_name != 0)
                                                                @if(isset($grvval->AttName->att_name) && isset($grvval->AttValue->att_value)) 
                                                                    <span>({{$grvval->AttName->att_name}} : {{$grvval->AttValue->att_value}})</span>
                                                                @endif
                                                            @endif
                                                        </span></td>

                                                        <td>
                                                            <span class="ibcl_item_description">
                                                                @if(isset($grvval->Products->product_code))
                                                                    {{$grvval->Products->product_code}}
                                                                @else
                                                                    {{'-----'}}
                                                                @endif
                                                            </span>
                                                        </td>

                                                        <td><span class="ibcl_item_quantity">{{$grvval->return_qty}}</span></td>

                                                        <td><span class="ibcl_item_price add_currency_left">Rs. {{$grvval->return_amount}}</span></td>

                                                        <td><span class="ibcl_item_tax ib_item_percentage">{{$grvval->tax}}%</span></td>

                                                        <td>Rs. <span class="ibcl_item_line_total det_totalprice"> {{$grvval->return_qty * $grvval->return_amount}}</span></td>
                                                    </tr>

                                                    @php ($i = $i+1)
                                                @endforeach
                                          
                                            </tbody>
                                        </table>
                                    </section>
              
                                    <section id="sums">      
                                        <table cellpadding="0" cellspacing="0">
                                            <tbody>          
                                                <tr class="amount-total">
                                                    <th class="ibcl_amount_total_label">Total:</th>
                                                    <td class="ibcl_amount_total">Rs. <span class="gj_net_amount">0.00</span></td>
                                                </tr>          
                                            </tbody>
                                        </table>
                                    </section>
                                @else
                                    <p class="gj_no_data">Data not Found</p>
                                @endif
                                  
                                <div class="clearfix"></div>
                                  
                                <!-- <section id="terms">      
                                    <span class="hidden ibcl_terms_label">Terms &amp; Notes</span>

                                    <div class="ibcl_terms">Fred, thank you very much. We really appreciate your business.<br>Please send payments before the due date.</div>
                                </section> -->

                                <div class="bottom-circles">
                                    <section>
                                        <div>
                                            <div></div>
                                        </div>
                                        <div>
                                            <div>
                                                <div>
                                                    <div></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="gj_exp_but text-right gj_vw_cn_div">
                        <button class="btn btn-primary" onclick="Export()">Export</button>
                        
                        <a href="{{ route('manage_credit_notes') }}"><button class="btn btn-info">Back</button></a>
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
                pdfMake.createPdf(docDefinition).download("view_credit_notes.pdf");
            }
        });
    }
</script>

<script>
    function gj_round(value, decPlaces) {
      var val = value * Math.pow(10, decPlaces);
      var fraction = (Math.round((val - parseInt(val)) * 10) / 10);

      // -342.055 => -342.06
      if (fraction == -0.5) fraction = -0.6;

      val = Math.round(parseInt(val) + fraction) / Math.pow(10, decPlaces);
      return val;
    }

    function sum() {
        var sum = 0;
        
        $(".det_totalprice").each(function() {
            var value = $(this).html();
            if(!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
            }
        });

        sum = gj_round(sum, 2);

        $('.gj_net_amount').html(sum);
    }

    $(document).ready(function() {
        $('p.alert').delay(5000).slideUp(500); 

        sum();
    });
</script>
@endsection