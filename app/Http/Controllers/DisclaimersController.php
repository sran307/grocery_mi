<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Disclaimers;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use App\SiteSettings;
use Redirect;
use URL;

class DisclaimersController extends Controller
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
                ->where('B.module_name', '=', 'Disclaimers')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $discls = Disclaimers::all();
                return View::make("settings.discls.add_disclaimers")->with(array('discls'=>$discls, 'page'=>$page));
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
    public function get_settings($slug)
    {
        
       $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Disclaimers')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $as=SiteSettings::where('slug',$slug)->first();
              
                    if($as) {
                    return View::make('settings.site_set.'.$slug)->with(array('as'=>$as, 'page'=>$page,'slug'=>$slug));
                } else {
                    return View::make('settings.site_set.'.$slug)->with(array('page'=>$page,'slug'=>$slug));
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
    public function add_update_ettings($slug,Request $request)
    {
        $all=SiteSettings::where('slug',$slug)->first();
        if($all!=null)
        {
            $all->value=$request->page_datas;
            
        }
        else
        {
            $all=new SiteSettings();
            $all->slug=$slug;
            $all->value=$request->page_datas;
        }
        $all->save();
         Session::flash('message', 'Update Successfully'); 
            Session::flash('alert-class', 'alert-success');
             return redirect()->back();
    }
    public function create () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Disclaimers')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $discls = Disclaimers::where('is_block', 1)->first();
                if($discls) {
                    return View::make('settings.discls.add_disclaimers')->with(array('discls'=>$discls, 'page'=>$page));
                } else {
                    return View::make('settings.discls.add_disclaimers')->with(array('page'=>$page));
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
        $error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Disclaimers')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                if($request->ajax() && isset($request->page_data)) {
                    $page_data = $request->page_data;
                    $id = $request->id;

                    $discls = "";
                    if(($id != 0) && ($id != '')) {
                        $discls = Disclaimers::where('is_block', 1)->where('id', $id)->first();
                    } else {
                        $discls = new Disclaimers();
                    }


                    if($discls) {
                        $discls->page_data         = $page_data;
                        $discls->is_block          = 1;

                        if($discls->save()) {
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