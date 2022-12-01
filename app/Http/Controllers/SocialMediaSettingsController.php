<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SocialMediaSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class SocialMediaSettingsController extends Controller
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
                ->where('B.module_name', '=', 'Social Media Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $social = SocialMediaSettings::all();

                if (sizeof($social) != 0) {
                    return response()->json(array('status_code'=>'1','response_msg'=>'Social Media Settings Deatils','response_data'=>array('data'=>$social,'page'=>$page)), 200);
                } else {
                    return response()->json(array('status_code'=>'0','response_msg'=>'No Social Media Settings Deatils'), 200);
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
                ->where('B.module_name', '=', 'Social Media Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $social = SocialMediaSettings::first();
                if($social) {
                    return View::make("settings.social_media_setting")->with(array('social'=>$social,'page'=>$page));
                } else {
                    return View::make('settings.social_media_setting');
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
                ->where('B.module_name', '=', 'Social Media Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $rules = array(
                    'facebook_app_id'       => 'required',
                    'facebook_secrect_key'  => 'required',
                    'facebook_page_url'     => 'nullable',
                    'facebook_like_url'     => 'nullable',
                    'twitter_page_url'      => 'nullable',
                    'twitter_app_id'        => 'required',
                    'twitter_secrect_key'   => 'required',
                    'linkedin_page_url'     => 'nullable',
                    'youtube_url'           => 'nullable',
                    'instagram_url'         => 'nullable',
                    'pinterest_url'         => 'nullable',
                    'gmap_app_key'          => 'required',
                    'analytics_code'        => 'required',
                );
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                    $social = SocialMediaSettings::first();
                    if($social) {
                       return View::make('settings.social_media_setting')->withErrors($validator)->with(array('social'=>$social,'page'=>$page));
                    } else {
                       return View::make('settings.social_media_setting')->withErrors($validator);
                    }
                } else {
                    $data = Input::all();
                    $id = Input::get('id');
                    $social = '';
                    if($id != '') {
                        $social = SocialMediaSettings::Where('id', $id)->first();
                    } else {
                        $social = new SocialMediaSettings();
                    }

                    if($social) {
                        $social->facebook_app_id       = $data['facebook_app_id'];
                        $social->facebook_secrect_key  = $data['facebook_secrect_key'];
                        $social->facebook_page_url     = $data['facebook_page_url'];
                        $social->facebook_like_url     = $data['facebook_like_url'];
                        $social->twitter_page_url      = $data['twitter_page_url'];
                        $social->twitter_app_id        = $data['twitter_app_id'];
                        $social->twitter_secrect_key   = $data['twitter_secrect_key'];
                        $social->linkedin_page_url     = $data['linkedin_page_url'];
                        $social->youtube_url           = $data['youtube_url'];
                        $social->instagram_url         = $data['instagram_url'];
                        $social->pinterest_url         = $data['pinterest_url'];
                        $social->gmap_app_key          = $data['gmap_app_key'];
                        $social->analytics_code        = $data['analytics_code'];
                        
                        if($social->save()) {
                            Session::flash('message', 'Add or Update Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            return View::make("settings.social_media_setting")->with(array('social'=>$social,'page'=>$page));
                        } else{
                            Session::flash('message', 'Add or Update Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return Redirect::back();
                        }
                    } else{
                        Session::flash('message', 'Add or Update Not Possible!'); 
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