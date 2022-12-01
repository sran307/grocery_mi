@extends('layouts.frontend')
@section('title', 'Build Your PC\'s')
<!--<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">-->
@section('content')
<style>
    .breadcrumb  li:a {
    cursor: pointer !important;
    z-index: 1;
}
</style>
 <div class="scp-breadcrumb pull-right">
    <ul class="breadcrumb">
        <li><a href="{{url('/')}}">Home</a></li>
        <li class="active"><a href="#">Build Your PC's</a></li>
      
    </ul>
</div>
<br>
<section class="section contenz">
    <div class="container">
        <p class="alert alert-success" id="alert_info" style="display:none;"></p>
        <div class="row">
            <div class="col-md-12">
                <div class="content-about col-md-12 col-sm-12"
                style="margin: 30px 0px 0px; padding-top: 0px; padding-bottom: 0px; width: 100%; -webkit-box-flex: 0; flex: 0 0 100%; max-width: 100%; text-align: center;">
                    <div class="title-about-us" style="text-align:left">
                        <h2 style="margin: 0px 0px 20px; padding: 0px; font-weight: 500; line-height: 1.2; color: rgb(34, 34, 34); font-size: 18px;">
                            {{$setting->title}}</h2>
                    </div>
                    <hr>
                        <div class="des-about-us" style="    text-align: justify; padding: 0px; line-height: 28px;">
                            <p style="margin-bottom: 1rem; padding: 0px; font-size: 14px;">
                         <p>
                         {!!$setting->description!!}
                            </p>
                            </div>
                             <hr>
                             <div class="des-about-us" style="text-align:justify; padding: 0px; line-height: 28px;"><p style="margin-bottom: 1rem; padding: 0px; font-size: 14px;">
                         {!!$setting->product_features!!}
                           
                            </div>
                            <hr>
                             <div class="des-about-us" style="text-align:justify; padding: 0px; line-height: 28px;"><p style="margin-bottom: 1rem; padding: 0px; font-size: 14px;">
                         <p>{!!$setting->notes!!}</p>
                           
                            </div>
                            </div>
             
            </div>
            <hr>
            <div class="col-sm-8">
                <div class="addrz">
                    <h5> BUILD YOUR PC</h5>
                    <div class="table-responsive">
                        
                        <table class="table table-bordered table-hover build-your-pc">
                            <thead>
                                <tr style="background:#eee;">
                                    <th class="text-center align-middle fs-14" scope="col" style="min-width:180px;">Component</th>
                                    <th class="text-center align-middle fs-14" scope="col" style="min-width: 100px;">Image</th>
                                    <th class="text-center align-middle fs-14" scope="col" style="min-width: 120px;">Product Name</th>
                                    <th class="text-center align-middle fs-14" scope="col" style="width:12%;">Model</th>
                                    <th class="text-center align-middle fs-14" scope="col" style="width:105px;">Quantity</th>
                                    <th class="text-center align-middle fs-14" scope="col" style="min-width: 120px;">Unit Price</th>
                                    <th class="text-center align-middle fs-14" scope="col" style="min-width: 100px;">Total</th>
                                    <th class="text-center align-middle fs-14" scope="col" style="min-width:180px;">Action</th>
                                </tr>
                                                            </thead>
                            <tbody>
                                <div id='loader' style='display: none;'>
                                  <img src="{{asset('frontend/images/1495.gif')}}" width='40px' height='40px'>
                                </div>
                                @foreach($component as $value)
                                     <tr class="trow_{{$value->id}}">
                                    <td scope="row" class="align-middle fs-14 category-name">{{$value->component_name}}</td>
                                    <td colspan="7" class="text-center align-middle fs-13">Choose {{$value->component_name}}            
                                    <a href="#" style="float: right"><i class="custom-icon fa fa-plus testimonial" id="render_product_list_btn_{{$value->id}}" 
                                    data-catid="{{$value->id}}" data-limit="5"  name="compoent{{$value->id}}" data-skip="0" onclick="render_more_products(this);" aria-hidden="true">
                                        
                                    </i></a>
                                        <span style="float: right"><i class="custom-icon fa fa-times" id="remove_product_list_btn_{{$value->id}}" 
                                        style="display:none;color:#ff5c00;" data-catid="{{$value->id}}" onclick="remove_render_product_list(this);" aria-hidden="true"></i></span>
                                    </td>
                                </tr>
                                @endforeach
                                                               
                                                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="gj_h_map_div">
                 
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td class="text-center align-middle" colspan="3" style="padding: 18px;background-color:#dddddd36;">
                            <span style="font-size: 16px;">Estimated Wattage: <span id="total_wat">0</span>W</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle td_grandtotal" colspan="3" style="padding: 23px;">
                                <span style="font-size: 16px;">Total Price: Rs. <span id="total_price">0</span>/- </span>
                            </td>
                            <input type="hidden" name="grandtotal" id="grandtotal" value="16150" spellcheck="false">
                        </tr>
                        <tr class="">
                            <td class="text-center align-middle hoverTable" colspan="3" style="padding: 16px;" id="addToallCart">
                                <a href="#" class="btn btn-success" id="all_cart" onclick="add_all_cart()" disabled>Add All To Cart</a></span>
                            </td>
                        </tr>
                        <!--<tr>
                            <td class="text-center align-middle" colspan="3" style="padding: 9px;background-color:#dddddd36; ">
                                                                <a href="#" class="btn btn-primary">Save This Build </a></span>
                            </td>
                        </tr> -->
                       <!-- <tr>
                            <td class="text-center align-middle" style="padding: 16px;">
                                <a onclick="create_link();">Create Link</a>
                            </td>
                            <td class="text-center align-middle" style="padding: 16px;">
                                <a onclick="sendPdfToMail();">Mail PDF</a>
                            </td>
                            <td class="text-center align-middle" style="padding: 16px;">
                                <a onclick="printBuildYourPc();">Print</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" style="padding: 16px;">
                                <a data-toggle="modal" data-target="#login_messege_modal">What\'sApp PDF</a>
                            </td>
                            <td class="text-center align-middle" style="padding: 16px;">
                                <a onclick="directdownloadPdf();">Download PDF</a>
                            </td>
                            <td class="text-center align-middle" style="padding: 16px;">
                                <a onclick="getBuildYourPcLinkToShare();">Share On Social</a>
                            </td>
                        </tr>-->
                        <tr>
                            <td class="align-middle" colspan="3" style="font-size: 14px;">Important: If in Doubt, For Compatibility Assured. Then Contact us at the Support Center </td>
                        </tr>
                        </tbody>
                    </table>
              
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300); 
    });
