@extends('layouts.master')
@section('title', 'Manage Inventory Sub Stock Details')
@section('content')
<section class="gj_m_stock_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.product_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage Inventory Sub Stock Details  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Inventory Sub Stock Details  </h5>
                </header>

                <!-- <div class="gj_manage_filter">
                    <span class="gj_squaredFour">
                        <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                        <label for="ckbCheckAll">Check all</label>
                    </span>

                    <button class="btn btn-info" id="export_csv" type="button">Export CSV</button>
                    <a href="#" id="download_csv"><button class="btn btn-info" id="export_csv" type="button">Download CSV</button></a>
                </div> -->

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_stock">
                        <table class="table table-bordered table-striped" id="gj_mge_substock_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Attribute</th>
                                    <th>Qty</th>
                                    <th>Previous On hand Quantity</th>
                                    <th>Current Quantity</th>
                                    <th>Add on Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_substock_bdy">
                                @if($sub_stock)
                                    @php ($i = 1)
                                    
                                    @foreach($sub_stock as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>
                                                @if($value->product_id)
                                                    @if($value->Products->product_title)
                                                        {{$value->Products->product_title}}
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>

                                            <td>
                                                @if($value->attribute)
                                                    @if($value->Attribute->AttributeName->att_name && $value->Attribute->AttributeValue->att_value)
                                                        {{$value->Attribute->AttributeName->att_name .' - '. $value->Attribute->AttributeValue->att_value}}
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>

                                            <td>
                                                @if($value->date)
                                                    <?php echo date("d-m-Y", strtotime($value->date)); ?>
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                            <td>{{$value->previous_qty}}</td>
                                            <td>{{$value->current_qty}}</td>
                                            <td>{{$value->addon_qty}}</td>
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
<!-- 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script> -->

<script>
    $(document).ready(function() { 
        $('#gj_mge_substock_table').dataTable({
            "paginate": true,
            "searching": true,
            "bInfo" : false,
            "sort": true
            // dom: 'Bfrtip',
            // buttons: [
            //     'csv', 'excel', 'pdf', 'print'
            // ]
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

        $('p.alert').delay(5000).slideUp(500);
    });
</script>

<!-- Export CSV Script Start -->
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
                url: '{{url('/export_stock_csv')}}',
                data: {ids: all, type: 'csv'},
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
</script>
<!-- Export CSV Script End -->
@endsection
