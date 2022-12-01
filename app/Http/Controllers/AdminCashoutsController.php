<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdminCashouts;
use App\CreditsNotes;
use App\Cashout;
use App\CashoutRequest;
use App\CashoutPayment;
use App\BankDetails;
use App\AdminCommision;
use App\OrdersTransactions;
use App\Orders;
use App\OrderDetails;
use App\GrvOrders;
use App\GrvOrdersDetails;
use App\User;
use App\Products;
use App\CreditsManagement;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class AdminCashoutsController extends Controller
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
                ->where('B.module_name', '=', 'Admin Cashouts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Accounts";
                $log = session()->get('user');
                $vendors = User::WhereIn('user_type', ['2','3'])->Where('is_block', 1)->get();
                if($log) {
                    if ($log->user_type == 1) {
                        $cashout = AdminCashouts::OrderBy('id', 'DESC')->get();

                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'vendors'=>$vendors, 'page'=>$page));
                    } else if ($log->user_type == 2 || $log->user_type == 3) {
                        $cashout = AdminCashouts::Where('vendor', $log->id)->OrderBy('id', 'DESC')->get();
                        
                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'page'=>$page));
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

    public function SelectVendor( Request $request) {  
        $error = 1;
        $id = 0;
        if($request->ajax() && isset($request->id)) {
            $id = $request->id;
            $vendor = User::Where('id', $id)->first();

            if($vendor) {
                $credits = CreditsManagement::OrderBy('id', 'desc')->Where('merchant_id', $vendor->id)->first();
                if($credits && $credits->current_credits) {
                    $credit = $credits->current_credits;
                } else {
                    $credit = 0;
                }

                $error = '<div class="gj_v_dets">
                <p>Vendor Name : '.$vendor->first_name.' '.$vendor->last_name.'</p>
                <p>Vendor EMail : '.$vendor->email.'</p>
                <p>Vendor Phone : '.$vendor->phone.'</p>
                <p>Current Credit : Rs.'.$credit.'</p>
                </div>';
            }
        }

        echo $error;
    }

    public function SelectCreditNote( Request $request) {  
        $error = 1;
        $id = 0;
        $co_id = [];
        $items = 0;
        if($request->ajax() && isset($request->id)) {
            $id = $request->id;
            $credit_notes = CreditsNotes::Where('id', $id)->first();

            if($credit_notes) {
                if($credit_notes->grv_id) {
                    $grv = GrvOrders::Where('id', $credit_notes->grv_id)->first();
                    if($grv) {
                        $o_grv_details = GrvOrdersDetails::Where('grv_id', $grv->id)->get();
                        if(sizeof($o_grv_details) != 0) {
                            foreach ($o_grv_details as $key => $value) {
                                if($value->return_type == "Refund") {
                                    array_push($co_id, $value->id);
                                }
                            }

                            if (sizeof($co_id) != 0) {
                                $grv_details = GrvOrdersDetails::WhereIn('id', $co_id)->get();
                                $items = count($grv_details);
                            }
                        }
                    }


                    if($credit_notes->GRV->Orders->order_code) {
                        $order_code = $credit_notes->GRV->Orders->order_code;
                    } else {
                        $order_code = "------";
                    }
                } else {
                    $order_code = "------";
                }
                
                $error = '<div class="gj_cn_dets">
                <p>Order Code : '.$order_code.'</p>
                <p>Amount : Rs.'.$credit_notes->amount.'</p>
                <p>Items : '.$items.'</p>
                <p>Date : '.date('d-m-Y', strtotime($credit_notes->date)).'</p>
                </div>';
            }
        }

        echo $error;
    }

    public function create () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Cashouts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Accounts";
                $admin_amount = 0;
                $vendor_amount = 0;
                $log = session()->get('user');
                if($log) {
                    if ($log->user_type == 1) {
                        $vendors = User::WhereIn('user_type', ['2','3'])->Where('is_block', 1)->get();
                        $notes = CreditsNotes::Where('is_paid', '!=', "Paid")->get();
                        $order = Orders::WhereNotIn('order_status', ['4','5'])->WhereNull('ref_order_id')->get();

                        return View::make("accounts.admin_cashout.add_admin_cashout")->with(array('vendors'=>$vendors, 'notes'=>$notes, 'order'=>$order, 'page'=>$page));
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
        $data= Input::all();
        $log = session()->get('user');

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Cashouts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                if($log) {
                    if ($log->user_type == 1) {
                        $rules = array(
                            'vendor'        => 'required|exists:users,id',
                            'process_type'  => 'required',
                            'amount'        => 'required|numeric',
                            'credit_note'   => 'required|exists:credits_notes,id',
                            'reasons'       => 'required',
                            'others'        => 'nullable',
                            'remarks'       => 'nullable',
                        );

                        $messages=[
                            'remarks.required'=>'The remarks field is required.',
                        ];
                        $validator = Validator::make($data, $rules,$messages);

                        if ($validator->fails()) {
                            return Redirect::to('/add_admin_cashout/')->withErrors($validator);
                        } else {
                            $cash = new AdminCashouts();

                            if($cash) {
                                $cash->vendor         = $data['vendor'];  
                                $cash->process_type   = $data['process_type'];    
                                $cash->amount         = $data['amount'];    
                                $cash->credit_note    = $data['credit_note'];  
                                $cash->reasons        = $data['reasons'];  
                                $cash->others         = $data['others'];  
                                $cash->remarks        = $data['remarks'];   
                                
                                if($cash->save()) {
                                    $crts = 0;
                                    $last_cr_man = CreditsManagement::Where('merchant_id', $data['vendor'])->OrderBy('id', "desc")->first();
                                    if($last_cr_man && $last_cr_man->current_credits) {
                                        $crts = $last_cr_man->current_credits;
                                    }

                                    $cr_man = new CreditsManagement();
                                    $cr_man->merchant_id       = $data['vendor'];  
                                    $cr_man->previous_credits  = $crts; 

                                    if($data['process_type'] == "Deduction") {
                                        $cr_man->current_credits   = $crts - $data['amount'];
                                    }  else {
                                        $cr_man->current_credits   = $crts + $data['amount'];
                                    }

                                    $cr_man->add_credits       = $data['amount'];  
                                    $cr_man->remarks           = $data['process_type'];  
                                    
                                    if($cr_man->save()) {
                                        $mers = User::Where('id', $cr_man->merchant_id)->first();
                                        if($mers) {
                                            $mers->credits = $cr_man->current_credits;
                                            if($mers->save()) {
                                                Session::flash('message', 'Successfully Add!'); 
                                                Session::flash('alert-class', 'alert-success');
                                                return redirect()->route('manage_admin_cashout');
                                            } else {
                                                if($cash->delete()) {
                                                    Session::flash('message', 'Added Failed!');
                                                } else {
                                                    Session::flash('message', 'Added Failed, Try Again Later!'); 
                                                }

                                                Session::flash('alert-class', 'alert-success');
                                                return redirect()->route('manage_admin_cashout');
                                            }
                                        } else {
                                            if($cash->delete()) {
                                                Session::flash('message', 'Added Failed!');
                                            } else {
                                                Session::flash('message', 'Added Failed, Try Again Later!'); 
                                            }

                                            Session::flash('alert-class', 'alert-success');
                                            return redirect()->route('manage_admin_cashout');
                                        }
                                    } else {
                                        if($cash->delete()) {
                                            Session::flash('message', 'Added Failed!');
                                        } else {
                                            Session::flash('message', 'Added Failed, Try Again Later!'); 
                                        }

                                        Session::flash('alert-class', 'alert-success');
                                        return redirect()->route('manage_admin_cashout');
                                    }
                                } else{
                                    Session::flash('message', 'Added Failed!');
                                    Session::flash('alert-class', 'alert-success');
                                    return redirect()->route('manage_admin_cashout');
                                }  
                            } else{
                                Session::flash('message', 'Added Not Posssible!');
                                Session::flash('alert-class', 'alert-success');
                                return redirect()->route('manage_admin_cashout');
                            }
                        }
                    } else {
                        Session::flash('message', 'You Are Not Access to Add!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } else {
                    Session::flash('message', 'You Are Not Login!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_admin_cashout');
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('manage_admin_cashout');
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_admin_cashout');
        }   
    }

    public function view ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Cashouts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
                $page = "Accounts";
                $log = session()->get('user');
                if($log) {
                    if ($log->user_type == 1) {
                        $cashout = AdminCashouts::Where('id', $id)->first();
                        if($cashout) {
                            $credits = CreditsManagement::OrderBy('id', "desc")->Where('merchant_id', $cashout->vendor)->first();
                            return View::make("accounts.admin_cashout.view_admin_cashout")->with(array('cashout'=>$cashout, 'credits'=>$credits, 'page'=>$page));
                        } else {
                            Session::flash('message', 'View Not Availible!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_admin_cashout');
                        }
                    } else if ($log->user_type == 2 || $log->user_type == 3) {
                        $cashout = AdminCashouts::Where('vendor', $log->id)->Where('id', $id)->first();
                        if($cashout) {
                            $credits = CreditsManagement::OrderBy('id', "desc")->Where('merchant_id', $cashout->vendor)->first();
                            return View::make("accounts.admin_cashout.view_admin_cashout")->with(array('cashout'=>$cashout, 'credits'=>$credits, 'page'=>$page));
                        } else {
                            Session::flash('message', 'View Not Availible!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_admin_cashout');
                        }
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

    public function SearchAdminCashout (Request $request) {
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
                    $cashout = AdminCashouts::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('process_type', $p_status)->Where('vendor', $vendor)->get();
                    if(sizeof($cashout) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'vendors'=>$vendors, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } else if($start_date && $end_date && $p_status) {
                    $cashout = AdminCashouts::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('process_type', $p_status)->get();
                    if(sizeof($cashout) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'vendors'=>$vendors, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } else if($start_date && $end_date && $vendor) {
                    $cashout = AdminCashouts::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('vendor', $vendor)->get();
                    if(sizeof($cashout) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'vendors'=>$vendors, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } elseif($start_date && $end_date) {
                    $cashout = AdminCashouts::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->get();
                    if(sizeof($cashout) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'vendors'=>$vendors, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } elseif($p_status) {
                    $cashout = AdminCashouts::OrderBy('id', 'DESC')->Where('process_type', $p_status)->get();
                    if(sizeof($cashout) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'vendors'=>$vendors, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } elseif($vendor) {
                    $cashout = AdminCashouts::OrderBy('id', 'DESC')->Where('vendor', $vendor)->get();
                    if(sizeof($cashout) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'vendors'=>$vendors, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } else {
                    Session::flash('message', 'Search Items Not Found!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_admin_cashout');
                }
            } else if ($user->user_type == 2 || $user->user_type == 3) {
                if($start_date && $end_date && $p_status) {
                    $cashout = AdminCashouts::Where('vendor', $user->id)->OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('process_type', $p_status)->get();
                    if(sizeof($cashout) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } elseif($start_date && $end_date) {
                    $cashout = AdminCashouts::Where('vendor', $user->id)->OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->get();
                    if(sizeof($cashout) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } elseif($p_status) {
                    $cashout = AdminCashouts::Where('vendor', $user->id)->OrderBy('id', 'DESC')->Where('process_type', $p_status)->get();
                    if(sizeof($cashout) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("accounts.admin_cashout.manage_admin_cashout")->with(array('cashout'=>$cashout, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } else {
                    Session::flash('message', 'Search Items Not Found!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_admin_cashout');
                }
            } else {
                Session::flash('message', 'You Are Not Properly Login!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('admin');
            }
        } else {
            Session::flash('message', 'You Are Not Properly Login!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('admin');
        }
    }    

    public function ExportACCSV( Request $request) {  
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Cashouts')
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
                                    $table = AdminCashouts::WhereIn('id', $ids)->get();
                                } else if ($user->user_type == 2 || $user->user_type == 3) {
                                    $table = AdminCashouts::Where('vendor', $user->id)->WhereIn('id', $ids)->get();
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
                                $table = AdminCashouts::all();
                            } else if ($user->user_type == 2 || $user->user_type == 3) {
                                $table = AdminCashouts::Where('vendor', $user->id)->get();
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
                        $credits = CreditsManagement::OrderBy('id', "desc")->Where('merchant_id', $value->vendor)->first();

                        if($value->vendor) {
                            if(isset($value->Vendors->first_name)) {
                                $table[$key]['vendor'] = $value->Vendors->first_name.' '.$value->Vendors->last_name;
                            } else {
                                $table[$key]['vendor'] = "---------";
                            }
                        } else {
                            $table[$key]['vendor'] = "---------";
                        }

                        if($value->credit_note) {
                            if(isset($value->CNotes->cn_code)) {
                                $table[$key]['credit_note'] = $value->CNotes->cn_code;
                            } else {
                                $table[$key]['credit_note'] = "---------";
                            }
                        } else {
                            $table[$key]['credit_note'] = "---------";
                        }

                        if($value->amount) {
                            $table[$key]['amount'] = 'Rs. '.$value->amount;
                        } else {
                            $table[$key]['amount'] = 'Rs. 0';
                        }

                        

                        if($value->created_at) {
                            $table[$key]['date'] = date('d-m-Y', strtotime($value->created_at));
                        } else {
                            $table[$key]['date'] = "---------";
                        }

                        if($credits){
                            if($credits->previous_credits) {
                                $table[$key]['previous_credits'] = 'Rs. '.$credits->previous_credits;
                            } else {
                                $table[$key]['previous_credits'] = "Rs. 0";
                            }

                            if($credits->add_credits) {
                                $table[$key]['add_credits'] = 'Rs. '.$credits->add_credits;
                            } else {
                                $table[$key]['add_credits'] = "Rs. 0";
                            }

                            if($credits->current_credits) {
                                $table[$key]['current_credits'] = 'Rs. '.$credits->current_credits;
                            } else {
                                $table[$key]['current_credits'] = "Rs. 0";
                            }

                            if($credits->remarks) {
                                $table[$key]['cr_remarks'] = $credits->remarks;
                            } else {
                                $table[$key]['cr_remarks'] = "---------";
                            }
                        }
                    }
                    
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('Vendor', 'Process Type', 'Amount', 'Credits Notes', 'Reasons', 'Others', 'Remarks', 'Vendor Remarks', 'Created Date', 'Vendor Previous Credits', 'Vendor Add / Deduct on Credits', 'Vendor Current Credits', 'Vendor Credits Remarks'));

                    foreach($table as $row) {
                        fputcsv($handle, array($row['vendor'], $row['process_type'], $row['amount'], $row['credit_note'], $row['reasons'], $row['others'], $row['remarks'], $row['vendor_remarks'], $row['date'], $row['previous_credits'], $row['add_credits'], $row['current_credits'], $row['cr_remarks']));
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

    public function remark ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Cashouts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                $page = "Accounts";
                $admin_amount = 0;
                $vendor_amount = 0;
                $log = session()->get('user');
                if($log) {
                    if ($log->user_type == 2 || $log->user_type == 3) {
                        $cashout = AdminCashouts::Where('vendor', $log->id)->Where('id', $id)->first();
                        if($cashout) {
                            return View::make("accounts.admin_cashout.remark_admin_cashout")->with(array('cashout'=>$cashout, 'page'=>$page));
                        } else {
                            Session::flash('message', 'You have Not Access to Remarked!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_admin_cashout');
                        }
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

    public function storeremark(Request $request) {
        $page = "Accounts";
        $log = session()->get('user');
        $error = array();
        $data= Input::all();
        $log = session()->get('user');

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Cashouts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($log) {
                    if ($log->user_type == 2 || $log->user_type == 3) {
                        $rules = array(
                            'cashout_id'      => 'required|exists:admin_cashouts,id',
                            'vendor_remarks'  => 'required',
                        );

                        $messages=[
                            'remarks.required'=>'The remarks field is required.',
                        ];
                        $validator = Validator::make($data, $rules,$messages);

                        if ($validator->fails()) {
                            return Redirect::to('/remark_admin_cashout/'. $data['cashout_id'])->withErrors($validator);
                        } else {
                            $cashout = AdminCashouts::Where('vendor', $log->id)->Where('id', $data['cashout_id'])->first();

                            if($cashout) {
                                $cashout->vendor_remarks  = $data['vendor_remarks'];  
                                
                                if($cashout->save()) {
                                    Session::flash('message', 'Remarked Successfully!');
                                    Session::flash('alert-class', 'alert-success');
                                    return redirect()->route('manage_admin_cashout');
                                } else{
                                    Session::flash('message', 'Remarked Failed!');
                                    Session::flash('alert-class', 'alert-danger');
                                    return redirect()->route('manage_admin_cashout');
                                }  
                            } else{
                                Session::flash('message', 'Remarked Not Posssible!');
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('manage_admin_cashout');
                            }
                        }
                    } else {
                        Session::flash('message', 'You Are Not Access to Remarked!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_cashout');
                    }
                } else {
                    Session::flash('message', 'You Are Not Login!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_admin_cashout');
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('manage_admin_cashout');
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_admin_cashout');
        }   
    }
}
