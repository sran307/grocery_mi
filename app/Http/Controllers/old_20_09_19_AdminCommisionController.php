<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class AdminCommisionController extends Controller
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
                ->where('B.module_name', '=', 'Admin Commision')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Accounts";
            	$user = session()->get('user');
                $vendors = User::WhereIn('user_type', ['2','3'])->Where('is_block', 1)->get();
                $admin_amount = 0;
                $vendor_amount = 0;

            	if($user) {
            		if ($user->user_type == 1) {
        		        $comis = AdminCommision::OrderBy('id', 'DESC')->paginate(10);
                        if(sizeof($comis) != 0) {
                            $admin_amount = AdminCommision::sum('amount');
                            $vendor_amount = AdminCommision::sum('merchant_amount');
                        }

        		    	return View::make("user.manage_admin_comis")->with(array('comis'=>$comis,'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
            		} else if ($user->user_type == 2 || $user->user_type == 3) {
            			$comis = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->paginate(10);
                        if(sizeof($comis) != 0) {
                            $admin_amount = AdminCommision::Where('merchant_id', $user->id)->sum('amount');
                            $vendor_amount = AdminCommision::Where('merchant_id', $user->id)->sum('merchant_amount');
                        }
            			return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
            		} else {
            			return redirect()->route('admin');
            		}
            	} else {
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

    public function OrderByAdminCom () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Order By Admin Commision')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Accounts";
                $user = session()->get('user');
                if($user) {
                    if ($user->user_type == 1) {
                        $comis = AdminCommision::groupBy('order_code')->selectRaw('*, sum(`amount`) as `comis`')->OrderBy('id', 'DESC')->paginate(10);
                        return View::make("user.orderby_admin_comis")->with(array('comis'=>$comis, 'page'=>$page));
                    } else if ($user->user_type == 2 || $user->user_type == 3) {
                        $comis = AdminCommision::groupBy('order_code', 'merchant_id')->selectRaw('*, sum(`amount`) as `comis`')->Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->paginate(10);
                        return View::make("user.orderby_admin_comis")->with(array('comis'=>$comis, 'page'=>$page));
                    } else {
                        return redirect()->route('admin');
                    }
                } else {
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

    public function StatusComis (Request $request) {
    	$id = 0;
		$pay = 0;
		$data = "";

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Commision')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id) && isset($request->pay)){
        			$id = $request->id;
        			$pay = $request->pay;

        			if($id != 0) {
        				$comis = AdminCommision::where('id',$id)->first();
        				if($comis) {
        					$comis->paid_status = $pay;
        					if ($comis->save()) {
        						$data = 0;
        					}
                        } 			
        			}
        		}
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $data = "";
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $data = "";
        }

		echo $data;
    }

    public function RemarkComis (Request $request) {
        $id = 0;
        $remark = 0;
        $data = "";

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Commision')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->id) && isset($request->remark)){
                    $id = $request->id;
                    $remark = $request->remark;

                    if($id != 0) {
                        $comis = AdminCommision::where('id',$id)->first();
                        if($comis) {
                            $comis->remarks = $remark;
                            if ($comis->save()) {
                                $data = 0;
                                Session::flash('message', 'Successfully Remarked!'); 
                                Session::flash('alert-class', 'alert-success');
                            }
                        }           
                    }
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                // return redirect()->back();
                $data = "";
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            $data = "";
        }

        echo $data;
    }

    public function SearchComis (Request $request) {
        $page = "Accounts";
        $user = session()->get('user');
        $admin_amount = 0;
        $vendor_amount = 0;
        $vendors = User::WhereIn('user_type', ['2','3'])->Where('is_block', 1)->get();

        $start_date = Input::get('gj_srh_srt_date');
        if($start_date) {
            $start_date = date('Y-m-d', strtotime($start_date));
        }

        $end_date = Input::get('gj_srh_end_date');
        if($end_date) {
            $end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
        }

        $order_code = Input::get('gj_srh_odr_code');
        $p_status = Input::get('gj_srh_p_sts');
        $merchants = Input::get('gj_srh_vendor');

        if($user) {
            if ($user->user_type == 1) {
                if($start_date && $end_date && $order_code) {
                    $comis = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('order_code', 'like', '%' . $order_code . '%')->paginate(10);
                    $admin_amount = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('order_code', 'like', '%' . $order_code . '%')->sum('amount');
                    $vendor_amount = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('order_code', 'like', '%' . $order_code . '%')->sum('merchant_amount');
                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } elseif($start_date && $end_date && $p_status) {
                    if($p_status == "paid") {
                        $p_status = 1;
                    } else if ($p_status == "unpaid") {
                        $p_status = 0;
                    } else {
                        $p_status = 2;
                    }

                    $comis = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('paid_status', $p_status)->paginate(10);
                    $admin_amount = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('paid_status', $p_status)->sum('amount');
                    $vendor_amount = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('paid_status', $p_status)->sum('merchant_amount');
                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } elseif($start_date && $end_date && $merchants) {
                    $comis = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('merchant_id', $merchants)->paginate(10);
                    $admin_amount = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('merchant_id', $merchants)->sum('amount');
                    $vendor_amount = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('merchant_id', $merchants)->sum('merchant_amount');
                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } elseif($start_date && $end_date) {
                    $comis = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->paginate(10);
                    $admin_amount = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
                    $vendor_amount = AdminCommision::OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->sum('merchant_amount');
                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } elseif($order_code) {
                    $comis = AdminCommision::OrderBy('id', 'DESC')->Where('order_code', 'like', '%' . $order_code . '%')->paginate(10);
                    $admin_amount = AdminCommision::OrderBy('id', 'DESC')->Where('order_code', 'like', '%' . $order_code . '%')->sum('amount');
                    $vendor_amount = AdminCommision::OrderBy('id', 'DESC')->Where('order_code', 'like', '%' . $order_code . '%')->sum('merchant_amount');
                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } elseif($p_status) {
                    if($p_status == "paid") {
                        $p_status = 1;
                    } else if ($p_status == "unpaid") {
                        $p_status = 0;
                    } else {
                        $p_status = 2;
                    }

                    $comis = AdminCommision::OrderBy('id', 'DESC')->Where('paid_status', $p_status)->paginate(10);
                    $admin_amount = AdminCommision::OrderBy('id', 'DESC')->Where('paid_status', $p_status)->sum('amount');
                    $vendor_amount = AdminCommision::OrderBy('id', 'DESC')->Where('paid_status', $p_status)->sum('merchant_amount');
                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } elseif($merchants) {
                    $comis = AdminCommision::OrderBy('id', 'DESC')->Where('merchant_id', $merchants)->paginate(10);
                    $admin_amount = AdminCommision::OrderBy('id', 'DESC')->Where('merchant_id', $merchants)->sum('amount');
                    $vendor_amount = AdminCommision::OrderBy('id', 'DESC')->Where('merchant_id', $merchants)->sum('merchant_amount');
                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Searched Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } else {
                    Session::flash('message', 'Search Items Not Found!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_admin_comis');
                }
            } else if ($user->user_type == 2 || $user->user_type == 3) {
                if($start_date && $end_date && $order_code) {
                    $comis = AdminCommision::Where('merchant_id', $user->id)->whereBetween('created_at', [$start_date, $end_date])->Where('order_code', 'like', '%' . $order_code . '%')->OrderBy('id', 'DESC')->paginate(10);
                    $admin_amount = AdminCommision::Where('merchant_id', $user->id)->whereBetween('created_at', [$start_date, $end_date])->Where('order_code', 'like', '%' . $order_code . '%')->sum('amount');
                    $vendor_amount = AdminCommision::Where('merchant_id', $user->id)->whereBetween('created_at', [$start_date, $end_date])->Where('order_code', 'like', '%' . $order_code . '%')->sum('merchant_amount');
                    
                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } elseif($start_date && $end_date && $p_status) {
                    if($p_status == "paid") {
                        $p_status = 1;
                    } else if ($p_status == "unpaid") {
                        $p_status = 0;
                    } else {
                        $p_status = 2;
                    }

                    $comis = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('paid_status', $p_status)->paginate(10);
                    $admin_amount = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('paid_status', $p_status)->sum('amount');
                    $vendor_amount = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->Where('paid_status', $p_status)->sum('merchant_amount');

                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } elseif($start_date && $end_date) {
                    $comis = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->paginate(10);
                    $admin_amount = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
                    $vendor_amount = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->whereBetween('created_at', [$start_date, $end_date])->sum('merchant_amount');

                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } elseif($order_code) {
                    $comis = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->Where('order_code', 'like', '%' . $order_code . '%')->paginate(10);
                    $admin_amount = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->Where('order_code', 'like', '%' . $order_code . '%')->sum('amount');
                    $vendor_amount = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->Where('order_code', 'like', '%' . $order_code . '%')->sum('merchant_amount');

                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } elseif($p_status) {
                    if($p_status == "paid") {
                        $p_status = 1;
                    } else if ($p_status == "unpaid") {
                        $p_status = 0;
                    } else {
                        $p_status = 2;
                    }

                    $comis = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->Where('paid_status', $p_status)->paginate(10);
                    $admin_amount = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->Where('paid_status', $p_status)->sum('amount');
                    $vendor_amount = AdminCommision::Where('merchant_id', $user->id)->OrderBy('id', 'DESC')->Where('paid_status', $p_status)->sum('merchant_amount');
                    
                    if($comis && sizeof($comis) != 0) {
                        Session::flash('message', 'Search Items Founded!'); 
                        Session::flash('alert-class', 'alert-success');
                        return View::make("user.manage_admin_comis")->with(array('comis'=>$comis, 'vendors'=>$vendors, 'admin_amount'=>$admin_amount, 'vendor_amount'=>$vendor_amount, 'page'=>$page));
                    } else {
                        Session::flash('message', 'Search Items Not Found!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_admin_comis');
                    }
                } else {
                    Session::flash('message', 'Searched Items Not Found!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_admin_comis');
                }
            } else {
                return redirect()->route('admin');
            }
        } else {
            return redirect()->route('admin');
        }
    }

    public function ExportComCSV( Request $request) {  
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Commision')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()) {
                    $ids = $request->ids;
                    $table = array();
                    $filename = "AdminCommision.csv";
                    $user = session()->get('user');

                    if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            if($user) {
                                if ($user->user_type == 1) {
                                    $table = AdminCommision::WhereIn('id', $ids)->get();
                                } else if ($user->user_type == 2 || $user->user_type == 3) {
                                    $table = AdminCommision::Where('merchant_id', $user->id)->WhereIn('id', $ids)->get();
                                } else {
                                    echo $error = 1;die();
                                }
                            } else {
                                echo $error = 1;die();
                            }
                            $filename = "AdminCommision.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') {
                        if($user) {
                            if ($user->user_type == 1) {
                                $table = AdminCommision::all();
                            } else if ($user->user_type == 2 || $user->user_type == 3) {
                                $table = AdminCommision::Where('merchant_id', $user->id)->get();
                            } else {
                                echo $error = 1;die();
                            }
                        } else {
                            echo $error = 1;die();
                        }
                        $filename = "AllAdminCommision.csv";
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }
                    
                    foreach ($table as $key => $value) {
                        if($value->order_code) {
                            $table[$key]['order_code'] = $value->order_code;
                        } else {
                            $table[$key]['order_code'] = "---------";
                        }

                        if($value->product_id) {
                            $table[$key]['product'] = $value->ComisProducts->product_title;
                        } else {
                            $table[$key]['product'] = "---------";
                        }

                        if($value->merchant_id) {
                            $table[$key]['merchant'] = $value->ComisMerchant->first_name.' '.$value->ComisMerchant->last_name;
                        } else {
                            $table[$key]['merchant'] = "---------";
                        }

                        if($value->order_dets) {
                            $table[$key]['order_qty'] = $value->ComisOdrDets->order_qty;
                        } else {
                            $table[$key]['order_qty'] = "---------";
                        }

                        if($value->order_dets) {
                            $table[$key]['totalprice'] = $value->ComisOdrDets->totalprice;
                        } else {
                            $table[$key]['totalprice'] = "---------";
                        }

                        if($value->amount) {
                            $table[$key]['amount'] = $value->amount;
                        } else {
                            $table[$key]['amount'] = 0.00;
                        }

                        if($value->order_dets) {
                            $table[$key]['vendor_amt'] = round($value->ComisOdrDets->totalprice - $value->amount ,2);
                        } else {
                            $table[$key]['vendor_amt'] = "---------";
                        }

                        if($value->created_at) {
                            $table[$key]['date'] = date('d-m-Y', strtotime($value->created_at));
                        } else {
                            $table[$key]['date'] = "---------";
                        }

                        if($value->paid_status == 0) {
                            $table[$key]['paid_status'] = "Un Paid";
                        } elseif ($value->paid_status == 1) {
                            $table[$key]['paid_status'] = "Paid";
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
                    fputcsv($handle, array('Order Code', 'Product', 'Merchant', 'Quantity', 'Net Amount', 'Commision Amount', 'Merchant Amount', 'Date', 'Status', 'Remarks'));

                    foreach($table as $row) {
                        fputcsv($handle, array($row['order_code'], $row['product'], $row['merchant'], $row['order_qty'], $row['totalprice'], $row['amount'], $row['vendor_amt'], $row['date'], $row['paid_status'] ,$row['remarks']));
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
                echo $data = '';die();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back();
            echo $data = '';die();
        }
    }
}
