<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CityManagement;
use App\StateManagements;
use App\CountriesManagement;
use App\EmailSettings;
use App\MerchantsDocuments;
use App\Carts;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Validator;
use Response;
use Input;
use DB;
use View;
use Session;
use Redirect;
use URL;
use Auth;

class oldUsersController extends Controller
{   
    protected $respose;
    protected $sub_domain;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->sub_domain = "";
    }

	public function Login () {
        $sub_domain = $this->sub_domain;
        if(isset($_COOKIE["user"]) && !empty($_COOKIE["user"])) {
            $cook = $_COOKIE["user"];
            $cook = json_decode($cook);
            $user = User::Where('id', $cook->id)->first();
            if($user) {
                if(($user->user_type == 4)) {
                    return redirect()->route('signin');
                    /*if($user->verification == 1) {
                        if($user->is_block == 1) {
                            session()->forget('user');
                            Session::flash('message', 'Login Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            Session::put('user', $user);

                            $users = session()->get('user');
                            $ses_carts = session()->get('cart');
                            $cartData = array();

                            if(isset($ses_carts)) {
                                Carts::Where('user_id', $users->id)->delete();
                                foreach ($ses_carts as $key => $value) {
                                    $carts = new Carts();
                                    if($carts) {
                                        $carts->product_id  = $value['product_id'];
                                        $carts->user_id     = $users->id;
                                        $carts->name        = (isset($value['name'])) ? $value['name'] : NULL;
                                        $carts->original_price  = (isset($value['original_price'])) ? $value['original_price'] : 0;
                                        $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
                                        $carts->total_price       = (isset($value['total_price'])) ? $value['total_price'] : 0;
                                        $carts->tax  = (isset($value['tax'])) ? $value['tax'] : NULL;
                                        $carts->tax_type  = (isset($value['tax_type'])) ? $value['tax_type'] : NULL;
                                        $carts->service_charge  = (isset($value['service_charge'])) ? $value['service_charge'] : NULL;
                                        $carts->shiping_charge  = (isset($value['shiping_charge'])) ? $value['shiping_charge'] : NULL;
                                        $carts->image       = (isset($value['image'])) ? $value['image'] : NULL;
                                        $carts->qty         = (isset($value['qty'])) ? $value['qty'] : 1;
                                        $carts->notes       = (isset($value['notes'])) ? $value['notes'] : NULL;
                                        $carts->is_block    = 1;

                                        $carts->save();
                                    }
                                }
                            }

                            return redirect()->route('home');
                        } else {
                            session()->forget('user');
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
                            Session::flash('message', 'Admin Has Blocked Your Account!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('home');
                        }
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        Session::flash('message', 'Not Activation Your Account!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('home');
                    }*/
                } else {
                    session()->forget('user');
                    
                    Session::flash('message', 'Login Successfully!'); 
                    Session::flash('alert-class', 'alert-success');
                    Session::put('user', $user);
                    
                    // session(['user' => $user]);
                    if($user->user_type == 1){
                        // $ses = Session::get('user');
                        // session()->get('user');
                        return redirect()->route('dashboard');
                    } else if(($user->user_type == 2) || ($user->user_type == 3)) {
                        if($user->is_block == 1) {
                            if($user->is_approved == 1) {
                                return redirect()->route('merchants_dashboard');
                            } else {
                                session()->forget('user');
                                if(isset($_COOKIE["user"])) {
                                    setcookie ("user","");
                                }
                                Session::flash('message', 'Admin Has Blocked Your Account!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('home');
                            } 
                        } else {
                            session()->forget('user');
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
                            Session::flash('message', 'Admin Has Blocked Your Account!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('home');
                        }
                    } else if(($user->user_type == 4)){
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        return redirect()->route('signin');
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        return redirect()->route('home');
                    }
                }
            } else {
                Session::flash('message', 'Login Failed!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('logout');
            }
        } else {
            return View::make('user.admin')->with(array('sub_domain'=>$sub_domain));
        }
    }

    public function CheckLogin (Request $request) {
    	$rules = array(
            'email'                   => 'required',
            // 'email'                   => 'required|email|exists:users,email',
            'password'                => 'required',
        );

        $messages=[
            'password.required'=>'The password field is required.',
            'email.required'=>'The email or mobile no field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
    	   	return View::make('user.admin')->withErrors($validator);
        } else {
        	$data = Input::all();
        	$user = User::where('email', $data['email'])->where('is_block', 1)->where('is_approved', 1)->first();
            if(!$user) {
                $user = User::where('phone', $data['email'])->where('is_block', 1)->where('is_approved', 1)->first();
            }

            if($user) {
            	$pass = md5($data['password']);
            	if ($user->password == $pass) {
                    if(($user->user_type == 4)) {
                        if($user->verification == 1) {
                            session()->forget('user');
                            Session::flash('message', 'Login Successfully!'); 
                            Session::flash('alert-class', 'alert-success');
                            Session::put('user', $user);
                            $user->{'pass'} = $data['password'];
                            $ck = json_encode($user);
                            if(isset($data["remember"]) && !empty($data["remember"])) {
                                // setcookie("user",$ck, time() + (60 * 60 * 5), "/");
                                setcookie("user",$ck, time() + (60 * 60 * 5));
                            } else {
                                if(isset($_COOKIE["user"])) {
                                    setcookie ("user","");
                                }
                            }

                            $users = session()->get('user');
                            $ses_carts = session()->get('cart');
                            $cartData = array();

                            if(isset($ses_carts) != 0) {
                                Carts::Where('user_id', $users->id)->delete();
                                foreach ($ses_carts as $key => $value) {
                                    $carts = new Carts();
                                    if($carts) {
                                        $carts->product_id  = $value['product_id'];
                                        $carts->user_id     = $users->id;
                                        $carts->name        = (isset($value['name'])) ? $value['name'] : NULL;
                                        $carts->original_price  = (isset($value['original_price'])) ? $value['original_price'] : 0;
                                        $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
                                        $carts->total_price       = (isset($value['total_price'])) ? $value['total_price'] : 0;
                                        $carts->tax  = (isset($value['tax'])) ? $value['tax'] : NULL;
                                        $carts->tax_type  = (isset($value['tax_type'])) ? $value['tax_type'] : NULL;
                                        $carts->service_charge  = (isset($value['service_charge'])) ? $value['service_charge'] : NULL;
                                        $carts->shiping_charge  = (isset($value['shiping_charge'])) ? $value['shiping_charge'] : NULL;
                                        $carts->image       = (isset($value['image'])) ? $value['image'] : NULL;
                                        $carts->qty         = (isset($value['qty'])) ? $value['qty'] : 1;
                                        $carts->notes       = (isset($value['notes'])) ? $value['notes'] : NULL;
                                        $carts->is_block    = 1;

                                        $carts->save();
                                    }
                                }
                            }

                            return redirect()->route('home');
                        } else {
                            session()->forget('user');
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
                            Session::flash('message', 'Not Activation Your Account!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->back();
                        }
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        
    					Session::flash('message', 'Login Successfully!'); 
    					Session::flash('alert-class', 'alert-success');
    					Session::put('user', $user);
                        if(isset($data["remember"]) && !empty($data["remember"])) {
                            setcookie ("user",$user,time()+ (60 * 60 * 5));
                        } else {
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
                        }
    					// session(['user' => $user]);
                		if($user->user_type == 1){
                			// $ses = Session::get('user');
                			// session()->get('user');
    						return redirect()->route('dashboard');
                		} else if(($user->user_type == 2) || ($user->user_type == 3)){
    						return redirect()->route('merchants_dashboard');
                		} else if(($user->user_type == 4)){
                            session()->forget('user');
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
    						return redirect()->route('signin');
                		} else {
                            session()->forget('user');
    						return redirect()->route('home');
                		}
                    }
            	} else {
            		Session::flash('message', 'Your Password is Wrong!'); 
					Session::flash('alert-class', 'alert-danger');
                    return redirect()->back();
	                // return redirect()->route('admin');
            	}
            } else{
            	Session::flash('message', 'Login Failed!'); 
				Session::flash('alert-class', 'alert-danger');
                // return redirect()->route('admin');
                return redirect()->back();
            }
        }
    }

    public function Logout () {
    	$value = session()->get('user');
        if(isset($_COOKIE["user"])) {
            setcookie ("user","");
        }
    	// print_r($value);die();
    	if($value) {
    		if($value->user_type == 4) {
    			session()->forget('user');
                session()->forget('cart');
	 			return redirect()->route('home');
    		} else {
    			session()->forget('user');
	 			return redirect()->route('admin');
    		} 
    	} else {
    		return redirect()->route('home');
    	}
    }

    public function Forgot () {
    	return View::make('user.forgot');
    }

    public function CheckForgot (Request $request) {
    	$rules = array(
            'email_id'                   => 'nullable|email|exists:users,email',
            'mobnumber'                  => 'nullable|numeric|digits:10|exists:users,phone',
        );

        $messages=[
            'mobnumber.numeric' => 'The Mobile Number field is only numbers.',
            'mobnumber.digits'  => 'The Mobile Number field is only 10 numbers allowed.',
            'mobnumber.exists'  => 'The Mobile Number has not Exist.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
    	   	// return View::make('user.forgot')->withErrors($validator);
            return Redirect::back()->withInput()->withErrors($validator);
        } else {
        	$data = Input::all();
            // print_r($data);die();
            $user = User::where('email', $data['email_id'])->where('is_block', 1)->first();
        	$mob_user = User::where('phone', $data['mobnumber'])->where('is_block', 1)->first();

            if ($user) {
            	$user->remember_token = uniqid().time();
            	if($user->save()) {
            		$adm = User::where('user_type', 1)->where('is_block', 1)->first();
            		$admin_email = "teamadsdev5@gmail.com";
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
            		$site_name = "InterCambiar";
            		if($general){
            			$site_name = $general->site_name;
            		} else {
            			$site_name = "InterCambiar";
        			} 

            		$name = $user->first_name.' '.$user->last_name;
            		$email = $user->email;
            		$reset_pw = $user->remember_token;

            		$headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    // $headers.= "From: $admin_email" . "\r\n";
            		$to = $email;
					$subject = "Verify your Account";
					$txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 50%;margin: auto;position: relative;background-color: white;">
                            <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                            <div style="padding: 20px;background-color: #ff5c00;color: white;">
                                <h2 style="color: white;margin-top: 0px;">Reset Password Code</h2>
                                <table>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;padding-bottom: 5px;color: white;">Name</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$name.'</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;padding-bottom: 5px;color: white;">E-Mail</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : <a href="mailto:'.$email.'" target="_blank" style="color: white;text-decoration: none;">'.$email.'</a></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;color: white;">Reset Code</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$reset_pw.'</td>
                                    </tr>
                                </table>
                                <p>Your Password Reset Code is '.$reset_pw.'</p>
								<p>Use this Code to change your Password</p>
								<p>Thank You.</p>
								<p></p>
								<p></p>
								<p>Thanks & Regards,</p>
								<p><a href="'.route('home').'">'.$site_name.'</a></p>
                            </div>
                        </div>';
					
					
					if(mail($to,$subject,$txt,$headers)){
						Session::flash('message', 'Mail Send Successfully!'); 
						Session::flash('alert-class', 'alert-success');
						return redirect()->route('reset');
					} else {
						Session::flash('message', 'Mail Send Failed!'); 
						Session::flash('alert-class', 'alert-danger');
		                return redirect()->route('forgot');	
					}
            	}
            } elseif ($mob_user) {
                $otp = mt_rand(100000, 999999);
                $mob_user->remember_token = $otp;
                if($mob_user->save()) {
                    $text = "Please Use this ".$otp." reference code to reset the password,Ecambiar.";
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
                        'mobile' => $mob_user->phone,
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
                        Session::flash('message', 'OTP Message Send Successfully!'); 
                        Session::flash('alert-class', 'alert-success');
                        return redirect()->route('reset');
                    } else {
                        Session::flash('message', 'OTP Message Send Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('forgot');
                    }
                }
            } else{
            	Session::flash('message', 'It\'s not valid Email or Phone Number!'); 
				Session::flash('alert-class', 'alert-danger');
                return redirect()->route('admin');
            }
        }
    }

    public function Reset () {
    	return View::make('user.reset');
    }

    public function ResetPassword (Request $request) {
    	$rules = array(
            'remember_token'          => 'required|exists:users,remember_token',
            'password'                => 'required|min:5',
            'password_salt'           => 'required|min:5|same:password',
        );

        $messages=[
            'password_salt.required' => 'The confirm password field is required.',
            'password_salt.min'      => 'The confirm password must be at least 5 characters.',
            'password_salt.same'     => 'The confirm password and password must match.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
    	   	return View::make('user.reset')->withErrors($validator);
        } else {
        	$data = Input::all();
            $ps = "gj";
            $pe = "ja";

        	$user = User::where('remember_token', $data['remember_token'])->where('is_block', 1)->first();

            if($user) {
	            $user->password                  = md5($data['password']);
	            $user->password_salt             = $ps.$data['password_salt'].$pe;
	            $user->remember_token            = NULL;


            	$pass = $data['password'];
                if($user->save()) {
                	$adm = User::where('user_type', 1)->where('is_block', 1)->first();
            		$admin_email = "teamadsdev5@gmail.com";
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
            		$site_name = "InterCambiar";
            		if($general){
            			$site_name = $general->site_name;
            		} else {
            			$site_name = "InterCambiar";
        			} 

            		$name = $user->first_name.' '.$user->last_name;
            		$email = $user->email;
            		$password = $pass;

            		$headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    // $headers.= "From: $admin_email" . "\r\n";
            		$to = $email;
					$subject = "Change your Password";
					$txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 50%;margin: auto;position: relative;background-color: white;">
                            <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                            <div style="padding: 20px;background-color: #ff5c00;color: white;">
                                <h2 style="color: white;margin-top: 0px;">Reset Password Code</h2>
                                <table>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;padding-bottom: 5px;color: white;">Name</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$name.'</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;padding-bottom: 5px;color: white;">E-Mail</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : <a href="mailto:'.$email.'" target="_blank" style="color: white;text-decoration: none;">'.$email.'</a></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;color: white;">Password</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$password.'</td>
                                    </tr>
                                </table>
                                <p>Your Password is '.$password.'</p>
								<p>Use this password to Login</p>
								<p>Thank You.</p>
								<p></p>
								<p></p>
								<p>Thanks & Regards,</p>
								<p><a href="'.route('home').'">'.$site_name.'</a></p>
                            </div>
                        </div>';
					
					
					if(mail($to,$subject,$txt,$headers)){
						Session::flash('message', 'Password Changed and Mail Send Successfully!'); 
						Session::flash('alert-class', 'alert-success');
						return redirect()->route('admin');
					} else {
						Session::flash('message', 'Password Changed and Mail Send Failed!'); 
						Session::flash('alert-class', 'alert-danger');
		                return redirect()->route('forgot');	
					}
	            } else{
	            	Session::flash('message', 'Password Changed Failed!'); 
					Session::flash('alert-class', 'alert-danger');
	                return redirect()->route('forgot');
	            } 
            } else{
            	Session::flash('message', 'You\'re Reset Code Is Not Valid!'); 
				Session::flash('alert-class', 'alert-danger');
                return redirect()->route('reset');
            }
        }
    }

    public function MyProfile () {
        $page = "Users";
        $profile = session()->get('user');
        if($profile) {
            return View::make('user.my_profile')->with(array('profile'=>$profile, 'page'=>$page));
        } else {
            return redirect()->route('admin');
        }
    }

    public function EditProfile () {
        $page = "Users";
    	$user = session()->get('user');
    	if($user) {
    		return View::make('user.edit_profile')->with(array('user'=>$user, 'page'=>$page));
    	} else {
    		return redirect()->route('admin');
    	}
    }

    public function UpdateProfile (Request $request) {
        $page = "Users";
        $id = Input::get('user_id');
        $user = '';
        if($id != '') {
            $user = User::Where('id', $id)->first();
        }

        if($user) {
            $rules = array(
                'first_name'              => 'required',
                'last_name'               => 'nullable',
                'bussiness_name'          => 'nullable',
                'buss_reg_no'             => 'nullable',
                'email'                   => 'required|email|unique:users,email,'.$id.',id',
                'country'                 => 'required',
                'state'                   => 'required',
                'city'                    => 'required',
                'phone'                   => 'required|numeric|unique:users,phone,'.$id.',id',
                'phone2'                  => 'nullable|numeric|unique:users,phone2,'.$id.',id',
                'gender'                  => 'required',
                'address1'                => 'required',
                'address2'                => 'required',
                'pincode'                 => 'required|numeric|integer',
                'commission'              => 'nullable|numeric',
                'return_commission'       => 'nullable|numeric',
                'payment_account_details' => 'nullable',
                'profile_img'             => 'nullable',
                'is_approved'             => 'nullable',
                'is_block'                => 'nullable',
                'user_type'               => 'nullable',
                'login_type'              => 'nullable',

                'd_name'                  => 'nullable',
                'd_image'                 => 'nullable',
            );
            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                // return View::make('user.edit_profile')->withErrors($validator)->with(array('user'=>$user));
                return Redirect::back()->withInput()->withErrors($validator)->with(array('page'=>$page));
            } else {
                $data = Input::all();
                
                $img_files = Input::file('profile_img');
                if(isset($img_files)) {
                    $file_name = time().uniqid() .'.'. $img_files->getClientOriginalExtension();
                    $date = date('M-Y');
                    // $file_path = '../public/images/profile_img/'.$date;
                    $file_path = 'images/profile_img/'.$date;
                    $img_files->move($file_path, $file_name);
                    $user->profile_img = $date.'/'.$file_name;
                } else {
                    $user->profile_img = NULL;
                }

                $user->first_name                = $data['first_name'];
                $user->last_name                 = $data['last_name'];
                $user->bussiness_name            = $data['bussiness_name'];
                $user->buss_reg_no               = $data['buss_reg_no'];
                $user->last_name                 = $data['last_name'];
                $user->email                     = $data['email'];
                $user->country                   = $data['country'];
                $user->state                     = $data['state'];
                $user->city                      = $data['city'];
                $user->phone                     = $data['phone'];
                $user->phone2                    = $data['phone2'];
                $user->gender                    = $data['gender'];
                $user->address1                  = $data['address1'];
                $user->address2                  = $data['address2'];
                $user->pincode                   = $data['pincode'];

                /*if (isset($data['commission'])) {
                    $user->commission            = $data['commission'];
                } else {
                    $user->commission            = 0;
                }

                if (isset($data['return_commission'])) {
                    $user->return_commission     = $data['return_commission'];
                } else {
                    $user->return_commission     = 0;
                }*/

                if (isset($data['payment_account_details'])) {
                    $user->payment_account_details  = $data['payment_account_details'];
                } else {
                    $user->payment_account_details  = NULL;
                }
                
                if (isset($data['user_type'])) {
                    $user->user_type                 = $data['user_type'];
                } else {
                    $user->user_type                 = $user->user_type;                    
                }

                // $user->is_approved               = 1;
                // $user->is_block                  = 1;
                // $user->login_type                = 1;

                if($user->save()) {
                    if(isset($data['d_name'])) {
                        if($data['d_name'] && count($data['d_name']) != 0) {
                            MerchantsDocuments::where('merchant', $user->id)->delete();
                            foreach ($data['d_name'] as $key => $value) {
                                $d_images = new MerchantsDocuments();

                                if(isset($data['d_image'][$key])) {
                                    $file_name = 'product'.$key.time() .'.'. $data['d_image'][$key]->getClientOriginalExtension();
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

                                $d_images->merchant  = $user->id; 

                                $d_images->d_name      = $value;     
                                $d_images->is_block    = 1;

                                $d_images->save();
                            }
                        }
                    }

                    session()->forget('user');
                    Session::flash('message', 'update profile Successfully!'); 
                    Session::flash('alert-class', 'alert-success');
                    Session::put('user', $user);
                    // return redirect()->route('my_profile');
                    return redirect()->back();

                } else{
                    Session::flash('message', 'update profile Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    // return redirect()->route('my_profile');
                    return Redirect::back();
                }   
            }
        } else{
            Session::flash('message', 'update profile Failed!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('my_profile');
        }
    }

    public function index () {
        $page = "Users";
        $user = User::all();
        if($user) {
        	foreach ($user as $key => $mer) {
        		$country = CountriesManagement::where('id',$mer->country)->first();
        		$city = CityManagement::where('id',$mer->city)->first();
        		if($country) {
        			$user[$key]['country'] = $country->country_name;
        		} else {
        			$user[$key]['country'] = "-------";
        		}

        		if($city) {
        			$user[$key]['city'] = $city->city_name;
        		} else {
        			$user[$key]['city'] = "-------";
        		}
        	}
        }
    	return View::make("user.manage_user")->with(array('user'=>$user, 'page'=>$page));
    }

    public function create () {
    	$page = "Users";
        return View::make('user.add_user')->with(array('page'=>$page));
    }

    public function SelectCity (Request $request) {
    	$country = 0;
		$city_val = 0;
		if($request->ajax() && isset($request->country)){
			$country = $request->country;

			if(isset($request->city)) {
				$city_val = $request->city;
			}

			$data = "";
			if($country != 0) {
				$city = CityManagement::where('country_name',$country)->get();
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
    	$page = "Users";
        $rules = array(
            'first_name'              => 'required',
            'last_name'               => 'nullable',
            'bussiness_name'          => 'nullable',
            'buss_reg_no'             => 'nullable',
            'last_name'               => 'nullable',
            'email'                   => 'required|email|unique:users,email',
            'password'                => 'required|min:5',
            'password_salt'           => 'required|min:5|same:password',
            'profile_img'             => 'required',
            'remember_token'          => 'nullable',
            'country'                 => 'required',
            'state'                   => 'required',
            'city'                    => 'required',
            'phone'                   => 'required|numeric|unique:users,phone',
            'phone2'                  => 'nullable|numeric|unique:users,phone2',
            'gender'                  => 'required',
            'address1'                => 'required',
            'address2'                => 'required',
            'pincode'                 => 'required|numeric|integer',
            'commission'              => 'required|numeric',
            'return_commission'       => 'required|numeric',
            'payment_account_details' => 'nullable',
            'is_approved'             => 'required',
            'is_block'                => 'nullable',
            'user_type'               => 'required',
            'login_type'              => 'nullable',
            'login_type'              => 'nullable',

            'd_name'                  => 'nullable',
            'd_image'                 => 'nullable',
        );

        $messages=[
            'password_salt.required'=>'The confirm password field is required.',
            'password_salt.min'=>'The confirm password must be at least 5 characters.',
            'password_salt.same'=>'The confirm password and password must match.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
    	   	return View::make('user.add_user')->withErrors($validator)->with(array('page'=>$page));
        } else {
            $data = Input::all();
            $ps = "gj";
            $pe = "ja";
        	$user = new User();

            if($user) {
                $img_files = Input::file('profile_img');
                if(isset($img_files)) {
                    $file_name = time().uniqid() .'.'. $img_files->getClientOriginalExtension();
                    $date = date('M-Y');
                    // $file_path = '../public/images/profile_img/'.$date;
                    $file_path = 'images/profile_img/'.$date;
                    $img_files->move($file_path, $file_name);
                    $user->profile_img = $date.'/'.$file_name;
                } else {
                    $user->profile_img = NULL;
                }

	            $user->first_name                = $data['first_name'];
                $user->last_name                 = $data['last_name'];
                $user->bussiness_name            = $data['bussiness_name'];
	            $user->buss_reg_no               = $data['buss_reg_no'];
	            $user->email                     = $data['email'];
	            $user->password                  = md5($data['password']);
	            $user->password_salt             = $ps.$data['password_salt'].$pe;
                $user->country                   = $data['country'];
	            $user->state                     = $data['state'];
	            $user->city                      = $data['city'];
                $user->phone                     = $data['phone'];
                $user->phone2                    = $data['phone2'];
	            $user->gender                    = $data['gender'];
	            $user->address1                  = $data['address1'];
	            $user->address2                  = $data['address2'];
	            $user->pincode                   = $data['pincode'];

                if($data['commission']) {
                    $user->commission            = $data['commission'];
                } else {
                    $user->commission            = 0;
                }

                if($data['return_commission']) {
                    $user->return_commission     = $data['return_commission'];
                } else {
                    $user->return_commission     = 0;
                }

	            $user->payment_account_details   = $data['payment_account_details'];
	            $user->user_type                 = $data['user_type'];

                if (isset($data['is_approved'])) {
	               $user->is_approved            = $data['is_approved'];
                } else {
                    $user->is_approved           = 1;
                }
	            $user->is_block                  = 1;
	            $user->login_type                = 1;

            	$pass = $data['password'];
                if($user->save()) {
                    if($data['d_name'] && count($data['d_name']) != 0) {
                        foreach ($data['d_name'] as $key => $value) {
                            $d_images = new MerchantsDocuments();

                            if(isset($data['d_image'][$key])) {
                                $file_name = 'product'.$key.time() .'.'. $data['d_image'][$key]->getClientOriginalExtension();
                                $date = date('M-Y');
                                // $file_path = '../public/documents/'.$date;
                                $file_path = 'documents/'.$date;
                                $data['d_image'][$key]->move($file_path, $file_name);
                                $d_images->image       = $date.'/'.$file_name;
                            } else {
                                $d_images->image       = NULL;
                            }

                            $d_images->merchant  = $user->id; 

                            $d_images->d_name      = $value;     
                            $d_images->is_block    = 1;

                            $d_images->save();
                        }
                    }

                	$adm = User::where('user_type', 1)->where('is_block', 1)->first();
            		$admin_email = "teamadsdev5@gmail.com";
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
            		$site_name = "InterCambiar";
            		if($general){
            			$site_name = $general->site_name;
            		} else {
            			$site_name = "InterCambiar";
        			} 

            		$name = $user->first_name.' '.$user->last_name;
            		$email = $user->email;
            		$password = $pass;

            		$headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    // $headers.= "From: $admin_email" . "\r\n";
            		$to = $email;
					$subject = "Create User";
					echo $txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 50%;margin: auto;position: relative;background-color: white;">
                            <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                            <div style="padding: 20px;background-color: #ff5c00;color: white;">
                                <h2 style="color: white;margin-top: 0px;">Create User</h2>
                                <table>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;padding-bottom: 5px;color: white;">Name</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$name.'</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;padding-bottom: 5px;color: white;">E-Mail</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : <a href="mailto:'.$email.'" target="_blank" style="color: white;text-decoration: none;">'.$email.'</a></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;color: white;">Password</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$password.'</td>
                                    </tr>
                                </table>
								<p>Use this email and password to Login</p>
                                <p>Dashboard url : <a href="'.route('admin').'">'.route('admin').'</a></p>
								<p></p>
								<p>Thanks & Regards,</p>
								<p><a href="'.route('home').'">'.$site_name.'</a></p>
                            </div>
                        </div>';

					if (mail($to,$subject,$message,$headers)) {
						Session::flash('message', 'Add & Mail Send Successfully!'); 
						Session::flash('alert-class', 'alert-success');
						return redirect()->route('manage_user');
					} else {
						Session::flash('message', 'Add Successfully, but Mail Send Failed!'); 
						Session::flash('alert-class', 'alert-danger');
		                return redirect()->route('manage_user');
					}
	            } else{
	            	Session::flash('message', 'Added Failed!'); 
					Session::flash('alert-class', 'alert-danger');
	                return redirect()->route('manage_user');
	            }  
            } else{
            	Session::flash('message', 'Added Failed!'); 
				Session::flash('alert-class', 'alert-danger');
                return redirect()->route('manage_user');
            }
        }
    }

	public function edit ($id) {
		$page = "Users";
        $user = User::where('id',$id)->first();
        if($user) {
            $docs = MerchantsDocuments::Where('merchant', $user->id)->get();
            if($docs) {
                $user['docs'] = $docs;
            } else {
                $user['docs'] = NULL;
            }
        }
		return View::make("user.edit_user")->with(array('user'=>$user, 'page'=>$page));
	}

	public function update (Request $request) {
        $page = "Users";
        $id = Input::get('user_id');
        $user = '';
        if($id != '') {
            $user = User::Where('id', $id)->first();
        }

        if($user) {
            $rules = array(
                'first_name'              => 'required',
                'last_name'               => 'nullable',
                'bussiness_name'          => 'nullable',
                'buss_reg_no'             => 'nullable',
                'email'                   => 'required|email|unique:users,email,'.$id.',id',
                'country'                 => 'required',
                'state'                   => 'required',
                'city'                    => 'required',
                'phone'                   => 'required|numeric|unique:users,phone,'.$id.',id',
                'phone2'                  => 'nullable|numeric|unique:users,phone2,'.$id.',id',
                'gender'                  => 'required',
                'address1'                => 'required',
                'address2'                => 'required',
                'pincode'                 => 'required|numeric|integer',
                'commission'              => 'required|numeric',
                'return_commission'       => 'required|numeric',
                'profile_img'             => 'nullable',
                'payment_account_details' => 'nullable',
                'is_approved'             => 'required',
                'is_block'                => 'nullable',
                'user_type'               => 'nullable',
                'login_type'              => 'nullable',

                'd_name'                  => 'nullable',
                'd_image'                 => 'nullable',
            );
            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                if($user) {
                    $docs = MerchantsDocuments::Where('merchant', $user->id)->get();
                    if($docs) {
                        $user['docs'] = $docs;
                    } else {
                        $user['docs'] = NULL;
                    }
                }
                return Redirect::to('/edit_user/' . $id)->withErrors($validator)->with(array('user'=>$user, 'page'=>$page));
            } else {
                $data = Input::all();

                $img_files = Input::file('profile_img');
                if(isset($img_files)) {
                    $file_name = time().uniqid() .'.'. $img_files->getClientOriginalExtension();
                    $date = date('M-Y');
                    // $file_path = '../public/images/profile_img/'.$date;
                    $file_path = 'images/profile_img/'.$date;
                    $img_files->move($file_path, $file_name);
                    $user->profile_img = $date.'/'.$file_name;
                } else if (isset($data['old_profile_img'])) {
                    $user->profile_img = $data['old_profile_img'];
                } else {
                    $user->profile_img = NULL;
                }

                $user->first_name                = $data['first_name'];
                $user->last_name                 = $data['last_name'];
                $user->bussiness_name            = $data['bussiness_name'];
                $user->buss_reg_no               = $data['buss_reg_no'];
                $user->email                     = $data['email'];
                $user->country                   = $data['country'];
                $user->state                     = $data['state'];
                $user->city                      = $data['city'];
                $user->phone                     = $data['phone'];
                $user->phone2                     = $data['phone2'];
                $user->gender                    = $data['gender'];
                $user->address1                  = $data['address1'];
                $user->address2                  = $data['address2'];
                $user->pincode                   = $data['pincode'];

                if($data['commission']) {
                    $user->commission            = $data['commission'];
                } else {
                    $user->commission            = 0;
                }

                if($data['return_commission']) {
                    $user->return_commission     = $data['return_commission'];
                } else {
                    $user->return_commission     = 0;
                }

                $user->payment_account_details   = $data['payment_account_details'];

                if (isset($data['user_type'])) {
                    $user->user_type             = $data['user_type'];
                }

                if (isset($data['is_approved'])) {
                    $user->is_approved           = $data['is_approved'];
                } else {
                    $user->is_approved           = 0;
                }

                $user->is_block                  = 1;
                $user->login_type                = 1;

                if($user->save()) {
                    if($data['d_name'] && count($data['d_name']) != 0) {
                        MerchantsDocuments::where('merchant', $user->id)->delete();
                        foreach ($data['d_name'] as $key => $value) {
                            $d_images = new MerchantsDocuments();

                            if(isset($data['d_image'][$key])) {
                                $file_name = 'product'.$key.time() .'.'. $data['d_image'][$key]->getClientOriginalExtension();
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

                            $d_images->merchant  = $user->id; 

                            $d_images->d_name      = $value;     
                            $d_images->is_block    = 1;

                            $d_images->save();
                        }
                    }

                    Session::flash('message', 'update Successfully!'); 
                    Session::flash('alert-class', 'alert-success');
                    return redirect()->route('manage_user');

                } else{
                    Session::flash('message', 'update Failed!'); 
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('manage_user');
                }   
            }
        } else{
            Session::flash('message', 'update Failed!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_user');
        }
    }

    public function view ($id) {
        $page = "Users";
        $user = User::where('id',$id)->first();
        if($user) {
            $docs = MerchantsDocuments::Where('merchant', $user->id)->get();
            if($docs) {
                $user['docs'] = $docs;
            } else {
                $user['docs'] = NULL;
            }
        }
        return View::make("user.view_user")->with(array('user'=>$user, 'page'=>$page));
    }

	public function delete( Request $request) {	
		$id = 0;
		if($request->ajax() && isset($request->id)){
			$id = $request->id;
			$error = 1;
			if($id != 0) {
				$user = User::where('id',$id)->where('user_type', '!=', 1)->first();
				if($user) {
					if($user->delete()) {
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

			echo $error;
		}
	}

	public function DeleteAll( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$user = User::where('id',$value)->where('user_type', '!=', 1)->first();
					if($user){
						if($user->delete()) {
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

			echo $error;
		}
	}

	public function Statususer ($id) {
        $user = '';
        $msg = '';
        if($id != '') {
            $user = User::where('id',$id)->where('user_type', '!=', 1)->first();
        }

        if($user) {
            if($user->is_block == 1) {
                $user->is_block        = 0;
                $msg = "Blocked Successfully";
            } else {
                $user->is_block        = 1;
                $msg = "Unblocked Successfully";
            }
            
            if($user->save()) {
                Session::flash('message', $msg); 
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('manage_user');
            } else{
                Session::flash('message', 'Failed Block or Unblock!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('manage_user');
            }
        } else{
            Session::flash('message', 'Failed Block or Unblock!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_user');
        }
    }

    public function ApprovedUser ($id) {
		$user = '';
		$msg = '';
    	if($id != '') {
        	$user = User::where('id',$id)->where('user_type', '!=', 1)->first();
        }

        if($user) {
            $user->approved_date = date('Y-m-d');
        	if($user->is_approved == 1) {
            	$user->is_approved        = 0;
            	$msg = "Rejected Successfully";
        	} else {
        		$user->is_approved        = 1;
            	$msg = "Approved Successfully";
        	}
	        
	        if($user->save()) {
                if($user->is_approved == 1) {
                    $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                    $admin_email = "teamadsdev5@gmail.com";
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
                    $site_name = "InterCambiar";
                    if($general){
                        $site_name = $general->site_name;
                    } else {
                        $site_name = "InterCambiar";
                    } 

                    $name = $user->first_name.' '.$user->last_name;
                    $email = $user->email;

                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    // $headers.= "From: $admin_email" . "\r\n";
                    $to = $email;
                    $subject = "Register Successful";
                    $txt = '<div class="gj_mail" style="border: 10px solid #ff5c007d;width: 50%;margin: auto;position: relative;background-color: white;">
                            <div style="margin:10px 0px;padding: 20px;"><img src="'.$logo.'" style="width: 100%;"></div>
                            <div style="padding: 20px;background-color: #ff5c00;color: white;">
                                <h2 style="color: white;margin-top: 0px;">Registration Process Successful</h2>
                                <table>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;padding-bottom: 5px;color: white;">Name</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : '.$name.'</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 100px;text-align: left;text-transform: uppercase;padding-bottom: 5px;color: white;">E-Mail</th>
                                        <td style="font-size: 14px;font-weight: bold;color: white;"> : <a href="mailto:'.$email.'" target="_blank" style="color: white;text-decoration: none;">'.$email.'</a></td>
                                    </tr>
                                </table>
                                <p>Use this email and password to Login</p>
                                <p>Dashboard url : <a href="'.route('admin').'">'.route('admin').'</a></p>
                                <p></p>
                                <p>Thanks & Regards,</p>
                                <p><a href="'.route('home').'">'.$site_name.'</a></p>
                            </div>
                        </div>';

                    if (mail($to,$subject,$txt,$headers)) {
                        Session::flash('message', $msg.' and Mail Send Successfully!');
                        Session::flash('alert-class', 'alert-success');
                        return redirect()->route('manage_user');
                    } else {
                        Session::flash('message', $msg.' and Mail Send Failed!');  
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('manage_user');
                    }                                
                } else {
    	        	Session::flash('message', $msg); 
    				Session::flash('alert-class', 'alert-success');
    				return redirect()->route('manage_user');
                }
	        } else{
	        	Session::flash('message', 'Failed Approved or Rejected!'); 
				Session::flash('alert-class', 'alert-danger');
	            return redirect()->route('manage_user');
	        }
        } else{
        	Session::flash('message', 'Failed Approved or Rejected!'); 
			Session::flash('alert-class', 'alert-danger');
            return redirect()->route('manage_user');
        }
	}

	public function UserBlock( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$user = User::where('id',$value)->where('user_type', '!=', 1)->first();
					if($user){
						$user->is_block = 0;
						$user->save();
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

			echo $error;
		}
	}

	public function UserUnblock( Request $request) {	
		$ids = array();

		if($request->ajax() && isset($request->ids)){
			$ids = $request->ids;
			$error = 1;
			if(sizeof($ids) != 0) {
				foreach ($ids as $key => $value) {
					$user = User::where('id',$value)->where('user_type', '!=', 1)->first();
					if($user){
						$user->is_block = 1;
						$user->save();
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

			echo $error;
		}
	}
}