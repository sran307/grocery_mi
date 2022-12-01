<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    DB, Validator, Session, Storage, Mail,
};
Use App\{
    User,
    CityManagement,
    CategoryManagementSettings,
    SubCategoryManagementSettings,
    Products,
    MeasurementUnits,
    ProductsImages,
    StateManagements,
    ShippingAddress,
    Brands,
    ProductsAttributes,
    Carts,
    BannerImageSettings,
    Widget, 
    Orders,
    TaxCutoff,
    Cod,
    OrdersTransactions,
    OrderDetails,
    AboutUsCMSSettings,
    EmailSettings,
    Pincode,
    HomeWidget,
    DeliveryTime,
    StockTransactions
};
use App\Notification;
use URL;
use Carbon\Carbon;
class ApiController extends Controller
{
    //mobile number checking start
    public function check_phone(Request $request)
    {
        $phone      =   $request->phone;
        
        $rules = array(
            'phone'     => 'required|numeric|digits:10',
        );
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            //$error_message = $validator->errors()->toArray();
            //$error = array('status' => 'error', 'validation' => $validator->errors(), 'msg' => 'Please Fix Validation Error');
            return response()->json([
                "status"    =>  "error",
                "msg"=>  $validator->errors(),
            ]);
        }else{

            $rules = array(
                'phone'     => 'required | unique:users,phone',
            );

            $messages=array(
                'phone.unique'=>'This phone number already taken..!',
            );

            $validator = Validator::make($request->all(), $rules,$messages);

            if ($validator->fails()) {
               
                //$error = array('status' => 'success', 'msg' => 'Login successful.', 'response' => 'login_1');
                return response()->json([
                    "status"    =>  "success",
                    "msg"       =>  "Login Successful.",
                    "response"  =>  "login_1"
                ]);
            }else{
                return response()->json([
                    "status"    =>  "success",
                    "msg"       =>  "Please sign up.",
                    "response"  =>  "login_0"
                ]);
                
            }
        }
    }
    //mobile number checking end
 //otp generation start
    public function get_otp(Request $request)
    {
        
        $rules = array(
            'phone'     => 'required|numeric|digits:10',
        );
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            //$error_message = $validator->errors()->toArray();
            //$error = array('status' => 'error', 'validation' => $validator->errors(), 'msg' => 'Please Fix Validation Error');
            return response()->json([
                "status"    =>  "error",
                "msg"=>  $validator->errors(),
            ]);
        }else{
            $otp = mt_rand(100000, 999999);
            $mobile=(int)$request->phone;            
            $text = "Your OTP is ".$otp;
            //dd($mobile);
            $xml_data = "user=Whizcrew&key=46b61b4c3aXX&mobile=$mobile&message=$text&senderid=ALRTSM&accusage=1";
            $URL = "https://sms.bulkssms.com/submitsms.jsp?"; 
            $ch = \curl_init($URL);
            \curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            \curl_setopt($ch, CURLOPT_POST, 1);
            \curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');			
            \curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
            \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            return response()->json([
                "status"    =>  "success",
                "msg"       =>  "OTP generated successfully.",
                "otp"       =>  "$otp"
            ]);
        }
    }

    //otp generation end
    //sign up section start
    public function signup(Request $request)
    {
        $phone      =   $request->phone;
        $full_name  =   $request->full_name;
        $email      =   $request->email;
        $device_id  =   $request->device_id;
        $device_type=   $request->device_type;
        $rules=[
            'phone'         =>  'required | numeric | digits:10 | unique:users,phone',
            "full_name"     =>  "required | string",
            "email"         =>  "nullable | unique:users,email",
            "device_id" =>  "required",
            "device_type"   =>  "required"
        ];

        $validator=Validator::make($request->all(), $rules);

        if($validator->fails()){
            //$error = array('status' => 'error', 'validation' => $validator->errors(), 'msg' => 'Please Fix Validation Error');
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors(),
            ]);
        }else{
            $verification="GJ".uniqid();
            DB::beginTransaction();
            try{
                User::create([
                    "phone"         =>  $phone,
                    "mobile_verify" => 1,
                    "signup"        =>  "App SignUp",
                    "verification"  =>  1,
                    "user_type"     =>  4,
                    "device_id"     =>  $device_id,   
                    "device_type"   =>  $device_type,  
                    "first_name"   =>  $full_name,
                    "email"        =>  $email,
                    "login_type"    =>  1,
                    "is_block"      =>  1,
                    "is_approved"   =>  1,
                    "approved_date" =>  date("Y-m-d"),
                    "verification"  =>  $verification,
                ]);
               
                    //email verification start
                if(isset($email)){
                    $r_url = route('app_activation', ['code' => $verification]);
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
                    $site_name = "Grocery360.in";
                    if($general){
                        $site_name = $general->site_name;
                    } 

                    $contacts = \DB::table('email_settings')->first();
                    $c_email = "info@grocery360.in";
                    $c_phone = "9508558974";
                    if($contacts) {
                        $c_email = $contacts->contact_email;
                        $c_phone = $contacts->contact_phone1;
                    }

                    $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers.= "MIME-Version: 1.0\r\n";
                    // $headers.= "From: $admin_email" . "\r\n";
                    $headers.= "From: Grocery360" . "\r\n";
                     $headers.= "order@grocery360.in" . "\r\n";
                    $to = $email;
                    $subject = "Activate Account";
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

                        <table width="600" align="center">
                            <tbody>
                                <tr>
                                    <td style="padding:10px;font-size:15px;color:#333333;font-weight:bold;font-family:Segoe UI,Arial,Helvetica,sans-serif">Your registration is completed..! Click on the link below to activate your account.<br></td>
                                </tr>
                            </tbody>
                        </table>

                        <table width="600px" align="center" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>Please click on link below to activate your account</b> </td>
                                </tr>
                        
                                <tr>
                                    <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b>link</b> </td>
                                    <td style="background-color:#ececec;border-top:dotted #ababab 1px;font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;color:#414042;padding-left:15px;padding-top:10px;padding-bottom:5px"> <b><a href="'.$r_url.'" target="_blank">'.$r_url.'</a></b> </td>
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


                    if(mail($to,$subject,$txt,$headers)) {
                        DB::commit();
                        return response()->json([
                            "status"    =>  "success",
                            "msg"       =>  "Registration successful and verify using email verification link .",
                            "phone"     =>  $phone,
                            "name"      =>  $full_name,
                            "email"     =>  $email,
                            "user_id"   =>  User::max("id"),
                        ]);
                    }else{
                        DB::commit();
                        return response()->json([
                            "status"    =>  "error",
                            "msg"       =>  "Cannot send the mail."
                        ]);
                    }
                    //email verification end
                }else{
                    DB::commit();
                    return response()->json([
                        "status"    =>  "success",
                        "msg"       =>  "Registration successful.",
                        "phone"     =>  $phone,
                        "name"      =>  $full_name,
                        "email"     =>  $email,
                        "user_id"   =>  User::max("id"),
                    ]);
                }
            }catch(\Exception $e){
                //dd($e);
                DB::rollback();
                //$error = array('status' => 'error', 'msg' => 'Cannot register.');
                return response()->json([
                    "status"    =>  "error",
                    "msg"       =>  "Cannot register."
                ]);
            } 
        }
    }
    //signup section end

    //signin start
    public function signin(Request $request)
    {
        $phone      =   $request->phone;
        $user_phone =   User::where("phone", $phone)->value("phone");
        $device_id  =   $request->device_id;
        $device_type=   $request->device_type;

        $rules = array(
            'phone'         => 'required|numeric|digits:10',
            "device_id"     =>  "required",
            "device_type"   =>  "required"
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $error = array('status' => 'error', 'validation' => $validator->errors(), 'msg' => 'Please Fix Validation Error');
            return response()->json([
                "status"    =>  "errors",
                "msg"       =>  $validator->errors(),
            ]);
        }else{

            if($phone == $user_phone){
                //setting login verification
                DB::beginTransaction();
                try{
                    User::where("phone", $phone)->update([
                        "login_verify"   => 1,
                        "device_id"     =>  $device_id,
                        "device_type"   =>  $device_type,
                        
                    ]);
                    DB::commit();
                }catch(\Exception $e){
                    DB::rollback();
                }

                $details=User::where("phone", $phone)->first();

                return response()->json([
                    "status"    => "success",
                    "msg"       =>  "Login successful.",
                    "user_id"   =>  $details->id,
                    "full_name" =>  $details->first_name,
                    "phone"     =>  $details->phone,
                    "email"     =>  $details->email
                ]);
            }else{
                return response()->json([
                    "status"    => "error",
                    "msg"       =>  "Please register first."
                ]);
            }
        }  
    }
    //sign in end

    //user profile section start
    public function profile(Request $request)
    {
        $user_id=$request->user_id;

        $rules = array(
            'user_id'     => 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors(),
            ]);
        }else{

            $data=User::where("id", $user_id)->first();
            if(isset($data->profile_img)){
                $image= asset("images/profile_img/".$data->profile_img);
            }else{
                $image= asset("images/profile_img/app_profile.png");
            }
        
            if(isset($data)){
                return response()->json([
                    "status"        =>   "success",
                    "user_id"       =>  $data->id,
                    "full_name"     =>  $data->first_name,
                    "email"         =>  $data->email,
                    "phone"         =>  $data->phone,
                    "image"         =>  $image,
                ]);
            }else{
                return response()->json([
                    "status"    =>  "error",
                    "msg"       =>  "Register first."
                ]);
            }
        }  
    }
    //user profile section end

    //user profile updation start
    public function update_profile(Request $request){
        $user_id            =   $request->user_id;
        $full_name          =   $request->full_name;
        $email              =   $request->email;
        //$city               =   $request->city;
        //$address            =   $request->address;
       // $pincode            =   $request->pincode;

        $rules = array(
            "user_id"       =>  "required | numeric",
            'full_name'     =>  "required | string",
            "email"         =>  "required | email ",
          //  "address"       =>  "required",
           // "city"          =>  "required",
          //  "pincode"       =>  "required | digits:6 | numeric"
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            $data = User::where("id", $user_id)->first();   
          
            if(!isset($data)){
                return response()->json([
                    "status"    =>  "error",
                    "msg"       =>  "User data not found."
                ]);
            }else{
                //checking whether the email and phone number is same as this user
                //if current id and the given email id's table id are same then the same user same as phone
                $user_email= User::where("id", $user_id)->value("email");
                $cite_email=User::where("email", $email)->value("email");
                if(($email == $user_email) || !isset($cite_email)){
                    
                    DB::beginTransaction();
                    try{
                        User::where("id", $user_id)->update([
                            "first_name"   =>  $full_name,
                            "email"        =>  $email,
                            "verification"  =>  1,
                            "email_verify"  =>  1
                            //"city"         =>  $city,
                           // "address1"     =>  $address,
                           // "pincode"      =>   $pincode
                        ]);
                    
                        DB::commit();
                        return response()->json([
                            "status"        =>  "success",
                            "msg"           =>  "Profile updated."
                        ]);
                    }catch(\Exception $e){
                        //dd($e);
                        DB::rollback();
                        return response()->json([
                            "status"    =>  "error",
                            "msg"       =>  "Cannot update the profile."
                        ]);
                    }
                }else{
                    return response()->json([
                        "status"    =>  "error",
                        "msg"       =>  "Email used by another person."
                    ]);
                }

                
            }
        }
    }
    //user profile updation end

    //state section started
    public function state()
    {
        $states=StateManagements::where("is_block", 1)->where('id', 12)->get();
        $state=[];
        foreach($states as $value){
            array_push($state, [
                "state_id"    =>  $value->id,
                "state"  =>  $value->state
            ]);
        }

        return response()->json([
            "status"      =>  "success",
            "states"      =>  $state
        ]);
    }
    //state section ended
    //city section start
    public function city(Request $request)
    {
        $rules = array(
            "state_id"       =>  "required | numeric",
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            $cities=CityManagement::where("state", $request->state_id)
                                ->where("is_block", 1)->get();
            $city=[];
            foreach($cities as $value){
                array_push($city, [
                    "district_id"    =>  $value->id,
                    "district"  =>  $value->city_name
                ]);
            }

            return response()->json([
                "status"    =>  "success",
                "districts"      =>  $city
            ]);
        }
    }
    //city section end

    //profile image update section started
    public function profile_img(Request $request)
    {
        $port_image = $request->image;
        $user_id = $request->user_id;
        $encoded_data = explode('/',$port_image);
        //dd($encoded_data);
        if($encoded_data[0]!='data:image')
        {
             $base64img = 'data:image/jpg;base64,'. $port_image;
            
        }
        else
        {
            $base64img=$port_image;
        }
       // dd($base64img);
        $base64img = str_replace('\r\n', '', $base64img);  
        $base64img = str_replace('%2B', '+', $base64img);  
        $base64img = str_replace(' ', '+', $base64img);  
        $extension = str_replace("image/", "", substr($base64img, 5, strpos($base64img, ';')-5));
        $base64img = str_replace(substr($base64img, 0, strpos($base64img, ',')+1), "", $base64img);
        //dd($extension);
        $data1 = base64_decode($base64img);
        $target_file_name = time() . '.' . $extension; 
       
        // $path =base_path($path . $target_file_name);
        $path="images/profile_img/";
        $path =base_path($path . $target_file_name);
       
        //Image::make($data1)->resize($h,$w)->save($path); 
        file_put_contents($path, $data1);

        DB::beginTransaction();
        try{
            User::where("id", $user_id)->update([
                "profile_img" =>  $target_file_name,
            ]);
            DB::commit();
            return response()->json([
                "status"    =>  "success",
                "msg"       =>  "Image updated successfully.",
               
            ]);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  "Cannot update the image."
            ]);
        }
    }
    //profile image section ended

    //main category section started
    public function main_category()
    {
        $items=CategoryManagementSettings::where("is_block", 1)->orderBy("id", "desc")->get();
        $item=[];
        foreach($items as $value){
            array_push($item, [
                "id"    =>  $value->id,
                "name"  =>  $value->main_cat_name,
                "image" =>  asset("images/main_cat_image/".$value->main_cat_image),
            ]);
        }

        if(isset($items)){
            return response()->json([
                "status"    =>  "success",
                "msg"       =>  "Category fetched successfully",
                "categories"=>  $item
            ]);
        }else{
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  "No category found"
            ]);
        }
    }
    //main category section ended

    //sub category section started
    public function sub_category(Request $request)
    {
        $cat_id=$request->cat_id;

        $rules = array(
            "cat_id"       =>  "required | numeric",
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
             $sub_items=SubCategoryManagementSettings::where("main_cat_name", $cat_id)
                                                        ->where("is_block", 1)
                                                        ->orderBy("sub_cat_id", "desc")
                                                        ->get();

            if(isset($sub_items)){
                $sub_item=[];
                foreach($sub_items as $value){
                    array_push($sub_item, [
                        "sub_id"    =>  $value->sub_cat_id,
                        "sub_name"  =>  $value->sub_cat_name,
                        "sub_image" =>  asset("images/sub_cat_image/". $value->sub_cat_image),
                    ]);
                }
                return response()->json([
                    "status"        =>  "success",
                    "msg"           =>  "Sub category items fetched successfully.",
                    "sub_cat_item"  =>  $sub_item
                ]);
            }else{
                return response()->json([
                    "status"        =>  "error",
                    "msg"           =>  "Sub category items not found.",
                ]);
            }
        }

    }
    //sub category section ended

    //products section started
    public function products()
    {
       $products= Products::Where('is_block',1)->OrderBy('id', 'desc')->get();
      
       if(isset($products)){
            $all_product=[];
            $rel=[];
            foreach($products as $product){
                array_push($all_product, [
                    "product_id"    =>  $product->id,
                    "product_code"  =>  $product->product_code,
                    "product_name"  =>  $product->product_title,
                    "image"         =>  asset("images/featured_products/".$product->featured_product_img),
                    "total_qty"     =>  $product->onhand_qty,
                    "unit"          =>  MeasurementUnits::where('id', $product->measurement_unit)->value("unit_name"),
                    "price"         =>  number_format($product->discounted_price, 2)          
                ]);


            }

            return response()->json([
                "status"    =>  "success",
                "msg"       =>  "Product listed successfully",
                "products"  =>  $all_product,
            ]);
       }else{
        return response()->json([
            "status"    =>  "error",
            "msg"       =>  "Product not available",
        ]);
       }
    }
    //product section ended

    //product details section started
    public function product_detail(Request $request)
    {
        $id=$request->product_id;

        $rules = array(
            "product_id"       =>  "required | numeric",
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            $products = Products::Where('id', $id)->Where('is_block',1)->get();
            $related = Products::Where('sub_sub_cat_name', $products[0]->sub_sub_cat_name)->Where('id', '!=', $id)->Where('is_block',1)
                            ->select('products.*','discounted_price as discounted_price')->get();
            $pro_images= ProductsImages::where('product_id', $products[0]->id)->Where('is_block', 1)->get();
            //dd($pro_images);
            if(isset($products)){
                $pro_detail=[];
                foreach($products as $value){
                    $description=strip_tags(html_entity_decode($value->product_desc));
                    $features=strip_tags(html_entity_decode($value->features));
                    $shipping=strip_tags(html_entity_decode($value->shiping_policy));
                    array_push($pro_detail, [
                        "name"  =>  $value->product_title,
                        "description"   =>  str_replace(["\t\t", "\t"], '', str_replace(PHP_EOL, ' ', $description)),
                        "features"      =>  str_replace(["\t", "\t\t"], '', str_replace(PHP_EOL, ' ', $features)),
                        "image"         =>  asset("images/featured_products/".$value->featured_product_img),
                        "total_qty"     =>  $value->onhand_qty,
                        "unit"          =>  MeasurementUnits::where('id', $value->measurement_unit)->value("unit_name"),
                        "price"         =>  number_format($value->discounted_price, 2), 
                        "tax"           =>  number_format($value->tax_amount, 2),
                        "service_charge"=>  number_format($value->service_charge, 2),
                        "shipping_charge"=> number_format($value->shipping_charge, 2),
                        "shipping_policy"=> str_replace(PHP_EOL, '', $shipping)
                    ]);
                }

                $related_product=[];
                foreach($related as $value){
                    $description=strip_tags(html_entity_decode($value->product_desc));
                    $features=strip_tags(html_entity_decode($value->features));
                    $shipping=strip_tags(html_entity_decode($value->shiping_policy));
                    array_push($related_product, [
                        "product_id"    =>  $value->id,
                        "product_code"  =>  $value->product_code,
                        "product_name"  =>  $value->product_title,
                        "image"         =>  asset("images/featured_products/".$value->featured_product_img),
                        "total_qty"     =>  $value->onhand_qty,
                        "unit"          =>  MeasurementUnits::where('id', $value->measurement_unit)->value("unit_name"),
                        "price"         =>  number_format($value->discounted_price, 2)       
                    ]);
                }

                $product_image=[];
                foreach($pro_images as $value){
                    array_push($product_image, [
                        "product_image" =>  asset("images/products/".$value->image)
                    ]);
                }

                return response()->json([
                    "status"    =>  "success",
                    "msg"       =>  "Product details fetched successfully.",
                    "details"   =>  $pro_detail,
                    "related"   =>  $related_product,
                    "images"    =>  $product_image
                ]);
            }
        }
    }
    //product details section ended

    //subcategory product listing section started
    public function sub_cat_listing(Request $request)
    {
        $sub_id=$request->sub_id;

        $rules = array(
            "sub_id"       =>  "required | numeric",
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            $products= Products::where("sub_cat_name", $sub_id)
            ->Where('is_block',1)->OrderBy('id', 'desc')->get();
      
            if(isset($products)){
                    $all_product=[];
                    foreach($products as $product){
                        array_push($all_product, [
                            "product_id"    =>  $product->id,
                            "product_code"  =>  $product->product_code,
                            "product_name"  =>  $product->product_title,
                            "image"         =>  asset("images/featured_products/".$product->featured_product_img),
                            "total_qty"     =>  $product->onhand_qty,
                            "unit"          =>  MeasurementUnits::where('id', $product->measurement_unit)->value("unit_name"),
                            "price"         =>  number_format($product->discounted_price, 2)          
                        ]);
                    }

                    return response()->json([
                        "status"    =>  "success",
                        "msg"       =>  "Product listed successfully",
                        "products"  =>  $all_product,
                    ]);
            }else{
                return response()->json([
                    "status"    =>  "error",
                    "msg"       =>  "Product not available",
                ]);
            }
        }
    }
    //subcategpry product listing section ended

    //add address function start
    public function add_address(Request $request)
    {
        $user_id    =   $request->user_id;
        $house_no   =   $request->house_no;
        $street_name  =   $request->street_name;
        $landmark   =   $request->landmark;
        $city       =   $request->city;
        $pincode    =   $request->pincode;
        $district   =   $request->district;
        $state      =   $request->state;

        $rules = array(
            "user_id"       =>  "required | numeric",
            "house_no"      =>  "required",
            "street_name"     =>  "required",
            "landmark"      =>  "required",
            "city"          =>  "required",
            "pincode"       =>  "required | digits:6",
            "district"      =>  "required",
            "state"         =>  "required"
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            DB::beginTransaction();
            try{
                ShippingAddress::create([
                    "user_id"   =>  $user_id,
                    "house_no"  =>  $house_no,
                    "street_no" =>  $street_name,
                    "landmark"  =>  $landmark,
                    "address"   =>  $city,
                    "pincode"   =>  $pincode,
                    "city"      =>  $district,
                    "state"     =>  $state,

                ]);
                DB::commit();
                return response()->json([
                    "status"    =>  "success",
                    "msg"       =>  "Address addedd successfully",
                    "address_id"=>  ShippingAddress::max("id"),
                ]);
            }catch(\Exception $e){
                //dd($e);
                DB::rollback();
                return response()->json([
                    "status"    => "error",
                    "msg"       =>  "Cannot add the address",
                ]);
            }
        }
    }
    //add address function end

    //address taking function started
    public function get_address(Request $request)
    {
        $user_id = $request->user_id;

        $rules = array(
            "user_id"       =>  "required | numeric",
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            $data = ShippingAddress::where("user_id", $user_id)->orderBy("id", "desc")->get();

            $addresses=[];
            foreach($data as $value){
                array_push($addresses, [
                    "address_id"    =>  $value->id,
                    "house_no"      =>  $value->house_no,
                    "street_name"   =>  $value->address,
                    "state"         =>  StateManagements::where("id", $value->state)->value("state"),
                    "district"      =>  CityManagement::where("id", $value->city)->value("city_name"),
                    "city"          =>  $value->address,
                    "pincode"       =>  $value->pincode,
                    "landmark"      =>  $value->landmark
                ]);
            }

            return response()->json([
                "status"    =>  "success",
                "addresses" =>  $addresses
            ]);
        }
    }
    //address taking function ended

    //update address section started
    public function update_address(Request $request)
    {
        $address_id     =   $request->address_id;
        $house_no       =   $request->house_no;
        $street_name    =   $request->street_name;
        $landmark       =   $request->landmark;
        $city           =   $request->city;
        $pincode        =   $request->pincode;
        $district       =   $request->district;
        $state          =   $request->state;

        $rules = array(
            "address_id"        =>  "required | numeric",
            "house_no"          =>  "required",
            "street_name"       =>  "required",
            "landmark"          =>  "required",
            "city"              =>  "required",
            "pincode"           =>  "required | digits:6",
            "district"          =>  "required",
            "state"             =>  "required"
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            DB::beginTransaction();
            try{
                ShippingAddress::where("id", $address_id)->update([
                    "house_no"  =>  $house_no,
                    "street_no" =>  $street_name,
                    "landmark"  =>  $landmark,
                    "address"   =>  $city,
                    "pincode"   =>  $pincode,
                    "city"      =>  $district,
                    "state"     =>  $state,

                ]);
                DB::commit();
                return response()->json([
                    "status"    =>  "success",
                    "msg"       =>  "Address updated successfully",
                ]);
            }catch(\Exception $e){
                //dd($e);
                DB::rollback();
                return response()->json([
                    "status"    => "error",
                    "msg"       =>  "Cannot add the address",
                ]);
            }
        }

    }
    //update address section ended

    //product search function start
    public function search_product(Request $request)
    {
        $value=$request->value;
        if(isset($value)){
            $all_products = Products::Where('is_block',1)
                            ->Where('product_title', 'LIKE', '%' . $value. '%')
                            ->OrderBy('id', 'desc')->take(10)->get();
            if(count($all_products)>0){
                $products=[];
                foreach($all_products as $value){
                    array_push($products, [
                        "product_id"    =>  $value->id,
                        "name"          =>  $value->product_title
                    ]);
                }

                return response()->json([
                    "status"    =>  "success",
                    "products"  =>  $products
                ]);
            }else{
                return response()->json([
                    "status"    =>  "error",
                    "products"  =>  "Products Not Found"
                ]);
            }
        }else{
            return response()->json([
                "status"    =>  "error",
                "products"  =>  "Products Not Found"
            ]);
        }
    }
    //product search function end

    //selecting all brands for filering
    public function all_brand()
    {
        $brands = Brands::all();
        $brand=[];
        foreach($brands as $value){
            array_push($brand, [
                "brand_id"  =>  $value->id,
                "brand_name"=>  $value->brand_name
            ]);
        }

        return response()->json([
            "status"    =>  "success",
            "brands"    =>  $brand
        ]);
    }

    //brand search function start
    public function brand_search(Request $request){
        $brand_id = $request->brand_id;
       
        $rules = array(
            "brand_id"        =>  "required | numeric",
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            $products = Products::Where('is_block',1)
            ->Where('brand', $brand_id)
            ->OrderBy('id', 'desc')->get();

            if(count($products)>0){
                $product=[];
                foreach($products as $value){
                    array_push($product, [
                        "product_id"    =>  $value->id,
                        "product_code"  =>  $value->product_code,
                        "product_name"  =>  $value->product_title,
                        "image"         =>  asset("images/featured_products/".$value->featured_product_img),
                        "total_qty"     =>  $value->onhand_qty,
                        "unit"          =>  MeasurementUnits::where('id', $value->measurement_unit)->value("unit_name"),
                        "price"         =>  number_format($value->discounted_price, 2)   
                    ]);
                }

                return response()->json([
                    "status"    =>  "success",
                    "products"  =>  $product
                ]);
            }else{
                return response()->json([
                    "status"    =>  "error",
                    "products"  =>  "No product found."
                ]);
            }
            
        }
    }
    //brand search section end

    //add to cart function started
    public function add_cart(Request $request)
    {
        $user_id        =   $request->user_id;
        $product_id     =   $request->product_id;
        $quantity       =   $request->quantity;
        $flag           =   $request->flag;
        $rules = array(
            "user_id"           =>  "required | numeric",
            "product_id"        =>  "required | numeric",
            "quantity"          =>  "required | numeric",
            "flag"              =>  "required | numeric"
        );
                
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            $products   =   Products::where("id", $product_id)->first();
            $p_attrs = ProductsAttributes::Where('product_id', $products->id)->first();
            $att_name='';
            $att_value='';
            if($p_attrs){
                $att_name=$p_attrs->attribute_name;
                $att_value=$p_attrs->attribute_values;
            }

            $cart_data=Carts::where('user_id', $user_id)
                ->where("product_id", $product_id)->get();
            //dd(count($cart_data));
            
            if(count($cart_data)>0){
                if($products->onhand_qty==0 || $quantity>$products->onhand_qty){
                    return response()->json([
                        "status"    =>  "error",
                        "msg"       =>  "Sorry products available only ".$products->onhand_qty
                    ]);
                }else{
                    if($flag==1){
                        DB::beginTransaction();
                        try{
                            Carts::where('user_id', $user_id)
                            ->where('product_id', $product_id)
                            ->update([
                                "qty"               =>  $quantity+$cart_data[0]->qty,
                                "total_price"       =>  $products->discounted_price*( $quantity+$cart_data[0]->qty)
                            ]);
                            DB::commit();
                            return response()->json([
                                "status"    =>  "success",
                                "msg"       =>  "Quantity Updated",
                            ]);
                        }catch(\Exception $e){
                            //dd($e);
                            DB::rollback();
                            return response()->json([
                                "status"    => "error",
                                "msg"       =>  "Cannot update the quantity",
                            ]);
                        }
                    }else if($flag==0){
                        $minus=($cart_data[0]->qty)-$quantity;
                        if($minus>0){
                            DB::beginTransaction();
                            try{
                                Carts::where('user_id', $user_id)
                                ->where('product_id', $product_id)
                                ->update([
                                    "qty"               => $minus ,
                                    "total_price"       =>  $products->discounted_price*( $minus)
                                ]);
                                DB::commit();
                                return response()->json([
                                    "status"    =>  "success",
                                    "msg"       =>  "Quantity Updated",
                                ]);
                            }catch(\Exception $e){
                                //dd($e);
                                DB::rollback();
                                return response()->json([
                                    "status"    => "error",
                                    "msg"       =>  "Cannot update the quantity",
                                ]);
                            }
                        }else if($minus<=0){
                            Carts::where('user_id', $user_id)
                            ->where('product_id', $product_id)
                            ->delete();
                            return response()->json([
                                "status"    => "success",
                                "msg"       =>  "Product deleted",
                            ]);
                        }
                    }
                   
                }
            }else{
                if($products->onhand_qty==0 || $quantity>$products->onhand_qty){
                    return response()->json([
                        "status"    =>  "error",
                        "msg"       =>  "Sorry products available only ".$products->onhand_qty
                    ]);
                }else{

                    DB::beginTransaction();
                    try{
                        Carts::create([
                                "product_id"        =>  $product_id,
                                "user_id"           =>  $user_id,
                                "name"              =>  $products->product_title,
                                "qty"               =>  $quantity,
                                "original_price"    =>  $products->original_price,
                                "product_cost"      =>  $products->product_cost,
                                "price"             =>  $products->discounted_price,
                                "tax"               =>  $products->tax,
                                "tax_amount"        =>  $products->tax_amount,
                                "total_price"       =>  round($quantity*$products->discounted_price, 2),
                                "service_charge"    =>  $products->service_charge,
                                "shiping_charge"   =>  $products->shipping_charge,
                                "tax_type"          =>  $products->tax_type,
                                "image"             =>  $products->featured_product_img,
                                "cart_key"          =>  time().uniqid(),
                                "cart_del"          =>  time(),
                                'att_name'          => $att_name,
                                'att_value'         => $att_value,
        
                        ]);
                        DB::commit();
                        return response()->json([
                                "status"    =>  "success",
                                "msg"       =>  "Product added to the cart",
                        ]);
                    }catch(\Exception $e){
                            //dd($e);
                            DB::rollback();
                            return response()->json([
                                "status"    => "error",
                                "msg"       =>  "Cannot add products to the cart",
                            ]);
                        }
                    }
                }
            } 
    }

    //add to cart section ended

    //slider image function started
    public function slider_img()
    {
        $images=BannerImageSettings::all();

        $image=[];
        foreach($images as $value){
            array_push($image, [
                'image'=> asset('images/banner_image/'.$value->banner_image)
            ]);
        }

        return response()->json([
            "status"    =>  "success",
            "image"     =>  $image
        ]);
    }
    //slider image function end

    //time wigdet function started
    public function time_widget()
    {
        $data=Widget::first();
        return response()->json([
            "status"    =>  "success",
            "widget"    =>  $data->first_content,
        ]);
    }
    //time widget function ended

    //cart items function start
    public function get_cart(Request $request)
    {
        $user_id = $request->user_id;

        $rules = array(
            "user_id"           =>  "required | numeric",
        );
                
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            $products=Carts::where("user_id", $user_id)->get();
            if(count($products)>0){
                $product=[];
                foreach($products as $value){
                    array_push($product, [
                        "product_id"    =>  $value->product_id,
                        "product_name"  =>  $value->name,
                        "image"         =>  asset("images/featured_products/".$value->image),
                        "qty"           =>  $value->qty,
                        "total_qty"     =>  Products::where('id', $value->product_id)->value("onhand_qty"),
                        "unit"          =>  MeasurementUnits::where('id', Products::where("id", $value->product_id)->value('measurement_unit'))->value("unit_name"),
                        "price"         =>  number_format($value->total_price, 2)   
                    ]);
                }

                return response()->json([
                    "status"    =>  "success",
                    "products"  =>  $product,
                    "total_price"   =>  number_format(Carts::where("user_id", $user_id)->sum("total_price"), 2),
                ]);
            }else{
                return response()->json([
                    "status"    =>  "error",
                    "msg"  =>  "No product available in the cart",
                ]);
            }
        }
    }

    //cart items function end

    //checkout section start
    public function checkout(Request $request)
    {
        $user_id= $request->user_id;

        $rules = array(
            "user_id"           =>  "required | numeric",
        );
                
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        }else{
            $products=Carts::where("user_id", $user_id)->get();
            $addresses= ShippingAddress::where("user_id", $user_id)->get();

            if(count($products)>0){
                $product=[];
                foreach($products as $value){
                    array_push($product, [
                        "product_id"    =>  $value->product_id,
                        "product_name"  =>  $value->name,
                        "image"         =>  asset("images/featured_products/".$value->image),
                        "qty"           =>  $value->qty,
                        "total_qty"     =>  Products::where('id', $value->product_id)->value("onhand_qty"),
                        "unit"          =>  MeasurementUnits::where('id', Products::where("id", $value->product_id)->value('measurement_unit'))->value("unit_name"),
                        "price"         =>  number_format($value->total_price, 2)   
                    ]);
                }

               $address = [];
               foreach($addresses as $value){
                   array_push($address, [
                       "address_id" =>  $value->id,
                        "house_no"      =>  $value->house_no,
                        "street_name"   =>  $value->address,
                        "state"         =>  StateManagements::where("id", $value->state)->value("state"),
                        "district"      =>  CityManagement::where("id", $value->city)->value("city_name"),
                        "city"          =>  $value->address,
                        "pincode"       =>  $value->pincode,
                        "landmark"      =>  $value->landmark,
                        "flag"          =>  $value->adress_status
                   ]);
               }

               $d_time=DeliveryTime::where("status", 1)->get();
               $delivery=[];
               foreach($d_time as $d_t){
                   array_push($delivery, 
                       $d_t->time,
                   );
               }

                return response()->json([
                    "status"    =>  "success",
                    "products"  =>  $product,
                    "address"   =>  $address,
                    "delivery_start_time" =>  $delivery
                ]);
            }else{
                $address = [];
               foreach($addresses as $value){
                   array_push($address, [
                       "address_id" =>  $value->id,
                        "house_no"      =>  $value->house_no,
                        "street_name"   =>  $value->address,
                        "state"         =>  StateManagements::where("id", $value->state)->value("state"),
                        "district"      =>  CityManagement::where("id", $value->city)->value("city_name"),
                        "city"          =>  $value->address,
                        "pincode"       =>  $value->pincode,
                        "landmark"      =>  $value->landmark,
                        "flag"          =>  $value->adress_status
                   ]);
               }
                return response()->json([
                    "status"    =>  "success",
                    "msg"  =>  "No product available in the cart",
                    "address"   =>  $address,
                    "delivery_start_time" =>  $delivery
                ]);
            }
        }

    }
    //checkout section end

    //checkout verification start
    public function checkout_verify( Request $request) {
        
        $user_id        =   $request->user_id;
        $address_id     =   $request->address_id;
        $product_id     =   $request->product_id;
        $payment_method =   $request->payment_method;
        $code           =   $request->code;
        $shipping       =   1;
        $qty_id         =   $request->qty_id;
        $time           =   $request->time;
        $user = User::Where('id', $user_id)->Where('is_block', 1)->first();
      
        if($user) {
            //dd("brule");
            $rules = array(
                'user_id'        => 'required | numeric',
                'address_id'         => 'required | numeric',
                'product_id'          => 'required',
                'payment_method'    => 'required',
                "qty_id"            =>  "required",
                'time'              =>  "required"
            );
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    "status"    =>  "error",
                    "msg"       =>  $validator->errors()
                ]);
            } else {
                
                    $order = new Orders();
                    $cart = Carts::Where('user_id', $user_id)->where('is_block', 1)->get();

                    if($order) {
                        $total_items = 0;
                        $total_amount = 0;
                        $net_amount = 0;
                        $total_service = 0;
                        $total_shiping = 0;
                        $tax_amount = 0;
                        if(count($cart) != 0) {
                            $total_items = $cart->sum('qty');

                            $product_serv = DB::table('carts')
                                ->select(DB::raw('sum(carts.service_charge) AS serv_total'))
                                ->join('products', 'products.id', '=', 'carts.product_id')
                                ->where('carts.user_id', $user_id)
                                ->first();
                            if($product_serv->serv_total) {
                                $total_service = $product_serv->serv_total;
                            } else {
                                $total_service = 0;
                            }

                            $product_ships = DB::table('carts')
                                ->select(DB::raw('MAX(carts.shiping_charge) AS ship_total'))
                                ->join('products', 'products.id', '=', 'carts.product_id')
                                ->where('carts.user_id', $user_id)
                                ->where('products.tax_type', 2)
                                ->first();
                            $total_shiping = $product_ships->ship_total;
                            
                            if($product_ships->ship_total) {
                                $total_shiping = $product_ships->ship_total;
                            } else {
                                $total_shiping = 0;
                            }

                            $net_total = DB::table('carts')
                                ->select(DB::raw('sum(total_price) AS total'))
                                // ->select(DB::raw('Round(sum(total_price) ,2) AS total'))
                                ->where('user_id', $user_id)
                                ->first();
                                /*$cols= DB::table('carts')->where('user_id', $user_id)->get();
                                
                                foreach($cols as $vols)
                                {
                                    $proc=Products::find($vols->product_id);
                              if($log_user->user_type==4)
                              {
                                  $unit_price=$proc->discounted_price;
                              }
                              else if($log_user->user_type==5)
                              {
                                  $unit_price=$proc->discounted_price_dealer;
                              }
                              $total_amount+=$total_amount+$unit_price;
                                }*/
                                
                            if($net_total->total) {
                                $total_amount = $net_total->total;
                            } else {
                                $total_amount = 0;
                            }
                            //  dd($total_amount);

                            $tax_total = DB::table('carts')
                                ->select(DB::raw('sum(tax_amount) AS taxs'))
                                // ->select(DB::raw('Round(sum(total_price) ,2) AS total'))
                                ->where('user_id', $user_id)
                                ->first();
                            if(!empty($request->tax_amount)) {
                                $tax_amount = array_sum($request->tax_amount);
                            } else {
                                $tax_amount = 0;
                            }
                            
                            // $total_amount = $net_total->total;

                            $cutoff = TaxCutoff::Where('is_block', 1)->get();
                            $cutoff = $cutoff->sortBy('above_amount');
                            if(sizeof($cutoff) != 0) {
                                foreach ($cutoff as $ckey => $cvalue) {
                                    if($cvalue->above_amount < $total_amount) {
                                        $total_shiping = $cvalue->shiping_amount;
                                    }                    
                                } 
                            }

                            if(count($cart) == 1) {
                                if($cart[0]->shiping_charge == 0) {
                                    $total_shiping = 0.00;
                                }
                            }
                            
                            $total_shiping=$request->ship_total?$request->ship_total:0.00;
                            $cod = Cod::Where('is_block', 1)->get();
                            $cod = $cod->sortBy('above_amount');
                            $cod_amount = 0;
                            //salus
                            /*if($data['payment_method'] == 1) {
                                if(sizeof($cod) != 0) {
                                    foreach ($cod as $keyz => $valuez) {
                                        if($valuez->above_amount < $total_amount) {
                                            $cod_amount = $valuez->cod_amount;
                                        }                    
                                    }
                                }
                            }*/

                            // $net_amount = $total_amount + $tax_amount + $total_shiping + $cod_amount;
                            $net_amount = $total_amount + $total_shiping + $cod_amount;
                           
                            $total_amount = round($total_amount, 2);
                            $tax_amount = round($tax_amount, 2);
                            $net_amount = round($net_amount, 2);
                            $total_service = round($total_service, 2);
                            $total_shiping = round($total_shiping, 2);
                            $cod_amount = round($cod_amount, 2);
                        }
                         //dd($net_amount);
                        if($net_amount != 0) {
                            $max = Orders::max('order_code');
                            $max_id = "00001";
                            $max_st = "Order";
                            if($max) {
                                $max_no = substr($max, 5);
                                $increment = (int)$max_no + 1;
                                $code = $max_st.sprintf("%05d", $increment);
                            } else {
                                $code = $max_st.$max_id;
                            }

                            $order->order_code = $code;
                            $order->order_date = date('Y-m-d');
                            $order->user_id = $user_id;
                            $order->payment_mode = $payment_method;
                            $order->contact_person = $user->first_name.' '.$user->last_name;
                            $order->contact_email = $user->email;
                            $order->contact_no = $user->phone;
                            if(isset($shipping)){
                                $order->shipping_address_flag = $shipping;
                            } else {
                                $order->shipping_address_flag = 0;
                            }

                            $deli_pincode = "";
                            $deli_city = "";

                            $ship=ShippingAddress::where("id", $address_id)->first();
                            
                            if(isset($shipping) && $shipping == 1 ) {
                                if($ship) {
                                    $order->shipping_address =  $ship->house_no.','.$ship->street_no.','.$ship->address.','.$ship->City->city_name.','.$ship->pincode.','.$ship->State->state;
                                    $deli_pincode = $ship->pincode;
                                    $deli_city = $ship->City->city_name;
                                } else {
                                    $order->shipping_address =  $ship->house_no.','.$ship->street_no.','.$ship->address.','.$ship->City->city_name.','.$ship->pincode.','.$ship->State->state;
                                    $deli_pincode = $user->pincode;
                                    $deli_city = $user->City->city_name;
                                }
                            } else {
                                $order->shipping_address = $user->address1.','.$user->address2.','.$user->City->city_name.','.$user->pincode.','.$user->State->state.','.$user->Country->country_name; 
                                $deli_pincode = $user->pincode;
                                $deli_city = $user->City->city_name;

                            }

                            $order->city = $deli_city;
                            $order->pincode = $deli_pincode;
                            $order->total_items = $total_items;
                            $order->total_amount = $total_amount;
                            $order->tax_amount = $tax_amount;
                            $order->service_charge = $total_service;
                            $order->shipping_charge = $total_shiping;
                            $order->cod_charge = $cod_amount;
                            $order->net_amount = $net_amount;
                            $order->order_status = 1;
                            $order->payment_status = 0;
                            $order->remarks = NULL;
                            $order->is_block = 1;
                            $order->delivery_time=$time;
                            // $order->discount_flag = NULL;
                            // $order->discount = NULL;
                            // $order->delivery_date = NULL;
                            // $order->delivery_status = NULL;
                            //dd($order);
                            if($order->save()) {

                                if (isset($product_id)){ 
                                    $id1=explode(",", $product_id);
                                    $qty_id=explode(",", $qty_id);
                                    //dd($id1[0]);
                                    $det='';
                                    for($i=0; $i<count($id1); $i++ ) {
                                        Carts::where("user_id", $user_id)->where('product_id', $id1[$i])->delete();
                                         $stock = Products::Where('id', $id1[$i])->first();

                                        if($stock && ($stock->onhand_qty != 0)) {
                                            $stock_trans = new StockTransactions();
                                            $stock_trans->order_code   = $code;
                                            $stock_trans->product_id   = $id1[$i];
                                            $stock_trans->previous_qty = $stock->onhand_qty;
                                            $stock_trans->current_qty  = $stock->onhand_qty - $qty_id[$i];
                                            $stock_trans->date         = date('Y-m-d');
                                            $stock_trans->remarks      = $stock->product_title.' is ordered.';

                                            $stock->onhand_qty = $stock->onhand_qty - $qty_id[$i];

                                            if($stock->save() && $stock_trans->save()) {
                                                $sck = 1;
                                            }
                                        }
                                        $order_details = new OrderDetails();
                                        $order_details->order_id = $order->id;
                                        $order_details->product_id = $id1[$i];
                                        
                                        $product_data=Products::where("id", $id1[$i])->get();
                                        
                                        foreach ($product_data as $pro){
                                            $det.= '<tr>
                                            <td style="font-size: 11px;font-weight: 600;color:black;border:1px solid black;text-align:center;"> '.$pro->product_title.'</td>
                                            <td style="font-size: 11px;font-weight: 600;color:black;border:1px solid black;text-align:center;"> '.$qty_id[$i].'</td>
                                            <td style="font-size: 11px;font-weight: 600;color:black;border:1px solid black;text-align:center;">Rs.  '.$pro->discounted_price.'</td>
                                            <td style="font-size: 11px;font-weight: 600;color: black;border: 1px solid black;text-align: right;">Rs.  '.$qty_id[$i]*$pro->discounted_price.'</td>
                                        </tr>';
                                            $order_details->product_title = $pro->product_title;
                                            $order_details->tax = $pro->tax;
                                            $order_details->tax_type = $pro->tax_type;
                                            $order_details->unitprice = $pro->product_cost;
                                            $order_details->tax_amount = $pro->tax_amount;
                                            $order_details->order_qty = $qty_id[$i];
                                            $order_details->totalprice = $qty_id[$i]*$pro->discounted_price;
                                           // Products::where("id", $id1[$i])->update([
                                              //  "onhand_qty" => $pro->onhand_qty-$qty_id[$i]
                                           // ]);

                                           $order_details->is_block = 1;
                                           //dd($order_details);
                                                                                   if($order_details->save()) {
                                                                                       $sus2 = 1;
                                                                                   } 
                                        }
                                      
                                       

                                    }                            
                                }

                                if($payment_method == 1) {
                                    //dd(2);
                                    $order_trans = new OrdersTransactions();
                                    $t_max = OrdersTransactions::max('trans_code');
                                    $t_max_id = "00001";
                                    $t_max_st = "Trans";
                                    if($t_max) {
                                        $t_max_no = substr($t_max, 5);
                                        $t_increment = (int)$t_max_no + 1;
                                        $transaction_code = $t_max_st.sprintf("%05d", $t_increment);
                                    } else {
                                        $transaction_code = $t_max_st.$t_max_id;
                                    }

                                    $order_trans->trans_code =  $transaction_code;
                                    $order_trans->trans_date = date('Y-m-d H:i:s');
                                    $order_trans->order_id = $order->id;
                                    $order_trans->net_amount = $net_amount;
                                    $order_trans->amountpaid = "Unpaid";
                                    $order_trans->paymentmode = $payment_method;
                                    $order_trans->gatewaytransactionid = NULL;
                                    $order_trans->trans_status = "PENDING";
                                    $order_trans->remarks = NULL;
                                    $order_trans->is_block = 1;

                                    if($order_trans->save()) {
                                        $sus3 = 1;
                                    }

                                    
                                    $customer_name = $user->first_name.' '.$user->last_name;
                                    $email = $user->email;
                                    $contact= $user->phone;
                                    $address=ShippingAddress::where("id", $address_id)->first();
                                    $order_code=$code;
                                    $order_date=date('Y-m-d');

                                    $adm = EmailSettings::where('id', 1)->first();
                                    $admin_email = "info@grocery.in";
                                    if($adm) {
                                        $admin_email = $adm->contact_email;
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
                                    $site_name ="Grocery360";
                                    if($general){
                                        $site_name = $general->site_name;
                                    } else {
                                        $site_name = "Grocery360";
                                    }


                                    if($email!='null'){

                                       

                                        $headers="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                        $headers.= "MIME-Version: 1.0\r\n";
                                        // $headers.= "From: $admin_email" . "\r\n";
                                        $headers.= "From: order@grocery.in" . "\r\n";
                                        $to = $email;
                                        $to2 = $admin_email;
                                        $subject = "Orders Details";
                                        $txt = '<div class="gj_mail" style="width: 600px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat;  background-size: 100% 102%;">
                                            <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                            <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                                <h2 style="color: #ff5c00;margin-top: 0px;">Orders Details</h2>
                                                <table align="center" style=" text-align: center;">
                                                    <tr>
                                                        <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Customer Name</th>
                                                        <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$customer_name.'</td>
                                                    </tr>

                                                    <tr>
                                                        <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Contact No</th>
                                                        <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$contact.'</td>
                                                    </tr>

                                                <!-- <tr>
                                                        <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Address</th>
                                                        <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$address.'</td>
                                                    </tr>-->

                                                    <tr>
                                                        <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Order Code</th>
                                                        <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$order_code.'</td>
                                                    </tr>

                                                    <tr>
                                                        <th style="text-align:center;text-transform:uppercase;padding-bottom:12px;color:#333;width:120px;font-size: 12px;font-weight: 900;">Order Date</th>
                                                        <td style="font-size: 12px;font-weight:bold;color:#333;padding-bottom:12px;"> : '.$order_date.'</td>
                                                    </tr>
                                                </table>

                                                <table style="width: 100%;border: 1px solid black;">
                                                    <tr>
                                                        <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Product Title</th>
                                                        <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Quantity</th>
                                                        <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Price</th>
                                                        <th style="width: 100px;text-align: right;text-transform: uppercase;padding-bottom: 5px;color: black;border: 1px solid black;font-size: 13px;font-weight: 700;">Total(Inc Tax)</th>
                                                    </tr>'.$det.'
                                                    <tr>
                                                        <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Sub Total</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->total_amount.'</td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Shipping Charge</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->shipping_charge.'</td>
                                                    </tr>
                                                
                                                    <tr>
                                                        <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Net Total</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$net_amount.'</td>
                                                    </tr>
                                                </table>

                                                <p></p>
                                                <p>Thank You.</p>
                                                <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                                <p>Thanks & Regards,</p>
                                                <p><a href="'.route('home').'">'.$site_name.'</a></p>
                                            </div>
                                        </div>';
                                    //notification salus
                                    /*<tr>
                                                        <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">COD Charge</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->cod_charge.'</td>
                                                    </tr>*/
                                    
                                    //email to admin
                                    $adm1 = EmailSettings::where('id', 1)->first();
                                    $admin_email1 = "info@grocery360.in";
                                    if($adm) {
                                        $admin_email1 = $adm1->contact_email;
                                    }

                                    $logos1 = \DB::table('logo_settings')->first();
                                    $logo_path = 'images/logo';
                                    $logo1 = "";
                                    if($logos1) {
                                        $logo1 = asset($logo_path.'/'.$logos1->logo_image);
                                    } else {
                                        $logo1 = asset('images/logo.png');
                                    }

                                    $general1 = \DB::table('general_settings')->first();
                                    $site_name1 = "Grocery360";
                                    if($general1){
                                        $site_name1 = $general1->site_name;
                                    } else {
                                        $site_name1 = "Grocery360";
                                    } 

                                    $headers1="Content-Type: text/html; charset=ISO-8859-1\r\n";
                                    $headers1.= "MIME-Version: 1.0\r\n";
                                    // $headers.= "From: $admin_email" . "\r\n";
                                    $headers1.= "From: order@grocery.in" . "\r\n";
                                    $to1 = $admin_email1;
                                    $subject1 = "New Order Received";

                                    $txt1 = '<div class="gj_mail" style="width: 500px; padding: 20px 30px; margin: 0 auto; position: relative; background-image: url('.asset('images/shadow.png').'); background-repeat: no-repeat; height: 100%;  background-size: 100% 102%;">
                                            <div style="margin: 10px 20px; padding: 20px;  border-bottom: 1px solid #ff5c00;"><img src="'.$logo1.'" style="width: 300px; margin: 0 auto;display: block;"></div>
                                            <div style="padding: 5px; color: #333; margin: 0px 20px; text-align: center; font-size: 18px;">
                                                <p>'.$customer_name.' placed an order '.$code.'</p>
                                                <table style="width: 100%;border: 1px solid black;">
                                                    <tr>
                                                        <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Product Title</th>
                                                        <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Quantity</th>
                                                        <th style="width:100px;text-align:center;text-transform:uppercase;padding-bottom: 5px;color:black;border:1px solid black;font-size: 13px;font-weight: 700;">Price</th>
                                                        <th style="width: 100px;text-align: right;text-transform: uppercase;padding-bottom: 5px;color: black;border: 1px solid black;font-size: 13px;font-weight: 700;">Total(Inc Tax)</th>
                                                    </tr>'.$det.'
                                                    <tr>
                                                        <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Sub Total</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->total_amount.'</td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Shipping Charge</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$order->shipping_charge.'</td>
                                                    </tr>
                                                
                                                    <tr>
                                                        <th colspan="3" style="width:100px;text-align:right;text-transform:uppercase;padding-bottom:5px;color:black;border:1px solid black;padding-right:10px;font-size: 16px;">Net Total</th>
                                                        <td style="font-size: 14px;font-weight: bold;color: black;border: 1px solid black;text-align: right;">Rs. '.$net_amount.'</td>
                                                    </tr>
                                                </table>

                                                <p></p>
                                    <p><a href="'.URL::to('/view_orders/'.$order->id).'">Click here to view</a></p>
                                    <p></p>
                                    <div style="padding:10px 0px 0px; border-bottom: 1px solid #ff5c00;"> </div>
                                    <p>Thanks & Regards,</p>
                                    <p><a href="'.route('home').'">'.$site_name1.'</a></p>
                                </div>
                            </div>';
                    
                            if(mail($to1,$subject1,$txt1,$headers1)){
                            }
                                        
                                
                                    //email to admin end
                                        // if(1==1){
                                        if(mail($to,$subject,$txt,$headers)){
                                            mail($to2,$subject,$txt,$headers);
                                        }
                        }
                                    return response()->json([
                                        "status"    =>  "success",
                                        "msg"       =>  "Order placed successfully"
                                    ]);
                                }
                               
                               

                            } else {
                               return response()->json([
                                   "status" =>  "error",
                                   "msg"    =>  "Order place error.",
                                  
                               ]);
                            }
                        } else {
                            return response()->json([
                                "status" =>  "error",
                                "msg"    =>  "Cannot place the order."
                            ]); 
                        }
                    }
            }
        } else {
            return response()->json([
                "status" =>  "error",
                "msg"    =>  "User not found."
            ]); 
        }
        
    }
    //checkout verification end
    
    //my order function start
    public function my_order(Request $request)
    {
        $user_id =$request->user_id;
        $rules = array(
            'user_id'        => 'required | numeric',
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        } else {
            $orders=Orders::where('user_id', $user_id)->orderby("id", "desc")->get();

            if($orders){
                $data=[];
                $unit=[];
               foreach ($orders as $key => $value){
                   $details=OrderDetails::where("order_id", $value->id)->orderby("id", "desc")->get();
                  foreach($details as $detail){
                       $details->{'product_name'}=$detail->product_title;
                       array_push($unit, MeasurementUnits::where('id', Products::where("id", $detail->id)->value('measurement_unit'))->value("unit_name"));
                   }
                   $value->{'details'}=$details;
                   array_push($data, [
                       "id" =>  $value->id,
                       "status" => $value->order_status,
                       "amount" =>  round($value->total_amount, 2),
                       "code"   =>  $value->order_code,
                        "qty"   =>  count(OrderDetails::where("order_id", $value->id)->get()),
                        "payment_status"    =>  $value->payment_status,
                        "order_date"    =>  $value->order_date
                   ]);
                   
               }
                   
                
                return response()->json([
                    "status"    =>  "success",
                    "orders"    =>  $data
                ]);
            }else{
                return response()->json([
                    "status"    =>  "error",
                    "msg"       =>  "No active orders"
                ]);
            }
            
        }
    }
    //my order function end

    //cancel order start
    public function cancel_order(Request $request)
    {
        $order_id= $request->order_id;
        $status_id = $request->status_id;
        $rules = array(
            'order_id'        => 'required | numeric',
            'status_id'        => 'required | numeric',
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        } else {
            DB::beginTransaction();
            try{
                Orders::where("id", $order_id)->update([
                        "order_status"        =>  $status_id,

                ]);
                DB::commit();
                return response()->json([
                        "status"    =>  "success",
                        "msg"       =>  "Order cancelled",
                ]);
            }catch(\Exception $e){
                    dd($e);
                    DB::rollback();
                    return response()->json([
                        "status"    => "error",
                        "msg"       =>  "Cannot cancel the product",
                    ]);
                }
        }
    }
    //cancel order end

    public function contact()
    {
        $about=AboutUsCMSSettings::where("is_block", 1)->first();

        $description=strip_tags(html_entity_decode($about->page_data));

        $contact=EmailSettings::first();
        return response()->json([
            "status"    =>  "success",
            "heading"   =>  $about->heading,
            "details"    =>  str_replace(["\t\t", "\t"], '', str_replace(PHP_EOL, ' ', $description)),
            "email" =>  $contact->contact_email,
            "phone_1" =>  $contact->contact_phone1,
            "phone_2" =>  $contact->contact_phone2,
        ]);
    }

    public function order_details(Request $request)
    {
        $order_id= $request->order_id;
        $rules = array(
            'order_id'        => 'required | numeric',
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        } else {
           $data=OrderDetails::where("order_id", $order_id)->get();
           $order=Orders::where("id", $order_id)->first();
           $pay_method=OrdersTransactions::where("order_id", $order_id)->value('pay_method');
           $orders=[];
           foreach($data as $value){
               array_push($orders, [
                   "name"   => $value->product_title,
                   "quantity"   =>  $value->order_qty,
                   "total_price"    =>  $value->totalprice,
                   "unit"       =>  MeasurementUnits::where("id", Products::where("id", $value->product_id)->value('measurement_unit'))->value('unit_name'),

               ]);
           }

           return response()->json([
               "status" =>  "success",
               "code"   =>  $order->order_code,
               "payment_status" => $order->payment_status,
               "address"    =>  $order->shipping_address,
               "time"       => $order->delivery_time,
               "date"       =>  $order->order_date,
               "products"   =>  $orders,
               "pay_method" =>  $pay_method,
               "order_status" => $order->order_status,
               "total_amount"=>OrdersTransactions::where("order_id", $order_id)->value('net_amount'),
           ]);
        }
    }

    //invoice function start
    public function invoice(Request $request){
        $order_id= $request->order_id;
        $user_id= $request->user_id;
        $rules = array(
            'order_id'        => 'required | numeric',
            "user_id"       =>  'required | numeric'
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        } else {
           $data=OrderDetails::where("order_id", $order_id)->get();
           $order=Orders::where("id", $order_id)->first();
           $pay_method=OrdersTransactions::where("order_id", $order_id)->value('pay_method');

           $user=User::where("id", $user_id)->first();

           $orders=[];
           foreach($data as $value){
               array_push($orders, [
                "product_code"   => $value->product_id,
                   "product_name"   => $value->product_title,
                   "quantity"   =>  $value->order_qty,
                   "total_price"    =>  $value->totalprice,
                   "unit"       =>  MeasurementUnits::where("id", Products::where("id", $value->product_id)->value('measurement_unit'))->value('unit_name'),
              ]);
           }

           return response()->json([
               "status" =>  "success",
               "invoice_no" =>  "INV - ".rand(1000, 10000),
               "invoice_date"   =>  date("d-m-Y"),
               "ref_no"   =>  $order->order_code,
               "ref_date"   =>  $order->order_date,
               "customer_name"  =>  $user->first_name." ".$user->last_name,
               "contact_no"     =>  $user->phone,
               "email"          =>  $user->email,
               "products"   =>  $orders,
               "payment_status" => $order->payment_status,
               "pay_method" =>  $pay_method,
               "total_amount"=>OrdersTransactions::where("order_id", $order_id)->value('net_amount'),
           ]);
        }
    }
    //invoice function end

    //push notification function started
    public function push_notification()
    {
       $device_id='eNGH4ud9R7mh2_6WJdI4jx:APA91bG6-jkz8LX1sL8JAqfzqj1GpHDBtyY0c2por3cQ8wQW2xKRZyzq66XkV5qjfJU3cR9oHV1Hu7dT6hLmjwdlRxXP-HXeLcqjiMtKwa3l00oQCexCSOHIkF9flt3XFGrzrH_Nf-Bf';
        $message="for check";
        $data=['hy', 'hello'];
        $action='.OrderDetailScreen';
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token=$device_id;
        $serverKey='AAAAbJxc7xQ:APA91bFtRlaXlfY3HaXNJgM6DvLHXw1YNBx2m9AAjggKjqSPW3B4kOQtzWi7OepYtQYS6JEs8NzzwyGxB0eYEk9q9xsyRrpWI6EBNIpB-eJWkcsFxW5Lp3mMeo5kO2VF4g024sz1SLvU';
        $notification = [
            'title' => $message,
            'sound' => true,
            'body'=>$data,
            'click_action'=>$action,
            'data2'=>$data
        ];
        
        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

       $headers = [
        'Authorization: key='.$serverKey,
        'Content-Type: application/json'
    ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
   

        return true;
    }
    //push notification function ended

    //pincode function started
    public function pincode(){
        $pincodes=Pincode::all();

        $data=[];
        foreach ($pincodes as $pincode){
            array_push($data, [
                "pincode_id"    =>  $pincode->id,
                "pincode"       =>  $pincode->pincode,
                "city"          =>  $pincode->divisionname
            ]);
        }

        return response()->json([
            "status"    =>  "success",
            "pincodes"  =>  $data
        ]);
    }
    //pincode function ended

    //banner image function started
    public function banner()
    {
        $banners=HomeWidget::all();

        $banner=[];
        foreach($banners as $value){
            array_push($banner, [
                "image" => asset('images/site_img/'.$value->image),
                "url"   => $value->url
            ]);
        }

        return response()->json([
            "status"    =>  'success',
            "banner"    =>  $banner
        ]);
    }
    //banner image function ended

    //notification count function start
    public function count_notification(Request $request)
    {
        $user_id= $request->user_id;
        $rules = array(
            "user_id"       =>  'required | numeric'
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        } else {
            $notification = Notification::where("customer_id", $user_id)->where('read_flag', 0)->orderby('id', "desc")->get();

            $data=[];
            foreach($notification as $value){
                array_push($data, [
                "id"    =>  $value->id,
                "order_id"  =>  $value->order_id,
                "msg"   =>  $value->message,
                "read_flag" =>  $value->read_flag,
                "time"=> Carbon::parse($value->created_at)->diffForHumans(),
                ]);
            }

            return response()->json([
                "status"    =>  'success',
                'notification'  =>  $data
            ]);
        }
    }
    //notification count function end

    //update notification read flag
    public function update_notification(Request $request)
    {
        $id= $request->id;
        $read_flag = $request->read_flag;
        $rules = array(
            "id"       =>  'required | numeric',
            "read_flag"     =>  "required "
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "status"    =>  "error",
                "msg"       =>  $validator->errors()
            ]);
        } else {
            DB::beginTransaction();
            try{
                Notification::where("id", $id)->update([
                        "read_flag"        =>  $read_flag,

                ]);
                DB::commit();
                return response()->json([
                        "status"    =>  "success",
                        "msg"       =>  "Read flag updated",
                ]);
            }catch(\Exception $e){
                    dd($e);
                    DB::rollback();
                    return response()->json([
                        "status"    => "error",
                        "msg"       =>  "Cannot update",
                    ]);
            }
        }

    }
    public function get_pdf()
    {
        require_once('TCPDF/tcpdf.php');
        $pdf = new \Tcpdf();
        $pdf->AddPage();
        $pdf_html='<h1>hy this sreeraj attachment</h1>';
      
       // -----------------------------------------------------------------------------
       // ob_end_clean();
       //Close and output PDF document
      
       $pdf->SetFont('times','B',16);
       // $pdf->Cell(40,10,'Hello World!');
        $pdf->writeHTML($pdf_html, true, false, false, false, '');
        $pdf->Output(dirname(__FILE__).'/invoice/invoice'.time().'.pdf', 'F');

       // $pdf->Output('example_001.pdf', 'I'); I -  for open the file int thte browser
       $to          = "sreerajs728@gmail.com"; // addresses to email pdf to
       $from        = "order@grocery360.in"; // address message is sent from
       $subject     = "Your PDF email subject"; // email subject
       $body        = "<p>The PDF is attached.</p>"; // email body
       $pdfLocation = "app/Http/Controllers/invoice/invoice".time().".pdf"; // file location
       $pdfName     = "invoice.pdf"; // pdf file name recipient will get
       $filetype    = "application/pdf"; // type
       
       // create headers and mime boundry
       $eol = PHP_EOL;
       $semi_rand     = md5(time());
       $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
       $headers       = "From: $from$eol" .
         "MIME-Version: 1.0$eol" .
         "Content-Type: multipart/mixed;$eol" .
         " boundary=\"$mime_boundary\"";
       
       // add html message body
         $message = "--$mime_boundary$eol" .
         "Content-Type: text/html; charset=\"iso-8859-1\"$eol" .
         "Content-Transfer-Encoding: 7bit$eol$eol" .
         $body . $eol;
       
       // fetch pdf
       $file = fopen($pdfLocation, 'rb');
       $data = fread($file, filesize($pdfLocation));
       fclose($file);
       $pdf = chunk_split(base64_encode($data));
       
       // attach pdf to email
       $message .= "--$mime_boundary$eol" .
         "Content-Type: $filetype;$eol" .
         " name=\"$pdfName\"$eol" .
         "Content-Disposition: attachment;$eol" .
         " filename=\"$pdfName\"$eol" .
         "Content-Transfer-Encoding: base64$eol$eol" .
         $pdf . $eol .
         "--$mime_boundary--";
       
       // Send the email
       if(mail($to, $subject, $message, $headers)) {
         echo "The email was sent.";
       }
       else {
         echo "There was an error sending the mail.";
       }
       
    }

    public function send_otp(){
        $xml_data = 'user=Whizcrew&key=46b61b4c3aXX&mobile=+919334155746&message=test sms&senderid=DALERT&accusage=1&entityid=DLT Number&tempid=DLT Template ID';

        $URL = "http://sms.bulkssms.com/submitsms.jsp?user=Whizcrew&key=46b61b4c3aXX&mobile=7403291258&message=OTP%20:Variable%20CompanyName&senderid=ALRTSM&accusage=1"; 

			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');			
			curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);

