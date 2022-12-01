@extends('layouts.master')
@section('title', 'Change Report Product Status')
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
                    <h5 class="gj_heading"> Change Report Product Status  </h5>
                </header>

                <div class="col-md-12">
                    {{ Form::open(array('url' => 'cancel_req_status','class'=>'gj_ccos_form','files' => true)) }}
                        @if($orders)
                            {{ Form::hidden('repor_id', $orders->id, array('class' => 'form-control gj_odr_id')) }}
                        @endif
                            {{ Form::hidden('btn_id',1, array('class' => 'form-control')) }}

                        <div class="form-group">
                            {{ Form::label('order_code', 'Product') }}
                            <span class="error">* 
                                @if ($errors->has('order_code'))
                                    {{ $errors->first('order_code') }}
                                @endif
                            </span>
                                    @php
                                    
                                     $pro=App\Products::find($orders->product_id);
                                    
                                    @endphp
                                    {{$pro->product_title}}
                        </div>
                        <div class="form-group">
                             @if($orders->upload_image1!=null)
                            <img src="{{asset('images/report_img/'.$orders->upload_image1)}}" width="150px" height="100px">
                             @endif
                            @if($orders->upload_image2!=null)
                             <img src="{{asset('images/report_img/'.$orders->upload_image2)}}" width="150px" height="100px">
                             @endif
                        </div> 
 <div class="form-group">
     
     
     <label>Order Id: {{$orders->Orders->order_code}}</label>
     </div>
     <div class="form-group">
     
     
     <label>Customer Name: {{$orders->Users->first_name}}</label>
     </div>
                        <div class="form-group">
                            {{ Form::label('cancel_remarks', 'Remarks') }}
                            <span class="error">* 
                                @if ($errors->has('cancel_remarks'))
                                    {{ $errors->first('cancel_remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('report_remarks', ($orders->cancel_remarks ? $orders->cancel_remarks : Input::old('cancel_remarks')), array('class' => 'form-control gj_cancel_remarks','placeholder' => 'Enter  Remarks','rows' => '5')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('report_status', 'Report Status') }}
                            <span class="error">* 
                                @if ($errors->has('report_status'))
                                    {{ $errors->first('report_status') }}
                                @endif
                            </span>

                            <div class="gj_py_ro_div">
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($orders->report_status == 3) { echo "checked"; } ?> name="report_status" value="3"> Reject
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($orders->report_status == 1) { echo "checked"; } ?> name="report_status" value="1"> Pending
                                </span>
                                <span class="gj_py_ro">
                                    <input type="radio" <?php if($orders->report_status == 2) { echo "checked"; } ?> name="report_status" value="2"> Take Action
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
