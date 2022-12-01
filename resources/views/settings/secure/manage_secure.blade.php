@extends('layouts.master')
@section('title', 'Manage Login Security')
@section('content')
<section class="gj_secure_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage Login Security  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Login Security  </h5>
                </header>

                <div class="gj_manage_filter">
                    <span class="gj_squaredFour">
                        <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                        <label for="ckbCheckAll">Check all</label>
                    </span>
                    <button class="btn btn-primary" id="Block_value" type="button">Block</button>
                    <button class="btn btn-warning" id="UNBlock_value" type="button">Un Block</button>          
                    <button class="btn btn-danger" id="Delete_value" type="button">Delete</button>          
                </div>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_secure">
                        <table class="table table-bordered table-striped" id="gj_mge_secure_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Edit</th>
                                    <th>Status</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_secure_bdy">
                                @if($secure)
                                    @php ($i = 1)
                                    @foreach($secure as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->question}}</td>
                                            <td>
                                                <a href="{{ route('edit_secure', ['id' => $value->id]) }}" title="Edit">
                                                    <i class="fa fa-edit fa-2x"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('status_secure', ['id' => $value->id]) }}" title="block">
                                                    @if($value->is_block == 1)
                                                        <i class="gj_ok fa fa-check fa-2x"></i>
                                                    @else
                                                        <i class="gj_danger fa fa-ban fa-2x"></i>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" id="{{$value->id}}" class="gj_mge_secure_del" title="Delete">
                                                    <i class="fa fa-trash fa-2x"></i>
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
        $('#gj_mge_secure_table').dataTable({
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
                url: '{{url('/secure_block')}}',
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
                url: '{{url('/secure_unblock')}}',
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
                            url: '{{url('/delete_secure_all')}}',
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

    $('.gj_mge_secure_del').on('click',function(){
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
                        url: '{{url('/delete_secure')}}',
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
@endsection
