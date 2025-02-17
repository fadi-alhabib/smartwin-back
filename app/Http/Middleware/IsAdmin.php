<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class IsAdmin
{

    public function handle(Request $request, Closure $next)
    {
        Log("from isAdmin");
        $id = Auth::guard('admin')->id();

        $user = Admin::where('id', $id)->first();

        if ($user) {
            // Auth::logout();

            return $next($request);
        }


        return redirect('/logout');
    }
}
