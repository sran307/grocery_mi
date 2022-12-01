<?php  
    $general = \DB::table('general_settings')->first();
    $product_path = 'images/featured_products';
    $noimage = \DB::table('noimage_settings')->first();
    $noimage_path = 'images/noimage';
?>
@extends('groceryView.layouts.headerFooter')
@section('title', 'View CheckOut')
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
                            <li class="breadcrumb-item"><a href="{{url('/cart')}}">Cart</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Check Out</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
<div class="all-product-grid">
<div class="container">
<div class="row">
          
          
        <div class="wishlist-product col-md-12">
            <h2 class="page-title">Checkout</h2>
            @if(Session::has("message"))
            <p class="alert alert-{{Session::get('alert-class')}}">{{Session::get('message')}}</p>
            @endif
            @if (isset($items) && count($items) != 0)
                <div class="pageContent">
                    {{ Form::open(array('url' => 'checkout_verif','class'=>'gj_ch_trans','id'=>'gj_ch_trans','files' => true)) }}
                        <div class="table-responsive">
                          <table class="table table-hover wishlist-product" id="pdt_table">
                            <thead>
                              <tr class="wl-title">
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Unit Price (Excl.Tax)</th>
                                <th class="text-center">Tax Rate (%)</th>
                                <th class="text-center">Tax Amount</th>
                                <!-- <th class="text-center">Shipping Type</th> -->
                                <th class="text-center">Total(incl.Tax)</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($items as $key => $value)
                              <?php
                              
                              $proc=App\Products::find($value->product_id);
                              if($users->user_type==4)
                              {
                                  $unit_price=$proc->original_retailer_price;
                              }
                              else if($users->user_type==5)
                              {
                                  $unit_price=$proc->original_dealer_price;
                              }
                              ?>
                                <tr class="row-15484157755503 product-item1" id="x15484157755503">
                                  <td>
                                    <input type="hidden" name="product_id[]" id="product_{{$value->product_id}}" class="gj_p_id" value="{{$value->product_id}}">

                                    <input type="hidden" name="c_id[]" id="cart_{{$value->id}}" class="gj_ch_id" value="{{$value->id}}">

                                    <input type="hidden" name="is_offer[]" id="cart_{{$value->id}}" class="gj_is_offer" value="{{$value->is_offer}}">

                                    <input type="hidden" name="offer_id[]" id="cart_{{$value->id}}" class="gj_offer_id" value="{{$value->offer_id}}">

                                    <input type="hidden" name="offer_det_id[]" id="cart_{{$value->id}}" class="gj_offer_det_id" value="{{$value->offer_det_id}}">

                                    <a href="{{ route('view_products', ['id' => $value->product_id]) }}" class="product-grid-image">
                                      <img class="cart__image" src="{{ asset($product_path.'/'.$value->image) }}" alt="{{$value->name}}">
                                    </a>

                                    <input type="hidden" name="image[]" id="image_{{$value->product_id}}" class= "gj_ch_img" value="{{$value->image}}">
                                  </td>
                                  <td>
                                    <a href="{{ route('view_products', ['id' => $value->product_id]) }}" class="product-title"><span class="gj_ctit">{{$value->name}}</span></a>
                                    @if(isset($value->att_name) && $value->att_name != 0)
                                      <input type="hidden" name="att_name[]" id="attname_{{$value->att_name}}" class="gj_ch_att_name" value="{{$value->att_name}}">
                                      <input type="hidden" name="att_value[]" id="attname_{{$value->att_value}}" class="gj_ch_att_value" value="{{$value->att_value}}">
                                      @if(isset($value->AttName->att_name) && isset($value->AttValue->att_value))
                                        <span>
                                          ({{$value->AttName->att_name}} : {{$value->AttValue->att_value}})
                                        </span>
                                      @endif
                                    @else
                                      <input type="hidden" name="att_name[]" class="gj_ch_att_name" value="">
                                      <input type="hidden" name="att_value[]" class="gj_ch_att_value" value="">
                                    @endif

                                      <input type="hidden" name="name[]" id="name_{{$value->product_id}}" class="gj_ch_name" value="{{$value->name}}">
                                  </td>
                                  <td class="text-center">
                                    <input class="cart__qty-input gj_ch_qty" type="number" name="h_qty[]" id="hqty_{{$value->product_id}}" value="{{$value->qty}}" min="1" pattern="[0-9]*" @if($value->is_offer == "Yes") disabled @endif>
                                    <input class="cart__qty-input gj_ch_hqty" type="hidden" name="qty[]" id="qty_{{$value->product_id}}" value="{{$value->qty}}" min="1" pattern="[0-9]*">
                                  </td>
                                  <td class="text-center">
                                    <div class="price">
                                      <span class="price-new"><span class="money" data-currency-usd="&#8377; {{$value->price}}">&#8377; <span class="gj_ch_d_p">{{$unit_price}}</span></span></span>
                                      <span class="price-old"><span class="money" data-currency-usd="&#8377; {{$value->original_price}}">&#8377; <span class="gj_ch_o_p">{{$value->original_price}}</span></span></span>
                                    </div>

                                    <input type="hidden" name="product_cost[]" id="dp_{{$value->product_id}}" class="gj_ch_dp" value="{{$unit_price}}">

                                    <input type="hidden" name="price[]" id="dp_{{$value->product_id}}" class="gj_ch_price" value="{{$value->price}}">

                                    <input type="hidden" name="original_price[]" id="op_{{$value->product_id}}" class="gj_ch_op" value="{{$value->original_price}}">

                                    <input type="hidden" name="service_charge[]" id="sc_{{$value->product_id}}" class="gj_ch_sc" value="{{$value->Products->service_charge}}">

                                    @if ($value->Products->tax_type == 2)
                                      <input type="hidden" name="shiping_charge[]" id="shc_{{$value->product_id}}" class="gj_ch_shc" value="{{$value->Products->shiping_charge}}">
                                    @else
                                      <input type="hidden" name="shiping_charge[]" id="shc_{{$value->product_id}}" class="gj_ch_shc" value="0">
                                    @endif
                                  </td>
                                  <td class="text-center">
                                      <span class="gj_ch_tpers"><span class="gj_che_tax_percent">{{$value->tax}}%</span></span>
                                  </td>
                                  @php
                                  
                                  $tax_amount=number_format(((($unit_price * $value->tax)/100) * $value->qty),2);
                                  $total_price=round((($value->qty * $unit_price * $value->tax)/100)+($value->qty * $unit_price),2);
                                  @endphp
                                  <td class="text-center">
                                      <span class="gj_ch_t">&#8377;<span class="gj_che_tax">{{$tax_amount}}</span></span>
                                      <input type="hidden" name="tax[]" id="tax_{{$value->product_id}}" class="gj_ch_tax" value="{{$value->tax}}">
                                      <input type="hidden" name="tax_amount[]" id="taxamount_{{$value->product_id}}" class="gj_ch_tax_amt" value="{{$tax_amount}}">
                                  </td>
                                  <!-- <td class="text-center">
                                      <span class="gj_ch_tt">
                                        <span class="gj_che_tax_type">
                                          @if ($value->Products->tax_type == 1)
                                            {{'Inclusive'}}
                                          @elseif ($value->Products->tax_type == 2)
                                            {{'Exclusive'}}
                                          @else
                                            {{'-------'}}
                                          @endif
                                        </span>
                                      </span>
                                      <input type="hidden" name="tax_type[]" id="taxtype_{{$value->product_id}}" class="gj_ch_tax_type" value="{{$value->Products->tax_type}}">
                                  </td> -->
                                  <td class="text-center">
                                    <span class="gj_ch_tot">&#8377; 
                                      <span class="gj_ch_p">
                                        {{$total_price }}
                                      </span>
                                    </span>

                                    <input type="hidden" name="total[]" id="total_{{$value->product_id}}" class="gj_ch_total" value="{{$total_price}}">
                                  </td>
                                </tr>
                              @endforeach
                              <tr>
                                <td colspan="6" class="text-right"> <b> Sub Total(Incl.Tax) </b> </td>
                                <td colspan="1" class="text-center">  <b> <span class="money">&#8377; <span class="gj_ch_sub_tot">0.00</span> </span> </b> </td>

                                <input type="hidden" name="sub_total" id="sub_total">
                                <input type="hidden" name="tax_total" id="tax_total">
                                <input type="hidden" name="serv_total" id="serv_total">
                                <input type="hidden" name="ship_total" id="ship_total">
                                <input type="hidden" name="cod_charge" id="cod_charge">
                                <input type="hidden" name="net_amount" id="net_amount">
                                <input type="hidden" name="cut_off" id="cut_off">
                                <input type="hidden" name="total_items" id="total_items">
                              </tr>

                              <!-- <tr>
                                <td colspan="6" class="text-right"> <b> Tax Total </b> </td>
                                <td colspan="1" class="text-center">  <b> <span class="money">&#8377; <span class="gj_ch_tax_tot">0.00</span> </span> </b> </td>
                              </tr> -->

                              <!-- <tr>
                                <td colspan="6" class="text-right"> <b> Service Charge </b> </td>
                                <td colspan="1" class="text-center">  <b> <span class="money"> &#8377; <span class="gj_ch_sc_tot">0.00</span> </span> </b> </td>
                              </tr> -->

                              <tr>
                                <td colspan="6" class="text-right"> <b> Shipping Charge </b> </td>
                                <td colspan="1" class="text-center">  <b> <span class="money">&#8377; <span class="gj_ch_shc_tot">0.00</span> </span> </b> </td>
                              </tr>

                              <!--<tr class="gj_cod_set">
                                <td colspan="6" class="text-right"> <b> COD Charge </b> </td>
                                <td colspan="1" class="text-center">  <b> <span class="money">&#8377; <span class="gj_ch_cod">0.00</span> </span> </b> </td>
                              </tr>-->

                              <tr>
                                <td colspan="6" class="text-right"> <b> Grand Total </b> </td>
                                <td colspan="1" class="text-center">  <b> <span class="money">&#8377; <span class="gj_ch_grand_tot">0.00</span> </span> </b> </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class=" " id="billing-info">
                           <div class="row">
                              <div class="col-md-12">
                                 <h2 class="page-title">Billing Address</h2>
                                  @if ( count( $errors ) > 0 )
                                    <ul class="all_error">
                                      @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                      @endforeach
                                    </ul>
                                  @endif
                                  <?php
                                   $loged = \session()->get('user');
                                  $users=App\User::find($loged->id);
                                  $shipping=App\ShippingAddress::where('user_id',$loged->id)->first();
                                  ?>
                                  <div class="row">
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                           <input id="user_id" type="hidden" placeholder="user_id" name="user_id" class="form-control input-md" value="{{$users->id}}">

                                           <input id="firstname" type="text" placeholder="First Name" name="first_name" class="form-control input-md" value="{{!empty($shipping)?$shipping->first_name:$users->first_name}}">
                                        </div>
                                        <!-- end form-group -->
                                        <div class="form-group">
                                           <input id="email" type="text" placeholder="Email" name="email" class="form-control input-md email" value="{{!empty($shipping)?$shipping->email:$users->email}}" required>
                                        </div>
                                        <!-- end form-group -->
                                     </div>
                                     <!-- end col -->
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                           <input id="surname" type="text" placeholder="Last Name" name="last_name" class="form-control input-md" value="{{!empty($shipping)?$shipping->last_name:$users->last_name}}">
                                        </div>
                                        <!-- end form-group -->
                                        <div class="form-group">
                                           <input id="phone" type="tel" placeholder="Mobile Number *" name="phone" class="form-control input-md required" value="{{!empty($shipping)?$shipping->contact_no:$users->phone}}" required>
                                        </div>
                                        <!-- end form-group -->
                                     </div>
                                  </div>
                                  <div class="row">
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                           <input id="alternate_contact" type="text" placeholder="Alternate Contact Number" name="alternate_contact" class="form-control input-md" value="{{!empty($shipping)?$shipping->alternate_contact_number:$users->phone2}}">
                                        </div>
                                        <!-- end form-group -->
                                     </div>
                                     <!-- end col -->
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Delivery Pincode:</label>
                                           <input id="pincode" type="text" placeholder="Pincode" name="pincode" class="form-control input-md required" value="{{!empty($shipping)?$shipping->pincode:$users->pincode}}" required>
                                        </div>
                                        <!-- end form-group -->
                                     </div>
                                     <!-- end col -->
                                  </div>
                                  <!-- end row -->

                                  <div class="row">
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <input id="address1" type="text" placeholder="Address with Flat Number / Building Name:" name="address1" class="form-control input-md" value="{{!empty($shipping)?$shipping->address:$users->address1}}" required>
                                      </div>
                                    </div>

                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <input id="address2" type="text" placeholder="Full Address" name="address2" class="form-control input-md" value="{{!empty($shipping)?$shipping->full_address:$users->address2}}" required>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="row">
                                     <div class="col-sm-3">
                                        <div class="form-group">
                                           <input id="landmark" type="text" placeholder="Landmark" name="landmark" class="form-control input-md" value="{{!empty($shipping)?$shipping->landmark:$users->landmark}}" required>
                                        </div>
                                        <!-- end form-group -->
                                     </div>

                                     <div class="col-sm-3">
                                        <div class="form-group">
                                          <select class="form-control country" name="country" id="country" required>
                                            <option value="">--select country--</option>
                                            @if(isset($country) && count($country) != 0)
                                              @foreach ($country as $key => $value)
                                                @if ($value->id == $users->country)
                                                  <option selected value="{{$value->id}}">{{$value->country_name}}</option>
                                                @else
                                                  <option value="{{$value->id}}">{{$value->country_name}}</option>
                                                @endif 
                                              @endforeach 
                                            @endif
                                          </select>
                                        </div>
                                        <!-- end form-group -->
                                     </div>

                                     <div class="col-sm-3">
                                        <div class="form-group">
                                          <input type="hidden" name="old_state" id="old_state" value="{{$users->state}}" >
                                          <select class="form-control state" name="state" id="state" disabled required>
                                            <option value="">--Select State--</option>
                                          </select>
                                        </div>
                                        <!-- end form-group -->
                                     </div>

                                     <div class="col-sm-3">
                                        <div class="form-group">
                                          <input type="hidden" name="old_city" id="old_city" value="{{$users->city}}">
                                          <select class="form-control city" name="city" id="city" disabled>
                                            <option value="">--Select District--</option>
                                          </select>
                                        </div>
                                        <!-- end form-group -->
                                     </div>
                                     <!-- end col -->
                                     <!-- end col -->
                                  </div>
                                  <!-- end row -->
                              </div>
                              <!-- end col -->
                           </div>
                           <!-- end row -->
                        </div>

                        <div class="gj_shp_addrs" id="gj_shipping">
                           <div class="row">
                              <div class="col-md-12">
                                 <h2 class="page-title">Shipping Address</h2>
                                  <div class="row">
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                          <input type="hidden" name="s_id" id="s_id" value="@if(isset($ships->id)){{$ships->id}}@endif">
                                           <input id="s_firstname" type="text" placeholder="First Name" name="s_first_name" class="form-control input-md" value="@if(isset($ships->first_name)){{$ships->first_name}}@endif">
                                        </div>
                                        <!-- end form-group -->
                                        <div class="form-group">
                                           <input id="contact_no" type="text" placeholder="Contact No" name="contact_no" class="form-control input-md email" value="@if(isset($ships->contact_no)){{$ships->contact_no}}@endif">
                                        </div>
                                        <!-- end form-group -->
                                     </div>
                                     <!-- end col -->
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                           <input id="s_last_name" type="text" placeholder="Last Name" name="s_last_name" class="form-control input-md" value="@if(isset($ships->last_name)){{$ships->last_name}}@endif">
                                        </div>
                                        <!-- end form-group -->
                                        <div class="form-group">
                                           <input id="s_landmark" type="tel" placeholder="Landmark" name="s_landmark" class="form-control input-md required" value="@if(isset($ships->landmark)){{$ships->landmark}}@endif">
                                        </div>
                                        <!-- end form-group -->
                                     </div>
                                  </div>
                                  <div class="row">
                                     <div class="col-sm-12">
                                        <div class="form-group">
                                           <input id="address" type="text" placeholder="Full Address with Flat Number / Building Name" name="address" class="form-control input-md" value="@if(isset($ships->address)){{$ships->address}}@endif">
                                        </div>
                                        <!-- end form-group -->
                                     </div>
                                     <!-- end col -->
                                  </div>
                                  <!-- end row -->

                                  <div class="row">
                                     <div class="col-sm-3">
                                        <div class="form-group">
                                           <input id="s_pincode" type="text" placeholder="Pincode" name="s_pincode" class="form-control input-md required" value="@if(isset($ships->pincode)){{$ships->pincode}}@endif">
                                        </div>
                                        <!-- end form-group -->
                                     </div>

                                     <div class="col-sm-3">
                                        <div class="form-group">
                                          <select class="form-control country" name="s_country" id="s_country">
                                            <option value="0">--select country--</option>
                                            @if(isset($country) && count($country) != 0)
                                              @foreach ($country as $key => $value)
                                                @if (isset($ships->country))
                                                  @if ($value->id == $ships->country)
                                                    <option selected value="{{$value->id}}">{{$value->country_name}}</option>
                                                  @else
                                                    <option value="{{$value->id}}">{{$value->country_name}}</option>
                                                  @endif
                                                @else
                                                  <option value="{{$value->id}}">{{$value->country_name}}</option>
                                                @endif 
                                              @endforeach 
                                            @endif
                                          </select>
                                        </div>
                                        <!-- end form-group -->
                                     </div>

                                     <div class="col-sm-3">
                                        <div class="form-group">
                                          <input type="hidden" name="s_old_state" id="s_old_state" value="@if(isset($ships->state)){{$ships->state}}@endif">
                                          <select class="form-control state" name="s_state" id="s_state" disabled>
                                            <option value="">--Select State--</option>
                                          </select>
                                        </div>
                                        <!-- end form-group -->
                                     </div>

                                     <div class="col-sm-3">
                                        <div class="form-group">
                                          <input type="hidden" name="s_old_city" id="s_old_city" value="@if(isset($ships->city)){{$ships->city}}@endif">
                                          <select class="form-control city" name="s_city" id="s_city" disabled>
                                            <option value="">--Select District--</option>
                                          </select>
                                        </div>
                                        <!-- end form-group -->
                                     </div>
                                     <!-- end col -->
                                     <!-- end col -->
                                  </div>
                                  <!-- end row -->
                              </div>
                              <!-- end col -->
                           </div>
                           <!-- end row -->
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                              <div class="form-group">
                                <div class="checkbox">
                                  <label><input type="checkbox" name="is_same" id="is_same" value="1">Shipping Address Same as Billing Address</label>
                                </div>
                              </div>
                            </div>

                            <!--<div class="col-sm-3">
                              <div class="form-group">
                                <div class="checkbox">
                                  <label><input type="checkbox" name="shipping" id="shipping" value="1">Shipping Address</label>
                                </div>
                              </div>
                            </div>-->
                        </div>
                      
                        @if(isset($general) && $general)
                            @if(isset($general->cod) && $general->cod == 1 || isset($general->paypal) && $general->paypal == 1)
                              <div class="payizz gj_pm_opt">
                                 <h2 class="page-title"> Payment Method </h2>
                                    @if($general->cod == 1) 
                                      <label class="radio-inline">
                                        <input type="radio" name="payment_method" value="1" checked> Cash on Delivery
                                      </label>
                                    @endif 

                                    @if($general->paypal == 1) 
                                      <label class="radio-inline">
                                        <input type="radio" name="payment_method" value="2"> Online Payment
                                      </label>
                                    @endif
                                    <!-- <label class="radio-inline">
                                      <input type="radio" name="payment_method" value="3">  PayPal Method
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="payment_method" value="4">  Bank Transfer
                                    </label> -->
                                 <!-- <br>  -->
                                 <!-- <div class="row">
                                    <aside class="col-sm-6">
                                       <h4> Credit Card</h4>
                                       <form role="form">
                                          <div class="form-group">
                                             <label for="username">Full name (on the card)</label>
                                             <div class="input-group">
                                                <div class="input-group-prepend">
                                                   <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="username" placeholder="" required="">
                                             </div>
                                          </div>
                                          <div class="form-group">
                                             <label for="cardNumber">Card number</label>
                                             <div class="input-group">
                                                <div class="input-group-prepend">
                                                   <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="cardNumber" placeholder="">
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-sm-8">
                                                <div class="form-group">
                                                   <label><span class="hidden-xs">Expiration</span> </label>
                                                   <div class="form-inline">
                                                      <select class="form-control" style="width:45%">
                                                         <option>MM</option>
                                                         <option>01 - Janiary</option>
                                                         <option>02 - February</option>
                                                         <option>03 - February</option>
                                                      </select>
                                                      <span style="width:10%; text-align: center"> / </span>
                                                      <select class="form-control" style="width:45%">
                                                         <option>YY</option>
                                                         <option>2018</option>
                                                         <option>2019</option>
                                                      </select>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-sm-4">
                                                <div class="form-group">
                                                   <label data-toggle="tooltip" title="" data-original-title="3 digits code on back side of the card">CVV <i class="fa fa-question-circle"></i></label>
                                                   <input class="form-control" required="" type="text">
                                                </div>
                                             </div>
                                          </div>
                                       </form>
                                    </aside>
                                    <aside class="col-sm-6">
                                    </aside>
                                 </div> -->
                                 <!-- row.// -->
                              </div>
                              <p class="error">Note : You Must Select Payment Method to Proceed</p>

                              <button class="subscribe next-btn16 hover-btn mt-3 coniz" type="submit"> Confirm  </button>
                            @else
                              <p class="gj_no_data">Check Out Process Not Available</p>
                            @endif        
                        @else
                            <p class="gj_no_data">Check Out Process Not Available</p>
                        @endif        
                    {{ Form::close() }}
                </div>
            @else
                <p class="gj_no_data">Products Not Found</p>
            @endif
        </div>
      </div>
      </div>
