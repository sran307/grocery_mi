<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\loginSecurity;
use App\MerchantsDocuments;
use App\ShippingAddress;
use App\Store;
use App\CityManagement;
use App\StateManagements;
use App\CountriesManagement;
use App\EmailSettings;
use App\GeneralSettings;
use App\Widget;
use App\BannerImageSettings;
use App\CategoryAdvertisementSettings;
use App\CategoryManagementSettings;
use App\SubCategoryManagementSettings;
use App\SubSubCategoryManagementSettings;
use App\Products;
use App\ProductsAttributes;
use App\StockManagement;
use App\AttributesSettings;
use App\AttributesFields;
use App\ProductsImages;
use App\MeasurementUnits;
use App\Tags;
use App\CMSPageManagement;
use App\TermsCMSSettings;
use App\AboutUsCMSSettings;
use App\Disclaimers;
use App\Contacts;
use App\NewsLetter;
use App\SizeSettings;
use App\ColorSettings;
use App\Brands;
use App\Carts;
use App\TaxCutoff;
use App\Cod;
use App\WishList;
use App\PaymentSettings;
use App\Orders;
use App\OrderDetails;
use App\OrdersTransactions;
use App\StockTransactions;
use App\Shipment;
use App\AdminCommision;
use App\Review;
use App\FeedBack;
use App\ShypliteAuth;
use App\ReturnOrder;
use App\ReturnOrderDetails;
use App\Offers;
use App\OffersSub;
use App\OfferTransaction;


use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Crypt;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;
use Carbon\Carbon;

