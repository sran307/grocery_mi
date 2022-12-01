@extends('layouts.master')
@section('title', 'Manage All Shipments')
@section('content')
<section class="gj_all_shipments_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.courier_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage All Shipments  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage All Shipments  </h5>
                </header>

                <div class="gj_manage_filter">
                    <span class="gj_squaredFour">
                        <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                        <label for="ckbCheckAll">Check all</label>
                    </span>
                    
                    <button class="btn btn-danger" id="Delete_value" type="button">Delete</button>
                    <button class="btn btn-info" id="export_shipment" type="button">Export Shipmentment</button>
                    <button class="btn btn-info" id="export_all_csv" type="button">Export All CSV</button>
                    <a href="#" id="download_shipment"><button class="btn btn-info" id="export_csv_co_but" type="button">Download Shipment</button></a> 
                </div>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_all_shipodr">
                        <div class="gj_cs_srh_div">
                            {{ Form::open(array('url' => 'search_shipment','method' => 'GET','class'=>'gj_ship_srh_form','files' => true)) }}
                                <input type="date" name="gj_srh_ship_date" id="gj_srh_ship_date" class="gj_srh_ship_date">
                                <input type="text" name="gj_srh_track" id="gj_srh_track" class="gj_srh_track" placeholder="Search By Tracking Number">
                                <button type="submit" class="gj_srh_odr_det btn btn-primary" id="gj_srh_odr_code">Search</button>
                            {{ Form::close() }}
                        </div>

                        <table class="table table-bordered table-striped" id="gj_mge_all_shipodr_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Order Code</th>
                                    <th>Shipment Date</th>
                                    <th>Shipment ID</th>
                                    <th>Shipment Type</th>
                                    <th>Shipment Mode Type</th>
                                    <th>Carrier</th>
                                    <th>AWB</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_all_shipodr_bdy">
                                @if($shipodr)
                                    @php ($i = 1)
                                    
                                    @foreach($shipodr as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->order_code}}</td>
                                            <td>{{ date('d-m-Y', strtotime($value->shipment_date)) }}</td>
                                            <td>{{$value->shipment_id}}</td>
                                            <td>{{$value->type}}</td>
                                            <td>{{$value->mode_type}}</td>
                                            <td>{{$value->carrier}}</td>
                                            <td>{{$value->awb}}</td>
                                            <td>{{$value->shiping_status}}</td>
                                            <td>
                                                <?php $log = session()->get('user'); ?>

                                                @if($log)
                                                    @if($log->user_type == 1)
                                                        <span>
                                                            <a href="{{ route('edit_shipment_order', ['id' => $value->id]) }}" title="Edit">
                                                            <i class="fa fa-edit fa-2x"></i>
                                                        </span>
                                                        
                                                        <span>
                                                            </a>
                                                            <a href="#" id="{{$value->id}}" class="gj_mge_shipodr_del" title="Delete">
                                                                <i class="fa fa-trash fa-2x"></i>
                                                            </a>
                                                        </span>
                                                    @endif
                                                @endif

                                                <span>
                                                    <a href="{{ route('view_shipment_order', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_all_shipodr_sview" title="View">
                                                        <i class="fa fa-eye fa-2x"></i>
                                                    </a>
                                                </span>
                                            </td>
                                        </tr>
                                        @php ($i = $i+1)
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div id="gj_ct_exp"></div>
                    </div>

                    @if($shipodr)
                        {{ $shipodr->appends(request()->input())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('#gj_mge_all_shipodr_table').dataTable({
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

        $('p.alert').delay(2000).slideUp(300);
        $("#download_shipment").hide();
        $("#gj_ct_exp").hide();
    });
</script>

<!-- Delete Script Start -->
<script type="text/javascript">
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
                            url: '{{url('/delete_all_shipment_order')}}',
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

    $('.gj_mge_shipodr_del').on('click',function(){
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
                        url: '{{url('/delete_shipment_order')}}',
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
<!-- Delete Script End -->

<!-- Export Script Start -->
    <script type="text/javascript">
        $('#export_shipment').on('click',function(){
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
                    url: '{{url('/export_shipment_order')}}',
                    data: {ids: all, type: 'export'},
                    success: function(response){
                        if(response){
                            $("#gj_ct_exp").html(response);
                            doit('xlsx');
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

    <!-- Export CSV ALL Script Start -->
    <script type="text/javascript">
        $('#export_all_csv').on('click',function() {
            $.ajax({
                type: 'post',
                url: '{{url('/export_shipment_order')}}',
                data: {type: 'export_all'},
                success: function(response){
                    if(response) {
                        $("#gj_ct_exp").html(response);
                        doit('xlsx');
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

<script type="text/javascript" src="{{ asset('js/excel/shim.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/excel/xlsx.full.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/excel/Blob.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/excel/FileSaver.js')}}"></script>

<script>
function doit(type, fn, dl) {
    var elt = document.getElementById('gj_shp_exp');
    var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
    return dl ?
        XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
        XLSX.writeFile(wb, fn || ('shipment.' + (type || 'xlsx')));
}
</script>
<!-- Export Script End -->
@endsection
