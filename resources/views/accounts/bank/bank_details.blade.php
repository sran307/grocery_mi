<?php 
    $log = session()->get('user');
?>
@extends('layouts.master')
@section('title', 'Manage Bank Details')
@section('content')
<section class="gj_bk_det_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage Bank Details  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Bank Details  </h5>
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
                    <div class="table-responsive gj_manage_bnks">
                        <table class="table table-bordered table-striped" id="gj_mge_bnks_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>A/C No</th>
                                    <th>A/C Holder Name</th>
                                    <th>Bank Name</th>
                                    <th>Branch Name</th>
                                    <th>IFSC</th>

                                    @if(isset($log->user_type) && ($log->user_type == 3 || $log->user_type == 2))
                                        <th>Default</th>
                                    @endif

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_bnks_bdy">
                                @if($banks)
                                    @php ($i = 1)
                                    
                                    @foreach($banks as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->ac_no}}</td>
                                            <td>{{$value->ac_name}}</td>
                                            <td>{{$value->bank_name}}</td>
                                            <td>{{$value->bank_branch}}</td>
                                            <td>{{$value->bank_ifsc}}</td>

                                            @if(isset($log->user_type) && ($log->user_type == 3 || $log->user_type == 2))
                                                <td>
                                                    <input type="radio" <?php if($value->default == 1) { echo "Checked"; } ?> value="{{$value->id}}" name="default" id="default_{{$value->id}}" class="gj_default">
                                                </td>
                                            @endif
                                            <td>
                                                @if(isset($log->user_type) && ($log->user_type == 3 || $log->user_type == 2))
                                                    <span>
                                                        <a href="{{ route('edit_bank_details', ['id' => $value->id]) }}" title="Edit">
                                                            <i class="fa fa-edit fa-2x"></i>
                                                        </a>
                                                    </span>
                                                    <span>
                                                        <a href="{{ route('view_bank_details', ['id' => $value->id]) }}" title="View">
                                                            <i class="fa fa-eye fa-2x"></i>
                                                        </a>
                                                    </span>
                                                    <span>
                                                        <a href="#" id="{{$value->id}}" class="gj_mge_bnks_del" data-tooltip="Delete">
                                                            <i class="fa fa-trash fa-2x"></i>
                                                        </a>
                                                    </span>
                                                @else
                                                    <span>
                                                        <a href="{{ route('view_bank_details', ['id' => $value->id]) }}" title="View">
                                                            <i class="fa fa-eye fa-2x"></i>
                                                        </a>
                                                    </span>
                                                @endif
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
        $('#gj_mge_bnks_table').dataTable({
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
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                        
                    }
                }
            });
        } else {
            $.ajax({
                type: 'post',
                url: '{{url('/bnks_block')}}',
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
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                        
                    }
                }
            });
        } else {
            $.ajax({
                type: 'post',
                url: '{{url('/bnks_unblock')}}',
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
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
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
                            url: '{{url('/delete_all_bank_details')}}',
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

    $('.gj_mge_bnks_del').on('click',function(){
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
                        url: '{{url('/delete_bank_details')}}',
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

<!-- Default Set Start -->
<script type="text/javascript">
    $(".gj_default").change(function(){
        if ($(this).prop("checked")){
            var id = $(this).val();
            if(!id) {
                $.confirm({
                    title: '',
                    content: 'Please Select Anyone to Default!',
                    icon: 'fa fa-ban',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                            
                        }
                    }
                });
            } else {
                $.ajax({
                    type: 'post',
                    url: '{{url('/bank_default')}}',
                    data: {id: id, type: 'default'},
                    success: function(data){
                        if(data == 0){
                            window.location.reload();
                        } else if(data == 2) {
                            $.confirm({
                                title: '',
                                content: 'You Are Not Permission to Access!',
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
                        } else if(data == 3) {
                            $.confirm({
                                title: '',
                                content: 'You Are Not Login!',
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
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Anyone to Default!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                        window.location.reload();
                    }
                }
            });
        }
    });
</script>
<!-- Default Set End -->
@endsection
