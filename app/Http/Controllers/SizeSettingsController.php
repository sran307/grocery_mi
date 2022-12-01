<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SizeSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class SizeSettingsController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index () {
        $size = SizeSettings::all();
    	return View::make("settings.size.manage_size")->with(array('size'=>$size));
    }

    public function create () {
    	return View::make('settings.size.add_size');
    }

    public function store(Request $request) {
    	$rules = array(
            'size'      => 'required',
            'is_block'  => 'nullable',
        );

        $messages=[
            'size.required'=>'The size field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
    	   	return View::make('settings.size.add_size')->withErrors($validator);
        } else {
            $data = Input::all();
            
        	$size = new SizeSettings();

            if($size) {
	            $size->size      = $data['size'];	            
	            $size->is_block  = 1;

                if($size->save()) {
	                Session::flash('message', 'Add Successfully!'); 
					Session::flash('alert-class', 'alert-success');
					return redirect()->route('manage_size');

	            } else{
	            	Session::flash('message', 'Added Failed!'); 
					Session::flash('alert-class', 'alert-danger');
	                return redirect()->route('manage_size');
	            }  
            } else{
            	Session::flash('message', 'Added Failed!'); 
				Session::flash('alert-class', 'alert-danger');
                return redirect()->route('manage_size');
            }
        }
    }

	public function edit ($id) {
		$size = SizeSettings::where('id',$id)->first();
		return View::make("settings.size.edit_size")->with(array('size'=>$size));
	}

	public function update (Request $request) {
		$id = Input::get('size_id');
        $size = '';
        if($id != '') {
        	$size = SizeSettings::Where('id', $id)->first();
        }

        if($size) {
			$rules = array(
	            'size'        => 'required',
	            'is_block'    => 'nullable',
	        );
	        $validator = Validator::make(Input::all(), $rules);

	        if ($validator->fails()) {
	    	   	return View::make('settings.size.edit_size')->withErrors($validator)->with(array('size'=>$size));
	        } else {
	            $data = Input::all();

	            $size->size      = $data['size'];	            
	            $size->is_block  = 1;

	            if($size->save()) {
	            	Session::flash('message', 'update Successfully!'); 
					Session::flash('alert-class', 'alert-success');
					return redirect()->route('manage_size');

	            } else{
	            	Session::flash('message', 'update Failed!'); 
					Session::flash('alert-class', 'alert-danger');
	                return redirect()->route('manage_size');
	            }   
	        }
        } else{
        	Session::flash('message', 'update Failed!'); 
			Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_size');
        }
	}

	public function delete( Request $request) {	
		$id = 0;
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			$error = 1;
			if($id != 0) {
				$size = SizeSettings::where('id',$id)->first();
				if($size){
					if($size->delete()) {
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
					$size = SizeSettings::where('id',$value)->first();
					if($size){
						if($size->delete()) {
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

	public function StatusSize ($id) {
		$size = '';
		$msg = '';
    	if($id != '') {
        	$size = SizeSettings::Where('id', $id)->first();
        }

        if($size) {
        	if($size->is_block == 1) {
            	$size->is_block        = 0;
            	$msg = "Blocked Successfully";
        	} else {
        		$size->is_block        = 1;
            	$msg = "Unblocked Successfully";
        	}
	        
	        if($size->save()) {
	        	Session::flash('message', $msg); 
				Session::flash('alert-class', 'alert-success');
				return redirect()->route('manage_size');
	        } else{
	        	Session::flash('message', 'Failed Block or Unblock!'); 
				Session::flash('alert-class', 'alert-danger');
	            return redirect()->route('manage_size');
	        }
        } else{
        	Session::flash('message', 'Failed Block or Unblock!'); 
			Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_size');
        }
	}

	public function SizeBlock( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$size = SizeSettings::where('id',$value)->first();
					if($size){
						$size->is_block = 0;
						$size->save();
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

	public function SizeUnblock( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$size = SizeSettings::where('id',$value)->first();
					if($size){
						$size->is_block = 1;
						$size->save();
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
