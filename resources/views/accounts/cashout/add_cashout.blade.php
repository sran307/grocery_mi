@extends('layouts.master')
@section('title', 'Add Cashout Request')
@section('content')

<?php $log = session()->get('user'); ?>

<section class="gj_chreq_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>
        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Add Cashout Request  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Add Cashout Request  </h5>
                </header>

                <div class="col-md-12">
                    <div class="gj_manage_filter gj_add_cho_dets">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="gj_l_div">
                                    <div class="gj_l_dets1">
                                        <label>Outstanding Balance 
                                            <span>
                                                @if($comis->{'outstand'})
                                                    {{'Rs. '.$comis->outstand}}
                                                @else
                                                    {{'Rs. 0.00'}}
                                                @endif
                                            </span>
                                        </label>
                                    </div>
                               
                                    <div class="gj_l_dets2">
                                        <label>Last Request Date <span>{{$comis->last_request_date}}</span></label>
                                    </div>
                                </div>
                            </div>         
                            <div class="col-md-6">
                                <div class="gj_r_div text-right">
                                    <div class="gj_r_dets1">
                                        <label>Last Request Amount <span>Rs.{{$comis->last_request_amount}}</span></label>
                                    </div>
                                </div>
                            </div>         
                        </div>         
                    </div>

                    {{ Form::open(array('url' => 'add_cashout','class'=>'gj_chreq_form','files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('request_amount', 'Request Amount') }}
                            <span class="error">* 
                                @if ($errors->has('request_amount'))
                                    {{ $errors->first('request_amount') }}
                                @endif
                            </span>

                            {{ Form::text('d_request_amount', 0.00, array('class' => 'form-control gj_d_request_amount', 'disabled','placeholder' => 'Enter Request Amount in rupees')) }}

                            {{ Form::hidden('request_amount', 0.00, array('class' => 'form-control gj_request_amount', 'placeholder' => 'Enter Request Amount in rupees')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('request_date', 'Request Date') }}
                            <span class="error">* 
                                @if ($errors->has('request_date'))
                                    {{ $errors->first('request_date') }}
                                @endif
                            </span>

                            {{ Form::date('request_date', Input::old('request_date'), array('class' => 'form-control gj_request_date', 'placeholder' => 'Enter Request Date')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('remarks', 'Remarks') }}
                            <span class="error">* 
                                @if ($errors->has('remarks'))
                                    {{ $errors->first('remarks') }}
                                @endif
                            </span>

                            {{ Form::textarea('remarks', Input::old('remarks'), array('class' => 'form-control gj_remarks', 'rows' => '5', 'placeholder' => 'Enter Your Remarks')) }}
                        </div>

                        <div class="gj_box dark">
                            <header class="gj_ch_hdr">
                                <div class="gj_icons"><i class="fa fa-edit"></i></div>
                                <h5 class="gj_heading"> Cash Out  Details  </h5>

                                <span class="gj_squaredFour">
                                    <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                                    <label for="ckbCheckAll">Check all</label>
                                </span>

                                <div class="gj_ch_ps_div">
                                    <button class="gj_ch_ps_but btn btn-primary" type="button" id="gj_ch_ps_but">Process</button>
                                </div>
                            </header>

                            <div class="col-md-12">
                                <div class="table-responsive gj_manage_comis">
                                    <table class="table table-bordered table-striped" id="gj_all_cash_table">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>#</th>
                                                <th>Type</th>
                                                <th>Order Code/Credit Notes Code</th>
                                                <th>Product</th>
                                                <th>Merchant</th>
                                                <th>Quantity</th>
                                                <th>Net Amount</th>
                                                <th>Commision Amount</th>
                                                <th>Vendor Amount</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="gj_mge_comis_bdy">
                                            @if($comis)
                                                @php ($i = 1)

                                                @foreach($comis as $key => $value)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                                        <td>
                                                            {{$value->type}}

                                                            <input type="hidden" name="type[]" id="type_{{$value->id}}" value="{{$value->type}}">
                                                        </td>
                                                        <td>
                                                            @if($value->type == "Order")
                                                                @if($value->order_code)
                                                                    @if(isset($value->ComisOrders->id))
                                                                        <a target="_blank" href="{{ route('view_orders', ['id' => $value->ComisOrders->id]) }}">{{$value->order_code}}</a>
                                                                        
                                                                        <input type="hidden" name="order_id[]" id="orderid_{{$value->ComisOrders->id}}" value="{{$value->ComisOrders->id}}">
                                                                    @else
                                                                        {{$value->order_code}}

                                                                        <input type="hidden" name="order_id[]" value="">
                                                                    @endif
                                                                @else
                                                                    {{'------'}}

                                                                    <input type="hidden" name="order_id[]" value="">
                                                                @endif
                                                            @elseif($value->type == "Credit Notes")
                                                                @if($value->cn_id)
                                                                    @if(isset($value->CNotes->cn_code))
                                                                        <a target="_blank" href="{{ route('view_credit_notes', ['id' => $value->cn_id]) }}">{{$value->CNotes->cn_code}}</a>
                                                                    @else
                                                                        {{'------'}}
                                                                    @endif

                                                                    <input type="hidden" name="cn_id[]" id="cn_id_{{$value->cn_id}}" value="{{$value->cn_id}}">
                                                                @else
                                                                    {{'------'}}

                                                                    <input type="hidden" name="cn_id[]" value="">
                                                                @endif
                                                            @else
                                                                {{'------'}}

                                                                <input type="hidden" name="cn_id[]" value="">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($value->product_id)
                                                                @if(isset($value->ComisProducts->product_title))
                                                                    <a href="{{ route('view_product', ['id' => $value->product_id]) }}">
                                                                        {{$value->ComisProducts->product_title}}

                                                                        @if(isset($value->att_name) && $value->att_name != 0)
                                                                            @if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) 
                                                                                <span>({{$value->AttName->att_name}} : {{$value->AttValue->att_value}})</span>
                                                                            @endif
                                                                        @endif

                                                                        <p>Product Code : {{$value->ComisProducts->product_code}}</p>
                                                                    </a>
                                                                @else
                                                                    {{'------'}}
                                                                @endif

                                                                <input type="hidden" name="product_id[]" id="product_id_{{$value->product_id}}" value="{{$value->product_id}}">

                                                                <input type="hidden" name="att_name[]" id="product_id_{{$value->att_name}}" value="{{$value->att_name}}">

                                                                <input type="hidden" name="att_value[]" id="product_id_{{$value->att_value}}" value="{{$value->att_value}}">
                                                            @else
                                                                {{'------'}}

                                                                <input type="hidden" name="product_id[]" value="">
                                                                <input type="hidden" name="att_name[]" value="">
                                                                <input type="hidden" name="att_value[]" value="">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($value->merchant_id)
                                                                @if(isset($value->ComisMerchant->first_name))
                                                                    <a href="{{ route('view_user', ['id' => $value->merchant_id]) }}">{{$value->ComisMerchant->first_name.' '.$value->ComisMerchant->last_name}}</a>

                                                                    <input type="hidden" name="merchant_id[]" id="merchant_id_{{$value->merchant_id}}" value="{{$value->merchant_id}}">
                                                                @else
                                                                    {{'-------'}}

                                                                    <input type="hidden" name="merchant_id[]" value="">
                                                                @endif
                                                            @else
                                                                {{'-------'}}

                                                                <input type="hidden" name="merchant_id[]" value="">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($value->qty)
                                                                <span>{{$value->qty}}</span>
                                                            @else
                                                                <span>{{'------'}}</span>
                                                            @endif

                                                            <input type="hidden" name="order_qty[]" id="order_qty_{{$value->qty}}" value="{{$value->qty}}">
                                                        </td>
                                                        <td>
                                                            @if($value->totalprice)
                                                                <span>Rs. {{$value->totalprice}}</span>
                                                            @else
                                                                <span>{{'------'}}</span>
                                                            @endif
                                                            
                                                            <input type="hidden" name="totalprice[]" id="totalprice_{{$value->totalprice}}" value="{{$value->totalprice}}">
                                                        </td>
                                                        <td>
                                                            <span>Rs. {{$value->amount}}</span>
                                                            
                                                            <input type="hidden" name="comis_admin[]" id="comis_admin_{{$value->amount}}" value="{{$value->amount}}">
                                                        </td>

                                                        <td>
                                                            <span>Rs. {{$value->merchant_amount}}</span>
                                                            
                                                            <input type="hidden" class="gj_mer_amt" name="comis_merchants[]" id="comis_merchants_{{$value->merchant_amount}}" value="{{$value->merchant_amount}}">
                                                        </td>
                                                        <td>{{date('d-m-Y', strtotime($value->created_at))}}</td>
                                                        <td>
                                                            <span>
                                                                @if ($value->paid_status == 1)
                                                                    {{'Paid'}}
                                                                @else
                                                                    {{'Unpaid'}}
                                                                @endif
                                                            </span>
                                                            
                                                            <input type="hidden" name="paid_status[]" id="paid_status_{{$value->paid_status_}}" value="{{$value->paid_status_}}">

                                                            <input type="hidden" name="com_id[]" id="comid_{{$value->id}}" value="{{$value->id}}">
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

                        {{ Form::button('Save', array('class' => 'btn btn-primary', 'id'=>'gj_save_req')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('#gj_all_cash_table').dataTable({
            "paginate": true,
            "searching": true,
            "bInfo" : false,
            "sort": true
        });

        $('p.alert').delay(5000).slideUp(500);
    });

    $(document).ready(function () {
        /*check all script start*/
        $("#ckbCheckAll").click(function () {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });
        
        $(".checkBoxClass").change(function(){
            if (!$(this).prop("checked")){
                $("#ckbCheckAll").prop("checked",false);
            }
        });
        /*check all script end*/

        /*Merchant Amount Calculate Script Start*/
        $('#gj_ch_ps_but').on('click',function(){
            var all = [];
            var mer_amt = 0;

            $("input:checkbox[class=checkBoxClass]:checked").each(function () {
                mer_amt = mer_amt + parseFloat($(this).closest('tr').find('.gj_mer_amt').val());
            });
            mer_amt = (mer_amt).toFixed(2);
            if(mer_amt != 0) {
                $('.gj_d_request_amount').val(mer_amt);
                $('.gj_request_amount').val(mer_amt);
            } else {
                $('.gj_d_request_amount').val(0);
                $('.gj_request_amount').val(0);
                $.confirm({
                    title: '',
                    content: 'Please Select Another Orders!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'purple',
                    buttons: {
                        Ok: function(){
                            window.location.reload();
                        }
                    }
                });
            }
        });
        /*Merchant Amount Calculate Script End*/

        /*Request Add Script Start*/
        $('#gj_save_req').on('click',function(){
            var all = [];
            var request_amount = 0;
            var request_date = false;
            var remarks = false;

            $("input:checkbox[class=checkBoxClass]:checked").each(function () {
                all.push($(this).val());
            });

            if($('#request_amount').val()) {
                request_amount = $('#request_amount').val();
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Select atleast one Order!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                            window.location.reload();
                        }
                    }
                });
            }

            if($('#request_date').val()) {
                request_date = $('#request_date').val();
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Select Request Date!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                            window.location.reload();
                        }
                    }
                });
            }

            if($('#remarks').val()) {
                remarks = $('#remarks').val();
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Enter Remarks!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                            window.location.reload();
                        }
                    }
                });
            }

            if (all.length === 0 || !request_amount || !request_date || !remarks) {
                $.confirm({
                    title: '',
                    content: 'please Enter all data!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                            window.location.reload();
                        }
                    }
                });
            } else {
                $.ajax({
                    type: 'post',
                    url: '{{url('/add_cashout')}}',
                    data: {ids: all, request_amount: request_amount, request_date: request_date, remarks: remarks, type: 'add'},
                    dataType: 'json', 
                    success: function(response){
                        var msg = "";
                        var icn = "fa fa-times";
                        var url = "";
                        if(response['status'] == 1) {
                            msg = "Successfully Added!";
                            icn = "fa fa-check";
                            url = "<?php echo route('manage_cashout'); ?>";
                        } else if(response['status'] == 22) {
                            msg = "Please Select Another Order!";
                            url = "<?php echo route('add_cashout'); ?>";
                        } else if(response['status'] == 2) {
                            msg = "Validation Error!";
                            if(response['error']['request_amount']) {
                                msg+= " , " + response['error']['request_amount'];
                            } 

                            if(response['error']['request_date']) {
                                msg+= " , " + response['error']['request_date'];
                            } 

                            if(response['error']['remarks']) {
                                msg+= " , " + response['error']['remarks'];
                            } 

                            url = "<?php echo route('add_cashout'); ?>";
                        } else if(response['status'] == 0) {
                            msg = "Added Failed!";
                            url = "<?php echo route('manage_cashout'); ?>";
                        } else if(response['status'] == 3) {
                            msg = "You Are Not Access to Add!";
                            url = "<?php echo route('manage_cashout'); ?>";
                        } else if(response['status'] == 4) {
                            msg = "You Are Not Login!";
                            url = "<?php echo route('admin'); ?>";
                        } else {
                            msg = "No Action Performed!";
                            url = "<?php echo route('manage_cashout'); ?>";
                        }

                        $.confirm({
                            title: '',
                            content: msg,
                            icon: icn,
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
                            buttons: {
                                Ok:function() {
                                    // window.location.reload();
                                    window.location = url;
                                }
                            }
                        });
                    }
                });
            }
        });
        /*Request Add Script End*/
    });
</script>
@endsection
