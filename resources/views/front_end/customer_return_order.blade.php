@extends('layouts.frontend')
@section('title', 'Return Order')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
<section class="gj_rtn_odr_sec">
    <div class="main-content" id="MainContent">
        <div id="shopify-section-template-contact" class="shopify-section">
            <div class="container page-contact style1 ">
                <div class="row">
                    <div class="contact-form col-12">
                        <div class="wrap">
                            <h2 class="page-title">Return / Replacement Order</h2>
                            <div class="contact-des">
                            </div>
                            <div class="contact-form form-vertical">
                                {{ Form::open(array('url' => 'customer_return_order','class'=>'gj_rtn_odr_form','files' => true)) }}
                                    @if ($errors->any())
                                        <div class="error">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{$error}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if($order)
                                        <div class="row">
                                            <div class="col-md-6 col-12 col-sm-6">
                                                <div class="gj_rtn_l_div">
                                                    <span class="gj_frm_rtn1">Order Code : </span>
                                                    <span class="gj_frm_rtn2"> {{$order->order_code}}</span>
                                                    <input type="hidden" name="order_id" value="{{$order->id}}">
                                                    <input type="hidden" name="order_code" value="{{$order->order_code}}">
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12 col-sm-6">
                                                <div class="gj_rtn_r_div">
                                                    <span class="gj_frm_rtn1">Order Date : </span>
                                                    <span class="gj_frm_rtn2"> {{date('d-F-Y', strtotime($order->order_date))}}</span>
                                                    <input type="hidden" name="order_date" value="{{$order->order_date}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-12 col-sm-6">
                                                <div class="gj_rtn_l_div">
                                                    <span class="gj_frm_rtn1">Total Quantity : </span>
                                                    <span class="gj_frm_rtn2"> {{$order->total_items}}</span>
                                                    <input type="hidden" name="total_items" value="{{$order->total_items}}">
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12 col-sm-6">
                                                <div class="gj_rtn_r_div">
                                                    <span class="gj_frm_rtn1">Total Amount : </span>
                                                    <span class="gj_frm_rtn2"> Rs. {{$order->net_amount}}</span>
                                                    <input type="hidden" name="net_amount" value="{{$order->net_amount}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="gj_note">Note : First Select the Return or Replacement Items. Then Choose The Return Type and Enter Quantity, Reason, Remark and must upload return or replacement item damage image. </p>
                                                <div class="table-responsive gj_rtn_resp">
                                                    <table class="table table-bordered table-striped" id="gj_rtn_odr_table">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>#</th>
                                                                <th>Name</th>
                                                                <th>Qty</th>
                                                                <th>Price</th>
                                                                <th>Return Type</th>
                                                                <th>Return Qty</th>
                                                                <th>Return Amount</th>
                                                                <th>Reason</th>
                                                                <th>Remarks</th>
                                                                <th>image</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="gj_rtn_odr_bdy">
                                                            @if(sizeof($order->odr_dets) != 0)
                                                                @php ($i = 1)
                                                                
                                                                @foreach($order->odr_dets as $key => $value)
                                                                    <tr>
                                                                        <td>{{$i}}</td>
                                                                        <td>
                                                                            <input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" />
                                                                            <input type="hidden" name="det_id[]" class="gj_det_id">
                                                                        </td>
                                                                        <td>
                                                                            @if($value->product_title)
                                                                                <input type="hidden" name="product_title[]" class="gj_product_title" value="{{$value->product_title}}">
                                                                                {{$value->product_title}} 
                                                                                @if($value->att_name && $value->att_value)
                                                                                    @if($value->AttName->att_name && $value->AttValue->att_value)
                                                                                        ({{$value->AttName->att_name .' - '. $value->AttValue->att_value}})
                                                                                    @endif
                                                                                @endif
                                                                            @else
                                                                                {{'------'}}
                                                                            @endif
                                                                        </td>

                                                                        <td>
                                                                            @if($value->order_qty)
                                                                                <span class="order_qty">{{$value->order_qty}}</span>
                                                                            @else
                                                                                {{'------'}}
                                                                            @endif
                                                                        </td>

                                                                        <td>
                                                                            @if($value->totalprice)
                                                                                Rs. <span class="totalprice">{{$value->totalprice}}</span>
                                                                                <input type="hidden" name="unitprice[]" class="gj_unitprice" value="{{$value->unitprice}}">
                                                                            @else
                                                                                {{'------'}}
                                                                            @endif

                                                                            <input type="hidden" name="tax[]" class="gj_tax" value="{{$value->tax}}">
                                                                            <input type="hidden" name="old_tax_amount[]" class="gj_old_tax_amount" value="{{$value->tax_amount}}">
                                                                        </td>

                                                                        <!-- <td>
                                                                            @if($value->tax_amount)
                                                                                Rs. <span class="tax_amount">{{$value->tax_amount}}</span>
                                                                            @else
                                                                                {{'------'}}
                                                                            @endif
                                                                        </td> -->

                                                                        <td>
                                                                            <div class="gj_sel_rtn_div">
                                                                                <select name="return_type[]">
                                                                                    <option value="">Select Return Type</option>
                                                                                    <option value="Exchange">Exchange</option>
                                                                                    <option value="Replacement">Replacement</option>
                                                                                    <option value="Refund">Refund</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>

                                                                        <td>
                                                                            <input type="number" name="return_qty[]" class="return_qty" value="" placeholder="Enter Return Qty">
                                                                        </td>

                                                                        <td>
                                                                            <p> Rs. <span class="gj_rtn_amount">0.00</span></p>
                                                                            <input type="hidden" name="return_amount[]" class="return_amount" value="" placeholder="Enter Return Amount">

                                                                            <input type="hidden" name="return_tax_amount[]" class="return_tax_amount" value="" placeholder="Enter Return Tax Amount">
                                                                        </td>

                                                                        <!-- <td>
                                                                            <p> Rs. <span class="gj_rtn_tax_amount">0.00</span></p>
                                                                        </td> -->

                                                                        <td>
                                                                            <textarea name="reason[]" placeholder="Enter Reason"></textarea>
                                                                        </td>

                                                                        <td>
                                                                            <textarea name="remarks[]" placeholder="Enter Remarks"></textarea>
                                                                        </td>

                                                                        <td>
                                                                            <input type="file" name="rtn_image[]" id="rtn_image" accept="image/*" class="gj_rtn_image">
                                                                        </td>
                                                                    </tr>
                                                                    @php ($i = $i+1)
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <input type="submit" class="btn" value="Send">
                                    @else
                                        <p class="gj_no_data">Return or Replacement Order Not Possible!</p>
                                        <a href="{{route('my_account')}}/#Section4" class="btn btn-info pull-right">Back</a>
                                    @endif
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function gj_round(value, decPlaces) {
      var val = value * Math.pow(10, decPlaces);
      var fraction = (Math.round((val - parseInt(val)) * 10) / 10);

      // -342.055 => -342.06
      if (fraction == -0.5) fraction = -0.6;

      val = Math.round(parseInt(val) + fraction) / Math.pow(10, decPlaces);
      return val;
    }

    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $(".checkBoxClass").change(function(){
            if ($(this).prop("checked")){
                $(this).next().val($(this).val());
            } else {
                $(this).next().val('');
            }
        });
    });

    $(".return_qty").change(function() {
        var order_qty = $(this).closest('tr').find('.order_qty').text();
        var unitprice = 0.00;
        var unit_total = 0.00;
        var qty = 0;
        var price = 0.00;
        var tax = 0.00;

        if($(this).val()) {
            qty = parseInt($(this).val());
        }

        if($(this).closest('tr').find('.gj_unitprice').val()) {
           unitprice =  parseFloat($(this).closest('tr').find('.gj_unitprice').val());
        }

        if($(this).closest('tr').find('.gj_tax').val()) {
           tax =  $(this).closest('tr').find('.gj_tax').val();
        }

        if(parseInt(qty) == 0) {
            $.confirm({
                title: '',
                content: 'Please Enter Correct Value!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function() {
                        
                    }
                }
            });
            $(this).val('');
            $(this).closest('tr').find('.gj_rtn_amount').text(0.00);
            $(this).closest('tr').find('.return_amount').val(0.00);
            // $(this).closest('tr').find('.gj_rtn_tax_amount').text(0.00);
            // $(this).closest('tr').find('.return_tax_amount').val(0.00);
        } else if(parseInt(order_qty) >= parseInt(qty)) {
            price = parseInt(qty) * unitprice;
            // price = (price).toFixed(2);
            price = gj_round(price, 2);
            $(this).closest('tr').find('.gj_rtn_amount').text(price);
            $(this).closest('tr').find('.return_amount').val(price);
            // c_tax = ((unitprice * tax)/100) * parseInt(qty);
            // c_tax = gj_round(c_tax, 2);
            // $(this).closest('tr').find('.gj_rtn_tax_amount').text(c_tax);
            // $(this).closest('tr').find('.return_tax_amount').val(c_tax);
        } else {
            $.confirm({
                title: '',
                content: 'Return Quantity is Less Than or Equal to Order Quantity!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'purple',
                buttons: {
                    Ok: function() {
                        
                    }
                }
            });
            $(this).val('');
            $(this).closest('tr').find('.gj_rtn_amount').text(0.00);
            $(this).closest('tr').find('.return_amount').val(0.00);
            // $(this).closest('tr').find('.gj_rtn_tax_amount').text(0.00);
            // $(this).closest('tr').find('.return_tax_amount').val(0.00);
        }
    });
</script>
@endsection
