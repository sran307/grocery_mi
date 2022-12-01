<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules;
use App\Roles;
use App\User;
use App\Previlages;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class ModulesController extends Controller
{
    protected $response;
 
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
                ->where('B.module_name', '=', 'Modules')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
                $roles = Modules::all();
            	return View::make("settings.modules.manage_modules")->with(array('roles'=>$roles, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Modules')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	return View::make('settings.modules.add_modules')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'Modules')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Settings";
            	$rules = array(
                    'module_name'  => 'required|unique:modules,module_name',
                );

                $messages=[
                    'module_name.required'=>'The modules name field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            	   	return View::make('settings.modules.add_modules')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                    
                	$roles = new Modules();

                    if($roles) {
        	            $roles->module_name      = $data['module_name'];	            

                        if($roles->save()) {
                            $u_role = Roles::all();
                            if(sizeof($u_role) != 0){
                                foreach ($u_role as $key => $value) {
                                    $prev = new Previlages();
                                    $prev->role       = $value->id;
                                    $prev->module     = $roles->id;
                                    $prev->list       = 2;
                                    $prev->add        = 2;
                                    $prev->edit       = 2;
                                    $prev->view       = 2;
                                    $prev->delete     = 2;
                                    $prev->status     = 2;
                                    $prev->export     = 2;
                                    $prev->save();
                                }
                            }

        	                Session::flash('message', 'Add Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_modules');

        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_modules');
        	            }  
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_modules');
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
                ->where('B.module_name', '=', 'Modules')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$roles = Modules::where('id',$id)->first();
        		return View::make("settings.modules.edit_modules")->with(array('roles'=>$roles, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Modules')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Settings";
        		$id = Input::get('modules_id');
                $roles = '';
                if($id != '') {
                	$roles = Modules::findorfail($id);
                }

                if($roles) {
        			$rules = array(
        	            'module_name'        => 'required|unique:modules,module_name,'.$id.',id',
        	        );
        	        $validator = Validator::make(Input::all(), $rules);

        	        if ($validator->fails()) {
        	        	return Redirect::to('/edit_modules/' . $id)->withErrors($validator)->with(array('roles'=>$roles, 'page'=>$page));
        	        } else {
        	            $data = Input::all();

        	            $roles->module_name      = $data['module_name'];	            

        	            if($roles->save()) {
        	            	Session::flash('message', 'update Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_modules');

        	            } else{
        	            	Session::flash('message', 'update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_modules');
        	            }   
        	        }
                } else{
                	Session::flash('message', 'update Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_modules');
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
                ->where('B.module_name', '=', 'Modules')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$roles = Modules::where('id',$id)->first();
        				if($roles){
        					if($roles->delete()) {
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
                ->where('B.module_name', '=', 'Modules')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$roles = Modules::where('id',$value)->first();
        					if($roles){
        						if($roles->delete()) {
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