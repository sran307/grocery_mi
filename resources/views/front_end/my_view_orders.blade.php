@extends('layouts.frontend')
@section('title', 'View Orders')
<!--<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">-->
@section('content')
@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

                        @include('layouts.normal_user_sidebar')
                   <div class="col-lg-9 col-md-8">
<div class="dashboard-right">
<div class="row">
<div class="col-md-12">
    <div role="tabpanel" class="tab-pane fadein active" id="Section4">
             <div class="gj_back_div text-right">
                <a href="{{url('get_orders')}}"><button type="button" class="gj_bck_btn btn btn-primary">My Orders</button></a>
            </div>
            <h3> View Orders   </h3>

            @if(isset($orders))
                <div class= "table-responsive">
                    <table class="table text-center table-bordered">
                        <tr>
                            <th colspan="2">Order Code</th>
                            <td colspan="3">{{$orders->order_code}}</td>
                        </tr>

                        <tr>
                            <th colspan="2">Order Date</th>
                            <td colspan="3">{{ date('d-m-Y', strtotime($orders->order_date)) }}</td>
                        </tr>

                        <tr>
                            <th colspan="2">Payment Mode</th>
                            <td colspan="3">
                                @if($orders->payment_mode == 0)
                                    {{'------'}}
                                @elseif ($orders->payment_mode == 1)
                                    {{'Cash On Delivery'}}
                                @elseif ($orders->payment_mode == 2)
                                    {{'Online Payment'}}
                                @else
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Delivery Date</th>
                            <td colspan="3">
                                @if($orders->delivery_date)
                                    {{ date('d-m-Y', strtotime($orders->delivery_date)) }}
                                @else
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Order Status</th>
                            <td colspan="3">
                                @if($orders->order_status == 0)
                                    {{'------'}}
                                @elseif($orders->order_status == 1)
                                    {{'Order Placed'}}
                                @elseif ($orders->order_status == 2)
                                    {{'Order Dispatched'}}
                                @elseif ($orders->order_status == 3)
                                    {{'Order Delivered'}}
                                @elseif ($orders->order_status == 4)
                                    {{'Order Complete'}}
                                @elseif ($orders->order_status == 5)
                                    {{'Order Cancelled'}}
                                @else
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>
                        
                        <tr>
                            <th colspan="2">Contact Person</th>
                            <td colspan="3">{{$orders->contact_person}}</td>
                        </tr>
                        
                        <tr>
                            <th colspan="2">Contact Number</th>
                            <td colspan="3">{{$orders->contact_no}}</td>
                        </tr>

                        <tr>
                            <th colspan="2">Shipping Address</th>
                            <td colspan="3">{{$orders->shipping_address}}</td>
                        </tr>

                        <tr>
                            <th colspan="2">Total Items</th>
                            <td colspan="3">{{$orders->total_items}}</td>
                        </tr>

                        <tr>
                            <th colspan="2">Discount</th>
                            <td colspan="3">
                                @if($orders->discount_flag)
                                    {{$orders->discount_flag}}
                                @else
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Discount Rate</th>
                            <td colspan="3">
                                @if($orders->discount)
                                    {{$code}} {{$orders->discount}}
                                @else
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Shipping Charge</th>
                            <td colspan="3">
                                @if($orders->shipping_charge)
                                    {{$code}} 
                                    {{$orders->shipping_charge}}
                                @else
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">COD Charge</th>
                            <td colspan="3">
                                @if($orders->cod_charge)
                                    {{$code}} 
                                    {{$orders->cod_charge}}
                                @else
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Tax Amount</th>
                            <td colspan="3">
                                @if($orders->tax_amount)
                                    {{$code}} {{$orders->tax_amount}}
                                @else
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Net Amount</th>
                            <td colspan="3">
                                @if($orders->net_amount)
                                    {{$code}} {{$orders->net_amount}}
                                @else
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Payment Status</th>
                            <td colspan="3">
                                @if($orders->payment_status == 0)
                                    {{'Pending'}}
                                @elseif($orders->payment_status == 1)
                                    {{'Success'}}
                                @elseif ($orders->payment_status == 2)
                                    {{'Failed'}}
                                @else
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Delivery Status</th>
                            <td colspan="3">
                                @if($orders->delivery_status == 0)
                                    {{'------'}}
                                @elseif ($orders->delivery_status == 1)
                                    {{'Success'}}
                                @elseif ($orders->delivery_status == 2)
                                    {{'Failed'}}
                                @else 
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Return Order Status</th>
                            <td colspan="3">
                                @if($orders->return_order_status == 0)
                                    {{'------'}}
                                @elseif ($orders->return_order_status == 1)
                                    {{'Order Return Initialized'}}
                                @elseif ($orders->return_order_status == 2)
                                    {{'Order Return Confirmed'}}
                                @elseif ($orders->return_order_status == 3)
                                    {{'Order Return Cancelled'}}
                                @else 
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">RePlace Order</th>
                            <td colspan="3">
                                {{$orders->replace_order}}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Reference Order</th>
                            <td colspan="3">
                                @if($orders->ref_order_id)
                                    @if($orders->Reference->order_code)
                                        {{$orders->Reference->order_code}}
                                    @else 
                                        {{'------'}}
                                    @endif
                                @else 
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Remarks</th>
                            <td colspan="3">
                                @if($orders->remarks)
                                    {{$orders->remarks}}
                                @else 
                                    {{'------'}}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th colspan="4"></th>
                        </tr>

                        @if(count($orders['details']) != 0) 
                            <tr>
                                <th>Title</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Tax</th>
                                <th>Total</th>
                            </tr>
                            @foreach ($orders['details'] as $key => $value)
                                <tr>
                                    <td>
                                        {{$value->product_title}}

                                        @if(isset($value->att_name) && $value->att_name != 0)
                                            @if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) 
                                                <span>({{$value->AttName->att_name}} : {{$value->AttValue->att_value}})</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{$value->order_qty}}</td>
                                    <td>{{$code}} {{$value->unitprice}}</td>
                                    <td>{{$code}} {{$value->tax_amount}}</td>
                                    <td>{{$code}} {{$value->totalprice}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            @else
                <p class="gj_no_data">Orders is Empty</p>
            @endif

           
        </div>               
                   
        <?php
                $user = session()->get('user');
            ?>
    </div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>   
        
   

<script>     
    $(document).ready(function(){
        $('.vertical-tab .nav-tabs li a[href="#Section4"]').tab('show');
        $('.vertical-tab .nav-tabs li').removeClass('active'); 
        $('.vertical-tab .nav-tabs li a[href="#Section4"]').parent().addClass('active');

        $('.vertical-tab .nav-tabs li').click(function(){ 
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $(this).addClass('active'); 
        });

        $('#logout').click(function(){ 
            window.location.href = "{{ route('logout') }}";
        });

        $('.buzin').click(function(){ 
            $(".buzzacc").toggle(); 
        })
    });
</script>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(7000).slideUp(500); 
        $("#country").select2();
    });
