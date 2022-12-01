@extends('layouts.frontend')
@section('title', 'Review Orders')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')
@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

<section class="accountz">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="vertical-tab" role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                         @include('layouts.normal_user_sidebar')
                              </ul>
                    <!-- Tab panes -->
                    <div class="tab-content tabs">
                        <?php
                                $user = session()->get('user');
                            ?>
                       @include('layouts.account_data')

                        @include('layouts.section2')
                         @include('layouts.section3')
                           @include('layouts.section4')
                        <div role="tabpanel" class="tab-pane fadein active gj_rw_tab" id="Section5">
                            <div class="gj_back_div text-right">
                                <a href="{{ route('my_account') }}?tab_id=Section5"><button type="button" class="gj_bck_btn btn btn-primary">View Past Orders</button></a>
                            </div>
                            <h3> Review Orders   </h3>

                            @if(isset($ordersz))
                                @if(!empty($ordersz))
                                    <p class="error"> 
                                        @if ($errors->has('product_id'))
                                            {{ $errors->first('product_id') }}
                                        @elseif ($errors->has('order_id'))
                                            {{ $errors->first('order_id') }}
                                        @elseif ($errors->has('user_id'))
                                            {{ $errors->first('user_id') }}
                                        @endif
                                    </p>

                                    <p class="error"> 
                                        @if ($errors->has('rating'))
                                            {{ $errors->first('rating') }}
                                        @endif
                                    </p>
                                    
                                    <p class="error"> 
                                        @if ($errors->has('description'))
                                            {{ $errors->first('description') }}
                                        @endif
                                    </p>

                                    <select class="" name="gj_rw_odr" id="gj_rw_odr">
                                        <option value="0">-- Select Reviewed Item --</option>
                                        @foreach ($ordersz['details'] as $key => $value)
                                            <option value="{{$value->product_id}}">{{$value->product_title}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <p class="gj_no_data">You're Not Reviewed This Time!</p>
                                @endif

                                <div class="gj_p_rw_div">
                                    {{ Form::open(array('url' => 'submit_review','class'=>'gj_rw_form','files' => true)) }}
                                        <input type="hidden" name="product_id" id="gj_rw_product_id">
                                        @if(isset($ordersz) && $ordersz->id)
                                            <input type="hidden" name="order_id" id="gj_rw_order_id" value="{{$ordersz->id}}">
                                        @else
                                            <input type="hidden" name="order_id" id="gj_rw_order_id" value="0">
                                        @endif

                                        <?php 
                                        $value = session()->get('user');
                                        
                                        ?>
                                       
                                        @if($value)
                                            @if($value->user_type == 4 || $value->user_type == 5)
                                                <input type="hidden" name="user_id" id="gj_rw_user_id" value="{{$value->id}}">
                                            @else
                                                <input type="hidden" name="user_id" id="gj_rw_user_id" value="0">
                                            @endif
                                        @else
                                            <input type="hidden" name="user_id" id="gj_rw_user_id" value="0">
                                        @endif

                                        <div class="form-group">
                                            <label class="gj_sr_rw_hd">Star Ratings
                                                <span class="error">*</span>
                                            </label>
                                            <div class="rating">
                                                <span title="5/5 Stars">
                                                    <input type="radio" name="rating" id="str5" value="5">
                                                    <label for="str5">
                                                        <i class="fa fa-star"></i>
                                                    </label>
                                                </span>

                                                <span title="4/5 Stars">
                                                    <input type="radio" name="rating" id="str4" value="4">
                                                    <label for="str4">
                                                        <i class="fa fa-star"></i>
                                                    </label>
                                                </span>

                                                <span title="3/5 Stars">
                                                    <input type="radio" name="rating" id="str3" value="3">
                                                    <label for="str3">
                                                        <i class="fa fa-star"></i>
                                                    </label>
                                                </span>

                                                <span title="2/5 Stars">
                                                    <input type="radio" name="rating" id="str2" value="2">
                                                    <label for="str2">
                                                        <i class="fa fa-star"></i>
                                                    </label>
                                                </span>

                                                <span title="1/5 Stars">
                                                    <input type="radio" name="rating" id="str1" value="1">
                                                    <label for="str1">
                                                        <i class="fa fa-star"></i>
                                                    </label>
                                                </span>
                                                <input type="hidden" name="rating" id="gj_rw_rating" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Review</label>
                                            <span class="error">*</span>
                                            <textarea rows="5" class="form-control gj_rw_desc" id="gj_rw_desc" name="description" placeholder="Enter Your Review"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" name="submit" class="gj_submit_rw" value="Submit">
                                        </div>
                                    {{ Form::close() }}
                                </div>
                            @else
                                <p class="gj_no_data">You're Not Reviewed This Time!</p>
                            @endif

                            
                        </div>
                      
                        @include('layouts.section6')
                       
                            @include('layouts.rtn_odr')
                        

                        @include('layouts.section7')
                        

                        

                        
                          
                        
                    </div>
                        
                    
                </div>
            </div>
        </div>
    </div>
</section>

<script>     
    $(document).ready(function(){
        $('.vertical-tab .nav-tabs li a[href="#Section5"]').tab('show');
        $('.vertical-tab .nav-tabs li').removeClass('active'); 
        $('.vertical-tab .nav-tabs li a[href="#Section5"]').parent().addClass('active');

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
        $('p.alert').delay(2000).slideUp(300); 
        $("#gj_rw_odr").select2();
    });
</script>

<script>
    $('#gj_rw_odr').on('change', function() { 
        if($(this).select2('val') && $(this).select2('val') != 0) {
            $('.gj_p_rw_div').show();
            $('#gj_rw_product_id').val($(this).select2('val'));
        } else {
            $('.gj_p_rw_div').hide(); 
        }
    });

    $(document).ready(function() {
        // Check Radio-box
        $(".rating input:radio").attr("checked", false);

        $('.rating input').click(function () {
            $(".rating span").removeClass('checked');
            $(this).parent().addClass('checked');
        });

        $('.gj_p_rw_div').hide();

        $('input:radio').change(
          function(){
            var userRating = this.value;
            if(userRating) {
                $('#gj_rw_rating').val(userRating)
            } else {
                $('#gj_rw_rating').val('0')
            }
        }); 
    });
</script>

<script>     
    $(document).ready(function() {
        <?php if(isset($_GET['tab_id']) && $_GET['tab_id'] == 'Section5') { ?>
            $('.vertical-tab .nav-tabs li a[href="#Section5"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#Section5"]').parent().addClass('active');
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
    //     $('a[href="#Section5"]').trigger();                                                                      
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
                                                    // window.location.reload();
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
                                        // window.location.reload();
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
                                    // window.location.reload();
                                }
                            }
                        });
                        $("#state").prop("disabled", true);
                        $("#city").prop("disabled", true);
                    }
                }
            });
        } else {
           /* $.confirm({
                title: '',
                content: 'Please Select Country!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'purple',
                buttons: {
                    Ok: function(){
                        // window.location.reload();
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