<?php 
$banner_path = 'images/banner_image';
$main_cat_path = 'images/main_cat_image';
$sub_cat_path = 'images/sub_cat_image';
$product_path = 'images/featured_products';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';

$all_left_off = \DB::table('category_advertisement_settings')->Where('is_block', 1)->Where('payment_status', 1)->Where('page', 'All Product Page')->Where('position', 'Bottom Left')->first();
$nw_date = date('Y-m-d');
$nw_date = date('Y-m-d', strtotime($nw_date));

if($all_left_off) {
  $st_date1 = date('Y-m-d', strtotime($all_left_off->ad_start_date));
  $en_date1 = date('Y-m-d', strtotime($all_left_off->ad_end_date));
}
        $fields=App\AttributesFields::where('is_block',1)->get();

?>
@extends('groceryView.layouts.headerFooter')
@section('title', 'All Products')
<!-- imported class start-->
<link rel="stylesheet" type="text/css" media="all" href="{{ asset('frontend/css/theme-config.css')}}">
<!-- imported class end -->
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/jquery-ui.css')}}">


@section('content')
<!-- PRODUCTS SECTION START -->
<div class="wrapper">


<div class="grocery-Breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Products</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <section class="gj_products_sec">
        <div class="gj_products_bg">
            <div class="page-container" id="PageContainer">
                <div class="main-content maxil" id="MainContent">
                    <div class="container positon-sidebar">
                        <div class="row">
                            <div class="col-sidebar sidebar-fixed col-lg-3">
                                <ul id="accordion" class="accordion">
                                    <li class="open">
                                        <div class="link"> Categories <i class="fa fa-chevron-down"></i></div>
                                        <ul class="submenu" style="display: block;">
                                            <div class="widget-content">
                                                <ul class="toggle-menu list-menu">
                                                    @if(isset($category) && (count($category) != 0))
                                                    @foreach ($category as $key => $value)
                                                    <li>
                                                        <input type="checkbox" value="{{$value->id}}" <?php if(Request::get('fil_cats')==$value->id)  echo 'checked';   ?> class="gj_id_fill_cat" data-id="{{$value->id}}">
                                                        <label>{{$value->main_cat_name}}<span class="count">( {{$value->cat_count}} )</span></label>
                                                    </li>
                                                    @endforeach
                                                    @else
                                                    <li>
                                                        <a href="#">No Category</a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </ul>
                                    </li>
                                    <li >
                                        <!--<div class="link"><label>Sort By Price</label><i class="fa fa-chevron-down"></i></div>-->
                                        <ul class="submenu">
                                            <div class="widget-content">
                                                <ul class="toggle-menu list-menu">
                                                    <li>
                                                        <div id="shopify-section-sidebar-filter-collection" class="shopify-section">
                                                            <script src="{{ asset('frontend/js/jquery.history.js') }}" type="text/javascript"></script>
                                                            <script src="{{ asset('frontend/js/ss-filter-shopby.js') }}" type="text/javascript"></script>
                                                            <div class="block anblokwiz widget-filter yt-left-wrap clearfix">
                                                                <div id="layered-navigation">
                                                                    <div class="block block-layered-nav">
                                                                        
                                                                        <div class="block-content">
                                                                            <dl id="narrow-by-list">
                                                                                <div class="filter-tags">
                                                                                    <form method="GET" action="{{route('all_filter_products')}}" accept-charset="UTF-8" class="gj_all_product_form" enctype="multipart/form-data">
                                                                                        <input type="hidden" name="fil_cats" class="gj_fil_cats" value="<?php if(isset($filter_cats)){ echo $filter_cats; } ?>">

                                                                                        <input type="hidden" name="fil_ss_cats" class="gj_fil_ss_cats" value="<?php if(isset($filter_ss_cats)){ echo $filter_ss_cats; } ?>">

                                                                                        <input type="hidden" name="fil_brnd" class="gj_fil_brnd" value="<?php if(isset($filter_brnd)){ echo $filter_brnd; } ?>">

                                                                                        <input type="hidden" name="fil_atts" class="gj_fil_atts" value="<?php if(isset($filter_atts)){ echo $filter_atts; } ?>">

                                                                                        <input type="hidden" name="fil_sort" class="gj_fil_sort" value="<?php if(isset($filter_sort)){ echo $filter_sort; } ?>">

                                                                                        <div class="Price gj_pri_rge price-lot">
                                                                                       <!-- <dd> 
                                                                                        <div class="lab1"><label for="amount">Min Price : {{$code}} </label>
                                                                                        <input type="text" name="p_amount1" id="p_amount1" style="border:0; color:#f6931f; font-weight:bold;"></div>
                                                                                        <div class="lab2"><label for="amount">Max Price : {{$code}} </label>
                                                                                        <input type="text" name="p_amount2" id="p_amount2" style="border:0; color:#f6931f; font-weight:bold;"></div>
                                                                            
                                                                                                        <div class="clearfix"> </div>
                                                                                                
                                                                                                <div id="slider-range" class="sklikopzs"></div>

                                                                                                <button type="submit" class="btn btn-info gj_p_filt">Filter</button>
                                                                                            </dd>-->
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </dl>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="clearfix clr clear"></div>
                                                        </div>   
                                                    </li>
                                                </ul>
                                            </div>
                                        </ul>
                                    </li>
                                </ul>
                                <span id="close-sidebar" class="btn-fixed d-lg-none"><i class="fa fa-times"></i></span>
                    
                                <div class="block sidebar-html">
                                    <h3 class="block-title"><span>Custom Services</span></h3>
                                    
                                    <div class="widget-content">
                                        <div class="rte-setting">
                                            <div class="services-sidebar">
                                                <ul>
                                                    @if($widget)
                                                        <li>
                                                            <div class="service-content">
                                                                <div class="service-icon" style="font-size: 30px;">
                                                                    @if($widget->first_icon)
                                                                        <em class="fa {{$widget->first_icon}}"></em>
                                                                    @else
                                                                        <em class="fa fa-truck"></em>
                                                                    @endif
                                                                </div>
                                                                <div class="service-info">
                                                                    <h4>
                                                                        @if($widget->first_url)
                                                                            <a href="{{$widget->second_url}}" title="Free Delivery">
                                                                                @if($widget->first_title)
                                                                                    {{$widget->first_title}}
                                                                                @else
                                                                                    Free Delivery
                                                                                @endif
                                                                            </a>
                                                                        @else
                                                                            <a href="#" title="Free Delivery">Free Delivery</a>
                                                                        @endif
                                                                    </h4>

                                                                    @if($widget->first_content)
                                                                        <p>{{$widget->first_content}}</p>
                                                                    @else
                                                                        <p>From  {{$code}}  500.00</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif

                                                    @if($widget)
                                                        <li>
                                                            <div class="service-content">
                                                                <div class="service-icon" style="font-size: 30px;">
                                                                    @if($widget->second_icon)
                                                                        <em class="fa {{$widget->second_icon}}"></em>
                                                                    @else
                                                                        <em class="fa fa-support"></em>
                                                                    @endif
                                                                </div>
                                                                <div class="service-info">
                                                                    <h4>
                                                                        @if($widget->second_url)
                                                                            <a href="{{$widget->second_url}}" title="Support 24/7">
                                                                                @if($widget->second_title)
                                                                                    {{$widget->second_title}}
                                                                                @else
                                                                                    Support 24/7
                                                                                @endif
                                                                            </a>
                                                                        @else
                                                                            <a href="#" title="Support 24/7">Support 24/7</a>
                                                                        @endif
                                                                    </h4>

                                                                    @if($widget->second_content)
                                                                        <p>{{$widget->second_content}}</p>
                                                                    @else
                                                                        <p>Online 24 hours</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif

                                                    @if($widget)
                                                        <li>
                                                            <div class="service-content">
                                                                <div class="service-icon" style="font-size: 30px;">
                                                                    @if($widget->third_icon)
                                                                        <em class="fa {{$widget->third_icon}}"></em>
                                                                    @else
                                                                        <em class="fa fa-refresh"></em>
                                                                    @endif
                                                                </div>
                                                                <div class="service-info">
                                                                    <h4>
                                                                        @if($widget->third_url)
                                                                            <a href="{{$widget->third_url}}" title="Free return">
                                                                                @if($widget->third_title)
                                                                                    {{$widget->third_title}}
                                                                                @else
                                                                                    Free return
                                                                                @endif
                                                                            </a>
                                                                        @else
                                                                            <a href="#" title="Free return">Free return</a>
                                                                        @endif
                                                                    </h4>

                                                                    @if($widget->third_content)
                                                                        <p>{{$widget->third_content}}</p>
                                                                    @else
                                                                        <p>365 a day</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif

                                                    @if($widget)
                                                        <li>
                                                            <div class="service-content">
                                                                <div class="service-icon" style="font-size: 30px;">
                                                                    @if($widget->fourth_icon)
                                                                        <em class="fa {{$widget->fourth_icon}}"></em>
                                                                    @else
                                                                        <em class="fa fa-cc-paypal"></em>
                                                                    @endif
                                                                </div>
                                                                <div class="service-info">
                                                                    <h4>
                                                                        @if($widget->fourth_url)
                                                                            <a href="{{$widget->fourth_url}}" title="payment method">
                                                                                @if($widget->fourth_title)
                                                                                    {{$widget->fourth_title}}
                                                                                @else
                                                                                    payment method
                                                                                @endif
                                                                            </a>
                                                                        @else
                                                                            <a href="#" title="payment method">payment method</a>
                                                                        @endif
                                                                    </h4>

                                                                    @if($widget->fourth_content)
                                                                        <p>{{$widget->fourth_content}}</p>
                                                                    @else
                                                                        <p>Secure payment</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--@if($all_left_off)
                                    @if($all_left_off->ad_image)
                                        @if(($nw_date >= $st_date1) && ($nw_date < $en_date1))
                                            <div class="block sidebar-banner spaceBlock banners">
                                                <div class="gj_all_offs">
                                                    <a href="{{$all_left_off->ad_website}}" title="{{$all_left_off->ad_title}}">
                                                        <img class="img-responsive lazyload" data-sizes="auto" src="{{asset('images/category_advertisement/'.$all_left_off->ad_image)}}" alt="{{$all_left_off->ad_title}}" data-src="{{asset('images/category_advertisement/'.$all_left_off->ad_image)}}">
                                                    </a>
                                                    
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif-->
                            </div>
                                                            
                            <div class="col-main col-lg-9 col-12">
                                <a href="javascript:void(0)" class="open-sidebar d-lg-none"><i class="fa fa-bars"></i> Sidebar</a>

                                <div id="shopify-section-collection-infos" class="shopify-section">
                                    <div class="collection-main">
                                        <div class="filters-toolbar-wrapper">
                                            <div class="filters-toolbar">
                                                <div class="row">
                                                    <div class="col col-6 col-sm-6 col-lg-6">
                                                        <a href="{{ route('all_products') }}"><h4> All Products </h4></a>
                                                    </div>
                                                    <div class="col col-6 col-sm-6 col-lg-6 ">
                                                        <div class="filters-toolbar-item filter-fiel pull-right">
                                                            <label for="SortBy" class="label-sortby hidden-xs">Sort By:</label>
                                                            <!-- <form method="get" action="{{ route('sort_filter_products') }}" class="gj_all_product_sort_form"> -->
                                                                <select name="Sort" id="Sort" class="gj_SortBy filters-toolbar__input filters-toolbar__input--sort filters-toolbar-sort">
                                                                    <option value="">Select Sorted By Items</option>
                                                                    <option <?php if(isset($filter_sort) && $filter_sort == "manual") { echo "selected"; } ?> value="manual">Featured</option>
                                                                    <option <?php if(isset($filter_sort) && $filter_sort == "best-selling") { echo "selected"; } ?> value="best-selling">Best Selling</option>
                                                                    <option <?php if(isset($filter_sort) && $filter_sort == "title-ascending") { echo "selected"; } ?> value="title-ascending">Alphabetically, A-Z</option>
                                                                    <option <?php if(isset($filter_sort) && $filter_sort == "title-descending") { echo "selected"; } ?> value="title-descending">Alphabetically, Z-A</option>
                                                                    <option <?php if(isset($filter_sort) && $filter_sort == "price-ascending") { echo "selected"; } ?> value="price-ascending">Price, low to high</option>
                                                                    <option <?php if(isset($filter_sort) && $filter_sort == "price-descending") { echo "selected"; } ?> value="price-descending">Price, high to low</option>
                                                                    <option <?php if(isset($filter_sort) && $filter_sort == "created-ascending") { echo "selected"; } ?> value="created-ascending">Date, old to new</option>
                                                                    <option <?php if(isset($filter_sort) && $filter_sort == "star-ascending") { echo "selected"; } ?> value="star-ascending"> Customer star Rating</option>
                                                                </select>
                                                            <!-- </form> -->
                                                            <input class="collection-header__default-sort" type="hidden" value="created-descending">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <div id="shopify-section-collection-template" class="shopify-section">
                                    <div data-section-id="collection-template" data-section-type="collection-template" class="products-collection">
                                        <div class="product-wrapper anborderd" id="Collection">
                                            <div class="products-listing products-grid grid row EndlessClick">
                                                @if(($all_products) && count($all_products) != 0)
                                                    @foreach($all_products as $key => $value)
                                                        <div id="product-{{$value->id}}" class="product product-layout grid__item grid__item--collection-template col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-6 grid_4" data-price="126.00">
                                                            <span class="d-none"><span class="money" data-currency-usd="&#8377; 126.00"> &#8377;126.00</span></span>
                                                            <div class="product-item" data-id="product-{{$value->id}}">
                                                                <div class="product-item-container grid-view-item   ">
                                                                    <div class="product-addto-links">
                                                                                <a class="btn_df btnProduct gj_wish_list" href="" title="Wishlist" data-wish-id="{{$value->id}}">
                                                                                    <i class="fa fa-heart gj_wish_hrt"></i>
                                                                                    <!--<span class="hidden-xs hidden-sm hidden-md">Wishlist</span>-->
                                                                                </a>
                                                                            </div>
                                                                    <div class="left-block">
                                                                        <div class="product-image-container product-image">
                                                                            <a class="grid-view-item__link image-ajax" href="{{ route('view_products', ['id' => $value->id]) }}">
                                                                                <img class="img-responsive s-img lazyautosizes lazyloaded gj_allprod_img" data-sizes="auto" src="{{ asset($product_path.'/'.$value->featured_product_img) }}" data-src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="{{$value->product_title}}" sizes="269px">
                                                                            </a>  
                                                                            <div class="box-countdown">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="right-block">
                                                                        <div class="button-link">
                                                                            
                                                                            
                                                                            <div class="qty-cart">
                                                                                <div class="quantity buttons_added">
                                                                                <input type="button" value="-" class="minus minus-btn">
                                                                                <input type="number" step="1" id="qty{{$value->id}}" name="quantity" value="1" min="1" class="input-text qty text">
                                                                                <input type="button" value="+" class="plus plus-btn">
                                                                                <input type="hidden" id="price_{{$value->id}}" name="price" value="{{$value->discounted_price}}" class="quantity-selector">

                                                                            </div>
                                                                            <div class="add-cart">
                                                                                <button class="addcartzhom gj_add2cartzsx" data-cart-id="{{$value->id}}"> Add to Cart </button>
                                                                            </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="caption">
                                                                            <div class="custom-reviews">
                                                                                <span class="spr-badge" id="spr_badge_{{$value->id}}" data-rating="0.0">
                                                                                    <span class="spr-starrating spr-badge-starrating">
                                                                                        @if(isset($value->review) && $value->review != 0)
                                                                                            <?php 
                                                                                            $r_average = round($value->review); 
                                                                                            $tot_rev = 5;
                                                                                            ?>
                                                                                            @for ($i=0; $i<$tot_rev; $i++)
                                                                                                @if($i < $r_average)
                                                                                                    <i class="fa fa-star"></i>
                                                                                                @else
                                                                                                    <i class="fa fa-star-o"></i>
                                                                                                @endif
                                                                                            @endfor
                                                                                        @endif
                                                                                    </span>
                                                                                </span>
                                                                            </div>

                                                                            <h4 class="title-product text-truncate"><a class="product-name" href="{{ route('view_products', ['id' => $value->id]) }}">{{$value->product_title}}</a></h4>
                                                                            <p class="gj_ssc_prod_dp">{{$code}}  {{$value->discounted_price}} <span class="gj_ssc_prod_op"> {{$code}} {{$value->original_price}} </span></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="product product-layout grid__item grid__item--collection-template col-xl-3 col-lg-4 col-md-4 col-sm-4 col-xs-6 grid_4">
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
                                                        {{ $all_products->appends(request()->input())->links() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sidebar-overlay"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- PRODUCTS SECTION END -->
</div>
<script src="{{ asset('assetsGrocery/js/jquery-3.3.1.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        	$(function() {
	var Accordion = function(el, multiple) {
		this.el = el || {};
		this.multiple = multiple || false;

		// Variables privadas
		var links = this.el.find('.link');
		// Evento
		links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
	}

	Accordion.prototype.dropdown = function(e) {
		var $el = e.data.el;
			$this = $(this),
			$next = $this.next();

		$next.slideToggle();
		$this.parent().toggleClass('open'); 
	}	

	var accordion = new Accordion($('#accordion'), false);
});
        /*$("form.gj_all_product_form").on("change", "input:checkbox", function(){
            $("form.gj_all_product_form").submit();
        });

        $("form.gj_all_product_sort_form").on("change", "#SortBy", function(){
            $("form.gj_all_product_sort_form").submit();
        });*/
    });

    $(".gj_ftr_attr_vls").click(function(e){
        e.preventDefault();
        var att = "";
        if($(this).attr('data-id')) {
            att = $(this).attr('data-id');
        }

        $('.gj_fil_atts').val(att);

        $('.gj_p_filt').trigger('click');
    });

    $(".gj_id_fill_cat").click(function(e){
        e.preventDefault();
        if($(this).is(':checked'))
            {
              var cat = "";
        
        if($(this).attr('data-id')) {
            cat = $(this).attr('data-id');
            $('.gj_fil_cats').val(cat);

        $('.gj_p_filt').trigger('click');
        }
            }else
            {
              var cat = "";
        
        if($(this).attr('data-id')) {
         
            $('.gj_fil_cats').val('');

        $('.gj_p_filt').trigger('click');
        }
            }
        

        
    });

    $(".gj_SortBy").change(function(e){
        e.preventDefault();
        var sort = "";
        if($(this).val()) {
            sort = $(this).val();
        }

        $('.gj_fil_sort').val(sort);

        $('.gj_p_filt').trigger('click');
    });
