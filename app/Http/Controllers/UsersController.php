<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\loginSecurity;
use App\CityManagement;
use App\StateManagements;
use App\CountriesManagement;
use App\EmailSettings;
use App\MerchantsDocuments;
use App\Carts;
use App\Products;
use App\ShippingAddress;
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

class UsersController extends Controller
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
        $loged = session()->get('user');
        if(isset($_COOKIE["user"]) && !empty($_COOKIE["user"])) {
            $cook = $_COOKIE["user"];
            $cook = json_decode($cook);
            $user = User::Where('id', $cook->id)->first();
            if($user) {
                if(($user->user_type == 4 ||$user->user_type == 5)) {
                    return redirect()->route('signin');
                    /*if($user->verification == 1) {
                        if($user->is_block == 1) {
                            session()->forget('user');
                            Session::flash('message', 'Login Successfully!'); 
                            Session::flash('alert-class', 'success');
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
                                        $carts->product_cost       = (isset($value['product_cost'])) ? $value['product_cost'] : 0;
                                        $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
                                        $carts->tax_amount       = (isset($value['tax_amount'])) ? $value['tax_amount'] : 0;
                                        $carts->total_price       = (isset($value['total_price'])) ? $value['total_price'] : 0;
                                        $carts->tax  = (isset($value['tax'])) ? $value['tax'] : NULL;
                                        $carts->tax_type  = (isset($value['tax_type'])) ? $value['tax_type'] : NULL;
                                        $carts->service_charge  = (isset($value['service_charge'])) ? $value['service_charge'] : NULL;
                                        $carts->shiping_charge  = (isset($value['shiping_charge'])) ? $value['shiping_charge'] : NULL;
                                        $carts->image       = (isset($value['image'])) ? $value['image'] : NULL;
                                        $carts->qty         = (isset($value['qty'])) ? $value['qty'] : 1;
                                        $carts->notes       = (isset($value['notes'])) ? $value['notes'] : NULL;
                                        $carts->is_offer       = (isset($value['is_offer'])) ? $value['is_offer'] : 'No';
                                        $carts->offer_id       = (isset($value['offer_id'])) ? $value['offer_id'] : NULL;
                                        $carts->offer_det_id       = (isset($value['offer_det_id'])) ? $value['offer_det_id'] : NULL;
                                        $carts->cart_key       = (isset($value['cart_key'])) ? $value['cart_key'] : NULL;
                                        $carts->cart_del       = (isset($value['cart_del'])) ? $value['cart_del'] : NULL;
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
                            Session::flash('alert-class', 'danger');
                            return redirect()->route('home');
                        }
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('home');
                    }*/
                } else {
                    session()->forget('user');
                    
                    Session::flash('message', 'Login Successfully!'); 
                    Session::flash('alert-class', 'success');
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
                                Session::flash('alert-class', 'danger');
                                return redirect()->route('home');
                            } 
                        } else {
                            session()->forget('user');
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
                            Session::flash('message', 'Admin Has Blocked Your Account!'); 
                            Session::flash('alert-class', 'danger');
                            return redirect()->route('home');
                        }
                    } else if(($user->user_type == 4) || $user->user_type == 5){
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
                Session::flash('alert-class', 'danger');
                return redirect()->route('logout');
            }
        } else if($loged) {
            if(($loged->user_type == 4) ||$loged->user_type == 5) {
                return redirect()->route('signin');
                /*if($loged->verification == 1) {
                    if($loged->is_block == 1) {
                        session()->forget('user');
                        Session::flash('message', 'Login Successfully!'); 
                        Session::flash('alert-class', 'success');
                        Session::put('user', $loged);

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
                                    $carts->product_cost       = (isset($value['product_cost'])) ? $value['product_cost'] : 0;
                                    $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
                                    $carts->tax_amount       = (isset($value['tax_amount'])) ? $value['tax_amount'] : 0;
                                    $carts->total_price       = (isset($value['total_price'])) ? $value['total_price'] : 0;
                                    $carts->tax  = (isset($value['tax'])) ? $value['tax'] : NULL;
                                    $carts->tax_type  = (isset($value['tax_type'])) ? $value['tax_type'] : NULL;
                                    $carts->service_charge  = (isset($value['service_charge'])) ? $value['service_charge'] : NULL;
                                    $carts->shiping_charge  = (isset($value['shiping_charge'])) ? $value['shiping_charge'] : NULL;
                                    $carts->image       = (isset($value['image'])) ? $value['image'] : NULL;
                                    $carts->qty         = (isset($value['qty'])) ? $value['qty'] : 1;
                                    $carts->notes       = (isset($value['notes'])) ? $value['notes'] : NULL;
                                    $carts->is_offer       = (isset($value['is_offer'])) ? $value['is_offer'] : 'No';
                                    $carts->offer_id       = (isset($value['offer_id'])) ? $value['offer_id'] : NULL;
                                    $carts->offer_det_id       = (isset($value['offer_det_id'])) ? $value['offer_det_id'] : NULL;
                                    $carts->cart_key       = (isset($value['cart_key'])) ? $value['cart_key'] : NULL;
                                    $carts->cart_del       = (isset($value['cart_del'])) ? $value['cart_del'] : NULL;
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
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('home');
                    }
                } else {
                    session()->forget('user');
                    if(isset($_COOKIE["user"])) {
                        setcookie ("user","");
                    }
                    Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                    Session::flash('alert-class', 'danger');
                    return redirect()->route('home');
                }*/
            } else {
                session()->forget('user');
                
                Session::flash('message', 'Login Successfully!'); 
                Session::flash('alert-class', 'success');
                Session::put('user', $loged);
                
                // session(['user' => $user]);
                if($loged->user_type == 1){
                    // $ses = Session::get('user');
                    // session()->get('user');
                    return redirect()->route('dashboard');
                } else if(($loged->user_type == 2) || ($loged->user_type == 3)) {
                    if($loged->is_block == 1) {
                        if($loged->is_approved == 1) {
                            return redirect()->route('merchants_dashboard');
                        } else {
                            session()->forget('user');
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
                            Session::flash('message', 'Admin Has Blocked Your Account!'); 
                            Session::flash('alert-class', 'danger');
                            return redirect()->route('home');
                        } 
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        Session::flash('message', 'Admin Has Blocked Your Account!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('home');
                    }
                } else if(($loged->user_type == 4) ||$loged->user_type == 5){
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
                    if(($user->user_type == 4) ||$user->user_type == 5) {
                        Session::flash('message', 'Wrong User Name And Password!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('admin');
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        
                        Session::flash('message', 'Login Successfully!'); 
                        Session::flash('alert-class', 'success');
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
                        } else if(($user->user_type == 4) ||$user->user_type == 5){
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
                    Session::flash('alert-class', 'danger');
                    return redirect()->back();
                    // return redirect()->route('admin');
                }
            } else{
                Session::flash('message', 'Login Failed!'); 
                Session::flash('alert-class', 'danger');
                // return redirect()->route('admin');
                return redirect()->back();
            }
        }
    }

    public function CheckSignInEmail (Request $request) {
        $data = Input::all();
        $rules = array(
            // 'email'                   => 'required',
            'email'                   => 'required|email|exists:users,email',
            'password'                => 'required',
            'bk_log_with'             => 'nullable',
        );

        $messages=[
            'password.required'=>'The password field is required.',
            // 'email.required'=>'The email or mobile no field is required.',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            return View::make('front_end.signin')->withErrors($validator)->with(array('bk_log_with'=>'email'));
        } else {
            $user = User::where('email', $data['email'])->where('is_block', 1)->first();
// dd($user);
            if($user) {
                $pass = md5($data['password']);
                if ($user->password == $pass) {
                    if(($user->user_type == 4 || $user->user_type == 5)) {
                        if($user->verification == 1) {
                            if($user->is_approved==1)
                            {
                            session()->forget('user');
                            Session::flash('message', 'Login Successfully!'); 
                            Session::flash('alert-class', 'success');
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
                                        $proc=Products::find($value['product_id']);
                                        if($user->user_type == 4)
                                        {
                                             $price= $proc->discounted_price;
 
                                        }
                                        else if($user->user_type == 5)
                                        {
                                            $price= $proc->discount_price_dealer;

                                        }
                                     $t_price = round(($value['qty'] * $price),2);

                                        $carts->product_id  = $value['product_id'];
                                        $carts->user_id     = $users->id;
                                        $carts->name        = (isset($value['name'])) ? $value['name'] : NULL;
                                        $carts->original_price  = (isset($value['original_price'])) ? $value['original_price'] : 0;
                                        $carts->product_cost       = (isset($price)) ?$price : 0;
                                        $carts->price       = (isset($price)) ? $price : 0;
                                        $carts->tax_amount       = (isset($value['tax_amount'])) ? $value['tax_amount'] : 0;
                                        $carts->total_price       = (isset($t_price)) ? $t_price : 0;
                                        $carts->att_name  = (isset($value['att_name'])) ? $value['att_name'] : NULL;
                                        $carts->att_value  = (isset($value['att_value'])) ? $value['att_value'] : NULL;
                                        $carts->tax  = (isset($value['tax'])) ? $value['tax'] : NULL;
                                        $carts->tax_type  = (isset($value['tax_type'])) ? $value['tax_type'] : NULL;
                                        $carts->service_charge  = (isset($value['service_charge'])) ? $value['service_charge'] : NULL;
                                        $carts->shiping_charge  = (isset($value['shiping_charge'])) ? $value['shiping_charge'] : NULL;
                                        $carts->image       = (isset($value['image'])) ? $value['image'] : NULL;
                                        $carts->qty         = (isset($value['qty'])) ? $value['qty'] : 1;
                                        $carts->notes       = (isset($value['notes'])) ? $value['notes'] : NULL;
                                        $carts->is_offer       = (isset($value['is_offer'])) ? $value['is_offer'] : 'No';
                                        $carts->offer_id       = (isset($value['offer_id'])) ? $value['offer_id'] : NULL;
                                        $carts->offer_det_id       = (isset($value['offer_det_id'])) ? $value['offer_det_id'] : NULL;
                                        $carts->cart_key       = (isset($value['cart_key'])) ? $value['cart_key'] : NULL;
                                        $carts->cart_del       = (isset($value['cart_del'])) ? $value['cart_del'] : NULL;
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
                        Session::flash('message', 'Your account is not approved by admin!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('signin'); 
                            
                        }
                        }
                        else
                        {
                           
                         session()->forget('user');
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
                            Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                            Session::flash('alert-class', 'danger');
                            return redirect()->route('signin');
                        }
                    } else {
                        Session::flash('message', 'Wrong User Name And Password!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('signin');
                    }
                } else {
                    Session::flash('message', 'Your Password is Wrong!'); 
                    Session::flash('alert-class', 'danger');
                    return redirect()->route('signin');
                }
            } else{
                Session::flash('message', 'Login Failed!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->route('signin');
            }
        }
    }

    public function LoginOTP( Request $request) {    
        $mobile = 0;

        if($request->ajax() && isset($request->mobile)){
            $mobile = $request->mobile;
            $error = 1;

            if($mobile) {
                $user = User::where('phone', $mobile)->where('is_block', 1)->where('is_approved', 1)->first();
                if($user) {
                    $otp = mt_rand(100000, 999999);
                    $user->signin_verify = $otp;
                    if($user->save()) {
                        $text = "Please Use this ".$otp." otp code to your SignIn process,Ecambiar.";
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
                            'mobile' => $user->phone,
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
                            $error = 0;
                        } else {
                            Session::flash('message', 'OTP Code Send Failed!'); 
                            Session::flash('alert-class', 'danger');
                            $error = 1;
                        }
                    } else {
                        Session::flash('message', 'OTP Code Send Failed!'); 
                        Session::flash('alert-class', 'danger');
                        $error = 1;
                    }
                } else {
                    Session::flash('message', 'Invalid Mobile Number!, Please Enter Your Authenticate Mobile Number!'); 
                    Session::flash('alert-class', 'danger');  
                }

            } else {
                Session::flash('message', 'Must Enter Valid Mobile Number!'); 
                Session::flash('alert-class', 'danger');
            }

            echo $error;
        }
    }

    public function CheckSignInMobile (Request $request) {
        $data = Input::all();
        $rules = array(
            'phone'                  => 'required',
          
        );

        $messages=[
            'phone.required'=>'',
        ];
        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails()) {
            return View::make('front_end.signin')->withErrors($validator)->with(array('bk_log_with'=>'mobile'));
        } else {
            $user = User::where('phone', $data['phone'])->where('is_block', 1)->where('is_approved', 1)->first();

            if($user) {
                if(($user->user_type == 4)  ||$user->user_type == 5) {
                    if($user->verification == 1) {
                        session()->forget('user');
                        Session::flash('message', 'Login Successfully!'); 
                        Session::flash('alert-class', 'success');
                        Session::put('user', $user);
                        $ck = json_encode($user);
                        if(isset($data["mob_rem"]) && !empty($data["mob_rem"])) {
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
                                    $carts->product_cost       = (isset($value['product_cost'])) ? $value['product_cost'] : 0;
                                    $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
                                    $carts->tax_amount       = (isset($value['tax_amount'])) ? $value['tax_amount'] : 0;
                                    $carts->total_price       = (isset($value['total_price'])) ? $value['total_price'] : 0;
                                    $carts->att_name  = (isset($value['att_name'])) ? $value['att_name'] : NULL;
                                    $carts->att_value  = (isset($value['att_value'])) ? $value['att_value'] : NULL;
                                    $carts->tax  = (isset($value['tax'])) ? $value['tax'] : NULL;
                                    $carts->tax_type  = (isset($value['tax_type'])) ? $value['tax_type'] : NULL;
                                    $carts->service_charge  = (isset($value['service_charge'])) ? $value['service_charge'] : NULL;
                                    $carts->shiping_charge  = (isset($value['shiping_charge'])) ? $value['shiping_charge'] : NULL;
                                    $carts->image       = (isset($value['image'])) ? $value['image'] : NULL;
                                    $carts->qty         = (isset($value['qty'])) ? $value['qty'] : 1;
                                    $carts->notes       = (isset($value['notes'])) ? $value['notes'] : NULL;
                                    $carts->is_offer       = (isset($value['is_offer'])) ? $value['is_offer'] : 'No';
                                    $carts->offer_id       = (isset($value['offer_id'])) ? $value['offer_id'] : NULL;
                                    $carts->offer_det_id       = (isset($value['offer_det_id'])) ? $value['offer_det_id'] : NULL;
                                    $carts->cart_key       = (isset($value['cart_key'])) ? $value['cart_key'] : NULL;
                                    $carts->cart_del       = (isset($value['cart_del'])) ? $value['cart_del'] : NULL;
                                    $carts->is_block    = 1;

                                    $carts->save();
                                }
                            }
                        }

                        $user->signin_verify = NULL;
                        $user->mobile_verify = 1;
                        $user->save();

                        return redirect()->route('home');
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }
                        Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('signin');
                    }
                } else {
                    Session::flash('message', 'Wrong User Name And Password!'); 
                    Session::flash('alert-class', 'danger');
                    return redirect()->route('signin');
                }
            } else{
                Session::flash('message', 'Login Failed!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->route('signin');
            }
        }
    }

    public function Logout () {
        session_start();
        session_unset(); 
        $value = session()->get('user');
        if(isset($_COOKIE["user"])) {
            setcookie ("user","");
        }
        session()->forget('cart');
        session()->forget('chk_verify');
        // print_r($value);die();
        if($value) {
            if($value->user_type == 4 ||$value->user_type == 5) {
                session()->forget('user');
                session()->forget('cart');
                return redirect()->route('home');
            } else if($value->user_type == 2 || $value->user_type == 3) {
                session()->forget('user');
                return redirect()->route('merchant');
            } else {
                session()->forget('user');
                return redirect()->route('admin');
            } 
        } else {
            session()->forget('cart');
            session()->forget('user');
            return redirect()->route('home');
        }
    }

    public function Forgot () {
        return View::make('user.forgot');
    }

    public function CheckForgot (Request $request) {
        $data = Input::all();

        if(isset($data['mobnumber'])) {
            $rules = array(
                'email_id'       => 'nullable|email|exists:users,email',
                'mobnumber'      => 'nullable|numeric|digits:10|exists:users,phone',
            );
        } else {
            $rules = array(
                'email_id'       => 'nullable|email|exists:users,email',
            );
        }


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
            $user = false;
            $mob_user = false;
            // print_r($data);die();
            if(isset($data['mobnumber'])) {
                $user = User::where('email', $data['email_id'])->where('is_block', 1)->first();
                $mob_user = User::where('phone', $data['mobnumber'])->where('is_block', 1)->first();
            } else {
                $user = User::where('email', $data['email_id'])->where('is_block', 1)->first();
            }

            if ($user) {
                $user->remember_token = time();
                if($user->save()) {
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

                    $name = $user->first_name.' '.$user->last_name;
                    $email = $user->email;
                    $reset_pw = $user->remember_token;

                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    $headers.= "From: Grocery360 <order@grocery360.in>" . "\r\n";
                    $to = $email;
                    $subject = "Verify your Account";

                    $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                            <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                            <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                <h2 style="color: #ff5c00;margin-top: 0px;">Reset Password Code</h2>
                                <table align="center" style=" text-align: center;">
                                    <tr>
                                        <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Name</th>
                                        <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$name.'</td>
                                    </tr>
                                    <tr>
                                        <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">E-Mail</th>
                                        <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : <a href="mailto:'.$email.'" target="_blank" style="color: #333;text-decoration: none;">'.$email.'</a></td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;text-transform: uppercase;color: #333;padding-bottom: 12px;font-weight:bold;width: 120px;">Password</th>
                                        <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$reset_pw.'</td>
                                    </tr>
                                </table>
                                <p>Your Password Reset Code is '.$reset_pw.'</p>
                                <p>Use this Code to change your Password</p>
                                <p>Thank You.</p>
                                <p></p>
                                <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                <p>Thanks & Regards,</p>
                                <p><a href="'.route('home').'">'.$site_name.'</a></p>
                            </div>
                        </div>';
                    
                    if(mail($to,$subject,$txt,$headers)){
                        Session::flash('message', 'Mail Send Successfully!'); 
                        Session::flash('alert-class', 'success');
                        return redirect()->route('reset');
                    } else {
                        Session::flash('message', 'Mail Send Failed!'); 
                        Session::flash('alert-class', 'danger');
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
                        Session::flash('alert-class', 'success');
                        return redirect()->route('reset');
                    } else {
                        Session::flash('message', 'OTP Message Send Failed!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('forgot');
                    }
                }
            } else{
                Session::flash('message', 'It\'s not valid Email or Phone Number!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->route('home');
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
            'remember_token.required' => 'The reset code field is required.',
            'remember_token.exists'   => 'Wrong reset code.',
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

                    $name = $user->first_name.' '.$user->last_name;
                    $email = $user->email;
                    $password = $pass;

                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    // $headers.= "From: $admin_email" . "\r\n";
                    $headers.= "From: smarthub@bioessenza.com" . "\r\n";
                    $to = $email;
                    $subject = "Change your Password";
                    $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                            <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                            <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                <h2 style="color: #ff5c00;margin-top: 0px;">Reset Password Code</h2>
                                <table align="center" style=" text-align: center;">
                                    <tr>
                                        <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Name</th>
                                        <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$name.'</td>
                                    </tr>
                                    <tr>
                                        <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">E-Mail</th>
                                        <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : <a href="mailto:'.$email.'" target="_blank" style="color: #333;text-decoration: none;">'.$email.'</a></td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;text-transform: uppercase;color: #333;padding-bottom: 12px;font-weight:bold;width: 120px;">Password</th>
                                        <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$password.'</td>
                                    </tr>
                                </table>
                                <p>Your Password is <span style="font-weight:bold"> '.$password.' </span></p>
                                <p>Use this password to Login</p>
                                <p>Thank You.</p>
                                 <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                <p>Thanks & Regards,</p>
                                <p><a href="'.route('home').'">'.$site_name.'</a></p>
                            </div>
                        </div>';
                    
                    
                    if(mail($to,$subject,$txt,$headers)){
                        Session::flash('message', 'Password Changed and Mail Send Successfully!'); 
                        Session::flash('alert-class', 'success');
                        session()->forget('user');
                        if($user->user_type == 4||$user->user_type == 5)  {
                            return redirect()->route('signin');
                        } else if($user->user_type == 2 || $user->user_type == 3) {
                            return redirect()->route('merchant');
                        } else {
                            return redirect()->route('admin');
                        }
                    } else {
                        Session::flash('message', 'Password Changed Successfully!'); 
                        Session::flash('alert-class', 'success');
                        session()->forget('user');
                        if($user->user_type == 4 ||$user->user_type == 5) {
                            return redirect()->route('signin');
                        } else if($user->user_type == 2 || $user->user_type == 3) {
                            return redirect()->route('merchant');
                        } else {
                            return redirect()->route('admin');
                        }
                    }
                } else{
                    Session::flash('message', 'Password Changed Failed!'); 
                    Session::flash('alert-class', 'danger');
                    return redirect()->route('forgot');
                } 
            } else{
                Session::flash('message', 'You\'re Reset Code Is Not Valid!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->route('reset');
            }
        }
    }

    public function ChkRepwdQuestion () {
        $secure = loginSecurity::all();
        return View::make('user.chk_repwd_question')->with(array('secure'=>$secure));
    }

    public function ChkRepwdAnswer (Request $request) {
        $rules = array(
            'mobno'                   => 'required',
            'question'                => 'required',
            'answer'                  => 'required',
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
            return redirect()->route('chk_repwd_question')->withErrors($validator);
        } else {
            $data = Input::all();
            $ps = "gj";
            $pe = "ja";
            $user = User::where('email', $data['mobno'])->where('is_block', 1)->first();
            if(!$user) {
                $user = User::where('phone', $data['mobno'])->where('is_block', 1)->first();
            }

            if($user) {
                $act = User::where('id', $user->id)->where('is_block', 1)->where('question', $data['question'])->first();

                if($act) {
                    if($act->answer == $data['answer']) {
                        $user->password                  = md5($data['password']);
                        $user->password_salt             = $ps.$data['password_salt'].$pe;


                        $pass = $data['password'];
                        if($user->save()) {
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

                            $name = $user->first_name.' '.$user->last_name;
                            $email = $user->email;
                            $password = $pass;

                            $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                            $headers.= "MIME-Version: 1.0\r\n";
                            // $headers.= "From: $admin_email" . "\r\n";
                            $headers.= "From: smarthub@bioessenza.com" . "\r\n";
                            $to = $email;
                            $subject = "Password Changed";
                            $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                    <h2 style="color: #ff5c00;margin-top: 0px;">Changed Password Successfully</h2>
                                    <table align="center" style=" text-align: center;">
                                        <tr>
                                            <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Name</th>
                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$name.'</td>
                                        </tr>
                                        <tr>
                                            <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">E-Mail</th>
                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : <a href="mailto:'.$email.'" target="_blank" style="color: #333;text-decoration: none;">'.$email.'</a></td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;text-transform: uppercase;color: #333;padding-bottom: 12px;font-weight:bold;width: 120px;">Password</th>
                                            <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$password.'</td>
                                        </tr>
                                    </table>
                                    <p>Your Password is <span style="font-weight:bold"> '.$password.' </span></p>
                                    <p>Use this password to Login</p>
                                    <p>Thank You.</p>
                                    <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                    <p>Thanks & Regards,</p>
                                    <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                </div>
                            </div>';
                            
                            if(mail($to,$subject,$txt,$headers)){
                                Session::flash('message', 'Password Changed and Mail Send Successfully!'); 
                                Session::flash('alert-class', 'success');
                                session()->forget('user');
                                if($user->user_type == 4 ||$user->user_type == 5) {
                                    return redirect()->route('signin');
                                } else if($user->user_type == 2 || $user->user_type == 3) {
                                    return redirect()->route('merchant');
                                } else {
                                    return redirect()->route('admin');
                                }
                            } else {
                                Session::flash('message', 'Password Changed Successfully!'); 
                                Session::flash('alert-class', 'success');
                                session()->forget('user');
                                if($user->user_type == 4 ||$user->user_type == 5) {
                                    return redirect()->route('signin');
                                } else if($user->user_type == 2 || $user->user_type == 3) {
                                    return redirect()->route('merchant');
                                } else {
                                    return redirect()->route('admin');
                                }
                            }
                        } else{
                            Session::flash('message', 'Password Changed Failed!'); 
                            Session::flash('alert-class', 'danger');
                            return redirect()->route('chk_repwd_question');
                        } 
                    } else {
                        Session::flash('message', 'Your Security Answer is Wrong!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('chk_repwd_question');
                    }
                } else {
                    Session::flash('message', 'Your Security Question is Wrong!'); 
                    Session::flash('alert-class', 'danger');
                    return redirect()->route('chk_repwd_question');
                }                         
            } else{
                Session::flash('message', 'Your E-Mail or Mobile Number is Not Valid!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->route('chk_repwd_question');
            }
        }
    }

    public function MyProfile () {
        $page = "Users";
        $profile = session()->get('user');
        if($profile) {
            return View::make('user.my_profile')->with(array('profile'=>$profile, 'page'=>$page));
        } else if($profile->user_type == 2 || $profile->user_type == 3) {
            return redirect()->route('merchant');
        } else {
            return redirect()->route('admin');
        }
    }
    public function change_password()
    {
        $page = "Users";
        $user = session()->get('user');
        if($user) {
            $docs = MerchantsDocuments::Where('merchant', $user->id)->get();
            if($docs) {
                $user['docs'] = $docs;
            } else {
                $user['docs'] = NULL;
            }
            return View::make('user.change_password')->with(array('user'=>$user, 'page'=>$page));
        } else {
            return redirect()->back();
        }
    }
    public function EditProfile () {
        $page = "Users";
        $user = session()->get('user');
        if($user) {
            $docs = MerchantsDocuments::Where('merchant', $user->id)->get();
            if($docs) {
                $user['docs'] = $docs;
            } else {
                $user['docs'] = NULL;
            }
            return View::make('user.edit_profile')->with(array('user'=>$user, 'page'=>$page));
        } else {
            return redirect()->back();
        }
    }

    public function UpdateProfile (Request $request) {
        //remember the validation things
        $page = "Users";
        $id = Input::get('user_id');
        $user = '';
        if($id != '') {
            $user = User::Where('id', $id)->first();
        }
        
        if($id) {
            $rules = array(
                'first_name'              => 'required',
                'last_name'               => 'nullable',
                'bussiness_name'          => 'nullable',
                'buss_reg_no'             => 'nullable',
                'email'                   => 'required|email',
                'country'                 => 'required',
                'state'                   => 'required',
                'city'                    => 'required',
                'phone'                   => 'required|numeric',
                'phone2'                  => 'nullable|numeric',
                'gender'                  => 'required',
                'address1'                => 'required',
                'address2'                => 'required',
                'pincode'                 => 'required|numeric|integer',
                'commission'              => 'nullable|numeric',
                'return_commission'       => 'nullable|numeric',
                'question'                => 'nullable',
                'answer'                  => 'nullable',
                'payment_account_details' => 'nullable',
                'profile_img'             => 'nullable',
                'is_approved'             => 'nullable',
                'is_block'                => 'nullable',
                'user_type'               => 'nullable',
                'login_type'              => 'nullable',
               // 'company_name' => 'required_if:user_type,==,5|required',
               // 'company_gst_no' => 'required_if:user_type,==,5|required',
                'verification_document' => 'nullable',
                'd_name'                  => 'nullable',
                'd_image'                 => 'nullable',
            );
             $messages=[
            'company_name.required_if'=>'The company name is required ',
            'company_gst_no.required_if'=>'The Company GSTIN  is required ',
            'verification_document.required_if'=>'The verification document is required',

        ];
            $validator = Validator::make(Input::all(), $rules,$messages);

            if ($validator->fails()) {
                dd($request->all());
                // return View::make('user.edit_profile')->withErrors($validator)->with(array('user'=>$user));
                return Redirect::back()->withInput()->withErrors($validator)->with(array('page'=>$page));
            } else {
                
                $data = Input::all();
                
                $img_files = Input::file('profile_img');
                if(isset($img_files)) {
                    $file_name = $img_files->getClientOriginalName();
                    $date = date('M-Y');
                    // $file_path = '../public/images/profile_img/'.$date;
                    $file_path = 'images/profile_img/'.$date;
                    $img_files->move($file_path, $file_name);
                    $user->profile_img = $date.'/'.$file_name;
                } else {
                    $user->profile_img = NULL;
                }
                $verification_document= Input::file('verification_document');
                         if(isset($verification_document)) {
                            $file_name = $verification_document->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/profile_img/'.$date;
                            $file_path = 'images/gst/'.$date;
                            $verification_document->move($file_path, $file_name);
                            $user->gst_document = $date.'/'.$file_name;
                        } else {
                            $user->gst_document = NULL;
                        }
                        
                $user->first_name                = $data['first_name'];
                $user->last_name                 = $data['last_name'];

                if($request->user_type==5)
                        {
                            $user->bussiness_name=$request->company_name;
                              $user->is_gst=1;
                                $user->gstn_no=$request->company_gst_no;
                         
                    
                        

                    if($data['buss_reg_no']) {
                        $user->buss_reg_no           = $data['buss_reg_no'];
                    }

                    //modified by sr

                }

                if(isset($data['question'])) {
                    $user->question              = $data['question'];
                }

                if(isset($data['answer'])) {
                    $user->answer                = $data['answer'];
                }

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

                                $d_images->merchant  = $user->id; 

                                $d_images->d_name      = $value;     
                                $d_images->is_block    = 1;

                                $d_images->save();
                            }
                        }
                    }
                    session()->forget('user');
                    Session::flash('message', 'update profile Successfully!'); 
                    Session::flash('alert-class', 'success');
                    Session::put('user', $user);

                    if($user->user_type == 4 || $user->user_type == 5) {
                        return redirect()->route('my_account');
                    } else {
                        return redirect()->route('my_profile');
                    }
                } else{
                    Session::flash('message', 'update profile Failed!'); 
                    Session::flash('alert-class', 'danger');
                    // return redirect()->route('my_profile');
                    // return Redirect::back();
                    if($user->user_type == 4 || $user->user_type == 5) {
                        return redirect()->route('my_account');
                    } else {
                        return redirect()->route('my_profile');
                    }
                }   
            }
        } else{
            Session::flash('message', 'update profile Failed!'); 
            Session::flash('alert-class', 'danger');
            return redirect()->route('my_profile');
        }
    }

    public function manage_address()
    {
        //ShippingAddress
        $page = "Users";
        $user = session()->get('user');
        if($user) {
            $value = ShippingAddress::Where('user_id', $user->id)->first();
           
            $country = CountriesManagement::Where('is_block', 1)->get();
            $state = StateManagements::Where('is_block', 1)->get();
            $city = CityManagement::Where('is_block', 1)->get();
            // dd($value);
            return View::make('user.manage_address')->with(array('user'=>$user,'city'=>$city,'state'=>$state,'country'=>$country,'value'=>$value, 'page'=>$page));
        } else {
            return redirect()->back();
        }
    }
    public function delete_address($id)
    {
        $lov= ShippingAddress::find($id);
        $lov->delete();
         Session::flash('message', 'Deleted Successfully'); 
        Session::flash('alert-class', 'danger');
        return redirect()->back();
    }
    public function setAddress($id)
    {
         $lov= ShippingAddress::where('is_default',1)->update(['is_default'=>0]);
         $mol=ShippingAddress::find($id);
         $mol->is_default=1;
         $mol->update();
         Session::flash('message', 'Updated Successfully'); 
        Session::flash('alert-class', 'success');
        return redirect()->back();
    }
    public function store_address(Request $request)
    {
        $data=$request->all();
        $user = session()->get('user');
        if(isset($data['update_btn']))
        {
             $lov= ShippingAddress::find($data['update_btn']);
        }
        else
        {
             $lov=new ShippingAddress();
        }
       
        $lov->user_id=$user->id;
        $lov->first_name=$data['first_name'];
        $lov->last_name=$data['last_name'];
        $lov->email=$data['email'];
        $lov->contact_no=$data['phone_number'];
        $lov->alternate_contact_number=$data['alternate_phone_number'];
        $lov->address=$data['address'];
        $lov->full_address=$data['full_address'];
        $lov->pincode=$data['pincode'];
        $lov->landmark=$data['landmark']; 
        
        $lov->address_type=$data['address_type'];
         if(isset($data['update_btn']))
        {
            $lov->country=$data['country_s'];
        $lov->state=$data['state_s'];
        $lov->city=$data['city_s'];
             $lov->update();
             Session::flash('message', 'Address Updated Successfully'); 
        Session::flash('alert-class', 'success');
        return redirect()->back();
        }
        else
        {
            $lov->country=$data['country'];
        $lov->state=$data['state'];
        $lov->city=$data['city'];
               $lov->save();
               Session::flash('message', 'Address Saved Successfully'); 
        Session::flash('alert-class', 'success');
        return redirect()->back();
        }
      
        
    }
    public function index (Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.list', '=', 1)
                ->first();

            if($privil) {
                $page = "Users";
                if($request->type!=null)
                $user = User::orderBy('id','desc')->where('user_type',$request->type)->get();
                else
                $user = User::orderBy('id','desc')->whereIn('user_type',[4])->get();
               
                if($user) {
                    foreach ($user as $key => $mer) {
                      
                        $country = CountriesManagement::where('id',$mer->country)->first();
                        $state = StateManagements::where('id',$mer->state)->first();
                        $city = CityManagement::where('id',$mer->city)->first();
                        
                        if($country) {
                            $user[$key]['country'] = $country->country_name;
                        } else {
                            $user[$key]['country'] = "-------";
                        }

                        if($state) {
                            $user[$key]['state'] = $state->state;
                        } else {
                            $user[$key]['state'] = "-------";
                        }

                        if($city) {
                            $user[$key]['city'] = $city->city_name;
                        } else {
                            $user[$key]['city'] = "-------";
                        }
                    }
                }
                return View::make("user.manage_user")->with(array('user'=>$user, 'page'=>$page));
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
            return redirect()->back();
        }
    }

    public function create () {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();
            $roles=DB::table('roles')->whereIn('id',[4])->pluck('role','id')->all();
            if($privil) {
                $page = "Users";
                return View::make('user.add_user')->with(array('page'=>$page,'roles'=>$roles));
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
            return redirect()->back();
        }
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
    public function download_file(Request $request)
    {
       
        $slug=$request->slug;
        $file_path = 'images/gst/';
      return response()->download(base_path($file_path.$slug));

    }
    public function store(Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.add', '=', 1)
                ->first();

            if($privil) {
                $page = "Users";
                $rules = array(
                    'user_type'=>'required',
                    'first_name'              => 'required',
                    'last_name'               => 'nullable',
                    'email'                   => 'required|email|unique:users,email',
                    'phone'                   => 'required|numeric|unique:users,phone',
                    'phone2'                  => 'nullable|numeric|unique:users,phone2',
                    'password'                => 'required|min:5',
                    'password_salt'           => 'required|min:5|same:password',
                    'company_name' => 'required_if:user_type,==,5|required',
                    'company_gst_no' => 'required_if:user_type,==,5|required',
                    'verification_document' => 'required_if:user_type,==,5|required',
                    'profile_img'             => 'nullable',
                    'remember_token'          => 'nullable',
                    'gender'                  => 'required',
                    'is_approved'             => 'required',
                    'is_block'                => 'nullable',
                    'user_type'               => 'nullable',
                );

                $messages=[
                    'password_salt.required'=>'The confirm password field is required.',
                    'password_salt.min'=>'The confirm password must be at least 5 characters.',
                    'password_salt.same'=>'The confirm password and password must match.',
                    'company_name.required_if'=>'The company name is required for user type dealer',
                     'company_gst_no.required_if'=>'The Company GSTIN  is required for user type dealer',
                      'verification_document.required_if'=>'The verification document is required for user type dealer',

                ];
                $validator = Validator::make(Input::all(), $rules,$messages);

                if ($validator->fails()) {
                    $roles=DB::table('roles')->whereNotIn('id',[1,2,3])->pluck('role','id')->all();
                    return View::make('user.add_user')->withErrors($validator)->with(array('page'=>$page,'roles'=>$roles))->withInput();
                } else {
                    $data = Input::all();
                    $ps = "gj";
                    $pe = "ja";
                    $user = new User();

                    if($user) {
                        $img_files = Input::file('profile_img');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/profile_img/'.$date;
                            $file_path = 'images/profile_img/'.$date;
                            $img_files->move($file_path, $file_name);
                            $user->profile_img = $date.'/'.$file_name;
                        } else {
                            $user->profile_img = NULL;
                        }
                        $verification_document= Input::file('verification_document');
                         if(isset($verification_document)) {
                            $file_name = $verification_document->getClientOriginalName();
                            $date = date('M-Y');
                            // $file_path = '../public/images/profile_img/'.$date;
                            $file_path = 'images/gst/'.$date;
                            $verification_document->move($file_path, $file_name);
                            $user->gst_document = $date.'/'.$file_name;
                        } else {
                            $user->gst_document = NULL;
                        }

                        $user->first_name                = $data['first_name'];
                        $user->last_name                 = $data['last_name'];
                        $user->email                     = $data['email'];
                        $user->password                  = md5($data['password']);
                        $user->password_salt             = $ps.$data['password_salt'].$pe;
                        $user->phone                     = $data['phone'];
                        $user->phone2                    = $data['phone2'];
                        $user->gender                    = $data['gender'];
                        $user->user_type                 = $request->user_type;
                        if($request->user_type==5)
                        {
                            $user->bussiness_name=$request->company_name;
                              $user->is_gst=1;
                                $user->gstn_no=$request->company_gst_no;
                            
                        }

                        if (isset($data['is_approved'])) {
                           $user->is_approved            = $data['is_approved'];
                        } else {
                            $user->is_approved           = 1;
                        }
                        $user->is_block                  = 1;
                        $user->login_type                = 1;

                        $pass = $data['password'];
                        if($user->save()) {
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
                            $site_name = "smarthub";
                            if($general){
                                $site_name = $general->site_name;
                            } else {
                                $site_name = "smarthub";
                            } 

                            $name = $user->first_name.' '.$user->last_name;
                            $email = $user->email;
                            $password = $pass;
                            $dash = route('home');
                            if ($user->user_type == 1) {
                                $dash = route('admin');
                            } else if ($user->user_type == 2 || $user->user_type == 3) {
                                $dash = route('merchant');
                            } else {
                                $dash = route('home');
                            }

                            $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                            $headers.= "MIME-Version: 1.0\r\n";
                            // $headers.= "From: $admin_email" . "\r\n";
                            $headers.= "From: smarthub@bioessenza.com" . "\r\n";
                            $to = $email;
                            $subject = "Create User";

                            $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                    <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                    <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                        <h2 style="color: #ff5c00;margin-top: 0px;">Created User</h2>
                                        <table align="center" style=" text-align: center;">
                                            <tr>
                                                <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Name</th>
                                                <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$name.'</td>
                                            </tr>
                                            <tr>
                                                <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">E-Mail</th>
                                                <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : <a href="mailto:'.$email.'" target="_blank" style="color: #333;text-decoration: none;">'.$email.'</a></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: center;text-transform: uppercase;color: #333;padding-bottom: 12px;font-weight:bold;width: 120px;">Password</th>
                                                <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$password.'</td>
                                            </tr>
                                        </table>
                                        <p>Use this email and password to Login</p>
                                        <p>Dashboard url : <a href="'.$dash.'">'.$dash.'</a></p>
                                        <p></p>
                                        <p>Thank You.</p>
                                        <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                        <p>Thanks & Regards,</p>
                                        <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                    </div>
                                </div>';

                            // if (1==1) {
                            if (mail($to,$subject,$txt,$headers)) {
                                Session::flash('message', 'Add & Mail Send Successfully!'); 
                                Session::flash('alert-class', 'success');
                                return redirect()->route('manage_user');
                            } else {
                                Session::flash('message', 'Add Successfully!'); 
                                Session::flash('alert-class', 'danger');
                                return redirect()->route('manage_user');
                            }
                        } else{
                            Session::flash('message', 'Added Failed!'); 
                            Session::flash('alert-class', 'danger');
                            return redirect()->route('manage_user');
                        }  
                    } else{
                        Session::flash('message', 'Added Failed!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('manage_user');
                    }
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
            return redirect()->back();
        }
    }

    public function edit ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Users";
                $roles=DB::table('roles')->whereNotIn('id',[1,2,3])->pluck('role','id')->all();

                $user = User::where('id',$id)->first();
                if($user) {
                    $docs = MerchantsDocuments::Where('merchant', $user->id)->get();
                    if($docs) {
                        $user['docs'] = $docs;
                    } else {
                        $user['docs'] = NULL;
                    }
                }
                return View::make("user.edit_user")->with(array('user'=>$user, 'page'=>$page,'roles'=>$roles));
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
            return redirect()->back();
        }
    }

    public function update (Request $request) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.edit', '=', 1)
                ->first();

            if($privil) {
                $page = "Users";
                $id = Input::get('user_id');
                $user = '';
                if($id != '') {
                    $user = User::Where('id', $id)->first();
                }

                if($user) {
                    $rules = array(
                         'user_type'=>'required',
                        'first_name'              => 'required',
                        'last_name'               => 'nullable',
                        'email'                   => 'required|email|unique:users,email,'.$id.',id',
                        'phone'                   => 'required|numeric|unique:users,phone,'.$id.',id',
                        'phone2'                  => 'nullable|numeric|unique:users,phone2,'.$id.',id',
                        'gender'                  => 'required',
                         'company_name' => 'required_if:user_type,==,5|required',
                        'company_gst_no' => 'required_if:user_type,==,5|required',
                        'verification_document' => 'nullable',
                        'profile_img'             => 'nullable',
                        'is_approved'             => 'required',
                        'is_block'                => 'nullable',
                        
                        'login_type'              => 'nullable',
                    );
                    $messages=[
                  
                    'company_name.required_if'=>'The company name is required for user type dealer',
                     'company_gst_no.required_if'=>'The Company GSTIN  is required for user type dealer',
                      'verification_document.required_if'=>'The verification document is required for user type dealer',

                ];
                    $validator = Validator::make(Input::all(), $rules,$messages);

                    if ($validator->fails()) {
                        if($user) {
                            $docs = MerchantsDocuments::Where('merchant', $user->id)->get();
                            if($docs) {
                                $user['docs'] = $docs;
                            } else {
                                $user['docs'] = NULL;
                            }
                        }
                        $roles=DB::table('roles')->whereNotIn('id',[1,2,3])->pluck('role','id')->all();
                        return Redirect::to('/edit_user/' . $id)->withErrors($validator)->with(array('user'=>$user, 'page'=>$page,'roles'=>$roles));
                    } else {
                        $data = Input::all();

                        $img_files = Input::file('profile_img');
                        if(isset($img_files)) {
                            $file_name = $img_files->getClientOriginalName();
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
                        $user->email                     = $data['email'];
                        $user->phone                     = $data['phone'];
                        $user->phone2                    = $data['phone2'];
                        $user->gender                    = $data['gender'];
                       $user->user_type                 = $request->user_type;
                        if($request->user_type==5)
                        {
                            $user->bussiness_name=$request->company_name;
                              $user->is_gst=1;
                                $user->gstn_no=$request->company_gst_no;
                            
                        }

                        if (isset($data['is_approved'])) {
                            $user->is_approved           = $data['is_approved'];
                        } else {
                            $user->is_approved           = 0;
                        }

                        $user->is_block                  = 1;
                        $user->login_type                = 1;

                        if($user->save()) {
                            Session::flash('message', 'update Successfully!'); 
                            Session::flash('alert-class', 'success');
                            return redirect()->route('manage_user');

                        } else{
                            Session::flash('message', 'update Failed!'); 
                            Session::flash('alert-class', 'danger');
                            return redirect()->route('manage_user');
                        }   
                    }
                } else{
                    Session::flash('message', 'update Failed!'); 
                    Session::flash('alert-class', 'danger');
                    return redirect()->route('manage_user');
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
            return redirect()->back();
        }
    }

    public function view ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.view', '=', 1)
                ->first();

            if($privil) {
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
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
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
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->id)){
                    $id = $request->id;
                    if($id != 0) {
                        $user = User::where('id',$id)->where('user_type', '!=', 1)->first();
                        if($user) {
                            if($user->delete()) {
                                Session::flash('message', 'Deleted Successfully!'); 
                                Session::flash('alert-class', 'success');
                                $error = 0;
                            } else {
                                Session::flash('message', 'Deleted Failed!'); 
                                Session::flash('alert-class', 'danger');
                                $error = 1;
                            }
                        }   else {
                            Session::flash('message', 'Deleted Failed!'); 
                            Session::flash('alert-class', 'danger');
                            $error = 1;
                        }           
                    } else {
                        Session::flash('message', 'Deleted Failed!'); 
                        Session::flash('alert-class', 'danger');
                        $error = 1;
                    }
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
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
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.delete', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
                    $ids = $request->ids;
                    if(sizeof($ids) != 0) {
                        foreach ($ids as $key => $value) {
                            $user = User::where('id',$value)->where('user_type', '!=', 1)->first();
                            if($user){
                                if($user->delete()) {
                                    Session::flash('message', 'Deleted Successfully!'); 
                                    Session::flash('alert-class', 'success');
                                    $error = 0;
                                } else {
                                    Session::flash('message', 'Deleted Failed!'); 
                                    Session::flash('alert-class', 'danger');

                                }
                            }   else {
                                Session::flash('message', 'Deleted Failed!'); 
                                Session::flash('alert-class', 'danger');
                            }           
                        }
                    } else {
                        Session::flash('message', 'Deleted Failed!'); 
                        Session::flash('alert-class', 'danger');
                        $error = 1;
                    }
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
            // return redirect()->back();
            $error = 1;
        }
        
        echo $error;
    }

    public function Statususer ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
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
                        Session::flash('alert-class', 'success');
                        return redirect()->route('manage_user');
                    } else{
                        Session::flash('message', 'Failed Block or Unblock!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('manage_user');
                    }
                } else{
                    Session::flash('message', 'Failed Block or Unblock!'); 
                    Session::flash('alert-class', 'danger');
                    return redirect()->route('manage_user');
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
            return redirect()->back();
        }
    }

    public function ApprovedUser ($id) {
        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
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
                            $site_name = "smarthub";
                            if($general){
                                $site_name = $general->site_name;
                            } else {
                                $site_name = "smarthub";
                            } 

                            $name = $user->first_name.' '.$user->last_name;
                            $email = $user->email;
                            $dash = route('home');
                            if ($user->user_type == 1) {
                                $dash = route('admin');
                            } else if ($user->user_type == 2 || $user->user_type == 3) {
                                $dash = route('merchant');
                            } else {
                                $dash = route('home');
                            }

                            $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                            $headers.= "MIME-Version: 1.0\r\n";
                            // $headers.= "From: $admin_email" . "\r\n";
                            $headers.= "From: smarthub@bioessenza.com" . "\r\n";
                            $password=	substr($user->password_salt, 2, -2);
                            $to = $email;
                            $subject = $msg;

                            $txt = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                    <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                    <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                        <h2 style="color: #ff5c00;margin-top: 0px;">'.$msg.'</h2>
                                        <table align="center" style=" text-align: center;">
                                            <tr>
                                                <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">Name</th>
                                                <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$name.'</td>
                                            </tr>
                                            <tr>
                                                <th style=" text-align: center;text-transform: uppercase;padding-bottom: 12px;color: #333;font-weight:bold;width: 120px;">E-Mail</th>
                                                <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : <a href="mailto:'.$email.'" target="_blank" style="color: #333;text-decoration: none;">'.$email.'</a></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: center;text-transform: uppercase;color: #333;padding-bottom: 12px;font-weight:bold;width: 120px;">Password</th>
                                                <td style="font-size: 14px;font-weight: bold;color: #333;padding-bottom: 12px;"> : '.$password.'</td>
                                            </tr>
                                        </table>
                                        <p>Use this email and password to Login</p>
                                        <p>Dashboard url : <a href="'.$dash.'">'.$dash.'</a></p>
                                        <p></p>
                                        <p>Thank You.</p>
                                        <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                        <p>Thanks & Regards,</p>
                                        <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                    </div>
                                </div>';

                            if (mail($to,$subject,$txt,$headers)) {
                                Session::flash('message', $msg.' and Mail Send Successfully!');
                                Session::flash('alert-class', 'success');
                                return redirect()->route('manage_user');
                            } else {
                                Session::flash('message', $msg.' and Mail Send Failed!');  
                                Session::flash('alert-class', 'danger');
                                return redirect()->route('manage_user');
                            }                                
                        } else {
                            Session::flash('message', $msg); 
                            Session::flash('alert-class', 'success');
                            return redirect()->route('manage_user');
                        }
                    } else{
                        Session::flash('message', 'Failed Approved or Rejected!'); 
                        Session::flash('alert-class', 'danger');
                        return redirect()->route('manage_user');
                    }
                } else{
                    Session::flash('message', 'Failed Approved or Rejected!'); 
                    Session::flash('alert-class', 'danger');
                    return redirect()->route('manage_user');
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
            return redirect()->back();
        }
    }

    public function UserBlock( Request $request) {  
        $ids = array();
        $error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
                    $ids = $request->ids;
                    if(sizeof($ids) != 0) {
                        foreach ($ids as $key => $value) {
                            $user = User::where('id',$value)->where('user_type', '!=', 1)->first();
                            if($user){
                                $user->is_block = 0;
                                $user->save();
                                Session::flash('message', 'Blocked Successfully!'); 
                                Session::flash('alert-class', 'success');
                                $error = 0;
                            }   else {
                                Session::flash('message', 'Blocked Failed!'); 
                                Session::flash('alert-class', 'danger');
                            }           
                        }
                    } else {
                        Session::flash('message', 'Blocked Failed!'); 
                        Session::flash('alert-class', 'danger');
                        $error = 1;
                    }
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
            // return redirect()->back();
            $error = 1;
        }
        
        echo $error;
    }

    public function UserUnblock( Request $request) {    
        $ids = array();
        $error = 1;

        $loged = session()->get('user');
        if($loged) {
            $privil = DB::table('previlages as A')
                ->leftjoin('modules as B', 'A.module', '=', 'B.id')
                ->select('A.id as pid','A.*','B.id as mid','B.*')
                ->where('B.module_name', '=', 'All User')
                ->where('A.role', '=', $loged->user_type)
                ->where('A.status', '=', 1)
                ->first();

            if($privil) {
                if($request->ajax() && isset($request->ids)){
                    $ids = $request->ids;
                    if(sizeof($ids) != 0) {
                        foreach ($ids as $key => $value) {
                            $user = User::where('id',$value)->where('user_type', '!=', 1)->first();
                            if($user){
                                $user->is_block = 1;
                                $user->save();
                                Session::flash('message', 'Unblocked Successfully!'); 
                                Session::flash('alert-class', 'success');
                                $error = 0;
                            }   else {
                                Session::flash('message', 'Unblocked Failed!'); 
                                Session::flash('alert-class', 'danger');
                            }           
                        }
                    } else {
                        Session::flash('message', 'Unblocked Failed!'); 
                        Session::flash('alert-class', 'danger');
                        $error = 1;
                    }
                }
            } else {
                Session::flash('message', 'You Are Not Access This Module!'); 
                Session::flash('alert-class', 'danger');
                // return redirect()->back();
                $error = 1;
            }
        } else {
            Session::flash('message', 'Please Login Properly!'); 
            Session::flash('alert-class', 'danger');
            // return redirect()->back();
            $error = 1;
        }

        echo $error;
    }
}