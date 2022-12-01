<?php  
    $product_path = 'images/featured_products';
    $noimage = \DB::table('noimage_settings')->first();
    $noimage_path = 'images/noimage';
?>
@extends('groceryView.layouts.headerFooter')
@section('title', 'View Cart')
<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">
@section('content')

<div class="wrapper">
<div class="grocery-Breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Cart</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
<div class="all-product-grid">
<div class="container">
<div class="row">
    
            <div class="container page-cart" data-section-id="cart" data-section-type="cart">
                <div class="page-carts">
                    <h4 class="title-cart text-center">Your cart</h4>
                </div>
                @if(isset($carts) && count($carts) != 0)
                    {{ Form::open(array('url' => 'cart', 'novalidate','class'=>'cart','files' => true)) }}
                          <div class="table-responsive">
                              <table class="table table-bordered gj_cart_page_table">
                             <thead class="cart__row">
                                <th class="text-left" colspan="1">Product Image</th>
                                <th class="text-left" colspan="1">Product Title</th>
                                <th>Price</th>
                                <th>Tax</th>
                                <!-- <th>Tax Type</th> -->
                                <th class="text-left">Quantity</th>
                                <th class="text-left">Total</th>
                                <th class="text-left">Remove</th>
                             </thead>
                             <tbody>
                                  @foreach ($carts as $key => $value)
                                      <tr>
                                       <td class="cart__image-wrapper">
                                          <input type="hidden" name="product_id[]" id="product_{{$value->product_id}}" class="gj_p_id" value="{{$value->product_id}}">
    
                                          <input type="hidden" name="cart_id[]" id="cart_{{$value->id}}" class="gj_cart_id" value="{{$value->id}}">
    
                                          <input type="hidden" name="cart_key[]" id="cartkey_{{$key}}" class="gj_cart_key" value="{{(isset($value->cart_key) ? $value->cart_key : '')}}">
    
                                          <input type="hidden" name="cart_del[]" id="cart_del_{{$key}}" class="gj_cart_del" value="{{(isset($value->cart_del) ? $value->cart_del : '')}}">
    
                                          <input type="hidden" name="is_offer[]" id="isoffer_{{$key}}" class="gj_is_offer" value="{{(isset($value->is_offer) ? $value->is_offer : '')}}">
    
                                          <input type="hidden" name="offer_id[]" id="offerid_{{$key}}" class="gj_offer_id" value="{{(isset($value->offer_id) ? $value->offer_id : '')}}">
                                          
                                          <input type="hidden" name="offer_det_id[]" id="offerdetid_{{$key}}" class="gj_offer_det_id" value="{{(isset($value->offer_det_id) ? $value->offer_det_id : '')}}">
    
                                          <a href="{{ route('view_products', ['id' => $value->product_id]) }}">
                                              <img class="cart__image" src="{{ asset($product_path.'/'.$value->image) }}" alt="{{$value->name}}">
                                              <input type="hidden" name="image[]" id="image_{{$value->product_id}}" class= "gj_c_img" value="{{$value->image}}">
                                          </a>
                                       </td>
                                       <td class="small--text-left">
                                          <div class="list-view-item__title">
                                             <a href="{{ route('view_products', ['id' => $value->product_id]) }}">
                                             <span class="gj_ctit">{{$value->name}}</span></a>
                                             @if(isset($value->att_name) && $value->att_name != 0)
                                              @if(isset($value->AttName->att_name) && isset($value->AttValue->att_value))
                                                <span>
                                                  ({{$value->AttName->att_name}} : {{$value->AttValue->att_value}})
                                                </span>
    
                                              @endif
                                             @endif
                                           
                                            <input type="hidden" name="att_name[]" id="attname_{{$value->att_name}}" class="gj_att_name" value="{{$value->att_name}}">
    
                                            <input type="hidden" name="att_value[]" id="attvalue_{{$value->att_value}}" class="gj_att_value" value="{{$value->att_value}}">
    
                                             <input type="hidden" name="name[]" id="name_{{$value->product_id}}" class="gj_c_name" value="{{$value->name}}">
                                             
                                          </div>
                                       </td>
                                       <td class="cart__price-wrapper">
                                          <span class="money">&#8377; <span class="gj_cart_mny">{{$value->product_cost}}</span></span>
                                          <input type="hidden" name="original_price[]" id="price_{{$value->product_id}}" class="gj_c_o_price" value="{{$value->original_price}}">
                                          <input type="hidden" name="product_cost[]" id="price_{{$value->product_id}}" class="gj_c_product_cost" value="{{$value->product_cost}} gr">
                                          <input type="hidden" name="price[]" id="price_{{$value->product_id}}" class="gj_c_price" value="{{$value->product_cost}}">
                                          <input type="hidden" name="tax_amount[]" id="price_{{$value->product_id}}" class="gj_c_tax_amount" value="{{$value->tax_amount}}">
                                       </td>
                                       <td class="cart__price-wrapper">
                                          <span class="money"><span class="gj_cart_tax">{{$value->tax}}</span> %</span>
                                          <input type="hidden" name="tax[]" id="tax_{{$value->product_id}}" class="gj_c_tax" value="{{$value->tax}}">
    
                                          <input type="hidden" name="tax_type[]" id="taxtype_{{$value->product_id}}" class="gj_c_tax_type" value="{{$value->tax_type}}">
                                       </td>
                                       <!-- <td class="cart__price-wrapper cart-flex-item">
                                          <span class="money">
                                            <span class="gj_cart_tax_type">
                                              @if ($value->tax_type == 1)
                                                {{'Inclusive'}}
                                              @elseif ($value->tax_type == 2)
                                                {{'Exclusive'}}
                                              @else
                                                {{'-------'}}
                                              @endif
                                            </span>
                                          </span>
                                       </td> -->
                                       <td class="cart__update-wrapper text-left">
                                          <div class="cart__qty">
                                            <input type="hidden" name="service_charge[]" id="sc_{{$value->product_id}}" class="gj_sc_service_charge" value="{{$value->service_charge}}">
                                            @if ($value->tax_type == 2)
                                              <input type="hidden" name="shiping_charge[]" id="shc_{{$value->product_id}}" class="gj_sc_shiping_charge" value="{{$value->shiping_charge}}">
                                            @else
                                              <input type="hidden" name="shiping_charge[]" id="shc_{{$value->product_id}}" class="gj_sc_shiping_charge" value="0">
                                            @endif
    
                                            <input class="cart__qty-input gj_cart_qty" type="number" name="h_qty[]" id="gj_cart_hqty_{{$value->product_id}}" value="{{$value->qty}}" min="1" pattern="[0-9]*" @if($value->is_offer == "Yes") disabled @endif>
    
                                            <input class="cart__qty-input gj_cart_qty" type="hidden" name="qty[]" id="gj_cart_qty_{{$value->product_id}}" value="{{$value->qty}}" min="1" pattern="[0-9]*">
                                          </div>
                                       </td>
                                       <td class="text-left">
                                          <div>
                                             <span class="money">&#8377; 
                                              <span class="gj_cart_pce">
                                                {{ round(($value->qty * $value->product_cost),2) }}
                                              </span>
                                            </span>
                                          </div>
                                          <input type="hidden" name="total_price[]" id="totprice_{{$value->product_id}}" class="gj_tot_price" value="{{ round(($value->qty * $value->product_cost),2) }}">
                                       </td>
                                       <td class="text-center wishlist-product">
                                          <button type="button" class="btnRemoveWishlist gj_cart_tabl_del" data-id="{{$value->product_id}}" data-cart-id="{{$value->id}}" data-cart-key="{{(isset($value->cart_key) ? $value->cart_key : '')}}" data-cart-del="{{(isset($value->cart_del) ? $value->cart_del : '')}}"><i class="fa fa-remove"></i></button>
                                      </td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>
                          </div>
                  
                      <footer class="cart__footer">
                         <div class="row">
                            <div class="col-sm-6 col-12 medium-up--one-half cart-note">
                               <div class="cart_border">
                                  <label for="CartSpecialInstructions" class="cart-note__label small--text-center"><span>Note</span>Add a note to your order</label>
                                  <textarea rows="6" name="notes" id="CartSpecialInstructions" class="gj_c_notes cart-note__input">{{(isset($carts[0]->notes) ? $carts[0]->notes : '')}}</textarea>
                               </div>
                            </div>
                            <div class="col-sm-6 col-12 text-right small--text-center medium-up--one-half">
                               <div class="cart_border">
                                  <div>
                                     <span class="cart__subtotal-title"><span id="bk-cart-subtotal-label">Subtotal</span></span>
                                     <span class="cart__subtotal"><span id="bk-cart-subtotal-price" class="money">&#8377; <span class="gj_cart_sub_tot"> 00.00</span></span></span>
                                  </div>
                                  <div class="cart__shipping">Shipping &amp; taxes calculated at checkout</div>
                                  <a href="{{ route('home') }}" class="next-btn16 hover-btn mt-3 cart__update cart__continue--large small--hide" >Continue shopping</a>
                                  <input type="submit" name="update" id="gj_cart_update" class="next-btn16 hover-btn mt-3 cart__update cart__update--large small--hide" value="Update"> 
                                  <a href="{{ route('checkout') }}"   class="next-btn16 hover-btn mt-3 cart__update  cart__continue--large small--hide" > Checkout </a>
                               </div>
                            </div>
                         </div>
                      </footer>
                    {{ Form::close() }}
                @elseif(isset($ses_carts))
                    {{ Form::open(array('url' => 'cart', 'novalidate','class'=>'cart','files' => true)) }}
                      <table class="gj_cart_page_table">
                         <thead class="cart__row cart__header">
                            <th class="text-left" colspan="1">Product Image</th>
                            <th class="text-left" colspan="1">Product Title</th>
                            <th>Price</th>
                            <th>Tax</th>
                            <!-- <th>Tax Type</th> -->
                            <th class="text-left">Quantity</th>
                            <th class="text-left">Total</th>
                            <th class="text-left">Remove</th>
                         </thead>
                         <tbody>
                              @foreach ($ses_carts as $key => $value)
                                  <tr class="cart__row border-bottom line1 cart-flex border-top">
                                   <td class="cart__image-wrapper cart-flex-item">
                                      <input type="hidden" name="product_id[]" id="product_{{$value->product_id}}" class="gj_p_id" value="{{$value->product_id}}">

                                      <input type="hidden" name="cart_id[]" id="cart_{{$key}}" class="gj_cart_id" value="0">

                                      <input type="hidden" name="cart_key[]" id="cartkey_{{$key}}" class="gj_cart_key" value="{{(isset($value->cart_key) ? $value->cart_key : '')}}">

                                      <input type="hidden" name="cart_del[]" id="cartdel_{{$key}}" class="gj_cart_del" value="{{(isset($value->cart_del) ? $value->cart_del : '')}}">

                                      <input type="hidden" name="is_offer[]" id="isoffer_{{$key}}" class="gj_is_offer" value="{{(isset($value->is_offer) ? $value->is_offer : '')}}">

                                      <input type="hidden" name="offer_id[]" id="offerid_{{$key}}" class="gj_offer_id" value="{{(isset($value->offer_id) ? $value->offer_id : '')}}">
                                      
                                      <input type="hidden" name="offer_det_id[]" id="offerdetid_{{$key}}" class="gj_offer_det_id" value="{{(isset($value->offer_det_id) ? $value->offer_det_id : '')}}">

                                      <a href="{{ route('view_products', ['id' => $value->product_id]) }}">
                                          <img class="cart__image" src="{{ asset($product_path.'/'.$value->image) }}" alt="{{$value->name}}">
                                          <input type="hidden" name="image[]" id="image_{{$value->product_id}}" class= "gj_c_img" value="{{$value->image}}">
                                      </a>
                                   </td>
                                   <td class="cart__meta small--text-left cart-flex-item">
                                      <div class="list-view-item__title">
                                         <a href="{{ route('view_products', ['id' => $value->product_id]) }}">
                                         <span class="gj_ctit">{{$value->name}}</span>
                                         @if((isset($value->att_n)) && ($value->att_n) && (isset($value->att_v)) && ($value->att_v))
                                            <span>
                                              ({{$value->att_n}} : {{$value->att_v}})
                                            </span>
                                         @endif
                                        
                                        <input type="hidden" name="att_name[]" id="attname_{{$value->att_name}}" class="gj_att_name" value="{{$value->att_name}}">

                                        <input type="hidden" name="att_value[]" id="attvalue_{{$value->att_value}}" class="gj_att_value" value="{{$value->att_value}}">
                                           
                                         <input type="hidden" name="name[]" id="name_{{$value->product_id}}" class="gj_c_name" value="{{$value->name}}">
                                         </a>
                                      </div>
                                   </td>
                                   <td class="cart__price-wrapper cart-flex-item">
                                      <span class="money">&#8377; <span class="gj_cart_mny">{{$value->product_cost}}</span></span>
                                      <input type="hidden" name="original_price[]" id="price_{{$value->product_id}}" class="gj_c_o_price" value="{{$value->original_price}}">
                                      <input type="hidden" name="price[]" id="price_{{$value->product_id}}" class="gj_c_price" value="{{$value->product_cost}}">
                                   </td>
                                   <td class="cart__price-wrapper cart-flex-item">
                                      <span class="money"><span class="gj_cart_tax">{{$value->tax}}</span> %</span>
                                      <input type="hidden" name="tax[]" id="tax_{{$value->product_id}}" class="gj_c_tax" value="{{$value->tax}}">

                                      <input type="hidden" name="tax_type[]" id="taxtype_{{$value->product_id}}" class="gj_c_tax_type" value="{{$value->tax_type}}">
                                   </td>
                                   <!-- <td class="cart__price-wrapper cart-flex-item">
                                      <span class="money">
                                        <span class="gj_cart_tax_type">
                                          @if ($value->tax_type == 1)
                                            {{'Inclusive'}}
                                          @elseif ($value->tax_type == 2)
                                            {{'Exclusive'}}
                                          @else
                                            {{'-------'}}
                                          @endif
                                        </span>
                                      </span>
                                   </td> -->
                                   <td class="cart__update-wrapper cart-flex-item text-left">
                                      <div class="cart__qty">
                                        <input type="hidden" name="service_charge[]" id="sc_{{$value->product_id}}" class="gj_sc_service_charge" value="{{$value->service_charge}}">
                                        @if ($value->tax_type == 2)
                                          <input type="hidden" name="shiping_charge[]" id="shc_{{$value->product_id}}" class="gj_sc_shiping_charge" value="{{$value->shiping_charge}}">
                                        @else
                                          <input type="hidden" name="shiping_charge[]" id="shc_{{$value->product_id}}" class="gj_sc_shiping_charge" value="0">
                                        @endif

                                        <input class="cart__qty-input gj_cart_qty" type="number" name="h_qty[]" id="gj_cart_hqty_{{$value->product_id}}" value="{{$value->qty}}" min="1" pattern="[0-9]*" @if(isset($value->is_offer) && $value->is_offer == "Yes") disabled @endif>

                                        <input class="cart__qty-input gj_cart_qty" type="hidden" name="qty[]" id="gj_cart_qty_{{$value->product_id}}" value="{{$value->qty}}" min="1" pattern="[0-9]*">
                                      </div>
                                   </td>
                                   <td class="text-left small--hide">
                                      <div>
                                         <span class="money">&#8377; 
                                          <span class="gj_cart_pce">
                                            {{ round(($value->qty * $value->product_cost),2) }}
                                          </span>
                                        </span>
                                      </div>
                                      <input type="hidden" name="total_price[]" id="totprice_{{$value->product_id}}" class="gj_tot_price" value="{{ round($value->qty * ($value->product_cost + (($value->product_cost * $value->tax)/100)),2) }}">
                                   </td>
                                   <td class="text-center wishlist-product">
                                      <button type="button" class="btnRemoveWishlist gj_cart_tabl_del" data-id="{{$value->product_id}}" data-cart-id="0" data-cart-key="{{(isset($value->cart_key) ? $value->cart_key : '')}}" data-cart-del="{{(isset($value->cart_del) ? $value->cart_del : '')}}"><i class="fa fa-remove"></i></button>
                                  </td>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>

                      <footer class="cart__footer">
                         <div class="row">
                            <div class="col-sm-6 col-12 medium-up--one-half cart-note">
                               <div class="cart_border">
                                  <label for="CartSpecialInstructions" class="cart-note__label small--text-center"><span>Note</span>Add a note to your order</label>
                                  <textarea rows="6" name="notes" id="CartSpecialInstructions" class="gj_c_notes cart-note__input">{{--$value->notes--}}</textarea>
                               </div>
                            </div>
                            <div class="col-sm-6 col-12 text-right small--text-center medium-up--one-half">
                               <div class="cart_border">
                                  <div>
                                     <span class="cart__subtotal-title"><span id="bk-cart-subtotal-label">Subtotal</span></span>
                                     <span class="cart__subtotal"><span id="bk-cart-subtotal-price" class="money">&#8377; <span class="gj_cart_sub_tot"> 00.00</span></span></span>
                                  </div>
                                  <div class="cart__shipping">Shipping &amp; taxes calculated at checkout</div>
                                  <a href="{{ route('home') }}" class="next-btn16 hover-btn mt-3 cart__update cart__continue--large small--hide" >Continue shopping</a>
                                  <input type="submit" name="update" id="gj_cart_update" class="next-btn16 hover-btn mt-3 cart__update cart__update--large small--hide" value="Update"> 
                                  <a href="{{ route('checkout') }}"   class="next-btn16 hover-btn mt-3 cart__update  cart__continue--large small--hide" > Checkout </a>
                               </div>
                            </div>
                         </div>
                      </footer>
                    {{ Form::close() }}
                @else
                    <p class="gj_no_data">Cart is Empty</p>
                @endif 
            </div>
        </div>
    </div>
