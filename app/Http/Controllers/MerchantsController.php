<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Store;
use App\CityManagement;
use App\StateManagements;
use App\CountriesManagement;
use App\EmailSettings;
use App\MerchantsDocuments;

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

class MerchantsController extends Controller
{
    protected $response;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function dashboard () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Merchant Dashboard')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
            	$page = "Merchants";
        		$merchant = User::WhereIn('user_type',[2,3])->get();
        		$store = Store::all();
        		if($merchant) {
        			$merchant['cnt_total_merchant'] = count($merchant);

        			$merchant['tot_active_merchants'] = User::WhereIn('user_type',[2,3])->where('is_block', 1)->get();
        			$merchant['cnt_active_merchant'] = count($merchant['tot_active_merchants']);

        			$merchant['tot_inactive_merchants'] = User::WhereIn('user_type',[2,3])->where('is_block', 0)->get();
        			$merchant['cnt_inactive_merchant'] = count($merchant['tot_inactive_merchants']);

        			$cur_date = date('Y-m-d');
        			$merchant['tot_today_merchants'] = User::WhereIn('user_type',[2,3])->whereDate('created_at', $cur_date)->get();
        			$merchant['cnt_today_merchant'] = count($merchant['tot_today_merchants']);

        			$merchant['tot_last7_merchants'] = User::WhereIn('user_type',[2,3])->whereDate('created_at', '>=', Carbon::now()->subDays(7))->get();
        			$merchant['cnt_last7_merchant'] = count($merchant['tot_last7_merchants']);

        			$merchant['tot_last30_merchants'] = User::WhereIn('user_type',[2,3])->whereDate('created_at', '>=', Carbon::now()->subDays(30))->get();
        			$merchant['cnt_last30_merchant'] = count($merchant['tot_last30_merchants']);

        			$merchant['tot_last12_merchants'] = User::WhereIn('user_type',[2,3])->whereDate('created_at', '>=', Carbon::now()->subMonths(12))->get();
        			$merchant['cnt_last12_merchant'] = count($merchant['tot_last12_merchants']);

        			$merchant['tot_admin_merchants'] = User::where('user_type', 2)->get();
        			$merchant['cnt_admin_merchant'] = count($merchant['tot_admin_merchants']);

        			$merchant['tot_website_merchants'] = User::where('user_type', 3)->get();
        			$merchant['cnt_website_merchant'] = count($merchant['tot_website_merchants']);

        			$year = date('Y');
        			for ($i=1; $i <= 12; $i++) { 
        				$merchant['tot_last_'.date('F',mktime(0,0,0,$i,1,$year)).'_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', str_pad($i, 2, "0", STR_PAD_LEFT))->get();
        				$merchant['cnt_last_'.date('F',mktime(0,0,0,$i,1,$year)).'_merchants'] = count($merchant['tot_last_'.date('F',mktime(0,0,0,$i,1,$year)).'_merchants']);
        			}

        			/*$merchant['tot_last_jan_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '01')->get();
        			$merchant['cnt_last_jan_merchants'] = count($merchant['tot_last_jan_merchants']);

        			$merchant['tot_last_feb_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '02')->get();
        			$merchant['cnt_last_feb_merchants'] = count($merchant['tot_last_feb_merchants']);

        			$merchant['tot_last_mar_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '03')->get();
        			$merchant['cnt_last_mar_merchants'] = count($merchant['tot_last_mar_merchants']);

        			$merchant['tot_last_apr_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '04')->get();
        			$merchant['cnt_last_apr_merchants'] = count($merchant['tot_last_apr_merchants']);

        			$merchant['tot_last_may_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '05')->get();
        			$merchant['cnt_last_may_merchants'] = count($merchant['tot_last_may_merchants']);

        			$merchant['tot_last_jun_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '06')->get();
        			$merchant['cnt_last_jun_merchants'] = count($merchant['tot_last_jun_merchants']);

        			$merchant['tot_last_jul_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '07')->get();
        			$merchant['cnt_last_jul_merchants'] = count($merchant['tot_last_jul_merchants']);

        			$merchant['tot_last_aug_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '08')->get();
        			$merchant['cnt_last_aug_merchants'] = count($merchant['tot_last_aug_merchants']);

        			$merchant['tot_last_sep_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '09')->get();
        			$merchant['cnt_last_sep_merchants'] = count($merchant['tot_last_sep_merchants']);

        			$merchant['tot_last_oct_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '10')->get();
        			$merchant['cnt_last_oct_merchants'] = count($merchant['tot_last_oct_merchants']);

        			$merchant['tot_last_nov_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '11')->get();
        			$merchant['cnt_last_nov_merchants'] = count($merchant['tot_last_nov_merchants']);

        			$merchant['tot_last_dec_merchants'] = User::WhereIn('user_type',[2,3])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', '12')->get();
        			$merchant['cnt_last_dec_merchants'] = count($merchant['tot_last_dec_merchants']);*/

        			if($store) {
        				$merchant['store'] = $store;
        				$merchant['tot_store'] = count($merchant['store']);

        				$merchant['admin_store'] = Store::where('login_type', 1)->get();
        				$merchant['tot_admin_store'] = count($merchant['admin_store']);

        				$merchant['website_store'] = Store::where('login_type', 2)->get();
        				$merchant['tot_website_store'] = count($merchant['website_store']);
        			}
        		}

