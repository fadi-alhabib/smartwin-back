<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Privilege;
use App\Models\UserPrivilege;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PrivilegeController extends Controller
{



    public function privilege()
    {
        $privileges = Privilege::get();

        // $users = User::where('is_admin', 1)->get();

        $users = DB::select(DB::raw('select users.id, first_name, last_name, GROUP_CONCAT(user_privileges.privilege_id) AS privilege FROM users LEFT JOIN user_privileges ON user_privileges.user_id = users.id WHERE is_admin = 1 GROUP BY users.id'));

        // $users = DB::statement('SELECT users.id, first_name, last_name, GROUP_CONCAT(user_privileges.privilege_id) AS privilege FROM users LEFT JOIN user_privileges ON user_privileges.user_id = users.id GROUP BY users.id');

        // $users = DB::table('users')->select(DB::raw('users.id, first_name, last_name, GROUP_CONCAT(user_privileges.privilege_id) as privilege '))->join('user_privileges', 'user_id', 'users.id')->GROUPBY('users.id')->get();

        $user_privileges = UserPrivilege::all();

        return view('privilege', ['privileges' => $privileges, 'users' => $users, 'user_privileges' => $user_privileges]);

        // dd($users);
    }

    public function checkPrivilege(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'privilege_id' => 'required',
            'status' => 'required'
        ]);

        if ($request->status == 'true') {
            UserPrivilege::create([
                'user_id' => $request->user_id,
                'privilege_id' => $request->privilege_id,
            ]);
        } else {
            UserPrivilege::where('user_id', $request->user_id)->where('privilege_id', $request->privilege_id)->delete();
        }
    }
}
