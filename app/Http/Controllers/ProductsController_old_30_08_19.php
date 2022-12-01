<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\ProductsAttributes;
use App\AttributesSettings;
use App\AttributesFields;
use App\ProductsImages;
use App\StockManagement;
use App\SubStock;
use App\User;
use App\CityManagement;
use App\CountriesManagement;
use App\CategoryManagementSettings;
use App\SubCategoryManagementSettings;
use App\SubSubCategoryManagementSettings;
use App\MeasurementUnits;
use App\TaxManagement;
use App\Tags;
use App\Store;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;
use Carbon\Carbon;

class ProductsController extends Controller
{
    protected $respose;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Products";
            	$log = session()->get('user');
            	if($log) {
        	    	if($log->user_type == 1) {
                		$product = Products::Orderby('id', 'desc')->paginate(10);
            			return View::make("products.product.manage_product")->with(array('product'=>$product, 'page'=>$page));
        	    	} elseif ($log->user_type == 2 || $log->user_type == 3) {
            			$product = Products::Where('created_user',$log->id)->paginate(10);
            			return View::make("products.product.manage_product")->with(array('product'=>$product, 'page'=>$page));
        	    	}
            	} else {
            		Session::flash('message', 'You Are Not Login!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('admin');
            	}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function create () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Products";
            	$attributes = AttributesFields::Where('is_block', 1)->get();
            	return View::make('products.product.add_product')->with(array('attributes'=>$attributes, 'page'=>$page));
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function store(Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Products";
            	$log = session()->get('user');
            	$rules = array(
                    'product_code'         => 'nullable',
                    'product_title'        => 'required',
                    'product_desc'         => 'required',
                    'product_weight'       => 'nullable|numeric',
                    'product_length'       => 'nullable|numeric',
                    'product_width'        => 'nullable|numeric',
                    'product_height'       => 'nullable|numeric',
                    'brand'                => 'nullable',
                    'model_no'             => 'nullable',
                    'varient'              => 'nullable',
                    'vendor_code'          => 'nullable',
                    'main_cat_name'        => 'required',
                    'sub_cat_name'         => 'required',
                    'sub_sub_cat_name'     => 'required',
                    'manufacturer'         => 'required',
                    'tags'                 => 'nullable',
                    'original_price'       => 'required|numeric',
                    'tax'                  => 'required|numeric',
                    'product_cost'         => 'required|numeric',
                    'tax_amount'           => 'required|numeric',
                    'discounted_price'     => 'required|numeric',
                    'service_charge'       => 'nullable|numeric',
                    'tax_type'             => 'required',
                    'shiping_charge'       => 'required|numeric',
                    'onhand_qty'           => 'required|integer',
                    'measurement_unit'     => 'required',
                    'features'             => 'required',
                    'shiping_policy'       => 'required',
                    'attributes_flag'      => 'required',
                    'offers_flag'          => 'required',
                    'featuredproduct_flag' => 'required',
                    'toprated_flag'        => 'required',
                    'best_seller_flag'     => 'required',
                    'delivery'             => 'nullable|integer',
                    'store_name'           => 'nullable',
                    'created_user'         => 'nullable',
                    'modified_user'        => 'nullable',
                    'is_block'             => 'nullable',
                    'featured_product_img' => 'required|dimensions:max_width=800,max_height=800',

                    // 'v_att_default'        => 'required_if:attributes_flag,==,1',
                    'attribute_name'       => 'required_if:attributes_flag,==,1',
                    'att_value'            => 'required_if:attributes_flag,==,1',
                    'att_description'      => 'required_if:attributes_flag,==,1',
                    'att_cost'             => 'required_if:attributes_flag,==,1',
                    'att_tax_amount'       => 'required_if:attributes_flag,==,1',
                    'att_price'            => 'required_if:attributes_flag,==,1',
                    'att_qty'              => 'required_if:attributes_flag,==,1',
                    'att_image'            => 'required_if:attributes_flag,==,1',

                    'p_name'               => 'required',
                    'p_image'              => 'required',
                );

                $messages=[
                    'onhand_qty.required'=>'The onhand quantity field is required.',
                    'att_image.required_if'=>'Attribute Name, Price, Description and Image field is required.',
                    'p_image.required'=>'Product Image field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            	   	$attributes = AttributesFields::Where('is_block', 1)->get();
            		return View::make('products.product.add_product')->withErrors($validator)->with(array('attributes'=>$attributes, 'page'=>$page));
                } else {
                    $data = Input::all();
                    $max = Products::max('product_code');
                    $max_id = "0001";
                	$max_st = "pr";
                    if(($max)) {
                    	$max_no = substr($max, 2);
                    	$increment = (int)$max_no + 1;
                    	$data['product_code'] = $max_st.sprintf("%04d", $increment);
                    } else {
                    	$data['product_code'] = $max_st.$max_id;
                    }

                	$product = new Products();

                	if($data['attributes_flag'] == 1 && isset($data['att_qty'])) {
                		$data['onhand_qty'] = array_sum($data['att_qty']);
                	}

                    if($product) {
        	            $product->product_code           = $data['product_code'];	 
        	            $product->product_title          = $data['product_title'];	 
        	            $product->product_desc           = $data['product_desc'];	 
        	           // $product->product_weight         = $data['product_weight'];	 
        	           // $product->product_length         = $data['product_length'];	 
        	           // $product->product_width          = $data['product_width'];	 
        	           // $product->product_height         = $data['product_height'];	 
        	            // $product->vendor_code            = $data['vendor_code'];	
        	            $product->brand                  = $data['brand'];	 
        	            $product->model_no               = $data['model_no'];	 
        	            $product->varient                = $data['varient'];	 
        	            $product->main_cat_name          = $data['main_cat_name'];	 
        	            $product->sub_cat_name           = $data['sub_cat_name'];	 
        	            $product->sub_sub_cat_name       = $data['sub_sub_cat_name'];	 
        	            $product->manufacturer           = $data['manufacturer'];	 

                        if(isset($data['tags'])) {
                           $product->tags                = json_encode($data['tags']);
                        } else {
                           $product->tags                = NULL;
                        }   

        	            $product->original_price         = $data['original_price'];	 
        	            $product->tax                    = $data['tax']; 
        	            $product->product_cost           = $data['product_cost'];	 
                        $product->tax_amount             = $data['tax_amount'];    
                        $product->discounted_price       = $data['discounted_price'];    
        	            $product->service_charge         = $data['service_charge'];	 
        	            $product->tax_type               = $data['tax_type'];	 
        	            $product->shiping_charge         = $data['shiping_charge'];	 
        	            $product->onhand_qty             = $data['onhand_qty'];	 
        	            $product->measurement_unit       = $data['measurement_unit'];	 
        	            $product->features               = $data['features'];	 
        	            $product->shiping_policy         = $data['shiping_policy'];	 
        	            $product->attributes_flag        = $data['attributes_flag'];	 
        	            $product->offers_flag            = $data['offers_flag'];	 
        	            $product->featuredproduct_flag   = $data['featuredproduct_flag'];	 
        	            $product->toprated_flag          = $data['toprated_flag'];	 
        	            $product->best_seller_flag       = $data['best_seller_flag'];	 
        	            $product->delivery               = $data['delivery'];	 
        	            if($data['store_name']) {
        	            	$product->store              = $data['store_name'];	
        	            } else {
        	            	$product->store              = 0;	
        	            }

        	            if($log) {
        	            	$product->created_user       = $log->id;	            
        	            } else {
        	            	$product->created_user       = 1;	            
        	            }
        	            $product->is_block               = 1;

        	            $img_files = Input::file('featured_product_img');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/featured_products/'.$date;
                            $file_path = 'images/featured_products/'.$date;
                            $img_files->move($file_path, $file_name);
                            $product->featured_product_img = $date.'/'.$file_name;
                        } else {
                            $product->featured_product_img = NULL;
                        }	 
                        
                        if($product->save()) {
                        	$stock = new StockManagement();
                        	$stock->product_id    =  $product->id; 
                        	$stock->previous_qty  =  0;
                        	$stock->current_qty   =  $product->onhand_qty;
        		            $stock->addon_qty     =  0;
        		            $stock->date          =  date('Y-m-d');
        		            if($log) {
        		            	$stock->created_user = $log->id;	            
        		            } else {
        		            	$stock->created_user =  1;
        		            }
        		            $stock->is_block      =  1;
        		            $stock->save();

        		            if($product->attributes_flag == 1) {
        			            if($data['attribute_name'] && count($data['attribute_name']) != 0) {
        			            	foreach ($data['attribute_name'] as $key => $value) {
        			            		$attr = new ProductsAttributes();

        			            		$attr->product_id        = $product->id; 
        			            		$attr->attribute_name  = $value;

        			            		if(isset($data['att_value'][$key])) {
        			            			$attr->attribute_values  = $data['att_value'][$key];	 
        			            		} else {
        			            			$attr->attribute_values  = NULL;	 
        			            		}

        			            		if(isset($data['v_att_default'][$key])) {
        			            			$attr->att_default  = $data['v_att_default'][$key];	 
        			            		} else {
        			            			$attr->att_default  = 0;	 
        			            		}

        			            		if(isset($data['att_description'][$key])) {
        			            			$attr->description = $data['att_description'][$key];	 
        			            		} else {
        			            			$attr->description = NULL;	 
        			            		}

        			            		if(isset($data['att_cost'][$key])) {
        			            			$attr->att_cost = $data['att_cost'][$key];	 
        			            		} else {
        			            			$attr->att_cost = 0.00;	 
        			            		}

                                        if(isset($data['att_tax_amount'][$key])) {
                                            $attr->att_tax_amount = $data['att_tax_amount'][$key];   
                                        } else {
                                            $attr->att_tax_amount = 0.00;  
                                        }

                                        if(isset($data['att_price'][$key])) {
                                            $attr->att_price = $data['att_price'][$key];     
                                        } else {
                                            $attr->att_price = 0.00;     
                                        }

        			            		if(isset($data['att_qty'][$key])) {
        			            			$attr->att_qty   = $data['att_qty'][$key];	 
        			            		} else {
        			            			$attr->att_qty   = NULL;	 
        			            		}

        			            		if(isset($data['att_image'][$key])) {
        		            				$file_name = $data['att_image'][$key]->getClientOriginalName();
        				                    $date = date('M-Y');
        				                    // $file_path = '../public/images/attributes/'.$date;
        				                    $file_path = 'images/attributes/'.$date;
        				                    $data['att_image'][$key]->move($file_path, $file_name);

        				                    $attr->image       = $date.'/'.$file_name;
        			            		} else {
        				                    $attr->image       = NULL;

        			            		}
        			            		
        			            		if ($product->attributes_flag == 1) {
        			            			$attr->is_block        = 1;
        			            		} else {
        			            			$attr->is_block        = 0;
        			            		}

        	            				$attr->save();

        	            				if($attr && $stock) {
        						            $sub_stock = new SubStock();
        				                	$sub_stock->product_id    =  $product->id; 
        				                	$sub_stock->attribute     =  $attr->id; 
        				                	$sub_stock->stock         =  $stock->id; 
        				                	$sub_stock->current_qty   =  $attr->att_qty;
        				                	$sub_stock->date          =  date('Y-m-d');
        						            $sub_stock->save();
        					            }
        			            	}
        			            }
        		            }

        		            if($data['p_name'] && count($data['p_name']) != 0) {
        		            	foreach ($data['p_name'] as $key => $value) {
        		            		$p_images = new ProductsImages();

        		            		if(isset($data['p_image'][$key])) {
        		            			$file_name = $data['p_image'][$key]->getClientOriginalName();
        			                    $date = date('M-Y');
        			                    // $file_path = '../public/images/products/'.$date;
        			                    $file_path = 'images/products/'.$date;
        			                    $data['p_image'][$key]->move($file_path, $file_name);
        			                    $p_images->image       = $date.'/'.$file_name;
        		                    } else {
        			                    $p_images->image       = NULL;
        		                    }

        		            		$p_images->product_id  = $product->id; 

        	            			$p_images->p_name      = $value;	 
        		            		$p_images->is_block    = 1;

        		            		$p_images->save();
        		            	}
        		            }
        	                
        	                Session::flash('message', 'Added Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_product');
        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_product');
        	            }  
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_product');
                    }
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function view ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
        		$page = "Products";
        		$product = Products::where('id',$id)->first();
        		return View::make("products.product.view_product")->with(array('product'=>$product, 'page'=>$page));
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
	}

	public function edit ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Products";
        		$product = Products::where('id',$id)->first();
        		$attributes = AttributesFields::Where('is_block', 1)->get();
        		return View::make("products.product.edit_product")->with(array('product'=>$product, 'attributes'=>$attributes, 'page'=>$page));
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
	}

