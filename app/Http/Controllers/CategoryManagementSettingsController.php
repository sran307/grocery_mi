<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryManagementSettings;
use App\SubCategoryManagementSettings;
use App\BannerImageSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class CategoryManagementSettingsController extends Controller
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
                ->where('B.module_name', '=', 'Product Main Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $main = CategoryManagementSettings::all();
                if($main) {
                    foreach ($main as $key => $value) {
                        $sub = SubCategoryManagementSettings::where('main_cat_name',$value->id)->get();
                        $cnt_sub = count($sub);
                        $main[$key]['sub'] = $cnt_sub;     
                    }       
                }
            	return View::make("settings.category_management.manage_category")->with(array('main'=>$main, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Product Main Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";                                   
            	return View::make('settings.category_management.add_category')->with(array('page'=>$page));
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
public function resize_image($path, $width=200, $height=200, $update = false) {
   $size  = @getimagesize($path);// [width, height, type index]
   $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png',4 =>'jpg');
   
   if ( array_key_exists($size['2'], $types) ) {
      $load        = 'imagecreatefrom' . $types[$size['2']];
      $save        = 'image'           . $types[$size['2']];
      $image       = $load($path);
      $resized     = imagecreatetruecolor($width, $height);
      $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
      imagesavealpha($resized, true);
      imagefill($resized, 0, 0, $transparent);
      imagecopyresampled($resized, $image, 0, 0, 0, 0, $width, $height, $size['0'], $size['1']);
      imagedestroy($image);
       $save($resized, $update ? $path : null);
   }
}
    public function store(Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Product Main Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $rules = array(
                    'main_cat_name'    => 'required',
                    'main_cat_image'   => 'required',
                    'main_cat_icon'    => 'nullable',
                    'is_block'         => 'required',
                );

                $messages=[
                    'main_cat_name.required'=>'The main category name field is required.',
                    'main_cat_image.required'=>'The main category Image field is required.',
                    'main_cat_icon.required'=>'The main category Icon field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            	   	return View::make('settings.category_management.add_category')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                    
                	$main = new CategoryManagementSettings();

                    if($main) {
        	            $main->main_cat_name    = $data['main_cat_name'];	            

                        $img_files = Input::file('main_cat_image');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/main_cat_image/'.$date;
                            $file_path = 'images/main_cat_image/'.$date;
                            $img_files->move($file_path, $file_name);
                              $this->resize_image($file_path.'/'.$file_name, true);

                            $main->main_cat_image = $date.'/'.$file_name;
                        } else {
                            $main->main_cat_image = NULL;
                        }

                        $main->main_cat_icon  = $data['main_cat_icon'];
                        $main->is_block       = $data['is_block'];
        	            $main->is_home        = 0;
                        
                        if($main->save()) {
                        	Session::flash('message', 'Add Successfully!'); 
            				Session::flash('alert-class', 'alert-success');
            				return redirect()->route('manage_category');
                        } else{
                        	Session::flash('message', 'Added Failed!'); 
            				Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_category');
                        }
                    } else{
                        Session::flash('message', 'Added Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_category');
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

	public function edit ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Product Main Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
                $main = CategoryManagementSettings::where('id',$id)->first();
        		if($main) {
        			return View::make("settings.category_management.edit_category")->with(array('main'=>$main, 'page'=>$page));
        		} else {
        			Session::flash('message', 'Edit Not Possible!'); 
        			Session::flash('alert-class', 'alert-danger');
        			return redirect()->route('manage_category'); 
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

	public function update (Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Product Main Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $id = Input::get('mc_id');
                $main = '';
                if($id != '') {
                    $main = CategoryManagementSettings::Where('id', $id)->first();
                }

                if($main) {
            		$rules = array(
                        'main_cat_name'    => 'required',
                        'main_cat_image'   => 'nullable',
                        'main_cat_icon'    => 'nullable',
                        'is_block'         => 'required',
                    );

                    $messages=[
                        'main_cat_name.required'=>'The main category name field is required.',
                        'main_cat_icon.required'=>'The main category icon field is required.',
                    ];
                    $validator = Validator::make(Input::all(), $rules,$messages);

                    if ($validator->fails()) {
                        return Redirect::to('/edit_category/' . $id)->withErrors($validator)->withErrors($validator)->with(array('main'=>$main, 'page'=>$page));
                    } else {
                        $data = Input::all();
                        
        	            $main->main_cat_name    = $data['main_cat_name'];	            

                        $img_files = Input::file('main_cat_image');
                        $old_main_cat_image = Input::get('old_main_cat_image');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/main_cat_image/'.$date;
                            $file_path = 'images/main_cat_image/'.$date;
                            $img_files->move($file_path, $file_name);
                                                                                      $this->resize_image($file_path.'/'.$file_name, true);

                            $main->main_cat_image = $date.'/'.$file_name;
                        } else if(isset($old_main_cat_image) && $old_main_cat_image != '') {
                            $main->main_cat_image = $old_main_cat_image;
                        } else {
                            $main->main_cat_image = NULL;
                        }

                        $main->main_cat_icon  = $data['main_cat_icon'];
        	            $main->is_block       = $data['is_block'];
                        
                        if($main->save()) {
                        	Session::flash('message', 'Update Successfully!'); 
            				Session::flash('alert-class', 'alert-success');
            				return redirect()->route('manage_category');
                        } else{
                        	Session::flash('message', 'Update Failed!'); 
            				Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_category');
                        }
                    }
                } else{
                    Session::flash('message', 'Update Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_category');
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

	public function StatusMainCategory ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Product Main Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$main = '';
        		$msg = '';
            	if($id != '') {
                	$main = CategoryManagementSettings::Where('id', $id)->first();
                }

                if($main) {
                	if($main->is_block == 1) {
                    	$main->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$main->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
                    
                    if($main->save()) {
                    	Session::flash('message', $msg); 
            			Session::flash('alert-class', 'alert-success');
            			return redirect()->route('manage_category');
                    } else{
                    	Session::flash('message', 'Failed Block or Unblock!'); 
            			Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_category');
                    }
                } else{
                    Session::flash('message', 'Failed Block or Unblock!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_category');
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

	public function MainCategoryBlock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Product Main Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$main = CategoryManagementSettings::where('id',$value)->first();
        					if($main){
        						$main->is_block = 0;
        						$main->save();
        						Session::flash('message', 'Blocked Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						$error = 0;
        					}	else {
        						Session::flash('message', 'Blocked Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        					}			
        				}
        			} else {
        				Session::flash('message', 'Blocked Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = 1;
        			}
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

	public function MainCategoryUnblock( Request $request) {   
        $ids = array();
        $error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Product Main Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
                    $ids = $request->ids;
                    if(sizeof($ids) != 0) {
                        foreach ($ids as $key => $value) {
                            $main = CategoryManagementSettings::where('id',$value)->first();
                            if($main){
                                $main->is_block = 1;
                                $main->save();
                                Session::flash('message', 'Unblocked Successfully!'); 
                                Session::flash('alert-class', 'alert-success');
                                $error = 0;
                            }   else {
                                Session::flash('message', 'Unblocked Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                            }           
                        }
                    } else {
                        Session::flash('message', 'Unblocked Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        $error = 1;
                    }
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

    public function HomeView( Request $request) {	
        $id = 0;
		$is_home = 0;

		if($request->ajax() && isset($request->is_home) && isset($request->id)){
            $is_home = $request->is_home;
			$id = $request->id;
			$error = 1;
			
            $affected = DB::table('category_management_settings')->where('is_home', '=', $is_home)->update(array('is_home' => 0));

            $main = CategoryManagementSettings::where('id',$id)->first();
			if($main){
				$main->is_home = $is_home;
				$main->save();
				Session::flash('message', 'Home Page View Set Successfully!'); 
				Session::flash('alert-class', 'alert-success');
				$error = 0;
			}	else {
				Session::flash('message', 'Home Page View Set Failed!'); 
				Session::flash('alert-class', 'alert-danger');
			}

			echo $error;
		}
	}
}
