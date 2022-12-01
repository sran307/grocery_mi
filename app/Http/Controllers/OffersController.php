<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offers;
use App\OffersSub;
use App\OfferStockManagements;
use App\OfferTransaction;
use App\Products;
use App\ProductsAttributes;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class OffersController extends Controller
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
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Offers";
                $deals = Offers::all();
            	return View::make("products.offers.manage_offer")->with(array('deals'=>$deals, 'page'=>$page));
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

    public function CheckStock( Request $request) {
        $id = 0;
        $qty = 0;
        $att_name = 0;
        $att_value = 0;
        $error = array('error' => '0');
        if($request->ajax() && isset($request->id)){
            $id = $request->id;
            $qty = $request->qty;
            $att_name = $request->att_name;
            $att_value = $request->att_value;
            if($id) {
                $product = Products::where('id',$id)->first();
                if($product){
                    $onhand_qty = $product->onhand_qty;
                    $offer_cost = $product->product_cost;
                    $offer_tax_amount = $product->tax_amount;
                    $offer_price = $product->discounted_price;
                    if($onhand_qty != 0) {
                        if($qty && $qty != 0) {
                            if($onhand_qty >= $qty) {
                                if($att_name && $att_name != 0 && $att_value && $att_value != 0) {
                                    $patt = ProductsAttributes::where('product_id', $product->id)->where('attribute_name', $att_name)->where('attribute_values', $att_value)->Where('is_block', 1)->first();
                                    if($patt) {
                                        $att_qty = $patt->att_qty;
                                        $offer_cost = $patt->att_cost;
                                        $offer_tax_amount = $patt->att_tax_amount;
                                        $offer_price = $patt->att_price;
                                        if($att_qty >= $qty) {
                                            $error = array('onhand_qty' => $onhand_qty, 'offer_cost' => $offer_cost, 'offer_tax_amount' => $offer_tax_amount, 'offer_price' => $offer_price, 'error' => '1');
                                            $error = json_encode($error);
                                        } else {
                                            $error = array('onhand_qty' => $att_qty, 'error' => '2');
                                            $error = json_encode($error);
                                        }
                                    } else {
                                        $error = array('error' => '0');
                                        $error = json_encode($error);
                                    }
                                } else {
                                    $error = array('onhand_qty' => $onhand_qty, 'offer_cost' => $offer_cost, 'offer_tax_amount' => $offer_tax_amount, 'offer_price' => $offer_price, 'error' => '1');
                                    $error = json_encode($error);
                                }
                            } else {
                                $error = array('onhand_qty' => $onhand_qty, 'error' => '2');
                                $error = json_encode($error);
                            }
                        } else {
                            $error = array('onhand_qty' => $onhand_qty, 'offer_cost' => $offer_cost, 'offer_tax_amount' => $offer_tax_amount, 'offer_price' => $offer_price, 'error' => '1');
                            $error = json_encode($error);
                        }
                    } else {
                        $error = array('onhand_qty' => $onhand_qty, 'error' => '3');
                        $error = json_encode($error);
                    }
                } else {
                    $error = array('error' => '0');
                    $error = json_encode($error);
                }
            } else {
                $error = array('error' => '0');
                $error = json_encode($error);
            }
        } else {
            $error = array('error' => '0');
            $error = json_encode($error);
        }

        echo $error;
    }

    public function SelectAtts( Request $request) {
        $id = 0;
        $error = 0;
        $att_n_id = 0;
        if($request->ajax() && isset($request->id)){
            $id = $request->id;
            $att_n_id = $request->att_n_id;
            if($id) {
                $product = Products::where('id',$id)->first();
                if($product){
                    $pro_atts = ProductsAttributes::Where('product_id', $id)->groupBy('attribute_name')->get();
                    if(sizeof($pro_atts) != 0) {
                        $opt='<option value="">Select Attribute Name</option>';
                        foreach ($pro_atts as $pa_key => $pa_value) {
                            if($pa_value->attribute_name) {
                                if($pa_value->AttributeName->att_name) {
                                    if($att_n_id && $att_n_id != 0) {
                                        if($att_n_id == $pa_value->AttributeName->id) {
                                            $opt.='<option selected value="'.$pa_value->AttributeName->id.'">'.$pa_value->AttributeName->att_name.'</option>';
                                        } else {
                                            $opt.='<option value="'.$pa_value->AttributeName->id.'">'.$pa_value->AttributeName->att_name.'</option>';
                                        }
                                    } else {
                                        $opt.='<option value="'.$pa_value->AttributeName->id.'">'.$pa_value->AttributeName->att_name.'</option>';
                                    }
                                } else {
                                    $error = 0;
                                }
                            } else {
                                $error = 0;
                            }
                        }
                        $error = $opt;
                    } else {
                        $error = 0;
                    }
                } else {
                    $error = 0;
                }
            } else {
                $error = 0;
            }
        } else {
           $error = 0;
        }

        echo $error;
    }

    public function create () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Offers";
            	$products = Products::Where('is_block', 1)->get();
            	return View::make('products.offers.add_offer')->with(array('page'=>$page, 'products'=>$products));
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
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$data = Input::all();
            	$page = "Offers";
            	$products = Products::Where('is_block', 1)->get();
            	$log = session()->get('user');
            	$rules = array(
                    'offer_title'  => 'required',
                    'description'  => 'required',
                    'offer_type'   => 'nullable',
                    'x_pro_cnt'    => 'required|numeric',
                    'y_pro_cnt'    => 'required|numeric',
                    'offer_start'  => 'required',
                    'offer_end'    => 'required',
                    'grab_offer'   => 'required',
                    'image'        => 'required',

                    'product_id.*'        => 'required|exists:products,id',
                    'att_name.*'          => 'nullable|exists:attributes_fields,id',
                    'att_value.*'         => 'nullable|exists:attributes_settings,id',
                    'qty.*'               => 'required',
                    'offer_cost.*'        => 'required',
                    'offer_tax_amount.*'  => 'required',
                    'offer_price.*'       => 'required',
                    'type.*'              => 'required',
                );

                $messages=[
                    'offer_start.required'=>'Start Date field is required.',
                    'offer_end.required'=>'End Date field is required.',
                    'x_pro_cnt.required'=>'X-Product Count field is required.',
                    'x_pro_cnt.numeric'=>'X-Product Count field is only numbers.',
                    'y_pro_cnt.required'=>'Y-Product Count field is required.',
                    'y_pro_cnt.numeric'=>'Y-Product Count field is only numbers.',
                    'qty.required'=>'Quantity field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            		return View::make('products.offers.add_offer')->withErrors($validator)->with(array('products'=>$products, 'page'=>$page));
                } else {
        	        $stt = date('Y-m-d', strtotime($data['offer_start']));
        	        $end = date('Y-m-d', strtotime($data['offer_end']));

        	        if($stt <= $end) {
                        $deals = new Offers();

                        if($deals) {
                            $deals->offer_title   = $data['offer_title'];   

                            if($data['offer_type']) {
                                $deals->offer_type    = $data['offer_type'];  
                            } else {
                                $deals->offer_type    = NULL;  
                            } 

                            $deals->description   = $data['description'];    
                            $deals->x_pro_cnt     = $data['x_pro_cnt'];  
                            $deals->y_pro_cnt     = $data['y_pro_cnt'];  
                            $deals->offer_start   = date('Y-m-d H:i:s', strtotime($data['offer_start']));
                            $deals->offer_end     = date('Y-m-d H:i:s', strtotime($data['offer_end']));
                            $deals->grab_offer    = $data['grab_offer'];  
                            $deals->is_block      = 1;

                            $img_files = Input::file('image');
                            if(isset($img_files)) {
                                $file_name = $img_files->getClientOriginalName();
                                $date = date('M-Y');
                                // $file_path = '../public/images/offer_products/'.$date;
                                $file_path = 'images/offer_products/'.$date;
                                $img_files->move($file_path, $file_name);
                                $deals->image = $date.'/'.$file_name;
                            } else {
                                $deals->image = NULL;
                            }    
                            
                            if($deals->save()) {
                                if($data['product_id'] && count($data['product_id']) != 0) {
                                    foreach ($data['product_id'] as $key => $value) {
                                        $off_sub = new OffersSub();

                                        $off_sub->offer       = $deals->id; 
                                        $off_sub->product_id  = $value;

                                        if(isset($data['att_name'][$key])) {
                                            $off_sub->att_name = $data['att_name'][$key];  
                                        } else {
                                            $off_sub->att_name = NULL;    
                                        }

                                        if(isset($data['att_value'][$key])) {
                                            $off_sub->att_value = $data['att_value'][$key];  
                                        } else {
                                            $off_sub->att_value = NULL;    
                                        }

                                        if(isset($data['qty'][$key])) {
                                            $off_sub->qty     = $data['qty'][$key];  
                                        } else {
                                            $off_sub->qty     = NULL;    
                                        }

                                        if(isset($data['type'][$key])) {
                                            $off_sub->type    = $data['type'][$key];     
                                        } else {
                                            $off_sub->type    = NULL;    
                                        }

                                        if($off_sub->type == 1) {
                                            if(isset($data['offer_cost'][$key])) {
                                                $off_sub->offer_cost     = $data['offer_cost'][$key];  
                                            } else {
                                                $off_sub->offer_cost     = 0.00;    
                                            }
                                        } else {
                                            $off_sub->offer_cost     = 0.00;
                                        }

                                        if($off_sub->type == 1) {
                                            if(isset($data['offer_tax_amount'][$key])) {
                                                $off_sub->offer_tax_amount     = $data['offer_tax_amount'][$key];  
                                            } else {
                                                $off_sub->offer_tax_amount     = 0.00;    
                                            }
                                        } else {
                                            $off_sub->offer_tax_amount     = 0.00;
                                        }

                                        if($off_sub->type == 1) {
                                            if(isset($data['offer_price'][$key])) {
                                                $off_sub->offer_price     = $data['offer_price'][$key];  
                                            } else {
                                                $off_sub->offer_price     = 0.00;    
                                            }
                                        } else {
                                            $off_sub->offer_price     = 0.00;
                                        }
                                        
                                        $off_sub->is_block    = 1;
                                        $off_sub->save();

                                        $off_stock = new OfferStockManagements();
                                        $off_stock->offer       = $deals->id; 
                                        $off_stock->product_id  = $value;

                                        if(isset($data['att_name'][$key])) {
                                            $off_stock->att_name = $data['att_name'][$key];  
                                        } else {
                                            $off_stock->att_name = NULL;    
                                        }

                                        if(isset($data['att_value'][$key])) {
                                            $off_stock->att_value = $data['att_value'][$key];  
                                        } else {
                                            $off_stock->att_value = NULL;    
                                        }

                                        if(isset($data['qty'][$key])) {
                                            $off_stock->offer_qty     = $data['qty'][$key];  
                                        } else {
                                            $off_stock->offer_qty     = NULL;    
                                        }

                                        $off_stock->date     = date('Y-m-d');   
                                        $off_stock->save();   
                                    }

                                    $offer_detail = OffersSub::Where('is_block', 1)->Where('offer', $deals->id)->get();
                                    if($offer_detail) {
                                        foreach ($offer_detail as $key => $value) {
                                            $stock = Products::Where('id', $value->product_id)->first();

                                            if($stock && ($stock->onhand_qty != 0)) {
                                                $stock->onhand_qty = $stock->onhand_qty - $value->qty;
                                                $stock->save();
                                                
                                                $p_atts = ProductsAttributes::Where('product_id', $value->product_id)->Where('attribute_name', $value->att_name)->Where('attribute_values', $value->att_value)->first();
                                                if($p_atts) {
                                                    $p_atts->att_qty = $p_atts->att_qty - $value->qty;
                                                    $p_atts->save();
                                                }
                                            }
                                        }
                                    }

                                    Session::flash('message', 'Added Successfully!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    return redirect()->route('manage_offer');
                                } else {
                                    $deals->delete();
                                    Session::flash('message', 'Added Failed!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('manage_offer');
                                }
                            } else{
                                Session::flash('message', 'Added Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('manage_offer');
                            }  
                        } else{
                            Session::flash('message', 'Added Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_offer');   
                        }
                    } else{
                        Session::flash('message', 'End Date After on Start Date!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return View::make('products.offers.add_offer')->with(array('products'=>$products, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
        		$page = "Offers";
        		$deals = Offers::where('id',$id)->first();
        		if($deals) {
        			$deals['subs'] = OffersSub::Where('offer', $deals->id)->Where('is_block', 1)->get();
        		}
        		return View::make("products.offers.view_offer")->with(array('deals'=>$deals, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Offers";
        		$deals = Offers::where('id',$id)->first();
        		$products = Products::where('is_block', 1)->get();
        		return View::make("products.offers.edit_offer")->with(array('deals'=>$deals, 'products'=>$products, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$data = Input::all();
        		$page = "Offers";
        		$products = Products::where('is_block', 1)->get();
        		$log = session()->get('user');
        		$id = Input::get('deals_id');
                $deals = '';
                if($id != '') {
                	$deals = Offers::Where('id', $id)->first();
                }

                if($deals) {
        	        $rules = array(
                        'offer_title'  => 'required',
                        'description'  => 'required',
                        'offer_type'   => 'nullable',
                        'x_pro_cnt'    => 'required|numeric',
                        'y_pro_cnt'    => 'required|numeric',
                        'offer_start'  => 'required',
                        'offer_end'    => 'required',
                        'grab_offer'   => 'required',
                        'image'        => 'nullable',

                        'product_id.*'   => 'required|exists:products,id',
                        'att_name.*'     => 'nullable|exists:attributes_fields,id',
                        'att_value.*'    => 'nullable|exists:attributes_settings,id',
                        'qty.*'          => 'required',
                        'offer_cost.*'        => 'required',
                        'offer_tax_amount.*'  => 'required',
                        'offer_price.*'       => 'required',
                        'type.*'         => 'required',
                    );

        	        $messages=[
        	            'offer_cat.required'=>'The Offer Category field is required.',
        	            'offer_start.required'=>'Start Date field is required.',
        	            'offer_end.required'=>'End Date field is required.',
        	            'x_pro_cnt.required'=>'X-Product Count field is required.',
        	            'x_pro_cnt.numeric'=>'X-Product Count field is only numbers.',
        	            'y_pro_cnt.required'=>'Y-Product Count field is required.',
        	            'y_pro_cnt.numeric'=>'Y-Product Count field is only numbers.',
        	            'qty.required'=>'Quantity field is required.',
        	        ];
        	        $validator = Validator::make(Input::all(), $rules,$messages);

                    if ($validator->fails()) {
                        return Redirect::to('/edit_offer/' . $id)->withErrors($validator)->with(array('products'=>$products, 'deals'=>$deals, 'page'=>$page));
                    } else {
                        $stt = date('Y-m-d', strtotime($data['offer_start']));
                        $end = date('Y-m-d', strtotime($data['offer_end']));

                        if($stt <= $end) {
                            $deals->offer_title   = $data['offer_title'];   

                            if($data['offer_type']) {
                                $deals->offer_type    = $data['offer_type'];  
                            } else {
                                $deals->offer_type    = NULL;  
                            } 

                            $deals->description   = $data['description'];    
                            $deals->x_pro_cnt     = $data['x_pro_cnt'];  
                            $deals->y_pro_cnt     = $data['y_pro_cnt'];  
                            $deals->offer_start   = date('Y-m-d H:i:s', strtotime($data['offer_start']));
                            $deals->offer_end     = date('Y-m-d H:i:s', strtotime($data['offer_end']));
                            $deals->grab_offer    = $data['grab_offer'];  
                            $deals->is_block      = 1;

                            $img_files = Input::file('image');
                            if(isset($img_files)) {
                                $file_name = $img_files->getClientOriginalName();
                                $date = date('M-Y');
                                // $file_path = '../public/images/offer_products/'.$date;
                                $file_path = 'images/offer_products/'.$date;
                                $img_files->move($file_path, $file_name);
                                $deals->image = $date.'/'.$file_name;
                            } else if (isset($data['old_image'])) {
                                $deals->image = $data['old_image'];
                            } else {
                                $deals->image = NULL;
                            }      
                            
                            if($deals->save()) {
                                if($data['product_id'] && count($data['product_id']) != 0) {
                                    OffersSub::where('offer', $deals->id)->delete();
                                    OfferStockManagements::where('offer', $deals->id)->delete();
                                    foreach ($data['product_id'] as $key => $value) {
                                        $off_sub = new OffersSub();

                                        $off_sub->offer       = $deals->id; 
                                        $off_sub->product_id  = $value;

                                        if(isset($data['att_name'][$key])) {
                                            $off_sub->att_name = $data['att_name'][$key];  
                                        } else {
                                            $off_sub->att_name = NULL;    
                                        }

                                        if(isset($data['att_value'][$key])) {
                                            $off_sub->att_value = $data['att_value'][$key];  
                                        } else {
                                            $off_sub->att_value = NULL;    
                                        }

                                        if(isset($data['qty'][$key])) {
                                            $off_sub->qty     = $data['qty'][$key];  
                                        } else {
                                            $off_sub->qty     = NULL;    
                                        }

                                        if(isset($data['type'][$key])) {
                                            $off_sub->type    = $data['type'][$key];     
                                        } else {
                                            $off_sub->type    = NULL;    
                                        }

                                        if($off_sub->type == 1) {
                                            if(isset($data['offer_cost'][$key])) {
                                                $off_sub->offer_cost     = $data['offer_cost'][$key];  
                                            } else {
                                                $off_sub->offer_cost     = 0.00;    
                                            }
                                        } else {
                                            $off_sub->offer_cost     = 0.00;
                                        }

                                        if($off_sub->type == 1) {
                                            if(isset($data['offer_tax_amount'][$key])) {
                                                $off_sub->offer_tax_amount     = $data['offer_tax_amount'][$key];  
                                            } else {
                                                $off_sub->offer_tax_amount     = 0.00;    
                                            }
                                        } else {
                                            $off_sub->offer_tax_amount     = 0.00;
                                        }

                                        if($off_sub->type == 1) {
                                            if(isset($data['offer_price'][$key])) {
                                                $off_sub->offer_price     = $data['offer_price'][$key];  
                                            } else {
                                                $off_sub->offer_price     = 0.00;    
                                            }
                                        } else {
                                            $off_sub->offer_price     = 0.00;
                                        }
                                        
                                        $off_sub->is_block    = 1;
                                        $off_sub->save();

                                        $off_stock = new OfferStockManagements();
                                        $off_stock->offer       = $deals->id; 
                                        $off_stock->product_id  = $value;

                                        if(isset($data['att_name'][$key])) {
                                            $off_stock->att_name = $data['att_name'][$key];  
                                        } else {
                                            $off_stock->att_name = NULL;    
                                        }

                                        if(isset($data['att_value'][$key])) {
                                            $off_stock->att_value = $data['att_value'][$key];  
                                        } else {
                                            $off_stock->att_value = NULL;    
                                        }

                                        if(isset($data['qty'][$key])) {
                                            $off_stock->offer_qty     = $data['qty'][$key];  
                                        } else {
                                            $off_stock->offer_qty     = NULL;    
                                        }

                                        $off_stock->date     = date('Y-m-d');   
                                        $off_stock->save();   
                                    }

                                    $offer_detail = OffersSub::Where('is_block', 1)->Where('offer', $deals->id)->get();
                                    if($offer_detail) {
                                        foreach ($offer_detail as $key => $value) {
                                            $stock = Products::Where('id', $value->product_id)->first();

                                            if($stock && ($stock->onhand_qty != 0)) {
                                                $old_qty = 0;
                                                if(isset($data['old_qty'][$key])) {
                                                    $old_qty = $data['old_qty'][$key];  
                                                } else {
                                                    $old_qty = 0;    
                                                }

                                                $stock->onhand_qty = ($stock->onhand_qty + $old_qty) - $value->qty;
                                                $stock->save();
                                                
                                                $p_atts = ProductsAttributes::Where('product_id', $value->product_id)->Where('attribute_name', $value->att_name)->Where('attribute_values', $value->att_value)->first();
                                                if($p_atts) {
                                                    $p_atts->att_qty = ($p_atts->att_qty + $old_qty) - $value->qty;
                                                    $p_atts->save();
                                                }
                                            }
                                        }
                                    }

                                    Session::flash('message', 'Update Successfully!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    return redirect()->route('manage_offer');
                                } else {
                                    $deals->delete();
                                    Session::flash('message', 'Update Failed!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('manage_offer');
                                }
                            } else{
                                Session::flash('message', 'Update Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('manage_offer');
                            }  
                        } else{
                            Session::flash('message', 'End Date After on Start Date!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return View::make('products.offers.add_offer')->with(array('products'=>$products, 'page'=>$page));
                        }       
                    }
                } else {
                	Session::flash('message', 'Updated Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_offer');
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
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$deals = Offers::where('id',$id)->first();
        				if($deals){
        					if($deals->delete()) {
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
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$deals = Offers::where('id',$value)->first();
        					if($deals){
        						if($deals->delete()) {
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

	public function StatusOffer ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$deals = '';
        		$msg = '';
            	if($id != '') {
                	$deals = Offers::Where('id', $id)->first();
                }

                if($deals) {
                	if($deals->is_block == 1) {
                    	$deals->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$deals->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($deals->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_offer');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_offer');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_offer');
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

	public function OfferBlock( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$deals = Offers::where('id',$value)->first();
        					if($deals){
        						$deals->is_block = 0;
        						$deals->save();
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

	public function OfferUnblock( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Offer')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$deals = Offers::where('id',$value)->first();
        					if($deals){
        						$deals->is_block = 1;
        						$deals->save();
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

    public function OfferStock () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Offer Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Offers";
                $log = session()->get('user');
                $co_id = [];

                if($log) {
                    $user= $log->id;
                    if($log->user_type == 1) {
                        $stock_trans = OfferStockManagements::OrderBy('id', 'DESC')->paginate(10);
                        return View::make("products.offers.manage_offer_stock")->with(array('stock_trans'=>$stock_trans, 'page'=>$page));
                    } elseif ($log->user_type == 2 || $log->user_type == 3) {
                        $stock_trans = array();
                        $stts = DB::table('offer_stock_managements as A')
                            ->leftjoin('products as B', 'B.id', '=', 'A.product_id')
                            ->leftjoin('users as C', 'C.id', '=', 'B.created_user')
                            ->select('A.id','B.id as p_id', 'C.id as u_id')
                            ->OrderBy('A.id', 'DESC')
                            ->where('B.created_user', '=', $log->id)
                            ->where('C.id', '=', $log->id)
                            ->whereIn('C.user_type', ['2','3'])
                            ->get();

                        if (sizeof($stts) != 0) {
                            foreach ($stts as $key => $value) {
                                array_push($co_id, $value->id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $stock_trans = OfferStockManagements::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                        }

                        return View::make("products.offers.manage_offer_stock")->with(array('stock_trans'=>$stock_trans, 'page'=>$page));
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

    public function ExportOfferStockCSV( Request $request) { 
        $error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Offer Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()) {
                    $ids = $request->ids;
                    $table = array();
                    $filename = "Inventory_offer_stock.csv";

                    if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            $table = OfferStockManagements::WhereIn('id', $ids)->get();
                            $filename = "Inventory_offer_stock.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') {
                        $table = OfferStockManagements::all();
                        $filename = "Inventory_offer_stock_all.csv";
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }

                    foreach ($table as $key => $value) {
                        if($value->offer) {
                            $table[$key]['offer'] = $value->Offers->offer_title;
                        } else {
                            $table[$key]['offer'] = "---------";
                        }

                        if($value->product_id) {
                            $table[$key]['p_code'] = $value->OfferProducts->product_code;
                            $table[$key]['product'] = $value->OfferProducts->product_title;
                        } else {
                            $table[$key]['p_code'] = "---------";
                            $table[$key]['product'] = "---------";
                        }

                        if($value->att_name) {
                            $table[$key]['att_name'] = $value->AttributeName->att_name;
                        } else {
                            $table[$key]['att_name'] = "---------";
                        }

                        if($value->att_value) {
                            $table[$key]['att_value'] = $value->AttributeValue->att_value;
                        } else {
                            $table[$key]['att_value'] = "---------";
                        }

                        if($value->offer_qty) {
                            $table[$key]['offer_qty'] = $value->offer_qty;
                        } else {
                            $table[$key]['offer_qty'] = 0;
                        }

                        if($value->date) {
                            $table[$key]['date'] = date('d-m-Y', strtotime($value->date));
                        } else {
                            $table[$key]['date'] = "---------";
                        }
                    }
                    
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('Offers', 'Product Code', 'Product', 'Attribute Name', 'Attribute Value', 'Offer Qty', 'Date'));

                    foreach($table as $row) {
                        fputcsv($handle, array($row['offer'], $row['p_code'], $row['product'], $row['att_name'], $row['att_value'], $row['offer_qty'], $row['date']));
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

    public function OfferTrans () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Offer Stock Transaction')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Offers";
                $log = session()->get('user');
                $co_id = [];

                if($log) {
                    $user= $log->id;
                    if($log->user_type == 1) {
                        $stock_trans = OfferTransaction::OrderBy('id', 'DESC')->paginate(10);
                        return View::make("products.offers.manage_offer_trans")->with(array('stock_trans'=>$stock_trans, 'page'=>$page));
                    } elseif ($log->user_type == 2 || $log->user_type == 3) {
                        $stock_trans = array();
                        $stts = DB::table('offer_transactions as A')
                            ->leftjoin('products as B', 'B.id', '=', 'A.product_id')
                            ->leftjoin('users as C', 'C.id', '=', 'B.created_user')
                            ->select('A.id','B.id as p_id', 'C.id as u_id')
                            ->OrderBy('A.id', 'DESC')
                            ->where('B.created_user', '=', $log->id)
                            ->where('C.id', '=', $log->id)
                            ->whereIn('C.user_type', ['2','3'])
                            ->get();

                        if (sizeof($stts) != 0) {
                            foreach ($stts as $key => $value) {
                                array_push($co_id, $value->id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $stock_trans = OfferTransaction::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                        }

                        return View::make("products.offers.manage_offer_trans")->with(array('stock_trans'=>$stock_trans, 'page'=>$page));
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

    public function ExportOfferTransCSV( Request $request) {
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Offer Stock Transaction')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()) {
                    $ids = $request->ids;
                    $table = array();
                    $filename = "Inventory_offer_trans.csv";

                    if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            $table = OfferTransaction::WhereIn('id', $ids)->get();
                            $filename = "Inventory_offer_trans.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') {
                        $table = OfferTransaction::all();
                        $filename = "Inventory_offer_trans_all.csv";
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }

                    foreach ($table as $key => $value) {
                        if($value->order_code) {
                            $table[$key]['order_code'] = $value->order_code;
                        } else {
                            $table[$key]['order_code'] = "---------";
                        }

                        if($value->offer) {
                            $table[$key]['offer'] = $value->Offers->offer_title;
                        } else {
                            $table[$key]['offer'] = "---------";
                        }

                        if($value->product_id) {
                            $table[$key]['p_code'] = $value->OfferProducts->product_code;
                            $table[$key]['product'] = $value->OfferProducts->product_title;
                        } else {
                            $table[$key]['p_code'] = "---------";
                            $table[$key]['product'] = "---------";
                        }

                        if($value->att_name) {
                            $table[$key]['att_name'] = $value->AttributeName->att_name;
                        } else {
                            $table[$key]['att_name'] = "---------";
                        }

                        if($value->att_value) {
                            $table[$key]['att_value'] = $value->AttributeValue->att_value;
                        } else {
                            $table[$key]['att_value'] = "---------";
                        }

                        if($value->previous_qty) {
                            $table[$key]['previous_qty'] = $value->previous_qty;
                        } else {
                            $table[$key]['previous_qty'] = 0;
                        }

                        if($value->current_qty) {
                            $table[$key]['current_qty'] = $value->current_qty;
                        } else {
                            $table[$key]['current_qty'] = 0;
                        }   

                        if($value->date) {
                            $table[$key]['date'] = date('d-m-Y', strtotime($value->date));
                        } else {
                            $table[$key]['date'] = "---------";
                        }
                    }
                    
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('Order Code', 'Offers', 'Product Code', 'Product', 'Attribute Name', 'Attribute Value', 'Previous On hand Qty', 'Current Qty', 'Date'));

                    foreach($table as $row) {
                        fputcsv($handle, array($row['order_code'], $row['offer'], $row['p_code'], $row['product'], $row['att_name'], $row['att_value'], $row['previous_qty'], $row['current_qty'], $row['date']));
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