</div>
</div>

<script src="{{ asset('assetsGrocery/js/jquery-3.3.1.min.js')}}"></script>
<script>
    function gj_round(value, decPlaces) {
      var val = value * Math.pow(10, decPlaces);
      var fraction = (Math.round((val - parseInt(val)) * 10) / 10);

      // -342.055 => -342.06
      if (fraction == -0.5) fraction = -0.6;

      val = Math.round(parseInt(val) + fraction) / Math.pow(10, decPlaces);
      return val;
    }

    function cut_off(sum, shc, sc, tax_tot, cnt_shc) {
        var is_cod = $("input[name='payment_method']:checked").val();
        if(is_cod && is_cod == 1){
            is_cod = 1;
        } else {
            is_cod = 2;
        }
        var cart_ids = $("input[name='c_id[]']")
              .map(function(){return $(this).val();}).get();
               var qty = $("input[name='h_qty[]']")
              .map(function(){return $(this).val();}).get();
              
// console.log(cart_ids);

        $.ajax({
          type: 'post',
          url: '{{url('/check_cut_off')}}',
          data: {cart_ids:cart_ids,sum: sum, tax_tot: tax_tot, cnt_shc: cnt_shc, shc: shc, sc: sc, is_cod: is_cod, type: 'check_cut_off',qty:qty},    
          dataType:"json",   
          success: function(data){
            if(data['error'] == 1){
              $('#cut_off').val(data['shc']);

              $('.gj_ch_sub_tot').html(data['sum']);
              $('#sub_total').val(data['sum']);

              $('.gj_ch_tax_tot').html(data['tax_tot']);
              $('#tax_total').val(data['tax_tot']);

              // $('.gj_ch_sc_tot').html(data['sc']);
              $('#serv_total').val(data['sc']);

              $('.gj_ch_shc_tot').html(data['shc']);
              $('#ship_total').val(data['shc']);

              $('.gj_ch_cod').html(data['cod_amount']);
            //salus   $('#cod_charge').val(data['cod_amount']);

$('#cod_charge').val(0);
              $('.gj_ch_grand_tot').html(data['tot']);
              $('#net_amount').val(data['tot']);          
            }
          }
        });
    }

    function sum() {
        var sum = 0;
        var tax_tot = 0;
        var gj = 0;
        var sc = 0;
        var shc = 0; 
        var cnt_shc = 0; 
        
        $(".gj_ch_p").each(function() {
          var value = $(this).text();
          if(!isNaN(value) && value.length != 0) {
            sum += parseFloat(value);
          }
        });

        $(".gj_ch_tax_amt").each(function() {
          var values = $(this).val();
          if(!isNaN(values) && values.length != 0) {
            tax_tot += parseFloat(values);
          }
        });

        $(".gj_ch_sc").each(function() {
          var value = $(this).val();
          if(!isNaN(value) && value.length != 0) {
            sc += parseFloat(value);
          }
        });

        // $(".gj_ch_shc").each(function() {
        //   var value = $(this).val();
        //   if(!isNaN(value) && value.length != 0) {
        //     shc += parseFloat(value);
        //   }
        // });
        
        if($(".product-item1").find(".gj_ch_shc").length) {
            cnt_shc = $(".product-item1").find(".gj_ch_shc").length;
        }

        var shc = Math.max.apply(Math, $('.gj_ch_shc').map(function(i,elem){ 
            return Number($(elem).val()); 
        }));

        $(".gj_ch_qty").each(function() {
          var value = $(this).val();
          if(!isNaN(value) && value.length != 0) {
            gj += parseFloat(value);
          }
        });

        cut_off(sum, shc, sc, tax_tot, cnt_shc);

        $('#total_items').val(gj);
    }

  $('#shipping').on('change', function() {
    if($(this).is(':checked')) { 
      $('#gj_shipping').slideDown(); 
      $(this).val(1); 
    } else {
      $(this).val(0); 
      $('#gj_shipping').slideUp(); 
    }
  });

    $(document).ready(function() {
        $('p.alert').delay(5000).slideUp(500);
        $('#gj_shipping').hide(); 

        var radioValue = $("input[name='payment_method']:checked").val();
        if(radioValue && radioValue == 1){
            $('.gj_cod_set').show();
        } else {
            $('.gj_cod_set').hide();
        }

        $('input[name="payment_method"]').on('change', function() {
            if($(this).is(':checked')) { 
                if($(this).val() && $(this).val() == 1){
                    $('.gj_cod_set').show();
                } else {
                    $('.gj_cod_set').hide();
                }
            }
            sum();
        });

        if($('#shipping').is(':checked')) { 
          $('#shipping').val(1); 
        } else {
          $('#shipping').val(0); 
        }

        sum();
    });

    $('.gj_ch_qty').on('change', function() {
        var id = $(this).closest('tr').find('.gj_p_id').val();
        var cart_id = $(this).closest('tr').find('.gj_ch_id').val();
        var qty = 1;
        var price = 0;
        var tax_amount = 0;
        var att_name = 0;
        var att_value = 0;
        var tax = 0;
        var tax_type = 0;
        var total = 0.00;
        var is_offer = "No";
        var offer_det_id = 0;
        var hm = $(this);

        if($(this).val() == 0) {
          var qty = 1;
          $(this).val(qty);
          $(this).closest('tr').find('.gj_ch_hqty').val(qty);
        } else {
            var qty = $(this).val();
        }

        if($(this).closest('tr').find('.gj_is_offer').val()) {
          var is_offer = $(this).closest('tr').find('.gj_is_offer').val();
        }

        if($(this).closest('tr').find('.gj_offer_det_id').val()) {
          var offer_det_id = $(this).closest('tr').find('.gj_offer_det_id').val();
        }

        if($(this).closest('tr').find('.gj_ch_att_name').val()) {
            var att_name = $(this).closest('tr').find('.gj_ch_att_name').val();
        }

        if($(this).closest('tr').find('.gj_ch_att_value').val()) {
            var att_value = $(this).closest('tr').find('.gj_ch_att_value').val();
        }

        if($(this).closest('tr').find('.gj_ch_dp').val()) {
            var price = parseFloat($(this).closest('tr').find('.gj_ch_dp').val());
        }

        if($(this).closest('tr').find('.gj_ch_tax_amt').val()) {
            var tax_amount = parseFloat($(this).closest('tr').find('.gj_ch_tax_amt').val());
        }

        if($(this).closest('tr').find('.gj_ch_tax').val()) {
          tax = $(this).closest('tr').find('.gj_ch_tax').val();
        }

        if($(this).closest('tr').find('.gj_ch_tax_type').val()) {
          tax_type = $(this).closest('tr').find('.gj_ch_tax_type').val();
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
            data: {id: id, is_offer: is_offer, offer_det_id: offer_det_id, qty: qty, price: price, att_name: att_name, att_value: att_value, type: 'check_onhand_qty'}, 
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
                            window.location.reload();
                        }
                    }
                });
                $(hm).val(1);
                data = price * $(hm).val();
                data = gj_round(data ,2);
                $(hm).closest('tr').find('.gj_ch_hqty').val(1);
                $(hm).closest('tr').find('.gj_ch_p').html(data);
                $(hm).closest('tr').find('.gj_ch_total').val(data);
                sum();
              } 
              else if(data['error'] == 3){
                $.confirm({
                    title: '',
                    content: 'Out of Stock. Products Not Avaliable!',
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
                data = price * $(hm).val();
                data = gj_round(data ,2);
                $(hm).closest('tr').find('.gj_ch_hqty').val(1);
                $(hm).closest('tr').find('.gj_ch_p').html(data);
                $(hm).closest('tr').find('.gj_ch_total').val(data);
                sum();
              } else if (data.amount != 1) {
                $(hm).val(qty);
               
                 $(hm).closest('tr').find('.gj_che_tax').html(data.tax);
                data = gj_round(data.amount ,2);
               
                $(hm).closest('tr').find('.gj_ch_hqty').val(qty);
                $(hm).closest('tr').find('.gj_ch_p').html(data);
                $(hm).closest('tr').find('.gj_ch_total').val(data);
                sum();
              } else {
                $(hm).val('1');
                data = price * $(hm).val();
                data = gj_round(data ,2);
                $(hm).closest('tr').find('.gj_ch_hqty').val(1);
                $(hm).closest('tr').find('.gj_ch_p').html(data);
                $(hm).closest('tr').find('.gj_ch_total').val(data);
                sum();
              }

              var qtys = $(hm).val();
              var totals = $(hm).closest('tr').find('.gj_ch_total').val();
              var tax_amount = $(hm).closest('tr').find('.gj_ch_tax_amt').val();
              $.ajax({
                type: 'post',
                url: '{{url('/update_qty')}}',
                data: {cart_id: cart_id, qtys: qtys, totals: totals, type: 'update_qty'}, 
                dataType:"json",   
                success: function(data) {

                }
              });
            }
          });        
        }
    });
