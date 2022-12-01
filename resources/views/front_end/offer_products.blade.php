<?php 
$banner_path = 'images/banner_image';
$main_cat_path = 'images/main_cat_image';
$sub_cat_path = 'images/sub_cat_image';
$product_path = 'images/offer_products';
$noimage = \DB::table('noimage_settings')->first();
$noimage_path = 'images/noimage';
$now = date('Y-m-d H:i:s');
?>
@extends('layouts.frontend')
@section('title', 'OFFER PRODUCTS')
<style>
    /*offer styles*/
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
    .buyget {
        padding: 2px 9px;
        background: #ff5c00;
        border-radius: 30px;
        font-size: 12px;
        position: absolute;
        top: 6px;
        left: 0px;
        color: #fff;
    }
    .product-item {
        position: relative;
        margin-bottom: 30px;
        padding: 10px;
        background: #fff;
        box-shadow: 0px 0px 10px #000;
    }
    .offdatez{    
        position: absolute;
        top: 0px;
        right: 0px;
    }
    .offdatez li {
        padding: 3px 12px 3px 18px;
        margin: 0px 0px 3px;
        background: #ff5c00;
        position: relative;
        color: #fff;
    }
    .offdatez li:before {
        position: absolute;
        content: " ";
        left: 0px;
        top: 0px;
        border-left: 15px solid #fff;
        border-bottom: 28px solid #ff5c00;
    }
    #shopoffer{
        padding:50px 0px 30px;
    }
    .offdatez li:first-child {
        background: #222222;
    }
    .offdatez li:first-child:before {    
        border-bottom: 28px solid #222222;
    }
    span.gj_offer_desc {
        height: 38px;
        overflow: hidden;
        display: block;
    }
    .shopify-payment-button__button--hidden {
        visibility: hidden;
    }
    .pt50 {
        padding-top:50px;
    }
    .hilit {
        font-size:16px;
    }
    .hilit label {
        font-weight: 700!important;
        font-size: 16px!important;
        color: #ff5c00!important;
    }
    /*offer styles*/    
</style>

@section('content')
<!-- OFFER PRODUCTS SECTION START -->
<section class="gj_offer_sec">
    <div id="shopoffer" class="shopify-section">
        <div class="page-hotdeal">
            <div class="container">
                <div class="products-listing grid">
                    <div class="product-layout">
                        <div class="row">  
                            @if(($offer_products) && count($offer_products) != 0)
                                @foreach($offer_products as $key => $value)
                                    <div class="col-xl-3 col-lg-3 col-sm-4 col=12 product"> 
                                        <div class="product-item" data-id="product-1674856071279">
                                            <div class="product-item-container grid-view-item  ">
                                                <div class="left-block">
                                                    <div class="product-image-container product-image">
                                                        <a class="grid-view-item__link image-ajax" href="{{ route('offer_products_dets', ['id' => $value->id]) }}">
                                                            <img class="img-responsive s-img lazyautosizes lazyloaded" data-sizes="auto" src="{{ asset($product_path.'/'.$value->image) }}" alt="{{$value->product_title}}" sizes="348px">
                                                        </a> 
                                                    </div>

                                                    @if($value->offer_type)
                                                        <div class="box-label">  
                                                            <span class="buyget"> {{$value->offer_type}}  </span> 
                                                      </div>
                                                    @endif

                                                    <div class="offdatez">
                                                        <ul>
                                                            <li> Start Date <span>  {{date('Y-m-d', strtotime($value->offer_start))}} </span> </li>
                                                            <li> End Date <span>  {{date('Y-m-d', strtotime($value->offer_end))}} </span> </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                
                                                <div class="right-block">
                                                    <div class="button-link">
                                                        <div class="btn-button add-to-cart action  ">  
                                                           <a class="grl btn_df" href="{{ route('offer_products_dets', ['id' => $value->id]) }}" title="view"> Grab This Offer </a>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="caption">
                                                        <h4 class="title-product"><a class="product-name" href="{{ route('offer_products_dets', ['id' => $value->id]) }}">{{$value->offer_title}}</a></h4>
                    
                                                        <div class="price"> 
                                                            <span class="gj_offer_desc"> <span class="money">  {{$value->description}} </span> </span>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div> 
                                @endforeach
                            @else
                                <div class="col-xl-3 col-lg-3 col-sm-4 col=12 product"> 
                                    <div class="product-item" data-id="product-1674856071279">
                                        <div class="product-item-container grid-view-item  ">
                                            <div class="left-block">
                                                <div class="product-image-container product-image">
                                                    <a class="grid-view-item__link image-ajax" href="#">
                                                        <img class="img-responsive s-img lazyautosizes lazyloaded" data-sizes="auto" src="{{ asset($noimage_path.'/'.$noimage->product_no_image) }}" sizes="348px">
                                                    </a> 
                                                </div>
                                            </div>
                                            
                                            <div class="right-block">
                                                <div class="caption">
                                                    <h4 class="title-product"><a class="product-name" href="#">No Offers</a></h4>
                
                                                    <div class="price"> 
                                                        <span class=""> <span class="money">  Offers  Not Available </span> </span>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div> 
                            @endif      
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="gj_pagination">
                                    {{ $offer_products->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- OFFER PRODUCTS SECTION END -->
@endsection