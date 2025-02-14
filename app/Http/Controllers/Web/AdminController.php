<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // Retrieve all admins from the admins table
        $admins = Admin::all();

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
                'full_name' => 'required',
                'username'  => 'required|unique:admins',
                'password'  => 'required|min:8|confirmed',
            ],
            [
                'full_name.required' => 'حقل الاسم مطلوب',
                'username.required'  => 'حقل اسم المستخدم مطلوب',
                'username.unique'    => 'اسم المستخدم هذا مستخدم من قبل',
                'password.required'  => 'حقل كلمة المرور مطلوب',
                'password.min'       => 'كلمة المرور يجب ألا تقل عن 8 محارف',
                'password.confirmed' => 'لم تتطابق كلمة المرور',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        Admin::create([
            'full_name' => $request->full_name,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
        ]);

        return redirect('admin');
    }

    public function show($id)
    {
        // Not implemented as per resource route convention.
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);

        return view('users.edit-admin', ['admin' => $admin]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'full_name' => 'required',
            ],
            [
                'full_name.required' => 'حقل الاسم مطلوب',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        $data = [
            'full_name' => $request->full_name,
        ];

        if (!empty($request->password)) {
            $passwordValidator = Validator::make(
                $request->all(),
                [
                    'password' => 'min:8|confirmed',
                ],
                [
                    'password.min'       => 'يجب ألا تقل كلمة المرور عن 8 محارف',
                    'password.confirmed' => 'لم تتطابق كلمة المرور',
                ]
            );

            if ($passwordValidator->fails()) {
                return back()->withErrors($passwordValidator);
            }

            $data['password'] = Hash::make($request->password);
        }

        Admin::where('id', $id)->update($data);

        return redirect('admin');
    }

    public function destroy($id)
    {
        Admin::destroy($id);

        return redirect('admin');
    }
}
