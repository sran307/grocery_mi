@extends('layouts.master')
@section('title', 'Manage Store')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.merchant_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage Store  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Store  </h5>
                </header>

                <div class="gj_manage_filter">
                    <span class="gj_squaredFour">
                        <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                        <label for="ckbCheckAll">Check all</label>
                    </span>
                    <button class="btn btn-primary" id="Block_value" type="button">Block</button>
                    <button class="btn btn-warning" id="UNBlock_value" type="button">Un Block</button>          
                </div>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_store">
                        <table class="table table-bordered table-striped" id="gj_mge_store_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Store Name</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>District</th>
                                    <th class="gj_mge_str_img_th">Store Image</th>
                                    <th>Edit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_store_bdy">
                                @if($store)
                                    @php ($i = 1)
                                    <?php 
                                    $file_path = 'images/stores_image';
                                    $no_file_path = 'images/noimage';
                                    $no_images = \DB::table('noimage_settings')->first();
                                    $images = "";
                                    if($no_images) {
                                        $images =  $no_file_path.'/'.$no_images->category_no_image;
                                    }
                                    ?>
                                    @foreach($store as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->store_name}}</td>
                                            <td>{{$value->store_phone}}</td>
                                            <td>{{$value->store_address1}},{{$value->store_address2}}</td>
                                            <td>{{$value->city}}</td>
                                            <td class="gj_mge_str_img_td">
                                                @if($value->stores_image)
                                                    <a href="{{ asset($file_path.'/'.$value->stores_image)}}" target="_blank"><img src="{{ asset($file_path.'/'.$value->stores_image)}}" class="img-responsive gj_mge_str_img"></a>
                                                @else 
                                                    <a href="{{ asset($images)}}" target="_blank"><img src="{{ asset($images)}}" class="img-responsive gj_mge_str_img"></a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('edit_store', ['id' => $value->id]) }}" data-tooltip="Edit">
                                                    <i class="fa fa-edit fa-2x"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('status_store', ['id' => $value->id]) }}" data-tooltip="block">
                                                    @if($value->is_block == 1)
                                                        <i class="gj_ok fa fa-check fa-2x"></i>
                                                    @else
                                                        <i class="gj_danger fa fa-ban fa-2x"></i>
                                                    @endif
                                                </a>
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
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('#gj_mge_store_table').dataTable({
            "paginate": true,
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
                url: '{{url('/store_block')}}',
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
                url: '{{url('/store_unblock')}}',
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
</script>
@endsection
