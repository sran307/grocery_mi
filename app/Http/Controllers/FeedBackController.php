<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\FeedBack;


use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Crypt;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;
use Carbon\Carbon;

class FeedBackController extends Controller
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
                ->where('B.module_name', '=', 'Feed Back')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Users";
            	$log = session()->get('user');
            	if($log) {
        	    	if($log->user_type == 1) {
        				$feeds = FeedBack::all();
            			return View::make("user.fback.feedbacks")->with(array('feeds'=>$feeds, 'page'=>$page));
        			} else {
        				Session::flash('message', 'You Are Not Permission to Access!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('admin');
        			}
        		} else {
        			Session::flash('message', 'You Are Not Login!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('admin');
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

    public function view ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Feed Back')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
        		$page = "Users";
            	$log = session()->get('user');
            	if($log) {
        	    	if($log->user_type == 1) {
        	    		$feeds = FeedBack::Where('id', $id)->first();
        				return View::make('user.fback.view_feedbacks')->with(array('feeds'=>$feeds, 'page'=>$page));
        			} else {
        				Session::flash('message', 'You Are Not Permission to View!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('admin');
        			}
        		} else {
        			Session::flash('message', 'You Are Not Login!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('admin');
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
		$error = 1;	
		$log = session()->get('user');
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Feed Back')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($log) {
        	    	if($log->user_type == 1) {
        				$id = 0;
        				if($request->ajax() && isset($request->id)) {
        					$id = $request->id;
        					if($id != 0) {
        						$feeds = FeedBack::where('id',$id)->first();
        						if($feeds){
        							if($feeds->delete()) {
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
        				Session::flash('message', 'You Are Not Permission Access!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            $error = 2;	
        			}
        		} else {
        			Session::flash('message', 'You Are Not Login!'); 
        			Session::flash('alert-class', 'alert-danger');
                    $error = 3;	
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
		$log = session()->get('user');
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Feed Back')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($log) {
        	    	if($log->user_type == 1) {
        				if($request->ajax() && isset($request->ids)){
        					$ids = $request->ids;
        					$error = 1;
        					if(sizeof($ids) != 0) {
        						foreach ($ids as $key => $value) {
        							$feeds = FeedBack::where('id',$value)->first();
        							if($feeds) {
        								if($feeds->delete()) {
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
        				Session::flash('message', 'You Are Not Permission to Access!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            $error = 2;	
        			}
        		} else {
        			Session::flash('message', 'You Are Not Login!'); 
        			Session::flash('alert-class', 'alert-danger');
                    $error = 3;	
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

	public function StatusFeedbacks ($id) {
		$feeds = '';
		$msg = '';
    	if($id != '') {
        	$feeds = FeedBack::Where('id', $id)->first();
        }

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Feed Back')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($feeds) {
                	if($feeds->is_block == 1) {
                    	$feeds->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$feeds->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($feeds->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('feedbacks');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('feedbacks');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('feedbacks');
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

	public function FeedbacksBlock( Request $request) {	
		$ids = array();
		$error = 1;

		$loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Feed Back')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$feeds = FeedBack::where('id',$value)->first();
        					if($feeds){
        						$feeds->is_block = 0;
        						$feeds->save();
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

	public function FeedbacksUnblock( Request $request) {	
		$ids = array();
		$error = 1;

		$loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Feed Back')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$feeds = FeedBack::where('id',$value)->first();
        					if($feeds){
        						$feeds->is_block = 1;
        						$feeds->save();
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
