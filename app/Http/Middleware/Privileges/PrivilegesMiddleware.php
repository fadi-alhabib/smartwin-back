<?php

namespace App\Http\Middleware\Privileges;

use App\Models\AdminPrivilege;
use Closure;
use Illuminate\Http\Request;
use App\Models\UserPrivilege;

class PrivilegesMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $user_id = auth('admin')->user()->id;

        $user_privilege = AdminPrivilege::where('admin_id', $user_id)->where('privilege_id', 4)->count();

        if ($user_privilege > 0) {
            return $next($request);
        } else {
            return redirect('/home');
        }
    }
}
