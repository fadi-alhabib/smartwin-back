<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');
            Log::error($credentials);

            if (auth()->guard('admin')->attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
                Log::alert('authenticated');
                Log::alert(Auth::guard('admin')->user());
                $request->session()->regenerate(destroy: true);
                return redirect()->intended('home');
            } else {
                Log::alert('unauthenticated');
            }

            return back()->withErrors([
                'username' => 'Invalid username or password.',
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        Session::flush();
        return redirect('admin/login');
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Ensure you have a view at resources/views/auth/login.blade.php
    }
}
