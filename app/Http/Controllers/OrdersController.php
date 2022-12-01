<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orders;
use App\OrderDetails;
use App\OrdersTransactions;
use App\Products;
use App\ProductsAttributes;
use App\User;
use App\ShippingAddress;
use App\NoimageSettings;
use App\CityManagement;
use App\Notification;
use App\CountriesManagement;
use App\StateManagements;
use App\ReportProducts;
use App\StockTransactions;
use App\ReturnOrder;
use App\ReturnOrderDetails;
use App\GrvOrders;
use App\GrvOrdersDetails;
use App\CreditsNotes;
use App\AdminCommision;
use App\GeneralSettings;
use App\EmailSettings;
use App\LogoSettings;

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

class OrdersController extends Controller
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
                ->where('B.module_name', '=', 'All Orders')
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
                        $orders = Orders::OrderBy('id', 'DESC')->paginate(10);
                        if(sizeof($orders) != 0) {
                            foreach ($orders as $key => $value) {
                                $det = OrderDetails::Where('order_id', $value->id)->get(); 
                                if(sizeof($det)) {
                                    $orders[$key]->{'details'} = $det;
                                } else {
                                    $orders[$key]->{'details'} =  '';
                                }
                            }
                        }
                    } else if($sess->user_type == 2 || $sess->user_type == 3) {
                        $ords = DB::table('orders as A')
                            ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
                            ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                            ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                            ->select('A.id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                            ->OrderBy('A.id', 'DESC')
                            ->where('C.created_user', '=', $sess->id)
                            ->where('D.id', '=', $sess->id)
                            ->whereIn('D.user_type', ['2','3'])
                            ->GroupBy('B.order_id')
                            ->get();

                        if (sizeof($ords) != 0) {
                            foreach ($ords as $key => $value) {
                                array_push($co_id, $value->id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $orders = Orders::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                            if(sizeof($orders) != 0) {
                                foreach ($orders as $key => $value) {
                                    $det = OrderDetails::Where('order_id', $value->id)->get(); 
                                    if(sizeof($det) != 0) {
                                        $orders[$key]->{'details'} = $det;
                                    } else {
                                        $orders[$key]->{'details'} =  '';
                                    }
                                }
                            }
                        }
                    }
                }

            	return View::make("transaction.orders.all_orders")->with(array('orders'=>$orders, 'page'=>$page));
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

    public function ReplaceOrders () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Replace New Order')
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
                        $orders = Orders::Where('replace_order', "Yes")->OrderBy('id', 'DESC')->paginate(10);
                        if(sizeof($orders) != 0) {
                            foreach ($orders as $key => $value) {
                                $det = OrderDetails::Where('order_id', $value->id)->get(); 
                                if(sizeof($det)) {
                                    $orders[$key]->{'details'} = $det;
                                } else {
                                    $orders[$key]->{'details'} =  '';
                                }
                            }
                        }
                    } else if($sess->user_type == 2 || $sess->user_type == 3) {
                        $ords = DB::table('orders as A')
                            ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
                            ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                            ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                            ->select('A.id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                            ->OrderBy('A.id', 'DESC')
                            ->where('A.replace_order', '=', 'Yes')
                            ->where('C.created_user', '=', $sess->id)
                            ->where('D.id', '=', $sess->id)
                            ->whereIn('D.user_type', ['2','3'])
                            ->GroupBy('B.order_id')
                            ->get();

                        if (sizeof($ords) != 0) {
                            foreach ($ords as $key => $value) {
                                array_push($co_id, $value->id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $orders = Orders::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                            if(sizeof($orders) != 0) {
                                foreach ($orders as $key => $value) {
                                    $det = OrderDetails::Where('order_id', $value->id)->get(); 
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

                return View::make("transaction.orders.all_orders")->with(array('orders'=>$orders, 'page'=>$page));
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

    public function CancelAllOrders () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Cancel Order')
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
                        $orders = Orders::Where('cancel_approved', '!=', 0)->OrderBy('id', 'DESC')->paginate(10);
                        if(sizeof($orders) != 0) {
                            foreach ($orders as $key => $value) {
                                $det = OrderDetails::Where('order_id', $value->id)->get(); 
                                if(sizeof($det)) {
                                    $orders[$key]->{'details'} = $det;
                                } else {
                                    $orders[$key]->{'details'} =  '';
                                }
                            }
                        }
                    } else if($sess->user_type == 2 || $sess->user_type == 3) {
                        $ords = DB::table('orders as A')
                            ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
                            ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                            ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                            ->select('A.id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                            ->OrderBy('A.id', 'DESC')
                            ->where('A.cancel_approved', '!=', 0)
                            ->where('C.created_user', '=', $sess->id)
                            ->where('D.id', '=', $sess->id)
                            ->whereIn('D.user_type', ['2','3'])
                            ->GroupBy('B.order_id')
                            ->get();

                        if (sizeof($ords) != 0) {
                            foreach ($ords as $key => $value) {
                                array_push($co_id, $value->id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $orders = Orders::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                            if(sizeof($orders) != 0) {
                                foreach ($orders as $key => $value) {
                                    $det = OrderDetails::Where('order_id', $value->id)->get(); 
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

                return View::make("transaction.orders.cancel_all_orders")->with(array('orders'=>$orders, 'page'=>$page));
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

    public function CancelReqOrders () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Cancel Order Requests')
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
                        $orders = Orders::Where('cancel_approved', 3)->OrderBy('id', 'DESC')->paginate(10);
                        if(sizeof($orders) != 0) {
                            foreach ($orders as $key => $value) {
                                $det = OrderDetails::Where('order_id', $value->id)->get(); 
                                if(sizeof($det)) {
                                    $orders[$key]->{'details'} = $det;
                                } else {
                                    $orders[$key]->{'details'} =  '';
                                }
                            }
                        }
                    } else if($sess->user_type == 2 || $sess->user_type == 3) {
                        $ords = DB::table('orders as A')
                            ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
                            ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                            ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                            ->select('A.id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                            ->OrderBy('A.id', 'DESC')
                            ->where('A.cancel_approved', '=', 2)
                            ->where('C.created_user', '=', $sess->id)
                            ->where('D.id', '=', $sess->id)
                            ->whereIn('D.user_type', ['2','3'])
                            ->GroupBy('B.order_id')
                            ->get();

                        if (sizeof($ords) != 0) {
                            foreach ($ords as $key => $value) {
                                array_push($co_id, $value->id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $orders = Orders::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                            if(sizeof($orders) != 0) {
                                foreach ($orders as $key => $value) {
                                    $det = OrderDetails::Where('order_id', $value->id)->get(); 
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

                return View::make("transaction.orders.cancel_req_orders")->with(array('orders'=>$orders, 'page'=>$page));
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
      public function report_admin_action ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Cancel Order Requests')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $orders = ReportProducts::Where('report_status', 1)->where('id',$id)->first();
                if($orders) {
                    return View::make("transaction.orders.admin_report_status")->with(array('orders'=>$orders, 'page'=>$page));
                } else {
                   Session::flash('message', 'Could Not Accepted/Reject Cancel Order Request!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('cancel_req_orders'); 
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

    public function CancelReqAccept ($id,Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Cancel Order Requests')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                if($request->notify_id!=null)
                {
                     $as=Notification::find($request->notify_id);
                    $as->read_flag=1;
                    $as->update();
                }
                $orders = Orders::Where('cancel_approved', 3)->where('id',$id)->first();
                if($orders) {
                    return View::make("transaction.orders.cancel_order_sts")->with(array('orders'=>$orders, 'page'=>$page));
                } else {
                   Session::flash('message', 'Could Not Accepted/Reject Cancel Order Request!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('cancel_req_orders'); 
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
        public function admin_report_products(Request $request)
        {
             $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Cancel Order Requests')
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
                        $orders = ReportProducts::OrderBy('id', 'DESC')->paginate(10);
                        if(sizeof($orders) != 0) {
                            foreach ($orders as $key => $value) {
                                $det = OrderDetails::Where('order_id', $value->order_id)->get(); 
                                if(sizeof($det)) {
                                    $orders[$key]->{'details'} = $det;
                                } else {
                                    $orders[$key]->{'details'} =  '';
                                }
                            }
                        }
                    }
                }

                return View::make("transaction.orders.report_admin")->with(array('orders'=>$orders, 'page'=>$page));
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
    public function CancelReqStatus (Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Cancel Order Requests')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $data = Input::all();
            if(isset($request->btn_id))
            {
                 $rules = array(
                    'report_id'          => 'required',
                    'report_remarks'    => 'required',
                    'report_status'   => 'required',
                );

                $messages=[
                    'cancel_approved.required'=>'The Cancel Order Status field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                    Session::flash('message', 'Fix Validation Error!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return Redirect::to('/report_admin_action/'.$data['report_id'])->withErrors($validator);
                    // return redirect()->route('cancel_req_accept'.$data['order_id'])->withErrors($validator);
                } else {
                    
                    $orders = ReportProducts::find($data['report_id']);
                    $orders->report_status=$request->report_status;
                    $orders->update();
                      Session::flash('message', 'Updated Successfully'); 
                    Session::flash('alert-class', 'alert-success');
                    return redirect()->route('admin_report_products');
                } 
            }
            else
            {
                 $rules = array(
                    'order_id'          => 'required|exists:orders,id',
                    'cancel_remarks'    => 'required',
                    'cancel_approved'   => 'required',
                );

                $messages=[
                    'cancel_approved.required'=>'The Cancel Order Status field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                    Session::flash('message', 'Fix Validation Error!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return Redirect::to('/cancel_req_accept/'.$data['order_id'])->withErrors($validator);
                    // return redirect()->route('cancel_req_accept'.$data['order_id'])->withErrors($validator);
                } else {
                    $orders = Orders::Where('cancel_approved', 3)->where('id',$data['order_id'])->first();
                    if($orders) {
                        $orders->cancel_approved = $data['cancel_approved'];
                        $orders->cancel_remarks  = $data['cancel_remarks'];

                        if($data['cancel_approved'] == 1) {
                            $orders->order_status = 5;
                            $orders->cancel_date = date('Y-m-d');
                        }

                        if($orders->save()) {
                            if($orders->cancel_approved == 1) {
                                $text = "Your Order Cancel Request is Accepted. Plz note the Order Code - ".$orders->order_code.", grocery360.in";
                                $subject = "Cancel Order Request Accepted";
                            } elseif ($orders->cancel_approved == 2) {
                                $text = "Your Order Cancel Request is Rejected. Plz note the Order Code - ".$orders->order_code.", grocery360.in";
                                $subject = "Cancel Order Request Rejected";
                            }

                            $text = urlencode($text);

                            $curl = curl_init();
                            $user = User::Where('id', $orders->user_id)->first();
                            if($user) { 
                                $adm = EmailSettings::where('id', 1)->first();
                                $admin_email = "info@grocery360.in";
                                if($adm) {
                                    $admin_email = $adm->contact_email;
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
                                $site_name = "grocery360.in";
                                if($general){
                                    $site_name = $general->site_name;
                                } else {
                                    $site_name = "grocery360.in";
                                } 

                                $name = $user->first_name.' '.$user->last_name;

                                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                $headers.= "MIME-Version: 1.0\r\n";
                                // $headers.= "From: $admin_email" . "\r\n";
                                $headers.= "From: Grocery360 <order@grocery360.in>" . "\r\n";
                                $to1 = $user->email;
                                $to2 = $admin_email;

                                $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                            <h2 style="color: #ff5c00;margin-top: 0px;">'.$subject.'</h2>
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
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$orders->order_code.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Order Date</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$orders->order_date.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Request Replied Date</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$orders->cancel_date.'</td>
                                                </tr>

                                                <tr>
                                                    <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Remarks</th>
                                                    <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$orders->cancel_remarks.'</td>
                                                </tr>
                                            </table>

                                            <p>Use This Order Code To Further Reference.</p>
                                            <p>Thank You.</p>
                                             <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                            <p>Thanks & Regards,</p>
                                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                        </div>
                                    </div>';
                                    
                                    
                                // if(1==1){
                                if(mail($to1,$subject,$txt,$headers) && mail($to2,$subject,$txt,$headers)) {
                                    Session::flash('message', $subject); 
                                    Session::flash('alert-class', 'alert-success');
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
                                    Session::flash('message', $subject); 
                                    Session::flash('alert-class', 'alert-success');
                                    return redirect()->route('cancel_req_orders');
                                } else {
                                    Session::flash('message', $subject); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('cancel_req_orders');
                                }
                            } else {
                                Session::flash('message', 'Could Not Accept/Reject Cancel Order Request!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('cancel_req_orders');
                            }
                        } else {
                            Session::flash('message', 'Could Not Accepted/Reject Cancel Order Request!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('cancel_req_orders');
                        }
                    } else {
                       Session::flash('message', 'Could Not Accepted/Reject Cancel Order Request!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('cancel_req_orders'); 
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

    public function NewOrders () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Replace New Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $grv = GrvOrders::Where('grv_status', 1)->get();
                return View::make("transaction.orders.new_orders")->with(array('grv'=>$grv, 'page'=>$page));
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

    public function CreateCreditNotes () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Credit Notes')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $grv = GrvOrders::Where('grv_status', 1)->get();
                return View::make("transaction.orders.create_credit_notes")->with(array('grv'=>$grv, 'page'=>$page));
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

    public function SaveNewOrders (Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Replace New Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $data = Input::all();

                $rules = array(
                    'return_type'       => 'nullable',
                    'grv_id'            => 'nullable',
                    'remarks'           => 'required',
                );

                $messages=[
                    'grv_id.required'=>'The Grv field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                    Session::flash('message', 'Fix Validation Error, Remark Fields is required!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('create_credit_notes')->withErrors($validator);
                } else {
                    $sus1 = 0;
                    $sus2 = 0;
                    $sus3 = 0;

                    $user = User::Where('id', $data['user_id'])->Where('is_block', 1)->first();
                    if($user) {
                        if($data['return_type'] == "Refund") {
                            if (isset($data['det_return_type']) && count($data['det_return_type']) != 0) {
                                if (in_array("Exchange", $data['det_return_type'])) {
                                    Session::flash('message', 'Only Refund is Available!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('create_credit_notes');
                                } else {
                                    if (in_array("Replacement", $data['det_return_type'])) {
                                        Session::flash('message', 'Only Refund is Available!'); 
                                        Session::flash('alert-class', 'alert-danger');
                                        return redirect()->route('create_credit_notes');
                                    } else {
                                        $cn = new CreditsNotes();

                                        if($cn) {
                                            $max = CreditsNotes::max('cn_code');
                                            $max_id = "00001";
                                            $max_st = "CN";
                                            if($max) {
                                                $max_no = substr($max, 2);
                                                $increment = (int)$max_no + 1;
                                                $data['cn_code'] = $max_st.sprintf("%05d", $increment);
                                            } else {
                                                $data['cn_code'] = $max_st.$max_id;
                                            }

                                            $cn->cn_code = $data['cn_code'];
                                            $cn->grv_id = $data['grv_id'];
                                            $cn->amount = $data['det_sub_tot'];
                                            $cn->remarks = $data['remarks'];
                                            $cn->date = date('Y-m-d');
                                            $cn->is_paid = 'Un Paid';

                                            if($cn->save()) {
                                                if (isset($data['grv_det_id']) && count($data['grv_det_id']) != 0) {
                                                    foreach ($data['grv_det_id'] as $keys => $values) {
                                                        $grv_details = GrvOrdersDetails::Where('id', $values)->first();
                                                        if($grv_details) {
                                                            $grv_details->grv_issued = "Yes";
                                                            $grv_details->save();

                                                            $com_per = $grv_details->Products->Creatier->commission;
                                                            $t_pce = $cn->amount;
                                                            $admin_com = round($t_pce * ($com_per / 100), 2);
                                                            $mer_amt = round($t_pce - $admin_com, 2);

                                                            $comis = new AdminCommision();
                                                            $comis->type         = 'Credit Notes';
                                                            $comis->cn_id        = $cn->id;
                                                            $comis->order_code   = null;
                                                            $comis->order_dets   = null;
                                                            $comis->product_id   = $grv_details->product_id;
                                                            $comis->att_name     = $grv_details->att_name;
                                                            $comis->att_value    = $grv_details->att_value;
                                                            $comis->merchant_id  = $grv_details->Products->Creatier->id;
                                                            $comis->amount       = $admin_com;
                                                            $comis->merchant_amount = $mer_amt;
                                                            $comis->paid_status  = 0;
                                                            $comis->remarks      = $grv_details->product_title.' product against Admin Commision is Rs. '.$admin_com.' set.';
                                                            $comis->save();
                                                        }
                                                    }
                                                }

                                                $odr_cde = "";
                                                $grv = GrvOrders::Where('id', $data['grv_id'])->first();
                                                if($grv) {
                                                    $odr_cde = $grv->Orders->order_code;
                                                    $grv_dets = GrvOrdersDetails::Where('grv_id', $grv->id)->get();
                                                    if($grv_dets->contains('grv_issued', 'No')){
                                                        $grv->grv_status = 1;
                                                    } else {
                                                        $grv->grv_status = 2;
                                                    }
                                                    $grv->save();
                                                }

                                                $adm = EmailSettings::where('id', 1)->first();;
                                                $admin_email = "info@grocery360.in";
                                                if($adm) {
                                                    $admin_email = $adm->contact_email;
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
                                                $site_name = "grocery360.in";
                                                if($general){
                                                    $site_name = $general->site_name;
                                                } else {
                                                    $site_name = "grocery360.in";
                                                }

                                                $customer_name = $user->first_name.' '.$user->last_name;
                                                $address = $user->address1.','.$user->address2.','.$user->City->city_name.','.$user->pincode.','.$user->State->state.','.$user->Country->country_name;
                                                $contact = $user->phone.','.$user->phone2;

                                                $name = $user->first_name.' '.$user->last_name;
                                                $email = $user->email;

                                                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                                $headers.= "MIME-Version: 1.0\r\n";
                                                // $headers.= "From: $admin_email" . "\r\n";
                                                $headers.= "From:Grocery360 <order@grocery360.in>" . "\r\n";
                                                $to = $email;
                                                $to2 = $admin_email;
                                                $subject = "Credit Notes Details";
                                                $txt = '<div class="gj_mail" style="width: 600px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat;  background-size: 100% 102%;">
                                                    <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                                    <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                                        <h2 style="color: #ff5c00;margin-top: 0px;">Credit Notes Details</h2>
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
                                                                <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$odr_cde.'</td>
                                                            </tr>

                                                            <tr>
                                                                <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Credit Notes Code</th>
                                                                <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$cn->cn_code.'</td>
                                                            </tr>

                                                            <tr>
                                                                <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Amount</th>
                                                                <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : Rs. '.$cn->amount.'</td>
                                                            </tr>

                                                            <tr>
                                                                <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Order Date</th>
                                                                <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.date('d-m-Y', strtotime($cn->date)).'</td>
                                                            </tr>
                                                        </table>
                                                        <p></p>
                                                        <p>Thank You.</p>
                                                         <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                                        <p>Thanks & Regards,</p>
                                                        <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                                    </div>
                                                </div>';
                                
                                                // if(1==1) {
                                                if(mail($to,$subject,$txt,$headers)){
                                                    mail($to2,$subject,$txt,$headers);
                                                    if($user->phone) {
                                                        $text = "Your Order Refund Request against Credit Notes Create Successful - Reference Code  - ".$cn->cn_code.", grocery360.in";
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
                                                            Session::flash('message', 'Credit Notes Created and confirm  Message and Email Send Successfully!');
                                                            Session::flash('alert-class', 'alert-success');
                                                        } else {
                                                            Session::flash('message', 'Credit Notes Created & Email Send Successfully!'); 
                                                            Session::flash('alert-class', 'alert-success');
                                                        }
                                                        return redirect()->route('all_orders');
                                                    } else {
                                                        Session::flash('message', 'Credit Notes Created & Mail Send Successfully!'); 
                                                        Session::flash('alert-class', 'alert-success');
                                                    }
                                                    return redirect()->route('all_orders');
                                                } else {
                                                    if($user->phone) {
                                                        $text = "Your Order Refund Request against Credit Notes Create Successful - Reference Code  - ".$cn->cn_code.", grocery360.in";
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
                                                            Session::flash('message', 'Credit Notes Created and  Confirm Message and Email Send Successfully!');
                                                            Session::flash('alert-class', 'alert-success');
                                                        } else {
                                                            Session::flash('message', 'Credit Notes Created Successfully!'); 
                                                            Session::flash('alert-class', 'alert-success');
                                                        }
                                                        return redirect()->route('all_orders');
                                                    } else {
                                                        Session::flash('message', 'Credit Notes Created!'); 
                                                        Session::flash('alert-class', 'alert-success');
                                                    }
                                                    return redirect()->route('all_orders');
                                                }
                                            } else {
                                                Session::flash('message', 'Credit Notes Created Failed!'); 
                                                Session::flash('alert-class', 'alert-danger');
                                                return redirect()->route('create_credit_notes');
                                            }
                                        } else {
                                            Session::flash('message', 'Credit Notes Created Not Possible!'); 
                                            Session::flash('alert-class', 'alert-danger');
                                            return redirect()->route('create_credit_notes');      
                                        }
                                    }
                                }
                            } else {
                                Session::flash('message', 'Please Enter All Details!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('create_credit_notes');
                            }
                        } else if(($data['return_type'] == "Replacement")) {
                            if (isset($data['det_return_type']) && count($data['det_return_type']) != 0) {
                                if (in_array("Refund", $data['det_return_type'])) {
                                    Session::flash('message', 'Only Replacement is Available!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('new_orders');
                                } else {
                                    $order = new Orders();

                                    if($order) {
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
                                        $order->payment_mode = $data['payment_mode'];
                                        $order->contact_person = $data['contact_person'];
                                        $order->contact_email = $data['contact_email'];
                                        $order->contact_no = $data['contact_no'];
                                        $order->shipping_address_flag = $data['shipping_address_flag'];
                                        $order->shipping_address = $data['shipping_address'];
                                        $order->city = $data['city'];
                                        $order->pincode = $data['pincode'];
                                        $order->total_items = $data['total_items'];
                                        // $order->tax_amount = $data['tax_amount'];
                                        $order->total_amount = $data['det_sub_tot'];
                                        $order->service_charge = $data['det_serv_charge'];
                                        $order->shipping_charge = $data['det_shipping_charge'];
                                        $order->net_amount = $data['det_net_amount'];
                                        $order->ref_order_id = $data['order_id'];
                                        $order->grv_id = $data['grv_id'];
                                        $order->order_status = 1;
                                        $order->payment_status = 0;
                                        $order->remarks = NULL;
                                        $order->replace_order = 'Yes';
                                        $order->is_block = 1;

                                        if($order->save()) {
                                            if (isset($data['det_product_id']) && count($data['det_product_id']) != 0) {
                                                foreach ($data['det_product_id'] as $key => $value) {
                                                    $order_details = new OrderDetails();
                                                    $order_details->order_id = $order->id;
                                                    $order_details->product_id = $value;
                                                    
                                                    if(isset($data['det_product_title'][$key])) {
                                                        $order_details->product_title = $data['det_product_title'][$key];
                                                    } else {
                                                        $order_details->product_title = NULL;
                                                    }
                                                    
                                                    if(isset($data['det_return_qty'][$key])) {
                                                        $order_details->order_qty = $data['det_return_qty'][$key];
                                                    } else {
                                                        $order_details->order_qty = NULL;
                                                    }

                                                    if(isset($data['det_att_name'][$key])) {
                                                        $order_details->att_name = $data['det_att_name'][$key];
                                                    } else {
                                                        $order_details->att_name = NULL;
                                                    }

                                                    if(isset($data['det_att_value'][$key])) {
                                                        $order_details->att_value = $data['det_att_value'][$key];
                                                    } else {
                                                        $order_details->att_value = NULL;
                                                    }

                                                    if(isset($data['det_tax'][$key])) {
                                                        $order_details->tax = $data['det_tax'][$key];
                                                    } else {
                                                        $order_details->tax = NULL;
                                                    }

                                                    if(isset($data['det_tax_type'][$key])) {
                                                        $order_details->tax_type = $data['det_tax_type'][$key];
                                                    } else {
                                                        $order_details->tax_type = NULL;
                                                    }

                                                    if(isset($data['det_unitprice'][$key])) {
                                                        $order_details->unitprice = $data['det_unitprice'][$key];
                                                    } else {
                                                        $order_details->unitprice = NULL;
                                                    }

                                                    // if(isset($data['det_return_tax_amount'][$key])) {
                                                    //     $order_details->tax_amount = $data['det_return_tax_amount'][$key];
                                                    // } else {
                                                    //     $order_details->tax_amount = NULL;
                                                    // }

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
                                                $order_trans->net_amount = $order->net_amount;
                                                $order_trans->amountpaid = "Unpaid";
                                                $order_trans->paymentmode = $data['payment_mode'];
                                                $order_trans->gatewaytransactionid = NULL;
                                                $order_trans->trans_status = "PENDING";
                                                $order_trans->remarks = NULL;
                                                $order_trans->is_block = 1;

                                                if($order_trans->save()) {
                                                    $sus3 = 1;
                                                }
                                            } else if($data['payment_mode'] == 2) {
                                                if($order) {
                                                    $order->order_status = 1;
                                                    $order->payment_status = 0;
                                                    $order->save();
                                                    $sus3 = 1;
                                                }
                                            } 

                                            if($sus2 == 1 && $sus3 == 1) {
                                                if (isset($data['grv_det_id']) && count($data['grv_det_id']) != 0) {
                                                    foreach ($data['grv_det_id'] as $keys => $values) {
                                                        $grv_details = GrvOrdersDetails::Where('id', $values)->first();
                                                        $grv_details->grv_issued = "Yes";
                                                        $grv_details->save();
                                                    }
                                                }

                                                $grv = GrvOrders::Where('id', $data['grv_id'])->first();
                                                if($grv) {
                                                    $grv_dets = GrvOrdersDetails::Where('grv_id', $grv->id)->get();
                                                    if($grv_dets->contains('grv_issued', 'No')){
                                                        $grv->grv_status = 1;
                                                    } else {
                                                        $grv->grv_status = 2;
                                                    }
                                                    $grv->save();
                                                }

                                                $adm = EmailSettings::where('id', 1)->first();
                                                $admin_email = "info@grocery360.in";
                                                if($adm) {
                                                    $admin_email = $adm->contact_email;
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
                                                $site_name = "grocery360.in";
                                                if($general){
                                                    $site_name = $general->site_name;
                                                } else {
                                                    $site_name = "grocery360.in";
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
                                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: center;"> '.$value->product_title.' '. $att_tit .'</td>
                                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: center;"> '.$value->order_qty.'</td>
                                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: center;">Rs.  '.$value->unitprice.'</td>
                                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs.  '.$value->totalprice.'</td>
                                                        </tr>';
                                                    }
                                                }

                                                if($order) {
                                                    $order->net_commision = $net_comis;
                                                    $order->net_merchant_amout = $net_mer_amt;
                                                    $order->save();
                                                }

                                                $ships = ShippingAddress::Where('user_id', $user->id)->Where('is_block', 1)->first();
                                                if(isset($data['shipping_address_flag']) && $data['shipping_address_flag'] == 1) {
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
                                                $headers.= "From:Grocery360 <order@grocery360.in>" . "\r\n";
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
                                
                                                // if(1==1) {
                                                if(mail($to,$subject,$txt,$headers)){
                                                    mail($to2,$subject,$txt,$headers);
                                                    if($user->phone) {
                                                        $text = "Replacement order successful - Order Reference Code  - ".$order_code.",grocery360.in";
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
                                                        } else {
                                                            Session::flash('message', 'Order placed & Email Send Successfully!'); 
                                                            Session::flash('alert-class', 'alert-success');
                                                        }
                                                        return redirect()->route('all_orders');
                                                    } else {
                                                        Session::flash('message', 'Order Placed & Mail Send Successfully!'); 
                                                        Session::flash('alert-class', 'alert-success');
                                                    }
                                                    return redirect()->route('all_orders');
                                                } else {
                                                    if($user->phone) {
                                                        $text = "Thanks for shopping with us.Plz note the Order Code - ".$order_code.", grocery360.in";
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
                                                        } else {
                                                            Session::flash('message', 'Order placed Successfully!'); 
                                                            Session::flash('alert-class', 'alert-success');
                                                        }
                                                        return redirect()->route('all_orders');
                                                    } else {
                                                        Session::flash('message', 'Order Placed Successfully!'); 
                                                        Session::flash('alert-class', 'alert-success');
                                                    }
                                                    return redirect()->route('all_orders');
                                                }
                                            } else {
                                                Orders::where('id', $order->id)->delete();
                                                Session::flash('message', 'Replace New Orders Placed Failed!'); 
                                                Session::flash('alert-class', 'alert-danger');
                                                return redirect()->route('new_orders');
                                            }
                                        } else {
                                            Session::flash('message', 'Replace New Orders Placed Failed!'); 
                                            Session::flash('alert-class', 'alert-danger');
                                            return redirect()->route('new_orders');
                                        }
                                    } else {
                                        Session::flash('message', 'New Order Created Not Possible!'); 
                                        Session::flash('alert-class', 'alert-danger');
                                        return redirect()->route('new_orders');      
                                    }
                                }
                            } else {
                                Session::flash('message', 'Please Enter All Details!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('new_orders');
                            }
                        } else if(($data['return_type'] == "Exchange")) {
                            if (isset($data['det_return_type']) && count($data['det_return_type']) != 0) {
                                if (in_array("Refund", $data['det_return_type'])) {
                                    Session::flash('message', 'Only Exchange is Available!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('new_orders');
                                } else {
                                    $order = new Orders();

                                    if($order) {
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
                                        $order->payment_mode = $data['payment_mode'];
                                        $order->contact_person = $data['contact_person'];
                                        $order->contact_email = $data['contact_email'];
                                        $order->contact_no = $data['contact_no'];
                                        $order->shipping_address_flag = $data['shipping_address_flag'];
                                        $order->shipping_address = $data['shipping_address'];
                                        $order->city = $data['city'];
                                        $order->pincode = $data['pincode'];
                                        $order->total_items = $data['total_items'];
                                        // $order->tax_amount = $data['tax_amount'];
                                        $order->total_amount = $data['det_sub_tot'];
                                        $order->service_charge = $data['det_serv_charge'];
                                        $order->shipping_charge = $data['det_shipping_charge'];
                                        $order->net_amount = $data['det_net_amount'];
                                        $order->ref_order_id = $data['order_id'];
                                        $order->grv_id = $data['grv_id'];
                                        $order->order_status = 1;
                                        $order->payment_status = 0;
                                        $order->remarks = NULL;
                                        $order->replace_order = 'Yes';
                                        $order->is_block = 1;

                                        if($order->save()) {
                                            if (isset($data['det_product_id']) && count($data['det_product_id']) != 0) {
                                                foreach ($data['det_product_id'] as $key => $value) {
                                                    $order_details = new OrderDetails();
                                                    $order_details->order_id = $order->id;
                                                    $order_details->product_id = $value;
                                                    
                                                    if(isset($data['det_product_title'][$key])) {
                                                        $order_details->product_title = $data['det_product_title'][$key];
                                                    } else {
                                                        $order_details->product_title = NULL;
                                                    }
                                                    
                                                    if(isset($data['det_return_qty'][$key])) {
                                                        $order_details->order_qty = $data['det_return_qty'][$key];
                                                    } else {
                                                        $order_details->order_qty = NULL;
                                                    }

                                                    if(isset($data['cge_atts'][$key]) && ($data['cge_atts'][$key] == "Yes")) {
                                                        if(isset($data['cge_att_name'][$key]) && $data['cge_att_name'][$key]) {
                                                            $order_details->att_name = $data['cge_att_name'][$key];
                                                        } else {
                                                            if(isset($data['det_att_name'][$key])) {
                                                                $order_details->att_name = $data['det_att_name'][$key];
                                                            } else {
                                                                $order_details->att_name = NULL;
                                                            }
                                                        }

                                                        if(isset($data['cge_att_value'][$key]) && $data['cge_att_value'][$key]) {
                                                            $order_details->att_value = $data['cge_att_value'][$key];
                                                        } else {
                                                            if(isset($data['det_att_value'][$key])) {
                                                                $order_details->att_value = $data['det_att_value'][$key];
                                                            } else {
                                                                $order_details->att_value = NULL;
                                                            }
                                                        }
                                                    } else {
                                                        if(isset($data['det_att_name'][$key])) {
                                                            $order_details->att_name = $data['det_att_name'][$key];
                                                        } else {
                                                            $order_details->att_name = NULL;
                                                        }

                                                        if(isset($data['det_att_value'][$key])) {
                                                            $order_details->att_value = $data['det_att_value'][$key];
                                                        } else {
                                                            $order_details->att_value = NULL;
                                                        }
                                                    }          

                                                    if(isset($data['det_tax'][$key])) {
                                                        $order_details->tax = $data['det_tax'][$key];
                                                    } else {
                                                        $order_details->tax = NULL;
                                                    }

                                                    if(isset($data['det_tax_type'][$key])) {
                                                        $order_details->tax_type = $data['det_tax_type'][$key];
                                                    } else {
                                                        $order_details->tax_type = NULL;
                                                    }

                                                    if(isset($data['det_unitprice'][$key])) {
                                                        $order_details->unitprice = $data['det_unitprice'][$key];
                                                    } else {
                                                        $order_details->unitprice = NULL;
                                                    }

                                                    // if(isset($data['det_return_tax_amount'][$key])) {
                                                    //     $order_details->tax_amount = $data['det_return_tax_amount'][$key];
                                                    // } else {
                                                    //     $order_details->tax_amount = NULL;
                                                    // }

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
                                                $order_trans->net_amount = $order->net_amount;
                                                $order_trans->amountpaid = "Unpaid";
                                                $order_trans->paymentmode = $data['payment_mode'];
                                                $order_trans->gatewaytransactionid = NULL;
                                                $order_trans->trans_status = "PENDING";
                                                $order_trans->remarks = NULL;
                                                $order_trans->is_block = 1;

                                                if($order_trans->save()) {
                                                    $sus3 = 1;
                                                }
                                            } else if($data['payment_mode'] == 2) {
                                                if($order) {
                                                    $order->order_status = 1;
                                                    $order->payment_status = 0;
                                                    $order->save();
                                                    $sus3 = 1;
                                                }
                                            } 

                                            if($sus2 == 1 && $sus3 == 1) {
                                                if (isset($data['grv_det_id']) && count($data['grv_det_id']) != 0) {
                                                    foreach ($data['grv_det_id'] as $keys => $values) {
                                                        $grv_details = GrvOrdersDetails::Where('id', $values)->first();
                                                        $grv_details->grv_issued = "Yes";
                                                        $grv_details->save();
                                                    }
                                                }

                                                $grv = GrvOrders::Where('id', $data['grv_id'])->first();
                                                if($grv) {
                                                    $grv_dets = GrvOrdersDetails::Where('grv_id', $grv->id)->get();
                                                    if($grv_dets->contains('grv_issued', 'No')){
                                                        $grv->grv_status = 1;
                                                    } else {
                                                        $grv->grv_status = 2;
                                                    }
                                                    $grv->save();
                                                }

                                                $adm = EmailSettings::where('id', 1)->first();
                                                $admin_email = "info@grocery360.in";
                                                if($adm) {
                                                    $admin_email = $adm->contact_email;
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
                                                $site_name = "";
                                                if($general){
                                                    $site_name = $general->site_name;
                                                } else {
                                                    $site_name = "grocery360.in";
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
                                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: center;"> '.$value->product_title.' '. $att_tit .'</td>
                                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: center;"> '.$value->order_qty.'</td>
                                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: center;">Rs.  '.$value->unitprice.'</td>
                                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs.  '.$value->totalprice.'</td>
                                                        </tr>';
                                                    }
                                                }

                                                if($order) {
                                                    $order->net_commision = $net_comis;
                                                    $order->net_merchant_amout = $net_mer_amt;
                                                    $order->save();
                                                }

                                                $ships = ShippingAddress::Where('user_id', $user->id)->Where('is_block', 1)->first();
                                                if(isset($data['shipping_address_flag']) && $data['shipping_address_flag'] == 1) {
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
                                                $headers.= "From: Grocery360 <order@grocery360.in>" . "\r\n";
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
                                
                                                // if(1==1) {
                                                if(mail($to,$subject,$txt,$headers)){
                                                    mail($to2,$subject,$txt,$headers);
                                                    if($user->phone) {
                                                        $text = "Replacement order successful - Order Reference Code  - ".$order_code.", grocery360.in";
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
                                                        } else {
                                                            Session::flash('message', 'Order placed & Email Send Successfully!'); 
                                                            Session::flash('alert-class', 'alert-success');
                                                        }
                                                        return redirect()->route('all_orders');
                                                    } else {
                                                        Session::flash('message', 'Order Placed & Mail Send Successfully!'); 
                                                        Session::flash('alert-class', 'alert-success');
                                                    }
                                                    return redirect()->route('all_orders');
                                                } else {
                                                    if($user->phone) {
                                                        $text = "Thanks for shopping with us.Plz note the Order Code - ".$order_code.", grocery360.in";
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
                                                        } else {
                                                            Session::flash('message', 'Order placed Successfully!'); 
                                                            Session::flash('alert-class', 'alert-success');
                                                        }
                                                        return redirect()->route('all_orders');
                                                    } else {
                                                        Session::flash('message', 'Order Placed Successfully!'); 
                                                        Session::flash('alert-class', 'alert-success');
                                                    }
                                                    return redirect()->route('all_orders');
                                                }
                                            } else {
                                                Orders::where('id', $order->id)->delete();
                                                Session::flash('message', 'Replace New Orders Placed Failed!'); 
                                                Session::flash('alert-class', 'alert-danger');
                                                return redirect()->route('new_orders');
                                            }
                                        } else {
                                            Session::flash('message', 'Replace New Orders Placed Failed!'); 
                                            Session::flash('alert-class', 'alert-danger');
                                            return redirect()->route('new_orders');
                                        }
                                    } else {
                                        Session::flash('message', 'New Order Created Not Possible!'); 
                                        Session::flash('alert-class', 'alert-danger');
                                        return redirect()->route('new_orders');      
                                    }
                                }
                            } else {
                                Session::flash('message', 'Please Enter All Details!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('new_orders');
                            }
                        } else {
                            Session::flash('message', 'Please Select Return Type!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->back();
                        }
                    } else {
                        Session::flash('message', 'Invalid Customer, Please Check Customer!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->back();
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

    public function GetGRV( Request $request) {   
        $id = 0;
        $error = 0;
        $r_type = 0;
        if($request->ajax() && isset($request->id)){
            $id = $request->id;
            $r_type = $request->r_type;
            if($id != 0) {
                $grv = GrvOrders::where('id',$id)->where('grv_status', 1)->first();
                if($grv) {
                    $re_orders = ReturnOrder::Where('id', $grv->return_order_id)->first();
                    $orders = Orders::Where('id', $grv->order_id)->first();
                    if($re_orders && $orders && ($re_orders->order_id == $orders->id)) {
                        $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                        $re_orders['details'] = ReturnOrderDetails::Where('return_order_id', $re_orders->id)->get();
                        $grv['details'] = GrvOrdersDetails::Where('grv_id', $grv->id)->Where('grv_issued', 'No')->get();
                        $dets = "";
                        $details = "";
                        if(sizeof($grv['details']) != 0) {
                            foreach ($grv['details'] as $key => $value) {
                                if($value->return_type == $r_type) {
                                    $attributes = "";
                                    if(isset($value->att_name) && $value->att_name != 0) {
                                        if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) {
                                            $attributes = '<span>('.$value->AttName->att_name.' : '.$value->AttValue->att_value.')</span>';
                                        }
                                    }

                                    $shiping = "";
                                    if ($value->Products->tax_type == 2 ) {
                                        $shiping = '<input type="hidden" name="tot_shipping_charge[]" class="gj_det_spc" value="'.($value->product_id ? $value->Products->shiping_charge : 0).'">';
                                    } else {
                                        $shiping ='<input type="hidden" name="tot_shipping_charge[]" class="gj_det_spc" value="0">';
                                    }

                                    $a_product = ProductsAttributes::Where('product_id', $value->product_id)->get();
                                    $a_product = $a_product->unique('attribute_name');
                                    $optz = "";
                                    if(sizeof($a_product) != 0) {
                                        foreach ($a_product as $apkey => $apvalue) {
                                            $optz.= '<option value="'.$apvalue->AttributeName->id.'">'.$apvalue->AttributeName->att_name.'</option>';
                                        }
                                    }

                                    $details.='<tr class="gj_tr_det" id="gj_tr_det_'.($key+1).'">
                                        <td>
                                            <input type="hidden" name="grv_det_id[]" class="grv_det_id" value="'.$value->id.'" placeholder="Enter GRV Details ID">

                                            <input type="hidden" name="det_product_id[]" class="det_product_id" value="'.$value->product_id.'" placeholder="Enter Product ID">

                                            <input type="hidden" name="det_att_name[]" class="det_att_name" value="'.$value->att_name.'" placeholder="Enter Attribute Name">

                                            <input type="hidden" name="det_att_value[]" class="det_att_value" value="'.$value->att_value.'" placeholder="Enter Attribute Value">

                                            <input type="hidden" name="det_tax[]" class="det_tax" value="'.$value->tax.'" placeholder="Enter Tax Value">

                                            <input type="hidden" name="det_tax_type[]" class="det_tax_type" value="'.$value->tax_type.'" placeholder="Enter Tax Type">

                                            <input type="hidden" name="det_product_title[]" class="det_product_title" value="'.$value->product_title.'" placeholder="Enter Product Title" readonly>

                                            <span>
                                                '.$value->product_title.'
                                                '.$attributes.'
                                            </span>
                                        </td>

                                        <!-- <td>
                                            <select name="cge_atts[]" class="form-control cge_atts">
                                                <option value="">Change Attributes?</option>
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select> 
                                        </td>

                                        <td>
                                            <select name="cge_att_name[]" class="form-control cge_att_name">
                                                <option value="">Change Attribute Name</option>
                                                '.$optz.'
                                            </select> 
                                        </td>

                                        <td>
                                            <select name="cge_att_value[]" class="form-control cge_att_value">
                                                <option value="">Change Attribute Value</option>
                                            </select> 
                                        </td> -->

                                        <td>
                                            <input type="hidden" name="det_old_order_qty[]" class="det_old_order_qty" value="'.$value->order_qty.'" placeholder="Enter Quantity" min="1">

                                            <input type="number" name="det_order_qty[]" class="det_order_qty" value="'.$value->order_qty.'" placeholder="Enter Quantity" min="1" disabled>

                                            <input type="hidden" name="det_order_qty[]" class="det_order_qty" value="'.$value->order_qty.'" placeholder="Enter Quantity" min="1">
                                        </td>

                                        <td>
                                            <input type="text" name="det_h_unitprice[]" class="det_h_unitprice" value="'.$value->unitprice.'" placeholder="Enter Price" disabled>

                                            <input type="hidden" name="det_unitprice[]" class="det_unitprice" value="'.$value->unitprice.'" placeholder="Enter Price">
                                        </td>

                                        <!--<td>
                                            <input type="text" name="det_h_tax_amount[]" class="det_h_tax_amount" value="'.$value->tax_amount.'" placeholder="Enter Tax Amount" disabled>

                                            <input type="hidden" name="det_tax_amount[]" class="det_tax_amount" value="'.$value->tax_amount.'" placeholder="Enter Tax Amount">
                                            <input type="hidden" name="det_tax[]" class="det_tax" value="'.$value->tax.'" placeholder="Enter Tax Amount">
                                        </td>-->

                                        <td>
                                            <select name="det_return_type[]" class="form-control det_return_type">
                                                <option value="">Select Return Type</option>
                                                <option '.($value->return_type == 'Exchange' ? "selected" : "").' value="Exchange">Exchange</option>
                                                <option '.($value->return_type == 'Replacement' ? "selected" : "").' value="Replacement">Replacement</option>
                                                <option '.($value->return_type == 'Refund' ? "selected" : "").' value="Refund">Refund</option>
                                            </select>
                                        </td>

                                        <td>
                                            <input type="hidden" name="det_old_return_qty[]" class="det_old_return_qty" value="'.$value->return_qty.'" placeholder="Enter Quantity" min="1">
                                            <input type="hidden" name="assign_qty[]" class="assign_qty" value="'.$value->assign_qty.'" placeholder="Enter Quantity">

                                            <input type="number" name="det_return_qty[]" class="det_return_qty" value="'.$value->return_qty.'" placeholder="Enter Quantity" min="1">
                                        </td>

                                        <td>
                                            <input type="text" name="det_h_return_amount[]" class="det_h_return_amount" value="'.$value->return_amount.'" placeholder="Enter Price" disabled>

                                            <input type="hidden" name="det_return_amount[]" class="det_return_amount" value="'.$value->return_amount.'" placeholder="Enter Price">
                                        </td>

                                        <!--<td>
                                            <input type="text" name="det_h_return_tax_amount[]" class="det_h_return_tax_amount" value="'.$value->return_tax_amount.'" placeholder="Enter Tax" disabled>

                                            <input type="hidden" name="det_return_tax_amount[]" class="det_return_tax_amount" value="'.$value->return_tax_amount.'" placeholder="Enter Tax">
                                        </td>-->

                                        <td>
                                            <input type="text" name="det_h_totalprice[]" class="det_h_totalprice" value="'.$value->return_amount.'" placeholder="Enter Total Price" disabled>

                                            <input type="hidden" name="det_totalprice[]" class="det_totalprice" value="'.$value->return_amount.'" placeholder="Enter Total Price">

                                            <input type="hidden" name="tot_service_charge[]" class="gj_det_sc" value="'.($value->product_id ? $value->Products->service_charge : 0).'">

                                            '.$shiping.'
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger gj_del_det" data-del-id="'.$value->id.'"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>';
                                }           
                            }
                        } else {
                            /*$details.='<tr id="gj_tr_det_1">
                                <td>
                                    <p class="gj_nodata">New Order Not Possible</p>
                                </td>
                            </tr>';*/
                            echo $error = 0;die();
                        }

                        $dets = '<div class="gj_odr_det_resp table-responsive">
                            <table class="table table-stripped table-bordered gj_tab_odr_det">
                                <thead>
                                    <tr>
                                        <th>Product Title</th>
                                        <!--<th>Change Attributes</th>-->
                                        <!--<th>Attribute Name</th>-->
                                        <!--<th>Attribute Value</th>-->
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <!--<th>Tax</th>-->
                                        <th>Return Type</th>
                                        <th>Return Qty</th>
                                        <th>Return Amount</th>
                                        <!--<th>Return Tax</th>-->
                                        <th>Total Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="gj_odr_det">
                                    '.$details.'
                                    <tr>
                                        <td colspan="6" class="text-right"> <b> Sub Total </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_det_sub_tot">0.00</span> </span> </b> </td>

                                        <input type="hidden" name="det_sub_tot" id="det_sub_tot">
                                        <input type="hidden" name="det_tax_total" id="det_tax_total">
                                        <input type="hidden" name="det_total_items" id="det_total_items">
                                        <input type="hidden" name="det_net_amount" id="det_net_amount">
                                        <input type="hidden" name="cut_off" id="cut_off">
                                        <input type="hidden" name="cod_charge" id="cod_charge">
                                        <input type="hidden" name="det_serv_charge" id="det_serv_charge">
                                        <input type="hidden" name="det_shipping_charge" id="det_shipping_charge">
                                    </tr>

                                    <!-- <tr>
                                        <td colspan="6" class="text-right"> <b> Service Charge </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_det_sc_tot">'.($orders->shipping_charge ? $orders->shipping_charge : "0.00").'</span> </span> </b> </td>                                           
                                    </tr> -->

                                    <tr>
                                        <td colspan="6" class="text-right"> <b> Tax Total </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_ch_tax_tot">0.00</span> </span> </b> </td>
                                    </tr>

                                    <tr>
                                        <td colspan="6" class="text-right"> <b> Shipping Charge </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_ch_shc_tot">0.00</span> </span> </b> </td>
                                    </tr>

                                    <tr class="gj_cod_set">
                                        <td colspan="6" class="text-right"> <b> COD Charge </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_ch_cod">0.00</span> </span> </b> </td>
                                    </tr>

                                    <tr>
                                        <td colspan="6" class="text-right"> <b> Grand Total </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_det_grand_tot">0.00</span> </span> </b> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>';

                        $error ='';
                        $error.='<div class="col-md-12">
                            <div class="form-group">
                                <label for="payment_mode">Payment Mode</label>

                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" '.($orders->payment_mode == 1 ? "checked" : "").' name="payment_mode" value="1"> Cash On Delivery
                                    </span>

                                    <span class="gj_py_ro">
                                        <input type="radio" '.($orders->payment_mode == 2 ? "checked" : "").' name="payment_mode" value="2"> Online
                                    </span>
                                </div>

                                <input class="form-control gj_order_id" placeholder="Order ID" name="order_id" type="hidden" value="'.$re_orders->order_id.'" id="order_id">

                                <input class="form-control gj_user_id" placeholder="User ID" name="user_id" type="hidden" value="'.$re_orders->user_id.'" id="user_id">
                            </div>

                            <div class="form-group">
                                <label for="delivery_date">Delivery Date</label>

                                <input class="form-control gj_delivery_date" placeholder="Delivery Date" name="delivery_date" type="date" id="delivery_date" autocomplete="new-password" value="'.date("Y-m-d", strtotime($orders->delivery_date)).'">
                            </div>

                            <div class="form-group">
                                <label for="order_status">Order Status</label>

                                <select id="order_status" name="order_status" class="form-control gj_edt_order_status">
                                    <option value="1" selected>Order Placed</option>
                                    <option value="2">Order Dispatched</option>
                                    <option value="3">Order Delivered </option>
                                    <option value="4">Order Complete</option>
                                    <option value="5">Order Cancelled</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="contact_person">Contact Person</label>

                                <input class="form-control gj_contact_person" placeholder="Contact Person" name="h_contact_person" type="text" value="'.$orders->contact_person.'" id="h_contact_person" disabled>

                                <input class="form-control gj_contact_person" placeholder="Contact Person" name="contact_person" type="hidden" value="'.$orders->contact_person.'" id="contact_person">

                                <input class="form-control gj_contact_email" placeholder="Contact Person" name="contact_email" type="hidden" value="'.$orders->contact_email.'" id="contact_email">
                            </div>

                            <div class="form-group">
                                <label for="contact_no">Contact Number</label>

                                <input class="form-control gj_contact_no" placeholder="Contact Number" name="h_contact_no" type="text" value="'.$orders->contact_no.'" id="h_contact_no" disabled>

                                <input class="form-control gj_contact_no" placeholder="Contact Number" name="contact_no" type="hidden" value="'.$orders->contact_no.'" id="contact_no">
                            </div>

                            <div class="form-group">
                                <label for="shipping_address">Shipping Address</label>

                                <input class="form-control gj_shipping_address" placeholder="Shipping Address" name="h_shipping_address" type="text" value="'.$orders->shipping_address.'" id="h_shipping_address" disabled>

                                <input class="form-control gj_shipping_address" placeholder="Shipping Address" name="shipping_address" type="hidden" value="'.$orders->shipping_address.'" id="shipping_address">

                                <input class="form-control gj_shipping_address_flag" placeholder="Shipping Address" name="shipping_address_flag" type="hidden" value="'.$orders->shipping_address_flag.'" id="shipping_address_flag">

                                <input class="form-control gj_city" placeholder="Shipping Address" name="city" type="hidden" value="'.$orders->city.'" id="city">

                                <input class="form-control gj_pincode" placeholder="Shipping Address" name="pincode" type="hidden" value="'.$orders->pincode.'" id="pincode">
                            </div>

                            <div class="form-group">
                                <label for="total_items">Total Items</label>

                                <input class="form-control gj_total_items" placeholder="Total Items" name="h_total_items" type="text" value="'.$re_orders->total_items.'" id="h_total_items" disabled>

                                <input class="form-control gj_total_items" placeholder="Total Items" name="total_items" type="hidden" value="'.$re_orders->total_items.'" id="total_items">
                            </div>

                            <div class="form-group">
                                <label for="discount_flag">Discount Flag</label>

                                <input class="form-control gj_discount_flag" placeholder="Discount Flag" name="discount_flag" type="number" id="discount_flag" autocomplete="new-password" value="'.$orders->discount_flag.'">
                            </div>

                            <div class="form-group">
                                <label for="discount">Discount</label>

                                <input class="form-control gj_discount" placeholder="Discount" name="discount" type="number" id="discount" autocomplete="new-password" value="'.$orders->discount.'">
                            </div>

                            <div class="form-group">
                                <label for="shipping_charge">Shipping Charge</label>

                                <input class="form-control gj_shipping_charge" placeholder="Shipping Charge" name="h_shipping_charge" type="text" value="'.$orders->shipping_charge.'" id="h_shipping_charge" disabled>

                                <input class="form-control gj_shipping_charge" placeholder="Shipping Charge" name="shipping_charge" type="hidden" value="'.$orders->shipping_charge.'" id="shipping_charge">
                            </div>

                            <!-- <div class="form-group">
                                <label for="tax_amount">Tax Amount</label>

                                <input class="form-control gj_tax_amount" placeholder="Tax Amount" name="h_tax_amount" type="text" value="'.$orders->tax_amount.'" id="h_tax_amount" disabled>

                                <input class="form-control gj_tax_amount" placeholder="Tax Amount" name="tax_amount" type="hidden" value="'.$orders->tax_amount.'" id="tax_amount">
                            </div>-->

                            <div class="form-group">
                                <label for="net_amount">Net Amount</label>

                                <input class="form-control gj_net_amount" placeholder="Net Amount" name="h_net_amount" type="text" value="'.$re_orders->net_amount.'" id="h_net_amount" disabled>

                                <input class="form-control gj_net_amount" placeholder="Net Amount" name="net_amount" type="hidden" value="'.$re_orders->net_amount.'" id="net_amount">
                            </div>

                            <div class="form-group">
                                <label for="payment_status">Payment Status</label>

                                <select id="payment_status" name="payment_status" class="form-control gj_edt_payment_status">
                                    <option value="0" selected>Pending</option>
                                    <option value="1">Success</option>
                                    <option value="2">Failed </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="delivery_status">Delivery Status</label>

                                <select id="delivery_status" name="delivery_status" class="form-control gj_edt_delivery_status">
                                    <option value="0" selected>Pending</option>
                                    <option value="1">Success</option>
                                    <option value="2">Failed </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="remarks">Remarks</label>

                                <textarea class="form-control gj_remarks" placeholder="Remarks" rows="5" name="remarks" cols="50" id="remarks">'.$orders->remarks.'</textarea>
                            </div>

                            <p class="error gj_note">Note : In New Order to exchange the product, the admin need to select "Change Attributes" to "YES" & Choose "Attribute Name"(ie Size or Color) and select their corresponding "Attribute Value"</p>

                            '.$dets.'

                            <input class="btn btn-primary" type="submit" value="Update" autocomplete="new-password">
                        </div>';

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

    public function GetEXGRV( Request $request) {   
        $id = 0;
        $error = 0;
        $r_type = 0;
        if($request->ajax() && isset($request->id)){
            $id = $request->id;
            $r_type = $request->r_type;
            if($id != 0) {
                $grv = GrvOrders::where('id',$id)->where('grv_status', 1)->first();
                if($grv) {
                    $re_orders = ReturnOrder::Where('id', $grv->return_order_id)->first();
                    $orders = Orders::Where('id', $grv->order_id)->first();
                    if($re_orders && $orders && ($re_orders->order_id == $orders->id)) {
                        $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                        $re_orders['details'] = ReturnOrderDetails::Where('return_order_id', $re_orders->id)->get();
                        $grv['details'] = GrvOrdersDetails::Where('grv_id', $grv->id)->Where('grv_issued', 'No')->get();
                        $dets = "";
                        $details = "";
                        if(sizeof($grv['details']) != 0) {
                            foreach ($grv['details'] as $key => $value) {
                                if($value->return_type == $r_type) {
                                    $attributes = "";
                                    if(isset($value->att_name) && $value->att_name != 0) {
                                        if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) {
                                            $attributes = '<span>('.$value->AttName->att_name.' : '.$value->AttValue->att_value.')</span>';
                                        }
                                    }

                                    $shiping = "";
                                    if ($value->Products->tax_type == 2 ) {
                                        $shiping = '<input type="hidden" name="tot_shipping_charge[]" class="gj_det_spc" value="'.($value->product_id ? $value->Products->shiping_charge : 0).'">';
                                    } else {
                                        $shiping ='<input type="hidden" name="tot_shipping_charge[]" class="gj_det_spc" value="0">';
                                    }

                                    $a_product = ProductsAttributes::Where('product_id', $value->product_id)->get();
                                    $a_product = $a_product->unique('attribute_name');
                                    $optz = "";
                                    if(sizeof($a_product) != 0) {
                                        foreach ($a_product as $apkey => $apvalue) {
                                            $optz.= '<option value="'.$apvalue->AttributeName->id.'">'.$apvalue->AttributeName->att_name.'</option>';
                                        }
                                    }

                                    $details.='<tr class="gj_tr_det" id="gj_tr_det_'.($key+1).'">
                                        <td>
                                            <input type="hidden" name="grv_det_id[]" class="grv_det_id" value="'.$value->id.'" placeholder="Enter GRV Details ID">

                                            <input type="hidden" name="det_product_id[]" class="det_product_id" value="'.$value->product_id.'" placeholder="Enter Product ID">

                                            <input type="hidden" name="det_att_name[]" class="det_att_name" value="'.$value->att_name.'" placeholder="Enter Attribute Name">

                                            <input type="hidden" name="det_att_value[]" class="det_att_value" value="'.$value->att_value.'" placeholder="Enter Attribute Value">

                                            <input type="hidden" name="det_tax[]" class="det_tax" value="'.$value->tax.'" placeholder="Enter Tax Value">

                                            <input type="hidden" name="det_tax_type[]" class="det_tax_type" value="'.$value->tax_type.'" placeholder="Enter Tax Type">

                                            <input type="hidden" name="det_product_title[]" class="det_product_title" value="'.$value->product_title.'" placeholder="Enter Product Title" readonly>

                                            <span>
                                                '.$value->product_title.'
                                                '.$attributes.'
                                            </span>
                                        </td>

                                        <td>
                                            <select name="cge_atts[]" class="form-control cge_atts">
                                                <option value="">Change Attributes?</option>
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select> 
                                        </td>

                                        <td>
                                            <select name="cge_att_name[]" class="form-control cge_att_name">
                                                <option value="">Change Attribute Name</option>
                                                '.$optz.'
                                            </select> 
                                        </td>

                                        <td>
                                            <select name="cge_att_value[]" class="form-control cge_att_value">
                                                <option value="">Change Attribute Value</option>
                                            </select> 
                                        </td>

                                        <td>
                                            <input type="hidden" name="det_old_order_qty[]" class="det_old_order_qty" value="'.$value->order_qty.'" placeholder="Enter Quantity" min="1">

                                            <input type="number" name="det_order_qty[]" class="det_order_qty" value="'.$value->order_qty.'" placeholder="Enter Quantity" min="1" disabled>

                                            <input type="hidden" name="det_order_qty[]" class="det_order_qty" value="'.$value->order_qty.'" placeholder="Enter Quantity" min="1">
                                        </td>

                                        <td>
                                            <input type="text" name="det_h_unitprice[]" class="det_h_unitprice" value="'.$value->unitprice.'" placeholder="Enter Price" disabled>

                                            <input type="hidden" name="det_unitprice[]" class="det_unitprice" value="'.$value->unitprice.'" placeholder="Enter Price">
                                        </td>

                                        <!--<td>
                                            <input type="text" name="det_h_tax_amount[]" class="det_h_tax_amount" value="'.$value->tax_amount.'" placeholder="Enter Tax Amount" disabled>

                                            <input type="hidden" name="det_tax_amount[]" class="det_tax_amount" value="'.$value->tax_amount.'" placeholder="Enter Tax Amount">
                                            <input type="hidden" name="det_tax[]" class="det_tax" value="'.$value->tax.'" placeholder="Enter Tax Amount">
                                        </td>-->

                                        <td>
                                            <select name="det_return_type[]" class="form-control det_return_type">
                                                <option value="">Select Return Type</option>
                                                <option '.($value->return_type == 'Exchange' ? "selected" : "").' value="Exchange">Exchange</option>
                                                <option '.($value->return_type == 'Replacement' ? "selected" : "").' value="Replacement">Replacement</option>
                                                <option '.($value->return_type == 'Refund' ? "selected" : "").' value="Refund">Refund</option>
                                            </select>
                                        </td>

                                        <td>
                                            <input type="hidden" name="det_old_return_qty[]" class="det_old_return_qty" value="'.$value->return_qty.'" placeholder="Enter Quantity" min="1">
                                            <input type="hidden" name="assign_qty[]" class="assign_qty" value="'.$value->assign_qty.'" placeholder="Enter Quantity">

                                            <input type="number" name="det_return_qty[]" class="det_return_qty" value="'.$value->return_qty.'" placeholder="Enter Quantity" min="1">
                                        </td>

                                        <td>
                                            <input type="text" name="det_h_return_amount[]" class="det_h_return_amount" value="'.$value->return_amount.'" placeholder="Enter Price" disabled>

                                            <input type="hidden" name="det_return_amount[]" class="det_return_amount" value="'.$value->return_amount.'" placeholder="Enter Price">
                                        </td>

                                        <!--<td>
                                            <input type="text" name="det_h_return_tax_amount[]" class="det_h_return_tax_amount" value="'.$value->return_tax_amount.'" placeholder="Enter Tax" disabled>

                                            <input type="hidden" name="det_return_tax_amount[]" class="det_return_tax_amount" value="'.$value->return_tax_amount.'" placeholder="Enter Tax">
                                        </td>-->

                                        <td>
                                            <input type="text" name="det_h_totalprice[]" class="det_h_totalprice" value="'. $value->return_amount.'" placeholder="Enter Total Price" disabled>

                                            <input type="hidden" name="det_totalprice[]" class="det_totalprice" value="'.$value->return_amount.'" placeholder="Enter Total Price">

                                            <input type="hidden" name="tot_service_charge[]" class="gj_det_sc" value="'.($value->product_id ? $value->Products->service_charge : 0).'">

                                            '.$shiping.'
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger gj_del_det" data-del-id="'.$value->id.'"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>';
                                }           
                            }
                        } else {
                            /*$details.='<tr id="gj_tr_det_1">
                                <td>
                                    <p class="gj_nodata">New Order Not Possible</p>
                                </td>
                            </tr>';*/
                            echo $error = 0;die();
                        }

                        $dets = '<div class="gj_odr_det_resp table-responsive">
                            <table class="table table-stripped table-bordered gj_tab_odr_det">
                                <thead>
                                    <tr>
                                        <th>Product Title</th>
                                        <th>Change Attributes</th>
                                        <th>Attribute Name</th>
                                        <th>Attribute Value</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <!--<th>Tax</th>-->
                                        <th>Return Type</th>
                                        <th>Return Qty</th>
                                        <th>Return Amount</th>
                                        <!--<th>Return Tax</th>-->
                                        <th>Total Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="gj_odr_det">
                                    '.$details.'
                                    <tr>
                                        <td colspan="9" class="text-right"> <b> Sub Total </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_det_sub_tot">0.00</span> </span> </b> </td>

                                        <input type="hidden" name="det_sub_tot" id="det_sub_tot">
                                        <input type="hidden" name="det_tax_total" id="det_tax_total">
                                        <input type="hidden" name="det_total_items" id="det_total_items">
                                        <input type="hidden" name="det_net_amount" id="det_net_amount">
                                        <input type="hidden" name="cut_off" id="cut_off">
                                        <input type="hidden" name="cod_charge" id="cod_charge">
                                        <input type="hidden" name="det_serv_charge" id="det_serv_charge">
                                        <input type="hidden" name="det_shipping_charge" id="det_shipping_charge">
                                    </tr>

                                    <!-- <tr>
                                        <td colspan="9" class="text-right"> <b> Service Charge </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_det_sc_tot">'.($orders->shipping_charge ? $orders->shipping_charge : "0.00").'</span> </span> </b> </td>                                           
                                    </tr> -->

                                    <tr>
                                        <td colspan="9" class="text-right"> <b> Tax Total </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_ch_tax_tot">0.00</span> </span> </b> </td>
                                    </tr>

                                    <tr>
                                        <td colspan="9" class="text-right"> <b> Shipping Charge </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_ch_shc_tot">0.00</span> </span> </b> </td>
                                    </tr>

                                    <tr class="gj_cod_set">
                                        <td colspan="9" class="text-right"> <b> COD Charge </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_ch_cod">0.00</span> </span> </b> </td>
                                    </tr>

                                    <tr>
                                        <td colspan="9" class="text-right"> <b> Grand Total </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_det_grand_tot">0.00</span> </span> </b> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>';

                        $error ='';
                        $error.='<div class="col-md-12">
                            <div class="form-group">
                                <label for="payment_mode">Payment Mode</label>

                                <div class="gj_py_ro_div">
                                    <span class="gj_py_ro">
                                        <input type="radio" '.($orders->payment_mode == 1 ? "checked" : "").' name="payment_mode" value="1"> Cash On Delivery
                                    </span>

                                    <span class="gj_py_ro">
                                        <input type="radio" '.($orders->payment_mode == 2 ? "checked" : "").' name="payment_mode" value="2"> Online
                                    </span>
                                </div>

                                <input class="form-control gj_order_id" placeholder="Order ID" name="order_id" type="hidden" value="'.$re_orders->order_id.'" id="order_id">

                                <input class="form-control gj_user_id" placeholder="User ID" name="user_id" type="hidden" value="'.$re_orders->user_id.'" id="user_id">
                            </div>

                            <div class="form-group">
                                <label for="delivery_date">Delivery Date</label>

                                <input class="form-control gj_delivery_date" placeholder="Delivery Date" name="delivery_date" type="date" id="delivery_date" autocomplete="new-password" value="'.date("Y-m-d", strtotime($orders->delivery_date)).'">
                            </div>

                            <div class="form-group">
                                <label for="order_status">Order Status</label>

                                <select id="order_status" name="order_status" class="form-control gj_edt_order_status">
                                    <option value="1" selected>Order Placed</option>
                                    <option value="2">Order Dispatched</option>
                                    <option value="3">Order Delivered </option>
                                    <option value="4">Order Complete</option>
                                    <option value="5">Order Cancelled</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="contact_person">Contact Person</label>

                                <input class="form-control gj_contact_person" placeholder="Contact Person" name="h_contact_person" type="text" value="'.$orders->contact_person.'" id="h_contact_person" disabled>

                                <input class="form-control gj_contact_person" placeholder="Contact Person" name="contact_person" type="hidden" value="'.$orders->contact_person.'" id="contact_person">

                                <input class="form-control gj_contact_email" placeholder="Contact Person" name="contact_email" type="hidden" value="'.$orders->contact_email.'" id="contact_email">
                            </div>

                            <div class="form-group">
                                <label for="contact_no">Contact Number</label>

                                <input class="form-control gj_contact_no" placeholder="Contact Number" name="h_contact_no" type="text" value="'.$orders->contact_no.'" id="h_contact_no" disabled>

                                <input class="form-control gj_contact_no" placeholder="Contact Number" name="contact_no" type="hidden" value="'.$orders->contact_no.'" id="contact_no">
                            </div>

                            <div class="form-group">
                                <label for="shipping_address">Shipping Address</label>

                                <input class="form-control gj_shipping_address" placeholder="Shipping Address" name="h_shipping_address" type="text" value="'.$orders->shipping_address.'" id="h_shipping_address" disabled>

                                <input class="form-control gj_shipping_address" placeholder="Shipping Address" name="shipping_address" type="hidden" value="'.$orders->shipping_address.'" id="shipping_address">

                                <input class="form-control gj_shipping_address_flag" placeholder="Shipping Address" name="shipping_address_flag" type="hidden" value="'.$orders->shipping_address_flag.'" id="shipping_address_flag">

                                <input class="form-control gj_city" placeholder="Shipping Address" name="city" type="hidden" value="'.$orders->city.'" id="city">

                                <input class="form-control gj_pincode" placeholder="Shipping Address" name="pincode" type="hidden" value="'.$orders->pincode.'" id="pincode">
                            </div>

                            <div class="form-group">
                                <label for="total_items">Total Items</label>

                                <input class="form-control gj_total_items" placeholder="Total Items" name="h_total_items" type="text" value="'.$re_orders->total_items.'" id="h_total_items" disabled>

                                <input class="form-control gj_total_items" placeholder="Total Items" name="total_items" type="hidden" value="'.$re_orders->total_items.'" id="total_items">
                            </div>

                            <div class="form-group">
                                <label for="discount_flag">Discount Flag</label>

                                <input class="form-control gj_discount_flag" placeholder="Discount Flag" name="discount_flag" type="number" id="discount_flag" autocomplete="new-password" value="'.$orders->discount_flag.'">
                            </div>

                            <div class="form-group">
                                <label for="discount">Discount</label>

                                <input class="form-control gj_discount" placeholder="Discount" name="discount" type="number" id="discount" autocomplete="new-password" value="'.$orders->discount.'">
                            </div>

                            <div class="form-group">
                                <label for="shipping_charge">Shipping Charge</label>

                                <input class="form-control gj_shipping_charge" placeholder="Shipping Charge" name="h_shipping_charge" type="text" value="'.$orders->shipping_charge.'" id="h_shipping_charge" disabled>

                                <input class="form-control gj_shipping_charge" placeholder="Shipping Charge" name="shipping_charge" type="hidden" value="'.$orders->shipping_charge.'" id="shipping_charge">
                            </div>

                            <!-- <div class="form-group">
                                <label for="tax_amount">Tax Amount</label>

                                <input class="form-control gj_tax_amount" placeholder="Tax Amount" name="h_tax_amount" type="text" value="'.$orders->tax_amount.'" id="h_tax_amount" disabled>

                                <input class="form-control gj_tax_amount" placeholder="Tax Amount" name="tax_amount" type="hidden" value="'.$orders->tax_amount.'" id="tax_amount">
                            </div>-->

                            <div class="form-group">
                                <label for="net_amount">Net Amount</label>

                                <input class="form-control gj_net_amount" placeholder="Net Amount" name="h_net_amount" type="text" value="'.$re_orders->net_amount.'" id="h_net_amount" disabled>

                                <input class="form-control gj_net_amount" placeholder="Net Amount" name="net_amount" type="hidden" value="'.$re_orders->net_amount.'" id="net_amount">
                            </div>

                            <div class="form-group">
                                <label for="payment_status">Payment Status</label>

                                <select id="payment_status" name="payment_status" class="form-control gj_edt_payment_status">
                                    <option value="0" selected>Pending</option>
                                    <option value="1">Success</option>
                                    <option value="2">Failed </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="delivery_status">Delivery Status</label>

                                <select id="delivery_status" name="delivery_status" class="form-control gj_edt_delivery_status">
                                    <option value="0" selected>Pending</option>
                                    <option value="1">Success</option>
                                    <option value="2">Failed </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="remarks">Remarks</label>

                                <textarea class="form-control gj_remarks" placeholder="Remarks" rows="5" name="remarks" cols="50" id="remarks">'.$orders->remarks.'</textarea>
                            </div>

                            <p class="error gj_note">Note : In New Order to exchange the product, the admin need to select "Change Attributes" to "YES" & Choose "Attribute Name"(ie Size or Color) and select their corresponding "Attribute Value"</p>

                            '.$dets.'

                            <input class="btn btn-primary" type="submit" value="Update" autocomplete="new-password">
                        </div>';

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

    public function GetCNGRV( Request $request) {   
        $id = 0;
        $error = 0;
        if($request->ajax() && isset($request->id)){
            $id = $request->id;
            if($id != 0) {
                $grv = GrvOrders::where('id',$id)->where('grv_status', 1)->first();
                if($grv) {
                    $re_orders = ReturnOrder::Where('id', $grv->return_order_id)->first();
                    $orders = Orders::Where('id', $grv->order_id)->first();
                    if($re_orders && $orders && ($re_orders->order_id == $orders->id)) {
                        $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                        $re_orders['details'] = ReturnOrderDetails::Where('return_order_id', $re_orders->id)->get();
                        if($request->type == 'get_GRV_cn') {
                            $grv['details'] = GrvOrdersDetails::Where('grv_id', $grv->id)->Where('return_type', 'Refund')->Where('grv_issued', 'No')->get();
                        } else {
                            $grv['details'] = GrvOrdersDetails::Where('grv_id', $grv->id)->Where('return_type', '!=', 'Refund')->Where('grv_issued', 'No')->get();
                        }
                        $dets = "";
                        $details = "";
                        if(sizeof($grv['details']) != 0) {
                            foreach ($grv['details'] as $key => $value) {
                                $attributes = "";
                                if(isset($value->att_name) && $value->att_name != 0) {
                                    if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) {
                                        $attributes = '<span>('.$value->AttName->att_name.' : '.$value->AttValue->att_value.')</span>';
                                    }
                                }

                                $shiping = "";
                                if ($value->Products->tax_type == 2 ) {
                                    $shiping = '<input type="hidden" name="tot_shipping_charge[]" class="gj_det_spc" value="'.($value->product_id ? $value->Products->shiping_charge : 0).'">';
                                } else {
                                    $shiping ='<input type="hidden" name="tot_shipping_charge[]" class="gj_det_spc" value="0">';
                                }

                                $details.='<tr class="gj_tr_det" id="gj_tr_det_'.($key+1).'">
                                    <td>
                                        <input type="hidden" name="grv_det_id[]" class="grv_det_id" value="'.$value->id.'" placeholder="Enter GRV Details ID">

                                        <input type="hidden" name="det_product_id[]" class="det_product_id" value="'.$value->product_id.'" placeholder="Enter Product ID">

                                        <input type="hidden" name="det_att_name[]" class="det_att_name" value="'.$value->att_name.'" placeholder="Enter Attribute Name">

                                        <input type="hidden" name="det_att_value[]" class="det_att_value" value="'.$value->att_value.'" placeholder="Enter Attribute Value">

                                        <input type="hidden" name="det_tax[]" class="det_tax" value="'.$value->tax.'" placeholder="Enter Tax Value">

                                        <input type="hidden" name="det_tax_type[]" class="det_tax_type" value="'.$value->tax_type.'" placeholder="Enter Tax Type">

                                        <input type="hidden" name="det_product_title[]" class="det_product_title" value="'.$value->product_title.'" placeholder="Enter Product Title" readonly>

                                        <span>
                                            '.$value->product_title.'
                                            '.$attributes.'
                                        </span>
                                    </td>

                                    <td>
                                        <input type="hidden" name="det_old_order_qty[]" class="det_old_order_qty" value="'.$value->order_qty.'" placeholder="Enter Quantity" min="1">

                                        <input type="number" name="det_order_qty[]" class="det_order_qty" value="'.$value->order_qty.'" placeholder="Enter Quantity" min="1" disabled>

                                        <input type="hidden" name="det_order_qty[]" class="det_order_qty" value="'.$value->order_qty.'" placeholder="Enter Quantity" min="1">
                                    </td>

                                    <td>
                                        <input type="text" name="det_h_unitprice[]" class="det_h_unitprice" value="'.$value->unitprice.'" placeholder="Enter Price" disabled>

                                        <input type="hidden" name="det_unitprice[]" class="det_unitprice" value="'.$value->unitprice.'" placeholder="Enter Price">
                                    </td>

                                    <!--<td>
                                        <input type="text" name="det_h_tax_amount[]" class="det_h_tax_amount" value="'.$value->tax_amount.'" placeholder="Enter Tax Amount" disabled>

                                        <input type="hidden" name="det_tax_amount[]" class="det_tax_amount" value="'.$value->tax_amount.'" placeholder="Enter Tax Amount">
                                        <input type="hidden" name="det_tax[]" class="det_tax" value="'.$value->tax.'" placeholder="Enter Tax Amount">
                                    </td>-->

                                    <td>
                                        <select name="det_return_type[]" class="form-control det_return_type">
                                            <option value="">Select Return Type</option>
                                            <option '.($value->return_type == 'Exchange' ? "selected" : "").' value="Exchange">Exchange</option>
                                            <option '.($value->return_type == 'Replacement' ? "selected" : "").' value="Replacement">Replacement</option>
                                            <option '.($value->return_type == 'Refund' ? "selected" : "").' value="Refund">Refund</option>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="hidden" name="det_old_return_qty[]" class="det_old_return_qty" value="'.$value->return_qty.'" placeholder="Enter Quantity" min="1">
                                        <input type="hidden" name="assign_qty[]" class="assign_qty" value="'.$value->assign_qty.'" placeholder="Enter Quantity">

                                        <input type="number" name="det_return_qty[]" class="det_return_qty" value="'.$value->return_qty.'" placeholder="Enter Quantity" min="1">
                                    </td>

                                    <td>
                                        <input type="text" name="det_h_return_amount[]" class="det_h_return_amount" value="'.$value->return_amount.'" placeholder="Enter Price" disabled>

                                        <input type="hidden" name="det_return_amount[]" class="det_return_amount" value="'.$value->return_amount.'" placeholder="Enter Price">
                                    </td>

                                    <!--<td>
                                        <input type="text" name="det_h_return_tax_amount[]" class="det_h_return_tax_amount" value="'.$value->return_tax_amount.'" placeholder="Enter Tax" disabled>

                                        <input type="hidden" name="det_return_tax_amount[]" class="det_return_tax_amount" value="'.$value->return_tax_amount.'" placeholder="Enter Tax">
                                    </td>-->

                                    <td>
                                        <input type="text" name="det_h_totalprice[]" class="det_h_totalprice" value="'.$value->return_amount.'" placeholder="Enter Total Price" disabled>

                                        <input type="hidden" name="det_totalprice[]" class="det_totalprice" value="'.$value->return_amount.'" placeholder="Enter Total Price">

                                        <input type="hidden" name="tot_service_charge[]" class="gj_det_sc" value="'.($value->product_id ? $value->Products->service_charge : 0).'">

                                        '.$shiping.'
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger gj_del_det" data-del-id="'.$value->id.'"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>';             
                            }
                        } else {
                            /*$details.='<tr id="gj_tr_det_1">
                                <td>
                                    <p class="gj_nodata">New Order Not Possible</p>
                                </td>
                            </tr>';*/
                            echo $error = 0;die();
                        }

                        $dets = '<div class="gj_odr_det_resp table-responsive">
                            <table class="table table-stripped table-bordered gj_tab_odr_det">
                                <thead>
                                    <tr>
                                        <th>Product Title</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <!--<th>Tax</th>-->
                                        <th>Return Type</th>
                                        <th>Return Qty</th>
                                        <th>Return Amount</th>
                                        <!--<th>Return Tax</th>-->
                                        <th>Total Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="gj_odr_det">
                                    '.$details.'
                                    <tr>
                                        <td colspan="6" class="text-right"> <b> Sub Total </b> </td>
                                        <td colspan="2" class="text-center">  <b> <span class="money"> ₹ <span class="gj_det_sub_tot">0.00</span> </span> </b> </td>

                                        <input type="hidden" name="det_sub_tot" id="det_sub_tot">
                                        <input type="hidden" name="det_tax_total" id="det_tax_total">
                                        <input type="hidden" name="det_total_items" id="det_total_items">
                                        <input type="hidden" name="det_net_amount" id="det_net_amount">
                                        <input type="hidden" name="cut_off" id="cut_off">
                                        <input type="hidden" name="cod_charge" id="cod_charge">
                                        <input type="hidden" name="det_serv_charge" id="det_serv_charge">
                                        <input type="hidden" name="det_shipping_charge" id="det_shipping_charge">
                                    </tr>
                                </tbody>
                            </table>
                        </div>';

                        $error ='';
                        $error.='<div class="col-md-12">
                            <div class="form-group">
                                <label for="contact_person">Contact Person</label>
                                <input class="form-control gj_order_id" placeholder="Order ID" name="order_id" type="hidden" value="'.$re_orders->order_id.'" id="order_id">

                                <input class="form-control gj_user_id" placeholder="User ID" name="user_id" type="hidden" value="'.$re_orders->user_id.'" id="user_id">

                                <input class="form-control gj_contact_person" placeholder="Contact Person" name="h_contact_person" type="text" value="'.$orders->contact_person.'" id="h_contact_person" disabled>

                                <input class="form-control gj_contact_person" placeholder="Contact Person" name="contact_person" type="hidden" value="'.$orders->contact_person.'" id="contact_person">

                                <input class="form-control gj_contact_email" placeholder="Contact Person" name="contact_email" type="hidden" value="'.$orders->contact_email.'" id="contact_email">
                            </div>

                            <div class="form-group">
                                <label for="contact_no">Contact Number</label>

                                <input class="form-control gj_contact_no" placeholder="Contact Number" name="h_contact_no" type="text" value="'.$orders->contact_no.'" id="h_contact_no" disabled>

                                <input class="form-control gj_contact_no" placeholder="Contact Number" name="contact_no" type="hidden" value="'.$orders->contact_no.'" id="contact_no">
                            </div>

                            <div class="form-group">
                                <label for="shipping_address">Shipping Address</label>

                                <input class="form-control gj_shipping_address" placeholder="Shipping Address" name="h_shipping_address" type="text" value="'.$orders->shipping_address.'" id="h_shipping_address" disabled>

                                <input class="form-control gj_shipping_address" placeholder="Shipping Address" name="shipping_address" type="hidden" value="'.$orders->shipping_address.'" id="shipping_address">

                                <input class="form-control gj_shipping_address_flag" placeholder="Shipping Address" name="shipping_address_flag" type="hidden" value="'.$orders->shipping_address_flag.'" id="shipping_address_flag">

                                <input class="form-control gj_city" placeholder="Shipping Address" name="city" type="hidden" value="'.$orders->city.'" id="city">

                                <input class="form-control gj_pincode" placeholder="Shipping Address" name="pincode" type="hidden" value="'.$orders->pincode.'" id="pincode">
                            </div>

                            <div class="form-group">
                                <label for="total_items">Total Items</label>

                                <input class="form-control gj_total_items" placeholder="Total Items" name="h_total_items" type="text" value="'.$re_orders->total_items.'" id="h_total_items" disabled>

                                <input class="form-control gj_total_items" placeholder="Total Items" name="total_items" type="hidden" value="'.$re_orders->total_items.'" id="total_items">
                            </div>

                            <div class="form-group">
                                <label for="shipping_charge">Shipping Charge</label>

                                <input class="form-control gj_shipping_charge" placeholder="Shipping Charge" name="h_shipping_charge" type="text" value="'.$orders->shipping_charge.'" id="h_shipping_charge" disabled>

                                <input class="form-control gj_shipping_charge" placeholder="Shipping Charge" name="shipping_charge" type="hidden" value="'.$orders->shipping_charge.'" id="shipping_charge">
                            </div>

                            <!-- <div class="form-group">
                                <label for="tax_amount">Tax Amount</label>

                                <input class="form-control gj_tax_amount" placeholder="Tax Amount" name="h_tax_amount" type="text" value="'.$orders->tax_amount.'" id="h_tax_amount" disabled>

                                <input class="form-control gj_tax_amount" placeholder="Tax Amount" name="tax_amount" type="hidden" value="'.$orders->tax_amount.'" id="tax_amount">
                            </div>-->

                            <div class="form-group">
                                <label for="net_amount">Net Amount</label>

                                <input class="form-control gj_net_amount" placeholder="Net Amount" name="h_net_amount" type="text" value="'.$re_orders->net_amount.'" id="h_net_amount" disabled>

                                <input class="form-control gj_net_amount" placeholder="Net Amount" name="net_amount" type="hidden" value="'.$re_orders->net_amount.'" id="net_amount">
                            </div>

                            <div class="form-group">
                                <label for="remarks">Remarks</label>

                                <textarea class="form-control gj_remarks" placeholder="Remarks" rows="5" name="remarks" cols="50" id="remarks"></textarea>
                            </div>

                            '.$dets.'

                            <input class="btn btn-primary" type="submit" value="Update" autocomplete="new-password">
                        </div>';

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

    public function edit ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $orders = Orders::where('id',$id)->first();
                if($orders) {
                    $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                    $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
                    $orders['products'] = Products::Where('is_block', 1)->get();
                }
                return View::make("transaction.orders.edit_orders")->with(array('orders'=>$orders, 'page'=>$page));
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
                ->where('B.module_name', '=', 'All Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Transaction";
                $id = Input::get('orders_id');
                $data = Input::all();
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

        	            'det_order_id'           => 'nullable',
        	            'det_product_id'         => 'nullable',
        	            'det_product_title'      => 'required',
        	            'det_order_qty'          => 'required',
                        'det_att_name'           => 'required',
                        'det_att_value'          => 'required',
                        'det_tax'                => 'required',
                        'det_tax_type'           => 'required',
        	            'det_unitprice'          => 'nullable',
        	            'det_totalprice'         => 'nullable',
        	            'det_shipping_charge'    => 'nullable',
        	            'det_total_items'        => 'nullable',
        	            'det_net_amount'         => 'nullable',
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

                        $orders = Orders::Where('id', $orders->id)->first();
        	            $orders->payment_mode     = $data['payment_mode'];	            
        	            $orders->delivery_date    = $data['delivery_date'];	            
        	            $orders->order_status     = $data['order_status'];	            
                        $orders->discount_flag    = $data['discount_flag'];             
                        $orders->discount         = $data['discount'];              
        	            $orders->total_items      = $data['det_total_items'];
                        // $orders->tax_amount       = $data['tax_total'];
                        $orders->total_amount     = $data['det_sub_tot'];
                        $orders->service_charge    = $data['det_serv_charge'];
                        $orders->shipping_charge  = $data['det_shipping_charge'];               
        	            $orders->net_amount       = $data['det_net_amount'];	            
        	            $orders->payment_status   = $data['payment_status'];	            
        	            $orders->delivery_status  = $data['delivery_status'];	            
        	            $orders->remarks          = $data['remarks'];	            
        	            $orders->is_block         = 1;
                        
                        if($orders->save()) {
        	            	if (isset($data['order_det_id']) && count($data['order_det_id']) != 0) {
                                foreach ($data['order_det_id'] as $key => $value) {
            		                $order_details = OrderDetails::Where('id', $value)->first();
                                    if($order_details) {
                                        $order_details->order_id = $orders->id;
                                        
                                        if(isset($data['det_product_id'][$key])) {
                                            $order_details->product_id = $data['det_product_id'][$key];
                                        } else {
                                            $order_details->product_id = NULL;
                                        }

                                        if(isset($data['det_product_title'][$key])) {
                                            $order_details->product_title = $data['det_product_title'][$key];
                                        } else {
                                            $order_details->product_title = NULL;
                                        }

                                        if(isset($data['det_att_name'][$key])) {
                                            $order_details->att_name = $data['det_att_name'][$key];
                                        } else {
                                            $order_details->att_name = NULL;
                                        }

                                        if(isset($data['det_att_value'][$key])) {
                                            $order_details->att_value = $data['det_att_value'][$key];
                                        } else {
                                            $order_details->att_value = NULL;
                                        }

                                        if(isset($data['det_tax'][$key])) {
                                            $order_details->tax = $data['det_tax'][$key];
                                        } else {
                                            $order_details->tax = NULL;
                                        }

                                        if(isset($data['det_tax_type'][$key])) {
                                            $order_details->tax_type = $data['det_tax_type'][$key];
                                        } else {
                                            $order_details->tax_type = NULL;
                                        }
                                        
                                        if(isset($data['det_order_qty'][$key])) {
                                            $order_details->order_qty = $data['det_order_qty'][$key];
                                        } else {
                                            $order_details->order_qty = NULL;
                                        }

                                        if(isset($data['det_unitprice'][$key])) {
                                            $order_details->unitprice = $data['det_unitprice'][$key];
                                        } else {
                                            $order_details->unitprice = 0.00;
                                        }

                                        // if(isset($data['det_tax_amount[]'][$key])) {
                                        //     $order_details->tax_amount = $data['det_tax_amount[]'][$key];
                                        // } else {
                                        //     $order_details->tax_amount = 0.00;
                                        // }

                                        if(isset($data['det_totalprice'][$key])) {
                                            $order_details->totalprice = $data['det_totalprice'][$key];
                                        } else {
                                            $order_details->totalprice = 0.00;
                                        }
                                        
                                        $order_details->is_block = 1;

                                        if($order_details->save()) {
                                            $sus2 = 1;
                                        }    
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
                                $net_comis = 0.00;
                                $net_mer_amt = 0.00;
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
                                        $stock = Products::Where('id', $value->product_id)->first();

                                        if(isset($data['det_old_order_qty'][$key])) {
                                            $det_old_order_qty = $data['det_old_order_qty'][$key];
                                        } else {
                                            $det_old_order_qty = 0;
                                        }

                                        if($stock && ($stock->onhand_qty != 0)) {
                                            $stock_trans = new StockTransactions();
                                            $stock_trans->order_code   = $order_code;
                                            $stock_trans->product_id   = $value->product_id;
                                            $stock_trans->att_name     = $value->att_name;
                                            $stock_trans->att_value    = $value->att_value;
                                            $stock_trans->previous_qty = $stock->onhand_qty - $det_old_order_qty;
                                            $stock_trans->current_qty  = ($stock->onhand_qty - $det_old_order_qty) + $value->order_qty;
                                            $stock_trans->date         = date('Y-m-d');
                                            $stock_trans->remarks      = $value->product_title.' is reordered.';

                                            $stock->onhand_qty = ($stock->onhand_qty - $det_old_order_qty) + $value->order_qty;
                                            
                                            $p_atts = ProductsAttributes::Where('product_id', $value->product_id)->Where('attribute_name', $value->att_name)->Where('attribute_values', $value->att_value)->first();
                                            if($p_atts) {
                                                $stock_trans->att_previous_qty = $p_atts->att_qty - $det_old_order_qty;
                                                $stock_trans->att_current_qty  = ($p_atts->att_qty - $det_old_order_qty) + $value->order_qty;
                                                
                                                $p_atts->att_qty = ($p_atts->att_qty - $det_old_order_qty) + $value->order_qty;
                                                $p_atts->save();
                                            }

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

                                                AdminCommision::Where('order_dets', $value->id)->delete();
                                                $comis = new AdminCommision();
                                                if($comis) {
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
                                                }

                                                $net_comis   = $net_comis + $admin_com;
                                                $net_mer_amt = $net_mer_amt + $mer_amt;
                                            }
                                        }
                                    }
                                }

                                $order = Orders::Where('id', $orders->id)->first();
                                if($order) {
                                    $order->net_commision = $net_comis;
                                    $order->net_merchant_amout = $net_mer_amt;
                                    $order->save();
                                }        
                
                                Session::flash('message', 'Update Successfully!'); 
                                Session::flash('alert-class', 'alert-success');
                                return redirect()->route('all_orders');
                            } else {
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

    public function EditDelivery ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Delivery')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $orders = Orders::where('id',$id)->first();
                if($orders) {
                    $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                    $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
                    $orders['products'] = Products::Where('is_block', 1)->get();
                }
                return View::make("transaction.orders.delivery_orders")->with(array('orders'=>$orders, 'page'=>$page));
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

    public function UpdateDelivery (Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Delivery')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $id = Input::get('orders_id');
                $orders = '';
                if($id != '') {
                    $orders = Orders::where('id',$id)->first();
                    $u_orders = Orders::where('id',$id)->first();
                    if($orders && $u_orders) {
                        $orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
                        $orders['trans'] = OrdersTransactions::Where('order_id', $orders->id)->get();
                        $orders['products'] = Products::Where('is_block', 1)->get();
                    }
                }

                if($orders && $u_orders) {
                    $rules = array(
                        'delivery_date'          => 'required',
                        'delivery_status'        => 'required',
                        'order_status'           => 'required',
                        'remarks'                => 'nullable',
                        'is_block'               => 'nullable',
                    );

                    $messages=[
                        'det_product_title.required'=>'The Products Title field is required.',
                        'det_order_qty.required'=>'The Quantity field is required.',
                    ];
                    $validator = Validator::make(Input::all(), $rules,$messages);

                    if ($validator->fails()) {
                        return Redirect::to('/delivery_orders/' . $id)->withErrors($validator)->with(array('orders'=>$orders, 'page'=>$page));
                    } else {
                        $data = Input::all();
                        $u_orders->delivery_date    = $data['delivery_date'];             
                        $u_orders->delivery_status  = $data['delivery_status'];               
                        $u_orders->order_status     = $data['order_status'];              
                        $u_orders->remarks          = $data['remarks'];               
                        $u_orders->is_block         = 1;

                        if($u_orders->save()) {
                            Session::flash('message', 'Update Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            return redirect()->route('all_orders');
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

	public function view ($id,Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                if(!empty($request->notify_id))
                {
                    $as=Notification::find($request->notify_id);
                    $as->read_flag=1;
                    $as->update();
                }
        		$orders = Orders::where('id',$id)->first();
        		if($orders) {
        			$orders['details'] = OrderDetails::Where('order_id', $orders->id)->get();
        		}
        		return View::make("transaction.orders.view_orders")->with(array('orders'=>$orders, 'page'=>$page));
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

	public function CheckTax( Request $request) {	
		$id = 0;
		$price = 0;
		$error = 0;
		if($request->ajax() && isset($request->id) && isset($request->price) && isset($request->qty)){
			$id = $request->id;
			$price = $request->price;
            $qty = $request->qty;
			if($id != 0 && $price != 0 && $qty != 0) {
				$products = Products::where('id',$id)->first();
				if($products) {
					$tax = $products->tax;
					$tax_type = $products->tax_type;

                    $price = round(($price + (($price * $tax)/100)),2);

					/*if($tax_type == 2) {
			          $calc_tax = (($price * $tax)/100);
			          $price = $price + $calc_tax;
			        }*/
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
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
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
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $error = 0;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $error = 0;
        }

		echo $error;
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

                    if ($products->tax_type == 2) {
                        $ships = $products->shiping_charge;
                    } else {
                        $ships = 0;
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
                        <input type="text" name="det_h_totalprice[]" class="det_h_totalprice" value="'.round(1 * ($products->discounted_price + (($products->discounted_price * $products->tax)/100)),2).'" placeholder="Enter Total Price" disabled>

                        <input type="hidden" name="det_totalprice[]" class="det_totalprice" value="'.round(1 * ($products->discounted_price + (($products->discounted_price * $products->tax)/100)),2).'" placeholder="Enter Total Price">

                        <input type="hidden" name="tot_service_charge[]" class="gj_det_sc" value="'.$products->service_charge.'">

                        <input type="hidden" name="tot_shipping_charge[]" class="gj_det_spc" value="'.$ships.'">
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
		$error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id) && isset($request->status)){
        			$id = $request->id;
        			$status = $request->status;
                   
        			if($id != 0) {
        				$orders = Orders::where('id',$id)->first();
                        
                         //push notification code start
                            $device_id=User::where("id", $orders->user_id)->value('device_id');
                            //$device_id='eNGH4ud9R7mh2_6WJdI4jx:APA91bG6-jkz8LX1sL8JAqfzqj1GpHDBtyY0c2por3cQ8wQW2xKRZyzq66XkV5qjfJU3cR9oHV1Hu7dT6hLmjwdlRxXP-HXeLcqjiMtKwa3l00oQCexCSOHIkF9flt3XFGrzrH_Nf-Bf';
                            if(!empty($device_id)){
                                if($status==1){
                                    $sts="Order Placed";
                                }elseif($status==2){
                                    $sts="Order Confirmed";
                                }elseif($status==3){
                                    $sts="Order Dispatched";
                                }elseif($status==4){
                                    $sts="Order Deliverd";
                                }elseif($status==5){
                                    $sts="Order completed";
                                }elseif($status==6){
                                    $sts="Order Cancelled";
                                }

                                $as=new Notification();
                                $as->user_type=1;
                                $as->user_id=1;
                                $as->message=$sts.','.$orders->order_code;
                                $as->url=URL::to('/view_orders/'.$id);
                                $as->customer_id=User::where("id", $orders->user_id)->value('id');
                                $as->order_id=$id;
                                $as->save();

                                $message="Status Changed to ".$sts;
                                $data=['order_id'=>$id];
                                $action='.OrderDetailScreen';
                                $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
                                $token=$device_id;
                                $serverKey='AAAAbJxc7xQ:APA91bFtRlaXlfY3HaXNJgM6DvLHXw1YNBx2m9AAjggKjqSPW3B4kOQtzWi7OepYtQYS6JEs8NzzwyGxB0eYEk9q9xsyRrpWI6EBNIpB-eJWkcsFxW5Lp3mMeo5kO2VF4g024sz1SLvU';
                                $notification = [
                                    'title' => $message,
                                    'sound' => true,
                                  
                                    'click_action'=>$action,
                                    'data2'=>$data
                                ];
                                
                                $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
            
                                $fcmNotification = [
                                    //'registration_ids' => $tokenList, //multple token array
                                    'to'        => $token, //single token
                                    'notification' => $notification,
                                    'data' => $extraNotificationData
                                ];
            
                                $headers = [
                                    'Authorization: key='.$serverKey,
                                    'Content-Type: application/json'
                                ];
            
            
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL,$fcmUrl);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                                $result = curl_exec($ch);
                                curl_close($ch);
                                dd($result);
                            }
                            //push notification code end
        				if($orders) {
                            if($status == 5) {
                                $orders->cancel_date = date('Y-m-d');
                            }
                            $orders->order_status = $status;

        					if($orders->save()) {
                                if($orders->order_status == 3 || $orders->order_status == 5 ||$orders->order_status == 2 ||$orders->order_status == 4) {
                                    if($orders->order_status == 3) {
                                        $text = "Your Order has been Delivered. Plz note the Order Code - ".$orders->order_code.",grocery360.in.";
                                        $sub='Order has been Delivered';
                                         $user = User::Where('id', $orders->user_id)->first();
                                    
                                    if($user) {
                                        //email
                                $adm = EmailSettings::where('id', 1)->first();
                                $admin_email = "info@Grocery360.com";
                                if($adm) {
                                    $admin_email = $adm->contact_email;
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
                                $site_name = "Grocery360";
                                if($general){
                                    $site_name = $general->site_name;
                                } else {
                                    $site_name = "Grocery360";
                                } 
                
                                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                $headers.= "MIME-Version: 1.0\r\n";
                                // $headers.= "From: $admin_email" . "\r\n";
                                $headers.= "From: Grocery360 <order@grocery360.in>" . "\r\n";
                                $to = $user->email;
                                $subject = $sub;
                
                                $txt1 = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                            <p>Hi ' .$user->first_name.',</p>
                                            <p>'.$text.'</p>
                                            <p>Thank You.</p>
                                            <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                            <p>Thanks & Regards,</p>
                                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                        </div>
                                    </div>';
                                
                                if(mail($to,$subject,$txt1,$headers)){
                                }
                                        
                                        
                                        
                                        //end email
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
                                            Session::flash('message', 'Order Status Changed Successfully and  Confirm Message Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            echo $error = 0;die();
                                        } else {
                                            Session::flash('message', 'Order Status Changed Successfully!'); 
                                            Session::flash('alert-class', 'alert-danger');
                                            echo $error = 0;die();
                                        }                                
                                    }
                                    }
                                    if($orders->order_status == 2) {
                                        $text = "Your Order has been Dispatched. Plz note the Order Code - ".$orders->order_code.",grocery360.in.";
                                         $sub='Order has been Dispatched';
                                          $user = User::Where('id', $orders->user_id)->first();
                                    
                                    if($user) {
                                        //email
                                $adm = EmailSettings::where('id', 1)->first();
                                $admin_email = "info@grocery360.in";
                                if($adm) {
                                    $admin_email = $adm->contact_email;
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
                                $site_name = "grocery360.in";
                                if($general){
                                    $site_name = $general->site_name;
                                } else {
                                    $site_name = "grocery360.in";
                                } 
                
                                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                $headers.= "MIME-Version: 1.0\r\n";
                                // $headers.= "From: $admin_email" . "\r\n";
                                $headers.= "From: Grocery360 <order@grocery360.in>" . "\r\n";
                                $to = $user->email;
                                $subject = $sub;
                
                                $txt1 = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                            <p>Hi ' .$user->first_name.',</p>
                                            <p>'.$text.'</p>
                                            <p>Thank You.</p>
                                            <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                            <p>Thanks & Regards,</p>
                                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                        </div>
                                    </div>';
                                
                                if(mail($to,$subject,$txt1,$headers)){
                                }
                                        
                                        
                                        
                                        //end email
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
                                            Session::flash('message', 'Order Status Changed Successfully and  Confirm Message Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            echo $error = 0;die();
                                        } else {
                                            Session::flash('message', 'Order Status Changed Successfully!'); 
                                            Session::flash('alert-class', 'alert-danger');
                                            echo $error = 0;die();
                                        }                                
                                    }
                                    }
                                    if($orders->order_status == 4) {
                                        $text = "Your Order has been Completed its delivery. Plz note the Order Code - ".$orders->order_code.",grocery360.in.";
                                         $sub='Order has been Completed';
                                          $user = User::Where('id', $orders->user_id)->first();
                                    
                                    if($user) {
                                        //email
                                $adm =EmailSettings::where('id', 1)->first();
                                $admin_email = "info@grocery360.in";
                                if($adm) {
                                    $admin_email = $adm->contact_email;
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
                                $site_name = "grocery360.in";
                                if($general){
                                    $site_name = $general->site_name;
                                } else {
                                    $site_name = "grocery360.in";
                                } 
                
                                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                $headers.= "MIME-Version: 1.0\r\n";
                                // $headers.= "From: $admin_email" . "\r\n";
                                $headers.= "From: Grocery360 <order@grocery360.in>" . "\r\n";
                                $to = $user->email;
                                $subject = $sub;
                
                                $txt1 = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                            <p>Hi ' .$user->first_name.',</p>
                                            <p>'.$text.'</p>
                                            <p>Thank You.</p>
                                            <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                            <p>Thanks & Regards,</p>
                                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                        </div>
                                    </div>';
                                
                                if(mail($to,$subject,$txt1,$headers)){
                                }
                                        
                                        
                                        
                                        //end email
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
                                            Session::flash('message', 'Order Status Changed Successfully and  Confirm Message Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            echo $error = 0;die();
                                        } else {
                                            Session::flash('message', 'Order Status Changed Successfully!'); 
                                            Session::flash('alert-class', 'alert-danger');
                                            echo $error = 0;die();
                                        }                                
                                    }
                                    }else {
                                        $text = "Your Order ".$orders->order_code."  has been Cancelled.grocery360.in.";
                                         $sub='Order has been Cancelled';
                                          $user = User::Where('id', $orders->user_id)->first();
                                    
                                    if($user) {
                                        //email
                                $adm = EmailSettings::where('id', 1)->first();
                                $admin_email = "info@grocery360.in";
                                if($adm) {
                                    $admin_email = $adm->contact_email;
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
                                $site_name = "grocery360.in";
                                if($general){
                                    $site_name = $general->site_name;
                                } else {
                                    $site_name = "grocery360.in";
                                } 
                
                                $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                $headers.= "MIME-Version: 1.0\r\n";
                                // $headers.= "From: $admin_email" . "\r\n";
                                $headers.= "From: Grocery360 <order@grocery360.in>" . "\r\n";
                                $to = $user->email;
                                $subject = $sub;
                
                                $txt1 = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                            <p>Hi ' .$user->first_name.',</p>
                                            <p>'.$text.'</p>
                                            <p>Thank You.</p>
                                            <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                            <p>Thanks & Regards,</p>
                                            <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                        </div>
                                    </div>';
                                
                                if(mail($to,$subject,$txt1,$headers)){
                                }
                                        
                                        
                                        
                                        //end email
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
                                            Session::flash('message', 'Order Status Changed Successfully and  Confirm Message Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            echo $error = 0;die();
                                        } else {
                                            Session::flash('message', 'Order Status Changed Successfully!'); 
                                            Session::flash('alert-class', 'alert-danger');
                                            echo $error = 0;die();
                                        }                                
                                    }
                                    }

                                   
                                }
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

    public function PaymentStatusOrders( Request $request) { 
        $id = 0;
        $status = 0;
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->id) && isset($request->status)){
                    $id = $request->id;
                    $status = $request->status;
                    if($id != 0) {
                        $orders = Orders::where('id',$id)->first();
                        $order_trans = OrdersTransactions::Where('order_id', $orders->id)->first();
                        if(!$order_trans) {
                            $order_trans = OrdersTransactions::Where('order_id', $orders->order_code)->first();
                        }
                        if($orders && $order_trans) {
                            $orders->payment_status = $status;
                            if($status == 0) {
                                $order_trans->amountpaid = 'Unpaid';
                                $order_trans->trans_status = 'PENDING';
                            } else if($status == 1) {
                                $order_trans->amountpaid = 'Paid';
                                $order_trans->trans_status = 'SUCCESS';
                            } else if($status == 2) {
                                $order_trans->amountpaid = 'Unpaid';
                                $order_trans->trans_status = 'FAILED';
                            } else {
                                $order_trans->amountpaid = 'Unpaid';
                                $order_trans->trans_status = 'FAILED';
                            }

                            if($orders->save() && $order_trans->save()) {
                                Session::flash('message', 'Status Changed Successfully!'); 
                                Session::flash('alert-class', 'alert-success');
                                $error = 0;
                            } else {
                                Session::flash('message', 'Status Changed Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                $error = 1;
                            }
                        }   else {
                            Session::flash('message', 'Status Changed Failed'); 
                            Session::flash('alert-class', 'alert-danger');
                            $error = 1;
                        }           
                    } else {
                        Session::flash('message', 'Status Changed Failed, Invalid ID!'); 
                        Session::flash('alert-class', 'alert-danger');
                        $error = 1;
                    }
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                // $error = 1;
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            // $error = 1;
            $error = 1;
        }

        echo $error;
    }

	public function delete( Request $request) {	
		$id = 0;
		$error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
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
                ->where('B.module_name', '=', 'All Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
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
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                // $error = 1;
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            // $error = 1;
            $error = 1;
        }

		echo $error;
	}

    public function ExportCSV( Request $request) {  
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()){
                    $ids = $request->ids;
                    $table = array();
                    $filename = "Orders.csv";
                    $user = session()->get('user');

                    if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            if($user) {
                                $table = Orders::whereIn('id',$ids)->get();
                            } else {
                                echo $error = 1;die();
                            }
                            $filename = "Orders.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') {
                        if($user) {
                            if ($user->user_type == 1) {
                                $table = Orders::all();
                            } else if ($user->user_type == 2 || $user->user_type == 3) {
                                $co_id=[];
                                $ords = DB::table('orders as A')
                                    ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
                                    ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                                    ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                                    ->select('A.id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                                    ->OrderBy('A.id', 'DESC')
                                    ->where('C.created_user', '=', $user->id)
                                    ->where('D.id', '=', $user->id)
                                    ->whereIn('D.user_type', ['2','3'])
                                    ->GroupBy('B.order_id')
                                    ->get();

                                if (sizeof($ords) != 0) {
                                    foreach ($ords as $key => $value) {
                                        array_push($co_id, $value->id);
                                    }
                                }

                                if (sizeof($co_id) != 0) {
                                    $table = Orders::WhereIn('id', $co_id)->get();
                                    if(sizeof($table) != 0) {
                                        foreach ($table as $key => $value) {
                                            $det = OrderDetails::Where('order_id', $value->id)->get(); 
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
                        $filename = "All_Orders.csv";
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }

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
                            $table[$key]['payment_mode'] = "Online";
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

                        $patt = "---------";
                        if($value->attributes_flag == 1) {
                            $PA = ProductsAttributes::where('product_id', $value->id)->get();
                            if(sizeof($PA) != 0) {
                                $patt="";
                                foreach ($PA as $pkey => $pvalue) {
                                    $patt.= 'Attributes : '.$pvalue->AttributeName->att_name.' - '.$pvalue->AttributeValue->att_value.', Price : Rs.'.$pvalue->att_price.', Qty : '.$pvalue->att_qty.', Description : '.$pvalue->description.', ';                              
                                }
                            }
                            $table[$key]['p_attributes'] = $patt;
                        } else {
                            $table[$key]['p_attributes'] = $patt;
                        }

                        $odr = "---------";
                        if(sizeof($table_det) != 0) {
                            $odr="";
                            foreach ($table_det as $keyz => $valuez) {
                                $odr.= 'Product Title : '.$valuez->product_title.', Price : Rs.'.$valuez->unitprice.', Qty : '.$valuez->order_qty.', Total Price : '.$valuez->totalprice.', ';                              
                            }
                            $table[$key]['odr'] = $odr;
                        } else {
                            $table[$key]['odr'] = $odr;
                        }
                    }
                    
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('Order Code', 'Order Date', 'Payment Mode', 'Delivery Date', 'Order Status', 'Contact Person', 'Contact Number', 'Shipping Address', 'Total Items', 'Discount', 'Discount Rate', 'Shipping Charge', 'Net Amount', 'Payment Status', 'Delivery Status', 'Remarks', 'Order Deatils'));

                    foreach($table as $row) {
                        fputcsv($handle, array($row['order_code'], $row['order_date'], $row['payment_mode'], $row['delivery_date'], $row['order_status'], $row['contact_person'], $row['contact_no'], $row['shipping_address'], $row['total_items'], $row['discount_flag'], $row['discount'], $row['shipping_charge'], $row['net_amount'], $row['payment_status'], $row['delivery_status'], $row['remarks'], $row['odr']));
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

    public function ExportCourierCSV( Request $request) {  
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Orders')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
                    $ids = $request->ids;
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
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
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

    public function AllCreditNotes () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Credit Notes')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $sess = session()->get('user');
                $cn = array(); 
                $co_id = []; 
                $co_ids = []; 
                
                if($sess) {
                    if($sess->user_type == 1) {
                        $cn = CreditsNotes::OrderBy('id', 'DESC')->paginate(10);
                    } else if($sess->user_type == 2 || $sess->user_type == 3) {
                        $ords = DB::table('credits_notes as CN')
                            ->leftjoin('grv_orders as A', 'A.id', '=', 'CN.grv_id')
                            ->leftjoin('grv_orders_details as B', 'A.id', '=', 'B.grv_id')
                            ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                            ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                            ->select('CN.id as cn_id','A.id as grv_id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                            ->OrderBy('A.id', 'DESC')
                            ->where('C.created_user', '=', $sess->id)
                            ->where('D.id', '=', $sess->id)
                            ->whereIn('D.user_type', ['2','3'])
                            ->GroupBy('B.grv_id')
                            ->get();

                        if (sizeof($ords) != 0) {
                            foreach ($ords as $key => $value) {
                                array_push($co_id, $value->cn_id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $cn = CreditsNotes::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                        }
                    }
                }

                return View::make("transaction.orders.manage_credit_notes")->with(array('cn'=>$cn, 'page'=>$page));
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

    public function ViewCreditNotes ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Credit Notes')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $sess = session()->get('user');
                $cn = array(); 
                $co_id = []; 
                $co_ids = []; 
                $general = GeneralSettings::first();
                $logo = LogoSettings::first();
                $contact = EmailSettings::first();
                $grv = '';
                $grv_details = array();

                if($sess) {
                    $cn = CreditsNotes::Where('id', $id)->first();
                    if($cn) {
                        $grv = GrvOrders::Where('id', $cn->grv_id)->first();
                        if($grv) {
                            $o_grv_details = GrvOrdersDetails::Where('grv_id', $grv->id)->get();
                            if(sizeof($o_grv_details) != 0) {
                                foreach ($o_grv_details as $key => $value) {
                                    if($value->return_type == "Refund") {
                                        array_push($co_id, $value->id);
                                    }
                                }

                                if (sizeof($co_id) != 0) {
                                    $grv_details = GrvOrdersDetails::WhereIn('id', $co_id)->get();
                                    if(sizeof($grv_details) != 0) {
                                        return View::make("transaction.orders.view_credit_notes")->with(array('cn'=>$cn, 'general'=>$general, 'contact'=>$contact, 'logo'=>$logo, 'grv'=>$grv, 'grv_details'=>$grv_details, 'page'=>$page));
                                    } else {
                                        Session::flash('message', 'GRV Details Not Found!'); 
                                        Session::flash('alert-class', 'alert-danger');
                                        return redirect()->route('manage_credit_notes');
                                    }
                                } else {
                                    Session::flash('message', 'GRV Details Not Found!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('manage_credit_notes');
                                }
                            } else {
                                Session::flash('message', 'GRV Details Not Found!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('manage_credit_notes');
                            }
                        } else {
                            Session::flash('message', 'GRV Invalid!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_credit_notes');
                        }
                    } else {
                        Session::flash('message', 'View Not Possible!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_credit_notes');
                    }
                } else {
                    Session::flash('message', 'This User Cannot View This Module!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_credit_notes');
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

    public function StatusCreditNotes( Request $request) {  
        $id = 0;
        $status = 0;
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Credit Notes')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->id) && isset($request->status)){
                    $id = $request->id;
                    $status = $request->status;
                    if($id != 0) {
                        $cn = CreditsNotes::where('id',$id)->first();
                        if($cn) {
                            $cn->is_paid = $status;
                            if($cn->save()) {
                                if($cn->is_paid == 'Paid') {
                                    $text = "Your Refund Order Amount is Paid. Plz note the Order Code - ".$cn->GRV->Orders->order_code.", grocery360.in";
                                    $subject = "Refund Amount Paid";
                                /*} elseif ($cn->is_paid == 'Un Paid') {
                                    $text = "Your Refund Order Amount is Un Paid. Plz note the Order Code - ".$cn->GRV->Orders->order_code.", grocery360.in";
                                    $subject = "Un Paid Refund";
                                }*/

                                    $text = urlencode($text);

                                    $curl = curl_init();
                                    $user = User::Where('id', $cn->GRV->Orders->user_id)->first();
                                    if($user) { 
                                        $adm = EmailSettings::where('id', 1)->first();
                                        $admin_email = "info@grocery360.in";
                                        if($adm) {
                                            $admin_email = $adm->contact_email;
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
                                        $site_name = "grocery360.in";
                                        if($general){
                                            $site_name = $general->site_name;
                                        } else {
                                            $site_name = "grocery360.in";
                                        } 

                                        $name = $user->first_name.' '.$user->last_name;

                                        $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                        $headers.= "MIME-Version: 1.0\r\n";
                                        // $headers.= "From: $admin_email" . "\r\n";
                                        $headers.= "From: Grocery360 <order@grocery360.in>" . "\r\n";
                                        $to1 = $user->email;
                                        $to2 = $admin_email;

                                        $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                                <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                                <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                                    <h2 style="color: #ff5c00;margin-top: 0px;">'.$subject.'</h2>
                                                    <table align="center" style=" text-align: center;">
                                                        <tr>
                                                            <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">customer Name</th>
                                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$user->first_name.' '.$user->last_name.'</td>
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
                                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$cn->GRV->Orders->order_code.'</td>
                                                        </tr>

                                                        <tr>
                                                            <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Request Replied Date</th>
                                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$cn->cn_code.'</td>
                                                        </tr>

                                                        <tr>
                                                            <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Issue Date</th>
                                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.date('d-F-Y', Strtotime($cn->date)).'</td>
                                                        </tr>
                                                    </table>

                                                    <p>Your Refund Order Amount is Paid.</p>
                                                    <p>Thank You.</p>
                                                     <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                                    <p>Thanks & Regards,</p>
                                                    <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                                </div>
                                            </div>';
                                            
                                            
                                        // if(1==1){
                                        if(mail($to1,$subject,$txt,$headers) && mail($to2,$subject,$txt,$headers)) {
                                            Session::flash('message', 'Status Changed Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            $error = 0;
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
                                            Session::flash('message', 'Status Changed Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            $error = 0;
                                        } else {
                                            Session::flash('message', 'Status Changed Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            $error = 0;
                                        }
                                    } else {
                                        Session::flash('message', 'Status Changed Successfully!'); 
                                        Session::flash('alert-class', 'alert-success');
                                        $error = 0;
                                    }
                                    Session::flash('message', 'Status Changed Successfully!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    $error = 0;
                                } elseif ($cn->is_paid == 'Un Paid') {
                                    $text = "Your Refund Order Amount is Un Paid. Plz note the Order Code - ".$cn->GRV->Orders->order_code.", grocery360.in";
                                    $subject = "Un Paid Refund";

                                    Session::flash('message', 'Status Changed Successfully!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    $error = 0;
                                }
                            } else {
                                Session::flash('message', 'Status Changed Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                $error = 1;
                            }
                        }   else {
                            Session::flash('message', 'Status Changed Not Possible!'); 
                            Session::flash('alert-class', 'alert-danger');
                            $error = 1;
                        }           
                    } else {
                        Session::flash('message', 'Invalid ID!'); 
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

    public function TransactionSummary () {
        $loged = session()->get('user');
        if($loged) {
            $page = "Transaction";
            $sess = session()->get('user');
            $orders = ''; 
            $co_id = []; 
            $co_ids = []; 
            
            if($sess) {
                if($sess->user_type == 1) {
                    $orders = Orders::OrderBy('id', 'DESC')->paginate(10);
                    $vendors = User::WhereIn('user_type', ['2','3'])->get();
                    if(sizeof($orders) != 0) {
                        foreach ($orders as $key => $value) {
                            $det = OrderDetails::Where('order_id', $value->id)->get(); 
                            if(sizeof($det)) {
                                $orders[$key]->{'details'} = $det;
                            } else {
                                $orders[$key]->{'details'} =  '';
                            }
                        }
                    }
                } else if($sess->user_type == 2 || $sess->user_type == 3) {
                    $ords = DB::table('orders as A')
                        ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
                        ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                        ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                        ->select('A.id', 'sum(B.unitprice) as sum', 'B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                        ->OrderBy('A.id', 'DESC')
                        ->where('C.created_user', '=', $sess->id)
                        ->where('D.id', '=', $sess->id)
                        ->whereIn('D.user_type', ['2','3'])
                        ->GroupBy('B.order_id')
                        ->get();

                    if (sizeof($ords) != 0) {
                        foreach ($ords as $key => $value) {
                            array_push($co_id, $value->id);
                        }
                    }

                    if (sizeof($co_id) != 0) {
                        $orders = Orders::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                        if(sizeof($orders) != 0) {
                            foreach ($orders as $key => $value) {
                                $det = OrderDetails::Where('order_id', $value->id)->get(); 
                                if(sizeof($det) != 0) {
                                    $orders[$key]->{'details'} = $det;
                                } else {
                                    $orders[$key]->{'details'} =  '';
                                }
                            }
                        }
                    }
                }
            }

            return View::make("transaction.orders.transaction_summary")->with(array('orders'=>$orders, 'vendors'=>$vendors, 'page'=>$page));
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function FilterTransactionSummary (Request $request) {
        $page = "Transaction"; 
        $vendors = User::WhereIn('user_type', ['2','3'])->Where('is_block', 1)->get();    
        $fo_id = [];                                         
        $fod_id = [];                                         
        $gj_srh_from_date = Input::get('gj_srh_from_date');
        $gj_srh_to_date = Input::get('gj_srh_to_date');
        $gj_srh_vendor = Input::get('gj_srh_vendor');
        $orders = array();

        if($gj_srh_from_date && $gj_srh_to_date && $gj_srh_vendor) {
            $gj_srh_from_date = date('Y-m-d', strtotime($gj_srh_from_date));
            $gj_srh_to_date = date('Y-m-d', strtotime($gj_srh_to_date));

            $ords = DB::table('orders as A')
                ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
                ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                ->select('A.id as o_id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                ->OrderBy('A.id', 'DESC')
                ->where('C.created_user', '=', $gj_srh_vendor)
                ->where(function($query) use ($gj_srh_from_date, $gj_srh_to_date) {
                    return $query->whereBetween('order_date', [$gj_srh_from_date, $gj_srh_to_date])
                    ->orWhereNull('order_date');
                })
                ->where('D.id', '=', $gj_srh_vendor)
                ->whereIn('D.user_type', ['2','3'])
                ->GroupBy('B.order_id')
                ->get();

            if (sizeof($ords) != 0) {
                foreach ($ords as $key => $value) {
                    array_push($fo_id, $value->o_id);
                }
            }

            if (sizeof($fo_id) != 0) {
                $orders = Orders::WhereIn('id', $fo_id)->OrderBy('id', 'DESC')->paginate(10);
                if(sizeof($orders) != 0) {
                    foreach ($orders as $key => $value) {
                        $det = OrderDetails::Where('order_id', $value->id)->get(); 
                        if(sizeof($det) != 0) {
                            $orders[$key]->{'details'} = $det;
                        } else {
                            $orders[$key]->{'details'} =  '';
                        }
                    }
                }
            }

            if (sizeof($orders) != 0) {
                return View::make("transaction.orders.transaction_summary")->with(array('orders'=>$orders, 'vendors'=>$vendors, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('transaction_summary');
            }
        } elseif($gj_srh_from_date && $gj_srh_vendor) {
            $gj_srh_from_date = date('Y-m-d', strtotime($gj_srh_from_date));

            $ords = DB::table('orders as A')
                ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
                ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                ->select('A.id as o_id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                ->OrderBy('A.id', 'DESC')
                ->where('C.created_user', '=', $gj_srh_vendor)
                ->where('A.order_date', '=', $gj_srh_from_date)
                ->where('D.id', '=', $gj_srh_vendor)
                ->whereIn('D.user_type', ['2','3'])
                ->GroupBy('B.order_id')
                ->get();

            if (sizeof($ords) != 0) {
                foreach ($ords as $key => $value) {
                    array_push($fo_id, $value->o_id);
                }
            }

            if (sizeof($fo_id) != 0) {
                $orders = Orders::WhereIn('id', $fo_id)->OrderBy('id', 'DESC')->paginate(10);
                if(sizeof($orders) != 0) {
                    foreach ($orders as $key => $value) {
                        $det = OrderDetails::Where('order_id', $value->id)->get(); 
                        if(sizeof($det) != 0) {
                            $orders[$key]->{'details'} = $det;
                        } else {
                            $orders[$key]->{'details'} =  '';
                        }
                    }
                }
            }

            if (sizeof($orders) != 0) {
                return View::make("transaction.orders.transaction_summary")->with(array('orders'=>$orders, 'vendors'=>$vendors, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('transaction_summary');
            }
        } elseif($gj_srh_vendor) {
            $ords = DB::table('orders as A')
                ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
                ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                ->select('A.id as o_id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                ->OrderBy('A.id', 'DESC')
                ->where('C.created_user', '=', $gj_srh_vendor)
                ->where('D.id', '=', $gj_srh_vendor)
                ->whereIn('D.user_type', ['2','3'])
                ->GroupBy('B.order_id')
                ->get();

            if (sizeof($ords) != 0) {
                foreach ($ords as $key => $value) {
                    array_push($fo_id, $value->o_id);
                }
            }

            if (sizeof($fo_id) != 0) {
                $orders = Orders::WhereIn('id', $fo_id)->OrderBy('id', 'DESC')->paginate(10);
                if(sizeof($orders) != 0) {
                    foreach ($orders as $key => $value) {
                        $det = OrderDetails::Where('order_id', $value->id)->get(); 
                        if(sizeof($det) != 0) {
                            $orders[$key]->{'details'} = $det;
                        } else {
                            $orders[$key]->{'details'} =  '';
                        }
                    }
                }
            }

            if (sizeof($orders) != 0) {
                return View::make("transaction.orders.transaction_summary")->with(array('orders'=>$orders, 'vendors'=>$vendors, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('transaction_summary');
            }
        } else {
            Session::flash('message', 'Search Items Not Found!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('transaction_summary');
        }
    }
}