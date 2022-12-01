@extends('groceryView.layouts.headerFooter')
@section('title', 'My Account')
<!--<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">-->
@section('content')
{{-- @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif


                        @include('layouts.section2')
                         

                       
                        @include('layouts.section5')
                        @include('layouts.section6')
                       
                            @include('layouts.rtn_odr')
                        

                        @include('layouts.section7')
--}}

                         @include('layouts.normal_user_sidebar')
                   <div class="col-lg-9 col-md-8">
<div class="dashboard-right">
<div class="row">
<div class="col-md-12">
    @include('layouts.section4')
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
    $(document).ready(function() {
        <?php if(isset($_GET['tab_id']) && ($_GET['tab_id'] == 'Section4' || $_GET['tab_id'] == 'Section5')) { ?>
        @if($_GET['tab_id'] == 'Section4')
        
            $('.vertical-tab .nav-tabs li a[href="#Section4"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#Section4"]').parent().addClass('active');
        @endif
             @if($_GET['tab_id'] == 'Section5')
             
            $('.vertical-tab .nav-tabs li a[href="#Section5"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#Section5"]').parent().addClass('active');
             @endif
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
        $('p.alert').delay(5000).slideUp(500); 
        $("#country").select2();
        $("#state").select2();
        $("#city").select2();
        $("#question").select2();

        var trgr = false;
        var url = document.location.href;
        var res = url.toString().split('#');
        var resu = url.toString().split('my_account');

        if(res[1]) {
            var trgr = res[1];
        }

        if(trgr) {
            $('.vertical-tab .nav-tabs li a[href="#' + trgr + '"]').tab('show');
            $('.vertical-tab .nav-tabs li').removeClass('active'); 
            $('.vertical-tab .nav-tabs li a[href="#' + trgr + '"]').parent().addClass('active');
        }

        /*@if(isset($orders) && count($orders) != 0)
            if(resu[1]) {
                if(resu[1] == '?page=<?php echo $orders->currentPage(); ?>') {
                    $('.vertical-tab .nav-tabs li a[href="#Section4"]').tab('show');
                    $('.vertical-tab .nav-tabs li').removeClass('active'); 
                    $('.vertical-tab .nav-tabs li a[href="#Section4"]').parent().addClass('active');
                }
            }
        @endif*/

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
                                            type: 'blue',
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
                                type: 'blue',
                                buttons: {
                                    Ok: function(){
                                    }
                                }
                            });
                        }
                    } else {
                        /*$.confirm({
                            title: '',
                            content: 'Please Select Country!',
                            icon: 'fa fa-ban',
                            theme: 'modern',
                            closeIcon: true,
                            animation: 'scale',
                            type: 'blue',
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
                type: 'blue',
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
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                            window.location.reload();
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
                            type: 'blue',
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
                type: 'blue',
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
                            type: 'blue',
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
                type: 'blue',
                buttons: {
                    Ok: function(){
                    }
                }
            });
        }
    });
</script>

<!-- Cancel Order Script Start -->
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
<!-- Cancel Order Script End -->

<!-- Return Order Script Start -->
<!-- <script type="text/javascript">
    $('body').on('click','.gj_my_rodr_req',function() {
        var id = 0;                                                       
        var th = $(this);                                                       
        if($(this).attr('data-id')){
            id = $(this).attr('data-id');
        }   
    
        if(id != 0) {
            $.confirm({
                title: '',
                content: 'Are You Sure to Return / Replace this Order?',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
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
                                        content: 'You can cancel order request send after two days of ordering!',
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
</script> -->
<!-- Return Order Script End -->
@endsection