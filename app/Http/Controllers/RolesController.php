<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Previlages;
use App\Modules;
use App\Roles;
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

class RolesController extends Controller
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
                ->where('B.module_name', '=', 'Roles')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $role = Roles::all();
                return View::make("settings.role.manage_role")->with(array('role'=>$role, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Roles')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                return View::make('settings.role.add_role')->with(array('page'=>$page));
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
                ->where('B.module_name', '=', 'Roles')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $rules = array(
                    'role'       => 'required|unique:roles,role',
                    'is_block'   => 'nullable',
                );

                $messages=[
                    'role.required'=>'The Role field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                    return View::make('settings.role.add_role')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                    $role = new Roles();

                    if($role) {
                        $role->role      = $data['role'];               
                        $role->is_block  = 1;

                        if($role->save()) {
                            Session::flash('message', 'Add Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            return redirect()->route('manage_role');

                        } else{
                            Session::flash('message', 'Added Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_role');
                        }  
                    } else{
                        Session::flash('message', 'Added Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_role');
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
                ->where('B.module_name', '=', 'Roles')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $role = Roles::where('id',$id)->first();
                return View::make("settings.role.edit_role")->with(array('role'=>$role, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Roles')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Settings";
                $id = Input::get('role_id');
                $role = '';
                if($id != '') {
                    $role = Roles::Where('id', $id)->first();
                }
                
                if($role) {
                    $rules = array(
                        'role'       => 'required|unique:roles,role,'.$id.',id',
                        'is_block'   => 'nullable',
                    );
                    $validator = Validator::make(Input::all(), $rules);

                    if ($validator->fails()) {
                        return Redirect::to('/edit_role/' . $id)->withErrors($validator)->with(array('role'=>$role, 'page'=>$page));
                    } else {
                        $data = Input::all();
                        
                        $role->role       = $data['role'];             
                        $role->is_block        = 1;

                        if($role->save()) {
                            Session::flash('message', 'update Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            return redirect()->route('manage_role');

                        } else{
                            Session::flash('message', 'update Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('manage_role');
                        }   
                    }
                } else{
                    Session::flash('message', 'update Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_role');
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
        if($request->ajax() && isset($request->id)){
            $id = $request->id;
            $error = 1;
            
            $loged = session()->get('user');
            if($loged) {
                $privil = DB::table('previlages as A')
                    ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                    ->select('A.id as pid','A.*','B.id as mid','B.*')
                    ->where('B.module_name', '=', 'Roles')
                    ->where('A.role', '=', $loged->user_type)
                    ->where('A.delete', '=', 1)
                    ->first();

                if($privil) {
                    if($id != 0) {
                        $role = Roles::where('id',$id)->first();
                        if($role){
                            if($role->id == 1 || $role->id == 2 || $role->id == 3 || $role->id == 4) {
                                Session::flash('message', 'Not Possible Delete for This Role!'); 
                                Session::flash('alert-class', 'alert-danger');
                                $error = 1;
                            } else {
                                if($role->delete()) {
                                    Session::flash('message', 'Deleted Successfully!'); 
                                    Session::flash('alert-class', 'alert-success');
                                    $error = 0;
                                } else {
                                    Session::flash('message', 'Deleted Failed!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    $error = 1;
                                }
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

    public function DeleteAll( Request $request) {  
        $ids = array();

        if($request->ajax() && isset($request->ids)){
            $ids = $request->ids;
            $error = 1;

            $loged = session()->get('user');
            if($loged) {
                $privil = DB::table('previlages as A')
                    ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                    ->select('A.id as pid','A.*','B.id as mid','B.*')
                    ->where('B.module_name', '=', 'Roles')
                    ->where('A.role', '=', $loged->user_type)
                    ->where('A.delete', '=', 1)
                    ->first();

                if($privil) {
                    if(sizeof($ids) != 0) {
                        foreach ($ids as $key => $value) {
                            $role = Roles::where('id',$value)->first();
                            if($role){
                                if($role->id == 1 || $role->id == 2 || $role->id == 3 || $role->id == 4) {
                                    Session::flash('message', 'Not Possible Delete for This Role!'); 
                                    Session::flash('alert-class', 'alert-danger');
                                    $error = 1;
                                } else {
                                    if($role->delete()) {
                                        Session::flash('message', 'Deleted Successfully!'); 
                                        Session::flash('alert-class', 'alert-success');
                                        $error = 0;
                                    } else {
                                        Session::flash('message', 'Deleted Failed!'); 
                                        Session::flash('alert-class', 'alert-danger');

                                    }
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

    public function Statusrole ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Roles')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                $role = '';
                $msg = '';
                if($id != '') {
                    $role = Roles::Where('id', $id)->first();
                }

                if($role) {
                    if($role->is_block == 1) {
                        $role->is_block        = 0;
                        $msg = "Blocked Successfully";
                    } else {
                        $role->is_block        = 1;
                        $msg = "Unblocked Successfully";
                    }
                    
                    if($role->save()) {
                        Session::flash('message', $msg); 
                        Session::flash('alert-class', 'alert-success');
                        return redirect()->route('manage_role');
                    } else{
                        Session::flash('message', 'Failed Block or Unblock!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_role');
                    }
                } else{
                    Session::flash('message', 'Failed Block or Unblock!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_role');
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

    public function roleBlock( Request $request) {   
        $ids = array();
        $error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Roles')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
                    $ids = $request->ids;

                    if(sizeof($ids) != 0) {
                        foreach ($ids as $key => $value) {
                            $role = Roles::where('id',$value)->first();
                            if($role){
                                $role->is_block = 0;
                                $role->save();
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

    public function roleUnblock( Request $request) { 
        $ids = array();
        $error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Roles')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
                    $ids = $request->ids;
                    if(sizeof($ids) != 0) {
                        foreach ($ids as $key => $value) {
                            $role = Roles::where('id',$value)->first();
                            if($role){
                                $role->is_block = 1;
                                $role->save();
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

    public function UserPrivileges () {
        $loged = session()->get('user');
        if($loged) {
            if($loged->user_type == 1) {
                $page = "Users";
                // $roles = Roles::Where('is_block', 1)->Where('id', '!=', 4)->get();
                $roles = Roles::Where('is_block', 1)->WhereNotIn('id', [1,4])->get();
                $modules = Modules::all();
                // $users = User::Where('user_type', '!=', 4)->get();
                $users = User::WhereNotIn('user_type', [1,4])->get();
                return View::make('settings.role.user_previl')->with(array('page'=>$page, 'modules'=>$modules, 'users'=>$users, 'roles'=>$roles));
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

    public function SavePrivileges(Request $request) {
        $error = 1;
        $loged = session()->get('user');
        if($loged) {
            if($loged->user_type == 1) {
                $page = "Users";
                $data = Input::all();
                // print_r($data);die();
                $usr_role = false;

                if(isset($data['roles'])) {
                    if($data['roles'] != 0) {
                        $usr_role = Roles::Where('id', $data['roles'])->first();
                    } else {
                        Session::flash('message', 'Please Select Roles!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('user_previl');
                        // echo $error = 2;die();
                    }
                }


                if($usr_role) {
                    if($usr_role->id == 1) {
                        Session::flash('message', 'You have Not set to Admin Privileges!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('user_previl');
                    }
                    if(sizeof($data['mid']) != 0) {
                        Previlages::Where('role', $usr_role->id)->delete();
                        foreach ($data['mid'] as $mkey => $mvalue) {
                            $prev = new Previlages();
                            $prev->role       = $usr_role->id;
                            $prev->module     = $mvalue;
                            $prev->list       = $data['h_listcheck'][$mkey];
                            $prev->add        = $data['h_addcheck'][$mkey];
                            $prev->edit       = $data['h_editcheck'][$mkey];
                            $prev->view       = $data['h_viewcheck'][$mkey];
                            $prev->delete     = $data['h_deletecheck'][$mkey];
                            $prev->status     = $data['h_statuscheck'][$mkey];
                            $prev->export     = $data['h_exportcheck'][$mkey];
                            $prev->save();
                        }
                        
                        Session::flash('message', 'Roles & Privileges Changed Successfully!'); 
                        Session::flash('alert-class', 'alert-success');
                        return redirect()->route('user_previl');
                        // $error = 0;
                    } else {
                        Session::flash('message', 'Roles & Privileges Changed Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('user_previl');
                        // $error = 3;
                    }
                } else {
                    Session::flash('message', 'Please Select Roles!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('user_previl');
                    // $error = 2;
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
                // $error = 4;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
            // $error = 5;
        }
    }

    public function SelectPrivileges( Request $request) {   
        $id = 0;
        if($request->ajax() && isset($request->roles) && isset($request->data)){
            $id = $request->roles;
            $data = $request->data;
            $error = 1;
            if($id != 0) {
                $roles = DB::table('previlages as A')
                    ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                    ->leftjoin('roles as C', 'A.role', '=', 'C.id')
                    ->select('A.id as pid','A.*','B.id as mid','B.*','C.id as rid','C.*')
                    ->where('A.role', '=', $id)
                    ->where('C.id', '=', $id)
                    ->get();
                // print_r($roles);
                // die();

                if(sizeof($roles) != 0) {
                    $error = "";
                    $i = 1;
                    foreach ($roles as $mkey => $mval) {
                        $error.= '<tr>
                            <td>'.$i.'</td>
                            <td>
                                <input type="hidden" name="mid[]" value="'.$mval->mid.'">
                                <input type="checkbox" name="rowcheck[]" class="rowcheck" id="rowcheck_'.$i.'" />
                            </td>
                            <td>'.$mval->module_name.'</td>
                            <td>
                                <input type="checkbox" name="listcheck[]" class="listcheck" id="listcheck_'.$mval->mid.'" value="'.$mval->list.'" '.($mval->list == 1 ? 'checked' : '').'/>
                                <input type="hidden" name="h_listcheck[]" class="h_listcheck" id="hlistcheck_'.$mval->mid.'" value="'.$mval->list.'" />
                            </td>
                            <td>
                                <input type="checkbox" name="addcheck[]" class="addcheck" id="addcheck_'.$mval->mid.'" value="'.$mval->add.'" '.($mval->add == 1 ? 'checked' : '').'/>
                                <input type="hidden" name="h_addcheck[]" class="h_addcheck" id="addcheck_'.$mval->mid.'" value="'.$mval->add.'" />
                            </td>
                            <td>
                                <input type="checkbox" name="editcheck[]" class="editcheck" id="editcheck_'.$mval->mid.'" value="'.$mval->edit.'" '.($mval->edit == 1 ? 'checked' : '').'/>
                                <input type="hidden" name="h_editcheck[]" class="h_editcheck" id="h_editcheck_'.$mval->mid.'" value="'.$mval->edit.'" />
                             </td>
                            <td>
                                <input type="checkbox" name="viewcheck[]" class="viewcheck" id="viewcheck_'.$mval->mid.'" value="'.$mval->view.'" '.($mval->view == 1 ? 'checked' : '').'/>
                                <input type="hidden" name="h_viewcheck[]" class="h_viewcheck" id="h_viewcheck_'.$mval->mid.'" value="'.$mval->view.'" />
                            </td>
                            <td>
                                <input type="checkbox" name="deletecheck[]" class="deletecheck" id="deletecheck_'.$mval->mid.'" value="'.$mval->delete.'" '.($mval->delete == 1 ? 'checked' : '').'/>
                                <input type="hidden" name="h_deletecheck[]" class="h_deletecheck" id="h_deletecheck_'.$mval->mid.'" value="'.$mval->delete.'" />
                            </td>
                            <td>
                                <input type="checkbox" name="statuscheck[]" class="statuscheck" id="statuscheck_'.$mval->mid.'" value="'.$mval->status.'" '.($mval->status == 1 ? 'checked' : '').'/>
                                <input type="hidden" name="h_statuscheck[]" class="h_statuscheck" id="h_statuscheck_'.$mval->mid.'" value="'.$mval->status.'" />
                            </td>
                            <td>
                                <input type="checkbox" name="exportcheck[]" class="exportcheck" id="exportcheck_'.$mval->mid.'" value="'.$mval->export.'" '.($mval->export == 1 ? 'checked' : '').'/>
                                <input type="hidden" name="h_exportcheck[]" class="h_exportcheck" id="h_exportcheck_'.$mval->mid.'" value="'.$mval->export.'" />
                            </td>
                        </tr>';
                        $i++;
                    }
                }
            }

            if($error == 1) {
                $modules = Modules::all();
                if(sizeof($modules) != 0) {
                    $i = 1;
                    $error = "";
                    foreach($modules as $mkey =>$mval) {
                        $error.= '<tr>
                            <td>'.$i.'</td>
                            <td>
                                <input type="hidden" name="mid[]" value="'.$mval->id.'"  autocomplete="off">
                                <input type="checkbox" name="rowcheck[]" class="rowcheck" id="rowcheck_'.$i.'" autocomplete="off"/>
                            </td>
                            <td>'.$mval->module_name.'</td>
                            <td>
                                <input type="checkbox" name="listcheck[]" class="listcheck" id="listcheck_'.$mval->id.'" value="0" autocomplete="off"/>
                                <input type="hidden" name="h_listcheck[]" class="h_listcheck" id="hlistcheck_'.$mval->id.'" value="0" autocomplete="off"/>
                            </td>
                            <td>
                                <input type="checkbox" name="addcheck[]" class="addcheck" id="addcheck_'.$mval->id.'" value="0" autocomplete="off"/>
                                <input type="hidden" name="h_addcheck[]" class="h_addcheck" id="addcheck_'.$mval->id.'" value="0" autocomplete="off"/>
                            </td>
                            <td>
                                <input type="checkbox" name="editcheck[]" class="editcheck" id="editcheck_'.$mval->id.'" value="0" autocomplete="off"/>
                                <input type="hidden" name="h_editcheck[]" class="h_editcheck" id="h_editcheck_'.$mval->id.'" value="0" autocomplete="off"/>
                             </td>
                            <td>
                                <input type="checkbox" name="viewcheck[]" class="viewcheck" id="viewcheck_'.$mval->id.'" value="0" autocomplete="off"/>
                                <input type="hidden" name="h_viewcheck[]" class="h_viewcheck" id="h_viewcheck_'.$mval->id.'" value="0" autocomplete="off"/>
                            </td>
                            <td>
                                <input type="checkbox" name="deletecheck[]" class="deletecheck" id="deletecheck_'.$mval->id.'" value="0" autocomplete="off"/>
                                <input type="hidden" name="h_deletecheck[]" class="h_deletecheck" id="h_deletecheck_'.$mval->id.'" value="0" autocomplete="off"/>
                            </td>
                            <td>
                                <input type="checkbox" name="statuscheck[]" class="statuscheck" id="statuscheck_'.$mval->id.'" value="0" autocomplete="off"/>
                                <input type="hidden" name="h_statuscheck[]" class="h_statuscheck" id="h_statuscheck_'.$mval->id.'" value="0" autocomplete="off"/>
                            </td>
                            <td>
                                <input type="checkbox" name="exportcheck[]" class="exportcheck" id="exportcheck_'.$mval->id.'" value="0" autocomplete="off"/>
                                <input type="hidden" name="h_exportcheck[]" class="h_exportcheck" id="h_exportcheck_'.$mval->id.'" value="0" autocomplete="off"/>
                            </td>
                        </tr>';
                        $i = $i+1;
                    }
                }
            }

            echo $error;
        }
    }
}
