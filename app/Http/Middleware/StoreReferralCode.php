<?php

namespace App\Http\Middleware;

use Closure;
use App\ReferralLink;
use App\partner_referral_link;
use App\Event;
use Carbon\Carbon;
class StoreReferralCode
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
            $orig_url_arr  = explode("/",$request->path());
            $event = Event::find($orig_url_arr[1]);
            $time = 1 * 24 * 60; //1 день       7 дней
            $referral = ReferralLink::whereCode($request->get('ref'))->first();
            $referral->count++;
            $referral->save();
            if(Carbon::parseFromLocale($event->date, 'ru') > Carbon::now()){
                $response->cookie('ref', $referral->id, $time);    
            }
            else {
                return redirect('/')->withCookie('ref', $referral->id, $time);
            }
        }



        elseif ($request->has('part_ref')){
            $orig_url_arr  = explode("/",$request->path());
            $time = 1 * 24 * 60; //1 день       7 дней
            $referral = partner_referral_link::whereCode($request->get('part_ref'))->first();
            // $referral->count++;
            $referral->save();
            $response->cookie('part_ref', $referral->id, $time);

            // if(Carbon::parseFromLocale($event->date, 'ru') > Carbon::now()){
                   
            // }
            // else {
            //     return redirect('/')->withCookie('ref', $referral->id, $time);
            // }
        }









        return $response;
    }
}
