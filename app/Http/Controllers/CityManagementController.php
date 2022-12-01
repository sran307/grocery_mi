<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CityManagement;
use App\CountriesManagement;
use App\StateManagements;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class CityManagementController extends Controller
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
                ->where('B.module_name', '=', 'Districts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $city = CityManagement::all();
                if($city) {
                	foreach ($city as $key => $cty) {
                		$country = CountriesManagement::where('id',$cty->country_name)->first();
                		$state = StateManagements::where('id',$cty->state)->first();
                		if($country) {
                			$city[$key]['country_name'] = $country->country_name;
                		} else {
                			$city[$key]['country_name'] = "-------";
                		}

                		if($state) {
                			$city[$key]['state'] = $state->state;
                		} else {
                			$city[$key]['state'] = "-------";
                		}
                	}
                }
            	return View::make("settings.city.manage_city")->with(array('city'=>$city, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Districts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	return View::make('settings.city.add_city')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'Districts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	$rules = array(
                    'country_name'    => 'required',
                    'state'           => 'required',
                    'city_name'       => 'required',
                    'default'         => 'nullable',
                    'is_block'        => 'nullable',
                );

                $messages=[
                    'country_name.required'=>'The Country Name field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            	   	return View::make('settings.city.add_city')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                    
                	$city = new CityManagement();

                    if($city) {
        	            $city->country_name    = $data['country_name'];	            
        	            $city->state           = $data['state'];	            
        	            $city->city_name       = $data['city_name'];	            
        	            $city->default         = 0;
        	            $city->is_block        = 1;

                        if($city->save()) {
        	                Session::flash('message', 'Add Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_city');

        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_city');
        	            }  
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_city');
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
                ->where('B.module_name', '=', 'Districts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$city = CityManagement::where('id',$id)->first();
        		return View::make("settings.city.edit_city")->with(array('city'=>$city, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Districts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$id = Input::get('city_id');
        		$rules = array(
                    'country_name'    => 'required',
                    'state'           => 'required',
                    'city_name'       => 'required',
                    'default'         => 'nullable',
                    'is_block'        => 'nullable',
                );
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                	$city = CityManagement::where('id',$id)->first();
                	if($city) {
            	   		return Redirect::to('/edit_city/' . $id)->withErrors($validator)->with(array('city'=>$city, 'page'=>$page));
                	} else {
            	   		return Redirect::to('/edit_city/' . $id)->withErrors($validator)->with(array('city'=>$city, 'page'=>$page));
                	}
                } else {
                    $data = Input::all();
                    $id = Input::get('city_id');
                    $city = '';
                    if($id != '') {
                    	$city = CityManagement::Where('id', $id)->first();
                    }

                    if($city) {
        	            $city->country_name    = $data['country_name'];	            
        	            $city->state           = $data['state'];	            
        	            $city->city_name       = $data['city_name'];	            
        	            $city->default         = 0;
        	            $city->is_block        = 1;

                        if($city->save()) {
        	            	Session::flash('message', 'update Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_city');

        	            } else{
        	            	Session::flash('message', 'update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_city');
        	            }   
                    } else{
                    	Session::flash('message', 'update Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_city');
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
                ->where('B.module_name', '=', 'Districts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$city = CityManagement::where('id',$id)->first();
        				if($city){
        					if($city->delete()) {
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
                ->where('B.module_name', '=', 'Districts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$city = CityManagement::where('id',$value)->first();
        					if($city){
        						if($city->delete()) {
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

	public function StatusCity ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Districts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$city = '';
        		$msg = '';
            	if($id != '') {
                	$city = CityManagement::Where('id', $id)->first();
                }

                if($city) {
                	if($city->is_block == 1) {
                    	$city->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$city->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($city->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_city');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_city');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_city');
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

	public function CityDefault (Request $request) {
		$error = 1;

		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			$city = CityManagement::where('id',$id)->where('is_block',1)->first();
			$citys = CityManagement::all();
			if(($citys) && (count($citys) != 0)) {
				foreach ($citys as $key => $value) {
					$st = CityManagement::where('id',$value->id)->first();
					$st->default = 0;
					$st->save();					
				}
				
				if($city){
					$city->default = 1;
					$city->save();
					Session::flash('message', 'Update Default city Successfully!'); 
					Session::flash('alert-class', 'alert-success');
					$error = 0;
				}	else {
					Session::flash('message', 'Update Default city Failed!'); 
					Session::flash('alert-class', 'alert-danger');
				}
			}
		}
		echo $error;	
	}

	public function CityBlock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Districts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$city = CityManagement::where('id',$value)->first();
        					if($city){
        						$city->is_block = 0;
        						$city->save();
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

	public function CityUnblock	( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Districts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$city = CityManagement::where('id',$value)->first();
        					if($city){
        						$city->is_block = 1;
        						$city->save();
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

	public function StateDetails (Request $request) {
    	$country = 0;
		$state_val = 0;
		if($request->ajax() && isset($request->country)){
			$country = $request->country;

			if(isset($request->state)) {
				$state_val = $request->state;
			}

			$data = "";
			if($country != 0) {
				$state = StateManagements::where('country',$country)->get();
				if(($state) && (sizeof($state) != 0)){
					if($state_val != 0) {
	                    foreach ($state as $key => $value) {
	                    	if($state_val == $value->id) {
	                        	$data.='<option selected value="'.$value->id.'">'.$value->state.'</option>';
	                    	} else {
	                        	$data.='<option value="'.$value->id.'">'.$value->state.'</option>';
	                    	}
	                    }
					} else {
						$data = '<option value="0" selected disabled>Select State</option>';
	                    foreach ($state as $key => $value) {
	                        $data.='<option value="'.$value->id.'">'.$value->state.'</option>';
	                    }
					}
                } 			
			}
			echo $data;
		}
    }
}
