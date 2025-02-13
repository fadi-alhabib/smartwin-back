<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{

    public function index()
    {
        return view('changePassword');
    }

    public function update(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'old_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ],
            [
                'old_password.required' => 'كلمة السر القديمة مطلوبة',
                'new_password.required' => 'كلمة السر الجديدة مطلوبة',
                'new_password.min' => 'يجب ألا تقل كلمة المرور عن 8 محارف',
                'new_password.confirmed' => 'لم تتطابق كلمة المرور',

            ]
        );


        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = User::find(auth()->user()->id);

        $check_password = Hash::check($request->old_password, $user->password);

        if ($check_password) {
            User::where('id', auth()->user()->id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect('home');
        } else {
            return back()->withErrors(['old_password' => 'لم تتطابق كلمة المرور مع الكلمة الحالية']);
        }

        return redirect('/changePassword');
    }
}
