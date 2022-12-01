<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\User;
use App\CityManagement;
use App\CountriesManagement;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;

class StoreController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Merchant Store')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $store = Store::where('merchant',$id)->get();
                if($store) {
                	foreach ($store as $key => $str) {
                		$city = CityManagement::where('id',$str->store_city)->first();
            			$merchants = User::where('id',$str->merchant)->first();

                		if($city) {
                			$store[$key]['city'] = $city->city_name;
                		} else {
                			$store[$key]['city'] = "-------";
                		}

                		if($merchants) {
                			$store[$key]['merchant_det'] = $merchants;
                		} else {
                			$store[$key]['merchant_det'] = "-------";
                		}
                	}
                }
            	return View::make("store.manage_store")->with(array('store'=>$store));
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

    public function create ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Merchant Store')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$merchants = User::where('id',$id)->first();
            	return View::make('store.add_store')->with(array('merchants'=>$merchants));
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
                ->where('B.module_name', '=', 'Merchant Store')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $data = Input::all();
                $merchants = User::where('id',$data['merchant'])->first();
                if($merchants) {
                	$rules = array(
                        'merchant'                => 'required|exists:users,id',
                        'store_name'              => 'required',
                        'store_phone'             => 'required|numeric|unique:stores,store_phone',
                        'store_address1'          => 'required',
                        'store_address2'          => 'required',
                        'store_country'           => 'required',
                        'store_state'             => 'required',
                        'store_city'              => 'required',
                        'store_zipcode'           => 'required',
                        'meta_keyword'            => 'nullable',
                        'meta_description'        => 'nullable',
                        'website'                 => 'nullable',
                        'slogan'                  => 'required',
                        'stores_image'            => 'required',
                        'is_block'                => 'nullable',
                        'login_type'              => 'nullable',
                    );

                    $messages=[
                        'store_address1.required'=>'The store address field is required.',
                        'store_address2.required'=>'The store address field is required.',
                    ];
                    $validator = Validator::make(Input::all(), $rules,$messages);

                    if ($validator->fails()) {
                        return Redirect::to('/add_store/' . $data['merchant'])->withErrors($validator)->with(array('merchants'=>$merchants));
                    } else {
                    	$store = new Store();

                        if($store) {
            	            $store->merchant                  = $data['merchant'];
            	            $store->store_name                = $data['store_name'];
            	            $store->store_phone               = $data['store_phone'];
            	            $store->store_address1            = $data['store_address1'];
            	            $store->store_address2            = $data['store_address2'];
                            $store->store_country             = $data['store_country'];
            	            $store->store_state               = $data['store_state'];
            	            $store->store_city                = $data['store_city'];
            	            $store->store_zipcode             = $data['store_zipcode'];
            	            $store->meta_keyword              = $data['meta_keyword'];
            	            $store->meta_description          = $data['meta_description'];
            	            // $store->website                   = $data['website'];
            	            $store->slogan                    = $data['slogan'];
                            $store->is_block                  = 1;
            	            $store->login_type                = 1;

            	            $img_files = Input::file('stores_image');
                            if(isset($img_files)) {
                                $file_name = $img_files->getClientOriginalName();
                                $date = date('M-Y');
                                // $file_path = '../public/images/stores_image/'.$date;
                                $file_path = 'images/stores_image/'.$date;
                                $img_files->move($file_path, $file_name);
                                $store->stores_image = $date.'/'.$file_name;
                            } else {
                                $store->stores_image = NULL;
                            }
                            $lvalue = session()->get('user');

                            if($store->save()) {
                                Session::flash('message', 'Add Successfully!'); 
                                Session::flash('alert-class', 'alert-success');
                                if($lvalue) {
                                    if($lvalue->user_type == 1) {
                                       return redirect()->route('manage_merchant');
                                    } elseif ($lvalue->user_type == 2 || $lvalue->user_type == 3) {
                                       return Redirect::to('/manage_store/' . $lvalue->id);
                                    }
                                }
            	            } else{
            	            	Session::flash('message', 'Added Failed!'); 
            					Session::flash('alert-class', 'alert-danger');
            	                if($lvalue) {
                                    if($lvalue->user_type == 1) {
                                       return redirect()->route('manage_merchant');
                                    } elseif ($lvalue->user_type == 2 || $lvalue->user_type == 3) {
                                       return Redirect::to('/manage_store/' . $lvalue->id);
                                    }
                                }
            	            }  
                        } else{
                        	Session::flash('message', 'Added Failed!'); 
            				Session::flash('alert-class', 'alert-danger');
                            if($lvalue) {
                                if($lvalue->user_type == 1) {
                                   return redirect()->route('manage_merchant');
                                } elseif ($lvalue->user_type == 2 || $lvalue->user_type == 3) {
                                   return Redirect::to('/manage_store/' . $lvalue->id);
                                }
                            }
                        }
                    }
                } else {
                    Session::flash('message', 'Invalid User!'); 
                    Session::flash('alert-class', 'alert-danger');
                    if($lvalue) {
                        if($lvalue->user_type == 1) {
                           return redirect()->route('manage_merchant');
                        } elseif ($lvalue->user_type == 2 || $lvalue->user_type == 3) {
                           return Redirect::to('/manage_store/' . $lvalue->id);
                        }
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
                ->where('B.module_name', '=', 'Merchant Store')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$store = Store::where('id',$id)->first();
        		if($store) {
        			$merchants = User::where('id', $store->merchant)->where('is_block', 1)->first();
        			if($merchants) {
        				$store['merchant_name'] = $merchants->first_name;
        			} else {
        				$store['merchant_name'] = "--------";
        			}
        		}
        		return View::make("store.edit_store")->with(array('store'=>$store));
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
                ->where('B.module_name', '=', 'Merchant Store')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $id = Input::get('store_id');
                $store = '';
                if($id != '') {
                    $store = Store::Where('id', $id)->first();
                }

                if($store) {
            		$rules = array(
                        'merchant'                => 'required',
                        'store_name'              => 'required',
                        'store_phone'             => 'required|numeric|unique:stores,store_phone,'.$id.',id',
                        'store_address1'          => 'required',
                        'store_address2'          => 'required',
                        'store_country'           => 'required',
                        'store_state'              => 'required',
                        'store_city'              => 'required',
                        'store_zipcode'           => 'required',
                        'meta_keyword'            => 'nullable',
                        'meta_description'        => 'nullable',
                        'website'                 => 'nullable',
                        'slogan'                  => 'required',
                        'stores_image'            => 'nullable',
                        'is_block'                => 'nullable',
                        'login_type'              => 'nullable',
                    );
                    $validator = Validator::make(Input::all(), $rules);

                    if ($validator->fails()) {
                        $merchants = User::where('id', $store->merchant)->where('is_block', 1)->first();
                        if($merchants) {
                            $store['merchant_name'] = $merchants->first_name;
                        } else {
                            $store['merchant_name'] = "--------";
                        }
                        
                        return Redirect::to('/edit_store/' . $id)->withErrors($validator)->with(array('store'=>$store));
                    } else {
                        $data = Input::all();
                        $ps = "gj";
                        $pe = "ja";
                        
                        $store->merchant                  = $data['merchant'];
                        $store->store_name                = $data['store_name'];
                        $store->store_phone               = $data['store_phone'];
                        $store->store_address1            = $data['store_address1'];
                        $store->store_address2            = $data['store_address2'];
                        $store->store_country             = $data['store_country'];
                        $store->store_state               = $data['store_state'];
                        $store->store_city                = $data['store_city'];
                        $store->store_zipcode             = $data['store_zipcode'];
                        $store->meta_keyword              = $data['meta_keyword'];
                        $store->meta_description          = $data['meta_description'];
                        // $store->website                   = $data['website'];
                        $store->slogan                    = $data['slogan'];
                        $store->is_block                  = 1;
                        $store->login_type                = 1;

                        $img_files = Input::file('stores_image');
                        $old_stores_image = Input::get('old_stores_image');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/stores_image/'.$date;
                            $file_path = 'images/stores_image/'.$date;
                            $img_files->move($file_path, $file_name);
                            $store->stores_image = $date.'/'.$file_name;
                        } else if(isset($old_stores_image) && $old_stores_image != '') {
                            $store->stores_image = $old_stores_image;
                        } else {
                            $store->stores_image = NULL;
                        }
                        $lvalue = session()->get('user');

                        if($store->save()) {
                        	Session::flash('message', 'update Successfully!'); 
            				Session::flash('alert-class', 'alert-success');
                            if($lvalue) {
                                if($lvalue->user_type == 1) {
                                   return redirect()->route('manage_merchant');
                                } elseif ($lvalue->user_type == 2 || $lvalue->user_type == 3) {
                                   return Redirect::to('/manage_store/' . $lvalue->id);
                                }
                            }
                        } else{
                        	Session::flash('message', 'update Failed!'); 
            				Session::flash('alert-class', 'alert-danger');
                            if($lvalue) {
                                if($lvalue->user_type == 1) {
                                   return redirect()->route('manage_merchant');
                                } elseif ($lvalue->user_type == 2 || $lvalue->user_type == 3) {
                                   return Redirect::to('/manage_store/' . $lvalue->id);
                                }
                            }
                        }   
                    }
                } else {
                    Session::flash('message', 'update Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    if($lvalue) {
                        if($lvalue->user_type == 1) {
                           return redirect()->route('manage_merchant');
                        } elseif ($lvalue->user_type == 2 || $lvalue->user_type == 3) {
                           return Redirect::to('/manage_store/' . $lvalue->id);
                        }
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

	public function StatusStore ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Merchant Store')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$store = '';
        		$msg = '';
            	if($id != '') {
                	$store = Store::Where('id', $id)->first();
                }
                $lvalue = session()->get('user');

                if($store) {
                	if($store->is_block == 1) {
                    	$store->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$store->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($store->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				if($lvalue) {
                            if($lvalue->user_type == 1) {
                               return redirect()->route('manage_merchant');
                            } elseif ($lvalue->user_type == 2 || $lvalue->user_type == 3) {
                               return Redirect::to('/manage_store/' . $lvalue->id);
                            }
                        }
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            if($lvalue) {
                            if($lvalue->user_type == 1) {
                               return redirect()->route('manage_merchant');
                            } elseif ($lvalue->user_type == 2 || $lvalue->user_type == 3) {
                               return Redirect::to('/manage_store/' . $lvalue->id);
                            }
                        }
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    if($lvalue) {
                        if($lvalue->user_type == 1) {
                           return redirect()->route('manage_merchant');
                        } elseif ($lvalue->user_type == 2 || $lvalue->user_type == 3) {
                           return Redirect::to('/manage_store/' . $lvalue->id);
                        }
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

	public function StoreBlock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Merchant Store')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$store = Store::where('id',$value)->first();
        					if($store){
        						$store->is_block = 0;
        						$store->save();
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

	public function StoreUnblock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Merchant Store')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$store = Store::where('id',$value)->first();
        					if($store){
        						$store->is_block = 1;
        						$store->save();
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
