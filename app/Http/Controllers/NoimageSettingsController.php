<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NoimageSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class NoimageSettingsController extends Controller
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
                ->where('B.module_name', '=', 'Noimage Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $noimage = NoimageSettings::all();

                if (sizeof($noimage) != 0) {
                	return response()->json(array('status_code'=>'1','response_msg'=>'noimage Settings Deatils','response_data'=>array('data'=>$noimage,'page'=>$page)), 200);
                } else {
                    return response()->json(array('status_code'=>'0','response_msg'=>'No noimage Settings Deatils'), 200);
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
                ->where('B.module_name', '=', 'Noimage Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $noimage = NoimageSettings::first();
            	if($noimage) {
                	return View::make("settings.noimage_setting")->with(array('noimage'=>$noimage,'page'=>$page));
            	} else {
                	return View::make('settings.noimage_setting')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'Noimage Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $rules = "";
            	$noimage = NoimageSettings::first();
            	if($noimage) {
        	    	$rules = array(
        	            'no_image'                  => 'nullable',
                        'profile_no_img'            => 'nullable',
        	            'product_no_image'          => 'nullable',
        	            'deal_no_image'             => 'nullable',
        	            'stores_no_image'           => 'nullable',
        	            'blog_banner_no_image'      => 'nullable',
        	            'banner_no_image'           => 'nullable',
        	            'category_banner_no_image'  => 'nullable',
        	            'ads_no_image'              => 'nullable',
        	            'category_no_image'         => 'nullable',
        	        );
            	} else {
        	        $rules = array(
        	            'no_image'                 => 'required',
                        'profile_no_img'           => 'required',
        	            'product_no_image'         => 'required',
        	            'deal_no_image'            => 'required',
        	            'stores_no_image'          => 'required',
        	            'blog_banner_no_image'     => 'required',
        	            'banner_no_image'          => 'required',
        	            'category_banner_no_image' => 'required',
        	            'ads_no_image'             => 'required',
        	            'category_no_image'        => 'required',
        	        );
            	}
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                    $noimage = NoimageSettings::first();
                    if($noimage) {
                       return View::make('settings.noimage_setting')->withErrors($validator)->with(array('noimage'=>$noimage,'page'=>$page));
                    } else {
                	   return View::make('settings.noimage_setting')->withErrors($validator)->with(array('page'=>$page));
                    }
                } else {
                    $data = Input::all();
                    $id = Input::get('id');
                    $noimage = '';
                    if($id != '') {
                    	$noimage = NoimageSettings::Where('id', $id)->first();
                    } else {
                    	$noimage = new NoimageSettings();
                    }

                    if($noimage) {
                        $img_files1 = Input::file('no_image');
                        $old_no_image = Input::get('old_no_image');
                        if(isset($img_files1)) {
                            $file_name = $img_files1->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/noimage/'.$date;
                            $file_path = 'images/noimage/'.$date;
                            $img_files1->move($file_path, $file_name);
                            $noimage->no_image = $date.'/'.$file_name;
                        } else if(isset($old_no_image) && $old_no_image != '') {
                            $noimage->no_image = $old_no_image;
                        } else {
                            $noimage->no_image = NULL;
                        }

                        $img_files2 = Input::file('profile_no_img');
                        $old_profile_no_img = Input::get('old_profile_no_img');
                        if(isset($img_files2)) {
                            $file_name = $img_files2->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/noimage/'.$date;
                            $file_path = 'images/noimage/'.$date;
                            $img_files2->move($file_path, $file_name);
                            $noimage->profile_no_img = $date.'/'.$file_name;
                        } else if(isset($old_profile_no_img) && $old_profile_no_img != '') {
                            $noimage->profile_no_img = $old_profile_no_img;
                        } else {
                            $noimage->profile_no_img = NULL;
                        }

                        $img_files3 = Input::file('product_no_image');
                        $old_product_no_image = Input::get('old_product_no_image');
                        if(isset($img_files3)) {
                            $file_name = $img_files3->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/noimage/'.$date;
                            $file_path = 'images/noimage/'.$date;
                            $img_files3->move($file_path, $file_name);
                            $noimage->product_no_image = $date.'/'.$file_name;
                        } else if(isset($old_product_no_image) && $old_product_no_image != '') {
                            $noimage->product_no_image = $old_product_no_image;
                        } else {
                            $noimage->product_no_image = NULL;
                        }
                        
                        $img_files4 = Input::file('deal_no_image');
                        $old_deal_no_image = Input::get('old_deal_no_image');
                        if(isset($img_files4)) {
                            $file_name = $img_files4->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/noimage/'.$date;
                            $file_path = 'images/noimage/'.$date;
                            $img_files4->move($file_path, $file_name);
                            $noimage->deal_no_image = $date.'/'.$file_name;
                        } else if(isset($old_deal_no_image) && $old_deal_no_image != '') {
                            $noimage->deal_no_image = $old_deal_no_image;
                        } else {
                            $noimage->deal_no_image = NULL;
                        }

                        $img_files5 = Input::file('stores_no_image');
                        $old_stores_no_image = Input::get('old_stores_no_image');
                        if(isset($img_files5)) {
                            $file_name = $img_files5->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/noimage/'.$date;
                            $file_path = 'images/noimage/'.$date;
                            $img_files5->move($file_path, $file_name);
                            $noimage->stores_no_image = $date.'/'.$file_name;
                        } else if(isset($old_stores_no_image) && $old_stores_no_image != '') {
                            $noimage->stores_no_image = $old_stores_no_image;
                        } else {
                            $noimage->stores_no_image = NULL;
                        }

                        $img_files6 = Input::file('blog_banner_no_image');
                        $old_blog_banner_no_image = Input::get('old_blog_banner_no_image');
                        if(isset($img_files6)) {
                            $file_name = $img_files6->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/noimage/'.$date;
                            $file_path = 'images/noimage/'.$date;
                            $img_files6->move($file_path, $file_name);
                            $noimage->blog_banner_no_image = $date.'/'.$file_name;
                        } else if(isset($old_blog_banner_no_image) && $old_blog_banner_no_image != '') {
                            $noimage->blog_banner_no_image = $old_blog_banner_no_image;
                        } else {
                            $noimage->blog_banner_no_image = NULL;
                        }

                        $img_files7 = Input::file('banner_no_image');
                        $old_banner_no_image = Input::get('old_banner_no_image');
                        if(isset($img_files7)) {
                            $file_name = $img_files7->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/noimage/'.$date;
                            $file_path = 'images/noimage/'.$date;
                            $img_files7->move($file_path, $file_name);
                            $noimage->banner_no_image = $date.'/'.$file_name;
                        } else if(isset($old_banner_no_image) && $old_banner_no_image != '') {
                            $noimage->banner_no_image = $old_banner_no_image;
                        } else {
                            $noimage->banner_no_image = NULL;
                        }
                        
                        $img_files8 = Input::file('category_banner_no_image');
                        $old_category_banner_no_image = Input::get('old_category_banner_no_image');
                        if(isset($img_files8)) {
                            $file_name = $img_files8->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/noimage/'.$date;
                            $file_path = 'images/noimage/'.$date;
                            $img_files8->move($file_path, $file_name);
                            $noimage->category_banner_no_image = $date.'/'.$file_name;
                        } else if(isset($old_category_banner_no_image) && $old_category_banner_no_image != '') {
                            $noimage->category_banner_no_image = $old_category_banner_no_image;
                        } else {
                            $noimage->category_banner_no_image = NULL;
                        }

                        $img_files9 = Input::file('ads_no_image');
                        $old_ads_no_image = Input::get('old_ads_no_image');
                        if(isset($img_files9)) {
                            $file_name = $img_files9->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/noimage/'.$date;
                            $file_path = 'images/noimage/'.$date;
                            $img_files9->move($file_path, $file_name);
                            $noimage->ads_no_image = $date.'/'.$file_name;
                        } else if(isset($old_ads_no_image) && $old_ads_no_image != '') {
                            $noimage->ads_no_image = $old_ads_no_image;
                        } else {
                            $noimage->ads_no_image = NULL;
                        }
                        
                    	$img_files10 = Input::file('category_no_image');
                    	$old_category_no_image = Input::get('old_category_no_image');
                    	if(isset($img_files10)) {
        					$file_name = $img_files10->getClientOriginalName();
        	                $date = date('M-Y');
                            // $file_path = '../public/images/noimage/'.$date;
        	                $file_path = 'images/noimage/'.$date;
        	                $img_files10->move($file_path, $file_name);
        	                $noimage->category_no_image = $date.'/'.$file_name;
        				} else if(isset($old_category_no_image) && $old_category_no_image != '') {
                    		$noimage->category_no_image = $old_category_no_image;
                    	} else {
        	                $noimage->category_no_image = NULL;
        				}
                        
                        if($noimage->save()) {
                        	Session::flash('message', 'Add or Update Successfully!'); 
            				Session::flash('alert-class', 'alert-success');
                        	return View::make("settings.noimage_setting")->with(array('noimage'=>$noimage,'page'=>$page));
                        } else{
                        	Session::flash('message', 'Add or Update Failed!'); 
            				Session::flash('alert-class', 'alert-danger');
                            return Redirect::back();
                        }
                    }  else{
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