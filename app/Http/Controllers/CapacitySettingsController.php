<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CapacitySettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class CapacitySettingsController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index () {
        $capacity = CapacitySettings::all();
    	return View::make("settings.capacity.manage_capacity")->with(array('capacity'=>$capacity));
    }

    public function create () {
    	return View::make('settings.capacity.add_capacity');
    }

    public function store(Request $request) {
    	$rules = array(
            'capacity'  => 'required',
            'is_block'  => 'nullable',
        );

        $messages=[
            'capacity.required'=>'The capacity field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
    	   	return View::make('settings.capacity.add_capacity')->withErrors($validator);
        } else {
            $data = Input::all();
            
        	$capacity = new CapacitySettings();

            if($capacity) {
	            $capacity->capacity  = $data['capacity'];	            
	            $capacity->is_block  = 1;

                if($capacity->save()) {
	                Session::flash('message', 'Add Successfully!'); 
					Session::flash('alert-class', 'alert-success');
					return redirect()->route('manage_capacity');

	            } else{
	            	Session::flash('message', 'Added Failed!'); 
					Session::flash('alert-class', 'alert-danger');
	                return redirect()->route('manage_capacity');
	            }  
            } else{
            	Session::flash('message', 'Added Failed!'); 
				Session::flash('alert-class', 'alert-danger');
                return redirect()->route('manage_capacity');
            }
        }
    }

	public function edit ($id) {
		$capacity = CapacitySettings::where('id',$id)->first();
		return View::make("settings.capacity.edit_capacity")->with(array('capacity'=>$capacity));
	}

	public function update (Request $request) {
		$id = Input::get('capacity_id');
    	$capacity = '';
        if($id != '') {
        	$capacity = CapacitySettings::Where('id', $id)->first();
        }

        if($capacity) {
			$rules = array(
	            'capacity'    => 'required',
	            'is_block'    => 'nullable',
	        );
	        $validator = Validator::make(Input::all(), $rules);

	        if ($validator->fails()) {
	    	   	return View::make('settings.capacity.edit_capacity')->withErrors($validator)->with(array('capacity'=>$capacity));
	        } else {
	            $data = Input::all();

	            $capacity->capacity      = $data['capacity'];	            
	            $capacity->is_block  = 1;

                if($capacity->save()) {
	            	Session::flash('message', 'update Successfully!'); 
					Session::flash('alert-class', 'alert-success');
					return redirect()->route('manage_capacity');

	            } else{
	            	Session::flash('message', 'update Failed!'); 
					Session::flash('alert-class', 'alert-danger');
	                return redirect()->route('manage_capacity');
	            }   
	        }
        } else{
        	Session::flash('message', 'update Failed!'); 
			Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_capacity');
        }
	}

	public function delete( Request $request) {	
		$id = 0;
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			$error = 1;
			if($id != 0) {
				$capacity = CapacitySettings::where('id',$id)->first();
				if($capacity){
					if($capacity->delete()) {
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
					$capacity = CapacitySettings::where('id',$value)->first();
					if($capacity){
						if($capacity->delete()) {
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

	public function StatusCapacity ($id) {
		$capacity = '';
		$msg = '';
    	if($id != '') {
        	$capacity = CapacitySettings::Where('id', $id)->first();
        }

        if($capacity) {
        	if($capacity->is_block == 1) {
            	$capacity->is_block        = 0;
            	$msg = "Blocked Successfully";
        	} else {
        		$capacity->is_block        = 1;
            	$msg = "Unblocked Successfully";
        	}
	        
	        if($capacity->save()) {
	        	Session::flash('message', $msg); 
				Session::flash('alert-class', 'alert-success');
				return redirect()->route('manage_capacity');
	        } else{
	        	Session::flash('message', 'Failed Block or Unblock!'); 
				Session::flash('alert-class', 'alert-danger');
	            return redirect()->route('manage_capacity');
	        }
        } else{
        	Session::flash('message', 'Failed Block or Unblock!'); 
			Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_capacity');
        }
	}

	public function CapacityBlock( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$capacity = CapacitySettings::where('id',$value)->first();
					if($capacity){
						$capacity->is_block = 0;
						$capacity->save();
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

	public function CapacityUnblock( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$capacity = CapacitySettings::where('id',$value)->first();
					if($capacity){
						$capacity->is_block = 1;
						$capacity->save();
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
