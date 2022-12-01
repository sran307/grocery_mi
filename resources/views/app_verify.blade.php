@extends('groceryView.layouts.headerFooter')
@section('title', 'verification')

@section('content')
    <!-- 404 Page Section Start -->
    <div class="wrapper">
        <section class="disclaim">
            <div class="sc-404-page wow bounceInLeft" data-wow-duration="1s" data-wow-delay="0.002s">
                <div class="container">
                    <div class="sc-main-content">
                        <div class="s-content-wrapper">
                            <div class="sc-404-page__wrapper">
                                <div class="sc-404-page__box">
                                    <div class="sc-404-page__box__wrapper-img">
                                       <!--<img class="img-responsive" alt="404" src="{{ asset('images/site_img/404.png')}}">-->
                                    </div>
                                    @if(Session::has("message"))
                                    <span class="content-text alert alert-{{Session::get('alert-class')}}">{{Session::get("message")}}</span>
                                    @endif
                                   <!-- <a class="btn-green" href="{{route('home')}}">GO HOME</a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- 404 Page Section End -->
        <div class="clearfix"></div>
    </div>
   
@endsection