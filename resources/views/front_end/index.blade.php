<?php 
$banner_path = 'images/banner_image';
$brand_path = 'images/brands';
$main_cat_path = 'images/main_cat_image';
$product_path = 'images/featured_products';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';
$ads_cat= \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)
->Where('page', 'Home Page')->Where('position', 'Top Middle')
->Where('ad_start_date', '<=',date('Y-m-d'))->Where('ad_end_date', '>=',date('Y-m-d'))
->orderBy('id','desc')->limit(3)
->get();
$index_tr_as = \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)
->Where('page', 'Home Page')->Where('position', 'Top Right')
->first();
$index_cat2_as = \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)->Where('page', 'Home Category-2')->Where('position', 'Right')->first();
$index_cat3_as = \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)->Where('page', 'Home Category-3')->Where('position', 'Right')->first();
$middle_as = \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)->Where('page', 'Home Page')->Where('position', 'Middle')->first();
$left_offer = \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)->Where('page', 'Home Page')->Where('position', 'Bottom Left')->first();
$right_offer = \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)->Where('page', 'Home Page')->Where('position', 'Bottom Right')->first();
$nw_date = date('Y-m-d');
$nw_date = date('Y-m-d', strtotime($nw_date));
$side_image=\DB::table('banner_side_images')->Where('is_block', 1)->get();
if($index_tr_as) {
    $st_date = date('Y-m-d', strtotime($index_tr_as->ad_start_date));
    $en_date = date('Y-m-d', strtotime($index_tr_as->ad_end_date));
}

if($index_cat2_as) {
    $st_date2 = date('Y-m-d', strtotime($index_cat2_as->ad_start_date));
    $en_date2 = date('Y-m-d', strtotime($index_cat2_as->ad_end_date));
}

if($index_cat3_as) {
    $st_date3= date('Y-m-d', strtotime($index_cat3_as->ad_start_date));
    $en_date3 = date('Y-m-d', strtotime($index_cat3_as->ad_end_date));
}

if($middle_as) {
    $st_date4= date('Y-m-d', strtotime($middle_as->ad_start_date));
    $en_date4 = date('Y-m-d', strtotime($middle_as->ad_end_date));
}

if($left_offer) {
    $st_date5= date('Y-m-d', strtotime($left_offer->ad_start_date));
    $en_date5 = date('Y-m-d', strtotime($left_offer->ad_end_date));
}

if($right_offer) {
    $st_date6= date('Y-m-d', strtotime($right_offer->ad_start_date));
    $en_date6 = date('Y-m-d', strtotime($right_offer->ad_end_date));
}
?>
@extends('layouts.frontend')
@section('title', 'Home')

