<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class LogoSettingsController extends Controller
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
                ->where('B.module_name', '=', 'Logo Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $logo = LogoSettings::all();
                $page = "Settings";

                if (sizeof($logo) != 0) {
                	return response()->json(array('status_code'=>'1','response_msg'=>'logo Settings Deatils','response_data'=>array('data'=>$logo,'page'=>$page)), 200);
                } else {
                    return response()->json(array('status_code'=>'0','response_msg'=>'No logo Settings Deatils'), 200);
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
                ->where('B.module_name', '=', 'Logo Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $logo = LogoSettings::first();
            	if($logo) {
                	return View::make("settings.logo_setting")->with(array('logo'=>$logo,'page'=>$page));
            	} else {
                	return View::make('settings.logo_setting')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'Logo Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.edit', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $rules = "";
            	$logo = LogoSettings::first();
            	if($logo) {
        	    	$rules = array(
        	            'logo_image'   => 'nullable',
        	        );
            	} else {
        	        $rules = array(
        	            'logo_image'   => 'required',
        	        );
            	}
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                    $logo = LogoSettings::first();
                    if($logo) {
                       return View::make('settings.logo_setting')->withErrors($validator)->with(array('logo'=>$logo,'page'=>$page));
                    } else {
                	   return View::make('settings.logo_setting')->withErrors($validator)->with(array('page'=>$page));
                    }
                } else {
                    $data = Input::all();
                    $id = Input::get('id');
                    $logo = '';
                    if($id != '') {
                    	$logo = LogoSettings::Where('id', $id)->first();
                    } else {
                    	$logo = new LogoSettings();
                    }

                    if($logo) {
                    	$img_files = Input::file('logo_image');
                    	$old_logo_image = Input::get('old_logo_image');
                    	if(isset($img_files)) {
        					$file_name = $img_files->getClientOriginalName();
        	                $date = date('M-Y');
                            // $file_path = '../public/images/logo/'.$date;
        	                $file_path = 'images/logo/'.$date;
        	                $img_files->move($file_path, $file_name);
        	                $logo->logo_image = $date.'/'.$file_name;
        				} else if(isset($old_logo_image) && $old_logo_image != '') {
                    		$logo->logo_image = $old_logo_image;
                    	} else {
        	                $logo->logo_image = NULL;
        				}
                        
                        if($logo->save()) {
                        	Session::flash('message', 'Add or Update Successfully!'); 
            				Session::flash('alert-class', 'alert-success');
                        	return View::make("settings.logo_setting")->with(array('logo'=>$logo,'page'=>$page));
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
}
