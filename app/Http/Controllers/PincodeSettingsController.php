<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SizeSettings;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use App\Pincode;
use App\User;
use View;
use Yajra\DataTables\DataTables;

use Session;
use Redirect;
use URL;

class PincodeSettingsController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index (Request $request) {
        
        if($request->Ajax())
        {
            ## Read value
            $data=$request->all();
// $draw = $data['draw'];
// $row = $data['start'];
// // dd($draw);
// $rowperpage = $data['length']; // Rows display per page
// $columnIndex = $data['order'][0]['column']; // Column index
// $columnName = $data['columns'][$columnIndex]['data']; // Column name
// $columnSortOrder = $data['order'][0]['dir']; // asc or desc
// $searchValue = mysqli_real_escape_string($con,$data['search']['value']); // Search value
$sel =Pincode::select([
                    'pincodes.id', 'pincodes.pincode', 'circlename','taluk','pincodes.divisionname', 'pincodes.egionname', 'pincodes.districtname', 'pincodes.statename', 'pincodes.is_block',
                ])->get()->take(500000);
// $records = json_encode($sel);

$totalRecords =count($sel);
$totalRecordwithFilter = $totalRecords;

$data = array();
foreach($sel as $k => $row)
{
    $updateButton ='<a href="'.url('edit_pincode/'.$row->id).'" data-tooltip="Edit">
                                                        <i class="fa fa-edit fa-2x"></i>
                                                    </a>';

     // Delete Button
     $deleteButton = '<a href="#" id="'.$row->id.'"  onclick="ee_delete('.$row->id.')" class="gj_mge_product_del" data-tooltip="Delete">
                                                        <i class="fa fa-trash fa-2x"></i>
                                                    </a>';

     $action = $updateButton."<br>".$deleteButton;
$k=$k+1;


$html='<a href="'.url('status_pincode/'.$row->id).'" data-tooltip="block">';
    if($row->is_block == 1)
        $html.='<i class="gj_ok fa fa-check fa-2x"></i>';
    else
        $html.='<i class="gj_danger fa fa-ban fa-2x"></i>';
    
$html.='</a>';

  $data[] = array( 
        "id"=>$k,
        "pincode"=>$row->pincode,
        "divisionname"=>$row->divisionname,
        "egionname"=>$row->circlename.','.$row->taluk,
        "districtname"=>$row->districtname,
        "statename"=>$row->statename,
        "is_block"=>$html,
        "action"=>$action
   );   
   
   
}

$response = array(
 "data"=>$data
);

echo json_encode($response);exit;



            $cities = Pincode::select([
                    'pincodes.id', 'pincodes.pincode', 'pincodes.divisionname', 'pincodes.egionname', 'pincodes.districtname', 'pincodes.statename', 'pincodes.is_block',
                ]);
        return Datatables::of($cities)
                        ->filter(function ($query) use ($request) {
                            
                        })
                         ->editColumn('id', function ($cities) {
                            $html='<input type="checkbox" name="check[]" class="checkBoxClass" value="'.$cities->id.'" id="Checkbox'.$cities->id.'" />';
                                                   
                                                return $html;
                        })
                        ->editColumn('is_block', function ($cities) {
                            $html='<a href="" data-tooltip="block">';
                                                    if($cities->is_block == 1)
                                                       $html.='<i class="gj_ok fa fa-check fa-2x"></i>';
                                                    else
                                                        $html.='<i class="gj_danger fa fa-ban fa-2x"></i>';
                                                  
                                                $html.='</a>';
                                                return $html;
                        })
                       
                        ->addColumn('action', function ($cities) {
                          $html=' <a href="" data-tooltip="Edit">
                                    <i class="fa fa-edit fa-2x"></i>
                                </a>';
                                $html.='<a href="#" id="" class="gj_mge_size_del" data-tooltip="Delete">
                                    <i class="fa fa-trash fa-2x"></i>
                                </a>';
                                return $html;
                        })
                        ->setRowId(function($cities) {
                            return 'cityDtRow' . $cities->id;
                        })
                        ->make(true);
        }
        
