<?php  
    $product_path = 'images/featured_products';
    $noimage = \DB::table('noimage_settings')->first();
    $noimage_path = 'images/noimage';
?>
@extends('layouts.frontend')
@section('title', 'Track Orders')
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
                            <h3> Track Orders   </h3>
 <div class="gj_back_div text-right">
                                <a href="{{url('get_orders')}}"><button type="button" class="gj_bck_btn btn btn-primary">My Orders</button></a>
                            </div>
                            @if(isset($orders))
                            <div class="outer">
                                @if ($orders['shipments'])
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table  class="table table-striped table-responsive">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <p> Shipment 1 of 1 </p>
                                                            <p class="gj_sh_det_p">
                                                                @if ($orders['shipments']->shiping_status)
                                                                    <b>{{$orders['shipments']->shiping_status}}</b>
                                                                @else
                                                                    <b>No Status</b>
                                                                @endif
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p> Courier</p>
                                                            <p class="gj_sh_det_p">
                                                                @if ($orders['shipments']->shiping_status)
                                                                    <b>{{$orders['shipments']->carrier}}</b>
                                                                @else
                                                                    <b>Carrier Not Availible</b>
                                                                @endif
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p> Tracking #</p>
                                                            <p class="gj_sh_det_p">
                                                                @if ($orders['shipments']->shiping_status)
                                                                    <b>{{$orders['shipments']->awb}}</b>
                                                                @else
                                                                    <b>------</b>
                                                                @endif
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <h4 class="gj_tk_sts">
                                                            @if($orders->order_status == 1)
                                                                {{'Order Placed Successfully'}}
                                                            @elseif($orders->order_status == 2)
                                                                Order Dispatched Successfully
                                                            @elseif($orders->order_status == 3)
                                                                Order Delivered Successfully
                                                            @elseif($orders->order_status == 4)
                                                                Order Completed Successfully
                                                            @elseif($orders->order_status == 5)
                                                                Order Cancelled
                                                            @else
                                                                {{'Tracking Not Possible'}}
                                                            @endif
                                                        </h4>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <p class="gj_tk_sts_p gj_tk_sts_p1 @if($orders->order_status == 1) gj_tk_sts_ss_p @endif">
                                                            <img src="{{ asset('frontend/images/order_status/placed.png')}}">
                                                        </p>
                                                        <p class="gj_tk_sts_det"><b>Order Placed</b></p>
                                                    </td>
                                                    <td>
                                                        <p class="gj_tk_sts_p gj_tk_sts_p2 @if($orders->order_status == 2) gj_tk_sts_ss_p @endif">
                                                            <img src="{{ asset('frontend/images/order_status/dispatched.png')}}">
                                                        </p>
                                                        <p class="gj_tk_sts_det"><b>Order Dispatched</b></p>
                                                    </td>

                                                    <td>
                                                        <p class="gj_tk_sts_p gj_tk_sts_p3 @if($orders->order_status == 3) gj_tk_sts_ss_p @endif">
                                                            <img src="{{ asset('frontend/images/order_status/delivered.png')}}">
                                                        </p>
                                                        <p class="gj_tk_sts_det"><b>Order Delivered</b></p>
                                                    </td>
                                                    <td>
                                                        <p class="gj_tk_sts_p gj_tk_sts_p4 @if($orders->order_status == 4) gj_tk_sts_ss_p @endif">
                                                            <img src="{{ asset('frontend/images/order_status/completed.png')}}">
                                                        </p>
                                                        <p class="gj_tk_sts_det"><b>Order Completed</b></p>
                                                    </td>
                                                    <td>
                                                        <p class="gj_tk_sts_p gj_tk_sts_p5 @if($orders->order_status == 5) gj_tk_sts_ss_p @endif">
                                                            <img src="{{ asset('frontend/images/order_status/cancel.png')}}">
                                                        </p>
                                                        <p class="gj_tk_sts_det"><b>Order Cancel</b></p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6"> 
                                        <p>Order Date :<b class="gj_tk_dts"> {{date('l, F d, Y', strtotime($orders->order_date))}} </b></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-right">Estimated delivery :<b class="gj_tk_dts">  {{($orders->delivery_date ? date('l, F d, Y', strtotime($orders->delivery_date)) : '------')}}</b></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table width="100%" class="table table-striped  table-bordered table-condensed table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>Tax</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($orders['details']) && (count($orders['details']) != 0))
                                                    @foreach ($orders['details'] as $key => $value)
                                                        <tr>
                                                            <td>
                                                                @if($value->product_id)
                                                                    @if($value->Products->featured_product_img)
                                                                        <img src="{{ asset($product_path.'/'.$value->Products->featured_product_img) }}" class="gj_tk_dts_img" alt="{{$value->product_title}}">
                                                                    @else
                                                                        <img src="{{ asset($noimage_path.'/'.$noimage->product_no_image) }}" class="gj_tk_dts_img" alt="No Images">
                                                                    @endif
                                                                @else
                                                                    <img src="{{ asset($noimage_path.'/'.$noimage->product_no_image) }}" class="gj_tk_dts_img" alt="No Images">
                                                                @endif
                                                            </td>
                                                            <td>{{$value->product_title}}</td>
                                                            <td>{{$value->order_qty}}</td>
                                                            <td>
                                                                @if($value->product_id)
                                                                    @if($value->Products->tax)
                                                                        {{$value->Products->tax}} %
                                                                    @else
                                                                        {{'-----'}}
                                                                    @endif
                                                                @else
                                                                    {{'-----'}}
                                                                @endif
                                                            </td>
                                                            <td>{{$code}} <span class="gj_tk_up">{{$value->totalprice}}</span></td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="4" class="text-right">Total Amount</td>
                                                        <td>{{$code}} <span class="gj_tk_st">0.00</span></td>
                                                     </tr>
                                                    <tr>
                                                        <td colspan="4" class="text-right">shipping charge</td>
                                                        <td>{{$code}} {{$orders->shipping_charge}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" class="text-right">Grand total</td>
                                                        <td><b>{{$code}} {{$orders->net_amount}} </b></td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="5">
                                                            <p class="gj_no_data">Products Not Found</p>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-center">
                                            <a href="{{ route('live_track_order', ['id' => $orders->id]) }}" title="">
                                                <button class="btn btn-primary gj_tk_vw_sts" >View Live Status</button>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @else
                                <p class="gj_no_data">Orders is Empty</p>
                            @endif

                           
                        </div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
  
 <?php
                                $user = session()->get('user');
                            ?>
                      
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
        });

        var sum = 0;
        $(".gj_tk_up").each(function() {
            var value = $(this).html();
            if(!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
            }
        });
        sum = (sum).toFixed(2);
        $('.gj_tk_st').html(sum);
    });
</script>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(5000).slideUp(500); 
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
                                        $("#city").prop("disabled", true);
                                    }
                                }
                            });
                        } else {
                            $.confirm({
                                title: '',
                                content: 'Please Select State!',
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
                        }
                    } else {
                        $.confirm({
                            title: '',
                            content: 'Please Select Country!',
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
                        $("#state").prop("disabled", true);
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
            
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