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
$facebook= \DB::table('social_media_settings')->value("facebook_page_url");
$twitter= \DB::table('social_media_settings')->value("twitter_page_url");
$linkedin=\DB::table('social_media_settings')->value("linkedin_page_url");
$youtube=\DB::table('social_media_settings')->value("youtube_url");
$insta=\DB::table('social_media_settings')->value("instagram_url");
$pint=\DB::table('social_media_settings')->value("pinterest_url");

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
$code="INR";
?>
@extends('groceryView.layouts.headerFooter')
@section('title', 'Home')
@section('content')

<div class="wrapper">
		<div class="main-slider">
			<div class="container-fluid">
				<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
				    
				    <ol class="carousel-indicators">
				        @if(count($banner_images) != 0)
				    @foreach($banner_images as $key => $value)
                <li data-target="#carouselExampleCaptions" data-slide-to="{{$key}}" @if($key==0) class="active" @endif></li>
                @endforeach
                @endif
				    </ol>
					<div class="carousel-inner">
					    
					    @if($banner_images)
                @if(count($banner_images) != 0)
                @foreach($banner_images as $key => $value)
                <div class="carousel-item @if($key==0) active  @endif"> <img src="{{ asset($banner_path.'/'.$value->banner_image)}}" class="d-block w-100" alt="...">
							 
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
		<div class="section145">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="main-title-tt">
							<div class="main-title-left">
								<h2>{{App\HeadingModel::where("id", 7)->value("heading")}} </h2> </div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="owl-carousel cate-slider owl-theme">
							@if($main_cat)
                                            @if(count($main_cat) != 0)
                                                @foreach($main_cat as $key => $value)
												<div class="item">
								<a href="{{ url('all_filter_products?fil_cats='.$value->id) }}" class="category-item">
									<div class="cate-img"> <img src="{{ asset($main_cat_path.'/'.$value->main_cat_image) }}" alt="Ecambiar Main Category{{$value->id}}"> </div>
									<h4>{{$value->main_cat_name}} </h4> </a>
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
		<div class="section145">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="main-title-tt">
							<div class="main-title-left">
								<h2>{{App\HeadingModel::where("id", 1)->value("heading")}}</h2> </div> <a href="{{route('all_products')}}" class="see-more-btn">See All</a> </div>
					</div>
					<div class="col-md-12">
						<div class="owl-carousel featured-slider owl-theme">
							
							@if(($featured_products) && (count($featured_products) != 0))
						@foreach ($featured_products as $key => $value)
						<div class="item">
							<div class="product-item">
								<a href="{{ route('view_products', ['id' => $value->id]) }}" class="product-img"> <img src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="">



									<div class="product-absolute-options"> <!-- discount calculation -->
                                        <?php 
                                            $disc=round((($value->original_price-$value->discounted_price)/$value->original_price)*100);
                                            if($disc>0){
											    echo "<span class='offer-badge-1'>".$disc."% off</span>";
                                            }
                                        ?> <span class="like-icon gj_wish_list" title="wishlist" data-wish-id="{{$value->id}}"></span> </div>
								</a>
								<div class="product-text-dt">

									<div class="kelelclienzhre">
										<button class="gj_shareall_btn"> <i class="fa fa-share-alt sjareall"></i> </button>

										<div class="share-links gj_vend_share">
											<ul class="gj_soc_sre">
												<li><a class="op1" href="{{$facebook}}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
												<li><a class="op2" href="{{$twitter}}" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
												<li><a class="op3" href="{{$pint}}" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
												<li><a class="op4" href="{{$insta}}" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
												<li><a class="op5" href="{{$youtube}}" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                                            </ul>
										</div>

									</div>
									

									<h4> {{$value->product_title}}</h4>
									<div class="product-price">{{$code}} {{$value->discounted_price}} @if($value->original_price>$value->discounted_price)<span>Inr {{$value->original_price}}</span>@endif</div>

									<div class="qty-cart">
										<div class="quantity buttons_added">
											<input type="button" value="-" class="minus minus-btn">
											<input type="number" step="1" id="qty{{$value->id}}" data-qty-id={{$value->id}} name="quantity" value="1" min="1" class="input-text qty text">
											<input type="button" value="+" class="plus plus-btn">
											<input type="hidden" id="price_{{$value->id}}" name="price" value="{{$value->discounted_price}}" class="quantity-selector">
										</div>
										<div class="add-cart">
											<button  class="addcartzhom gj_add2cartzsx" data-cart-id="{{$value->id}}"> Add to Cart </button>
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
		 @if(count($offers)>0)
		<div class="section145">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="main-title-tt">
							<div class="main-title-left">
								<h2> {{App\HeadingModel::where("id", 2)->value("heading")}} </h2> </div> <a href="{{route('all_products')}}" class="see-more-btn">See All</a> </div>
					</div>
					<div class="col-md-12">
						<div class="owl-carousel deal-slider owl-theme">
						   
							@foreach($offers as $offer)
							<div class="item">
								<div class="product-item">
									<div class="product-layout col-xs-12">
										<div class="product-thumb transition row">
											<div class="image col-md-6">
											<a href="{{ route('view_products', ['id' => $offer->id]) }}"><img src="{{ asset($product_path.'/'.$offer->featured_product_img) }}" class="img-responsive center-block"></a>
												<div class="button-group">
												    <button class='gj_wish_list' type="button"> <i class="fa fa-heart"></i> </button>
													<button type="button"> <i class="fa fa-random"></i> </button>
													<button type="button"> <i class="fa fa-eye"></i> </button>
												</div>
											</div>
											<div class="caption col-md-6 text-left">
											<h4><a href="{{ route('view_products', ['id' => $offer->id]) }}"> {{$offer->product_title}} </a></h4>
												<div><?php echo $offer->features; ?></div>
												<hr>
											     <p class="price"> <span class="price-new">{{$code}} {{$offer->discounted_price}}</span> @if($offer->original_price>$offer->discounted_price)<span class="price-old"> {{$code}} {{$offer->original_price}}</span> @endif</p>
												<div class="qty-cart">
													<div class="quantity buttons_added">
														<input type="button" value="-" class="minus minus-btn">
														<input type="number" step="1" id="o_qty{{$offer->id}}" data-qty-id={{$offer->id}} name="quantity" value="1" min="1" class="input-text qty text">
														<input type="button" value="+" class="plus plus-btn"> </div> <span class="cart-icon gj_add2cartoffer" data-cart-id="{{$offer->id}}"><i class="uil uil-shopping-cart-alt"></i></span> </div>
														<input type="hidden" id="price_{{$offer->id}}" name="price" value="{{$offer->discounted_price}}" class="quantity-selector">
												<!--<div class="cmtk_dt">
													<div class="product_countdown-timer offer-counter-text dekldatcollz" data-countdown="2022/03/06"></div>
												</div>-->
											</div>
										</div>
									</div>
								</div>
							</div>
							@endforeach
							
							<!--<div class="item">
								<div class="product-item">
									<div class="product-layout col-xs-12">
										<div class="product-thumb transition row">
											<div class="image col-md-6">
												<a href="#"><img src="{{ asset('assetsGrocery/images/product/img-2.jpg')}}" class="img-responsive center-block"></a>
												<div class="button-group">
													<button type="button"> <i class="fa fa-heart"></i> </button>
													<button type="button"> <i class="fa fa-random"></i> </button>
													<button type="button"> <i class="fa fa-eye"></i> </button>
												</div>
											</div>
											<div class="caption col-md-6 text-left">
												<h4><a href="#"> Strawberry </a></h4>
												<p> The 30-inch Apple Cinema HD Display delivers an..</p>
												<hr>
												<p class="price"> <span class="price-new">{{$code}} 110.00</span> <span class="price-old"> {{$code}} 122.00</span> </p>
												<div class="qty-cart">
													<div class="quantity buttons_added">
														<input type="button" value="-" class="minus minus-btn">
														<input type="number" step="1" name="quantity" value="1" class="input-text qty text">
														<input type="button" value="+" class="plus plus-btn"> </div> <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span> </div>
												<div class="cmtk_dt">
													<div class="product_countdown-timer offer-counter-text dekldatcollz" data-countdown="2022/03/06"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="product-item">
									<div class="product-layout col-xs-12">
										<div class="product-thumb transition row">
											<div class="image col-md-6">
												<a href="#"><img src="{{ asset('assetsGrocery/images/product/img-1.jpg')}}" class="img-responsive center-block"></a>
												<div class="button-group">
													<button type="button"> <i class="fa fa-heart"></i> </button>
													<button type="button"> <i class="fa fa-random"></i> </button>
													<button type="button"> <i class="fa fa-eye"></i> </button>
												</div>
											</div>
											<div class="caption col-md-6 text-left">
												<h4><a href="#"> Cauliflower </a></h4>
												<p> The 30-inch Apple Cinema HD Display delivers an..</p>
												<hr>
												<p class="price"> <span class="price-new">{{$code}} 110.00</span> <span class="price-old"> {{$code}} 122.00</span> </p>
												<div class="qty-cart">
													<div class="quantity buttons_added">
														<input type="button" value="-" class="minus minus-btn">
														<input type="number" step="1" name="quantity" value="1" class="input-text qty text">
														<input type="button" value="+" class="plus plus-btn"> </div> <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span> </div>
												<div class="cmtk_dt">
													<div class="product_countdown-timer offer-counter-text dekldatcollz" data-countdown="2022/03/06"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="product-item">
									<div class="product-layout col-xs-12">
										<div class="product-thumb transition row">
											<div class="image col-md-6">
												<a href="#"><img src="{{ asset('assetsGrocery/images/product/img-4.jpg')}}" class="img-responsive center-block"></a>
												<div class="button-group">
													<button type="button"> <i class="fa fa-heart"></i> </button>
													<button type="button"> <i class="fa fa-random"></i> </button>
													<button type="button"> <i class="fa fa-eye"></i> </button>
												</div>
											</div>
											<div class="caption col-md-6 text-left">
												<h4><a href="#"> Carrot  </a></h4>
												<p> The 30-inch Apple Cinema HD Display delivers an..</p>
												<hr>
												<p class="price"> <span class="price-new">{{$code}} 110.00</span> <span class="price-old"> {{$code}} 122.00</span> </p>
												<div class="qty-cart">
													<div class="quantity buttons_added">
														<input type="button" value="-" class="minus minus-btn">
														<input type="number" step="1" name="quantity" value="1" class="input-text qty text">
														<input type="button" value="+" class="plus plus-btn"> </div> <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span> </div>
												<div class="cmtk_dt">
													<div class="product_countdown-timer offer-counter-text dekldatcollz" data-countdown="2022/03/06"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="product-item">
									<div class="product-layout col-xs-12">
										<div class="product-thumb transition row">
											<div class="image col-md-6">
												<a href="#"><img src="{{ asset('assetsGrocery/images/product/img-3.jpg')}}" class="img-responsive center-block"></a>
												<p class="sale-tag sale">Save 10%</p>
												<div class="button-group">
													<button type="button"> <i class="fa fa-heart"></i> </button>
													<button type="button"> <i class="fa fa-random"></i> </button>
													<button type="button"> <i class="fa fa-eye"></i> </button>
												</div>
											</div>
											<div class="caption col-md-6 text-left">
												<h4><a href="#"> Graphes  </a></h4>
												<p> The 30-inch Apple Cinema HD Display delivers an..</p>
												<hr>
												<p class="price"> <span class="price-new">{{$code}} 110.00</span> <span class="price-old"> {{$code}} 122.00</span> </p>
												<div class="qty-cart">
													<div class="quantity buttons_added">
														<input type="button" value="-" class="minus minus-btn">
														<input type="number" step="1" name="quantity" value="1" class="input-text qty text">
														<input type="button" value="+" class="plus plus-btn"> </div> <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span> </div>
												<div class="cmtk_dt">
													<div class="product_countdown-timer offer-counter-text dekldatcollz" data-countdown="2022/03/06"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>-->
						</div>
					</div>
				</div>
			</div>
		</div>
	 @endif
	 @if(count($vegetables)>0)
		<div class="section145">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="main-title-tt">
							<div class="main-title-left">
								<h2>{{App\HeadingModel::where("id", 3)->value("heading")}}</h2> </div> <a href="{{route('all_products')}}" class="see-more-btn">See All</a> </div>
					</div>
					<div class="col-md-12">
						<div class="owl-carousel featured-slider owl-theme">
							<!--Fresh fruits and vegetables-->
							@foreach ($vegetables as $key => $value)
							<div class="item">
								<div class="product-item">
									<a href="{{ route('view_products', ['id' => $value->id]) }}" class="product-img"> <img src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="">
										<div class="product-absolute-options"> 
											<!-- discount calculation -->
                                        <?php 
                                            $disc=round((($value->original_price-$value->discounted_price)/$value->original_price)*100);
                                            if($disc>0){
											   echo "<span class='offer-badge-1'>".$disc."% off</span>";
										   }
                                        ?> <span class="like-icon gj_wish_list" title="wishlist" data-wish-id="{{$value->id}}"></span> </div>
									</a>
									<div class="product-text-dt">
									
									<div class="kelelclienzhre">                                                      
                                                    <button class="gj_shareall_btn"> <i class="fa fa-share-alt sjareall"></i> </button>
                                                    
                                                     <div class="share-links gj_vend_share">
														<ul class="gj_soc_sre">
															<li><a class="op1" href="{{$facebook}}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
															<li><a class="op2" href="{{$twitter}}" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
															<li><a class="op3" href="{{$pint}}" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
															<li><a class="op4" href="{{$insta}}" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
															<li><a class="op5" href="{{$youtube}}" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                                                        </ul>
                                                    </div>

                                                    </div>
													
										<h4> {{$value->product_title}} </h4>
									    <div class="product-price">{{$code}} {{$value->discounted_price}}@if($value->original_price>$value->discounted_price)<span>{{$code}} {{$value->original_price}}</span>@endif</div>
										<div class="qty-cart">
											<div class="quantity buttons_added">
												<input type="button" value="-" class="minus minus-btn">
												<input type="number" step="1" id="v_qty{{$value->id}}" data-qty-id={{$value->id}} name="quantity" value="1" min="1" class="input-text qty text">
												<input type="button" value="+" class="plus plus-btn">
												<input type="hidden" id="price_{{$value->id}}" name="price" value="{{$value->discounted_price}}" class="quantity-selector">
											</div>
											<div class="add-cart">
												<button  class="addcartzhom gj_add2cartsrj" data-cart-id="{{$value->id}}"> Add to Cart </button>
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
		@endif
		<div class="section145">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="main-title-tt">
							<div class="main-title-left">
								<h2>{{App\HeadingModel::where("id", 4)->value("heading")}}</h2> </div>
						</div>
					</div>
					@foreach($deals as $deal)
					<div class="col-lg-4 col-md-6">
						<a href="{{$deal->url}}" class="best-offer-item"> <img src="{{ asset('images/site_img/'.$deal->image)}}" alt=""> 
						</a>
					</div>
					
					@endforeach
				</div>
			</div>
		</div>
		<div class="section145">
			<div class="container">
				<div class="row">
				
				<div class="col-md-12">
						<div class="main-title-tt">
							<div class="main-title-left">
								<h2>{{App\HeadingModel::where("id", 5)->value("heading")}}</h2> </div>
						</div>
					</div>
					@foreach($new_products->chunk(3) as $chunk)
					<div class="col-md-3">
					
						<div class="interclntz"> 
						@foreach($chunk as $val)
						 	<div class="inclentz">
						 	<a href="{{ route('view_products', ['id' => $val->id]) }}" ><img src="{{ asset($product_path.'/'.$val->featured_product_img) }}" alt=""></a>
								<h4> {{$val->product_title}} </h4>
						 	</div>
						 
						 	
						 @endforeach
						 </div>
					</div>
					
					
					@endforeach
			
					
				</div>
			</div>
		</div>
		@if(count($fishes)>0)
		<!-- fish and meat section start -->
		<div class="section145">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="main-title-tt">
							<div class="main-title-left">
								<h2>{{App\HeadingModel::where("id", 6)->value("heading")}}  </h2> </div> <a href="{{route('all_products')}}" class="see-more-btn">See All</a> </div>
					</div>
					<div class="col-md-12">
						<div class="owl-carousel featured-slider owl-theme">
							@foreach ($fishes as $key => $value)
							<div class="item">
								<div class="product-item">
									<a href="{{ route('view_products', ['id' => $value->id]) }}" class="product-img"> <img src="{{ asset($product_path.'/'.$value->featured_product_img) }}" alt="">
										<div class="product-absolute-options"> <span class="offer-badge-1">New</span> <span class="like-icon gj_wish_list"  title="wishlist" data-wish-id="{{$value->id}}"></span> </div>
									</a>
									<div class="product-text-dt">
									
									<div class="kelelclienzhre">                                                      
                                                    <button class="gj_shareall_btn"> <i class="fa fa-share-alt sjareall"></i> </button>
                                                    
                                                     <div class="share-links gj_vend_share">
                                                 		<ul class="gj_soc_sre">
												 			<li><a class="op1" href="{{$facebook}}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
															<li><a class="op2" href="{{$twitter}}" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
															<li><a class="op3" href="{{$pint}}" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
															<li><a class="op4" href="{{$insta}}" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
															<li><a class="op5" href="{{$youtube}}" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                                                        </ul>
                                                    </div>

                                                    </div>
													
										<h4> {{$value->product_title}} </h4>
										
										<div class="kelelclienzhre">                                                      
                                                    <button class="gj_shareall_btn"> <i class="fa fa-share-alt sjareall"></i> </button>
                                                    
                                                     <div class="share-links gj_vend_share">
                                                		 <ul class="gj_soc_sre">
                                                            <<li><a class="op1" href="{{$facebook}}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
															<li><a class="op2" href="{{$twitter}}" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
															<li><a class="op3" href="{{$pint}}" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
															<li><a class="op4" href="{{$insta}}" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
															<li><a class="op5" href="{{$youtube}}" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                                                        </ul>
                                                    </div>

                                                    </div>
													

									<div class="product-price">{{$code}} {{$value->discounted_price}}@if($value->original-price>$value->discounted_price) <span>{{$code}} {{$value->original_price}}</span>@endif</div>
										<div class="qty-cart">
											<div class="quantity buttons_added">
												<input type="button" value="-" class="minus minus-btn">
												<input type="number" step="1" id="f_qty{{$value->id}}" data-qty-id={{$value->id}} name="quantity" value="1" min="1" class="input-text qty text">
												<input type="button" value="+" class="plus plus-btn">
												<input type="hidden" id="price_{{$value->id}}" name="price" value="{{$value->discounted_price}}" class="quantity-selector">
											</div>
											<div class="add-cart">
												<button  class="addcartzhom gj_add2cartsrjf" data-cart-id="{{$value->id}}"> Add to Cart </button>
											</div>
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
		@endif
		<!-- fish and meat section end -->
	</div>	

@endsection
@section('script')



@endsection