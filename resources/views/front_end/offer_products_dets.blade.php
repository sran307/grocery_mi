<?php 
use App\Review;

$banner_path = 'images/banner_image';
$main_cat_path = 'images/main_cat_image';
$sub_cat_path = 'images/sub_cat_image';
$product_path = 'images/featured_products';
$product_img_path = 'images/products';
$product_att_path = 'images/attributes';
$profile_img_path = 'images/profile_img';
$brand_img_path = 'images/brands';
$offer_path = 'images/offer_products';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';
?>
@extends('layouts.frontend')
@section('title', 'View Offer Products')
<style>
    /*offer styles*/
    .shopify-payment-button__button {
        border-radius: 4px;
        border: none;
        box-shadow: 0 0 0 0 transparent;
        color: white;
        cursor: pointer;
        display: block;
        font-size: 1em;
        font-weight: 500;
        line-height: 1;
        text-align: center;
        width: 100%;
        transition: background 0.2s ease-in-out;
    }
    .shopify-payment-button__button[disabled] {
        opacity: 0.6;
        cursor: default;
    }
    .shopify-payment-button__button--unbranded {
        background-color: #1990c6;
        padding: 1em 2em;
    }
    .shopify-payment-button__button--unbranded:hover:not([disabled]) {
        background-color: #136f99;
    }
    .shopify-payment-button__more-options {
        background: transparent;
        border: 0 none;
        cursor: pointer;
        display: block;
        font-size: 1em;
        margin-top: 1em;
        text-align: center;
        width: 100%;
    }
    .shopify-payment-button__more-options:hover:not([disabled]) {
        text-decoration: underline;
    }
    .shopify-payment-button__more-options[disabled] {
        opacity: 0.6;
        cursor: default;
    }
    .shopify-payment-button__button--branded {
        display: flex;
        flex-direction: column;
        min-height: 44px;
        position: relative;
        z-index: 1;
    }
    .shopify-payment-button__button--branded .shopify-cleanslate {
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .buyget {
        padding: 2px 9px;
        background: #ff5c00;
        border-radius: 30px;
        font-size: 12px;
        position: absolute;
        top: 6px;
        left: 0px;
        color: #fff;
    }
    .product-item {
        position: relative;
        margin-bottom: 30px;
        padding: 10px;
        background: #fff;
        box-shadow: 0px 0px 10px #000;
    }
    .offdatez{    
        position: absolute;
        top: 0px;
        right: 0px;
    }
    .offdatez li {
        padding: 3px 12px 3px 18px;
        margin: 0px 0px 3px;
        background: #ff5c00;
        position: relative;
        color: #fff;
    }
    .offdatez li:before {
        position: absolute;
        content: " ";
        left: 0px;
        top: 0px;
        border-left: 15px solid #fff;
        border-bottom: 28px solid #ff5c00;
    }
    #shopoffer{
        padding:50px 0px 30px;
    }
    .offdatez li:first-child {
        background: #222222;
    }
    .offdatez li:first-child:before {    
        border-bottom: 28px solid #222222;
    }
    span.gj_offer_desc {
        height: 38px;
        overflow: hidden;
        display: block;
    }
    .shopify-payment-button__button--hidden {
        visibility: hidden;
    }
    .pt50 {
        padding-top:50px;
    }
    .hilit {
        font-size:16px;
    }
    .hilit label {
        font-weight: 700!important;
        font-size: 16px!important;
        color: #ff5c00!important;
    }
    /*offer styles*/    
</style>

