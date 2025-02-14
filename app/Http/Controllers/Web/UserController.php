<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // Retrieve all users from the new users table
        $users = User::all();

        return view('users.user', ['users' => $users]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validate input using the new fields
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'phone'     => 'required',
            'password'  => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate a unique invite code
        $invite_code = Str::random(6);
        while (true) {
            $check_code = User::where('invite_code', $invite_code)->count();
            if ($check_code > 0) {
                $invite_code = Str::random(6);
            } else {
                break;
            }
        }

        // Create the new user record
        User::create([
            'full_name'   => $request->full_name,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'country'     => $request->country, // country is optional in the new table
            'invite_code' => $invite_code
        ]);

        return redirect('user');
    }

    public function show($id)
    {
        // Use find() to get a single user record
        $user = User::find($id);

        return view('users.show', ['user' => $user]);
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('users.edit-user', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        // Validate the new input fields
        $validator = Validator::make(
            $request->all(),
            [
                'full_name' => 'required',
                'phone'     => 'required',
                'country'   => 'required',
            ],
            [
                'full_name.required' => 'حقل الاسم مطلوب',
                'phone.required'     => 'حقل الهاتف مطلوب',
                'country.required'   => 'حقل البلد مطلوب',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'full_name' => $request->full_name,
            'phone'     => $request->phone,
            'country'   => $request->country,
        ];

        // If a new password is provided, validate and update it.
        if (isset($request->password) && !empty($request->password)) {
            $passwordValidator = Validator::make(
                $request->all(),
                [
                    'password' => 'min:8|confirmed',
                ],
                [
                    'password.min'       => 'كلمة المرور يجب على الاقل ان تكون 8 محارف',
                    'password.confirmed' => 'لم تتطابق كلمة المرور'
                ]
            );

            if ($passwordValidator->fails()) {
                return back()->withErrors($passwordValidator)->withInput();
            }

            $data['password'] = Hash::make($request->password);
        }

        User::where('id', $id)->update($data);

        return redirect('user');
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return redirect('user');
    }

    public function status(Request $request)
    {
        User::where('id', $request->user_id)->update([
            'is_active' => $request->status
        ]);

        return back();
    }
}
