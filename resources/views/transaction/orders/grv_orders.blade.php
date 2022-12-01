<?php $sess = session()->get('user'); ?>
@extends('layouts.master')
@section('title', 'Manage GRV Orders')
@section('content')

@php ($logged = session()->get('user'))
<section class="gj_grv_orders_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage GRV Orders  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage GRV Orders  </h5>
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
                    <div class="table-responsive gj_manage_grv_orders">
                        <table class="table table-bordered table-striped" id="gj_mge_grv_orders_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>GRV Code</th>
                                    <th>Order Code</th>
                                    <th>Order Date</th>
                                    <th>GRV Date</th>
                                    <th>Customer Name</th>
                                    <th>Total Items</th>
                                    <th>Total Price</th>
                                    <th>Order Return Status</th>
                                    <th>GRV Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_grv_orders_bdy">
                                @if($orders)
                                    @php ($i = 1)
                                    
                                    @foreach($orders as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->grv_code}}</td>
                                            <td>
                                                @if($value->order_id)
                                                    @if($value->Orders->order_code)
                                                        {{$value->Orders->order_code}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>

                                            <td>
                                                @if($value->order_id)
                                                    @if($value->Orders->order_date)
                                                        {{ date('d-m-Y', strtotime($value->Orders->order_date)) }}
                                                    @else
                                                        {{'-------'}}
                                                    @endif
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>

                                            <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>

                                            <td>
                                                @if ($value->return_order_id)
                                                    @if($value->ReOrders->Users->first_name)
                                                        {{$value->ReOrders->Users->first_name}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif
                                                @else 
                                                    {{'-------'}}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($value->return_order_id)
                                                    @if($value->ReOrders->total_items)
                                                        {{$value->ReOrders->total_items}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif
                                                @else 
                                                    {{'-------'}}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($value->return_order_id)
                                                    @if($value->ReOrders->net_amount)
                                                        Rs. {{$value->ReOrders->net_amount}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif
                                                @else 
                                                    {{'-------'}}
                                                @endif
                                            </td>

                                            <td>
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
                                            </td>

                                            <td>
                                                @if($sess)
                                                    @if($sess->user_type == 1)
                                                        <select name="grv_status" class="grv_status" data-id="{{$value->id}}">
                                                            <option @if($value->grv_status == 0)  selected @endif value="">Select GRV Order Status</option>
                                                            <option @if($value->grv_status == 1)  selected @endif value="1">GRV Opened</option>
                                                            <option @if($value->grv_status == 2)  selected @endif  value="2">GRV Closed</option>
                                                        </select>
                                                    @else
                                                        @if($value->order_id)
                                                            <p class="gj_p_met text-center">
                                                                @if($value->grv_status == 1)
                                                                    {{'GRV Opened'}}
                                                                @elseif($value->grv_status == 2)
                                                                    {{'GRV Closed'}}
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
                                                    <a href="{{ route('edit_grv_orders', ['id' => $value->id]) }}" title="Edit">
                                                        <i class="fa fa-edit fa-2x"></i>
                                                    </a>
                                                </span>
                                                
                                                <span>
                                                    <a href="{{ route('view_grv_orders', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_grv_orders_sview" title="View">
                                                        <i class="fa fa-eye fa-2x"></i>
                                                    </a>
                                                </span>
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
            $('#gj_mge_grv_orders_table').dataTable({
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

        $('.grv_status').on('change',function(){
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
                url: '{{url('/grv_sts_orders')}}',
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
                    url: '{{url('/export_grv_order')}}',
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
                url: '{{url('/export_grv_order')}}',
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