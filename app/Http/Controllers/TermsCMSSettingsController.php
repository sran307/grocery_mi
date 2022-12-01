<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TermsCMSSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class TermsCMSSettingsController extends Controller
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
                ->where('B.module_name', '=', 'Terms & Condition Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $terms = TermsCMSSettings::all();
            	return View::make("settings.terms.manage_terms")->with(array('terms'=>$terms, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Terms & Condition Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $terms = TermsCMSSettings::where('is_block', 1)->first();
            	if($terms) {
            		return View::make('settings.terms.terms')->with(array('terms'=>$terms, 'page'=>$page));
            	} else {
            		return View::make('settings.terms.terms')->with(array('page'=>$page));
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
    	$page = "Settings";
        $error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Terms & Condition Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
            	if($request->ajax() && isset($request->page_data)) {
        	    	$page_data = $request->page_data;
        	    	$id = $request->id;

            		$terms = "";
        	    	if(($id != 0) && ($id != '')) {
        	    		$terms = TermsCMSSettings::where('is_block', 1)->where('id', $id)->first();
        	    	} else {
        	    		$terms = new TermsCMSSettings();
        	    	}


                    if($terms) {
        	            $terms->page_data         = $page_data;	            
        	            $terms->is_block          = 1;

                        if($terms->save()) {
                        	$error = 0;
        	                Session::flash('message', 'Add or Update Successfully!'); 
        					Session::flash('alert-class', 'alert-success');

        	            } else {
        	            	$error = 1;
        	            	Session::flash('message', 'Add or Update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	            }  
                    } else {
                    	$error = 1;
                    	Session::flash('message', 'Add or Update Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                    }
                } else {
                	$error = 1;
                	Session::flash('message', 'Add or Update Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
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
