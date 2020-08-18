<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckLabour
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
        if(!Auth::user()->isLabour()) {
            return Helper::redirectBackWithNotification('error', 'Sorry! You Are not Authorised!.');
        }
        return $next($request);
    }
}
