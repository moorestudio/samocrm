<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Session;
use App\ReferralLink;
class CheckSales
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
        $response = $next($request);

        if ($request->has('ref')){
            $referral = ReferralLink::whereCode($request->get('ref'))->first();
            $referrer_id = $referral->user_id;

            if(Auth::check() && Auth::user()->role_id == 2 && Auth::user()->franchise_id != null){
                if(Auth::user()->franchise_id != $referrer_id){
                    // dd('NOT SAME');
                    $time = 1 * 24 * 60; //1 день  
                    Session::put('check_sales', $referrer_id);
                    // $response->cookie('check_sales', $referrer_id, $time); 
                }
                // else{
                //     dd('SAME');
                // }
            } 
        }
        return $response;
    }
}
