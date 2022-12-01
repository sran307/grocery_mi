<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Orders;
// use App\OrderDetails;
// use App\OrdersTransactions;
// use App\Products;
// use App\User;
// use App\ShippingAddress;
// use App\NoimageSettings;
// use App\CityManagement;
// use App\CountriesManagement;
// use App\StateManagements;

// use Collective\Html\HtmlFacade;
// use Illuminate\Support\Facades\Validator;
// use Response;
// use Input;
// use DB;
// use View;
// use Session;
// use Redirect;
// use URL;

class /*OrdersController*/ extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function AllOrders () {
        $page = "Courier Tracking";
        $orders = Orders::paginate(10);
    	return View::make("transaction.orders.all_orders")->with(array('orders'=>$orders, 'page'=>$page));
    }

    public function edit ($id) {
        $page = "Courier Tracking";
        $orders = Orders::where('id',$id)->first();
        if($orders) {
            $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
            $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
            $orders['products'] = Products::Where('is_block', 1)->get();
        }
        return View::make("transaction.orders.edit_orders")->with(array('orders'=>$orders, 'page'=>$page));
    }

	public function update (Request $request) {
		$page = "Courier Tracking";
        $id = Input::get('orders_id');
    	$orders = '';
        if($id != '') {
        	$orders = Orders::where('id',$id)->first();
        	if($orders) {
				$orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
				$orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
				$orders['products'] = Products::Where('is_block', 1)->get();
			}
        }

        if($orders) {
			$rules = array(
	            'payment_mode'           => 'required',
	            'delivery_date'          => 'nullable',
	            'order_status'           => 'required',
	            'total_items'            => 'nullable',
	            'discount_flag'          => 'nullable',
	            'discount'               => 'nullable',
	            'shipping_charge'        => 'nullable',
	            'net_amount'             => 'nullable',
	            'payment_status'         => 'nullable',
	            'delivery_status'        => 'nullable',
	            'remarks'                => 'required',
	            'is_block'               => 'nullable',

	            'det_total_items'        => 'nullable',
	            'det_net_amount'         => 'nullable',
	            'det_shipping_charge'    => 'nullable',
	            'det_order_id'           => 'nullable',
	            'det_product_id'         => 'nullable',
	            'det_product_title'      => 'required',
	            'det_order_qty'          => 'required',
	            'det_unitprice'          => 'nullable',
	            'det_totalprice'         => 'nullable',
	        );

	        $messages=[
                'det_product_title.required'=>'The Products Title field is required.',
                'det_order_qty.required'=>'The Quantity field is required.',
            ];
            $validator = Validator::make(Input::all(), $rules,$messages);

	        if ($validator->fails()) {
	    	   	return Redirect::to('/edit_orders/' . $id)->withErrors($validator)->with(array('orders'=>$orders, 'page'=>$page));
	        } else {
	        	$sus2 = 0;
	        	$sus3 = 0;

	            $data = Input::all();
	            $orders = Orders::where('id',$id)->first();
	            $orders->payment_mode     = $data['payment_mode'];	            
	            $orders->delivery_date    = $data['delivery_date'];	            
	            $orders->order_status     = $data['order_status'];	            
	            $orders->total_items      = $data['det_total_items'];	            
	            $orders->discount_flag    = $data['discount_flag'];	            
	            $orders->discount         = $data['discount'];	            
	            $orders->shipping_charge  = $data['det_shipping_charge'];	            
	            $orders->net_amount       = $data['det_net_amount'];	            
	            $orders->payment_status   = $data['payment_status'];	            
	            $orders->delivery_status  = $data['delivery_status'];	            
	            $orders->remarks          = $data['remarks'];	            
	            $orders->is_block         = 1;

                if($orders->save()) {
	            	if (isset($data['det_product_id']) && count($data['det_product_id']) != 0) {
	            		OrderDetails::Where('order_id', $orders->id)->delete();
                        foreach ($data['det_product_id'] as $key => $value) {
                            $order_details = new OrderDetails();
                            $order_details->order_id = $orders->id;
                            $order_details->product_id = $value;
                            
                            if(isset($data['det_product_title'][$key])) {
                                $order_details->product_title = $data['det_product_title'][$key];
                            } else {
                                $order_details->product_title = NULL;
                            }
                            
                            if(isset($data['det_order_qty'][$key])) {
                                $order_details->order_qty = $data['det_order_qty'][$key];
                            } else {
                                $order_details->order_qty = NULL;
                            }

                            if(isset($data['det_unitprice'][$key])) {
                                $order_details->unitprice = $data['det_unitprice'][$key];
                            } else {
                                $order_details->unitprice = NULL;
                            }

                            if(isset($data['det_totalprice'][$key])) {
                                $order_details->totalprice = $data['det_totalprice'][$key];
                            } else {
                                $order_details->totalprice = NULL;
                            }
                            
                            $order_details->is_block = 1;

                            if($order_details->save()) {
                                $sus2 = 1;
                            }                                
                        }                            
                    }

                    if($data['payment_mode'] == 1) {
                    	$order_trans = OrdersTransactions::Where('order_id', $orders->id)->first();
                    	if($order_trans) {
                			$order_trans->order_id = $orders->id;
	                        $order_trans->net_amount = $orders->net_amount;
	                        $order_trans->amountpaid = NULL;
	                        $order_trans->paymentmode = $data['payment_mode'];
	                        $order_trans->gatewaytransactionid = NULL;
	                        $order_trans->trans_status = "Pending";
	                        $order_trans->remarks = NULL;
	                        $order_trans->is_block = 1;

	                        if($order_trans->save()) {
	                            $sus3 = 1;
	                        }
                    	} else {
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
	                        $order_trans->trans_date = date('Y-m-d');
	                        $order_trans->order_id = $orders->id;
	                        $order_trans->net_amount = $orders->net_amount;
	                        $order_trans->amountpaid = NULL;
	                        $order_trans->paymentmode = $data['payment_mode'];
	                        $order_trans->gatewaytransactionid = NULL;
	                        $order_trans->trans_status = "Pending";
	                        $order_trans->remarks = NULL;
	                        $order_trans->is_block = 1;

	                        if($order_trans->save()) {
	                            $sus3 = 1;
	                        }
                        }
                    }

                    if($sus2 == 1 && $sus3 == 1) {
                        $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                        $admin_email = "teamadsdev5@gmail.com";
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
                        $site_name = "InterCambiar";
                        if($general){
                            $site_name = $general->site_name;
                        } else {
                            $site_name = "InterCambiar";
                        }

                        $customer_name = "";
                        $contact = "";
                        $address = "";
                        $order_code = $orders->order_code;
                        $order_date = date('d-m-Y', strtotime($orders->order_date));
                        $net_tot = $orders->net_amount;
                        $details = "";
                        $order_detail = OrderDetails::Where('is_block', 1)->Where('order_id', $orders->id)->get();

                        if($order_detail) {
                            foreach ($order_detail as $key => $value) {
                                $details.= '<tr>
                                    <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: center;"> '.$value->product_title.'</td>
                                    <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: center;"> '.$value->order_qty.'</td>
                                    <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: center;"> '.$value->unitprice.'</td>
                                    <td style="font-size: 14px;font-weight: bold;color: white;border: 1px solid white;text-align: right;"> '.$value->totalprice.'</td>
                                </tr>';
                            }
                        }

                        $user = User::Where('id', $orders->user_id)->Where('is_block', 1)->first();
                        if($orders->shipping_address_flag == 1) {
                       		$ship = ShippingAddress::Where('user_id', $orders->user_id)->first();
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

                        if (mail($to1,$subject,$message,$headers) && mail($to2,$subject,$message,$headers)) {
                            Session::flash('message', 'Update & Mail Send Successfully!');
                            Session::flash('alert-class', 'alert-success');
                            return redirect()->route('all_orders');
                        } else {
                            Session::flash('message', 'Update Successfully, but Mail Send Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('all_orders');
                        }
                    } else {
                        Orders::where('id', $order->id)->delete();
                        Session::flash('message', 'Update Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('all_orders');
                    }
                } else {
                    Session::flash('message', 'Update Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('all_orders');
                } 
	        }
        } else{
        	Session::flash('message', 'Update Failed!'); 
			Session::flash('alert-class', 'alert-danger');
            return redirect()->route('all_orders');
        }
	}

	public function view ($id) {
        $page = "Courier Tracking";
		$orders = Orders::where('id',$id)->first();
		if($orders) {
			$orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
		}
		return View::make("transaction.orders.view_orders")->with(array('orders'=>$orders, 'page'=>$page));
	}

	public function CheckTax( Request $request) {	
		$id = 0;
		$price = 0;
		$error = 0;
		if($request->ajax() && isset($request->id) && isset($request->price)){
			$id = $request->id;
			$price = $request->price;
			if($id != 0 && $price != 0) {
				$products = Products::where('id',$id)->first();
				if($products) {
					$tax = $products->tax;
					$tax_type = $products->tax_type;

					if($tax_type == 2) {
			          $calc_tax = (($price * $tax)/100);
			          $price = $price + $calc_tax;
			        }
			        $error = $price;
				}	else {
					$error = 0;
				}			
			} else {
				$error = 0;
			}

			echo $error;
		}
	}

	public function DeleteOrderDetails( Request $request) {	
		$id = 0;
		$error = 0;
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			if($id != 0) {
				if(OrderDetails::where('id',$id)->delete()) {
					$error = 1;
				}	else {
					$error = 0;
				}			
			} else {
				$error = 0;
			}

			echo $error;
		}
	}

	public function SearchProducts( Request $request) {	
		$id = 0;
		$result = array();
		$table = "";
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			$error = 1;
			$product_path = 'images/featured_products';
			$noimage = NoimageSettings::first();
			$noimage_path = 'images/noimage';

			if($id != 0) {
				$products = Products::where('id',$id)->first();
				if($products) {
					if($products->featured_product_img){
						$image = '<img src="'.asset($product_path.'/'.$products->featured_product_img).'" alt="'.$products->product_title.'" class="img-responsive gj_cge_det_prod_img">'; 
					} else {
						$image = '<img src="'.asset($noimage_path.'/'.$noimage->product_no_image).'" alt="No Images" class="img-responsive gj_cge_det_prod_img">'; 
					}
					$table = '<table class="table table-hover gj_cge_det_tbl">
						<thead>
							<tr>
								<th>Product Title</th>
								<th>Images</th>
								<th>Apply</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>'.$products->product_title.'</td>
								<td>'.$image.'</td>
								<td><button type="button" class="btn btn-info gj_aly_det_btn" data-apply-id="'.$products->id.'">Apply</button></td>
							</tr>
						</tbody>
					</table>';
					$result = array('table' => $table, 'error' => '0');
				}	else {
					$result = array('table' => $table, 'error' => '1');
				}			
			} else {
				$result = array('table' => $table, 'error' => '1');
			}

			echo json_encode($result);
		}
	}

	public function ApplyProducts( Request $request) {	
		$id = 0;
		$result = array();
		$table = "";
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			$error = 1;
			$product_path = 'images/featured_products';
			$noimage = NoimageSettings::first();
			$noimage_path = 'images/noimage';

			if($id != 0) {
				$products = Products::where('id',$id)->first();
				if($products) {
					if($products->featured_product_img){
						$image = '<img src="'.asset($product_path.'/'.$products->featured_product_img).'" alt="'.$products->product_title.'" class="img-responsive gj_cge_det_prod_img">'; 
					} else {
						$image = '<img src="'.asset($noimage_path.'/'.$noimage->product_no_image).'" alt="No Images" class="img-responsive gj_cge_det_prod_img">'; 
					}

					$table = '<td>
                        <input type="hidden" name="det_product_id[]" class="det_product_id" value="'.$products->id.'" placeholder="Enter Product ID">

                        <input type="text" name="det_product_title[]" class="det_product_title" value="'.$products->product_title.'" placeholder="Enter Product Title">
                    </td>

                    <td>
                        <input type="number" name="det_order_qty[]" class="det_order_qty" value="1" placeholder="Enter Quantity" min="1">
                    </td>

                    <td>
                        <input type="text" name="det_h_unitprice[]" class="det_h_unitprice" value="'.$products->discounted_price.'" placeholder="Enter Price" disabled>

                        <input type="hidden" name="det_unitprice[]" class="det_unitprice" value="'.$products->discounted_price.'" placeholder="Enter Price">
                    </td>

                    <td>
                        <input type="text" name="det_h_totalprice[]" class="det_h_totalprice" value="'.$products->discounted_price.'" placeholder="Enter Total Price" disabled>

                        <input type="hidden" name="det_totalprice[]" class="det_totalprice" value="'.$products->discounted_price.'" placeholder="Enter Total Price">

                        <input type="hidden" name="det_service_charge[]" class="gj_det_sc" value="'.$products->service_charge.'">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger gj_del_det" data-del-id=""><i class="fa fa-trash"></i></button>
                    </td>';
					$result = array('table' => $table, 'error' => '0');
				}	else {
					$result = array('table' => $table, 'error' => '1');
				}			
			} else {
				$result = array('table' => $table, 'error' => '1');
			}

			echo json_encode($result);
		}
	}

	public function StatusOrders( Request $request) {	
		$id = 0;
		$status = 0;
		if($request->ajax() && isset($request->id) && isset($request->status)){
			$id = $request->id;
			$status = $request->status;
			$error = 1;
			if($id != 0) {
				$orders = Orders::where('id',$id)->first();
				if($orders) {
					$orders->order_status = $status;
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

			echo $error;
		}
	}

	public function delete( Request $request) {	
		$id = 0;
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			$error = 1;
			if($id != 0) {
				$orders = Orders::where('id',$id)->first();
				if($orders){
					if($orders->delete()) {
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

			echo $error;
		}
	}

	public function DeleteAll( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$orders = Orders::where('id',$value)->first();
					if($orders){
						if($orders->delete()) {
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

			echo $error;
		}
	}

    public function ExportCSV( Request $request) {  
        if($request->ajax() && isset($request->ids)){
            $ids = $request->ids;
            $error = 1;
            if(sizeof($ids) != 0) {
                $table = Orders::whereIn('id',$ids)->get();

                foreach ($table as $key => $value) {
                    $table_det = OrderDetails::where('order_id',$value->id)->get();

                    if($value->order_code) {
                        $table[$key]['order_code'] = $value->order_code;
                    } else {
                        $table[$key]['order_code'] = "---------";
                    }

                    if($value->order_date) {
                        $table[$key]['order_date'] = date('d-m-Y', strtotime($value->order_date));
                    } else {
                        $table[$key]['order_date'] = "---------";
                    }   

                    if($value->payment_mode == 0) {
                        $table[$key]['payment_mode'] = "---------";
                    } elseif ($value->payment_mode == 1) {
                        $table[$key]['payment_mode'] = "Cash On Delivery";
                    } elseif ($value->payment_mode == 2) {
                        $table[$key]['payment_mode'] = "CC Avenue";
                    } else {
                        $table[$key]['payment_mode'] = "---------";
                    }

                    if($value->delivery_date) {
                        $table[$key]['delivery_date'] = date('d-m-Y', strtotime($value->delivery_date));
                    } else {
                        $table[$key]['delivery_date'] = "---------";
                    }

                    if($value->order_status == 0) {
                        $table[$key]['order_status'] = "---------";
                    } elseif($value->order_status == 1) {
                        $table[$key]['order_status'] = "Order Placed";
                    } elseif ($value->order_status == 2) {
                        $table[$key]['order_status'] = "Order Dispatched";
                    } elseif ($value->order_status == 3) {
                        $table[$key]['order_status'] = "Order Delivered";
                    } elseif ($value->order_status == 4) {
                        $table[$key]['order_status'] = "Order Complete";
                    } elseif ($value->order_status == 5) {
                        $table[$key]['order_status'] = "Order Cancelled";
                    } else {
                        $table[$key]['order_status'] = "---------";
                    }

                    if($value->contact_person) {
                        $table[$key]['contact_person'] = $value->contact_person;
                    } else {
                        $table[$key]['contact_person'] = "---------";
                    }

                    if($value->contact_no) {
                        $table[$key]['contact_no'] = $value->contact_no;
                    } else {
                        $table[$key]['contact_no'] = "---------";
                    }

                    if($value->shipping_address) {
                        $table[$key]['shipping_address'] = $value->shipping_address;
                    } else {
                        $table[$key]['shipping_address'] = "---------";
                    }

                    if($value->total_items) {
                        $table[$key]['total_items'] = $value->total_items;
                    } else {
                        $table[$key]['total_items'] = "---------";
                    }

                    if($value->discount_flag) {
                        $table[$key]['discount_flag'] = $value->discount_flag;
                    } else {
                        $table[$key]['discount_flag'] = "---------";
                    }

                    if($value->discount) {
                        $table[$key]['discount'] = 'Rs '.$value->discount;
                    } else {
                        $table[$key]['discount'] = "---------";
                    }

                    if($value->shipping_charge) {
                        $table[$key]['shipping_charge'] = 'Rs '.$value->shipping_charge;
                    } else {
                        $table[$key]['shipping_charge'] = "---------";
                    }

                    if($value->net_amount) {
                        $table[$key]['net_amount'] = 'Rs '.$value->net_amount;
                    } else {
                        $table[$key]['net_amount'] = "---------";
                    }

                    if($value->payment_status == 0) {
                        $table[$key]['payment_status'] = "Pending";
                    } elseif($value->payment_status == 1) {
                        $table[$key]['payment_status'] = "Success";
                    } elseif ($value->payment_status == 2) {
                        $table[$key]['payment_status'] = "Failed";
                    } else {
                        $table[$key]['payment_status'] = "---------";
                    }

                    if($value->delivery_status == 0) {
                        $table[$key]['delivery_status'] = "Pending";
                    } elseif($value->delivery_status == 1) {
                        $table[$key]['delivery_status'] = "Success";
                    } elseif ($value->delivery_status == 2) {
                        $table[$key]['delivery_status'] = "Failed";
                    } else {
                        $table[$key]['delivery_status'] = "---------";
                    }

                    if($value->remarks) {
                        $table[$key]['remarks'] = $value->remarks;
                    } else {
                        $table[$key]['remarks'] = "---------";
                    }

                    if(count($table_det) != 0) {
                        foreach ($table_det as $keyz => $valuez) {
                            if($valuez->product_title) {
                                $table[$key]['product_title'].= $valuez->product_title.',';
                            } else {
                                $table[$key]['product_title'] = "---------";
                            }

                            if($valuez->order_qty) {
                                $table[$key]['order_qty'].= $valuez->order_qty.',';
                            } else {
                                $table[$key]['order_qty'] = "---------";
                            }

                            if($valuez->unitprice) {
                                $table[$key]['unitprice'].= 'Rs '.$valuez->unitprice.',';
                            } else {
                                $table[$key]['unitprice'] = "---------";
                            }

                            if($valuez->totalprice) {
                                $table[$key]['totalprice'].= 'Rs '.$valuez->totalprice.',';
                            } else {
                                $table[$key]['totalprice'] = "---------";
                            }
                        }
                    }
                }
                
                $filename = "Orders.csv";
                $handle = fopen($filename, 'w+');
                fputcsv($handle, array('Order Code', 'Order Date', 'Payment Mode', 'Delivery Date', 'Order Status', 'Contact Person', 'Contact Number', 'Shipping Address', 'Total Items', 'Discount', 'Discount Rate', 'Shipping Charge', 'Net Amount', 'Payment Status', 'Delivery Status', 'Remarks', 'Title', 'Quantity', 'Price', 'Total'));

                foreach($table as $row) {
                    fputcsv($handle, array($row['order_code'], $row['order_date'], $row['payment_mode'], $row['delivery_date'], $row['order_status'], $row['contact_person'], $row['contact_no'], $row['shipping_address'], $row['total_items'], $row['discount_flag'], $row['discount'], $row['shipping_charge'], $row['net_amount'], $row['payment_status'], $row['delivery_status'], $row['remarks'], $row['product_title'], $row['order_qty'], $row['unitprice'], $row['totalprice']));
                }

                fclose($handle);

                $headers = array(
                    'Content-Type' => 'text/csv',
                );

                // Session::flash('message', 'CSV Export Successfully!'); 
                // Session::flash('alert-class', 'alert-success');
                $file_path = $filename;
                return $file_path;
            } else {
                Session::flash('message', 'CSV Export Failed!'); 
                Session::flash('alert-class', 'alert-danger');
            }
        }
    }

    public function ExportCourierCSV( Request $request) {  
        if($request->ajax() && isset($request->ids)){
            $ids = $request->ids;
            $error = 1;
            if(sizeof($ids) != 0) {
                $table = Orders::whereIn('id',$ids)->get();

                foreach ($table as $key => $value) {
                    $table_det = OrderDetails::where('order_id',$value->id)->get();

                    if($value->order_code) {
                        $table[$key]['order_id'] = $value->order_code;
                    } else {
                        $table[$key]['order_id'] = "";
                    }

                    if($value->contact_person) {
                        $table[$key]['cus_name'] = $value->contact_person;
                    } else {
                        $table[$key]['cus_name'] = "";
                    }

                    if($value->shipping_address) {
                        $table[$key]['cus_address'] = $value->shipping_address;
                    } else {
                        $table[$key]['cus_address'] = "";
                    }

                    if($value->city) {
                        $table[$key]['cus_city'] = $value->city;
                    } else {
                        $table[$key]['cus_city'] = "";
                    }

                    if($value->city) {
                        $table[$key]['cus_pincode'] = $value->pincode;
                    } else {
                        $table[$key]['cus_pincode'] = "";
                    }

                    if($value->contact_no) {
                        $table[$key]['cus_contact_no'] = $value->contact_no;
                    } else {
                        $table[$key]['cus_contact_no'] = "";
                    }

                    if($value->order_date) {
                        $table[$key]['shipment_date'] = date('d-m-Y', strtotime($value->order_date));
                    } else {
                        $table[$key]['shipment_date'] = "";
                    }

                    $table[$key]['category'] = "";

                    if(count($table_det) != 0) {
                        foreach ($table_det as $keyz => $valuez) {
                            if($valuez->product_title) {
                                $table[$key]['item_name'].= $valuez->product_title.',';
                            } else {
                                $table[$key]['item_name'] = "---------";
                            }
                        }
                    }

                    if($value->total_items) {
                        $table[$key]['qty'] = $value->total_items;
                    } else {
                        $table[$key]['qty'] = "";
                    }

                    $table[$key]['pick_addrs'] = "";

                    $table[$key]['odr_type'] = "";

                    if($value->net_amount) {
                        $table[$key]['total_value'] = $value->net_amount;
                    } else {
                        $table[$key]['total_value'] = "";
                    }

                    $table[$key]['pack_len'] = "";

                    $table[$key]['pack_wid'] = "";

                    $table[$key]['pack_hgh'] = "";

                    $table[$key]['pack_wgt'] = "";

                    $table[$key]['mode_type'] = "";
                }

                $exp = '<table id="gj_co_exp">
                    <thead>
                        <tr>
                            <th>zdfghsdf</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>gjuifyrjhgnmfhds</td>
                        </tr>
                    </tbody>
                </table>';
                return $exp;
                
                /*$filename = "Courier_Orders.xlsx";
                $handle = fopen($filename, 'w+');
                fwrite($handle, array('Order ID', 'Customer Name', 'Customer Address', 'Customer City', 'Customer Pincode', 'Customer Contact Number', 'Shipment Date', 'Category', 'Item Name', 'Quantity', 'Pickup Address Name', 'Order Type', 'Total Value (Rs.)', 'Package Length (cm)', 'Package Width (cm)', 'Package Height (cm)', 'Package Weight (kg)', 'Mode Type'));

                foreach($table as $row) {
                    fwrite($handle, array($row['order_id'], $row['cus_name'], $row['cus_address'], $row['cus_city'], $row['cus_pincode'], $row['cus_contact_no'], $row['shipment_date'], $row['category'], $row['item_name'], $row['qty'], $row['pick_addrs'], $row['odr_type'], $row['total_value'], $row['pack_len'], $row['pack_wid'], $row['pack_hgh'], $row['pack_wgt'], $row['mode_type']));
                }

                fclose($handle);

                $headers = array(
                    'Content-Type' => 'text/application/vnd.ms-excel',
                );

                // Session::flash('message', 'CSV Export Successfully!'); 
                // Session::flash('alert-class', 'alert-success');
                $file_path = $filename;
                return $file_path;*/
            } else {
                Session::flash('message', 'CSV Export Failed!'); 
                Session::flash('alert-class', 'alert-danger');
            }
        }
    }

    public function SearchOrder (Request $request) {
        $page = "Transaction";                                               
        $order_date = Input::get('gj_srh_odr_date');
        $order_code = Input::get('gj_srh_odr_code');

        if($order_date && $order_code) {
            $orders = Orders::Where('order_date', $order_date)->Where('order_code', 'like', '%' . $order_code . '%')->paginate(10);
            if(count($orders) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("transaction.orders.all_orders")->with(array('orders'=>$orders, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                $orders = Orders::paginate(10);
                return View::make("transaction.orders.all_orders")->with(array('orders'=>$orders, 'page'=>$page));
            }
        } elseif($order_date) {
            $orders = Orders::Where('order_date', $order_date)->paginate(10);
            if(count($orders) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("transaction.orders.all_orders")->with(array('orders'=>$orders, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                $orders = Orders::paginate(10);
                return View::make("transaction.orders.all_orders")->with(array('orders'=>$orders, 'page'=>$page));
            }
        } elseif($order_code) {
            $orders = Orders::orWhere('order_code', 'like', '%' . $order_code . '%')->paginate(10);
            if(count($orders) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("transaction.orders.all_orders")->with(array('orders'=>$orders, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                $orders = Orders::paginate(10);
                return View::make("transaction.orders.all_orders")->with(array('orders'=>$orders, 'page'=>$page));
            }
        } else {
            $orders = Orders::paginate(10);
            Session::flash('message', 'Search Items Not Found!'); 
            Session::flash('alert-class', 'alert-danger');
            return View::make("transaction.orders.all_orders")->with(array('orders'=>$orders, 'page'=>$page));
        }
    }
}