</script>

<script type="text/javascript">
  $('#is_same').on('change', function() {
    if($(this).is(':checked')) {
      if($('#user_id').val()) {
        var user_id = $('#user_id').val();

        $.ajax({
          type: 'post',
          url: '{{url('/data_billing')}}',
          data: {id: user_id, type: 'data_billing'}, 
          dataType:"json",   
          success: function(data) {
            if(data['error'] == 1) {
              $('#s_firstname').val(data['user']['first_name']);
              $('#s_last_name').val(data['user']['last_name']);
              $('#contact_no').val(data['user']['phone']);
              $('#s_landmark').val(data['user']['landmark']);
              $('#address').val(data['user']['address1'] + ',' + data['user']['address2']);
              $('#s_pincode').val(data['user']['pincode']);
              $('#s_country').val(data['user']['country']);
              $('#s_state').val(data['user']['state']);
              $('#s_city').val(data['user']['city']);
            } else {
              /*$.confirm({
                  title: '',
                  content: 'Please You Have Enter Manually!',
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
          }
        });
      } else {
        $.confirm({
            title: '',
            content: 'Please Enter Manually!',
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
      if($('#user_id').val()) {
        var user_id = $('#user_id').val();

        $.ajax({
          type: 'post',
          url: '{{url('/data_billing')}}',
          data: {id: user_id, type: 'data_shipping'}, 
          dataType:"json",   
          success: function(data) {
            if(data['error'] == 1) {
              $('#s_firstname').val(data['user']['first_name']);
              $('#s_last_name').val(data['user']['last_name']);
              $('#contact_no').val(data['user']['contact_no']);
              $('#s_landmark').val(data['user']['landmark']);
              $('#address').val(data['user']['address']);
              $('#s_pincode').val(data['user']['pincode']);
              $('#s_country').val(data['user']['country']);
              $('#s_state').val(data['user']['state']);
              $('#s_city').val(data['user']['city']);
            } else {
              /*$.confirm({
                  title: '',
                  content: 'Please You Have Enter Manually!',
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
          }
        });
      } else {
        $.confirm({
            title: '',
            content: 'Please Enter Manually!',
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
    }
  });
</script>

@if (isset($items) && count($items) != 0)
  <script type="text/javascript">
    $(document).ready(function() {
      var country = $('#country').val();
      var state = $('#old_state').val();

      var s_country = $('#s_country').val();
      var s_state = $('#s_old_state').val();
      if(country != 0) {
        $.ajax({
          type: 'post',
          url: '{{url('/select_state')}}',
          data: {country: country, state: state, type: 'state'},
          success: function(data){
            if(data){
              $("#state").html(data);
              $("#state").removeAttr("disabled");

              var st = $('#state').val();
              var city = $('#old_city').val();
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

      if(s_country != 0) {
        $.ajax({
          type: 'post',
          url: '{{url('/select_state')}}',
          data: {country: s_country, state: s_state, type: 'state'},
          success: function(data){
            if(data){
              $("#s_state").html(data);
              $("#s_state").removeAttr("disabled");

              var s_st = $('#s_state').val();
              var s_city = $('#s_old_city').val();
              if(s_st) {
                  $.ajax({
                      type: 'post',
                      url: '{{url('/select_city')}}',
                      data: {st: s_st, city: s_city, type: 'city'},
                      success: function(data){
                          if(data){
                              $("#s_city").html(data);
                              $("#s_city").removeAttr("disabled");
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
                              $("#s_city").prop("disabled", true);
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
        if($('#shipping').is(':checked')) { 
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
      }
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
                      $("#state").val(0);
                      $("#city").val(0);
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
                    $("#city").val(0);
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

    $('#s_country').on('change',function() {
      var country = $(this).val();
      if(country) {
          $.ajax({
              type: 'post',
              url: '{{url('/select_state')}}',
              data: {country: country, type: 'state'},
              success: function(data){
                  if(data){
                      $("#s_state").html(data);
                      $("#s_state").removeAttr("disabled");
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
                      $("#s_state").val(0);
                      $("#s_city").val(0);
                      $("#s_state").prop("disabled", true);
                      $("#s_city").prop("disabled", true);
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

    $('#s_state').on('change',function() {
      var st = $(this).val();
      if(st) {
        $.ajax({
            type: 'post',
            url: '{{url('/select_city')}}',
            data: {st: st, type: 'city'},
            success: function(data){
                if(data){
                    $("#s_city").html(data);
                    $("#s_city").removeAttr("disabled");
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
                    $("#s_city").val(0);
                    $("#s_city").prop("disabled", true);
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
@endif
@endsection