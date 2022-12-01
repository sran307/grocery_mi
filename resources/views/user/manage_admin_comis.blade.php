@extends('layouts.master')
@section('title', 'Manage Admin Commision')
@section('content')

<?php $user = session()->get('user'); ?>

<section class="gj_comis_setting">
    <div class="row gj_row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            @include('layouts.account_sidebar')
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class=""><a> Home  </a></li>
                        <li class="active"><a> Manage Admin Commision  </a></li>
                    </ul>
                </div>
            </div> -->

            <div class="gj_box dark">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <header>
                    <div class="gj_icons"><i class="fa fa-edit"></i></div>
                    <h5 class="gj_heading"> Manage Admin Commision  </h5>
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

                <div class="gj_comis_details">
                    @if(isset($admin_amount) && $admin_amount)
                        <p class="gj_admin_amount">Total Admin Commision : Rs. <span> {{$admin_amount}}</span></p>
                    @else
                        <p class="gj_admin_amount">Total Admin Commision : Rs.<span>  0</span></p>
                    @endif

                    @if(isset($vendor_amount) && $vendor_amount)
                        <p class="gj_vendor_amount">Total Vendor Amount :  Rs. <span> {{$vendor_amount}}</span></p>
                    @else
                        <p class="gj_vendor_amount">Total Vendor Amount :  Rs. <span> 0</span></p>
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="table-responsive gj_manage_comis">
                        <div class="gj_cs_srh_div gj_comis_srh_div">
                            {{ Form::open(array('url' => 'search_comis','method' => 'GET','class'=>'gj_search_comis_form','files' => true)) }}
                                <div class="gj_1srh">
                                    <label>Start Date</label>
                                    <input type="date" name="gj_srh_srt_date" id="gj_srh_srt_date" class="gj_srh_srt_date">
                                    <label>End Date</label>
                                    <input type="date" name="gj_srh_end_date" id="gj_srh_end_date" class="gj_srh_end_date">
                                    <input type="text" name="gj_srh_odr_code" id="gj_srh_odr_code" class="gj_srh_odr_code" placeholder="Search By Order Code">
                                </div>

                                <div class="gj_2srh">
                                    <select name="gj_srh_p_sts" class="gj_srh_p_sts" id="gj_srh_p_sts">
                                        <option value="">Select Payment Status</option>
                                        <option value="paid">Paid</option>
                                        <option value="unpaid">Un Paid</option>
                                    </select>

                                    @if($user)
                                        @if ($user->user_type == 1)
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

                        <table class="table table-bordered table-striped" id="gj_mge_comis_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Order Code/Credit Notes Code</th>
                                    <th>Product</th>
                                    <th>Merchant</th>
                                    <th>Quantity</th>
                                    <th>Net Amount</th>
                                    <th>Commision Amount</th>
                                    <th>Vendor Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>remarks</th>
                                </tr>
                            </thead>
                            <tbody id="gj_mge_comis_bdy">
                                @if($comis)
                                    @php ($i = 1)

                                    @foreach($comis as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><input type="checkbox" name="check[]" class="checkBoxClass" value="{{$value->id}}" id="Checkbox{{$i}}" /></td>
                                            <td>
                                                {{$value->type}}
                                            </td>
                                            <td>
                                                @if($value->type == "Order")
                                                    @if($value->order_code)
                                                        <a target="_blank" href="{{ route('view_orders', ['id' => $value->ComisOrders->id]) }}">{{$value->order_code}}</a>
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @elseif($value->type == "Credit Notes")
                                                    @if($value->cn_id)
                                                        @if(isset($value->CNotes->cn_code))
                                                            <a target="_blank" href="{{ route('view_credit_notes', ['id' => $value->cn_id]) }}">{{$value->CNotes->cn_code}}</a>
                                                        @else
                                                            {{'------'}}
                                                        @endif
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->product_id)
                                                    @if(isset($value->ComisProducts->product_title))
                                                        <a href="{{ route('view_product', ['id' => $value->product_id]) }}">
                                                            {{$value->ComisProducts->product_title}}

                                                            @if(isset($value->att_name) && $value->att_name != 0)
                                                                @if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) 
                                                                    <span>({{$value->AttName->att_name}} : {{$value->AttValue->att_value}})</span>
                                                                @endif
                                                            @endif

                                                            <p>Product Code : {{$value->ComisProducts->product_code}}</p>
                                                        </a>
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->merchant_id)
                                                    @if(isset($value->ComisMerchant->first_name))
                                                        <a href="{{ route('view_user', ['id' => $value->merchant_id]) }}">{{$value->ComisMerchant->first_name.' '.$value->ComisMerchant->last_name}}</a>
                                                    @else
                                                        {{'------'}}
                                                    @endif
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->qty)
                                                    {{$value->qty}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->totalprice)
                                                    {{$value->totalprice}}
                                                @else
                                                    {{'------'}}
                                                @endif
                                            </td>
                                            <td>Rs. {{$value->amount}}</td>
                                            <td>Rs. {{$value->merchant_amount}}</td>
                                            <td>{{date('d-m-Y', strtotime($value->created_at))}}</td>
                                            <td>
                                                @if($user)
                                                    @if ($user->user_type == 1)
                                                        <select name="gj_com_pay" id="gj_com_pay" class="gj_com_pay" data-id="{{$value->id}}">
                                                            <option value="">Status</option>
                                                            <option value="1" {{($value->paid_status == 1 ? 'selected' : null)}}>Paid</option>
                                                            <option value="0" {{($value->paid_status == 0 ? 'selected' : null)}}>Un Paid</option>
                                                            <option value="2" {{($value->paid_status == 2 ? 'selected' : null)}}>Partial</option>
                                                        </select>
                                                    @elseif ($user->user_type == 2 || $user->user_type == 3)
                                                        @if ($value->paid_status == 1)
                                                            {{'Paid'}}
                                                        @elseif ($value->paid_status == 2)
                                                            {{'Partial'}}
                                                        @else
                                                            {{'Unpaid'}}
                                                        @endif
                                                    @else
                                                        {{'----'}}
                                                    @endif
                                                @else
                                                    {{'----'}}
                                                @endif

                                                <input type="hidden" name="com_id" id="com_id" value="$value->id">
                                            </td>
                                            <td class="gj_com_remark">
                                               <span class="gj_span_rmk">
                                                    <span class="gj_rmks">{{$value->remarks}}</span>
                                                    <button class="btn btn-primary gj_rmk_edt" id="gj_rmk_edt"><i class="fa fa-edit"></i></button>
                                                    <textarea class="hidden form-control gj_comis_remarks" id="gj_comis_remarks" name="gj_comis_remarks">{{$value->remarks}}</textarea>
                                                    <button data-id="{{$value->id}}" class="btn btn-primary gj_rmk_save hidden" id="gj_rmk_save"><i class="fa fa-save"></i></button>
                                               </span>
                                            </td>
                                        </tr>
                                        @php ($i = $i+1)
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if($comis)
                        {{ $comis->appends(request()->input())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

    <script>
        $(document).ready(function() { 
            $('#gj_mge_comis_table').dataTable({
                "paginate": false,
                "searching": true,
                "bInfo" : false,
                "sort": true
            });
            $("#download_csv").hide();

            $('p.alert').delay(3000).slideUp(300);
            $("#gj_srh_vendor").select2();
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
                    url: '{{url('/export_com_csv')}}',
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
                url: '{{url('/export_com_csv')}}',
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

    <script type="text/javascript">
        $('.gj_com_pay').on('change',function(){
            var id = 0;
            var pay = 0;
            if($(this).attr('data-id')) {
                id = $(this).attr('data-id');
            }

            if($(this).val()) {
                pay = $(this).val();
            }

            if(id != 0) {
                $.confirm({
                    title: '',
                    content: 'Are You Sure to Changed?',
                    icon: 'fa fa-pencil-square-o',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'blue',
                    buttons: {
                        Ok: function(){
                            $.ajax({
                                type: 'post',
                                url: '{{url('/status_comis')}}',
                                data: {id: id, pay: pay, type: 'status'},
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
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Changed The Payment Status after sometimes!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'blue',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
            }
        });
    </script>

    <script type="text/javascript">
        $('.gj_rmk_edt').on('click',function(){
            $(this).closest('tr').find('.gj_comis_remarks').removeClass('hidden');
            $(this).closest('tr').find('.gj_rmk_save').removeClass('hidden');
            $(this).closest('tr').find('.gj_rmks').addClass('hidden');
            $(this).hide();
        });

        $('.gj_rmk_save').on('click',function(){
            var id = 0;
            var remark = 0;
            if($(this).attr('data-id')) {
                id = $(this).attr('data-id');
            }

            if($(this).closest('tr').find('.gj_comis_remarks').val()) {
                remark = $(this).closest('tr').find('.gj_comis_remarks').val();
            }

            if(id != 0) {
                $.confirm({
                    title: '',
                    content: 'Are You Sure to Changed?',
                    icon: 'fa fa-pencil-square-o',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'blue',
                    buttons: {
                        Ok: function(){
                            $.ajax({
                                type: 'post',
                                url: '{{url('/remark_comis')}}',
                                data: {id: id, remark: remark, type: 'status'},
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
            } else {
                $.confirm({
                    title: '',
                    content: 'Please Changed The Payment Status after sometimes!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'blue',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
            }
        });
    </script>
@endsection
