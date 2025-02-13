<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        $users = Admin::all();

        return view('users.user', ['users' => $users]);
    }


    public function create()
    {
        return view('user.create');
    }



    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'requried',
            'last_name' => 'required',
            'username' => 'required',
            'phone' => 'required',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInputs($request->all());
        }

        // Make invite code

        $invite_code = str_random(6);

        while (true) {

            $check_code = User::where('invite_code', $invite_code)->count();

            if ($check_code > 0) {
                $invite_code = str_random(6);
            } else {
                break;
            }
        }

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'country' => $request->country,
            'invite_code' => $invite_code
        ]);


        return redirect('user');
    }


    public function show($id)
    {
        $user = User::where('id', $id)->get();

        return view('users.show', [$user => 'user']);
    }



    public function edit($id)
    {
        $user = User::where('id', $id)->get();

        return view('users.edit-user', ['user' => $user]);
    }



    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'country' => 'required',
            ],
            [
                'first_name.required' => 'حقل الاسم الأول مطلوب',
                'last_name.required' => 'حقل الاسم الأخير مطلوب',
                'phone.required' => 'حقل الهاتف مطلوب',
                'country.required' => 'حقل البلد مطلوب',
            ]
        );


        if ($validator->fails()) {
            return back()->withErrors($validator)->withInputs($request->all());
        }

        $user = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'country' => $request->country
        ];

        if (isset($request->password) && !empty($request->password)) {
            $passwordValidator = Validator::make(
                $request->all(),
                [
                    'password' => 'min:8|confirmed',
                ],
                [
                    'password.min' => 'كلمة المرور يجب على الاقل ان تكون 8 محارف',
                    'password.confirmed' => 'لم تتطابق كلمة المرور'
                ]
            );


            if ($passwordValidator->fails()) {
                return back()->withErrors($passwordValidator)->withInputs($request->all());
            }

            $user['password'] = Hash::make($request->password);
        }


        User::where('id', $id)->update($user);


        return redirect('user');
    }



    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return redirect('user');
    }




    public function status(Request $request)
    {
        User::where('id', $request->user_id)->UPDATE([
            'is_active' => $request->status
        ]);

        return back();
    }
}
