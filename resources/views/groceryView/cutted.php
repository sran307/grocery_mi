{{--	<div class="section145">
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
                                                    <!-- <div class="item">
                                                        <div class="collect ">
                                                            <a href="{{ url('all_filter_products?fil_cats='.$value->id) }}" class="collection-item">
                                                                <img class="collection-img img-responsive lazyload" data-sizes="auto" src="" alt="Ecambiar Main Category{{$value->id}}" data-src="{{ asset($main_cat_path.'/'.$value->main_cat_image) }}"/>
                                                            </a>
                                                            <div class="collection-name">
                                                                <a href="{{ url('all_filter_products?fil_cats='.$value->id) }}" class="collection clearfix">{{$value->main_cat_name}}</a>
                                                           </div>
                                                        </div>
                                                    </div> -->
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
		</div>--}}