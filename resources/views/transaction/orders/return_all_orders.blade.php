<?php $sess = session()->get('user'); ?>
@extends('layouts.master')
@section('title', 'Manage Return All Orders')
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
                        <li class="active"><a> Manage Return All Orders  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Return All Orders  </h5>
                </header>

                <div class="gj_manage_filter">
                    <span class="gj_squaredFour">
                        <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                        <label for="ckbCheckAll">Check all</label>
                    </span>
                    <button class="btn btn-info" id="export_csv" type="button">Export CSV</button>
                    <button class="btn btn-info" id="export_all_csv" type="button">Export All CSV</button>
                    <a href="#" id="download_csv"><button class="btn btn-info" id="export_csv_but" type="button">Download CSV</button></a>
                </div>

                <div class="col-md-12">
                    <p class="gj_note">Note : If Customer Returned or Replacement Order Items all are get in Ware House. Then You have Create the GRV. </p>
                    <div class="table-responsive gj_manage_all_orders">
                        <table class="table table-bordered table-striped" id="gj_mge_all_orders_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Order Code</th>
                                    <th>Order Date</th>
                                    <th>Return Date</th>
                                    <th>Customer Name</th>
                                    <th>Total Items</th>
                                    <th>Total Price</th>
                                    <th>Return Items</th>
                                    <th>Return Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_all_orders_bdy">
                                @if($orders)
                                    @php ($i = 1)
                                    
                                    @foreach($orders as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->order_code}}</td>
                                            <td>{{ date('d-m-Y', strtotime($value->order_date)) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($value->return_date)) }}</td>
                                            <td>
                                                @if ($value->user_id)
                                                    @if($value->Users->first_name)
                                                        {{$value->Users->first_name}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif
                                                @else 
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                            <td>{{$value->total_items}}</td>
                                            <td>Rs. {{$value->net_amount}}</td>
                                            <td>{{$value->return_total_items}}</td>
                                            <td>Rs. {{$value->return_net_amount}}</td>
                                            <td>
                                                @if($sess)
                                                    @if($sess->user_type == 1)
                                                        <select name="return_order_status" class="return_order_status" data-id="{{$value->order_id}}">
                                                            <option @if($value->Orders->return_order_status == 0)  selected @endif value="">Select Return Order Status</option>
                                                            <option @if($value->Orders->return_order_status == 1)  selected @endif value="1">Order Return Initialized</option>
                                                            <option @if($value->Orders->return_order_status == 2)  selected @endif value="2">Order Return Confirmed</option>
                                                            <option @if($value->Orders->return_order_status == 3)  selected @endif value="3" disabled>Order Return Cancelled</option>
                                                        </select>
                                                    @else
                                                        @if($value->order_id)
                                                            <p class="gj_p_met text-center">
                                                                @if($value->Orders->return_order_status == 1)
                                                                    {{'Order Return Initialized'}}
                                                                @elseif($value->Orders->return_order_status == 2)
                                                                    {{'Order Return Confirmed'}}
                                                                @elseif($value->Orders->return_order_status == 3)
                                                                    {{'Order Return Cancelled'}}
                                                                @else
                                                                    {{'----'}}
                                                                @endif
                                                            </p>
                                                        @else
                                                            {{'----'}}
                                                        @endif
                                                    @endif
                                                @else
                                                    {{'----'}}
                                                @endif
                                            </td>

                                            <td>
                                                <span>
                                                    <a href="{{ route('view_return_orders', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_all_orders_sview" title="View">
                                                        <i class="fa fa-eye fa-2x"></i>
                                                    </a>
                                                </span>

                                                @if($value->order_id)
                                                    @if($value->Orders->return_order_status == 1)   
                                                        <span>
                                                            <a href="{{ route('create_grv_orders', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_all_orders_crt" title="Create GRV">
                                                                <i class="fa fa-plus-square-o fa-2x"></i>
                                                            </a>
                                                        </span>

                                                        <span>
                                                            <a href="{{ route('get_reject_return_orders', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_rro" title="Return Orders Status">
                                                                <i class="fa fa-snowflake-o fa-2x"></i>
                                                            </a>
                                                        </span>
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
                        {{$orders->links()}}
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

        $('.return_order_status').on('change',function(){
            var id = 0;
            var status = 0;
            if($(this).attr('data-id')) {
                id = $(this).attr('data-id');
            }

            if($(this).val()) {
                status = $(this).val();
            }

            $.ajax({
                type: 'post',
                url: '{{url('/return_sts_orders')}}',
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
                    url: '{{url('/export_return_order')}}',
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
                url: '{{url('/export_return_order')}}',
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