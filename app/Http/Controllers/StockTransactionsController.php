<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StockTransactions;
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

class StockTransactionsController extends Controller
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
                ->where('B.module_name', '=', 'Stock Transaction')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Products";
                $log = session()->get('user');
                $co_id = [];

                if($log) {
                    $user= $log->id;
                    if($log->user_type == 1) {
                        $stock_trans = StockTransactions::OrderBy('id', 'DESC')->paginate(10);
                    	return View::make("products.stock_trans.manage_stock_trans")->with(array('stock_trans'=>$stock_trans, 'page'=>$page));
                    } elseif ($log->user_type == 2 || $log->user_type == 3) {
                        $stock_trans = array();
                        $stts = DB::table('stock_transactions as A')
                            ->leftjoin('products as B', 'B.id', '=', 'A.product_id')
                            ->leftjoin('users as C', 'C.id', '=', 'B.created_user')
                            ->select('A.id','B.id as p_id', 'C.id as u_id')
                            ->OrderBy('A.id', 'DESC')
                            ->where('B.created_user', '=', $log->id)
                            ->where('C.id', '=', $log->id)
                            ->whereIn('C.user_type', ['2','3'])
                            ->get();

                        if (sizeof($stts) != 0) {
                            foreach ($stts as $key => $value) {
                                array_push($co_id, $value->id);
                            }
                        }

                        if (sizeof($co_id) != 0) {
                            $stock_trans = StockTransactions::WhereIn('id', $co_id)->OrderBy('id', 'DESC')->paginate(10);
                        }

                        return View::make("products.stock_trans.manage_stock_trans")->with(array('stock_trans'=>$stock_trans, 'page'=>$page));
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

    public function ExportStockCSV( Request $request) { 
        $error = 1;
        $loged = session()->get('user');

        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Stock Transaction')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.export', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax()) {
                    $ids = $request->ids;
                    $table = array();
                    $filename = "Inventory_trans.csv";

                    if(isset($ids) && $ids) {
                        if(sizeof($ids) != 0) {
                            $table = StockTransactions::WhereIn('id', $ids)->get();
                            $filename = "Inventory_trans.csv";
                        }  else {
                            Session::flash('message', 'CSV Export Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            die();
                        }
                    } else if(isset($request->type) && $request->type == 'export_all') {
                        $table = StockTransactions::all();
                        $filename = "Inventory_trans_all.csv";
                    } else {
                        Session::flash('message', 'CSV Export Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        die();
                    }

                    foreach ($table as $key => $value) {
                        if($value->product_id) {
                            $table[$key]['order_code'] = $value->order_code;
                        } else {
                            $table[$key]['order_code'] = "---------";
                        }

                        if($value->product_id) {
                            $table[$key]['p_code'] = $value->StockProducts->product_code;
                            $table[$key]['product'] = $value->StockProducts->product_title;
                        } else {
                            $table[$key]['p_code'] = "---------";
                            $table[$key]['product'] = "---------";
                        }

                        if($value->att_name) {
                            $table[$key]['att_name'] = $value->StockAttName->att_name;
                        } else {
                            $table[$key]['att_name'] = "---------";
                        }

                        if($value->att_value) {
                            $table[$key]['att_value'] = $value->StockAttValue->att_value;
                        } else {
                            $table[$key]['att_value'] = "---------";
                        }

                        if($value->previous_qty) {
                            $table[$key]['previous_qty'] = $value->previous_qty;
                        } else {
                            $table[$key]['previous_qty'] = 0;
                        }

                        if($value->current_qty) {
                            $table[$key]['current_qty'] = $value->current_qty;
                        } else {
                            $table[$key]['current_qty'] = 0;
                        }   

                        if($value->date) {
                            $table[$key]['date'] = date('d-m-Y', strtotime($value->date));
                        } else {
                            $table[$key]['date'] = "---------";
                        }

                        if($value->att_previous_qty) {
                            $table[$key]['att_previous_qty'] = $value->att_previous_qty;
                        } else {
                            $table[$key]['att_previous_qty'] = 0;
                        }

                        if($value->att_current_qty) {
                            $table[$key]['att_current_qty'] = $value->att_current_qty;
                        } else {
                            $table[$key]['att_current_qty'] = 0;
                        }

                        if($value->remarks) {
                            $table[$key]['remarks'] = $value->remarks;
                        } else {
                            $table[$key]['remarks'] = "---------";
                        }
                    }
                    
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('Order Code', 'Product Code', 'Product', 'Attribute Name', 'Attribute Value', 'Previous On hand Qty', 'Current Qty', 'Date', 'Previous Attribute Qty', 'Current Attribute Qty', 'Remarks'));

                    foreach($table as $row) {
                        fputcsv($handle, array($row['order_code'], $row['p_code'], $row['product'], $row['att_name'], $row['att_value'], $row['previous_qty'], $row['current_qty'], $row['date'], $row['att_previous_qty'], $row['att_current_qty'], $row['remarks']));
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
