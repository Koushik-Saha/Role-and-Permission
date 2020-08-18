<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\URL;

class CheckInstallation
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
        $role_count = Role::all()->count();
        $admin = Role::whereRoleName('Administrator')
            ->whereRoleSlug('administrator')
            ->first();

        if(($role_count < 6 || $admin === null) && URL::current() !== url('install')) {
            return redirect()->route('login');
        }

        if($role_count >= 6 && $admin != null && URL::current() === url('install')) {
            return redirect()->route('/');
        }

        return $next($request);
    }
}