        		return View::make("merchant.merchant_dashboard")->with(array('merchant'=>$merchant, 'page'=>$page));
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

	public function index () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Merchant Accounts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
        		$page = "Merchants";
                $merchant = User::WhereIn('user_type',[2,3])->OrderBy('id', 'desc')->get();
                if($merchant) {
                	foreach ($merchant as $key => $mer) {
                		$country = CountriesManagement::where('id',$mer->country)->first();
                		$city = CityManagement::where('id',$mer->city)->first();
                		if($country) {
                			$merchant[$key]['country'] = $country->country_name;
                		} else {
                			$merchant[$key]['country'] = "-------";
                		}

                		if($city) {
                			$merchant[$key]['city'] = $city->city_name;
                		} else {
                			$merchant[$key]['city'] = "-------";
                		}

                		$store = Store::where('merchant',$mer->id)->get();
                		$c_store = count($store);
                		if(($store) && (count($store) != 0)) {
                			$merchant[$key]['store'] = $store;
                			$merchant[$key]['c_store'] = $c_store;
                		} else {
                			$merchant[$key]['c_store'] = 0;
                		}
                	}
                }
            	return View::make("merchant.manage_merchant")->with(array('merchant'=>$merchant, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Admin Merchant Accounts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Merchants";
            	return View::make('merchant.add_merchant')->with(array('page'=>$page));
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

    public function SelectState (Request $request) {
    	$country = 0;
		$state_val = 0;
		if($request->ajax() && isset($request->country)){
			$country = $request->country;

			if(isset($request->state)) {
				$state_val = $request->state;
			}

			$data = "";
			if($country != 0) {
				$state = StateManagements::where('country',$country)->get();
				if(($state) && (sizeof($state) != 0)){
					if($state_val != 0) {
	                    foreach ($state as $key => $value) {
	                    	if($state_val == $value->id) {
	                        	$data.='<option selected value="'.$value->id.'">'.$value->state.'</option>';
	                    	} else {
	                        	$data.='<option value="'.$value->id.'">'.$value->state.'</option>';
	                    	}
	                    }
					} else {
						$data = '<option value="0" selected disabled>Select State</option>';
	                    foreach ($state as $key => $value) {
	                        $data.='<option value="'.$value->id.'">'.$value->state.'</option>';
	                    }
					}
                } 			
			}
			echo $data;
		}
    }

    public function SelectCity (Request $request) {
    	$state = 0;
		$city_val = 0;
		if($request->ajax() && isset($request->st)){
			$state = $request->st;

			if(isset($request->city)) {
				$city_val = $request->city;
			}

			$data = "";
			if($state != 0) {
				$city = CityManagement::where('state',$state)->get();
				if(($city) && (sizeof($city) != 0)){
					if($city_val != 0) {
	                    foreach ($city as $key => $value) {
	                    	if($city_val == $value->id) {
	                        	$data.='<option selected value="'.$value->id.'">'.$value->city_name.'</option>';
	                    	} else {
	                        	$data.='<option value="'.$value->id.'">'.$value->city_name.'</option>';
	                    	}
	                    }
					} else {
						$data = '<option value="0" selected disabled>Select City</option>';
	                    foreach ($city as $key => $value) {
	                        $data.='<option value="'.$value->id.'">'.$value->city_name.'</option>';
	                    }
					}
                } 			
			}
			echo $data;
		}
    }

    public function store(Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Merchant Accounts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
            	$page = "Merchants";
            	$rules = array(
                    'first_name'              => 'required',
                    'last_name'               => 'nullable',
                    'bussiness_name'          => 'required',
                    'buss_reg_no'             => 'nullable',
                    'is_gst'                  => 'required',
                    'gstn_no'                 => 'required_if:is_gst,==,1',
                    'email'                   => 'required|email|unique:users,email',
                    'password'                => 'required|min:5',
                    'password_salt'           => 'required|min:5|same:password',
                    'profile_img'             => 'nullable',
                    'remember_token'          => 'nullable',
                    'country'                 => 'required',
                    'state'                   => 'required',
                    'city'                    => 'required',
                    'phone'                   => 'required|numeric|unique:users,phone',
                    'gender'                  => 'required',
                    'address1'                => 'required',
                    'address2'                => 'required',
                    'pincode'                 => 'required|numeric|integer',
                    'commission'              => 'required|numeric',
                    'return_commission'       => 'nullable|numeric',
                    'payment_account_details' => 'nullable',
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
                    'is_approved'             => 'nullable',
                    'is_block'                => 'nullable',
                    'user_type'               => 'required',
                    'login_type'              => 'nullable',

                    'd_name'                  => 'required',
                    'd_image'                 => 'required',
                );

                $messages=[
                    'password_salt.required'=>'The confirm password field is required.',
                    'password_salt.min'=>'The confirm password must be at least 5 characters.',
                    'password_salt.same'=>'The confirm password and password must match.',
                    'd_image.required'=>'The document name and file field is required.',
                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
            	   	return View::make('merchant.add_merchant')->withErrors($validator)->with(array('page'=>$page));
                } else {
                    $data = Input::all();
                    $ps = "gj";
                    $pe = "ja";
                	$merchant = new User();

                    if($merchant) {
                    	$img_files111 = Input::file('profile_img');
                        if(isset($img_files111)) {
                            $file_name = $img_files111->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/profile_img/'.$date;
                            $file_path = 'images/profile_img/'.$date;
                            $img_files111->move($file_path, $file_name);
                            $merchant->profile_img = $date.'/'.$file_name;
                        } else {
                            $merchant->profile_img = NULL;
                        }

        	            $merchant->first_name                = $data['first_name'];
        	            $merchant->last_name                 = $data['last_name'];
        	            $merchant->bussiness_name            = $data['bussiness_name'];
        	            $merchant->buss_reg_no               = $data['buss_reg_no'];
                        $merchant->is_gst                    = $data['is_gst'];
                        $merchant->gstn_no                   = $data['gstn_no'];
        	            $merchant->email                     = $data['email'];
        	            $merchant->password                  = md5($data['password']);
        	            $merchant->password_salt             = $ps.$data['password_salt'].$pe;
        	            $merchant->country                   = $data['country'];
        	            $merchant->state                     = $data['state'];
        	            $merchant->city                      = $data['city'];
        	            $merchant->phone                     = $data['phone'];
        	            $merchant->gender                    = $data['gender'];
        	            $merchant->address1                  = $data['address1'];
        	            $merchant->address2                  = $data['address2'];
        	            $merchant->pincode                   = $data['pincode'];
        	            if($data['commission']) {
                            $merchant->commission            = $data['commission'];
                        } else {
                            $merchant->commission            = 0;
                        }

                        if($data['return_commission']) {
                            $merchant->return_commission     = $data['return_commission'];
                        } else {
                            $merchant->return_commission     = 0;
                        }
        	            $merchant->payment_account_details   = $data['payment_account_details'];
        	            $merchant->user_type                 = $data['user_type'];
        	            if(isset($data['is_approved'])) {
        	            	$merchant->is_approved           = $data['is_approved'];
        	            } else {
        		            $merchant->is_approved           = 0;
        	            }
        	            $merchant->is_block                  = 1;
        	            $merchant->login_type                = 1;

                    	$pass = $data['password'];
                        if($merchant->save()) {
                        	if($data['d_name'] && count($data['d_name']) != 0) {
        		            	foreach ($data['d_name'] as $key => $value) {
        		            		$d_images = new MerchantsDocuments();

        		            		if(isset($data['d_image'][$key])) {
        		            			$file_name = $data['d_image'][$key]->getClientOriginalName();
        			                    $date = date('M-Y');
        			                    // $file_path = '../public/documents/'.$date;
        			                    $file_path = 'documents/'.$date;
        			                    $data['d_image'][$key]->move($file_path, $file_name);
        			                    $d_images->image       = $date.'/'.$file_name;
        		                    } else {
        			                    $d_images->image       = NULL;
        		                    }

        		            		$d_images->merchant  = $merchant->id; 

        	            			$d_images->d_name      = $value;	 
        		            		$d_images->is_block    = 1;

        		            		$d_images->save();
        		            	}
        		            }

                        	$store = new Store();

        		            if($store) {
        			            $store->merchant                  = $merchant->id;
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

        			            if($merchant->user_type == 2) {
        			            	$store->login_type                = 1;
        			            } else {
        			            	$store->login_type                = 2;
        			            }

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

        		                if($store->save()) {
        		                	$admin_mail = "";
            						$admin  = User::where('user_type', 1)->first();
        		                	if($admin) {
        		                		$admin_mail = $admin->email;
        		                	}

        		                	if($admin_mail == "") {
        		                		$admin_mail = "info.ecambiar@gmail.com";
        		                	}

        		                	$logos = \DB::table('logo_settings')->first();
        		                    $logo_path = 'images/logo';
        		                    $logo = "";
        		                    if($logos) {
        		                        $logo = asset($logo_path.'/'.$logos->logo_image);
        		                    } else {
        		                        $logo = asset('images/logo.png');
        		                    }
        		                    
                                    $general = \DB::table('general_settings')->first();
                                    $site_name = "ECambiar";
                                    if($general){
                                        $site_name = $general->site_name;
                                    } 

        		                	$admin_mail;
        		                	$to = $merchant->email;
        							$subject = "Create Merchants Details";

        							$message = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
        	                            <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
        	                            <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
        	                                <h2 style="color: #ff5c00;margin-top: 0px;">Merchants Details</h2>
        	                                <table align="center" style=" text-align: center;">
        	                                    <tr>
        	                                        <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Name</th>
        	                                        <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$merchant->first_name.' '.$merchant->last_name.'</td>
        	                                    </tr>
        	                                    <tr>
        	                                        <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">E-Mail</th>
        	                                        <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : <a href="mailto:'.$merchant->email.'" target="_blank" style="color: #333;text-decoration: none;">'.$merchant->email.'</a></td>
        	                                    </tr>
        	                                    <tr>
        	                                        <th style="text-align: center;text-transform: uppercase;color: #333;padding-bottom: 12px;font-weight:bold;width: 120px;">Password</th>
        	                                        <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$pass.'</td>
        	                                    </tr>
        	                                </table>
        	                                <p>Your Password is <span style="font-weight:bold"> '.$pass.' </span></p>
        	                                <p>Use this password to Login</p>
        	                                <p>Login URL is : '.route('merchant').'</p>
        	                                <p>Thank You.</p>
        	                                 <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
        	                                <p>Thanks & Regards,</p>
        	                                <p><a href="'.route('home').'">'.$site_name.'</a></p>
        	                            </div>
        	                        </div>';


        							// Always set content-type when sending HTML email
        							$headers = "MIME-Version: 1.0" . "\r\n";
        							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        							//mail headers
        							$headers.= "From: noreply@ecambiar.com" . "\r\n";

        							if (mail($to,$subject,$message,$headers) && mail($admin_mail,$subject,$message,$headers)) {
        								Session::flash('message', 'Add & Mail Send Successfully!'); 
        								Session::flash('alert-class', 'alert-success');
        								return redirect()->route('manage_merchant');
        							} else {
        								Session::flash('message', 'Add Successfully, but Mail Send Failed!'); 
        								Session::flash('alert-class', 'alert-danger');
        				                return redirect()->route('manage_merchant');
        							}
        			            } else{
        			            	Session::flash('message', 'Added Failed!'); 
        							Session::flash('alert-class', 'alert-danger');
        			                return redirect()->route('manage_merchant');
        			            }  
        		            } else{
        		            	Session::flash('message', 'Added Failed!'); 
        						Session::flash('alert-class', 'alert-danger');
        		                return redirect()->route('manage_merchant');
        		            }
        	            } else{
        	            	Session::flash('message', 'Added Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_merchant');
        	            }  
                    } else{
                    	Session::flash('message', 'Added Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_merchant');
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
                ->where('B.module_name', '=', 'Admin Merchant Accounts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
        		$page = "Merchants";
        		$merchant = User::where('id',$id)->first();
        		if($merchant) {
        			$docs = MerchantsDocuments::Where('merchant', $merchant->id)->get();
        			if($docs) {
        				$merchant['docs'] = $docs;
        			} else {
        				$merchant['docs'] = NULL;
        			}
                    
                    return View::make("merchant.view_merchant")->with(array('merchant'=>$merchant, 'page'=>$page));
        		} else {
                    Session::flash('message', 'View Not Possible!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->back();
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
                ->where('B.module_name', '=', 'Admin Merchant Accounts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Merchants";
                $merchant = User::where('id',$id)->first();
                if($merchant) {
                    $docs = MerchantsDocuments::Where('merchant', $merchant->id)->get();
                    if($docs) {
                        $merchant['docs'] = $docs;
                    } else {
                        $merchant['docs'] = NULL;
                    }
                }
                return View::make("merchant.edit_merchant")->with(array('merchant'=>$merchant, 'page'=>$page));
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
                ->where('B.module_name', '=', 'Admin Merchant Accounts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
        		$page = "Merchants";
        		$id = Input::get('merchant_id');
                $merchant = '';
                if($id != '') {
                	$merchant = User::Where('id', $id)->first();
                }

                if($merchant) {
        			$rules = array(
        	            'first_name'              => 'required',
        	            'last_name'               => 'nullable',
        	            'bussiness_name'          => 'required',
        	            'buss_reg_no'             => 'nullable',
                        'is_gst'                  => 'required',
                        'gstn_no'                 => 'required_if:is_gst,==,1',
        	            'email'                   => 'required|email|unique:users,email,'.$id.',id',
        	            'country'                 => 'required',
        	            'state'                   => 'required',
        	            'city'                    => 'required',
        	            'phone'                   => 'required|numeric|unique:users,phone,'.$id.',id',
        	            'gender'                  => 'required',
        	            'address1'                => 'required',
        	            'address2'                => 'required',
        	            'pincode'                 => 'required|numeric|integer',
        	            'commission'              => 'required|numeric',
        	            'return_commission'       => 'nullable|numeric',
        	            'profile_img'             => 'nullable',
        	            'payment_account_details' => 'nullable',
        	            'is_approved'             => 'nullable',
        	            'is_block'                => 'nullable',
        	            'user_type'               => 'required',
        	            'login_type'              => 'nullable',

        	            'd_name'                  => 'required',
                    	'd_image'                 => 'nullable',
        	        );
        	        $messages=[
        	            'd_image.required'=>'The document name and file field is required.',
        	        ];
        	        $validator = Validator::make(Input::all(), $rules,$messages);

        	        if ($validator->fails()) {
        	        	if($merchant) {
        					$docs = MerchantsDocuments::Where('merchant', $merchant->id)->get();
        					if($docs) {
        						$merchant['docs'] = $docs;
        					} else {
        						$merchant['docs'] = NULL;
        					}
        				}
        	    	   	return Redirect::to('/edit_merchant/' . $id)->withErrors($validator)->with(array('merchant'=>$merchant, 'page'=>$page));
        	        } else {
        	            $data = Input::all();
        	            
        	            $img_files111 = Input::file('profile_img');
                        if(isset($img_files111)) {
                            $file_name = $img_files111->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/profile_img/'.$date;
                            $file_path = 'images/profile_img/'.$date;
                            $img_files111->move($file_path, $file_name);
                            $merchant->profile_img = $date.'/'.$file_name;
                        } else if (isset($data['old_profile_img'])) {
                            $merchant->profile_img = $data['old_profile_img'];
                        } else {
                            $merchant->profile_img = NULL;
                        }

        	            $merchant->first_name                = $data['first_name'];
        	            $merchant->last_name                 = $data['last_name'];
        	            $merchant->bussiness_name            = $data['bussiness_name'];
        	            $merchant->buss_reg_no               = $data['buss_reg_no'];
                        $merchant->is_gst                    = $data['is_gst'];
                        $merchant->gstn_no                   = $data['gstn_no'];
        	            $merchant->email                     = $data['email'];
        	            $merchant->country                   = $data['country'];
        	            $merchant->state                     = $data['state'];
        	            $merchant->city                      = $data['city'];
        	            $merchant->phone                     = $data['phone'];
        	            $merchant->gender                    = $data['gender'];
        	            $merchant->address1                  = $data['address1'];
        	            $merchant->address2                  = $data['address2'];
        	            $merchant->pincode                   = $data['pincode'];
        	            if($data['commission']) {
                            $merchant->commission            = $data['commission'];
                        } else {
                            $merchant->commission            = 0;
                        }

                        if($data['return_commission']) {
                            $merchant->return_commission     = $data['return_commission'];
                        } else {
                            $merchant->return_commission     = 0;
                        }
        	            $merchant->payment_account_details   = $data['payment_account_details'];
        	            $merchant->user_type                 = $data['user_type'];
        	            $merchant->is_approved               = $data['is_approved'];
        	            $merchant->is_block                  = 1;
        	            $merchant->login_type                = 1;

        	            if($merchant->save()) {
        	            	if($data['d_name'] && count($data['d_name']) != 0) {
        		            	MerchantsDocuments::where('merchant', $merchant->id)->delete();
        		            	foreach ($data['d_name'] as $key => $value) {
        		            		$d_images = new MerchantsDocuments();

        		            		if(isset($data['d_image'][$key])) {
        		            			$file_name = $data['d_image'][$key]->getClientOriginalName();
        			                    $date = date('M-Y');
        			                    // $file_path = '../public/documents/'.$date;
        			                    $file_path = 'documents/'.$date;
        			                    $data['d_image'][$key]->move($file_path, $file_name);
        			                    $d_images->image       = $date.'/'.$file_name;
        		                    } else if (isset($data['old_d_image'][$key])) {
        			                    $d_images->image       = $data['old_d_image'][$key];
        		                    } else {
        			                    $d_images->image       = NULL;
        		                    }

        		            		$d_images->merchant  = $merchant->id; 

        	            			$d_images->d_name      = $value;	 
        		            		$d_images->is_block    = 1;

        		            		$d_images->save();
        		            	}
        		            }

        	            	Session::flash('message', 'update Successfully!'); 
        					Session::flash('alert-class', 'alert-success');
        					return redirect()->route('manage_merchant');

        	            } else{
        	            	Session::flash('message', 'update Failed!'); 
        					Session::flash('alert-class', 'alert-danger');
        	                return redirect()->route('manage_merchant');
        	            }   
        	        }
                } else{
                	Session::flash('message', 'update Failed!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_merchant');
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

	public function ApproveMerchant ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Merchant Accounts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$merchant = '';
        		$msg = '';
            	if($id != '') {
                	$merchant = User::Where('id', $id)->first();
                }

                if($merchant) {
                	if($merchant->is_approved == 1) {
                    	$merchant->is_approved        = 0;
                    	$msg = "Disapproved";
                	} else {
                		$merchant->is_approved   = 1;
                        $merchant->approved_date = date('Y-m-d');
                    	$msg = "Approved";
                	}
        	        
        	        if($merchant->save()) {
        	        	$adm = User::where('user_type', 1)->where('is_block', 1)->first();
                        $admin_email = "info@ecambiar.com";
                        if($adm) {
                            $admin_email = $adm->email;
                        }

                        $logos = \DB::table('logo_settings')->first();
                        $logo_path = 'images/logo';
                        $logo = "";
                        if($logos) {
                            $logo = asset($logo_path.'/'.$logos->logo_image);
                        } else {
                            $logo = asset('images/logo.png');
                        }

                        $general = \DB::table('general_settings')->first();
                        $site_name = "ECambiar";
                        if($general){
                            $site_name = $general->site_name;
                        } else {
                            $site_name = "ECambiar";
                        }

                        $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                        $headers.= "MIME-Version: 1.0\r\n";
                        // $headers.= "From: $admin_email" . "\r\n";
                        $headers.= "From: noreply@ecambiar.com" . "\r\n";
                        $to = $merchant->email;
                        $subject = "Merchants Approvel";

                        $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                    <h2 style="color: #ff5c00;margin-top: 0px;">Your Registration '.$msg.' Successfully</h2>
                                    <p>Our Admin Team Evaluate and '.$msg.' Successfully.</p>
                                    <p>Now You Can Login and Continued...</p>
                                    <p>Login URL : <a href="'.route('merchant').'" target="_blank" style="color: ff5c00;text-decoration: none;">'.route('merchant').'</a></p>
                                    <p>Any Queries Please email at <a href="mailto:info@ecambiar.com" target="_blank" style="color: ff5c00;text-decoration: none;">info@ecambiar.com</a>.</p>
                                    <p></p>
                                    <p>Thank You.</p>
                                     <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                    <p>Thanks & Regards,</p>
                                    <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                </div>
                            </div>';
                        
                        if($merchant->is_approved == 1) {
                        	if (mail($to,$subject,$txt,$headers) && mail($admin_email,$subject,$txt,$headers)) {
        	                    Session::flash('message', $msg.' Successfully'); 
        						Session::flash('alert-class', 'alert-success');
        	                }

        	                if($merchant->phone) {
        	                    $text = "Your Account get Verified & Approved by Administrator and Now You can Login. Login URL : ".route('merchant').", ecambiar.com";
        	                    $text = urlencode($text);
        	 
        	                    $curl = curl_init();
        	                 
        	                    // Send the POST request with cURL
        	                    curl_setopt_array($curl, array(
        	                    CURLOPT_RETURNTRANSFER => 1,
        	                    CURLOPT_URL => "http://smschub.com/api/sms/format/json",
        	                    CURLOPT_POST => 1,
        	                    CURLOPT_CUSTOMREQUEST => 'POST',
        	                    CURLOPT_HTTPHEADER => array('X-Authentication-Key:01fe318b290f9f9cb686a6bc28a4affa', 'X-Api-Method:MT'),
        	                    CURLOPT_POSTFIELDS => array(
        	                        'mobile' => $merchant->phone,
        	                        'route' => 'TL',
        	                        'text' => $text,
        	                        'sender' => 'GJICAM')));
        	                 
        	                    // Send the request & save response to $response
        	                    $response = curl_exec($curl);
        	                 
        	                    // Close request to clear up some resources
        	                    curl_close($curl);
        	                    $response = json_decode($response);
        	                    // Print response

        	                    if(isset($response->data->status) && $response->data->status == "success") {
        	                        Session::flash('message', $msg.' Successfully'); 
        							Session::flash('alert-class', 'alert-success');
        	                    }
        	                }
                        }

                        Session::flash('message', $msg.' Successfully!'); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_merchant');
        	        } else{
        	        	Session::flash('message', 'Approved or Disapproved Failed!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_merchant');
        	        }
                } else{
                	Session::flash('message', 'Approved or Disapproved Not Possible!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_merchant');
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

	public function StatusMerchant ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Merchant Accounts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		$merchant = '';
        		$msg = '';
            	if($id != '') {
                	$merchant = User::Where('id', $id)->first();
                }

                if($merchant) {
                	if($merchant->is_block == 1) {
                    	$merchant->is_block        = 0;
                    	$msg = "Blocked Successfully";
                	} else {
                		$merchant->is_block        = 1;
                    	$msg = "Unblocked Successfully";
                	}
        	        
        	        if($merchant->save()) {
        	        	Session::flash('message', $msg); 
        				Session::flash('alert-class', 'alert-success');
        				return redirect()->route('manage_merchant');
        	        } else{
        	        	Session::flash('message', 'Failed Block or Unblock!'); 
        				Session::flash('alert-class', 'alert-danger');
        	            return redirect()->route('manage_merchant');
        	        }
                } else{
                	Session::flash('message', 'Failed Block or Unblock!'); 
        			Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_merchant');
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

	public function MerchantBlock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Merchant Accounts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$merchant = User::where('id',$value)->WhereIn('user_type',[2,3])->first();
        					if($merchant){
        						$merchant->is_block = 0;
        						$merchant->save();
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

	public function MerchantUnblock( Request $request) {	
		$ids = array();
		$error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'Admin Merchant Accounts')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
        		if($request->ajax() && isset($request->ids)){
        			$ids = $request->ids;
        			if(sizeof($ids) != 0) {
        				foreach ($ids as $key => $value) {
        					$merchant = User::where('id',$value)->WhereIn('user_type',[2,3])->first();
        					if($merchant){
        						$merchant->is_block = 1;
        						$merchant->save();
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