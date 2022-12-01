<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StockManagement;
use App\SubStock;
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

class StockManagementController extends Controller
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
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Products";
            	$log = session()->get('user');
            	if($log) {
            		if($log->user_type == 1) {
        		        $stock = StockManagement::join('products','products.id','stock_managements.product_id')->orderBy('date', 'DESC')->where('products.is_block',1)->select('stock_managements.*')->paginate(10);
        		        if(sizeof($stock) != 0) {
        		        	foreach ($stock as $key => $value) {
        			        	$sub_stock = SubStock::Where('stock', $value->id)->get();
        			        	if(sizeof($sub_stock) != 0) {
        		        			$stock[$key]->{'sub_stock'} = $sub_stock;
        			        	} else {
        		        			$stock[$key]->{'sub_stock'} = array();
        			        	}
        		        	}
        		        }
        		    	return View::make("products.stock.manage_stock")->with(array('stock'=>$stock, 'page'=>$page));
        	    	} elseif ($log->user_type == 2 || $log->user_type == 3) {
        	    		$stock = StockManagement::Where('created_user', $log->id)->orderBy('date', 'DESC')->paginate(10);
        	    		if(sizeof($stock) != 0) {
        		        	foreach ($stock as $key => $value) {
        			        	$sub_stock = SubStock::Where('stock', $value->id)->get();
        			        	if(sizeof($sub_stock) != 0) {
        		        			$stock[$key]->{'sub_stock'} = $sub_stock;
        			        	} else {
        		        			$stock[$key]->{'sub_stock'} = array();
        			        	}
        		        	}
        		        }
        		    	return View::make("products.stock.manage_stock")->with(array('stock'=>$stock, 'page'=>$page));
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

    public function Filter ($filter) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Products";
                $log = session()->get('user');
                if($log) {
                    if($log->user_type == 1) {
                        if($filter == 'damage') {
                            $stock = StockManagement::Where('is_damage', "Yes")->orderBy('date', 'DESC')->paginate(10);
                        } else if($filter == 'add_stock') {
                            $stock = StockManagement::Where('is_damage', "No")->orderBy('date', 'DESC')->paginate(10);
                        } else {
                            $stock = StockManagement::orderBy('date', 'DESC')->paginate(10);
                        }

                        // $stock = StockManagement::orderBy('date', 'DESC')->paginate(10);
                        if(sizeof($stock) != 0) {
                            foreach ($stock as $key => $value) {
                                $sub_stock = SubStock::Where('stock', $value->id)->get();
                                if(sizeof($sub_stock) != 0) {
                                    $stock[$key]->{'sub_stock'} = $sub_stock;
                                } else {
                                    $stock[$key]->{'sub_stock'} = array();
                                }
                            }
                        }
                        return View::make("products.stock.manage_stock")->with(array('stock'=>$stock, 'page'=>$page));
                    } elseif ($log->user_type == 2 || $log->user_type == 3) {
                        $stock = StockManagement::Where('created_user', $log->id)->orderBy('date', 'DESC')->paginate(10);
                        if(sizeof($stock) != 0) {
                            foreach ($stock as $key => $value) {
                                $sub_stock = SubStock::Where('stock', $value->id)->get();
                                if(sizeof($sub_stock) != 0) {
                                    $stock[$key]->{'sub_stock'} = $sub_stock;
                                } else {
                                    $stock[$key]->{'sub_stock'} = array();
                                }
                            }
                        }
                        return View::make("products.stock.manage_stock")->with(array('stock'=>$stock, 'page'=>$page));
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

    public function SubStock ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Products";
            	$log = session()->get('user');
            	if($log) {
            		if($log->user_type == 1) {
            			$stock = StockManagement::where('id',$id)->first();
            			if($stock) {
        		        	$sub_stock = SubStock::Where('stock', $stock->id)->get();
            			}
        		    	return View::make("products.stock.manage_substock")->with(array('sub_stock'=>$sub_stock, 'page'=>$page));
        	    	} elseif ($log->user_type == 2 || $log->user_type == 3) {
        	    		$stock = StockManagement::Where('created_user', $log->id)->orderBy('date', 'DESC')->paginate(10);
        	    		if(sizeof($stock) != 0) {
        		        	foreach ($stock as $key => $value) {
        			        	$sub_stock = SubStock::Where('stock', $value->id)->get();
        			        	if(sizeof($sub_stock) != 0) {
        		        			$stock[$key]->{'sub_stock'} = $sub_stock;
        			        	} else {
        		        			$stock[$key]->{'sub_stock'} = array();
        			        	}
        		        	}
        		        }
        		    	return View::make("products.stock.manage_substock")->with(array('stock'=>$stock, 'page'=>$page));
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

    public function SelectQty( Request $request) {	
		$p_id = 0;
		$error = array('error' => 2);
		$data = "";

		if($request->ajax() && isset($request->p_id)){
			$p_id = $request->p_id;
            $type = $request->type;
			
			$product = Products::where('id',$p_id)->first();
			if($product) {
				$attr = ProductsAttributes::where('product_id', $product->id)->get();
				if(sizeof($attr) != 0) {
					$tr_data = "";
					foreach ($attr as $key => $value) {
						$tr_data.= '<tr>
							<td>
								<input type="hidden" name="product_id[]" value="'.$value->product_id.'">
								<span class="gj_ssto_span">'.$product->product_title.'</span>
							</td>
							<td>
								<input type="hidden" name="attribute[]" value="'.$value->id.'">
								<span class="gj_ssto_span">'.$value->AttributeName->att_name .' - '. $value->AttributeValue->att_value.'</span>
							</td>
							<td>
								<input type="hidden" name="current_qty[]" value="'.$value->att_qty.'">
								<span class="gj_ssto_span">'.$value->att_qty.'</span>
							</td>
							<td>
								<input type="number" name="addon_qty[]">
							</td>
						</tr>';
					}

					$product->{'attr'} = $attr;

                    if($type == "damage_qty") {
                        $type = "Damage Qty";
                    } else {
                        $type = "Add On Qty";
                    }

					if ($tr_data != "") {
						$data = '<table class="table table-stripped table-bordered gj_tab_ssto">
	                        <thead>
	                            <tr>
	                                <th>Product</th>
	                                <th>Attribute</th>
	                                <th>Current Qty</th>
	                                <th>'.$type.'</th>
	                            </tr>
	                        </thead>
	                        <tbody id="gj_ssto_bdy">
	                        	'.$tr_data.'
	                        </tbody>
	                    </table>'; 
						
						$error = array('error' => 0, 'product' => $product, 'data' => $data);
					} else {
						$error = array('error' => 1, 'product' => $product, 'data' => $data);
					}
				} else {
					$error = array('error' => 1, 'product' => $product, 'data' => $data);
				}

				// $error = $product;
				// $product = json_encode($product);
			}
		}

		echo json_encode($error);
	}

    public function create () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Products";
            	return View::make('products.stock.add_stock')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Products";
            	$log = session()->get('user');
            	$data = Input::all();
            	// print_r($data['product_id'][0]);
            	// die();

            	$rules = array(
                    'product_id'     => 'required',
                    'previous_qty'   => 'nullable',
                    'current_qty'    => 'required',
                    'addon_qty'      => 'required',
                    'attribute'      => 'nullable',
                    'stock'          => 'nullable',
                    'date'           => 'nullable',
                    'created_user'   => 'nullable',
                    'modified_user'  => 'nullable',
                    'is_block'       => 'nullable',
                );

                $messages=[
                    'current_qty.required' => 'The current quantity field is required.',
                    'addon_qty.required'   => 'The addon quantity field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            	   	return View::make('products.stock.add_stock')->withErrors($validator)->with(array('page'=>$page));
                } else {
                	$stock = new StockManagement();

                	if(isset($data['attribute']) && sizeof($data['attribute']) != 0) {
                		if($stock) {
        	        		if(sizeof($data['product_id']) != 0){
        	        			$stock->product_id = $data['product_id'][0];
        	        		} else {
        	        			$stock->product_id = NULL;
        	        		}

        	        		if(sizeof($data['current_qty']) != 0){
        	        			$stock->previous_qty = array_sum($data['current_qty']);
        	        		} else {
        	        			$stock->previous_qty = 0;
        	        		}

        	        		if(sizeof($data['current_qty']) != 0 && sizeof($data['addon_qty']) != 0){
        	        			$stock->current_qty = array_sum($data['current_qty']) + array_sum($data['addon_qty']);
        	        		} else {
        	        			$stock->current_qty = 0;
        	        		}

        	        		if(sizeof($data['addon_qty']) != 0){
        	        			$stock->addon_qty = array_sum($data['addon_qty']);
        	        		} else {
        	        			$stock->addon_qty = 0;
        	        		}

        	        		$stock->date          =  date('Y-m-d');
                    		if($log) {
        		            	$stock->created_user = $log->id;	            
        		            } else {
        		            	$stock->created_user =  1;
        		            }

        		            $stock->is_block      =  1;

        	                if($stock->save()) {
        	                	foreach ($data['attribute'] as $key => $value) {
        		        			$attr = ProductsAttributes::Where('id', $value)->first();

        		            		if(isset($data['current_qty'][$key]) && isset($data['addon_qty'][$key])) {
        		            			$attr->att_qty  = $data['current_qty'][$key] + $data['addon_qty'][$key];
        		            			$attr->save();
        		            		}

        		            		$sub_stock = new SubStock();

        		            		if(isset($data['product_id'][$key])) {
        		            			$sub_stock->product_id  = $data['product_id'][$key];	 
        		            		} else {
        		            			$sub_stock->product_id  = NULL;	 
        		            		}

        		            		if(isset($data['attribute'][$key])) {
        		            			$sub_stock->attribute  = $data['attribute'][$key];	 
        		            		} else {
        		            			$sub_stock->attribute  = NULL;	 
        		            		}

        		            		if(isset($data['current_qty'][$key])) {
        		            			$sub_stock->previous_qty  = $data['current_qty'][$key];	 
        		            		} else {
        		            			$sub_stock->previous_qty  = 0;	 
        		            		}

        		            		if(isset($data['addon_qty'][$key])) {
        		            			$sub_stock->addon_qty  = $data['addon_qty'][$key];	 
        		            		} else {
        		            			$sub_stock->addon_qty  = 0;	 
        		            		}

        		            		if(isset($data['addon_qty'][$key]) && isset($data['current_qty'][$key])) {
        		            			$sub_stock->current_qty  = $data['addon_qty'][$key] + $data['current_qty'][$key];	 
        		            		} else {
        		            			$sub_stock->current_qty  = 0;	 
        		            		}

        		            		$sub_stock->stock         =  $stock->id;
        		            		$sub_stock->date          =  date('Y-m-d');
        				            $sub_stock->save();
        		        		}

        	                	$product = Products::Where('id', $stock->product_id)->first();
        	                	if ($product) {
        		                	$product->onhand_qty = $stock->current_qty;

        		                	if ($product->save()) {
        				                Session::flash('message', 'Added Successfully!'); 
        								Session::flash('alert-class', 'alert-success');
        								return redirect()->route('manage_stock');
        		                	} else {
        		                		$stock->delete();
        		                		Session::flash('message', 'Added Failed!'); 
        								Session::flash('alert-class', 'alert-danger');
        				                return redirect()->route('manage_stock');
        		                	}
        	                	} else {
        	                		$stock->delete();
        	                		Session::flash('message', 'Added Failed!'); 
        							Session::flash('alert-class', 'alert-danger');
        			                return redirect()->route('manage_stock');
        	                	}
        		            } else{
        		            	Session::flash('message', 'Added Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('manage_stock');
        		            }  
                		} else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_stock');
        	            } 
                	} else {
        	            if($stock) {
        		            $stock->product_id    =  $data['product_id'];
        		            $stock->previous_qty   = $data['current_qty'];
        		            $stock->current_qty   =  $data['current_qty'] + $data['addon_qty'];
        		            $stock->addon_qty     =  $data['addon_qty'];
        		            $stock->date          =  date('Y-m-d');

        		            if($log) {
        		            	$stock->created_user = $log->id;	            
        		            } else {
        		            	$stock->created_user =  1;
        		            }

        		            $stock->is_block      =  1;

        	                if($stock->save()) {
        	                	$product = Products::Where('id', $stock->product_id)->first();
        	                	if ($product) {
        		                	$product->onhand_qty = $stock->current_qty;

        		                	if ($product->save()) {
        				                Session::flash('message', 'Added Successfully!'); 
        								Session::flash('alert-class', 'alert-success');
        								return redirect()->route('manage_stock');
        		                	} else {
        		                		$stock->delete();
        		                		Session::flash('message', 'Added Failed!'); 
        								Session::flash('alert-class', 'alert-danger');
        				                return redirect()->route('manage_stock');
        		                	}
        	                	} else {
        	                		$stock->delete();
        	                		Session::flash('message', 'Added Failed!'); 
        							Session::flash('alert-class', 'alert-danger');
        			                return redirect()->route('manage_stock');
        	                	}
        		            } else{
        		            	Session::flash('message', 'Added Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('manage_stock');
        		            }  
        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_stock');
        	            }
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

    public function Damagecreate () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Products";
                return View::make('products.stock.damage_stock')->with(array('page'=>$page));
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

    public function Damagestore(Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Products";
                $log = session()->get('user');
                $data = Input::all();
                // print_r($data['product_id'][0]);
                // die();

                $rules = array(
                    'product_id'     => 'required',
                    'previous_qty'   => 'nullable',
                    'current_qty'    => 'required',
                    'addon_qty'      => 'required',
                    'attribute'      => 'nullable',
                    'stock'          => 'nullable',
                    'date'           => 'nullable',
                    'created_user'   => 'nullable',
                    'modified_user'  => 'nullable',
                    'is_block'       => 'nullable',
                );

                $messages=[
                    'current_qty.required' => 'The current quantity field is required.',
                    'addon_qty.required'   => 'The damage quantity field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                    return View::make('products.stock.damage_stock')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $stock = new StockManagement();

                    if(isset($data['attribute']) && sizeof($data['attribute']) != 0) {
                        if($stock) {
                            if(sizeof($data['product_id']) != 0){
                                $stock->product_id = $data['product_id'][0];
                            } else {
                                $stock->product_id = NULL;
                            }

                            if(sizeof($data['current_qty']) != 0){
                                $stock->previous_qty = array_sum($data['current_qty']);
                            } else {
                                $stock->previous_qty = 0;
                            }

                            if(sizeof($data['current_qty']) != 0 && sizeof($data['addon_qty']) != 0){
                                $stock->current_qty = array_sum($data['current_qty']) - array_sum($data['addon_qty']);
                            } else {
                                $stock->current_qty = 0;
                            }

                            if(sizeof($data['addon_qty']) != 0){
                                $stock->addon_qty = '-'.array_sum($data['addon_qty']);
                            } else {
                                $stock->addon_qty = 0;
                            }

                            $stock->date          =  date('Y-m-d');
                            if($log) {
                                $stock->created_user = $log->id;                
                            } else {
                                $stock->created_user =  1;
                            }

                            $stock->is_damage = "Yes";

                            $stock->is_block      =  1;

                            if($stock->save()) {
                                foreach ($data['attribute'] as $key => $value) {
                                    $attr = ProductsAttributes::Where('id', $value)->first();

                                    if(isset($data['current_qty'][$key]) && isset($data['addon_qty'][$key])) {
                                        $attr->att_qty  = $data['current_qty'][$key] - $data['addon_qty'][$key];
                                        $attr->save();
                                    }

                                    $sub_stock = new SubStock();

                                    if(isset($data['product_id'][$key])) {
                                        $sub_stock->product_id  = $data['product_id'][$key];     
                                    } else {
                                        $sub_stock->product_id  = NULL;  
                                    }

                                    if(isset($data['attribute'][$key])) {
                                        $sub_stock->attribute  = $data['attribute'][$key];   
                                    } else {
                                        $sub_stock->attribute  = NULL;   
                                    }

                                    if(isset($data['current_qty'][$key])) {
                                        $sub_stock->previous_qty  = $data['current_qty'][$key];  
                                    } else {
                                        $sub_stock->previous_qty  = 0;   
                                    }

                                    if(isset($data['addon_qty'][$key])) {
                                        $sub_stock->addon_qty  = '-'.$data['addon_qty'][$key];   
                                    } else {
                                        $sub_stock->addon_qty  = 0;  
                                    }

                                    if(isset($data['addon_qty'][$key]) && isset($data['current_qty'][$key])) {
                                        $sub_stock->current_qty  = $data['current_qty'][$key] - $data['addon_qty'][$key];    
                                    } else {
                                        $sub_stock->current_qty  = 0;    
                                    }

                                    $sub_stock->stock         =  $stock->id;
                                    $sub_stock->date          =  date('Y-m-d');
                                    $sub_stock->save();
                                }

                                $product = Products::Where('id', $stock->product_id)->first();
                                if ($product) {
                                    $product->onhand_qty = $stock->current_qty;

                                    if ($product->save()) {
                                        Session::flash('message', 'Added Successfully!'); 
                                        Session::flash('alert-class', 'alert-success');
                                        return redirect()->route('manage_stock');
                                    } else {
                                        $stock->delete();
                                        Session::flash('message', 'Added Failed!'); 
                                        Session::flash('alert-class', 'alert-danger');
                                        return redirect()->route('manage_stock');
                                    }
                                } else {
                                    $stock->delete();
                                    Session::flash('message', 'Added Failed!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('manage_stock');
                                }
                            } else{
                                Session::flash('message', 'Added Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('manage_stock');
                            }  
                        } else{
                            Session::flash('message', 'Added Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_stock');
                        } 
                    } else {
                        if($stock) {
                            $stock->product_id    =  $data['product_id'];
                            $stock->previous_qty   = $data['current_qty'];
                            $stock->current_qty   =  $data['current_qty'] - $data['addon_qty'];
                            $stock->addon_qty     =  '-'.$data['addon_qty'];
                            $stock->date          =  date('Y-m-d');

                            if($log) {
                                $stock->created_user = $log->id;                
                            } else {
                                $stock->created_user =  1;
                            }

                            $stock->is_damage = "Yes";
                            $stock->is_block      =  1;
                            if($stock->save()) {
                                $product = Products::Where('id', $stock->product_id)->first();
                                if ($product) {
                                    $product->onhand_qty = $stock->current_qty;

                                    if ($product->save()) {
                                        Session::flash('message', 'Added Successfully!'); 
                                        Session::flash('alert-class', 'alert-success');
                                        return redirect()->route('manage_stock');
                                    } else {
                                        $stock->delete();
                                        Session::flash('message', 'Added Failed!'); 
                                        Session::flash('alert-class', 'alert-danger');
                                        return redirect()->route('manage_stock');
                                    }
                                } else {
                                    $stock->delete();
                                    Session::flash('message', 'Added Failed!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('manage_stock');
                                }
                            } else{
                                Session::flash('message', 'Added Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('manage_stock');
                            }  
                        } else{
                            Session::flash('message', 'Added Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_stock');
                        }
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

	public function edit ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Products";
        		$stock = StockManagement::where('id',$id)->first();
        		return View::make("products.stock.edit_stock")->with(array('stock'=>$stock, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Products";
        		$id = Input::get('stock_id');
        		$log = session()->get('user');
                $stock = '';
                if($id != '') {
                	$stock = StockManagement::Where('id', $id)->first();
                }

                if($stock) {
        			$rules = array(
        	            'product_id'     => 'required|exists:products,id',
        	            'current_qty'    => 'required|integer',
        	            'addon_qty'      => 'nullable|integer',
        	            'date'           => 'required',
        	            'created_user'   => 'nullable',
        	            'modified_user'  => 'nullable',
        	            'is_block'       => 'nullable',
        	        );
        	        $validator = Validator::make(Input::all(), $rules);

        	        if ($validator->fails()) {
        	    	   	return Redirect::to('/edit_stock/' . $id)->withErrors($validator)->with(array('stock'=>$stock, 'page'=>$page));
        	        } else {
        	            $data = Input::all();
        	            
        	            $stock->product_id    =  $data['product_id'];
        	            // $stock->current_qty   =  $data['current_qty'];
        	            if($data['addon_qty']) { 
        	            	$stock->addon_qty     =  $stock->addon_qty + $data['addon_qty'];
        	            } else {
        	            	$stock->addon_qty     =  $stock->addon_qty + 0;
        	            }
        	            $stock->date          =  $data['date'];
        	            if($log) {
        	            	$stock->modified_user      = $log->id;	            
        	            } else {
        	            	$stock->modified_user      = 1;	            
        	            } 
        	            $stock->is_block      =  1;

        	            Session::flash('message', 'Update Not Posssible!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_stock');

        	            /*if($stock->save()) {
        	            	$product = Products::Where('id', $stock->product_id)->first();
        	            	if ($product) {
        	                	$product->onhand_qty = $stock->current_qty + $stock->addon_qty;

        	                	if ($product->save()) {
        			                Session::flash('message', 'Updated Successfully!'); 
        							Session::flash('alert-class', 'alert-success');
        							return redirect()->route('manage_stock');
        	                	} else {
        	                		Session::flash('message', 'Updated Failed!'); 
        							Session::flash('alert-class', 'alert-danger');
        			                return redirect()->route('manage_stock');
        	                	}
        	            	} else {
        	            		Session::flash('message', 'Updated Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('manage_stock');
        	            	}
        	            } else{
        	            	Session::flash('message', 'Updated Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_stock');
        	            }  */
        	        }
                } else{
                	Session::flash('message', 'Updated Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_stock');
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
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$stock = StockManagement::where('id',$id)->first();
        				if($stock){
        					$product = Products::Where('id', $stock->product_id)->first();

        					if($stock->delete()) {
        	                	if ($product) {
        		                	$product->onhand_qty = $product->onhand_qty - $stock->addon_qty;
        		                	$product->save();
        	                	}
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
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$stock = StockManagement::where('id',$value)->first();
        					if($stock){
        						$product = Products::Where('id', $stock->product_id)->first();

        						if($stock->delete()) {
        		                	if ($product) {
        			                	$product->onhand_qty = $product->onhand_qty - $stock->addon_qty;
        			                	$product->save();
        		                	}
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

	public function StatusStock ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$stock = '';
        		$msg = '';
            	if($id != '') {
                	$stock = StockManagement::Where('id', $id)->first();
                }

                if($stock) {
                	if($stock->is_block == 1) {
                    	$stock->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$stock->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($stock->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_stock');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_stock');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_stock');
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

	public function StockBlock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$stock = StockManagement::where('id',$value)->first();
        					if($stock){
        						$stock->is_block = 0;
        						$stock->save();
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

	public function StockUnblock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$stock = StockManagement::where('id',$value)->first();
        					if($stock){
        						$stock->is_block = 1;
        						$stock->save();
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

	public function ExportStockCSV( Request $request) {	
		$error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Inventory Stock Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax()) {
        			$ids = $request->ids;
        			$table = array();
        			$filename = "Inventory.csv";

        			if(isset($ids) && $ids) {
        				if(sizeof($ids) != 0) {
                            $table = StockManagement::WhereIn('id', $ids)->get();
                            $filename = "Inventory.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
        			} else if(isset($request->type) && $request->type == 'export_all') {
        				$table = StockManagement::all();
        				$filename = "Inventory_all.csv";
        			} else {
        				Session::flash('message', 'CSV Export Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				die();
        			}

        			foreach ($table as $key => $value) {
        				if($value->product_id) {
        					$table[$key]['p_code'] = $value->Products->product_code;
        				} else {
        					$table[$key]['p_code'] = "---------";
        				}

        				if($value->product_id) {
        					$table[$key]['product'] = $value->Products->product_title;
        				} else {
        					$table[$key]['product'] = "---------";
        				}

        				if($value->date) {
        					$table[$key]['date'] = date('d-m-Y', strtotime($value->date));
        				} else {
        					$table[$key]['date'] = "---------";
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

        				if($value->addon_qty) {
        					$table[$key]['addon_qty'] = $value->addon_qty;
        				} else {
        					$table[$key]['addon_qty'] = 0;
        				} 

        				$s_sck = "---------";
        				if($value->id) {
        					$ss = SubStock::Where('stock', $value->id)->get();
        					if(sizeof($ss) != 0) {
        						$s_sck="";
        						foreach ($ss as $skey => $svalue) {
        							$s_sck.= 'Products : '.$svalue->Products->product_title.', Attributes : '.$svalue->Attribute->AttributeName->att_name.' - '.$svalue->Attribute->AttributeValue->att_value.', Previous Qty : '.$svalue->previous_qty.', Current Qty : '.$svalue->current_qty.', Add On Qty : '.$svalue->addon_qty.', ';  							
        						}
        					}
        					$table[$key]['sub_stock'] = $s_sck;
        				} else {
        					$table[$key]['sub_stock'] = $s_sck;
        				}
        			}
        	    	
        		    $handle = fopen($filename, 'w+');
        		    fputcsv($handle, array('Product Code', 'Product', 'Add on Date', 'Previous On hand Quantity', 'Current Quantity', 'Add on Quantity', 'Sub Stocks'));

        		    foreach($table as $row) {
        		        fputcsv($handle, array($row['p_code'], $row['product'], $row['date'], $row['previous_qty'], $row['current_qty'], $row['addon_qty'], $row['sub_stock']));
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

	public function SearchInvStock(Request $request) {
        $page = "Products";

        $start_date = Input::get('gj_srh_srt_date');
        if($start_date) {
            $start_date = date('Y-m-d', strtotime($start_date));
        }

        $end_date = Input::get('gj_srh_end_date');
        if($end_date) {
            $end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
        }

        $pro_code = Input::get('gj_srh_pro_code');

        if($start_date && $end_date && $pro_code) {
            $stock = StockManagement::OrderBy('date', 'DESC')->whereBetween('date', [$start_date, $end_date])->whereHas('Products', function($query) use ($pro_code) {
        			$query->where('product_title', 'like', '%' . $pro_code . '%');
			})->paginate(10);

            if($stock && sizeof($stock) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("products.stock.manage_stock")->with(array('stock'=>$stock, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('manage_stock');
            }
        } elseif($start_date && $end_date) {
            $stock = StockManagement::OrderBy('date', 'DESC')->whereBetween('date', [$start_date, $end_date])->paginate(10);
            if($stock && sizeof($stock) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("products.stock.manage_stock")->with(array('stock'=>$stock, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('manage_stock');
            }
        } elseif($pro_code) {
            $stock = StockManagement::OrderBy('date', 'DESC')->whereHas('Products', function($query) use ($pro_code) {
        			$query->where('product_title', 'like', '%' . $pro_code . '%');
			})->paginate(10);

            if($stock && sizeof($stock) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("products.stock.manage_stock")->with(array('stock'=>$stock, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('manage_stock');
            }
        } else {
            Session::flash('message', 'Search Items Not Found!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_stock');
        }
    }
}