	public function update (Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Products";
        		$log = session()->get('user');
        		$id = Input::get('product_id');
                $product = '';
                if($id != '') {
                	$product = Products::Where('id', $id)->first();
                }

                if($product) {
        	        $rules = array(
        	            'product_code'         => 'nullable',
        	            'product_title'        => 'required',
        	            'product_desc'         => 'required',
        	            'product_weight'       => 'nullable|numeric',
        	            'product_length'       => 'nullable|numeric',
        	            'product_width'        => 'nullable|numeric',
        	            'product_height'       => 'nullable|numeric',
        	            'brand'                => 'nullable',
        	            'model_no'             => 'nullable',
        	            'varient'              => 'nullable',
        	            // 'vendor_code'          => 'nullable',
        	            'main_cat_name'        => 'required',
        	            'sub_cat_name'         => 'required',
        	            'sub_sub_cat_name'     => 'required',
        	            'manufacturer'         => 'required',
        	            'tags'                 => 'nullable',
                        'original_price'       => 'required|numeric',
                        'tax'                  => 'required|numeric',
                        'product_cost'         => 'required|numeric',
                        'tax_amount'           => 'required|numeric',
                        'discounted_price'     => 'required|numeric',
        	            'service_charge'       => 'nullable|numeric',
        	            'tax_type'             => 'required',
        	            'shiping_charge'       => 'required|numeric',
        	            // 'onhand_qty'           => 'required|integer',
        	            'measurement_unit'     => 'required',
        	            'features'             => 'required',
        	            'shiping_policy'       => 'required',
        	            'attributes_flag'      => 'required',
        	            'offers_flag'          => 'required',
        	            'featuredproduct_flag' => 'required',
        	            'toprated_flag'        => 'required',
        	            'best_seller_flag'     => 'required',
        	            'delivery'             => 'nullable|integer',
        	            'store_name'           => 'nullable',
        	            'created_user'         => 'nullable',
        	            'modified_user'        => 'nullable',
        	            'is_block'             => 'nullable',
        	            'featured_product_img' => 'nullable|dimensions:max_width=800,max_height=800',

        	            'attribute_name'       => 'required',
        	            'att_value'            => 'required',
        	            'att_description'      => 'required',
        	            'att_cost'             => 'required',
                        'att_tax_amount'       => 'required',
                        'att_price'            => 'required',
                        'att_qty'              => 'required',
        	            'att_image'            => 'nullable',

        	            // 'v_att_default'        => 'required_if:attributes_flag,==,1',
        	            // 'attribute_name'       => 'required_if:attributes_flag,==,1',
        	            // 'att_value'            => 'required_if:attributes_flag,==,1',
        	            // 'colors'               => 'required_if:attributes_flag,==,1',
        	            // 'sizes'                => 'required_if:attributes_flag,==,1',
        	            // 'capacity'             => 'required_if:attributes_flag,==,1',
        	            // 'att_description'      => 'required_if:attributes_flag,==,1',
        	            // // 'att_price'            => 'required_if:attributes_flag,==,1',
        	            // 'att_image'            => 'required_if:attributes_flag,==,1',		

        	            'p_name'               => 'required',
        	            'p_image'              => 'nullable',
        	        );
        	        $validator = Validator::make(Input::all(), $rules);

        	        if ($validator->fails()) {
        	        	$attributes = AttributesFields::Where('is_block', 1)->get();
        	        	return Redirect::to('/edit_product/' . $id)->withErrors($validator)->with(array('product'=>$product, 'attributes'=>$attributes, 'page'=>$page));
        	        } else {
        	            $data = Input::all();

        	            if($data['attributes_flag'] == 1 && isset($data['att_qty'])) {
        	        		$data['onhand_qty'] = array_sum($data['att_qty']);
        	        	}

        	            $product->product_title          = $data['product_title'];	 
        	            $product->product_desc           = $data['product_desc'];
        	           // $product->product_weight         = $data['product_weight'];	 
        	           // $product->product_length         = $data['product_length'];	 
        	           // $product->product_width          = $data['product_width'];	 
        	           // $product->product_height         = $data['product_height'];	 
        	            $product->brand                  = $data['brand'];	 
        	            $product->model_no               = $data['model_no'];	 
        	            $product->varient                = $data['varient'];	
        	            $product->main_cat_name          = $data['main_cat_name'];	 
        	            $product->sub_cat_name           = $data['sub_cat_name'];	 
        	            $product->sub_sub_cat_name       = $data['sub_sub_cat_name'];	 
        	            $product->manufacturer           = $data['manufacturer'];
                        
                        if(isset($data['tags'])) {
        	               $product->tags                = json_encode($data['tags']);
                        } else {
                           $product->tags                = NULL;
                        }

                        $product->original_price         = $data['original_price'];  
                        $product->tax                    = $data['tax']; 
                        $product->product_cost           = $data['product_cost'];    
                        $product->tax_amount             = $data['tax_amount'];    
                        $product->discounted_price       = $data['discounted_price'];  
        	            $product->service_charge         = $data['service_charge'];	 
        	            $product->tax_type               = $data['tax_type'];	 
        	            $product->shiping_charge         = $data['shiping_charge'];	 
        	            $product->onhand_qty             = $data['onhand_qty'];	 
        	            $product->measurement_unit       = $data['measurement_unit'];	 
        	            $product->features               = $data['features'];	 
        	            $product->shiping_policy         = $data['shiping_policy'];	 
        	            $product->attributes_flag        = $data['attributes_flag'];	 
        	            $product->offers_flag            = $data['offers_flag'];	 
        	            $product->featuredproduct_flag   = $data['featuredproduct_flag'];	 
        	            $product->toprated_flag          = $data['toprated_flag'];	 
        	            $product->best_seller_flag       = $data['best_seller_flag'];	 
        	            $product->delivery               = $data['delivery'];	 
        	            if($data['store_name']) {
        	            	$product->store              = $data['store_name'];	
        	            } else {
        	            	$product->store              = 0;	
        	            }

        	            if($log) {
        	            	$product->modified_user      = $log->id;	            
        	            } else {
        	            	$product->modified_user      = 1;	            
        	            }         
        	            $product->is_block               = 1;

        	            $img_files = Input::file('featured_product_img');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/featured_products/'.$date;
                            $file_path = 'images/featured_products/'.$date;
                            $img_files->move($file_path, $file_name);
                            $product->featured_product_img = $date.'/'.$file_name;
                        } else if (isset($data['old_featured_product_img'])) {
                            $product->featured_product_img = $data['old_featured_product_img'];
                        } else {
                            $product->featured_product_img = NULL;
                        }	 
                        
                        if($product->save()) {
                        	$stock = StockManagement::Where('product_id', $product->id)->first();
                            if($stock) {
                            	$stock->current_qty   =  $product->onhand_qty;
            		            $stock->save();
                            } else {
                                $stock = new StockManagement();
                                $stock->product_id   =  $product->id;
                                $stock->previous_qty =  0;
                                $stock->current_qty  =  $product->onhand_qty;
                                $stock->addon_qty    =  $product->onhand_qty;
                                $stock->date         =  date('Y-m-d');
                                $stock->created_user =  $log->id;
                                $stock->is_block     =  1;
                                $stock->save();
                            }
        								
                        	if($product->attributes_flag == 1) {
        			            if($data['attribute_name'] && count($data['attribute_name']) != 0) {
        			            	ProductsAttributes::where('product_id', $product->id)->delete();
        			            	foreach ($data['attribute_name'] as $key => $value) {
        			            		$attr = new ProductsAttributes();

        			            		$attr->product_id        = $product->id; 
        			            		$attr->attribute_name  = $value;

        			            		if(isset($data['att_value'][$key])) {
        			            			$attr->attribute_values  = $data['att_value'][$key];	 
        			            		} else {
        			            			$attr->attribute_values  = NULL;	 
        			            		}

        			            		if(isset($data['v_att_default'][$key])) {
        			            			$attr->att_default  = $data['v_att_default'][$key];	 
        			            		} else {
        			            			$attr->att_default  = 2;	 
        			            		}

                                        if(isset($data['att_cost'][$key])) {
                                            $attr->att_cost = $data['att_cost'][$key];   
                                        } else {
                                            $attr->att_cost = 0.00;  
                                        }

                                        if(isset($data['att_tax_amount'][$key])) {
                                            $attr->att_tax_amount = $data['att_tax_amount'][$key];   
                                        } else {
                                            $attr->att_tax_amount = 0.00;  
                                        }

                                        if(isset($data['att_price'][$key])) {
                                            $attr->att_price = $data['att_price'][$key];     
                                        } else {
                                            $attr->att_price = 0.00;     
                                        }

        			            		if(isset($data['att_qty'][$key])) {
        			            			$attr->att_qty  = $data['att_qty'][$key];	 
        			            		} else {
        			            			$attr->att_qty  = NULL;	 
        			            		}

        			            		if(isset($data['att_description'][$key])) {
        			            			$attr->description = $data['att_description'][$key];	 
        			            		} else {
        			            			$attr->description = NULL;	 
        			            		}

        			            		if(isset($data['att_image'][$key])) {
        		            				$file_name = $data['att_image'][$key]->getClientOriginalName();
        				                    $date = date('M-Y');
        				                    // $file_path = '../public/images/attributes/'.$date;
        				                    $file_path = 'images/attributes/'.$date;
        				                    $data['att_image'][$key]->move($file_path, $file_name);

        				                    $attr->image       = $date.'/'.$file_name;
        			            		} else if (isset($data['old_att_image'][$key])) {
        				                    $attr->image       = $data['old_att_image'][$key];

        			            		} else {
        				                    $attr->image       = NULL;

        			            		}

        			            		if ($product->attributes_flag == 1) {
        			            			$attr->is_block        = 1;
        			            		} else {
        			            			$attr->is_block        = 0;
        			            		}

        	            				$attr->save();

        	            				if($attr && $stock) {
        	            					// SubStock::where('product_id', $product->id)->delete();
        						            $sub_stock = new SubStock();
        				                	$sub_stock->product_id    =  $product->id; 
        				                	$sub_stock->attribute     =  $attr->id; 
        				                	$sub_stock->stock         =  $stock->id; 
        				                	$sub_stock->current_qty   =  $attr->att_qty;
        				                	$sub_stock->date          =  date('Y-m-d');
        						            $sub_stock->save();
        					            }
        			            	}
        			            }
                        	} else {
                        		ProductsAttributes::where('product_id', $product->id)->delete();
                        	}

        		            if($data['p_name'] && count($data['p_name']) != 0) {
        		            	ProductsImages::where('product_id', $product->id)->delete();
        		            	foreach ($data['p_name'] as $key => $value) {
        		            		$p_images = new ProductsImages();

        		            		if(isset($data['p_image'][$key])) {
        		            			$file_name = $data['p_image'][$key]->getClientOriginalName();
        			                    $date = date('M-Y');
        			                    // $file_path = '../public/images/products/'.$date;
        			                    $file_path = 'images/products/'.$date;
        			                    $data['p_image'][$key]->move($file_path, $file_name);
        			                    $p_images->image       = $date.'/'.$file_name;
        		                    } else if (isset($data['old_p_image'][$key])) {
        			                    $p_images->image       = $data['old_p_image'][$key];
        		                    } else {
        			                    $p_images->image       = NULL;
        		                    }

        		            		$p_images->product_id  = $product->id; 

        	            			$p_images->p_name      = $value;	 
        		            		$p_images->is_block    = 1;

        		            		$p_images->save();
        		            	}
        		            }
        	                
        	                Session::flash('message', 'Updated Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_product');
        	            } else{
        	            	Session::flash('message', 'Updated Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_product');
        	            }	            
        	        }
                } else {
                	Session::flash('message', 'Updated Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_product');
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
	}