@section('content')
<style>
     .shopify-payment-button__button--unbranded {
         background-color: #1990c6;
         padding: 1em 2em;
         }
         .shopify-payment-button__button--unbranded:hover:not([disabled]) {
         background-color: #136f99;
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
</style>
<div id="wrapper" class="page-wrapper wrapper-full effect_10">
    <div id="shopify-section-header" class="shopify-section"></div>
    <div class="quick-view"></div>
    <div class="page-container" id="PageContainer">
        <div class="main-content" id="MainContent">
            <!-- Banner Section Start -->
            <!--<section class="gj_banner_sec">
                <div id="shopify-section-1527310410724" class="shopify-section home-section">
                    <div class="widget-slideshow widget-slideshow1">
                        <div class="container">
                            <div class="row">
                                @if($index_tr_as)
                                    @if($index_tr_as->ad_image)
                                        @if(($nw_date > $st_date) && ($nw_date < $en_date))
                                            <div class="col-slider col-xl-8 col-lg-8 col-md-8 col-12 col_2">
                                        @else
                                        <div class="col-slider col-xl-12 col-lg-12 col-md-12 col-12 col_2">
                                        @endif
                                    @else
                                    <div class="col-slider col-xl-12 col-lg-12 col-md-12 col-12 col_2">
                                    @endif
                                @else
                                <div class="col-slider col-xl-12 col-lg-12 col-md-12 col-12 col_2">
                                @endif
                                    <div class="tp-banner-container" data-section-id="1527310410724" data-section-type="slideshow-section" style="visibility: hidden; opacity: 0; height: 440px;">
                                        <span class="slider-preload" style="display: block;"></span>
                                        <div class="tp-banner-1527310410724" data-speed="3000">
                                            <ul>
                                                @if($banner_images)
                                                    @if(count($banner_images) != 0)
                                                        @foreach($banner_images as $key => $value)
                                                            <li data-transition="fade" data-slotamount="1" data-masterspeed="1000" data-delay="{{$value->slider_speed}}" data-link="{{$value->redirect_url}}" data-aid="{{$value->id}}" data-target="_blank">
                                                                <img src="{{ asset($banner_path.'/'.$value->banner_image)}}" alt="{{$value->image_title}}" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat">
                                                            </li>
                                                        @endforeach
                                                    @else
                                                        <li data-transition="fade" data-slotamount="1" data-masterspeed="1000" data-link="#" data-target="_blank">
                                                            <img src="{{ asset($noimage_path.'/'.$noimage->banner_no_image)}}" alt="No Image" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat">
                                                        </li>
                                                    @endif
                                                @else
                                                    <li data-transition="fade" data-slotamount="1" data-masterspeed="1000" data-link="#" data-target="_blank">
                                                        <img src="{{ asset($noimage_path.'/'.$noimage->banner_no_image)}}" alt="No Image" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat">
                                                    </li>
                                                @endif
                                            </ul>
                                            <div class="tp-bannertimer"></div>
                                        </div>
                                    </div>
                                </div>

                                @if($index_tr_as)
                                    @if($index_tr_as->ad_image)
                                        @if(($nw_date >= $st_date) && ($nw_date < $en_date))
                                            <div class="image-ad col-xl-4 col-lg-4 col-md-4 d-none d-xl-block">
                                                <div class="bannerstop banners">
                                                    <div class="row">
                                                        <div class="item1 col-xl-12 col-12">
                                                            <a href="{{$index_tr_as->ad_website}}" class="gj_h_tr_as">
                                                                <img class="img-responsive lazyload" data-sizes="auto" src="{{ asset('frontend/images/icon-loadings.svg')}}" alt="{{$index_tr_as->ad_title}}" data-src="{{asset('images/category_advertisement/'.$index_tr_as->ad_image)}}" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                    
                            </div>
                        </div>
                    </div>
                    <script>
                        var revapi;
                        jQuery(document).ready(function($) {
                            //$(window).load(function() {
                                $('.slider-preload').hide();
                                $('.tp-banner-container').css({'visibility': 'visible', 'opacity': '1'});
                                revapi = $('.tp-banner-1527310410724').revolution({
                                    delay:16000,
                                    startwidth: 1090,
                                    startheight:440,
                                    startWithSlide: 0,
                                    hideThumbs:10,
                                    fullWidth:"off",
                                    navigationType: "bullet",
                                    navigationStyle: "round",
                                    navigationArrows: "solo",
                                    fullScreen: 'off',
                                    hideTimerBar: 'off'
                                })
                            //})
                        })
                    </script>
                </div>
            </section>-->
            <!-- Banner Section End -->
 <div id="shopify-section-1527310410724" class="shopify-section home-section">
                  <link href="css/ss_slider.css" rel="stylesheet" type="text/css" media="all" />
                  <script src="js/jquery.themepunch.tools.min.js" type="text/javascript"></script>
                  <script src="js/jquery.themepunch.revolution.min.js" type="text/javascript"></script>
                  <div class="widget-slideshow widget-slideshow1">
                      <div class="main-slider">
                     <div class="container-fluid">
                         <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
					     @if($banner_images)
                                                    @if(count($banner_images) != 0)
                                                        @foreach($banner_images as $key => $value)
                            						<li data-target="#carouselExampleCaptions" data-slide-to="{{$key}}" @if($key==0) class="active" @endif></li>
                            					 @endforeach
                                                    
                                                    @endif
                                                @endif
					</ol>
					
					<div class="carousel-inner">
					     @if($banner_images)
                                                    @if(count($banner_images) != 0)
                                                        @foreach($banner_images as $key => $value)
						<div class="carousel-item @if($key==0) active  @endif"> <img src="{{ asset($banner_path.'/'.$value->banner_image)}}"
						class="d-block w-100" alt="...">
							<div class="carousel-caption d-none d-md-block">
								<h3>{{$value->image_title}}</h3>
							</div>
						</div>
						@endforeach
						 @endif
                                                @endif
						
					</div>
					<a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a>
					<a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>
				</div>
                        
                     </div>
                     </div>
                  </div>
                  <script>
                     var revapi;
                     jQuery(document).ready(function($) {
                       //$(window).load(function() {
                         $('.slider-preload').hide();
                         $('.tp-banner-container').css({'visibility': 'visible', 'opacity': '1'});
                         revapi = $('.tp-banner-1527310410724').revolution({
                           delay:16000,
                           startwidth: 1090,
                           startheight:440,
                           startWithSlide: 0,
                           hideThumbs:10,
                           fullWidth:"off",
                           navigationType: "bullet",
                           navigationStyle: "round",
                           navigationArrows: "solo",
                           fullScreen: 'off',
                           hideTimerBar: 'off'
                         })
                       //})
                     })
                  </script>
               </div>
               
               
                	   <section class="advetis">
                  <div class="container">
                     <div class="row">
                         @foreach($ads_cat as $value)
                        <div class="col-md-4">
                           <img src="{{asset('images/category_advertisement/'.$value->ad_image)}}" style="height:136px">
                        </div>
                       @endforeach
                     </div>
                  </div>
               </section>

            <!-- Free Delivery Section Start -->
            <section class="gj_free_dry_sec">
                <div id="shopify-section-1527301767427" class="shopify-section home-section">
                    <div class="container">
                        <div class="widget-services-v1 home-policy">
                            <div class="services-inline">
                                <h3 class="title-home hidden">Home Policy</h3>
                                <div class="ss-carousel ss-owl widget-sevicer">
                                    <div class="product-layout owl-carousel" 
                                        data-dots      ="false" 
                                        data-nav           ="false" 
                                        data-margin        ="30"
                                        data-autoplay  ="false" 
                                        data-autospeed ="10000" 
                                        data-speed     ="300"
                                        data-column1       ="5" 
                                        data-column2       ="4" 
                                        data-column3       ="3" 
                                        data-column4       ="2" 
                                        data-column5       ="2">
                                        @if($widget)
                                            <div class="policy policy0">
                                                <div class="policy_inner">
                                                    @if($widget->first_url)
                                                    <a href="{{$widget->first_url}}">
                                                    @else
                                                    <a href="#">
                                                    @endif
                                                        <div class="service-ico">
                                                            @if($widget->first_icon)
                                                                <i class="fa {{$widget->first_icon}}"></i>
                                                            @else
                                                                <i class="fa fa-truck"></i>
                                                            @endif
                                                        </div>
                                                        <div class="service-info">
                                                            @if($widget->first_title)
                                                                <h2 class="title">{{$widget->first_title}}</h2>
                                                            @else
                                                                <h2 class="title">Free Delivery</h2>
                                                            @endif

                                                            @if($widget->first_content)
                                                                <p class="des">{{$widget->first_content}}</p>
                                                            @else
                                                                <p class="des">From  {{$code}}  500.00</p>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        @if($widget)
                                            <div class="policy policy1">
                                                <div class="policy_inner">
                                                    @if($widget->second_url)
                                                    <a href="{{$widget->second_url}}">
                                                    @else
                                                    <a href="#">
                                                    @endif
                                                        <div class="service-ico">
                                                            @if($widget->second_icon)
                                                                <i class="fa {{$widget->second_icon}}"></i>
                                                            @else
                                                                <i class="fa fa-user"></i>
                                                            @endif
                                                        </div>
                                                        <div class="service-info">
                                                            @if($widget->second_title)
                                                                <h2 class="title">{{$widget->second_title}}</h2>
                                                            @else
                                                                <h2 class="title">Support 24/7</h2>
                                                            @endif

                                                            @if($widget->second_content)
                                                                <p class="des">{{$widget->second_content}}</p>
                                                            @else
                                                                <p class="des">Online 24 hours</p>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        @if($widget)
                                            <div class="policy policy2">
                                                <div class="policy_inner">
                                                    @if($widget->third_url)
                                                    <a href="{{$widget->third_url}}">
                                                    @else
                                                    <a href="#">
                                                    @endif
                                                        <div class="service-ico">
                                                            @if($widget->third_icon)
                                                                <i class="fa {{$widget->third_icon}}"></i>
                                                            @else
                                                                <i class="fa fa-refresh"></i>
                                                            @endif
                                                        </div>
                                                        <div class="service-info">
                                                            @if($widget->third_title)
                                                                <h2 class="title">{{$widget->third_title}}</h2>
                                                            @else
                                                                <h2 class="title">Free return</h2>
                                                            @endif

                                                            @if($widget->third_content)
                                                                <p class="des">{{$widget->third_content}}</p>
                                                            @else
                                                                <p class="des">365 a day</p>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        @if($widget)
                                            <div class="policy policy3">
                                                <div class="policy_inner">
                                                    @if($widget->fourth_url)
                                                    <a href="{{$widget->fourth_url}}">
                                                    @else
                                                    <a href="#">
                                                    @endif
                                                        <div class="service-ico">
                                                            @if($widget->fourth_icon)
                                                                <i class="fa {{$widget->fourth_icon}}"></i>
                                                            @else
                                                                <i class="fa fa-credit-card"></i>
                                                            @endif
                                                        </div>
                                                        <div class="service-info">
                                                            @if($widget->fourth_title)
                                                                <h2 class="title">{{$widget->fourth_title}}</h2>
                                                            @else
                                                                <h2 class="title">Payment Method</h2>
                                                            @endif

                                                            @if($widget->fourth_content)
                                                                <p class="des">{{$widget->fourth_content}}</p>
                                                            @else
                                                                <p class="des">Secure payment</p>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        @if($widget)
                                            <div class="policy policy4">
                                                <div class="policy_inner">
                                                    @if($widget->fifth_url)
                                                    <a href="{{$widget->fifth_url}}">
                                                    @else
                                                    <a href="#">
                                                    @endif
                                                        <div class="service-ico">
                                                            @if($widget->fifth_icon)
                                                                <i class="fa {{$widget->fifth_icon}}"></i>
                                                            @else
                                                                <i class="fa fa-money"></i>
                                                            @endif
                                                        </div>
                                                        <div class="service-info">
                                                            @if($widget->fifth_title)
                                                                <h2 class="title">{{$widget->fifth_title}}</h2>
                                                            @else
                                                                <h2 class="title">Big Saving</h2>
                                                            @endif

                                                            @if($widget->fifth_content)
                                                                <p class="des">{{$widget->fifth_content}}</p>
                                                            @else
                                                                <p class="des">Weekend Sales</p>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="policy policy4">
                                            <div class="policy_inner">
                                                <div class="service-ico">
                                                    <i class="fa "></i>
                                                </div>
                                                <div class="service-info">
                                                    <h2 class="title"></h2>
                                                    <p class="des"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Free Delivery Section End -->
            
            <!-- SHOP BY CATEGORIES Section Start -->
            <section class="gj_shop_cat_sec">
                <div id="shopify-section-1526543904826" class="shopify-section home-section clearfix">
                    <div class="widget-collection shopcatz">
                        <div class="container">
                            <div class="home-title">
                                <h2>Shop by categories</h2>
                                <a class="viewall pull-right" href="{{ route('all_products') }}"> View All </a>
                            </div>
                            <div class="collections">
                                <div class="ss-carousel ss-owl">
                                    <div class="owl-carousel"
                                        data-dots      ="false" 
                                        data-nav       ="false" 
                                        data-margin    ="30"
                                        data-column1   ="5" 
                                        data-column2   ="5" 
                                        data-column3   ="4" 
                                        data-column4   ="3" 
                                        data-column5   ="2">

                                        @if($main_cat)
                                            @if(count($main_cat) != 0)
                                                @foreach($main_cat as $key => $value)
                                                    <div class="item">
                                                        <div class="collect ">
                                                            <a href="{{ url('all_filter_products?fil_cats='.$value->id) }}" class="collection-item">
                                                                <img class="collection-img img-responsive lazyload" data-sizes="auto" src="" alt="Ecambiar Main Category{{$value->id}}" data-src="{{ asset($main_cat_path.'/'.$value->main_cat_image) }}"/>
                                                            </a>
                                                            <div class="collection-name">
                                                                <a href="{{ url('all_filter_products?fil_cats='.$value->id) }}" class="collection clearfix">{{$value->main_cat_name}}</a>
                                                           </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="item">
                                                    <div class="collect ">
                                                        <a href="#" class="collection-item">
                                                            <img class="collection-img img-responsive lazyload" data-sizes="auto" src="" alt="No Items" data-src="{{ asset($noimage_path.'/'.$noimage->category_no_image)}}"/>
                                                        </a>
                                                        <div class="collection-name">
                                                            <a href="#" class="collection clearfix">No Items</a>
                                                       </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="item">
                                                <div class="collect ">
                                                    <a href="#" class="collection-item">
                                                        <img class="collection-img img-responsive lazyload" data-sizes="auto" src="" alt="No Items" data-src="{{ asset($noimage_path.'/'.$noimage->category_no_image)}}"/>
                                                    </a>
                                                    <div class="collection-name">
                                                        <a href="#" class="collection clearfix">No Items</a>
                                                   </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- SHOP BY CATEGORIES Section End -->
 <section id="shopify-section-1525859554530" class="shopify-section home-section">
                  <div class="widget-deals-carousel owl-style1">
                     <div class="container">
                        <div class="wrap-product">
                           <div class="widget-head">
                              <div class="home-title">
                                 <h2>Recently Added </h2>
                              </div>
                           </div>
                           <div class="products-listing grid">
                              <div class="product-layout block-content">
                                 <div class="ss-carousel ss-owl">
                                    <div class="owl-carousel" 
                                       data-dots		="false" 
                                       data-nav		="true" 
                                       data-margin		="5"
                                       data-autoplay	="false" 
                                       data-autospeed	="10000" 
                                       data-speed		="300"
                                       data-column1	="6" 
                                       data-column2	="4" 
                                       data-column3	="3" 
                                       data-column4	="2" 
                                       data-column5	="2">
                                        @foreach($recently_added as $k => $value)
                                       <div class="item">
								<div class="product-item">
									<a href="{{ route('view_products', ['id' => $value->id]) }}" class="product-img"> 
									<img src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="">
									
									
													
										<div class="product-absolute-options">
										     <span class="like-icon gj_wish_list" title="wishlist" data-wish-id="{{$value->id}}">
										         
										     </span> </div>
									</a>
									 
									<div class="product-text-dt">
									
									<div class="kelelclienzhre">                                                      
                                                    <button class="gj_shareall_btn"> <i class="fa fa-share-alt sjareall"></i> </button>
                                                    
                                                     <div class="share-links gj_vend_share">
                                                 <ul class="gj_soc_sre">
                                                            <li><a class="op1" target="_blank" href="https://www.facebook.com/sharer.php?u={{Request::fullUrl()}}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op2" target="_blank" href="https://twitter.com/intent/tweet?url={{Request::fullUrl()}}&title=SmartHub">
                                                                <i class="fa fa-twitter" aria-hidden="true"></i></a></li>       
															<li><a class="op3" target="_blank" href="https://www.pinterest.com/pin/create/button/?url={{Request::fullUrl()}}"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op4" target="_blank" href="https://t.me/share/url?url={{Request::fullUrl()}}&title=SmartHub"><i class="fa fa-telegram" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op5" target="_blank" href="https://www.linkedin.com/shareArticle?mimi=true&url={{Request::fullUrl()}}&title=SmartHub"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>    
                                                        </ul>
                                                    </div>

                                                    </div>
													
													
										<h4><a href="{{ route('view_products', ['id' => $value->id]) }}">  {{$value->product_title}}</a></h4>
										<div class="product-price">{{$code}}  {{$value->discounted_price}}</div>
										
										<div class="qty-cart">
										<div class="quantity buttons_added">
										<input type="button" value="-" class="minus minus-btn">
										<input type="number" step="1" id="qty_{{$value->id}}" name="quantity" value="1" min="1" class="input-text qty text">
										<input type="button" value="+" class="plus plus-btn">
								<input type="hidden" id="price_{{$value->id}}" name="price" value="{{$value->discounted_price}}" class="quantity-selector">

										</div>
										<div class="add-cart">
											 <button class="addcartzhom gj_add2cartzsx" data-cart-id="{{$value->id}}"> Add to Cart </button>
										 </div>
										</div>
									</div>
								</div>
							</div>
                                       
                                       
                                       @endforeach
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </section>
            <!-- <div id="shopify-section-1525859554530" class="shopify-section home-section">
                <div class="widget-deals-carousel owl-style1">
                    <div class="container">
                        <div class="wrap-product">
                            <div class="widget-head">
                                <div class="home-title">
                                    <h2>DAILY DEALS</h2>
                                </div>
                            </div>
                            <div class="products-listing grid">
                                <div class="product-layout block-content">
                                    <div class="ss-carousel ss-owl">
                                        <div class="owl-carousel" 
                                            data-dots        ="false" 
                                            data-nav     ="true" 
                                            data-margin      ="5"
                                            data-autoplay    ="false" 
                                            data-autospeed   ="10000" 
                                            data-speed       ="300"
                                            data-column1 ="6" 
                                            data-column2 ="4" 
                                            data-column3 ="3" 
                                            data-column4 ="2" 
                                            data-column5 ="2">
                                            <div class="item">
                                                <div class="product-item" data-id="product-1674857185391">
                                                    <div class="product-item-container  ">
                                                        <div class="row">
                                                            <div class="left-block col-12">
                                                                <div class="product-image-container product-image">
                                                                    <a class="grid-view-item__link image-ajax" href="#">
                                                                        <img class="img-responsive lazyload" data-sizes="auto" src="images/icon-loadings.svg" data-src="images/e2.jpg" alt=" ">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="right-block">
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
                                                                        <a class="btn_df btnProduct" href="#" title="Wishlist">
                                                                            <i class="fa fa-heart gj_wish_hrt"></i>
                                                                            <span class="hidden-xs hidden-sm hidden-md">Wishlist</span>
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                                <div class="caption">
                                                                    <div class="custom-reviews hidden-xs">          
                                                                        <span class="shopify-product-reviews-badge" data-id="{{$value->id}}"></span>          
                                                                    </div>
                                                                    <h4 class="title-product text-truncate"><a class="product-name" href="{{ route('view_products', ['id' => $value->id]) }}">{{$value->product_title}}</a></h4>
                                                                    <div class="price">
                                                                        <span class="visually-hidden">Regular price</span>
                                                                        <span class="price-new"><span class="money"> {{$code}}  {{$value->discounted_price}}</span></span> 
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="product-item" data-id="product-1674856071279">
                                                    <div class="product-item-container  ">
                                                        <div class="row">
                                                            <div class="left-block col-12">
                                                                <div class="product-image-container product-image">
                                                                    <a class="grid-view-item__link image-ajax" href="#">
                                                                        <img class="img-responsive lazyload" data-sizes="auto" src="images/icon-loadings.svg" data-src="images/e3.jpg" alt="Boudin ando bualo">
                                                                    </a>
                                                                    <span class="label-product label-sale"><span class="hidden">Sale</span> -5%</span>
                                                                </div>
                                                            </div>
                                                            <div class="right-block col-12">
                                                                <div class="button-link">
                                                                    <div class="btn-button add-to-cart add-sellect">  
                                                                        <a class="btn_df" href="#" class="grl" title="Select options">Select options</a>
                                                                    </div>
                                                                    <div class="product-addto-links">
                                                                        <a class="btn_df btnProduct" href="#" title="Wishlist">
                                                                            <i class="fa fa-heart"></i>
                                                                            <span class="hidden-xs hidden-sm hidden-md">Wishlist</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="caption">
                                                                    <div class="custom-reviews hidden-xs">          
                                                                        <span class="shopify-product-reviews-badge" data-id="1674856071279"></span>
                                                                    </div>
                                                                    <h4 class="title-product text-truncate"><a class="product-name" href="#"> LED Tv</a></h4>
                                                                    <div class="price">
                                                                        <span class="visually-hidden">Regular price</span>
                                                                        <span class="price-new"><span class="money"> {{$code}}  37.00</span></span>
                                                                    </div>
                                                                </div>
                                                                <div class="countdown_tabs">
                                                                    <div class="countdown_inner" data-date=" 2019/12/12"></div>
                                                                </div>
                                                                <div class="countdown_tabs">
                                                                    <div class="countdown_inner" data-date="s"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="product-item" data-id="product-1674855743599">
                                                    <div class="product-item-container  ">
                                                        <div class="row">
                                                            <div class="left-block col-12">
                                                                <div class="product-image-container product-image">
                                                                    <a class="grid-view-item__link image-ajax" href="#">
                                                                        <img class="img-responsive lazyload" data-sizes="auto" src="images/icon-loadings.svg" data-src="images/e4.jpg" alt="Capicola leber ham">
                                                                    </a>
                                                                    <span class="label-product label-sale"><span class="hidden">Sale</span> -1%</span>
                                                                </div>
                                                            </div>
                                                            <div class="right-block col-12">
                                                                <div class="button-link">
                                                                    <div class="btn-button add-to-cart action  ">
                                                                        <form action=" " method="post" class="variants" data-id="AddToCartForm-1674855743599" enctype="multipart/form-data">   
                                                                            <input type="hidden" name="id" value="15484178399343" />
                                                                            <a class="btn-addToCart grl btn_df" href="javascript:void(0)" title="Add to cart">Add to cart</a>
                                                                        </form>
                                                                    </div>
                                                                    <div class="product-addto-links">
                                                                        <a class="btn_df btnProduct" href="#" title="Wishlist">
                                                                            <i class="fa fa-heart"></i>
                                                                            <span class="hidden-xs hidden-sm hidden-md">Wishlist</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="caption">
                                                                    <div class="custom-reviews hidden-xs">          
                                                                    <span class="shopify-product-reviews-badge" data-id="1674855743599"></span>          
                                                                </div>
                                                                <h4 class="title-product text-truncate"><a class="product-name" href="#">Laptop</a></h4>
                                                                <div class="price">
                                                                    <span class="visually-hidden">Regular price</span>
                                                                    <span class="price-new"><span class="money"> {{$code}}  731.00</span></span>  
                                                                </div>
                                                            </div>
                                                            <div class="countdown_tabs">
                                                                <div class="countdown_inner" data-date=" 2019/12/12"></div>
                                                            </div>
                                                            <div class="countdown_tabs">
                                                                <div class="countdown_inner" data-date="s"></div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="product-item" data-id="product-1674857185391">
                                                    <div class="product-item-container  ">
                                                        <div class="row">
                                                            <div class="left-block col-12">
                                                                <div class="product-image-container product-image">
                                                                    <a class="grid-view-item__link image-ajax" href="#">
                                                                        <img class="img-responsive lazyload" data-sizes="auto" src="images/icon-loadings.svg" data-src="images/e5.jpg" alt=" ">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="right-block col-12">
                                                                <div class="button-link">
                                                                    <div class="btn-button add-to-cart action  ">
                                                                        <form action=" " method="post" class="variants" data-id="AddToCartForm-1674857185391" enctype="multipart/form-data">   
                                                                            <input type="hidden" name="id" value="15484183347311" />
                                                                            <a class="btn-addToCart grl btn_df" href="javascript:void(0)" title="Add to cart">Add to cart</a>
                                                                        </form>
                                                                    </div>
                                                                    <div class="product-addto-links">
                                                                        <a class="btn_df btnProduct" href="#" title="Wishlist">
                                                                            <i class="fa fa-heart"></i>
                                                                            <span class="hidden-xs hidden-sm hidden-md">Wishlist</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="caption">
                                                                    <div class="custom-reviews hidden-xs">          
                                                                        <span class="shopify-product-reviews-badge" data-id="1674857185391"></span>
                                                                    </div>
                                                                    <h4 class="title-product text-truncate"><a class="product-name" href="#"> Tab</a></h4>
                                                                    <div class="price">
                                                                        <span class="visually-hidden">Regular price</span>
                                                                        <span class="price-new"><span class="money"> {{$code}}  231.00</span></span> 
                                                                    </div>
                                                                </div>
                                                                <div class="countdown_tabs">
                                                                    <div class="countdown_inner" data-date=" 2019/12/12"></div>
                                                                </div>
                                                                <div class="countdown_tabs">
                                                                    <div class="countdown_inner" data-date="s"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item">
                                                <div class="product-item" data-id="product-1674856071279">
                                                    <div class="product-item-container  ">
                                                        <div class="row">
                                                            <div class="left-block col-12">
                                                                <div class="product-image-container product-image">
                                                                    <a class="grid-view-item__link image-ajax" href="#">
                                                                        <img class="img-responsive lazyload" data-sizes="auto" src="images/icon-loadings.svg" data-src="images/e6.jpg" alt="Boudin ando bualo">
                                                                    </a>
                                                                    <span class="label-product label-sale"><span class="hidden">Sale</span> -5%</span>
                                                                </div>
                                                            </div>
                                                            <div class="right-block col-12">
                                                                <div class="button-link">
                                                                    <div class="btn-button add-to-cart add-sellect">  
                                                                        <a class="btn_df" href="#" class="grl" title="Select options">Select options</a>
                                                                    </div>
                                                                    <div class="product-addto-links">
                                                                        <a class="btn_df btnProduct" href="#" title="Wishlist">
                                                                            <i class="fa fa-heart"></i>
                                                                            <span class="hidden-xs hidden-sm hidden-md">Wishlist</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="caption">
                                                                    <div class="custom-reviews hidden-xs">          
                                                                        <span class="shopify-product-reviews-badge" data-id="1674856071279"></span>
                                                                    </div>
                                                                    <h4 class="title-product text-truncate"><a class="product-name" href="#"> Smart Phone</a></h4>
                                                                    <div class="price">
                                                                        <span class="visually-hidden">Regular price</span>
                                                                        <span class="price-new"><span class="money"> {{$code}}  37.00</span></span>
                                                                    </div>
                                                                </div>
                                                                <div class="countdown_tabs">
                                                                    <div class="countdown_inner" data-date=" 2019/12/12"></div>
                                                                </div>
                                                                <div class="countdown_tabs">
                                                                    <div class="countdown_inner" data-date="s"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                           <div class="item">
                                              <div class="product-item" data-id="product-1674855743599">
                                                 <div class="product-item-container  ">
                                                    <div class="row">
                                                       <div class="left-block col-12">
                                                          <div class="product-image-container product-image">
                                                             <a class="grid-view-item__link image-ajax" href="#">
                                                             <img class="img-responsive lazyload" data-sizes="auto" src="images/icon-loadings.svg" data-src="images/e7.jpg" alt="Capicola leber ham">
                                                             </a>
                                                             <span class="label-product label-sale"><span class="hidden">Sale</span>
                                                             -1%</span>
                                                          </div>
                                                       </div>
                                                       <div class="right-block col-12">
                                                          <div class="button-link">
                                                             <div class="btn-button add-to-cart action  ">
                                                                <form action=" " method="post" class="variants" data-id="AddToCartForm-1674855743599" enctype="multipart/form-data">   
                                                                   <input type="hidden" name="id" value="15484178399343" />           
                                                                   <a class="btn-addToCart grl btn_df" href="javascript:void(0)" title="Add to cart">Add to cart</a>
                                                                </form>
                                                             </div>
                                                             <div class="product-addto-links">
                                                                <a class="btn_df btnProduct" href="#" title="Wishlist">
                                                                <i class="fa fa-heart"></i>
                                                                <span class="hidden-xs hidden-sm hidden-md">Wishlist</span>
                                                                </a>
                                                             </div>
                                                          </div>
                                                          <div class="caption">
                                                             <div class="custom-reviews hidden-xs">          
                                                                <span class="shopify-product-reviews-badge" data-id="1674855743599"></span>          
                                                             </div>
                                                             <h4 class="title-product text-truncate"><a class="product-name" href="#">Camera</a></h4>
                                                             <div class="price">
                                                                <span class="visually-hidden">Regular price</span>
                                                                <span class="price-new"><span class="money"> {{$code}}  731.00</span></span>   
                                                             </div>
                                                          </div>
                                                          <div class="countdown_tabs">
                                                             <div class="countdown_inner" data-date=" 2019/12/12"></div>
                                                          </div>
                                                          <div class="countdown_tabs">
                                                             <div class="countdown_inner" data-date="s"></div>
                                                          </div>
                                                       </div>
                                                    </div>
                                                 </div>
                                              </div>
                                           </div>
                                           <div class="item">
                                              <div class="product-item" data-id="product-1674857185391">
                                                 <div class="product-item-container  ">
                                                    <div class="row">
                                                       <div class="left-block col-12">
                                                          <div class="product-image-container product-image">
                                                             <a class="grid-view-item__link image-ajax" href="#">
                                                             <img class="img-responsive lazyload" data-sizes="auto" src="images/icon-loadings.svg" data-src="images/e2.jpg" alt=" ">
                                                             </a>
                                                          </div>
                                                       </div>
                                                       <div class="right-block col-12">
                                                          <div class="button-link">
                                                             <div class="btn-button add-to-cart action  ">
                                                                <form action=" " method="post" class="variants" data-id="AddToCartForm-1674857185391" enctype="multipart/form-data">   
                                                                   <input type="hidden" name="id" value="15484183347311" />           
                                                                   <a class="btn-addToCart grl btn_df" href="javascript:void(0)" title="Add to cart">Add to cart</a>
                                                                </form>
                                                             </div>
                                                             <div class="product-addto-links">
                                                                <a class="btn_df btnProduct" href="#" title="Wishlist">
                                                                <i class="fa fa-heart"></i>
                                                                <span class="hidden-xs hidden-sm hidden-md">Wishlist</span>
                                                                </a>
                                                             </div>
                                                          </div>
                                                          <div class="caption">
                                                             <div class="custom-reviews hidden-xs">          
                                                                <span class="shopify-product-reviews-badge" data-id="1674857185391"></span>          
                                                             </div>
                                                             <h4 class="title-product text-truncate"><a class="product-name" href="#"> </a></h4>
                                                             <div class="price">
                                                                <span class="visually-hidden">Regular price</span>
                                                                <span class="price-new"><span class="money"> {{$code}}  231.00</span></span> 
                                                             </div>
                                                          </div>
                                                          <div class="countdown_tabs">
                                                             <div class="countdown_inner" data-date=" 2019/12/12"></div>
                                                          </div>
                                                          <div class="countdown_tabs">
                                                             <div class="countdown_inner" data-date="s"></div>
                                                          </div>
                                                       </div>
                                                    </div>
                                                 </div>
                                              </div>
                                           </div>
                                           <div class="item">
                                              <div class="product-item" data-id="product-1674856071279">
                                                 <div class="product-item-container  ">
                                                    <div class="row">
                                                       <div class="left-block col-12">
                                                          <div class="product-image-container product-image">
                                                             <a class="grid-view-item__link image-ajax" href="#">
                                                             <img class="img-responsive lazyload" data-sizes="auto" src="images/icon-loadings.svg" data-src="images/e3.jpg" alt="Boudin ando bualo">
                                                             </a>
                                                             <span class="label-product label-sale"><span class="hidden">Sale</span>
                                                             -5%</span>
                                                          </div>
                                                       </div>
                                                       <div class="right-block col-12">
                                                          <div class="button-link">
                                                             <div class="btn-button add-to-cart add-sellect">  
                                                                <a class="btn_df" href="#" class="grl" title="Select options">Select options</a>
                                                             </div>
                                                             <div class="product-addto-links">
                                                                <a class="btn_df btnProduct" href="#" title="Wishlist">
                                                                <i class="fa fa-heart"></i>
                                                                <span class="hidden-xs hidden-sm hidden-md">Wishlist</span>
                                                                </a>
                                                             </div>
                                                          </div>
                                                          <div class="caption">
                                                             <div class="custom-reviews hidden-xs">          
                                                                <span class="shopify-product-reviews-badge" data-id="1674856071279"></span>          
                                                             </div>
                                                             <h4 class="title-product text-truncate"><a class="product-name" href="#">Boudin ando bualo</a></h4>
                                                             <div class="price">
                                                                <span class="visually-hidden">Regular price</span>
                                                                <span class="price-new"><span class="money"> {{$code}}  37.00</span></span>
                                                             </div>
                                                          </div>
                                                          <div class="countdown_tabs">
                                                             <div class="countdown_inner" data-date=" 2019/12/12"></div>
                                                          </div>
                                                          <div class="countdown_tabs">
                                                             <div class="countdown_inner" data-date="s"></div>
                                                          </div>
                                                       </div>
                                                    </div>
                                                 </div>
                                              </div>
                                           </div>
                                           <div class="item">
                                              <div class="product-item" data-id="product-1674855743599">
                                                 <div class="product-item-container  ">
                                                    <div class="row">
                                                       <div class="left-block col-12">
                                                          <div class="product-image-container product-image">
                                                             <a class="grid-view-item__link image-ajax" href="#">
                                                             <img class="img-responsive lazyload" data-sizes="auto" src="images/icon-loadings.svg" data-src="images/e4.jpg" alt="Capicola leber ham">
                                                             </a>
                                                             <span class="label-product label-sale"><span class="hidden">Sale</span>
                                                             -1%</span>
                                                          </div>
                                                       </div>
                                                       <div class="right-block col-12">
                                                          <div class="button-link">
                                                             <div class="btn-button add-to-cart action  ">
                                                                <form action=" " method="post" class="variants" data-id="AddToCartForm-1674855743599" enctype="multipart/form-data">   
                                                                   <input type="hidden" name="id" value="15484178399343" />           
                                                                   <a class="btn-addToCart grl btn_df" href="javascript:void(0)" title="Add to cart">Add to cart</a>
                                                                </form>
                                                             </div>
                                                             <div class="product-addto-links">
                                                                <a class="btn_df btnProduct" href="#" title="Wishlist">
                                                                <i class="fa fa-heart"></i>
                                                                <span class="hidden-xs hidden-sm hidden-md">Wishlist</span>
                                                                </a>
                                                             </div>
                                                          </div>
                                                          <div class="caption">
                                                             <div class="custom-reviews hidden-xs">          
                                                                <span class="shopify-product-reviews-badge" data-id="1674855743599"></span>          
                                                             </div>
                                                             <h4 class="title-product text-truncate"><a class="product-name" href="#">Capicola leber ham</a></h4>
                                                             <div class="price">
                                                                <span class="visually-hidden">Regular price</span>
                                                             </div>
                                                          </div>
                                                          <div class="countdown_tabs">
                                                             <div class="countdown_inner" data-date=" 2019/12/12"></div>
                                                          </div>
                                                          <div class="countdown_tabs">
                                                             <div class="countdown_inner" data-date="s"></div>
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
                        </div>
                    </div>
                </div>
            </div> -->
            @php
            
          $ads_cat2= \DB::table('category_advertisement_settings')->Where('is_block', 1)
          ->Where('page', 'Home Page')->Where('position', 'Below Recent Products')
          ->Where('ad_start_date', '<=',date('Y-m-d'))->Where('ad_end_date', '>=',date('Y-m-d'))->get();
  
            
            @endphp
  <section class="advetis">
                  <div class="container">
                     <div class="row">
                         @foreach($ads_cat2 as $value)
                        <div class="col-md-4">
                           <img src="{{asset('images/category_advertisement/'.$value->ad_image)}}"  style="height:136px">
                        </div>
                       @endforeach
                       
                     </div>
                  </div>
               </section>
            <!-- View All Section Start -->
            <!--<section class="gj_view_all_sec">
                <div id="shopify-section-1527303536972" class="shopify-section home-section">
                    <div class="widget_multibanner radius_3 clearfix">
                        <div class="container">
                            <div class="widget-content">
                                <div class="row">
                                    <div class="item_banner item1 col-12">
                                        <div class="banners">
                                            <div class="">
                                                @if($widget)
                                                    @if($widget->provide_img && $widget->provide_url)
                                                        <a href="{{ $widget->provide_url }}" title="Ecambiar">
                                                            <img class="img-responsive" alt=" Ecambiar " src="{{ asset('images/widget/'.$widget->provide_img)}}" />
                                                        </a>
                                                    @else
                                                        <a href="{{ route('all_products') }}" title="Ecambiar">
                                                            <img class="img-responsive" alt=" Ecambiar " src="{{ asset('frontend/images/02.png')}}" />
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('all_products') }}" title="Ecambiar">
                                                        <img class="img-responsive" alt=" Ecambiar " src="{{ asset('frontend/images/02.png')}}" />
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>-->
            <!-- View All Section End -->

            <!-- First CATEGORIES Section Start -->
            <section class="gj_first_cat_sec">
                <div id="shopify-section-1526877348109" class="shopify-section home-section">
                    <div class="widget-product-carousel clearfix owl-style1">
                        <div class="container">
                            <div class="border-tabs">
                                <div class="widget-head">
                                    @if($first_cat)
                                        <div class="home-title"><span>{{$first_cat->main_cat_name}}</span></div>
                                    @else
                                        <div class="home-title"><span>No items</span></div>
                                    @endif
                                </div>
                                <div class="tab-product">
                                    <div class="widget-product__item">
                                        <div class="products-listing grid">
                                            <div class="product-layout block-content">
                                                <div class="ss-carousel ss-owl">
                                                    <div class="owl-carousel" 
                                                        data-nav       ="true"
                                                        data-margin    ="30"
                                                        data-autoplay  ="true" 
                                                        data-autospeed ="10000" 
                                                        data-speed     ="300"
                                                        data-column1   ="5" 
                                                        data-column2   ="4" 
                                                        data-column3   ="3" 
                                                        data-column4   ="3" 
                                                        data-column5   ="2">

                                                        @if(($first_products) && (count($first_products) != 0))
                                                            @foreach ($first_products as $key => $value)
                                                               <div class="item">
								<div class="product-item">
									<a href="{{ route('view_products', ['id' => $value->id]) }}" class="product-img"> 
									<img src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="">
									
									
													
										<div class="product-absolute-options">
										     <span class="like-icon gj_wish_list" title="wishlist" data-wish-id="{{$value->id}}">
										         
										     </span> </div>
									</a>
									 
									<div class="product-text-dt">
									
									<div class="kelelclienzhre">                                                      
                                                    <button class="gj_shareall_btn"> <i class="fa fa-share-alt sjareall"></i> </button>
                                                    
                                                     <div class="share-links gj_vend_share">
                                                 <ul class="gj_soc_sre">
                                                            <li><a class="op1" target="_blank" href="https://www.facebook.com/sharer.php?u={{Request::fullUrl()}}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op2" target="_blank" href="https://twitter.com/intent/tweet?url={{Request::fullUrl()}}&title=SmartHub">
                                                                <i class="fa fa-twitter" aria-hidden="true"></i></a></li>       
															<li><a class="op3" target="_blank" href="https://www.pinterest.com/pin/create/button/?url={{Request::fullUrl()}}"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op4" target="_blank" href="https://t.me/share/url?url={{Request::fullUrl()}}&title=SmartHub"><i class="fa fa-telegram" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op5" target="_blank" href="https://www.linkedin.com/shareArticle?mimi=true&url={{Request::fullUrl()}}&title=SmartHub"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>    
                                                        </ul>
                                                    </div>

                                                    </div>
													
													
										<h4><a href="{{ route('view_products', ['id' => $value->id]) }}">{{$value->product_title}}</a></h4>
										<div class="product-price">{{$code}}  {{$value->discounted_price}}</div>
										
										<div class="qty-cart">
										<div class="quantity buttons_added">
										<input type="button" value="-" class="minus minus-btn">
										<input type="number" step="1" id="qty_{{$value->id}}" name="quantity" value="1" min="1" class="input-text qty text">
										<input type="button" value="+" class="plus plus-btn">
								<input type="hidden" id="price_{{$value->id}}" name="price" value="{{$value->discounted_price}}" class="quantity-selector">

										</div>
										<div class="add-cart">
											 <button class="addcartzhom gj_add2cartzsx" data-cart-id="{{$value->id}}"> Add to Cart </button>
										 </div>
										</div>
									</div>
								</div>
							</div>
                                                            @endforeach
                                                        @else
                                                            <div class="item">
                                                                <div class="product-item" data-id="product_else1">
                                                                    <div class="product-item-container grid-view-item   ">
                                                                        <div class="left-block">
                                                                            <div class="product-image-container product-image">
                                                                                <a class="grid-view-item__link image-ajax" href="#">
                                                                                    <img class="img-responsive s-img lazyload" data-sizes="auto" src="{{ asset('frontend/images/icon-loadings.svg') }}" data-src="{{ asset($noimage_path.'/'.$noimage->product_no_image)}}" alt="No Products" />
                                                                                </a>
                                                                                <div class="box-countdown">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="right-block">
                                                                            <div class="caption">
                                                                                <div class="custom-reviews hidden-xs">          
                                                                                    <span class="shopify-product-reviews-badge" data-id="{{$value->id}}"></span>          
                                                                                </div>
                                                                                <h4 class="title-product text-truncate"><a class="product-name" href="#">No Products</a></h4>
                                                                                <div class="price">
                                                                                    <span class="visually-hidden">Regular price</span>
                                                                                    <span class="price-new"><span class="money"> {{$code}}  0.00</span></span> 
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
            </section>
           
             <section id="shopify-section-1526368282151" class="shopify-section home-section">
                  <div class="widget-logolist">
                     <div class="container">
					 
					  <div class="widget-head anlogheadz">
                              <div class="home-title"><span> Brands </span></div>
                           </div>
                        <div class="wrap">
                           <div class="ss-carousel ss-owl logo-bars">
                              <div class="product-layout owl-carousel" 
                                 data-nav			="true" 
                                 data-margin		="30"
                                 data-autoplay	="false" 
                                 data-autospeed	="10000" 
                                 data-speed		="300"
                                 data-column1		="6" 
                                 data-column2		="5" 
                                 data-column3		="4" 
                                 data-column4		="3" 
                                 data-column5		="2">
                                 @if(sizeof($brand) != 0)
                                    @foreach($brand as $bkey => $bval)
                                        <div class="logo-item" >
                                            @if($bval->brand_image)
                                                <a href="{{ route('brands_products', ['id' => $bval->id]) }}" class="logo-bar__link">
                                                    <img class="img-responsive" alt=" Ecambiar" title="{{$bval->brand_name}}" src="{{ asset($brand_path.'/'.$bval->brand_image)}}" />
                                                </a>
                                            @else
                                                <img class="img-responsive" alt="Ecambiar" src="{{ asset('images/site_img/brand_no_image.jpg')}}" />
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                 <div class="logo-item" >
                                    <a href="#" class="logo-bar__link">
                                    <img class="img-responsive" alt=" Ecambiar " src="{{ asset('frontend/images/b1.png')}}" />
                                    </a>
                                 </div>
                                 <div class="logo-item" >
                                    <a href="#" class="logo-bar__link">
                                    <img class="img-responsive" alt=" Ecambiar " src="{{ asset('frontend/images/b6.png')}}" />
                                    </a>
                                 </div>
                                 <div class="logo-item" >
                                    <a href="#" class="logo-bar__link">
                                    <img class="img-responsive" alt=" Ecambiar " src="{{ asset('frontend/images/b5.png')}}" />
                                    </a>
                                 </div>
                                 <div class="logo-item" >
                                    <a href="#" class="logo-bar__link">
                                    <img class="img-responsive" alt=" Ecambiar " src="{{ asset('frontend/images/b4.png')}}" />
                                    </a>
                                 </div>
                                 <div class="logo-item" >
                                    <a href="#" class="logo-bar__link">
                                    <img class="img-responsive" alt=" Ecambiar " src="{{ asset('frontend/images/b3.png')}}" />
                                    </a>
                                 </div>
                                 <div class="logo-item" >
                                    <a href="#" class="logo-bar__link">
                                    <img class="img-responsive" alt=" Ecambiar " src="{{ asset('frontend/images/b2.png')}}" />
                                    </a>
                                 </div>
                                 <div class="logo-item" >
                                    <a href="#" class="logo-bar__link">
                                    <img class="img-responsive" alt=" Ecambiar " src="{{ asset('frontend/images/b1.png')}}" />
                                    </a>
                                 </div>
                                @endif
                                 
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
              <!-- FEATURED PRODUCTS Section Start -->
           
              <section class="gj_second_cat_sec">
                <div id="shopify-section-1526897752236" class="shopify-section  home-section">
                    <div class="widget-product-carousel clearfix owl-style1">
                        <div class="container">
                            <div class="border-tabs">
                                <div class="widget-head">
                                  
                                   <div class="home-title"><span> Featured Products</span></div>
                                </div>

                                <div class="tab-product">
                                    <div class="widget-product__item index_cat2_as">
                                        <div class="products-listing grid">
                                            <div class="product-layout block-content">
                                                <div class="ss-carousel ss-owl">
                                                    <div class="owl-carousel" 
                                                        data-nav       ="true"
                                                        data-margin    ="30"
                                                        data-autoplay  ="true" 
                                                        data-autospeed ="10000" 
                                                        data-speed     ="300"
                                                        data-column1   ="5" 
                                                        data-column2   ="4" 
                                                        data-column3   ="3" 
                                                        data-column4   ="3" 
                                                        data-column5   ="2">

                                                        @if(($featured_products) && (count($featured_products) != 0))
                                                            @foreach ($featured_products as $key => $value)
                                                                <div class="item">
								<div class="product-item">
									<a href="{{ route('view_products', ['id' => $value->id]) }}" class="product-img"> 
									<img src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="">
									
									
													
										<div class="product-absolute-options">
										     <span class="like-icon gj_wish_list" title="wishlist" data-wish-id="{{$value->id}}">
										         
										     </span> </div>
									</a>
									 
									<div class="product-text-dt">
									
									<div class="kelelclienzhre">                                                      
                                                    <button class="gj_shareall_btn"> <i class="fa fa-share-alt sjareall"></i> </button>
                                                    
                                                     <div class="share-links gj_vend_share">
                                                 <ul class="gj_soc_sre">
                                                            <li><a class="op1" target="_blank" href="https://www.facebook.com/sharer.php?u={{Request::fullUrl()}}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op2" target="_blank" href="https://twitter.com/intent/tweet?url={{Request::fullUrl()}}&title=SmartHub">
                                                                <i class="fa fa-twitter" aria-hidden="true"></i></a></li>       
															<li><a class="op3" target="_blank" href="https://www.pinterest.com/pin/create/button/?url={{Request::fullUrl()}}"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op4" target="_blank" href="https://t.me/share/url?url={{Request::fullUrl()}}&title=SmartHub"><i class="fa fa-telegram" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op5" target="_blank" href="https://www.linkedin.com/shareArticle?mimi=true&url={{Request::fullUrl()}}&title=SmartHub"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>    
                                                        </ul>
                                                    </div>

                                                    </div>
													
													
										<h4><a href="{{ route('view_products', ['id' => $value->id]) }}">{{$value->product_title}}</a></h4>
										<div class="product-price">{{$code}}  {{$value->discounted_price}}</div>
										
										<div class="qty-cart">
										<div class="quantity buttons_added">
										<input type="button" value="-" class="minus minus-btn">
										<input type="number" step="1" id="qty_{{$value->id}}" name="quantity" value="1" min="1" class="input-text qty text">
										<input type="button" value="+" class="plus plus-btn">
								<input type="hidden" id="price_{{$value->id}}" name="price" value="{{$value->discounted_price}}" class="quantity-selector">

										</div>
										<div class="add-cart">
											 <button class="addcartzhom gj_add2cartzsx" data-cart-id="{{$value->id}}"> Add to Cart </button>
										 </div>
										</div>
									</div>
								</div>
							</div>
                                                            @endforeach
                                                        @else
                                                            <div class="item">
                                                                <div class="product-item" data-id="product-{{$value->id}}">
                                                                    <div class="product-item-container grid-view-item   ">
                                                                        <div class="left-block">
                                                                            <div class="product-image-container product-image">
                                                                                <a class="grid-view-item__link image-ajax" href="#">
                                                                                    <img class="img-responsive s-img lazyload" data-sizes="auto" src="{{ asset('frontend/images/icon-loadings.svg') }}" data-src="{{ asset($noimage_path.'/'.$noimage->product_no_image)}}" alt="No Products" />
                                                                                </a>
                                                                                <div class="box-countdown">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($index_cat2_as)
                                        @if($index_cat2_as->ad_image)
                                            @if(($nw_date >= $st_date2) && ($nw_date < $en_date2))
                                                <div class="widget_bestseler left-product-carousel owl-style_dot">
                                                    <a href="{{$index_cat2_as->ad_website}}" class="gj_cat2_as"><img src="{{asset('images/category_advertisement/'.$index_cat2_as->ad_image)}}" alt="{{$index_cat2_as->ad_title}}"></a>
                                                </div>
                                            @else
                                                <style type="text/css">
                                                    .widget-product-carousel .tab-product .widget-product__item.index_cat2_as {
                                                        padding: 0px;
                                                        width: 100%;
                                                        float: left;
                                                        border-right: 0px solid #e9ecf1;
                                                    }
                                                </style>
                                            @endif
                                        @else
                                            <style type="text/css">
                                                .widget-product-carousel .tab-product .widget-product__item.index_cat2_as {
                                                    padding: 0px;
                                                    width: 100%;
                                                    float: left;
                                                    border-right: 0px solid #e9ecf1;
                                                }
                                            </style>
                                        @endif
                                    @else
                                        <style type="text/css">
                                            .widget-product-carousel .tab-product .widget-product__item.index_cat2_as {
                                                padding: 0px;
                                                width: 100%;
                                                float: left;
                                                border-right: 0px solid #e9ecf1;
                                            }
                                        </style>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- FEATURED PRODUCTS Section End -->
             @php
            
          $ads_cat2= \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)
          ->Where('page', 'Home Page')->Where('position', 'Below Featured Products')
          ->Where('ad_start_date', '<=',date('Y-m-d'))->Where('ad_end_date', '>=',date('Y-m-d'))->get();
  
            
            @endphp
  <section class="advetis">
                  <div class="container">
                     <div class="row">
                         @foreach($ads_cat2 as $value)
                        <div class="col-md-4">
                           <img src="{{asset('images/category_advertisement/'.$value->ad_image)}}"  style="height:136px">
                        </div>
                       @endforeach
                       
                     </div>
                  </div>
               </section>
              
            <!-- First CATEGORIES Section End -->
                             <section class="gj_second_cat_sec">
                <div id="shopify-section-1526897752236" class="shopify-section  home-section">
                    <div class="widget-product-carousel clearfix owl-style1">
                        <div class="container">
                            <div class="border-tabs">
                                <div class="widget-head">
                                  
                                   <div class="home-title"><span> Top Selling Products</span></div>
                                </div>

                                <div class="tab-product">
                                    <div class="widget-product__item index_cat2_as">
                                        <div class="products-listing grid">
                                            <div class="product-layout block-content">
                                                <div class="ss-carousel ss-owl">
                                                    <div class="owl-carousel" 
                                                        data-nav       ="true"
                                                        data-margin    ="30"
                                                        data-autoplay  ="true" 
                                                        data-autospeed ="10000" 
                                                        data-speed     ="300"
                                                        data-column1   ="5" 
                                                        data-column2   ="4" 
                                                        data-column3   ="3" 
                                                        data-column4   ="3" 
                                                        data-column5   ="2">

                                                        @if(($best_seller) && (count($best_seller) != 0))
                                                            @foreach ($best_seller as $key => $value)
                                                                <div class="item">
								<div class="product-item">
									<a href="{{ route('view_products', ['id' => $value->id]) }}" class="product-img"> 
									<img src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="">
									
									
													
										<div class="product-absolute-options">
										     <span class="like-icon gj_wish_list" title="wishlist" data-wish-id="{{$value->id}}">
										         
										     </span> </div>
									</a>
									 
									<div class="product-text-dt">
									
									<div class="kelelclienzhre">                                                      
                                                    <button class="gj_shareall_btn"> <i class="fa fa-share-alt sjareall"></i> </button>
                                                    
                                                     <div class="share-links gj_vend_share">
                                                 <ul class="gj_soc_sre">
                                                            <li><a class="op1" target="_blank" href="https://www.facebook.com/sharer.php?u={{Request::fullUrl()}}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op2" target="_blank" href="https://twitter.com/intent/tweet?url={{Request::fullUrl()}}&title=SmartHub">
                                                                <i class="fa fa-twitter" aria-hidden="true"></i></a></li>       
															<li><a class="op3" target="_blank" href="https://www.pinterest.com/pin/create/button/?url={{Request::fullUrl()}}"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op4" target="_blank" href="https://t.me/share/url?url={{Request::fullUrl()}}&title=SmartHub"><i class="fa fa-telegram" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op5" target="_blank" href="https://www.linkedin.com/shareArticle?mimi=true&url={{Request::fullUrl()}}&title=SmartHub"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>    
                                                        </ul>
                                                    </div>

                                                    </div>
													
													
										<h4><a href="{{ route('view_products', ['id' => $value->id]) }}">{{$value->product_title}}</a></h4>
										<div class="product-price">{{$code}}  {{$value->discounted_price}}</div>
										
										<div class="qty-cart">
										<div class="quantity buttons_added">
										<input type="button" value="-" class="minus minus-btn">
										<input type="number" step="1" id="qty_{{$value->id}}" name="quantity" value="1" min="1" class="input-text qty text">
										<input type="button" value="+" class="plus plus-btn">
								<input type="hidden" id="price_{{$value->id}}" name="price" value="{{$value->discounted_price}}" class="quantity-selector">

										</div>
										<div class="add-cart">
											 <button class="addcartzhom gj_add2cartzsx" data-cart-id="{{$value->id}}"> Add to Cart </button>
										 </div>
										</div>
									</div>
								</div>
							</div>
                                                            @endforeach
                                                        @else
                                                            <div class="item">
                                                                <div class="product-item" data-id="product-{{$value->id}}">
                                                                    <div class="product-item-container grid-view-item   ">
                                                                        <div class="left-block">
                                                                            <div class="product-image-container product-image">
                                                                                <a class="grid-view-item__link image-ajax" href="#">
                                                                                    <img class="img-responsive s-img lazyload" data-sizes="auto" src="{{ asset('frontend/images/icon-loadings.svg') }}" data-src="{{ asset($noimage_path.'/'.$noimage->product_no_image)}}" alt="No Products" />
                                                                                </a>
                                                                                <div class="box-countdown">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($index_cat2_as)
                                        @if($index_cat2_as->ad_image)
                                            @if(($nw_date >= $st_date2) && ($nw_date < $en_date2))
                                                <div class="widget_bestseler left-product-carousel owl-style_dot">
                                                    <a href="{{$index_cat2_as->ad_website}}" class="gj_cat2_as"><img src="{{asset('images/category_advertisement/'.$index_cat2_as->ad_image)}}" alt="{{$index_cat2_as->ad_title}}"></a>
                                                </div>
                                            @else
                                                <style type="text/css">
                                                    .widget-product-carousel .tab-product .widget-product__item.index_cat2_as {
                                                        padding: 0px;
                                                        width: 100%;
                                                        float: left;
                                                        border-right: 0px solid #e9ecf1;
                                                    }
                                                </style>
                                            @endif
                                        @else
                                            <style type="text/css">
                                                .widget-product-carousel .tab-product .widget-product__item.index_cat2_as {
                                                    padding: 0px;
                                                    width: 100%;
                                                    float: left;
                                                    border-right: 0px solid #e9ecf1;
                                                }
                                            </style>
                                        @endif
                                    @else
                                        <style type="text/css">
                                            .widget-product-carousel .tab-product .widget-product__item.index_cat2_as {
                                                padding: 0px;
                                                width: 100%;
                                                float: left;
                                                border-right: 0px solid #e9ecf1;
                                            }
                                        </style>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @if($middle_as)
                @if($middle_as->ad_image)
                    @if(($nw_date >= $st_date4) && ($nw_date < $en_date4))
                        <section class="gj_sge_center_ads_sec">
                            <div id="shopify-section-1527305108573" class="shopify-section  home-section">
                              <div class="widget_multibanner radius_3 clearfix">
                                 <div class="container">
                                    <div class="widget-content">
                                       <div class="row">
                                          <div class="item_banner item1 col-12">
                                             <div class="banners">
                                                <div class="gj_mdl_off">
                                                   <a href="{{$middle_as->ad_website}}" title="{{$middle_as->ad_title}}">
                                                   <img class="img-responsive" alt="{{$middle_as->ad_title}}" src="{{asset('images/category_advertisement/'.$middle_as->ad_image)}}" />
                                                   </a>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                            </div>
                        </section>
                    @endif
                @endif
            @endif
            <!-- Third CATEGORIES Section End -->

            <!-- Single Center ADS Section Start -->
           
            <!-- Single Center ADS Section End -->
  <section class="gj_second_cat_sec">
                <div id="shopify-section-1526897752236" class="shopify-section  home-section">
                    <div class="widget-product-carousel clearfix owl-style1">
                        <div class="container">
                            <div class="border-tabs">
                                <div class="widget-head">
                                  
                                   <div class="home-title"><span> TOP RATED PRODUCTS</span></div>
                                </div>

                                <div class="tab-product">
                                    <div class="widget-product__item index_cat2_as">
                                        <div class="products-listing grid">
                                            <div class="product-layout block-content">
                                                <div class="ss-carousel ss-owl">
                                                    <div class="owl-carousel" 
                                                        data-nav       ="true"
                                                        data-margin    ="30"
                                                        data-autoplay  ="true" 
                                                        data-autospeed ="10000" 
                                                        data-speed     ="300"
                                                        data-column1   ="5" 
                                                        data-column2   ="4" 
                                                        data-column3   ="3" 
                                                        data-column4   ="3" 
                                                        data-column5   ="2">

                                                        @if(($top_products) && (count($top_products) != 0))
                                                            @foreach ($top_products as $key => $value)
                                                                <div class="item">
								<div class="product-item">
									<a href="{{ route('view_products', ['id' => $value->id]) }}" class="product-img"> 
									<img src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="">
									
									
													
										<div class="product-absolute-options">
										     <span class="like-icon gj_wish_list" title="wishlist" data-wish-id="{{$value->id}}">
										         
										     </span> </div>
									</a>
									 
									<div class="product-text-dt">
									
									<div class="kelelclienzhre">                                                      
                                                    <button class="gj_shareall_btn"> <i class="fa fa-share-alt sjareall"></i> </button>
                                                    
                                                     <div class="share-links gj_vend_share">
                                                 <ul class="gj_soc_sre">
                                                            <li><a class="op1" target="_blank" href="https://www.facebook.com/sharer.php?u={{Request::fullUrl()}}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op2" target="_blank" href="https://twitter.com/intent/tweet?url={{Request::fullUrl()}}&title=SmartHub">
                                                                <i class="fa fa-twitter" aria-hidden="true"></i></a></li>       
															<li><a class="op3" target="_blank" href="https://www.pinterest.com/pin/create/button/?url={{Request::fullUrl()}}"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op4" target="_blank" href="https://t.me/share/url?url={{Request::fullUrl()}}&title=SmartHub"><i class="fa fa-telegram" aria-hidden="true"></i></a></li> 
                                                            <li><a class="op5" target="_blank" href="https://www.linkedin.com/shareArticle?mimi=true&url={{Request::fullUrl()}}&title=SmartHub"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>    
                                                        </ul>
                                                    </div>

                                                    </div>
													
													
										<h4><a href="{{ route('view_products', ['id' => $value->id]) }}">{{$value->product_title}}</a></h4>
										<div class="product-price">{{$code}}  {{$value->discounted_price}}</div>
										
										<div class="qty-cart">
										<div class="quantity buttons_added">
										<input type="button" value="-" class="minus minus-btn">
										<input type="number" step="1" id="qty_{{$value->id}}" name="quantity" value="1" min="1" class="input-text qty text">
										<input type="button" value="+" class="plus plus-btn">
								<input type="hidden" id="price_{{$value->id}}" name="price" value="{{$value->discounted_price}}" class="quantity-selector">

										</div>
										<div class="add-cart">
											 <button class="addcartzhom gj_add2cartzsx" data-cart-id="{{$value->id}}"> Add to Cart </button>
										 </div>
										</div>
									</div>
								</div>
							</div>
                                                            @endforeach
                                                        @else
                                                            <div class="item">
                                                                <div class="product-item" data-id="product-{{$value->id}}">
                                                                    <div class="product-item-container grid-view-item   ">
                                                                        <div class="left-block">
                                                                            <div class="product-image-container product-image">
                                                                                <a class="grid-view-item__link image-ajax" href="#">
                                                                                    <img class="img-responsive s-img lazyload" data-sizes="auto" src="{{ asset('frontend/images/icon-loadings.svg') }}" data-src="{{ asset($noimage_path.'/'.$noimage->product_no_image)}}" alt="No Products" />
                                                                                </a>
                                                                                <div class="box-countdown">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($index_cat2_as)
                                        @if($index_cat2_as->ad_image)
                                            @if(($nw_date >= $st_date2) && ($nw_date < $en_date2))
                                                <div class="widget_bestseler left-product-carousel owl-style_dot">
                                                    <a href="{{$index_cat2_as->ad_website}}" class="gj_cat2_as"><img src="{{asset('images/category_advertisement/'.$index_cat2_as->ad_image)}}" alt="{{$index_cat2_as->ad_title}}"></a>
                                                </div>
                                            @else
                                                <style type="text/css">
                                                    .widget-product-carousel .tab-product .widget-product__item.index_cat2_as {
                                                        padding: 0px;
                                                        width: 100%;
                                                        float: left;
                                                        border-right: 0px solid #e9ecf1;
                                                    }
                                                </style>
                                            @endif
                                        @else
                                            <style type="text/css">
                                                .widget-product-carousel .tab-product .widget-product__item.index_cat2_as {
                                                    padding: 0px;
                                                    width: 100%;
                                                    float: left;
                                                    border-right: 0px solid #e9ecf1;
                                                }
                                            </style>
                                        @endif
                                    @else
                                        <style type="text/css">
                                            .widget-product-carousel .tab-product .widget-product__item.index_cat2_as {
                                                padding: 0px;
                                                width: 100%;
                                                float: left;
                                                border-right: 0px solid #e9ecf1;
                                            }
                                        </style>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Top Rated Section Start -->
            
            <!-- Top Rated Section End -->

            <!-- Center TWO ADS Section Start -->
         
            <!-- Center TWO ADS Section End -->

          

            <!-- Partners Section Start -->
          
            <!-- Partners Section End -->
        </div>
    </div>
</div>



<!-- <script language ="javascript">
    setInterval(back, 3000);

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
      var name = cname + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var ca = decodedCookie.split(';');
      for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }

    setCookie('i', 0, 365);

    function back() {   
        var rand = Math.floor(Math.random() * 5);
        var x = getCookie('i');
        var all = [];

        if(x == 0) {
            x = 1;
        }

        
        $(".tp-revslider-slidesli").each(function () {
            all.push($(this).attr('data-aid'));
            $(this).removeClass('active-revslide');
        });

        if (all.length !== 0) {
            if(all.length >= x) {
                $('.tp-revslider-mainul li:nth-child('+x+')').addClass('active-revslide');
                var y = x+1;
                setCookie('i', y, 365);
            } else {
                setCookie('i', 0, 365);
            }
        }
    }
</script> -->

<!-- <script language ="javascript">
    var t="xxxy";
    var e="xyyy";
    setInterval(back, 2000, t, e);

    function back(t,e)
    {   
        alert(t+e);
    }
</script> -->
@endsection

