<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class EmailVerificationMiddleware
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
        if (Auth::check() && Auth::user()->confirmed_at == Null && Auth::user()->role_id == 2) {
            // return redirect()->route('admin');
            // временно, потом создать отдельную стр для предуреждения и возможности переотправки ссылки и наверно изменения почты тож

            // $event_orig_id=Cookie::get('orig_event_id');
            // $url_link = url("buy/".$event_orig_id);
            // return Redirect::to($url_link);
            return redirect()->route('email_sent');
            
        }
        return $next($request);  //продолжит
    }
}
