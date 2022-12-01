@extends('layouts.master')
@section('title', 'Manage Main Category')
@section('content')
<section class="gj_email_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage Main Category  </a></li>
                    </ul>
                </div>
            </div>

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Main Category  </h5>
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
                    <div class="table-responsive gj_manage_cnty">
                        <table class="table table-bordered table-striped" id="gj_mge_main_cat_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Main Category Name</th>
                                    <th class="gj_mge_mc_img_th">Main Category Image</th>
                                    <th>Add Sub Category</th>
                                    <th>Manage Sub Category</th>
                                    <th>Home Page View</th>
                                    <th>Edit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_mc_bdy">
                                @if($main)
                                    @php ($i = 1)
                                    <?php 
                                    $file_path = 'images/main_cat_image';
                                    $no_file_path = 'images/noimage';
                                    $no_images = \DB::table('noimage_settings')->first();
                                    $images = "";
                                    if($no_images) {
                                        $images =  $no_file_path.'/'.$no_images->category_no_image;
                                    }
                                    ?>
                                    @foreach($main as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->main_cat_name}}</td>
                                            <td class="gj_mge_mc_img_td">
                                                @if($value->main_cat_image)
                                                    <a href="{{ asset($file_path.'/'.$value->main_cat_image)}}" target="_blank"><img src="{{ asset($file_path.'/'.$value->main_cat_image)}}" class="img-responsive gj_mge_mc_img"></a>
                                                @else 
                                                    <a href="{{ asset($images)}}" target="_blank"><img src="{{ asset($images)}}" class="img-responsive gj_mge_mc_img"></a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('add_sub_category', ['id' => $value->id]) }}" data-tooltip="Add Main Category">
                                                    <i class="fa fa-plus fa-2x"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('manage_sub_category', ['id' => $value->id]) }}" data-tooltip="Manage Main Category">
                                                    <i class="fa fa-shopping-cart fa-2x"></i>
                                                    <span class="gj_cnt_sc">( {{$value->sub}} ) Categories </span>
                                                </a>
                                            </td>
                                            <td>
                                                <select id="is_home" class="form-control gj_cat_home">
                                                    <option <?php if($value->is_home == 0) { echo "selected"; } ?> value="0">Select Home Page View</option>
                                                    <option <?php if($value->is_home == 1) { echo "selected"; } ?> value="1">1</option>
                                                    <option <?php if($value->is_home == 2) { echo "selected"; } ?> value="2">2</option>
                                                    <option <?php if($value->is_home == 3) { echo "selected"; } ?> value="3">3</option>
                                                </select>
                                                <input type="hidden" id="is_home_id" value="{{$value->id}}">
                                            </td>
                                            <td>
                                                <a href="{{ route('edit_category', ['id' => $value->id]) }}" data-tooltip="Edit">
                                                    <i class="fa fa-edit fa-2x"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('status_category', ['id' => $value->id]) }}" data-tooltip="block">
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
        $('#gj_mge_main_cat_table').dataTable({
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
                url: '{{url('/category_block')}}',
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
                url: '{{url('/category_unblock')}}',
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

    $('body').on('change','#is_home',function() {
        var is_home = 0;
        var id = 0;
        if($(this).val()){
            is_home = $(this).val();
            id = $(this).next().val();
            if((is_home != 0) && (id != 0)) {
                $.ajax({
                    type: 'post',
                    url: '{{url('/home_view')}}',
                    data: {is_home: is_home, id: id, type: 'home_view'},
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
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Select Another Time!',
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
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Another Time!',
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
</script>
@endsection
