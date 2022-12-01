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
    <meta name="author" content="Godwin Joe Jo">
    <meta name="title" content="@if($general) {{$general->meta_title}} @else Online E-Cambiar Fashion Shopping Website @endif">
    <meta name="description" content="@if($general) {{$general->meta_description}} @else Online E-Cambiar Fashion Shopping Website @endif">
    <meta name="keywords" content="@if($general) {{$general->meta_keywords}} @else Online E-Cambiar Fashion Shopping Website @endif">
    <title>@if($general){{$general->site_name}} @else E-Cambiar @endif - @yield('title')</title>

    @if($favicon)
      <link rel="shortcut icon" href="{{ asset($favicon_path.'/'.$favicon->favicon_image)}}" type="image/x-icon">
    @else
      <link rel="shortcut icon" href="{{ asset('images/fav_icon.png')}}" type="image/x-icon">
    @endif

    <script src="{{ asset('frontend/js/jquery.min.js')}}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js')}}"></script>
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
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/owl.carousel.css')}}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/jquery.fancybox.css')}}">
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
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('frontend/css/styles.css')}}">
  </head>
  <body>
    <header id="header" class="header header-style1">
          <div class="bg-header">
              <div class="header-center">
                  <div class="container">
                    <div class="row">
                        <div class="navbar-logo col-xl-2 col-lg-3 d-none d-lg-block">
                            <div class="site-header-logo title-heading">
                            <a href="{{ route('home') }}" itemprop="url" class="site-header-logo-image">
                            @if($logo)
                              <img src="{{ asset($logo_path.'/'.$logo->logo_image)}}" alt="Logo">
                            @else
                              <img src="{{ asset('images/logo.png')}}" alt="Logo">
                            @endif
                          </a>
                            </div>
                        </div>
                        <div class="header-search col-xl-7 col-lg-4 col-12 d-none d-lg-block">
                            <div class="search-header-w">
                                <div class="btn btn-search-mobi hidden" >
                                  <i class="fa fa-search"></i>
                                </div>
                                <div class="form_search">
                                  {{ Form::open(array('url' => 'main_search', 'method'=>'GET','class'=>'formSearch','files' => true)) }}
                                      <input type="hidden" name="type" value="product">
                                      <input class="form-control" type="search" name="main_srh" value="" placeholder="Enter keywords here... " autocomplete="off" />
                                      <button class="btn btn-search" type="submit" >
                                        <span class="btnSearchText hidden">Search</span>
                                        <i class="fa fa-search"></i>
                                      </button>
                                  {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                        <div class="middle-right col-xl-3 col-lg-5 d-none d-lg-block">
                            <ul>
                                <li>
                                  <div class="widget_text pull-right">
                                      <div class="header-login">
                                          <!-- <span class="icon-user wiseer"> <i class="fa fa-user"></i></span> -->
                                        <?php 
                                            $value = session()->get('user'); 
                                        ?>
                                        <span class="icon-user wiseer"> 
                                            @if($value)
                                                @if($value->user_type == 4)
                                                    @if($value->profile_img)
                                                        <img src="{{ asset($prof_file_path.'/'.$value->profile_img)}}" class="gj_prof_imgs img-responsive"> 
                                                    @else
                                                        <img src="{{ asset('images/site_img/default_profile.jpg')}}" class="gj_prof_imgs img-responsive"> 
                                                    @endif
                                                @else
                                                    <img src="{{ asset('images/site_img/default_profile.jpg')}}" class="gj_prof_imgs img-responsive"> 
                                                @endif
                                            @else
                                                <img src="{{ asset('images/site_img/default_profile.jpg')}}" class="gj_prof_imgs img-responsive"> 
                                            @endif
                                        </span>
                                          <span class="form-login">
                                            @if($value)
                                                @if($value->user_type == 4)
                                                    <a href="{{ route('my_account') }}"><span class="wellcome">{{$value->first_name}}</span></a><br>
                                                    <span class="login">
                                                        <a href="{{ route('logout') }}"  id="customer_login_link">Logout</a>
                                                    </span>
                                                @else
                                                    <span class="wellcome">Welcome Guest</span><br>
                                                    <span class="login">
                                                        <a href="{{ route('signin') }}"  id="customer_login_link">Login</a> / <a href="{{ route('signup') }}"  id="customer_register_link">Register</a>
                                                    </span>
                                                @endif
                                            @else
                                              <span class="wellcome">Welcome Guest</span><br>
                                              <span class="login">
                                                <a href="{{ route('signin') }}"  id="customer_login_link">Login</a> / <a href="{{ route('signup') }}"  id="customer_register_link">Register</a>
                                              </span>
                                            @endif
                                          </span>
                                      </div>
                                  </div>
                                </li>
                                <li>
                                  <div class="minicart-header">
                                    <a href="#" class="site-header__carts shopcart dropdown-toggle font-ct">
                                      <span class="cart_ico wisez">
                                        <i class="fa fa-shopping-cart"></i>
                                        <?php  
                                          $users = session()->get('user');
                                        ?>
                                        @if($users)
                                          @if ($users->user_type == 4)
                                            <?php  
                                              $carts = \DB::table('carts')->Where('user_id', $users->id)->get()->toArray();
                                            ?>
                                            @if(count($carts) != 0)
                                              <?php  
                                              $tot_qty = \DB::table('carts')->selectRaw('sum(qty) as tot_qty')->Where('user_id', $users->id)->get();
                                              $tot_pce = \DB::table('carts')->selectRaw('sum(price) as tot_pce')->Where('user_id', $users->id)->get();
                                              ?>
                                              <!-- <span id="CartCount" class="cout_cart font-ct">{{--$tot_qty[0]->tot_qty--}} <span class="hidden">item - </span></span> -->
                                              <span id="CartCount" class="cout_cart font-ct">{{count($carts)}} <span class="hidden">item - </span></span>
                                            @else
                                              <span id="CartCount" class="cout_cart font-ct">0 <span class="hidden">item - </span></span>
                                            @endif
                                          @elseif(Session::has('cart'))
                                            <?php
                                            // $cart_total = Session::get('cart_total');
                                            $cart_total = Session::get('cart');
                                            if(count($cart_total) != 0) { ?>
                                              <!-- <span id="CartCount" class="cout_cart font-ct">{{--$cart_total['tot_qty']--}} <span class="hidden">item - </span></span> -->
                                              <span id="CartCount" class="cout_cart font-ct">{{count($cart_total)}} <span class="hidden">item - </span></span>
                                            <?php } else { ?>
                                              <span id="CartCount" class="cout_cart font-ct">0 <span class="hidden">item - </span></span>
                                            <?php } ?>
                                          @else
                                            <span id="CartCount" class="cout_cart font-ct">0 <span class="hidden">item - </span></span>
                                          @endif
                                        @elseif(Session::has('cart'))
                                          <?php
                                          // $cart_total = Session::get('cart_total');
                                          $cart_total = Session::get('cart');
                                          if(count($cart_total) != 0) { ?>
                                            <!-- <span id="CartCount" class="cout_cart font-ct">{{--$cart_total['tot_qty']--}} <span class="hidden">item - </span></span> -->
                                            <span id="CartCount" class="cout_cart font-ct">{{count($cart_total)}} <span class="hidden">item - </span></span>
                                          <?php } else { ?>
                                            <span id="CartCount" class="cout_cart font-ct">0 <span class="hidden">item - </span></span>
                                          <?php } ?>
                                        @else
                                          <span id="CartCount" class="cout_cart font-ct">0 <span class="hidden">item - </span></span>
                                        @endif
                                      </span>
                                      <span class="cart_info">
                                        <span class="cart-title"><span class="title-cart">My Cart</span></span> 
                                      </span>
                                    </a> 
                                    <div class="block-content dropdown-content" style="display: none;">
                                      @if($users)
                                        @if ($users->user_type == 4)
                                          <?php  
                                            $carts = \DB::table('carts')->Where('user_id', $users->id)->get()->toArray();
                                          ?>
                                          @if(count($carts) != 0)
                                            <div class="gj_cart_res_tabl table-responsive">
                                              <table class="table table-stripped table-bordered">
                                                <tbody>
                                                  @foreach($carts as $key => $value)
                                                  <tr>
                                                    <td>{{$value->name}}</td>
                                                    <td>
                                                      <?php if($value->image) {
                                                        echo '<img class="img-responsive gj_cart_thumb"src="'.asset($product_path.'/'.$value->image).'" alt="'.$value->name.'">';
                                                      } else {
                                                        echo '<img src="'.asset($noimage_path.'/'.$noimage->product_no_image).'" alt="No Images" class="img-responsive gj_cart_thumb">';
                                                      } ?>
                                                    </td>
                                                    <td class="gj_cp"><i class="fa fa-inr"></i> {{$value->product_cost}}</td>
                                                    <td><button type="button" data-id="{{$value->product_id}}" data-cart-id="{{$value->id}}"  data-cart-key="{{$value->cart_key}}"  data-cart-del="{{$value->cart_del}}" class="gj_cart_tabl_del btn btn-dander"><i class="fa fa-trash"></i></button></td>
                                                  </tr>
                                                  @endforeach
                                                  <tr>
                                                    <td colspan="4">
                                                      <div class="bottom-action actions">
                                                        <div class="button-wrapper">
                                                          <a href="{{ route('cart') }}" class="link-button btn-gotocart" title="View your cart">View cart</a>
                                                          <a href="{{ route('checkout') }}" class="link-button btn-checkout" title="Checkout">Checkout</a>
                                                          <div style="clear:both;"></div>
                                                       </div>
                                                     </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </div>
                                          @else
                                            <div class="no-items">
                                              <p>Your cart is currently empty.</p>
                                              <p class="text-continue btn"><a href="{{ route('home') }}">Continue Shopping</a></p>
                                            </div>
                                          @endif
                                        @elseif(Session::has('cart'))
                                          <?php
                                          $cart = Session::get('cart');
                                          if(count($cart) != 0) { ?>
                                            <div class="gj_cart_res_tabl table-responsive">
                                              <table class="table table-stripped table-bordered">
                                                <tbody>
                                                  @foreach($cart as $key => $value)
                                                  <tr>
                                                    <td>{{$value['name']}}</td>
                                                    <td>
                                                      <?php if($value['image']) {
                                                        echo '<img class="img-responsive gj_cart_thumb"src="'.asset($product_path.'/'.$value['image']).'" alt="'.$value['name'].'">';
                                                      } else {
                                                        echo '<img src="'.asset($noimage_path.'/'.$noimage->product_no_image).'" alt="No Images" class="img-responsive gj_cart_thumb">';
                                                      } ?>
                                                    </td>
                                                    <td class="gj_cp"><i class="fa fa-inr"></i> {{$value['price']}}</td>
                                                    <td><button type="button" data-id="{{$value['product_id']}}" data-cart-id="0"  data-cart-key="{{(isset($value['cart_key']) ? $value['cart_key'] : '')}}"  data-cart-del="{{(isset($value['cart_del']) ? $value['cart_del'] : '')}}" class="gj_cart_tabl_del btn btn-dander"><i class="fa fa-trash"></i></button></td>
                                                  </tr>
                                                  @endforeach
                                                  <tr>
                                                    <td colspan="4">
                                                      <div class="bottom-action actions">
                                                        <div class="button-wrapper">
                                                          <a href="{{ route('cart') }}" class="link-button btn-gotocart" title="View your cart">View cart</a>
                                                          <a href="{{ route('checkout') }}" class="link-button btn-checkout" title="Checkout">Checkout</a>
                                                          <div style="clear:both;"></div>
                                                       </div>
                                                     </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </div>
                                          <?php } else { ?>
                                            <div class="no-items">
                                              <p>Your cart is currently empty.</p>
                                              <p class="text-continue btn"><a href="{{ route('home') }}">Continue Shopping</a></p>
                                            </div>
                                          <?php } ?>
                                        @else
                                          <div class="no-items">
                                            <p>Your cart is currently empty.</p>
                                            <p class="text-continue btn"><a href="{{ route('home') }}">Continue Shopping</a></p>
                                          </div>
                                        @endif
                                      @elseif(Session::has('cart'))
                                        <?php
                                        $cart = Session::get('cart');
                                        if(count($cart) != 0) { ?>
                                          <div class="gj_cart_res_tabl table-responsive">
                                            <table class="table table-stripped table-bordered">
                                              <tbody>
                                                @foreach($cart as $key => $value)
                                                <tr>
                                                  <td>{{$value['name']}}</td>
                                                  <td>
                                                    <?php if($value['image']) {
                                                      echo '<img class="img-responsive gj_cart_thumb"src="'.asset($product_path.'/'.$value['image']).'" alt="'.$value['name'].'">';
                                                    } else {
                                                      echo '<img src="'.asset($noimage_path.'/'.$noimage->product_no_image).'" alt="No Images" class="img-responsive gj_cart_thumb">';
                                                    } ?>
                                                  </td>
                                                  <td class="gj_cp"><i class="fa fa-inr"></i> {{$value['price']}}</td>
                                                  <td><button type="button" data-id="{{$value['product_id']}}" data-cart-id="0"  data-cart-key="{{(isset($value['cart_key']) ? $value['cart_key'] : '')}}"  data-cart-del="{{(isset($value['cart_del']) ? $value['cart_del'] : '')}}" class="gj_cart_tabl_del btn btn-dander"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                  <td colspan="4">
                                                    <div class="bottom-action actions">
                                                      <div class="button-wrapper">
                                                        <a href="{{ route('cart') }}" class="link-button btn-gotocart" title="View your cart">View cart</a>
                                                        <a href="{{ route('checkout') }}" class="link-button btn-checkout" title="Checkout">Checkout</a>
                                                        <div style="clear:both;"></div>
                                                     </div>
                                                   </div>
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        <?php } else { ?>
                                          <div class="no-items">
                                            <p>Your cart is currently empty.</p>
                                            <p class="text-continue btn"><a href="{{ route('home') }}">Continue Shopping</a></p>
                                          </div>
                                        <?php } ?>
                                      @else
                                        <div class="no-items">
                                          <p>Your cart is currently empty.</p>
                                          <p class="text-continue btn"><a href="{{ route('home') }}">Continue Shopping</a></p>
                                        </div>
                                      @endif
                                    </div>
                                  </div>
                                </li>
                                <li>
                                  @if($users)
                                    @if ($users->user_type == 4)
                                      <?php  
                                        $wishs = \DB::table('wish_lists')->Where('user_id', $users->id)->get();
                                      ?>
                                      @if(count($wishs) != 0)
                                        <div class="wishl">
                                          <a href="{{ route('wishlist') }}">
                                            <span> {{count($wishs)}} </span>
                                            <i class="fa fa-heart"></i>
                                          </a>
                                        </div>
                                      @else
                                        <div class="wishl">
                                          <span> 0 </span>
                                          <i class="fa fa-heart"></i>
                                        </div>
                                      @endif
                                    @else
                                      <div class="wishl">
                                        <span> 0 </span>
                                        <i class="fa fa-heart"></i>
                                      </div>
                                    @endif
                                  @else
                                    <div class="wishl">
                                      <span> 0 </span>
                                      <i class="fa fa-heart"></i>
                                    </div>
                                  @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                  </div>
              </div>

              <div class="header-mobile d-lg-none">
                  <div class="container">
                    <div class="d-flex justify-content-between">
                        <div class="logo-mobiles">
                            <div class="site-header-logo title-heading">
                                <a href="{{ route('home') }}" itemprop="url" class="site-header-logo-image">
                            @if($logo)
                              <img src="{{ asset($logo_path.'/'.$logo->logo_image)}}" alt="Logo">
                            @else
                              <img src="{{ asset('images/logo.png')}}" alt="Logo">
                            @endif
                          </a>
                            </div>
                        </div>
                        <div class="group-nav">
                            <div class="group-nav__ico group-nav__menu">
                                <div class="mob-menu">
                                  <i class="material-icons">&#xE8FE;</i>
                                </div>
                            </div>
                            <div class="group-nav__ico group-nav__search no__at">
                                <div class="btn-search-mobi dropdown-toggle">
                                  <i class="material-icons">&#xE8B6;</i>
                                </div>
                                <div class="form_search dropdown-content" style="display: none;">
                                  {{ Form::open(array('url' => 'main_search', 'method'=>'GET','class'=>'formSearch','files' => true)) }}
                                    <input type="hidden" name="type" value="product">
                                    <input class="form-control" type="search" name="main_srh" value="" placeholder="Enter keywords here... " autocomplete="off" />
                                    <button class="btn btn-search" type="submit" >
                                      <span class="btnSearchText hidden">Search</span>
                                      <i class="fa fa-search"></i>
                                    </button>
                                  {{ Form::close() }}
                                </div>
                            </div>
                            <div class="group-nav__ico group-nav__account no__at">
                                <a href="#" class="dropdown-toggle">
                                  <i class="material-icons">&#xE7FF;</i>
                                </a>
                                <ul class="dropdown-content dropdown-menu sn">
                                  <?php 
                                      $value = session()->get('user'); 
                                  ?>
                                  @if($value)
                                      @if($value->user_type == 4)
                                        <li class="s-login">
                                          <a href="{{ route('my_account') }}"><i class="fa fa-user"></i></a>
                                          <a href="{{ route('logout') }}"  id="customer_login_link">Logout</a>
                                        </li>

                                        <li>
                                          <a href="{{ route('wishlist') }}" title="My Wishlist">
                                            <i class="fa fa-heart"></i>My Wishlist
                                          </a>
                                        </li>

                                        <li>
                                          <a href="{{ route('checkout') }}" title="Checkout">
                                            <i class="fa fa-external-link-square" aria-hidden="true"></i>Checkout
                                          </a>
                                        </li>
                                      @else
                                        <li class="s-login">
                                          <i class="fa fa-user"></i>
                                          <a href="{{ route('signin') }}"  id="customer_login_link">Login</a>
                                        </li>

                                        <li>
                                          <a href="{{ route('signin') }}" title="My Wishlist">
                                            <i class="fa fa-heart"></i>My Wishlist
                                          </a>
                                        </li>

                                        <li>
                                          <a href="{{ route('signin') }}" title="Checkout">
                                            <i class="fa fa-external-link-square" aria-hidden="true"></i>Checkout
                                          </a>
                                        </li>
                                      @endif
                                  @else
                                    <li class="s-login">
                                      <i class="fa fa-user"></i>
                                      <a href="{{ route('signin') }}"  id="customer_login_link">Login</a>
                                    </li>

                                    <li>
                                      <a href="{{ route('signin') }}" title="My Wishlist">
                                        <i class="fa fa-heart"></i>My Wishlist
                                      </a>
                                    </li>

                                    <li>
                                      <a href="{{ route('signin') }}" title="Checkout">
                                        <i class="fa fa-external-link-square" aria-hidden="true"></i>Checkout
                                      </a>
                                    </li>
                                  @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                  </div>
              </div>

              <div class="header-bottom compad_hidden">
                  <div class="container">
                    <div class="wrap">
                        <div class="row">
                            <div class="vertical_menu col-xl-2 col-lg-3 col-12">
                                <div id="shopify-section-ss-vertical-menu" class="shopify-section">
                                  <div class="widget-verticalmenu">
                                      <div class="vertical-content">
                                          <div class="navbar-vertical">
                                              <button style="background: #ff5c00" type="button" id="show-verticalmenu" class="navbar-toggles">
                                                <i class="fa fa-bars"></i>
                                                <span class="title-nav">ALL CATEGORIES</span>
                                              </button>
                                          </div>
                                          <div class="vertical-wrapper">
                                              <div class="menu-remove d-block d-lg-none">
                                                <div class="close-vertical"><i class="material-icons">&#xE14C;</i></div>
                                              </div>
                                              <ul class="vertical-group">
                                      @if(count($main_cat) != 0)
                                          @foreach($main_cat as $key => $value)
                                        <li class="vertical-item level1 toggle-menu vertical_drop mega_parent">
                                            <a class="menu-link" href="{{ route('sub_category', ['main_cat' => $value->id]) }}">
                                              <span class="icon_items"><i class="fa {{$value->main_cat_icon}}"></i></span>
                                              <span class="menu-title">{{$value->main_cat_name}}</span>
                                              <span class="caret"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                                  </a>
                                              @if(count($value->sub_cat) != 0)
                                                  <div class="vertical-drop drop-mega drop-lv1 sub-menu " style="width: 720px;">
                                                      <div class="row">
                                                          <div class="ss_megamenu_col col_menu col-lg-12">
                                                              <div class="row">
                                                                  @foreach($value->sub_cat as $keys => $values)
                                                          <div class="ss_megamenu_col col-lg-6">
                                                              <ul class="content-links">
                                                                <li class="ss_megamenu_lv2 menuTitle">
                                                                  <a class="{{$values->sub_cat_name}}" href="{{ route('sub_sub_category', ['sub_cat' => $values->sub_cat_id]) }}" title="">{{$values->sub_cat_name}}</a> 
                                                                </li>
                                                                @if(count($values->sub_sub_cat) != 0)
                                                                    @foreach($values->sub_sub_cat as $keyz => $valuez)
                                                                  <li class="ss_megamenu_lv3 ">
                                                                    <a href="{{ route('sub_sub_category_products', ['sub_sub_cat' => $valuez->sub_sub_cat_id]) }}" title="">{{$valuez->sub_sub_cat_name}}</a>
                                                                  </li>
                                                                    @endforeach
                                                                @endif
                                                              </ul>
                                                        </div>
                                                                  @endforeach
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              @endif
                                        </li>
                                          @endforeach
                                      @endif
                                              </ul>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="vertical-screen d-block d-lg-none">&nbsp;</div>
                                </div>
                            </div>
                            <div class="horizontal_menu col-xl-10 col-lg-9">
                                <div id="shopify-section-ss-mainmenu" class="shopify-section">
                                  <div class="main-megamenu d-none d-lg-block">
                                      <nav class="main-wrap">
                                          <ul class="main-navigation nav hidden-tablet hidden-sm hidden-xs">
                                              <li class="ss_menu_lv1   arrow active">      
                                                <a href="{{ route('home') }}" title="">
                                                  <span class="ss_megamenu_title">Home</span>
                                                </a>
                                              </li>
                                              <li class="ss_menu_lv1  arrow">      
                                                <a href="{{ route('all_products') }}" title="">
                                                  <span class="ss_megamenu_title">Collections</span>
                                                </a>
                                              </li>
                                              <li class="ss_menu_lv1 menu_item menu_item_drop arow">
                                                <a href="{{ route('all_products') }}" title="">
                                                  <span class="ss_megamenu_title">Shop</span>
                                                </a>
                                                <div class="hidden ss_megamenu_dropdown megamenu_dropdown width-custom left " style="width:840px;margin-left: 0 !important;">
                                                    <div class="row">
                                                        <div class="ss_megamenu_col col_menu col-sm-12">
                                                            <div class="ss_inner">
                                                              <div class="row">
                                                                  <div class="ss_megamenu_col col-md-3">
                                                                      <ul class="menulink">
                                                                          <li class="ss_megamenu_lv2 megatitle">
                                                                            <a href="#" title="">Electronics</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Apple</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Fashion & Accessories</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Dell</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Scanners</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Toshiba</a>
                                                                          </li>
                                                                      </ul>
                                                                  </div>
                                                                  <div class="ss_megamenu_col col-md-3">
                                                                      <ul class="menulink">
                                                                          <li class="ss_megamenu_lv2 megatitle">
                                                                            <a href="#" title="">Health & Beauty</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Web Cameras</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Diam sit</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Monitors</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Labore et</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Scanners</a>
                                                                          </li>
                                                                      </ul>
                                                                  </div>
                                                                  <div class="ss_megamenu_col col-md-3">
                                                                      <ul class="menulink">
                                                                          <li class="ss_megamenu_lv2 megatitle">
                                                                            <a href="#" title="">Iwatch & Accessories  </a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Lamp & Lighting</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Health & Beauty</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Sofa & Chairs</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Furniture & Decors</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Sound & Life  </a>
                                                                          </li>
                                                                      </ul>
                                                                  </div>
                                                                  <div class="ss_megamenu_col col-md-3">
                                                                      <ul class="menulink">
                                                                          <li class="ss_megamenu_lv2 megatitle">
                                                                            <a href="#" title="">Technology</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Bags & Shoes</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Smartphone</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Appliances</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Fashion</a>
                                                                          </li>
                                                                          <li class="ss_megamenu_lv3 ">
                                                                            <a href="#" title="">Furniture & Decor</a>
                                                                          </li>
                                                                      </ul>
                                                                  </div>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                              </li>

                                              <li class="ss_menu_lv1 menu_item menu_item_drop menu_item_css arrow">
                                                <a href="#" class="ss_megamenu_head" title="">
                                                  <span class="ss_megamenu_title">Brands</span>
                                                </a>
                                                <ul class="ss_megamenu_dropdown dropdown_lv1">
                                                  <li class="ss_megamenu_lv2 ">
                                                      <input type="text" name="auto_brand" class="gj_auto_brand" placeholder="Enter Brands Here">
                                                  </li>
                                                  <div class="gj_auto_br_div">
                                                  @if(($brands) && (count($brands) != 0))
                                                      @foreach($brands as $key => $value)
                                                          <li class="ss_megamenu_lv2 ">
                                                              <a href="{{ route('brands_products', ['id' => $value->id]) }}" title="{{$value->brand_name}}">{{$value->brand_name}}</a>
                                                          </li>
                                                      @endforeach
                                                  @else
                                                      <li class="ss_megamenu_lv2 ">
                                                          <a href="#" title="No Brands">No Brands</a>
                                                      </li> 
                                                  @endif
                                                  </div>
                                                </ul>
                                              </li>
                                          </ul>
                                      </nav>
                                  </div>
                                  <div class="navigation-mobile mobile-menu d-block d-lg-none">
                                      <div class="logo-nav">
                                          <a href="{{ route('home') }}" itemprop="url" class="site-header-logo-image">
                                            @if($logo)
                                              <img src="{{ asset($logo_path.'/'.$logo->logo_image)}}" alt="Logo">
                                            @else
                                              <img src="{{ asset('images/logo.png')}}" alt="Logo">
                                            @endif
                                          </a>
                                          <div class="menu-remove">
                                              <div class="close-megamenu"><i class="material-icons">clear</i></div>
                                          </div>
                                      </div>
                                      <ul class="site_nav_mobile active_mobile">
                                          <li class="menu-item toggle-menu active ">
                                              <a href="{{ route('home') }}" title="" class="ss_megamenu_title"> Home </a>
                                          </li>
                                          <li class="menu-item toggle-menu">
                                              <a href="{{ route('all_products') }}" title="" class="ss_megamenu_title"> Collections </a>
                                          </li>
                                          <li class="menu-item toggle-menu active ">
                                              <a href="#" title="" class="ss_megamenu_title"> Brands <span class="caret"><i class="fa fa-angle-down" aria-hidden="true"></i></span> </a>
                                              <div class="sub-menu">
                                                <div class="row">
                                                    <div class="col-sm-12 col-12 spaceMega">
                                                        <div class="row">
                                                            <div class="col-12">
                                                              <ul class="ss_megamenu_dropdown dropdown_lv1">
                                                                <li class="ss_megamenu_lv2 ">
                                                                    <input type="text" name="auto_brand" class="gj_auto_brand" placeholder="Enter Brands Here">
                                                                </li>
                                                                <div class="gj_auto_br_div">
                                                                @if(($brands) && (count($brands) != 0))
                                                                    @foreach($brands as $key => $value)
                                                                        <li class="ss_megamenu_lv2 ">
                                                                            <a href="{{ route('brands_products', ['id' => $value->id]) }}" title="{{$value->brand_name}}">{{$value->brand_name}}</a>
                                                                        </li>
                                                                    @endforeach
                                                                @else
                                                                    <li class="ss_megamenu_lv2 ">
                                                                        <a href="#" title="No Brands">No Brands</a>
                                                                    </li> 
                                                                @endif
                                                                </div>
                                                              </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                              </div>
                                          </li>
                                          <li class="menu-item toggle-menu">
                                              <a href="{{ route('all_products') }}" title="" class="ss_megamenu_title"> Shop </a>
                                          </li>

                                          <li class="menu-item toggle-menu">
                                              <a href="{{ route('sell_on_ecambiar') }}" title="" class="ss_megamenu_title"> Sell on Ecambiar </a>
                                          </li>

                                          <li class="menu-item toggle-menu">
                                              <a href="{{ route('offer_products') }}" title="" class="ss_megamenu_title"> Offers </a>
                                          </li>
                                      </ul>
                                  </div>
                                  <div class="mobile-screen d-block d-lg-none">&nbsp;</div>
                                </div>
                                <div class="minilink-header d-none d-xl-block">
                                  <div class="pull-right">
                                      <ul id="menu-special-offer" class="menu">
                      <li class="menu-special-offer"><a class="item-link sellzz " href="{{ route('sell_on_ecambiar') }}"><span class="menu-title sellz"> Sell on Ecambiar </span></a></li>
                                          <li class="menu-special-offer"><a class="item-link" href="{{ route('offer_products') }}"><span class="menu-title shik">  offers</span></a></li>
                                      </ul>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
              </div>
          </div>
    </header>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="gj_msg">
            @if(Session::has('message'))
              <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
    @yield('content')
    <div id="shopify-section-footer" class="shopify-section">
      <footer data-section-id="footer" data-section-type="header-section" class="site-footer clearfix">
          <div class="footer-1"  style="background: #f5f5f5">
              <div class="footer-top">
                  <div class="container">
                    <div class="row">
                      @if($foo_left_offer)
                        @if($foo_left_offer->ad_image)
                            @if(($nw_date >= $st_date1) && ($nw_date < $en_date1))
                              <div class="col-xl-4 col-lg-4 banners d-none d-lg-block">
                                <div class="fbanner">
                                  <a href="{{$foo_left_offer->ad_website}}" title="{{$foo_left_offer->ad_title}}" class="gj_foo_l_offer">
                                    <img src="{{ asset('frontend/images/icon-loadings.svg')}}" data-src="{{asset('images/category_advertisement/'.$foo_left_offer->ad_image)}}" class="img-payment lazyload" alt="{{$foo_left_offer->ad_title}}" data-sizes="auto">
                                  </a>
                                </div>
                              </div>
                            @endif
                        @endif
                      @endif

                      @if($foo_left_offer && $foo_right_offer)
                        @if($foo_left_offer->ad_image && $foo_right_offer->ad_image)
                          @if(($nw_date >= $st_date1) && ($nw_date < $en_date1) && ($nw_date >= $st_date2) && ($nw_date < $en_date2))
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 image-social">
                          @elseif(($nw_date >= $st_date1) && ($nw_date < $en_date1) || ($nw_date >= $st_date2) && ($nw_date < $en_date2))
                            <div class="col-xl-8 col-lg-8 col-md-8 col-12 image-social">
                          @else
                            <div class="col-xl-12 col-lg-12 col-md-12 col-12 image-social">
                          @endif
                        @elseif($foo_left_offer->ad_image || $foo_right_offer->ad_image)
                          <div class="col-xl-8 col-lg-8 col-md-8 col-12 image-social">  
                        @else
                          <div class="col-xl-12 col-lg-12 col-md-12 col-12 image-social">
                        @endif
                      @elseif($foo_left_offer || $foo_right_offer)
                        <div class="col-xl-8 col-lg-8 col-md-8 col-12 image-social">  
                      @else
                        <div class="col-xl-12 col-lg-12 col-md-12 col-12 image-social"> 
                      @endif  
                              <div class="socials-wraps" style ="background-image: url('{{ asset('frontend/images/f2-bg-social_530x100_9fb6c41a-160a-4654-99c9-5baca1b954ae_530x1005a60.png')}}');background-size: cover;background-repeat: no-repeat;" >
                                  <ul>
                                    <li class="facebook"><a class="_blank" href="{{ $social ? ($social->facebook_page_url ? $social->facebook_page_url : '#') : '#' }}" target="_blank"><i class="fa fa-facebook"></i></a></li>

                                    <li class="twitter"><a class="_blank" href="{{ $social ? ($social->twitter_page_url ? $social->twitter_page_url : '#') : '#' }}" target="_blank"><i class="fa fa-twitter"></i></a></li>

                                    <li class="linkedin"><a class="_blank" href="{{ $social ? ($social->linkedin_page_url ? $social->linkedin_page_url : '#') : '#' }}" target="_blank"><i class="fa fa-linkedin"></i></a></li>

                                    <li class="instagram"><a class="_blank" href="{{ $social ? ($social->instagram_url ? $social->instagram_url : '#') : '#' }}" target="_blank"><i class="fa fa-instagram"></i></a></li>

                                    <li class="pinterest"><a class="_blank" href="{{ $social ? ($social->pinterest_url ? $social->pinterest_url : '#') : '#' }}" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                                  </ul>
                              </div>
                            </div>
                      @if($foo_right_offer)
                        @if($foo_right_offer->ad_image)
                            @if(($nw_date >= $st_date2) && ($nw_date < $en_date2))
                              <div class="col-xl-4 col-lg-4 banners d-none d-lg-block">
                                <div class="fbanner">
                                  <a href="{{$foo_right_offer->ad_website}}" title="{{$foo_right_offer->ad_title}}" class="gj_foo_r_offer">
                                    <img src="{{ asset('frontend/images/icon-loadings.svg')}}" data-src="{{asset('images/category_advertisement/'.$foo_right_offer->ad_image)}}" class="img-payment lazyload" alt="{{$foo_right_offer->ad_title}}" data-sizes="auto">
                                  </a>
                                </div>
                              </div>
                            @endif
                        @endif
                      @endif
                    </div>
                  </div>
              </div>

              <div class="footer-center">
                  <div class="footer-main">
                    <div class="container">
                        <div class="footer-wrapper">
                            <div class="row">
                                <div class="col-xl-2 col-md-4 col-sm-12 col-12 ft-1">
                                  <div class="collapsed-block footer-block footer-about">
                                      <ul class="footer-block-content">
                                          <li class="logo">
                                              <a href="{{ route('home') }}" title="Ecambiar">
                                    @if($logo)
                                      <img class="img-payment lazyload" data-sizes="auto" src="{{ asset($logo_path.'/'.$logo->logo_image)}}" alt=" Ecambiar" data-src="{{ asset($logo_path.'/'.$logo->logo_image)}}" />
                                    @else
                                      <img src="{{ asset('images/logo.png')}}" alt="Logo">
                                    @endif
                                  </a>
                                          </li>
                                          <li class="phone">
                                              <span> @if($email){{$email->contact_phone1}} @else 971 925 6546 @endif</span>
                                          </li>
                                          <li class="phone">
                                              <span> @if($email){{$email->contact_phone2}} @else 0471 58669464 @endif</span>
                                          </li>
                                          <li class="email">
                                              <span>@if($email){{$email->contact_email}} @else info@ecambiar.com @endif</span>
                                          </li>
                                          <li class="email">
                                              <span>@if($email){{$email->skype_email}} @else help@ecambiar.com @endif</span>
                                          </li>
                                          <li class="globe">
                                              <span>@if($email){{$general->frontend_url}} @else 0471 58669464 @endif</span>
                                        </li>
                                      </ul>
                                  </div>
                                </div>
                                <div class="col-xl-2 col-md-4 col-sm-12 col-12 ft-2">
                                  <div class="collapsed-block footer-block">
                                      <h3 class="footer-block-title"> 
                                        @if($widget)
                                            @if($widget->footer_hd1)
                                                {{$widget->footer_hd1}}
                                            @else
                                                Quick Links
                                            @endif 
                                        @else
                                            Quick Links
                                        @endif

                                        <span class="expander"><i class="fa fa-plus"></i></span></h3>
                                      <ul class="footer-block-content">
                                            <li>
                                                <a href="{{ route('my_account') }}">My Account</a>
                                            </li>
                                            <li>
                                              <a href="{{ route('contact') }}">Contact Us</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('my_account') }}#Section4"> Track your Order</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('wishlist') }}">Wishlist</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('terms_conditions') }}">Terms & Conditions</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('how_to_find_us') }}">How to find us</a>
                                          </li>
                                        
                                      </ul>
                                  </div>
                                </div>
                                <div class="col-xl-2 col-md-4 col-sm-12 col-12 ft-2">
                                  <div class="collapsed-block footer-block">
                                      <h3 class="footer-block-title"> 
                                        @if($widget)
                                            @if($widget->footer_hd2)
                                                {{$widget->footer_hd2}}
                                            @else
                                                Services
                                            @endif 
                                        @else
                                            Services
                                        @endif

                                        <span class="expander"><i class="fa fa-plus"></i></span></h3>
                                      <ul class="footer-block-content">
                                          <!--<li>-->
                                          <!--  <a href="payment.html">Payment</a>-->
                                          <!--</li>-->
                                          <!--<li>-->
                                          <!--  <a href="delivery.html">Delivery Details</a>-->
                                          <!--</li>-->
                                            <li>
                                              <a href="{{ route('about') }}">About Ecambiar</a>
                                          </li>
                                          <li>
                                            <a href="{{ route('cart') }}">Cart</a>
                                          </li>
                                          <li>
                                            <a href="{{ route('checkout') }}">Checkout</a>
                                          </li>
                                          <!--<li>-->
                                          <!--  <a href="{{ route('privacy') }}"> Privacy Policies </a>-->
                                          <!--</li>-->
                                          <li>
                                            <a href="{{ route('disclaimer') }}"> Disclaimers </a>
                                          </li>
                                          <!-- <li>
                                            <a target="_blank" href="{{ route('home') }}/old_site_map.xml">Site Map</a>
                                          </li>

                                          <li>
                                            <a target="_blank" href="{{ route('home') }}/sitemap.xml">Site Map</a>
                                          </li> -->

                                          <li>
                                            <a target="_blank" href="{{ route('home') }}/sitemap.html">Site Map</a>
                                          </li>
                                      </ul>
                                  </div>
                                </div>
                                <div class="col-xl-2 col-md-4 col-sm-12 col-12 ft-2">
                                  <div class="collapsed-block footer-block">
                                      <h3 class="footer-block-title"> 
                                        @if($widget)
                                            @if($widget->footer_hd3)
                                                {{$widget->footer_hd3}}
                                            @else
                                                Pages
                                            @endif 
                                        @else
                                            Pages
                                        @endif

                                        <span class="expander"><i class="fa fa-plus"></i></span></h3>
                                      <ul class="footer-block-content">
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
                                <div class="col-xl-2 col-md-4 col-sm-12 col-12 ft-3">
                                  <div class="collapsed-block footer-block">
                                      <h3 class="footer-block-title"> 
                                        @if($widget)
                                            @if($widget->footer_hd4)
                                                {{$widget->footer_hd4}}
                                            @else
                                                Category products
                                            @endif 
                                        @else
                                            Category products
                                        @endif 

                                        <span class="expander"><i class="fa fa-plus"></i></span></h3>
                                      <ul class="footer-block-content">
                                                @if(($main_cat) && (count($main_cat) != 0))
                                                    <?php 
                                                    for ($i=0; $i <= 6; $i++) { 
                                                        if(isset($main_cat[$i])) { ?>
                                                            <li>
                                                               <a href="{{ route('category_products', ['main_cat' => $main_cat[$i]->id]) }}"><?php echo $main_cat[$i]->main_cat_name; ?></a>
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
                                <div class="col-xl-2 col-md-4 col-sm-12 col-12 ft-3">
                                  <div class="collapsed-block footer-block">
                                      <h3 class="footer-block-title">
                                        @if($widget)
                                            @if($widget->footer_hd5)
                                                {{$widget->footer_hd5}}
                                            @else
                                                Sign up for Newsletter
                                            @endif 
                                        @else
                                            Sign up for Newsletter
                                        @endif 
                                        
                                        <span class="expander"><i class="fa fa-plus"></i></span></h3>
                                      <div class="footer-block-content footer-newsletter">
                                            <p>
                                                @if($widget)
                                                    @if($widget->footer_nl_quotes)
                                                        {{$widget->footer_nl_quotes}}
                                                    @else
                                                        We’ll never share your email address.
                                                    @endif 
                                                @else
                                                    We’ll never share your email address.
                                                @endif 
                                            </p>
                                          <div class="newsletter">
                                              {{ Form::open(array('url' => 'news_letters', 'id'=>'contact_form','class'=>'contact-form','files' => true)) }}
                                                <div class="input-group password__input-group">
                                                    <div class="input-box">
                                                      <span class="error"> 
                                                          @if (isset($errors) && $errors->has('email'))
                                                            {{ $errors->first('email') }}
                                                          @endif
                                                      </span>
                                                        <input type="email" name="email" id="Email" class="input-group__field newsletter__input" value="" placeholder="Email address">
                                                    </div>
                                                    <span class="input-group__btn">
                                                      <button type="submit" class="btn newsletter__submit" name="commit" id="Subscribe">
                                                        <span class="newsletter__submit-text--large">Subscribe</span>
                                                      </button>
                                                    </span>
                                                </div>
                                              {{ Form::close() }}
                                          </div>
                                      </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
              </div>

              <div class="footer-bottom"  style="background:#222222">
                  <div class="container">
                    <div class="row">
                        <div class="copyright col-xl-6 col-md-12">
                            <address>© {{ Carbon\Carbon::today()->format('Y') }} <a href="{{ route('home') }}"> Ecambiar </a> All Rights Reserved.</address>
                        </div>
                        <div class="img payment-w col-xl-6 col-md-12">
                            @if($widget)
                                @if($widget->footer_pay_img && $widget->footer_pay_url)
                                    <a href="{{$widget->footer_pay_url}}" title="Ecambiar">
                                        <img class="img-payment lazyload" data-sizes="auto" src="{{ asset('frontend/images/icon-loadings.svg')}}" alt=" Ecambiar " data-src="{{ asset('images/widget/'.$widget->footer_pay_img)}}" />
                                    </a>
                                @else
                                    <a href="#" title="Ecambiar">
                                        <img class="img-payment lazyload" data-sizes="auto" src="{{ asset('frontend/images/icon-loadings.svg')}}" alt=" Ecambiar " data-src="{{ asset('frontend/images/payment.png')}}" />
                                    </a>
                                @endif
                            @else
                                <a href="#" title="Ecambiar">
                                    <img class="img-payment lazyload" data-sizes="auto" src="{{ asset('frontend/images/icon-loadings.svg')}}" alt=" Ecambiar " data-src="{{ asset('frontend/images/payment.png')}}" />
                                </a>
                            @endif
                        </div>
                    </div>
                  </div>
              </div>

              <div id="goToTop" class="hidden-xs"><span></span></div>
          </div>
      </footer>
    </div>
    
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
                            window.location.reload();
                        }
                    }
                });
                
                setTimeout(function(){ window.location.reload(); }, 3000);
              } else if(data != 1){
                $.confirm({
                    title: '',
                    content: 'Added To Cart!',
                    icon: 'fa fa-check',
                    theme: 'modern',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'green',
                    buttons: {
                        Ok: function(){
                            window.location.reload();
                        }
                    }
                });
                // setTimeout(function(){ window.location.reload(); }, 3000);
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