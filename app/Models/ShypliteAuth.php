<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShypliteAuth extends Model
{
    public $appID = '2269';
    public $SellerID = '11681';
    public $key = '4Rnv41WqZJw=';
    public $secret = 'gMAD32KUArUF0NGANQuJJN3V8mEqdxkMipgh9URN08MLHZ2L8r77vRyoJUE0HhQxc367Yt4gx3uZYmHYtljtVw==';

    public function authenticatShyplite() {
        $timestamp = time();
        $appID = '2269';
        $key = '4Rnv41WqZJw=';
        $secret = 'gMAD32KUArUF0NGANQuJJN3V8mEqdxkMipgh9URN08MLHZ2L8r77vRyoJUE0HhQxc367Yt4gx3uZYmHYtljtVw==';

        $email =  "faizal@intercambiarinternational.com";
        $password =  "allahhelpme";

        $sign = "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
        $authtoken = rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));

        $ch = curl_init();

        $header = array(
            "x-appid: $appID",
            "x-timestamp: $timestamp",
            "Authorization: $authtoken"
        );

        curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/login');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "emailID=$email&password=$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return $server_output;
    }
}