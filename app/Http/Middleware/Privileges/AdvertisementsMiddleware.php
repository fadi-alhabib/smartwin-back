<?php

namespace App\Http\Middleware\Privileges;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserPrivilege;

class AdvertisementsMiddleware
{
    
    
    public function handle(Request $request, Closure $next)
    {
        $user_id = auth()->user()->id;

        $user_privilege = UserPrivilege::where('user_id', $user_id)->where('privilege_id', 5)->count();

        if($user_privilege > 0)
        {   
            return $next($request);
        }
        else
        {
            return redirect('/home');
        }
    }
}
