<?php
$general = \DB::table('general_settings')->first();
$email = \DB::table('email_settings')->first();
$social = \DB::table('social_media_settings')->first();
$widget = \DB::table('widgets')->first();
$main_cat = \DB::table('category_management_settings')->where('is_block', 1)->get();
if($main_cat) {
    if(count($main_cat) != 0) {
        foreach ($main_cat as $key => $value) {
            $s_cat = \DB::table('sub_category_management_settings')->where('is_block', 1)->where('main_cat_name', $value->id)->get();
            $main_cat[$key]->{'sub_cat'} = $s_cat;

            foreach ($s_cat as $keys => $values) {
                $ss_cat = \DB::table('sub_sub_category_management_settings')->where('is_block', 1)->where('sub_cat_name', $values->sub_cat_id)->get();
                $main_cat[$key]->{'sub_cat'}[$keys]->{'sub_sub_cat'} = $ss_cat;
            }
        }
    }
}

$sub_cat = \DB::table('sub_category_management_settings')->where('is_block', 1)->get();
$sub_sub_cat = \DB::table('sub_sub_category_management_settings')->where('is_block', 1)->get();
$cms = \DB::table('c_m_s_page_managements')->where('is_block', 1)->get();
$brands = \DB::table('brands')->where('is_block', 1)->take(5)->get();

$logo = \DB::table('logo_settings')->first();
$logo_path = 'images/logo';
$favicon = \DB::table('favicon_settings')->first();
$favicon_path = 'images/favicon';
$product_path = 'images/featured_products';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';
$prof_file_path = 'images/profile_img';

$foo_right_offer = \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)->Where('page', 'Footer')->Where('position', 'Right')->first();
$foo_left_offer = \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)->Where('page', 'Footer')->Where('position', 'Left')->first();
$nw_date = date('Y-m-d');
$nw_date = date('Y-m-d', strtotime($nw_date));

if($foo_right_offer) {
  $st_date1 = date('Y-m-d', strtotime($foo_right_offer->ad_start_date));
  $en_date1 = date('Y-m-d', strtotime($foo_right_offer->ad_end_date));
}

