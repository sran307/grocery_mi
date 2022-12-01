<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmailSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class EmailSettingsController extends Controller
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
                ->where('B.module_name', '=', 'E-Mail & Contact Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";                          
                $email = EmailSettings::all();

                if (sizeof($email) != 0) {
                	return response()->json(array('status_code'=>'1','response_msg'=>'Email Settings Deatils','response_data'=>array('data'=>$email,'page'=>$page)), 200);
                } else {
                    return response()->json(array('status_code'=>'0','response_msg'=>'No Email Settings Deatils'), 200);
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
                ->where('B.module_name', '=', 'E-Mail & Contact Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";                           
            	$email = EmailSettings::first();
            	if($email) {
                	return View::make("settings.email_setting")->with(array('email'=>$email,'page'=>$page));
            	} else {
                	return View::make('settings.email_setting');
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
                ->where('B.module_name', '=', 'E-Mail & Contact Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $rules = array(
                    'contact_name'         => 'required',
                    'contact_email'        => 'required|email',
                    'skype_email'          => 'nullable|email',
                    'webmaster_email'      => 'required|email',
                    'site_no_reply_email'  => 'required|email',
                    'contact_phone1'       => 'required|min:10|regex:/^[0-9 +()-]+$/',
                    'contact_phone2'       => 'required|min:10|regex:/^[0-9 +()-]+$/',
                    'address1'             => 'required',
                    'address2'             => 'required',
                    'pincode'              => 'required',
                    'country'              => 'required',
                    'state'                => 'required',
                    'city'                 => 'required',
                );
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                    $email = EmailSettings::first();
                    if($email) {
                       return View::make('settings.email_setting')->withErrors($validator)->with(array('email'=>$email,'page'=>$page));
                    } else {
                	   return View::make('settings.email_setting')->withErrors($validator);
                    }
                } else {
                    $data = Input::all();
                    $id = Input::get('id');
                    $email = '';
                    if($id != '') {
                    	$email = EmailSettings::Where('id', $id)->first();
                    } else {
                    	$email = new EmailSettings();
                    }

                    if($email) {
        	            $email->contact_name        = $data['contact_name'];
        	            $email->contact_email       = $data['contact_email'];
        	            $email->skype_email         = $data['skype_email'];
        	            $email->webmaster_email     = $data['webmaster_email'];
        	            $email->site_no_reply_email = $data['site_no_reply_email'];
        	            $email->contact_phone1      = $data['contact_phone1'];
                        $email->contact_phone2      = $data['contact_phone2'];
                        $email->address1            = $data['address1'];
                        $email->address2            = $data['address2'];
                        $email->pincode             = $data['pincode'];
                        $email->country             = $data['country'];
                        $email->state               = $data['state'];
        	            $email->city                = $data['city'];
                        $email->google_map          = $data['google_map'];
                        
                        if($email->save()) {
                            Session::flash('message', 'Update Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                        	return View::make("settings.email_setting")->with(array('email'=>$email,'page'=>$page));
                        } else {
                            Session::flash('message', 'Update Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return Redirect::back();
                        }
                    } else{
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
}