class UIController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function array_flatten($array) { 
        if (!is_array($array)) { 
            return FALSE; 
        } 
        $result = array(); 
        foreach ($array as $key => $value) { 
            if (is_array($value)) { 
                $result = array_merge($result, array_flatten($value)); 
            } else { 
                $result[$key] = $value; 
            } 
        } 
        return $result; 
    } 

    public function Home () {
        $merchant = User::WhereIn('user_type',[2,3])->Where('is_block',1)->get();
        $banner_images = BannerImageSettings::Where('is_block',1)->get();
        $main_cat = CategoryManagementSettings::Where('is_block',1)->get();
        $brand = Brands::Where('is_block',1)->get();
        $first_cat = CategoryManagementSettings::Where('is_block',1)->Where('is_home', 1)->first();
        $second_cat = CategoryManagementSettings::Where('is_block',1)->Where('is_home', 2)->first();
        $third_cat = CategoryManagementSettings::Where('is_block',1)->Where('is_home', 3)->first();
        $widget = Widget::first();

        $first_products = array();
        if ($first_cat) {
            $first_products = Products::Where('is_block',1)->Where('main_cat_name', $first_cat->id)->get();
        }

        $second_products = array();
        if ($second_cat) {
            $second_products = Products::Where('is_block',1)->Where('main_cat_name', $second_cat->id)->get();
        }

        $third_products = array();
        if ($third_cat) {
            $third_products = Products::Where('is_block',1)->Where('main_cat_name', $third_cat->id)->get();
        }

        $top_products = Products::Where('is_block',1)->Where('toprated_flag', 1)->get();
        $featured_products = Products::Where('is_block',1)->Where('featuredproduct_flag', 1)->get();
        $best_seller = Products::Where('is_block',1)->Where('best_seller_flag', 1)->OrderBy('id', 'desc')->take(10)->get();

        return View::make("front_end.index")->with(array('banner_images'=>$banner_images, 'main_cat'=>$main_cat, 'brand'=>$brand, 'first_cat'=>$first_cat, 'second_cat'=>$second_cat, 'third_cat'=>$third_cat, 'first_products'=>$first_products, 'second_products'=>$second_products, 'third_products'=>$third_products, 'top_products'=>$top_products, 'featured_products'=>$featured_products, 'best_seller'=>$best_seller, 'widget'=>$widget));
    }

    public function MainSearch (Request $request) {
        $data = Input::all();
        $keyword = $data['main_srh'];
        $all_products = "";

        if($keyword) {
            $exp = explode(' ', $keyword);
            
            $all_products = Products::Where('is_block',1)->where(function($q) use ($exp){
                foreach($exp as $ekey => $evalue){
                    $q->orWhere('product_title', 'LIKE', '%' . $evalue . '%');
                }
            })->OrderBy('id', 'desc')->paginate(32);
        }


        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(32)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        $widget = Widget::first();
        

        if(($category) && (count($category) != 0)) {
            foreach ($category as $key => $value) {
                $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                $category[$key]->{'cat_count'} = count($a_c_products);               
            }
        }

        if(($all_products) && (count($all_products) != 0)) {
            foreach ($all_products as $keyzz => $valuezz) {
                $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                $average = 0;
                if(count($review) != 0) {
                    $sum = $review->sum('rating');
                    $count = count($review);
                    if($sum != 0) {
                        $average = $sum/$count;
                    } else {
                        $average = 0;
                    }
                }
                $all_products[$keyzz]->{'review'} = $average; 
            }
            
            return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget));    
        } else {
            Session::flash('message', 'Sorry No Products For This Keyword!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('home');
        }               
    } 

    public function AllProducts () {
        $all_products = Products::Where('is_block',1)->OrderBy('id', 'desc')->paginate(32);
        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(32)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        $widget = Widget::first();
        

        if(($category) && (count($category) != 0)) {
            foreach ($category as $key => $value) {
                $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                $category[$key]->{'cat_count'} = count($a_c_products);               
            }
        }

        if(($all_products) && (count($all_products) != 0)) {
            $max = Products::max('discounted_price');
            $all_products->{'max_price'} = $max;
            foreach ($all_products as $keyzz => $valuezz) {
                $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                $average = 0;
                if(count($review) != 0) {
                    $sum = $review->sum('rating');
                    $count = count($review);
                    if($sum != 0) {
                        $average = $sum/$count;
                    } else {
                        $average = 0;
                    }
                }
                $all_products[$keyzz]->{'review'} = $average; 
            }
        }               
        return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget));
    }

    public function AllCatProducts ($main_cat) {
        $all_products = Products::Where('main_cat_name', $main_cat)->Where('is_block',1)->OrderBy('id', 'desc')->paginate(32);
        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(32)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        $widget = Widget::first();
        

        if(($category) && (count($category) != 0)) {
            foreach ($category as $key => $value) {
                $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                $category[$key]->{'cat_count'} = count($a_c_products);               
            }
        }

        if(($all_products) && (count($all_products) != 0)) {
            $max = Products::max('discounted_price');
            $all_products->{'max_price'} = $max;
            foreach ($all_products as $keyzz => $valuezz) {
                $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                $average = 0;
                if(count($review) != 0) {
                    $sum = $review->sum('rating');
                    $count = count($review);
                    if($sum != 0) {
                        $average = $sum/$count;
                    } else {
                        $average = 0;
                    }
                }
                $all_products[$keyzz]->{'review'} = $average; 
            }
        } 
        return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget));
    }

    public function AllFilterProducts (Request $request) {
        $data = Input::all();
        $p_id = [];
        $p_amount1 = "";
        $p_amount2 = "";
        $fil_cats  = "";
        $fil_ss_cats  = "";
        $fil_brnd  = "";
        $fil_atts  = "";
        $sort_by   = "";
        // print_r($data);die();

        if(isset($data['p_amount1'])) {
            $p_amount1  = $data['p_amount1'];
        }

        if(isset($data['p_amount2'])) {
            $p_amount2  = $data['p_amount2'];
        }

        if(isset($data['fil_cats'])) {
            $fil_cats  = $data['fil_cats'];
        }

        if(isset($data['fil_ss_cats'])) {
            $fil_ss_cats  = $data['fil_ss_cats'];
        }

        if(isset($data['fil_brnd'])) {
            $fil_brnd  = $data['fil_brnd'];
        }

        if(isset($data['fil_atts'])) {
            $fil_atts  = $data['fil_atts'];
        }

        if(isset($data['fil_sort'])) {
            $sort_by  = $data['fil_sort'];
        }

        // $all_products = Products::Where('is_block',1)->OrderBy('id', 'desc');
        $all_products = array();
        if(($p_amount1) && ($p_amount2) && ($fil_cats) && ($fil_ss_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($p_amount2) && ($fil_cats) && ($fil_ss_cats) && ($fil_brnd)) { 
            $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount1) && ($p_amount2) && ($fil_cats) && ($fil_ss_cats) && ($fil_atts)) { 
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($p_amount2) && ($fil_cats) && ($fil_brnd) && ($fil_atts)) { 
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($p_amount2) && ($fil_ss_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($fil_cats) && ($fil_ss_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount2) && ($fil_cats) && ($fil_ss_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($p_amount2) && ($fil_cats) && ($fil_ss_cats)) {
            $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
        } else if(($p_amount1) && ($p_amount2) && ($fil_cats) && ($fil_brnd)) {
            $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount1) && ($p_amount2) && ($fil_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($p_amount2) && ($fil_ss_cats) && ($fil_brnd)) {
            $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount1) && ($p_amount2) && ($fil_ss_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('sub_sub_cat_name', $fil_ss_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($p_amount2) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($fil_cats) && ($fil_ss_cats) && ($fil_brnd)) {
            $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount1) && ($fil_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($fil_cats) && ($fil_ss_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($fil_ss_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount2) && ($fil_cats) && ($fil_ss_cats) && ($fil_brnd)) {
            $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount2) && ($fil_cats) && ($fil_ss_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
            }
        } else if(($p_amount2) && ($fil_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount2) && ($fil_ss_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($fil_cats) && ($fil_ss_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($p_amount2) && ($fil_cats)) {
            $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('main_cat_name', $fil_cats)->Where('is_block',1);
        } else if(($p_amount1) && ($p_amount2) && ($fil_ss_cats)) {
            $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
        } else if(($p_amount1) && ($p_amount2) && ($fil_brnd)) {
            $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount1) && ($p_amount2) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('is_block',1);
                }
            } else {
                $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('is_block',1);
            }
        } else if(($p_amount1) && ($fil_cats) && ($fil_ss_cats)) {
            $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
        } else if(($p_amount1) && ($fil_cats) && ($fil_brnd)) {
            $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount1) && ($fil_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($fil_ss_cats) && ($fil_brnd)) {
            $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount1) && ($fil_ss_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('sub_sub_cat_name', $fil_ss_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
            }
        } else if(($p_amount1)&& ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount2) && ($fil_cats) && ($fil_ss_cats)) {
            $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
        } else if(($p_amount2) && ($fil_cats) && ($fil_brnd)) {
            $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount2) && ($fil_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('is_block',1);
            }
        } else if(($p_amount2) && ($fil_ss_cats) && ($fil_brnd)) {
            $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount2) && ($fil_ss_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('sub_sub_cat_name', $fil_ss_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
            }
        } else if(($p_amount2) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($fil_cats) && ($fil_ss_cats) && ($fil_brnd)) {
            $all_products = Products::Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($fil_cats) && ($fil_ss_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
            }
        } else if(($fil_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($fil_ss_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount1) && ($p_amount2)) {
            $all_products = Products::WhereBetween('discounted_price', [$p_amount1, $p_amount2])->Where('is_block',1);
        } else if(($p_amount1) && ($fil_cats)) {
            $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('main_cat_name', $fil_cats)->Where('is_block',1);
        } else if(($p_amount1) && ($fil_ss_cats)) {
            $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
        } else if(($p_amount1) && ($fil_brnd)) {
            $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount1) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('is_block',1);
            }
        } else if(($p_amount2) && ($fil_cats)) {
            $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('is_block',1);
        } else if(($p_amount2) && ($fil_ss_cats)) {
            $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
        } else if(($p_amount2) && ($fil_brnd)) {
            $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount2) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('is_block',1);
            }
        } else if(($fil_cats) && ($fil_ss_cats)) {
            $all_products = Products::Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
        } else if(($fil_cats) && ($fil_brnd)) {
            $all_products = Products::Where('main_cat_name', $fil_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        }  else if(($fil_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('main_cat_name', $fil_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('main_cat_name', $fil_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('main_cat_name', $fil_cats)->Where('is_block',1);
            }
        } else if(($fil_ss_cats) && ($fil_brnd)) {
            $all_products = Products::Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($p_amount1) && ($p_amount2) && ($fil_cats) && ($fil_ss_cats) && ($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('main_cat_name', $fil_cats)->Where('sub_sub_cat_name', $fil_ss_cats)->Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($fil_ss_cats) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('sub_sub_cat_name', $fil_ss_cats)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
            }
        } else if(($fil_brnd) && ($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::Where('brand', $fil_brnd)->WhereIn('id', $p_id)->Where('is_block',1);
                } else {
                    $all_products = Products::Where('brand', $fil_brnd)->Where('is_block',1);
                }
            } else {
                $all_products = Products::Where('brand', $fil_brnd)->Where('is_block',1);
            }
        } else if(($p_amount1)) {
            $all_products = Products::Where('discounted_price', '>=', $p_amount1)->Where('is_block',1); 
        } else if(($p_amount2)) {
            $all_products = Products::Where('discounted_price', '<=', $p_amount2)->Where('is_block',1); 
        } else if(($fil_cats)) {
            $all_products = Products::Where('main_cat_name', $fil_cats)->Where('is_block',1);
        } else if(($fil_ss_cats)) {
            $all_products = Products::Where('sub_sub_cat_name', $fil_ss_cats)->Where('is_block',1);
        } else if(($fil_brnd)) {
            $all_products = Products::Where('brand', $fil_brnd)->Where('is_block',1);
        } else if(($fil_atts)) {
            $att = ProductsAttributes::where('attribute_values',$fil_atts)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                if(count($p_id) != 0){
                    $all_products = Products::WhereIn('id', $p_id)->Where('is_block',1); 
                }
            }
        } else {
            Session::flash('message', 'No More Products by your Searched Items!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('all_products');
        }

        // print_r($sort_by);die();

        if(isset($sort_by) && !empty($sort_by)) {
            if($sort_by == "manual") {
                $all_products = $all_products->Where('featuredproduct_flag', 1);
            } elseif ($sort_by == "best-selling") {
                $all_products = $all_products->Where('best_seller_flag',1);
            } elseif ($sort_by == "title-ascending") {
                $all_products = $all_products->OrderBy('product_title', 'asc');
            } elseif ($sort_by == "title-descending") {
                $all_products = $all_products->OrderBy('product_title', 'desc');
            } elseif ($sort_by == "price-ascending") {
                $all_products = $all_products->OrderBy('discounted_price', 'asc');
            } elseif ($sort_by == "price-descending") {
                $all_products = $all_products->OrderBy('discounted_price', 'desc');
            } elseif ($sort_by == "created-ascending") {
                $all_products = $all_products->OrderBy('created_at', 'asc');
            } elseif ($sort_by == "star-ascending") {
                $prods = Products::Where('is_block',1)->OrderBy('id', 'desc')->get();
                if(($prods) && (count($prods) != 0)) {
                    foreach ($prods as $pkeyzz => $pvaluezz) {
                        $reviews = Review::Where('product_id', $pvaluezz->id)->Where('is_block', 1)->get();
                        $p_avgs = 0;
                        if(count($reviews) != 0) {
                            $p_sum = $reviews->sum('rating');
                            $cntz = count($reviews);
                            if($p_sum != 0) {
                                $p_avgs = $p_sum/$cntz;
                            } else {
                                $p_avgs = 0;
                            }
                        }
                        $prods[$pkeyzz]->{'review'} = $p_avgs; 
                    }
                }
                $p_ids = array();
                foreach ($prods as $pkey => $pvalue) {
                    if(isset($pvalue->review) && $pvalue->review != 0) {
                        $p_ids[] = $pvalue->id;
                    }
                }

                $all_products = $all_products->WhereIn('id', $p_ids);

            }
        } else {
            $all_products = $all_products->OrderBy('id', 'desc');
        }

        $all_products = $all_products->paginate(32);
        // print_r($all_products);die();

        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(32)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        $widget = Widget::first();
        

        if(($category) && (count($category) != 0)) {
            foreach ($category as $key => $value) {
                $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                $category[$key]->{'cat_count'} = count($a_c_products);               
            }
        }

        if(($all_products) && (count($all_products) != 0)) {
            $max = Products::max('discounted_price');
            $all_products->{'max_price'} = $max;
            foreach ($all_products as $keyzz => $valuezz) {
                $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                $average = 0;
                if(count($review) != 0) {
                    $sum = $review->sum('rating');
                    $count = count($review);
                    if($sum != 0) {
                        $average = $sum/$count;
                    } else {
                        $average = 0;
                    }
                }
                $all_products[$keyzz]->{'review'} = $average; 
            }
        } 

        if(count($all_products) != 0) {
            return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget, 'filter_cats'=>$fil_cats, 'filter_ss_cats'=>$fil_ss_cats, 'filter_brnd'=>$fil_brnd, 'filter_atts'=>$fil_atts, 'filter_amount1'=>$p_amount1, 'filter_amount2'=>$p_amount2, 'filter_sort'=>$sort_by));
        } else {
            Session::flash('message', 'No More Products by your Searched Items!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('all_products');
        }
    }

    public function ValueFilterProducts ($id) {
        if($id != 0) {
            $att = ProductsAttributes::where('attribute_values',$id)->get();
            if(isset($att) && count($att) != 0) {
                foreach ($att as $keyz => $valuez) {
                    $p_id[] = $valuez->product_id;
                }
                
                $all_products = "";
                if(count($p_id) != 0){
                    $all_products = Products::WhereIn('id', $p_id)->Where('is_block',1)->OrderBy('id', 'desc')->paginate(32);
                }

                $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
                $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(32)->get();
                $attributes = AttributesSettings::Where('is_block', 1)->get();
                $widget = Widget::first();

                if(($category) && (count($category) != 0)) {
                    foreach ($category as $key => $value) {
                        $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                        $category[$key]->{'cat_count'} = count($a_c_products);               
                    }
                }

                if(($all_products) && (count($all_products) != 0)) {
                    $max = Products::max('discounted_price');
                    $all_products->{'max_price'} = $max;
                    foreach ($all_products as $keyzz => $valuezz) {
                        $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                        $average = 0;
                        if(count($review) != 0) {
                            $sum = $review->sum('rating');
                            $count = count($review);
                            if($sum != 0) {
                                $average = $sum/$count;
                            } else {
                                $average = 0;
                            }
                        }
                        $all_products[$keyzz]->{'review'} = $average; 
                    }
                } 

                return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget));
            } else {
                Session::flash('message', 'No More Product for Your Search!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('all_products');
            }
        } else {
            Session::flash('message', 'No More Product for Your Search!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('all_products');
        }
    }

    public function SortFilterProducts () {
        $data = Input::all();
        $all_products = "";
        // print_r($data);die();

        if(isset($data['SortBy']) && !empty($data['SortBy'])) {
            if($data['SortBy'] == "manual") {
                $all_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->paginate(32);
            } elseif ($data['SortBy'] == "best-selling") {
                /*not develope*/
                $all_products = Products::Where('is_block',1)->OrderBy('id', 'desc')->paginate(32);
            } elseif ($data['SortBy'] == "title-ascending") {
                $all_products = Products::Where('is_block',1)->OrderBy('product_title', 'asc')->paginate(32);
            } elseif ($data['SortBy'] == "title-descending") {
                $all_products = Products::Where('is_block',1)->OrderBy('product_title', 'desc')->paginate(32);
            } elseif ($data['SortBy'] == "price-ascending") {
                $all_products = Products::Where('is_block',1)->OrderBy('discounted_price', 'asc')->paginate(32);
            } elseif ($data['SortBy'] == "price-descending") {
                $all_products = Products::Where('is_block',1)->OrderBy('discounted_price', 'desc')->paginate(32);
            } elseif ($data['SortBy'] == "created-ascending") {
                $all_products = Products::Where('is_block',1)->OrderBy('created_at', 'asc')->paginate(32);
            } elseif ($data['SortBy'] == "star-ascending") {
                $prods = Products::Where('is_block',1)->OrderBy('id', 'desc')->get();
                if(($prods) && (count($prods) != 0)) {
                    foreach ($prods as $pkeyzz => $pvaluezz) {
                        $reviews = Review::Where('product_id', $pvaluezz->id)->Where('is_block', 1)->get();
                        $p_avgs = 0;
                        if(count($reviews) != 0) {
                            $p_sum = $reviews->sum('rating');
                            $cntz = count($reviews);
                            if($p_sum != 0) {
                                $p_avgs = $p_sum/$cntz;
                            } else {
                                $p_avgs = 0;
                            }
                        }
                        $prods[$pkeyzz]->{'review'} = $p_avgs; 
                    }
                }
                $p_ids = array();
                foreach ($prods as $pkey => $pvalue) {
                    if(isset($pvalue->review) && $pvalue->review != 0) {
                        $p_ids[] = $pvalue->id;
                    }
                }
                $all_products = Products::WhereIn('id', $p_ids)->Where('is_block',1)->OrderBy('id', 'desc')->paginate(32);
            } else {
                $all_products = Products::Where('is_block',1)->OrderBy('id', 'desc')->paginate(32);
            }
                                                                   
            $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
            $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(32)->get();
            $attributes = AttributesSettings::Where('is_block', 1)->get();
            $widget = Widget::first();
            

            if(($category) && (count($category) != 0)) {
                foreach ($category as $key => $value) {
                    $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                    $category[$key]->{'cat_count'} = count($a_c_products);               
                }
            }

            if(($all_products) && (count($all_products) != 0)) {
                $max = Products::max('discounted_price');
                $all_products->{'max_price'} = $max;
                foreach ($all_products as $keyzz => $valuezz) {
                    $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                    $average = 0;
                    if(count($review) != 0) {
                        $sum = $review->sum('rating');
                        $count = count($review);
                        if($sum != 0) {
                            $average = $sum/$count;
                        } else {
                            $average = 0;
                        }
                    }
                    $all_products[$keyzz]->{'review'} = $average; 
                }
            } 
            return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget));
        } else {
            Session::flash('message', 'No More Product for Your Search!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('all_products');
        }
    }

    public function OfferProducts () {
        $offer_products = Offers::Where('is_block',1)->where(function($q) {
            $q->where('offer_end', '>=', date("Y-m-d H:i:s"))
            ->orWhereNull('offer_end');
            })->OrderBy('id', 'desc')->paginate(12);

        if(sizeof($offer_products) != 0) {
            return View::make("front_end.offer_products")->with(array('offer_products'=>$offer_products));
        } else {
            Session::flash('message', 'Offers Not Available!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('home');
        }

    }

    public function OfferProductsDetails ($id) {
        $offer_products = Offers::Where('is_block',1)->Where('id', $id)->first();
        if($offer_products) {
            $main_prods = [];
            $offer_prods = [];
            $offers = OffersSub::Where('offer', $offer_products->id)->get();
            if(sizeof($offers) != 0) {
                foreach ($offers as $key => $value) {
                    if($value->type == 1) {
                        array_push($main_prods, $value->id);
                    } else if($value->type == 2) {
                        array_push($offer_prods, $value->id);
                    }
                }

                if(sizeof($main_prods) != 0) {
                    $main_products = OffersSub::WhereIn('id', $main_prods)->get();
                    if(sizeof($offer_prods) != 0) {
                        $offer_pds = OffersSub::WhereIn('id', $offer_prods)->get();
                        return View::make("front_end.offer_products_dets")->with(array('offer_products'=>$offer_products, 'main_products'=>$main_products, 'offer_pds'=>$offer_pds));
                    } else {
                        Session::flash('message', 'Offers Not Available!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('offer_products');
                    }
                } else {
                    Session::flash('message', 'Offers Not Available!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('offer_products');
                }
            } else {
                Session::flash('message', 'This Offers is closed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('offer_products');
            }
        } else {
            Session::flash('message', 'Offers Not Available!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('offer_products');
        }

    }

    /*public function SubCategory ($main_cat) {
        $sub_cat = SubCategoryManagementSettings::Where('main_cat_name', $main_cat)->Where('is_block',1)->paginate(12);

        return View::make("front_end.sub_category")->with(array('sub_cat'=>$sub_cat));
    }*/

    public function SubCategory ($main_cat) {
        $sub_cat = SubCategoryManagementSettings::Where('main_cat_name', $main_cat)->Where('is_block',1)->paginate(32);
        $all_products = Products::Where('main_cat_name', $main_cat)->Where('is_block',1)->OrderBy('id', 'desc')->paginate(32);
        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(32)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        $widget = Widget::first();
        

        if(($category) && (count($category) != 0)) {
            foreach ($category as $key => $value) {
                $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                $category[$key]->{'cat_count'} = count($a_c_products);               
            }
        }

        if(($all_products) && (count($all_products) != 0)) {
            $max = Products::max('discounted_price');
            $all_products->{'max_price'} = $max;
            foreach ($all_products as $keyzz => $valuezz) {
                $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                $average = 0;
                if(count($review) != 0) {
                    $sum = $review->sum('rating');
                    $count = count($review);
                    if($sum != 0) {
                        $average = $sum/$count;
                    } else {
                        $average = 0;
                    }
                }
                $all_products[$keyzz]->{'review'} = $average; 
            }
        } 
        return View::make("front_end.sub_category")->with(array('sub_cat'=>$sub_cat, 'all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget));
    }

    public function SubSubCategory ($sub_cat) {
        $sub_sub_cat = SubSubCategoryManagementSettings::Where('sub_cat_name', $sub_cat)->Where('is_block',1)->paginate(32);
        $all_products = Products::Where('is_block',1)->OrderBy('id', 'desc')->paginate(32);
        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(32)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        $widget = Widget::first();
        

        if(($category) && (count($category) != 0)) {
            foreach ($category as $key => $value) {
                $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                $category[$key]->{'cat_count'} = count($a_c_products);               
            }
        }

        if(($all_products) && (count($all_products) != 0)) {
            $max = Products::max('discounted_price');
            $all_products->{'max_price'} = $max;
            foreach ($all_products as $keyzz => $valuezz) {
                $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                $average = 0;
                if(count($review) != 0) {
                    $sum = $review->sum('rating');
                    $count = count($review);
                    if($sum != 0) {
                        $average = $sum/$count;
                    } else {
                        $average = 0;
                    }
                }
                $all_products[$keyzz]->{'review'} = $average; 
            }
        } 
        return View::make("front_end.sub_sub_category")->with(array('sub_sub_cat'=>$sub_sub_cat, 'all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget));
    }

    public function CategoryProducts ($main_cat) {
        $filter_cats = $main_cat;
        // $products = Products::Where('main_cat_name', $main_cat)->Where('is_block',1)->paginate(12);

        // return View::make("front_end.category_products")->with(array('products'=>$products));

        $all_products = Products::Where('main_cat_name', $main_cat)->Where('is_block',1)->paginate(32);
        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(12)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        $widget = Widget::first();
        

        if(($category) && (count($category) != 0)) {
            foreach ($category as $key => $value) {
                $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                $category[$key]->{'cat_count'} = count($a_c_products);               
            }
        }

        if(($all_products) && (count($all_products) != 0)) {
            $max = Products::max('discounted_price');
            $all_products->{'max_price'} = $max;
            foreach ($all_products as $keyzz => $valuezz) {
                $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                $average = 0;
                if(count($review) != 0) {
                    $sum = $review->sum('rating');
                    $count = count($review);
                    if($sum != 0) {
                        $average = $sum/$count;
                    } else {
                        $average = 0;
                    }
                }
                $all_products[$keyzz]->{'review'} = $average; 
            }
            
            return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget, 'filter_cats'=>$filter_cats));
        } else {
            Session::flash('message', 'Category Products Not Available!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('all_products');
        }               
    }

    public function SubSubCategoryProducts ($sub_sub_cat) {
        $filter_ss_cats = $sub_sub_cat;
        // $products = Products::Where('sub_sub_cat_name', $sub_sub_cat)->Where('is_block',1)->paginate(12);

        // return View::make("front_end.sub_sub_category_products")->with(array('products'=>$products));

        $all_products = Products::Where('sub_sub_cat_name', $sub_sub_cat)->Where('is_block',1)->paginate(32);
        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(12)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        $widget = Widget::first();
        

        if(($category) && (count($category) != 0)) {
            foreach ($category as $key => $value) {
                $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                $category[$key]->{'cat_count'} = count($a_c_products);               
            }
        }

        if(($all_products) && (count($all_products) != 0)) {
            $max = Products::max('discounted_price');
            $all_products->{'max_price'} = $max;
            foreach ($all_products as $keyzz => $valuezz) {
                $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                $average = 0;
                if(count($review) != 0) {
                    $sum = $review->sum('rating');
                    $count = count($review);
                    if($sum != 0) {
                        $average = $sum/$count;
                    } else {
                        $average = 0;
                    }
                }
                $all_products[$keyzz]->{'review'} = $average; 
            }
            
            return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget, 'filter_ss_cats'=>$filter_ss_cats));
        } else {
            Session::flash('message', 'Sub Sub Category Products Not Available!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('all_products');
        }            
    }

    public function BrandsProducts ($id) {
        $fil_brnd = $id;
        // $products = Products::Where('brand', $id)->Where('is_block',1)->paginate(12);

        // return View::make("front_end.brands_products")->with(array('products'=>$products));

        $all_products = Products::Where('brand', $id)->Where('is_block',1)->paginate(32);
        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(12)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        $widget = Widget::first();
        

        if(($category) && (count($category) != 0)) {
            foreach ($category as $key => $value) {
                $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                $category[$key]->{'cat_count'} = count($a_c_products);               
            }
        }

        if(($all_products) && (count($all_products) != 0)) {
            $max = Products::max('discounted_price');
            $all_products->{'max_price'} = $max;
            foreach ($all_products as $keyzz => $valuezz) {
                $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                $average = 0;
                if(count($review) != 0) {
                    $sum = $review->sum('rating');
                    $count = count($review);
                    if($sum != 0) {
                        $average = $sum/$count;
                    } else {
                        $average = 0;
                    }
                }
                $all_products[$keyzz]->{'review'} = $average; 
            }
            
            return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget, 'filter_brnd'=>$fil_brnd));
        } else {
            Session::flash('message', 'Brand Products Not Available!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('all_products');
        }               
    }

    public function ViewProducts ($id) {
        $products = Products::Where('id', $id)->Where('is_block',1)->first();
        if($products) {
            $related = Products::Where('sub_sub_cat_name', $products->sub_sub_cat_name)->Where('id', '!=', $id)->Where('is_block',1)->get();
        } else {
            $related = array();
        }
        $review = Review::Where('is_block', 1)->Where('product_id', $id)->get();
        $stars = array();
        
        $average = 0;
        if(count($review) != 0) {
            $sum = $review->sum('rating');
            $count = count($review);
            if($sum != 0) {
                $average = $sum/$count;
            } else {
                $average = 0;
            }

            $stars['review5'] = count(Review::Where('is_block', 1)->Where('product_id', $id)->Where('rating', 5)->get());
            $stars['review4'] = count(Review::Where('is_block', 1)->Where('product_id', $id)->Where('rating', 4)->get());
            $stars['review3'] = count(Review::Where('is_block', 1)->Where('product_id', $id)->Where('rating', 3)->get());
            $stars['review2'] = count(Review::Where('is_block', 1)->Where('product_id', $id)->Where('rating', 2)->get());
            $stars['review1'] = count(Review::Where('is_block', 1)->Where('product_id', $id)->Where('rating', 1)->get());
        } else {
            $average = 0;
        }

        $att_id = []; 
        $att_vals_id = [];
        if($products) {
            $products['images'] = ProductsImages::where('product_id', $products->id)->Where('is_block', 1)->get();
            $products['att'] = ProductsAttributes::where('product_id', $products->id)->Where('is_block', 1)->get();
            $p_atts = ProductsAttributes::where('product_id', $products->id)->Where('is_block', 1)->groupBy('attribute_name')->get();
            $p_atts_vals = ProductsAttributes::where('product_id', $products->id)->Where('is_block', 1)->get();
            if(sizeof($p_atts) != 0) {
                foreach ($p_atts as $key => $value) {
                    array_push($att_id, $value->attribute_name);                   
                }
            }

            if(sizeof($p_atts_vals) != 0) {
                foreach ($p_atts_vals as $key => $value) {
                    array_push($att_vals_id, $value->attribute_values);                   
                }
            }

            if(sizeof($att_id) != 0) {
                $products['att_fields'] = AttributesFields::WhereIn('id', $att_id)->get();
            }

            if(sizeof($att_vals_id) != 0) {
                $products['att_values'] = AttributesSettings::WhereIn('id', $att_vals_id)->get();
            }
            // print_r($products['att_fields']);
            // print_r($products['att_values']);die();
            // print_r($att_id);die();
            // print_r($p_atts);die();
        } else {
            Session::flash('message', 'View Not Possible!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('home');
        }

        return View::make("front_end.view_products")->with(array('products'=>$products, 'related'=>$related, 'review'=>$review, 'stars'=>$stars, 'average'=>$average));
    }

    public function TagProducts ($id) {
        $tg = Tags::Where('id', $id)->Where('is_block',1)->first();
        $ids = array();
        if($tg) {
            $prod = Products::Where('is_block',1)->get();
            if(($prod) && (count($prod) != 0)) {
                foreach ($prod as $key => $value) {
                    $tags = json_decode($value->tags);
                    if($tags && count($tags) != 0) {
                        foreach ($tags as $keys => $values) {
                            if(($tg->id == $values)) {
                                $ids [] = $value->id;
                            }
                        }
                    }
                }
            }
        }

        // $products = Products::WhereIn('id', $ids)->Where('is_block',1)->paginate(12);

        // return View::make("front_end.tag_products")->with(array('products'=>$products));

        $all_products = Products::WhereIn('id', $ids)->Where('is_block',1)->paginate(32);
        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(12)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        $widget = Widget::first();
        

        if(($category) && (count($category) != 0)) {
            foreach ($category as $key => $value) {
                $a_c_products = Products::Where('main_cat_name', $value->id)->Where('is_block',1)->OrderBy('id', 'desc')->get();
                $category[$key]->{'cat_count'} = count($a_c_products);               
            }
        }

        if(($all_products) && (count($all_products) != 0)) {
            $max = Products::max('discounted_price');
            $all_products->{'max_price'} = $max;
            foreach ($all_products as $keyzz => $valuezz) {
                $review = Review::Where('product_id', $valuezz->id)->Where('is_block', 1)->get();
                $average = 0;
                if(count($review) != 0) {
                    $sum = $review->sum('rating');
                    $count = count($review);
                    if($sum != 0) {
                        $average = $sum/$count;
                    } else {
                        $average = 0;
                    }
                }
                $all_products[$keyzz]->{'review'} = $average; 
            }
        }               
        return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes, 'widget'=>$widget));
    }

    public function AttributesImage( Request $request) { 
        $id = 0;
        $product_id = 0;
        $image = 0;
        $price = 0;
        $data = array();
        if($request->ajax() && isset($request->id) && isset($request->product_id)){
            $id = $request->id;
            $product_id = $request->product_id;
            if($id != 0 && $product_id != 0) {
                $att = ProductsAttributes::where('attribute_values',$id)->where('product_id',$product_id)->first();
                if($att){
                    $image = asset('/images/attributes/'.$att->image);
                    $price = $att->att_price;
                    $data = array('image'=>$image, 'price'=>$price);
                }          
            }
        }
        echo json_encode($data);
    }

    public function Pages ($name) {
        $cms = CMSPageManagement::where('page_name',$name)->first();
        return View::make("front_end.pages")->with(array('cms'=>$cms));
    }

    public function Terms () {
        $terms = TermsCMSSettings::first();
        return View::make("front_end.terms_conditions")->with(array('terms'=>$terms));
    }

    public function About () {
        $about = AboutUsCMSSettings::first();
        return View::make("front_end.about")->with(array('about'=>$about));
    }

    public function SellOnEcambiar () {
        return View::make("front_end.sell_on_ecambiar");
    }

    public function StoreSellOnEcambiar(Request $request) {
        $rules = array(
            'first_name'              => 'required',
            'last_name'               => 'nullable',
            'bussiness_name'          => 'required',
            'buss_reg_no'             => 'nullable',
            'is_gst'                  => 'required',
            'gstn_no'                 => 'required_if:is_gst,==,1',
            'email'                   => 'required|email|unique:users,email',
            'password'                => 'required|min:5',
            'password_salt'           => 'required|min:5|same:password',
            'profile_img'             => 'nullable',
            'remember_token'          => 'nullable',
            'country'                 => 'required',
            'state'                   => 'required',
            'city'                    => 'required',
            'phone'                   => 'required|numeric|unique:users,phone',
            'phone2'                  => 'nullable|numeric|unique:users,phone2',
            'address1'                => 'required',
            'address2'                => 'required',
            'pincode'                 => 'required|numeric|integer',
            'commission'              => 'nullable|numeric',
            'return_commission'       => 'nullable|numeric',
            'payment_account_details' => 'nullable',
            'store_name'              => 'required',
            'store_phone'             => 'required|numeric|unique:stores,store_phone',
            'store_address1'          => 'required',
            'store_address2'          => 'required',
            'store_country'           => 'required',
            'store_state'             => 'required',
            'store_city'              => 'required',
            'store_zipcode'           => 'required',
            'meta_keyword'            => 'nullable',
            'meta_description'        => 'nullable',
            'website'                 => 'nullable',
            'slogan'                  => 'nullable',
            'stores_image'            => 'nullable',
            'is_approved'             => 'nullable',
            'is_block'                => 'nullable',
            'user_type'               => 'required',
            'login_type'              => 'nullable',

            'd_name'                  => 'required',
            'd_image'                 => 'required',
        );

        $messages=[
            'password_salt.required'=>'The confirm password field is required.',
            'password_salt.min'=>'The confirm password must be at least 5 characters.',
            'password_salt.same'=>'The confirm password and password must match.',
            'd_image.required'=>'The document name and file field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            // return Redirect::back()->withInput()->withErrors($validator);
            return View::make('front_end.sell_on_ecambiar')->withErrors($validator);
        } else {
            $data = Input::all();
            $ps = "gj";
            $pe = "ja";
            $merchant = new User();

            if($merchant) {
                $img_files111 = Input::file('profile_img');
                if(isset($img_files111)) {
                    $file_name = $img_files111->getClientOriginalName();
                    $date = date('M-Y');
                    // $file_path = '../public/images/profile_img/'.$date;
                    $file_path = 'images/profile_img/'.$date;
                    $img_files111->move($file_path, $file_name);
                    $merchant->profile_img = $date.'/'.$file_name;
                } else {
                    $merchant->profile_img = NULL;
                }

                $merchant->first_name                = $data['first_name'];
                $merchant->last_name                 = $data['last_name'];
                $merchant->bussiness_name            = $data['bussiness_name'];
                $merchant->buss_reg_no               = $data['buss_reg_no'];
                $merchant->is_gst                    = $data['is_gst'];
                $merchant->gstn_no                   = $data['gstn_no'];
                $merchant->email                     = $data['email'];
                $merchant->password                  = md5($data['password']);
                $merchant->password_salt             = $ps.$data['password_salt'].$pe;
                $merchant->country                   = $data['country'];
                $merchant->state                     = $data['state'];
                $merchant->city                      = $data['city'];
                $merchant->phone                     = $data['phone'];
                $merchant->phone2                    = $data['phone2'];
                $merchant->address1                  = $data['address1'];
                $merchant->address2                  = $data['address2'];
                $merchant->pincode                   = $data['pincode'];

                $merchant->commission                = 0;   
                $merchant->return_commission         = 0;
                $merchant->payment_account_details   = NULL;
                $merchant->user_type                 = 3;
                $merchant->is_approved               = 0;
                $merchant->is_block                  = 0;
                $merchant->login_type                = 1;

                $pass = $data['password'];
                if($merchant->save()) {
                    if($data['d_name'] && count($data['d_name']) != 0) {
                        foreach ($data['d_name'] as $key => $value) {
                            $d_images = new MerchantsDocuments();

                            if(isset($data['d_image'][$key])) {
                                $file_name = $data['d_image'][$key]->getClientOriginalName();
                                $date = date('M-Y');
                                // $file_path = '../public/documents/'.$date;
                                $file_path = 'documents/'.$date;
                                $data['d_image'][$key]->move($file_path, $file_name);
                                $d_images->image       = $date.'/'.$file_name;
                            } else {
                                $d_images->image       = NULL;
                            }

                            $d_images->merchant  = $merchant->id; 

                            $d_images->d_name      = $value;     
                            $d_images->is_block    = 1;

                            $d_images->save();
                        }
                    }

                    $store = new Store();

                    if($store) {
                        $store->merchant                  = $merchant->id;
                        $store->store_name                = $data['store_name'];
                        $store->store_phone               = $data['store_phone'];
                        $store->store_address1            = $data['store_address1'];
                        $store->store_address2            = $data['store_address2'];
                        $store->store_country             = $data['store_country'];
                        $store->store_state               = $data['store_state'];
                        $store->store_city                = $data['store_city'];
                        $store->store_zipcode             = $data['store_zipcode'];
                        $store->meta_keyword              = $data['meta_keyword'];
                        $store->meta_description          = $data['meta_description'];
                        // $store->website                   = $data['website'];
                        $store->slogan                    = $data['slogan'];
                        $store->is_block                  = 1;

                        if($merchant->user_type == 2) {
                            $store->login_type                = 1;
                        } else {
                            $store->login_type                = 2;
                        }

                        $img_files = Input::file('stores_image');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/stores_image/'.$date;
                            $file_path = 'images/stores_image/'.$date;
                            $img_files->move($file_path, $file_name);
                            $store->stores_image = $date.'/'.$file_name;
                        } else {
                            $store->stores_image = NULL;
                        }

                        if($store->save()) {
                            $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                            $admin_email = "info@ecambiar.com";
                            if($adm) {
                                $admin_email = $adm->email;
                            }

                            $logos = \DB::table('logo_settings')->first();
                            $logo_path = 'images/logo';
                            $logo = "";
                            if($logos) {
                                $logo = asset($logo_path.'/'.$logos->logo_image);
                            } else {
                                $logo = asset('images/logo.png');
                            }

                            $general = \DB::table('general_settings')->first();
                            $site_name = "ECambiar";
                            if($general){
                                $site_name = $general->site_name;
                            } else {
                                $site_name = "ECambiar";
                            }

                            $name = $merchant->first_name.' '.$merchant->last_name;
                            $email = $merchant->email;
                            $ph = $merchant->phone;

                            $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                            $headers.= "MIME-Version: 1.0\r\n";
                            // $headers.= "From: $admin_email" . "\r\n";
                            $headers.= "From: noreply@ecambiar.com" . "\r\n";
                            $to = $email;
                            $subject = "Merchants Registration";

                            $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                    <h2 style="color: #ff5c00;margin-top: 0px;">Register Process Success</h2>
                                    <p>"Thank You For Your Registering with us".</p>
                                        <p>Our Admin Team Will Evaluate and Approve Soon.</p>
                                        <p>Any Queries Please email at <a href="mailto:info@ecambiar.com" target="_blank" style="color: black;text-decoration: none;">info@ecambiar.com</a>.</p>
                                    <p></p>
                                    <p>Thank You.</p>
                                    <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                    <p>Thanks & Regards,</p>
                                    <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                </div>
                            </div>';

                            $msg = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                    <h2 style="color: #ff5c00;margin-top: 0px;">Merchants Details</h2>
                                    <table align="center" style=" text-align: center;">
                                        <tr>
                                            <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Name</th>
                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$name.'</td>
                                        </tr>
                                        <tr>
                                            <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">E-Mail</th>
                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : <a href="mailto:'.$email.'" target="_blank" style="color: #333;text-decoration: none;">'.$email.'</a></td>
                                        </tr>
                                        <tr>
                                            <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Phone No</th>
                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$ph.'</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;text-transform: uppercase;color: #333;padding-bottom: 12px;font-weight:bold;width: 120px;">Password</th>
                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$pass.'</td>
                                        </tr>
                                    </table>
                                    
                                    <p>New Merchant Details. Verify and Approve this Merchant</p>
                                    <p></p>
                                    <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                    <p>Thanks & Regards,</p>
                                    <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                </div>
                            </div>';
                            
                            if (mail($to,$subject,$txt,$headers) && mail($admin_email,$subject,$msg,$headers)) {
                                Session::flash('message', 'Thanks, we received your Vendor registration request, we will review the details and get back to you soon!'); 
                                Session::flash('alert-class', 'alert-success');
                                return redirect()->route('home');
                            } else {
                                Session::flash('message', 'Thanks, we received your Vendor registration request, we will review the details and get back to you soon!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('home');
                            }
                        } else{
                            Session::flash('message', 'Register Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('sell_on_ecambiar');
                        }  
                    } else{
                        Session::flash('message', 'Register Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('sell_on_ecambiar');
                    }
                } else{
                    Session::flash('message', 'Register Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('sell_on_ecambiar');
                }  
            } else{
                Session::flash('message', 'Register Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('sell_on_ecambiar');
            }
        }
    }

    public function SignIn () {
        $l_usr = session()->get('user');
        if(isset($_COOKIE["user"]) && !empty($_COOKIE["user"])) {
            $cook = $_COOKIE["user"];
            $cook = json_decode($cook);
            $user = User::Where('id', $cook->id)->first();
            if($user) {
                if(($user->user_type == 4)) {
                    if($user->verification == 1) {
                        if($user->is_block == 1) {
                            session()->forget('user');
                            Session::flash('message', 'Login Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            Session::put('user', $user);

                            $users = session()->get('user');
                            $ses_carts = session()->get('cart');
                            $cartData = array();

                            if(isset($ses_carts)) {
                                Carts::Where('user_id', $users->id)->delete();
                                foreach ($ses_carts as $key => $value) {
                                    $carts = new Carts();
                                    if($carts) {
                                        $carts->product_id  = $value['product_id'];
                                        $carts->user_id     = $users->id;
                                        $carts->name        = (isset($value['name'])) ? $value['name'] : NULL;
                                        $carts->original_price  = (isset($value['original_price'])) ? $value['original_price'] : 0;
                                        $carts->product_cost       = (isset($value['product_cost'])) ? $value['product_cost'] : 0;
                                        $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
                                        $carts->tax_amount   = (isset($value['tax_amount'])) ? $value['tax_amount'] : 0;
                                        $carts->total_price   = (isset($value['total_price'])) ? $value['total_price'] : 0;
                                        $carts->att_name  = (isset($value['att_name'])) ? $value['att_name'] : NULL;
                                        $carts->att_value  = (isset($value['att_value'])) ? $value['att_value'] : NULL;
                                        $carts->tax  = (isset($value['tax'])) ? $value['tax'] : NULL;
                                        $carts->tax_type  = (isset($value['tax_type'])) ? $value['tax_type'] : NULL;
                                        $carts->service_charge  = (isset($value['service_charge'])) ? $value['service_charge'] : NULL;
                                        $carts->shiping_charge  = (isset($value['shiping_charge'])) ? $value['shiping_charge'] : NULL;
                                        $carts->image       = (isset($value['image'])) ? $value['image'] : NULL;
                                        $carts->qty         = (isset($value['qty'])) ? $value['qty'] : 1;
                                        $carts->notes       = (isset($value['notes'])) ? $value['notes'] : NULL;
                                        $carts->notes       = (isset($value['notes'])) ? $value['notes'] : NULL;
                                        $carts->is_offer       = (isset($value['is_offer'])) ? $value['is_offer'] : 'No';
                                        $carts->offer_id       = (isset($value['offer_id'])) ? $value['offer_id'] : NULL;
                                        $carts->offer_det_id       = (isset($value['offer_det_id'])) ? $value['offer_det_id'] : NULL;
                                        $carts->cart_key       = (isset($value['cart_key'])) ? $value['cart_key'] : NULL;
                                        $carts->cart_del       = (isset($value['cart_del'])) ? $value['cart_del'] : NULL;
                                        $carts->is_block    = 1;

                                        $carts->save();
                                    }
                                }
                            }

                            return redirect()->route('home');
                        } else {
                            session()->forget('user');
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
                            Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('signin');
                        }
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('signin');
                    }
                } else {
                    session()->forget('user');
                    if(isset($_COOKIE["user"])) {
                        setcookie ("user","");
                    }        
                    Session::flash('message', 'Login failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('signin');
                }
            } else{
                Session::flash('message', 'Login Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else if($l_usr) {
            if(($l_usr->user_type == 4)) {
                if($l_usr->verification == 1) {
                    if($l_usr->is_block == 1) {
                        session()->forget('user');
                        Session::flash('message', 'Login Successfully!'); 
                        Session::flash('alert-class', 'alert-success');
                        Session::put('user', $l_usr);

                        $users = session()->get('user');
                        $ses_carts = session()->get('cart');
                        $cartData = array();

                        if(isset($ses_carts)) {
                            Carts::Where('user_id', $users->id)->delete();
                            foreach ($ses_carts as $key => $value) {
                                $carts = new Carts();
                                if($carts) {
                                    $carts->product_id  = $value['product_id'];
                                    $carts->user_id     = $users->id;
                                    $carts->name        = (isset($value['name'])) ? $value['name'] : NULL;
                                    $carts->original_price  = (isset($value['original_price'])) ? $value['original_price'] : 0;
                                    $carts->product_cost       = (isset($value['product_cost'])) ? $value['product_cost'] : 0;
                                    $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
                                    $carts->tax_amount   = (isset($value['tax_amount'])) ? $value['tax_amount'] : 0;
                                    $carts->total_price   = (isset($value['total_price'])) ? $value['total_price'] : 0;
                                    $carts->att_name  = (isset($value['att_name'])) ? $value['att_name'] : NULL;
                                    $carts->att_value  = (isset($value['att_value'])) ? $value['att_value'] : NULL;
                                    $carts->tax  = (isset($value['tax'])) ? $value['tax'] : NULL;
                                    $carts->tax_type  = (isset($value['tax_type'])) ? $value['tax_type'] : NULL;
                                    $carts->service_charge  = (isset($value['service_charge'])) ? $value['service_charge'] : NULL;
                                    $carts->shiping_charge  = (isset($value['shiping_charge'])) ? $value['shiping_charge'] : NULL;
                                    $carts->image       = (isset($value['image'])) ? $value['image'] : NULL;
                                    $carts->qty         = (isset($value['qty'])) ? $value['qty'] : 1;
                                    $carts->notes       = (isset($value['notes'])) ? $value['notes'] : NULL;
                                    $carts->is_offer       = (isset($value['is_offer'])) ? $value['is_offer'] : 'No';
                                    $carts->offer_id       = (isset($value['offer_id'])) ? $value['offer_id'] : NULL;
                                    $carts->offer_det_id       = (isset($value['offer_det_id'])) ? $value['offer_det_id'] : NULL;
                                    $carts->cart_key       = (isset($value['cart_key'])) ? $value['cart_key'] : NULL;
                                    $carts->cart_del       = (isset($value['cart_del'])) ? $value['cart_del'] : NULL;
                                    $carts->is_block    = 1;

                                    $carts->save();
                                }
                            }
                        }

                        return redirect()->route('home');
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('signin');
                    }
                } else {
                    session()->forget('user');
                    if(isset($_COOKIE["user"])) {
                        setcookie ("user","");
                    }
                    Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('signin');
                }
            } else {
                session()->forget('user');
                if(isset($_COOKIE["user"])) {
                    setcookie ("user","");
                }        
                Session::flash('message', 'Login failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            return View::make("front_end.signin");
        }                          
    }

    public function SignUp () {
        $secure = loginSecurity::all();
        return View::make("front_end.signup")->with(array('secure'=>$secure));
    }

    public function Register(Request $request) {
        $rules = array(
            'first_name'              => 'required',
            'last_name'               => 'nullable',
            'email'                   => 'required|email|unique:users,email',
            'password'                => 'required|min:5',
            'password_salt'           => 'nullable',
            'profile_img'             => 'nullable',
            'question'                => 'required|unique:login_securities,question',
            'answer'                  => 'required',
            'remember_token'          => 'nullable',
            'phone'                   => 'required|numeric|unique:users,phone',
            'verification'            => 'nullable',
            'is_approved'             => 'nullable',
            'is_block'                => 'nullable',
            'user_type'               => 'nullable',
            'login_type'              => 'nullable',
        );

        $messages=[
            'password.required'=>'The password field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            // return Redirect::back()->withInput()->withErrors($validator);
            // return View::make('front_end.signup')->withErrors($validator);
            return redirect()->route('signup')->withErrors($validator);
        } else {
            $data = Input::all();
            $ps = "gj";
            $pe = "ja";
            $users = new User();

            if($users) {
                $img_files = Input::file('profile_img');
                if(isset($img_files)) {
                    $file_name = $img_files->getClientOriginalName();
                    $date = date('M-Y');
                    // $file_path = '../public/images/profile_img/'.$date;
                    $file_path = 'images/profile_img/'.$date;
                    $img_files->move($file_path, $file_name);
                    $users->profile_img = $date.'/'.$file_name;
                } else {
                    $users->profile_img = NULL;
                }
                            
                $users->first_name                = $data['first_name'];
                $users->last_name                 = $data['last_name'];
                $users->email                     = $data['email'];
                $users->password                  = md5($data['password']);
                $users->password_salt             = $ps.$data['password'].$pe;
                $users->question                  = $data['question'];
                $users->answer                    = $data['answer'];
                $users->phone                     = $data['phone'];
                $users->user_type                 = 4;
                if(isset($data['is_approved'])) {
                    $users->is_approved           = $data['is_approved'];
                } else {
                    $users->is_approved           = 1;
                }
                $users->verification              = "GJ".uniqid();
                $users->is_block                  = 1;
                $users->login_type                = 1;

                $pass = $data['password'];
                if($users->save()) {
                    $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                    $admin_email = "info@ecambiar.com";
                    if($adm) {
                        $admin_email = $adm->email;
                    }

                    // $r_url = url('/activation/'.$users->verification);
                    $r_url = route('activation', ['code' => $users->verification]);
                    $mail_img = asset('images/mail.png');
                    $phone_img = asset('images/phone.png');
                    $logos = \DB::table('logo_settings')->first();
                    $logo_path = 'images/logo';
                    $logo = "";
                    if($logos) {
                        $logo = asset($logo_path.'/'.$logos->logo_image);
                    } else {
                        $logo = asset('images/logo.png');
                    }

                    $general = \DB::table('general_settings')->first();
                    $site_name = "ECambiar";
                    if($general){
                        $site_name = $general->site_name;
                    } 

                    $contacts = \DB::table('email_settings')->first();
                    $c_email = "info@ecambiar.com";
                    $c_phone = "971 925 6546";
                    if($contacts) {
                        $c_email = $contacts->contact_email;
                        $c_phone = $contacts->contact_phone1;
                    }

                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    // $headers.= "From: $admin_email" . "\r\n";
                    $headers.= "From: noreply@ecambiar.com" . "\r\n";
                    $to = $users->email;
                    $subject = "Activate Account";
                    $txt = '<div style="margin: 30px auto 20px;border: 1px solid #ff5c00;width: 602px;">
                        <table width="600" align="center" cellpadding="0" cellspacing="0" height="74">
                            <tbody>
                                <tr bgcolor="#ffffff">
                                    <td style="padding-left:20px;padding-top:10px;padding-bottom:10px" height="70"><a href="'.route('home').'"><img src="'.$logo.'" border="0"></a></td>
                                </tr> 
                                <tr bgcolor="#ff5c00" height="7px">
                                    <td><br></td>
                                </tr>
                            </tbody>
                        </table>

                        <table width="600" align="center">
                            <tbody>
                                <tr>
                                    <td style="padding:10px;font-size:15px;color:#333333;font-weight:bold;font-family:Segoe UI,Arial,Helvetica,sans-serif">Your registration is completed..! Click on the link below to activate your account.<br></td>
                                </tr>
                            </tbody>
                        </table>

                        <table width="600px" align="center" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Your Username</b> </td>
                                    <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b><a href="mailto:'.$users->email.'" target="_blank">'.$users->email.'</a></b> </td>
                                </tr>
                        
                                <tr>
                                    <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Your Password</b> </td>
                                    <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>'.$pass.'</b> </td>
                                </tr>
                        
                                <tr>
                                    <td colspan="2" style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Please click on link below to activate your account</b> </td>
                                </tr>
                        
                                <tr>
                                    <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>link</b> </td>
                                    <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b><a href="'.$r_url.'" target="_blank">'.$r_url.'</a></b> </td>
                                </tr>
                            </tbody>
                        </table>

                        <table width="600" align="center" cellpadding="0" cellspacing="0" height="61">
                            <tbody>
                                <tr bgcolor="#ffffff">
                                    <td colspan="5" height="11"><br></td>
                                </tr>
                                
                                <tr bgcolor="#ff5c00" height="7px">
                                    <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec;padding-left:10px" width="100" height="48">Contact Us : </td>

                                    <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec" width="29"><img src="'.$mail_img.'"></td>

                                    <td style="font-family:Segoe UI,Arial;font-size:11px;color:#ececec" width="135"><a href="mailto:'.$c_email.'" style="color:#ececec;text-decoration:none"> '.$c_email.'</a></td>

                                    <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec" width="28"><img src="'.$phone_img.'" style="margin-left:8px;"></td>

                                    <td style="font-family:Segoe UI,Arial;font-size:11px;color:#ececec" width="300">'.$c_phone.'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>';
                    if(mail($to,$subject,$txt,$headers)) {
                        Session::flash('message', 'Register Successfully & Activation URL Send your Email. Use That Url to Activate and login your Account!'); 
                        Session::flash('alert-class', 'alert-success');
                        return redirect()->route('signin');
                    } else {
                        Session::flash('message', 'Register Successfully!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('signup');
                    }
                } else{
                    Session::flash('message', 'Register Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('signup');
                }  
            } else{
                Session::flash('message', 'Register Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signup');
            }
        }
    }

    public function GoogleSignin(Request $request) {
        $error = 0;
        $id = 0;
        if($request->ajax() && isset($request->first_name) && isset($request->profile) && isset($request->email)) {
            $data['first_name'] = $request->first_name;
            $data['profile'] = $request->profile;
            $data['email'] = $request->email;

            if($request->last_name) {
                $data['last_name'] = $request->last_name;
            } else {
                $data['last_name'] = "";
            }

            if($request->social_ref_id) {
                $data['social_ref_id'] = $request->social_ref_id;
            } else {
                $data['social_ref_id'] = "";
            }

            if($request->id_token) {
                $data['id_token'] = $request->id_token;
            } else {
                $data['id_token'] = "";
            }

            $loged_usr = User::Where('email', $data['email'])->first();
            if($loged_usr) {
                if(($loged_usr->user_type == 4)) {
                    if($loged_usr->verification == 1) {
                        if($loged_usr->is_block == 1) {
                            session()->forget('user');
                            Session::flash('message', 'Login Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            Session::put('user', $user);

                            $users = session()->get('user');
                            $ses_carts = session()->get('cart');
                            $cartData = array();

                            if(isset($ses_carts)) {
                                Carts::Where('user_id', $users->id)->delete();
                                foreach ($ses_carts as $key => $value) {
                                    $carts = new Carts();
                                    if($carts) {
                                        $carts->product_id  = $value['product_id'];
                                        $carts->user_id     = $users->id;
                                        $carts->name        = (isset($value['name'])) ? $value['name'] : NULL;
                                        $carts->original_price  = (isset($value['original_price'])) ? $value['original_price'] : 0;
                                        $carts->product_cost       = (isset($value['product_cost'])) ? $value['product_cost'] : 0;
                                        $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
                                        $carts->tax_amount   = (isset($value['tax_amount'])) ? $value['tax_amount'] : 0;
                                        $carts->total_price   = (isset($value['total_price'])) ? $value['total_price'] : 0;
                                        $carts->att_name  = (isset($value['att_name'])) ? $value['att_name'] : NULL;
                                        $carts->att_value  = (isset($value['att_value'])) ? $value['att_value'] : NULL;
                                        $carts->tax  = (isset($value['tax'])) ? $value['tax'] : NULL;
                                        $carts->tax_type  = (isset($value['tax_type'])) ? $value['tax_type'] : NULL;
                                        $carts->service_charge  = (isset($value['service_charge'])) ? $value['service_charge'] : NULL;
                                        $carts->shiping_charge  = (isset($value['shiping_charge'])) ? $value['shiping_charge'] : NULL;
                                        $carts->image       = (isset($value['image'])) ? $value['image'] : NULL;
                                        $carts->qty         = (isset($value['qty'])) ? $value['qty'] : 1;
                                        $carts->notes       = (isset($value['notes'])) ? $value['notes'] : NULL;
                                        $carts->is_offer       = (isset($value['is_offer'])) ? $value['is_offer'] : 'No';
                                        $carts->offer_id       = (isset($value['offer_id'])) ? $value['offer_id'] : NULL;
                                        $carts->offer_det_id       = (isset($value['offer_det_id'])) ? $value['offer_det_id'] : NULL;
                                        $carts->cart_key       = (isset($value['cart_key'])) ? $value['cart_key'] : NULL;
                                        $carts->cart_del       = (isset($value['cart_del'])) ? $value['cart_del'] : NULL;
                                        $carts->is_block    = 1;

                                        $carts->save();
                                    }
                                }
                            }

                            echo $error = 1;die();
                        } else {
                            session()->forget('user');
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
                            Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                            Session::flash('alert-class', 'alert-danger');
                            echo $error = 3;die();
                        }
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                        Session::flash('alert-class', 'alert-danger');
                        echo $error = 3;die();
                    }
                } else {
                    session()->forget('user');
                    if(isset($_COOKIE["user"])) {
                        setcookie ("user","");
                    }        
                    Session::flash('message', 'Login failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    echo $error = 4;die();
                }
            } else {
                $users = new User();

                if($users) {                                
                    $users->first_name                = $data['first_name'];
                    $users->last_name                 = $data['last_name'];
                    $users->email                     = $data['email'];
                    $users->user_type                 = 4;
                    if(isset($data['profile'])) {
                        $users->profile_img           = $data['profile'];
                    } else {
                        $users->profile_img           = NULL;
                    }

                    if(isset($data['is_approved'])) {
                        $users->is_approved           = $data['is_approved'];
                    } else {
                        $users->is_approved           = 1;
                    }
                    $users->verification              = 1;
                    $users->is_block                  = 1;
                    $users->login_type                = 2;
                    $users->signup                    = "Google Login";
                    $users->social_ref_id             = $data['social_ref_id'];
                    $users->id_token                  = $data['id_token'];

                    if($users->save()) {
                        $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                        $admin_email = "info@ecambiar.com";
                        if($adm) {
                            $admin_email = $adm->email;
                        }

                        $mail_img = asset('images/mail.png');
                        $phone_img = asset('images/phone.png');
                        $logos = \DB::table('logo_settings')->first();
                        $logo_path = 'images/logo';
                        $logo = "";
                        if($logos) {
                            $logo = asset($logo_path.'/'.$logos->logo_image);
                        } else {
                            $logo = asset('images/logo.png');
                        }

                        $general = \DB::table('general_settings')->first();
                        $site_name = "ECambiar";
                        if($general){
                            $site_name = $general->site_name;
                        } 

                        $contacts = \DB::table('email_settings')->first();
                        $c_email = "info@ecambiar.com";
                        $c_phone = "971 925 6546";
                        if($contacts) {
                            $c_email = $contacts->contact_email;
                            $c_phone = $contacts->contact_phone1;
                        }

                        $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                        $headers.= "MIME-Version: 1.0\r\n";
                        // $headers.= "From: $admin_email" . "\r\n";
                        $headers.= "From: noreply@ecambiar.com" . "\r\n";
                        $to = $users->email;
                        $subject = "Activate Account";
                        $txt = '<div style="margin: 30px auto 20px;border: 1px solid #ff5c00;width: 602px;">
                            <table width="600" align="center" cellpadding="0" cellspacing="0" height="74">
                                <tbody>
                                    <tr bgcolor="#ffffff">
                                        <td style="padding-left:20px;padding-top:10px;padding-bottom:10px" height="70"><a href="'.route('home').'"><img src="'.$logo.'" border="0"></a></td>
                                    </tr> 
                                    <tr bgcolor="#ff5c00" height="7px">
                                        <td><br></td>
                                    </tr>
                                </tbody>
                            </table>

                            <table width="600px" align="center" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Your Username</b> </td>
                                        <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b><a href="mailto:'.$users->email.'" target="_blank">'.$users->email.'</a></b> </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Register And Login Successfully, Please Your Profile Update.</b> </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table width="600" align="center" cellpadding="0" cellspacing="0" height="61">
                                <tbody>
                                    <tr bgcolor="#ffffff">
                                        <td colspan="5" height="11"><br></td>
                                    </tr>
                                    
                                    <tr bgcolor="#ff5c00" height="7px">
                                        <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec;padding-left:10px" width="100" height="48">Contact Us : </td>

                                        <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec" width="29"><img src="'.$mail_img.'"></td>

                                        <td style="font-family:Segoe UI,Arial;font-size:11px;color:#ececec" width="135"><a href="mailto:'.$c_email.'" style="color:#ececec;text-decoration:none"> '.$c_email.'</a></td>

                                        <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec" width="28"><img src="'.$phone_img.'" style="margin-left:8px;"></td>

                                        <td style="font-family:Segoe UI,Arial;font-size:11px;color:#ececec" width="300">'.$c_phone.'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>'; 

                        if(mail($to,$subject,$txt,$headers)) {
                            Session::flash('message', 'Register and Mail Send Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            echo $error = 1;die();
                        } else {
                            Session::flash('message', 'Register Successfully!'); 
                            Session::flash('alert-class', 'alert-danger');
                            echo $error = 1;die();
                        }
                    } else{
                        Session::flash('message', 'Added Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        echo $error = 0;die();
                    }  
                } else{
                    Session::flash('message', 'Added Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    echo $error = 0;die();
                }
            }   
        }
        echo $error;
    }

    public function Activation ($code) {
        $user = User::where('verification', $code)->where('is_block', 1)->first();
        if($user) {
            if($user->verification != 1) {
                $user->verification = 1;
                $user->email_verify = 1;
                if($user->save()) {
                    Session::flash('message', 'Your Account Activated Successfully!'); 
                    Session::flash('alert-class', 'alert-success');
                    return redirect()->route('signin');
                } else {
                    Session::flash('message', 'Your Account Activation Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('signin');
                }
            } else {
                Session::flash('message', 'Your Account is Already Activated!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            Session::flash('message', 'Your Account Activation URL Expired!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }    
    }

    public function Verify ($on, $id) {
        $user = User::where('id', $id)->where('is_block', 1)->first();
        if($user) {
            $otp = mt_rand(100000, 999999);
            if($on == 'mobile') {
                $user->mobile_verify = $otp;

                if($user->save()) {
                    $text = "Please Use this ".$otp." otp code to Verify Your Mobile Number, ecambiar.com";
                    $text = urlencode($text);
 
                    $curl = curl_init();
                 
                    // Send the POST request with cURL
                    curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => "http://smschub.com/api/sms/format/json",
                    CURLOPT_POST => 1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_HTTPHEADER => array('X-Authentication-Key:01fe318b290f9f9cb686a6bc28a4affa', 'X-Api-Method:MT'),
                    CURLOPT_POSTFIELDS => array(
                        'mobile' => $user->phone,
                        'route' => 'TL',
                        'text' => $text,
                        'sender' => 'GJICAM')));
                 
                    // Send the request & save response to $response
                    $response = curl_exec($curl);
                 
                    // Close request to clear up some resources
                    curl_close($curl);
                    $response = json_decode($response);
                    // Print response
                    if(isset($response->data->status) && $response->data->status == "success") {
                        Session::flash('message', 'OTP Code Send on your Mobile Number, Please Enter That code To Verify Now!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("front_end.verify")->with(array('verf'=>'Mobile Number'));
                    } else {
                        Session::flash('message', 'OTP Code Send Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('my_account');
                    }
                } else {
                    Session::flash('message', 'Sorry Mobile Number Verification Not Possible this time!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('my_account');
                }
            } else if($on == 'email') {
                $user->email_verify = $otp;

                if($user->save()) {
                    $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                    $admin_email = "info@ecambiar.com";
                    if($adm) {
                        $admin_email = $adm->email;
                    }

                    $logos = \DB::table('logo_settings')->first();
                    $logo_path = 'images/logo';
                    $logo = "";
                    if($logos) {
                        $logo = asset($logo_path.'/'.$logos->logo_image);
                    } else {
                        $logo = asset('images/logo.png');
                    }

                    $general = \DB::table('general_settings')->first();
                    $site_name = "ECambiar";
                    if($general){
                        $site_name = $general->site_name;
                    } else {
                        $site_name = "ECambiar";
                    } 

                    $name = $user->first_name.' '.$user->last_name;
                    $email = $user->email;

                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    // $headers.= "From: $admin_email" . "\r\n";
                    $headers.= "From: noreply@ecambiar.com" . "\r\n";
                    $to = $email;
                    $subject = "Verify Email Address";
                    $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                            <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                            <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                <h2 style="color: #ff5c00;margin-top: 0px;">EMail Verification Code</h2>
                                <table align="center" style=" text-align: center;">
                                    <tr>
                                        <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">OTP</th>
                                        <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$otp.'</td>
                                    </tr>
                                </table>
                                <p>Your Email Verification OTP code is <span style="font-weight:bold"> '.$otp.' </span></p>
                                <p>Use this OTP to Verify your EMail Address</p>
                                <p>Thank You.</p>
                                 <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                <p>Thanks & Regards,</p>
                                <p><a href="'.route('home').'">'.$site_name.'</a></p>
                            </div>
                        </div>';
                    
                    
                    // if(1==1){
                    if(mail($to,$subject,$txt,$headers)){
                        Session::flash('message', 'OTP Code Send on your EMail Address, Please Enter That code To Verify Now!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("front_end.verify")->with(array('verf'=>'E-Mail Address'));
                    } else {
                        Session::flash('message', 'OTP Code Send Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('my_account');
                    }
                } else {
                    Session::flash('message', 'Sorry EMail Address Verification Not Possible this time!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('my_account');
                }
            } else {
                Session::flash('message', 'Sorry Verification Not Possible this time!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('my_account');
            }
        } else {
            Session::flash('message', 'You Are Not Authenticate User!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }    
    }

    public function CheckVerify (Request $request) {
        $rules = array(
            'otp'         => 'required',
        );

        $messages=[
            'opt.required'=>'The OTP field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            return redirect()->route('verify')->withErrors($validator);
        } else {
            $data = Input::all();
            $user = User::where('mobile_verify', $data['otp'])->first();

            if($user) {
                $user->mobile_verify = 1;
                if($user->save()) {
                    session()->forget('user');
                    Session::flash('message', 'Your Mobile Number Verification Successfully!'); 
                    Session::flash('alert-class', 'alert-success');
                    Session::put('user', $user);
                    return redirect()->route('my_account');
                } else{
                    Session::flash('message', 'Your Mobile Number Verification Failed, Please Try Again Later!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('my_account');
                }
            } else {
                $user = User::where('email_verify', $data['otp'])->first();

                if($user) {
                    $user->email_verify = 1;
                    if($user->save()) {
                        session()->forget('user');
                        Session::flash('message', 'Your Email Address Verification Successfully!'); 
                        Session::flash('alert-class', 'alert-success');
                        Session::put('user', $user);
                        return redirect()->route('my_account');
                    } else{
                        Session::flash('message', 'Your Email Address Verification Failed, Please Try Again Later!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('my_account');
                    }
                } else {
                    Session::flash('message', 'Your OTP is  InValid, Please Try Again!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('my_account');
                }
            } 
        }
    }

    public function ResendUrl () {
        return View::make("front_end.resend_url");
    }

    public function ResendActivateUrl (Request $request) {
        $rules = array(
            'email'                   => 'required',
            'password'                => 'required',
        );

        $messages=[
            'password.required'=>'The password field is required.',
            'email.required'=>'The email or mobile no field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            return View::make('front_end.resend_url')->withErrors($validator);
        } else {
            $data = Input::all();
            $user = User::where('email', $data['email'])->where('is_block', 1)->where('is_approved', 1)->first();
            if(!$user) {
                $user = User::where('phone', $data['email'])->where('is_block', 1)->where('is_approved', 1)->first();
            }

            if($user) {
                $pass = md5($data['password']);
                if ($user->password == $pass) {
                    if(($user->user_type == 4)) {
                        $users = $user;
                        $users->verification = "GJ".uniqid();
                        $pass = $data['password'];
                        if($users->save()) {
                            $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                            $admin_email = "info@ecambiar.com";
                            if($adm) {
                                $admin_email = $adm->email;
                            }

                            // $r_url = url('/activation/'.$users->verification);
                            $r_url = route('activation', ['code' => $users->verification]);
                            $mail_img = asset('images/mail.png');
                            $phone_img = asset('images/phone.png');
                            $logos = \DB::table('logo_settings')->first();
                            $logo_path = 'images/logo';
                            $logo = "";
                            if($logos) {
                                $logo = asset($logo_path.'/'.$logos->logo_image);
                            } else {
                                $logo = asset('images/logo.png');
                            }

                            $general = \DB::table('general_settings')->first();
                            $site_name = "ECambiar";
                            if($general){
                                $site_name = $general->site_name;
                            } 

                            $contacts = \DB::table('email_settings')->first();
                            $c_email = "info@ecambiar.com";
                            $c_phone = "971 925 6546";
                            if($contacts) {
                                $c_email = $contacts->contact_email;
                                $c_phone = $contacts->contact_phone1;
                            }

                            $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                            $headers.= "MIME-Version: 1.0\r\n";
                            // $headers.= "From: $admin_email" . "\r\n";
                            $headers.= "From: noreply@ecambiar.com" . "\r\n";
                            $to = $users->email;
                            $subject = "Activate Account";
                            $txt = '<div style="margin: 30px auto 20px;border: 1px solid #ff5c00;width: 602px;">
                                <table width="600" align="center" cellpadding="0" cellspacing="0" height="74">
                                    <tbody>
                                        <tr bgcolor="#ffffff">
                                            <td style="padding-left:20px;padding-top:10px;padding-bottom:10px" height="70"><a href="'.route('home').'"><img src="'.$logo.'" border="0"></a></td>
                                        </tr> 
                                        <tr bgcolor="#ff5c00" height="7px">
                                            <td><br></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table width="600" align="center">
                                    <tbody>
                                        <tr>
                                            <td style="padding:10px;font-size:15px;color:#333333;font-weight:bold;font-family:Segoe UI,Arial,Helvetica,sans-serif">Your registration is completed..! Click on the link below to activate your account.<br></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table width="600px" align="center" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Your Username</b> </td>
                                            <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b><a href="mailto:'.$users->email.'" target="_blank">'.$users->email.'</a></b> </td>
                                        </tr>
                                
                                        <tr>
                                            <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Your Password</b> </td>
                                            <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>'.$pass.'</b> </td>
                                        </tr>
                                
                                        <tr>
                                            <td colspan="2" style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Please click on link below to activate your account</b> </td>
                                        </tr>
                                
                                        <tr>
                                            <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>link</b> </td>
                                            <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b><a href="'.$r_url.'" target="_blank">'.$r_url.'</a></b> </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table width="600" align="center" cellpadding="0" cellspacing="0" height="61">
                                    <tbody>
                                        <tr bgcolor="#ffffff">
                                            <td colspan="5" height="11"><br></td>
                                        </tr>
                                        
                                        <tr bgcolor="#ff5c00" height="7px">
                                            <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec;padding-left:10px" width="100" height="48">Contact Us : </td>

                                            <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec" width="29"><img src="'.$mail_img.'"></td>

                                            <td style="font-family:Segoe UI,Arial;font-size:11px;color:#ececec" width="135"><a href="mailto:'.$c_email.'" style="color:#ececec;text-decoration:none"> '.$c_email.'</a></td>

                                            <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec" width="28"><img src="'.$phone_img.'" style="margin-left:8px;"></td>

                                            <td style="font-family:Segoe UI,Arial;font-size:11px;color:#ececec" width="300">'.$c_phone.'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>';
                            if(mail($to,$subject,$txt,$headers)) {
                                Session::flash('message', 'Activation URL Resend. Use That Url to verify and login your Account!'); 
                                Session::flash('alert-class', 'alert-success');
                                return redirect()->route('signin');
                            } else {
                                Session::flash('message', 'URL Resend Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->back();
                            }
                        } else{
                            Session::flash('message', 'URL Send Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->back();
                        }
                    } else {
                        Session::flash('message', 'You Are Not Authenticate User!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->back();
                    }
                } else {
                    Session::flash('message', 'Do Not Match Your Password!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->back();
                }
            } else {
                Session::flash('message', 'Your E-Mail or Mobile Number is Not Valid!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            } 
        }
    }

    public function ChkActQuestion () {
        $secure = loginSecurity::all();
        return View::make("front_end.chk_act_question")->with(array('secure'=>$secure));
    }

    public function ChkActAnswer (Request $request) {
        $rules = array(
            'email'                   => 'required',
            'question'                => 'required',
            'answer'                  => 'required',
        );

        $messages=[
            'email.required'=>'The email or mobile no field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            return redirect()->route('chk_act_question')->withErrors($validator);
        } else {
            $data = Input::all();
            $user = User::where('email', $data['email'])->where('is_block', 1)->first();
            if(!$user) {
                $user = User::where('phone', $data['email'])->where('is_block', 1)->first();
            }

            $act = false;
            if($user) {
                $act = User::where('id', $user->id)->where('is_block', 1)->where('question', $data['question'])->first();

                if($act) {
                    if($act->answer == $data['answer']) {
                        if(($act->user_type == 4)) {
                            if($act->verification != 1) {
                                $act->verification = 1;
                                if($act->save()) {
                                    Session::flash('message', 'Your Account Activated Successfully!');
                                    Session::flash('alert-class', 'alert-success');
                                    return redirect()->route('signin');
                                } else {
                                    Session::flash('message', 'Your Account Activation Failed!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('signin');
                                }
                            } else {
                                Session::flash('message', 'Your Account is Already Activated!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('signin');
                            }
                        } else {
                            Session::flash('message', 'You Are Not Authenticate User!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('signin');
                        }
                    } else {
                        Session::flash('message', 'Your Security Answer is Wrong!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('chk_act_question');
                    }
                } else {
                    Session::flash('message', 'Your Security Question is Wrong!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('chk_act_question');
                } 
            } else {
                Session::flash('message', 'Your E-Mail or Mobile Number is Not Valid!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('chk_act_question');
            }
        }
    }

    public function MyAccount () {
        $value = session()->get('user');
        if($value) {
            if($value->user_type == 4) {
                $past_orders= Orders::where('user_id',$value->id)->Where('order_status', 4)->Where('payment_status', 1)->paginate(12);
                if(count($past_orders) != 0) {
                    foreach ($past_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $cancel_orders= Orders::where('user_id',$value->id)->Where('cancel_approved', '!=', 0)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $re_orders = ReturnOrder::Where('user_id',$value->id)->OrderBy('id', 'DESC')->paginate(12);
                if(sizeof($re_orders) != 0) {
                    foreach ($re_orders as $keyzz => $valuezz) {
                        $valuezz['details'] = ReturnOrderDetails::Where('return_order_id', $value->id)->get();
                        $valuezz['trans'] = OrdersTransactions::Where('order_id', $valuezz->id)->get();
                        $valuezz['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $orders = Orders::where('user_id',$value->id)->whereNotIn('order_status',[4,5])->OrderBy('id', 'DESC')->paginate(12);
                if(count($orders) != 0) {
                    foreach ($orders as $key => $value) {
                        $value['details'] = OrderDetails::Where('order_id', $value->id)->get();
                        $value['trans'] = OrdersTransactions::Where('order_id', $value->id)->get();
                        $value['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $secure = loginSecurity::all();
                $general = GeneralSettings::first();
                return View::make("front_end.my_account")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 're_orders'=>$re_orders, 'secure'=>$secure, 'general'=>$general));
            } else {
                Session::flash('message', 'You Are Not Login!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            Session::flash('message', 'You Are Not Login!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }
    }

    public function ViewOrder ($id) {
        $value = session()->get('user');
        if($value) {
            if($value->user_type == 4) {
                $past_orders= Orders::where('user_id',$value->id)->Where('order_status', 4)->Where('payment_status', 1)->paginate(12);
                if(count($past_orders) != 0) {
                    foreach ($past_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $cancel_orders= Orders::where('user_id',$value->id)->Where('cancel_approved', '!=', 0)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $re_orders = ReturnOrder::Where('user_id',$value->id)->OrderBy('id', 'DESC')->paginate(12);
                if(sizeof($re_orders) != 0) {
                    foreach ($re_orders as $keyzz => $valuezz) {
                        $valuezz['details'] = ReturnOrderDetails::Where('return_order_id', $value->id)->get();
                        $valuezz['trans'] = OrdersTransactions::Where('order_id', $valuezz->id)->get();
                        $valuezz['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $orders = Orders::where('id',$id)->first();
                if($orders) {
                    $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                    $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
                    $orders['products'] = Products::Where('is_block', 1)->get();
                }

                $secure = loginSecurity::all();
                $general = GeneralSettings::first();
                return View::make("front_end.my_view_orders")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 're_orders'=>$re_orders, 'secure'=>$secure, 'general'=>$general));
            } else {
                Session::flash('message', 'You Are Not Login!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            Session::flash('message', 'You Are Not Login!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }
    }

    public function MyViewReturnOrder ($id) {
        $value = session()->get('user');
        if($value) {
            if($value->user_type == 4) {
                $past_orders= Orders::where('user_id',$value->id)->Where('order_status', 4)->Where('payment_status', 1)->paginate(12);
                if(count($past_orders) != 0) {
                    foreach ($past_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $cancel_orders= Orders::where('user_id',$value->id)->Where('cancel_approved', '!=', 0)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $re_orders = ReturnOrder::Where('id',$id)->first();
                if($re_orders) {
                    $re_orders['details'] = ReturnOrderDetails::Where('return_order_id', $re_orders->id)->get();
                    $re_orders['trans'] = OrdersTransactions::Where('order_id', $re_orders->order_id)->get();
                    $re_orders['products'] = Products::Where('is_block', 1)->get();
                }

                $orders = Orders::where('user_id',$value->id)->whereNotIn('order_status',[4,5])->paginate(12);
                if(count($orders) != 0) {
                    foreach ($orders as $key => $value) {
                        $value['details'] = OrderDetails::Where('order_id', $value->id)->get();
                        $value['trans'] = OrdersTransactions::Where('order_id', $value->id)->get();
                        $value['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $secure = loginSecurity::all();
                $general = GeneralSettings::first();
                return View::make("front_end.my_view_return_order")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 're_orders'=>$re_orders, 'secure'=>$secure, 'general'=>$general));
            } else {
                Session::flash('message', 'You Are Not Login!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            Session::flash('message', 'You Are Not Login!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }
    }

    public function TrackOrder ($id) {
        $value = session()->get('user');
        if($value) {
            if($value->user_type == 4) {
                $past_orders= Orders::where('user_id',$value->id)->Where('order_status', 4)->Where('payment_status', 1)->paginate(12);
                if(count($past_orders) != 0) {
                    foreach ($past_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $cancel_orders= Orders::where('user_id',$value->id)->Where('cancel_approved', '!=', 0)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $re_orders = ReturnOrder::Where('user_id',$value->id)->OrderBy('id', 'DESC')->paginate(12);
                if(sizeof($re_orders) != 0) {
                    foreach ($re_orders as $keyzz => $valuezz) {
                        $valuezz['details'] = ReturnOrderDetails::Where('return_order_id', $value->id)->get();
                        $valuezz['trans'] = OrdersTransactions::Where('order_id', $valuezz->id)->get();
                        $valuezz['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $orders = Orders::where('id',$id)->first();
                if($orders) {
                    $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                    $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
                    $orders['products'] = Products::Where('is_block', 1)->get();
                    $orders['shipments'] = Shipment::Where('order_code', $orders->order_code)->first();
                }
                $secure = loginSecurity::all();
                $general = GeneralSettings::first();
                return View::make("front_end.my_track_orders")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 're_orders'=>$re_orders, 'secure'=>$secure, 'general'=>$general));
            } else {
                Session::flash('message', 'You Are Not Login!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            Session::flash('message', 'You Are Not Login!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }
    }

    public function LiveTrackOrder ($id) {
        $value = session()->get('user');
        if($value) {
            if($value->user_type == 4) {
                $past_orders= Orders::where('user_id',$value->id)->Where('order_status', 4)->Where('payment_status', 1)->paginate(12);
                if(count($past_orders) != 0) {
                    foreach ($past_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $cancel_orders= Orders::where('user_id',$value->id)->Where('cancel_approved', '!=', 0)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $re_orders = ReturnOrder::Where('user_id',$value->id)->OrderBy('id', 'DESC')->paginate(12);
                if(sizeof($re_orders) != 0) {
                    foreach ($re_orders as $keyzz => $valuezz) {
                        $valuezz['details'] = ReturnOrderDetails::Where('return_order_id', $value->id)->get();
                        $valuezz['trans'] = OrdersTransactions::Where('order_id', $valuezz->id)->get();
                        $valuezz['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $orders = Orders::Where('id', $id)->first();
                if($orders) {
                    $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                    $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
                    $orders['products'] = Products::Where('is_block', 1)->get();
                    $orders['shipments'] = Shipment::Where('order_code', $orders->order_code)->first();
                    if($orders['shipments']) {
                        $log_shyp = new ShypliteAuth();
                        $login_shyp = $log_shyp->authenticatShyplite();
                        $login_shyp=json_decode($login_shyp, true);
                        
                        if(!isset($login_shyp['error'])) {
                            $timestamp = time();
                            $appID = $log_shyp->appID; 
                            $key = $log_shyp->key; 
                            $secret = $log_shyp->secret; 
                            if(isset($login_shyp['userToken'])) {
                                $secret = $login_shyp['userToken'];
                            }
                            $SellerID = $log_shyp->SellerID;

                            $sign = "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
                            $authtoken = rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
                            $ch = curl_init();
                            
                            $header = array(
                                "x-appid: $appID",
                                "x-timestamp: $timestamp",
                                "x-sellerid:$SellerID",
                                "Authorization: $authtoken"
                            );

                            curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/track/'.$orders['shipments']->awb);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $server_output = curl_exec($ch);
                            $resp = json_decode($server_output, true);

                            $secure = loginSecurity::all();
                            $general = GeneralSettings::first();
                            return View::make("front_end.live_track_order")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 're_orders'=>$re_orders, 'secure'=>$secure, 'general'=>$general, 'response'=>$resp));
                        } else {
                            Session::flash('message', 'Track This Order After Sometimes!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('my_account');
                        }
                    } else {
                        Session::flash('message', 'Your order is processing and will be shipped soon!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('my_account');
                    }
                } else {
                    Session::flash('message', 'Could Not Track This Order!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('my_account');
                }
            } else {
                Session::flash('message', 'You Are Not Login!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            Session::flash('message', 'You Are Not Login!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }
    }

    public function ReviewOrder ($id) {
        $value = session()->get('user');
        if($value) {
            if($value->user_type == 4) {
                $past_orders= Orders::where('user_id',$value->id)->Where('order_status', 4)->Where('payment_status', 1)->paginate(12);
                if(count($past_orders) != 0) {
                    foreach ($past_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $cancel_orders= Orders::where('user_id',$value->id)->Where('cancel_approved', '!=', 0)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $re_orders = ReturnOrder::Where('user_id',$value->id)->OrderBy('id', 'DESC')->paginate(12);
                if(sizeof($re_orders) != 0) {
                    foreach ($re_orders as $keyzz => $valuezz) {
                        $valuezz['details'] = ReturnOrderDetails::Where('return_order_id', $value->id)->get();
                        $valuezz['trans'] = OrdersTransactions::Where('order_id', $valuezz->id)->get();
                        $valuezz['products'] = Products::Where('is_block', 1)->get();
                    }
                }
                
                $orders = Orders::where('id',$id)->first();
                if($orders) {
                    $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                    $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
                    $orders['products'] = Products::Where('is_block', 1)->get();
                }
                $secure = loginSecurity::all();
                $general = GeneralSettings::first();
                return View::make("front_end.my_review_orders")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 're_orders'=>$re_orders, 'secure'=>$secure,'general'=>$general));
            } else {
                Session::flash('message', 'You Are Not Login!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            Session::flash('message', 'You Are Not Login!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }
    }

    public function CustomerCancelOrder( Request $request) {   
        $id = 0;
        $error = 0;
        if($request->ajax() && isset($request->id)) {
            $id = $request->id;
            if($id != 0) {
                $cancel = Orders::where('id',$id)->where('order_status',1)->first();
                if ($cancel) {
                    $n_date = date('Y-m-d');
                    $c_date = date('Y-m-d', strtotime($cancel->order_date. ' + 1 days'));
                    if($c_date >= $n_date) {
                        $cancel->cancel_approved = 3;
                        $cancel->cancel_remarks = "processing";
                        $cancel->cancel_date = $n_date;
                        if($cancel->save()) {
                            $text = "Your Order Cancel Request against ".$cancel->order_code." has been received, We will  Notify you the status soon, ecambiar.com";
                            $text = urlencode($text);
         
                            $curl = curl_init();
                            $user = User::Where('id', $cancel->user_id)->first();
                            if($user) { 
                                $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                                $admin_email = "info@ecambiar.com";
                                if($adm) {
                                    $admin_email = $adm->email;
                                }

                                $logos = \DB::table('logo_settings')->first();
                                $logo_path = 'images/logo';
                                $logo = "";
                                if($logos) {
                                    $logo = asset($logo_path.'/'.$logos->logo_image);
                                } else {
                                    $logo = asset('images/logo.png');
                                }

                                $general = \DB::table('general_settings')->first();
                                $site_name = "ECambiar";
                                if($general){
                                    $site_name = $general->site_name;
                                } else {
                                    $site_name = "ECambiar";
                                } 

                                $name = $user->first_name.' '.$user->last_name;

                                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                $headers.= "MIME-Version: 1.0\r\n";
                                // $headers.= "From: $admin_email" . "\r\n";
                                $headers.= "From: noreply@ecambiar.com" . "\r\n";
                                $to1 = $user->email;
                                $to2 = $admin_email;
                                $subject = "Cancel Order Request";

                                $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                            <h2 style="color: #ff5c00;margin-top: 0px;">Cancel Order Request</h2>
                                            <table align="center" style=" text-align: center;">
                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">customer Name</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$name.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Contact No</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$user->phone.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Email</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$user->email.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Order Code</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$cancel->order_code.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Order Date</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$cancel->order_date.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Request Date</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$cancel->cancel_date.'</td>
                                                </tr>
                                            </table>

                                            <p>Your Order Cancel Request has been received, We will  Notify you the status soon.</p>
                                            <p>Thank You.</p>
                                             <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                            <p>Thanks & Regards,</p>
                                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                        </div>
                                    </div>';
                                    
                                    
                                // if(1==1){
                                if(mail($to1,$subject,$txt,$headers) && mail($to2,$subject,$txt,$headers)){
                                    Session::flash('message', 'Your Order Cancel Request Send Successfully!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    $error = 1;
                                }

                                // Send the POST request with cURL
                                curl_setopt_array($curl, array(
                                CURLOPT_RETURNTRANSFER => 1,
                                CURLOPT_URL => "http://smschub.com/api/sms/format/json",
                                CURLOPT_POST => 1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_HTTPHEADER => array('X-Authentication-Key:01fe318b290f9f9cb686a6bc28a4affa', 'X-Api-Method:MT'),
                                CURLOPT_POSTFIELDS => array(
                                    'mobile' => $user->phone,
                                    'route' => 'TL',
                                    'text' => $text,
                                    'sender' => 'GJICAM')));
                             
                                // Send the request & save response to $response
                                $response = curl_exec($curl);
                             
                                // Close request to clear up some resources
                                curl_close($curl);
                                $response = json_decode($response);
                                // Print response
                                if(isset($response->data->status) && $response->data->status == "success") {
                                    Session::flash('message', 'Your Order Cancel Request Send Successfully!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    echo $error = 1;die();
                                } else {
                                    Session::flash('message', 'Your Order Cancel Request Send Successfully!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    echo $error = 1;die();
                                }
                            }
                            $error = 1;
                        } else {
                            $error = 0;
                        }
                    } else {
                        $error = 5;
                    }
                } else {
                    $error = 0;
                }           
            } else {
                $error = 0;
            }

            echo $error;
        }
    }

    public function CustomerReturnOrder( $id) {   
        $order = Orders::Where('id', $id)->first();
        if($order) {
            $odr_dets = OrderDetails::Where('order_id', $order->id)->get();
            if(sizeof($odr_dets) != 0) {
                $order->{'odr_dets'} = $odr_dets;
            } else {
                $order->{'odr_dets'} = array();
            }
            return View::make("front_end.customer_return_order")->with(array('order'=>$order));
        } else {
            Session::flash('message', 'Return / Replace Order Not Possible!'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('/my_account/#Section4');
        }
    }

    public function SaveReturnOrder(Request $request) {
        $log = session()->get('user');
        $data = Input::all();
        // print_r($data);die();

        $rules = array(
            'order_id'             => 'nullable|exists:orders,id',
            'user_id'              => 'nullable|exists:users,id',
            'order_code'           => 'required',
            'order_date'           => 'required',
            'total_items'          => 'required',
            'net_amount'           => 'required',
            'return_total_items'   => 'nullable',
            'return_net_amount'    => 'nullable',
            'return_date'          => 'nullable',
            'is_block'             => 'nullable',

            'check.*'              => 'nullable',
            'det_id.*'             => 'nullable',
            'return_type.*'        => 'nullable',
            'return_qty.*'         => 'nullable',
            'return_amount.*'      => 'nullable',
            // 'return_tax_amount.*'  => 'nullable',
            'reason.*'             => 'nullable',
            'remarks.*'            => 'nullable',
            'rtn_image.*'          => 'nullable',
        );

        $messages=[
            'return_qty.required'=>'The Quantity field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            return Redirect::to('/customer_return_order/'.$data['order_id'])->withErrors($validator);
        } else {
            $re_odr = new ReturnOrder();

            if($re_odr) {
                if($log) {
                    if($log->id) {
                        $re_odr->user_id  = $log->id;    
                    } else {
                        $re_odr->user_id  = NULL;    
                    }
                } else {
                    $re_odr->user_id      = NULL;    
                }

                $re_odr->order_id         = $data['order_id'];  
                $re_odr->order_code       = $data['order_code'];    
                $re_odr->order_date       = date('Y-m-d', strtotime($data['order_date']));    
                $re_odr->total_items      = $data['total_items'];    
                $re_odr->net_amount       = $data['net_amount'];    
                $re_odr->return_date      = date('Y-m-d');    
                $re_odr->is_block         = 1;

                if($re_odr->save()) {
                    $order = Orders::Where('id', $re_odr->order_id)->first();
                    if($order) {
                        $order->return_order_status = 1;
                        $order->save();
                    }

                    $ok = 0;
                    $return_total_items = 0;
                    $return_net_amount = 0;
                    if (isset($data['det_id']) && sizeof($data['det_id']) != 0) {
                        ReturnOrderDetails::Where('return_order_id', $re_odr->id)->delete();
                        foreach ($data['det_id'] as $key => $value) {
                            if($value) {
                                $re_odr_details = new ReturnOrderDetails();
                                $re_odr_details->return_order_id = $re_odr->id;
                                $re_odr_details->rtn_odr_det_id = $value;
                                $odr_dets = OrderDetails::Where('id', $value)->first();

                                if($odr_dets) {
                                    $re_odr_details->product_id = $odr_dets->product_id;
                                    $re_odr_details->product_title = $odr_dets->product_title;
                                    $re_odr_details->att_name = $odr_dets->att_name;
                                    $re_odr_details->att_value = $odr_dets->att_value;
                                    $re_odr_details->tax = $odr_dets->tax;
                                    $re_odr_details->tax_type = $odr_dets->tax_type;
                                    $re_odr_details->order_qty = $odr_dets->order_qty;
                                    $re_odr_details->unitprice = $odr_dets->unitprice;
                                    // $re_odr_details->tax_amount = $odr_dets->tax_amount;
                                    $re_odr_details->totalprice = $odr_dets->totalprice;
                                }
                                
                                if(isset($data['return_type'][$key])) {
                                    $re_odr_details->return_type = $data['return_type'][$key];
                                } else {
                                    $re_odr_details->return_type = NULL;
                                }
                                
                                if(isset($data['return_qty'][$key])) {
                                    $re_odr_details->return_qty = $data['return_qty'][$key];
                                } else {
                                    $re_odr_details->return_qty = NULL;
                                }

                                if(isset($data['return_amount'][$key])) {
                                    $re_odr_details->return_amount = $data['return_amount'][$key];
                                } else {
                                    $re_odr_details->return_amount = 0.00;
                                }

                                /*if(isset($data['return_tax_amount'][$key])) {
                                    $re_odr_details->return_tax_amount = $data['return_tax_amount'][$key];
                                } else {
                                    $re_odr_details->return_tax_amount = 0.00;
                                }*/

                                if(isset($data['reason'][$key])) {
                                    $re_odr_details->reason = $data['reason'][$key];
                                } else {
                                    $re_odr_details->reason = NULL;
                                }

                                if(isset($data['remarks'][$key])) {
                                    $re_odr_details->remarks = $data['remarks'][$key];
                                } else {
                                    $re_odr_details->remarks = NULL;
                                }
                                
                                if(isset($data['rtn_image'][$key])) {
                                    $file_name = $data['rtn_image'][$key]->getClientOriginalName();
                                    $date = date('M-Y');
                                    // $file_path = '../public/images/attributes/'.$date;
                                    $file_path = 'images/return_order_image/'.$date;
                                    $data['rtn_image'][$key]->move($file_path, $file_name);

                                    $re_odr_details->rtn_image       = $date.'/'.$file_name;
                                } else {
                                    $re_odr_details->rtn_image       = NULL;

                                }

                                $return_total_items = $return_total_items + $re_odr_details->return_qty;
                                $return_net_amount = $return_net_amount + $re_odr_details->return_amount;
                                $re_odr_details->order_returned  = "No";
                                $re_odr_details->status  = "Process";

                                $re_odr_details->save();  
                                $ok = 1;                             
                            }
                        } 

                        if($ok == 1) {
                            $re_odr->return_total_items = $return_total_items;
                            $re_odr->return_net_amount = $return_net_amount;
                            if($re_odr->save()) {
                                $ok = 1;
                            }

                            $text = "Your Order Return/Replacemnet Request against ".$re_odr->order_code." Has been received , we will verify the same and get back to you soon, ecambiar.com";
                            $text = urlencode($text);
         
                            $curl = curl_init();
                            $user = User::Where('id', $re_odr->user_id)->first();
                            if($user) { 
                                $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                                $admin_email = "info@ecambiar.com";
                                if($adm) {
                                    $admin_email = $adm->email;
                                }

                                $logos = \DB::table('logo_settings')->first();
                                $logo_path = 'images/logo';
                                $logo = "";
                                if($logos) {
                                    $logo = asset($logo_path.'/'.$logos->logo_image);
                                } else {
                                    $logo = asset('images/logo.png');
                                }

                                $general = \DB::table('general_settings')->first();
                                $site_name = "ECambiar";
                                if($general){
                                    $site_name = $general->site_name;
                                } else {
                                    $site_name = "ECambiar";
                                } 

                                $name = $user->first_name.' '.$user->last_name;

                                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                $headers.= "MIME-Version: 1.0\r\n";
                                // $headers.= "From: $admin_email" . "\r\n";
                                $headers.= "From: noreply@ecambiar.com" . "\r\n";
                                $to1 = $user->email;
                                $to2 = $admin_email;
                                $subject = "Return Order Request";

                                $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                            <h2 style="color: #ff5c00;margin-top: 0px;">Return Order Request</h2>
                                            <table align="center" style=" text-align: center;">
                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">customer Name</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$name.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Contact No</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$user->phone.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Email</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$user->email.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Order Code</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$re_odr->order_code.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Order Date</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$re_odr->order_date.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Request Date</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$re_odr->return_date.'</td>
                                                </tr>
                                            </table>

                                            <p>Your Order Return/Replacemnet Request Has been received , we will verify the same and get back to you soon.</p>
                                            <p>Thank You.</p>
                                             <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                            <p>Thanks & Regards,</p>
                                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                        </div>
                                    </div>';
                                    
                                    
                                // if(1==1){
                                if(mail($to1,$subject,$txt,$headers) && mail($to2,$subject,$txt,$headers)){
                                    Session::flash('message', 'Your Order Return/Replacemnet Request Submitted Successfully, we will get back to you soon!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    // return Redirect::to('/my_account/#Section4');
                                }

                                // Send the POST request with cURL
                                curl_setopt_array($curl, array(
                                CURLOPT_RETURNTRANSFER => 1,
                                CURLOPT_URL => "http://smschub.com/api/sms/format/json",
                                CURLOPT_POST => 1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_HTTPHEADER => array('X-Authentication-Key:01fe318b290f9f9cb686a6bc28a4affa', 'X-Api-Method:MT'),
                                CURLOPT_POSTFIELDS => array(
                                    'mobile' => $user->phone,
                                    'route' => 'TL',
                                    'text' => $text,
                                    'sender' => 'GJICAM')));
                             
                                // Send the request & save response to $response
                                $response = curl_exec($curl);
                             
                                // Close request to clear up some resources
                                curl_close($curl);
                                $response = json_decode($response);
                                // Print response
                                if(isset($response->data->status) && $response->data->status == "success") {
                                    Session::flash('message', 'Your Order Return/Replacemnet Request Submitted Successfully, we will get back to you soon!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    return Redirect::to('/my_account/#Section4');
                                } else {
                                    Session::flash('message', 'Your Order Return/Replacemnet Request Submitted Successfully, we will get back to you soon!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return Redirect::to('/my_account/#Section4');
                                }
                            }
                        } else {
                            $re_odr->delete();
                            Session::flash('message', 'Your Return or Replacement Order Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return Redirect::to('/customer_return_order/'.$data['order_id']);
                        } 
                    } else {
                        $re_odr->delete();
                        Session::flash('message', 'Your Return or Replacement Order Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return Redirect::to('/customer_return_order/'.$data['order_id']);
                    }
                } else{
                    Session::flash('message', 'Your Return or Replacement Order Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return Redirect::to('/my_account/#Section4');
                }  
            } else{
                Session::flash('message', 'Your Return or Replacement Order Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return Redirect::to('/my_account/#Section4');
            }
        }
    }

    public function SendFeedBack(Request $request) {
        $log = session()->get('user');
        $data = Input::all();

        $rules = array(
            'user_id'  => 'nullable',
            'subject'  => 'required',
            'message'  => 'required',
            'is_block' => 'nullable',
        );

        $messages=[
            'subject.required'=>'The subject field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            // return redirect()->route('my_account')->withErrors($validator);
            return Redirect::to('/my_account/#Section7')->withErrors($validator);
        } else {
            $feeds = new FeedBack();

            if($feeds) {
                $feeds->user_id    = $data['user_id'];    
                $feeds->subject    = $data['subject'];  
                $feeds->message    = $data['message'];    
                $feeds->is_block   = 1;     
                
                if($feeds->save()) {
                    Session::flash('message', 'Feed Back Send Successfully!'); 
                    Session::flash('alert-class', 'alert-success');
                    return redirect()->route('my_account');
                } else{
                    Session::flash('message', 'Feed Back Send Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('my_account');
                }  
            } else{
                Session::flash('message', 'Feed Back Send Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('my_account');
            }
        }
    }

    public function SubmitReview (Request $request) {
        $data = Input::all();
        $orders = Orders::where('id',$data['order_id'])->first();
        if($orders) {
            $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
            $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
            $orders['products'] = Products::Where('is_block', 1)->get();
        }

        $rules = array(
            'product_id'       => 'required|exists:products,id',
            'order_id'         => 'required|exists:orders,id',
            'user_id'          => 'required|exists:users,id',
            'rating'           => 'required',
            'description'      => 'required',
            'is_block'         => 'nullable',
        );

        $messages=[
            'product_id.required'  =>'Could Not Reviewed This  Product!',
            'product_id.exists'    =>'Could Not Reviewed This  Product!',
            'order_id.required'    =>'Could Not Reviewed This  Product!',
            'order_id.exists'      =>'Could Not Reviewed This  Product!',
            'user_id.required'     =>'Could Not Reviewed This  Product!',
            'user_id.exists'       =>'Could Not Reviewed This  Product!',
            'rating.required'      =>'Star Rating Field Required!',
            'description.required' =>'Review Field is Required!',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            return Redirect::to('/my_account/review_order/' . $data['order_id'])->withErrors($validator)->with(array('orders'=>$orders));
        } else {
            $review = new Review();
            $review->product_id  = $data['product_id'];              
            $review->user_id     = $data['user_id'];             
            $review->rating      = $data['rating'];              
            $review->description = $data['description'];               
            $review->is_block    = 0;

            if($review->save()) {
                Session::flash('message', 'Reviewed Successfully!'); 
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('my_account');
            } else {
                Session::flash('message', 'Reviewed Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('my_account');
            }
        }
    }

    public function Contact () {
        $emails = EmailSettings::first();
        return View::make("front_end.contact")->with(array('emails'=>$emails));
    }

    public function StoreContact(Request $request) {
        $rules = array(
            'contact_name'            => 'required',
            'contact_email'           => 'required|email',
            'contact_no'              => 'required',
            'message'                 => 'required',
            'is_block'                => 'nullable',
        );

        $messages=[
            'contact_no.required'=>'The Phone Number field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            $emails = EmailSettings::first();
            return View::make("front_end.contact")->withErrors($validator)->with(array('emails'=>$emails));
        } else {
            $data = Input::all();
            $contact = new Contacts();

            if($contact) {
                $contact->contact_name       = $data['contact_name'];
                $contact->contact_email      = $data['contact_email'];
                $contact->contact_no         = $data['contact_no'];
                $contact->message            = $data['message'];
                $contact->is_block           = 1;

                if($contact->save()) {
                    $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                    $admin_email = "info@ecambiar.com";
                    if($adm) {
                        $admin_email = $adm->email;
                    }

                    $logos = \DB::table('logo_settings')->first();
                    $logo_path = 'images/logo';
                    $logo = "";
                    if($logos) {
                        $logo = asset($logo_path.'/'.$logos->logo_image);
                    } else {
                        $logo = asset('images/logo.png');
                    }

                    $general = \DB::table('general_settings')->first();
                    $site_name = "ECambiar";
                    if($general){
                        $site_name = $general->site_name;
                    } else {
                        $site_name = "ECambiar";
                    } 

                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    // $headers.= "From: $admin_email" . "\r\n";
                    $headers.= "From: noreply@ecambiar.com" . "\r\n";
                    $to = $contact->contact_email;
                    $subject = "Thanks For Your Contacts";

                    $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                            <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                            <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                <p>Thanks For Your Enqueries. We Will Contact you Soon.</p>
                                <p></p>
                                <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                <p>Thanks & Regards,</p>
                                <p><a href="'.route('home').'">'.$site_name.'</a></p>
                            </div>
                        </div>';

                    $txt2 = '<div class="gj_mail" style="width: 600px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat;  background-size: 100% 102%;">
                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                            <h2 style="color: #ff5c00;margin-top: 0px;">Customer Enqueries</h2>
                            <table align="center" style=" text-align: center;">
                                <tr>
                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Customer Name</th>
                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$contact->contact_name.'</td>
                                </tr>

                                <tr>
                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Contact No</th>
                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$contact->contact_no.'</td>
                                </tr>

                                <tr>
                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">E-Mail</th>
                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$contact->contact_email.'</td>
                                </tr>

                                <tr>
                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Message</th>
                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$contact->message.'</td>
                                </tr>
                            </table>

                            <p></p>
                            <p>Thank You.</p>
                            <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                            <p>Thanks & Regards,</p>
                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                        </div>
                    </div>';
                    
                    if(mail($to,$subject,$txt,$headers) && mail($admin_email,$subject,$txt2,$headers)){
                        Session::flash('message', 'Mail Send Successfully!'); 
                        Session::flash('alert-class', 'alert-success');
                        return redirect()->route('contact');
                    } else {
                        Session::flash('message', 'Mail Send Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('contact'); 
                    }
                } else{
                    Session::flash('message', 'Mail Send Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('contact');
                }  
            } else{
                Session::flash('message', 'Mail Send Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('contact');
            }
        }
    }

    public function HowToFindUs () {
        $emails = EmailSettings::first();
        $general = GeneralSettings::first();
        return View::make("front_end.how_to_find_us")->with(array('emails'=>$emails, 'general'=>$general));
    }

    public function Privacy () {
        return View::make("front_end.privacy");
    }

    public function Disclaimer () {
        $disc = Disclaimers::first();
        if ($disc) {
            return View::make("front_end.disclaimer")->with(array('disc'=>$disc));
        } else {
            Session::flash('message', 'Page Not Found!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('home');
        }
    }

    public function NewsLetters(Request $request) {
        $rules = array(
            'email'           => 'required|email',
            'is_block'        => 'nullable',
        );

        $messages=[
            'email.required'=>'The Email field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            return View::make('front_end.index')->withErrors($validator);
        } else {
            $data = Input::all();
            $news_letters = new NewsLetter();

            if($news_letters) {
                $news_letters->email      = $data['email'];
                $news_letters->is_block   = 1;

                if($news_letters->save()) {
                    $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                    $admin_email = "info@ecambiar.com";
                    if($adm) {
                        $admin_email = $adm->email;
                    }

                    $logos = \DB::table('logo_settings')->first();
                    $logo_path = 'images/logo';
                    $logo = "";
                    if($logos) {
                        $logo = asset($logo_path.'/'.$logos->logo_image);
                    } else {
                        $logo = asset('images/logo.png');
                    }

                    $general = \DB::table('general_settings')->first();
                    $site_name = "ECambiar";
                    if($general){
                        $site_name = $general->site_name;
                    } else {
                        $site_name = "ECambiar";
                    } 

                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    // $headers.= "From: $admin_email" . "\r\n";
                    $headers.= "From: noreply@ecambiar.com" . "\r\n";
                    $to = $news_letters->email;
                    $subject = "Thanks For Your Subcribe";

                    $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                            <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                            <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                <p>Thanks For Your Subcribe. We Will Contact you Soon.</p>
                                <p><a href="'.route('unsubcribe', ['id' => $news_letters->id]).'">Unsubscribe</a></p>
                                <p></p>
                                <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                <p>Thanks & Regards,</p>
                                <p><a href="'.route('home').'">'.$site_name.'</a></p>
                            </div>
                        </div>';
                    
                    if(mail($to,$subject,$txt,$headers)){
                        Session::flash('message', 'Mail Send Successfully!'); 
                        Session::flash('alert-class', 'alert-success');
                        return redirect()->route('home');
                    } else {
                        Session::flash('message', 'Mail Send Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('home'); 
                    }
                } else{
                    Session::flash('message', 'Mail Send Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('home');
                }  
            } else{
                Session::flash('message', 'Mail Send Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('home');
            }
        }
    }

    public function UnSubscribeNewsLetters ($id) {
        $news_letters = NewsLetter::where('id',$id)->where('is_block', 1)->first();
        if($news_letters) {
            $news_letters->is_block   = 0;

            if($news_letters->save()) {
                $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                $admin_email = "info@ecambiar.com";
                if($adm) {
                    $admin_email = $adm->email;
                }

                $logos = \DB::table('logo_settings')->first();
                $logo_path = 'images/logo';
                $logo = "";
                if($logos) {
                    $logo = asset($logo_path.'/'.$logos->logo_image);
                } else {
                    $logo = asset('images/logo.png');
                }

                $general = \DB::table('general_settings')->first();
                $site_name = "ECambiar";
                if($general){
                    $site_name = $general->site_name;
                } else {
                    $site_name = "ECambiar";
                } 

                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                $headers.= "MIME-Version: 1.0\r\n";
                // $headers.= "From: $admin_email" . "\r\n";
                $headers.= "From: noreply@ecambiar.com" . "\r\n";
                $to = $news_letters->email;
                $subject = "UnSubcribe For Email Notification";

                $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                            <p>Unsubscribe For Email Notification Successfully.</p>
                            <p></p>
                            <p>Thank You.</p>
                            <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                            <p>Thanks & Regards,</p>
                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                        </div>
                    </div>';
                
                if(mail($to,$subject,$txt,$headers)){
                    Session::flash('message', 'Unsubscribe & Mail Send Successfully!'); 
                    Session::flash('alert-class', 'alert-success');
                    return redirect()->route('home');
                } else {
                    Session::flash('message', 'Mail Send Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('home'); 
                }
            } else{
                Session::flash('message', 'Unsubscribed to the Another Time!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('home');
            }
        } else {
            Session::flash('message', 'You Are Not Subscribed User!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('home');
        }
    }

    public function AddToCart( Request $request) { 
        $id = 0;
        if($request->ajax() && isset($request->id)){
            $id = $request->id;
            $qty = $request->qty;
            $price = $request->price;
            $att_name = $request->att_name;
            $att_value = $request->att_value;
            if($att_name == 0) {
                $att_name = NULL;
            }
            if($att_value == 0) {
                $att_value = NULL;
            }

            if(!$qty){
                $qty = 1;
            }

            $error = 1;
            if($id) {
                $product = Products::where('id',$id)->first();
                if($product){
                    $price = $product->product_cost;
                    $tax_amount = $product->tax_amount;
                    $onhand_qty = $product->onhand_qty;
                    if($onhand_qty != 0 && $onhand_qty > 0) {
                        if($onhand_qty >= $qty) {
                            $att_qty = 0;
                            $p_attrs = ProductsAttributes::Where('product_id', $product->id)->where('att_default', 1)->first();
                            if($p_attrs) {
                                if(!$att_name && !$att_value) {
                                    $att_name = $p_attrs->attribute_name;
                                    $att_value = $p_attrs->attribute_values;
                                    $att_qty = $p_attrs->att_qty;
                                    $price = $p_attrs->att_cost;
                                    $tax_amount = $p_attrs->att_tax_amount;
                                    $qty = 1;
                                }
                                // } else {
                                //     $p_attz = ProductsAttributes::Where('product_id', $product->id)->where('attribute_values', $att_value)->where('attribute_name', $att_name)->first();
                                //     if($p_attz) {
                                //         $att_name = $p_attrs->attribute_name;
                                //         $att_value = $p_attrs->attribute_values;
                                //         $att_qty = $p_attrs->att_qty;
                                //         $price = $p_attrs->att_price;
                                //     }
                                // }
                            }

                            if($att_name && $att_value) {
                                $p_attz = ProductsAttributes::Where('product_id', $product->id)->where('attribute_values', $att_value)->where('attribute_name', $att_name)->first();

                                if($p_attz) {
                                    $price = $p_attz->att_cost;
                                    $tax_amount = $p_attz->att_tax_amount;
                                    $att_qty = $p_attz->att_qty;
                                }

                                if($att_qty >= $qty) {
                                    $session = $request->session();
                                    $cartAllData = array();
                                    $cartData = ($session->get('cart')) ? $session->get('cart') : array();
                                    if($cartData) {
                                        foreach ($cartData as $keyz => $valuez) {
                                            if(isset($cartData[$keyz]) && $cartData[$keyz]['product_id'] == $id && $cartData[$keyz]['att_name'] == $att_name && $cartData[$keyz]['att_value'] == $att_value && $cartData[$keyz]['is_offer'] == 'No') {
                                                Session::flash('message', 'Already Added to Cart!'); 
                                                Session::flash('alert-class', 'alert-danger');
                                                echo $error = 2;
                                                die();
                                            }
                                        }
                                    }

                                    $sc = 0;
                                    $shc = 0;
                                    if($product->tax_type == 2) {
                                        $shc = $product->shiping_charge;
                                    } else {
                                        $shc = 0;
                                    }

                                    if($product->service_charge) {
                                        $sc = $product->service_charge;
                                    } else {
                                        $sc = 0;
                                    }

                                    if($qty) {
                                        $qty = $qty;
                                    } else {
                                        $qty = 1;
                                    }

                                    if(!isset($price) && $price == 0) {
                                        $price = $product->product_cost;
                                    }

                                    if($price) {
                                        $price = $price;
                                    } else {
                                        $price = $product->product_cost;
                                    }

                                    $product_cost = $price + $tax_amount;

                                    $t_price = round(($qty * $product_cost),2);

                                    $cart_key = time().uniqid();
                                    $cart_del = time();

                                    $cartData[$cart_key] = array(
                                        'product_id' => $product->id,
                                        'qty'   => $qty,
                                        'original_price' => $product->original_price,
                                        'product_cost' => $product_cost,
                                        'price' => $price,
                                        'tax_amount' => $tax_amount,
                                        'total_price' => $t_price,
                                        'att_name' => $att_name,
                                        'att_value' => $att_value,
                                        'tax' => $product->tax,
                                        'tax_type' => $product->tax_type,
                                        'service_charge' => $sc,
                                        'shiping_charge' => $shc,
                                        'image' => $product->featured_product_img,
                                        'name'  => $product->product_title,
                                        'notes' => '',
                                        'is_offer' => 'No',
                                        'offer_id' => NULL,
                                        'offer_det_id' => NULL,
                                        'cart_key' => $cart_key,
                                        'cart_del' => $cart_del,
                                    );

                                    $users = session()->get('user');
                                    if($users) {
                                        if($users->user_type == 4) {
                                            $carts = new Carts();
                                            if($carts) {
                                                $carts->product_id  = $product->id;
                                                $carts->user_id     = $users->id;
                                                $carts->name        = $product->product_title;
                                                $carts->original_price = $product->original_price;
                                                $carts->product_cost  = $product_cost;
                                                $carts->price       = $price;
                                                $carts->tax_amount = $tax_amount;
                                                $carts->total_price = $t_price;
                                                $carts->image       = $product->featured_product_img;
                                                $carts->att_name  = $att_name;
                                                $carts->att_value  = $att_value;
                                                $carts->tax  = $product->tax;
                                                $carts->tax_type  = $product->tax_type;
                                                $carts->service_charge  = $sc;
                                                $carts->shiping_charge  = $shc;
                                                $carts->qty         = $qty;
                                                $carts->cart_key    = $cart_key;
                                                $carts->cart_del    = $cart_del;
                                                $carts->is_offer    = "No";
                                                $carts->offer_id    = NULL;
                                                $carts->offer_det_id    = NULL;
                                                $carts->is_block    = 1;

                                                if($carts->save()) {
                                                    $error = "Added to Cart Successfully!";
                                                }
                                            }
                                        }
                                    }

                                    $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                                    $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                                    $request->session()->put('cart', $cartData);
                                    $request->session()->put('cart_total', $cartAllData);

                                    $error = "Added to Cart Successfully!";
                                    Session::flash('message', 'Added to Cart Successfully!'); 
                                    Session::flash('alert-class', 'alert-success');
                                } else {
                                    Session::flash('message', 'Out of Stock. Only ' . $att_qty. '  Products Avaliable!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    echo $error = 7; die();
                                }
                            } else {
                                $session = $request->session();
                                $cartAllData = array();
                                $cartData = ($session->get('cart')) ? $session->get('cart') : array();

                                if($cartData) {
                                    foreach ($cartData as $keyz => $valuez) {
                                        // if(isset($cartData[$keyz]) && $cartData[$keyz]['product_id'] == $id && $cartData[$keyz]['is_offer'] == 'No') {
                                        if(isset($cartData[$keyz]) && $cartData[$keyz]['product_id'] == $id && $cartData[$keyz]['att_name'] == $att_name && $cartData[$keyz]['att_value'] == $att_value && $cartData[$keyz]['is_offer'] == 'No') {
                                            Session::flash('message', 'Already Added to Cart!'); 
                                            Session::flash('alert-class', 'alert-danger');
                                            echo $error = 2;
                                            die();
                                        }
                                    }
                                }

                                $sc = 0;
                                $shc = 0;
                                if($product->tax_type == 2) {
                                    $shc = $product->shiping_charge;
                                } else {
                                    $shc = 0;
                                }

                                if($product->service_charge) {
                                    $sc = $product->service_charge;
                                } else {
                                    $sc = 0;
                                }

                                if($qty) {
                                    $qty = $qty;
                                } else {
                                    $qty = 1;
                                }

                                if(!isset($price) && $price == 0) {
                                    $price = $product->product_cost;
                                }

                                if($price) {
                                    $price = $price;
                                } else {
                                    $price = $product->product_cost;
                                }

                                $product_cost = $price + $tax_amount;

                                $t_price = round(($qty * $product_cost),2);

                                $cart_key = time().uniqid();
                                $cart_del = time();

                                $cartData[$cart_key] = array(
                                    'product_id' => $product->id,
                                    'qty'   => $qty,
                                    'original_price' => $product->original_price,
                                    'product_cost' => $product_cost,
                                    'price' => $price,
                                    'tax_amount' => $tax_amount,
                                    'total_price' => $t_price,
                                    'att_name' => $att_name,
                                    'att_value' => $att_value,
                                    'tax' => $product->tax,
                                    'tax_type' => $product->tax_type,
                                    'service_charge' => $sc,
                                    'shiping_charge' => $shc,
                                    'image' => $product->featured_product_img,
                                    'name'  => $product->product_title,
                                    'notes' => '',
                                    'is_offer' => 'No',
                                    'offer_id' => NULL,
                                    'offer_det_id' => NULL,
                                    'cart_key' => $cart_key,
                                    'cart_del' => $cart_del,
                                );

                                $users = session()->get('user');
                                if($users) {
                                    if($users->user_type == 4) {
                                        $carts = new Carts();
                                        if($carts) {
                                            $carts->product_id  = $product->id;
                                            $carts->user_id     = $users->id;
                                            $carts->name        = $product->product_title;
                                            $carts->original_price = $product->original_price;
                                            $carts->product_cost       = $product_cost;
                                            $carts->price       = $price;
                                            $carts->tax_amount = $tax_amount;
                                            $carts->total_price = $t_price;
                                            $carts->image       = $product->featured_product_img;
                                            $carts->att_name  = $att_name;
                                            $carts->att_value  = $att_value;
                                            $carts->tax  = $product->tax;
                                            $carts->tax_type  = $product->tax_type;
                                            $carts->service_charge  = $sc;
                                            $carts->shiping_charge  = $shc;
                                            $carts->qty         = $qty;
                                            $carts->cart_key    = $cart_key;
                                            $carts->cart_del    = $cart_del;
                                            $carts->is_offer    = "No";
                                            $carts->offer_id    = NULL;
                                            $carts->offer_det_id    = NULL;
                                            $carts->is_block    = 1;

                                            if($carts->save()) {
                                                $error = "Added to Cart Successfully!";
                                            }
                                        }
                                    }
                                }
                                

                                $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                                $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                                $request->session()->put('cart', $cartData);
                                $request->session()->put('cart_total', $cartAllData);

                                $error = "Added to Cart Successfully!";
                                Session::flash('message', 'Added to Cart Successfully!'); 
                                Session::flash('alert-class', 'alert-success');
                            }
                        } else {
                            Session::flash('message', 'Out of Stock. Only ' . $onhand_qty. '  Products Avaliable!'); 
                            Session::flash('alert-class', 'alert-danger');
                            $error = 7;
                        }
                    } else {
                        Session::flash('message', 'Out of Stock. Products Not Avaliable!'); 
                        Session::flash('alert-class', 'alert-danger');
                        $error = 7;
                    }
                } else {
                    Session::flash('message', 'Added to Cart Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    $error = 1;
                }           
            } else {
                Session::flash('message', 'Added to Cart Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                $error = 1;
            }
            echo $error;
        }
    }

    public function OfferAddToCart( Request $request) { 
        $data = Input::all();
        $page = "Offers";
        $log = session()->get('user');

        $rules = array(
            'select_offer_id.*'       => 'required|exists:offers,id',
            'select_offer_det_id.*'   => 'required|exists:offers_subs,id',
            'select_offer_type.*'     => 'required',
        );

        $messages=[
            'select_offer_id.*.required'=>'Offer Field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            Session::flash('message', 'Added to Cart Failed!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('offer_products');
        } else {
            $o_id = $data['select_offer_id'][0];
            $error = 0;
            $err = 0;
            $vals = array_count_values($data['select_offer_type']);
            $offers = Offers::Where('id', $o_id)->first();
            $offer_cost = 0;
            $offer_tax_amount = 0;
            $price = 0;
            
            if($offers) {
                if(sizeof($vals) != 0) {
                    if(isset($vals[1]) && isset($vals[2]) && $offers->x_pro_cnt == $vals[1] && $offers->y_pro_cnt == $vals[2]) {
                        if((date('Y-m-d H:i:s', strtotime($offers->offer_start)) <= date('Y-m-d H:i:s'))) {
                            if((date('Y-m-d H:i:s', strtotime($offers->offer_end)) >= date('Y-m-d H:i:s'))) {
                                if($data['select_offer_det_id'] && sizeof($data['select_offer_det_id']) != 0) {
                                    $qty = 1;
                                    foreach ($data['select_offer_det_id'] as $okey => $ovalue) {
                                        $cart_key = time().uniqid();

                                        $cart_del = time();

                                        $off_subs = OffersSub::Where('id', $ovalue)->first();
                                        if($off_subs) {
                                            $offer_cost = $off_subs->offer_cost;
                                            $offer_tax_amount = $off_subs->offer_tax_amount;
                                            $price = $off_subs->offer_price;
                                            $att_name = $off_subs->att_name;
                                            $att_value = $off_subs->att_value;
                                            
                                            $product = Products::where('id', $off_subs->product_id)->first();
                                            if($product) {
                                                $id = $product->id;
                                                $onhand_qty = $off_subs->qty;
                                                if($onhand_qty && $onhand_qty != 0 && $onhand_qty > 0) {
                                                    if($onhand_qty >= $qty) {
                                                        $session = $request->session();
                                                        $cartAllData = array();
                                                        $cartData = ($session->get('cart')) ? $session->get('cart') : array();

                                                        $sc = 0;
                                                        $shc = 0;

                                                        if($off_subs->type == 1) {
                                                            if($product->tax_type == 2) {
                                                                $shc = $product->shiping_charge;
                                                            } else {
                                                                $shc = 0;
                                                            }

                                                            if($product->service_charge) {
                                                                $sc = $product->service_charge;
                                                            } else {
                                                                $sc = 0;
                                                            }

                                                            if(!isset($price) && $price == 0) {
                                                                $offer_cost = $product->discounted_price;
                                                                $offer_tax_amount = $product->tax_amount;
                                                                $price = $product->product_cost;
                                                            }

                                                            if($price) {
                                                                $price = $price;
                                                            } else {
                                                                $offer_cost = $product->discounted_price;
                                                                $offer_tax_amount = $product->tax_amount;
                                                                $price = $product->product_cost;
                                                            }
                                                        }

                                                        if($qty) {
                                                            $qty = $qty;
                                                        } else {
                                                            $qty = 1;
                                                        }

                                                        $tax_amount = round($offer_tax_amount, 2);

                                                        $product_cost = $price + $offer_tax_amount;

                                                        $t_price = round(($qty * $product_cost),2);

                                                        $cartData[$cart_key] = array(
                                                            'product_id' => $product->id,
                                                            'qty'   => $qty,
                                                            'original_price' => $product->original_price,
                                                            'product_cost' => $product_cost,
                                                            'price' => $price,
                                                            'tax_amount' => $tax_amount,
                                                            'total_price' => $t_price,
                                                            'att_name' => $att_name,
                                                            'att_value' => $att_value,
                                                            'tax' => $product->tax,
                                                            'tax_type' => $product->tax_type,
                                                            'service_charge' => $sc,
                                                            'shiping_charge' => $shc,
                                                            'image' => $product->featured_product_img,
                                                            'name'  => $product->product_title,
                                                            'notes' => '',
                                                            'is_offer' => 'Yes',
                                                            'offer_id' => $off_subs->offer,
                                                            'offer_det_id' => $off_subs->id,
                                                            'cart_key' => $cart_key,
                                                            'cart_del' => $cart_del,
                                                        );

                                                        $users = session()->get('user');
                                                        if($users) {
                                                            if($users->user_type == 4) {
                                                                $carts = new Carts();
                                                                if($carts) {
                                                                    $carts->product_id  = $product->id;
                                                                    $carts->user_id     = $users->id;
                                                                    $carts->name        = $product->product_title;
                                                                    $carts->original_price = $product->original_price;
                                                                    $carts->product_cost       = $product_cost;
                                                                    $carts->price       = $price;
                                                                    $carts->tax_amount = $tax_amount;
                                                                    $carts->total_price = $t_price;
                                                                    $carts->image       = $product->featured_product_img;
                                                                    $carts->att_name  = $att_name;
                                                                    $carts->att_value  = $att_value;
                                                                    $carts->tax  = $product->tax;
                                                                    $carts->tax_type  = $product->tax_type;
                                                                    $carts->service_charge  = $sc;
                                                                    $carts->shiping_charge  = $shc;
                                                                    $carts->qty         = $qty;
                                                                    $carts->cart_key    = $cart_key;
                                                                    $carts->cart_del    = $cart_del;
                                                                    $carts->is_offer    = "Yes";
                                                                    $carts->offer_id = $off_subs->offer;
                                                                    $carts->offer_det_id = $off_subs->id;
                                                                    $carts->is_block    = 1;

                                                                    if($carts->save()) {
                                                                        $err = 1;
                                                                    } else {
                                                                        $error = 1;
                                                                    }
                                                                } else {
                                                                    $err = 1;
                                                                }
                                                            } else {
                                                                $err = 1;
                                                            }
                                                        } else {
                                                            $err = 1;
                                                        }

                                                        $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                                                        $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                                                        $request->session()->put('cart', $cartData);
                                                        $request->session()->put('cart_total', $cartAllData);
                                                    } else {
                                                        Session::flash('message', 'Out of Stock. Only ' . $onhand_qty. '  Products Avaliable!'); 
                                                        Session::flash('alert-class', 'alert-danger');
                                                        $error = 7; 
                                                        $cartData = ($session->get('cart')) ? $session->get('cart') : array();
                                                        if (array_key_exists($cart_key, $cartData)) {
                                                            foreach ($cartData as $index => $data) {
                                                                if ($data['cart_del'] == $cart_del) {
                                                                    unset($cartData[$index]);
                                                                }
                                                            }   
                                                        }
                                                        $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                                                        $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                                                        $request->session()->put('cart', $cartData);
                                                        $request->session()->put('cart_total', $cartAllData);
                                                        Carts::Where('offer_id', $o_id)->delete();
                                                        return redirect()->route('offer_products');
                                                    }
                                                } else {
                                                    Session::flash('message', 'Out of Stock. Products Not Avaliable!'); 
                                                    Session::flash('alert-class', 'alert-danger');
                                                    $error = 7;
                                                    $cartData = ($session->get('cart')) ? $session->get('cart') : array();
                                                    if (array_key_exists($cart_key, $cartData)) {
                                                        foreach ($cartData as $index => $data) {
                                                            if ($data['cart_del'] == $cart_del) {
                                                                unset($cartData[$index]);
                                                            }
                                                        }   
                                                    }
                                                    $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                                                    $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                                                    $request->session()->put('cart', $cartData);
                                                    $request->session()->put('cart_total', $cartAllData);
                                                    Carts::Where('offer_id', $o_id)->delete();
                                                    return redirect()->route('offer_products');
                                                }
                                            } else {
                                                Session::flash('message', 'Added to Cart Failed, Product Not Matched!'); 
                                                Session::flash('alert-class', 'alert-danger');
                                                $error = 1;
                                                $cartData = ($session->get('cart')) ? $session->get('cart') : array();
                                                if (array_key_exists($cart_key, $cartData)) {
                                                    foreach ($cartData as $index => $data) {
                                                        if ($data['cart_del'] == $cart_del) {
                                                            unset($cartData[$index]);
                                                        }
                                                    }   
                                                }
                                                $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                                                $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                                                $request->session()->put('cart', $cartData);
                                                $request->session()->put('cart_total', $cartAllData);
                                                Carts::Where('offer_id', $o_id)->delete();
                                                return redirect()->route('offer_products');
                                            }
                                        } else {
                                            Session::flash('message', 'Added to Cart Failed, Invalid Offer Products!'); 
                                            Session::flash('alert-class', 'alert-danger');
                                            $error = 1;
                                            $cartData = ($session->get('cart')) ? $session->get('cart') : array();
                                            if (array_key_exists($cart_key, $cartData)) {
                                                foreach ($cartData as $index => $data) {
                                                    if ($data['cart_del'] == $cart_del) {
                                                        unset($cartData[$index]);
                                                    }
                                                }   
                                            }
                                            $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                                            $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                                            $request->session()->put('cart', $cartData);
                                            $request->session()->put('cart_total', $cartAllData);
                                            Carts::Where('offer_id', $o_id)->delete();
                                            return redirect()->route('offer_products');
                                        }
                                    }

                                    if($error == 0 && $err == 1) {
                                        Session::flash('message', 'Added to Cart Successfully!'); 
                                        Session::flash('alert-class', 'alert-success');
                                        return redirect()->route('offer_products');
                                    } else {
                                        Session::flash('message', 'Added to Cart Failed!'); 
                                        $cartData = ($session->get('cart')) ? $session->get('cart') : array();
                                        if (array_key_exists($cart_key, $cartData)) {
                                            foreach ($cartData as $index => $data) {
                                                if ($data['cart_del'] == $cart_del) {
                                                    unset($cartData[$index]);
                                                }
                                            }   
                                        }
                                        $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                                        $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                                        $request->session()->put('cart', $cartData);
                                        $request->session()->put('cart_total', $cartAllData);
                                        Session::flash('alert-class', 'alert-danger');
                                        Carts::Where('offer_id', $o_id)->delete();
                                        return redirect()->route('offer_products');
                                    }
                                } else {
                                    Session::flash('message', 'Added to Cart Failed, Offer Items Not Available!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('offer_products');
                                }
                            } else {
                                Session::flash('message', 'Offer End, Please Try Another Offers!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return Redirect::to('/offer_products/' . $o_id);
                            }
                        } else {
                            Session::flash('message', 'Offer Not Started, Please Try Another Offers!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return Redirect::to('/offer_products/' . $o_id);
                        }
                    } else {
                        Session::flash('message', 'Its Buy '.$offers->x_pro_cnt.' Get '.$offers->y_pro_cnt.' Offer. So You have Select Only '.$offers->x_pro_cnt.' Main Products and '.$offers->y_pro_cnt.' Offer Products!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return Redirect::to('/offer_products/' . $o_id);
                    }
                } else {
                    Session::flash('message', 'Added to Cart Failed, Main Products and Offer Products Not Available!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('offer_products');
                }
            } else {
                Session::flash('message', 'Added to Cart Failed, Please Try Another Time!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('offer_products');
            }
        }
    }

    public function DeleteCart( Request $request) {
        $id = 0;
        if($request->ajax() && isset($request->id) && isset($request->cart_id) && isset($request->cart_key) && isset($request->cart_del)){
            $id = $request->id;
            $cart_id = $request->cart_id;
            $cart_key = $request->cart_key;
            $cart_del = $request->cart_del;
            $error = 1;
            if($id) {
                $product = Products::where('id',$id)->first();
                if($product){
                    $del_carts = "";
                    $users = session()->get('user');
                    if($users) {
                        if($users->user_type == 4) {
                            $del_carts = Carts::Where('id', $cart_id)->Where('user_id', $users->id)->Where('product_id', $id)->first();
                        }
                    }

                    $session = $request->session();
                    $cartAllData = array();
                    $cartData = ($session->get('cart')) ? $session->get('cart') : array();
                    if (array_key_exists($cart_key, $cartData)) {
                        foreach ($cartData as $index => $data) {
                            if ($data['cart_del'] == $cart_del) {
                                unset($cartData[$index]);
                            }
                        }

                        $users = session()->get('user');
                        if($users) {
                            if($users->user_type == 4) {
                                $carts = Carts::Where('id', $cart_id)->Where('user_id', $users->id)->Where('product_id', $id)->first();
                                if($carts) {
                                    if($carts->is_offer == 'Yes') {
                                        if(Carts::Where('cart_del', $carts->cart_del)->delete()) {
                                            $error = 'Cart Deleted Successfully!';
                                        }
                                    } else {
                                        if($carts->delete()) {
                                            $error = 'Cart Deleted Successfully!';
                                        }
                                    }
                                }
                            }
                        }

                        $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                        $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                        $request->session()->put('cart', $cartData);
                        $request->session()->put('cart_total', $cartAllData);
                        Session::flash('message', 'Cart Deleted Successfully!'); 
                        Session::flash('alert-class', 'alert-success');
                        $error = 0;
                    } else if ($del_carts) {
                        $users = session()->get('user');
                        if($users) {
                            if($users->user_type == 4) {
                                $carts = Carts::Where('id', $cart_id)->Where('user_id', $users->id)->Where('product_id', $id)->first();
                                if($carts) {
                                    if($carts->is_offer == 'Yes') {
                                        if(Carts::Where('cart_del', $carts->cart_del)->delete()) {
                                            $error = 'Cart Deleted Successfully!';
                                        }
                                    } else {
                                        if($carts->delete()) {
                                            $error = 'Cart Deleted Successfully!';
                                        }
                                    }
                                }
                            }
                        }

                        Session::flash('message', 'Cart Deleted Successfully!'); 
                        Session::flash('alert-class', 'alert-success');
                    } else {
                        Session::flash('message', 'Cart Deleted Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        $error = 1;
                    }
                } else {
                    Session::flash('message', 'Cart Deleted Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    $error = 1;
                }
            } else {
                Session::flash('message', 'Cart Deleted Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                $error = 1;
            }
        } else {
            Session::flash('message', 'Cart Deleted Failed!'); 
            Session::flash('alert-class', 'alert-danger');
            $error = 1;
        }
        echo $error;
    }

    public function Cart () {
        $users = session()->get('user');
        $ses_carts = session()->get('cart');
        $ses_carts = json_decode(json_encode($ses_carts), FALSE);
        // print_r($ses_carts);die();
        $carts = "";
        if($users) {
            if($users->user_type == 4) {
                $carts = Carts::Where('user_id', $users->id)->get();
                return View::make("front_end.cart")->with(array('carts'=>$carts));
            } else {
                Session::flash('message', 'Please Login!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else if (isset($ses_carts)) {
            foreach ($ses_carts as $key => $value) {
                if(isset($value->att_name) && $value->att_name != 0 && isset($value->att_value) && $value->att_value != 0) {
                    $att_name = AttributesFields::Where('id', $value->att_name)->first();
                    $att_value = AttributesSettings::Where('id', $value->att_value)->first();
                    if($att_name) {
                        $value->{'att_n'} = $att_name->att_name;
                    } else {
                        $value->{'att_n'} = NULL;
                    }

                    if($att_value) {
                        $value->{'att_v'} = $att_value->att_value;
                    } else {
                        $value->{'att_v'} = NULL;
                    }
                }
            }
            return View::make("front_end.cart")->with(array('ses_carts'=>$ses_carts));
        } else {
            Session::flash('message', 'Please Login!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }
    }

    public function CheckOnHandQty( Request $request) {
        $id = 0;
        $qty = 0;
        $price = 0;
        $error = 1;
        $att_name = 0;
        $att_value = 0;
        $att_qty = 0;
        $is_offer = "No";
        $offer_det_id = 0;
        if($request->ajax() && isset($request->id) && isset($request->qty) && isset($request->price)){
            $id = $request->id;
            $qty = $request->qty;
            $price = $request->price;
            $att_name = $request->att_name;
            $att_value = $request->att_value;
            $is_offer = $request->is_offer;
            $offer_det_id = $request->offer_det_id;
            if($id) {
                $product = Products::where('id',$id)->first();

                if($product) {
                    if($is_offer == "Yes") {
                        $off_sub = OffersSub::Where('id', $offer_det_id)->first();
                        if($off_sub) {
                            if($off_sub->qty != 0) {
                                if($off_sub->qty >= $qty) {
                                    $error = $qty * $price;
                                } else {
                                    $error = array('onhand_qty' => $off_sub->qty, 'error' => '2');
                                    echo $error = json_encode($error);die();
                                }
                            } else {
                                $error = array('onhand_qty' => $onhand_qty, 'error' => '3');
                                echo $error = json_encode($error);die();
                            }
                        } else {
                            $error = 1;
                        }
                    }

                    $onhand_qty = $product->onhand_qty;
                    if($onhand_qty != 0) {
                        if($onhand_qty >= $qty) {
                            if($att_name && $att_value) {
                                $p_atts = ProductsAttributes::Where('product_id', $id)->Where('attribute_name', $att_name)->Where('attribute_values', $att_value)->first();
                                if($p_atts) {
                                    $att_qty = $p_atts->att_qty;
                                }

                                if($att_qty >= $qty) {
                                    if($qty != 0 && $price != 0) {
                                        $error = $qty * $price;
                                    }
                                } else {
                                    $error = array('onhand_qty' => $att_qty, 'error' => '2');
                                    $error = json_encode($error);
                                }
                            } else {
                                if($qty != 0 && $price != 0) {
                                    $error = $qty * $price;
                                }
                            }
                        } else {
                            // $error = 2;
                            $error = array('onhand_qty' => $onhand_qty, 'error' => '2');
                            $error = json_encode($error);
                        }
                    } else {
                        $error = array('onhand_qty' => $onhand_qty, 'error' => '3');
                        $error = json_encode($error);
                    }
                } else {
                    $error = 1;
                }
            } else {
                $error = 1;
            }
        } else {
            $error = 1;
        }

        echo $error;
    }

    public function CartSave( Request $request) {
        $data = Input::all();
        $users = session()->get('user');
        $ses_carts = session()->get('cart');
        $cartData = array();

        if($users) {
            if($users->user_type == 4) {
                if(count($data['cart_key']) != 0) {
                    $request->session()->forget('cart');

                    Carts::Where('user_id', $users->id)->delete();
                    foreach ($data['cart_key'] as $key => $value) {
                        if(isset($data['is_offer'][$key]) && $data['is_offer'][$key] == 'Yes') {
                            $data['qty'][$key] = 1;
                        }

                        $cartData[$value] = array(
                            'product_id' => (isset($data['product_id'][$key])) ? $data['product_id'][$key] : NULL,
                            'qty'        => (isset($data['qty'][$key])) ? $data['qty'][$key] : 1,
                            'original_price'      => (isset($data['original_price'][$key])) ? $data['original_price'][$key] : 0,
                            'product_cost'      => (isset($data['product_cost'][$key])) ? $data['product_cost'][$key] : 0,
                            'price'      => (isset($data['price'][$key])) ? $data['price'][$key] : 0,
                            'tax_amount'      => (isset($data['tax_amount'][$key])) ? $data['tax_amount'][$key] : 0,
                            'total_price'      => (isset($data['total_price'][$key])) ? $data['total_price'][$key] : 0,
                            'att_name'      => (isset($data['att_name'][$key])) ? $data['att_name'][$key] : NULL,
                            'att_value'      => (isset($data['att_value'][$key])) ? $data['att_value'][$key] : NULL,
                            'image'      => (isset($data['image'][$key])) ? $data['image'][$key] : NULL,
                            'tax' => (isset($data['tax'][$key])) ? $data['tax'][$key] : NULL,
                            'tax_type' => (isset($data['tax_type'][$key])) ? $data['tax_type'][$key] : NULL,
                            'service_charge' => (isset($data['service_charge'][$key])) ? $data['service_charge'][$key] : 0,
                            'shiping_charge' => (isset($data['shiping_charge'][$key])) ? $data['shiping_charge'][$key] : 0,
                            'name'       => (isset($data['name'][$key])) ? $data['name'][$key] : NULL,
                            'notes'      =>  (isset($data['notes'])) ? $data['notes'] : NULL,
                            'is_offer'      =>  (isset($data['is_offer'][$key])) ? $data['is_offer'][$key] : 'No',
                            'offer_id'      =>  (isset($data['offer_id'][$key])) ? $data['offer_id'][$key] : NULL,
                            'offer_det_id'      =>  (isset($data['offer_det_id'][$key])) ? $data['offer_det_id'][$key] : NULL,
                            'cart_key'      =>  $value,
                            'cart_del'      =>  (isset($data['cart_del'][$key])) ? $data['cart_del'][$key] : NULL,
                        );

                        
                        $carts = new Carts();
                        if($carts) {
                            $carts->product_id  = (isset($data['product_id'][$key])) ? $data['product_id'][$key] : NULL;
                            $carts->user_id     = $users->id;
                            $carts->name        = (isset($data['name'][$key])) ? $data['name'][$key] : NULL;
                            $carts->original_price       = (isset($data['original_price'][$key])) ? $data['original_price'][$key] : 0;
                            $carts->product_cost       = (isset($data['product_cost'][$key])) ? $data['product_cost'][$key] : 0;
                            $carts->price       = (isset($data['price'][$key])) ? $data['price'][$key] : 0;
                            $carts->tax_amount       = (isset($data['tax_amount'][$key])) ? $data['tax_amount'][$key] : 0;
                            $carts->total_price       = (isset($data['total_price'][$key])) ? $data['total_price'][$key] : 0;
                            $carts->att_name     = (isset($data['att_name'][$key])) ? $data['att_name'][$key] : NULL;
                            $carts->att_value     = (isset($data['att_value'][$key])) ? $data['att_value'][$key] : NULL;
                            $carts->tax  = (isset($data['tax'][$key])) ? $data['tax'][$key] : NULL;
                            $carts->tax_type  = (isset($data['tax_type'][$key])) ? $data['tax_type'][$key] : NULL;
                            $carts->service_charge  = (isset($data['service_charge'][$key])) ? $data['service_charge'][$key] : 0;
                            $carts->shiping_charge  = (isset($data['shiping_charge'][$key])) ? $data['shiping_charge'][$key] : 0;
                            $carts->image       = (isset($data['image'][$key])) ? $data['image'][$key] : NULL;
                            $carts->qty         = (isset($data['qty'][$key])) ? $data['qty'][$key] : 1;
                            $carts->notes       = (isset($data['notes'])) ? $data['notes'] : NULL;
                            $carts->is_offer       = (isset($data['is_offer'][$key])) ? $data['is_offer'][$key] : 'No';
                            $carts->offer_id       = (isset($data['offer_id'][$key])) ? $data['offer_id'][$key] : NULL;
                            $carts->offer_det_id       = (isset($data['offer_det_id'][$key])) ? $data['offer_det_id'][$key] : NULL;
                            $carts->cart_key       = $value;
                            $carts->cart_del       = (isset($data['cart_del'][$key])) ? $data['cart_del'][$key] : NULL;
                            $carts->is_block    = 1;

                            $carts->save();
                        }
                    }
                    
                    // $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                    $cartAllData['tot_qty'] = count($cartData);
                    $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                    $request->session()->put('cart', $cartData);
                    $request->session()->put('cart_total', $cartAllData);

                    Session::flash('message', 'Updated to Cart Successfully!'); 
                    Session::flash('alert-class', 'alert-success');
                    return redirect()->route('cart');
                } else {
                    Session::flash('message', 'Updated to Cart Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('cart');
                }   
            } else if (isset($ses_carts)) {
                if(count($data['cart_key']) != 0) {
                    $request->session()->forget('cart');
                    foreach ($data['cart_key'] as $key => $value) {
                        $cartData[$value] = array(
                            'product_id' => (isset($data['product_id'][$key])) ? $data['product_id'][$key] : NULL,
                            'qty'        => (isset($data['qty'][$key])) ? $data['qty'][$key] : 1,
                            'original_price'      => (isset($data['original_price'][$key])) ? $data['original_price'][$key] : 0,
                            'product_cost'      => (isset($data['product_cost'][$key])) ? $data['product_cost'][$key] : 0,
                            'price'      => (isset($data['price'][$key])) ? $data['price'][$key] : 0,
                            'tax_amount'      => (isset($data['tax_amount'][$key])) ? $data['tax_amount'][$key] : 0,
                            'total_price'      => (isset($data['total_price'][$key])) ? $data['total_price'][$key] : 0,
                            'att_name'      => (isset($data['att_name'][$key])) ? $data['att_name'][$key] : NULL,
                            'att_value'      => (isset($data['att_value'][$key])) ? $data['att_value'][$key] : NULL,
                            'tax' => (isset($data['tax'][$key])) ? $data['tax'][$key] : NULL,
                            'tax_type' => (isset($data['tax_type'][$key])) ? $data['tax_type'][$key] : NULL,
                            'service_charge' => (isset($data['service_charge'][$key])) ? $data['service_charge'][$key] : 0,
                            'shiping_charge' => (isset($data['shiping_charge'][$key])) ? $data['shiping_charge'][$key] : 0,
                            'image'      => (isset($data['image'][$key])) ? $data['image'][$key] : NULL,
                            'name'       => (isset($data['name'][$key])) ? $data['name'][$key] : NULL,
                            'notes'      =>  (isset($data['notes'])) ? $data['notes'] : NULL,
                            'is_offer'      =>  (isset($data['is_offer'][$key])) ? $data['is_offer'][$key] : 'No',
                            'offer_id'      =>  (isset($data['offer_id'][$key])) ? $data['offer_id'][$key] : NULL,
                            'offer_det_id'      =>  (isset($data['offer_det_id'][$key])) ? $data['offer_det_id'][$key] : NULL,
                            'cart_key'      =>  $value,
                            'cart_del'      =>  (isset($data['cart_del'][$key])) ? $data['cart_del'][$key] : NULL,
                        );
                    }
                    // $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                    $cartAllData['tot_qty'] = count($cartData);
                    $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                    $request->session()->put('cart', $cartData);
                    $request->session()->put('cart_total', $cartAllData);

                    Session::flash('message', 'Updated to Cart Successfully!'); 
                    Session::flash('alert-class', 'alert-success');
                    return redirect()->route('cart');
                } else {
                    Session::flash('message', 'Updated to Cart Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('cart');
                }                                                         
            } else {
                Session::flash('message', 'Updated to Cart Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('cart');
            }
        } else if (isset($ses_carts)) {
            if(count($data['cart_key']) != 0) {
                $request->session()->forget('cart');
                foreach ($data['cart_key'] as $key => $value) {
                    $cartData[$value] = array(
                        'product_id' => (isset($data['product_id'][$key])) ? $data['product_id'][$key] : NULL,
                        'qty'        => (isset($data['qty'][$key])) ? $data['qty'][$key] : 1,
                        'original_price'      => (isset($data['original_price'][$key])) ? $data['original_price'][$key] : 0,
                        'product_cost'      => (isset($data['product_cost'][$key])) ? $data['product_cost'][$key] : 0,
                        'price'      => (isset($data['price'][$key])) ? $data['price'][$key] : 0,
                        'tax_amount'      => (isset($data['tax_amount'][$key])) ? $data['tax_amount'][$key] : 0,
                        'total_price'      => (isset($data['total_price'][$key])) ? $data['total_price'][$key] : 0,
                        'att_name'      => (isset($data['att_name'][$key])) ? $data['att_name'][$key] : NULL,
                        'att_value'      => (isset($data['att_value'][$key])) ? $data['att_value'][$key] : NULL,
                        'tax'  => (isset($data['tax'][$key])) ? $data['tax'][$key] : NULL,
                        'tax_type'  => (isset($data['tax_type'][$key])) ? $data['tax_type'][$key] : NULL,
                        'service_charge'  => (isset($data['service_charge'][$key])) ? $data['service_charge'][$key] : 0,
                        'shiping_charge'  => (isset($data['shiping_charge'][$key])) ? $data['shiping_charge'][$key] : 0,
                        'image'      => (isset($data['image'][$key])) ? $data['image'][$key] : NULL,
                        'name'       => (isset($data['name'][$key])) ? $data['name'][$key] : NULL,
                        'notes'      =>  (isset($data['notes'])) ? $data['notes'] : NULL,
                        'cart_key'      =>  $value,
                        'cart_del'      =>  (isset($data['cart_del'][$key])) ? $data['cart_del'][$key] : NULL,
                    );
                }
                
                // $cartAllData['tot_qty'] = array_sum(array_column($cartData, 'qty'));
                $cartAllData['tot_qty'] = count($cartData);
                $cartAllData['tot_pce'] = array_sum(array_column($cartData, 'price'));

                $request->session()->put('cart', $cartData);
                $request->session()->put('cart_total', $cartAllData);

                Session::flash('message', 'Updated to Cart Successfully!'); 
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('cart');
            } else {
                Session::flash('message', 'Updated to Cart Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('cart');
            }                                                         
        } else {
            Session::flash('message', 'Updated to Cart Failed!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('cart');
        }
    }  

    public function WishList () {
        $users = session()->get('user');

        $wishlist = "";
        if($users) {
            if($users->user_type == 4) {
                $wishlist = WishList::Where('user_id', $users->id)->get();
                return View::make("front_end.wishlist")->with(array('wishlist'=>$wishlist));
            } else {
                return redirect()->route('signin');
            }
        } else {
            return redirect()->route('signin');
        }
    }  

    public function WishListSave( Request $request) { 
        $id = 0;
        if($request->ajax() && isset($request->id)){
            $id = $request->id;
            $error = 1;
            if($id) {
                $product = Products::where('id',$id)->first();
                $users = session()->get('user');
                if($users) {
                    if($users->user_type == 4) {
                        if($product) {
                            $check_wish = WishList::Where('product_id', $id)->Where('user_id', $users->id)->first();
                            
                            if ($check_wish) {
                                Session::flash('message', 'Already Added to Wish List!'); 
                                Session::flash('alert-class', 'alert-danger');
                                echo $error = 2;
                                die();
                            } else {
                                $wish = new WishList();
                                if($wish) {
                                    $wish->product_id       = $product->id;
                                    $wish->user_id          = $users->id;
                                    $wish->name             = $product->product_title;
                                    $wish->original_price   = $product->original_price;
                                    $wish->discounted_price = $product->discounted_price;
                                    $wish->image            = $product->featured_product_img;
                                    $wish->is_block         = 1;

                                    if($wish->save()) {
                                        $error = "Added to Wish List Successfully!";
                                        Session::flash('message', 'Added to Wish List Successfully!'); 
                                        Session::flash('alert-class', 'alert-success');
                                    } else {
                                        $error = 1;
                                        Session::flash('message', 'Added to Wish List Failed!'); 
                                        Session::flash('alert-class', 'alert-danger'); 
                                    }
                                } else {
                                    $error = 1;
                                    Session::flash('message', 'Added to Wish List Failed!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                }
                            }
                        } else {
                            Session::flash('message', 'Add To Wish List Could Not Possible This Time!'); 
                            Session::flash('alert-class', 'alert-danger');
                            $error = 1;
                        }
                    } else {
                        Session::flash('message', 'You Must Login and Continue Add Wish List!'); 
                        Session::flash('alert-class', 'alert-danger');
                        $error = 3;
                    }
                } else {
                    Session::flash('message', 'You Must Login and Continue Add Wish List!'); 
                    Session::flash('alert-class', 'alert-danger');
                    $error = 3;
                }           
            } else {
                Session::flash('message', 'Add To Wish List Could Not Possible This Time!'); 
                Session::flash('alert-class', 'alert-danger');
                $error = 1;
            }
            echo $error;
        }
    }  

    public function DeleteWishList( Request $request) { 
        $data = Input::all();
        $id = $data['id'];
        if($id) {
            $users = session()->get('user');
            if($users) {
                if($users->user_type == 4) {
                    $wishlist = WishList::Where('id', $id)->Where('user_id', $users->id)->first();
                    if($wishlist) {
                        if($wishlist->delete()) {
                            Session::flash('message', 'Deleted Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            return redirect()->route('wishlist');      
                        } else {
                            Session::flash('message', 'Deleted Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('wishlist');
                        }
                    } else {
                        Session::flash('message', 'Deleted Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('wishlist');
                    }
                } else {
                    Session::flash('message', 'You Must Login and Continue Add Wish List!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('signin');
                }
            } else {
                Session::flash('message', 'You Must Login and Continue Add Wish List!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            Session::flash('message', 'Deleted To Wish List Item Could Not Possible This Time!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('wishlist');
        }
    }    

    public function Checkout () {
        $users = session()->get('user');
        $ships = array();
        $items = "";
        if($users) {
            if($users->user_type == 4) {
                if($users->mobile_verify == 1) {                
                    if($users->email_verify == 1) {                
                        $lusr = User::Where('id', $users->id)->first();
                        if(!$lusr->checkout_verify) {
                            session()->forget('chk_verify');
                        }
                        $country = CountriesManagement::Where('is_block', 1)->get();
                        $state = StateManagements::Where('is_block', 1)->get();
                        $city = CityManagement::Where('is_block', 1)->get();
                        $ships = ShippingAddress::Where('user_id', $users->id)->Where('is_block', 1)->first();
                        $cutoff = TaxCutoff::Where('is_block', 1)->get();
                        $cutoff = json_decode($cutoff);

                        $items = Carts::Where('user_id', $users->id)->get();
                        return View::make("front_end.checkout")->with(array('items'=>$items, 'users'=>$users, 'country'=>$country, 'state'=>$state, 'city'=>$city, 'ships'=>$ships, 'cutoff'=>$cutoff));
                    } else {
                        Session::flash('message', 'You Must Verify Your E-Mail Address!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('my_account');
                    }
                } else {
                    Session::flash('message', 'You Must Verify Your Mobile Number!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('my_account');
                }
            } else {
                Session::flash('message', 'You Must Login And Continue to Checkout!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            Session::flash('message', 'You Must Login And Continue to Checkout!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }
    }  

    public function UpdateQty( Request $request) { 
        $id = 0;
        if($request->ajax() && isset($request->cart_id) && isset($request->qtys) && isset($request->totals)) {
            $id = $request->cart_id;
            $qtys = $request->qtys;
            $totals = $request->totals;
            $error = 1;
                
            $cart = Carts::where('id',$id)->first();
            if($cart) {
                $cart->qty = $qtys;
                $cart->total_price = $totals;
                if($cart->save()) {
                    $error = 0;
                } else {
                    $error = 1;
                }
            } else {
                $error = 1;
            }
        } else {
            $error = 1;
        }

        echo $error;
    }

    public function CheckCutOffs( Request $request) { 
        $sum = 0;
        $shc = 0;
        $sc = 0;
        $cod_amount = 0;
        $data = array('error'=>'0'); 

        if($request->ajax() && isset($request->sum) && isset($request->shc) && isset($request->sc) && isset($request->is_cod) && isset($request->tax_tot) && isset($request->cnt_shc)) {
            $sum = $request->sum;
            $tax_tot = $request->tax_tot;
            $cnt_shc = $request->cnt_shc;
            $shc = $request->shc;
            $p_shc = $request->shc;
            $sc = $request->sc;
            $is_cod = $request->is_cod;
                
            $cutoff = TaxCutoff::Where('is_block', 1)->get();
            $cutoff = $cutoff->sortBy('above_amount');
            $cod = Cod::Where('is_block', 1)->get();
            $cod = $cod->sortBy('above_amount');
            $cod_amount = 0.00;
            if($is_cod == 1) {
                if(sizeof($cod) != 0) {
                    foreach ($cod as $keyz => $valuez) {
                        if($valuez->above_amount < $sum) {
                            $cod_amount = $valuez->cod_amount;
                        }                    
                    }
                }
            }

            if(sizeof($cutoff) != 0) {
                foreach ($cutoff as $key => $value) {
                    if($value->above_amount < $sum) {
                        $shc = $value->shiping_amount;
                    }                    
                } 

                if($cnt_shc == 1) {
                    if($p_shc == 0) {
                        $shc = 0.00;
                    }
                }              

                // $tot = $sum + $shc + $cod_amount + $tax_tot;
                $tot = $sum + $shc + $cod_amount;
                $sum = round($sum, 2);
                $tax_tot = round($tax_tot, 2);
                $shc = round($shc, 2);
                $sc = round($sc, 2);
                $cod_amount = round($cod_amount, 2);
                $tot = round($tot, 2);
                $data = array('error' => '1', 'sum' => $sum, 'tax_tot' => $tax_tot, 'shc' => $shc,'sc' => $sc,'cod_amount' => $cod_amount,'tot' => $tot); 
            }
        }

        $data = json_encode($data);
        echo $data;
    }

    public function DataBilling( Request $request) { 
        $id = 0;
        $data = array('error'=>'0'); 

        if($request->ajax() && isset($request->id) && isset($request->type)) {
            $id = $request->id;
            $type = $request->type;
            
            if($type == "data_billing") {
                $user = User::Where('is_block', 1)->Where('id', $id)->first();
                if($user) {
                    $data = array('error' => '1', 'user' => $user); 
                }
            } else if($type == "data_shipping") {
                $user = ShippingAddress::Where('is_block', 1)->Where('user_id', $id)->first();
                if($user) {
                    $data = array('error' => '1', 'user' => $user); 
                }
            }     
        }

        $data = json_encode($data);
        echo $data;
    } 

    public function CheckoutTrans( Request $request) {
        $data = Input::all();
        $user = User::Where('id', $data['user_id'])->Where('is_block', 1)->first();
        if($user) {
            $rules = array(
                'first_name'        => 'required',
                'last_name'         => 'nullable',
                'email'             => 'required|unique:users,email,'.$data['user_id'].',id',
                'phone'             => 'required|numeric|unique:users,phone,'.$data['user_id'].',id',
                'alternate_contact' => 'nullable|numeric|unique:users,phone2,'.$data['user_id'].',id',
                'pincode'           => 'required|numeric|digits:6',
                'address1'          => 'required',
                'address2'          => 'required',
                'landmark'          => 'required',
                'country'           => 'required',
                'state'             => 'required',
                'city'              => 'required',
                'payment_method'    => 'nullable',
            );

            if(isset($data['shipping']) && $data['shipping'] == 1) {
                $rules['s_first_name'] = 'required';
                $rules['s_last_name']  = 'required';
                $rules['contact_no']   = 'required|numeric';
                $rules['address']      = 'required';
                $rules['s_landmark']   = 'required';
                $rules['s_city']       = 'required';
                $rules['s_pincode']    = 'required|numeric|digits:6';
                $rules['s_state']      = 'required';
                $rules['s_country']    = 'required';
            }

            $messages=[
                'address1.required'=>'The address field is required.',
                'address2.required'=>'The address field is required.',
                's_first_name.required'=>'The shipping first name field is required.',
                's_last_name.required'=>'The shipping last name field is required.',
                's_landmark.required'=>'The shipping landmark field is required.',
                's_city.required'=>'The shipping city field is required.',
                's_pincode.required'=>'The shipping pincode field is required.',
                's_pincode.numeric'=>'The shipping pincode field is input only numbers.',
                's_state.required'=>'The shipping state field is required.',
                's_country.required'=>'The shipping country field is required.',
            ];
            $validator = Validator::make(Input::all(), $rules,$messages);

            if ($validator->fails()) {
                // return View::make('front_end.checkout')->withErrors($validator);
                return redirect()->route('checkout')->withErrors($validator);
            } else {
                $sus1 = 0;
                $sus2 = 0;
                $sus3 = 0;
                $sus4 = 0;
                
                $user->first_name = $data['first_name'];
                $user->last_name  = $data['last_name'];
                $user->email      = $data['email'];
                $user->phone      = $data['phone'];
                $user->phone2     = $data['alternate_contact'];
                $user->pincode    = $data['pincode'];
                $user->address1   = $data['address1'];
                $user->address2   = $data['address2'];
                $user->landmark   = $data['landmark'];
                $user->country    = $data['country'];
                $user->state      = $data['state'];
                $user->city       = $data['city'];

                $ship = "";
                if ($user->save()) {
                    $sus1 = 1;

                    if(isset($data['shipping']) && $data['shipping'] == 1) {
                        if ($data['s_id']) {
                            $ship = ShippingAddress::Where('id', $data['s_id'])->first();
                        } else {
                            $ship = new ShippingAddress ();
                        }

                        if($ship) {
                            $ship->user_id    = $user->id;
                            $ship->first_name = $data['s_first_name'];
                            $ship->last_name  = $data['s_last_name'];
                            $ship->contact_no = $data['contact_no'];
                            $ship->address    = $data['address'];
                            $ship->landmark   = $data['s_landmark'];
                            $ship->city       = $data['s_city'];
                            $ship->pincode    = $data['s_pincode'];
                            $ship->state      = $data['s_state'];
                            $ship->country    = $data['s_country'];
                            $ship->is_block   = 1;

                            if($ship->save()) {
                                $sus4 = 1;
                            } else {
                                Session::flash('message', 'Your Shipping Address Add Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('checkout');
                            }
                        }
                    }

                    $log_user = session()->get('user');
                    if($log_user) {
                        if($log_user->user_type == 4) {
                            if($log_user->id == $user->id) {
                                session()->forget('user');
                                $users = User::Where('id', $user->id)->first();
                                if($users) {
                                    session()->put('user', $users);
                                }
                            }
                        }
                    }
                }

                $postal_code = "";
                $ava_deliv = 0;
                // $ava_deliv = 1;
                if(isset($data['shipping']) && $data['shipping'] == 1) {
                    $postal_code = $data['s_pincode'];
                } elseif ($user) {
                    $postal_code = $user->pincode;
                }

                if($postal_code) {
                    if($postal_code && strlen($postal_code) == 6) {
                        $log_shyp = new ShypliteAuth();
                        $login_shyp = $log_shyp->authenticatShyplite();
                        $login_shyp=json_decode($login_shyp, true);
                        // print_r($login_shyp);die();
                        
                        if(!isset($login_shyp['error'])) {
                            $timestamp = time();
                            $appID = $log_shyp->appID; 
                            $key = $log_shyp->key; 
                            $secret = $log_shyp->secret; 
                            if(isset($login_shyp['userToken'])) {
                                $secret = $login_shyp['userToken'];
                            }
                            $SellerID = $log_shyp->SellerID;

                            $sign = "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
                            $authtoken = rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
                            $ch = curl_init();
                            
                            $header = array(
                                "x-appid: $appID",
                                "x-timestamp: $timestamp",
                                "x-sellerid:$SellerID",
                                "Authorization: $authtoken"
                            );

                            curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/getserviceability/691021/'.$postal_code);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $server_output = curl_exec($ch);
                            // var_dump($server_output);
                            $resp = json_decode($server_output, true);
                            // print_r($resp);
                            // die();
                            $airCod = false;
                            $surfaceCod = false;
                            $surface10kgPrepaid = false;
                            $surface10kgCod = false;
                            $surface5kgPrepaid = false;
                            $surface5kgCod = false;
                            $lite2kgPrepaid = false;
                            $lite2kgCod = false;
                            $lite1kgPrepaid = false;
                            $lite1kgCod = false;
                            $liteHalfKgPrepaid = false;
                            $liteHalfKgCod = false;
                            if(isset($resp['serviceability']['surface10kgPrepaid']) && isset($resp['serviceability']['surface10kgCod']) && isset($resp['serviceability']['surface5kgPrepaid']) && isset($resp['serviceability']['surface5kgCod']) && isset($resp['serviceability']['lite2kgPrepaid']) && isset($resp['serviceability']['lite2kgCod']) && isset($resp['serviceability']['lite1kgPrepaid']) && isset($resp['serviceability']['lite1kgCod']) && isset($resp['serviceability']['lite0.5kgPrepaid']) && isset($resp['serviceability']['lite0.5kgCod'])) {
                                $surface10kgPrepaid = $resp['serviceability']['surface10kgPrepaid'];
                                $surface10kgCod = $resp['serviceability']['surface10kgCod'];
                                $surface5kgPrepaid = $resp['serviceability']['surface5kgPrepaid'];
                                $surface5kgCod = $resp['serviceability']['surface5kgCod'];
                                $lite2kgPrepaid = $resp['serviceability']['lite2kgPrepaid'];
                                $lite2kgCod = $resp['serviceability']['lite2kgCod'];
                                $lite1kgPrepaid = $resp['serviceability']['lite1kgPrepaid'];
                                $lite1kgCod = $resp['serviceability']['lite1kgCod'];
                                $liteHalfKgPrepaid = $resp['serviceability']['lite0.5kgPrepaid'];
                                $liteHalfKgCod = $resp['serviceability']['lite0.5kgCod'];
                            }
                            
                            // if(1==1) {
                            if($surface10kgPrepaid == TRUE && $surface10kgCod == TRUE && $surface5kgPrepaid == TRUE && $surface5kgCod == TRUE && $lite2kgPrepaid == TRUE && $lite2kgCod == TRUE && $lite1kgPrepaid == TRUE && $lite1kgCod == TRUE && $liteHalfKgPrepaid == TRUE && $liteHalfKgCod == TRUE) {
                                $ava_deliv = 1;
                            } elseif(isset($resp['error'])) {
                                Session::flash('message', 'Delivery Option Not Checked This Time!');
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('checkout');
                            } else {
                                Session::flash('message', 'Delivery Not Available for this Pincode!');
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('checkout');
                            }
                            curl_close($ch);
                        } else {
                            Session::flash('message', 'Delivery Option Not Checked This Time!');
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('checkout');
                        }
                    } else {
                        Session::flash('message', 'Enter Valid Pincode and Pincode Must 6 Numbers only!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('checkout');
                    }
                } else {
                    Session::flash('message', 'Your Pincode is Not Correct!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('checkout');
                }
                
                if($ava_deliv == 1) {
                    $cart = Carts::Where('user_id', $data['user_id'])->get();
                    $otp = mt_rand(100000, 999999);
                    $user->checkout_verify = $otp;
                    if($user->save()) {
                        $text = "Please Use this ".$otp." reference code to verify your checkout process, ecambiar.com";
                        $text = urlencode($text);
     
                        $curl = curl_init();
                     
                        // Send the POST request with cURL
                        curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => "http://smschub.com/api/sms/format/json",
                        CURLOPT_POST => 1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_HTTPHEADER => array('X-Authentication-Key:01fe318b290f9f9cb686a6bc28a4affa', 'X-Api-Method:MT'),
                        CURLOPT_POSTFIELDS => array(
                            'mobile' => $user->phone,
                            'route' => 'TL',
                            'text' => $text,
                            'sender' => 'GJICAM')));
                     
                        // Send the request & save response to $response
                        $response = curl_exec($curl);
                     
                        // Close request to clear up some resources
                        curl_close($curl);
                        $response = json_decode($response);
                        // Print response
                        if(isset($response->data->status) && $response->data->status == "success") {
                            Session::flash('message', 'Order Verification Code Send Successfully, Verify this Code to Checkout Process Complete!'); 
                            Session::flash('alert-class', 'alert-success');
                            Session::put('chk_verify', 1);
                            return redirect()->route('checkout');
                        } else {
                            Session::flash('message', 'Order Verification Code Send Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('checkout');
                        }
                    } else {
                        Session::flash('message', 'Order Verification Code Send Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('checkout');
                    }
                } else {
                    Session::flash('message', 'Your Pincode is Not Available For Delivery!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('checkout');
                }
            }
        } else {
            Session::flash('message', 'You Must Login And Continue to Checkout!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');     
        }
    }

    public function CheckoutVerif( Request $request) {
        $data = Input::all();
        $user = User::Where('id', $data['user_id'])->Where('is_block', 1)->first();
        if($user) {
            if($user->email_verify != 1 || $user->mobile_verify != 1) {
                Session::flash('message', 'Please Verify Your Mobile and Email!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('my_account');
            }

            $rules = array(
                'first_name'        => 'required',
                'last_name'         => 'nullable',
                'email'             => 'required|unique:users,email,'.$data['user_id'].',id',
                'phone'             => 'required|numeric|unique:users,phone,'.$data['user_id'].',id',
                'alternate_contact' => 'nullable|numeric|unique:users,phone2,'.$data['user_id'].',id',
                'pincode'           => 'required|numeric|digits:6',
                'address1'          => 'required',
                'address2'          => 'required',
                'landmark'          => 'required',
                'country'           => 'required',
                'state'             => 'required',
                'city'              => 'required',
                'payment_method'    => 'required',
            );

            if(isset($data['shipping']) && $data['shipping'] == 1) {
                $rules['s_first_name'] = 'required';
                $rules['s_last_name']  = 'required';
                $rules['contact_no']   = 'required|numeric';
                $rules['address']      = 'required';
                $rules['s_landmark']   = 'required';
                $rules['s_city']       = 'required';
                $rules['s_pincode']    = 'required|numeric|digits:6';
                $rules['s_state']      = 'required';
                $rules['s_country']    = 'required';
            }

            $messages=[
                'address1.required'=>'The address field is required.',
                'address2.required'=>'The address field is required.',
                's_first_name.required'=>'The shipping first name field is required.',
                's_last_name.required'=>'The shipping last name field is required.',
                's_landmark.required'=>'The shipping landmark field is required.',
                's_city.required'=>'The shipping city field is required.',
                's_pincode.required'=>'The shipping pincode field is required.',
                's_pincode.numeric'=>'The shipping pincode field is input only numbers.',
                's_state.required'=>'The shipping state field is required.',
                's_country.required'=>'The shipping country field is required.',
            ];
            $validator = Validator::make(Input::all(), $rules,$messages);

            if ($validator->fails()) {
                return redirect()->route('checkout')->withErrors($validator);
            } else {
                $sus1 = 0;
                $sus2 = 0;
                $sus3 = 0;
                $sus4 = 0;
                
                $user->first_name = $data['first_name'];
                $user->last_name  = $data['last_name'];
                $user->email      = $data['email'];
                $user->phone      = $data['phone'];
                $user->phone2     = $data['alternate_contact'];
                $user->pincode    = $data['pincode'];
                $user->address1   = $data['address1'];
                $user->address2   = $data['address2'];
                $user->landmark   = $data['landmark'];
                $user->country    = $data['country'];
                $user->state      = $data['state'];
                $user->city       = $data['city'];

                $ship = "";
                if ($user->save()) {
                    $sus1 = 1;

                    if(isset($data['shipping']) && $data['shipping'] == 1) {
                        if ($data['s_id']) {
                            $ship = ShippingAddress::Where('id', $data['s_id'])->first();
                        } else {
                            $ship = new ShippingAddress ();
                        }

                        if($ship) {
                            $ship->user_id    = $user->id;
                            $ship->first_name = $data['s_first_name'];
                            $ship->last_name  = $data['s_last_name'];
                            $ship->contact_no = $data['contact_no'];
                            $ship->address    = $data['address'];
                            $ship->landmark   = $data['s_landmark'];
                            $ship->city       = $data['s_city'];
                            $ship->pincode    = $data['s_pincode'];
                            $ship->state      = $data['s_state'];
                            $ship->country    = $data['s_country'];
                            $ship->is_block   = 1;

                            if($ship->save()) {
                                $sus4 = 1;
                            } else {
                                Session::flash('message', 'Your Shipping Address Add Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('checkout');
                            }
                        }
                    }

                    $log_user = session()->get('user');
                    if($log_user) {
                        if($log_user->user_type == 4) {
                            if($log_user->id == $user->id) {
                                session()->forget('user');
                                $users = User::Where('id', $user->id)->first();
                                if($users) {
                                    session()->put('user', $users);
                                }
                            }
                        }
                    }
                }

                $postal_code = "";
                $ava_deliv = 0;
                $ava_deliv = 1;
                if(isset($data['shipping']) && $data['shipping'] == 1) {
                    $postal_code = $data['s_pincode'];
                } elseif ($user) {
                    $postal_code = $user->pincode;
                }
                
                // if($postal_code) {
                //     if($postal_code && strlen($postal_code) == 6) {
                //         $log_shyp = new ShypliteAuth();
                //         $login_shyp = $log_shyp->authenticatShyplite();
                //         $login_shyp=json_decode($login_shyp, true);
                //         // print_r($login_shyp);die();
                        
                //         if(!isset($login_shyp['error'])) {
                //             $timestamp = time();
                //             $appID = $log_shyp->appID; 
                //             $key = $log_shyp->key; 
                //             $secret = $log_shyp->secret; 
                //             if(isset($login_shyp['userToken'])) {
                //                 $secret = $login_shyp['userToken'];
                //             }
                //             $SellerID = $log_shyp->SellerID;

                //             $sign = "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
                //             $authtoken = rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
                //             $ch = curl_init();
                            
                //             $header = array(
                //                 "x-appid: $appID",
                //                 "x-timestamp: $timestamp",
                //                 "x-sellerid:$SellerID",
                //                 "Authorization: $authtoken"
                //             );

                //             curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/getserviceability/691021/'.$postal_code);
                //             curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //             $server_output = curl_exec($ch);
                //             // var_dump($server_output);
                //             $resp = json_decode($server_output, true);
                //             // print_r($resp);
                //             // print_r($resp['serviceability']['surface10kgPrepaid']);
                //             // die();
                //             $airCod = false;
                //             $surfaceCod = false;
                //             $surface10kgPrepaid = false;
                //             $surface10kgCod = false;
                //             $surface5kgPrepaid = false;
                //             $surface5kgCod = false;
                //             $lite2kgPrepaid = false;
                //             $lite2kgCod = false;
                //             $lite1kgPrepaid = false;
                //             $lite1kgCod = false;
                //             $liteHalfKgPrepaid = false;
                //             $liteHalfKgCod = false;
                //             if(isset($resp['serviceability']['surface10kgPrepaid']) && isset($resp['serviceability']['surface10kgCod']) &&  isset($resp['serviceability']['surface5kgPrepaid']) && isset($resp['serviceability']['surface5kgCod']) && isset($resp['serviceability']['lite2kgPrepaid']) && isset($resp['serviceability']['lite2kgCod']) && isset($resp['serviceability']['lite1kgPrepaid']) && isset($resp['serviceability']['lite1kgCod']) && isset($resp['serviceability']['lite0.5kgPrepaid']) && isset($resp['serviceability']['lite0.5kgCod'])) {
                //                 $surface10kgPrepaid = $resp['serviceability']['surface10kgPrepaid'];
                //                 $surface10kgCod = $resp['serviceability']['surface10kgCod'];
                //                 $surface5kgPrepaid = $resp['serviceability']['surface5kgPrepaid'];
                //                 $surface5kgCod = $resp['serviceability']['surface5kgCod'];
                //                 $lite2kgPrepaid = $resp['serviceability']['lite2kgPrepaid'];
                //                 $lite2kgCod = $resp['serviceability']['lite2kgCod'];
                //                 $lite1kgPrepaid = $resp['serviceability']['lite1kgPrepaid'];
                //                 $lite1kgCod = $resp['serviceability']['lite1kgCod'];
                //                 $liteHalfKgPrepaid = $resp['serviceability']['lite0.5kgPrepaid'];
                //                 $liteHalfKgCod = $resp['serviceability']['lite0.5kgCod'];
                //             }
                            
                //             // if(1==1) {
                //             if($surface10kgPrepaid == TRUE && $surface10kgCod == TRUE && $surface5kgPrepaid == TRUE && $surface5kgCod == TRUE && $lite2kgPrepaid == TRUE && $lite2kgCod == TRUE && $lite1kgPrepaid == TRUE && $lite1kgCod == TRUE && $liteHalfKgPrepaid == TRUE && $liteHalfKgCod == TRUE) {
                //                 $ava_deliv = 1;
                //             } elseif(isset($resp['error'])) {
                //                 Session::flash('message', 'Delivery Option Not Checked This Time!');
                //                 Session::flash('alert-class', 'alert-danger');
                //                 return redirect()->route('checkout');
                //             } else {
                //                 Session::flash('message', 'Delivery Not Available for this Pincode!');
                //                 Session::flash('alert-class', 'alert-danger');
                //                 return redirect()->route('checkout');
                //             }
                //             curl_close($ch);
                //         } else {
                //             Session::flash('message', 'Delivery Option Not Checked This Time!');
                //             Session::flash('alert-class', 'alert-danger');
                //             return redirect()->route('checkout');
                //         }
                //     } else {
                //         Session::flash('message', 'Enter Valid Pincode and Pincode Must 6 Numbers only!'); 
                //         Session::flash('alert-class', 'alert-danger');
                //         return redirect()->route('checkout');
                //     }
                // } else {
                //     Session::flash('message', 'Your Pincode is Not Correct!'); 
                //     Session::flash('alert-class', 'alert-danger');
                //     return redirect()->route('checkout');
                // }
                
                if($ava_deliv == 1) {
                    $order = new Orders();
                    $cart = Carts::Where('user_id', $data['user_id'])->get();

                    if($order) {
                        $total_items = 0;
                        $total_amount = 0;
                        $net_amount = 0;
                        $total_service = 0;
                        $total_shiping = 0;
                        $tax_amount = 0;
                        if(count($cart) != 0) {
                            $total_items = $cart->sum('qty');

                            $product_serv = DB::table('carts')
                                ->select(DB::raw('sum(carts.service_charge) AS serv_total'))
                                ->join('products', 'products.id', '=', 'carts.product_id')
                                ->where('carts.user_id', $data['user_id'])
                                ->first();
                            if($product_serv->serv_total) {
                                $total_service = $product_serv->serv_total;
                            } else {
                                $total_service = 0;
                            }

                            $product_ships = DB::table('carts')
                                ->select(DB::raw('MAX(carts.shiping_charge) AS ship_total'))
                                ->join('products', 'products.id', '=', 'carts.product_id')
                                ->where('carts.user_id', $data['user_id'])
                                ->where('products.tax_type', 2)
                                ->first();
                            $total_shiping = $product_ships->ship_total;
                            if($product_ships->ship_total) {
                                $total_shiping = $product_ships->ship_total;
                            } else {
                                $total_shiping = 0;
                            }

                            $net_total = DB::table('carts')
                                ->select(DB::raw('sum(total_price) AS total'))
                                // ->select(DB::raw('Round(sum(total_price) ,2) AS total'))
                                ->where('user_id', $data['user_id'])
                                ->first();
                            if($net_total->total) {
                                $total_amount = $net_total->total;
                            } else {
                                $total_amount = 0;
                            }

                            $tax_total = DB::table('carts')
                                ->select(DB::raw('sum(tax_amount) AS taxs'))
                                // ->select(DB::raw('Round(sum(total_price) ,2) AS total'))
                                ->where('user_id', $data['user_id'])
                                ->first();
                            if($tax_total->taxs) {
                                $tax_amount = $tax_total->taxs;
                            } else {
                                $tax_amount = 0;
                            }
                            // $total_amount = $net_total->total;

                            $cutoff = TaxCutoff::Where('is_block', 1)->get();
                            $cutoff = $cutoff->sortBy('above_amount');
                            if(sizeof($cutoff) != 0) {
                                foreach ($cutoff as $ckey => $cvalue) {
                                    if($cvalue->above_amount < $total_amount) {
                                        $total_shiping = $cvalue->shiping_amount;
                                    }                    
                                } 
                            }

                            if(count($cart) == 1) {
                                if($cart[0]->shiping_charge == 0) {
                                    $total_shiping = 0.00;
                                }
                            }

                            $cod = Cod::Where('is_block', 1)->get();
                            $cod = $cod->sortBy('above_amount');
                            $cod_amount = 0;
                            if($data['payment_method'] == 1) {
                                if(sizeof($cod) != 0) {
                                    foreach ($cod as $keyz => $valuez) {
                                        if($valuez->above_amount < $total_amount) {
                                            $cod_amount = $valuez->cod_amount;
                                        }                    
                                    }
                                }
                            }

                            // $net_amount = $total_amount + $tax_amount + $total_shiping + $cod_amount;
                            $net_amount = $total_amount + $total_shiping + $cod_amount;
                            $total_amount = round($total_amount, 2);
                            $tax_amount = round($tax_amount, 2);
                            $net_amount = round($net_amount, 2);
                            $total_service = round($total_service, 2);
                            $total_shiping = round($total_shiping, 2);
                            $cod_amount = round($cod_amount, 2);
                        }

                        if($net_amount != 0) {
                            $max = Orders::max('order_code');
                            $max_id = "00001";
                            $max_st = "Order";
                            if($max) {
                                $max_no = substr($max, 5);
                                $increment = (int)$max_no + 1;
                                $data['order_code'] = $max_st.sprintf("%05d", $increment);
                            } else {
                                $data['order_code'] = $max_st.$max_id;
                            }

                            $order->order_code = $data['order_code'];
                            $order->order_date = date('Y-m-d');
                            $order->user_id = $data['user_id'];
                            $order->payment_mode = $data['payment_method'];
                            $order->contact_person = $data['first_name'].' '.$data['last_name'];
                            $order->contact_email = $data['email'];
                            $order->contact_no = $data['phone'];
                            if(isset($data['shipping'])) {
                                $order->shipping_address_flag = $data['shipping'];
                            } else {
                                $order->shipping_address_flag = 0;
                            }

                            $deli_pincode = "";
                            $deli_city = "";

                            if(isset($data['shipping']) && $data['shipping'] == 1 && $sus4 == 1) {
                                if($ship) {
                                    $order->shipping_address = $ship->address.','.$ship->City->city_name.','.$ship->pincode.','.$ship->State->state.','.$ship->Country->country_name;
                                    $deli_pincode = $ship->pincode;
                                    $deli_city = $ship->City->city_name;
                                } else {
                                    $order->shipping_address = $user->address1.','.$user->address2.','.$user->City->city_name.','.$user->pincode.','.$user->State->state.','.$user->Country->country_name; 
                                    $deli_pincode = $user->pincode;
                                    $deli_city = $user->City->city_name;
                                }
                            } else {
                                $order->shipping_address = $user->address1.','.$user->address2.','.$user->City->city_name.','.$user->pincode.','.$user->State->state.','.$user->Country->country_name; 
                                $deli_pincode = $user->pincode;
                                $deli_city = $user->City->city_name;

                            }

                            $order->city = $deli_city;
                            $order->pincode = $deli_pincode;
                            $order->total_items = $total_items;
                            $order->total_amount = $total_amount;
                            // $order->tax_amount = $tax_amount;
                            $order->service_charge = $total_service;
                            $order->shipping_charge = $total_shiping;
                            $order->cod_charge = $cod_amount;
                            $order->net_amount = $net_amount;
                            $order->order_status = 1;
                            $order->payment_status = 0;
                            $order->remarks = NULL;
                            $order->is_block = 1;

                            // $order->discount_flag = NULL;
                            // $order->discount = NULL;
                            // $order->delivery_date = NULL;
                            // $order->delivery_status = NULL;
                            if($order->save()) {
                                if (isset($data['product_id']) && count($data['product_id']) != 0) {
                                    foreach ($data['product_id'] as $key => $value) {
                                        $order_details = new OrderDetails();
                                        $order_details->order_id = $order->id;
                                        $order_details->product_id = $value;
                                        
                                        if(isset($data['name'][$key])) {
                                            $order_details->product_title = $data['name'][$key];
                                        } else {
                                            $order_details->product_title = NULL;
                                        }
                                        
                                        if(isset($data['qty'][$key])) {
                                            $order_details->order_qty = $data['qty'][$key];
                                        } else {
                                            $order_details->order_qty = NULL;
                                        }

                                        if(isset($data['att_name'][$key])) {
                                            $order_details->att_name = $data['att_name'][$key];
                                        } else {
                                            $order_details->att_name = NULL;
                                        }

                                        if(isset($data['att_value'][$key])) {
                                            $order_details->att_value = $data['att_value'][$key];
                                        } else {
                                            $order_details->att_value = NULL;
                                        }

                                        if(isset($data['tax'][$key])) {
                                            $order_details->tax = $data['tax'][$key];
                                        } else {
                                            $order_details->tax = NULL;
                                        }

                                        if(isset($data['tax_type'][$key])) {
                                            $order_details->tax_type = $data['tax_type'][$key];
                                        } else {
                                            $order_details->tax_type = NULL;
                                        }

                                        if(isset($data['product_cost'][$key])) {
                                            $order_details->unitprice = $data['product_cost'][$key];
                                        } else {
                                            $order_details->unitprice = 0.00;
                                        }

                                        if(isset($data['total'][$key])) {
                                            $order_details->totalprice = $data['total'][$key];
                                        } else {
                                            $order_details->totalprice = 0.00;
                                        }

                                        /*if(isset($data['tax_amount'][$key])) {
                                            $order_details->tax_amount = $data['tax_amount'][$key];
                                        } else {
                                            $order_details->tax_amount = 0.00;
                                        }*/
                                        
                                        $order_details->is_block = 1;

                                        if($order_details->save()) {
                                            $sus2 = 1;
                                        }                                
                                    }                            
                                }

                                if($data['payment_method'] == 1) {
                                    $order_trans = new OrdersTransactions();
                                    $t_max = OrdersTransactions::max('trans_code');
                                    $t_max_id = "00001";
                                    $t_max_st = "Trans";
                                    if($t_max) {
                                        $t_max_no = substr($t_max, 5);
                                        $t_increment = (int)$t_max_no + 1;
                                        $data['trans_code'] = $t_max_st.sprintf("%05d", $t_increment);
                                    } else {
                                        $data['trans_code'] = $t_max_st.$t_max_id;
                                    }

                                    $order_trans->trans_code = $data['trans_code'];
                                    $order_trans->trans_date = date('Y-m-d H:i:s');
                                    $order_trans->order_id = $order->id;
                                    $order_trans->net_amount = $net_amount;
                                    $order_trans->amountpaid = "Unpaid";
                                    $order_trans->paymentmode = $data['payment_method'];
                                    $order_trans->gatewaytransactionid = NULL;
                                    $order_trans->trans_status = "PENDING";
                                    $order_trans->remarks = NULL;
                                    $order_trans->is_block = 1;

                                    if($order_trans->save()) {
                                        $sus3 = 1;
                                    }
                                } else if($data['payment_method'] == 2) {
                                    if($order) {
                                        $order->order_status = 5;
                                        $order->payment_status = 2;
                                        $order->save();
                                    }
                                    $pay_set = PaymentSettings::first();
                                    return View::make("front_end.payment_start")->with(array('order'=>$order, 'pay_set'=>$pay_set));
                                } 

                                if($sus2 == 1 && $sus3 == 1) {
                                    $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                                    $admin_email = "info@ecambiar.com";
                                    if($adm) {
                                        $admin_email = $adm->email;
                                    }

                                    $logos = \DB::table('logo_settings')->first();
                                    $logo_path = 'images/logo';
                                    $logo = "";
                                    if($logos) {
                                        $logo = asset($logo_path.'/'.$logos->logo_image);
                                    } else {
                                        $logo = asset('images/logo.png');
                                    }

                                    $general = \DB::table('general_settings')->first();
                                    $site_name = "ECambiar";
                                    if($general){
                                        $site_name = $general->site_name;
                                    } else {
                                        $site_name = "ECambiar";
                                    }

                                    $net_comis = 0.00;
                                    $net_mer_amt = 0.00;
                                    $customer_name = "";
                                    $contact = "";
                                    $address = "";
                                    $order_code = $order->order_code;
                                    $order_date = date('d-m-Y', strtotime($order->order_date));
                                    $net_tot = $order->net_amount;
                                    // $tax_tot = $order->tax_amount;
                                    $details = "";
                                    $order_detail = OrderDetails::Where('is_block', 1)->Where('order_id', $order->id)->get();
                                    $details="";
                                    if($order_detail) {
                                        foreach ($order_detail as $key => $value) {
                                            $stock = Products::Where('id', $value->product_id)->first();

                                            $off_avi  = 0;
                                            if (isset($data['is_offer'][$key])) {
                                                if($data['is_offer'][$key] == "Yes") {
                                                    if (isset($data['offer_det_id'][$key])) {
                                                        $off_sub = OffersSub::Where('id', $data['offer_det_id'][$key])->first();
                                                        if($off_sub) {
                                                            
                                                            $off_trans = new OfferTransaction();
                                                            $off_trans->order_code   = $order_code;
                                                            $off_trans->offer = $off_sub->offer;
                                                            $off_trans->offer_det_id = $off_sub->id;
                                                            $off_trans->product_id = $off_sub->product_id;
                                                            $off_trans->att_name = $off_sub->att_name;
                                                            $off_trans->att_value = $off_sub->att_value;
                                                            $off_trans->previous_qty = $off_sub->qty;
                                                            $off_trans->current_qty = $off_sub->qty - $value->order_qty;
                                                            $off_trans->date = date('Y-m-d');

                                                            $off_trans->save();


                                                            $off_sub->qty = $off_sub->qty - $value->order_qty;
                                                            $off_sub->save();
                                                        }
                                                    }
                                                } else {
                                                    $off_avi  = 1;
                                                }
                                            } else {
                                                $off_avi  = 1;
                                            }

                                            if($off_avi  == 1) {
                                                if($stock && ($stock->onhand_qty != 0)) {
                                                    $stock_trans = new StockTransactions();
                                                    $stock_trans->order_code   = $order_code;
                                                    $stock_trans->product_id   = $value->product_id;
                                                    $stock_trans->att_name     = $value->att_name;
                                                    $stock_trans->att_value    = $value->att_value;
                                                    $stock_trans->previous_qty = $stock->onhand_qty;
                                                    $stock_trans->current_qty  = $stock->onhand_qty - $value->order_qty;
                                                    $stock_trans->date         = date('Y-m-d');
                                                    $stock_trans->remarks      = $value->product_title.' is ordered.';

                                                    $stock->onhand_qty = $stock->onhand_qty - $value->order_qty;
                                                    
                                                    $p_atts = ProductsAttributes::Where('product_id', $value->product_id)->Where('attribute_name', $value->att_name)->Where('attribute_values', $value->att_value)->first();
                                                    if($p_atts) {
                                                        $stock_trans->att_previous_qty = $p_atts->att_qty;
                                                        $stock_trans->att_current_qty  = $p_atts->att_qty - $value->order_qty;
                                                        
                                                        $p_atts->att_qty = $p_atts->att_qty - $value->order_qty;
                                                        $p_atts->save();
                                                    }

                                                    if($stock->save() && $stock_trans->save()) {
                                                        $sck = 1;
                                                    }
                                                }
                                            }

                                            if($stock && $stock->created_user != 1) {
                                                if($stock->Creatier->user_type == 2 || $stock->Creatier->user_type == 3) {
                                                    $com_per = $stock->Creatier->commission;
                                                    $t_pce = $value->totalprice;
                                                    $admin_com = round($t_pce * ($com_per / 100), 2);
                                                    $mer_amt = round($t_pce - $admin_com, 2);

                                                    $comis = new AdminCommision();
                                                    $comis->order_code   = $order_code;
                                                    $comis->order_dets   = $value->id;
                                                    $comis->product_id   = $value->product_id;
                                                    $comis->att_name     = $value->att_name;
                                                    $comis->att_value    = $value->att_value;
                                                    $comis->merchant_id  = $stock->Creatier->id;
                                                    $comis->amount       = $admin_com;
                                                    $comis->merchant_amount = $mer_amt;
                                                    $comis->paid_status  = 0;
                                                    $comis->remarks      = $value->product_title.' product against Admin Commision is Rs. '.$admin_com.' set.';
                                                    $comis->save();

                                                    $net_comis   = $net_comis + $admin_com;
                                                    $net_mer_amt = $net_mer_amt + $mer_amt;
                                                }
                                            }

                                            $att_tit = "";
                                            if(isset($value->att_name) && $value->att_name != 0) {
                                                if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) {
                                                    $att_tit = '<span>('.$value->AttName->att_name.' : '.$value->AttValue->att_value.')</span>';
                                                }
                                            }

                                            $details.= '<tr>
                                                <td style="font-size: 11px;font-weight: 600;color:black;border:1px solid black;text-align:center;"> '.$value->product_title.' '. $att_tit .'</td>
                                                <td style="font-size: 11px;font-weight: 600;color:black;border:1px solid black;text-align:center;"> '.$value->order_qty.'</td>
                                                <td style="font-size: 11px;font-weight: 600;color:black;border:1px solid black;text-align:center;">Rs.  '.$value->unitprice.'</td>
                                                <td style="font-size: 11px;font-weight: 600;color: black;border: 1px solid black;text-align: right;">Rs.  '.$value->totalprice.'</td>
                                            </tr>';
                                        }
                                    }

                                    if($order) {
                                        $order->net_commision = $net_comis;
                                        $order->net_merchant_amout = $net_mer_amt;
                                        $order->save();
                                    }


                                    if(isset($data['shipping']) && $data['shipping'] == 1 && $sus4 == 1) {
                                        if($ship) {
                                            $customer_name = $ship->first_name.' '.$ship->last_name;
                                            $address = $ship->address.','.$ship->City->city_name.','.$ship->pincode.','.$ship->State->state.','.$ship->Country->country_name;
                                            $contact = $ship->contact_no;
                                        } else if ($user) {
                                            $customer_name = $user->first_name.' '.$user->last_name;
                                            $address = $user->address1.','.$user->address2.','.$user->City->city_name.','.$user->pincode.','.$user->State->state.','.$user->Country->country_name;
                                            $contact = $user->phone.','.$user->phone2;
                                        }
                                    } else if ($user) {
                                        $customer_name = $user->first_name.' '.$user->last_name;
                                        $address = $user->address1.','.$user->address2.','.$user->City->city_name.','.$user->pincode.','.$user->State->state.','.$user->Country->country_name;
                                        $contact = $user->phone.','.$user->phone2;
                                    }

                                    $name = $user->first_name.' '.$user->last_name;
                                    $email = $user->email;

                                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                    $headers.= "MIME-Version: 1.0\r\n";
                                    // $headers.= "From: $admin_email" . "\r\n";
                                    $headers.= "From: noreply@ecambiar.com" . "\r\n";
                                    $to = $email;
                                    $to2 = $admin_email;
                                    $subject = "Orders Details";
                                    $txt = '<div class="gj_mail" style="width: 600px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat;  background-size: 100% 102%;">
                                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                            <h2 style="color: #ff5c00;margin-top: 0px;">Orders Details</h2>
                                            <table align="center" style=" text-align: center;">
                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Customer Name</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$customer_name.'</td>
                                                </tr>

                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Contact No</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$contact.'</td>
                                                </tr>

                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Address</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$address.'</td>
                                                </tr>

                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Order Code</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$order_code.'</td>
                                                </tr>

                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Order Date</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$order_date.'</td>
                                                </tr>
                                            </table>

                                            <table style="width: 100%;border: 1px solid black;">
                                                <tr>
                                                    <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Product Title</th>
                                                    <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Quantity</th>
                                                    <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Price</th>
                                                    <th style="width: 100px;text-align: right;text-transform: uppercase;padding-bottom: 5px;color: black;border: 1px solid black;font-size: 13px;font-weight: 700;">Total</th>
                                                </tr>'.$details.'
                                                <tr>
                                                    <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Sub Total</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->total_amount.'</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Shipping Charge</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->shipping_charge.'</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">COD Charge</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->cod_charge.'</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Net Total</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$net_tot.'</td>
                                                </tr>
                                            </table>

                                            <p></p>
                                            <p>Thank You.</p>
                                             <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                            <p>Thanks & Regards,</p>
                                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                        </div>
                                    </div>';
                    
                                    // if(1==1){
                                    if(mail($to,$subject,$txt,$headers)){
                                        mail($to2,$subject,$txt,$headers);
                                        if($user->phone) {
                                            $text = "Thanks for shopping with us.Plz note the Order Code - ".$order_code.", ecambiar.com";
                                            $text = urlencode($text);
                         
                                            $curl = curl_init();
                                         
                                            // Send the POST request with cURL
                                            curl_setopt_array($curl, array(
                                            CURLOPT_RETURNTRANSFER => 1,
                                            CURLOPT_URL => "http://smschub.com/api/sms/format/json",
                                            CURLOPT_POST => 1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_HTTPHEADER => array('X-Authentication-Key:01fe318b290f9f9cb686a6bc28a4affa', 'X-Api-Method:MT'),
                                            CURLOPT_POSTFIELDS => array(
                                                'mobile' => $user->phone,
                                                'route' => 'TL',
                                                'text' => $text,
                                                'sender' => 'GJICAM')));
                                         
                                            // Send the request & save response to $response
                                            $response = curl_exec($curl);
                                         
                                            // Close request to clear up some resources
                                            curl_close($curl);
                                            $response = json_decode($response);
                                            // Print response

                                            if(isset($response->data->status) && $response->data->status == "success") {
                                                Session::flash('message', 'Order confirmation Message and Email Send Successfully!'); 
                                                Session::flash('alert-class', 'alert-success');
                                                Carts::Where('user_id', $data['user_id'])->delete();
                                                session()->forget('cart');
                                            } else {
                                                Session::flash('message', 'Order placed & Email Send Successfully!'); 
                                                Session::flash('alert-class', 'alert-success');
                                                Carts::Where('user_id', $data['user_id'])->delete();
                                                session()->forget('cart');
                                            }
                                            return redirect()->route('home');
                                        } else {
                                            Session::flash('message', 'Order Placed & Mail Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                           
                                            Carts::Where('user_id', $data['user_id'])->delete();
                                            session()->forget('cart');
                                        }
                                        return redirect()->route('home');
                                    } else {
                                        if($user->phone) {
                                            $text = "Thanks for shopping with us.Plz note the Order Code - ".$order_code.", ecambiar.com";
                                            $text = urlencode($text);
                         
                                            $curl = curl_init();
                                         
                                            // Send the POST request with cURL
                                            curl_setopt_array($curl, array(
                                            CURLOPT_RETURNTRANSFER => 1,
                                            CURLOPT_URL => "http://smschub.com/api/sms/format/json",
                                            CURLOPT_POST => 1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_HTTPHEADER => array('X-Authentication-Key:01fe318b290f9f9cb686a6bc28a4affa', 'X-Api-Method:MT'),
                                            CURLOPT_POSTFIELDS => array(
                                                'mobile' => $user->phone,
                                                'route' => 'TL',
                                                'text' => $text,
                                                'sender' => 'GJICAM')));
                                         
                                            // Send the request & save response to $response
                                            $response = curl_exec($curl);
                                         
                                            // Close request to clear up some resources
                                            curl_close($curl);
                                            $response = json_decode($response);
                                            // Print response

                                            if(isset($response->data->status) && $response->data->status == "success") {
                                                Session::flash('message', 'Order confirmation Message and Email Send Successfully!'); 
                                                Session::flash('alert-class', 'alert-success');
                                                Carts::Where('user_id', $data['user_id'])->delete();
                                                session()->forget('cart');
                                            } else {
                                                Session::flash('message', 'Order placed Successfully!'); 
                                                Session::flash('alert-class', 'alert-success');
                                                Carts::Where('user_id', $data['user_id'])->delete();
                                                session()->forget('cart');
                                            }
                                            return redirect()->route('home');
                                        } else {
                                            Session::flash('message', 'Order Placed Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                           
                                            Carts::Where('user_id', $data['user_id'])->delete();
                                            session()->forget('cart');
                                        }
                                        return redirect()->route('home');
                                    }
                                } else {
                                    Orders::where('id', $order->id)->delete();
                                    Session::flash('message', 'Orders Placed Failed!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('checkout');
                                }
                            } else {
                                Session::flash('message', 'Orders Placed Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('checkout');
                            }
                        } else {
                            Session::flash('message', 'Orders Placed Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('checkout');  
                        }
                    } else {
                        Session::flash('message', 'Orders Placed Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('checkout');      
                    }
                } else {
                    Session::flash('message', 'Your Pincode is Not Available For Delivery!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('checkout');
                }
            }
        } else {
            Session::flash('message', 'You Must Login And Continue to Checkout!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');     
        }
    }

    public function PaymentRequest( Request $request) { 
        $data = Input::all();
        if(isset($data) && sizeof($data) != 0) {
            return View::make("front_end.payment_request")->with(array('data'=>$data));
        } else {
            return redirect()->route('checkout');
        }
    }

    public function PaymentResponse( Request $request) { 
        $data = Input::all();
        $user = session()->get('user');

        if($user) {
            if($user->user_type == 4) {
                if(isset($data) && sizeof($data) != 0) {
                    $order_trans = new OrdersTransactions();
                    $t_max = OrdersTransactions::max('trans_code');
                    $t_max_id = "00001";
                    $t_max_st = "Trans";
                    if($t_max) {
                        $t_max_no = substr($t_max, 5);
                        $t_increment = (int)$t_max_no + 1;
                        $data['trans_code'] = $t_max_st.sprintf("%05d", $t_increment);
                    } else {
                        $data['trans_code'] = $t_max_st.$t_max_id;
                    }

                    $order = Orders::Where('order_code', $data['orderId'])->first();

                    $order_trans->trans_code = $data['trans_code'];
                    $order_trans->trans_date = $data['txTime'];

                    if($order) {
                        $order_trans->order_id = $order->id;
                    } else {
                        $order_trans->order_id = $data['orderId'];
                    }

                    $order_trans->net_amount = $data['orderAmount'];

                    if($data['txStatus'] == "SUCCESS") {
                        $order_trans->amountpaid = "Paid";
                    } else {
                        $order_trans->amountpaid = "Unpaid";
                    }

                    $order_trans->paymentmode = 2;
                    $order_trans->pay_method = $data['paymentMode'];
                    $order_trans->gatewaytransactionid = $data['referenceId'];
                    $order_trans->trans_status = $data['txStatus'];
                    $order_trans->remarks = $data['txMsg'];
                    $order_trans->signature = $data['signature'];
                    $order_trans->is_block = 1;

                    if($order_trans->save()) {
                        if($order && ($data['txStatus'] == "SUCCESS")) {
                            $order->payment_status = 1;
                        } else if($order && ($data['txStatus'] == "FAILED")) {
                            $order->payment_status = 2;
                        } else if($order && ($data['txStatus'] == "FLAGGED")) {
                            $order->payment_status = 3;
                        } else if($order && ($data['txStatus'] == "CANCELLED")) {
                            $order->payment_status = 4;
                        } else if($order && ($data['txStatus'] == "PENDING")) {
                            $order->payment_status = 0;
                        } else {
                            $order->payment_status = 0;
                        }
                        $order->order_status = 1;
                        $order->save();

                        $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                        $admin_email = "info@ecambiar.com";
                        if($adm) {
                            $admin_email = $adm->email;
                        }

                        $logos = \DB::table('logo_settings')->first();
                        $logo_path = 'images/logo';
                        $logo = "";
                        if($logos) {
                            $logo = asset($logo_path.'/'.$logos->logo_image);
                        } else {
                            $logo = asset('images/logo.png');
                        }

                        $general = \DB::table('general_settings')->first();
                        $site_name = "ECambiar";
                        if($general){
                            $site_name = $general->site_name;
                        } else {
                            $site_name = "ECambiar";
                        }

                        $customer_name = "";
                        $contact = "";
                        $address = "";
                        
                        if($order) {
                            $order_code = $order->order_code;
                            $order_date = date('d-m-Y', strtotime($order->order_date));
                            $net_tot = $order->net_amount;
                            // $tax_tot = $order->tax_amount;
                            $details = "";
                            $order_detail = OrderDetails::Where('is_block', 1)->Where('order_id', $order->id)->get();
                            $sck = 0;
                            $net_comis = 0.00;
                            $net_mer_amt = 0.00;

                            if($order_detail) {
                                foreach ($order_detail as $key => $value) {
                                    if($order_trans->trans_status == "SUCCESS") {
                                        $stock = Products::Where('id', $value->product_id)->first();

                                        if($stock && ($stock->onhand_qty != 0)) {
                                            $stock_trans = new StockTransactions();
                                            $stock_trans->order_code   = $order_code;
                                            $stock_trans->product_id   = $value->product_id;
                                            $stock_trans->att_name     = $value->att_name;
                                            $stock_trans->att_value    = $value->att_value;
                                            $stock_trans->previous_qty = $stock->onhand_qty;
                                            $stock_trans->current_qty  = $stock->onhand_qty - $value->order_qty;
                                            $stock_trans->date         = date('Y-m-d');
                                            $stock_trans->remarks      = $value->product_title.' is ordered.';

                                            $p_atts = ProductsAttributes::Where('product_id', $value->product_id)->Where('attribute_name', $value->att_name)->Where('attribute_values', $value->att_value)->first();
                                            if($p_atts) {
                                                $stock_trans->att_previous_qty = $p_atts->att_qty;
                                                $stock_trans->att_current_qty  = $p_atts->att_qty - $value->order_qty;
                                                
                                                $p_atts->att_qty = $p_atts->att_qty - $value->order_qty;
                                                $p_atts->save();
                                            }

                                            $stock->onhand_qty = $stock->onhand_qty - $value->order_qty;
                                            if($stock->save() && $stock_trans->save()) {
                                                $sck = 1;
                                            }
                                        }

                                        if($stock && $stock->created_user != 1) {
                                            if($stock->Creatier->user_type == 2 || $stock->Creatier->user_type == 3) {
                                                $com_per = $stock->Creatier->commission;
                                                $t_pce = $value->totalprice;
                                                $admin_com = round($t_pce * ($com_per / 100), 2);
                                                $mer_amt = round($t_pce - $admin_com, 2);

                                                $comis = new AdminCommision();
                                                $comis->order_code   = $order_code;
                                                $comis->order_dets   = $value->id;
                                                $comis->product_id   = $value->product_id;
                                                $comis->att_name     = $value->att_name;
                                                $comis->att_value    = $value->att_value;
                                                $comis->merchant_id  = $stock->Creatier->id;
                                                $comis->amount       = $admin_com;
                                                $comis->merchant_amount = $mer_amt;
                                                $comis->paid_status  = 0;
                                                $comis->remarks      = $value->product_title.' product against Admin Commision is Rs. '.$admin_com.' set.';
                                                $comis->save();

                                                $net_comis = $net_comis + $admin_com;
                                                $net_mer_amt = $net_mer_amt + $mer_amt;
                                            }
                                        }
                                    }

                                    $att_tit = "";
                                    if(isset($value->att_name) && $value->att_name != 0) {
                                        if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) {
                                            $att_tit = '<span>('.$value->AttName->att_name.' : '.$value->AttValue->att_value.')</span>';
                                        }
                                    }

                                    $details.= '<tr>
                                        <td style="font-size: 11px;font-weight: 600;color:black;border:1px solid black;text-align:center;"> '.$value->product_title.' '. $att_tit .'</td>
                                        <td style="font-size: 11px;font-weight: 600;color:black;border:1px solid black;text-align:center;"> '.$value->order_qty.'</td>
                                        <td style="font-size: 11px;font-weight: 600;color:black;border:1px solid black;text-align:center;">Rs.  '.$value->unitprice.'</td>
                                        <td style="font-size: 11px;font-weight: 600;color: black;border: 1px solid black;text-align: right;">Rs.  '.$value->totalprice.'</td>
                                    </tr>';
                                }
                            }

                            if($order) {
                                $order->net_commision = $net_comis;
                                $order->net_merchant_amout = $net_mer_amt;
                                $order->save();
                            } 

                            if($sck == 1) {
                                if($order->shipping_address_flag == 1 && $user) {
                                    $ship = ShippingAddress::Where('user_id', $user->id)->first();
                                    if($ship) {
                                        $customer_name = $ship->first_name.' '.$ship->last_name;
                                        $address = $ship->address.','.$ship->City->city_name.','.$ship->pincode.','.$ship->State->state.','.$ship->Country->country_name;
                                        $contact = $ship->contact_no;
                                    } else if ($user) {
                                        $customer_name = $user->first_name.' '.$user->last_name;
                                        $address = $user->address1.','.$user->address2.','.$user->City->city_name.','.$user->pincode.','.$user->State->state.','.$user->Country->country_name;
                                        $contact = $user->phone.','.$user->phone2;
                                    }
                                } else if ($user) {
                                    $customer_name = $user->first_name.' '.$user->last_name;
                                    $address = $user->address1.','.$user->address2.','.$user->City->city_name.','.$user->pincode.','.$user->State->state.','.$user->Country->country_name;
                                    $contact = $user->phone.','.$user->phone2;
                                }

                                $name = $user->first_name.' '.$user->last_name;
                                $email = $user->email;

                                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                $headers.= "MIME-Version: 1.0\r\n";
                                // $headers.= "From: $admin_email" . "\r\n";
                                $headers.= "From: noreply@ecambiar.com" . "\r\n";
                                $to = $email;
                                $to2 = $admin_email;
                                $subject = "Orders Details";

                                $txt = '<div class="gj_mail" style="width: 600px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat;  background-size: 100% 102%;">
                                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                            <h2 style="color: #ff5c00;margin-top: 0px;">Orders Details</h2>
                                            <table align="center" style=" text-align: center;">
                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Customer Name</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$customer_name.'</td>
                                                </tr>

                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Contact No</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$contact.'</td>
                                                </tr>

                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Address</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$address.'</td>
                                                </tr>

                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Order Code</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$order_code.'</td>
                                                </tr>

                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Order Date</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$order_date.'</td>
                                                </tr>
                                            </table>

                                            <table style="width: 100%;border: 1px solid black;">
                                                <tr>
                                                    <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Product Title</th>
                                                    <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Quantity</th>
                                                    <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Price</th>
                                                    <th style="width: 100px;text-align: right;text-transform: uppercase;padding-bottom: 5px;color: black;border: 1px solid black;font-size: 13px;font-weight: 700;">Total</th>
                                                </tr>'.$details.'
                                                <tr>
                                                    <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Sub Total</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->total_amount.'</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Shipping Charge</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->shipping_charge.'</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">COD Charge</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->cod_charge.'</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Net Total</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$net_tot.'</td>
                                                </tr>
                                            </table>

                                            <p></p>
                                            <p>Thank You.</p>
                                             <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                            <p>Thanks & Regards,</p>
                                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                        </div>
                                    </div>';                 
                    
                                // if(1==1){
                                if(mail($to,$subject,$txt,$headers)){
                                    mail($to2,$subject,$txt,$headers);
                                    if($user->phone) {
                                        $text = "Thanks for shopping with us.Plz note the Order Code - ".$order_code.", ecambiar.com";
                                        $text = urlencode($text);
                     
                                        $curl = curl_init();
                                     
                                        // Send the POST request with cURL
                                        curl_setopt_array($curl, array(
                                        CURLOPT_RETURNTRANSFER => 1,
                                        CURLOPT_URL => "http://smschub.com/api/sms/format/json",
                                        CURLOPT_POST => 1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_HTTPHEADER => array('X-Authentication-Key:01fe318b290f9f9cb686a6bc28a4affa', 'X-Api-Method:MT'),
                                        CURLOPT_POSTFIELDS => array(
                                            'mobile' => $user->phone,
                                            'route' => 'TL',
                                            'text' => $text,
                                            'sender' => 'GJICAM')));
                                     
                                        // Send the request & save response to $response
                                        $response = curl_exec($curl);
                                     
                                        // Close request to clear up some resources
                                        curl_close($curl);
                                        $response = json_decode($response);
                                        // Print response

                                        if(isset($response->data->status) && $response->data->status == "success") {
                                            Session::flash('message', 'Order confirmation Message and Email Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            Carts::Where('user_id', $user->id)->delete();
                                            session()->forget('cart');
                                        } else {
                                            Session::flash('message', 'Order placed & Email Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            Carts::Where('user_id', $user->id)->delete();
                                            session()->forget('cart');
                                        }
                                        return redirect()->route('home');
                                    } else {
                                        Session::flash('message', 'Order Placed & Mail Send Successfully!'); 
                                        Session::flash('alert-class', 'alert-success');
                                       
                                        Carts::Where('user_id', $data['user_id'])->delete();
                                        session()->forget('cart');
                                    }
                                    return redirect()->route('home');
                                } else {
                                    if($user->phone) {
                                        $text = "Thanks for shopping with us.Plz note the Order Code - ".$order_code.", ecambiar.com";
                                        $text = urlencode($text);
                     
                                        $curl = curl_init();
                                     
                                        // Send the POST request with cURL
                                        curl_setopt_array($curl, array(
                                        CURLOPT_RETURNTRANSFER => 1,
                                        CURLOPT_URL => "http://smschub.com/api/sms/format/json",
                                        CURLOPT_POST => 1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_HTTPHEADER => array('X-Authentication-Key:01fe318b290f9f9cb686a6bc28a4affa', 'X-Api-Method:MT'),
                                        CURLOPT_POSTFIELDS => array(
                                            'mobile' => $user->phone,
                                            'route' => 'TL',
                                            'text' => $text,
                                            'sender' => 'GJICAM')));
                                     
                                        // Send the request & save response to $response
                                        $response = curl_exec($curl);
                                     
                                        // Close request to clear up some resources
                                        curl_close($curl);
                                        $response = json_decode($response);
                                        // Print response

                                        if(isset($response->data->status) && $response->data->status == "success") {
                                            Session::flash('message', 'Order confirmation Message and Email Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            Carts::Where('user_id', $user->id)->delete();
                                            session()->forget('cart');
                                        } else {
                                            Session::flash('message', 'Order placed Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            Carts::Where('user_id', $user->id)->delete();
                                            session()->forget('cart');
                                        }
                                        return redirect()->route('home');
                                    } else {
                                        Session::flash('message', 'Order Placed Successfully!'); 
                                        Session::flash('alert-class', 'alert-success');
                                       
                                        Carts::Where('user_id', $user->id)->delete();
                                        session()->forget('cart');
                                    }
                                    return redirect()->route('home');
                                }
                            } else {
                                Session::flash('message', 'Stock Not Maintained!'); 
                                Session::flash('alert-class', 'alert-danger');

                                Carts::Where('user_id', $user->id)->delete();
                                session()->forget('cart');

                                return View::make("front_end.payment_response")->with(array('data'=>$data));
                            }
                        } else {
                            Session::flash('message', 'Order Placed Successfully!'); 
                            Session::flash('alert-class', 'alert-danger');
                            Carts::Where('user_id', $user->id)->delete();
                            session()->forget('cart');
                            return redirect()->route('home');
                        }
                    } else {
                        Session::flash('message', 'Order Placed Successfully!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('checkout');
                    }
                } else {
                    Session::flash('message', 'Order Placed Successfully!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('checkout');
                }
            } else {
                Session::flash('message', 'Order Placed Successfully!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('checkout');
            }
        } else {
            Session::flash('message', 'Order Placed Successfully!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('checkout');
        }
    }

    public function PincodeCheck( Request $request) { 
        $pincode = 0;
        $error = 1;
        if($request->ajax() && isset($request->pincode)) {
            $pincode = $request->pincode;
            if($pincode && strlen($pincode) == 6) {
                $log_shyp = new ShypliteAuth();
                $login_shyp = $log_shyp->authenticatShyplite();
                $login_shyp=json_decode($login_shyp, true);
                // print_r($login_shyp);die();
                
                if(!isset($login_shyp['error'])) {
                    $timestamp = time();
                    $appID = $log_shyp->appID; 
                    $key = $log_shyp->key; 
                    $secret = $log_shyp->secret; 
                    if(isset($login_shyp['userToken'])) {
                        $secret = $login_shyp['userToken'];
                    }
                    $SellerID = $log_shyp->SellerID;

                    $sign = "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
                    $authtoken = rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
                    $ch = curl_init();
                    
                    $header = array(
                        "x-appid: $appID",
                        "x-timestamp: $timestamp",
                        "x-sellerid:$SellerID",
                        "Authorization: $authtoken"
                    );

                    curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/getserviceability/691021/'.$pincode);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec($ch);
                    // var_dump($server_output);
                    $resp = json_decode($server_output, true);
                    // print_r($resp);
                    // die();
                    $airCod = false;
                    $surfaceCod = false;
                    $surface10kgPrepaid = false;
                    $surface10kgCod = false;
                    $surface5kgPrepaid = false;
                    $surface5kgCod = false;
                    $lite2kgPrepaid = false;
                    $lite2kgCod = false;
                    $lite1kgPrepaid = false;
                    $lite1kgCod = false;
                    $liteHalfKgPrepaid = false;
                    $liteHalfKgCod = false;
                    if(isset($resp['serviceability']['surface10kgPrepaid']) && isset($resp['serviceability']['surface10kgCod']) && isset($resp['serviceability']['surface5kgPrepaid']) && isset($resp['serviceability']['surface5kgCod']) && isset($resp['serviceability']['lite2kgPrepaid']) && isset($resp['serviceability']['lite2kgCod']) && isset($resp['serviceability']['lite1kgPrepaid']) && isset($resp['serviceability']['lite1kgCod']) && isset($resp['serviceability']['lite0.5kgPrepaid']) && isset($resp['serviceability']['lite0.5kgCod'])) {
                        $surface10kgPrepaid = $resp['serviceability']['surface10kgPrepaid'];
                        $surface10kgCod = $resp['serviceability']['surface10kgCod'];
                        $surface5kgPrepaid = $resp['serviceability']['surface5kgPrepaid'];
                        $surface5kgCod = $resp['serviceability']['surface5kgCod'];
                        $lite2kgPrepaid = $resp['serviceability']['lite2kgPrepaid'];
                        $lite2kgCod = $resp['serviceability']['lite2kgCod'];
                        $lite1kgPrepaid = $resp['serviceability']['lite1kgPrepaid'];
                        $lite1kgCod = $resp['serviceability']['lite1kgCod'];
                        $liteHalfKgPrepaid = $resp['serviceability']['lite0.5kgPrepaid'];
                        $liteHalfKgCod = $resp['serviceability']['lite0.5kgCod'];
                    }
                    
                    // if(1==1) {
                    if($surface10kgPrepaid == TRUE && $surface10kgCod == TRUE && $surface5kgPrepaid == TRUE && $surface5kgCod == TRUE && $lite2kgPrepaid == TRUE && $lite2kgCod == TRUE && $lite1kgPrepaid == TRUE && $lite1kgCod == TRUE && $liteHalfKgPrepaid == TRUE && $liteHalfKgCod == TRUE) {
                        $ava_deliv = 1;
                        $error = "1";
                    } elseif(isset($resp['error'])) {
                        $error = "Delivery Option Not Checked This Time!";
                    } else {
                        $error = "Delivery Not Available for this Pincode!";
                    }
                    curl_close($ch);
                } else {
                    $error = "Delivery Availability Not Checked This Time!";
                }
            } else {
                $error = "Enter Valid Pincode and Pincode Must 6 Numbers only!";
            }
        } else {
            $error = "Enter Valid Pincode and Pincode Must 6 Numbers only!";
        }

        echo $error;
    }

    public function BrandAutoComplete( Request $request) { 
        $keywrd = "";
        $li = "";
        $error = array('error' => 1);

        if($request->ajax() && isset($request->keywrd)) {
            $keywrd = $request->keywrd;
            if($keywrd) {
                $expl = explode(' ', $keywrd);

                $brands = Brands::Where('is_block',1)->where(function($q) use ($expl){
                    foreach($expl as $ekey => $evalue){
                        $q->orWhere('brand_name', 'LIKE', '%' . $evalue . '%');
                    }
                })->get();

                if($brands && sizeof($brands) != 0) {
                    foreach ($brands as $key => $value) {
                        $li.= '<li class="ss_megamenu_lv2 "><a href="'.route('brands_products', ['id' => $value->id]).'" title="'.$value->brand_name.'">'.$value->brand_name.'</a></li>';                        
                    }
                    
                    $error = array(
                        'error' => 0,
                        'data' => $li 
                    );
                }
            }
        }

        echo json_encode($error);
    }            
}