<?php

namespace App\Http\Middleware\Privileges;

use App\Models\AdminPrivilege;
use App\Models\UserPrivilege;
use Closure;
use Illuminate\Http\Request;

class StoreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_id = auth('admin')->user()->id;

        $user_privilege = AdminPrivilege::where('admin_id', $user_id)->where('privilege_id', 6)->count();

        if ($user_privilege > 0) {
            return $next($request);
        } else {
            return redirect('/home');
        }
    }
}
