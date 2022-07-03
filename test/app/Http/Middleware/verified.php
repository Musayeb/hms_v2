<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class verified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
         if(empty(Auth::user()->email_verified_at)) {
             return view('auth.verify_email');
         } else{
            return $next($request);
         } 
    }
}
