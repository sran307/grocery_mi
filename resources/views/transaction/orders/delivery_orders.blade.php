@extends('layouts.master')
@section('title', 'Delivery Orders')
@section('content')
<section class="gj_deli_orders_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Delivery Orders  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-truck"></i></div>
                    <h5 class="gj_heading"> Delivery Orders  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'delivery_orders','class'=>'gj_orders_form','files' => true)) }}
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
                            {{ Form::label('delivery_date', 'Delivery Date') }}
                            <span class="error"> 
                                @if ($errors->has('delivery_date'))
                                    {{ $errors->first('delivery_date') }}
                                @endif
                            </span>

                            {{ Form::date('delivery_date', ($orders->delivery_date ? date('Y-m-d', strtotime($orders->delivery_date)) : Input::old('delivery_date')), array('class' => 'form-control gj_delivery_date','placeholder' => 'Delivery Date','min' => $orders->order_date ? date('Y-m-d', strtotime($orders->order_date)) : Input::old('order_date'))) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('delivery_status', 'Delivery Status') }}
                            <span class="error"> 
                                @if ($errors->has('delivery_status'))
                                    {{ $errors->first('delivery_status') }}
                                @endif
                            </span>

                            <select id="delivery_status" name="delivery_status" class="form-control gj_edt_delivery_status">
                                <option value="0" @if($orders->delivery_status == 0) {{'selected'}} @endif>Pending</option>
                                <option value="1" @if($orders->delivery_status == 1) {{'selected'}} @endif>Success</option>
                                <option value="2" @if($orders->delivery_status == 2) {{'selected'}} @endif>Failed </option>
                            </select>
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
                            {{ Form::label('service_charge', 'Service Charge') }}
                            <span class="error"> 
                                @if ($errors->has('service_charge'))
                                    {{ $errors->first('service_charge') }}
                                @endif
                            </span>

                            <input class="form-control gj_service_charge" placeholder="Shipping Charge" name="h_service_charge" type="text" value="{{($orders->service_charge ? $orders->service_charge : Input::old('service_charge'))}}" id="h_service_charge" disabled>

                            {{ Form::hidden('service_charge', ($orders->service_charge ? $orders->service_charge : Input::old('service_charge')), array('class' => 'form-control gj_service_charge','placeholder' => 'Service Charge')) }}
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
                            {{ Form::label('remarks', 'Remarks') }}
                            <span class="error">* 
                                @if ($errors->has('remarks'))
                                    {{ $errors->first('remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('remarks', ($orders->remarks ? $orders->remarks : Input::old('remarks')), array('class' => 'form-control gj_remarks','placeholder' => 'Remarks','rows' => '5')) }}
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        $('p.alert').delay(5000).slideUp(500);
    });
</script>
@endsection
