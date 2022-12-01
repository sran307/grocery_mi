@extends('layouts.master')
@section('title', 'Edit Shipments')
@section('content')
<section class="gj_edt_shipments_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.courier_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Edit Shipments  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Edit Shipments  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'edit_shipment_order','class'=>'gj_shipodr_edt_form','files' => true)) }}
                        @if($shipodr)
                            {{ Form::hidden('shipodr_id', ($shipodr->id ? $shipodr->id : Input::old('id')), array('class' => 'form-control gj_shipodr_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('order_code', 'Order Code') }}
                            <span class="error">* 
                                @if ($errors->has('order_code'))
                                    {{ $errors->first('order_code') }}
                                @endif
                            </span>

                            {{ Form::text('order_code', ($shipodr->order_code ? $shipodr->order_code : Input::old('order_code')), array('class' => 'form-control gj_order_code','placeholder' => 'Order Code')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('shipment_id', 'Shipment ID') }}
                            <span class="error">* 
                                @if ($errors->has('shipment_id'))
                                    {{ $errors->first('shipment_id') }}
                                @endif
                            </span>

                            {{ Form::text('shipment_id', ($shipodr->shipment_id ? $shipodr->shipment_id : Input::old('shipment_id')), array('class' => 'form-control gj_shipment_id','placeholder' => 'Shipment ID')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('shipment_date', 'Shipment Date') }}
                            <span class="error">* 
                                @if ($errors->has('shipment_date'))
                                    {{ $errors->first('shipment_date') }}
                                @endif
                            </span>

                            {{ Form::date('shipment_date', ($shipodr->shipment_date ? $shipodr->shipment_date : Input::old('shipment_date')), array('class' => 'form-control gj_shipment_date ch_date','placeholder' => 'Shipment Date')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('value', 'Amount Value') }}
                            <span class="error">* 
                                @if ($errors->has('value'))
                                    {{ $errors->first('value') }}
                                @endif
                            </span>

                            {{ Form::text('value', ($shipodr->value ? $shipodr->value : Input::old('value')), array('class' => 'form-control gj_amt_value','placeholder' => 'Amount Value')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('weight', 'Weight (Kilo Gram)') }}
                            <span class="error">* 
                                @if ($errors->has('weight'))
                                    {{ $errors->first('weight') }}
                                @endif
                            </span>

                            {{ Form::text('weight', ($shipodr->weight ? $shipodr->weight : Input::old('weight')), array('class' => 'form-control gj_weight','placeholder' => 'Weight (Kilo Gram)')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('type', 'Type') }}
                            <span class="error">* 
                                @if ($errors->has('type'))
                                    {{ $errors->first('type') }}
                                @endif
                            </span>

                            {{ Form::text('type', ($shipodr->type ? $shipodr->type : Input::old('type')), array('class' => 'form-control gj_type','placeholder' => 'Type')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('mode_type', 'Mode Type') }}
                            <span class="error">* 
                                @if ($errors->has('mode_type'))
                                    {{ $errors->first('mode_type') }}
                                @endif
                            </span>

                            {{ Form::text('mode_type', ($shipodr->mode_type ? $shipodr->mode_type : Input::old('mode_type')), array('class' => 'form-control gj_mode_type','placeholder' => 'Mode Type')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('carrier', 'Carrier') }}
                            <span class="error">* 
                                @if ($errors->has('carrier'))
                                    {{ $errors->first('carrier') }}
                                @endif
                            </span>

                            {{ Form::text('carrier', ($shipodr->carrier ? $shipodr->carrier : Input::old('carrier')), array('class' => 'form-control gj_carrier','placeholder' => 'Carrier')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('awb', 'AWB') }}
                            <span class="error">* 
                                @if ($errors->has('awb'))
                                    {{ $errors->first('awb') }}
                                @endif
                            </span>

                            {{ Form::text('awb', ($shipodr->awb ? $shipodr->awb : Input::old('awb')), array('class' => 'form-control gj_awb','placeholder' => 'AWB')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('shiping_status', 'Shiping Status') }}
                            <span class="error">* 
                                @if ($errors->has('shiping_status'))
                                    {{ $errors->first('shiping_status') }}
                                @endif
                            </span>

                            {{ Form::text('shiping_status', ($shipodr->shiping_status ? $shipodr->shiping_status : Input::old('shiping_status')), array('class' => 'form-control gj_shiping_status','placeholder' => 'Shiping Status')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('delivery_charges', 'Delivery Charges') }}
                            <span class="error"> 
                                @if ($errors->has('delivery_charges'))
                                    {{ $errors->first('delivery_charges') }}
                                @endif
                            </span>

                            {{ Form::text('delivery_charges', ($shipodr->delivery_charges ? $shipodr->delivery_charges : Input::old('delivery_charges')), array('class' => 'form-control gj_delivery_charges','placeholder' => 'Delivery Charges')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('delivery_date', 'Delivery Date') }}
                            <span class="error"> 
                                @if ($errors->has('delivery_date'))
                                    {{ $errors->first('delivery_date') }}
                                @endif
                            </span>

                            {{ Form::date('delivery_date', ($shipodr->delivery_date ? $shipodr->delivery_date : Input::old('delivery_date')), array('class' => 'form-control gj_delivery_date ch_date','placeholder' => 'Delivery Date')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('ship_remarks', 'Remarks') }}
                            <span class="error"> 
                                @if ($errors->has('ship_remarks'))
                                    {{ $errors->first('ship_remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('ship_remarks', ($shipodr->ship_remarks ? $shipodr->ship_remarks : Input::old('ship_remarks')), array('class' => 'form-control gj_ship_remarks', 'rows' => '5','placeholder' => 'Remarks')) }}
                        </div> 

                        <div class="form-group">
                            {{ Form::label('courier_payment_status', 'Payment Status') }}
                            <span class="error">* 
                                @if ($errors->has('courier_payment_status'))
                                    {{ $errors->first('courier_payment_status') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($shipodr->courier_payment_status == 1) { echo "checked"; } ?> name="courier_payment_status" value="1"> Paid
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($shipodr->courier_payment_status == 0) { echo "checked"; } ?> name="courier_payment_status" value="0"> Un Paid
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('courier_payment_remarks', 'Payment Remarks') }}
                            <span class="error"> 
                                @if ($errors->has('courier_payment_remarks'))
                                    {{ $errors->first('courier_payment_remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('courier_payment_remarks', ($shipodr->courier_payment_remarks ? $shipodr->courier_payment_remarks : Input::old('courier_payment_remarks')), array('class' => 'form-control gj_courier_payment_remarks', 'rows' => '5','placeholder' => 'Courier Payment Remarks')) }}
                        </div>

                        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('p.alert').delay(2000).slideUp(300); 
</script>
@endsection