</div>
</div>

<script src="{{ asset('assetsGrocery/js/jquery-3.3.1.min.js')}}"></script>
<script>
    function sum() {
        var sum = 0;
        $(".gj_cart_pce").each(function() {
            var value = $(this).text();

            if(!isNaN(value) && value.length != 0) {
              sum += parseFloat(value);
            }
        });

        sum = (sum).toFixed(2);
        $('.gj_cart_sub_tot').html(sum);
    }

    $(document).ready(function() { 
        $('p.alert').delay(2000).slideUp(300);

        sum(); 
    });

    $('.gj_cart_qty').on('change', function() {
        var id = $(this).closest('tr').find('.gj_p_id').val();
        var qty = 1;
        var price = 0;
        var tax = 0;
        var att_name = 0;
        var att_value = 0;
        var tax_type = 0;
        var total = 0.00;
        var is_offer = "No";
        var hm = $(this);

        if($(this).val() == 0) {
            var qty = 1;
            $(this).val(qty);
        } else {
            var qty = $(this).val();
        }

        if($(this).closest('tr').find('.gj_is_offer').val()) {
          var is_offer = $(this).closest('tr').find('.gj_is_offer').val();
        }

        if($(this).closest('tr').find('.gj_att_name').val()) {
          var att_name = $(this).closest('tr').find('.gj_att_name').val();
        }

        if($(this).closest('tr').find('.gj_att_value').val()) {
          var att_value = $(this).closest('tr').find('.gj_att_value').val();
        }

        if($(this).closest('tr').find('.gj_cart_mny').html()) {
          var price = parseFloat($(this).closest('tr').find('.gj_cart_mny').html());
        }

        if($(this).closest('tr').find('.gj_c_tax').val()) {
          tax = $(this).closest('tr').find('.gj_c_tax').val();
        }

        if($(this).closest('tr').find('.gj_c_tax_type').val()) {
          tax_type = $(this).closest('tr').find('.gj_c_tax_type').val();
        }

        /*if(tax_type == 2) {
          var calc_tax = ((price * tax)/100);
          price = price + calc_tax;
        }*/

        // var calc_tax = ((price * tax)/100);
        // price = price + calc_tax;
        if(is_offer == 'Yes') {
            qty = 1;
            $(this).attr('disabled', true);
        }

        if(id) {
            $.ajax({
                type: 'post',
                url: '{{url('/check_onhand_qty')}}',
                data: {id: id, qty: qty, price: price, att_name: att_name, att_value: att_value, type: 'check_onhand_qty'},       
                dataType:"json",   
                success: function(data){
                    if(data['error'] == 2){
                      $.confirm({
                            title: '',
                            content: 'Out of Stock. Only ' + data['onhand_qty'] + ' Products Avaliable!',
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
                        $(hm).val(1);
                        data = (price * $(hm).val()).toFixed(2);
                        $(hm).closest('tr').find('.gj_cart_qty').val(1);
                        $(hm).closest('tr').find('.gj_cart_pce').html(data);
                        $(hm).closest('tr').find('.gj_tot_price').val(data);
                        sum();
                    } else if(data['error'] == 3){
                      $.confirm({
                            title: '',
                            content: 'Out of Stock.Products Not Avaliable!',
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
                        $(hm).val(1);
                        data = (price * $(hm).val()).toFixed(2);
                        $(hm).closest('tr').find('.gj_cart_qty').val(1);
                        $(hm).closest('tr').find('.gj_cart_pce').html(data);
                        $(hm).closest('tr').find('.gj_tot_price').val(data);
                        sum();
                    } else if (data != 1) {
                        $(hm).val(qty);
                        console.log(data);
                        var data = parseFloat(data['amount']).toFixed(2);
                        $(hm).closest('tr').find('.gj_cart_qty').val(qty);
                        $(hm).closest('tr').find('.gj_cart_pce').html(data);
                        $(hm).closest('tr').find('.gj_tot_price').val(data);
                        sum();
                    } else {
                        $(hm).val('1');
                        data = (price * $(hm).val()).toFixed(2);
                        $(hm).closest('tr').find('.gj_cart_qty').val(1);
                        $(hm).closest('tr').find('.gj_cart_pce').html(data);
                        $(hm).closest('tr').find('.gj_tot_price').val(data);
                        sum();
                    }
                }
            });        
        }
    });
</script>
@endsection
