<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountSettings;
use App\User;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class AccountSettingsController extends Controller
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
                ->where('B.module_name', '=', 'Account Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $account = AccountSettings::all();
                $page = "Settings";

                if (sizeof($account) != 0) {
                	return response()->json(array('status_code'=>'1','response_msg'=>'Account Settings Deatils','response_data'=>array('data'=>$account,'page'=>$page)), 200);
                } else {
                    return response()->json(array('status_code'=>'0','response_msg'=>'No Account Settings Deatils'), 200);
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
                ->where('B.module_name', '=', 'Account Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
            	$login = session()->get('user');
            	$u_id = 0;
            	$account = "";
            	if($login) {
                    if($login->user_type == 2 || $login->user_type == 3) {
                    	$u_id = $login->id;
                    }
            	}

            	if($u_id != 0) {
                	$account = AccountSettings::Where('user_id', $u_id)->Where('is_block', 1)->first();
        	    	if($account) {
        	        	return View::make("settings.create_account_setting")->with(array('account'=>$account,'page'=>$page));
        	    	} else {
        	    		return View::make("settings.create_account_setting")->with(array('page'=>$page));
        	    	}
            	} else {
        			Session::flash('message', 'You Are Not Merchant!'); 
        			Session::flash('alert-class', 'alert-danger');
                	return redirect()->route('merchants_dashboard');
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
                ->where('B.module_name', '=', 'Account Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
            	$login = session()->get('user');
            	$u_id = 0;
            	$account = "";
            	if($login) {
                    if($login->user_type == 2 || $login->user_type == 3) {
                    	$u_id = $login->id;
                    }
            	}

            	$rules = array(
                    'user_id'                  => 'nullable',
                    'is_taxable'               => 'required',
                    'vat_gst_no'               => "required_if:is_taxable,==,1",
                    'primary_acc_type'         => 'required',
                    'primary_acc_no'           => 'required|numeric',
                    'primary_acc_holder_name'  => 'required',
                    'primary_acc_bank'         => 'required',
                    'primary_acc_branch'       => 'required',
                    'primary_acc_ifsc'         => 'required',
                    'optional_acc_type'        => 'nullable',
                    'optional_acc_no'          => 'nullable|numeric',
                    'optional_acc_holder_name' => 'nullable',
                    'optional_acc_bank'        => 'nullable',
                    'optional_acc_branch'      => 'nullable',
                    'optional_acc_ifsc'        => 'nullable',
                    'initial_credits'          => 'required|numeric',
                    'is_block'                 => 'nullable',
                );
                
                $messages=[
                    'primary_acc_type.required'=>'The Account Type field is required.',
                    'primary_acc_no.required'=>'The Account Number field is required.',
                    'primary_acc_no.numeric'=>'The Account Number field is only Numbers.',
                    'primary_acc_holder_name.required'=>'The Account Holder Name field is required.',
                    'primary_acc_bank.required'=>'The Bank Name field is required.',
                    'primary_acc_branch.required'=>'The Branch Name field is required.',
                    'primary_acc_ifsc.required'=>'The IFSC field is required.',
                    'optional_acc_no.numeric'=>'The Another Account Number field is only Numbers.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

            	if($u_id != 0) {
                	if ($validator->fails()) {
        	        	$account = AccountSettings::Where('user_id', $u_id)->Where('is_block', 1)->first();
        		    	if($account) {
        		        	return View::make('settings.create_account_setting')->withErrors($validator)->with(array('account'=>$account,'page'=>$page));
        		    	} else {
        		    		return View::make('settings.create_account_setting')->withErrors($validator)->with(array('page'=>$page));
        		    	}
        	        } else {
        	            $data = Input::all();
        	            $id = Input::get('id');
        	            $account = '';
        	            if($id != '') {
        	            	$account = AccountSettings::Where('id', $id)->first();
        	            } else {
        	            	$account = new AccountSettings();
        	            }

        	            if($account) {
        		            $account->user_id                  = $u_id;
        		            if(isset($data['is_taxable'])) {
        			            if($data['is_taxable'] == TRUE){
        			            	$account->is_taxable       = 1;
        			            } else {
        			            	if($data['is_taxable'] == 1) {
        			            		$account->is_taxable   = 1;
        			            	} else {
        			            		$account->is_taxable   = 0;
        			            	}
        			            }
        		            } else {
        	            		$account->is_taxable           = 0;
        		            }
        		            $account->vat_gst_no               = $data['vat_gst_no'];
        		            $account->primary_acc_type         = $data['primary_acc_type'];
        		            $account->primary_acc_no           = $data['primary_acc_no'];
        		            $account->primary_acc_holder_name  = $data['primary_acc_holder_name'];
        		            $account->primary_acc_bank         = $data['primary_acc_bank'];
        		            $account->primary_acc_branch       = $data['primary_acc_branch'];
        	                $account->primary_acc_ifsc         = $data['primary_acc_ifsc'];
        	                $account->optional_acc_type        = $data['optional_acc_type'];
        		            $account->optional_acc_no          = $data['optional_acc_no'];
        		            $account->optional_acc_holder_name = $data['optional_acc_holder_name'];
        		            $account->optional_acc_bank        = $data['optional_acc_bank'];
        		            $account->optional_acc_branch      = $data['optional_acc_branch'];
        		            $account->optional_acc_ifsc        = $data['optional_acc_ifsc'];
        		            $account->initial_credits          = $data['initial_credits'];
        		            $account->is_block                 = 1;
        	                
        	                if($account->save()) {
        	                	return View::make("settings.create_account_setting")->with(array('account'=>$account,'page'=>$page));
        	                } else{
        	                    return Redirect::back();
        	                }
        	            } else{
        	                return Redirect::back();
        	            }
        	        }
                } else {
        			Session::flash('message', 'You Are Not Merchant!'); 
        			Session::flash('alert-class', 'alert-danger');
                	return redirect()->route('merchants_dashboard');
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
