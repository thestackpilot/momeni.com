<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBroadLoomUser
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
        if (Auth::check() && (Auth::user()->broadloom_user == 1 || Auth::user()->is_sale_rep)) {
            return $next($request);
        }
        // Redirect to home route if the user is not authenticated or broad_user is not 1
        return redirect('/');
    }
}
