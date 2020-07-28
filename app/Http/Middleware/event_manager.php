<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class event_manager
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
        if (Auth::check() && Auth::user()->role_id == 3) {
            // return redirect()->route('admin');
            return $next($request);  //продолжит
        }
        elseif (Auth::check() && Auth::user()->role_id == 4) {
            return redirect()->route('event_index'); // вернет на индекс
        }
        elseif (Auth::check() && Auth::user()->role_id == 5) {
            return redirect()->route('event_index'); // вернет на индекс
        }
        elseif (Auth::check() && Auth::user()->role_id == 8) {
            return redirect()->route('event_index'); // вернет на индекс
        }
        elseif (Auth::check() && Auth::user()->role_id == 6)
        {
            return redirect()->route('event_index');
        }
        else{
            abort(403);
        }

    }
}