@section('content')
<!-- SUB CATEGORY SECTION START -->
@if($offer_products)
<section class="gj_view_product_sec">
    <div class="main-content maxil" id="MainContent">
        <div class="container">
            <div class="col-main col-full">
                <div id="shopify-section-product-template" class="shopify-section main-product">
                    <div id="ProductSection-product-template" class="bzoom product-template__containe product" >
                        <input type="hidden" name="offer_id" id="offer_id" value="{{$offer_products->id}}">

                        <div class="product-single ">
                            <div class="row">
                                <div class="col-lg-5 col-md-12 col-sm-12 col-12  horizontal">
                                    <div class=" product-media thumbnais-bottom">
                                        <div   class="product-photo-container slider-for horizontal">
                                            <div class="thumb">
                                                @if($offer_products && $offer_products->image)
                                                    <a class="fancybox" rel="gallery1" href="{{ asset($offer_path.'/'.$offer_products->image)}}" >
                                                        <img id="product-featured-image-4774111576175" class="product-featured-img" src="{{ asset($offer_path.'/'.$offer_products->image)}}" alt="Offers" data-zoom-image="{{ asset($offer_path.'/'.$offer_products->image)}}"/>
                                                    </a>
                                                @else
                                                    <a class="fancybox" rel="gallery1" href="#" >
                                                        <img id="product-featured-image-4774111576175" class="product-featured-img" src="{{ asset($noimage_path.'/'.$noimage->product_no_image)}}" alt="no image" data-zoom-image="{{ asset($noimage_path.'/'.$noimage->product_no_image)}}"/>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-7 col-md-12 col-sm-12 col-12 product-single__detail grid__item ">
                                    <div class="product-single__meta">
                                        <h1 itemprop="name" class="product-single__title"> {{$offer_products->offer_title}}  </h1>
                                        <hr>
                                        
                                        <div>
                                            <p class="lorip">{{$offer_products->description}}</p>
                                            
                                            <p class="hilit"><label >Start Date </label>: {{date('d-m-Y g:ia', strtotime($offer_products->offer_start))}}</p>
                                            
                                            <p class="hilit"><label>Expires on </label>:  {{date('d-m-Y g:ia', strtotime($offer_products->offer_end))}}</p>
                                        </div>
                                        <hr>

                                        <div class="gj_off_avil">
                                            <h4>Buy {{$offer_products->x_pro_cnt}} Get {{$offer_products->y_pro_cnt}}</h4>
                                            <p class="gj_off_note">You have Get This Offer For Buy {{$offer_products->x_pro_cnt}} and Get {{$offer_products->y_pro_cnt}}. You Have Selected Main Product to {{$offer_products->x_pro_cnt}} Products and Offer Products to {{$offer_products->y_pro_cnt}} Products.</p>
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="stripe">
                                    <div class="product-details">
                                        <?php echo $offer_products->grab_offer; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="shopoffer" class="shopify-section">
                            <div class="page-hotdeal">
                                <div class="container">
                                    <!-- Main Products Start -->
                                    <div class="widget-head">
                                        <div class="home-title">
                                            <h2>Main Products  </h2>
                                        </div>
                                    </div>
                                    
                                    <br>
                                    
                                    <div class="col-main col-lg-12 col-12">
                                        <div id="shopify-section-collection-template" class="shopify-section">
                                            <div data-section-id="collection-template" data-section-type="collection-template" class="products-collection">
                                                <div class="product-wrapper" id="Collection">
                                                    <div class="products-listing products-grid grid row EndlessClick">
                                                        @if(sizeof($main_products) != 0)
                                                            @foreach($main_products as $mkey => $mval)
                                                                @if($mval->product_id)
                                                                    <div id="offer-{{$mval->id}}" class="gj_offer_product product product-layout grid__item grid__item--collection-template col-xl-3 col-lg-4 col-md-4 col-sm-4 col-xs-6 grid_4" data-price="{{$mval->offer_price}}">
                                                                        <span class="d-none"><span class="money" data-currency-usd="<i class="fa fa-inr"></i> {{$mval->offer_price}}"> <i class="fa fa-inr"></i>{{$mval->offer_price}}</span></span>
                                                                        
                                                                        <div class="product-item" data-id="product-1674860167279">
                                                                            <div class="product-item-container grid-view-item   ">
                                                                                <div class="left-block">
                                                                                    <div class="product-image-container product-image">
                                                                                        <a class="grid-view-item__link image-ajax" href="{{ route('view_products', ['id' => $mval->product_id]) }}">
                                                                                            @if($mval->OfferProducts->featured_product_img)
                                                                                                <img class="img-responsive s-img lazyautosizes lazyloaded" data-sizes="auto" src="{{ asset($product_path.'/'.$mval->OfferProducts->featured_product_img) }}" data-src="{{ asset($product_path.'/'.$mval->OfferProducts->featured_product_img) }}" alt="Offer Main Products" sizes="254px">
                                                                                            @else
                                                                                                <img class="img-responsive s-img lazyautosizes lazyloaded" data-sizes="auto" src="{{ asset($noimage_path.'/'.$noimage->product_no_image) }}" data-src="{{ asset($noimage_path.'/'.$noimage->product_no_image) }}" alt="Offer Main Products" sizes="254px">
                                                                                            @endif
                                                                                        </a>  
                                                                                        
                                                                                        <div class="box-countdown">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="right-block">
                                            <div class="button-link">
                                                <div class="btn-button add-to-cart action  ">
                                                    <a class="gj_off_addtocart btn-addToCart grl btn_df" href="javascript:void(0)" data-off-id="{{$mval->offer}}" data-off-det-id="{{$mval->id}}" data-off-type="{{$mval->type}}" title="Add to cart">Add to cart</a>
                                                </div>
                                            </div>
                                                                                    
                                            <div class="caption">
                                                <div class="custom-reviews hidden-xs">
                                                    <?php 
                                                        $avgStar = Review::Where('product_id', $mval->product_id)->avg('rating');
                                                        $avgStar = round($avgStar); 
                                                        $tot_rev = 5;
                                                    ?>
                                                    <span class="spr-badge" id="spr_badge_1674860167279" data-rating="{{$avgStar}}">
                                                        @if($avgStar != 0)
                                                            <span class="spr-starrating spr-badge-starrating" title="{{$avgStar}}/5">
                                                                @for ($i=0; $i<$tot_rev; $i++)
                                                                    @if($i < $avgStar)
                                                                        <i class="fa fa-star"></i>
                                                                    @else
                                                                        <i class="fa fa-star-o"></i>
                                                                    @endif
                                                                @endfor
                                                            </span>
                                                        @else
                                                            <span class="spr-starrating spr-badge-starrating"  title="0/5">
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star-o"></i>
                                                            </span>
                                                        @endif
                                                    </span>
                                                </div>

                                                <h4 class="title-product text-truncate">
                                                    <a class="product-name" href="{{ route('view_products', ['id' => $mval->product_id]) }}">
                                                        <span class="gj_off_title">{{$mval->OfferProducts->product_title}}</span>

                                                        @if($mval->att_name)
                                                            @if($mval->att_value)
                                                                @if($mval->AttName->att_name && $mval->AttValue->att_value)
                                                                    <p class="gj_off_atts"> ({{$mval->AttName->att_name}} : {{$mval->AttValue->att_value}})</p>
                                                                    
                                                                    <input type="hidden" name="att_name[]" class="gj_att_name" value="{{$mval->att_name}}">

                                                                    <input type="hidden" name="att_value[]" class="gj_att_value" value="{{$mval->att_value}}">
                                                                @else
                                                                    <input type="hidden" name="att_name[]" class="gj_att_name" value="">

                                                                    <input type="hidden" name="att_value[]" class="gj_att_value" value="">
                                                                @endif
                                                            @else
                                                                <input type="hidden" name="att_name[]" class="gj_att_name" value="">

                                                                <input type="hidden" name="att_value[]" class="gj_att_value" value="">
                                                            @endif
                                                        @else
                                                            <input type="hidden" name="att_name[]" class="gj_att_name" value="">

                                                            <input type="hidden" name="att_value[]" class="gj_att_value" value="">
                                                        @endif
                                                    </a>
                                                </h4>
                                                
                                                <div class="price">
                                                    <span class="visually-hidden">Regular price</span>
                                                    <span class="price-new"><span class="money" data-currency-usd="<i class="fa fa-inr"></i> {{$mval->offer_price}}"> <i class="fa fa-inr"></i>{{$mval->offer_price}}</span></span> 
                                                </div>
                                            </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <p class="gj_no_data">Data Not Available</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Main Products End -->

                                    <br>

                                    <!-- Offer Products Start -->
                                    <div class="widget-head">
                                        <div class="home-title">
                                            <h2 class="text-center">Offer Products  </h2>
                                        </div>
                                    </div>
                                    
                                    <br>

                                    <div class="col-main col-lg-12 col-12">
                                        <div id="shopify-section-collection-template" class="shopify-section">
                                            <div data-section-id="collection-template" data-section-type="collection-template" class="products-collection">
                                                <div class="product-wrapper" id="Collection">
                                                    <div class="products-listing products-grid grid row EndlessClick">
                                                        @if(sizeof($offer_pds) != 0)
                                                            @foreach($offer_pds as $okey => $oval)
                                                                @if($oval->product_id)
                                                                    <div id="offer-{{$oval->id}}" class="gj_offer_product product product-layout grid__item grid__item--collection-template col-xl-3 col-lg-4 col-md-4 col-sm-4 col-xs-6 grid_4" data-price="{{$oval->offer_price}}">
                                                                        <span class="d-none"><span class="money" data-currency-usd="<i class="fa fa-inr"></i> {{$oval->offer_price}}"> <i class="fa fa-inr"></i>{{$oval->offer_price}}</span></span>
                                                                        
                                                                        <div class="product-item" data-id="product-1674860167279">
                                                                            <div class="product-item-container grid-view-item   ">
                                                                                <div class="left-block">
                                                                                    <div class="product-image-container product-image">
                                                                                        <a class="grid-view-item__link image-ajax" href="{{ route('view_products', ['id' => $oval->product_id]) }}">
                                                                                            @if($oval->OfferProducts->featured_product_img)
                                                                                                <img class="img-responsive s-img lazyautosizes lazyloaded" data-sizes="auto" src="{{ asset($product_path.'/'.$oval->OfferProducts->featured_product_img) }}" data-src="{{ asset($product_path.'/'.$oval->OfferProducts->featured_product_img) }}" alt="Offer Main Products" sizes="254px">
                                                                                            @else
                                                                                                <img class="img-responsive s-img lazyautosizes lazyloaded" data-sizes="auto" src="{{ asset($noimage_path.'/'.$noimage->product_no_image) }}" data-src="{{ asset($noimage_path.'/'.$noimage->product_no_image) }}" alt="Offer Main Products" sizes="254px">
                                                                                            @endif
                                                                                        </a>  
                                                                                        
                                                                                        <div class="box-countdown">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                        <div class="right-block">
                                            <div class="button-link">
                                                <div class="btn-button add-to-cart action  ">
                                                    <a class="gj_off_addtocart btn-addToCart grl btn_df" href="javascript:void(0)" data-off-id="{{$oval->offer}}" data-off-det-id="{{$oval->id}}" data-off-type="{{$oval->type}}" title="Add to cart">Add to cart</a>
                                                </div>
                                            </div>
                                                                                    
                                            <div class="caption">
                                                <div class="custom-reviews hidden-xs">
                                                    <?php 
                                                        $avgStar = Review::Where('product_id', $oval->product_id)->avg('rating');
                                                        $avgStar = round($avgStar); 
                                                        $tot_rev = 5;
                                                    ?>
                                                    <span class="spr-badge" id="spr_badge_1674860167279" data-rating="{{$avgStar}}">
                                                        @if($avgStar != 0)
                                                            <span class="spr-starrating spr-badge-starrating" title="{{$avgStar}}/5">
                                                                @for ($i=0; $i<$tot_rev; $i++)
                                                                    @if($i < $avgStar)
                                                                        <i class="fa fa-star"></i>
                                                                    @else
                                                                        <i class="fa fa-star-o"></i>
                                                                    @endif
                                                                @endfor
                                                            </span>
                                                        @else
                                                            <span class="spr-starrating spr-badge-starrating"  title="0/5">
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star-o"></i>
                                                            </span>
                                                        @endif
                                                    </span>
                                                </div>

                                                <h4 class="title-product text-truncate">
                                                    <a class="product-name" href="{{ route('view_products', ['id' => $oval->product_id]) }}">
                                                        <span class="gj_off_title">{{$oval->OfferProducts->product_title}}</span>

                                                        @if($oval->att_name)
                                                            @if($oval->att_value)
                                                                @if($oval->AttName->att_name && $oval->AttValue->att_value)
                                                                    <p class="gj_off_atts"> ({{$oval->AttName->att_name}} : {{$oval->AttValue->att_value}})</p>

                                                                    <input type="hidden" name="att_name[]" class="gj_att_name" value="{{$oval->att_name}}">

                                                                    <input type="hidden" name="att_value[]" class="gj_att_value" value="{{$oval->att_value}}">
                                                                @else
                                                                    <input type="hidden" name="att_name[]" class="gj_att_name" value="">

                                                                    <input type="hidden" name="att_value[]" class="gj_att_value" value="">
                                                                @endif
                                                            @else
                                                                <input type="hidden" name="att_name[]" class="gj_att_name" value="">

                                                                <input type="hidden" name="att_value[]" class="gj_att_value" value="">
                                                            @endif
                                                        @else
                                                            <input type="hidden" name="att_name[]" class="gj_att_name" value="">

                                                            <input type="hidden" name="att_value[]" class="gj_att_value" value="">
                                                        @endif
                                                    </a>
                                                </h4>
                                                
                                                <!-- <div class="price">
                                                    <span class="visually-hidden">Regular price</span>
                                                    <span class="price-new"><span class="money" data-currency-usd="<i class="fa fa-inr"></i> {{$oval->offer_price}}"> <i class="fa fa-inr"></i>{{$oval->offer_price}}</span></span> 
                                                </div> -->
                                            </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <p class="gj_no_data">Data Not Available</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Offer Products End -->
                                </div>
                            </div>
                        </div>
                        <!-- END content_for_index -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Select Offers Items Start -->
        <div id="gj_sel_offs_item" style="display: none;">
            <div class="gj_used_cmpl_box">
                {{ Form::open(array('url' => 'offer_add_to_cart','class'=>'used_cmpl_form','id'=>'used_cmpl_form','files' => true)) }}
                    <div id="used_cmp_cnt">
                        <div class="gj_append_html">
                            
                        </div>
                        
                        <div class="j_compare_butt1">
                            <input type="submit" value="Proceed">
                            
                            <div class="j_compare_butt2">
                                <input type="button" value="Discard" id="gj_rem_all_offs">
                            </div>
                        </div>

                        <div class="gj_close_icon">
                            <a href="javascript:void(0)" class="showit btn" role="button">Show Less <i class="fa fa-caret-square-o-down"></i></a>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>  
        </div>
        <!-- Select Offers Items End -->
    </div>
