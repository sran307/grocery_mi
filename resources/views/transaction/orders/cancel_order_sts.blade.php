@extends('layouts.master')
@section('title', 'Change Cancel Order Status')
@section('content')
<section class="gj_ccos_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Change Cancel Order Status  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Change Cancel Order Status  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'cancel_req_status','class'=>'gj_ccos_form','files' => true)) }}
                        @if($orders)
                            {{ Form::hidden('order_id', $orders->id, array('class' => 'form-control gj_odr_id')) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('order_code', 'Order Code') }}
                            <span class="error">* 
                                @if ($errors->has('order_code'))
                                    {{ $errors->first('order_code') }}
                                @endif
                            </span>

                            {{ Form::text('order_code', ($orders->order_code ? $orders->order_code : Input::old('order_code')), array('class' => 'form-control gj_order_code','placeholder' => 'Enter Order Code','readonly')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('cancel_remarks', 'Remarks') }}
                            <span class="error">* 
                                @if ($errors->has('cancel_remarks'))
                                    {{ $errors->first('cancel_remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('cancel_remarks', ($orders->cancel_remarks ? $orders->cancel_remarks : Input::old('cancel_remarks')), array('class' => 'form-control gj_cancel_remarks','placeholder' => 'Enter Cancel Order Remarks','rows' => '5')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('cancel_approved', 'Cancel Order Status') }}
                            <span class="error">* 
                                @if ($errors->has('cancel_approved'))
                                    {{ $errors->first('cancel_approved') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($orders->cancel_approved == 3) { echo "checked"; } ?> name="cancel_approved" value="3"> Process
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($orders->cancel_approved == 1) { echo "checked"; } ?> name="cancel_approved" value="1"> Accept
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($orders->cancel_approved == 2) { echo "checked"; } ?> name="cancel_approved" value="2"> Reject
                                </span>
                            </div>
                        </div>

                        {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
    });
</script>
@endsection
