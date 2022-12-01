@extends('layouts.master')
@section('title', 'Manage Credits')
@section('content')

<?php $log = session()->get('user'); ?>

<section class="gj_crdt_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage Credits  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Credits  </h5>
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
                    <div class="table-responsive gj_manage_credits">
                        @if($log)
                            @if($log->user_type == 1)
                                <table class="table table-bordered table-striped" id="gj_mge_credits_table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>#</th>
                                            <th>Name </th>
                                            <th>Business Name</th>
                                            <th>Phone</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gj_mge_credits_bdy">
                                        @if($credits)
                                            @php ($i = 1)
                                            
                                            @foreach($credits as $key => $value)
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                                    <td>
                                                        {{$value->first_name}} {{$value->last_name}}
                                                    </td>
                                                    <td>
                                                        @if($value->bussiness_name)
                                                            {{$value->bussiness_name}}
                                                        @else
                                                            {{'-------'}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$value->phone}}
                                                    </td>
                                                    <td>{{date('d-m-Y' ,strtotime($value->created_at))}}</td>
                                                    <td>
                                                        <a href="{{ route('add_credits', ['id' => $value->id]) }}"> 
                                                            <button class="btn btn-success">Add</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php ($i = $i+1)
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            @elseif ($log->user_type == 2 || $log->user_type == 3)
                                <table class="table table-bordered table-striped" id="gj_mge_credits_table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>#</th>
                                            <th>Previous Credits</th>
                                            <th>Add/Deduct on Credits</th>
                                            <th>Current Credits</th>
                                            <th>Add Date Credits</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gj_mge_credits_bdy">
                                        @if($credits)
                                            @php ($i = 1)
                                            
                                            @foreach($credits as $key => $value)
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                                    <td>
                                                        @if($value->previous_credits)
                                                            {{'Rs. '.$value->previous_credits}}
                                                        @else
                                                            {{'Rs. '.'0.00'}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->add_credits)
                                                            {{'Rs. '.$value->add_credits}}
                                                        @else
                                                            {{'Rs. '.'0.00'}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->current_credits)
                                                            {{'Rs. '.$value->current_credits}}
                                                        @else
                                                            {{'Rs. '.'0.00'}}
                                                        @endif
                                                    </td>
                                                    <td>{{date('d-m-Y' ,strtotime($value->created_at))}}</td>
                                                    <td>
                                                        {{$value->remarks}}
                                                    </td>
                                                </tr>
                                                @php ($i = $i+1)
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            @endif
                        @else
                            <p class="gj_no_data">Data Not Availible</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('#gj_mge_credits_table').dataTable({
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
                url: '{{url('/credits_block')}}',
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
                url: '{{url('/credits_unblock')}}',
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
                            url: '{{url('/delete_credits_all')}}',
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

    $('.gj_mge_credits_del').on('click',function(){
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
                        url: '{{url('/delete_credits')}}',
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
