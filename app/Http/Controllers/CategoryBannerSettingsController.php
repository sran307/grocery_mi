<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryBannerSettings;
use App\CategoryManagementSettings;
use App\UploadsImages;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class CategoryBannerSettingsController extends Controller
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
                ->where('B.module_name', '=', 'Category Banner')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $cat_bans = CategoryBannerSettings::all();
                if($cat_bans) {
        	        foreach ($cat_bans as $key => $value) {
        	        	$up = UploadsImages::where('img_id', $value->id)->where('notes', 'category banner')->where('is_block', 1)->get();
        	        	$cat_bans[$key]['images'] = $up;
        	        	$cats = CategoryManagementSettings::where('id', $value->main_cat_name)->where('is_block', 1)->first();
        	        	if($cats) {
        	        		$cat_bans[$key]['main_cat_name'] = $cats->main_cat_name;
        	        	}
        	        }
                }
            	return View::make("settings.category_banner.manage_category_banner")->with(array('cat_bans'=>$cat_bans));
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
                ->where('B.module_name', '=', 'Category Banner')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $mains = CategoryManagementSettings::where('is_block', 1)->get();
            	return View::make('settings.category_banner.add_category_banner')->with(array('mains'=>$mains));
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
                ->where('B.module_name', '=', 'Category Banner')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$rules = array(
                    'main_cat_name'  => 'required',
                    'c_banner_image' => 'required',
                    'is_block'       => 'nullable',
                );

                $messages=[
                    'main_cat_name.required'   =>'The main category name field is required.',
                    'c_banner_image.required'  =>'The category banner image field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                	$mains = CategoryManagementSettings::where('is_block', 1)->get();
            	   	return View::make('settings.category_banner.add_category_banner')->withErrors($validator)->with(array('mains'=>$mains));
                } else {
                    $data = Input::all();
                    
                	$cat_bans = new CategoryBannerSettings();

                    if($cat_bans) {
        	            $cat_bans->main_cat_name    = $data['main_cat_name'];	            
        	            $cat_bans->is_block       = 1;

                        $img_files = Input::file('c_banner_image');
        	            if(Count($img_files) <= 3) {
        	            	if($cat_bans->save()) {
        	            		$er = 1;
        	            		if(isset($img_files) && count($img_files) != 0) {
        		                	foreach ($img_files as $key => $value) {
        			                    $file_name = $value->getClientOriginalName();
        			                    $date = date('M-Y');
        			                    $file_path = '../public/images/category_banner/'.$date;
        			                    $file_path = 'images/category_banner/'.$date;
        			                    if($value->move($file_path, $file_name)) {
        			                    	$imgs = new UploadsImages();
        			                    	$imgs->img_id   = $cat_bans->id;
        			                    	$imgs->img_name = 'category banner';
        			                    	$imgs->images   = $date.'/'.$file_name;
        			                    	$imgs->notes    = 'category banner';
        			                    	$imgs->is_block = 1;
        			                    	if($imgs->save()) {
        			                    		$er = 0;
        			                    	} else {
        			                    		$ca = CategoryBannerSettings::where('id', $cat_bans->id)->first();
        			                    		$up = UploadsImages::where('img_id', $cat_bans->id)->where('notes', 'category banner')->first();
        			                    		if($ca) {
        			                    			$ca->delete();
        			                    		}
        			                    		if($up) {
        			                    			$up->delete();
        			                    		}
        			                    		$er = 1;
        			                    	}
        			                    }
        			                }
        		                }

        		                if($er == 0) {
        			            	Session::flash('message', 'Add Successfully!'); 
        							Session::flash('alert-class', 'alert-success');
        							return redirect()->route('manage_category_banner');
        		                } else {
        		                	Session::flash('message', 'Added Failed!'); 
        							Session::flash('alert-class', 'alert-danger');
        			                return redirect()->route('manage_category_banner');
        		                }

        		            } else{
        		            	Session::flash('message', 'Added Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('manage_category_banner');
        		            }
        	            } else {
        	            	Session::flash('message', 'Maximum Upload 3 Images!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('add_category_banner');
        	            }    
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_category_banner');
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
                ->where('B.module_name', '=', 'Category Banner')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$cat_bans = CategoryBannerSettings::where('id',$id)->first();
        		$mains = CategoryManagementSettings::where('is_block', 1)->get();
        		if($cat_bans) {
        			$up = UploadsImages::where('img_id', $cat_bans->id)->where('notes', 'category banner')->where('is_block', 1)->get();
        			$cat_bans['images'] = $up;
        			return View::make("settings.category_banner.edit_category_banner")->with(array('cat_bans'=>$cat_bans, 'mains'=>$mains));
        		} else {
        			Session::flash('message', 'Edit Not Possible!'); 
        			Session::flash('alert-class', 'alert-danger');
        			return redirect()->route('manage_category_banner'); 
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
                ->where('B.module_name', '=', 'Category Banner')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$rules = array(
                    'main_cat_name'  => 'required',
                    'c_banner_image' => 'nullable',
                    'is_block'       => 'nullable',
                );
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                	$id = Input::get('cb_id');
                	$cat_bans = CategoryBannerSettings::where('id',$id)->first();
        			$mains = CategoryManagementSettings::where('is_block', 1)->get();
        			if($cat_bans) {
        				$up = UploadsImages::where('img_id', $cat_bans->id)->where('notes', 'category banner')->where('is_block', 1)->get();
        				$cat_bans['images'] = $up;
        			}
            	   	return View::make('settings.category_banner.edit_category_banner')->withErrors($validator)->with(array('cat_bans'=>$cat_bans, 'mains'=>$mains));
                } else {
                    $data = Input::all();
                    $id = Input::get('cb_id');
                    $cat_bans = '';
                    if($id != '') {
                    	$cat_bans = CategoryBannerSettings::Where('id', $id)->first();
                    }

                    if($cat_bans) {
        	            $cat_bans->main_cat_name    = $data['main_cat_name'];	            
        	            $cat_bans->is_block       = 1;

                        $img_files = Input::file('c_banner_image');
                        $old_c_banner_image = Input::get('old_c_banner_image');
                        if(isset($img_files)) {
                        	if(Count($img_files) <= 3) {
        		            	if($cat_bans->save()) {
        		            		$er = 1;
        		            		if(isset($img_files) && count($img_files) != 0) {
        		            			$up = UploadsImages::where('img_id', $cat_bans->id)->where('notes', 'category banner')->delete();
        			                	foreach ($img_files as $key => $value) {
        				                    $file_name = $value->getClientOriginalName();
        				                    $date = date('M-Y');
        				                    // $file_path = '../public/images/category_banner/'.$date;
        				                    $file_path = 'images/category_banner/'.$date;
        				                    if($value->move($file_path, $file_name)) {
        				                    	$imgs = new UploadsImages();
        				                    	echo $imgs->img_id   = $cat_bans->id;
        				                    	$imgs->img_name = 'category banner';
        				                    	$imgs->images   = $date.'/'.$file_name;
        				                    	$imgs->notes    = 'category banner';
        				                    	$imgs->is_block = 1;
        				                    	if($imgs->save()) {
        				                    		$er = 0;
        				                    	} else {
        				                    		$ca = CategoryBannerSettings::where('id', $cat_bans->id)->first();
        				                    		$up = UploadsImages::where('img_id', $cat_bans->id)->where('notes', 'category banner')->first();
        				                    		if($ca) {
        				                    			$ca->delete();
        				                    		}
        				                    		if($up) {
        				                    			$up->delete();
        				                    		}
        				                    		$er = 1;
        				                    	}
        				                    }
        				                }
        			                }

        			                if($er == 0) {
        				            	Session::flash('message', 'update Successfully!'); 
        								Session::flash('alert-class', 'alert-success');
        								return redirect()->route('manage_category_banner');
        			                } else {
        			                	Session::flash('message', 'update Failed!'); 
        								Session::flash('alert-class', 'alert-danger');
        				                return redirect()->route('manage_category_banner');
        			                }

        			            } else{
        			            	Session::flash('message', 'update Failed!'); 
        							Session::flash('alert-class', 'alert-danger');
        			                return redirect()->route('manage_category_banner');
        			            }
        		            } else {
        		            	Session::flash('message', 'Maximum Upload 3 Images!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('manage_category_banner');
        		            }
                        } else if(($old_c_banner_image) && ($old_c_banner_image == 1)) {
                        	if($cat_bans->save()) {
                        		Session::flash('message', 'Update Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						return redirect()->route('manage_category_banner');
                    		} else {
                				Session::flash('message', 'Update Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('manage_category_banner');
                    		}
                        }   
                    } else{
                    	Session::flash('message', 'update Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_category_banner');
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

	public function delete( Request $request) {	
		$id = 0;
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Category Banner')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$cat_bans = CategoryBannerSettings::where('id',$id)->first();
        				if($cat_bans){
        					$up = UploadsImages::where('img_id', $cat_bans->id)->where('notes', 'category banner')->delete();

        					if($cat_bans->delete()) {
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
                // return redirect()->back();
                // $error = 1;
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            // $error = 1;
            $error = 1;
        }


		echo $error;
	}

	public function DeleteAll( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Category Banner')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$cat_bans = CategoryBannerSettings::where('id',$value)->first();
        					if($cat_bans){
        						$up = UploadsImages::where('img_id', $cat_bans->id)->where('notes', 'category banner')->delete();
        						if($cat_bans->delete()) {
        							Session::flash('message', 'Deleted Successfully!'); 
        							Session::flash('alert-class', 'alert-success');
        							$error = 0;
        						} else {
        							Session::flash('message', 'Deleted Failed!'); 
        							Session::flash('alert-class', 'alert-danger');

        						}
        					}	else {
        						Session::flash('message', 'Deleted Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        					}			
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

	public function StatusCategoryBanner ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Category Banner')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$cat_bans = '';
        		$msg = '';
            	if($id != '') {
                	$cat_bans = CategoryBannerSettings::Where('id', $id)->first();
                }

                if($cat_bans) {
                	if($cat_bans->is_block == 1) {
                    	$cat_bans->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$cat_bans->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($cat_bans->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_category_banner');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_category_banner');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_category_banner');
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

	public function CategoryBannerBlock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Category Banner')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$cat_bans = CategoryBannerSettings::where('id',$value)->first();
        					if($cat_bans){
        						$cat_bans->is_block = 0;
        						$cat_bans->save();
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

	public function CategoryBannerUnblock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Category Banner')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$cat_bans = CategoryBannerSettings::where('id',$value)->first();
        					if($cat_bans){
        						$cat_bans->is_block = 1;
        						$cat_bans->save();
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
