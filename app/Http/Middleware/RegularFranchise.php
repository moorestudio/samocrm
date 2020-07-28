<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RegularFranchise
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
        if (Auth::check() && Auth::user()->role_id == 4 || Auth::check() && Auth::user()->role_id == 3 || Auth::check() && Auth::user()->role_id == 5 || Auth::check() && Auth::user()->role_id == 6) {
            // return redirect()->route('admin');
            return $next($request);  //продолжит
        }
        else{
            return redirect()->route('event_index');
        }
    }
}
