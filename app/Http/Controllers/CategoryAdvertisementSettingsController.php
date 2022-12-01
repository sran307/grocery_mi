<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryAdvertisementSettings;
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

class CategoryAdvertisementSettingsController extends Controller
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
                ->where('B.module_name', '=', 'Category Advertisement')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $cat_ads = CategoryAdvertisementSettings::all();
                if($cat_ads) {
        	        foreach ($cat_ads as $key => $value) {
        	        	$up = UploadsImages::where('img_id', $value->id)->where('notes', 'category advertisement')->where('is_block', 1)->get();
        	        	$cat_ads[$key]['images'] = $up;
        	        	$cats = CategoryManagementSettings::where('id', $value->main_cat_name)->where('is_block', 1)->first();
        	        	if($cats) {
        	        		$cat_ads[$key]['main_cat_name'] = $cats->main_cat_name;
        	        	}
        	        }
                }
            	return View::make("settings.category_advertisement.manage_advertisement")->with(array('cat_ads'=>$cat_ads,'page'=>$page));
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
                ->where('B.module_name', '=', 'Category Advertisement')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $mains = CategoryManagementSettings::where('is_block', 1)->get();
            	return View::make('settings.category_advertisement.add_advertisement')->with(array('mains'=>$mains, 'page'=>$page));
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
public function resize_image($path, $width, $height, $update = false) {
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
      return $save($resized, $update ? $path : null);
   }
}
    public function store(Request $request) {
        
       
        
        
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Category Advertisement')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$rules = array(
                    'ad_title'       => 'required',
                    'ad_website'     => 'required',
                    'ad_start_date'  => 'required',
                    'ad_end_date'    => 'required|after:ad_start_date',
                    'ad_image'       => 'required',
                    'cust_name'      => 'required',
                    'cust_no'        => 'required|numeric',
                    'amount'         => 'required|numeric',
                    'payment_status' => 'required',
                    'page'           => 'required',
                    'position'       => 'required',
                    'main_cat_name'  => 'nullable',
                    'is_block'       => 'nullable',
                );

                $messages=[
                    'main_cat_name.required'=>'The main category name field is required.',
                    'cust_name.required'=>'The Customer name field is required.',
                    'cust_no.required'=>'The Customer Number field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                	$mains = CategoryManagementSettings::where('is_block', 1)->get();
            	   	return View::make('settings.category_advertisement.add_advertisement')->withErrors($validator)->with(array('mains'=>$mains));
                } else {
                    $data = Input::all();
                                        $old_ads = CategoryAdvertisementSettings::Where('page', $data['page'])->Where('position', $data['position'])->count();

                    if($data['position']=='Below Recent Products' || $data['position']=='Below Featured Products' || $data['position']=='Top Middle')
                    {
                       $old_ads=0; 
                    }
                    if($old_ads==0) {
        	        	$cat_ads = new CategoryAdvertisementSettings();

        	            if($cat_ads) {
        		            $cat_ads->ad_title        = $data['ad_title'];	            
        		            $cat_ads->ad_website      = $data['ad_website'];	            
        		            $cat_ads->ad_start_date   = $data['ad_start_date'];	            
        		            $cat_ads->ad_end_date     = $data['ad_end_date'];	            
        		            $cat_ads->cust_name       = $data['cust_name'];	            
        		            $cat_ads->cust_no         = $data['cust_no'];	            
        		            $cat_ads->amount          = $data['amount'];	            
        		            $cat_ads->payment_status  = $data['payment_status'];	            
        		            $cat_ads->page            = $data['page'];	            
        		            $cat_ads->position        = $data['position'];	            
        		            $cat_ads->is_block        = 1;
        		            
        		            if (isset($data['main_cat_name'])) {
        		            	if($data['main_cat_name'] != 0) {
        		            		$cat_ads->main_cat_name   = $data['main_cat_name'];
        		            	} else {
        		            		$cat_ads->main_cat_name   = 0;
        		            	}
        		            } else {
        		            	$cat_ads->main_cat_name   = 0;
        		            }

        		            $img_files = Input::file('ad_image');
        	                if(isset($img_files)) {
        	                    $file_name = $img_files->getClientOriginalName();
        	                    $date = date('M-Y');
        	                    // $file_path = '../public/images/category_advertisement/'.$date;
        	                    $file_path = 'images/category_advertisement/'.$date;
        	                    $img_files->move($file_path, $file_name);
        	                    if($request->page=='Home Page' && $request->position=='Top Middle' || $data['position']=='Below Recent Products' || $data['position']=='Below Featured Products' )
        	                    $this->resize_image($file_path.'/'.$file_name, 355, 136, true);
        	                    $cat_ads->ad_image = $date.'/'.$file_name;
        	                } else {
        	                    $cat_ads->ad_image = NULL;
        	                }	

        	            	if($cat_ads->save()) {
        		            	Session::flash('message', 'Add Successfully!'); 
        						Session::flash('alert-class', 'alert-success');
        						return redirect()->route('manage_advertisement');
        	                } else {
        	                	Session::flash('message', 'Added Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('manage_advertisement');
        	                }    
        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_advertisement');
        	            }
                    } else {
                    	Session::flash('message', 'Already ADs Exists in the Same Page and Same Position!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_advertisement');
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
                ->where('B.module_name', '=', 'Category Advertisement')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$cat_ads = CategoryAdvertisementSettings::where('id',$id)->first();
        		$mains = CategoryManagementSettings::where('is_block', 1)->get();
        		if($cat_ads) {
        			$up = UploadsImages::where('img_id', $cat_ads->id)->where('notes', 'category advertisement')->where('is_block', 1)->get();
        			$cat_ads['images'] = $up;
        			return View::make("settings.category_advertisement.edit_advertisement")->with(array('cat_ads'=>$cat_ads, 'mains'=>$mains, 'page'=>$page));
        		} else {
        			Session::flash('message', 'Edit Not Possible!'); 
        			Session::flash('alert-class', 'alert-danger');
        			return redirect()->route('manage_advertisement'); 
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
                ->where('B.module_name', '=', 'Category Advertisement')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$id = Input::get('ca_id');
        		$data = Input::all();
                $cat_ads = '';

                if($id != '') {
                	$cat_ads = CategoryAdvertisementSettings::Where('id', $id)->first();
                }

                if($cat_ads) {
        			$page = "Settings";
        			$rules = array(
        	            'ad_title'       => 'required',
        	            'ad_website'     => 'required',
        	            'ad_start_date'  => 'required',
        	            'ad_end_date'    => 'required|after:ad_start_date',
        	            'ad_image'       => 'nullable',
        	            'cust_name'      => 'required',
        	            'cust_no'        => 'required|numeric',
        	            'amount'         => 'required|numeric',
        	            'payment_status' => 'required',
        	            'page'           => 'required',
        	            'position'       => 'required',
        	            'main_cat_name'  => 'nullable',
        	            'is_block'       => 'nullable',
        	        );
        	        $validator = Validator::make(Input::all(), $rules);

        	        if ($validator->fails()) {
        	        	$id = Input::get('ca_id');
        	        	$cat_ads = CategoryAdvertisementSettings::where('id',$id)->first();
        				/*$mains = CategoryManagementSettings::where('is_block', 1)->get();
        				if($cat_ads) {
        					$up = UploadsImages::where('img_id', $cat_ads->id)->where('notes', 'category advertisement')->where('is_block', 1)->get();
        					$cat_ads['images'] = $up;
        				}*/
        	    	   	// return View::make('settings.category_advertisement.edit_advertisement')->withErrors($validator)->with(array('cat_ads'=>$cat_ads, 'mains'=>$mains, 'page'=>$page));
        	    	   	return Redirect::to('/edit_advertisement/' . $id)->withErrors($validator)->with(array('cat_ads'=>$cat_ads, 'page'=>$page));
        	        } else {
        	            $cat_ads->ad_title        = $data['ad_title'];	            
        	            $cat_ads->ad_website      = $data['ad_website'];	            
        	            $cat_ads->ad_start_date   = $data['ad_start_date'];	            
        	            $cat_ads->ad_end_date     = $data['ad_end_date'];	            
        	            $cat_ads->cust_name       = $data['cust_name'];	            
        	            $cat_ads->cust_no         = $data['cust_no'];	            
        	            $cat_ads->amount          = $data['amount'];	            
        	            $cat_ads->payment_status  = $data['payment_status'];	            
        	            $cat_ads->page            = $data['page'];	            
        	            $cat_ads->position        = $data['position'];	            
        	            $cat_ads->is_block        = 1;
        	            
        	            if (isset($data['main_cat_name'])) {
        	            	if($data['main_cat_name'] != 0) {
        	            		$cat_ads->main_cat_name   = $data['main_cat_name'];
        	            	} else {
        	            		$cat_ads->main_cat_name   = 0;
        	            	}
        	            } else {
        	            	$cat_ads->main_cat_name   = 0;
        	            }

        	            $img_files = Input::file('ad_image');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/category_advertisement/'.$date;
                            $file_path = 'images/category_advertisement/'.$date;
                            $img_files->move($file_path, $file_name);
                           
                            if($request->position=='Top Middle' || $data['position']=='Below Recent Products' || $data['position']=='Below Featured Products' )
        	                    $this->resize_image($file_path.'/'.$file_name, 355, 136, true);
                            $cat_ads->ad_image = $date.'/'.$file_name;
                        } else if (isset($data['old_ad_image'])) {
                            $cat_ads->ad_image = $data['old_ad_image'];
                        } else {
                            $cat_ads->ad_image = NULL;
                        }	

                        if($cat_ads->save()) {
        	            	Session::flash('message', 'update Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_advertisement');
                        } else {
                        	Session::flash('message', 'update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_advertisement');
                        }  
        	        }
                } else{
                	Session::flash('message', 'update Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_advertisement');
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
                ->where('B.module_name', '=', 'Category Advertisement')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$cat_ads = CategoryAdvertisementSettings::where('id',$id)->first();
        				if($cat_ads){
        					$up = UploadsImages::where('img_id', $cat_ads->id)->where('notes', 'category advertisement')->delete();

        					if($cat_ads->delete()) {
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

	public function DeleteAll( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Category Advertisement')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$cat_ads = CategoryAdvertisementSettings::where('id',$value)->first();
        					if($cat_ads){
        						$up = UploadsImages::where('img_id', $cat_ads->id)->where('notes', 'category advertisement')->delete();
        						if($cat_ads->delete()) {
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

	public function StatusAdvertisement ($id) {
		$loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Category Advertisement')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                $cat_ads = '';
        		$msg = '';
            	if($id != '') {
                	$cat_ads = CategoryAdvertisementSettings::Where('id', $id)->first();
                }

                if($cat_ads) {
                	if($cat_ads->is_block == 1) {
                    	$cat_ads->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$cat_ads->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($cat_ads->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_advertisement');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_advertisement');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_advertisement');
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

	public function AdvertisementBlock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Category Advertisement')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$cat_ads = CategoryAdvertisementSettings::where('id',$value)->first();
        					if($cat_ads){
        						$cat_ads->is_block = 0;
        						$cat_ads->save();
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

	public function AdvertisementUnblock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Category Advertisement')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$cat_ads = CategoryAdvertisementSettings::where('id',$value)->first();
        					if($cat_ads){
        						$cat_ads->is_block = 1;
        						$cat_ads->save();
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