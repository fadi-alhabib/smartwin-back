<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class DeleteAccountController extends Controller
{

    public function index()
    {
        return view('deleteAccount');
    }


    public function deleteAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);


        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        $user = User::where('phone', $request->email)->first();


        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                User::where('id', $user->id)->delete();

                return redirect('/deletedSuccessfuly');
            } else {
                return back()->withErrors(['password' => 'كلمة المرور غير صحيحة']);
            }
        } else {
            return back()->withErrors(['email' => 'هذا الحساب غير موجود']);
        }
    }


    public function deletedSuccessfuly()
    {
        return view('deletedSuccessfuly');
    }


    public function google()
    {
        return Socialite::driver('google')->redirect();
    }


    public function googleCallback()
    {
        $user = Socialite::driver('google')->user();

        $check = User::where('phone', $user->email)->where('with_google', true)->count();

        if ($check > 0) {
            User::where('phone', $user->email)->delete();

            return redirect('/deletedSuccessfuly');
        }

        return redirect('/deleteAccount')->withErrors(['password' => 'هذا الحساب غير موجود او لم يتم تسجيله عن طريق جوجل']);
    }
}
