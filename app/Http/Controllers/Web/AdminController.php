<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    // Except show from resource route


    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index()
    {

        $admins = User::where('is_admin', true)->get();

        return view('users.admin', ['admins' => $admins]);
    }




    public function create()
    {
        return view('users.create-admin');
    }





    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'username' => 'required|unique:users',
                'password' => 'required|min:8|confirmed',
                'phone' => 'required',
            ],
            [
                'first_name.required' => 'حقل الاسم الأول مطلوب',
                'last_name.required' => 'حقل الاسم الأخير مطلوب',
                'username.required' => 'حقل اسم المستخدم مطلوب',
                'username.unique' => 'اسم المستخدم هذا مستخدم من قبل',
                'password.required' => 'حقل كلمة المرور مطلوب',
                'password.min' => 'كلمة المرور يجب ألا تقل عن 8 محارف',
                'password.confirmed' => 'لم تتطابق كلمة المرور',
                'phone.required' => 'حقل الهاتف مطلوب'
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_admin' => 1
        ]);

        return redirect('admin');
    }


    public function show($id) {}



    public function edit($id)
    {
        $admin = User::where('id', $id)->get();

        return view('users.edit-admin', ['admin' => $admin]);
    }



    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
            ],
            [
                'first_name.required' => 'حقل الاسم الأول مطلوب',
                'last_name.required' => 'حقل الاسم الأخير مطلوب',
                'phone.required' => 'حقل الهاتف مطلوب'
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }


        $admin = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone
        ];


        if (!empty($request->password)) {
            $passwordValidator = Validator::make(
                $request->all(),
                [
                    'password' => 'min:8|confirmed'
                ],
                [
                    'password.min' => 'يجب ألا تقل كلمة المرور عن 8 محارف',
                    'password.confirmed' => 'لم تتطابق كلمة المرور',
                ]
            );

            if ($passwordValidator->fails()) {
                return back()->withErrors($passwordValidator);
            }

            $admin['password'] = Hash::make($request->password);
        }

        User::where('id', $id)->update($admin);

        return redirect('admin');
    }



    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return redirect('user');
    }
}
