<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cashout;
use App\CashoutRequest;
use App\CashoutPayment;
use App\BankDetails;
use App\AdminCommision;
use App\OrdersTransactions;
use App\Orders;
use App\OrderDetails;
use App\User;
use App\Products;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class CashoutController extends Controller
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
                ->where('B.module_name', '=', 'Process Cashout')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Accounts";
            	$log = session()->get('user');
            	if($log) {
            		if ($log->user_type == 1) {
            			$vendors = User::WhereIn('user_type', ['2','3'])->Where('is_block', 1)->get();
        		        $cash = Cashout::OrderBy('id', 'DESC')->get();
        		        if(sizeof($cash) != 0) {
        		        	$outstand = AdminCommision::Where('merchant_id', $log->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
        		        	
        		        	if($outstand) {
        		        		$cash->{'outstand'} = $outstand;
        		        	} else {
        		        		$cash->{'outstand'} = 0.00;
        		        	}

        		        	$last_request_amount = Cashout::pluck('request_amount')->last();
        		        	if($last_request_amount) {
        		        		$cash->{'last_request_amount'} = $last_request_amount;
        		        	} else {
        		        		$cash->{'last_request_amount'} = "-----";
        		        	}

        		        	$last_request_date = Cashout::pluck('request_date')->last();
        		        	if($last_request_date) {
        		        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
        		        	} else {
        		        		$cash->{'last_request_date'} = "-----";
        		        	}

        		        	foreach ($cash as $key => $value) {
        		        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
        		        		if(sizeof($ch_req) != 0) {
        		        			$cash[$key]->cash = $ch_req;
        		        			$cash[$key]->invoice = sizeof($ch_req);
        		        		} else {
        		        			$cash[$key]->cash = "";
        		        			$cash[$key]->invoice = 0;
        		        		}
        		        	}
        		        }
        		    	return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash,'vendors'=>$vendors, 'page'=>$page));
            		} else if ($log->user_type == 2 || $log->user_type == 3) {
            			$cash = Cashout::Where('merchant_id', $log->id)->OrderBy('id', 'DESC')->get();
            			if(sizeof($cash) != 0) {
            				$outstand = AdminCommision::Where('merchant_id', $log->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
        		        	
        		        	if($outstand) {
        		        		$cash->{'outstand'} = $outstand;
        		        	} else {
        		        		$cash->{'outstand'} = 0.00;
        		        	}

            				$last_request_amount = Cashout::pluck('request_amount')->last();
        		        	if($last_request_amount) {
        		        		$cash->{'last_request_amount'} = $last_request_amount;
        		        	} else {
        		        		$cash->{'last_request_amount'} = "-----";
        		        	}

        		        	$last_request_date = Cashout::pluck('request_date')->last();
        		        	if($last_request_date) {
        		        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
        		        	} else {
        		        		$cash->{'last_request_date'} = "-----";
        		        	}

        		        	foreach ($cash as $key => $value) {
        		        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
        		        		if(sizeof($ch_req) != 0) {
        		        			$cash[$key]->cash = $ch_req;
        		        			$cash[$key]->invoice = sizeof($ch_req);
        		        		} else {
        		        			$cash[$key]->cash = "";
        		        			$cash[$key]->invoice = 0;
        		        		}
        		        	}
        		        }
            			return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash, 'page'=>$page));
            		} else {
            			Session::flash('message', 'You Are Not Access!'); 
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

    public function create () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Process Cashout')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Accounts";
            	$log = session()->get('user');
            	if($log) {
            		if ($log->user_type == 2 || $log->user_type == 3) {
            			$comis = AdminCommision::Where('merchant_id', $log->id)->Where('paid_status', '!=', 1)->OrderBy('id', 'DESC')->get();
            			$comis->{'outstand'} = AdminCommision::Where('merchant_id', $log->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');

            			$last_request_amount = Cashout::max('request_amount');
        	        	if($last_request_amount) {
        	        		$comis->{'last_request_amount'} = Cashout::max('request_amount');
        	        	} else {
        	        		$comis->{'last_request_amount'} = "-----";
        	        	}

        	        	$last_request_date = Cashout::max('request_date');
        	        	if($last_request_date) {
        	        		$comis->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
        	        	} else {
        	        		$comis->{'last_request_date'} = "-----";
        	        	}

            			return View::make("accounts.cashout.add_cashout")->with(array('comis'=>$comis, 'page'=>$page));
        			} else {
        				Session::flash('message', 'You Are Not Access to Add!'); 
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

    public function store(Request $request) {
    	$page = "Accounts";
    	$log = session()->get('user');
    	$error = array();
    	$data= [];
    	$log = session()->get('user');

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Process Cashout')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	if($log) {
            		if ($log->user_type == 2 || $log->user_type == 3) {
            			if($request->ajax() && isset($request->ids) && isset($request->request_amount) && isset($request->request_date) && isset($request->remarks)) {
        					$ids = $request->ids;
        					if(sizeof($ids) == 0) {
        						$error = array('status'=>'22','error'=> "Please Select Another Order!");
        			    	   	echo json_encode($error);die();
        					}

        					$data['request_amount'] = $request->request_amount;
        					$data['remarks'] = $request->remarks;
        					$data['request_date'] = date('Y-m-d' ,strtotime($request->request_date));

        					$rules = array(
        			            'request_code'    => 'nullable',
        			            'request_amount'  => 'required|numeric',
        			            'amount_paid'     => 'nullable',
        			            'request_date'    => 'required|date',
        			            'merchant_id'     => 'nullable',
        			            'paid_status'     => 'nullable',
        			            'remarks'         => 'required',
        			        );

        			        $messages=[
        			            'remarks.required'=>'The remarks field is required.',
        			        ];
        			        $validator = Validator::make($data, $rules,$messages);

        			        if ($validator->fails()) {
        			    	   	$error = array('status'=>'2','error'=> $validator->getMessageBag()->toArray());
        			    	   	echo json_encode($error);die();
        			        } else {
        			            $max = Cashout::max('request_code');
        			            $max_id = "0001";
        			        	$max_st = "Req";
        			            if(($max)) {
        			            	$max_no = substr($max, 3);
        			            	$increment = (int)$max_no + 1;
        			            	$data['request_code'] = $max_st.sprintf("%04d", $increment);
        			            } else {
        			            	$data['request_code'] = $max_st.$max_id;
        			            }

        			        	$cash = new Cashout();

        			            if($cash) {
        				            $cash->request_code    = $data['request_code'];	 
        				            $cash->request_amount  = $data['request_amount'];	 
        				            $cash->amount_paid     = 0.00;	 
        				            $cash->balance         = $data['request_amount'];	 
        				            $cash->request_date    = $data['request_date'];	 
        				            $cash->merchant_id     = $log->id;	 
        				            $cash->paid_status     = "Unpaid";	 
        				            $cash->remarks         = $data['remarks'];	 
        			                
        			                if($cash->save()) {
        			                	$add = 0;
        			                	$n_add = 0;
        			                	$com_add = 0;
        			                	foreach ($ids as $key => $value) {
        			                		$comis = AdminCommision::Where('id', $value)->first();
        			                		if($comis) {
        					                	$ca_req = new CashoutRequest();
        					                	$ca_req->request_code    =  $cash->id; 
        					                	$ca_req->order_code      =  $comis->ComisOrders->id;
        					                	$ca_req->order_dets      =  $comis->order_dets;
        					                	$ca_req->product_id      =  $comis->product_id;
        					                	$ca_req->merchant_id     =  $log->id;
        					                	$ca_req->comis_amount    =  $comis->amount;
        					                	$ca_req->vendor_amount   =  $comis->merchant_amount;
        					                	if($ca_req->save()) {
        					                		$add = 1;
        					                	} else {
        					                		$n_add = 1;
        					                	}
        			                		} else {
        			                			$com_add = 1;
        			                		}
        			                	}

        			                	if($add == 1 && $n_add == 0 && $com_add == 0) {
        			                		Session::flash('message', 'Successfully Add!'); 
        									Session::flash('alert-class', 'alert-success');

        			                		$error = array('status'=>'1','error'=> 'Success');
        			                	} else {
        			                		CashoutRequest::Where('request_code',$cash->id)->delete();
        			                		$cash->delete();
        			                		Session::flash('message', 'Added Failed!'); 
        									Session::flash('alert-class', 'alert-danger');

        			                		$error = array('status'=>'0','error'=> 'Failed');
        			                	}
        				            } else{
        				            	Session::flash('message', 'Added Failed!'); 
        								Session::flash('alert-class', 'alert-danger');

        		                		$error = array('status'=>'0','error'=> 'Failed');
        				            }  
        			            } else{
        			            	Session::flash('message', 'Added Failed!'); 
        							Session::flash('alert-class', 'alert-danger');

        	                		$error = array('status'=>'0','error'=> 'Failed');
        			            }
        			        }
        				}
        			} else {
        				Session::flash('message', 'You Are Not Access to Add!'); 
        				Session::flash('alert-class', 'alert-danger');
        				$error = array('status'=>'3','error'=> 'You Are Not Access to Add!');
        			}
        		} else {
            		Session::flash('message', 'You Are Not Login!'); 
        			Session::flash('alert-class', 'alert-danger');
            		$error = array('status'=>'4','error'=> 'You Are Not Login!');
            	}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $error = array();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $error = array();
        }

	   	echo json_encode($error);die();   	
    }

    public function view ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Process Cashout')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
        		$page = "Accounts";
            	$log = session()->get('user');
            	if($log) {
            		if ($log->user_type == 1) {
        		        $cash = Cashout::Where('id', $id)->first();
        		        if($cash) {
        	        		$ch_req = CashoutRequest::Where('request_code', $cash->id)->get();
        	        		if(sizeof($ch_req) != 0) {
        	        			$cash->{'cash'} = $ch_req;
        	        			$cash->{'invoice'} = sizeof($ch_req);
        	        		} else {
        	        			$cash->{'cash'} = array();
        	        			$cash->{'invoice'} = 0;
        	        		}

        	        		$cash_pay = CashoutPayment::Where('request_code', $cash->id)->get();
        	        		if(sizeof($cash_pay) != 0) {
        	        			$cash->{'cash_pay'} = $cash_pay;
        	        		} else {
        	        			$cash->{'cash_pay'} = array();
        	        		}
        		        }
        		    	return View::make("accounts.cashout.view_cashout")->with(array('cash'=>$cash, 'page'=>$page));
            		} else if ($log->user_type == 2 || $log->user_type == 3) {
            			$cash = Cashout::Where('merchant_id', $log->id)->Where('id', $id)->first();
            			if($cash) {
        	        		$ch_req = CashoutRequest::Where('request_code', $cash->id)->get();
        	        		if(sizeof($ch_req) != 0) {
        	        			$cash->{'cash'} = $ch_req;
        	        			$cash->{'invoice'} = sizeof($ch_req);
        	        		} else {
        	        			$cash->{'cash'} = array();
        	        			$cash->{'invoice'} = 0;
        	        		}

        	        		$cash_pay = CashoutPayment::Where('request_code', $cash->id)->get();
        	        		if(sizeof($cash_pay) != 0) {
        	        			$cash->{'cash_pay'} = $cash_pay;
        	        		} else {
        	        			$cash->{'cash_pay'} = array();
        	        		}
        		        }
            			return View::make("accounts.cashout.view_cashout")->with(array('cash'=>$cash, 'page'=>$page));
            		} else {
            			Session::flash('message', 'You Are Not Access!'); 
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

	public function MakePay ($id) {
		$page = "Accounts";
    	$log = session()->get('user');
    	if($log) {
    		if ($log->user_type == 1) {
		        $cash = Cashout::Where('id', $id)->Where('paid_status', '!=', 'Paid')->first();
		        if($cash) {
	        		$ch_req = CashoutRequest::Where('request_code', $cash->id)->get();
	        		if(sizeof($ch_req) != 0) {
	        			$cash->{'cash'} = $ch_req;
	        			$cash->{'invoice'} = sizeof($ch_req);
	        		} else {
	        			$cash->{'cash'} = "";
	        			$cash->{'invoice'} = 0;
	        		}

	        		$banks = BankDetails::Where('merchant_id', $cash->merchant_id)->get();
	        		if(sizeof($banks) != 0) {
	        			$cash->{'banks'} = $banks;
	        		} else {
	        			$cash->{'banks'} = "";
	        		}
		    		return View::make("accounts.cashout.make_pay")->with(array('cash'=>$cash, 'page'=>$page));
		        } else {
		        	Session::flash('message', 'You Are Already Paid or Make Payment Not Availible For This Time!'); 
					Session::flash('alert-class', 'alert-danger');
	    			return redirect()->route('manage_cashout');
		        }
    		} else {
    			Session::flash('message', 'You Are Not Access!'); 
				Session::flash('alert-class', 'alert-danger');
    			return redirect()->route('manage_cashout');
    		}
    	} else {
    		Session::flash('message', 'You Are Not Login!'); 
			Session::flash('alert-class', 'alert-danger');
    		return redirect()->route('admin');
    	}
	}

	public function ProcessPay (Request $request) {
		$page = "Accounts";
    	$log = session()->get('user');
    	if($log) {
    		if ($log->user_type == 1) {
    			$id = Input::get('cash_id');
    			$cash = "";
    			$data = Input::all();

    			if($id) {
		        	$cash = Cashout::Where('id', $id)->Where('paid_status', '!=', 'Paid')->first();
    			}
    			if($cash) {
	    			$rules = array(
			            'bal_amount_tranfer'  => 'nullable',
			            'amount_paid'         => 'required|numeric',
			            'balance'             => 'required|numeric',
			            'remarks'             => 'required',
			            'paid_status'         => 'nullable',

			            'bank'                => 'required|exists:bank_details,id',
			            'pay_mode'            => 'required',
			            'cheque_no'           => 'required_if:pay_mode,==,1',
			            'bank_name'           => 'required_if:pay_mode,==,1',
			            'branch_name'         => 'required_if:pay_mode,==,1',
			            'receipt'             => 'required_if:pay_mode,==,2',
			        );

			        $messages=[
			            'bank.required'=>'bank field is required.',
			        ];
			        $validator = Validator::make(Input::all(), $rules,$messages);

			        if ($validator->fails()) {
			    		return Redirect::to('/make_pay/' . $id)->withErrors($validator)->with(array('cash'=>$cash, 'page'=>$page));
			        } else {
		        		$cash->amount_paid = $cash->amount_paid + $data['amount_paid'];
		        		$cash->balance     = $data['bal_amount_tranfer'] - $data['amount_paid'];
		        		$cash->remarks     = $data['remarks'];

		        		if($data['bal_amount_tranfer'] == $data['amount_paid'] && $data['bal_amount_tranfer'] <= $data['amount_paid']) {
		        			$cash->paid_status     = "Paid";
		        		} else if($data['bal_amount_tranfer'] > $data['amount_paid']) {
		        			$cash->paid_status     = "Partial";
		        		} else {
		        			$cash->paid_status     = "Unpaid";
		        		}

		        		if($cash->save()) {
		        			$c_pay = new CashoutPayment();
		        			$c_pay->request_code = $cash->id;
		        			$c_pay->bank = $data['bank'];
		        			$c_pay->pay_mode = $data['pay_mode'];

		        			if ($data['pay_mode'] ==1) {
		        				$c_pay->cheque_no = $data['cheque_no'];
		        				$c_pay->bank_name = $data['bank_name'];
		        				$c_pay->branch_name = $data['branch_name'];
                                
                                $cheque_img_files = Input::file('cheque_img');
                                if(isset($cheque_img_files)) {
                                    $file_name = $cheque_img_files->getClientOriginalName();
                                    $date = date('M-Y');
                                    // $file_path = '../public/images/cheque_img/'.$date;
                                    $file_path = 'cheque_img/'.$date;
                                    $cheque_img_files->move($file_path, $file_name);
                                    $c_pay->cheque_img = $date.'/'.$file_name;
                                } else {
                                    $c_pay->cheque_img = NULL;
                                } 
		        			} else if ($data['pay_mode'] ==2) {
		        				$img_files = Input::file('receipt');
				                if(isset($img_files)) {
				                    $file_name = $img_files->getClientOriginalName();
				                    $date = date('M-Y');
				                    // $file_path = '../public/images/receipt/'.$date;
				                    $file_path = 'receipt/'.$date;
				                    $img_files->move($file_path, $file_name);
				                    $c_pay->receipt = $date.'/'.$file_name;
				                } else {
				                    $c_pay->receipt = NULL;
				                } 
		        			}

		        			if($c_pay->save()) {
		        				Session::flash('message', 'Payment Paid Successfully!'); 
								Session::flash('alert-class', 'alert-success');
		        				return redirect()->route('view_cashout', ['id' => $cash->id]);
		        			} else {
		        				Session::flash('message', 'Payment Paid Failed!'); 
								Session::flash('alert-class', 'alert-danger');
		        				return redirect()->route('view_cashout', ['id' => $cash->id]);
		        			}
		        		} else {
		        			Session::flash('message', 'Payment Paid Failed!'); 
							Session::flash('alert-class', 'alert-danger');
	        				return redirect()->route('view_cashout', ['id' => $cash->id]);
		        		}
			        }
		        } else {
		        	Session::flash('message', 'You Are Already Paid or Make Payment Not Availible For This Time!'); 
					Session::flash('alert-class', 'alert-danger');
	    			return redirect()->route('manage_cashout');
		        }
    		} else {
    			Session::flash('message', 'You Are Not Access!'); 
				Session::flash('alert-class', 'alert-danger');
    			return redirect()->route('manage_cashout');
    		}
    	} else {
    		Session::flash('message', 'You Are Not Login!'); 
			Session::flash('alert-class', 'alert-danger');
    		return redirect()->route('admin');
    	}
	}

    public function SearchCashout (Request $request) {
        $page = "Accounts";
        $user = session()->get('user');
        $vendors = User::WhereIn('user_type', ['2','3'])->Where('is_block', 1)->get();

        $start_date = Input::get('gj_srh_srt_date');
        if($start_date) {
            $start_date = date('Y-m-d', strtotime($start_date));
        }

        $end_date = Input::get('gj_srh_end_date');
        if($end_date) {
            $end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
        }

        $p_status = Input::get('gj_srh_p_sts');
        $vendor = Input::get('gj_srh_vendor');

        if($user) {
            if ($user->user_type == 1) {
                if($start_date && $end_date && $p_status && $vendor) {
                    $cash = Cashout::OrderBy('id', 'DESC')->whereBetween('request_date', [$start_date, $end_date])->Where('paid_status', $p_status)->Where('merchant_id', $vendor)->get();
			        if(sizeof($cash) != 0) {
			        	$outstand = AdminCommision::Where('merchant_id', $user->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
			        	
			        	if($outstand) {
			        		$cash->{'outstand'} = $outstand;
			        	} else {
			        		$cash->{'outstand'} = 0.00;
			        	}

			        	$last_request_amount = Cashout::max('request_amount');
			        	if($last_request_amount) {
			        		$cash->{'last_request_amount'} = Cashout::max('request_amount');
			        	} else {
			        		$cash->{'last_request_amount'} = "-----";
			        	}

			        	$last_request_date = Cashout::max('request_date');
			        	if($last_request_date) {
			        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
			        	} else {
			        		$cash->{'last_request_date'} = "-----";
			        	}

			        	foreach ($cash as $key => $value) {
			        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
			        		if(sizeof($ch_req) != 0) {
			        			$cash[$key]->cash = $ch_req;
			        			$cash[$key]->invoice = sizeof($ch_req);
			        		} else {
			        			$cash[$key]->cash = "";
			        			$cash[$key]->invoice = 0;
			        		}
			        	}

			        	Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash, 'vendors'=>$vendors, 'page'=>$page));
			        } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cashout');
                    }
                } else if($start_date && $end_date && $p_status) {
                    $cash = Cashout::OrderBy('id', 'DESC')->whereBetween('request_date', [$start_date, $end_date])->Where('paid_status', $p_status)->get();
			        if(sizeof($cash) != 0) {
			        	$outstand = AdminCommision::Where('merchant_id', $user->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
			        	
			        	if($outstand) {
			        		$cash->{'outstand'} = $outstand;
			        	} else {
			        		$cash->{'outstand'} = 0.00;
			        	}

			        	$last_request_amount = Cashout::max('request_amount');
			        	if($last_request_amount) {
			        		$cash->{'last_request_amount'} = Cashout::max('request_amount');
			        	} else {
			        		$cash->{'last_request_amount'} = "-----";
			        	}

			        	$last_request_date = Cashout::max('request_date');
			        	if($last_request_date) {
			        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
			        	} else {
			        		$cash->{'last_request_date'} = "-----";
			        	}

			        	foreach ($cash as $key => $value) {
			        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
			        		if(sizeof($ch_req) != 0) {
			        			$cash[$key]->cash = $ch_req;
			        			$cash[$key]->invoice = sizeof($ch_req);
			        		} else {
			        			$cash[$key]->cash = "";
			        			$cash[$key]->invoice = 0;
			        		}
			        	}

			        	Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash, 'vendors'=>$vendors, 'page'=>$page));
			        } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cashout');
                    }
                } else if($start_date && $end_date && $vendor) {
                    $cash = Cashout::OrderBy('id', 'DESC')->whereBetween('request_date', [$start_date, $end_date])->Where('merchant_id', $vendor)->get();
			        if(sizeof($cash) != 0) {
			        	$outstand = AdminCommision::Where('merchant_id', $user->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
			        	
			        	if($outstand) {
			        		$cash->{'outstand'} = $outstand;
			        	} else {
			        		$cash->{'outstand'} = 0.00;
			        	}

			        	$last_request_amount = Cashout::max('request_amount');
			        	if($last_request_amount) {
			        		$cash->{'last_request_amount'} = Cashout::max('request_amount');
			        	} else {
			        		$cash->{'last_request_amount'} = "-----";
			        	}

			        	$last_request_date = Cashout::max('request_date');
			        	if($last_request_date) {
			        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
			        	} else {
			        		$cash->{'last_request_date'} = "-----";
			        	}

			        	foreach ($cash as $key => $value) {
			        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
			        		if(sizeof($ch_req) != 0) {
			        			$cash[$key]->cash = $ch_req;
			        			$cash[$key]->invoice = sizeof($ch_req);
			        		} else {
			        			$cash[$key]->cash = "";
			        			$cash[$key]->invoice = 0;
			        		}
			        	}

			        	Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash, 'vendors'=>$vendors, 'page'=>$page));
			        } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cashout');
                    }
                } elseif($start_date && $end_date) {
                    $cash = Cashout::OrderBy('id', 'DESC')->whereBetween('request_date', [$start_date, $end_date])->get();
			        if(sizeof($cash) != 0) {
			        	$outstand = AdminCommision::Where('merchant_id', $user->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
			        	
			        	if($outstand) {
			        		$cash->{'outstand'} = $outstand;
			        	} else {
			        		$cash->{'outstand'} = 0.00;
			        	}

			        	$last_request_amount = Cashout::max('request_amount');
			        	if($last_request_amount) {
			        		$cash->{'last_request_amount'} = Cashout::max('request_amount');
			        	} else {
			        		$cash->{'last_request_amount'} = "-----";
			        	}

			        	$last_request_date = Cashout::max('request_date');
			        	if($last_request_date) {
			        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
			        	} else {
			        		$cash->{'last_request_date'} = "-----";
			        	}

			        	foreach ($cash as $key => $value) {
			        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
			        		if(sizeof($ch_req) != 0) {
			        			$cash[$key]->cash = $ch_req;
			        			$cash[$key]->invoice = sizeof($ch_req);
			        		} else {
			        			$cash[$key]->cash = "";
			        			$cash[$key]->invoice = 0;
			        		}
			        	}

			        	Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash, 'vendors'=>$vendors, 'page'=>$page));
			        } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cashout');
                    }
                } elseif($p_status) {
                    $cash = Cashout::OrderBy('id', 'DESC')->Where('paid_status', $p_status)->get();
			        if(sizeof($cash) != 0) {
			        	$outstand = AdminCommision::Where('merchant_id', $user->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
			        	
			        	if($outstand) {
			        		$cash->{'outstand'} = $outstand;
			        	} else {
			        		$cash->{'outstand'} = 0.00;
			        	}

			        	$last_request_amount = Cashout::max('request_amount');
			        	if($last_request_amount) {
			        		$cash->{'last_request_amount'} = Cashout::max('request_amount');
			        	} else {
			        		$cash->{'last_request_amount'} = "-----";
			        	}

			        	$last_request_date = Cashout::max('request_date');
			        	if($last_request_date) {
			        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
			        	} else {
			        		$cash->{'last_request_date'} = "-----";
			        	}

			        	foreach ($cash as $key => $value) {
			        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
			        		if(sizeof($ch_req) != 0) {
			        			$cash[$key]->cash = $ch_req;
			        			$cash[$key]->invoice = sizeof($ch_req);
			        		} else {
			        			$cash[$key]->cash = "";
			        			$cash[$key]->invoice = 0;
			        		}
			        	}

			        	Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash, 'vendors'=>$vendors, 'page'=>$page));
			        } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cashout');
                    }
                } elseif($vendor) {
                    $cash = Cashout::OrderBy('id', 'DESC')->Where('merchant_id', $vendor)->get();
			        if(sizeof($cash) != 0) {
			        	$outstand = AdminCommision::Where('merchant_id', $user->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
			        	
			        	if($outstand) {
			        		$cash->{'outstand'} = $outstand;
			        	} else {
			        		$cash->{'outstand'} = 0.00;
			        	}

			        	$last_request_amount = Cashout::max('request_amount');
			        	if($last_request_amount) {
			        		$cash->{'last_request_amount'} = Cashout::max('request_amount');
			        	} else {
			        		$cash->{'last_request_amount'} = "-----";
			        	}

			        	$last_request_date = Cashout::max('request_date');
			        	if($last_request_date) {
			        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
			        	} else {
			        		$cash->{'last_request_date'} = "-----";
			        	}

			        	foreach ($cash as $key => $value) {
			        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
			        		if(sizeof($ch_req) != 0) {
			        			$cash[$key]->cash = $ch_req;
			        			$cash[$key]->invoice = sizeof($ch_req);
			        		} else {
			        			$cash[$key]->cash = "";
			        			$cash[$key]->invoice = 0;
			        		}
			        	}

			        	Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash, 'vendors'=>$vendors, 'page'=>$page));
			        } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cashout');
                    }
                } else {
                    Session::flash('message', 'Search Items Not Found!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_cashout');
                }
            } else if ($user->user_type == 2 || $user->user_type == 3) {
                if($start_date && $end_date && $p_status) {
                    $cash = Cashout::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->whereBetween('request_date', [$start_date, $end_date])->Where('paid_status', $p_status)->get();
			        if(sizeof($cash) != 0) {
			        	$outstand = AdminCommision::Where('merchant_id', $user->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
			        	
			        	if($outstand) {
			        		$cash->{'outstand'} = $outstand;
			        	} else {
			        		$cash->{'outstand'} = 0.00;
			        	}

			        	$last_request_amount = Cashout::max('request_amount');
			        	if($last_request_amount) {
			        		$cash->{'last_request_amount'} = Cashout::max('request_amount');
			        	} else {
			        		$cash->{'last_request_amount'} = "-----";
			        	}

			        	$last_request_date = Cashout::max('request_date');
			        	if($last_request_date) {
			        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
			        	} else {
			        		$cash->{'last_request_date'} = "-----";
			        	}

			        	foreach ($cash as $key => $value) {
			        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
			        		if(sizeof($ch_req) != 0) {
			        			$cash[$key]->cash = $ch_req;
			        			$cash[$key]->invoice = sizeof($ch_req);
			        		} else {
			        			$cash[$key]->cash = "";
			        			$cash[$key]->invoice = 0;
			        		}
			        	}

			        	Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash, 'page'=>$page));
			        } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cashout');
                    }
                } elseif($start_date && $end_date) {
                    $cash = Cashout::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->whereBetween('request_date', [$start_date, $end_date])->get();
			        if(sizeof($cash) != 0) {
			        	$outstand = AdminCommision::Where('merchant_id', $user->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
			        	
			        	if($outstand) {
			        		$cash->{'outstand'} = $outstand;
			        	} else {
			        		$cash->{'outstand'} = 0.00;
			        	}

			        	$last_request_amount = Cashout::max('request_amount');
			        	if($last_request_amount) {
			        		$cash->{'last_request_amount'} = Cashout::max('request_amount');
			        	} else {
			        		$cash->{'last_request_amount'} = "-----";
			        	}

			        	$last_request_date = Cashout::max('request_date');
			        	if($last_request_date) {
			        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
			        	} else {
			        		$cash->{'last_request_date'} = "-----";
			        	}

			        	foreach ($cash as $key => $value) {
			        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
			        		if(sizeof($ch_req) != 0) {
			        			$cash[$key]->cash = $ch_req;
			        			$cash[$key]->invoice = sizeof($ch_req);
			        		} else {
			        			$cash[$key]->cash = "";
			        			$cash[$key]->invoice = 0;
			        		}
			        	}

			        	Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash, 'page'=>$page));
			        } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cashout');
                    }
                } elseif($p_status) {
                    $cash = Cashout::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->Where('paid_status', $p_status)->get();
			        if(sizeof($cash) != 0) {
			        	$outstand = AdminCommision::Where('merchant_id', $user->id)->Where('paid_status', '!=', 1)->sum('merchant_amount');
			        	
			        	if($outstand) {
			        		$cash->{'outstand'} = $outstand;
			        	} else {
			        		$cash->{'outstand'} = 0.00;
			        	}

			        	$last_request_amount = Cashout::max('request_amount');
			        	if($last_request_amount) {
			        		$cash->{'last_request_amount'} = Cashout::max('request_amount');
			        	} else {
			        		$cash->{'last_request_amount'} = "-----";
			        	}

			        	$last_request_date = Cashout::max('request_date');
			        	if($last_request_date) {
			        		$cash->{'last_request_date'} = date('d-F-Y', strtotime($last_request_date));
			        	} else {
			        		$cash->{'last_request_date'} = "-----";
			        	}

			        	foreach ($cash as $key => $value) {
			        		$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
			        		if(sizeof($ch_req) != 0) {
			        			$cash[$key]->cash = $ch_req;
			        			$cash[$key]->invoice = sizeof($ch_req);
			        		} else {
			        			$cash[$key]->cash = "";
			        			$cash[$key]->invoice = 0;
			        		}
			        	}

			        	Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.cashout.manage_cashout")->with(array('cash'=>$cash, 'page'=>$page));
			        } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cashout');
                    }
                } else {
                    Session::flash('message', 'Search Items Not Found!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_cashout');
                }
            } else {
                return redirect()->route('You Are Not Access!');
            }
        } else {
            return redirect()->route('You Are Not Login!');
        }
    }

    public function ExportChoCSV( Request $request) {  
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Process Cashout')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()) {
                    $ids = $request->ids;
                    $table = array();
                    $filename = "Cashout.csv";
            		$user = session()->get('user');

                	if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            if($user) {
                                if ($user->user_type == 1) {
                                    $table = Cashout::WhereIn('id', $ids)->get();
                                } else if ($user->user_type == 2 || $user->user_type == 3) {
                                    $table = Cashout::Where('merchant_id', $user->id)->WhereIn('id', $ids)->get();
                                } else {
                                    echo $error = 1;die();
                                }
                            } else {
                                echo $error = 1;die();
                            }
                            $filename = "Cashout.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') {
                    	if($user) {
                            if ($user->user_type == 1) {
                                $table = Cashout::all();
                            } else if ($user->user_type == 2 || $user->user_type == 3) {
                                $table = Cashout::Where('merchant_id', $user->id)->get();
                            } else {
                                echo $error = 1;die();
                            }
                        } else {
                            echo $error = 1;die();
                        }
                        $filename = "AllCashout.csv";
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }
                        

                    foreach ($table as $key => $value) {
                    	$ch_req = CashoutRequest::Where('request_code', $value->id)->get();
                		if(sizeof($ch_req) != 0) {
                			$table[$key]['cash'] = $ch_req;
                			$table[$key]['invoice'] = sizeof($ch_req);
                		} else {
                			$table[$key]['cash'] = "";
                			$table[$key]['invoice'] = 0;
                		}

                        if($value->request_code) {
                            $table[$key]['request_code'] = $value->request_code;
                        } else {
                            $table[$key]['request_code'] = "---------";
                        }

                        if($value->request_amount) {
                            $table[$key]['request_amount'] = $value->request_amount;
                        } else {
                            $table[$key]['request_amount'] = 0.00;
                        }

                        if($value->amount_paid) {
                            $table[$key]['amount_paid'] = $value->amount_paid;
                        } else {
                            $table[$key]['amount_paid'] = 0.00;
                        }

                        if($value->balance) {
                            $table[$key]['balance'] = $value->balance;
                        } else {
                            $table[$key]['balance'] = 0.00;
                        }

                        if($value->request_date) {
                            $table[$key]['request_date'] = date('d-m-Y', strtotime($value->request_date));
                        } else {
                            $table[$key]['request_date'] = "---------";
                        }

                        if($value->merchant_id) {
                            $table[$key]['merchant_id'] = $value->CashMerchants->first_name.' '.$value->CashMerchants->last_name;
                        } else {
                            $table[$key]['merchant_id'] = "---------";
                        }

                        if($value->paid_status) {
                            $table[$key]['paid_status'] = $value->paid_status;
                        } else {
                            $table[$key]['paid_status'] = "---------";
                        }

                        if($value->remarks) {
                            $table[$key]['remarks'] = $value->remarks;
                        } else {
                            $table[$key]['remarks'] = "---------";
                        }
                    }
                    
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('Request Code', 'Request Amount', 'Amount Paid', 'Balance Amount', 'No.Of Invoice', 'Request Date', 'Merchant', 'Status', 'Remarks'));

                    foreach($table as $row) {
                        fputcsv($handle, array($row['request_code'], $row['request_amount'], $row['amount_paid'], $row['balance'], $row['invoice'], $row['request_date'], $row['merchant_id'], $row['paid_status'] ,$row['remarks']));
                    }

                    fclose($handle);

                    $headers = array(
                        'Content-Type' => 'text/csv',
                    );

                    // Session::flash('message', 'CSV Export Successfully!'); 
                    // Session::flash('alert-class', 'alert-success');
                    $file_path = $filename;
                    return $file_path;
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
