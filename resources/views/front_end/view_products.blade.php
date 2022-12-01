<?php 
$banner_path = 'images/banner_image';
$main_cat_path = 'images/main_cat_image';
$sub_cat_path = 'images/sub_cat_image';
$product_path = 'images/featured_products';
$product_img_path = 'images/products';
$product_att_path = 'images/attributes';
$profile_img_path = 'images/profile_img';
$brand_img_path = 'images/brands';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';
?>
@extends('groceryView.layouts.headerFooter')
@section('title', 'View Products')

@section('content')
  
    <link href="{{ asset('assetsGrocery/vendor/OwlCarousel/assets/owl.carousel.css')}}" rel="stylesheet">
    <link href="{{ asset('assetsGrocery/vendor/OwlCarousel/assets/owl.theme.default.min.css')}}" rel="stylesheet">
<!-- SUB CATEGORY SECTION START -->

<div class="wrapper">
    <div class="grocery-Breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{url('/all_products')}}">All Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$products->product_title}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="all-product-grid">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-dt-view">
                        <div class="row">
                            <div class="col-lg-4 col-md-4">
                                <div id="sync1" class="owl-carousel owl-theme sr_owl_theme">
                                    {{--@foreach($products['images'] as $key => $value)--}}
                                        <div class="item sr_img_item1">
                                            <img src="{{asset($product_path.'/'.$image)}}" alt="">
                                        </div>
                                        {{--@endforeach--}}
                                </div>
                                <div id="sync2" class="owl-carousel owl-theme">
                                    {{--@foreach($products['images'] as $key => $value)--}}
                                        <div class="item sr_img_item2">
                                            <img src="{{asset($product_path.'/'.$image)}}" alt="">
                                        </div>
                                       {{-- @endforeach--}}
                                    <!--<div class="item">
                                    <img src="images/b1.jpg" alt="">
                                    </div>
                                    <div class="item">
                                    <img src="images/b2.jpg" alt="">
                                    </div>
                                    <div class="item">
                                    <img src="images/product/big-4.jpg" alt="">
                                    </div>-->
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8">
                                <div class="product-dt-right">
                                    <h2>{{$products->product_title}}</h2>
                                    <div class="no-stock">
                                        <p class="pd-no">Product No.<span>{{$products->product_code}}</span></p>
                                        @if($products->onhand_qty>0)
                                            <p class="stock-qty">Available<span>(Instock)</span></p>
                                        @else
                                            <p class="stock-qty">Available<span>(Outof Stock)</span></p>
                                        @endif
                                    </div>

                                    <!-- unit selection section start -->
                                    @if($products->measurement_unit==7)
                                    <div class="product-radio">
                                        <ul class="product-now">
                                            <li>
                                                <input type="radio" id="p1" name="product1">
                                                <label for="p1">500g</label>
                                            </li>
                                            <li>
                                                <input type="radio" id="p2" name="product1">
                                                <label for="p2">1kg</label>
                                            </li>
                                            <li>
                                                <input type="radio" id="p3" name="product1">
                                                <label for="p3">2kg</label>
                                            </li>
                                            <li>
                                                <input type="radio" id="p4" name="product1">
                                                <label for="p4">3kg</label>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                    <!-- Unit selection section end -->

                                    <!-- Product desdcription section start -->
                                    <div class="pp-descp"><?php echo $products->features; ?></div>
                                    <!-- Product desdcription section end -->
                                    <div class="product-group-dt">
                                        <ul>
                                            <li><div class="main-price color-discount">Discount Price<span>INR {{round($products->discounted_price)}}</span></div></li>
                                            <li><div class="main-price mrp-price">MRP Price<span>INR {{round($products->original_price)}}</span></div></li>
                                        </ul>
                                        <ul class="gty-wish-share">
                                            <li>
                                                <div class="qty-product">
                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus minus-btn">
                                                        <input type="number" step=".5" id="qty{{$products->id}}" data-qty-id={{$products->id}} name="quantity"  value="1.0" min="1" class="input-text qty text">
                                                        <input type="button" value="+" class="plus plus-btn">
                                                        <input type="hidden" id="price_{{$products->id}}" name="price" value="{{$products->original_retailer_price}}" class="quantity-selector">

                                                    </div>
                                                </div>
                                            </li>
                                            <li><span class="like-icon gj_wish_list save-icon" data-wish-id="{{$products->id}}" title="wishlist"></span></li>
                                        </ul>
                                        <ul class="ordr-crt-share">
                                            <li><button data-cart-id='{{$products->id}}' class="add-cart-btn hover-btn gj_add2cartzsx"><i class="uil uil-shopping-cart-alt"></i>Add to Cart</button></li>
                                            <!--<li><button class="order-btn hover-btn">Order Now</button></li>-->
                                        </ul>
                                    </div>
                                    <div class="pdp-details">
                                        <ul>
                                            <li>
                                                <div class="pdp-group-dt">
                                                    <div class="pdp-icon"><i class="uil uil-usd-circle"></i></div>
                                                    <div class="pdp-text-dt">
                                                        <span>{{$widgets[0]->heading}}</span>
                                                        <p>{{$widgets[0]->content}}</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="pdp-group-dt">
                                                    <div class="pdp-icon"><i class="uil uil-cloud-redo"></i></div>
                                                    <div class="pdp-text-dt">
                                                        <span>{{$widgets[1]->heading}}</span>
                                                        <p>{{$widgets[1]->content}}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
    
            <div class="col-lg-12 col-md-12">
                <div class="pdpt-bg">
                    <div class="pdpt-title">
                        <h4>Product Details</h4>
                    </div>
                <div class="pdpt-body scrollstyle_4">
                    <div class="pdct-dts-1">
                        <div class="pdct-dt-step">
                            <h4>Description</h4>
                                <div><?php echo $products->features; ?></div>
                        </div>
                        <!--<div class="pdct-dt-step">
                            <h4>Benefits</h4>
                            <div class="product_attr">
                                Aliquam nec nulla accumsan, accumsan nisl in, rhoncus sapien.<br>
                                In mollis lorem a porta congue.<br>
                                Sed quis neque sit amet nulla maximus dignissim id mollis urna.<br>
                                Cras non libero at lorem laoreet finibus vel et turpis.<br>
                                Mauris maximus ligula at sem lobortis congue.<br>
                            </div>
                        </div>
                        <div class="pdct-dt-step">
                            <h4>How to Use</h4>
                            <div class="product_attr">
                                The peeled, orange segments can be added to the daily fruit bowl, and its juice is a refreshing drink.
                            </div>
                        </div>-->
                       <!--- <div class="pdct-dt-step">
                            <h4>Seller</h4>
                            <div class="product_attr">
                                Grocery 360 Pvt Ltd, Sks Nagar, Near Mbd Mall, Ludhana, 141001
                            </div>
                        </div>
                        <div class="pdct-dt-step">
                            <h4>Disclaimer</h4>
                            <p>Phasellus efficitur eu ligula consequat ornare. Nam et nisl eget magna aliquam consectetur. Aliquam quis tristique lacus. Donec eget nibh et quam maximus rutrum eget ut ipsum. Nam fringilla metus id dui sollicitudin, sit amet maximus sapien malesuada.</p>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    
    <div class="section145">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-title-tt">
                        <div class="main-title-left">
                            <h2> Related Products</h2>
                        </div>
                        <a href="{{route('all_products')}}" class="see-more-btn">See All</a>
                    </div>
                </div>
                <div class="col-md-12">
                <div class="owl-carousel featured-slider owl-theme">
                    @foreach($related as $key => $value)
                    <div class="item">
                        <div class="product-item">
                            <a href="{{ route('view_products', ['id' => $value->id]) }}" class="product-img">
                                <img src="{{asset($product_path.'/'.$value->featured_product_img)}}" alt="">
                                <div class="product-absolute-options">
                                    <span class="offer-badge-1">
                                        <!-- discount calculation -->
                                        <?php 
                                           echo $disc=round((($value->original_price-$value->discounted_price)/$value->original_price)*100);
                                        ?> 
                                    
                                   % off</span>
                                    <span class="like-icon gj_wish_list" data-wish-id="{{$value->id}}" title="wishlist"></span>
                                </div>
                            </a>
                            <div class="product-text-dt">
                                @if($value->onhand_qty>0)
                                    <p class="stock-qty">Available<span>(Instock)</span></p>
                                @else
                                    <p class="stock-qty">Available<span>(Outof Stock)</span></p>
                                @endif
                                <h4>{{$value->product_title}}</h4>
                                <div class="product-price">INR {{$value->discounted_price}} <span>INR {{$value->original_price}}</span></div>
                                <input type="hidden" id="price_{{$value->id}}" name="price" value="{{$value->original_retailer_price}}" class="quantity-selector">
                                <div class="add-cart"> <button class="addcartzhom gj_add2cartzsx" data-cart-id="{{$value->id}}"> Add to Cart  </button> </div> 
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
 <script src="{{ asset('assetsGrocery/js/jquery-3.3.1.min.js')}}"></script>
 <script src="{{ asset('assetsGrocery/vendor/OwlCarousel/owl.carousel.js')}}"></script>
 <script src="{{ asset('assetsGrocery/js/custom.js')}}"></script>

<script>
    $(document).ready(function () {
        $('.owl-theme').owlCarousel({

        loop:true,
        margin:10,
        nav:true,
        autoPlay: 1000,
    
    })
});

</script>


@endsection
