@extends('layouts.master')
@section('title', 'Manage All Orders')
@section('content')

@php ($logged = session()->get('user'))
<section class="gj_all_orders_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage All Orders  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage All Orders  </h5>
                </header>

                <div class="gj_manage_filter">
                    @if($logged)
                        @if($logged->user_type == 1)
                            <span class="gj_squaredFour">
                                <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                                <label for="ckbCheckAll">Check all</label>
                            </span>
                            <a href="{{route('replace_all_orders')}}" class="gj_repl_odr"><button type="button" class="btn btn-primary gj_srh_replace">Replace Orders</button></a>
                            <a href="{{route('all_orders')}}" class="gj_repl_odr"><button type="button" class="btn btn-warning gj_srh_replace">All Orders</button></a>
                            <button class="btn btn-danger" id="Delete_value" type="button">Delete</button>          
                            <button class="btn btn-info" id="export_csv" type="button">Export CSV</button>
                            <button class="btn btn-info" id="export_all_csv" type="button">Export All CSV</button>
                            <a href="#" id="download_csv"><button class="btn btn-info" id="export_csv_but" type="button">Download CSV</button></a>
                        @elseif($logged->user_type == 2 || $logged->user_type == 3)
                            <span class="gj_squaredFour">
                                <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                                <label for="ckbCheckAll">Check all</label>
                            </span>
                            <a href="{{route('replace_all_orders')}}" class="gj_repl_odr"><button type="button" class="btn btn-primary gj_srh_replace">Replace Orders</button></a>
                            <a href="{{route('all_orders')}}" class="gj_repl_odr"><button type="button" class="btn btn-warning gj_srh_replace">All Orders</button></a>
                            <button class="btn btn-info" id="export_csv" type="button">Export CSV</button>
                            <button class="btn btn-info" id="export_all_csv" type="button">Export All CSV</button>
                            <a href="#" id="download_csv"><button class="btn btn-info" id="export_csv_but" type="button">Download CSV</button></a>
                        @endif
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_all_orders">
                        <div class="gj_cs_srh_div">
                            {{ Form::open(array('url' => 'search_order','method' => 'GET','class'=>'gj_search_order_form','files' => true)) }}
                                <input type="date" name="gj_srh_odr_date" id="gj_srh_odr_date" class="gj_srh_odr_date">
                                <input type="text" name="gj_srh_odr_code" id="gj_srh_odr_code" class="gj_srh_odr_code" placeholder="Search By Order Code">
                                <button type="submit" class="gj_srh_subm btn btn-primary" id="gj_srh_odr_subm">Search</button>
                            {{ Form::close() }}
                        </div>

                        <table class="table table-bordered table-striped" id="gj_mge_all_orders_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Order Code</th>
                                    <th>Order Date</th>
                                    <th>Customer Name</th>
                                    <th>Total Items</th>
                                    <th>Total Price</th>
                                    <th>Payment</th>
                                    <!--<th>Delivery Start Time</th>
                                    <th>Delivery End Time</th>-->
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_all_orders_bdy">
                                @if($orders)
                                    @php ($i = 1)
                                    
                                    @foreach($orders as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>
                                                {{$value->order_code}}
                                                @if($value->ref_order_id)
                                                    @if($value->Reference->order_code)
                                                        <p class="gj_ref_odr">Reference Order : {{$value->Reference->order_code}}</p>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($value->order_date)) }}</td>
                                            <td>{{$value->contact_person}}</td>
                                            <td>{{$value->total_items}}</td>
                                            <td>{{$value->net_amount}}</td>
                                            <td>
                                                <p class="gj_p_met text-center">
                                                    @if($value->payment_mode == 1)
                                                        {{'COD'}}
                                                    @elseif($value->payment_mode == 2)
                                                        {{'Online'}}
                                                    @else
                                                        {{'----'}}
                                                    @endif
                                                </p>

                                                @if($logged)
                                                    @if($logged->user_type == 1)
                                                        <select name="paid_sts" id="paid_sts" data-ord-id="{{$value->id}}" class="form-control gj_paid_sts">
                                                            <option value="0" @if($value->payment_status == 0) {{'selected'}} @endif>Pending</option>
                                                            <option value="1" @if($value->payment_status == 1) {{'selected'}} @endif>Success</option>
                                                            <option value="2" @if($value->payment_status == 2) {{'selected'}} @endif>Failed</option>
                                                        </select>
                                                    @elseif($logged->user_type == 2 || $logged->user_type == 3)
                                                        <p class="gj_p_met text-center">
                                                            @if($value->payment_status == 0)
                                                                {{'Pending'}}
                                                            @elseif($value->payment_status == 1)
                                                                {{'Success'}}
                                                            @elseif($value->payment_status == 2)
                                                                {{'Failed'}}
                                                            @else
                                                                {{'----'}}
                                                            @endif
                                                        </p>
                                                    @endif
                                                @endif
                                            </td>
                                           <!-- {{--<td><select name="trip_time"class="form-control" id="">
												<option value="">--:--</option>
												<?php	
														date_default_timezone_set('Asia/Kolkata');
														for($hours=date("H"); $hours<24; $hours++){
    														for($mins=date("i"); $mins<60; $mins+=60) {
        														$time = str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT);
																echo "<option value='$time'>".date("g:i A", strtotime($time))."</option>";
															}
														}
												?>
											</select></td>
                                            <td><select name="trip_time"class="form-control" id="">
												<option value="">--:--</option>
												<?php	
														date_default_timezone_set('Asia/Kolkata');
														for($hours=date("H"); $hours<24; $hours++){
    														for($mins=date("i"); $mins<60; $mins+=60) {
        														$time = str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT);
																echo "<option value='$time'>".date("g:i A", strtotime($time))."</option>";
															}
														}
												?>
											</select></td> --}}-->
                                            <td>
                                                @if($logged)
                                                    @if($logged->user_type == 1)
                                                        <select name="status" id="staus" data-odr-id="{{$value->id}}" class="form-control gj_odr_sts">
                                                            <option value="0" @if($value->order_status == 0) {{'selected'}} @endif>-- Select Order Status --</option>
                                                            <option value="1" @if($value->order_status == 1) {{'selected'}} @endif>Order Placed</option>
                                                            <option value="2" @if($value->order_status == 2) {{'selected'}} @endif>Order Confirmed</option>
                                                            <option value="3" @if($value->order_status == 3) {{'selected'}} @endif>Order Dispatched</option>
                                                            <option value="4" @if($value->order_status == 4) {{'selected'}} @endif>Order Delivered </option>
                                                            <option value="5" @if($value->order_status == 5) {{'selected'}} @endif>Order Complete</option>
                                                            <option value="6" @if($value->order_status == 6) {{'selected'}} @endif>Order Cancelled</option>
                                                        </select>
                                                    @elseif($logged->user_type == 2 || $logged->user_type == 3)
                                                        @if($value->order_status == 1)
                                                            {{'Order Placed'}}
                                                        @elseif($value->order_status == 2)
                                                            {{'Order Confirmed'}}
                                                        @elseif($value->order_status == 3)
                                                            {{'Order Dispatched'}}
                                                        @elseif($value->order_status == 4)
                                                            {{'Order Delivered'}}
                                                        @elseif($value->order_status == 5)
                                                            {{'Order Complete'}}
                                                        @elseif($value->order_status == 6)
                                                            {{'Order Cancelled'}}
                                                        @else
                                                            {{'----'}}
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($logged)
                                                    @if($logged->user_type == 1)
                                                        <a href="{{ route('edit_orders', ['id' => $value->id]) }}" title="Edit">
                                                            <i class="fa fa-edit fa-2x"></i>
                                                        </a>
                                                        
                                                        <a href="{{ route('view_orders', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_all_orders_sview" title="View">
                                                            <i class="fa fa-eye fa-2x"></i>
                                                        </a>
                                                        <a href="#" id="{{$value->id}}" class="gj_mge_all_orders_del" title="Delete">
                                                            <i class="fa fa-trash fa-2x"></i>
                                                        </a>
                                                        @if($value->order_status != 4 && $value->order_status != 5)
                                                            <a href="{{ route('delivery_orders', ['id' => $value->id]) }}" title="Delivered">
                                                                <i class="fa fa-truck fa-2x"></i>
                                                            </a>
                                                        @endif
                                                    @elseif($logged->user_type == 2 || $logged->user_type == 3)
                                                        <a href="{{ route('view_orders', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_all_orders_sview" title="View">
                                                            <i class="fa fa-eye fa-2x"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @php ($i = $i+1)
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if($orders)
                        {{ $orders->appends(request()->input())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

    <script>
        $(document).ready(function() { 
            $('#gj_mge_all_orders_table').dataTable({
                "paginate": false,
                "searching": true,
                "bInfo" : false,
                "sort": true
            });
        });

        $(document).ready(function () {
            $("#ckbCheckAll").click(function () {
                $(".checkBoxClass").prop('checked', $(this).prop('checked'));
            });
            
            $(".checkBoxClass").change(function(){
                if (!$(this).prop("checked")){
                    $("#ckbCheckAll").prop("checked",false);
                }
            });

            $('p.alert').delay(5000).slideUp(500);
            $("#download_csv").hide();
        });

        $('#Delete_value').on('click',function(){
            var all = [];
            $("input:checkbox[class=checkBoxClass]:checked").each(function () {
                all.push($(this).val());
            });
            if (all.length === 0) {
                $.confirm({
                    title: '',
                    content: 'Please Select atleast one check box!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'purple',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
            } else {
                $.confirm({
                    title: '',
                    content: 'Are You Sure to Delete?',
                    icon: 'fa fa-trash-o',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'blue',
                    buttons: {
                        Ok: function(){
                            $.ajax({
                                type: 'post',
                                url: '{{url('/delete_all_orders')}}',
                                data: {ids: all, type: 'delete_all_orders'},
                                success: function(data){
                                    if(data == 0){
                                        window.location.reload();
                                    } else {
                                        $.confirm({
                                            title: '',
                                            content: 'No Action Performed!',
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
                                }
                            });
                        },
                        Cancel:function() {
                        }
                    }
                });
            }
        });

        $('.gj_mge_all_orders_del').on('click',function(){
            var id = 0;
            if($(this).attr('id')) {
                id = $(this).attr('id');
            }

            $.confirm({
                title: '',
                content: 'Are You Sure to Delete?',
                icon: 'fa fa-trash-o',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                        $.ajax({
                            type: 'post',
                            url: '{{url('/delete_orders')}}',
                            data: {id: id, type: 'delete'},
                            success: function(data){
                                if(data == 0){
                                    window.location.reload();
                                } else {
                                    $.confirm({
                                        title: '',
                                        content: 'No Action Performed!',
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
                            }
                        });
                    },
                    Cancel:function() {
                    }
                }
            });
        });

        $('.gj_odr_sts').on('change',function(){
            var id = 0;
            var status = 0;
            if($(this).attr('data-odr-id')) {
                id = $(this).attr('data-odr-id');
            }

            if($(this).val()) {
                status = $(this).val();
            }

            $.ajax({
                type: 'post',
                url: '{{url('/status_orders')}}',
                data: {id: id, status: status, type: 'staus_change'},
                success: function(data){
                    if(data == 0){
                        window.location.reload();
                    } else {
                        $.confirm({
                            title: '',
                            content: 'No Action Performed!',
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
                }
            });
        });

        $('.gj_paid_sts').on('change',function(){
            var id = 0;
            var status = 0;
            if($(this).attr('data-ord-id')) {
                id = $(this).attr('data-ord-id');
            }

            if($(this).val()) {
                status = $(this).val();
            }

            $.ajax({
                type: 'post',
                url: '{{url('/paymentstatus_orders')}}',
                data: {id: id, status: status, type: 'staus_change'},
                success: function(data){
                    window.location.reload();
                }
            });
        });
    </script>

    <!-- Export Script Start -->
    <script type="text/javascript">
        $('#export_csv').on('click',function(){
            var all = [];
            $("input:checkbox[class=checkBoxClass]:checked").each(function () {
                all.push($(this).val());
            });
            if (all.length === 0) {
                $.confirm({
                    title: '',
                    content: 'Please Select atleast one check box!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'purple',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
            } else {
                $.ajax({
                    type: 'post',
                    url: '{{url('/export_csv_order')}}',
                    data: {ids: all, type: 'export'},
                    success: function(response){
                        if(response){
                            $("#download_csv").show();
                            $("#download_csv").attr("href", response);
                        } else {
                            $.confirm({
                                title: '',
                                content: 'No Action Performed!',
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
                        $(function () {
                            setTimeout(function() {
                                window.location.reload();
                            }, 5000);
                        });
                    }
                });
            }
        });
    </script>
    <!-- Export Script End -->

    <!-- Export CSV ALL Script Start -->
    <script type="text/javascript">
        $('#export_all_csv').on('click',function() {
            $.ajax({
                type: 'post',
                url: '{{url('/export_csv_order')}}',
                data: {type: 'export_all'},
                success: function(response){
                    if(response){
                        window.location.href = "<?php echo route('home'); ?>/" + response;
                    } else {
                        $.confirm({
                            title: '',
                            content: 'No Action Performed!',
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
                }
            });
        });
    </script>
    <!-- Export CSV ALL Script End -->
@endsection