</script>

<script>
    $(document).ready(function(){
       fetch_list_data();
    });
    function fetch_list_data()
    {
         $.ajax({
            type: 'post',
            url: '{{url('/remove_list')}}',
            data: {type:'fetch_list'},
            
            success: function(data){
               
            //   console.log(data.length);
               for(var i=0;i<data['one'].length;i++)
               {
                $('.testimonial').each(function(){
                            if($(this).attr('data-catid')==data['one'][i])
                            {
                        var custom_id=data['one'][i];
                           render_more_products(document.getElementById($(this).attr('id')),1);
                        //   fetch_cart_details();
                    //   console.log('.filteration_row'+custom_id);
                           $('.trow_'+custom_id).replaceWith(data['two'][custom_id]);
                            // $('.quantity_'+data['three'][custom_id]+custom_id).val()
                      fetch_cart_details();
                            }
                        
                        
                         });
              
               }


            }
              
               
         });
    }
    
    function fetch_cart_details()
    {
         event.preventDefault();
         $.ajax({
            type: 'post',
            url: '{{url('/remove_list')}}',
            data: {type:'fetch_cart'},
            
            success: function(data1){
                
                    if(data1)
                    {
                        if(data1.total > 0)
                        {
                            $('#all_cart').attr('disabled',false);
                        }
                        else
                        {
                           $('#all_cart').attr('disabled',true);  
                        }
                       
                        $('#total_price').html(data1.total);
                        $('#total_wat').html(data1.wattage);

                    }
            }
         });
    }
    function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
     function decrease(custom_id,p_id)
    {
         event.preventDefault();
         if(parseInt($('.quantity_'+p_id+custom_id).val())-1 >0)
         {
        $('.quantity_'+p_id+custom_id).val(parseInt($('.quantity_'+p_id+custom_id).val())-1);
       
        var cart_key=$('.decrease_'+p_id+custom_id).attr('cart_key');
        var cart_del=$('.decrease_'+p_id+custom_id).attr('cart_del'); 
        var qty=$('.quantity_'+p_id+custom_id).val();
       
         $.ajax({
            type: 'post',
            url: '{{url('/remove_list')}}',
            data: {type:'list_remove',custom_id:custom_id,id:p_id,cart_key:cart_key,cart_del:cart_del,qty:qty},
            
            success: function(data1){
                if(data1)
                {
                    $('#total_prices_'+p_id+custom_id).html(data1);
                    fetch_cart_details();
                    
                }
            }
         });
         }
    }
    function update_quantity(p_id,custom_id)
    {
         event.preventDefault();
         var qty=$('.quantity_'+p_id+custom_id).val();
         if(qty >0)
         {
             var cart_key=$('.decrease_'+p_id+custom_id).attr('cart_key');
        var cart_del=$('.decrease_'+p_id+custom_id).attr('cart_del'); 
        var qty=$('.quantity_'+p_id+custom_id).val();
       
         $.ajax({
            type: 'post',
            url: '{{url('/remove_list')}}',
            data: {type:'list_update',custom_id:custom_id,id:p_id,cart_key:cart_key,cart_del:cart_del,qty:qty},
            
            success: function(data1){
                if(data1)
                {
                    $('#total_prices_'+p_id+custom_id).html(data1);
                    fetch_cart_details();
                    
                }
            }
         });
         }
         else
         {
             $('.quantity_'+p_id+custom_id).val(1);
         }
    }
    function increase(custom_id,p_id)
    {
         event.preventDefault();
        if(parseInt($('.quantity_'+p_id+custom_id).val())+1 >0)
        {
        $('.quantity_'+p_id+custom_id).val(parseInt($('.quantity_'+p_id+custom_id).val())+1);
        
        var cart_key=$('.increase_'+p_id+custom_id).attr('cart_key');
        var cart_del=$('.increase_'+p_id+custom_id).attr('cart_del'); 
        var qty=$('.quantity_'+p_id+custom_id).val();
       
         $.ajax({
            type: 'post',
            url: '{{url('/remove_list')}}',
            data: {type:'list_add',custom_id:custom_id,id:p_id,cart_key:cart_key,cart_del:cart_del,qty:qty},
            
            success: function(data1){
                if(data1)
                {
                    $('#total_prices_'+p_id+custom_id).html(data1);
                    fetch_cart_details();
                    
                }
            }
         });
        }
    }
    function choose_brand(id,custom_id)
    {
         event.preventDefault();
        if($(id).val()!='')
        {
            var obj=document.getElementById('render_product_list_btn_'+custom_id);
        render_more_products(obj,0,$(id).val());
        }
        else
        {
             var obj=document.getElementById('render_product_list_btn_'+custom_id);
        render_more_products(obj,2);
        }
       
    }
    function search_pdt(id,custom_id)
    {
         event.preventDefault();
       if($(id).val()!='')
        {
            var obj=document.getElementById('render_product_list_btn_'+custom_id);
        render_more_products(obj,0,'',$(id).val());
        }
        else
        {
             var obj=document.getElementById('render_product_list_btn_'+custom_id);
        render_more_products(obj,2);
        } 
    }
    function sort_product_list(id,custom_id)
    {
       event.preventDefault();
       if($(id).val()!='')
        {
            var obj=document.getElementById('render_product_list_btn_'+custom_id);
        render_more_products(obj,0,'','',$(id).val());
        }
        else
        {
             var obj=document.getElementById('render_product_list_btn_'+custom_id);
        render_more_products(obj,2);
        }  
    }
    function render_more_products(id,nos=0,brand='',pdt_name='',sort='')
    {
        event.preventDefault();
       
       var custom_id=$(id).attr('data-catid');
     
       if(brand!='' || pdt_name!='' || sort!='')
       {
       $('.filteration_row'+custom_id).remove();
         $('.head_row'+custom_id).remove();
                        $('.product_of_category'+custom_id).remove();
       }
       if(nos==2)
       {
         $('.filteration_row'+custom_id).remove();
         $('.head_row'+custom_id).remove();
                        $('.product_of_category'+custom_id).remove();  
       }
        $.ajax({
                type: 'post',
                url: '{{url('/render_action')}}',
                data: {custom_id: custom_id, type: 'fetch',brand:brand,pdt_name:pdt_name,sort:sort},
                beforeSend: function(){
                // Show image container
                $("#loader").show();
               },
                success: function(response){
                    if(response)
                    {
                        $('.trow_'+custom_id).after(response);
                        $('#render_product_list_btn_'+custom_id).attr('onclick','restore_initital('+custom_id+')');
                             $('#render_product_list_btn_'+custom_id).attr('class','custom-icon fa fa-close');
                             if(nos==1)
                             {
                                  $('.filteration_row'+custom_id).remove();
                        $('.head_row'+custom_id).remove();
                        $('.product_of_category'+custom_id).remove();
    
                             }

                    }
                },
                complete:function(data){
    // Hide image container
    $("#loader").hide();
   }
        });
    }
    function add_all_cart()
    {
        event.preventDefault();
         $.ajax({
            type: 'post',
            url: '{{url('/remove_list')}}',
            data: { type: 'add_all_cart'},
             success: function(data1){
                 if(data1==1)
                 {
                     $.confirm({
                    title: '',
                    content: 'Added To Cart!',
                    icon: 'fa fa-check',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'green',
                    buttons: {
                        // Ok: function(){
                           
                        // }
                    }
                });
                 window.location.reload();
                 }
             }
         });
            
    }
    function restore_initital(custom_id)
    {
       event.preventDefault();
         $.ajax({
            type: 'post',
            url: '{{url('/remove_list')}}',
            data: {custom_id:custom_id, type: 'restore_first'},
             beforeSend: function(){
                // Show image container
                $("#loader").show();
               },
            success: function(data1){
                
                    if(data1)
                    {
                         $('.filteration_row'+custom_id).remove();
                    $('.head_row'+custom_id).remove();
                    $('.product_of_category'+custom_id).remove();
                        $('.trow_'+custom_id).replaceWith(data1);
        
                    }
            },
            complete:function(data){
    $("#loader").hide();
   }
         }); 
    }
    function remove_list(id,cart_id,custom_id)
    {
        var cart_key=$('.close_'+id+custom_id).attr('cart_key');
        var cart_del=$('.close_'+id+custom_id).attr('cart_del');
        event.preventDefault();
         $.ajax({
            type: 'post',
            url: '{{url('/remove_list')}}',
            data: {id: id, cart_id: cart_id, cart_key: cart_key, cart_del: cart_del,custom_id:custom_id, type: 'delete_cart'},
             beforeSend: function(){
                // Show image container
                $("#loader").show();
               },
            success: function(data1){
                
                    if(data1)
                    {
                        fetch_cart_details();
                        $('.trow_'+custom_id).replaceWith(data1);
        
                    }
            },
            complete:function(data){
    $("#loader").hide();
   }
         });
    }
    function add_to_cart(id,custom_id)
    {
         var cart_key=$('.close_'+id+custom_id).attr('cart_key');
        var cart_del=$('.close_'+id+custom_id).attr('cart_del');
        event.preventDefault();
         $.ajax({
            type: 'post',
            url: '{{url('/remove_list')}}',
            data: {id: id, cart_id: cart_id, cart_key: cart_key, cart_del: cart_del,custom_id:custom_id, type: 'add_cart'},
             beforeSend: function(){
                // Show image container
                $("#loader").show();
               },
            success: function(data1){
                
                    if(data1)
                    {
                        $('.trow_'+custom_id).replaceWith(data1);
        
                    }
            },
            complete:function(data){
    $("#loader").hide();
   }
         });
    }
    function add_to_list(id,custom_id,price=0)
    {
         event.preventDefault();
         var qty=$('#quantity'+id).val();
            if(id) {
          $.ajax({
            type: 'post',
            url: '{{url('/add_to_list')}}',
            data: {id: id, qty: qty,custom_id:custom_id, price: price, type: 'add_to_cart'},
            success: function(data){
                if(data)
                {
                    fetch_cart_details();
                    $('.filteration_row'+custom_id).remove();
                    $('.head_row'+custom_id).remove();
                    $('.product_of_category'+custom_id).remove();

                    $('.trow_'+custom_id).replaceWith(data);

                }
              else if(data == 2){
                $.confirm({
                    title: '',
                    content: 'Already Added To List!',
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
                // window.location.reload();
              } else if(data == 7){
                $.confirm({
                    title: '',
                    content: 'Out Of Stock!',
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
                
                setTimeout(function(){ window.location.reload(); }, 3000);
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
                setTimeout(function(){ window.location.reload(); }, 3000);
              }
            }
          });
        }
    }
     
</script>
@endsection
