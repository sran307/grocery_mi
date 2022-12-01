@extends('layouts.master')
@section('title', 'Manage Credit Notes')
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
                        <li class="active"><a> Manage Credit Notes  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Credit Notes  </h5>
                </header>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_all_orders">
                        <table class="table table-bordered table-striped" id="gj_mge_all_orders_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <!-- <th>#</th> -->
                                    <th>CN Code</th>
                                    <th>CN Date</th>
                                    <th>GRV Code</th>
                                    <th>Order Code</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_all_orders_bdy">
                                @if($cn)
                                    @php ($i = 1)
                                    
                                    @foreach($cn as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <!-- <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td> -->
                                            <td>
                                                {{$value->cn_code}}
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($value->date)) }}</td>
                                            <td>
                                                @if($value->grv_id)
                                                    @if($value->GRV->grv_code)
                                                        {{$value->GRV->grv_code}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif 
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->grv_id)
                                                    @if($value->GRV->Orders->order_code)
                                                        {{$value->GRV->Orders->order_code}}
                                                    @else
                                                        {{'-------'}}
                                                    @endif 
                                                @else
                                                    {{'-------'}}
                                                @endif
                                            </td>
                                            <td>
                                                Rs. {{$value->amount}}
                                            </td>
                                            <td>
                                                @if($logged)
                                                    @if($logged->user_type == 1)
                                                        <select name="status" id="staus" data-cn-id="{{$value->id}}" class="form-control gj_cn_sts">
                                                            <option value="" @if($value->is_paid == '') {{'selected'}} @endif>-- Select Order Status --</option>
                                                            <option value="Paid" @if($value->is_paid == "Paid") {{'selected'}} @endif>Paid</option>
                                                            <option value="Un Paid" @if($value->is_paid == "Un Paid") {{'selected'}} @endif>Un Paid</option>
                                                        </select>
                                                    @elseif($logged->user_type == 2 || $logged->user_type == 3)
                                                        @if($value->is_paid)
                                                            {{$value->is_paid}}
                                                        @else
                                                            {{'----'}}
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('view_credit_notes', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_all_cn_sview" title="View">
                                                    <i class="fa fa-eye fa-2x"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php ($i = $i+1)
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if($cn)
                        {{ $cn->appends(request()->input())->links() }}
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

        $('.gj_cn_sts').on('change',function(){
            var id = 0;
            var status = 0;
            if($(this).attr('data-cn-id')) {
                id = $(this).attr('data-cn-id');
            }

            if($(this).val()) {
                status = $(this).val();
            }

            $.ajax({
                type: 'post',
                url: '{{url('/status_credit_notes')}}',
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