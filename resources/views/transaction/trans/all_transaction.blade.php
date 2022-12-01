@extends('layouts.master')
@section('title', 'Manage All Transaction')
@section('content')

@php ($logged = session()->get('user'))
<section class="gj_all_transaction_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage All Transaction  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage All Transaction  </h5>
                </header>

                <div class="gj_manage_filter">
                    @if($logged)
                        @if($logged->user_type == 1)
                            <span class="gj_squaredFour">
                                <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                                <label for="ckbCheckAll">Check all</label>
                            </span>
                            <button class="btn btn-danger" id="Delete_value" type="button">Delete</button>          
                            <button class="btn btn-info" id="export_csv" type="button">Export CSV</button>
                            <button class="btn btn-info" id="export_all_csv" type="button">Export All CSV</button>
                            <a href="#" id="download_csv"><button class="btn btn-info" id="export_csv_but" type="button">Download CSV</button></a>
                        @elseif($logged->user_type == 2 || $logged->user_type == 3)
                            <span class="gj_squaredFour">
                                <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                                <label for="ckbCheckAll">Check all</label>
                            </span>
                            <button class="btn btn-info" id="export_csv" type="button">Export CSV</button>
                            <button class="btn btn-info" id="export_all_csv" type="button">Export All CSV</button>
                            <a href="#" id="download_csv"><button class="btn btn-info" id="export_csv_but" type="button">Download CSV</button></a>
                        @endif
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_all_trans">
                        <div class="gj_cs_srh_div">
                            {{ Form::open(array('url' => 'search_trans','method' => 'GET','class'=>'gj_search_trans_form','files' => true)) }}
                                <input type="date" name="gj_srh_trans_date" id="gj_srh_trans_date" class="gj_srh_trans_date">
                                <input type="text" name="gj_srh_trans_code" id="gj_srh_trans_code" class="gj_srh_trans_code" placeholder="Search By Transaction Code">
                                <input type="text" name="gj_srh_odr_code" id="gj_srh_odr_code" class="gj_srh_odr_code" placeholder="Search By Order Code">
                                <select id="gj_srh_odr_sts" name="gj_srh_odr_sts" class="gj_srh_odr_sts">
                                    <option value=""> Select Order Status </option>
                                    <option value="1"> Order Placed </option>
                                    <option value="2"> Order Dispatched </option>
                                    <option value="3"> Order Delivered </option>
                                    <option value="4"> Order Complete </option>
                                    <option value="5"> Order Cancelled </option>
                                </select>
                                <button type="submit" class="gj_srh_subm btn btn-primary" id="gj_srh_trans_subm">Search</button>
                            {{ Form::close() }}
                        </div>

                        <table class="table table-bordered table-striped" id="gj_mge_all_trans_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>T-Code</th>
                                    <th>O-Code</th>
                                    <th>Customer</th>
                                    <th>Total Items</th>
                                    <th>Total Price</th>
                                    <th>Payment Type</th>
                                    <th>Payment Status</th>
                                    <th>Payment Date</th>
                                    <th>Order Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_all_trans_bdy">
                                @if($trans)
                                    @php ($i = 1)
                                    
                                    @foreach($trans as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->trans_code}}</td>
                                            <td>
                                                @if($value['orders'])
                                                    {{$value['orders']->order_code}}
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value['orders'])
                                                    {{$value['orders']->contact_person}}
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value['orders'])
                                                    {{$value['orders']->total_items}}
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                            <td>{{$value->net_amount}}</td>
                                            <td>
                                                @if ($value->paymentmode == 1)
                                                    {{'COD'}}
                                                @elseif ($value->paymentmode == 2)
                                                    @if ($value->pay_method)
                                                        {{$value->pay_method}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                            <td>{{$value->trans_status}}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($value->trans_date)) }}</td>
                                            <td>
                                                @if($value['orders'])
                                                    @if ($value['orders']->order_status == 1)
                                                        {{'Order Placed'}}
                                                    @elseif ($value['orders']->order_status == 2)
                                                        {{'Order Dispatched'}}
                                                    @elseif ($value['orders']->order_status == 3)
                                                        {{'Order Delivered'}}
                                                    @elseif ($value['orders']->order_status == 4)
                                                        {{'Order Complete'}}
                                                    @elseif ($value['orders']->order_status == 5)
                                                        {{'Order Cancelled'}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($logged)
                                                    @if($logged->user_type == 1)
                                                        <a href="{{ route('view_transaction', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_all_trans_sview" title="View">
                                                            <i class="fa fa-eye fa-2x"></i>
                                                        </a>
                                                        <a href="#" id="{{$value->id}}" class="gj_mge_all_trans_del" title="Delete">
                                                            <i class="fa fa-trash fa-2x"></i>
                                                        </a>
                                                    @elseif($logged->user_type == 2 || $logged->user_type == 3)
                                                        <a href="{{ route('view_transaction', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_all_trans_sview" title="View">
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

                    @if($trans)
                        {{ $trans->appends(request()->input())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('#gj_mge_all_trans_table').dataTable({
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

        $('p.alert').delay(2000).slideUp(300);
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
                            url: '{{url('/delete_all_trans')}}',
                            data: {ids: all, type: 'all_delete'},
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

    $('.gj_mge_all_trans_del').on('click',function(){
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
                        url: '{{url('/delete_trans')}}',
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
                url: '{{url('/export_csv_trans')}}',
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
                url: '{{url('/export_csv_trans')}}',
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
