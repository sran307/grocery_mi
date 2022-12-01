<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    AboutUsCMSSettings,
    about_widget_1,
    AboutWidget2,
    HomeWidget,
    DeliveryTime
    };

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class AboutUsCMSSettingsController extends Controller
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
                ->where('B.module_name', '=', 'CMS About Us Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $about_page = AboutUsCMSSettings::all();
            	return View::make("settings.aboutus.manage_about_page")->with(array('about_page'=>$about_page, 'page'=>$page));
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
                ->where('B.module_name', '=', 'CMS About Us Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $about_page = AboutUsCMSSettings::where('is_block', 1)->first();
            	if($about_page) {
            		return View::make('settings.aboutus.add_about_page')->with(array('about_page'=>$about_page, 'page'=>$page));
            	} else {
            		return View::make('settings.aboutus.add_about_page')->with(array('page'=>$page));
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
        $error = 1;

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'CMS About Us Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->orwhere('A.add', '=', 1)
                ->first();

            if($privil) {
    	       $page = "Settings";
                //dd($request->all());
            	if( isset($request->page_old)) {
        	    	$page_data = $request->page_old;
        	    	$id = $request->id;
                    $page_heading = $request->page_heading;
                    $image  = $request->file("about_image");

                    if($image!=null){
                        $image_name=$image->getClientOriginalName();
                        $date=date("M-Y");
                        $destination="images/site_img/".$date;
                        $image->move($destination, $image_name);

                        $about_page = AboutUsCMSSettings::where('is_block', 1)->where('id', $id)->first();
                        $about_page->image = $date.'/'.$image_name;
                        $about_page->save();
                    }
                   // dd($image_name);
            		$about_page = "";
        	    	if(($id != 0) && ($id != '')) {
        	    		$about_page = AboutUsCMSSettings::where('is_block', 1)->where('id', $id)->first();
        	    	} else {
        	    		$about_page = new AboutUsCMSSettings();
        	    	}


                    if($about_page) {
        	            $about_page->page_data         = $page_data;
                        $about_page->heading           = $page_heading;
                        
                        // $about_page->page_data  = htmlentities($page_data, ENT_QUOTES);                 
                        // $about_page->page_data  = preg_replace( "/\r|\n/", "", $about_page->page_data);	            
        	            $about_page->is_block          = 1;

                        if($about_page->save()) {
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

        return redirect()->back();
    }

    //about us page first widget
    public function manage_about_1()
    {
        $data=about_widget_1::all();
        return view("settings.aboutus.about_widget_1", compact("data"));
    }

    //edit widget 1 page
    public function edit_about_widget_1($id){
        //dd($id);
        $data=about_widget_1::where("id", $id)->first();
        return view("settings.aboutus.edit_widget_1", compact("data"));
    }

    //update widget 1 data
    public function update_widget_1(Request $request){
       //dd($request->all());
       $id=$request->page_id;
       $description= $request->description;
       $value=$request->value;
       $image=$request->widget_1_img;

       $validator=Validator::make($request->all(),[
          // "id"         => "required",
           "description" =>  "required",
           "value"      =>  "required"
       ])->validate();

       if($image!=null){
           $validator=Validator::make($request->all(),[
                "widget_1_img"      =>  "required"
           ])->validate();

            $image_name=$image->getClientOriginalName();
            $date=date("M-Y");
            $destination="images/site_img/".$date;
            $image->move($destination, $image_name);
            about_widget_1::where("id", $id)->update([
                "image" =>  $date.'/'.$image_name,
            ]);       
       }

       about_widget_1::where("id", $id)->update([
           "description"    =>  $description,
           "value"          =>  $value
       ]);

       return redirect()->route("manage_about_1")->with([
        Session::flash('message', 'Heading Updated'),
        Session::flash('alert-class', 'success'),
      ]);
    }

    //about us page widget 2
    public function manage_about_2()
    {
        $data=AboutWidget2::all();
        return view("settings.aboutus.about_widget_2", compact("data"));
    }

    public function edit_widget_2($id){
        //dd($id);
        $data=AboutWidget2::where("id", $id)->first();
        return view("settings.aboutus.edit_widget_2", compact("data"));
    }

    public function update_widget_2(Request $request){
        //dd($request->all());
        $id=$request->page_id;
        $content=$request->content;
       
 
        $validator=Validator::make($request->all(),[
            "page_id"            => "required",
            "content"      =>  "required"
        ])->validate();
 
        AboutWidget2::where("id", $id)->update([
            "contents"    =>  $content,
        ]);
 
        return redirect()->route("manage_about_2")->with([
         Session::flash('message', 'Content Updated'),
         Session::flash('alert-class', 'success'),
       ]);
     }
    public function widget1()
    {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'CMS About Us Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $data = HomeWidget::all();
            	return View::make("settings.aboutus.widget_1")->with(array('data'=>$data, 'page'=>$page));
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

    public function edit_widget1($id)
    {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'CMS About Us Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $data = HomeWidget::Where("id", $id)->first();
            	return View::make("settings.aboutus.edit_home_widget_1")->with(array('data'=>$data, 'page'=>$page));
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

    public function update_widget1(Request $request)
    {
       // dd($request->all());
        //dd($request->all());
       $id=$request->id;
       $url= $request->url;
       $image=$request->image;

       $validator=Validator::make($request->all(),[
          // "id"         => "required",
           "url" =>  "required",
       ])->validate();

       if($image!=null){
           $validator=Validator::make($request->all(),[
                "image"      =>  "required"
           ])->validate();

            $image_name=$image->getClientOriginalName();
            $date=date("M-Y");
            $destination="images/site_img/".$date;
            $image->move($destination, $image_name);
            HomeWidget::where("id", $id)->update([
                "image" =>  $date.'/'.$image_name,
            ]);       
       }

       HomeWidget::where("id", $id)->update([
           "url"    =>  $url
       ]);

       return redirect()->route("widget1")->with([
        Session::flash('message', 'Updated'),
        Session::flash('alert-class', 'success'),
      ]);
    }

    public function delivery_manage()
    {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'CMS About Us Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $data = DeliveryTime::all();
            	return View::make("settings.aboutus.delivery")->with(array('data'=>$data, 'page'=>$page));
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

    public function add_delivery()
    {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'CMS About Us Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                
            	return View::make("settings.aboutus.add_delivery")->with(array('page'=>$page));
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

    public function add_delivery_time(Request $request)
    {
        $time=$request->time;
 //
        $validator=Validator::make($request->all(),[
            "time" =>  "required",
        ])->validate();
       // dd($request->all());
        $result=DeliveryTime::create([
            "time"=>$time,
        ]);
        if($result) {
            $page = "Settings";
            Session::flash('message', 'Added!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('delivery_manage');
        } else {
            Session::flash('message', 'Cannot addedd!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function edit_delivery($id)
    {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'CMS About Us Page')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $data=DeliveryTime::where("id", $id)->get();
            	return View::make("settings.aboutus.edit_delivery")->with(array('data'=>$data, 'page'=>$page));
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

    public function edit_delivery_time(Request $request)
    {
        $time=$request->time;
        $id=$request->id;
        $validator=Validator::make($request->all(),[
            "time" =>  "required",
            "id"    =>  "required"
        ])->validate();
       // dd($request->all());
        $result=DeliveryTime::where("id", $id)->update([
            "time"=>$time,
        ]);
        if($result) {
            $page = "Settings";
            Session::flash('message', 'Updated!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('delivery_manage');
        } else {
            Session::flash('message', 'Cannot updated!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }
    public function delete_delivery( Request $request) {	
		$id = 0;
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Banner Image Settings')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$banner = DeliveryTime::where('id',$id)->first();
        				if($banner){
        					if($banner->delete()) {
        						Session::flash('message', 'Deleted Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						$error = 0;
        					} else {
        						Session::flash('message', 'Deleted Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        						$error = 1;
        					}
        				}	else {
        					Session::flash('message', 'Deleted Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        					$error = 1;
        				}			
        			} else {
        				Session::flash('message', 'Deleted Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = 1;
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

		echo $error;
	}
}