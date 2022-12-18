<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//mobile number checking already taken or not
Route::post("check_phone", "ApiController@check_phone")->name("check_phone");

//signup 
Route::post("signup", "ApiController@signup")->name("signup");

//sign in
Route::post("signin", "ApiController@signin")->name("signin");

//user profile
Route::post("profile", "ApiController@profile")->name("profile");

//user profile update
Route::post("update_profile", "ApiController@update_profile")->name("update_profile");

//state
Route::get("state", "ApiController@state")->name("state");

//district
Route::post("district", "ApiController@city")->name("district");

//update profile photo
Route::post("profile_img", "ApiController@profile_img")->name("profile_img");

//product main categories
Route::get("main_category", "ApiController@main_category")->name("main_category");

//product sub category
Route::post("sub_category", "ApiController@sub_category")->name("sub_category");

//product listing
Route::get("products", "ApiController@products")->name("products");

//product details
Route::post("product_detail", "ApiController@product_detail")->name("product_detail");

//subcategory product listing
Route::post("sub_cat_listing", "ApiController@sub_cat_listing")->name("sub_cat_listing");

//add address
Route::post("add_address", "ApiController@add_address")->name("add_address");

//Get address
Route::post("get_address", "ApiController@get_address")->name("get_address");

//update address
Route::post("update_address", "ApiController@update_address")->name("update_address");

//product searching 
Route::post("search_product", "ApiController@search_product")->name("search_product");

//filter by brand
Route::get("all_brand", "ApiController@all_brand")->name("all_brand");
Route::post("brand_search", "ApiController@brand_search")->name("brand_search");

//add to cart
Route::post("add_cart", "ApiController@add_cart")->name("add_cart");
Route::post("get_cart", "ApiController@get_cart")->name("get_cart");

//banner image
Route::get("slider_img", "ApiController@slider_img")->name("slider_img");

//app widget
Route::get("time_widget", "ApiController@time_widget")->name("time_widget");

//checkout
Route::post("checkout", "ApiController@checkout")->name("checkout");

//checkout verification
Route::post("checkout_verify", "ApiController@checkout_verify")->name("checkout_verify");

//my order
Route::post("my_order", "ApiController@my_order")->name("my_order");

//cancel order
Route::post("cancel_order", "ApiController@cancel_order")->name("cancel_order");

//contact us
Route::get("contact", "ApiController@contact")->name("contact");

Route::post("order_details", "ApiController@order_details")->name("order_details");

//invoice
Route::post("invoice", "ApiController@invoice")->name("invoice");

//pincode
Route::get("pincode", "ApiController@pincode")->name("pincode");

//pincode register
Route::post("register_pincode", "ApiController@register_pincode")->name("register_pincode");

//ddeal of the day banner image
Route::get("banner", "ApiController@banner")->name("banner");

//api count of notification
Route::post("count_notification", "ApiController@count_notification")->name("count_notification");

//notification read flag update
Route::post("update_notification", "ApiController@update_notification")->name("update_notification");

Route::get("get_pdf", "ApiController@get_pdf")->name("get_pdf");


Route::get("send_mail", "ApiController@send_mail")->name("send_mail");

//Route::get("get_sms", "ApiController@send_otp")->name("get_sms");

Route::post("get_otp", "ApiController@get_otp")->name("get_otp");

Route::get("push_notification", "ApiController@push_notification")->name("push_notification");