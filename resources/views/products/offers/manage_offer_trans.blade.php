@extends('layouts.master')
@section('title', 'Manage Offer Stock Transaction')
@section('content')
<section class="gj_stock_trans_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.product_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage Offer Stock Transaction  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Offer Stock Transaction  </h5>
                </header>

                <div class="gj_manage_filter">
                    <span class="gj_squaredFour">
                        <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                        <label for="ckbCheckAll">Check all</label>
                    </span>
                    <button class="btn btn-info" id="export_csv" type="button">Export CSV</button>

                    <button class="btn btn-info" id="export_all_csv" type="button">Export All CSV</button>

                    <a href="#" id="download_csv"><button class="btn btn-info" id="exports_csv" type="button">Download CSV</button></a>          
                </div>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_stock_trans">
                        <table class="table table-bordered table-striped" id="gj_mge_stock_trans_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Order Code</th>
                                    <th>Offer</th>
                                    <th>Product</th>
                                    <th>Previous Qty</th>
                                    <th>Current Qty</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_stock_trans_bdy">
                                @if($stock_trans)
                                    @php ($i = 1)
                                    @foreach($stock_trans as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>
                                                @if($value->order_code)
                                                    @if(isset($value->OfferOrders['id']))
                                                        <a href="{{ route('view_orders', ['id' => $value->OfferOrders['id']]) }}">{{$value->order_code}}</a>
                                                    @else
                                                        {{$value->order_code}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>

                                            <td>
                                                @if($value->offer)
                                                    @if(isset($value->Offers->offer_title))
                                                        <a href="{{ route('view_offer', ['id' => $value->offer]) }}">
                                                            {{$value->Offers->offer_title}} 
                                                        </a>
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->product_id)
                                                    @if(isset($value->OfferProducts->product_title))
                                                        <a href="{{ route('view_product', ['id' => $value->product_id]) }}">
                                                            {{$value->OfferProducts->product_title}}

                                                            @if($value->att_name) ({{$value->AttributeName->att_name}} @if($value->att_value) : {{$value->AttributeValue->att_value}}) @endif  @endif 
                                                        </a>
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                            <td>{{$value->previous_qty}}</td>
                                            <td>{{$value->current_qty}}</td>
                                            <td>{{date('d-m-Y', strtotime($value->date))}}</td>
                                        </tr>
                                        @php ($i = $i+1)
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if($stock_trans)
                        {{$stock_trans->links()}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

    <script>
        $(document).ready(function() { 
            $('#gj_mge_stock_trans_table').dataTable({
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

            $('p.alert').delay(5000).slideUp(500);
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
                    url: '{{url('/stock_trans_block')}}',
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
                    url: '{{url('/stock_trans_unblock')}}',
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
                                url: '{{url('/delete_stock_trans_all')}}',
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

        $('.gj_mge_stock_trans_del').on('click',function(){
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
                            url: '{{url('/delete_stock_trans')}}',
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
    
    <!-- Export CSV Script Start -->
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
                    url: '{{url('/export_offer_trans_csv')}}',
                    data: {ids: all, type: 'csv'},
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
    <!-- Export CSV Script End -->

    <!-- Export CSV ALL Script Start -->
    <script type="text/javascript">
        $('#export_all_csv').on('click',function() {
            $.ajax({
                type: 'post',
                url: '{{url('/export_offer_trans_csv')}}',
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
