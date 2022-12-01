<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BankDetails;
use App\User;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;
use Carbon\Carbon;

class BankDetailsController extends Controller
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
                ->where('B.module_name', '=', 'Bank Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Accounts";
            	$log = session()->get('user');
            	if($log) {
        	    	if($log->user_type == 3 || $log->user_type == 2) {
        				$banks = BankDetails::all();
        				if(sizeof($banks) == 1) {
        					$bank = BankDetails::Where('id', $banks[0]->id)->first();
        					if($bank) {
        						$bank->default = 1;
        						$bank->save();
        						$banks = BankDetails::all();
        					}
        				}
            			return View::make("accounts.bank.bank_details")->with(array('banks'=>$banks, 'page'=>$page));
        			} else {
        				Session::flash('message', 'You Are Not Permission to Add!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_credits');
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
                ->where('B.module_name', '=', 'Bank Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Accounts";
            	$log = session()->get('user');
            	if($log) {
        	    	if($log->user_type == 3 || $log->user_type == 2) {
        				return View::make('accounts.bank.add_bank_details')->with(array('page'=>$page));
        			} else {
        				Session::flash('message', 'You Are Not Permission to Add!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_credits');
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
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Bank Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Accounts";
            	$log = session()->get('user');
            	$data = Input::all();

            	if($log) {
        	    	if($log->user_type == 3 || $log->user_type == 2) {
        				$rules = array(
        		            'merchant_id' => 'nullable',
        		            'ac_no'       => 'required|numeric',
        		            'ac_name'     => 'required',
                            'ac_type'     => 'required',
        		            'bank_name'   => 'required',
        		            'bank_branch' => 'required',
        		            'bank_ifsc'   => 'required',
        		            'default'     => 'nullable',
        		            'remarks'     => 'nullable',
        		        );

        		        $messages=[
        		            'ac_no.required'=>'The A/C No field is required.',
        		            'ac_no.numeric'=>'The A/C No field is numbers only!',
        		            'ac_name.required'=>'The A/C Holder Name field is required.',
                            'ac_type.required'=>'The A/C Type field is required.',
        		        ];
        		        $validator = Validator::make(Input::all(), $rules,$messages);

        		        if ($validator->fails()) {
        		        	return redirect()->route('add_bank_details')->withErrors($validator);
        		        } else {
        		        	$banks = new BankDetails();

        		            if($banks) {
        			            $banks->merchant_id = $log->id;	 
        			            $banks->ac_no       = $data['ac_no'];	 
        			            $banks->ac_name     = $data['ac_name'];	 
                                $banks->ac_name     = $data['ac_name'];  
                                $banks->ac_type     = $data['ac_type'];  
        			            $banks->bank_name   = $data['bank_name'];	 
        			            $banks->bank_branch = $data['bank_branch'];	 
        			            $banks->bank_ifsc   = $data['bank_ifsc'];	 
        			            $banks->default     = 0;	 
        			            $banks->remarks     = $data['remarks'];
        		                
        		                if($banks->save()) {
                        			Session::flash('message', 'Added Successfully!'); 
        							Session::flash('alert-class', 'alert-success');
        							return redirect()->route('bank_details');
        			            } else{
        			            	Session::flash('message', 'Added Failed!'); 
        							Session::flash('alert-class', 'alert-danger');
        			                return redirect()->route('bank_details');
        			            }  
        		            } else{
        		            	Session::flash('message', 'Added Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('bank_details');
        		            }
        		        }
        			} else {
        				Session::flash('message', 'You Are Not Permission to Add!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_credits');
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
                ->where('B.module_name', '=', 'Bank Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
        		$page = "Accounts";
            	$log = session()->get('user');
            	if($log) {
        	    	if($log->user_type == 3 || $log->user_type == 2) {
        	    		$banks = BankDetails::Where('id', $id)->first();
        				return View::make('accounts.bank.view_bank_details')->with(array('banks'=>$banks, 'page'=>$page));
        			} else {
        				Session::flash('message', 'You Are Not Permission to View!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('bank_details');
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

    public function edit ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Bank Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Accounts";
                $log = session()->get('user');
                if($log) {
                    if($log->user_type == 3 || $log->user_type == 2) {
                        $banks = BankDetails::Where('id', $id)->first();
                        return View::make('accounts.bank.edit_bank_details')->with(array('banks'=>$banks, 'page'=>$page));
                    } else {
                        Session::flash('message', 'You Are Not Permission to Edit!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('bank_details');
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

	public function update (Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Bank Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Accounts";
            	$log = session()->get('user');
            	$data = Input::all();

            	if($log) {
        	    	if($log->user_type == 3 || $log->user_type == 2) {
        				$id = Input::get('banks_id');
        		    	$banks = '';
        		        if($id != '') {
        		        	$banks = BankDetails::Where('id', $id)->first();
        		        }

        		        if($banks) {
        					$rules = array(
        			            'merchant_id' => 'nullable',
        			            'ac_no'       => 'required|numeric',
        			            'ac_name'     => 'required',
                                'ac_type'     => 'required',
        			            'bank_name'   => 'required',
        			            'bank_branch' => 'required',
        			            'bank_ifsc'   => 'required',
        			            'default'     => 'nullable',
        			            'remarks'     => 'nullable',
        			        );
        			        $validator = Validator::make(Input::all(), $rules);

        			        if ($validator->fails()) {
        			    	   	return Redirect::to('/edit_bank_details/' . $id)->withErrors($validator)->with(array('banks'=>$banks, 'page'=>$page));
        			        } else {
        			            $data = Input::all();

        			            $banks->merchant_id = $log->id;	 
        			            $banks->ac_no       = $data['ac_no'];	 
        			            $banks->ac_name     = $data['ac_name'];	 
                                $banks->ac_type     = $data['ac_type'];  
        			            $banks->bank_name   = $data['bank_name'];	 
        			            $banks->bank_branch = $data['bank_branch'];	 
        			            $banks->bank_ifsc   = $data['bank_ifsc'];	 
        			            $banks->default     = $data['default'];	 
        			            $banks->remarks     = $data['remarks'];

        		                if($banks->save()) {
        			            	Session::flash('message', 'update Successfully!'); 
        							Session::flash('alert-class', 'alert-success');
        							return redirect()->route('bank_details');

        			            } else{
        			            	Session::flash('message', 'update Failed!'); 
        							Session::flash('alert-class', 'alert-danger');
        			                return redirect()->route('bank_details');
        			            }   
        			        }
        		        } else{
        		        	Session::flash('message', 'update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        		            return redirect()->route('bank_details');
        		        }
        	        } else {
        				Session::flash('message', 'You Are Not Permission to Add!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_credits');
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
                ->where('B.module_name', '=', 'Bank Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($log) {
        	    	if($log->user_type == 3 || $log->user_type == 2) {
        				$id = 0;
        				if($request->ajax() && isset($request->id)) {
        					$id = $request->id;
        					if($id != 0) {
        						$banks = BankDetails::where('id',$id)->first();
        						if($banks){
        							$bank = BankDetails::first();
        							if($bank && $banks->default == 1) {
        								$bank->default = 1;
        								$bank->save();
        							}

        							if($banks->delete()) {
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
                ->where('B.module_name', '=', 'Bank Details')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($log) {
        	    	if($log->user_type == 3 || $log->user_type == 2) {
        				if($request->ajax() && isset($request->ids)){
        					$ids = $request->ids;
        					$error = 1;
        					if(sizeof($ids) != 0) {
        						foreach ($ids as $key => $value) {
        							$banks = BankDetails::where('id',$value)->first();
        							if($banks) {
        								$bank = BankDetails::first();
        								if($bank && $banks->default == 1) {
        									$bank->default = 1;
        									$bank->save();
        								}

        								if($banks->delete()) {
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

    public function BankDefault (Request $request) {
    	$error = 1;	
		$log = session()->get('user');

		if($log) {
	    	if($log->user_type == 3 || $log->user_type == 2) {
				if($request->ajax() && isset($request->id)){
					$id = $request->id;
					$bank = BankDetails::where('id',$id)->first();
					$banks = BankDetails::all();
					if(($banks) && (count($banks) != 0)) {
						foreach ($banks as $key => $value) {
							$st = BankDetails::where('id',$value->id)->first();
							$st->default = 0;
							$st->save();					
						}
						
						if($bank){
							$bank->default = 1;
							$bank->save();
							Session::flash('message', 'Update Default Bank Successfully!'); 
							Session::flash('alert-class', 'alert-success');
							$error = 0;
						}	else {
							Session::flash('message', 'Update Default Bank Failed!'); 
							Session::flash('alert-class', 'alert-danger');
						}
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

		echo $error;	
	}
}
