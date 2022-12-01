<?php 
$banner_path = 'images/banner_image';
$main_cat_path = 'images/main_cat_image';
$sub_cat_path = 'images/sub_cat_image';
$product_path = 'images/featured_products';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';
?>
@extends('layouts.frontend')
@section('title', 'Sub Category')

@section('content')
<!-- SUB CATEGORY SECTION START -->
<section class="gj_main_categ_sec">
    <div class="gj_main_categ_bg">
        <div class="container">
            <div class="row">
                @if(($sub_cat) && count($sub_cat) != 0)
                    @foreach($sub_cat as $key => $value)
                        <div class="col-md-3 col-sm-4 col-xs-6">
                            <div class="gj_whole_mcat">
                                <div class="gj_mcat_img_div">
                                    @if($value->sub_cat_image)
                                        <a href="{{ route('sub_sub_category', ['sub_cat' => $value->sub_cat_id]) }}"><img src="{{ asset($sub_cat_path.'/'.$value->sub_cat_image) }}" alt="{{$value->sub_cat_name}}" class="img-responsive gj_mcat_img"></a>
                                    @else
                                        <a href="#"><img src="{{ asset($noimage_path.'/'.$noimage->category_no_image) }}" alt="No Images" class="img-responsive gj_mcat_img"></a>
                                    @endif()

                                </div>
                                <a href="{{ route('sub_sub_category', ['sub_cat' => $value->sub_cat_id]) }}"><p class="gj_mcat_img_hd">{{$value->sub_cat_name}}</p></a>
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
                        {{ $sub_cat->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- SUB CATEGORY SECTION END -->
@endsection