</section>
@else
<section class="gj_view_product_sec">
    <div class="main-content maxil" id="MainContent">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p class="gj_no_data">Data Not Available</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!-- SUB CATEGORY SECTION END -->
<script>
    /* Remove AddToCart Item Script Start*/
    $(document).on('click','.remov_added_used',function(){
        var removeable_id=$(this).closest('.added_used').find('input').val();
        $(this).closest('.added_used').remove();
        if($('.added_used').length<=0){
            $('#gj_sel_offs_item').css('display','none');
        }
    });
    /* Remove AddToCart Item Script End*/

    /* Discard All Script Start*/
    $(document).on('click','#gj_rem_all_offs',function(){
        $('#gj_sel_offs_item').css('display','none');
        $('.added_used').remove();
    });
    /* Discard All Script End*/

    /* Discard All Script End*/
    $(document).on('click',".showit",function(){
        $(".gj_used_cmpl_box").toggleClass("j_less");
        if($(".gj_used_cmpl_box").hasClass('j_less')) {
            $('.gj_close_icon').find('a').html('Show More <i class="fa fa-caret-square-o-up"></i>');
        } else {
            $('.gj_close_icon').find('a').html('Show Less <i class="fa fa-caret-square-o-down"></i>');
        }
    }); 
    /* Discard All Script End*/

    /* Offer Add To Cart Script Start*/
    $(document).on("click",".gj_off_addtocart",function(){
        var offer_id = 0;
        var offer_det_id = 0;
        var offer_type = 0;
        var img = 0;
        var title = 0;
        var atts = 0;

        if($(this).attr('data-off-id')) {
            offer_id = $(this).attr('data-off-id');
        }

        if($(this).attr('data-off-det-id')) {
            offer_det_id = $(this).attr('data-off-det-id');
        }

        if($(this).attr('data-off-type')) {
            offer_type = $(this).attr('data-off-type');
        }

        $(".select_offer_det_id").each(function() {
            var value = $(this).val();
            if(value == offer_det_id) {
                $.confirm({
                    title: '',
                    content: 'Already Added!',
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
                window.location.reload();
            }
        });

        if($(this).closest('.gj_offer_product').find('.s-img').attr('src')) {
            img = $(this).closest('.gj_offer_product').find('.s-img').attr('src');
        }

        if($(this).closest('.gj_offer_product').find('.gj_off_title').text()) {
            title = $(this).closest('.gj_offer_product').find('.gj_off_title').text();
        }

        if($(this).closest('.gj_offer_product').find('.gj_off_atts').text()) {
            atts = $(this).closest('.gj_offer_product').find('.gj_off_atts').text();
        }

        if(offer_id && offer_det_id && img && title) {
            if(atts) {
                var patts = '<p class="gj_ref_codes">'+atts+'</p>';
            } else {
                var patts = '';
            }

            var html='<div class="pull-left added_used"><input type="hidden" name="select_offer_id[]" class="select_offer_id" value="'+offer_id+'"><input type="hidden" name="select_offer_det_id[]" class="select_offer_det_id" value="'+offer_det_id+'"><input type="hidden" name="select_offer_type[]" class="select_offer_type" value="'+offer_type+'"><img src="'+img+'" class="img-responsive j_imgs_comp">'+patts+'<p class="text-center"  title="'+title+'">'+title+'</p><span class="remov_added_used">X</span></div>';
            $('.gj_append_html').append(html);
            $('#gj_sel_offs_item').css('display','block');
        } else {
            $.confirm({
                title: '',
                content: 'Add To Cart Not Available This Time!',
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
    /* Offer Add To Cart Script End*/
</script>
@endsection