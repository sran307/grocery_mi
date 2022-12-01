@extends('layouts.master')
@section('title', 'Manage Cashout Request')
@section('content')

<?php $log = session()->get('user'); ?>

<section class="gj_cash_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage Cashout Request  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Cashout Request  </h5>
                </header>

                <div class="gj_manage_filter">
                    <span class="gj_squaredFour">
                        <input type="checkbox" id="ckbCheckAll" name="ckbCheckAll" />
                        <label for="ckbCheckAll">Check all</label>
                    </span>
                    <button class="btn btn-info" id="export_csv" type="button">Export CSV</button>
                    <button class="btn btn-info" id="export_all_csv" type="button">Export All CSV</button>
                    <a href="#" id="download_csv"><button class="btn btn-info" id="export_csv_but" type="button">Download CSV</button></a>         
                </div>

                <div class="col-md-12">
                    <div class="gj_manage_filter gj_cho_dets">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="gj_l_div">
                                    @if($log)
                                        @if ($log->user_type == 2 || $log->user_type == 3)
                                            <div class="gj_l_dets1">
                                                @if(isset($cash))
                                                    <label>Outstanding Balance <span>Rs. {{$cash->outstand}}</span></label>
                                                @else
                                                    <label>Outstanding Balance <span>Rs. 0.00</span></label>
                                                @endif
                                            </div>
                                        @endif
                                    @endif

                                    @if(isset($cash))
                                        <div class="gj_l_dets2">
                                            <label>Last Request Date <span>{{$cash->last_request_date}}</span></label>
                                        </div>
                                    @endif
                                </div>
                            </div>         
                            <div class="col-md-6">
                                <div class="gj_r_div text-right">
                                    <div class="gj_r_dets1">
                                        <label>Last Request Amount <span>Rs. {{$cash->last_request_amount}}</span></label>
                                    </div>

                                    @if($log)
                                        @if ($log->user_type == 2 || $log->user_type == 3)
                                            <div class="gj_r_dets2">
                                                <a href="{{ route('add_cashout') }}"><button type="button" class="btn btn-info">Create CashOut Request</button></a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>         
                        </div>         
                    </div>

                    <div class="table-responsive gj_manage_cash">
                        @if($log)
                            @if ($log->user_type == 2 || $log->user_type == 3)
                                <div class="gj_cs_srh_div gj_cash_srh_div">
                            @else
                                <div class="gj_cs_srh_div gj_cash_adm_srh_div">
                            @endif
                        @else
                            <div class="gj_cs_srh_div gj_cash_adm_srh_div">
                        @endif

                            {{ Form::open(array('url' => 'search_cashout','class'=>'gj_search_cash_form','files' => true)) }}
                                <div class="gj_1srh">
                                    <label>Start Date</label>
                                    <input type="date" name="gj_srh_srt_date" id="gj_srh_srt_date" class="gj_srh_srt_date">
                                    <label>End Date</label>
                                    <input type="date" name="gj_srh_end_date" id="gj_srh_end_date" class="gj_srh_end_date">

                                    <select name="gj_srh_p_sts" class="gj_srh_p_sts" id="gj_srh_p_sts">
                                        <option value="">Select Payment Status</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Unpaid">Un Paid</option>
                                        <option value="Partial">Partial</option>
                                    </select>

                                    @if($log)
                                        @if ($log->user_type == 1)
                                            <select name="gj_srh_vendor" class="gj_srh_vendor" id="gj_srh_vendor">
                                                <option value="">Select Vendor</option>
                                                @if(sizeof($vendors) != 0)
                                                    @foreach($vendors as $keyz => $valuez)
                                                        <option value="{{$valuez->id}}">{{$valuez->first_name.' '.$valuez->first_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        @endif
                                    @endif

                                    <button type="submit" class="gj_srh_subm btn btn-primary" id="gj_srh_odr_subm">Search</button>
                                </div>
                            {{ Form::close() }}
                        </div>

                        <table class="table table-bordered table-striped" id="gj_mge_cash_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Request Code</th>
                                    <th>Date</th>
                                    <th>Request Amount</th>
                                    <th>Amount Paid</th>
                                    <th>No.of Invoice</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_cash_bdy">
                                @if($cash)
                                    @php ($i = 1)

                                    @foreach($cash as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>{{$value->request_code}}</td>
                                            <td>{{date('d-m-Y', strtotime($value->request_date))}}</td>
                                            <td>Rs. {{$value->request_amount}}</td>
                                            <td>Rs. {{$value->amount_paid}}</td>
                                            <td>{{$value->invoice}}</td>
                                            <td>{{$value->paid_status}}</td>
                                            <td class="gj_com_remark">
                                               <span class="gj_span_rmk">
                                                    <a href="{{ route('view_cashout', ['id' => $value->id]) }}" title="View">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <script>
        $(document).ready(function() { 
            $('#gj_mge_cash_table').dataTable({
                "paginate": true,
                "searching": true,
                "bInfo" : false,
                "sort": true
            });
            $("#download_csv").hide();
            $("#gj_srh_vendor").select2();

            $('p.alert').delay(3000).slideUp(300);
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
        });

        $('#export_csv').on('click',function(){
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
                    url: '{{url('/export_cho_csv')}}',
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

    <!-- Export CSV ALL Script Start -->
    <script type="text/javascript">
        $('#export_all_csv').on('click',function() {
            $.ajax({
                type: 'post',
                url: '{{url('/export_cho_csv')}}',
                data: {type: 'export_all'},
                success: function(response){
                    if(response){
                        window.location.href = "<?php echo route('home'); ?>/" + response;
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
@endsection
