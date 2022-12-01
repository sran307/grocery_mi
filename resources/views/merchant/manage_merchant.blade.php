@extends('layouts.master')
@section('title', 'Manage Merchant')
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
                        <li class="active"><a> Manage Merchant  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Merchant  </h5>
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
                    <div class="table-responsive gj_manage_merchant">
                        <table class="table table-bordered table-striped" id="gj_mge_merchant_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Name & E-Mail</th>
                                    <th>Store Name</th>
                                    <th>District</th>
                                    <th>Add Branch</th>
                                    <th>Manage Branch</th>
                                    <th>Approved</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>User Type</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_merchant_bdy">
                                @if($merchant)
                                    @php ($i = 1)
                                    
                                    @foreach($merchant as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td class="gj_m_n_e">
                                                <p class="gj_m_name">{{$value->first_name}}</p>
                                                <p class="gj_m_email">{{$value->email}}</p>
                                            </td>
                                            <td>
                                                @if(($value->store) && (count($value->store) != 0))
                                                    {{$value->store[0]['store_name']}}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                            <td>{{$value->city}}</td>
                                            <td>
                                                <a href="{{ route('add_store', ['id' => $value->id]) }}" data-tooltip="add">
                                                    <i class="fa fa-plus fa-2x"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('manage_store', ['id' => $value->id]) }}" data-tooltip="add">
                                                    <i class="fa fa-shopping-cart fa-2x"></i>
                                                    <span class="gj_cnt_sc">( {{$value->c_store}} ) Stores </span>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('approve_merchant', ['id' => $value->id]) }}" data-tooltip="Approve/Dis Approve">
                                                    @if($value->is_approved == 1)
                                                        <i class="gj_ok fa fa-check fa-2x"></i>
                                                    @else
                                                        <i class="gj_danger fa fa-ban fa-2x"></i>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                <span>
                                                    <a href="{{ route('edit_merchant', ['id' => $value->id]) }}" data-tooltip="Edit">
                                                        <i class="fa fa-edit fa-2x"></i>
                                                    </a>
                                                </span>

                                                <span>
                                                    <a href="{{ route('view_merchant', ['id' => $value->id]) }}" data-tooltip="View">
                                                        <i class="fa fa-eye fa-2x"></i>
                                                    </a>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('status_merchant', ['id' => $value->id]) }}" data-tooltip="block">
                                                    @if($value->is_block == 1)
                                                        <i class="gj_ok fa fa-check fa-2x"></i>
                                                    @else
                                                        <i class="gj_danger fa fa-ban fa-2x"></i>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                @if($value->user_type == 2)
                                                    Admin Add Merchant
                                                @elseif($value->user_type == 3)
                                                    Website Merchant
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
        $('#gj_mge_merchant_table').dataTable({
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
                url: '{{url('/merchant_block')}}',
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
                url: '{{url('/merchant_unblock')}}',
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