	public function delete( Request $request) {	
		$id = 0;
		$error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$product = Products::where('id',$id)->first();
        				if($product){
        					$p_id = $product->id;
        					if($product->delete()) {
        						Session::flash('message', 'Deleted Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						$error = 0;
        					} else {
        						Session::flash('message', 'Deleted Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        						$error = 1;
        					}
        				}	else {
        					Session::flash('message', 'Deleted Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        					$error = 1;
        				}			
        			} else {
        				Session::flash('message', 'Deleted Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = 1;
        			}
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $error = 1;
        }

		echo $error;
	}

	public function DeleteAll( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$product = Products::where('id',$value)->first();
        					if($product){
        						$p_id = $product->id;
        						if($product->delete()) {
        							Session::flash('message', 'Deleted Successfully!'); 
        							Session::flash('alert-class', 'alert-success');
        							$error = 0;
        						} else {
        							Session::flash('message', 'Deleted Failed!'); 
        							Session::flash('alert-class', 'alert-danger');

        						}
        					}	else {
        						Session::flash('message', 'Deleted Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        					}			
        				}
        			} else {
        				Session::flash('message', 'Deleted Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = 1;
        			}
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $error = 1;
        }

		echo $error;
	}

	public function StatusProduct ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$tag = '';
        		$msg = '';
            	if($id != '') {
                	$product = Products::Where('id', $id)->first();
                }

                if($product) {
                	if($product->is_block == 1) {
                    	$product->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$product->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($product->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_product');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_product');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_product');
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
	}

	public function ProductBlock( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;

        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$product = Products::where('id',$value)->first();
        					if($product){
        						$product->is_block = 0;
        						$product->save();
        						Session::flash('message', 'Blocked Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						$error = 0;
        					}	else {
        						Session::flash('message', 'Blocked Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        					}			
        				}
        			} else {
        				Session::flash('message', 'Blocked Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = 1;
        			}
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $error = 1;
        }

		echo $error;
	}

	public function ProductUnblock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$product = Products::where('id',$value)->first();
        					if($product){
        						$product->is_block = 1;
        						$product->save();
        						Session::flash('message', 'Unblocked Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						$error = 0;
        					}	else {
        						Session::flash('message', 'Unblocked Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        					}			
        				}
        			} else {
        				Session::flash('message', 'Unblocked Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = 1;
        			}
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

		echo $error;

	}

	public function SelectSubCat (Request $request) {
		$main_cat = 0;
    	$sub_cat_val = 0;
		if($request->ajax() && isset($request->main_cat)){
			$main_cat = $request->main_cat;

			if(isset($request->sub_cat)) {
				$sub_cat_val = $request->sub_cat;
			}

			$data = "";
			if($main_cat != 0) {
				$sub_cat = SubCategoryManagementSettings::where('main_cat_name',$main_cat)->get();
				if(($sub_cat) && (sizeof($sub_cat) != 0)){
					if($sub_cat_val != 0) {
	                    foreach ($sub_cat as $key => $value) {
	                    	if($sub_cat_val == $value->sub_cat_id) {
	                        	$data.='<option selected value="'.$value->sub_cat_id.'">'.$value->sub_cat_name.'</option>';
	                    	} else {
	                        	$data.='<option value="'.$value->sub_cat_id.'">'.$value->sub_cat_name.'</option>';
	                    	}
	                    }
					} else {
						$data = '<option value="0" selected>Select Sub Category Name</option>';
	                    foreach ($sub_cat as $key => $value) {
	                        $data.='<option value="'.$value->sub_cat_id.'">'.$value->sub_cat_name.'</option>';
	                    }
					}
                } 			
			}
			echo $data;
		}
    }

    public function SelectSubSubCat (Request $request) {
		$sub_cat = 0;
    	$sub_sub_cat_val = 0;
		if($request->ajax() && isset($request->sub_cat)){
			$sub_cat = $request->sub_cat;

			if(isset($request->sub_sub_cat)) {
				$sub_sub_cat_val = $request->sub_sub_cat;
			}

			$data = "";
			if($sub_cat != 0) {
				$sub_sub_cat = SubSubCategoryManagementSettings::where('sub_cat_name',$sub_cat)->get();
				if(($sub_sub_cat) && (sizeof($sub_sub_cat) != 0)){
					if($sub_sub_cat_val != 0) {
	                    foreach ($sub_sub_cat as $key => $value) {
	                    	if($sub_sub_cat_val == $value->sub_sub_cat_id) {
	                        	$data.='<option selected value="'.$value->sub_sub_cat_id.'">'.$value->sub_sub_cat_name.'</option>';
	                    	} else {
	                        	$data.='<option value="'.$value->sub_sub_cat_id.'">'.$value->sub_sub_cat_name.'</option>';
	                    	}
	                    }
					} else {
						$data = '<option value="0" selected>Select Sub Category Name</option>';
	                    foreach ($sub_sub_cat as $key => $value) {
	                        $data.='<option value="'.$value->sub_sub_cat_id.'">'.$value->sub_sub_cat_name.'</option>';
	                    }
					}
                } 			
			}
			echo $data;
		}
    }

    public function SelectAttVals (Request $request) {
		$id = 0;
		$old_id = 0;
		$product_id = 0;
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			if(isset($request->old_id) && ($request->old_id)) {
				$old_id = $request->old_id;
			}

			if(isset($request->product_id) && ($request->product_id)) {
				$product_id = $request->product_id;
			}

			$data = 0;
			$att_id = [];
			if($id != 0) {
				if($product_id) {
					$pro_att = ProductsAttributes::where('product_id', $product_id)->where('attribute_name', $id)->Where('is_block', 1)->get();
					if(sizeof($pro_att) != 0) {
		                foreach ($pro_att as $key => $value) {
		                    array_push($att_id, $value->attribute_values);                   
		                }
		            }

		            if(sizeof($att_id) != 0) {
		                $attributes = AttributesSettings::WhereIn('id', $att_id)->Where('is_block' ,1)->get();
		                if(isset($attributes) && (sizeof($attributes) != 0)){
		                	if($old_id != 0) {
								$data = '<option value="">Select Attributes Value</option>';
			                    foreach ($attributes as $key => $value) {
			                    	if($old_id == $value->id) {
			                        	$data.='<option value="'.$value->id.'" selected>'.$value->att_value.'</option>';
			                    	} else {
			                        	$data.='<option value="'.$value->id.'">'.$value->att_value.'</option>';
			                    	}
			                    }
							} else {
								$data = '<option value="" selected>Select Attributes Value</option>';
			                    foreach ($attributes as $key => $value) {
			                        $data.='<option value="'.$value->id.'">'.$value->att_value.'</option>';
			                    }
							}
		                } 
		            }
				} else {
					$attributes = AttributesSettings::where('att_name',$id)->Where('is_block' ,1)->get();
					if(isset($attributes) && (sizeof($attributes) != 0)){
						if($old_id != 0) {
							$data = '<option value="">Select Attributes Value</option>';
		                    foreach ($attributes as $key => $value) {
		                    	if($old_id == $value->id) {
		                        	$data.='<option value="'.$value->id.'" selected>'.$value->att_value.'</option>';
		                    	} else {
		                        	$data.='<option value="'.$value->id.'">'.$value->att_value.'</option>';
		                    	}
		                    }
						} else {
							$data = '<option value="" selected>Select Attributes Value</option>';
		                    foreach ($attributes as $key => $value) {
		                        $data.='<option value="'.$value->id.'">'.$value->att_value.'</option>';
		                    }
						}
	                } 			
				}
			}
			echo $data;
		}
    }

	public function GetTax( Request $request) {	
		$main_cat = "";

		if($request->ajax() && isset($request->main_cat)){
			$main_cat = $request->main_cat;
			$error = 'error';
			$tax = TaxManagement::Where('main_cat_name', $main_cat)->Where('is_block', 1)->first();
			if($tax) {
				$error = $tax->tax;
				Session::flash('message', 'Get Tax Successfully!'); 
				Session::flash('alert-class', 'alert-success');
			} else {
				Session::flash('message', 'Get Tax Failed!'); 
				Session::flash('alert-class', 'alert-danger');
				$error = 'error';
			}
		}
		echo $error;
	}	

    public function SearchProducts (Request $request) {
        $page = "Products";                                               
        $gj_srh_pdts = Input::get('gj_srh_pdts');

        if($gj_srh_pdts) {
            $page = "Products";
            $log = session()->get('user');
            if($log) {
                if($log->user_type == 1) {
                    $product = Products::Orderby('id', 'desc')->orWhere('product_title', 'like', '%' . $gj_srh_pdts . '%')->paginate(10);
                    Session::flash('message', 'Search Items Founded!'); 
                    Session::flash('alert-class', 'alert-success');
                    return View::make("products.product.manage_product")->with(array('product'=>$product, 'page'=>$page));
                } elseif ($log->user_type == 2 || $log->user_type == 3) {
                    $product = Products::Where('created_user',$log->id)->orWhere('product_title', 'like', '%' . $gj_srh_pdts . '%')->paginate(10);
                    Session::flash('message', 'Search Items Founded!'); 
                    Session::flash('alert-class', 'alert-success');
                    return View::make("products.product.manage_product")->with(array('product'=>$product, 'page'=>$page));
                }
            } else {
                Session::flash('message', 'You Are Not Login!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('admin');
            }
        } else {
            Session::flash('message', 'Search Items Not Found!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_product');
        }
    }
	
	public function ExportCSV( Request $request) {
		$error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Products')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {	
        		if($request->ajax()) {
        			$ids = $request->ids;
        			$table = array();
        			$filename = "products.csv";
        			if(isset($ids) && $ids) {
        				if(sizeof($ids) != 0) {
                            $table = Products::whereIn('id',$ids)->get();
                            $filename = "products.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
        			} else if(isset($request->type) && $request->type == 'export_all') {
        				$table = Products::all();
        				$filename = "all_products.csv";
        			} else {
        				Session::flash('message', 'CSV Export Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				die();
        			}

        			foreach ($table as $key => $value) {
        				if($value->main_cat_name) {
        					$table[$key]['main_cat_name'] = $value->MainCat->main_cat_name;
        				} else {
        					$table[$key]['main_cat_name'] = "---------";
        				}

        				if($value->sub_cat_name) {
        					$table[$key]['sub_cat_name'] = $value->SubCat->sub_cat_name;
        				} else {
        					$table[$key]['sub_cat_name'] = "---------";
        				}	

        				if($value->sub_sub_cat_name) {
        					$table[$key]['sub_sub_cat_name'] = $value->SubSubCat->sub_sub_cat_name;
        				} else {
        					$table[$key]['sub_sub_cat_name'] = "---------";
        				}

        				if($value->tags) {
        					$tags = json_decode($value->tags);
        		            if($tags && count($tags) != 0) {
        		                foreach ($tags as $keys => $values) {
        		                    $tag = \DB::table('tags')->where('id',$values)->where('is_block',1)->first();
        		                    if(($tag)){
        		                        $table[$key]['tags'] = $tag->tag_title.', ';
        		                    }
        		                }
        		            } else {
        		                $table[$key]['tags'] = "---------";
        		            }
        				} else {
        					$table[$key]['tags'] = "---------";
        				}

        				if($value->measurement_unit) {
        					$table[$key]['measurement_unit'] = $value->Measurement->unit_name;
        				} else {
        					$table[$key]['measurement_unit'] = "---------";
        				}

        				$patt = "---------";
        				if($value->attributes_flag == 1) {
        					$table[$key]['attributes_flag'] = "Active";
        					$PA = ProductsAttributes::where('product_id', $value->id)->get();
        					if(sizeof($PA) != 0) {
        						$patt="";
        						foreach ($PA as $pkey => $pvalue) {
        							$patt.= 'Attributes : '.$pvalue->AttributeName->att_name.' - '.$pvalue->AttributeValue->att_value.', Price : Rs.'.$pvalue->att_price.', Qty : '.$pvalue->att_qty.', Description : '.$pvalue->description.', ';  							
        						}
        					}
        					$table[$key]['p_attributes'] = $patt;
        				} else {
        					$table[$key]['attributes_flag'] = "Deactive";
        					$table[$key]['p_attributes'] = $patt;
        				}

        				if($value->offers_flag == 1) {
        					$table[$key]['offers_flag'] = "Yes";
        				} else {
        					$table[$key]['offers_flag'] = "No";
        				}

        				if($value->featuredproduct_flag == 1) {
        					$table[$key]['featuredproduct_flag'] = "Yes";
        				} else {
        					$table[$key]['featuredproduct_flag'] = "No";
        				}

        				if($value->toprated_flag == 1) {
        					$table[$key]['toprated_flag'] = "Yes";
        				} else {
        					$table[$key]['toprated_flag'] = "No";
        				}	

        				if($value->delivery) {
        					$table[$key]['delivery'] = $value->delivery." Days";
        				} else {
        					$table[$key]['delivery'] = "---------";
        				}

        				if($value->store) {
        					$table[$key]['store'] = $value->Store->store_name;
        				} else {
        					$table[$key]['store'] = "---------";
        				}

        				if($value->created_user) {
        					$table[$key]['created_user'] = $value->Creatier->first_name.' '.$value->Creatier->last_name;
        				} else {
        					$table[$key]['created_user'] = "---------";
        				}

        				if($value->modified_user) {
        					$table[$key]['modified_user'] = $value->Modifier->first_name.' '.$value->Modifier->last_name;
        				} else {
        					$table[$key]['modified_user'] = "---------";
        				}

        				if($value->is_block == 1) {
        					$table[$key]['is_block'] = "Active";
        				} else {
        					$table[$key]['is_block'] = "---------";
        				}	 	
        			}
        	    	
        		    $handle = fopen($filename, 'w+');
        		    fputcsv($handle, array('Product Code', 'Product Title', 'Product Description', 'Main Category', 'Sub Category', 'Sub Sub Category', 'Manufacturer', 'Tags', 'Original Price', 'discounted_price', 'tax', 'On Hand Quantity', 'Measurement Unit', 'features', 'Attributes Flag', 'Offers Flag', 'Featured Product Flag', 'Top Rated Flag', 'Delivery', 'Store Name', 'Created User', 'Modified User', 'is_block', 'Product Attributes'));

        		    foreach($table as $row) {
        		        fputcsv($handle, array($row['product_code'], $row['product_title'], $row['product_desc'], $row['main_cat_name'], $row['sub_cat_name'], $row['sub_sub_cat_name'], $row['manufacturer'], $row['tags'], "Rs. ".$row['original_price'], "Rs. ".$row['discounted_price'], $row['tax']."%", $row['onhand_qty'], $row['measurement_unit'], $row['features'], $row['attributes_flag'], $row['offers_flag'], $row['featuredproduct_flag'], $row['toprated_flag'], $row['delivery'], $row['store'], $row['created_user'], $row['modified_user'], $row['is_block'], $row['p_attributes']));
        		    }

        		    fclose($handle);

        		    $headers = array(
        		        'Content-Type' => 'text/csv',
        		    );

        			// Session::flash('message', 'CSV Export Successfully!'); 
        			// Session::flash('alert-class', 'alert-success');
        		    $file_path = $filename;
            		return $file_path;
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $error = 1;
        }
        echo $error;
	}
}