</script>

<script>
    $(".open-sidebar").click(function(e){
        $(".sidebar-overlay").toggleClass("show");
        $(".sidebar-fixed").toggleClass("active");
    });

    $( ".open-fiter" ).click(function() {
        $('.sidebar-fixed').slideToggle(200);
        $(this).toggleClass('active');
    });

    $(".sidebar-overlay").click(function(e){
        $(".sidebar-overlay").toggleClass("show");
        $(".sidebar-fixed").toggleClass("active");
    });

    $('#close-sidebar').click(function() {
        $('.sidebar-overlay').removeClass('show');
        $('.sidebar-fixed').removeClass('active');
    }); 
</script>

<!-- Accordian Script Start -->
<script type="text/javascript">
    var acc = document.getElementsByClassName("block-title");
    var i;

    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        $(this).closest('.block').find(".gj_ftr_attr").slideToggle();
        $(this).closest('.block').find(".widget-content").slideToggle();
        $(this).closest('.block').find(".block-content").slideToggle();
        $(this).closest('.block').find(".wrap").slideToggle();
      });
    }
</script>
<!-- Accordian Script End -->

<!-- Price Range Slider Script Start -->
<script src="{{ asset('frontend/js/jquery-ui.js')}}"></script>
<script>
    $( function() {
        var pa1 = 0;
        var pa2 = 0;
        @if(isset($filter_amount1) && $filter_amount1!='')
            pa1 = <?php echo $filter_amount1; ?>;
             pa1 = parseInt(pa1);
        @endif

        @if(isset($filter_amount2) && $filter_amount2!='')
            pa2 = <?php echo $filter_amount2; ?>;
            pa2 = parseInt(pa2);
        @endif

       
        

        $( "#slider-range" ).slider({
            range: true,
            min: 0,
            @if(isset($all_products) && sizeof($all_products) != 0)
                @if(isset($all_products->max_price) && ($all_products->max_price != 0))
                    max: <?php echo $all_products->max_price; ?>,
                @else
                    max: 500,
                @endif
            @else
                max: 500,
            @endif
            values: [ pa1, pa2 ],
            slide: function( event, ui ) {
                $( "#p_amount1" ).val(ui.values[ 0 ]);
                $( "#p_amount2" ).val(ui.values[ 1 ]);
            }
        });
        $( "#p_amount1" ).val($( "#slider-range" ).slider( "values", 0 ));
        $( "#p_amount2" ).val($( "#slider-range" ).slider( "values", 1 ));
    } );

    $('body').on('change','#p_amount1',function() {
        var p1 = 0;
        var p2 = 0;
        if($(this).val()) {
            p1 = parseInt($(this).val()); 
        } 

        if($('#p_amount2').val()) {
            p2 = parseInt($('#p_amount2').val()); 
            if(p2 <= p1) {
                p2 = p1;
            }
        }

        
        $( "#slider-range" ).slider({
            range: true,
            min: 0,
            @if(isset($all_products) && sizeof($all_products) != 0)
                @if(isset($all_products->max_price) && ($all_products->max_price != 0))
                    max: <?php echo $all_products->max_price; ?>,
                @else
                    max: 500,
                @endif
            @else
                max: 500,
            @endif
            values: [ p1, p2 ]
        });
        $( "#p_amount1" ).val($( "#slider-range" ).slider( "values", 0 ));
        $( "#p_amount2" ).val($( "#slider-range" ).slider( "values", 1 ));
    });

    $('body').on('change','#p_amount2',function() {
        var p1 = 0;
        var p2 = 0;

        if($('#p_amount1').val()) {
            p1 = parseInt($('#p_amount1').val()); 
        }
        if($(this).val()) {
            p2 = parseInt($(this).val()); 
            if(p2 <= p1) {
                p2 = p1;
            }
        } 
        
        $( "#slider-range" ).slider({
            range: true,
            min: 0,
            @if(isset($all_products) && sizeof($all_products) != 0)
                @if(isset($all_products->max_price) && ($all_products->max_price != 0))
                    max: <?php echo $all_products->max_price; ?>,
                @else
                    max: 500,
                @endif
            @else
                max: 500,
            @endif
            values: [ p1, p2 ]
        });
        $( "#p_amount1" ).val($( "#slider-range" ).slider( "values", 0 ));
        $( "#p_amount2" ).val($( "#slider-range" ).slider( "values", 1 ));
    });
</script>
<!-- Price Range Slider Script End -->
@endsection