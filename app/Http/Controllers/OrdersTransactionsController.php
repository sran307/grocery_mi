<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrdersTransactions;
use App\Orders;
use App\OrderDetails;
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

class OrdersTransactionsController extends Controller
{
    protected $respose;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function AllTransaction () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Transaction')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Transaction";
            	$sess = session()->get('user');
            	$co_id= [];

            	if( $sess) {
                    if( $sess->user_type == 1) {
                    	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
        		        if($trans && sizeof($trans) != 0) {
        		        	foreach ($trans as $key => $value) {
        		    			$orders = Orders::Where('id', $value->order_id)->first();
        		    			if($orders) {
        		    				$trans[$key]->{'orders'} = $orders;
        		    				$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
        		    			} else {
        		    				$orders = Orders::Where('order_code', $value->order_id)->first();
        		    				if($orders) {
        								$trans[$key]->{'orders'} = $orders;
        								$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
        							} else {
        								$trans[$key]->{'orders'} = "";
        								$trans[$key]->{'orders_dets'} = "";
        							}
        		    			}
        		        	}
        		        }
                	} else if( $sess->user_type == 2 ||  $sess->user_type == 3) {
                		$ords_trans = DB::table('orders as A')
                            ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
                            ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
                            ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
                            ->leftJoin('orders_transactions as E', function($join) {
                         		$join->on('E.order_id', '=', 'A.id');
                         		$join->oron('E.order_id', '=', 'A.order_code');
                         	})
                            ->select('A.id','B.id as od_id', 'C.id as p_id', 'D.id as u_id', 'E.id as ot_id')
                            ->OrderBy('E.id', 'DESC')
                            ->where('C.created_user', '=', $sess->id)
                            ->where('D.id', '=', $sess->id)
                            ->whereIn('D.user_type', ['2','3'])
                            ->GroupBy('B.order_id')
                            ->get();

                        if (sizeof($ords_trans) != 0) {
                            foreach ($ords_trans as $key => $value) {
                                array_push($co_id, $value->ot_id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $trans = OrdersTransactions::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
        			        if($trans && sizeof($trans) != 0) {
        			        	foreach ($trans as $key => $value) {
        			    			$orders = Orders::Where('id', $value->order_id)->first();
        			    			if($orders) {
        			    				$trans[$key]->{'orders'} = $orders;
        			    				$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
        			    			} else {
        			    				$orders = Orders::Where('order_code', $value->order_id)->first();
        			    				if($orders) {
        									$trans[$key]->{'orders'} = $orders;
        									$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
        								} else {
        									$trans[$key]->{'orders'} = "";
        									$trans[$key]->{'orders_dets'} = "";
        								}
        			    			}
        			        	}
        			        }
                        }
            		}
        		}

            	return View::make("transaction.trans.all_transaction")->with(array('trans'=>$trans, 'page'=>$page));
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
                ->where('B.module_name', '=', 'All Transaction')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
                $page = "Transaction";
        		$trans = OrdersTransactions::where('id',$id)->first();
        		if($trans) {
        			$orders = Orders::Where('id', $trans->order_id)->first();
        			if($orders) {
        				$trans->{'orders'} = $orders;
        				$trans->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
        			} else {
        				$orders = Orders::Where('order_code', $trans->order_id)->first();
        				if($orders) {
        					$trans->{'orders'} = $orders;
        					$trans->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
        				} else {
        					$trans->{'orders'} = "";
        					$trans->{'orders_dets'} = "";
        				}
        			}
                }
        		
        		return View::make("transaction.trans.view_transaction")->with(array('trans'=>$trans, 'page'=>$page));
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
                ->where('B.module_name', '=', 'All Transaction')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->id)){
        			$id = $request->id;
        			if($id != 0) {
        				$trans = OrdersTransactions::where('id',$id)->first();
        				if($trans){
        					if($trans->delete()) {
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
                ->where('B.module_name', '=', 'All Transaction')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$trans = OrdersTransactions::where('id',$value)->first();
        					if($trans){
        						if($trans->delete()) {
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

	public function ExportTransCSV( Request $request) {  
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All Transaction')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()){
                    $ids = $request->ids;
                    $trans = array();
                    $filename = "Transaction.csv";
                    $user = session()->get('user');
                    
                    if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            if($user) {
                                $trans = OrdersTransactions::whereIn('id',$ids)->get();
                                /*if ($user->user_type == 1) {
                                } else if ($user->user_type == 2 || $user->user_type == 3) {
                                    $trans = OrdersTransactions::whereIn('id',$ids)->get();
                                } else {
                                    echo $error = 1;die();
                                }*/
                            } else {
                                echo $error = 1;die();
                            }
                            $filename = "Transaction.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') {
                        if($user) {
                            if ($user->user_type == 1) {
                                $trans = OrdersTransactions::all();
                            } else if ($user->user_type == 2 || $user->user_type == 3) {
                            	$co_id= [];
                                $ords_trans = DB::table('orders as A')
        		                    ->leftjoin('order_details as B', 'A.id', '=', 'B.order_id')
        		                    ->leftjoin('products as C', 'C.id', '=', 'B.product_id')
        		                    ->leftjoin('users as D', 'D.id', '=', 'C.created_user')
        		                    ->leftJoin('orders_transactions as E', function($join) {
        		                 		$join->on('E.order_id', '=', 'A.id');
        		                 		$join->oron('E.order_id', '=', 'A.order_code');
        		                 	})
        		                    ->select('A.id','B.id as od_id', 'C.id as p_id', 'D.id as u_id', 'E.id as ot_id')
        		                    ->OrderBy('E.id', 'DESC')
        		                    ->where('C.created_user', '=', $user->id)
        		                    ->where('D.id', '=', $user->id)
        		                    ->whereIn('D.user_type', ['2','3'])
        		                    ->GroupBy('B.order_id')
        		                    ->get();

        		                if (sizeof($ords_trans) != 0) {
        		                    foreach ($ords_trans as $key => $value) {
        		                        array_push($co_id, $value->ot_id);
        		                    }
        		                }

        		                if (sizeof($co_id) != 0) {
        		                    $trans = OrdersTransactions::WhereIn('id', $co_id)->get();
        	                    }
                            } else {
                                echo $error = 1;die();
                            }
                        } else {
                            echo $error = 1;die();
                        }
                        $filename = "All_Transaction.csv";
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }

        	        if($trans && sizeof($trans) != 0) {
        	        	foreach ($trans as $key => $value) {
        	        		if($value->trans_date) {
                                $trans[$key]['trans_date'] = date('d-m-Y H:i:s', strtotime($value->trans_date));
                            } else {
                                $trans[$key]['trans_date'] = "---------";
                            } 

                            if($value->gatewaytransactionid) {
                                $trans[$key]['gatewaytransactionid'] = $value->gatewaytransactionid;
                            } else {
                                $trans[$key]['gatewaytransactionid'] = "---------";
                            }

                            if($value->amountpaid) {
                                $trans[$key]['amountpaid'] = $value->amountpaid;
                            } else {
                                $trans[$key]['amountpaid'] = "---------";
                            }

                            if($value->paymentmode == 1) {
                                $trans[$key]['paymentmode'] = 'COD';
                                if($value->pay_method) {
        	                        $trans[$key]['pay_method'] = $value->pay_method;
        	                    } else {
        	                        $trans[$key]['pay_method'] = "---------";
        	                    }
                            } else if($value->paymentmode == 2) {
                                $trans[$key]['paymentmode'] = 'Online Payment';
                                if($value->pay_method) {
        	                        $trans[$key]['pay_method'] = $value->pay_method;
        	                    } else {
        	                        $trans[$key]['pay_method'] = "---------";
        	                    }
                            } else {
                                $trans[$key]['paymentmode'] = "---------";
                                if($value->pay_method) {
        	                        $trans[$key]['pay_method'] = $value->pay_method;
        	                    } else {
        	                        $trans[$key]['pay_method'] = "---------";
        	                    }
                            } 

                            if($value->net_amount) {
                                $trans[$key]['net_amount'] = 'Rs '.$value->net_amount;
                            } else {
                                $trans[$key]['net_amount'] = "---------";
                            }

                            if($value->remarks) {
                                $trans[$key]['remarks'] = $value->remarks;
                            } else {
                                $trans[$key]['remarks'] = "---------";
                            }

        	    			$orders = Orders::Where('id', $value->order_id)->first();
        	    			if($orders) {
        	    				$trans[$key]->{'orders'} = $orders;
        	    				$orders_dets = OrderDetails::Where('order_id', $orders->id)->get();
        	    				$trans[$key]->{'orders_dets'} = $orders_dets;

        	    				if($orders->order_code) {
        	                        $trans[$key]['order_code'] = $orders->order_code;
        	                    } else {
        	                        $trans[$key]['order_code'] = "---------";
        	                    }

        	                    if($orders->contact_person) {
        	                        $trans[$key]['contact_person'] = $orders->contact_person;
        	                    } else {
        	                        $trans[$key]['contact_person'] = "---------";
        	                    }

        	                    if($orders->contact_no) {
        	                        $trans[$key]['contact_no'] = $orders->contact_no;
        	                    } else {
        	                        $trans[$key]['contact_no'] = "---------";
        	                    }

        	                    if($orders->shipping_address) {
        	                        $trans[$key]['shipping_address'] = $orders->shipping_address;
        	                    } else {
        	                        $trans[$key]['shipping_address'] = "---------";
        	                    }

        	                    if($orders->total_items) {
        	                        $trans[$key]['total_items'] = $orders->total_items;
        	                    } else {
        	                        $trans[$key]['total_items'] = "---------";
        	                    }

        	                    if($orders->order_status == 0) {
        	                        $trans[$key]['order_status'] = "---------";
        	                    } elseif($orders->order_status == 1) {
        	                        $trans[$key]['order_status'] = "Order Placed";
        	                    } elseif ($orders->order_status == 2) {
        	                        $trans[$key]['order_status'] = "Order Dispatched";
        	                    } elseif ($orders->order_status == 3) {
        	                        $trans[$key]['order_status'] = "Order Delivered";
        	                    } elseif ($orders->order_status == 4) {
        	                        $trans[$key]['order_status'] = "Order Complete";
        	                    } elseif ($orders->order_status == 5) {
        	                        $trans[$key]['order_status'] = "Order Cancelled";
        	                    } else {
        	                        $trans[$key]['order_status'] = "---------";
        	                    }

        	                    if($orders->shipping_charge) {
        	                        $trans[$key]['shipping_charge'] = 'Rs '.$orders->shipping_charge;
        	                    } else {
        	                        $trans[$key]['shipping_charge'] = "---------";
        	                    }

        	                    if(count($orders_dets) != 0) {
        	                        foreach ($orders_dets as $keyz => $valuez) {
        	                            if($valuez->product_title) {
        	                                $trans[$key]['product_title'].= $valuez->product_title.',';
        	                            } else {
        	                                $trans[$key]['product_title'] = "---------";
        	                            }

        	                            if($valuez->order_qty) {
        	                                $trans[$key]['order_qty'].= $valuez->order_qty.',';
        	                            } else {
        	                                $trans[$key]['order_qty'] = "---------";
        	                            }

        	                            if($valuez->unitprice) {
        	                                $trans[$key]['unitprice'].= 'Rs '.$valuez->unitprice.',';
        	                            } else {
        	                                $trans[$key]['unitprice'] = "---------";
        	                            }

        	                            if($valuez->totalprice) {
        	                                $trans[$key]['totalprice'].= 'Rs '.$valuez->totalprice.',';
        	                            } else {
        	                                $trans[$key]['totalprice'] = "---------";
        	                            }
        	                        }
        	                    }
        	    			} else {
        	    				$orders = Orders::Where('order_code', $value->order_id)->first();
        	    				if($orders) {
        							$trans[$key]->{'orders'} = $orders;
        							$orders_dets = OrderDetails::Where('order_id', $orders->id)->get();
        							$trans[$key]->{'orders_dets'} = $orders_dets;

        							if($orders->order_code) {
        		                        $trans[$key]['order_code'] = $orders->order_code;
        		                    } else {
        		                        $trans[$key]['order_code'] = "---------";
        		                    }

        		                    if($orders->contact_person) {
        		                        $trans[$key]['contact_person'] = $orders->contact_person;
        		                    } else {
        		                        $trans[$key]['contact_person'] = "---------";
        		                    }

        		                    if($orders->contact_no) {
        		                        $trans[$key]['contact_no'] = $orders->contact_no;
        		                    } else {
        		                        $trans[$key]['contact_no'] = "---------";
        		                    }

        		                    if($orders->shipping_address) {
        		                        $trans[$key]['shipping_address'] = $orders->shipping_address;
        		                    } else {
        		                        $trans[$key]['shipping_address'] = "---------";
        		                    }

        		                    if($orders->total_items) {
        		                        $trans[$key]['total_items'] = $orders->total_items;
        		                    } else {
        		                        $trans[$key]['total_items'] = "---------";
        		                    }

        		                    if($orders->order_status == 0) {
        		                        $trans[$key]['order_status'] = "---------";
        		                    } elseif($orders->order_status == 1) {
        		                        $trans[$key]['order_status'] = "Order Placed";
        		                    } elseif ($orders->order_status == 2) {
        		                        $trans[$key]['order_status'] = "Order Dispatched";
        		                    } elseif ($orders->order_status == 3) {
        		                        $trans[$key]['order_status'] = "Order Delivered";
        		                    } elseif ($orders->order_status == 4) {
        		                        $trans[$key]['order_status'] = "Order Complete";
        		                    } elseif ($orders->order_status == 5) {
        		                        $trans[$key]['order_status'] = "Order Cancelled";
        		                    } else {
        		                        $trans[$key]['order_status'] = "---------";
        		                    }

        		                    if($orders->shipping_charge) {
        		                        $trans[$key]['shipping_charge'] = 'Rs '.$orders->shipping_charge;
        		                    } else {
        		                        $trans[$key]['shipping_charge'] = "---------";
        		                    }

        		                    if(count($orders_dets) != 0) {
        		                        foreach ($orders_dets as $keyz => $valuez) {
        		                            if($valuez->product_title) {
        		                                $trans[$key]['product_title'].= $valuez->product_title.',';
        		                            } else {
        		                                $trans[$key]['product_title'] = "---------";
        		                            }

        		                            if($valuez->order_qty) {
        		                                $trans[$key]['order_qty'].= $valuez->order_qty.',';
        		                            } else {
        		                                $trans[$key]['order_qty'] = "---------";
        		                            }

        		                            if($valuez->unitprice) {
        		                                $trans[$key]['unitprice'].= 'Rs '.$valuez->unitprice.',';
        		                            } else {
        		                                $trans[$key]['unitprice'] = "---------";
        		                            }

        		                            if($valuez->totalprice) {
        		                                $trans[$key]['totalprice'].= 'Rs '.$valuez->totalprice.',';
        		                            } else {
        		                                $trans[$key]['totalprice'] = "---------";
        		                            }
        		                        }
        		                    }
        						} else {
        							$trans[$key]->{'orders'} = "";
        							$trans[$key]->{'orders_dets'} = "";

        	                        $trans[$key]['order_code'] = "---------";
        	                        $trans[$key]['contact_person'] = "---------";
        	                        $trans[$key]['contact_no'] = "---------";
        	                        $trans[$key]['shipping_address'] = "---------";
        	                        $trans[$key]['total_items'] = "---------";
        	                        $trans[$key]['order_status'] = "---------";
        	                        $trans[$key]['shipping_charge'] = "---------";
        	                        $trans[$key]['product_title'] = "---------";
        	                        $trans[$key]['order_qty'] = "---------";
        	                        $trans[$key]['unitprice'] = "---------";
        	                        $trans[$key]['totalprice'] = "---------";
        						}
        	    			}
        	        	}
        	        }
                    
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('Transaction Code','Order Code', 'Transaction Date', 'Transaction ID', 'Amount Paid', 'Order Status', 'Contact Person', 'Contact Number', 'Shipping Address', 'Total Items', 'Payment Mode', 'Pay Method', 'Shipping Charge', 'Net Amount', 'Transaction Status', 'Remarks', 'Title', 'Quantity', 'Price', 'Total'));

                    foreach($trans as $row) {
                        fputcsv($handle, array($row['trans_code'], $row['order_code'], $row['trans_date'], $row['gatewaytransactionid'], $row['amountpaid'], $row['order_status'], $row['contact_person'], $row['contact_no'], $row['shipping_address'], $row['total_items'], $row['paymentmode'], $row['pay_method'], $row['shipping_charge'], $row['net_amount'], $row['trans_status'], $row['remarks'], $row['product_title'], $row['order_qty'], $row['unitprice'], $row['totalprice']));
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

	public function SearchTrans (Request $request) {
        $page = "Transaction";                                               
        $trans_date = Input::get('gj_srh_trans_date');
        $trans_code = Input::get('gj_srh_trans_code');
        $order_code = Input::get('gj_srh_odr_code');
        $order_status = Input::get('gj_srh_odr_sts');

        if($trans_date && $trans_code) {
        	$page = "Transaction";
            $trans = OrdersTransactions::OrderBy('id', 'DESC')->Where('trans_date', 'like', '%' . $trans_date . '%')->Where('trans_code', 'like', '%' . $trans_code . '%')->paginate(10);
        	
        	if($trans && sizeof($trans) != 0) {
	        	Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
	        } else {
	        	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
         		Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
            }

            foreach ($trans as $key => $value) {
    			$orders = Orders::Where('id', $value->order_id)->first();
    			if($orders) {
    				$trans[$key]->{'orders'} = $orders;
    				$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
    			} else {
    				$orders = Orders::Where('order_code', $value->order_id)->first();
    				if($orders) {
						$trans[$key]->{'orders'} = $orders;
						$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
					} else {
						$trans[$key]->{'orders'} = "";
						$trans[$key]->{'orders_dets'} = "";
					}
    			}
        	}

        	return View::make("transaction.trans.all_transaction")->with(array('trans'=>$trans, 'page'=>$page));
        } elseif($trans_date && $order_code) {
        	$page = "Transaction";
			$joins = DB::table('orders_transactions')
				->select('orders_transactions.*','orders_transactions.id as tid','orders.*')
				->join('orders', function($join) {
					$join->on('orders.id','=','orders_transactions.order_id')
					->orOn('orders.order_code','=','orders_transactions.order_id');
			})->where('orders_transactions.trans_date', $trans_date)->orWhere('orders.order_code', 'like', "%$order_code%")->get();

    		$ids = array();
        	if($joins && sizeof($joins) != 0) {
        		foreach ($joins as $keys => $values) {
        			$ids[] = array_push($ids, $values->tid);
        		}
	        	Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
	        } else {
	        	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
         		Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
            }

            if($ids && sizeof($ids) != 0) {
            	$trans = OrdersTransactions::WhereIn('id', $ids)->OrderBy('id', 'DESC')->paginate(10);
            } else {
            	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
            }

            foreach ($trans as $key => $value) {
    			echo $orders = Orders::Where('id', $value->order_id)->first();
    			if($orders) {
    				$trans[$key]->{'orders'} = $orders;
    				$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
    			} else {
    				$orders = Orders::Where('order_code', $value->order_id)->first();
    				if($orders) {
						$trans[$key]->{'orders'} = $orders;
						$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
					} else {
						$trans[$key]->{'orders'} = "";
						$trans[$key]->{'orders_dets'} = "";
					}
    			}
        	}

        	return View::make("transaction.trans.all_transaction")->with(array('trans'=>$trans, 'page'=>$page));
        } elseif($trans_date && $order_status) {
        	$page = "Transaction";
			$joins = DB::table('orders_transactions')
				->select('orders_transactions.*','orders_transactions.id as tid','orders.*')
				->join('orders', function($join) {
					$join->on('orders.id','=','orders_transactions.order_id')
					->orOn('orders.order_code','=','orders_transactions.order_id');
			})->where('orders_transactions.trans_date', $trans_date)->Where('orders.order_status', $order_status)->get();

    		$ids = array();
        	if($joins && sizeof($joins) != 0) {
        		foreach ($joins as $keys => $values) {
        			$ids[] = array_push($ids, $values->tid);
        		}
	        	Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
	        } else {
	        	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
         		Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
            }

            if($ids && sizeof($ids) != 0) {
            	$trans = OrdersTransactions::WhereIn('id', $ids)->OrderBy('id', 'DESC')->paginate(10);
            } else {
            	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
            }

            foreach ($trans as $key => $value) {
    			echo $orders = Orders::Where('id', $value->order_id)->first();
    			if($orders) {
    				$trans[$key]->{'orders'} = $orders;
    				$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
    			} else {
    				$orders = Orders::Where('order_code', $value->order_id)->first();
    				if($orders) {
						$trans[$key]->{'orders'} = $orders;
						$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
					} else {
						$trans[$key]->{'orders'} = "";
						$trans[$key]->{'orders_dets'} = "";
					}
    			}
        	}

        	return View::make("transaction.trans.all_transaction")->with(array('trans'=>$trans, 'page'=>$page));
        } elseif($trans_date) {
 			$page = "Transaction";
        	$trans = OrdersTransactions::Where('trans_date', $trans_date)->orWhere('trans_date', 'like', '%' . $trans_date . '%')->OrderBy('id', 'DESC')->paginate(10);

        	if($trans && sizeof($trans) != 0) {
	        	Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
	        } else {
	        	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
         		Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
            }

            foreach ($trans as $key => $value) {
    			$orders = Orders::Where('id', $value->order_id)->first();
    			if($orders) {
    				$trans[$key]->{'orders'} = $orders;
    				$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
    			} else {
    				$orders = Orders::Where('order_code', $value->order_id)->first();
    				if($orders) {
						$trans[$key]->{'orders'} = $orders;
						$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
					} else {
						$trans[$key]->{'orders'} = "";
						$trans[$key]->{'orders_dets'} = "";
					}
    			}
        	}

        	return View::make("transaction.trans.all_transaction")->with(array('trans'=>$trans, 'page'=>$page));
        } elseif($trans_code) {
 			$page = "Transaction";
        	$trans = OrdersTransactions::Where('trans_code', $trans_code)->orWhere('trans_code', 'like', '%' . $trans_code . '%')->OrderBy('id', 'DESC')->paginate(10);

        	if($trans && sizeof($trans) != 0) {
	        	Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
	        } else {
	        	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
         		Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
            }

            foreach ($trans as $key => $value) {
    			$orders = Orders::Where('id', $value->order_id)->first();
    			if($orders) {
    				$trans[$key]->{'orders'} = $orders;
    				$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
    			} else {
    				$orders = Orders::Where('order_code', $value->order_id)->first();
    				if($orders) {
						$trans[$key]->{'orders'} = $orders;
						$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
					} else {
						$trans[$key]->{'orders'} = "";
						$trans[$key]->{'orders_dets'} = "";
					}
    			}
        	}

        	return View::make("transaction.trans.all_transaction")->with(array('trans'=>$trans, 'page'=>$page));
        } elseif($order_code) {
 			$page = "Transaction";
        	$joins = DB::table('orders_transactions')
        		->select('orders_transactions.*','orders_transactions.id as tid','orders.*')
				->join('orders', function($join) {
					$join->on('orders.id','=','orders_transactions.order_id')
					->orOn('orders.order_code','=','orders_transactions.order_id');
			})->Where('orders.order_code', $order_code)->orWhere('orders.order_code', 'like', '%' . $order_code . '%')->get();

    		$ids = array();
        	if($joins && sizeof($joins) != 0) {
        		foreach ($joins as $keys => $values) {
        			$ids[] = array_push($ids, $values->tid);
        		}
	        	Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
	        } else {
	        	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
         		Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
            }

            if($ids && sizeof($ids) != 0) {
            	$trans = OrdersTransactions::WhereIn('id', $ids)->OrderBy('id', 'DESC')->paginate(10);
            } else {
            	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
            }

            foreach ($trans as $key => $value) {
    			$orders = Orders::Where('id', $value->order_id)->first();
    			if($orders) {
    				$trans[$key]->{'orders'} = $orders;
    				$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
    			} else {
    				$orders = Orders::Where('order_code', $value->order_id)->first();
    				if($orders) {
						$trans[$key]->{'orders'} = $orders;
						$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
					} else {
						$trans[$key]->{'orders'} = "";
						$trans[$key]->{'orders_dets'} = "";
					}
    			}
        	}

        	return View::make("transaction.trans.all_transaction")->with(array('trans'=>$trans, 'page'=>$page));
        } elseif($order_status) {
 			$page = "Transaction";
        	$joins = DB::table('orders_transactions')
        		->select('orders_transactions.*','orders_transactions.id as tid','orders.*')
				->join('orders', function($join) {
					$join->on('orders.id','=','orders_transactions.order_id')
					->orOn('orders.order_code','=','orders_transactions.order_id');
			})->Where('orders.order_status', $order_status)->get();

    		$ids = array();
        	if($joins && sizeof($joins) != 0) {
        		foreach ($joins as $keys => $values) {
        			$ids[] = array_push($ids, $values->tid);
        		}
	        	Session::flash('message', 'Search Items Founded!'); 
                Session::flash('alert-class', 'alert-success');
	        } else {
	        	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
         		Session::flash('message', 'Search Items Not Found!'); 
                Session::flash('alert-class', 'alert-danger');
            }

            if($ids && sizeof($ids) != 0) {
            	$trans = OrdersTransactions::WhereIn('id', $ids)->OrderBy('id', 'DESC')->paginate(10);
            } else {
            	$trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
            }

            foreach ($trans as $key => $value) {
    			$orders = Orders::Where('id', $value->order_id)->first();
    			if($orders) {
    				$trans[$key]->{'orders'} = $orders;
    				$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
    			} else {
    				$orders = Orders::Where('order_code', $value->order_id)->first();
    				if($orders) {
						$trans[$key]->{'orders'} = $orders;
						$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
					} else {
						$trans[$key]->{'orders'} = "";
						$trans[$key]->{'orders_dets'} = "";
					}
    			}
        	}

        	return View::make("transaction.trans.all_transaction")->with(array('trans'=>$trans, 'page'=>$page));
        } else {
        	$page = "Transaction";
	        $trans = OrdersTransactions::OrderBy('id', 'DESC')->paginate(10);
	        if($trans && sizeof($trans) != 0) {
	        	foreach ($trans as $key => $value) {
	    			$orders = Orders::Where('id', $value->order_id)->first();
	    			if($orders) {
	    				$trans[$key]->{'orders'} = $orders;
	    				$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
	    			} else {
	    				$orders = Orders::Where('order_code', $value->order_id)->first();
	    				if($orders) {
							$trans[$key]->{'orders'} = $orders;
							$trans[$key]->{'orders_dets'} = OrderDetails::Where('order_id', $orders->id)->get();
						} else {
							$trans[$key]->{'orders'} = "";
							$trans[$key]->{'orders_dets'} = "";
						}
	    			}
	        	}
	        }

	        Session::flash('message', 'Search Items Not Founded!'); 
            Session::flash('alert-class', 'alert-danger');
	    	return View::make("transaction.trans.all_transaction")->with(array('trans'=>$trans, 'page'=>$page));
        }
    }
}
