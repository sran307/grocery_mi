<?php  
    $product_path = 'images/featured_products';
    $noimage = \DB::table('noimage_settings')->first();
    $noimage_path = 'images/noimage';
?>
@extends('groceryView.layouts.headerFooter')
@section('title', 'View Wish List')
<!--<link rel="stylesheet" type="text/css" href="{{ asset('login/animate.css')}}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('login/main.css')}}">-->
@section('content')

 @include('layouts.normal_user_sidebar')
<div class="col-lg-9 col-md-8">
  <div class="dashboard-right">
    <div class="row">
      <div class="col-md-12">
        <div class="main-title-tab">
          <h4><i class="uil uil-heart"></i>Shopping Wishlist</h4>
        </div>
      </div>
      <div class="col-lg-12 col-md-12">
        <div class="pdpt-bg">
          <div class="wishlist-body-dtt">
            @if (isset($wishlist) && count($wishlist) != 0)
              @php
                         $users = \session()->get('user');
                      @endphp
                    @foreach ($wishlist as $key =>$value)
                     @php
                      $proc=App\Products::find($value->product_id);
                              if($users->user_type==4)
                              {
                                  $unit_price=$proc->original_retailer_price;
                                  $normal=$proc->discount_price;
                              }
                              else if($users->user_type==5)
                              {
                                  $unit_price=$proc->original_dealer_price;
                                   $normal=$proc->discount_price_dealer;
                              }
                              @endphp
                        <div class="cart-item" id="pi_{{$value->id}}">
                          <input type="hidden" name="product_id[]" id="product_{{$value->product_id}}" class="gj_p_id" value="{{$value->product_id}}">
                          <input type="hidden" name="w_id[]" id="wishlist_{{$value->id}}" class="gj_w_id" value="{{$value->id}}">
                          <div class="cart-product-img">
                            <img class="cart__image" src="{{ asset($product_path.'/'.$value->image) }}" alt="{{$value->name}}">
                            <input type="hidden" name="image[]" id="image_{{$value->product_id}}" class= "gj_w_img" value="{{$value->image}}">
                            <input type="hidden" name="name[]" id="name_{{$value->product_id}}" class="gj_w_name" value="{{$value->name}}">
                            <input type="hidden" name="discounted_price[]" id="dp_{{$value->product_id}}" class="gj_w_dp" value="{{$normal}}">
                            <input type="hidden" name="name[]" id="op_{{$value->product_id}}" class="gj_w_op" value="{{$unit_price}}">

                          </div>
                          <div class="cart-text">
                            <h4> <a href="{{ route('view_products', ['id' => $value->product_id]) }}">{{$value->name}}</a></h4>
                            <div class="cart-item-price">{{$code}} {{$proc->discounted_price}}</div>
                            <form action="#" method="post" class="formAddToCart" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="15484180791407">
                                <button class="btn btn-info btnAddToCart gj_add2cart" data-cart-id="{{$value->product_id}}" type="submit" value="Submit">
                                  <span>Add to cart</span>
                                </button>
                            </form>
                            <form method="post" action="{{url('/delete_wishlist')}}" id="removeWishlist" accept-charset="UTF-8">
                              <input name="utf8" type="hidden" value="✓">
                              <input type="hidden" name="id" value="{{$value->id}}">
                              <button type="submit" class="cart-close-btn"><i class="uil uil-trash-alt"></i></button>
                            </form>

                      </div>
                    </div>
                  @endforeach
                @else
                <div class="cart-item">
                  <div class="cart-product-img">Wish List is Empty
                  </div>

                </div>
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
</div>
<script>
  $(document).ready(function() { 
    $('p.alert').delay(2000).slideUp(300);
  });
</script>
@endsection