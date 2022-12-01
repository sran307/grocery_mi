<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class PaymentSettingsController extends Controller
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
                ->where('B.module_name', '=', 'Payment Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $payment = PaymentSettings::all();

                if (sizeof($payment) != 0) {
                	return response()->json(array('status_code'=>'1','response_msg'=>'payment Settings Deatils','response_data'=>array('data'=>$payment,'page'=>$page)), 200);
                } else {
                    return response()->json(array('status_code'=>'0','response_msg'=>'No payment Settings Deatils'), 200);
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
                ->where('B.module_name', '=', 'Payment Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $payment = PaymentSettings::first();
            	if($payment) {
                	return View::make("settings.payment_setting")->with(array('payment'=>$payment,'page'=>$page));
            	} else {
                	return View::make('settings.payment_setting');
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

    public function store(Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Payment Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $rules = array(
                    'country_id'            => 'nullable',
                    'country_name'          => 'required',
                    'country_code'          => 'required',
                    'currency_symbol'       => 'required',
                    'currency_code'         => 'required',
                    /*'paypal_account'        => 'nullable',
                    'paypal_api_password'   => 'nullable',
                    'paypal_api_signature'  => 'nullable',
                    'payUmoney_key'         => 'nullable',
                    'payUmoney_salt'        => 'nullable',*/
                    'cash_free_api'         => 'required',
                    'cash_free_secret'      => 'required',
                    'payment_mode'          => 'required',
                );
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                    $payment = PaymentSettings::first();
                    if($payment) {
                       return View::make('settings.payment_setting')->withErrors($validator)->with(array('payment'=>$payment,'page'=>$page));
                    } else {
                	   return View::make('settings.payment_setting')->withErrors($validator);
                    }
                } else {
                    $data = Input::all();
                    $id = Input::get('id');
                    $payment = '';
                    if($id != '') {
                    	$payment = PaymentSettings::Where('id', $id)->first();
                    } else {
                    	$payment = new PaymentSettings();
                    }

                    if($payment) {
        	            $payment->country_id            = $data['country_name'];
        	            $payment->country_name          = $data['h_country_name'];
        	            $payment->country_code          = $data['country_code'];
        	            $payment->currency_symbol       = $data['currency_symbol'];
        	            $payment->currency_code         = $data['currency_code'];
        	            /*$payment->paypal_account        = $data['paypal_account'];
        	            $payment->paypal_api_password   = $data['paypal_api_password'];
        	            $payment->paypal_api_signature  = $data['paypal_api_signature'];
        	            $payment->payUmoney_key         = $data['payUmoney_key'];
        	            $payment->payUmoney_salt        = $data['payUmoney_salt'];*/
                        $payment->cash_free_api         = $data['cash_free_api'];
                        $payment->cash_free_secret      = $data['cash_free_secret'];
        	            $payment->payment_mode          = $data['payment_mode'];
                        
                        if($payment->save()) {
                        	Session::flash('message', 'Add or Update Successfully!'); 
            				Session::flash('alert-class', 'alert-success');
                        	return View::make("settings.payment_setting")->with(array('payment'=>$payment,'page'=>$page));
                        } else{
                        	Session::flash('message', 'Add or Update Failed!'); 
            				Session::flash('alert-class', 'alert-danger');
                            return Redirect::back();
                        }
                    } else{
                        Session::flash('message', 'Add or Update Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return Redirect::back();
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

    public function CountryDetails( Request $request) {	
		if($request->ajax() && isset($request->c_id)){
			$c_id = $request->c_id;
			$countrys = \DB::table('countries_managements')->where('id',$c_id)->where('is_block',1)->first();
			if($countrys){
				echo json_encode($countrys);
			}
		}
	}
}
