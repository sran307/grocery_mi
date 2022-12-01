<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orders;
use App\OrderDetails;
use App\OrdersTransactions;
use App\Products;
use App\User;
use App\ShippingAddress;
use App\NoimageSettings;
use App\CityManagement;
use App\CountriesManagement;
use App\StateManagements;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class CourierTrackController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function AllOrders () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Courier Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Courier Tracking";
                $orders = Orders::paginate(10);
            	return View::make("transaction.courier.courier_track")->with(array('orders'=>$orders, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Courier Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
                $page = "Courier Tracking";
        		$orders = Orders::where('id',$id)->first();
        		if($orders) {
        			$orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
        		}
        		return View::make("transaction.courier.view_courier_track")->with(array('orders'=>$orders, 'page'=>$page));
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

    public function ExportCourierCSV( Request $request) { 
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Courier Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()){
                    $ids = $request->ids;
                    $table = array();
                    $exp = '<table id="gj_co_exp">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Customer Address</th>
                                <th>Customer City</th>
                                <th>Customer Pincode</th>
                                <th>Customer Contact Number</th>
                                <th>Shipment Date</th>
                                <th>Category</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Pickup Address Name</th>
                                <th>Order Type</th>
                                <th>Total Value (Rs.)</th>
                                <th>Package Length (cm)</th>
                                <th>Package Width (cm)</th>
                                <th>Package Height (cm)</th>
                                <th>Package Weight (kg)</th>
                                <th>Mode Type</th>
                            </tr>
                        </thead>
                        <tbody>';
                        
                    if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            $table = Orders::whereIn('id',$ids)->Where('order_status', 1)->get();
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') {
                        $table = Orders::Where('order_status', 1)->get();
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }

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

                        if($value->pincode) {
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
                            $table[$key]['shipment_date'] = date('d/m/Y', strtotime($value->order_date));
                        } else {
                            $table[$key]['shipment_date'] = "";
                        }

                        $table[$key]['category'] = "";

                        if(count($table_det) != 0) {
                            foreach ($table_det as $keyz => $valuez) {
                                if($valuez->product_title) {
                                    $table[$key]['item_name'].= $valuez->product_title.',';
                                } else {
                                    $table[$key]['item_name'] = "";
                                }
                            }
                        }

                        if($value->total_items) {
                            $table[$key]['qty'] = $value->total_items;
                        } else {
                            $table[$key]['qty'] = "";
                        }

                        $table[$key]['pick_addrs'] = "INTERCAMBIAR";

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
                        
                        $exp.= '<tr>
                            <td>'.$table[$key]['order_id'].'</td>
                            <td>'.$table[$key]['cus_name'].'</td>
                            <td>'.$table[$key]['cus_address'].'</td>
                            <td>'.$table[$key]['cus_city'].'</td>
                            <td>'.$table[$key]['cus_pincode'].'</td>
                            <td>'.$table[$key]['cus_contact_no'].'</td>
                            <td>'.$table[$key]['shipment_date'].'</td>
                            <td>'.$table[$key]['category'].'</td>
                            <td>'.$table[$key]['item_name'].'</td>
                            <td>'.$table[$key]['qty'].'</td>
                            <td>'.$table[$key]['pick_addrs'].'</td>
                            <td>'.$table[$key]['odr_type'].'</td>
                            <td>'.$table[$key]['total_value'].'</td>
                            <td>'.$table[$key]['pack_len'].'</td>
                            <td>'.$table[$key]['pack_wid'].'</td>
                            <td>'.$table[$key]['pack_hgh'].'</td>
                            <td>'.$table[$key]['pack_wgt'].'</td>
                            <td>'.$table[$key]['mode_type'].'</td>
                        </tr>';
                    }
                    $exp.= '</tbody>
                    </table>';

                    return $exp;
                    
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
    
    public function SearchCouOrder (Request $request) {
        $page = "Courier Tracking";                                               
        $order_date = Input::get('gj_srh_odr_date');
        $order_code = Input::get('gj_srh_odr_code');

        if($order_date && $order_code) {
            $orders = Orders::Where('order_date', $order_date)->Where('order_code', 'like', '%' . $order_code . '%')->paginate(10);
            if(count($orders) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("transaction.courier.courier_track")->with(array('orders'=>$orders, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                $orders = Orders::paginate(10);
                return View::make("transaction.courier.courier_track")->with(array('orders'=>$orders, 'page'=>$page));
            }
        } elseif($order_date) {
            $orders = Orders::Where('order_date', $order_date)->paginate(10);
            if(count($orders) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("transaction.courier.courier_track")->with(array('orders'=>$orders, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                $orders = Orders::paginate(10);
                return View::make("transaction.courier.courier_track")->with(array('orders'=>$orders, 'page'=>$page));
            }
        } elseif($order_code) {
            $orders = Orders::orWhere('order_code', 'like', '%' . $order_code . '%')->paginate(10);
            if(count($orders) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("transaction.courier.courier_track")->with(array('orders'=>$orders, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                $orders = Orders::paginate(10);
                return View::make("transaction.courier.courier_track")->with(array('orders'=>$orders, 'page'=>$page));
            }
        } else {
            $orders = Orders::paginate(10);
            Session::flash('message', 'Search Items Not Found!'); 
            Session::flash('alert-class', 'alert-danger');
            return View::make("transaction.courier.courier_track")->with(array('orders'=>$orders, 'page'=>$page));
        }
    }
}
