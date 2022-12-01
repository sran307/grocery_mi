@extends('layouts.master')
@section('title', 'Manage Products')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.product_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage Products  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Products  </h5>
                </header>

                <div class="gj_manage_filter">
                    <span class="gj_squaredFour">
                        <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                        <label for="ckbCheckAll">Check all</label>
                    </span>
                    <button class="btn btn-primary" id="Block_value" type="button">Block</button>
                    <button class="btn btn-warning" id="UNBlock_value" type="button">Un Block</button>          
                    <button class="btn btn-danger" id="Delete_value" type="button">Delete</button>          
                    <!-- <a href="/export_csv"><button class="btn btn-info" id="export_csv" type="button">Export CSV</button></a>    -->
                    <button class="btn btn-info" id="export_csv" type="button">Export CSV</button>

                    <button class="btn btn-info" id="export_all_csv" type="button">Export All CSV</button>

                    <!-- <span id="download_csv"></span> -->
                    <a href="#" id="download_csv"><button class="btn btn-info" id="exports_csv" type="button">Download CSV</button></a>         
                </div>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_product">
                        <div class="gj_cs_srh_div">
                            {{ Form::open(array('url' => 'search_products','method' => 'GET','class'=>'gj_search_pdts_form','files' => true)) }}
                                <input type="text" name="gj_srh_pdts" id="gj_srh_pdts" class="gj_srh_pdts" placeholder="Search By Products">
                                <button type="submit" class="gj_srh_subm btn btn-primary" id="gj_srh_pdts_subm">Search</button>

                                <a href="{{route('manage_product')}}" title="All Products"><button class="btn btn-success gj_srh_subm" id="Block_value" type="button">All Products</button></a>
                            {{ Form::close() }}
                        </div>

                        <table class="table table-bordered table-striped" id="gj_mge_product_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Original Price</th>
                                    <th>Discount Price</th>
                                     <!--<th>Discount Price(Dealer)</th>-->
                                    
                                    <th class="gj_mge_fp_img_th">Product Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_product_bdy">
                                @if($product)
                                    @php ($i = 1)
                                    <?php 
                                    $file_path = 'images/featured_products';
                                    $no_file_path = 'images/noimage';
                                    $no_images = \DB::table('noimage_settings')->first();
                                    $images = "";
                                    if($no_images) {
                                        $images =  $no_file_path.'/'.$no_images->product_no_image;
                                    }
                                    ?>
                                    @foreach($product as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->product_code}}</td>
                                            <td>{{$value->product_title}}</td>
                                            <td>Rs. {{$value->original_price}}</td>
                                            <td>Rs. {{$value->discounted_price}}</td>
                                            <!--<td>Rs. {{$value->discount_price_dealer}}</td>-->
                                            <td class="gj_mge_fp_img_td">
                                                @if($value->featured_product_img)
                                                    <a href="{{ asset($file_path.'/'.$value->featured_product_img)}}" target="_blank"><img src="{{ asset($file_path.'/'.$value->featured_product_img)}}" class="img-responsive gj_mge_fp_img"></a>
                                                @else 
                                                    <a href="{{ asset($images)}}" target="_blank"><img src="{{ asset($images)}}" class="img-responsive gj_mge_fp_img"></a>
                                                @endif
                                            </td>
                                            <td class="gj_p_actions">
                                                <span>
                                                    <a href="{{ route('edit_product', ['id' => $value->id]) }}" data-tooltip="Edit">
                                                        <i class="fa fa-edit fa-2x"></i>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a href="{{ route('status_product', ['id' => $value->id]) }}" data-tooltip="block">
                                                        @if($value->is_block == 1)
                                                            <i class="gj_ok fa fa-check fa-2x"></i>
                                                        @else
                                                            <i class="gj_danger fa fa-ban fa-2x"></i>
                                                        @endif
                                                    </a>
                                                </span>
                                                <span>
                                                    <a href="#" id="{{$value->id}}" class="gj_mge_product_del" data-tooltip="Delete">
                                                        <i class="fa fa-trash fa-2x"></i>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a href="{{ route('view_products', ['id' => $value->id]) }}" target="_blank" id="{{$value->id}}" class="btn btn-info btn-sm gj_mge_product_vw" data-tooltip="View">
                                                        <button><i class="fa fa-eye"></i> Preview</button>
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

                    @if($product)
                        {{$product->appends(request()->input())->links()}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('#gj_mge_product_table').dataTable({
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
                url: '{{url('/product_block')}}',
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
                url: '{{url('/product_unblock')}}',
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
                            url: '{{url('/delete_product_all')}}',
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

    $('.gj_mge_product_del').on('click',function(){
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
                        url: '{{url('/delete_product')}}',
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

    $('#export_all_csv').on('click',function() {
        $.ajax({
            type: 'post',
            url: '{{url('/export_csv')}}',
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
@endsection
