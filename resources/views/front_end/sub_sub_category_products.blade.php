<?php 
$banner_path = 'images/banner_image';
$main_cat_path = 'images/main_cat_image';
$sub_cat_path = 'images/sub_cat_image';
$product_path = 'images/featured_products';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';
?>
@extends('layouts.frontend')
@section('title', 'Sub Sub Category')

@section('content')
<!-- SUB CATEGORY SECTION START -->
<section class="gj_main_categ_sec">
    <div class="gj_main_categ_bg">
        <div class="container">
            <div class="row">
                @if(($products) && count($products) != 0)
                    @foreach($products as $key => $value)
                        <div class="col-md-3 col-sm-4 col-xs-6">
                            <div class="gj_whole_ssc_prod">
                                <div class="gj_ssc_prod_img_div">
                                    <a href="{{ route('view_products', ['id' => $value->id]) }}"><img src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="{{$value->product_title}}" class="img-responsive gj_ssc_prod_img"></a>

                                </div>
                                <a href="{{ route('view_products', ['id' => $value->id]) }}"><p class="gj_ssc_prod_img_hd">{{$value->product_title}}</p></a>
                                <p class="gj_ssc_prod_dp"><i class="fa fa-inr"></i>  {{$value->discounted_price}}<span class="gj_ssc_prod_op"><i class="fa fa-inr"></i> {{$value->original_price}}</span></p>
                                
                                <div class="button-link">
                                  <div class="btn-button add-to-cart action  ">
                                    <form action=" " method="post" class="variants" data-id="AddToCartForm-{{$value->id}}" class= "gj_fst_cat_fm" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="{{$value->id}}" />           
                                        <a class="btn-addToCart grl btn_df gj_add2cart" data-cart-id="{{$value->id}}" href="javascript:void(0)" title="Add to cart">
                                            <p class="disable-in-col6">Add to cart</p>
                                            <i class="fa fa-shopping-basket enable-in-col6"></i>
                                        </a>
                                    </form>
                                  </div>
                                    <div class="product-addto-links">
                                        <a class="btn_df btnProduct gj_wish_list" href="" title="Wishlist" data-wish-id="{{$value->id}}">
                                            <i class="fa fa-heart gj_wish_hrt"></i>
                                            <span class="hidden-xs hidden-sm hidden-md">Wishlist</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <div class="gj_whole_ssc_prod">
                            <div class="gj_ssc_prod_img_div">
                                <a href="#"><img src="{{ asset($noimage_path.'/'.$noimage->product_no_image) }}" alt="No Images" class="img-responsive gj_ssc_prod_img"></a>

                            </div>
                            <a href="#"><p class="gj_ssc_prod_img_hd">Products Not Available</p></a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="gj_pagination">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- SUB CATEGORY SECTION END -->
@endsection