    	return View::make("settings.pincodes.manage_pincode")->with(array('page'=>'Settings'));
    }

    public function create () {
    	return View::make('settings.pincodes.add_pincode');
    }

    public function store(Request $request) {
    	$rules = array(
            'pincode'      => 'required|numeric',
             'divisionname'      => 'required',
              'regionname'      => 'required',
               'circlename'      => 'required',
                'taluk'      => 'required',
                 'districtname'      => 'required',
                  'statename'      => 'required',
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
            $already=Pincode::where('pincode',$data['pincode'])
            ->where('divisionname', $data['divisionname'])
            ->where('egionname',$data['regionname'])
            ->where('taluk',$data['taluk'])
            ->where('districtname',$data['districtname'])
            ->where('statename',$data['statename'])
            ->count();
            
            if($already==0)
            {
        	$size = new Pincode();

            if($size) {
	            $size->pincode      = $data['pincode'];
	             $size->divisionname      = $data['divisionname'];
	              $size->egionname      = $data['regionname'];
	               $size->circlename      = $data['circlename'];
	                $size->taluk      = $data['taluk'];
	                 $size->districtname      = $data['districtname'];
	                  $size->statename      = $data['statename'];
	                     $size->is_block  = 1;

                if($size->save()) {
	                Session::flash('message', 'Add Successfully!'); 
					Session::flash('alert-class', 'alert-success');
					return redirect()->route('manage_pincode');

	            } else{
	            	Session::flash('message', 'Added Failed!'); 
					Session::flash('alert-class', 'alert-danger');
	                return redirect()->route('manage_pincode');
	            }  
            }
            }
            else
            {
                	Session::flash('message', 'Duplicate Data!'); 
					Session::flash('alert-class', 'alert-danger');
	                return redirect()->route('manage_pincode');
            }
            
        }
    }

	public function edit ($id) {
		$size = Pincode::where('id',$id)->first();
		return View::make("settings.pincodes.edit_pincode")->with(array('size'=>$size));
	}

	public function update (Request $request) {
		$id = Input::get('pincode_id');
        $size = '';
        if($id != '') {
        	$size = Pincode::Where('id', $id)->first();
        }

        if($size) {
			$rules = array(
            'pincode'      => 'required|numeric',
             'divisionname'      => 'required',
              'regionname'      => 'required',
               'circlename'      => 'required',
                'taluk'      => 'required',
                 'districtname'      => 'required',
                  'statename'      => 'required',
            'is_block'  => 'nullable',
        );

	        $validator = Validator::make(Input::all(), $rules);

	        if ($validator->fails()) {
	    	   	return View::make('settings.pincodes.edit_pincode')->withErrors($validator)->with(array('size'=>$size));
	        } else {
	            $data = Input::all();

	           $size->pincode      = $data['pincode'];
	             $size->divisionname      = $data['divisionname'];
	              $size->egionname      = $data['regionname'];
	               $size->circlename      = $data['circlename'];
	                $size->taluk      = $data['taluk'];
	                 $size->districtname      = $data['districtname'];
	                  $size->statename      = $data['statename'];
	                     $size->is_block  = 1;


	            if($size->save()) {
	            	Session::flash('message', 'update Successfully!'); 
					Session::flash('alert-class', 'alert-success');
					return redirect()->route('manage_pincode');

	            } else{
	            	Session::flash('message', 'update Failed!'); 
					Session::flash('alert-class', 'alert-danger');
	                return redirect()->route('manage_pincode');
	            }   
	        }
        } else{
        	Session::flash('message', 'update Failed!'); 
			Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_pincode');
        }
	}

	public function delete( Request $request) {	
		$id = 0;
// 		echo 0;exit;
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			$error = 1;
			if($id != 0) {
				$size = Pincode::where('id',$id)->first();
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
        	$size = Pincode::Where('id', $id)->first();
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
				return redirect()->route('manage_pincode');
	        } else{
	        	Session::flash('message', 'Failed Block or Unblock!'); 
				Session::flash('alert-class', 'alert-danger');
	            return redirect()->route('manage_pincode');
	        }
        } else{
        	Session::flash('message', 'Failed Block or Unblock!'); 
			Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_pincode');
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
