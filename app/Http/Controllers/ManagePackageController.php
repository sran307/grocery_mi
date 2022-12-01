<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brands;
use App\CountriesManagement;
use App\StateManagements;
use App\CityManagement;
use App\PackageDimension;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class ManagePackageController extends Controller
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
                ->where('B.module_name', '=', 'Brands')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Products";
                $dimensions = PackageDimension::all();
            	return View::make("settings.package.manage_package")->with(array('dimensions'=>$dimensions, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Brands')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Products";
            	return View::make('settings.package.add_package')->with(array('page'=>$page));
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
public function resize_image($path, $width=140, $height=73, $update = false) {
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
                ->where('B.module_name', '=', 'Brands')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Products";
            	$rules = array(
                    'title'       => 'required|unique:package_dimensions,title',
                    'type'   => 'required',
                    'height'          => 'required|numeric',
                    'width'          => 'required|numeric',
                    'length'          => 'required|numeric',
                     'price'          => 'required|numeric',
                    
                );

                $messages=[
                    'title.required'=>'The Title field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            	   	return View::make('products.brands.add_brands')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                
                    
                	$brands = new PackageDimension();

                    if($brands) {
        	            $brands->title       = $data['title'];	            
        	            $brands->type       = $data['type'];	            
        	            $brands->length   = $data['length'];	            
        	            $brands->height          = $data['height'];	            
        	            $brands->width          = $data['width'];	            
        	            $brands->price          = $data['price'];	            
        	            /*$brands->state            = $data['state'];	            
        	            $brands->city             = $data['city'];	 */           
        	            $brands->is_block         = 1;

        	            

                        if($brands->save()) {
        	                Session::flash('message', 'Add Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_package_dimension');

        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_package_dimension');
        	            }  
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_package_dimension');
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

	public function edit (REquest $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Brands')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $id=$request->id;
        		$page = "Products";
        		$pack = PackageDimension::where('id',$id)->first();
        		return View::make("settings.package.edit_package")->with(array('pack'=>$pack, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Brands')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Products";
        		$id = Input::get('pack_id');
            	$brands = '';
                if($id != '') {
                	$brands = PackageDimension::Where('id', $id)->first();
                }

                if($brands) {
        		$rules = array(
                    'title'       => 'required|unique:package_dimensions,title,'.$id,
                    'type'   => 'required',
                    'height'          => 'required|numeric',
                    'width'          => 'required|numeric',
                    'length'          => 'required|numeric',
                     'price'          => 'required|numeric',
                    
                );

        	        $validator = Validator::make(Input::all(), $rules);

        	        if ($validator->fails()) {
        	    	   	return Redirect::to('/edit_package_dimension?id=' . $id)->withErrors($validator)->with(array('pack'=>$brands, 'page'=>$page));
        	        } else {
        	            $data = Input::all();

        	             $brands->title       = $data['title'];	            
        	            $brands->type       = $data['type'];	            
        	            $brands->length   = $data['length'];	            
        	            $brands->height          = $data['height'];	            
        	            $brands->width          = $data['width'];	            
        	            $brands->price          = $data['price'];	            
        	            $brands->is_block         = 1;

        	            

                        if($brands->save()) {
        	            	Session::flash('message', 'update Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_package_dimension');

        	            } else{
        	            	Session::flash('message', 'update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_package_dimension');
        	            }   
        	        }
                } else{
                	Session::flash('message', 'update Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_package_dimension');
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
                ->where('B.module_name', '=', 'Brands')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$brands = PackageDimension::where('id',$id)->first();
        				if($brands){
        					if($brands->delete()) {
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

	public function delete_pack_all( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Brands')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$brands = PackageDimension::where('id',$value)->first();
        					if($brands){
        						if($brands->delete()) {
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

	public function StatusPackage($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Brands')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$brands = '';
        		$msg = '';
            	if($id != '') {
                	$brands = PackageDimension::Where('id', $id)->first();
                }

                if($brands) {
                	if($brands->is_block == 1) {
                    	$brands->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$brands->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($brands->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_package_dimension');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_package_dimension');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_package_dimension');
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

	public function packsBlock( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Brands')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$brands = PackageDimension::where('id',$value)->first();
        					if($brands){
        						$brands->is_block = 0;
        						$brands->save();
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

	public function packssUnblock( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Brands')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$brands = PackageDimension::where('id',$value)->first();
        					if($brands){
        						$brands->is_block = 1;
        						$brands->save();
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

	public function CityDetails (Request $request) {
    	$state = 0;
		$city_val = 0;
		if($request->ajax() && isset($request->state)){
			$state = $request->state;

			if(isset($request->city)) {
				$city_val = $request->city;
			}

			$data = "";
			if($state != 0) {
				$city = CityManagement::where('state',$state)->get();
				if(($city) && (sizeof($city) != 0)){
					if($city_val != 0) {
	                    foreach ($city as $key => $value) {
	                    	if($city_val == $value->id) {
	                        	$data.='<option selected value="'.$value->id.'">'.$value->city_name.'</option>';
	                    	} else {
	                        	$data.='<option value="'.$value->id.'">'.$value->city_name.'</option>';
	                    	}
	                    }
					} else {
						$data = '<option value="0" selected disabled>Select City</option>';
	                    foreach ($city as $key => $value) {
	                        $data.='<option value="'.$value->id.'">'.$value->city_name.'</option>';
	                    }
					}
                } 			
			}
			echo $data;
		}
    }
}