</script>

<script>     
    $(document).ready(function() {
        <?php if(isset($_GET['tab_id']) && $_GET['tab_id'] == 'Section4') { ?>
            $('.vertical-tab .nav-tabs li a[href="#Section4"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#Section4"]').parent().addClass('active');
        <?php } ?>

        $('.vertical-tab .nav-tabs li').click(function(){ 
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $(this).addClass('active'); 
        });

        $('#logout').click(function(){ 
            window.location.href = "{{ route('logout') }}";
        });

        $('.buzin').click(function(){ 
            $(".buzzacc").toggle(); 
        })

    });
</script>

<script>
    // $('body').on('click','.gj_myacc_pge ul.pagination li',function() {
    //     $('a[href="#Section4"]').trigger();                                                                      
    // });
    function getUrlVars() {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(800); 
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();

        var trgr = false;
        var url = document.location.href;
        var res = url.toString().split('#');

        if(res[1]) {
            var trgr = res[1];
        }

        if(trgr) {
            $('.vertical-tab .nav-tabs li a[href="#' + trgr + '"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#' + trgr + '"]').parent().addClass('active');
        }

        var country = $('#country').select2('val');
        @if($user->state)
            var state = <?php echo $user->state; ?>;
        @else
            var state = 0;
        @endif

        @if($user->city)
            var city = <?php echo $user->city; ?>;
        @else
            var city = 0;
        @endif

        if(city) {
            city = city;          
        } else {
            city = 0;
        }

        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, state: state, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");

                        var st = $('#state').val();
                        if(st) {
                            $.ajax({
                                type: 'post',
                                url: '{{url('/select_city')}}',
                                data: {st: st, city: city, type: 'city'},
                                success: function(data){
                                    if(data){
                                        $("#city").html(data);
                                        $("#city").removeAttr("disabled");
                                    } else {
                                        $.confirm({
                                            title: '',
                                            content: 'Please Select State!',
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
                                        $("#city").prop("disabled", true);
                                    }
                                }
                            });
                        } else {
                            /*$.confirm({
                                title: '',
                                content: 'Please Select State!',
                                icon: 'fa fa-ban',
                                theme: 'modern',
                                closeIcon: true,
                                animation: 'scale',
                                type: 'red',
                                buttons: {
                                    Ok: function(){
                                    }
                                }
                            });*/
                        }
                    } else {
                       /* $.confirm({
                            title: '',
                            content: 'Please Select Country!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'red',
                            buttons: {
                                Ok: function(){
                                }
                            }
                        });*/
                        $("#state").prop("disabled", true);
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            /*$.confirm({
                title: '',
                content: 'Please Select Country!',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                    }
                }
            });*/
        }

        @if(isset($user['docs']))
            var cnt = <?php echo count($user['docs']) + 1;?>;
        @else
            var cnt = 2;
        @endif

        $("#img_addButton").click(function () {
            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'gj_tr_m_doc_' + cnt);
            newTextBoxDiv.after().html('<td><input class="form-control gj_d_name" placeholder="Enter Product Name" name="d_name[]" type="text" id="d_name_' + cnt + '"></td><td><input type="file" name="d_image[]" id="d_image_' + cnt + '" class="gj_d_image form-control"></td><td><button type="button" id="img_removeButton_' + cnt + '" class="gj_m_doc_rem"><i class="fa fa-trash"></i></button></td>');
            newTextBoxDiv.appendTo("#gj_m_doc_bdy");
            cnt++;
        });

        $('body').on('click','.gj_m_doc_rem',function() {
            if(cnt==1){
                $.confirm({
                    title: '',
                    content: 'No more textbox to remove!',
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
                return false;
            }   
        
            cnt--;
            $(this).closest('tr').remove();
        });
    });

    $('#country').on('change',function() {
        var country = $(this).val();
        if(country) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_state')}}',
                data: {country: country, type: 'state'},
                success: function(data){
                    if(data){
                        $("#state").html(data);
                        $("#state").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Country!',
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
                        $("#state").prop("disabled", true);
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select Country!',
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
        }
    });

    $('#state').on('change',function() {
        var st = $(this).val();
        if(st) {
            $.ajax({
                type: 'post',
                url: '{{url('/select_city')}}',
                data: {st: st, type: 'city'},
                success: function(data){
                    if(data){
                        $("#city").html(data);
                        $("#city").removeAttr("disabled");
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select State!',
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
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'Please Select State!',
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
        }
    });
</script>
<script type="text/javascript">
    $('body').on('click','.gj_my_codr',function() {
        var id = 0;                                                       
        var th = $(this);                                                       
        if($(this).attr('data-id')){
            id = $(this).attr('data-id');
        }   
    
        if(id != 0) {
            $.confirm({
                title: '',
                content: 'Are You Sure to Cancel this Order?',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'purple',
                buttons: {
                    Ok: function(){
                        $.ajax({
                            type: 'post',
                            url: '{{url('/customer_cancel_order')}}',
                            data: {id: id, type: 'cancel'},
                            success: function(data) {
                                if(data == 1) {
                                    $.confirm({
                                        title: '',
                                        content: 'Your Order Cancel Request Send Successfully!!',
                                        icon: 'fa fa-check',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'green',
                                        buttons: {
                                            Ok: function(){
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                                window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                            },
                                            Cancel:function() {
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                            }
                                        }
                                    });
                                } else if(data == 5){
                                    $.confirm({
                                        title: '',
                                        content: 'You can cancel order request send after 24 hours of ordering!',
                                        icon: 'fa fa-exclamation',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'red',
                                        buttons: {
                                            Ok: function(){
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                                window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                            },
                                            Cancel:function() {
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                            }
                                        }
                                    });
                                    window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                } else {
                                    $.confirm({
                                        title: '',
                                        content: 'No Way to Cancel This Order!',
                                        icon: 'fa fa-ban',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'scale',
                                        type: 'red',
                                        buttons: {
                                            Ok: function(){
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                                window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                                            },
                                            Cancel:function() {
                                                $('.modal').removeClass('show');
                                                $('.modal-backdrop').removeClass('show');
                                            }
                                        }
                                    });
                                    th.css("pointer-events", "none");
                                }
                            }
                        });
                    },
                    Cancel:function() {
                        $('.modal').removeClass('show');
                        $('.modal-backdrop').removeClass('show');
                    }
                }
            });
        } else {
            $.confirm({
                title: '',
                content: 'You Are Not Cancelled this Order!',
                icon: 'fa fa-ban',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    Ok: function(){
                        $('.modal').removeClass('show');
                        $('.modal-backdrop').removeClass('show');
                        window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
                    },
                    Cancel:function() {
                        $('.modal').removeClass('show');
                        $('.modal-backdrop').removeClass('show');
                    }
                }
            });

            window.location.href = "<?php echo route('my_account').'#Section4'; ?>";
        }
    });
</script>
@endsection