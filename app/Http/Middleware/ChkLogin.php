<?php

namespace App\Http\Middleware;

use Closure;
use User;
use DB;

class ChkLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $value = session()->get('user');
        // print_r($value);die();
        if(isset($_COOKIE["user"]) && !empty($_COOKIE["user"])) {
            if($value) {
                // return $next($request);
                if($value->user_type == 4) {
                    return redirect()->route('home');
                } else {
                    return $next($request);
                }
            } else {
                return redirect()->route('home');
            }
        } else if($value) {
            // return $next($request);
            if($value->user_type == 4) {
                return redirect()->route('home');
            } else {
                return $next($request);
            }
        } else {
            return redirect()->route('home');
        }
    }
}
