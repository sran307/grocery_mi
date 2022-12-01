<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cod;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class CodController extends Controller
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
                ->where('B.module_name', '=', 'COD Management')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $cod = Cod::all();
                return View::make("settings.cod.manage_cod")->with(array('cod'=>$cod, 'page'=>$page));
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
                ->where('B.module_name', '=', 'COD Management')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                return View::make('settings.cod.add_cod')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'COD Management')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $rules = array(
                    'above_amount'    => 'required|numeric',
                    'cod_amount'      => 'required|numeric',
                    'remarks'         => 'nullable',
                    'is_block'        => 'nullable',
                );

                $messages=[
                    'cod.required'=>'The cod field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                    return View::make('settings.cod.add_cod')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();            
                    $cod = new Cod();

                    if($cod) {
                        $cod->above_amount    = $data['above_amount'];               
                        $cod->cod_amount      = $data['cod_amount'];             
                        $cod->remarks         = $data['remarks'];                
                        $cod->is_block        = 1;

                        if($cod->save()) {
                            Session::flash('message', 'Add Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            return redirect()->route('manage_cod');

                        } else{
                            Session::flash('message', 'Added Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_cod');
                        }  
                    } else{
                        Session::flash('message', 'Added Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cod');
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

    public function edit ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'COD Management')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $cod = Cod::where('id',$id)->first();
                return View::make("settings.cod.edit_cod")->with(array('cod'=>$cod, 'page'=>$page));
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
                ->where('B.module_name', '=', 'COD Management')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $id = Input::get('cod_id');
                $cod = '';
                if($id != '') {
                    $cod = Cod::Where('id', $id)->first();
                }

                if($cod) {
                    $rules = array(
                        'above_amount'   => 'required',
                        'cod_amount'     => 'required',
                        'remarks'        => 'nullable',
                        'is_block'       => 'nullable',
                    );
                    $validator = Validator::make(Input::all(), $rules);

                    if ($validator->fails()) {
                        return Redirect::to('/edit_cod/' . $id)->withErrors($validator)->with(array('cod'=>$cod, 'page'=>$page));
                    } else {
                        $data = Input::all();

                        $cod->above_amount    = $data['above_amount'];               
                        $cod->cod_amount      = $data['cod_amount'];             
                        $cod->remarks         = $data['remarks'];                
                        $cod->is_block        = 1;

                        if($cod->save()) {
                            Session::flash('message', 'update Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            return redirect()->route('manage_cod');

                        } else{
                            Session::flash('message', 'update Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_cod');
                        }   
                    }
                } else{
                    Session::flash('message', 'update Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_cod');
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
                ->where('B.module_name', '=', 'COD Management')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) { 
                if($request->ajax() && isset($request->id)){
                    $id = $request->id;
                    if($id != 0) {
                        $cod = Cod::where('id',$id)->first();
                        if($cod){
                            if($cod->delete()) {
                                Session::flash('message', 'Deleted Successfully!'); 
                                Session::flash('alert-class', 'alert-success');
                                $error = 0;
                            } else {
                                Session::flash('message', 'Deleted Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                                $error = 1;
                            }
                        }   else {
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
                ->where('B.module_name', '=', 'COD Management')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
                    $ids = $request->ids;
                    if(sizeof($ids) != 0) {
                        foreach ($ids as $key => $value) {
                            $cod = Cod::where('id',$value)->first();
                            if($cod){
                                if($cod->delete()) {
                                    Session::flash('message', 'Deleted Successfully!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    $error = 0;
                                } else {
                                    Session::flash('message', 'Deleted Failed!'); 
                                    Session::flash('alert-class', 'alert-danger');

                                }
                            }   else {
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

    public function Statuscod ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'COD Management')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                $cod = '';
                $msg = '';
                if($id != '') {
                    $cod = Cod::Where('id', $id)->first();
                }

                if($cod) {
                    if($cod->is_block == 1) {
                        $cod->is_block        = 0;
                        $msg = "Blocked Successfully";
                    } else {
                        $cod->is_block        = 1;
                        $msg = "Unblocked Successfully";
                    }
                    
                    if($cod->save()) {
                        Session::flash('message', $msg); 
                        Session::flash('alert-class', 'alert-success');
                        return redirect()->route('manage_cod');
                    } else{
                        Session::flash('message', 'Failed Block or Unblock!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_cod');
                    }
                } else{
                    Session::flash('message', 'Failed Block or Unblock!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_cod');
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

    public function codBlock( Request $request) { 
        $ids = array();
        $error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'COD Management')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
                    $ids = $request->ids;
                    if(sizeof($ids) != 0) {
                        foreach ($ids as $key => $value) {
                            $cod = Cod::where('id',$value)->first();
                            if($cod){
                                $cod->is_block = 0;
                                $cod->save();
                                Session::flash('message', 'Blocked Successfully!'); 
                                Session::flash('alert-class', 'alert-success');
                                $error = 0;
                            }   else {
                                Session::flash('message', 'Blocked Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                            }           
                        }
                    } else {
                        Session::flash('message', 'Blocked Failed!'); 
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

    public function codUnblock( Request $request) {  
        $ids = array();
        $error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'COD Management')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
                    $ids = $request->ids;
                    if(sizeof($ids) != 0) {
                        foreach ($ids as $key => $value) {
                            $cod = Cod::where('id',$value)->first();
                            if($cod){
                                $cod->is_block = 1;
                                $cod->save();
                                Session::flash('message', 'Unblocked Successfully!'); 
                                Session::flash('alert-class', 'alert-success');
                                $error = 0;
                            }   else {
                                Session::flash('message', 'Unblocked Failed!'); 
                                Session::flash('alert-class', 'alert-danger');
                            }           
                        }
                    } else {
                        Session::flash('message', 'Unblocked Failed!'); 
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
}
