<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ColorSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class ColorSettingsController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index () {
        $colour = ColorSettings::all();
    	return View::make("settings.color.manage_color")->with(array('colour'=>$colour));
    }

    public function create () {
    	return View::make('settings.color.add_color');
    }

    public function store(Request $request) {
    	$error = 1;
    	if($request->ajax() && isset($request->cn) && isset($request->cc)) {
			$color_code =  $request->cc;           
			$color_name =  $request->cn;           
        	$colour = new ColorSettings();

            if($colour) {
	            $colour->color_code    = $color_code;	            
	            $colour->color_name    = $color_name;	            
	            $colour->is_block      = 1;

            	if($colour->save()) {
            		$error = 0;
            		Session::flash('message', 'Add Successfully!'); 
					Session::flash('alert-class', 'alert-success');
					// return redirect()->route('manage_color');
	            } else{
	            	$error = 1;
	            	Session::flash('message', 'Added Failed!'); 
					Session::flash('alert-class', 'alert-danger');
	                // return redirect()->route('manage_color');
	            }
            } else {
            	$error = 1;
            	Session::flash('message', 'Added Failed!'); 
				Session::flash('alert-class', 'alert-danger');
                // return redirect()->route('manage_color');
            }
    	} else {
    		$error = 1;
    		Session::flash('message', 'Added Failed!'); 
			Session::flash('alert-class', 'alert-danger');
            // return redirect()->route('manage_color');
    	}
		echo $error;
    }

	public function edit ($id) {
		$colour = ColorSettings::where('id',$id)->first();
		return View::make("settings.color.edit_color")->with(array('colour'=>$colour));
	}

	public function update(Request $request) {
    	$error = 1;
    	if($request->ajax() && isset($request->cn) && isset($request->cc) && isset($request->id)) {
			$color_code =  $request->cc;           
			$color_name =  $request->cn;           
        	
        	$id = $request->id;
            $colour = '';
            if($id != 0) {
            	$colour = ColorSettings::Where('id', $id)->first();
            }

            if($colour) {
	            $colour->color_code    = $color_code;	            
	            $colour->color_name    = $color_name;	            
	            $colour->is_block      = 1;

            	if($colour->save()) {
            		$error = 0;
            		Session::flash('message', 'update Successfully!'); 
					Session::flash('alert-class', 'alert-success');
					// return redirect()->route('manage_color');
	            } else{
	            	$error = 1;
	            	Session::flash('message', 'update Failed!'); 
					Session::flash('alert-class', 'alert-danger');
	                // return redirect()->route('manage_color');
	            }
            } else {
            	$error = 1;
            	Session::flash('message', 'update Failed!'); 
				Session::flash('alert-class', 'alert-danger');
                // return redirect()->route('manage_color');
            }
    	} else {
    		$error = 1;
    		Session::flash('message', 'update Failed!'); 
			Session::flash('alert-class', 'alert-danger');
            // return redirect()->route('manage_color');
    	}
		echo $error;
    }

	public function delete( Request $request) {	
		$id = 0;
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			$error = 1;
			if($id != 0) {
				$colour = ColorSettings::where('id',$id)->first();
				if($colour){
					if($colour->delete()) {
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

			echo $error;
		}
	}

	public function DeleteAll( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$colour = ColorSettings::where('id',$value)->first();
					if($colour){
						if($colour->delete()) {
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

			echo $error;
		}
	}

	public function StatusColor ($id) {
		$colour = '';
		$msg = '';
    	if($id != '') {
        	$colour = ColorSettings::Where('id', $id)->first();
        }

        if($colour) {
        	if($colour->is_block == 1) {
            	$colour->is_block        = 0;
            	$msg = "Blocked Successfully";
        	} else {
        		$colour->is_block        = 1;
            	$msg = "Unblocked Successfully";
        	}
	        
	        if($colour->save()) {
	        	Session::flash('message', $msg); 
				Session::flash('alert-class', 'alert-success');
				return redirect()->route('manage_color');
	        } else{
	        	Session::flash('message', 'Failed Block or Unblock!'); 
				Session::flash('alert-class', 'alert-danger');
	            return redirect()->route('manage_color');
	        }
        } else{
        	Session::flash('message', 'Failed Block or Unblock!'); 
			Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_color');
        }
	}

	public function ColorBlock( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$colour = ColorSettings::where('id',$value)->first();
					if($colour){
						$colour->is_block = 0;
						$colour->save();
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

			echo $error;
		}
	}

	public function ColorUnblock( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$colour = ColorSettings::where('id',$value)->first();
					if($colour){
						$colour->is_block = 1;
						$colour->save();
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

			echo $error;
		}
	}
}
