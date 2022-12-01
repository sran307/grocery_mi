<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Countries;
use App\CountriesManagement;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class CountriesManagementController extends Controller
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
                ->where('B.module_name', '=', 'Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $country = CountriesManagement::all();

            	return View::make("settings.country_management.manage_country")->with(array('country'=>$country, 'page'=>$page));
                /*if (sizeof($country) != 0) {
                } else {
                    return response()->json(array('status_code'=>'0','response_msg'=>'No Country Settings Deatils'), 200);
                }*/
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
                ->where('B.module_name', '=', 'Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	return View::make('settings.country_management.add_country')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	$rules = array(
                    'country_name'    => 'required',
                    'country_code'    => 'nullable',
                    'currency_symbol' => 'nullable',
                    'currency_code'   => 'nullable',
                    'is_block'        => 'nullable',
                );
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
            	   	return View::make('settings.country_management.add_country')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                    
                	$country = new CountriesManagement();

                    if($country) {
        	            $country->country_id    = $data['country_name'];
        	            $country->country_name    = $data['h_country_name'];
        	            if($data['country_code'] != ''){
        	            	$country->country_code    = $data['country_code'];
        	            } else {
        	            	$country->country_code    = 'NONE';
        	            }

        	            if($data['currency_symbol'] != ''){
        	            	$country->currency_symbol = $data['currency_symbol'];
        	            } else {
        	            	$country->currency_symbol    = '₹';
        	            }

        	            if($data['currency_code'] != ''){
        	            	$country->currency_code = $data['currency_code'];
        	            } else {
        	            	$country->currency_code    = 'NONE';
        	            }
        	            $country->is_block        = 1;

        	            if($country->save()) {
        	            	Session::flash('message', 'Add Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('index_manage_country');
        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('index_manage_country');
        	            }
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('index_manage_country');
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

    public function CountryDetails( Request $request) {	
		if($request->ajax() && isset($request->c_id)){
			$c_id = $request->c_id;
			$countrys = \DB::table('countries')->where('ID',$c_id)->first();
			if($countrys){
				echo json_encode($countrys);
			}
		}
	}

	public function edit ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$country = CountriesManagement::where('id',$id)->first();
        		if($country) {
        			return View::make("settings.country_management.edit_country")->with(array('country'=>$country, 'page'=>$page));
        		} else {
        			Session::flash('message', 'Edit Not Possible!'); 
        			Session::flash('alert-class', 'alert-danger');
        			$country = CountriesManagement::all();
                	return View::make("settings.country_management.manage_country")->with(array('country'=>$country, 'page'=>$page)); 
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
                ->where('B.module_name', '=', 'Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$id = Input::get('cm_id');
                $country = '';
                if($id != '') {
                	$country = CountriesManagement::Where('id', $id)->first();
                }

                if($country) {
        			$rules = array(
        	            'country_name'    => 'required',
        	            'country_code'    => 'nullable',
        	            'currency_symbol' => 'nullable',
        	            'currency_code'   => 'nullable',
        	            'is_block'        => 'nullable',
        	        );
        	        $validator = Validator::make(Input::all(), $rules);

        	        if ($validator->fails()) {
        	    	   	return Redirect::to('/edit_country/' . $id)->withErrors($validator)->with(array('country'=>$country, 'page'=>$page));
        	        } else {
        	            $data = Input::all();

        	            $country->country_id    = $data['country_name'];
        	            $country->country_name    = $data['h_country_name'];
        	            if($data['country_code'] != ''){
        	            	$country->country_code    = $data['country_code'];
        	            } else {
        	            	$country->country_code    = 'NONE';
        	            }

        	            if($data['currency_symbol'] != ''){
        	            	$country->currency_symbol = $data['currency_symbol'];
        	            } else {
        	            	$country->currency_symbol    = '₹';
        	            }

        	            if($data['currency_code'] != ''){
        	            	$country->currency_code = $data['currency_code'];
        	            } else {
        	            	$country->currency_code    = 'NONE';
        	            }
        	            $country->is_block        = 1;
        	            
        	            if($country->save()) {
        	            	Session::flash('message', 'Update Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('index_manage_country');
        	            } else{
        	            	Session::flash('message', 'Update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('index_manage_country');
        	            }	        
        	        }
                } else{
                	Session::flash('message', 'Update Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('index_manage_country');
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

	public function status_country ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$country = '';
        		$msg = '';
            	if($id != '') {
                	$country = CountriesManagement::Where('id', $id)->first();
                }

                if($country) {
                	if($country->is_block == 1) {
                    	$country->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$country->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($country->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('index_manage_country');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('index_manage_country');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('index_manage_country');
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

	public function CountryBlock( Request $request) {
		$ids = array();
		$error = 1;
        $loged = session()->get('user');
        
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {	
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$country = CountriesManagement::where('id',$value)->first();
        					if($country){
        						$country->is_block = 0;
        						$country->save();
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

	public function CountryUnblock( Request $request) {	
		$ids = array();
		$error = 1;
        $loged = session()->get('user');
        
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$country = CountriesManagement::where('id',$value)->first();
        					if($country){
        						$country->is_block = 1;
        						$country->save();
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

	public function All () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $country = Countries::all();

            	return View::make("settings.country.manage_all_country")->with(array('country'=>$country, 'page'=>$page));
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

    public function NewCreate () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	return View::make('settings.country.new_country')->with(array('page'=>$page));
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

    public function NewStore(Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	$rules = array(
                    'name'            => 'required',
                    'code'            => 'required|max:3',
                    'dial_code'       => 'required|integer',
                    'currency_name'   => 'required|max:20',
                    'currency_symbol' => 'required|max:20',
                    'currency_code'   => 'required|max:20',
                );
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
            	   	return View::make('settings.country.new_country')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                    
                	$country = new Countries();

                    if($country) {
        	            $country->name            = $data['name'];
        	            $country->code            = $data['code'];
        	            $country->dial_code       = $data['dial_code'];
                    	$country->currency_name   = $data['currency_name'];
                    	$country->currency_symbol = $data['currency_symbol'];
                    	$country->currency_code   = $data['currency_code'];

        	            if($country->save()) {
        	            	Session::flash('message', 'Add Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('index_manage_all_country');
        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('index_manage_all_country');
        	            }
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('index_manage_all_country');
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

	public function NewEdit ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$country = Countries::where('ID',$id)->first();
        		if($country) {
        			return View::make("settings.country.edit_all_country")->with(array('country'=>$country, 'page'=>$page));
        		} else {
        			Session::flash('message', 'Edit Not Possible!'); 
        			Session::flash('alert-class', 'alert-danger');
        			$country = Countries::all();
                	return View::make("settings.country.manage_all_country")->with(array('country'=>$country, 'page'=>$page));
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

	public function NewUpdate (Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$id = Input::get('c_id');
        		$rules = array(
                    'name'            => 'required',
                    'code'            => 'required|max:3',
                    'dial_code'       => 'required|integer',
                    'currency_name'   => 'required|max:20',
                    'currency_symbol' => 'required|max:20',
                    'currency_code'   => 'required|max:20',
                );
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                	$country = Countries::where('ID',$id)->first();
        			if($country) {
            	   		return Redirect::to('/edit_all_country/' . $id)->withErrors($validator)->with(array('country'=>$country, 'page'=>$page));
        			} else {
            	   		return Redirect::to('/edit_all_country/' . $id)->withErrors($validator)->with(array('country'=>$country, 'page'=>$page));
        			}
                } else {
                    $data = Input::all();
                    $id = Input::get('c_id');
                    $country = '';
                    if($id != '') {
                    	$country = Countries::Where('id', $id)->first();
                    }

                    if($country) {
        	            $country->name            = $data['name'];
        	            $country->code            = $data['code'];
        	            $country->dial_code       = $data['dial_code'];
                    	$country->currency_name   = $data['currency_name'];
                    	$country->currency_symbol = $data['currency_symbol'];
                    	$country->currency_code   = $data['currency_code'];
        	            
        	            if($country->save()) {
        	            	Session::flash('message', 'Update Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('index_manage_all_country');
        	            } else{
        	            	Session::flash('message', 'Update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('index_manage_all_country');
        	            }
                    } else{
                    	Session::flash('message', 'Update Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('index_manage_all_country');
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

	public function NewDelete( Request $request) {	
		$id = 0;
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$banner = Countries::where('ID',$id)->first();
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

	public function NewDeleteAll( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Country')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$banner = Countries::where('ID',$value)->first();
        					if($banner){
        						if($banner->delete()) {
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
}
