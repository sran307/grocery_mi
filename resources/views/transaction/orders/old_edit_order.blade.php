@extends('layouts.master')
@section('title', 'Edit Orders')
@section('content')
<section class="gj_orders_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Orders  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Orders  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_orders','class'=>'gj_orders_form','files' => true)) }}
                        @if($orders)
                            {{ Form::hidden('orders_id', $orders->id, array('class' => 'form-control gj_orders_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('order_code', 'Order Code') }}
                            <span class="error"> 
                                @if ($errors->has('order_code'))
                                    {{ $errors->first('order_code') }}
                                @endif
                            </span>

                            <input class="form-control gj_order_code" placeholder="Order Code" name="h_order_code" type="text" value="{{$orders->order_code ? $orders->order_code : Input::old('order_code')}}" id="h_order_code" disabled>

                            <input class="form-control gj_order_code" placeholder="Order Code" name="order_code" type="hidden" value="{{$orders->order_code ? $orders->order_code : Input::old('order_code')}}" id="order_code">
                        </div>

                        <div class="form-group">
                            {{ Form::label('order_date', 'Order Date') }}
                            <span class="error">
                                @if ($errors->has('order_date'))
                                    {{ $errors->first('order_date') }}
                                @endif
                            </span>

                            <input class="form-control gj_order_date" placeholder="Order Code" name="h_order_date" type="text" value="{{$orders->order_date ? date('d-m-Y', strtotime($orders->order_date)) : Input::old('order_date')}}" id="h_order_date" disabled>

                            <input class="form-control gj_order_date" placeholder="Order Code" name="order_date" type="hidden" value="{{$orders->order_date ? $orders->order_date : Input::old('order_date')}}" id="order_date">

                            <input class="form-control gj_user_id" placeholder="User ID" name="user_id" type="hidden" value="{{$orders->user_id ? $orders->user_id : Input::old('user_id')}}" id="user_id">
                        </div>

                        <div class="form-group">
                            {{ Form::label('payment_mode', 'Payment Mode') }}
                            <span class="error">* 
                                @if ($errors->has('payment_mode'))
                                    {{ $errors->first('payment_mode') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($orders->payment_mode == 1) { echo "checked"; } ?> name="payment_mode" value="1"> Cash On Delivery
                                </span>

                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($orders->payment_mode == 2) { echo "checked"; } ?> name="payment_mode" value="2"> Online
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('delivery_date', 'Delivery Date') }}
                            <span class="error"> 
                                @if ($errors->has('delivery_date'))
                                    {{ $errors->first('delivery_date') }}
                                @endif
                            </span>

                            {{ Form::date('delivery_date', ($orders->delivery_date ? date('Y-m-d', strtotime($orders->delivery_date)) : Input::old('delivery_date')), array('class' => 'form-control gj_delivery_date','placeholder' => 'Delivery Date')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('order_status', 'Order Status') }}
                            <span class="error">* 
                                @if ($errors->has('order_status'))
                                    {{ $errors->first('order_status') }}
                                @endif
                            </span>

                            <select id="order_status" name="order_status" class="form-control gj_edt_order_status">
                                <option value="1" @if($orders->order_status == 1) {{'selected'}} @endif>Order Placed</option>
                                <option value="2" @if($orders->order_status == 2) {{'selected'}} @endif>Order Dispatched</option>
                                <option value="3" @if($orders->order_status == 3) {{'selected'}} @endif>Order Delivered </option>
                                <option value="4" @if($orders->order_status == 4) {{'selected'}} @endif>Order Complete</option>
                                <option value="5" @if($orders->order_status == 5) {{'selected'}} @endif>Order Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('contact_person', 'Contact Person') }}
                            <span class="error"> 
                                @if ($errors->has('contact_person'))
                                    {{ $errors->first('contact_person') }}
                                @endif
                            </span>

                            <input class="form-control gj_contact_person" placeholder="Contact Person" name="h_contact_person" type="text" value="{{$orders->contact_person ? $orders->contact_person : Input::old('contact_person')}}" id="h_contact_person" disabled>

                            <input class="form-control gj_contact_person" placeholder="Contact Person" name="contact_person" type="hidden" value="{{$orders->contact_person ? $orders->contact_person : Input::old('contact_person')}}" id="contact_person">
                        </div>

                        <div class="form-group">
                            {{ Form::label('contact_no', 'Contact Number') }}
                            <span class="error"> 
                                @if ($errors->has('contact_no'))
                                    {{ $errors->first('contact_no') }}
                                @endif
                            </span>

                            <input class="form-control gj_contact_no" placeholder="Contact Number" name="h_contact_no" type="text" value="{{$orders->contact_no ? $orders->contact_no : Input::old('contact_no')}}" id="h_contact_no" disabled>

                            <input class="form-control gj_contact_no" placeholder="Contact Number" name="contact_no" type="hidden" value="{{$orders->contact_no ? $orders->contact_no : Input::old('contact_no')}}" id="contact_no">
                        </div>

                        <div class="form-group">
                            {{ Form::label('shipping_address', 'Shipping Address') }}
                            <span class="error">
                                @if ($errors->has('shipping_address'))
                                    {{ $errors->first('shipping_address') }}
                                @endif
                            </span>

                            <input class="form-control gj_shipping_address" placeholder="Shipping Address" name="h_shipping_address" type="text" value="{{$orders->shipping_address ? $orders->shipping_address : Input::old('shipping_address')}}" id="h_shipping_address" disabled>

                            <input class="form-control gj_shipping_address" placeholder="Shipping Address" name="shipping_address" type="hidden" value="{{$orders->shipping_address ? $orders->shipping_address : Input::old('shipping_address')}}" id="shipping_address">
                        </div>

                        <div class="form-group">
                            {{ Form::label('total_items', 'Total Items') }}
                            <span class="error"> 
                                @if ($errors->has('total_items'))
                                    {{ $errors->first('total_items') }}
                                @endif
                            </span>

                            <input class="form-control gj_total_items" placeholder="Total Items" name="h_total_items" type="text" value="{{$orders->total_items ? $orders->total_items : Input::old('total_items')}}" id="h_total_items" disabled>

                            <input class="form-control gj_total_items" placeholder="Total Items" name="total_items" type="hidden" value="{{$orders->total_items ? $orders->total_items : Input::old('total_items')}}" id="total_items">
                        </div>

                        <div class="form-group">
                            {{ Form::label('discount_flag', 'Discount Flag') }}
                            <span class="error"> 
                                @if ($errors->has('discount_flag'))
                                    {{ $errors->first('discount_flag') }}
                                @endif
                            </span>

                            {{ Form::number('discount_flag', ($orders->discount_flag ? $orders->discount_flag : Input::old('discount_flag')), array('class' => 'form-control gj_discount_flag','placeholder' => 'Discount Flag')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('discount', 'Discount') }}
                            <span class="error"> 
                                @if ($errors->has('discount'))
                                    {{ $errors->first('discount') }}
                                @endif
                            </span>

                            {{ Form::number('discount', ($orders->discount ? $orders->discount : Input::old('discount')), array('class' => 'form-control gj_discount','placeholder' => 'Discount')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('shipping_charge', 'Shipping Charge') }}
                            <span class="error"> 
                                @if ($errors->has('shipping_charge'))
                                    {{ $errors->first('shipping_charge') }}
                                @endif
                            </span>

                            <input class="form-control gj_shipping_charge" placeholder="Shipping Charge" name="h_shipping_charge" type="text" value="{{($orders->shipping_charge ? $orders->shipping_charge : Input::old('shipping_charge'))}}" id="h_shipping_charge" disabled>

                            {{ Form::hidden('shipping_charge', ($orders->shipping_charge ? $orders->shipping_charge : Input::old('shipping_charge')), array('class' => 'form-control gj_shipping_charge','placeholder' => 'Shipping Charge')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('net_amount', 'Net Amount') }}
                            <span class="error"> 
                                @if ($errors->has('net_amount'))
                                    {{ $errors->first('net_amount') }}
                                @endif
                            </span>

                            <input class="form-control gj_net_amount" placeholder="Net Amount" name="h_net_amount" type="text" value="{{($orders->net_amount ? $orders->net_amount : Input::old('net_amount'))}}" id="h_net_amount" disabled>

                            {{ Form::hidden('net_amount', ($orders->net_amount ? $orders->net_amount : Input::old('net_amount')), array('class' => 'form-control gj_net_amount','placeholder' => 'Net Amount')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('payment_status', 'Payment Status') }}
                            <span class="error"> 
                                @if ($errors->has('payment_status'))
                                    {{ $errors->first('payment_status') }}
                                @endif
                            </span>

                            <select id="payment_status" name="payment_status" class="form-control gj_edt_payment_status">
                                <option value="1" @if($orders->payment_status == 0) {{'selected'}} @endif>Pending</option>
                                <option value="2" @if($orders->payment_status == 1) {{'selected'}} @endif>Success</option>
                                <option value="3" @if($orders->payment_status == 2) {{'selected'}} @endif>Failed </option>
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('delivery_status', 'Delivery Status') }}
                            <span class="error"> 
                                @if ($errors->has('delivery_status'))
                                    {{ $errors->first('delivery_status') }}
                                @endif
                            </span>

                            <select id="delivery_status" name="delivery_status" class="form-control gj_edt_delivery_status">
                                <option value="1" @if($orders->delivery_status == 0) {{'selected'}} @endif>Pending</option>
                                <option value="2" @if($orders->delivery_status == 1) {{'selected'}} @endif>Success</option>
                                <option value="3" @if($orders->delivery_status == 2) {{'selected'}} @endif>Failed </option>
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('remarks', 'Remarks') }}
                            <span class="error">* 
                                @if ($errors->has('remarks'))
                                    {{ $errors->first('remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('remarks', ($orders->remarks ? $orders->remarks : Input::old('remarks')), array('class' => 'form-control gj_remarks','placeholder' => 'Remarks','rows' => '5')) }}
                        </div>

                        @if(count($orders['details']) != 0)
                            <div class="gj_odr_det_resp table-responsive">
                                <div class="gj_tot_err">
                                    @if ($errors->has('det_product_title'))
                                        <p class="error"> 
                                            {{ $errors->first('det_product_title') }}
                                        </p>
                                    @endif

                                    @if ($errors->has('det_order_qty'))
                                        <p class="error"> 
                                            {{ $errors->first('det_order_qty') }}
                                        </p>
                                    @endif
                                </div>
                                <table class="table table-stripped table-bordered gj_tab_odr_det">
                                    <thead>
                                        <tr>
                                            <th>Product Title</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gj_odr_det">
                                        @if(isset($orders['details']) && (count($orders['details']) != 0))
                                            @foreach ($orders['details'] as $key => $value)
                                                <tr id="gj_tr_det_{{$key+1}}">
                                                    <td>
                                                        <input type="hidden" name="det_order_id[]" class="det_order_id" value="{{$value->order_id}}" placeholder="Enter Order ID">

                                                        <input type="hidden" name="det_product_id[]" class="det_product_id" value="{{$value->product_id}}" placeholder="Enter Product ID">

                                                        <input type="text" name="det_product_title[]" class="det_product_title" value="{{$value->product_title}}" placeholder="Enter Product Title">
                                                    </td>

                                                    <td>
                                                        <input type="number" name="det_order_qty[]" class="det_order_qty" value="{{$value->order_qty}}" placeholder="Enter Quantity" min="1">
                                                    </td>

                                                    <td>
                                                        <input type="text" name="det_h_unitprice[]" class="det_h_unitprice" value="{{$value->unitprice}}" placeholder="Enter Price" disabled>

                                                        <input type="hidden" name="det_unitprice[]" class="det_unitprice" value="{{$value->unitprice}}" placeholder="Enter Price">
                                                    </td>

                                                    <td>
                                                        <input type="text" name="det_h_totalprice[]" class="det_h_totalprice" value="{{$value->totalprice}}" placeholder="Enter Total Price" disabled>

                                                        <input type="hidden" name="det_totalprice[]" class="det_totalprice" value="{{$value->totalprice}}" placeholder="Enter Total Price">

                                                        <input type="hidden" name="tot_service_charge[]" class="gj_det_sc" value="{{($value->product_id ? $value->Products->service_charge : 0)}}">

                                                        <input type="hidden" name="tot_shipping_charge[]" class="gj_det_spc" value="{{($value->product_id ? $value->Products->shiping_charge : 0)}}">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger gj_del_det" data-del-id="{{$value->id}}"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr id="gj_tr_det_1">
                                                <td>
                                                    <input type="hidden" name="det_order_id[]" class="det_order_id" value="" placeholder="Enter Order ID">

                                                    <input type="hidden" name="det_product_id[]" class="det_product_id" value="" placeholder="Enter Product ID">

                                                    <input type="text" name="det_product_title[]" class="det_product_title" value="" placeholder="Enter Product Title">
                                                </td>

                                                <td>
                                                    <input type="number" name="det_order_qty[]" class="det_order_qty" value="" placeholder="Enter Quantity" min="1">
                                                </td>

                                                <td>
                                                    <input type="text" name="det_h_unitprice[]" class="det_h_unitprice" value="" placeholder="Enter Price" disabled>

                                                    <input type="hidden" name="det_unitprice[]" class="det_unitprice" value="" placeholder="Enter Price">
                                                </td>

                                                <td>
                                                    <input type="text" name="det_h_totalprice[]" class="det_h_totalprice" value="" placeholder="Enter Total Price" disabled>

                                                    <input type="hidden" name="det_totalprice[]" class="det_totalprice" value="" placeholder="Enter Total Price">

                                                    <input type="hidden" name="det_service_charge[]" class="gj_det_sc" value="">
                                                </td>

                                                <td>
                                                    <button type="button" class="btn btn-danger gj_del_det" data-del-id=""><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="3" class="text-right"> <b> Sub Total </b> </td>
                                            <td colspan="2" class="text-center">  <b> <span class="money"> ??? <span class="gj_det_sub_tot">0.00</span> </span> </b> </td>

                                            <input type="hidden" name="det_total_items" id="det_total_items">
                                            <input type="hidden" name="det_net_amount" id="det_net_amount">
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right"> <b> Shipping Charge </b> </td>
                                            <td colspan="2" class="text-center">  <b> <span class="money"> ??? <span class="gj_det_sc_tot">{{($orders->shipping_charge ? $orders->shipping_charge : '0.00')}}</span> </span> </b> </td>
                                            <input type="hidden" name="det_shipping_charge" id="det_shipping_charge">
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right"> <b> Grand Total </b> </td>
                                            <td colspan="2" class="text-center">  <b> <span class="money"> ??? <span class="gj_det_grand_tot">0.00</span> </span> </b> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="gj_edt_odr_nb text-right">
                                <button type="button" class="btn btn-info" id="gj_add_odr_det">Add New</button>
                            </div>
                        @else
                            <p class="gj_no_data">Products Not Found</p> 
                        @endif


                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>

                <div class="modal fade gj_srh_prd_mdl" id="myModal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Change Products</h4>
                            </div>
                            <div class="modal-body">
                                <div class="gj_mdl_sdiv">
                                    <select name="gj_srh_prd" id="gj_srh_prd" class="gj_srh_prd">
                                        <option value=""> Select Products </option>
                                        @if(count($orders['products']) != 0)
                                            @foreach ($orders['products'] as $key => $value)
                                                <option value="{{$value->id}}"> {{$value->product_title}} </option>
                                                <option value="{{$value->id}}"> {{$value->product_code}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" name="gj_apply_det" id="gj_apply_det" class="gj_apply_det">
                                    <button type="button" id="gj_srh_prd_btn" class="gj_srh_prd_btn btn btn-primary"><i class="fa fa-search"></i></button>
                                </div>

                                <div class="gj_cge_det_resp table-responsive"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default closed" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function sum() {
        var sum = 0;
        var gj = 0;
        var sc = 0;
        
        $(".det_totalprice").each(function() {
            var value = $(this).val();
            if(!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
            }
        });

        $(".gj_det_sc").each(function() {
            var value = $(this).val();
            if(!isNaN(value) && value.length != 0) {
                sc += parseFloat(value);
            }
        });

        $(".det_order_qty").each(function() {
            var value = $(this).val();
            if(!isNaN(value) && value.length != 0) {
                gj += parseFloat(value);
            }
        });

        var tot = sum + sc;
        sum = (sum).toFixed(2);
        sc = (sc).toFixed(2);
        $('.gj_det_sub_tot').html(sum);
        $('.gj_det_sc_tot').html(sc);
        $('#det_shipping_charge').val(sc);
        $('.gj_shipping_charge').val(sc);
        $('#det_total_items').val(gj);
        $('.gj_total_items').val(gj);

        tot = (tot).toFixed(2);
        $('.gj_det_grand_tot').html(tot);
        $('#det_net_amount').val(tot);
        $('.gj_net_amount').val(tot);
    }

    function closed () {
        $('#myModal').modal('hide');
        $("#gj_srh_prd").select2("val", "");
        $('.gj_cge_det_resp').html('');   
    }

    $(document).ready(function() {
        $('p.alert').delay(2000).slideUp(300); 
        $("#gj_srh_prd").select2();

        sum();
    });

    $('body').on('change','.det_order_qty',function() {
        var id = $(this).closest('tr').find('.det_product_id').val();
        var qty = 1;
        var price = 0;
        var tax = 0;
        var tax_type = 0;
        var total = 0.00;
        var hm = $(this);

        if($(this).val() == 0) {
            var qty = 1;
            $(this).val(qty);
        } else {
            var qty = $(this).val();
        }

        if($(this).closest('tr').find('.det_unitprice').val()) {
          var price = parseFloat($(this).closest('tr').find('.det_unitprice').val());
        }   

        if(id && id != 0 && price != 0) {
            $.ajax({
                type: 'post',
                url: '{{url('/check_tax')}}',
                data: {id: id, price: price, type: 'check_tax'}, 
                dataType:"json",  
                success: function(data){
                    if(data != 0) {
                        price = data;
                        $.ajax({
                            type: 'post',
                            url: '{{url('/check_onhand_qty')}}',
                            data: {id: id, qty: qty, price: price, type: 'check_onhand_qty'},       
                            dataType:"json",   
                            success: function(data){
                              if(data['error'] == 2){
                                    $.confirm({
                                        title: '',
                                        content: 'Out of Stock. Only ' + data['onhand_qty'] + ' Products Avaliable!',
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
                                    $(hm).val(1);
                                    data = (price * $(hm).val()).toFixed(2);
                                    $(hm).closest('tr').find('.det_h_totalprice').val(data);
                                    $(hm).closest('tr').find('.det_totalprice').val(data);
                                    sum();
                                } else if (data != 1) {
                                    data = (data).toFixed(2);
                                    $(hm).closest('tr').find('.det_h_totalprice').val(data);
                                    $(hm).closest('tr').find('.det_totalprice').val(data);
                                    sum();
                                } else {
                                    $(hm).val('1');
                                    data = (price * $(hm).val()).toFixed(2);
                                    $(hm).closest('tr').find('.det_h_totalprice').val(data);
                                    $(hm).closest('tr').find('.det_totalprice').val(data);
                                    sum();
                                }
                            }
                        }); 
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Change The Quantity in Another Time!',
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

    $('body').on('click','.close',function() {
        closed();
    });

    $('body').on('click','.closed',function() {
        closed();
    });

    $('body').on('click','.det_product_title',function() {
        id = $(this).closest('tr').attr('id');
        $('#gj_apply_det').val(id);
        $('#myModal').modal('show');
    });

    $('body').on('click','#gj_srh_prd_btn',function() {
        if($('#gj_srh_prd').val() && $('#gj_srh_prd').val() != 0) {
            var id = $('#gj_srh_prd').val();
            $.ajax({
                type: 'post',
                url: '{{url('/srh_products')}}',
                data: {id: id, type: 'srh_products'}, 
                dataType:"json",  
                success: function(data){
                    if(data['error'] == 0) {
                        $('.gj_cge_det_resp').html(data['table']);
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Could Not Found This Products!',
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
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Valid Products!',
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
    });

    $('body').on('click','.gj_aly_det_btn',function() {
        if($(this).attr('data-apply-id') && $(this).attr('data-apply-id') != 0) {
            var id = $(this).attr('data-apply-id');
            $.ajax({
                type: 'post',
                url: '{{url('/apply_products')}}',
                data: {id: id, type: 'apply_products'}, 
                dataType:"json",  
                success: function(data){
                    if(data['error'] == 0) {
                        var inc = $('#gj_apply_det').val();                 
                        $('#' + inc).html(data['table']);
                        $('#myModal').modal('hide');
                        $("#gj_srh_prd").select2("val", "");
                        $('.gj_cge_det_resp').html('');
                        sum();
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Could Not Found This Products!',
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
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Valid Products!',
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
    });
</script>

<script type="text/javascript">
    var cnt = <?php echo count($orders['details']) + 1;?>;
    $("#gj_add_odr_det").click(function () {
        var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'gj_tr_det_' + cnt);
        newTextBoxDiv.after().html('<td><input type="hidden" name="det_order_id[]" class="det_order_id" value="" placeholder="Enter Order ID"><input type="hidden" name="det_product_id[]" class="det_product_id" value="" placeholder="Enter Product ID"><input type="text" name="det_product_title[]" class="det_product_title" value="" placeholder="Enter Product Title"></td><td><input type="number" name="det_order_qty[]" class="det_order_qty" value="" placeholder="Enter Quantity" min="1"></td><td><input type="text" name="det_h_unitprice[]" class="det_h_unitprice" value="" placeholder="Enter Price" disabled><input type="hidden" name="det_unitprice[]" class="det_unitprice" value="" placeholder="Enter Price"></td><td><input type="text" name="det_h_totalprice[]" class="det_h_totalprice" value="" placeholder="Enter Total Price" disabled><input type="hidden" name="det_totalprice[]" class="det_totalprice" value="" placeholder="Enter Total Price"><input type="hidden" name="det_service_charge[]" class="gj_det_sc" value=""></td><td><button type="button" class="btn btn-danger gj_del_det" data-del-id=""><i class="fa fa-trash"></i></button></td>');
        newTextBoxDiv.prependTo("#gj_odr_det");
        cnt++;
    });

    $('body').on('click','.gj_del_det',function() {
        if(cnt==1){
            $.confirm({
                title: '',
                content: 'No more items to remove!',
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
            return false;
        }   
    
        cnt--;
        $(this).closest('tr').remove();
        if($(this).attr('data-del-id')) {
            var id = $(this).attr('data-del-id');
            $.ajax({
                type: 'post',
                url: '{{url('/delete_odr_det')}}',
                data: {id: id, type: 'delete_odr_det'},   
                success: function(data){
                    if(data == 1) {
                        sum();
                        $.confirm({
                            title: '',
                            content: 'Deleted Successfully!',
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
