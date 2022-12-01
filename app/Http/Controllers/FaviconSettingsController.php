<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FaviconSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class FaviconSettingsController extends Controller
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
                ->where('B.module_name', '=', 'Favicon Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $favicon = FaviconSettings::all();

                if (sizeof($favicon) != 0) {
                	return response()->json(array('status_code'=>'1','response_msg'=>'Favicon Settings Deatils','response_data'=>array('data'=>$favicon,'page'=>$page)), 200);
                } else {
                    return response()->json(array('status_code'=>'0','response_msg'=>'No Favicon Settings Deatils'), 200);
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
                ->where('B.module_name', '=', 'Favicon Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $favicon = FaviconSettings::first();
            	if($favicon) {
                	return View::make("settings.favicon_setting")->with(array('favicon'=>$favicon,'page'=>$page));
            	} else {
                	return View::make('settings.favicon_setting')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'Favicon Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $rules = "";
            	$favicon = FaviconSettings::first();
            	if($favicon) {
        	    	$rules = array(
        	            'favicon_image'   => 'nullable',
        	        );
            	} else {
        	        $rules = array(
        	            'favicon_image'   => 'required',
        	        );
            	}
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                    $favicon = FaviconSettings::first();
                    if($favicon) {
                       return View::make('settings.favicon_setting')->withErrors($validator)->with(array('favicon'=>$favicon,'page'=>$page));
                    } else {
                	   return View::make('settings.favicon_setting')->withErrors($validator)->with(array('page'=>$page));
                    }
                } else {
                    $data = Input::all();
                    $id = Input::get('id');
                    $favicon = '';
                    if($id != '') {
                    	$favicon = FaviconSettings::Where('id', $id)->first();
                    } else {
                    	$favicon = new FaviconSettings();
                    }

                    if($favicon) {
                    	$img_files = Input::file('favicon_image');
                    	$old_favicon_image = Input::get('old_favicon_image');
                    	if(isset($img_files)) {
        					$file_name = $img_files->getClientOriginalName();
        	                $date = date('M-Y');
                            // $file_path = '../public/images/favicon/'.$date;
        	                $file_path = 'images/favicon/'.$date;
        	                $img_files->move($file_path, $file_name);
        	                $favicon->favicon_image = $date.'/'.$file_name;
        				} else if(isset($old_favicon_image) && $old_favicon_image != '') {
                    		$favicon->favicon_image = $old_favicon_image;
                    	} else {
        	                $favicon->favicon_image = NULL;
        				}
                        
                        if($favicon->save()) {
                        	Session::flash('message', 'Add or Update Successfully!'); 
            				Session::flash('alert-class', 'alert-success');
                        	return View::make("settings.favicon_setting")->with(array('favicon'=>$favicon,'page'=>$page));
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
