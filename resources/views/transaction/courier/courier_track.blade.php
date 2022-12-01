@extends('layouts.master')
@section('title', 'Manage All Orders')
@section('content')
<section class="gj_all_orders_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.courier_sidebar')
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
                                
                    <button class="btn btn-info" id="export_co_csv" type="button">Export Courier Format</button>
                    <button class="btn btn-info" id="export_all_csv" type="button">Export All CSV</button>
                    <a href="#" id="download_co_csv"><button class="btn btn-info" id="export_csv_co_but" type="button">Download Courier CSV</button></a>            
                </div>

                <div class="col-md-12">
                    <div class="gj_co_instuct">
                        <ul class="gj_co_ins_ul">
                            <li>Export The Courier Format to Needed Orders.</li>
                            <li>Import the Shylite Dashboard.</li>
                            <li>Shipment Status to Exported.</li>
                            <li>Then Import or Add to Shipment Details in Our Dashboard.</li>
                        </ul>
                    </div>

                    <div class="table-responsive gj_manage_all_orders">
                        <div class="gj_cs_srh_div">
                            {{ Form::open(array('url' => 'search_cou_order','method' => 'GET','class'=>'gj_search_cou_order_form','files' => true)) }}
                                <input type="date" name="gj_srh_odr_date" id="gj_srh_odr_date" class="gj_srh_odr_date">
                                <input type="text" name="gj_srh_odr_code" id="gj_srh_odr_code" class="gj_srh_odr_code" placeholder="Search By Order Code">
                                <button type="submit" class="gj_srh_odr_det btn btn-primary" id="gj_srh_odr_code">Search</button>
                            {{ Form::close() }}
                        </div>

                        <table class="table table-bordered table-striped" id="gj_mge_all_orders_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Order Code</th>
                                    <th>Order Staus</th>
                                    <th>Order Date</th>
                                    <th>Customer Name</th>
                                    <th>Total Items</th>
                                    <th>Total Price</th>
                                    <th>Action</th>
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
                                            <td>
                                                @if($value->order_status == 1)
                                                    {{'Order Placed'}}
                                                @elseif($value->order_status == 2)
                                                    {{'Order Dispatched'}}
                                                @elseif($value->order_status == 3)
                                                    {{'Order Delivered'}}
                                                @elseif($value->order_status == 4)
                                                    {{'Order Complete'}}
                                                @elseif($value->order_status == 5)
                                                    {{'Order Cancelled'}}
                                                @else
                                                    {{'----'}}
                                                @endif
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($value->order_date)) }}</td>
                                            <td>{{$value->contact_person}}</td>
                                            <td>{{$value->total_items}}</td>
                                            <td>Rs. {{$value->net_amount}}</td>
                                            <td>
                                                <a href="{{ route('view_courier_track', ['id' => $value->id]) }}" id="{{$value->id}}" class="gj_mge_all_orders_sview" title="View">
                                                    <i class="fa fa-eye fa-2x"></i>
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
                        {{ $orders->appends(request()->input())->links() }}
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
        $("#download_co_csv").hide();
        $("#gj_ct_exp").hide();
    });
</script>

<!-- Export Script Start -->
    <script type="text/javascript">
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
                url: '{{url('/export_co_csv_order')}}',
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
    var elt = document.getElementById('gj_co_exp');
    var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
    return dl ?
        XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
        XLSX.writeFile(wb, fn || ('Courier_Orders.' + (type || 'xlsx')));
}
</script>
<!-- Export Script End -->
@endsection
