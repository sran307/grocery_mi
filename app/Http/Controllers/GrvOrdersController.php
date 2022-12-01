<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orders;
use App\OrderDetails;
use App\OrdersTransactions;
use App\Products;
use App\ProductsAttributes;
use App\StockManagement;
use App\SubStock;
use App\StockTransactions;
use App\User;
use App\ShippingAddress;
use App\NoimageSettings;
use App\CityManagement;
use App\CountriesManagement;
use App\StateManagements;
use App\ReturnOrder;
use App\ReturnOrderDetails;
use App\GrvOrders;
use App\GrvOrdersDetails;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;
use Hash;

class GrvOrdersController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function GRVOrders () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'GRV Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $sess = session()->get('user');
                $orders = ''; 
                $co_id = []; 
                $co_ids = []; 
                
                if($sess) {
                    if($sess->user_type == 1) {
                        $orders = GRVOrders::OrderBy('id', 'DESC')->paginate(10);
                        if(sizeof($orders) != 0) {
                            foreach ($orders as $key => $value) {
                                $det = GrvOrdersDetails::Where('grv_id', $value->id)->get(); 
                                if(sizeof($det) != 0) {
                                    $orders[$key]->{'details'} = $det;
                                } else {
                                    $orders[$key]->{'details'} =  '';
                                }
                            }
                        }
                    } else if($sess->user_type == 2 || $sess->user_type == 3) {
                        $ords = DB::table('grv_orders as A')
                            ->leftjoin('grv_orders_details as B', 'A.id', '=', 'B.grv_id')
                            ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                            ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                            ->select('A.id as grv_id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                            ->OrderBy('A.id', 'DESC')
                            ->where('C.created_user', '=', $sess->id)
                            ->where('D.id', '=', $sess->id)
                            ->whereIn('D.user_type', ['2','3'])
                            ->GroupBy('B.grv_id')
                            ->get();

                        if (sizeof($ords) != 0) {
                            foreach ($ords as $key => $value) {
                                array_push($co_id, $value->grv_id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $orders = GRVOrders::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                            if(sizeof($orders) != 0) {
                                foreach ($orders as $key => $value) {
                                    $det = GrvOrdersDetails::Where('grv_id', $value->id)->get(); 
                                    if(sizeof($det)) {
                                        $orders[$key]->{'details'} = $det;
                                    } else {
                                        $orders[$key]->{'details'} =  '';
                                    }
                                }
                            }
                        }
                    }
                }

                return View::make("transaction.orders.grv_orders")->with(array('orders'=>$orders, 'page'=>$page));
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

    public function CreateGRVOrders ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'GRV Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Transaction";
            	$sess = session()->get('user');
                $orders = ''; 
                $co_id = []; 
                $co_ids = []; 
                
                if($sess) {
                    if($sess->user_type == 1) {
                        $orders = ReturnOrder::Where('id', $id)->first();
                        if($orders) {
                            $det = ReturnOrderDetails::Where('return_order_id', $orders->id)->Where('order_returned', 'No')->Where('status', 'Accept')->get(); 
                            if(sizeof($det) != 0) {
                                $orders->{'details'} = $det;
                            } else {
                                $orders->{'details'} =  '';
                            }
                        }
                    } else if($sess->user_type == 2 || $sess->user_type == 3) {
                        $ords = DB::table('return_orders as A')
                            ->leftjoin('return_order_details as B', 'A.id', '=', 'B.return_order_id')
                            ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                            ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                            ->select('A.id as ro_id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                            ->Where('A.id', $id)
                            ->where('C.created_user', '=', $sess->id)
                            ->where('D.id', '=', $sess->id)
                            ->where('B.order_returned', '=', 'No')
                            ->whereIn('D.user_type', ['2','3'])
                            ->GroupBy('B.return_order_id')
                            ->first();

                        if ($ords) {
                            $orders = ReturnOrder::Where('id', $ords->ro_id)->first();
        	                if($orders) {
        	                    $det = ReturnOrderDetails::Where('return_order_id', $orders->id)->get(); 
        	                    if(sizeof($det) != 0) {
        	                        $orders->{'details'} = $det;
        	                    } else {
        	                        $orders->{'details'} =  '';
        	                    }
        	                }
                        }
                    }
                }
            	return View::make('transaction.orders.create_grv_orders')->with(array('page'=>$page, 'orders'=>$orders));
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

    public function StoreGRVOrders(Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'GRV Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Transaction";
        		$log = session()->get('user');
            	$data = Input::all();
            	$grv = new GrvOrders();

                if($grv) {
        	        $rules = array(
        	            'grv_code'              => 'nullable',
        	            'order_id'              => 'required|exists:orders,id',
        	            'return_order_id'       => 'required|exists:return_orders,id',
        	            'grv_remarks'           => 'required',
        	            'grv_status'            => 'nullable',

        	            'rtn_odr_det_id.*'      => 'required|exists:return_order_details,id',
        	            'return_type.*'         => 'required',
        	            'return_qty.*'          => 'required',
        	            'return_amount.*'       => 'required',
                        // 'return_tax_amount.*'   => 'required',
        	            'reason.*'              => 'required',
        	            'remarks.*'             => 'required',
        	            'rtn_image.*'           => 'nullable',
        	        );
        	        $validator = Validator::make(Input::all(), $rules);

        	        if ($validator->fails()) {
        	        	return Redirect::to('/create_grv_orders/' . $data['return_order_id'])->withErrors($validator)->with(array('page'=>$page));
        	        } else {
        	        	$max = GrvOrders::max('grv_code');
        	            $max_id = "0001";
        	        	$max_st = "grv";
        	            if(($max)) {
        	            	$max_no = substr($max, 3);
        	            	$increment = (int)$max_no + 1;
        	            	$data['grv_code']    = $max_st.sprintf("%04d", $increment);
        	            } else {
        	            	$data['grv_code']    = $max_st.$max_id;
        	            }

        	            $grv->grv_code           = $data['grv_code'];
        	            $grv->order_id           = $data['order_id'];
        	            $grv->return_order_id    = $data['return_order_id'];
        	            $grv->grv_remarks        = $data['grv_remarks'];
        	            $grv->grv_status         = 1;	 
                        
                        if($grv->save()) {
                            $orders = Orders::Where('id', $grv->order_id)->first();
        					$sck = 0;

        		            if($data['rtn_odr_det_id'] && count($data['rtn_odr_det_id']) != 0) {
        		            	/*Details Add*/
        		            	foreach ($data['rtn_odr_det_id'] as $key => $value) {
        		            		$return_dets = ReturnOrderDetails::Where('id', $value)->first();

        		            		$grv_dets = new GrvOrdersDetails();

        		            		$grv_dets->grv_id          = $grv->id; 
        		            		$grv_dets->rtn_odr_det_id  = $value;
        		            		$grv_dets->product_id      = $return_dets->product_id;
        		            		$grv_dets->product_title   = $return_dets->product_title;
        		            		$grv_dets->att_name        = $return_dets->att_name;
        		            		$grv_dets->att_value       = $return_dets->att_value;
        		            		$grv_dets->tax             = $return_dets->tax;
        		            		$grv_dets->tax_type        = $return_dets->tax_type;
        		            		$grv_dets->order_qty       = $return_dets->order_qty;
        		            		$grv_dets->unitprice       = $return_dets->unitprice;
        		            		$grv_dets->totalprice      = $return_dets->totalprice;
                                    $grv_dets->tax_amount      = $return_dets->tax_amount;

        		            		if(isset($data['return_type'][$key])) {
        		            			$grv_dets->return_type  = $data['return_type'][$key];	 
        		            		} else {
        		            			$grv_dets->return_type  = NULL;	 
        		            		}

        		            		if(isset($data['return_qty'][$key])) {
        		            			$grv_dets->return_qty  = $data['return_qty'][$key];	 
        		            		} else {
        		            			$grv_dets->return_qty  = 0;	 
        		            		}

        		            		if(isset($data['return_amount'][$key])) {
        		            			if (preg_match('/^[0-9.]*$/', $data['return_amount'][$key])) {
        		            				$grv_dets->return_amount = $data['return_amount'][$key];	 
        								} else {
        		            				$grv_dets->return_amount = round($return_dets->totalprice * $data['return_qty'][$key], 2);
        								}
        		            		} else {
        		            			$grv_dets->return_amount = 0.00;	 
        		            		}

                                    /*if(isset($data['return_tax_amount'][$key])) {
                                        if (preg_match('/^[0-9.]*$/', $data['return_tax_amount'][$key])) {
                                            $grv_dets->return_tax_amount = $data['return_tax_amount'][$key];     
                                        } else {
                                            $grv_dets->return_tax_amount = round((($return_dets->totalprice * $return_dets->tax)/100) * $data['return_qty'][$key], 2);
                                        }
                                    } else {
                                        $grv_dets->return_tax_amount = 0.00;     
                                    }*/

        		            		if(isset($data['reason'][$key])) {
        		            			$grv_dets->reason  = $data['reason'][$key];	 
        		            		} else {
        		            			$grv_dets->reason  = NULL;	 
        		            		}

        		            		if(isset($data['remarks'][$key])) {
        		            			$grv_dets->remarks = $data['remarks'][$key];	 
        		            		} else {
        		            			$grv_dets->remarks = NULL;	 
        		            		}

        		            		if(isset($data['rtn_image'][$key])) {
        	            				$file_name = $data['rtn_image'][$key]->getClientOriginalName();
        			                    $date = date('M-Y');
        			                    $file_path = 'images/return_order_image/'.$date;
        			                    $data['rtn_image'][$key]->move($file_path, $file_name);

        			                    $grv_dets->rtn_image       = $date.'/'.$file_name;
        		            		} else if (isset($data['old_rtn_image'][$key])) {
        			                    $grv_dets->rtn_image       = $data['old_rtn_image'][$key];
        		            		} else {
        			                    $grv_dets->rtn_image       = NULL;
        		            		}

                    				$grv_dets->save();

                    				if($grv_dets) {
                                        $return_dets->assign_qty = $return_dets->assign_qty + $grv_dets->return_qty;
                                        if($return_dets->return_qty == $return_dets->assign_qty) {
                                            $return_dets->order_returned = 'Yes';
                                        }
                                        $return_dets->save();
                    					/*Stock Management*/
                    					$product = Products::Where('id', $return_dets->product_id)->first();

                    					if($product) {
        	            					$stock_trans = new StockTransactions();
                                            $stock_trans->order_code   = $orders->order_code;
                                            $stock_trans->product_id   = $return_dets->product_id;
                                            $stock_trans->att_name     = $return_dets->att_name;
                                            $stock_trans->att_value    = $return_dets->att_value;
                                            $stock_trans->previous_qty = $product->onhand_qty;
                                            $stock_trans->current_qty  = $product->onhand_qty + $grv_dets->return_qty;
                                            $stock_trans->date         = date('Y-m-d');
                                            $stock_trans->remarks      = $return_dets->product_title.' is order Returned.';

                                            $p_atts = ProductsAttributes::Where('product_id', $return_dets->product_id)->Where('attribute_name', $return_dets->att_name)->Where('attribute_values', $return_dets->att_value)->first();
                                            if($p_atts) {
                                                $stock_trans->att_previous_qty = $p_atts->att_qty;
                                                $stock_trans->att_current_qty  = $p_atts->att_qty + $grv_dets->return_qty;
                                                
        	                                    $st_mgnt = new StockManagement();
        	            						$st_mgnt->product_id   = $return_dets->product_id;
        	            						$st_mgnt->previous_qty = $product->onhand_qty;
        	            						$st_mgnt->addon_qty    = $grv_dets->return_qty;
        	            						$st_mgnt->current_qty  = $product->onhand_qty + $grv_dets->return_qty;
        	            						$st_mgnt->date         = date('Y-m-d');
        	            						$st_mgnt->created_user = $log->id;
        	            						$st_mgnt->is_block     = 1;
        		            					if($st_mgnt->save()) {
        		            						$sub_stk = new SubStock();
        		            						$sub_stk->product_id   = $return_dets->product_id;
        		            						$sub_stk->attribute    = $p_atts->id;
        		            						$sub_stk->stock        = $st_mgnt->id;
        		            						$sub_stk->previous_qty = $p_atts->att_qty;
        		            						$sub_stk->addon_qty    = $grv_dets->return_qty;
        		            						$sub_stk->current_qty  = $p_atts->att_qty + $grv_dets->return_qty;
        		            						$sub_stk->date         = date('Y-m-d');
        		            						$sub_stk->save();
        		            					}

        		            					$p_atts->att_qty = $p_atts->att_qty + $grv_dets->return_qty;
        		            					$p_atts->save();
                                            }

                                            $product->onhand_qty = $product->onhand_qty + $grv_dets->return_qty;

                                            if($product->save() && $stock_trans->save()) {
                                                $sck = 1;
                                            }
                    					}
        				            }		            	
        				        }
        		            } else {
        	            		$grv->delete();
        		            	Session::flash('message', 'GRV Orders Created Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('grv_orders');
        		            }

                            $orders = Orders::Where('id', $grv->order_id)->first();
                            $ret_dets = ReturnOrderDetails::Where('return_order_id', $grv->return_order_id)->get();
                            if($ret_dets->contains('order_returned', 'No')){
                                $orders->return_order_status =  1;
                            } else {
                                $orders->return_order_status =  2;
                            }
                            $orders->save();

        		            Session::flash('message', 'GRV Orders Created Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('grv_orders');
        	            } else {
        	            	Session::flash('message', 'GRV Orders Created Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('grv_orders');
        	            }	            
        	        }
                } else {
                	Session::flash('message', 'Create GRV Not Possible!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('grv_orders');
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
                ->where('B.module_name', '=', 'GRV Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
        		$orders = GRVOrders::where('id',$id)->first();
        		if($orders) {
        			$orders['details'] = GrvOrdersDetails::Where('grv_id', $orders->id)->get();
        		}
        		return View::make("transaction.orders.view_grv_orders")->with(array('orders'=>$orders, 'page'=>$page));
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

	public function GRVStsOrders( Request $request) {	
		$id = 0;
		$status = 0;
		$error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'GRV Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id) && isset($request->status)){
        			$id = $request->id;
        			$status = $request->status;
        			if($id != 0) {
        				$orders = GRVOrders::where('id',$id)->first();
        				if($orders) {
                            $orders->grv_status = $status;

        					if($orders->save()) {
        						Session::flash('message', 'Status Changed Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						$error = 0;
        					} else {
        						Session::flash('message', 'Status Changed Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        						$error = 1;
        					}
        				}	else {
        					Session::flash('message', 'Status Changed Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        					$error = 1;
        				}			
        			} else {
        				Session::flash('message', 'Status Changed Failed!'); 
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

    public function edit ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'GRV Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $orders = GRVOrders::where('id',$id)->first();
                if($orders) {
                    $orders->{'details'} = GrvOrdersDetails::Where('grv_id', $orders->id)->get();
                }
                return View::make("transaction.orders.edit_grv_orders")->with(array('orders'=>$orders, 'page'=>$page));
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
                ->where('B.module_name', '=', 'GRV Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $log = session()->get('user');
                $id = Input::get('grv_id');
                $data = Input::all();
                $grv = '';
                if($id != '') {
                    $grv = GrvOrders::where('id',$id)->first();
                }

                if($grv) {
                    $rules = array(
                        'grv_code'          => 'nullable',
                        'order_id'          => 'required|exists:orders,id',
                        'return_order_id'   => 'required|exists:return_orders,id',
                        'grv_remarks'       => 'required',
                        'grv_status'        => 'nullable',

                        'rtn_odr_det_id.*'  => 'required|exists:return_order_details,id',
                        'return_type.*'     => 'required',
                        'return_qty.*'      => 'required',
                        'return_amount.*'   => 'required',
                        'reason.*'          => 'required',
                        'remarks.*'         => 'required',
                        'rtn_image.*'       => 'nullable',
                    );
                    $validator = Validator::make(Input::all(), $rules);

                    if ($validator->fails()) {
                        return Redirect::to('/edit_grv_orders/' . $id)->withErrors($validator)->with(array('page'=>$page));
                    } else {
                        $grv->order_id           = $data['order_id'];
                        $grv->return_order_id    = $data['return_order_id'];
                        $grv->grv_remarks        = $data['grv_remarks'];
                        $grv->grv_status         = $data['grv_status'];    
                        
                        if($grv->save()) {
                            $orders = Orders::Where('id', $grv->order_id)->first();

                            if($data['rtn_odr_det_id'] && count($data['rtn_odr_det_id']) != 0) {
                                /*Details Add*/
                                GrvOrdersDetails::Where('grv_id', $grv->id)->delete();
                                foreach ($data['rtn_odr_det_id'] as $key => $value) {
                                    $grv_dets = new GrvOrdersDetails();

                                    $grv_dets->grv_id          = $grv->id; 
                                    $grv_dets->rtn_odr_det_id  = $value;

                                    if(isset($data['product_id'][$key])) {
                                        $grv_dets->product_id  = $data['product_id'][$key];    
                                    } else {
                                        $grv_dets->product_id  = NULL;  
                                    }

                                    if(isset($data['product_title'][$key])) {
                                        $grv_dets->product_title  = $data['product_title'][$key];    
                                    } else {
                                        $grv_dets->product_title  = NULL;  
                                    }

                                    if(isset($data['att_name'][$key])) {
                                        $grv_dets->att_name  = $data['att_name'][$key];    
                                    } else {
                                        $grv_dets->att_name  = NULL;  
                                    }

                                    if(isset($data['att_value'][$key])) {
                                        $grv_dets->att_value  = $data['att_value'][$key];    
                                    } else {
                                        $grv_dets->att_value  = NULL;  
                                    }

                                    if(isset($data['tax'][$key])) {
                                        $grv_dets->tax  = $data['tax'][$key];    
                                    } else {
                                        $grv_dets->tax  = NULL;  
                                    }

                                    if(isset($data['tax_type'][$key])) {
                                        $grv_dets->tax_type  = $data['tax_type'][$key];    
                                    } else {
                                        $grv_dets->tax_type  = NULL;  
                                    }

                                    if(isset($data['order_qty'][$key])) {
                                        $grv_dets->order_qty  = $data['order_qty'][$key];    
                                    } else {
                                        $grv_dets->order_qty  = NULL;  
                                    }

                                    if(isset($data['unitprice'][$key])) {
                                        $grv_dets->unitprice  = $data['unitprice'][$key];    
                                    } else {
                                        $grv_dets->unitprice  = NULL;  
                                    }

                                    if(isset($data['totalprice'][$key])) {
                                        $grv_dets->totalprice  = $data['totalprice'][$key];    
                                    } else {
                                        $grv_dets->totalprice  = NULL;  
                                    }

                                    if(isset($data['return_type'][$key])) {
                                        $grv_dets->return_type  = $data['return_type'][$key];    
                                    } else {
                                        $grv_dets->return_type  = NULL;  
                                    }

                                    if(isset($data['return_qty'][$key])) {
                                        $grv_dets->return_qty  = $data['return_qty'][$key];  
                                    } else {
                                        $grv_dets->return_qty  = NULL;   
                                    }

                                    if(isset($data['return_amount'][$key])) {
                                        if (preg_match('/^[0-9.]*$/', $data['return_amount'][$key])) {
                                            $grv_dets->return_amount = $data['return_amount'][$key];     
                                        } else {
                                            $grv_dets->return_amount = 0.00;
                                        }
                                    } else {
                                        $grv_dets->return_amount = 0.00;     
                                    }

                                    if(isset($data['reason'][$key])) {
                                        $grv_dets->reason  = $data['reason'][$key];  
                                    } else {
                                        $grv_dets->reason  = NULL;   
                                    }

                                    if(isset($data['remarks'][$key])) {
                                        $grv_dets->remarks = $data['remarks'][$key];     
                                    } else {
                                        $grv_dets->remarks = NULL;   
                                    }

                                    if(isset($data['grv_issued'][$key])) {
                                        $grv_dets->grv_issued = $data['grv_issued'][$key];     
                                    } else {
                                        $grv_dets->grv_issued = NULL;   
                                    }

                                    if(isset($data['rtn_image'][$key])) {
                                        $file_name = $data['rtn_image'][$key]->getClientOriginalName();
                                        $date = date('M-Y');
                                        $file_path = 'images/return_order_image/'.$date;
                                        $data['rtn_image'][$key]->move($file_path, $file_name);

                                        $grv_dets->rtn_image       = $date.'/'.$file_name;
                                    } else if (isset($data['old_rtn_image'][$key])) {
                                        $grv_dets->rtn_image       = $data['old_rtn_image'][$key];
                                    } else {
                                        $grv_dets->rtn_image       = NULL;
                                    }

                                    $grv_dets->save();

                                    if(isset($data['old_return_qty'][$key])) {
                                        $old_return_qty = $data['old_return_qty'][$key];
                                    } else {
                                        $old_return_qty = 0;
                                    }

                                    if($grv_dets) {
                                        /*Stock Management*/
                                        $product = Products::Where('id', $grv_dets->product_id)->first();
                                        if($product) {
                                            $stock_trans = new StockTransactions();
                                            $stock_trans->order_code   = $orders->order_code;
                                            $stock_trans->product_id   = $grv_dets->product_id;
                                            $stock_trans->att_name     = $grv_dets->att_name;
                                            $stock_trans->att_value    = $grv_dets->att_value;
                                            $stock_trans->previous_qty = $product->onhand_qty - $old_return_qty;
                                            $stock_trans->current_qty  = ($product->onhand_qty - $old_return_qty) + $grv_dets->return_qty;
                                            $stock_trans->date         = date('Y-m-d');
                                            $stock_trans->remarks      = $grv_dets->product_title.' is order Returned.';

                                            $p_atts = ProductsAttributes::Where('product_id', $grv_dets->product_id)->Where('attribute_name', $grv_dets->att_name)->Where('attribute_values', $grv_dets->att_value)->first();
                                            if($p_atts) {
                                                $stock_trans->att_previous_qty = $p_atts->att_qty - $old_return_qty;
                                                $stock_trans->att_current_qty  = ($p_atts->att_qty - $old_return_qty) + $grv_dets->return_qty;
                                                
                                                $st_mgnt = new StockManagement();
                                                $st_mgnt->product_id   = $grv_dets->product_id;
                                                $st_mgnt->previous_qty = $product->onhand_qty - $old_return_qty;
                                                $st_mgnt->addon_qty    = $grv_dets->return_qty;
                                                $st_mgnt->current_qty  = ($product->onhand_qty - $old_return_qty) + $grv_dets->return_qty;
                                                $st_mgnt->date         = date('Y-m-d');
                                                $st_mgnt->created_user = $log->id;
                                                $st_mgnt->is_block     = 1;
                                                if($st_mgnt->save()) {
                                                    $sub_stk = new SubStock();
                                                    $sub_stk->product_id   = $grv_dets->product_id;
                                                    $sub_stk->attribute    = $p_atts->id;
                                                    $sub_stk->stock        = $st_mgnt->id;
                                                    $sub_stk->previous_qty = $p_atts->att_qty - $old_return_qty;
                                                    $sub_stk->addon_qty    = $grv_dets->return_qty;
                                                    $sub_stk->current_qty  = ($p_atts->att_qty - $old_return_qty) + $grv_dets->return_qty;
                                                    $sub_stk->date         = date('Y-m-d');
                                                    $sub_stk->save();
                                                }

                                                $p_atts->att_qty = ($p_atts->att_qty - $old_return_qty) + $grv_dets->return_qty;
                                                $p_atts->save();
                                            }

                                            $product->onhand_qty = ($product->onhand_qty - $old_return_qty) + $grv_dets->return_qty;

                                            if($product->save() && $stock_trans->save()) {
                                                $sck = 1;
                                            }
                                        }
                                    }                       
                                }
                            } else {
                                // $grv->delete();
                                Session::flash('message', 'GRV Orders Updated Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('grv_orders');
                            }

                            $ret_dets = ReturnOrderDetails::Where('return_order_id', $grv->return_order_id)->get();
                            if($ret_dets->contains('order_returned', 'No')){
                                $orders->return_order_status =  1;
                            } else {
                                $orders->return_order_status =  2;
                            }
                            $orders->save();

                            Session::flash('message', 'GRV Orders Updated Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            return redirect()->route('grv_orders');
                        } else {
                            Session::flash('message', 'GRV Orders Updated Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('grv_orders');
                        }               
                    }
                } else {
                    Session::flash('message', 'Update GRV Not Possible!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('grv_orders');
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

    public function ExportCSV( Request $request) {  
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'GRV Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()){
                    $ids = $request->ids;
                    $table = array();
                    $filename = "GRVOrders.csv";
                    $user = session()->get('user');

                    if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            if($user) {
                                $table = GRVOrders::whereIn('id',$ids)->get();
                            } else {
                                echo $error = 1;die();
                            }
                            $filename = "GRVOrders.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') {
                        if($user) {
                            if ($user->user_type == 1) {
                                $table = GRVOrders::all();
                            } else if ($user->user_type == 2 || $user->user_type == 3) {
                                $co_id=[];
                                $ords = DB::table('grv_orders as A')
                                    ->leftjoin('grv_orders_details as B', 'A.id', '=', 'B.grv_id')
                                    ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                                    ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                                    ->select('A.id as g_id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                                    ->OrderBy('A.id', 'DESC')
                                    ->where('C.created_user', '=', $user->id)
                                    ->where('D.id', '=', $user->id)
                                    ->whereIn('D.user_type', ['2','3'])
                                    ->GroupBy('B.grv_id')
                                    ->get(); 

                                if (sizeof($ords) != 0) {
                                    foreach ($ords as $key => $value) {
                                        array_push($co_id, $value->g_id);
                                    }
                                }

                                if (sizeof($co_id) != 0) {
                                    $table = GRVOrders::WhereIn('id', $co_id)->get();
                                    if(sizeof($table) != 0) {
                                        foreach ($table as $key => $value) {
                                            $det = GrvOrdersDetails::Where('grv_id', $value->id)->get(); 
                                            if(sizeof($det)) {
                                                $table[$key]->{'details'} = $det;
                                            } else {
                                                $table[$key]->{'details'} =  '';
                                            }
                                        }
                                    }
                                }
                            } else {
                                echo $error = 1;die();
                            }
                        } else {
                            echo $error = 1;die();
                        }
                        $filename = "All_GRVOrders.csv";
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }

                    foreach ($table as $key => $value) {
                        $table_det = GrvOrdersDetails::where('grv_id',$value->id)->get();

                        if($value->grv_code) {
                            $table[$key]['grv_code'] = $value->grv_code;
                        } else {
                            $table[$key]['grv_code'] = "---------";
                        }

                        if($value->created_at) {
                            $table[$key]['grv_date'] = date('d-m-Y', strtotime($value->created_at));
                        } else {
                            $table[$key]['grv_date'] = "---------";
                        }   

                        if ($value->grv_status == 1) {
                            $table[$key]['grv_status'] = "GRV Opened";
                        } elseif ($value->grv_status == 2) {
                            $table[$key]['grv_status'] = "GRV Closed";
                        } else {
                            $table[$key]['grv_status'] = "---------";
                        }

                        if($value->grv_remarks) {
                            $table[$key]['grv_remarks'] = $value->grv_remarks;
                        } else {
                            $table[$key]['grv_remarks'] = "---------";
                        }

                        if($value->return_order_id) {
                            if($value->ReOrders->order_code) {
                                $table[$key]['order_code'] = $value->ReOrders->order_code;
                            } else {
                                $table[$key]['order_code'] = "---------";
                            }
                        } else {
                            $table[$key]['order_code'] = "---------";
                        }

                        if($value->return_order_id) {
                            if($value->ReOrders->order_date) {
                                $table[$key]['order_date'] = date('d-m-Y', strtotime($value->ReOrders->order_date));
                            } else {
                                $table[$key]['order_date'] = "---------";
                            }
                        } else {
                            $table[$key]['order_date'] = "---------";
                        }

                        if($value->return_order_id) {
                            if($value->ReOrders->return_date) {
                                $table[$key]['return_date'] = date('d-m-Y', strtotime($value->ReOrders->return_date));
                            } else {
                                $table[$key]['return_date'] = "---------";
                            }
                        } else {
                            $table[$key]['return_date'] = "---------";
                        }

                        if($value->order_id) {
                            if($value->Orders->return_order_status == 1) {
                                $table[$key]['return_order_status'] = "Order Return Initialized";
                            } elseif ($value->Orders->return_order_status == 2) {
                                $table[$key]['return_order_status'] = "Order Return Confirmed";
                            } elseif ($value->Orders->return_order_status == 3) {
                                $table[$key]['return_order_status'] = "Order Return Cancelled";
                            } else {
                                $table[$key]['return_order_status'] = "---------";
                            }
                        } else {
                            $table[$key]['return_order_status'] = "---------";
                        }

                        if($value->order_id) {
                            if($value->Orders->contact_person) {
                                $table[$key]['contact_person'] = $value->Orders->contact_person;
                            } else {
                                $table[$key]['contact_person'] = "---------";
                            }
                        } else {
                            $table[$key]['contact_person'] = "---------";
                        }

                        if($value->order_id) {
                            if($value->Orders->contact_no) {
                                $table[$key]['contact_no'] = $value->Orders->contact_no;
                            } else {
                                $table[$key]['contact_no'] = "---------";
                            }
                        } else {
                            $table[$key]['contact_no'] = "---------";
                        }

                        if($value->order_id) {
                            if($value->Orders->shipping_address) {
                                $table[$key]['shipping_address'] = $value->Orders->shipping_address;
                            } else {
                                $table[$key]['shipping_address'] = "---------";
                            }
                        } else {
                            $table[$key]['shipping_address'] = "---------";
                        }

                        $odr = "---------";
                        if(sizeof($table_det) != 0) {
                            $odr="";
                            $atts="";
                            foreach ($table_det as $keyz => $valuez) {
                                if(isset($valuez->att_name) && $valuez->att_name != 0) {
                                    if(isset($valuez->AttName->att_name) && isset($valuez->AttValue->att_value)) {
                                        $atts='('.$valuez->AttName->att_name.' : '.$valuez->AttValue->att_value.')';
                                    }
                                }

                                $odr= 'Product Title : '.$valuez->product_title.' '.$atts.', Product Add : '.$valuez->Products->Creatier->first_name.' '.$valuez->Products->Creatier->last_name.', Price : Rs.'.$valuez->unitprice.', Qty : '.$valuez->order_qty.', Tax : '.$valuez->tax.'%, Total Price : '.$valuez->totalprice.', Return Type : '.$valuez->return_type.', Return Qty : '.$valuez->return_qty.', Return Amount : '.$valuez->return_amount.', GRV Issued : '.$valuez->grv_issued.', Reason : '.$valuez->reason.', Remarks : '.$valuez->remarks.', ';                              
                            }
                            $table[$key]['odr'] = $odr;
                        } else {
                            $table[$key]['odr'] = $odr;
                        }
                    }
                    
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('GRV Code', 'GRV Date', 'GRV Status', 'GRV Remarks', 'Order Code', 'Order Date', 'Return Date', 'Return Order Status', 'Contact Person', 'Contact Number', 'Shipping Address', 'GRV Order Deatils'));

                    foreach($table as $row) {
                        fputcsv($handle, array($row['grv_code'], $row['grv_date'], $row['grv_status'], $row['grv_remarks'], $row['order_code'], $row['order_date'], $row['return_date'], $row['return_order_status'], $row['contact_person'], $row['contact_no'], $row['shipping_address'], $row['odr']));
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
