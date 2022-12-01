<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryManagementSettings;
use App\SubCategoryManagementSettings;
use App\SubSubCategoryManagementSettings;
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

class SubCategoryManagementSettingsController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Product Sub Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $sub_cats = SubCategoryManagementSettings::where('main_cat_name',$id)->get();
                if($sub_cats) {
                	foreach ($sub_cats as $key => $value) {
            			if($value->main_cat_name) {
                			$cats = CategoryManagementSettings::where('id',$value->main_cat_name)->first();
            				$sub_cats[$key]['main_cat_name'] = $cats->main_cat_name;

            				$sub_sub = SubSubCategoryManagementSettings::where('sub_cat_name',$value->sub_cat_id)->get();
        	                $cnt_sub_sub = count($sub_sub);
        	                $sub_cats[$key]['sub_sub'] = $cnt_sub_sub;
            			}
                	}
                }
                $cats = CategoryManagementSettings::where('id',$id)->first();
            	return View::make("settings.sub_category_management.manage_sub_category")->with(array('sub_cats'=>$sub_cats, 'cats'=>$cats, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Product Sub Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	$cats = CategoryManagementSettings::where('id',$id)->first();
            	return View::make('settings.sub_category_management.add_sub_category')->with(array('cats'=>$cats, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Product Sub Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$data = Input::all();
            	$id = $data['main_cat_name'];
            	$cats="";
            	if($cats) {
            		$cats = CategoryManagementSettings::where('id',$id)->first();
            	}
            	$page = "Settings";
            	$rules = array(
                    'main_cat_name'    => 'required',
                    'sub_cat_name'     => 'required',
                    'sub_cat_image'    => 'required',
                    'is_block'         => 'required',
                );

                $messages=[
                    'main_cat_name.required'=>'The main category name field is required.',
                    'sub_cat_name.required' =>'The sub category name field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            	   	return Redirect::to('/add_sub_category/' . $id)->withErrors($validator)->with(array('cats'=>$cats, 'page'=>$page));
                } else {
                    $data = Input::all();
                    
                	$sub_cats = new SubCategoryManagementSettings();

                    if($sub_cats) {
        	            $sub_cats->main_cat_name    = $data['main_cat_name'];	            
        	            $sub_cats->sub_cat_name     = $data['sub_cat_name'];	            

                        $img_files = Input::file('sub_cat_image');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/sub_cat_image/'.$date;
                            $file_path = 'images/sub_cat_image/'.$date;
                            $img_files->move($file_path, $file_name);
                            $sub_cats->sub_cat_image = $date.'/'.$file_name;
                        } else {
                            $sub_cats->sub_cat_image = NULL;
                        }

        	            $sub_cats->is_block       = $data['is_block'];
        	            
        	            if($sub_cats->save()) {
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
                ->where('B.module_name', '=', 'Product Sub Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$sub_cats = SubCategoryManagementSettings::where('sub_cat_id',$id)->first();
        		if($sub_cats) {
        			if($sub_cats->main_cat_name) {
            			$cats = CategoryManagementSettings::where('id',$sub_cats->main_cat_name)->first();
        				$sub_cats->{'cat_name'} = $cats->main_cat_name;
        			}
                }
        		if($sub_cats) {
        			return View::make("settings.sub_category_management.edit_sub_category")->with(array('sub_cats'=>$sub_cats, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Product Sub Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$id = Input::get('msc_id');
                $sub_cats = '';
                if($id != '') {
                	$sub_cats = SubCategoryManagementSettings::Where('sub_cat_id', $id)->first();
                }

                if($sub_cats) {
        			$rules = array(
        	            'main_cat_name'    => 'required',
        	            'sub_cat_name'     => 'required',
        	            'sub_cat_image'    => 'nullable',
        	            'is_block'         => 'required',
        	        );

        	        $messages=[
        	            'main_cat_name.required'=>'The main category name field is required.',
        	            'sub_cat_name.required' =>'The sub category name field is required.',
        	        ];
        	        $validator = Validator::make(Input::all(), $rules,$messages);

        	        if ($validator->fails()) {
        	        	if($sub_cats) {
        					if($sub_cats->main_cat_name) {
        		    			$cats = CategoryManagementSettings::where('id',$sub_cats->main_cat_name)->first();
        						$sub_cats->{'cat_name'} = $cats->main_cat_name;
        					}
        		        }
        		        return Redirect::to('/edit_sub_category/' . $id)->withErrors($validator)->with(array('sub_cats'=>$sub_cats, 'page'=>$page));
        	        } else {
        	            $data = Input::all();
        	            
        	            $sub_cats->main_cat_name    = $data['main_cat_name'];	            
        	            $sub_cats->sub_cat_name     = $data['sub_cat_name'];	            

                        $img_files = Input::file('sub_cat_image');
                        $old_sub_cat_image = Input::get('old_sub_cat_image');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/sub_cat_image/'.$date;
                            $file_path = 'images/sub_cat_image/'.$date;
                            $img_files->move($file_path, $file_name);
                            $sub_cats->sub_cat_image = $date.'/'.$file_name;
                        } else if(isset($old_sub_cat_image) && $old_sub_cat_image != '') {
                            $sub_cats->sub_cat_image = $old_sub_cat_image;
                        } else {
                            $sub_cats->sub_cat_image = NULL;
                        }

        	            $sub_cats->is_block       = $data['is_block'];
        	            
        	            if($sub_cats->save()) {
        	            	Session::flash('message', 'Update Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_category');
        	            } else{
        	            	Session::flash('message', 'Update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_category');
        	            }
        	        }
                }  else{
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
                ->where('B.module_name', '=', 'Product Sub Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$sub_cats = '';
        		$msg = '';
            	if($id != '') {
                	$sub_cats = SubCategoryManagementSettings::Where('sub_cat_id', $id)->first();
                }

                if($sub_cats) {
                	if($sub_cats->is_block == 1) {
                    	$sub_cats->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$sub_cats->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($sub_cats->save()) {
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
                ->where('B.module_name', '=', 'Product Sub Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$sub_cats = SubCategoryManagementSettings::where('sub_cat_id',$value)->first();
        					if($sub_cats){
        						$sub_cats->is_block = 0;
        						$sub_cats->save();
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
                ->where('B.module_name', '=', 'Product Sub Category')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$sub_cats = SubCategoryManagementSettings::where('sub_cat_id',$value)->first();
        					if($sub_cats){
        						$sub_cats->is_block = 1;
        						$sub_cats->save();
        						Session::flash('message', 'Unblocked Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						$error = 0;
        					}	else {
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
}
