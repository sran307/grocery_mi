<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CreditsManagement;
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
use Carbon\Carbon;

class CreditsManagementController extends Controller
{
	protected $respose;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index () {
    	$page = "Accounts";
    	$log = session()->get('user');
    	
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Credits')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                if($log) {
        	    	if($log->user_type == 1) {
                		$credits = User::WhereIn('user_type',[2,3])->OrderBy('id', 'desc')->get();
            			return View::make("accounts.manage_credits")->with(array('credits'=>$credits, 'page'=>$page));
        	    	} elseif ($log->user_type == 2 || $log->user_type == 3) {
            			$credits = CreditsManagement::Where('merchant_id',$log->id)->paginate(10);
            			return View::make("accounts.manage_credits")->with(array('credits'=>$credits, 'page'=>$page));
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

    public function create ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Credits')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Accounts";
                $log = session()->get('user');
                if($log) {
                    if($log->user_type == 1) {
                        $credits = User::Where('id', $id)->first();
                        if($credits) {
                            return View::make('accounts.add_credits')->with(array('credits'=>$credits, 'page'=>$page));
                        } else {
                            return redirect()->route('manage_credits');
                        }
                    } else {
                        Session::flash('message', 'You Are Not Permission to Add!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_credits');
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

    public function store(Request $request) {
    	$loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Credits')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Accounts";
            	$log = session()->get('user');
            	$data = Input::all();

            	$rules = array(
                    'merchant_id'       => 'required|exists:users,id',
                    'previous_credits'  => 'nullable|numeric',
                    'current_credits'   => 'nullable|numeric',
                    'add_credits'       => 'required|numeric',
                    'remarks'           => 'required',
                );

                $messages=[
                    'add_credits.required'=>'The Add Credits field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                	return redirect()->route('add_credits', ['id' => $data['merchant_id']])->withErrors($validator);
                } else {
                	$credits = new CreditsManagement();

                    if($credits) {
        	            $credits->merchant_id       = $data['merchant_id'];	 
        	            $credits->previous_credits  = $data['current_credits'];	 
        	            $credits->current_credits   = $data['current_credits'] + $data['add_credits'];	 
        	            $credits->add_credits       = $data['add_credits'];	 
        	            $credits->remarks           = $data['remarks'];
                        
                        if($credits->save()) {
                        	$mers = User::Where('id', $credits->merchant_id)->first();
                        	if($mers) {
                        		$mers->credits = $credits->current_credits;
                        		if($mers->save()) {
                        			Session::flash('message', 'Added Successfully!'); 
        							Session::flash('alert-class', 'alert-success');
        							return redirect()->route('manage_credits');
                        		} else {
                        			Session::flash('message', 'Merchants Credits Not Added!'); 
        							Session::flash('alert-class', 'alert-danger');
        			                return redirect()->route('manage_credits');
                        		}
                        	} else {
                    			Session::flash('message', 'Invalid Merchants Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('manage_credits');
                        	}
        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_credits');
        	            }  
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_credits');
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
