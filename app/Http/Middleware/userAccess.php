<?php

namespace App\Http\Middleware;

use Closure;

class userAccess
{
  public function handle($request, Closure $next)
  {
    if(auth()->user()->id != $request->route()->parameter('id'))
    {
       if(auth()->user()->role_id == 3 ){
          return $next($request);
        }

        return redirect('/home');
    }


    return $next($request);
  }
}