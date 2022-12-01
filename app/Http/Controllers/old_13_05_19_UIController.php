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
use App\Contacts;
use App\NewsLetter;
use App\SizeSettings;
use App\ColorSettings;
use App\Brands;
use App\Carts;
use App\TaxCutoff;
use App\WishList;
use App\Orders;
use App\OrderDetails;
use App\OrdersTransactions;
use App\StockTransactions;
use App\Shipment;
use App\AdminCommision;
use App\Review;
use App\FeedBack;
use App\ShypliteAuth;


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

class oldUIController extends Controller
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
        $first_cat = CategoryManagementSettings::Where('is_block',1)->Where('is_home', 1)->first();
        $second_cat = CategoryManagementSettings::Where('is_block',1)->Where('is_home', 2)->first();
        $third_cat = CategoryManagementSettings::Where('is_block',1)->Where('is_home', 3)->first();

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

        return View::make("front_end.index")->with(array('banner_images'=>$banner_images, 'main_cat'=>$main_cat, 'first_cat'=>$first_cat, 'second_cat'=>$second_cat, 'third_cat'=>$third_cat, 'first_products'=>$first_products, 'second_products'=>$second_products, 'third_products'=>$third_products, 'top_products'=>$top_products, 'featured_products'=>$featured_products, 'best_seller'=>$best_seller));
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
            })->OrderBy('id', 'desc')->paginate(12);
        }


        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(12)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        

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
            
            return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes));    
        } else {
            Session::flash('message', 'Sorry No Products For This Keyword!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('home');
        }               
    }                                        
    public function AllProducts () {
        $all_products = Products::Where('is_block',1)->OrderBy('id', 'desc')->paginate(12);
        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(12)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        

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
        return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes));
    }

    public function AllCatProducts ($main_cat) {
        $all_products = Products::Where('main_cat_name', $main_cat)->Where('is_block',1)->OrderBy('id', 'desc')->paginate(12);
        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(12)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        

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
        return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes));
    }

    public function AllFilterProducts (Request $request) {
        $data = Input::all();
        // print_r($data);die();
        $p_amount1 = $data['p_amount1'];
        $p_amount2 = $data['p_amount2'];

        $all_products = Products::Where('is_block',1)->OrderBy('id', 'desc');
        if(($p_amount1) && ($p_amount2)) { 
            $all_products->WhereBetween('discounted_price', [$p_amount1, $p_amount2]);
        } elseif (($p_amount1)) {
            $all_products->Where('discounted_price', '>=', $p_amount1); 
        } elseif (($p_amount2)) {
            $all_products->Where('discounted_price', '<=', $p_amount2); 
        } 
        $all_products = $all_products->paginate(12);
        // print_r($all_products);die();

        $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
        $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(12)->get();
        $attributes = AttributesSettings::Where('is_block', 1)->get();
        

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
            return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes));
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
                    $all_products = Products::WhereIn('id', $p_id)->Where('is_block',1)->OrderBy('id', 'desc')->paginate(12);
                }

                $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
                $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(12)->get();
                $attributes = AttributesSettings::Where('is_block', 1)->get();

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

                return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes));
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
                $all_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->paginate(12);
            } elseif ($data['SortBy'] == "best-selling") {
                /*not develope*/
                $all_products = Products::Where('is_block',1)->OrderBy('id', 'desc')->paginate(12);
            } elseif ($data['SortBy'] == "title-ascending") {
                $all_products = Products::Where('is_block',1)->OrderBy('product_title', 'asc')->paginate(12);
            } elseif ($data['SortBy'] == "title-descending") {
                $all_products = Products::Where('is_block',1)->OrderBy('product_title', 'desc')->paginate(12);
            } elseif ($data['SortBy'] == "price-ascending") {
                $all_products = Products::Where('is_block',1)->OrderBy('discounted_price', 'asc')->paginate(12);
            } elseif ($data['SortBy'] == "price-descending") {
                $all_products = Products::Where('is_block',1)->OrderBy('discounted_price', 'desc')->paginate(12);
            } elseif ($data['SortBy'] == "created-ascending") {
                $all_products = Products::Where('is_block',1)->OrderBy('created_at', 'asc')->paginate(12);
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
                $all_products = Products::WhereIn('id', $p_ids)->Where('is_block',1)->OrderBy('id', 'desc')->paginate(12);
            } else {
                $all_products = Products::Where('is_block',1)->OrderBy('id', 'desc')->paginate(12);
            }
                                                                   
            $category = CategoryManagementSettings::Where('is_block',1)->OrderBy('id', 'desc')->get();
            $featured_products = Products::Where('featuredproduct_flag', 1)->Where('is_block',1)->OrderBy('id', 'desc')->take(12)->get();
            $attributes = AttributesSettings::Where('is_block', 1)->get();
            

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
            return View::make("front_end.all_products")->with(array('all_products'=>$all_products, 'category'=>$category, 'featured_products'=>$featured_products, 'attributes'=>$attributes));
        } else {
            Session::flash('message', 'No More Product for Your Search!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('all_products');
        }
    }

    public function OfferProducts () {
        $offer_products = Products::Where('is_block',1)->Where('offers_flag', 1)->OrderBy('id', 'desc')->paginate(12);

        return View::make("front_end.offer_products")->with(array('offer_products'=>$offer_products));
    }

    public function SubCategory ($main_cat) {
        $sub_cat = SubCategoryManagementSettings::Where('main_cat_name', $main_cat)->Where('is_block',1)->paginate(12);

        return View::make("front_end.sub_category")->with(array('sub_cat'=>$sub_cat));
    }

    public function SubSubCategory ($sub_cat) {
        $sub_sub_cat = SubSubCategoryManagementSettings::Where('sub_cat_name', $sub_cat)->Where('is_block',1)->paginate(12);

        return View::make("front_end.sub_sub_category")->with(array('sub_sub_cat'=>$sub_sub_cat));
    }

    public function CategoryProducts ($main_cat) {
        $products = Products::Where('main_cat_name', $main_cat)->Where('is_block',1)->paginate(12);

        return View::make("front_end.category_products")->with(array('products'=>$products));
    }

    public function SubSubCategoryProducts ($sub_sub_cat) {
        $products = Products::Where('sub_sub_cat_name', $sub_sub_cat)->Where('is_block',1)->paginate(12);

        return View::make("front_end.sub_sub_category_products")->with(array('products'=>$products));
    }

    public function BrandsProducts ($id) {
        $products = Products::Where('brand', $id)->Where('is_block',1)->paginate(12);

        return View::make("front_end.brands_products")->with(array('products'=>$products));
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
        if($products) {
            $products['images'] = ProductsImages::where('product_id', $products->id)->Where('is_block', 1)->get();
            $products['att'] = ProductsAttributes::where('product_id', $products->id)->Where('is_block', 1)->get();
            $p_atts = ProductsAttributes::where('product_id', $products->id)->Where('is_block', 1)->groupBy('attribute_name')->get();
            if(sizeof($p_atts) != 0) {
                foreach ($p_atts as $key => $value) {
                    array_push($att_id, $value->attribute_name);                   
                }
            }

            if(sizeof($att_id) != 0) {
                $products['att_fields'] = AttributesFields::WhereIn('id', $att_id)->get();
            }
            // print_r($att_id);die();
            // print_r($p_atts);die();
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
        $products = Products::WhereIn('id', $ids)->Where('is_block',1)->paginate(12);

        return View::make("front_end.tag_products")->with(array('products'=>$products));
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
            'slogan'                  => 'required',
            'stores_image'            => 'required',
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
                    $file_name = time().uniqid() .'.'. $img_files111->getClientOriginalExtension();
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
                $merchant->is_block                  = 1;
                $merchant->login_type                = 1;

                $pass = $data['password'];
                if($merchant->save()) {
                    if($data['d_name'] && count($data['d_name']) != 0) {
                        foreach ($data['d_name'] as $key => $value) {
                            $d_images = new MerchantsDocuments();

                            if(isset($data['d_image'][$key])) {
                                $file_name = 'product'.$key.time() .'.'. $data['d_image'][$key]->getClientOriginalExtension();
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
                            $file_name = uniqid() . time() .'.'. $img_files->getClientOriginalExtension();
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

                            $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                            $headers.= "MIME-Version: 1.0\r\n";
                            // $headers.= "From: $admin_email" . "\r\n";
                            $headers.= "From: noreply@ecambiar.com" . "\r\n";
                            $to = $merchant->email;
                            $subject = "Merchants Registration";
                            $txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 50%;margin: auto;position: relative;background-color: white;">
                                    <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                                    <div style="padding: 20px;background-color: #ff5c00;color: white;">
                                        <h2 style="color: white;margin-top: 0px;">Register Process Success</h2>
                                        <p>"Thank You For Your Registering with us".</p>
                                        <p>Our Admin Team Will Evaluate and Approve Soon.</p>
                                        <p>Any Queries Please email at <a href="mailto:info@ecambiar.com" target="_blank" style="color: white;text-decoration: none;">info@ecambiar.com</a>.</p>
                                        <p></p>
                                        <p>Thanks & Regards,</p>
                                        <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                    </div>
                                </div>';
                            
                            if (mail($to,$subject,$txt,$headers)) {
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
                                        $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
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
                                    $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
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
                    $file_name = time().uniqid() .'.'. $img_files->getClientOriginalExtension();
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
                                        $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
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
                            Session::flash('message', 'Mail Send Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            echo $error = 1;die();
                        } else {
                            Session::flash('message', 'Register Successfully, but Mail Send Failed!'); 
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

                $cancel_orders= Orders::where('user_id',$value->id)->Where('order_status', 5)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $orders = Orders::where('user_id',$value->id)->paginate(12);
                if(count($orders) != 0) {
                    foreach ($orders as $key => $value) {
                        $value['details'] = OrderDetails::Where('order_id', $value->id)->get();
                        $value['trans'] = OrdersTransactions::Where('order_id', $value->id)->get();
                        $value['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $secure = loginSecurity::all();
                return View::make("front_end.my_account")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 'secure'=>$secure));
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

                $cancel_orders= Orders::where('user_id',$value->id)->Where('order_status', 5)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                $orders = Orders::where('id',$id)->first();
                if($orders) {
                    $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                    $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
                    $orders['products'] = Products::Where('is_block', 1)->get();
                }

                $secure = loginSecurity::all();
                return View::make("front_end.my_view_orders")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 'secure'=>$secure));
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

                $cancel_orders= Orders::where('user_id',$value->id)->Where('order_status', 5)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
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
                return View::make("front_end.my_track_orders")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 'secure'=>$secure));
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

                $cancel_orders= Orders::where('user_id',$value->id)->Where('order_status', 5)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
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
                            return View::make("front_end.live_track_order")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 'secure'=>$secure, 'response'=>$resp));
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

                $cancel_orders= Orders::where('user_id',$value->id)->Where('order_status', 5)->paginate(12);
                if(count($cancel_orders) != 0) {
                    foreach ($cancel_orders as $keys => $values) {
                        $values['details'] = OrderDetails::Where('order_id', $values->id)->get();
                        $values['trans'] = OrdersTransactions::Where('order_id', $values->id)->get();
                        $values['products'] = Products::Where('is_block', 1)->get();
                    }
                }
                
                $orders = Orders::where('id',$id)->first();
                if($orders) {
                    $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                    $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
                    $orders['products'] = Products::Where('is_block', 1)->get();
                }
                $secure = loginSecurity::all();
                return View::make("front_end.my_review_orders")->with(array('orders'=>$orders, 'past_orders'=>$past_orders, 'cancel_orders'=>$cancel_orders, 'secure'=>$secure));
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
                    $c_date = date('Y-m-d', strtotime($cancel->order_date. ' + 2 days'));
                    if($n_date <= $c_date) {
                        $cancel->order_status = 5;
                        $cancel->cancel_date = $n_date;
                        if($cancel->save()) {
                            $text = "Your Order has been Cancelled. Plz note the Order Code - ".$cancel->order_code.",Ecambiar.";
                            $text = urlencode($text);
         
                            $curl = curl_init();
                            $user = User::Where('id', $cancel->user_id)->first();
                            if($user) {                         
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
                                    Session::flash('message', 'Your Order has been Cancelled and  Confirmation Message Send Successfully!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    echo $error = 1;die();
                                } else {
                                    Session::flash('message', 'Your Order has been Cancelled!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    echo $error = 1;die();
                                }
                            }
                            $error = 1;
                        } else {
                            $error = 0;
                        }
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
            return View::make('front_end.contact')->withErrors($validator);
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
                    $txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 50%;margin: auto;position: relative;background-color: white;">
                            <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                            <div style="padding: 20px;background-color: #ff5c00;color: white;">
                                <p>Thanks For Your Enqueries. We Will Contact you Soon.</p>
                                <p></p>
                                <p></p>
                                <p>Thanks & Regards,</p>
                                <p><a href="'.route('home').'">'.$site_name.'</a></p>
                            </div>
                        </div>';
                    
                    if(mail($to,$subject,$txt,$headers)){
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
        return View::make("front_end.disclaimer");
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
                    $txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 50%;margin: auto;position: relative;background-color: white;">
                            <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                            <div style="padding: 20px;background-color: #ff5c00;color: white;">
                                <p>Thanks For Your Subcribe. We Will Contact you Soon.</p>
                                <p><a href="'.route('unsubcribe', ['id' => $news_letters->id]).'">Unsubscribe</a></p>
                                <p></p>
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
                $txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 50%;margin: auto;position: relative;background-color: white;">
                        <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                        <div style="padding: 20px;background-color: #ff5c00;color: white;">
                            <p>Unsubscribe For Email Notification Successfully.</p>
                            <p></p>
                            <p></p>
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

            $error = 1;
            if($id) {
                $product = Products::where('id',$id)->first();
                if($product){
                    $onhand_qty = $product->onhand_qty;
                    if($onhand_qty != 0) {
                        if($onhand_qty >= $qty) {
                            $session = $request->session();
                            $cartAllData = array();
                            $cartData = ($session->get('cart')) ? $session->get('cart') : array();
                            if (array_key_exists($id, $cartData)) {
                                Session::flash('message', 'Already Added to Cart!'); 
                                Session::flash('alert-class', 'alert-danger');
                                echo $error = 2;
                                die();
                            } else {
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

                                $cartData[$id] = array(
                                    'product_id' => $product->id,
                                    'qty'   => (isset($qty) && ($qty != 0)) ? $qty : 1,
                                    'original_price' => $product->original_price,
                                    'price' => (isset($price) && ($price != 0)) ? $price : $product->discounted_price,
                                    'total_price' => round(((isset($qty) && ($qty != 0)) ? $qty : 1) * ($product->discounted_price + (($product->discounted_price * $product->tax)/100)),2),
                                    'att_name' => $att_name,
                                    'att_value' => $att_value,
                                    'tax' => $product->tax,
                                    'tax_type' => $product->tax_type,
                                    'service_charge' => $sc,
                                    'shiping_charge' => $shc,
                                    'image' => $product->featured_product_img,
                                    'name'  => $product->product_title,
                                    'notes' => '',
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
                                            $carts->price       = (isset($price) && ($price != 0)) ? $price : $product->discounted_price;
                                            $carts->total_price = round(((isset($qty) && ($qty != 0)) ? $qty : 1) * ($product->discounted_price + (($product->discounted_price * $product->tax)/100)),2);
                                            $carts->image       = $product->featured_product_img;
                                            $carts->att_name  = $att_name;
                                            $carts->att_value  = $att_value;
                                            $carts->tax  = $product->tax;
                                            $carts->tax_type  = $product->tax_type;
                                            $carts->service_charge  = $sc;
                                            $carts->shiping_charge  = $shc;
                                            $carts->qty         = 1;
                                            $carts->is_block    = 1;

                                            if($carts->save()) {
                                                $error = "Added to Cart Successfully!";
                                            }
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
                           Session::flash('message', 'Out of Stock. Only ' . $onhand_qty. '  Products Avaliable!'); 
                            Session::flash('alert-class', 'alert-danger');
                            $error = 1;
                        }
                    } else {
                        Session::flash('message', 'Out of Stock. Products Not Avaliable!'); 
                        Session::flash('alert-class', 'alert-danger');
                        $error = 1;
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

    public function DeleteCart( Request $request) {
        $id = 0;
        if($request->ajax() && isset($request->id)){
            $id = $request->id;
            $error = 1;
            if($id) {
                $product = Products::where('id',$id)->first();
                if($product){
                    $del_carts = "";
                    $users = session()->get('user');
                    if($users) {
                        if($users->user_type == 4) {
                            $del_carts = Carts::Where('user_id', $users->id)->Where('product_id', $id)->first();
                        }
                    }

                    $session = $request->session();
                    $cartAllData = array();
                    $cartData = ($session->get('cart')) ? $session->get('cart') : array();
                    if (array_key_exists($id, $cartData)) {
                        foreach ($cartData as $index => $data) {
                            if ($data['product_id'] == $id) {
                                unset($cartData[$index]);
                            }
                        }

                        $users = session()->get('user');
                        if($users) {
                            if($users->user_type == 4) {
                                $carts = Carts::Where('user_id', $users->id)->Where('product_id', $id)->delete();
                                if($carts) {
                                    $error = 'Cart Deleted Successfully!';              
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
                                $carts = Carts::Where('user_id', $users->id)->Where('product_id', $id)->delete();
                                if($carts) {
                                    $error = 'Cart Deleted Successfully!';              
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
                    Session::flash('message', '1Cart Deleted Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    $error = 1;
                }
            } else {
                Session::flash('message', '2Cart Deleted Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                $error = 1;
            }
        } else {
            Session::flash('message', '3Cart Deleted Failed!'); 
            Session::flash('alert-class', 'alert-danger');
            $error = 1;
        }
        echo $error;
    }

    public function Cart () {
        $users = session()->get('user');
        $ses_carts = session()->get('cart');
        $ses_carts = json_decode(json_encode($ses_carts), FALSE);

        $carts = "";
        if($users) {
            if($users->user_type == 4) {
                $carts = Carts::Where('user_id', $users->id)->get();
                return View::make("front_end.cart")->with(array('carts'=>$carts));
            } else if (isset($ses_carts) && sizeof($ses_carts) != 0) {
                foreach ($ses_carts as $key => $value) {
                    if(isset($value->att_name) && $value->att_name != 0 && isset($value->att_value) && $value->att_value != 0) {
                        $att_name = AttributesFields::Where('id', $value->att_name);
                        $att_value = AttributesSettings::Where('id', $value->att_value);
                        if($att_name) {
                            $ses_carts[$key]->att_name = $att_name;
                        } else {
                            $ses_carts[$key]->att_name = NULL;
                        }

                        if($att_value) {
                            $ses_carts[$key]->att_value = $att_value;
                        } else {
                            $ses_carts[$key]->att_value = NULL;
                        }
                    }
                }
                return View::make("front_end.cart")->with(array('ses_carts'=>$ses_carts));
            } else {
                return redirect()->route('signin');
            }
        } else if (isset($ses_carts)) {
            foreach ($ses_carts as $key => $value) {
                                                        // print_r($value->att_name);die();
                if(isset($value->att_name) && $value->att_name != 0 && isset($value->att_value) && $value->att_value != 0) {
                    $att_name = AttributesFields::Where('id', $value->att_name)->first();
                    $att_value = AttributesSettings::Where('id', $value->att_value)->first();
                    if($att_name) {
                        $value->att_name = $att_name->att_name;
                    } else {
                        $value->att_name = NULL;
                    }

                    if($att_value) {
                        $value->att_value = $att_value->att_value;
                    } else {
                        $value->att_value = NULL;
                    }
                }
            }
            return View::make("front_end.cart")->with(array('ses_carts'=>$ses_carts));
        } else {
            return redirect()->route('signin');
        }
    }

    public function CheckOnHandQty( Request $request) {
        $id = 0;
        $qty = 0;
        $price = 0;
        $error = 1;
        if($request->ajax() && isset($request->id) && isset($request->qty) && isset($request->price)){
            $id = $request->id;
            $qty = $request->qty;
            $price = $request->price;
            if($id) {
                $product = Products::where('id',$id)->first();
                if($product){
                    $onhand_qty = $product->onhand_qty;
                    if($onhand_qty != 0) {
                        if($onhand_qty >= $qty) {
                            if($qty != 0 && $price != 0) {
                                $error = $qty * $price;
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
                if(count($data['product_id']) != 0) {
                    $request->session()->forget('cart');

                    Carts::Where('user_id', $users->id)->delete();
                    foreach ($data['product_id'] as $key => $value) {
                        $cartData[$value] = array(
                            'product_id' => $value,
                            'qty'        => (isset($data['qty'][$key])) ? $data['qty'][$key] : 1,
                            'original_price'      => (isset($data['original_price'][$key])) ? $data['original_price'][$key] : 0,
                            'price'      => (isset($data['price'][$key])) ? $data['price'][$key] : 0,
                            'total_price'      => (isset($data['total_price'][$key])) ? $data['total_price'][$key] : 0,
                            'image'      => (isset($data['image'][$key])) ? $data['image'][$key] : NULL,
                            'tax' => (isset($data['tax'][$key])) ? $data['tax'][$key] : NULL,
                            'tax_type' => (isset($data['tax_type'][$key])) ? $data['tax_type'][$key] : NULL,
                            'service_charge' => (isset($data['service_charge'][$key])) ? $data['service_charge'][$key] : 0,
                            'shiping_charge' => (isset($data['shiping_charge'][$key])) ? $data['shiping_charge'][$key] : 0,
                            'name'       => (isset($data['name'][$key])) ? $data['name'][$key] : NULL,
                            'notes'      =>  (isset($data['notes'])) ? $data['notes'] : NULL,
                        );

                        
                        $carts = new Carts();
                        if($carts) {
                            $carts->product_id  = $value;
                            $carts->user_id     = $users->id;
                            $carts->name        = (isset($data['name'][$key])) ? $data['name'][$key] : NULL;
                            $carts->original_price       = (isset($data['original_price'][$key])) ? $data['original_price'][$key] : 0;
                            $carts->price       = (isset($data['price'][$key])) ? $data['price'][$key] : 0;
                            $carts->total_price       = (isset($data['total_price'][$key])) ? $data['total_price'][$key] : 0;
                            $carts->tax  = (isset($data['tax'][$key])) ? $data['tax'][$key] : NULL;
                            $carts->tax_type  = (isset($data['tax_type'][$key])) ? $data['tax_type'][$key] : NULL;
                            $carts->service_charge  = (isset($data['service_charge'][$key])) ? $data['service_charge'][$key] : 0;
                            $carts->shiping_charge  = (isset($data['shiping_charge'][$key])) ? $data['shiping_charge'][$key] : 0;
                            $carts->image       = (isset($data['image'][$key])) ? $data['image'][$key] : NULL;
                            $carts->qty         = (isset($data['qty'][$key])) ? $data['qty'][$key] : 1;
                            $carts->notes       = (isset($data['notes'])) ? $data['notes'] : NULL;
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
                if(count($data['product_id']) != 0) {
                    $request->session()->forget('cart');
                    foreach ($data['product_id'] as $key => $value) {
                        $cartData[$value] = array(
                            'product_id' => $value,
                            'qty'        => (isset($data['qty'][$key])) ? $data['qty'][$key] : 1,
                            'original_price'      => (isset($data['original_price'][$key])) ? $data['original_price'][$key] : 0,
                            'price'      => (isset($data['price'][$key])) ? $data['price'][$key] : 0,
                            'total_price'      => (isset($data['total_price'][$key])) ? $data['total_price'][$key] : 0,
                            'tax' => (isset($data['tax'][$key])) ? $data['tax'][$key] : NULL,
                            'tax_type' => (isset($data['tax_type'][$key])) ? $data['tax_type'][$key] : NULL,
                            'service_charge' => (isset($data['service_charge'][$key])) ? $data['service_charge'][$key] : 0,
                            'shiping_charge' => (isset($data['shiping_charge'][$key])) ? $data['shiping_charge'][$key] : 0,
                            'image'      => (isset($data['image'][$key])) ? $data['image'][$key] : NULL,
                            'name'       => (isset($data['name'][$key])) ? $data['name'][$key] : NULL,
                            'notes'      =>  (isset($data['notes'])) ? $data['notes'] : NULL,
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
            if(count($data['product_id']) != 0) {
                $request->session()->forget('cart');
                foreach ($data['product_id'] as $key => $value) {
                    $cartData[$value] = array(
                        'product_id' => $value,
                        'qty'        => (isset($data['qty'][$key])) ? $data['qty'][$key] : 1,
                        'original_price'      => (isset($data['original_price'][$key])) ? $data['original_price'][$key] : 0,
                        'price'      => (isset($data['price'][$key])) ? $data['price'][$key] : 0,
                        'total_price'      => (isset($data['total_price'][$key])) ? $data['total_price'][$key] : 0,
                        'tax'  => (isset($data['tax'][$key])) ? $data['tax'][$key] : NULL,
                        'tax_type'  => (isset($data['tax_type'][$key])) ? $data['tax_type'][$key] : NULL,
                        'service_charge'  => (isset($data['service_charge'][$key])) ? $data['service_charge'][$key] : 0,
                        'shiping_charge'  => (isset($data['shiping_charge'][$key])) ? $data['shiping_charge'][$key] : 0,
                        'image'      => (isset($data['image'][$key])) ? $data['image'][$key] : NULL,
                        'name'       => (isset($data['name'][$key])) ? $data['name'][$key] : NULL,
                        'notes'      =>  (isset($data['notes'])) ? $data['notes'] : NULL,
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
        $data = array('error'=>'0'); 

        if($request->ajax() && isset($request->sum) && isset($request->shc) && isset($request->sc)) {
            $sum = $request->sum;
            $shc = $request->shc;
            $sc = $request->sc;
                
            $cutoff = TaxCutoff::Where('is_block', 1)->get();
            if(sizeof($cutoff) != 0) {
                foreach ($cutoff as $key => $value) {
                    if($value->above_amount < $sum) {
                        $shc = $value->shiping_amount;
                    }                    
                }

                $tot = $sum + $shc;
                $sum = round($sum, 2);
                $shc = round($shc, 2);
                $sc = round($sc, 2);
                $tot = round($tot, 2);
                $data = array('error' => '1', 'sum' => $sum, 'shc' => $shc,'sc' => $sc,'tot' => $tot); 
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
                            $airCod = '';
                            $surfaceCod = '';
                            if(isset($resp['serviceability']['surface10kgPrepaid']) && isset($resp['serviceability']['surface10kgCod']) && isset($resp['serviceability']['lite2kgPrepaid']) && isset($resp['serviceability']['lite2kgCod']) && isset($resp['serviceability']['lite1kgPrepaid']) && isset($resp['serviceability']['lite1kgCod']) && isset($resp['serviceability']['liteHalfKgPrepaid']) && isset($resp['serviceability']['liteHalfKgCod'])) {
                                $surface10kgPrepaid = $resp['serviceability']['surface10kgPrepaid'];
                                $surface10kgCod = $resp['serviceability']['surface10kgCod'];
                                $lite2kgPrepaid = $resp['serviceability']['lite2kgPrepaid'];
                                $lite2kgCod = $resp['serviceability']['lite2kgCod'];
                                $lite1kgPrepaid = $resp['serviceability']['lite1kgPrepaid'];
                                $lite1kgCod = $resp['serviceability']['lite1kgCod'];
                                $liteHalfKgPrepaid = $resp['serviceability']['liteHalfKgPrepaid'];
                                $liteHalfKgCod = $resp['serviceability']['liteHalfKgCod'];
                            }
                            
                            if($surface10kgPrepaid == TRUE && $surface10kgCod == TRUE && $lite2kgPrepaid == TRUE && $lite2kgCod == TRUE && $lite1kgPrepaid == TRUE && $lite1kgCod == TRUE && $liteHalfKgPrepaid == TRUE && $liteHalfKgCod == TRUE) {
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
                        $text = "Please Use this ".$otp." reference code to verify your checkout process,Ecambiar.";
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
            if($data['checkout_verify']) {
                if($user->checkout_verify == $data['checkout_verify']) {
                    $user->checkout_verify = NULL;
                    $user->save();
                } else {
                    Session::flash('message', 'Your Checkout Verification Code is Invalid!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('checkout');
                }
            } else {
                Session::flash('message', 'Please Enter Verification Code!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('checkout');
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
                            $airCod = '';
                            $surfaceCod = '';
                            if(isset($resp['serviceability']['surface10kgPrepaid']) && isset($resp['serviceability']['surface10kgCod']) && isset($resp['serviceability']['lite2kgPrepaid']) && isset($resp['serviceability']['lite2kgCod']) && isset($resp['serviceability']['lite1kgPrepaid']) && isset($resp['serviceability']['lite1kgCod']) && isset($resp['serviceability']['liteHalfKgPrepaid']) && isset($resp['serviceability']['liteHalfKgCod'])) {
                                $surface10kgPrepaid = $resp['serviceability']['surface10kgPrepaid'];
                                $surface10kgCod = $resp['serviceability']['surface10kgCod'];
                                $lite2kgPrepaid = $resp['serviceability']['lite2kgPrepaid'];
                                $lite2kgCod = $resp['serviceability']['lite2kgCod'];
                                $lite1kgPrepaid = $resp['serviceability']['lite1kgPrepaid'];
                                $lite1kgCod = $resp['serviceability']['lite1kgCod'];
                                $liteHalfKgPrepaid = $resp['serviceability']['liteHalfKgPrepaid'];
                                $liteHalfKgCod = $resp['serviceability']['liteHalfKgCod'];
                            }
                            
                            if($surface10kgPrepaid == TRUE && $surface10kgCod == TRUE && $lite2kgPrepaid == TRUE && $lite2kgCod == TRUE && $lite1kgPrepaid == TRUE && $lite1kgCod == TRUE && $liteHalfKgPrepaid == TRUE && $liteHalfKgCod == TRUE) {
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
                    $order = new Orders();
                    $cart = Carts::Where('user_id', $data['user_id'])->get();

                    if($order) {
                        $total_items = 0;
                        $net_amount = 0;
                        $total_service = 0;
                        $total_shiping = 0;
                        if(count($cart) != 0) {
                            $total_items = $cart->sum('qty');

                            $product_serv = DB::table('carts')
                                ->select(DB::raw('sum(products.service_charge) AS serv_total'))
                                ->join('products', 'products.id', '=', 'carts.product_id')
                                ->where('carts.user_id', $data['user_id'])
                                ->first();
                            $total_service = $product_serv->serv_total;

                            $product_ships = DB::table('carts')
                                ->select(DB::raw('sum(products.shiping_charge) AS ship_total'))
                                ->join('products', 'products.id', '=', 'carts.product_id')
                                ->where('carts.user_id', $data['user_id'])
                                ->where('products.tax_type', 2)
                                ->first();
                            $total_shiping = $product_ships->ship_total;

                            $net_total = DB::table('carts')
                                ->select(DB::raw('sum(total_price) AS total'))
                                // ->select(DB::raw('Round(sum(total_price) ,2) AS total'))
                                ->where('user_id', $data['user_id'])
                                ->first();
                            $total_amount = $net_total->total;

                            $cutoff = TaxCutoff::Where('is_block', 1)->get();
                            if(sizeof($cutoff) != 0) {
                                foreach ($cutoff as $ckey => $cvalue) {
                                    if($cvalue->above_amount < $total_amount) {
                                        $total_shiping = $cvalue->shiping_amount;
                                    }                    
                                } 
                            }

                            // $net_amount = $net_total->total + $total_service + $total_shiping;
                            $net_amount = $total_amount + $total_shiping;
                            $total_amount = round($total_amount, 2);
                            $net_amount = round($net_amount, 2);
                            $total_service = round($total_service, 2);
                            $total_shiping = round($total_shiping, 2);
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
                            $order->service_charge = $total_service;
                            $order->shipping_charge = $total_shiping;
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

                                        if(isset($data['price'][$key])) {
                                            $order_details->unitprice = $data['price'][$key];
                                        } else {
                                            $order_details->unitprice = NULL;
                                        }

                                        if(isset($data['total'][$key])) {
                                            $order_details->totalprice = $data['total'][$key];
                                        } else {
                                            $order_details->totalprice = NULL;
                                        }
                                        
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
                                    return View::make("front_end.payment_start")->with(array('order'=>$order));
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
                                    $details = "";
                                    $order_detail = OrderDetails::Where('is_block', 1)->Where('order_id', $order->id)->get();

                                    if($order_detail) {
                                        foreach ($order_detail as $key => $value) {
                                            $stock = Products::Where('id', $value->product_id)->first();

                                            if($stock && ($stock->onhand_qty != 0)) {
                                                $stock_trans = new StockTransactions();
                                                $stock_trans->order_code   = $order_code;
                                                $stock_trans->product_id   = $value->product_id;
                                                $stock_trans->previous_qty = $stock->onhand_qty;
                                                $stock_trans->current_qty  = $stock->onhand_qty- $value->order_qty;
                                                $stock_trans->date         = date('Y-m-d');
                                                $stock_trans->remarks      = $value->product_title.' is ordered.';

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
                                                <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: center;"> '.$value->product_title.' '. $att_tit .'</td>
                                                <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: center;"> '.$value->order_qty.'</td>
                                                <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: center;"> '.$value->unitprice.'</td>
                                                <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: right;"> '.$value->totalprice.'</td>
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

                                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                    $headers.= "MIME-Version: 1.0\r\n";
                                    // $headers.= "From: $admin_email" . "\r\n";
                                    $headers.= "From: noreply@ecambiar.com" . "\r\n";
                                    $to1 = $user->email;
                                    $to2 = $admin_email;
                                    $subject = "Order Details";
                                    $txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 600px;margin: auto;position: relative;background-color: white;">
                                            <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                                            <div style="padding: 20px;background-color: #ff5c00;color: white;">
                                                <h2 style="color: white;margin-top: 0px;">Orders Details</h2>
                                                <table style="border: 1px solid white;margin-bottom: 10px;padding: 10px;width: 570px;">
                                                    <tr>
                                                        <th style="width: 150px;text-align: left;text-transform: uppercase;padding-bottom: 5px;color: white;">customer Name</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$customer_name.'</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 150px;text-align: left;text-transform: uppercase;color: white;">Contact No</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$contact.'</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 150px;text-align: left;text-transform: uppercase;color: white;">Address</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$address.'</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 150px;text-align: left;text-transform: uppercase;color: white;">Order Code</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$order_code.'</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 150px;text-align: left;text-transform: uppercase;color: white;">Order Date</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$order_date.'</td>
                                                    </tr>
                                                </table>
                                                <table style="width: 570px;border: 1px solid white;">
                                                    <tr>
                                                        <th style="width: 100px;text-align: center;text-transform: uppercase;padding-bottom: 5px;color: white;border: 1px solid white;">Product Title</th>
                                                        <th style="width: 100px;text-align: center;text-transform: uppercase;padding-bottom: 5px;color: white;border: 1px solid white;">Quantity</th>
                                                        <th style="width: 100px;text-align: center;text-transform: uppercase;padding-bottom: 5px;color: white;border: 1px solid white;">Price</th>
                                                        <th style="width: 100px;text-align: right;text-transform: uppercase;padding-bottom: 5px;color: white;border: 1px solid white;">Total</th>
                                                    </tr>'.$details.'
                                                    <tr>
                                                        <th colspan="3" style="width: 100px;text-align: right;text-transform: uppercase;padding-bottom: 5px;color: white;border: 1px solid white;padding-right: 10px;">Net Total</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: right;"> '.$net_tot.'</td>
                                                    </tr>
                                                </table>
                                                <p></p>
                                                <p>Thanks & Regards,</p>
                                                <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                            </div>
                                        </div>';

                                    // if(1==1) {
                                    if (mail($to1,$subject,$txt,$headers) && mail($to2,$subject,$txt,$headers)) {
                                        if($user->phone) {
                                            $text = "Thanks for shopping with us.Plz note the Order Code - ".$order_code.", Ecambiar";
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
                                                Session::flash('message', 'Order Confirm Message and Email Send Successfully!'); 
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
                                        Session::flash('message', 'Order Placed Successfully!'); 
                                        Session::flash('alert-class', 'alert-danger');

                                        Carts::Where('user_id', $data['user_id'])->delete();
                                        session()->forget('cart');

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
                                            $stock_trans->previous_qty = $stock->onhand_qty;
                                            $stock_trans->current_qty  = $stock->onhand_qty- $value->order_qty;
                                            $stock_trans->date         = date('Y-m-d');
                                            $stock_trans->remarks      = $value->product_title.' is ordered.';

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
                                        <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: center;"> '.$value->product_title.' '.$att_tit.'</td>
                                        <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: center;"> '.$value->order_qty.'</td>
                                        <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: center;"> '.$value->unitprice.'</td>
                                        <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: right;"> '.$value->totalprice.'</td>
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

                                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                $headers.= "MIME-Version: 1.0\r\n";
                                // $headers.= "From: $admin_email" . "\r\n";
                                $headers.= "From: noreply@ecambiar.com" . "\r\n";
                                $to1 = $user->email;
                                $to2 = $admin_email;
                                $subject = "Order Details";
                                $txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 600px;margin: auto;position: relative;background-color: white;">
                                        <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                                        <div style="padding: 20px;background-color: #ff5c00;color: white;">
                                            <h2 style="color: white;margin-top: 0px;">Orders Details</h2>
                                            <table style="border: 1px solid white;margin-bottom: 10px;padding: 10px;width: 570px;">
                                                <tr>
                                                    <th style="width: 150px;text-align: left;text-transform: uppercase;padding-bottom: 5px;color: white;">customer Name</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$customer_name.'</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 150px;text-align: left;text-transform: uppercase;color: white;">Contact No</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$contact.'</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 150px;text-align: left;text-transform: uppercase;color: white;">Address</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$address.'</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 150px;text-align: left;text-transform: uppercase;color: white;">Order Code</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$order_code.'</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 150px;text-align: left;text-transform: uppercase;color: white;">Order Date</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$order_date.'</td>
                                                </tr>
                                            </table>
                                            <table style="width: 570px;border: 1px solid white;">
                                                <tr>
                                                    <th style="width: 100px;text-align: center;text-transform: uppercase;padding-bottom: 5px;color: white;border: 1px solid white;">Product Title</th>
                                                    <th style="width: 100px;text-align: center;text-transform: uppercase;padding-bottom: 5px;color: white;border: 1px solid white;">Quantity</th>
                                                    <th style="width: 100px;text-align: center;text-transform: uppercase;padding-bottom: 5px;color: white;border: 1px solid white;">Price</th>
                                                    <th style="width: 100px;text-align: right;text-transform: uppercase;padding-bottom: 5px;color: white;border: 1px solid white;">Total</th>
                                                </tr>'.$details.'
                                                <tr>
                                                    <th colspan="3" style="width: 100px;text-align: right;text-transform: uppercase;padding-bottom: 5px;color: white;border: 1px solid white;padding-right: 10px;">Net Total</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: right;"> '.$net_tot.'</td>
                                                </tr>
                                            </table>
                                            <p></p>
                                            <p>Thanks & Regards,</p>
                                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                        </div>
                                    </div>';

                                // if(1==1){
                                if (mail($to1,$subject,$txt,$headers) && mail($to2,$subject,$txt,$headers)) {
                                    if($user->phone) {
                                        $text = "Thanks for shopping with us.Plz note the Order Code ".$order_code.", Ecambiar";
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
                                            Session::flash('message', 'Order Confirm Message and Email Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            Carts::Where('user_id', $user->id)->delete();
                                            session()->forget('cart');
                                        } else {
                                            Session::flash('message', 'Order Placed Mail Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            Carts::Where('user_id', $user->id)->delete();
                                            session()->forget('cart');
                                        }
                                        return View::make("front_end.payment_response")->with(array('data'=>$data));
                                    } else {
                                        Session::flash('message', 'Order Placed Mail Send Successfully!'); 
                                        Session::flash('alert-class', 'alert-success');
                                       
                                        Carts::Where('user_id', $user->id)->delete();
                                        session()->forget('cart');
                                    }
                                    return View::make("front_end.payment_response")->with(array('data'=>$data));
                                } else {
                                    Session::flash('message', 'Order Placed Successfully!'); 
                                    Session::flash('alert-class', 'alert-danger');

                                    Carts::Where('user_id', $user->id)->delete();
                                    session()->forget('cart');

                                    return View::make("front_end.payment_response")->with(array('data'=>$data));
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
                    $airCod = '';
                    $surfaceCod = '';
                    if(isset($resp['serviceability']['surface10kgPrepaid']) && isset($resp['serviceability']['surface10kgCod']) && isset($resp['serviceability']['lite2kgPrepaid']) && isset($resp['serviceability']['lite2kgCod']) && isset($resp['serviceability']['lite1kgPrepaid']) && isset($resp['serviceability']['lite1kgCod']) && isset($resp['serviceability']['liteHalfKgPrepaid']) && isset($resp['serviceability']['liteHalfKgCod'])) {
                        $surface10kgPrepaid = $resp['serviceability']['surface10kgPrepaid'];
                        $surface10kgCod = $resp['serviceability']['surface10kgCod'];
                        $lite2kgPrepaid = $resp['serviceability']['lite2kgPrepaid'];
                        $lite2kgCod = $resp['serviceability']['lite2kgCod'];
                        $lite1kgPrepaid = $resp['serviceability']['lite1kgPrepaid'];
                        $lite1kgCod = $resp['serviceability']['lite1kgCod'];
                        $liteHalfKgPrepaid = $resp['serviceability']['liteHalfKgPrepaid'];
                        $liteHalfKgCod = $resp['serviceability']['liteHalfKgCod'];
                    }
                    
                    if($surface10kgPrepaid == TRUE && $surface10kgCod == TRUE && $lite2kgPrepaid == TRUE && $lite2kgCod == TRUE && $lite1kgPrepaid == TRUE && $lite1kgCod == TRUE && $liteHalfKgPrepaid == TRUE && $liteHalfKgCod == TRUE) {
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