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
use App\ReturnOrder;
use App\ReturnOrderDetails;
use App\RejectReturnOrderDetails;

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

class ReturnOrderController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function ReturnAllOrders () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Return Order')
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
                        $orders = ReturnOrder::OrderBy('id', 'DESC')->paginate(10);
                        if(sizeof($orders) != 0) {
                            foreach ($orders as $key => $value) {
                                $det = ReturnOrderDetails::Where('return_order_id', $value->id)->get(); 
                                if(sizeof($det) != 0) {
                                    $orders[$key]->{'details'} = $det;
                                } else {
                                    $orders[$key]->{'details'} =  '';
                                }
                            }
                        }
                    } else if($sess->user_type == 2 || $sess->user_type == 3) {
                        $ords = DB::table('return_orders as A')
                            ->leftjoin('return_order_details as B', 'A.id', '=', 'B.return_order_id')
                            ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                            ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                            ->select('A.id as ro_id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                            ->OrderBy('A.id', 'DESC')
                            ->where('C.created_user', '=', $sess->id)
                            ->where('D.id', '=', $sess->id)
                            ->whereIn('D.user_type', ['2','3'])
                            ->GroupBy('B.return_order_id')
                            ->get();

                        if (sizeof($ords) != 0) {
                            foreach ($ords as $key => $value) {
                                array_push($co_id, $value->ro_id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $orders = ReturnOrder::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                            if(sizeof($orders) != 0) {
                                foreach ($orders as $key => $value) {
                                    $det = ReturnOrderDetails::Where('return_order_id', $value->id)->get(); 
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

                return View::make("transaction.orders.return_all_orders")->with(array('orders'=>$orders, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Return Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
        		$orders = ReturnOrder::where('id',$id)->first();
        		if($orders) {
        			$orders['details'] = ReturnOrderDetails::Where('return_order_id', $orders->id)->get();
        		}
        		return View::make("transaction.orders.view_return_orders")->with(array('orders'=>$orders, 'page'=>$page));
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

    public function ReturnStsOrders( Request $request) {   
        $id = 0;
        $status = 0;
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Return Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->id) && isset($request->status)){
                    $id = $request->id;
                    $status = $request->status;
                    if($id != 0) {
                        $orders = Orders::where('id',$id)->first();
                        if($orders) {
                            $orders->return_order_status = $status;

                            if($orders->save()) {
                                if($orders->return_order_status == 3) {
                                    $text = "Your Return Order Request is Cancelled, ecambiar.com";
                                    $text = urlencode($text);
                 
                                    $curl = curl_init();
                                    $user = User::Where('id', $orders->user_id)->first();
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
                                        $subject = "Cancel Return Order Request";

                                        $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                                <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                                <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                                    <h2 style="color: #ff5c00;margin-top: 0px;">Cancel Return Order Request</h2>
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
                                                    </table>

                                                    <p>Your Return Order Request is Cancelled.</p>
                                                    <p>Thank You.</p>
                                                     <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                                    <p>Thanks & Regards,</p>
                                                    <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                                </div>
                                            </div>';
                                            
                                            
                                        // if(1==1){
                                        if(mail($to1,$subject,$txt,$headers) && mail($to2,$subject,$txt,$headers)){
                                            Session::flash('message', 'Return Order Request Cancelled and Mail Send Successfully!'); 
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
                                            Session::flash('message', 'Return Order Request Cancelled and Message Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                            echo $error = 0;die();
                                        } else {
                                            Session::flash('message', 'Return Order Request Cancelled  Successfully!'); 
                                            Session::flash('alert-class', 'alert-danger');
                                            echo $error = 0;die();
                                        }
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
                            } else {
                                Session::flash('message', 'Status Changed Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                $error = 1;
                            }
                        }   else {
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

    public function GetReturnOrdersStatus ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Return Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $orders = ReturnOrder::where('id',$id)->first();
                if($orders) {
                    $orders['details'] = ReturnOrderDetails::Where('return_order_id', $orders->id)->where('status' , '!=', 'Reject')->Where('order_returned', 'No')->get();
                    return View::make("transaction.orders.get_reject_return_orders")->with(array('orders'=>$orders, 'page'=>$page));
                } else {
                    Session::flash('message', 'Reject Return Orders not Possible this Time!');
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->back();
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

    public function ReturnOrdersStatus( Request $request) {   
        $id = 0;
        $status = 0;
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Return Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
                $data = Input::all();

                $rules = array(
                    'return_order_id'   => 'required|exists:return_orders,id',
                    // 'reject_remarks'    => 'required',

                    'return_type'     => 'required',
                    'status'          => 'required',
                    'admin_remarks'   => 'required',
                );

                $messages=[
                    'cancel_approved.required'=>'The Cancel Order Status field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                    Session::flash('message', 'Fix Validation Error!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return Redirect::to('/get_reject_return_orders/'.$data['return_order_id'])->withErrors($validator);
                } else {
                    $ok1 = 0;
                    $rod_id = [];
                    $ret_orders = ReturnOrder::where('id',$data['return_order_id'])->first();
                    if($ret_orders) {
                        $user = User::Where('id', $ret_orders->user_id)->first();
                        if($user) {
                            if (isset($data['rtn_odr_det_id']) && count($data['rtn_odr_det_id']) != 0) {
                                foreach ($data['rtn_odr_det_id'] as $key => $value) {
                                    $ret_odr_dets = ReturnOrderDetails::Where('id', $value)->first();
                                    if($ret_odr_dets) {
                                        if(isset($data['status'][$key])) {
                                            $ret_odr_dets->status  = $data['status'][$key];     
                                        } else {
                                            $ret_odr_dets->status  = 'Process';     
                                        }

                                        if(isset($data['admin_remarks'][$key])) {
                                            $ret_odr_dets->admin_remarks  = $data['admin_remarks'][$key];     
                                        } else {
                                            $ret_odr_dets->admin_remarks  = NULL;     
                                        }

                                        if(isset($data['return_type'][$key])) {
                                            $ret_odr_dets->return_type  = $data['return_type'][$key];     
                                        } else {
                                            $ret_odr_dets->return_type  = $ret_odr_dets->return_type;     
                                        }

                                        if($ret_odr_dets->status != "Process") {
                                            $ok1 = 1;
                                            if($ret_odr_dets->save()) {
                                                array_push($rod_id, $ret_odr_dets->id);
                                            }
                                        }                                
                                    }
                                }                            
                            }

                            if (sizeof($rod_id) != 0) {
                                $orders = Orders::where('id',$ret_orders->order_id)->first();
                                if($orders) {
                                    $all_ret_dets = ReturnOrderDetails::WhereIn('status', ['Accept','Process'])->Where('return_order_id', $ret_orders->id)->get();
                                    if(sizeof($all_ret_dets) == 0) {
                                        $orders->return_order_status = 3;
                                        $orders->save();
                                    }
                                } 

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

                                $details = "";
                                $cge_rorder_detail = ReturnOrderDetails::WhereIn('id', $rod_id)->get();
                                $details="";
                                if(sizeof($cge_rorder_detail) != 0) {
                                    foreach ($cge_rorder_detail as $key => $value) {
                                        $att_tit = "";
                                        if(isset($value->att_name) && $value->att_name != 0) {
                                            if(isset($value->AttName->att_name) && isset($value->AttValue->att_value)) {
                                                $att_tit = '<span>('.$value->AttName->att_name.' : '.$value->AttValue->att_value.')</span>';
                                            }
                                        }

                                        $details.= '<tr>
                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: center;"> '.$value->product_title.' '. $att_tit .'</td>
                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: center;">'.$value->return_type.'</td>
                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: center;">'.$value->status.'</td>
                                            <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: center;">'.$value->admin_remarks.'</td>
                                        </tr>';
                                    }

                                    $customer_name = $user->first_name.' '.$user->last_name;
                                    $address = $user->address1.','.$user->address2.','.$user->City->city_name.','.$user->pincode.','.$user->State->state.','.$user->Country->country_name;
                                    $contact = $user->phone.','.$user->phone2;
                                    
                                    $name = $user->first_name.' '.$user->last_name;
                                    $email = $user->email;

                                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                    $headers.= "MIME-Version: 1.0\r\n";
                                    // $headers.= "From: $admin_email" . "\r\n";
                                    $headers.= "From: noreply@ecambiar.com" . "\r\n";
                                    $to = $email;
                                    $to2 = $admin_email;
                                    $subject = "Return Orders Status";
                                    $txt = '<div class="gj_mail" style="width: 600px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat;  background-size: 100% 102%;">
                                        <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                        <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                            <h2 style="color: #ff5c00;margin-top: 0px;">Return Orders Status Details</h2>
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
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$ret_orders->order_code.'</td>
                                                </tr>

                                                <tr>
                                                    <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Order Date</th>
                                                    <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$ret_orders->order_date.'</td>
                                                </tr>
                                            </table>

                                            <table style="width: 100%;border: 1px solid black;">
                                                <tr>
                                                    <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Product Title</th>
                                                    <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Return Type</th>
                                                    <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Status</th>
                                                    <th style="width: 100px;text-align: center;text-transform: uppercase;padding-bottom: 5px;color: black;border: 1px solid black;font-size: 13px;font-weight: 700;">Remarks</th>
                                                </tr>'.$details.'
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
                                            $text = "Your Order Return/Replacemnet Request against ".$ret_orders->order_code." Has been verified, ecambiar.com";
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
                                                Session::flash('message', 'Status Changed Confirm Message and Email Send Successfully!');
                                                Session::flash('alert-class', 'alert-success');
                                            } else {
                                                Session::flash('message', 'Status Changed & Email Send Successfully!'); 
                                                Session::flash('alert-class', 'alert-success');
                                            }
                                            return redirect()->route('return_all_orders');
                                        } else {
                                            Session::flash('message', 'Status Changed & Mail Send Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                        }
                                        return redirect()->route('return_all_orders');
                                    } else {
                                        if($user->phone) {
                                            $text = "Your Order Return/Replacemnet Request against ".$ret_orders->order_code." Has been verified, ecambiar.com";
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
                                                Session::flash('message', 'Status Changed Confirm Message and Email Send Successfully!');
                                                Session::flash('alert-class', 'alert-success');
                                            } else {
                                                Session::flash('message', 'Status Changed Successfully!'); 
                                                Session::flash('alert-class', 'alert-success');
                                            }
                                            return redirect()->route('all_orders');
                                        } else {
                                            Session::flash('message', 'Status Changed Successfully!'); 
                                            Session::flash('alert-class', 'alert-success');
                                        }
                                        return redirect()->route('return_all_orders');
                                    }
                                } else {
                                    Session::flash('message', 'Status Changed Failed!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('return_all_orders');
                                } 
                            } else {
                                Session::flash('message', 'Status Changed Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('return_all_orders');
                            }
                        } else {
                            Session::flash('message', 'Invalid User, Try to Another Time!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('return_all_orders');
                        }
                    } else {
                        Session::flash('message', 'Please Try to Another Time, Invalid Return Order!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('return_all_orders');
                    }
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
                // $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
            // $error = 1;
        }

        // echo $error;
    }

    public function ReturnOrdersDelete( Request $request) { 
        $id = 0;
        $error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Return Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->id)){
                    $id = $request->id;
                    if($id != 0) {
                        $rod = ReturnOrderDetails::where('id',$id)->first();
                        if($rod){
                            $rod->status = "Reject";
                            if($rod->save()) {
                                Session::flash('message', 'Remove Successfully!'); 
                                Session::flash('alert-class', 'alert-success');
                                $error = 0;
                            } else {
                                Session::flash('message', 'Remove Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                $error = 1;
                            }
                        }   else {
                            Session::flash('message', 'Remove Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            $error = 1;
                        }           
                    } else {
                        Session::flash('message', 'Remove Failed!'); 
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

    public function ExportCSV( Request $request) {  
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Return Order')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()){
                    $ids = $request->ids;
                    $table = array();
                    $filename = "ReturnOrders.csv";
                    $user = session()->get('user');

                    if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            if($user) {
                                $table = ReturnOrder::whereIn('id',$ids)->get();
                            } else {
                                echo $error = 1;die();
                            }
                            $filename = "ReturnOrders.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') {
                        if($user) {
                            if ($user->user_type == 1) {
                                $table = ReturnOrder::all();
                            } else if ($user->user_type == 2 || $user->user_type == 3) {
                                $co_id=[];
                                $ords = DB::table('return_orders as A')
                                    ->leftjoin('return_order_details as B', 'A.id', '=', 'B.return_order_id')
                                    ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                                    ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                                    ->select('A.id as ro_id','B.id as od_id', 'C.id as p_id', 'D.id as u_id')
                                    ->OrderBy('A.id', 'DESC')
                                    ->where('C.created_user', '=', $sess->id)
                                    ->where('D.id', '=', $sess->id)
                                    ->whereIn('D.user_type', ['2','3'])
                                    ->GroupBy('B.return_order_id')
                                    ->get();

                                if (sizeof($ords) != 0) {
                                    foreach ($ords as $key => $value) {
                                        array_push($co_id, $value->ro_id);
                                    }
                                }

                                if (sizeof($co_id) != 0) {
                                    $orders = ReturnOrder::WhereIn('id', $co_id)->get();
                                    if(sizeof($orders) != 0) {
                                        foreach ($orders as $key => $value) {
                                            $det = ReturnOrderDetails::Where('return_order_id', $value->id)->get(); 
                                            if(sizeof($det)) {
                                                $orders[$key]->{'details'} = $det;
                                            } else {
                                                $orders[$key]->{'details'} =  '';
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
                        $filename = "All_ReturnOrders.csv";
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }

                    foreach ($table as $key => $value) {
                        $table_det = ReturnOrderDetails::where('return_order_id',$value->id)->get();

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

                        if($value->return_date) {
                            $table[$key]['return_date'] = date('d-m-Y', strtotime($value->return_date));
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

                        if($value->total_items) {
                            $table[$key]['total_items'] = $value->total_items;
                        } else {
                            $table[$key]['total_items'] = "---------";
                        }

                        if($value->net_amount) {
                            $table[$key]['net_amount'] = 'Rs. '.$value->net_amount;
                        } else {
                            $table[$key]['net_amount'] = "---------";
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

                                $odr= 'Product Title : '.$valuez->product_title.' '.$atts.', Product Add : '.$valuez->Products->Creatier->first_name.' '.$valuez->Products->Creatier->last_name.', Price : Rs.'.$valuez->unitprice.', Qty : '.$valuez->order_qty.', Tax : '.$valuez->tax.'%, Total Price : '.$valuez->totalprice.', Return Type : '.$valuez->return_type.', Return Qty : '.$valuez->return_qty.', Return Amount : '.$valuez->return_amount.', Order Returned : '.$valuez->order_returned.', Reason : '.$valuez->reason.', Remarks : '.$valuez->remarks.', ';                              
                            }
                            $table[$key]['odr'] = $odr;
                        } else {
                            $table[$key]['odr'] = $odr;
                        }
                    }
                    
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('Order Code', 'Order Date', 'Return Date', 'Return Order Status', 'Contact Person', 'Contact Number', 'Shipping Address', 'Total Items', 'Net Amount', 'GRV Order Deatils'));

                    foreach($table as $row) {
                        fputcsv($handle, array($row['order_code'], $row['order_date'], $row['return_date'], $row['return_order_status'], $row['contact_person'], $row['contact_no'], $row['shipping_address'], $row['total_items'], $row['net_amount'], $row['odr']));
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
