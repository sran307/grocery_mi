@extends('layouts.master')
@section('title', 'Manage All Orders')
@section('content')
<section class="gj_all_orders_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.transaction_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage All Orders  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage All Orders  </h5>
                </header>

                <div class="gj_manage_filter">
                    <span class="gj_squaredFour">
                        <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                        <label for="ckbCheckAll">Check all</label>
                    </span>
                    <button class="btn btn-danger" id="Delete_value" type="button">Delete</button>          
                    <button class="btn btn-info" id="export_csv" type="button">Export CSV</button>
                    <a href="#" id="download_csv"><button class="btn btn-info" id="export_csv_but" type="button">Download CSV</button></a>
                                
                    <button class="btn btn-info" id="export_co_csv" type="button">Export Courier Format</button>
                    <a href="#" id="download_co_csv"><button class="btn btn-info" id="export_csv_co_but" type="button">Download Courier CSV</button></a>            
                </div>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_all_orders">
                        <div class="gj_cs_srh_div">
                            <form method="post" action="/search_order">
                                <input type="date" name="gj_srh_odr_date" id="gj_srh_odr_date" class="gj_srh_odr_date">
                                <input type="text" name="gj_srh_odr_code" id="gj_srh_odr_code" class="gj_srh_odr_code" placeholder="Search By Order Code">
                                <button type="submit" class="gj_srh_odr_det btn btn-primary" id="gj_srh_odr_code">Search</button>
                            </form>
                        </div>

                        <table class="table table-bordered table-striped" id="gj_mge_all_orders_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Order Code</th>
                                    <th>Order Date</th>
                                    <th>Customer Name</th>
                                    <th>Total Items</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_all_orders_bdy">
                                @if($orders)
                                    @php ($i = 1)
                                    
                                    @foreach($orders as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->order_code}}</td>
                                            <td>{{ date('d-m-Y', strtotime($value->order_date)) }}</td>
                                            <td>{{$value->contact_person}}</td>
                                            <td>{{$value->total_items}}</td>
                                            <td>{{$value->net_amount}}</td>
                                            <td>
                                                <select name="status" id="staus" data-odr-id="{{$value->id}}" class="form-control gj_odr_sts">
                                                    <option value="0" @if($value->order_status == 0) {{'selected'}} @endif>-- Select Order Status --</option>
                                                    <option value="1" @if($value->order_status == 1) {{'selected'}} @endif>Order Placed</option>
                                                    <option value="2" @if($value->order_status == 2) {{'selected'}} @endif>Order Dispatched</option>
                                                    <option value="3" @if($value->order_status == 3) {{'selected'}} @endif>Order Delivered </option>
                                                    <option value="4" @if($value->order_status == 4) {{'selected'}} @endif>Order Complete</option>
                                                    <option value="5" @if($value->order_status == 5) {{'selected'}} @endif>Order Cancelled</option>
                                                </select>
                                            </td>
                                            <td>
                                                <a href="{{ route('edit_orders', ['id' => $value->id]) }}" data-tooltip="Edit">
                                                    <i class="fa fa-edit fa-2x"></i>
                                                </a>
                                                <a href="{{ route('view_orders', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_all_orders_sview" title="View">
                                                    <i class="fa fa-eye fa-2x"></i>
                                                </a>
                                                <a href="#" id="{{$value->id}}" class="gj_mge_all_orders_del" title="Delete">
                                                    <i class="fa fa-trash fa-2x"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php ($i = $i+1)
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div id="gj_ct_exp"></div>
                    </div>

                    @if($orders)
                        {{$orders->links()}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('#gj_mge_all_orders_table').dataTable({
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
        $("#download_csv").hide();
        $("#download_co_csv").hide();
        $("#gj_ct_exp").hide();
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
                            url: '{{url('/delete_all_orders')}}',
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

    $('.gj_mge_all_orders_del').on('click',function(){
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
                        url: '{{url('/delete_orders')}}',
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

    $('.gj_odr_sts').on('change',function(){
        var id = 0;
        var status = 0;
        if($(this).attr('data-odr-id')) {
            id = $(this).attr('data-odr-id');
        }

        if($(this).val()) {
            status = $(this).val();
        }

        $.ajax({
            type: 'post',
            url: '{{url('/status_orders')}}',
            data: {id: id, status: status, type: 'staus_change'},
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
    });
</script>

<!-- Export Script Start -->
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
                url: '{{url('/export_csv_order')}}',
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

    $('#export_co_csv').on('click',function(){
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
                url: '{{url('/export_co_csv_order')}}',
                data: {ids: all, type: 'export'},
                success: function(response){
                    if(response){
                        // $("#download_co_csv").show();
                        // $("#download_co_csv").attr("href", response);
                        $("#gj_ct_exp").html(response);
                        // tableToExcel('gj_co_exp', 'gjjj');
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

<script type="text/javascript">
    var tableToExcel = (function() {
      var uri = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
      return function(table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
        window.location.href = uri + base64(format(template, ctx))
      }
    })()
</script>

<script type="text/javascript" src="{{ asset('js/excel/shim.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/excel/xlsx.full.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/excel/Blob.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/excel/FileSaver.js')}}"></script>

<script>
function doit(type, fn, dl) {
    var elt = document.getElementById('gj_co_exp');
    var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
    return dl ?
        XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
        XLSX.writeFile(wb, fn || ('gj.' + (type || 'xlsx')));
}
</script>
<!-- Export Script End -->
@endsection