if($foo_left_offer) {
  $st_date2 = date('Y-m-d', strtotime($foo_left_offer->ad_start_date));
  $en_date2 = date('Y-m-d', strtotime($foo_left_offer->ad_end_date));
}
?>
<html lang="{{ app()->getLocale() }}"> 
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="author" content=" ">
    <meta name="title" content="@if($general) {{$general->meta_title}} @else Online Sanwariya Fashion Shopping Website @endif">
    <meta name="description" content="@if($general) {{$general->meta_description}} @else Online Sanwariya Fashion Shopping Website @endif">
    <meta name="keywords" content="@if($general) {{$general->meta_keywords}} @else Online Sanwariya Fashion Shopping Website @endif">
    <title>@if($general){{$general->site_name}} @else Sanwariya Enterprises @endif - @yield('title')</title>

    @if($favicon)
      <link rel="shortcut icon" href="{{ asset($favicon_path.'/'.$favicon->favicon_image)}}" type="image/x-icon">
    @else
      <link rel="shortcut icon" href="{{ asset('images/fav_icon.png')}}" type="image/x-icon">
    @endif

    <script src="{{ asset('frontend/js/jquery.min.js')}}"></script>
    	<script src="{{ asset('assets')}}/js/custom.js"></script>

    <!--<script src="{{ asset('frontend/js/bootstrap.min.js')}}"></script>-->
    <script src="{{ asset('frontend/js/slick.min.js')}}"></script>
    <script src="{{ asset('frontend/js/ss_custom.js')}}"></script>
    <script src="{{ asset('frontend/js/api.js')}}"></script>
    <script src="{{ asset('frontend/js/libs.js')}}"></script>
    <script src="{{ asset('frontend/js/wish-list.js')}}"></script>
    <script src="{{ asset('frontend/js/owl.carousel.js')}}"></script>
    <script src="{{ asset('frontend/js/jquery.fancybox.pack.js')}}" ></script>
    <script src="{{ asset('frontend/js/option_selection.js')}}"></script>
    <script src="{{ asset('frontend/js/sticky-kit.js')}}"></script>
    <script src="{{ asset('frontend/js/jquery.themepunch.tools.min.js')}}"></script>
    <script src="{{ asset('frontend/js/jquery.themepunch.revolution.min.js')}}"></script>
    <script src="{{ asset('frontend/js/express_buttons.js')}}" crossorigin="anonymous"></script>
    <script src="{{ asset('frontend/js/features.js')}}" crossorigin="anonymous"></script>
    <script src="{{ asset('frontend/js/preview_bar.js')}}" crossorigin="anonymous"></script>
    <script id="sections-script" src="{{ asset('frontend/js/scripts.js')}}" crossorigin="anonymous"></script>
    <script src="{{ asset('js/select2.min.js')}}"></script>
    <script src="{{ asset('js/jquery-confirm.min.js')}}"></script>
    
    <script src="{{ asset('assets')}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="{{ asset('assets')}}/vendor/OwlCarousel/owl.carousel.js"></script>
	<script src="{{ asset('assets')}}/vendor/semantic/semantic.min.js"></script>
	<script src="{{ asset('assets')}}/js/jquery.countdown.min.js"></script>
	<script src="{{ asset('assets')}}/js/offset_overlay.js"></script>
	<script src="{{ asset('assets')}}/js/product.thumbnail.slider.js"></script>
    <script type="text/javascript" src="{{ URL::asset('/js/jquery.toaster.js') }}"></script>


    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/jquery-confirm.min.css')}}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/slick.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/theme-config.css')}}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/theme-style.css')}}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/theme-style1.css')}}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/theme-sections.css')}}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/theme-responsive.css')}}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/animate.css')}}">
        <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/styles.css')}}">

    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/owl.carousel.css')}}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/jquery.fancybox.css')}}">
                <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/section.css')}}">


    <link rel="stylesheet" href="{{ asset('css/select2.min.css')}}">
      
    <script type="text/javascript">
      WebFontConfig = {
          google: { families: [ 
              'Poppins:100,200,300,400,500,600,700,800,900',
              'Agency FB:100,200,300,400,500,600,700,800,900',    
              'Poppins:100,200,300,400,500,600,700,800,900'
          ] }
      };
      (function() {
          var wf = document.createElement('script');
          wf.src = ('https:' == document.location.protocol ? 'https' : 'https') +
          '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
          wf.type = 'text/javascript';
          wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
          s.parentNode.insertBefore(wf, s);
      })(); 
    </script>

    <style>
      .shopify-payment-button__button--hidden {
        visibility: hidden;
      }
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
    </style>


    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('frontend/css/ss_slider.css')}}">
    <!--<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('frontend/css/styles.css')}}">-->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/style.css')}}">
    <link href="{{asset('assets')}}/css/responsive.css" rel="stylesheet"> 
    <link href='{{asset("assets")}}/vendor/unicons-2.0.1/css/unicons.css' rel='stylesheet'>
	<link href="{{asset('assets')}}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="{{asset('assets')}}/vendor/OwlCarousel/assets/owl.carousel.css" rel="stylesheet">
	<link href="{{asset('assets')}}/vendor/OwlCarousel/assets/owl.theme.default.min.css" rel="stylesheet">
	<link href="{{asset('assets')}}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{asset('assets')}}/vendor/semantic/semantic.min.css">
  </head>
  <body>
      	<div class="bs-canvas bs-canvas-left position-fixed bg-cart h-100">
      	     <?php  
                                          $users = session()->get('user');
                                        ?>
                                        @if($users)
                                        <?php  
                                              $carts = \DB::table('carts')->Where('user_id', $users->id)->get()->toArray();
                                            ?>
                                            @if(count($carts) != 0)
                                              <?php  
                                              $tot_qty = \DB::table('carts')->selectRaw('sum(qty) as tot_qty')->Where('user_id', $users->id)->get();
                                              $tot_pce = \DB::table('carts')->selectRaw('sum(price) as tot_pce')->Where('user_id', $users->id)->get();
                                              ?>
                                    		<div class="bs-canvas-header side-cart-header p-3 ">
                                    			<div class="d-inline-block  main-cart-title">My Cart <span>({{count($carts)}} Items)</span></div>
                                    			<button type="button" class="bs-canvas-close close" aria-label="Close"><i class="uil uil-multiply"></i></button>
                                    		</div>
                                    		@else 
                                    		<div class="bs-canvas-header side-cart-header p-3 ">
                                    			<div class="d-inline-block  main-cart-title">My Cart <span>(0 Items)</span></div>
                                    			<button type="button" class="bs-canvas-close close" aria-label="Close"><i class="uil uil-multiply"></i></button>
                                    		</div>
                                    		@endif
                        		  @if(count($carts) != 0)
                        		<div class="bs-canvas-body">
                        			<div class="cart-top-total">
                        				<div class="cart-total-dil">
                        					<h4>Smart Hub </h4> <span>ksh 34</span> </div>
                        				<div class="cart-total-dil pt-2">
                        					<h4>Delivery Charges</h4> <span>ksh 1</span> </div>
                        			</div>
                        			<div class="side-cart-items">
                        			    <?php $tot=0; ?>
                        			     @foreach($carts as $key => $value)
                        			     <?php $tot =$tot+$value->total_price; ?>
                        				<div class="cart-item">
                        					<div class="cart-product-img">
                        					     <?php if($value->image) {
                                                                        echo '<img class="img-responsive gj_cart_thumb"src="'.asset($product_path.'/'.$value->image).'" alt="'.$value->name.'">';
                                                                      } else {
                                                                        echo '<img src="'.asset($noimage_path.'/'.$noimage->product_no_image).'" alt="No Images" class="img-responsive gj_cart_thumb">';
                                                                      } ?>
                        						<!--<div class="offer-badge">6% OFF</div>-->
                        					</div>
                        					<div class="cart-text">
                        						<h4>{{$value->name}}</h4>
                        					
                        						<div class="qty-group">
                        							
                        							<div class="cart-item-price">{{$code}} {{$value->total_price}} X {{$value->qty}}</div>
                        						</div>
                        						<button type="button" data-id="{{$value->product_id}}" data-cart-id="{{$value->id}}"  data-cart-key="{{$value->cart_key}}"  data-cart-del="{{$value->cart_del}}" class="gj_cart_tabl_del cart-close-btn"><i class="uil uil-multiply"></i></button>
                        					</div>
                        				</div>
                        				@endforeach
                        			
                        			</div>
                        		</div>
                        		<div class="bs-canvas-footer">
                        			<div class="cart-total-dil saving-total ">
                        				<h4>Total Saving</h4> <span>{{$code}} {{$tot}}</span> </div>
                        			<div class="main-total-cart">
                        				<h2>Total</h2> <span>{{$code}} {{$tot}}</span> </div>
                        			<div class="checkout-cart"> 
                        			<a href="{{ route('checkout') }}" class="promo-code">
                        			    Proceed to Checkout</a>
                        			<a href="{{ route('cart') }}" class="cart-checkout-btn hover-btn">Go To Cart</a> 
                        			 </div>
                        		</div>
                        		@endif
                        	@elseif(Session::has('cart'))
                        	 <?php
                                          $cart = Session::get('cart');
                                          ?>
                                          @if(count($cart) != 0)
                                          <div class="bs-canvas-header side-cart-header p-3 ">
                                    			<div class="d-inline-block  main-cart-title">My Cart <span>({{count($cart)}} Items)</span></div>
                                    			<button type="button" class="bs-canvas-close close" aria-label="Close"><i class="uil uil-multiply"></i></button>
                                    		</div>
                                    			<div class="bs-canvas-body">
                        			<div class="cart-top-total">
                        				<div class="cart-total-dil">
                        					<h4>Smart Hub </h4> <span>ksh 34</span> </div>
                        				<div class="cart-total-dil pt-2">
                        					<h4>Delivery Charges</h4> <span>ksh 1</span> </div>
                        			</div>
                        			<div class="side-cart-items">
                        			    <?php $tot=0; ?>
                        			    @foreach($cart as $key => $value)
                        			     <?php $tot =$tot+$value['total_price']; ?>
                        				<div class="cart-item">
                        					<div class="cart-product-img">
                        					     <?php if($value['image']) {
                                                        echo '<img class="img-responsive gj_cart_thumb"src="'.asset($product_path.'/'.$value['image']).'" alt="'.$value['name'].'">';
                                                      } else {
                                                        echo '<img src="'.asset($noimage_path.'/'.$noimage->product_no_image).'" alt="No Images" class="img-responsive gj_cart_thumb">';
                                                      } ?>
                        						<!--<div class="offer-badge">6% OFF</div>-->
                        					</div>
                        					<div class="cart-text">
                        						<h4>{{$value['name']}}</h4>
                        					
                        						<div class="qty-group">
                        							
                        							<div class="cart-item-price">{{$code}}  {{$value['total_price']}}
                        							X {{$value['qty']}}</div>
                        						</div>
                        						<button type="button" 
                        						 data-id="{{$value['product_id']}}" data-cart-id="0"  data-cart-key="{{(isset($value['cart_key']) ? $value['cart_key'] : '')}}"
                                                    data-cart-del="{{(isset($value['cart_del']) ? $value['cart_del'] : '')}}"  class="gj_cart_tabl_del cart-close-btn"><i class="uil uil-multiply"></i></button>
                        					</div>
                        				</div>
                        				@endforeach
                        			
                        			</div>
                        		</div>
                        		<div class="bs-canvas-footer">
                        			<div class="cart-total-dil saving-total ">
                        				<h4>Total Saving</h4> <span>{{$code}} {{$tot}}</span> </div>
                        			<div class="main-total-cart">
                        				<h2>Total</h2> <span>{{$code}} {{$tot}}</span> </div>
                        			<div class="checkout-cart"> 
                        		
                        			<a href="{{ route('checkout') }}" class="promo-code">
                        			    Proceed to Checkout</a> 
                        			    	<a href="{{ route('cart') }}" class="cart-checkout-btn hover-btn">Go To Cart</a> 
                        			    </div>
                        		</div>
                                    	
                                    		
                                    		
                                    		@else
                                    		<div class="bs-canvas-header side-cart-header p-3 ">
                                    			<div class="d-inline-block  main-cart-title">My Cart <span>(0 Items)</span></div>
                                    			<button type="button" class="bs-canvas-close close" aria-label="Close"><i class="uil uil-multiply"></i></button>
                                    		</div>
                                    		@endif
                                    		
                    @else
                    	<div class="bs-canvas-header side-cart-header p-3 ">
                                    			<div class="d-inline-block  main-cart-title">My Cart <span>(0 Items)</span></div>
                                    			<button type="button" class="bs-canvas-close close" aria-label="Close"><i class="uil uil-multiply"></i></button>
                                    		</div>
                                          
                                          
                        		
		@endif
	</div>
    <header class="header clearfix">
		<div class="top-header-group">
			<div class="top-header">
				<div class="res_main_logo">
					<a href="{{ route('home') }}">
					    @if($logo)
                              <img src="{{ asset($logo_path.'/'.$logo->logo_image)}}" alt="Logo">
                            @else
                              <img src="{{ asset('images/logo.png')}}" alt="Logo">
                            @endif
					</a>
				</div>
				<div class="main_logo" id="logo">
				    <a href="{{ route('home') }}" itemprop="url" class="site-header-logo-image">
                            @if($logo)
                              <img src="{{ asset($logo_path.'/'.$logo->logo_image)}}" alt="Logo">
                            @else
                              <img src="{{ asset('images/logo.png')}}" alt="Logo">
                            @endif
                          </a>
                          <a href="{{ route('home') }}" itemprop="url" class="site-header-logo-image">
                            @if($logo)
                              <img class="logo-inverse" src="{{ asset($logo_path.'/'.$logo->logo_image)}}" alt="Logo">
                            @else
                              <img class="logo-inverse" src="{{ asset('images/logo.png')}}" alt="Logo">
                            @endif
                          </a>
				
				</div>
				<div class="select_location">
					<div class="ui inline dropdown loc-title">
						<div class="text"> <i class="uil uil-location-point"></i> Hariyana </div> <i class="uil uil-angle-down icon__14"></i>
						<div class="menu dropdown_loc">
							<div class="item channel_item"> <i class="uil uil-location-point"></i> Nairobi </div>
							<div class="item channel_item"> <i class="uil uil-location-point"></i> Mombasa </div>
							<div class="item channel_item"> <i class="uil uil-location-point"></i> Kisumu </div>
							<div class="item channel_item"> <i class="uil uil-location-point"></i> Nakuru </div>
							<div class="item channel_item"> <i class="uil uil-location-point"></i> Eldoret </div>
							<div class="item channel_item"> <i class="uil uil-location-point"></i> Malindi </div>
							<div class="item channel_item"> <i class="uil uil-location-point"></i> Ludhiana </div>
							<div class="item channel_item"> <i class="uil uil-location-point"></i> Chandigrah </div>
						</div>
					</div>
				</div>
				<div class="search120">
					<div class="ui search">
						<div class="ui left icon input swdh10">
						                                      {{ Form::open(array('url' => 'main_search', 'method'=>'GET','class'=>'formSearch','files' => true)) }}

							<input class="prompt srch10" type="search" name="main_srh" placeholder="Search for products..">
							<i class='uil uil-search-alt icon icon1'></i> </div>
							
							 {{ Form::close() }}
					</div>
				</div>
				<div class="header_right">
				    <?php 
                        $users = session()->get('user'); 
                         
                    ?>
                     <span class="icon-user wiseer"> 
                    @if(isset($users))
                    <?php 
                        
                         $value=App\User::find($users->id);
                    ?>
                        @if(isset($value) && $value->user_type == 4 || $value->user_type == 5)
                        <li class="ui dropdown">
                <a href="{{ route('my_account') }}" class="opts_account">
                        @if($value->profile_img)
                                <img src="{{ asset($prof_file_path.'/'.$value->profile_img)}}" > 
                            @else
                                <img src="{{ asset('images/site_img/default_profile.jpg')}}"> 
                            @endif
                <span class="user__name">{{$value->first_name}}</span>
                <i class="uil uil-angle-down"></i>
                </a>
                <div class="menu dropdown_account"> 
                <a href="{{ route('my_account') }}" class="item channel_item"><i class="uil uil-apps icon__1"></i>Dashbaord</a>
                <a href="{{url('get_orders')}}" class="item channel_item"><i class="uil uil-box icon__1"></i>My Orders</a>
                <a href="{{url('wishlist')}}" class="item channel_item"><i class="uil uil-heart icon__1"></i>My Wishlist</a>
                <!--<a href="#" class="item channel_item"><i class="uil uil-usd-circle icon__1"></i>My Wallet</a>-->
                <a href="{{url('manage_address')}}" class="item channel_item"><i class="uil uil-location-point icon__1"></i>My Address</a>
                 
                <a href="{{ route('logout') }}" class="item channel_item"><i class="uil uil-lock-alt icon__1"></i>Logout</a>
                </div>
                </li>
                           
                        @else
                        @endif
                    @else
                       
                    @endif
                </span>
                 
                                            @if(isset($users))
                                             <?php 
                        
                                         $value=App\User::find($users->id);
                                    ?>
                                                @if(isset($value) && $value->user_type == 4 || $value->user_type == 5)
                                                
                                                @else
                                                <ul>
                                                   
                                                    <li>
                                                        <a href="{{ route('signin') }}" class="option_links toplogreg" ><i class='fa fa-sign-in'></i> Login</a>
                                                        </li>
                                                        <li>
                                                         <a href="{{ route('signup') }}" class="option_links toplogreg" ><i class='fa fa-user'></i> Register</a>
                                                   </li>
                                                  </ul>
                                                @endif
                                            @else
                                            	<ul>
                                              <li>
                                                <a href="{{ route('signin') }}" class="option_links toplogreg" ><i class='fa fa-sign-in'></i> Login</a> 
                                                 </li>
                                                <li><a href="{{ route('signup') }}"  class="option_links toplogreg"><i class='fa fa-user'></i> Register</a>
                                              </li>
                                              </ul>
                                            @endif
                                         
				
					
				</div>
			</div>
		</div>
		<div class="sub-header-group">
			<div class="sub-header">
			
				<div class="navcatslide">
					<div class="hidden-xs">
						<div id="wr-menu">
							<button class="btn-block text-left" type="button" data-target="#all-menu" data-toggle="collapse"> <i class="fa fa-bars"></i> <span class="cate">Categories</span> </button>
						</div>
						<div id="all-menu" class="collapse show">
							<nav id="menu" class="navbar">
								<div class="navbar-header tonizz"><span id="category" class="visible-xs">Categories</span>
									<button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
								</div>
								<div class=" navbar-toggle collapse navbar-collapse navbar-ex1-collapse">
									<ul class="nav navgondop">
									    @if(count($main_cat) != 0)
                                          @foreach($main_cat as $key => $value)
                                           @if(count($value->sub_cat) != 0)
                                           <li class="dropdown moremenu">
											<a href="{{ url('all_filter_products?fil_cats='.$value->id) }}" class="dropdown-toggle header-menu" data-toggle="dropdown">
												<div class="menu-img pull-left"> 
												<img src="{{asset('images/main_cat_image/'.$value->main_cat_image)}}" alt="Flours"> 
												</div>	{{$value->main_cat_name}}<i class="fa fa-angle-down pull-right enangle"></i></a>
											<div class="dropdown-menu">
												<div class="dropdown-inner">
													<ul class="list-unstyled">
														<!--3rd level-->
														@foreach($value->sub_cat as $keys => $values)
														<li class="dropdown-submenu"> 
														<a href="{{ url('all_filter_products?fil_cats='.$value->id.'&fil_ss_cats='.$values->sub_cat_id) }}"
														class="submenu-title"> {{$values->sub_cat_name}} </a> </li>
														 @endforeach
														<!--3rd level over-->
													</ul>
												</div>
												</div>
										</li>
                                           @else
                                           	<li class="moremenu">
											<a href="{{ url('all_filter_products?fil_cats='.$value->id) }}">
												<div class="menu-img pull-left"> 
												<img src="{{asset('images/main_cat_image/'.$value->main_cat_image)}}" alt="Snacks"> </div>  	{{$value->main_cat_name}} </a>
										</li>
                                           @endif
                                           @endforeach
                                           @endif
										
									
										
									</ul>
									</div>
							</nav>
							</div>
							</div>
							</div> 
	 
				<nav class="navbar navbar-expand-lg navbar-light py-3">
					<div class="container-fluid">
						<button class="navbar-toggler menu_toggle_btn" type="button" data-target="#navbarSupportedContent"><i class="uil uil-bars"></i></button>
						<div class="collapse navbar-collapse d-flex flex-column flex-lg-row flex-xl-row justify-content-lg-end bg-dark1 p-3 p-lg-0 mt1-5 mt-lg-0 mobileMenu" id="navbarSupportedContent">
							<ul class="navbar-nav main_nav align-self-stretch">
								<li class="nav-item"><a href="{{url('/')}}" class="nav-link active" title="Home">Home</a></li>
								<li class="nav-item"><a href="{{url('/about')}}" class="nav-link" title="about">About</a></li>
								<li class="nav-item"><a href="{{url('all_products')}}" class="nav-link new_item" title=" Products"> Products</a></li>
								<li class="nav-item"><a href="{{url('contact')}}" class="nav-link" title="Contact">Contact Us</a></li>
							</ul>
						</div>
					</div>
				</nav>
				<div class="header_right header_cart">
				     <?php  
                                          $users = session()->get('user');
                                        ?>
                                        
                                            
                                            
					<ul>
					     @if($users)
                                         
                                            <?php  
                                              $carts = \DB::table('carts')->Where('user_id', $users->id)->get()->count();
                                               $wishs = \DB::table('wish_lists')->Where('user_id', $users->id)->count();
                                            ?>
                                            	<li> <a href="{{ route('cart') }}" class="option_links cart__btn pull-bs-canvas-left" 
                                            	title="Cart">
						    <i class='uil uil-shopping-cart-alt'></i><span class="noti_count1">{{$carts}}</span></a> </li>
						       	<li> <a href="{{ route('wishlist') }}" class="option_links" title="Wishlist">
						    <i class='uil uil-heart icon_wishlist'></i><span class="noti_count1">{{$wishs}}</span></a> </li>
						    @elseif(Session::has('cart'))
                                            <?php
                                            // $cart_total = Session::get('cart_total');
                                            $cart_total = Session::get('cart');
                                            ?>
                                            	<li> <a href="{{ route('cart') }}" class="option_links cart__btn pull-bs-canvas-left" 
                                            	title="Cart">
						    <i class='uil uil-shopping-cart-alt'></i><span class="noti_count1">{{count($cart_total)}}</span></a> </li>
						    	<li> <a href="#" class="option_links" title="Wishlist">
						    <i class='uil uil-heart icon_wishlist'></i><span class="noti_count1">0</span></a> </li>
						    @else
						    	<li> <a href="{{ route('cart') }}" class="option_links cart__btn pull-bs-canvas-left" 
                                            	title="Cart">
						    <i class='uil uil-shopping-cart-alt'></i><span class="noti_count1">0</span></a> </li>
						    	<li> <a href="#" class="option_links" title="Wishlist">
						    <i class='uil uil-heart icon_wishlist'></i><span class="noti_count1">0</span></a> </li>
                                            @endif
					
						    
					
					</ul>
				</div>
				<div class="search__icon order-1"> <a href="#" class="search__btn hover-btn" data-toggle="modal" data-target="#search_model" title="Search"><i class="uil uil-search"></i></a> </div>
			</div>
		</div>
	</header>
     @if(Session::has('message'))
            <script>
           
                $.toaster({ message : "{{ Session::get('message') }}", title : 'Smart Hub', priority : "{{ Session::get('alert-class', 'alert-info') }}" });
            </script>
             
            @endif
    
    @yield('content')
    
    	<footer class="footer" style="background: url({{asset('assets/footer.png')}})">
		<div class="footer-second-row">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-6 col-sm-6">
						<div class="second-row-item">
						     
							<h4>Categories</h4>
							<ul>
							@if(($main_cat) && (count($main_cat) != 0))
                                                    <?php 
                                                    for ($i=0; $i <= count($main_cat); $i++) { 
                                                        if(isset($main_cat[$i])) { ?>
                                                            <li>
                                                               <a href="{{ url('all_filter_products?fil_cats='.$main_cat[$i]->id) }}"><?php echo $main_cat[$i]->main_cat_name; ?></a>
                                                            </li>
                                                    <?php }
                                                    } ?>
                                                @else
                                                    <li>
                                                       <a href="#">No Category Products</a>
                                                    </li>
                                                @endif
							
							</ul>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-6">
						<div class="second-row-item">
							<h4>Useful Links</h4>
							<ul>
								@if(($cms) && (count($cms) != 0))
                                                <?php 
                                                for ($i=0; $i <= 6; $i++) { 
                                                    if(isset($cms[$i])) { ?>
                                                    <li>
                                                      <a href="{{ route('pages', ['name' => $cms[$i]->page_name]) }}"><?php echo $cms[$i]->page_name; ?></a>
                                                    </li>
                                                <?php }
                                                } ?>
                                                @else
                                                    <li>
                                                       <a href="#">No Pages</a>
                                                    </li>
                                                @endif
							</ul>
						</div>
					</div>
				
					<div class="col-lg-3 col-md-6 col-sm-6">
						<div class="second-row-item-payment">
							<h4>Payment Method</h4>
							<div class="footer-payments">
								<ul id="paypal-gateway" class="financial-institutes">
									<li class="financial-institutes__logo"> <img alt="Visa" title="Visa" src="{{asset('assets')}}/footer-icons/pyicon-6.svg"> </li>
									<li class="financial-institutes__logo"> <img alt="Visa" title="Visa" src="{{asset('assets')}}/footer-icons/pyicon-1.svg"> </li>
									<li class="financial-institutes__logo"> <img alt="MasterCard" title="MasterCard" src="{{asset('assets')}}/footer-icons/pyicon-2.svg"> </li>
									<li class="financial-institutes__logo"> <img alt="American Express" title="American Express" src="{{asset('assets')}}/footer-icons/pyicon-3.svg"> </li>
									<li class="financial-institutes__logo"> <img alt="Discover" title="Discover" src="{{asset('assets')}}/footer-icons/pyicon-4.svg"> </li>
								</ul>
							</div>
						</div>
						
					</div>
					<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="second-row-item-payment">
							<h4>Newsletter</h4>
							<div class="newsletter-input">
							{{ Form::open(array('url' => 'news_letters', 'id'=>'contact_form','class'=>'contact-form','files' => true)) }}
                                                        <span class="error"> 
                                                          @if (isset($errors) && $errors->has('email'))
                                                            {{ $errors->first('email') }}
                                                          @endif
                                                      </span>
								<input id="email" name="email" type="text" placeholder="Email Address" class="form-control input-md" required="">
								<button class="newsletter-btn hover-btn" type="submit"><i class="uil uil-telegram-alt"></i></button>
								{{ Form::close() }}
							</div>
						</div>
						</div>
				</div>
			</div>
		</div>
		<div class="footer-last-row">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="copyright-text"> 
						<address>© {{ Carbon\Carbon::today()->format('Y') }} <a href="{{ route('home') }}"> Smart Hub </a> All Rights Reserved.</address>
 </div>
					</div>
				</div>
			</div>
		</div>
	</footer>
    
    <!-- Whatsapp Chat Script Start -->
    <script type="text/javascript">
      (function () {
        var options = {
          whatsapp: "+91-7902506918", // WhatsApp number
          call_to_action: "Message us", // Call to action
          position: "left", // Position may be 'right' or 'left'
        };

        var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
      })();
    </script>
    <!-- Whatsapp Chat Script End -->

    <!--Start of Online Chat Tawk.to Script-->
    <!-- <script type="text/javascript">
      var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
      (function() {
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5ca3254e1de11b6e3b0665da/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
      })();
    </script> -->

        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=true;
                s1.src='https://embed.tawk.to/5daeda11df22d91339a067fa/default';
                s1.charset='UTF-8';
                s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0);
            })();
        </script>
        <!--End of Tawk.to Script-->
    <!--End of Online Chat Tawk.to Script-->

    <script src="{{ asset('frontend/js/currencies.js')}}"></script>
    <script src="{{ asset('frontend/js/jquery.currencies.js')}}"></script>

    <!-- Error Message Display Script Start -->
    <script type="text/javascript">
      $('p.news').delay(7000).slideUp(700);
      $('p.alert').delay(7000).slideUp(500);
      $('p.gj_alt').delay(7000).slideUp(900);
    </script>
    <!-- Error Message Display Script End -->

    <!-- Brand Menu Auto Search Script Start -->
    <script type="text/javascript">
      $('.gj_auto_brand').bind('keydown keyup',function () {
        var th = $(this);
        var keywrd = 0;
        if($(this).val()) {
          var keywrd = $(this).val();
        }

        if(keywrd != 0) {
          $.ajax({
            type: 'post',
            url: '{{url('/brand_auto_complete')}}',
            data: {keywrd: keywrd, type: 'brand_auto_complete'},
            dataType: "json",
            success: function(data) {
              if(data['error'] == 0) {
                th.closest('ul').find('.gj_auto_br_div').html(data['data']);
                // th.val('');
              }
            }
          });
        }
      });
    </script>
    <!-- Brand Menu Auto Search Script End -->

    <!-- Add To Cart Script Start -->
    <script type="text/javascript">
      $('.gj_add2cart').on('click', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-cart-id');
        var qty = 0;
        var price = 0;
        var att_name = 0;
        var att_value = 0;

        if($('#qty').val()) {
          var qty = $('#qty').val();
        }
        if($('#price').val()) {
          var price = $('#price').val();
        }
        if($('.gj_vw_att_name').val()) {
          var att_name = $('.gj_vw_att_name').val();
        }
        if($('.gj_vw_att_value').val()) {
          var att_value = $('.gj_vw_att_value').val();
        }

        if(id) {
          $.ajax({
            type: 'post',
            url: '{{url('/add_to_cart')}}',
            data: {id: id, qty: qty, price: price, att_value: att_value, att_name: att_name, type: 'add_to_cart'},
            success: function(data){
              if(data == 2){
                $.confirm({
                    title: '',
                    content: 'Already Added To Cart!',
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
                            // window.location.reload();
                        }
                    }
                });
                
                // setTimeout(function(){ window.location.reload(); }, 3000);
              } else if(data != 1){
                $.confirm({
                    title: '',
                    content: 'Added To Cart!',
                    icon: 'fa fa-check',
                    theme: 'modern',
                    closeIcon: false,
                    animation: 'scale',
                    type: 'green',
                    /*buttons: {
                        Ok: function(){
                            window.location.reload();
                        }
                    }*/
                });
                 window.location.reload();
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
        } else {
            $.confirm({
                title: '',
                content: 'Please Add product to the cart in another time!',
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
      });
      
      
      
      
      $('.gj_add2cartzsx').on('click', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-cart-id');
        var qty = 0;
        var price = 0;
        var att_name = 0;
        var att_value = 0;

        if($('#qty_'+id).val()) {
          var qty = $('#qty_'+id).val();
        }
        if($('#price_'+id).val()) {
          var price = $('#price_'+id).val();
        }
        

        if(id) {
          $.ajax({
            type: 'post',
            url: '{{url('/add_to_cart')}}',
            data: {id: id, qty: qty, price: price, att_value: att_value, att_name: att_name, type: 'add_to_cart'},
            success: function(data){
              if(data == 2){
                $.confirm({
                    title: '',
                    content: 'Already Added To Cart!',
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
                            // window.location.reload();
                        }
                    }
                });
                
                // setTimeout(function(){ window.location.reload(); }, 3000);
              } else if(data != 1){
                $.confirm({
                    title: '',
                    content: 'Added To Cart!',
                    icon: 'fa fa-check',
                    theme: 'modern',
                    closeIcon: false,
                    animation: 'scale',
                    type: 'green',
                    /*buttons: {
                        Ok: function(){
                            window.location.reload();
                        }
                    }*/
                });
                 window.location.reload();
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
        } else {
            $.confirm({
                title: '',
                content: 'Please Add product to the cart in another time!',
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
      });

      /*Delete Cart Script Start*/
      $('.gj_cart_tabl_del').on('click', function(){
        if($(this).data('id')) {
          var id = $(this).data('id');
          var cart_id = $(this).data('cart-id');
          var cart_key = $(this).data('cart-key');
          var cart_del = $(this).data('cart-del');
          $.ajax({
            type: 'post',
            url: '{{url('/delete_cart')}}',
            data: {id: id, cart_id: cart_id, cart_key: cart_key, cart_del: cart_del, type: 'delete_cart'},
            success: function(data){
              if(data != 1){
                window.location.reload();
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
              }
            }
          });
        }
      });
    </script>
    <!-- Add To Cart Script End -->

    <!-- Wish List Script Start -->
    <script type="text/javascript">
      $('.gj_wish_list').on('click', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-wish-id');

        if(id) {
          $.ajax({
            type: 'post',
            url: '{{url('/wishlist')}}',
            data: {id: id, type: 'wishlist'},
            success: function(data){
              if(data == 2){
                $.confirm({
                    title: '',
                    content: 'Already Added To Wish List!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'blue',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
                // window.location.reload();
              } else if(data == 3){
                $.confirm({
                    title: '',
                    content: 'You Must Login!',
                    icon: 'fa fa-exclamation',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        Ok: function(){
                        }
                    }
                });
                // window.location.reload();
              }  else if(data != 1){
                window.location.reload();
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
                        }
                    }
                });
              }
            }
          });
        } else {
            $.confirm({
                title: '',
                content: 'Please Add product to the Favourite in another time!',
                icon: 'fa fa-exclamation',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    Ok: function(){
                        window.location.reload();
                    }
                }
            });
        }
      });
    </script>
    <!-- Wish List Script End -->

    <!-- Auto Complete Off Script Start -->
    <script type="text/javascript">
      $(document).ready(function() {
        $("input").attr('autocomplete', 'new-password');
      });
    </script>
    <!-- Auto Complete Off Script End -->

    @if($social)
      @if($social->analytics_code)
        <div><?php echo htmlspecialchars_decode($social->analytics_code); ?></div>
      @endif
    @endif
  </body>
</html>