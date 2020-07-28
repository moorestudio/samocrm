<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Cookie;
class RedirectEmail
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

        if (Auth::check()) {
            if(Cookie::get('orig_event_id') !== null){
                $event_orig_id=Cookie::get('orig_event_id');
                return redirect('buy/'.$event_orig_id)->withCookie(Cookie::forget('orig_event_id'));
            }
            else{
                return redirect()->route('home');
            }
        }
        else{
            return $next($request);
        }

    }
}
