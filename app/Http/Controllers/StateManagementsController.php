<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StateManagements;
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

class StateManagementsController extends Controller
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
                ->where('B.module_name', '=', 'State')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $state = StateManagements::all();
                if($state) {
                	foreach ($state as $key => $ste) {
                		$country = CountriesManagement::where('id',$ste->country)->first();
                		if($country) {
                			$state[$key]['country'] = $country->country_name;
                		} else {
                			$state[$key]['country'] = "-------";
                		}
                	}
                }
            	return View::make("settings.state.manage_state")->with(array('state'=>$state, 'page'=>$page));
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
                ->where('B.module_name', '=', 'State')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	return View::make('settings.state.add_state')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'State')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	$rules = array(
                    'country'          => 'required',
                    'state'            => 'required',
                    'default'          => 'nullable',
                    'is_block'         => 'nullable',
                );

                $messages=[
                    'country.required'=>'The Country field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            	   	return View::make('settings.state.add_state')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                    
                	$state = new StateManagements();

                    if($state) {
        	            $state->country    = $data['country'];	            
        	            $state->state      = $data['state'];	            
        	            $state->default    = 0;
        	            $state->is_block   = 1;

                        if($state->save()) {
        	                Session::flash('message', 'Add Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_state');

        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_state');
        	            }  
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_state');
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
                ->where('B.module_name', '=', 'State')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$state = StateManagements::where('id',$id)->first();
        		return View::make("settings.state.edit_state")->with(array('state'=>$state, 'page'=>$page));
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
                ->where('B.module_name', '=', 'State')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$id = Input::get('state_id');
        		$rules = array(
                    'country'          => 'required',
                    'state'            => 'required',
                    'default'          => 'nullable',
                    'is_block'         => 'nullable',
                );
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                	$state = StateManagements::where('id',$id)->first();
                	if($state) {
            	   		return Redirect::to('/edit_state/' . $id)->withErrors($validator)->with(array('state'=>$state, 'page'=>$page));
                	} else {
            	   		return Redirect::to('/edit_state/' . $id)->withErrors($validator)->with(array('page'=>$page));
                	}
                } else {
                    $data = Input::all();
                    $id = Input::get('state_id');
                    $state = '';
                    if($id != '') {
                    	$state = StateManagements::Where('id', $id)->first();
                    }

                    if($state) {
        	            $state->country          = $data['country'];	            
        	            $state->state            = $data['state'];	            
        	            $state->default          = 0;
        	            $state->is_block         = 1;

                        if($state->save()) {
        	            	Session::flash('message', 'update Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_state');

        	            } else{
        	            	Session::flash('message', 'update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_state');
        	            }   
                    } else{
                    	Session::flash('message', 'update Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_state');
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
                ->where('B.module_name', '=', 'State')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$state = StateManagements::where('id',$id)->first();
        				if($state){
        					if($state->delete()) {
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

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'State')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			$error = 1;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$state = StateManagements::where('id',$value)->first();
        					if($state){
        						if($state->delete()) {
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

	public function StatusState ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'State')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$state = '';
        		$msg = '';
            	if($id != '') {
                	$state = StateManagements::Where('id', $id)->first();
                }

                if($state) {
                	if($state->is_block == 1) {
                    	$state->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$state->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($state->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_state');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_state');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_state');
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

	public function StateDefault (Request $request) {
		$error = 1;
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			$state = StateManagements::where('id',$id)->where('is_block',1)->first();
			$states = StateManagements::all();
			if(($states) && (count($states) != 0)) {
				foreach ($states as $key => $value) {
					$st = StateManagements::where('id',$value->id)->first();
					$st->default = 0;
					$st->save();					
				}
				
				if($state){
					$state->default = 1;
					$state->save();
					Session::flash('message', 'Update Default state Successfully!'); 
					Session::flash('alert-class', 'alert-success');
					$error = 0;
				}	else {
					Session::flash('message', 'Update Default state Failed!'); 
					Session::flash('alert-class', 'alert-danger');
				}
			}
		}
		echo $error;	
	}

	public function StateBlock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'State')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$state = StateManagements::where('id',$value)->first();
        					if($state){
        						$state->is_block = 0;
        						$state->save();
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

	public function StateUnblock	( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'State')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$state = StateManagements::where('id',$value)->first();
        					if($state){
        						$state->is_block = 1;
        						$state->save();
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
}