print_r($output); 
    }

    public function send_mail()
    {
        $to          = "sreerajs728@gmail.com"; // addresses to email pdf to
$from        = "order@grocery360.in"; // address message is sent from
$subject     = "Your PDF email subject"; // email subject
$body        = "<p>The PDF is attached.</p>"; // email body
$pdfLocation = "./1708.05117.pdf"; // file location
$pdfName     = "pdf-file.pdf"; // pdf file name recipient will get
$filetype    = "application/pdf"; // type

// create headers and mime boundry
$eol = PHP_EOL;
$semi_rand     = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
$headers       = "From: $from$eol" .
  "MIME-Version: 1.0$eol" .
  "Content-Type: multipart/mixed;$eol" .
  " boundary=\"$mime_boundary\"";

// add html message body
  $message = "--$mime_boundary$eol" .
  "Content-Type: text/html; charset=\"iso-8859-1\"$eol" .
  "Content-Transfer-Encoding: 7bit$eol$eol" .
  $body . $eol;

// fetch pdf
$file = fopen($pdfLocation, 'rb');
$data = fread($file, filesize($pdfLocation));
fclose($file);
$pdf = chunk_split(base64_encode($data));

// attach pdf to email
$message .= "--$mime_boundary$eol" .
  "Content-Type: $filetype;$eol" .
  " name=\"$pdfName\"$eol" .
  "Content-Disposition: attachment;$eol" .
  " filename=\"$pdfName\"$eol" .
  "Content-Transfer-Encoding: base64$eol$eol" .
  $pdf . $eol .
  "--$mime_boundary--";

// Send the email
if(mail($to, $subject, $message, $headers)) {
  echo "The email was sent.";
}
else {
  echo "There was an error sending the mail.";
}
    }
}
