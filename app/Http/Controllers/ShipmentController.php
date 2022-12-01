<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;
use App\Orders;
use App\OrderDetails;
use App\OrdersTransactions;
use App\Products;
use App\User;
use App\ShippingAddress;
use App\NoimageSettings;
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
use SimpleXLSX;

class ShipmentController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function AllShipment () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Courier Tracking";
                $shipodr = Shipment::paginate(10);
            	return View::make("transaction.shipment.shipment_order")->with(array('shipodr'=>$shipodr, 'page'=>$page));
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
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Courier Tracking";
            	return View::make('transaction.shipment.add_shipment_order')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Courier Tracking";
            	$log = session()->get('user');
            	$rules = array(
                    'order_code'              => 'required',
                    'shipment_id'             => 'required',
                    'shipment_date'           => 'required|date',
                    'value'                   => 'required|numeric',
                    'weight'                  => 'required|numeric',
                    'type'                    => 'required',
                    'mode_type'               => 'required',
                    'carrier'                 => 'required',
                    'awb'                     => 'required',
                    'shiping_status'          => 'required',
                    'delivery_charges'        => 'nullable',
                    'delivery_date'           => 'nullable|date',
                    'ship_remarks'            => 'nullable',
                    'courier_payment_status'  => 'required',
                    'courier_payment_remarks' => 'nullable',
                    'is_block'                => 'nullable',
                );

                $messages=[
                    'ship_remarks.nullable'=>'The remarks field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            		return View::make('transaction.shipment.add_shipment_order')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                	$shipodr = new Shipment();

                    if($shipodr) {
        	            $shipodr->order_code              = $data['order_code'];	 
        	            $shipodr->shipment_id             = $data['shipment_id'];	 
        	            $shipodr->shipment_date           = date('Y-m-d',strtotime($data['shipment_date']));	 
        	            $shipodr->value                   = $data['value'];	 
        	            $shipodr->weight                  = $data['weight'];	 
        	            $shipodr->type                    = $data['type'];	 
        	            $shipodr->mode_type               = $data['mode_type'];	 
        	            $shipodr->carrier                 = $data['carrier'];	 
        	            $shipodr->awb                     = $data['awb'];	 
        	            $shipodr->shiping_status          = $data['shiping_status'];	

        	            if($data['delivery_charges']) {
        	            	$shipodr->delivery_charges    = $data['delivery_charges'];
        	            } else {
        	            	$shipodr->delivery_charges    = NULL;
        	            }

        	            if($data['delivery_date']) {
        	            	$shipodr->delivery_date       = date('Y-m-d',strtotime($data['delivery_date']));
        	            } else {
        	            	$shipodr->delivery_date       = NULL;
        	            }
        	            $shipodr->ship_remarks            = $data['ship_remarks'];

        	            if($data['courier_payment_status'] == 1) {
        	            	$shipodr->courier_payment_status       = 1;
        	            } else {
        	            	$shipodr->courier_payment_status       = 0;
        	            }

        	            $shipodr->courier_payment_remarks = $data['courier_payment_remarks'];	 
        	            $shipodr->is_block                = 1;	 
                        
                        if($shipodr->save()) {	                
        	                Session::flash('message', 'Added Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('shipment_order');
        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('shipment_order');
        	            }  
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('shipment_order');
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

    public function BulkCreate () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Courier Tracking";
            	return View::make('transaction.shipment.add_bulk_shipment_order')->with(array('page'=>$page));
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

    public function BulkStore(Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Courier Tracking";

            	$rules = array(
                    'bulk_upload'              => 'required',
                );

                $messages=[
                    'ship_remarks.nullable'=>'The remarks field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            		return View::make('transaction.shipment.add_bulk_shipment_order')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                    $file = "";
                    $upload_file_name = "";
                    if(isset($_FILES['bulk_upload'])) {
                    	$file = $_FILES['bulk_upload'];
                    	$upload_file = Input::file('bulk_upload');
                        if(isset($upload_file)) {
                            $file_name = $upload_file->getClientOriginalName();
                            $date = date('M-Y');
                            $file_path = 'shipments/'.$date;
                            $upload_file->move($file_path, $file_name);
                            $upload_file_name = $date.'/'.$file_name;
                        }	 
                    }

                    if(($file != "") && ($upload_file_name != "")) {
                    	$ok = true;
                    	$excel_data = array();
        			    ini_set('error_reporting', E_ALL);
        				ini_set('display_errors', true);

        				if ( $xlsx = SimpleXLSX::parse(public_path().'/shipments/'.$upload_file_name)) {
        					$excel_data = $xlsx->rows();
        					$cnt_ed = sizeof($excel_data);
        					$excel_data = \array_splice($excel_data, 1, $cnt_ed);
        					$ori_data = "";
        					foreach ($excel_data as $key => $value) {
        						if($value[0]) {
        							$ori_data[] = $value;
        						}
        					}
        				} else {
        					$ok = false;
        					Session::flash('message', SimpleXLSX::parseError()); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('add_bulk_shipment_order');
        				}

        				if(sizeof($ori_data) != 0) {
        					$fail = 0;
        					foreach ($ori_data as $key => $value) {	
        						if(empty($value[0])) {
        							$fail = 1;	
        						} else if(empty($value[1])) {
        							$fail = 1;	
        						} else if(empty($value[2])) {
        							$fail = 1;	
        						} else if((empty($value[3])) && (!preg_match("/^[0-9.]*$/",$value[3]))) {
        							$fail = 1;	
        						} else if((empty($value[4])) && (!preg_match("/^[0-9.]*$/",$value[4]))) {
        							$fail = 1;	
        						} else if(empty($value[5])) {
        							$fail = 1;	
        						} else if(empty($value[6])) {
        							$fail = 1;	
        						} else if(empty($value[7])) {
        							$fail = 1;	
        						} else if(empty($value[8])) {
        							$fail = 1;	
        						} else if(empty($value[9])) {
        							$fail = 1;	
        						} else {
        							$fail = 0;	
        						}
        					}

        					if($fail == 0) {
        						$sdat = 0;
        						$esdat = 0;
        						foreach ($ori_data as $key => $value) {
        							$shipodr = new Shipment();

        				            $shipodr->order_code              = $value[0];	 
        				            $shipodr->shipment_id             = $value[1];	 
        				            $shipodr->shipment_date           = date('Y-m-d',strtotime($value[2]));	 
        				            $shipodr->value                   = $value[3]; 
        				            $shipodr->weight                  = $value[4]; 
        				            $shipodr->type                    = $value[5];	 
        				            $shipodr->mode_type               = $value[6];	 
        				            $shipodr->carrier                 = $value[7];	 
        				            $shipodr->awb                     = $value[8];	 
        				            $shipodr->shiping_status          = $value[9];	 
        				            if($value[10]) {
        				            	$shipodr->delivery_charges    = $value[10];
        				            } else {
        				            	$shipodr->delivery_charges    = NULL;
        				            }

        				            if($value[11]) {
        				            	$shipodr->delivery_date       = date('Y-m-d',strtotime($value[11]));
        				            } else {
        				            	$shipodr->delivery_date       = NULL;
        				            }
        				            $shipodr->ship_remarks            = $value[12];

        				            if($value[13] == 1) {
        				            	$shipodr->courier_payment_status       = 1;
        				            } else {
        				            	$shipodr->courier_payment_status       = 0;
        				            }	

        				            $shipodr->courier_payment_remarks = $value[14];
        				            $shipodr->is_block                = 1;	 
        		                	
        		                	if($shipodr->save()) {
        		                		$sdat = 1;
        		                	} else {
        		                		$esdat = 1;
        		                	}
        						}

        						if($sdat == 1 && $esdat == 0) {
        							Session::flash('message', 'Added Successfully!'); 
        							Session::flash('alert-class', 'alert-success');
        			                return redirect()->route('shipment_order');
        						} else {
        							Session::flash('message', 'Data is Wrong, To Check Sample Download Data!'); 
        							Session::flash('alert-class', 'alert-danger');
        			                return redirect()->route('add_bulk_shipment_order');
        						}
        					} else {
        						Session::flash('message', 'Data Format Wrong!, Please Check Sample Download Data!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('add_bulk_shipment_order');
        					}
        				} else {
        					Session::flash('message', 'Import Correct File!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('add_bulk_shipment_order');
        				}
                    } else {
                    	Session::flash('message', 'Import Correct File!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('add_bulk_shipment_order');
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

	public function view ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
                $page = "Courier Tracking";
        		$shipodr = Shipment::where('id',$id)->first();
        		return View::make("transaction.shipment.view_shipment_order")->with(array('shipodr'=>$shipodr, 'page'=>$page));
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
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
            	$page = "Courier Tracking";
            	$shipodr = Shipment::Where('id', $id)->first();
            	return View::make('transaction.shipment.edit_shipment_order')->with(array('page'=>$page, 'shipodr'=>$shipodr));
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

    public function update(Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
            	$page = "Courier Tracking";
            	$log = session()->get('user');
            	$id = Input::get('shipodr_id');
            	$shipodr = '';
                if($id != '') {
                	$shipodr = Shipment::Where('id', $id)->first();
                }

                if($shipodr) {
        	    	$rules = array(
        	            'order_code'              => 'required',
        	            'shipment_id'             => 'required',
        	            'shipment_date'           => 'required|date',
        	            'value'                   => 'required|numeric',
        	            'weight'                  => 'required|numeric',
        	            'type'                    => 'required',
        	            'mode_type'               => 'required',
        	            'carrier'                 => 'required',
        	            'awb'                     => 'required',
        	            'shiping_status'          => 'required',
        	            'delivery_charges'        => 'nullable',
        	            'delivery_date'           => 'nullable|date',
        	            'ship_remarks'            => 'nullable',
        	            'courier_payment_status'  => 'required',
                    	'courier_payment_remarks' => 'nullable',
        	            'is_block'                => 'nullable',
        	        );

        	        $messages=[
        	            'ship_remarks.nullable'=>'The remarks field is required.',
        	        ];
        	        $validator = Validator::make(Input::all(), $rules,$messages);

        	        if ($validator->fails()) {
        	        	return Redirect::to('/edit_shipment_order/' . $id)->withErrors($validator)->with(array('shipodr'=>$shipodr, 'page'=>$page));
        	        } else {
        	            $data = Input::all();

        	            $shipodr->order_code              = $data['order_code'];	 
        	            $shipodr->shipment_id             = $data['shipment_id'];	 
        	            $shipodr->shipment_date           = date('Y-m-d',strtotime($data['shipment_date']));	 
        	            $shipodr->value                   = $data['value'];	 
        	            $shipodr->weight                  = $data['weight'];	 
        	            $shipodr->type                    = $data['type'];	 
        	            $shipodr->mode_type               = $data['mode_type'];	 
        	            $shipodr->carrier                 = $data['carrier'];	 
        	            $shipodr->awb                     = $data['awb'];	 
        	            $shipodr->shiping_status          = $data['shiping_status'];	 
        	            $shipodr->delivery_charges        = $data['delivery_charges'];	 
        	            if($data['delivery_date']) {
        	            	$shipodr->delivery_date       = date('Y-m-d',strtotime($data['delivery_date']));
        	            } else {
        	            	$shipodr->delivery_date       = NULL;
        	            }
        	            $shipodr->ship_remarks            = $data['ship_remarks'];	 
        	            $shipodr->courier_payment_status  = $data['courier_payment_status'];	 
        	            $shipodr->courier_payment_remarks = $data['courier_payment_remarks'];	 
        	            $shipodr->is_block                = 1;	 
                        
                        if($shipodr->save()) {	                
        	                Session::flash('message', 'Updated Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('shipment_order');
        	            } else{
        	            	Session::flash('message', 'Updated Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('shipment_order');
        	            }
        	        }
                } else {
                	Session::flash('message', 'Updated Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('shipment_order');
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
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$shipodr = Shipment::where('id',$id)->first();
        				if($shipodr){
        					if($shipodr->delete()) {
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
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$shipodr = Shipment::where('id',$value)->first();
        					if($shipodr){
        						if($shipodr->delete()) {
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

	public function ExportShipmentCSV( Request $request) {  
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Shipments')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()){
                    $ids = $request->ids;
                    $table = array();
                    $exp = '<table id="gj_shp_exp">
                        <thead>
                            <tr>
                                <th>Order Code</th>
                                <th>Shipment ID</th>
                                <th>Shipment Date</th>
                                <th>Shipment Value</th>
                                <th>Shipment Weight</th>
                                <th>Shipment Type</th>
                                <th>Shipment Mode Type</th>
                                <th>Shipment Carrier</th>
                                <th>Shipment AWB</th>
                                <th>Shipment Status</th>
                                <th>Delivery Charges</th>
                                <th>Delivery Date</th>
                                <th>Shipment Remarks</th>
                                <th>Courier Payment Status</th>
                                <th>Courier Payment Remarks</th>
                            </tr>
                        </thead>
                        <tbody>';
                    if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            $table = Shipment::whereIn('id',$ids)->get();
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') { 
                        $table = Shipment::all();
                    } else {
                        Session::flash('message', 'Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }

                    foreach ($table as $key => $value) {
                        if($value->order_code) {
                            $table[$key]['order_code'] = $value->order_code;
                        } else {
                            $table[$key]['order_code'] = "";
                        }

                        if($value->shipment_id) {
                            $table[$key]['shipment_id'] = $value->shipment_id;
                        } else {
                            $table[$key]['shipment_id'] = "";
                        }

                        if($value->shipment_date) {
                            $table[$key]['shipment_date'] = date('d-m-Y',strtotime($value->shipment_date));
                        } else {
                            $table[$key]['shipment_date'] = "";
                        }

                        if($value->value) {
                            $table[$key]['value'] = $value->value;
                        } else {
                            $table[$key]['value'] = "";
                        }

                        if($value->weight) {
                            $table[$key]['weight'] = $value->weight;
                        } else {
                            $table[$key]['weight'] = "";
                        }

                        if($value->type) {
                            $table[$key]['type'] = $value->type;
                        } else {
                            $table[$key]['type'] = "";
                        }

                        if($value->mode_type) {
                            $table[$key]['mode_type'] = $value->mode_type;
                        } else {
                            $table[$key]['mode_type'] = "";
                        }

                        if($value->carrier) {
                            $table[$key]['carrier'] = $value->carrier;
                        } else {
                            $table[$key]['carrier'] = "";
                        }

                        if($value->awb) {
                            $table[$key]['awb'] = $value->awb;
                        } else {
                            $table[$key]['awb'] = "";
                        }

                        if($value->shiping_status) {
                            $table[$key]['shiping_status'] = $value->shiping_status;
                        } else {
                            $table[$key]['shiping_status'] = "";
                        }

                        if($value->delivery_charges) {
                            $table[$key]['delivery_charges'] = $value->delivery_charges;
                        } else {
                            $table[$key]['delivery_charges'] = "";
                        }

                        if($value->delivery_date) {
                            $table[$key]['delivery_date'] = date('d-m-Y', strtotime($value->delivery_date));
                        } else {
                            $table[$key]['delivery_date'] = "";
                        }

                        if($value->ship_remarks) {
                            $table[$key]['ship_remarks'] = $value->ship_remarks;
                        } else {
                            $table[$key]['ship_remarks'] = "";
                        }

                        if($value->courier_payment_status) {
                            if($value->courier_payment_status == 1) {
                                $table[$key]['courier_payment_status'] = "Paid";
                            } else {
                                $table[$key]['courier_payment_status'] = "Un Paid";
                            }
                    	} else {
                            $table[$key]['courier_payment_status'] = "Un Paid";
                        }

                        if($value->courier_payment_remarks) {
                            $table[$key]['courier_payment_remarks'] = $value->courier_payment_remarks;
                        } else {
                            $table[$key]['courier_payment_remarks'] = "";
                        }

                        $exp.= '<tr>
                            <td>'.$table[$key]['order_code'].'</td>
                            <td>'.$table[$key]['shipment_id'].'</td>
                            <td>'.$table[$key]['shipment_date'].'</td>
                            <td>'.$table[$key]['value'].'</td>
                            <td>'.$table[$key]['weight'].'</td>
                            <td>'.$table[$key]['type'].'</td>
                            <td>'.$table[$key]['mode_type'].'</td>
                            <td>'.$table[$key]['carrier'].'</td>
                            <td>'.$table[$key]['awb'].'</td>
                            <td>'.$table[$key]['shiping_status'].'</td>
                            <td>'.$table[$key]['delivery_charges'].'</td>
                            <td>'.$table[$key]['delivery_date'].'</td>
                            <td>'.$table[$key]['ship_remarks'].'</td>
                            <td>'.$table[$key]['courier_payment_status'].'</td>
                            <td>'.$table[$key]['courier_payment_remarks'].'</td>
                        </tr>';
                    }
                    $exp.= '</tbody>
                    </table>';

                    return $exp;
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

    public function SearchShipment (Request $request) {
        $page = "Courier Tracking";                                               
        $shipment_date = Input::get('gj_srh_ship_date');
        $awb = Input::get('gj_srh_track');

        if($shipment_date && $awb) {
            $shipodr = Shipment::Where('shipment_date', $shipment_date)->Where('awb', 'like', '%' . $awb . '%')->paginate(10);
            if(count($shipodr) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("transaction.shipment.shipment_order")->with(array('shipodr'=>$shipodr, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                $shipodr = Shipment::paginate(10);
                return View::make("transaction.shipment.shipment_order")->with(array('shipodr'=>$shipodr, 'page'=>$page));
            }
        } elseif($shipment_date) {
            $shipodr = Shipment::Where('shipment_date', $shipment_date)->paginate(10);
            if(count($shipodr) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("transaction.shipment.shipment_order")->with(array('shipodr'=>$shipodr, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                $shipodr = Shipment::paginate(10);
                return View::make("transaction.shipment.shipment_order")->with(array('shipodr'=>$shipodr, 'page'=>$page));
            }
        } elseif($awb) {
            $shipodr = Shipment::orWhere('awb', 'like', '%' . $awb . '%')->paginate(10);
            if(count($shipodr) != 0) {
                Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
                return View::make("transaction.shipment.shipment_order")->with(array('shipodr'=>$shipodr, 'page'=>$page));
            } else {
                Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
                $shipodr = Shipment::paginate(10);
                return View::make("transaction.shipment.shipment_order")->with(array('shipodr'=>$shipodr, 'page'=>$page));
            }
        } else {
            $shipodr = Shipment::paginate(10);
            Session::flash('message', 'Search Items Not Found!'); 
            Session::flash('alert-class', 'alert-danger');
            return View::make("transaction.shipment.shipment_order")->with(array('shipodr'=>$shipodr, 'page'=>$page));
        }
    }
}
