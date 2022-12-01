<?php 

$banner_path = 'images/banner_image';
$main_cat_path = 'images/main_cat_image';
$sub_cat_path = 'images/sub_cat_image';
$sub_sub_cat_path = 'images/sub_sub_cat_image';
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
?>
@extends('layouts.frontend')
@section('title', 'All Products')

<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/jquery-ui.css')}}">

@section('content')
<!-- PRODUCTS SECTION START -->
<section class="gj_products_sec">
    <div class="gj_products_bg">
        <div class="page-container" id="PageContainer">
            <div class="main-content maxil" id="MainContent">
                <div class="container positon-sidebar">
                    <div class="row">
                        <div class="col-sidebar sidebar-fixed col-lg-3">
                            <span id="close-sidebar" class="btn-fixed d-lg-none"><i class="fa fa-times"></i></span>
                            <div class="block block-category spaceBlock">
                                <h3 class="block-title"> Categories </h3>
                                <div class="widget-content">
                                    <ul class="toggle-menu list-menu">
                                      @if(isset($category) && (count($category) != 0))
                                        @foreach ($category as $key => $value)
                                            <li>
                                                <a href="{{ route('all_cat_products', ['main_cat' => $value->id]) }}">{{$value->main_cat_name}}<span class="count">( {{$value->cat_count}} )</span></a>
                                            </li>
                                        @endforeach
                                      @else
                                        <li>
                                          <a href="#">No Category</a>
                                        </li>
                                      @endif
                                    </ul>
                                </div>
                            </div>

                            <div id="shopify-section-sidebar-filter-collection" class="shopify-section">
                                <script src="{{ asset('frontend/js/jquery.history.js') }}" type="text/javascript"></script>
                                <script src="{{ asset('frontend/js/ss-filter-shopby.js') }}" type="text/javascript"></script>
                                <div class="block widget-filter yt-left-wrap clearfix">
                                    <div id="layered-navigation">
                                        <div class="block block-layered-nav">
                                            <div class="block-title">
                                                <strong><span>Sort By Price</span></strong>
                                            </div>
                                            <div class="block-content">
                                                <dl id="narrow-by-list">
                                                    <div class="filter-tags">
                                                        <form method="GET" action="{{route('all_filter_products')}}" accept-charset="UTF-8" class="gj_all_product_form" enctype="multipart/form-data">
                                                            <div class="Price gj_pri_rge">
                                                                <dd>
                                                                    <p>
                                                                        <label for="amount">Min Price : <i class="fa fa-inr"></i> </label>
                                                                        <input type="text" name="p_amount1" id="p_amount1" style="border:0; color:#f6931f; font-weight:bold;">
                                                                        <label for="amount">Max Price : <i class="fa fa-inr"></i> </label>
                                                                        <input type="text" name="p_amount2" id="p_amount2" style="border:0; color:#f6931f; font-weight:bold;">
                                                                    </p>
                                                                     
                                                                    <div id="slider-range"></div>

                                                                    <button type="submit" class="btn btn-info gj_p_filt">Filter</button>
                                                                </dd>
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

                            <div id="sshopify-section-sidebar-filter-collection" class="shopify-section">
                                <div class="block widget-filter yt-left-wrap clearfix">
                                    <div id="layered-navigation">
                                        <div class="block block-layered-nav">
                                            <div class="block-title">
                                                <strong><span>Attributes</span></strong>
                                            </div>
                                            <div class="block-content">
                                                <dl id="narrow-by-list">
                                                    <div class="filter-tags">
                                                        <!-- <form method="POST" action="{{--route('value_filter_products')--}}" accept-charset="UTF-8" class="gj_value_filter_products_form" enctype="multipart/form-data"> -->
                                                            <div class="gj_ftr_attr">
                                                                @if (isset($attributes) && count($attributes) != 0)
                                                                    @foreach ($attributes as $ky => $vl)
                                                                        @if($vl->id && $vl->id != 0)
                                                                            <a href="{{ route('value_filter_products', ['id' => $vl->id]) }}" data-id = "{{$vl->id}}" class="gj_ftr_attr_vls">
                                                                                <p>{{$vl->AttributesFields->att_name}} - {{$vl->att_value}}</p>
                                                                                <input type="hidden" name="att_value" class="gj_h_att_value" value="{{$vl->id}}">
                                                                            </a>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        <!-- </form> -->
                                                    </div>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix clr clear"></div>
                            </div>

                            <div class="block widget-prd-featured best-seller spaceBlock">
                                <h3 class="block-title"><strong><span>Featured Products</span></strong></h3>

                                <div class="wrap">
                                    @if(($featured_products) && count($featured_products) != 0)
                                        @foreach($featured_products as $key => $value)
                                            <div class="product-item clearfix on-sale">
                                                <a href="{{ route('view_products', ['id' => $value->id]) }}" class="product-img">
                                                    <img class="lazyautosizes lazyloaded" data-sizes="auto" src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="{{$value->product_title}}" sizes="85px">
                                                </a>

                                                <div class="product-info">
                                                    <a href="{{ route('view_products', ['id' => $value->id]) }}" title="{{$value->product_title}}" class="product-name"> {{$value->product_title}}</a>

                                                    <div class="price">
                                                        <p class="gj_ssc_prod_dp"><i class="fa fa-inr"></i>  {{$value->discounted_price}}<span class="gj_ssc_prod_op"><i class="fa fa-inr"></i> {{$value->original_price}}</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="product-item clearfix on-sale">
                                            <a href="#"><img src="{{ asset($noimage_path.'/'.$noimage->product_no_image) }}" alt="No Images" class="lazyautosizes lazyloaded" data-sizes="auto" sizes="85px"></a>

                                            <div class="product-info">
                                                <a href="#" title="No products" class="product-name"> Products Not Available</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="block sidebar-html">
                                <h3 class="block-title"><strong><span>Custom Services</span></strong></h3>
                                
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
                                                                    <p>From  <i class="fa fa-inr"></i>  500.00</p>
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

                            @if($all_left_off)
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
                            @endif
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
                                                        <form method="get" action="{{ route('sort_filter_products') }}" class="gj_all_product_sort_form">
                                                            <select name="SortBy" id="SortBy" class="filters-toolbar__input filters-toolbar__input--sort filters-toolbar-sort">
                                                                <option value="0">Select Sorted By Items</option>
                                                                <option value="manual">Featured</option>
                                                                <option value="best-selling">Best Selling</option>
                                                                <option value="title-ascending">Alphabetically, A-Z</option>
                                                                <option value="title-descending">Alphabetically, Z-A</option>
                                                                <option value="price-ascending">Price, low to high</option>
                                                                <option value="price-descending">Price, high to low</option>
                                                                <option value="created-ascending">Date, old to new</option>
                                                                <option value="star-ascending"> Customer star Rating</option>
                                                            </select>
                                                        </form>
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
                                    <div class="product-wrapper" id="Collection">
                                        <div class="products-listing products-grid grid row EndlessClick">
                                            @if(($sub_sub_cat) && count($sub_sub_cat) != 0)
                                                @foreach($sub_sub_cat as $key => $value)
                                                    <div class="col-md-3 col-sm-4 col-xs-6">
                                                        <div class="gj_whole_mcat">
                                                            <div class="gj_mcat_img_div">
                                                                <a href="{{ route('sub_sub_category_products', ['sub_sub_cat' => $value->sub_sub_cat_id]) }}"><img src="{{ asset($sub_sub_cat_path.'/'.$value->sub_sub_cat_image) }}" alt="{{$value->sub_sub_cat_name}}" class="img-responsive gj_mcat_img"></a>

                                                            </div>
                                                            <a href="{{ route('sub_sub_category_products', ['sub_sub_cat' => $value->sub_sub_cat_id]) }}"><p class="gj_mcat_img_hd">{{$value->sub_sub_cat_name}}</p></a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-md-3 col-sm-4 col-xs-6">
                                                    <div class="gj_whole_ssc_prod">
                                                        <div class="gj_ssc_prod_img_div">
                                                            <a href="#"><img src="{{ asset($noimage_path.'/'.$noimage->category_no_image) }}" alt="No Images" class="img-responsive gj_ssc_prod_img"></a>

                                                        </div>
                                                        <a href="#"><p class="gj_ssc_prod_img_hd">Category Not Available</p></a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="gj_pagination">
                                                    {{ $sub_sub_cat->appends(request()->input())->links() }}
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

<script type="text/javascript">
    $(document).ready(function() {
        $("form.gj_all_product_form").on("change", "input:checkbox", function(){
            $("form.gj_all_product_form").submit();
        });

        $("form.gj_all_product_sort_form").on("change", "#SortBy", function(){
            $("form.gj_all_product_sort_form").submit();
        });
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
            values: [ 100, 299 ],
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
            p1 = $(this).val(); 
        } 
        if($('#p_amount2').val()) {
            p2 = $('#p_amount2').val(); 
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
            p1 = $('#p_amount1').val(); 
        }
        if($(this).val()) {
            p2 = $(this).val(); 
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