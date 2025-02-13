<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class IsAdmin
{

    public function handle(Request $request, Closure $next)
    {

        $id = Auth::id();

        $user = User::where('id', $id)->get();

        if($user[0]->is_admin)
        {
            // Auth::logout();

            return $next($request);
        }
        
        
        return redirect('/logout');


    }
}
