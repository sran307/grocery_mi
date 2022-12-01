<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
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
use Socialite;
use Laravel\Socialite\SocialiteServiceProvider;

class SocialAuthController extends Controller
{
    protected $respose;
 
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function redirect($service) {
        return Socialite::driver ( $service )->redirect ();
    }

    public function callback($service) {
        $social = Socialite::with ($service)->user ();
        // print_r($social);die();

        if($social) {
            $ok = 0;
            
            if($social['email']) {
                $ok = 1;
            } else {
                $ok = 0;
            }

            if($ok == 1) {
                $loged_usr = User::Where('email', $social['email'])->first();
                if($loged_usr) {
                    if(($loged_usr->user_type == 4)) {
                        if($loged_usr->verification == 1) {
                            if($loged_usr->is_block == 1) {
                                session()->forget('user');
                                Session::flash('message', 'Login Successfully!'); 
                                Session::flash('alert-class', 'alert-success');
                                Session::put('user', $loged_usr);

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
                                            $carts->att_name        = (isset($value['att_name'])) ? $value['att_name'] : NULL;
                                            $carts->att_name        = (isset($value['att_name'])) ? $value['att_name'] : NULL;
                                            $carts->att_value        = (isset($value['att_value'])) ? $value['att_value'] : NULL;
                                            $carts->original_price  = (isset($value['original_price'])) ? $value['original_price'] : 0;
                                            $carts->product_cost       = (isset($value['product_cost'])) ? $value['product_cost'] : 0;
                                            $carts->price       = (isset($value['price'])) ? $value['price'] : 0;
                                            $carts->tax_amount   = (isset($value['tax_amount'])) ? $value['tax_amount'] : 0;
                                            $carts->total_price   = (isset($value['total_price'])) ? $value['total_price'] : 0;
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
                                Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('signin');
                            }
                        } else {
                            session()->forget('user');
                            if(isset($_COOKIE["user"])) {
                                setcookie ("user","");
                            }
                            Session::flash('message', 'Your account is not yet activated please check your e-mail and activate your account to Login!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('signin');
                        }
                    } else {
                        session()->forget('user');
                        if(isset($_COOKIE["user"])) {
                            setcookie ("user","");
                        }        
                        Session::flash('message', 'Login failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('signin');
                    }
                } else {
                    $users = new User();

                    if($users) { 
                        $users->email                     = $social['email'];

                        if($social['name']) {
                            $users->first_name            = $social['name'];
                        } else {
                            $users->first_name            = NULL;
                        } 

                        if(isset($social['avatar'])) {
                            $users->profile_img           = $social['avatar'];
                        } else {
                            $users->profile_img           = NULL;
                        }
                        
                        $users->user_type                 = 4;
                        $users->is_approved               = 1;
                        $users->approved_date             = date('Y-m-d');
                        $users->verification              = 1;
                        $users->is_block                  = 1;
                        $users->login_type                = 2;
                        $users->signup                    = $service;
                        $users->social_ref_id             = $social['id'];

                        if($users->save()) {
                            $users = User::Where('id', $users->id)->first();
                            $adm = User::where('user_type', 1)->where('is_block', 1)->first();
                            $admin_email = "info.ecambiar@gmail.com";
                            if($adm) {
                                $admin_email = $adm->email;
                            }

                            $mail_img = asset('images/mail.png');
                            $phone_img = asset('images/phone.png');
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
                            } 

                            $contacts = \DB::table('email_settings')->first();
                            $c_email = "teamadsdev5@gmail.com";
                            $c_phone = "971 925 6546";
                            if($contacts) {
                                $c_email = $contacts->contact_email;
                                $c_phone = $contacts->contact_phone1;
                            }

                            $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                            $headers.= "MIME-Version: 1.0\r\n";
                            // $headers.= "From: $admin_email" . "\r\n";
                            $headers.= "From: noreply@ecambiar.com" . "\r\n";
                            $to = $users->email;
                            $subject = "Account Activated";
                            $txt = '<div style="margin: 30px auto 20px;border: 1px solid #ff5c00;width: 602px;">
                                <table width="600" align="center" cellpadding="0" cellspacing="0" height="74">
                                    <tbody>
                                        <tr bgcolor="#ffffff">
                                            <td style="padding-left:20px;padding-top:10px;padding-bottom:10px" height="70"><a href="'.route('home').'"><img src="'.$logo.'" border="0"></a></td>
                                        </tr> 
                                        <tr bgcolor="#ff5c00" height="7px">
                                            <td><br></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table width="600px" align="center" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Your Username</b> </td>
                                            <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b><a href="mailto:'.$users->email.'" target="_blank">'.$users->email.'</a></b> </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Register And Login Successfully, Please Update Your Profile.</b> </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table width="600" align="center" cellpadding="0" cellspacing="0" height="61">
                                    <tbody>
                                        <tr bgcolor="#ffffff">
                                            <td colspan="5" height="11"><br></td>
                                        </tr>
                                        
                                        <tr bgcolor="#ff5c00" height="7px">
                                            <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec;padding-left:10px" width="100" height="48">Contact Us : </td>

                                            <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec" width="29"><img src="'.$mail_img.'"></td>

                                            <td style="font-family:Segoe UI,Arial;font-size:11px;color:#ececec" width="135"><a href="mailto:'.$c_email.'" style="color:#ececec;text-decoration:none"> '.$c_email.'</a></td>

                                            <td style="font-family:Segoe UI,Arial;font-size:14px;font-weight:bold;color:#ececec" width="28"><img src="'.$phone_img.'" style="margin-left:8px;"></td>

                                            <td style="font-family:Segoe UI,Arial;font-size:11px;color:#ececec" width="300">'.$c_phone.'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>'; 

                            if(mail($to,$subject,$txt,$headers) && mail($admin_email,$subject,$txt,$headers)) {
                                Session::put('user', $users);
                                Session::flash('message', 'Register and Mail Send Successfully, Please Completed Your Profile!'); 
                                Session::flash('alert-class', 'alert-success');
                                return redirect()->route('my_account');
                            } else {
                                Session::put('user', $users);
                                Session::flash('message', 'Register Successfully, but Mail Send Failed, Please Completed Your Profile!'); 
                                Session::flash('alert-class', 'alert-danger');
                                return redirect()->route('my_account');
                            }
                        } else{
                            Session::flash('message', 'Register Failed!'); 
                            Session::flash('alert-class', 'alert-danger');
                            return redirect()->route('signin');
                        }  
                    } else{
                        Session::flash('message', 'Register Failed!'); 
                        Session::flash('alert-class', 'alert-danger');
                        return redirect()->route('signin');
                    }
                } 
            } else {
                Session::flash('message', 'Please Try another Time or another Id!'); 
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('signin');
            }
        } else {
            Session::flash('message', 'Please Try another Time or another Id!'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('signin');
        }
    }
}