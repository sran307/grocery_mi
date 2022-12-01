@extends('layouts.master')
@section('title', 'Order By Admin Commision')
@section('content')
<section class="gj_odrby_comis_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Order By Admin Commision  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Order By Admin Commision  </h5>
                </header>

                <!-- <div class="gj_manage_filter">
                    <span class="gj_squaredFour">
                        <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                        <label for="ckbCheckAll">Check all</label>
                    </span>
                    <button class="btn btn-primary" id="Block_value" type="button">Block</button>
                    <button class="btn btn-warning" id="UNBlock_value" type="button">Un Block</button>          
                </div> -->

                <div class="gj_comis_details">
                    @if(isset($admin_com_total) && $admin_com_total)
                        <p class="gj_admin_amount">Total Admin Commision : Rs. <span> {{$admin_com_total}}</span></p>
                    @else
                        <p class="gj_admin_amount">Total Admin Commision : Rs.<span>  0</span></p>
                    @endif

                    @if(isset($vendor_com_total) && $vendor_com_total)
                        <p class="gj_vendor_amount">Total Vendor Amount :  Rs. <span> {{$vendor_com_total}}</span></p>
                    @else
                        <p class="gj_vendor_amount">Total Vendor Amount :  Rs. <span> 0</span></p>
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_comis">
                        <table class="table table-bordered table-striped" id="gj_mge_comis_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Order Code/Credit Notes Code</th>
                                    <th>Merchants</th>
                                    <th>Admin Commision Amount</th>
                                    <th>Vendor Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_comis_bdy">
                                @if($comis)
                                    @php ($i = 1)

                                    @foreach($comis as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>
                                                @if($value->type == "Order")
                                                    @if($value->order_code)
                                                        <a target="_blank" href="{{ route('view_orders', ['id' => $value->ComisOrders->id]) }}">{{$value->order_code}}</a>
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @elseif($value->type == "Credit Notes")
                                                    @if($value->cn_id)
                                                        @if(isset($value->CNotes->cn_code))
                                                            <a target="_blank" href="{{ route('view_credit_notes', ['id' => $value->cn_id]) }}">{{$value->CNotes->cn_code}}</a>
                                                        @else
                                                            {{'------'}}
                                                        @endif
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->merchant_id)
                                                    @if(isset($value->ComisMerchant->first_name))
                                                        <a href="{{ route('view_user', ['id' => $value->merchant_id]) }}">{{$value->ComisMerchant->first_name.' '.$value->ComisMerchant->last_name}}</a>
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                            <td>Rs. {{$value->comis}}</td>
                                            <td>Rs. {{$value->comis_vendor}}</td>
                                            <td>{{date('d-m-Y', strtotime($value->created_at))}}</td>
                                        </tr>
                                        @php ($i = $i+1)
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if($comis)
                        {{$comis->links()}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('#gj_mge_comis_table').dataTable({
            "paginate": false,
            "searching": true,
            "bInfo" : false,
            "sort": true
        });
        $("#download_csv").hide();
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

        $('p.alert').delay(1000).slideUp(300);
    });

    $('#Block_value').on('click',function(){
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
                url: '{{url('/comis_block')}}',
                data: {ids: all, type: 'block'},
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
        }
    });

    $('#UNBlock_value').on('click',function(){
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
                url: '{{url('/comis_unblock')}}',
                data: {ids: all, type: 'unblock'},
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
        }
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
                            url: '{{url('/delete_comis_all')}}',
                            data: {ids: all, type: 'unblock'},
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

    $('.gj_mge_comis_del').on('click',function(){
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
                        url: '{{url('/delete_comis')}}',
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
                url: '{{url('/export_csv')}}',
                data: {ids: all, type: 'unblock'},
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

<script type="text/javascript">
    $('.gj_com_pay').on('change',function(){
        var id = 0;
        var pay = 0;
        if($(this).attr('data-id')) {
            id = $(this).attr('data-id');
        }

        if($(this).val()) {
            pay = $(this).val();
        }

        if(id != 0) {
            $.confirm({
                title: '',
                content: 'Are You Sure to Changed?',
                icon: 'fa fa-pencil-square-o',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                        $.ajax({
                            type: 'post',
                            url: '{{url('/status_comis')}}',
                            data: {id: id, pay: pay, type: 'status'},
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
        } else {
            $.confirm({
                title: '',
                content: 'Please Changed The Payment Status after sometimes!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });
</script>
@endsection
