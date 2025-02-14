<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AdminPrivilege;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Privilege;
use Illuminate\Support\Facades\Validator;

class PrivilegeController extends Controller
{
    public function privilege()
    {
        // Retrieve all available privileges using Eloquent
        $privileges = Privilege::all();

        // Get all admins along with their privileges via the defined relationship
        $admins = Admin::with('privileges')->get();

        // Optionally, if you need all assignments separately:
        $admin_privileges = AdminPrivilege::all();

        return view('privilege', [
            'privileges'       => $privileges,
            'admins'           => $admins,
            'admin_privileges' => $admin_privileges,
        ]);
    }

    public function checkPrivilege(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_id'     => 'required',
            'privilege_id' => 'required',
            'status'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->status === 'true') {
            AdminPrivilege::create([
                'admin_id'     => $request->admin_id,
                'privilege_id' => $request->privilege_id,
            ]);
        } else {
            AdminPrivilege::where('admin_id', $request->admin_id)
                ->where('privilege_id', $request->privilege_id)
                ->delete();
        }

        return response()->json(['success' => true]);
    }
}
