<?php 
$banner_path = 'images/banner_image';
$main_cat_path = 'images/main_cat_image';
$sub_cat_path = 'images/sub_cat_image';
$product_path = 'images/featured_products';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';
?>
@extends('groceryView.layouts.headerFooter')
@section('title', 'About Us')

@section('content')

    <div class="wrapper">
        <div class="grocery-Breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">About Us</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="life-grocery">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="default-title left-text">
                            <h2>{{$abouts->heading}}</h2>
                                <!--<p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </p>-->
                        </div>
                    <div class="about-content">
                        <!-- <p> Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.    </p>-->
                <div> <?php echo $abouts->page_data ?>   </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="about-img">
                <img src="{{asset('images/site_img/'.$abouts->image)}}" alt="">
            </div>
        </div>
    </div>
    <div class="about-steps-group white-bg">
        <div class="container">
            <div class="row">
                @foreach($widget1s as $widget1)
                <div class="col-lg-3">
                    <div class="about-step">
                        <div class="about-step-img">
                            <img src="{{asset('images/site_img/'.$widget1->image)}}" alt="">
                        </div>
                        <h4>{{$widget1->value}}+</h4>
                       <!-- <p>People have joined the Grocery team in the past six months</p>-->
                       <p>{{$widget1->description}}</p>
                    </div>
                </div>
                @endforeach
                <!--<div class="col-lg-3">
                    <div class="about-step">
                        <div class="about-step-img">
                        <img src="images/about/icon-2.svg" alt="">
                    </div>
                    <h4>2x</h4>
                    <p>Rate of growth in our monthly user base</p>
                </div>-->
            </div>
            <!--
            <div class="col-lg-3">
                <div class="about-step">
                    <div class="about-step-img">
                        <img src="images/about/icon-3.svg" alt="">
                    </div>
                    <h4>10 days</h4>
                    <p>Time taken to launch in 8 cities across Kenya</p>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="about-step">
                    <div class="about-step-img">
                        <img src="images/about/icon-4.svg" alt="">
                    </div>
                    <h4>95k</h4>
                    <p>App downloads on iOS and Android</p>
                </div>
            </div>
            -->
        </div>
    </div>
    
    <div class="how-order-grocery">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="default-title">
                        <h2> {{$widget2[0]->contents}}  </h2>
                        <p>{{$widget2[1]->contents}} </p>

                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="how-order-steps">
                        <div class="how-order-icon">
                            <i class="uil uil-search"></i>
                        </div>
                        <h4> {{$widget2[2]->contents}}  </h4>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="how-order-steps">
                        <div class="how-order-icon">
                            <i class="uil uil-shopping-basket"></i>
                        </div>
                        <h4> {{$widget2[3]->contents}} </h4>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="how-order-steps">
                        <div class="how-order-icon">
                            <i class="uil uil-stopwatch"></i>
                        </div>
                        <h4> {{$widget2[4]->contents}}  </h4>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="how-order-steps">
                        <div class="how-order-icon">
                            <i class="uil uil-money-bill"></i>
                        </div>
                        <h4> {{$widget2[5]->contents}}  </h4>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="how-order-steps">
                        <div class="how-order-icon">
                            <i class="uil uil-truck"></i>
                        </div>
                        <h4> {{$widget2[6]->contents}}  </h4>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="how-order-steps">
                        <div class="how-order-icon">
                            <i class="uil uil-smile"></i>
                        </div>
                        <h4>{{$widget2[7]->contents}} </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

@endsection
