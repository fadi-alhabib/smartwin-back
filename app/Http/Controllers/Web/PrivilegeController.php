<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AdminPrivilege;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Privilege;
use Illuminate\Support\Facades\DB;
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
            'admin_id' => 'required|exists:admins,id',
            'privilege_id' => 'required|exists:privileges,id',
            'status' => 'required|boolean' // Ensure boolean validation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Use transaction for atomic operations
        DB::transaction(function () use ($request) {
            if ($request->status) {
                AdminPrivilege::firstOrCreate([
                    'admin_id' => $request->admin_id,
                    'privilege_id' => $request->privilege_id
                ]);
            } else {
                AdminPrivilege::where([
                    'admin_id' => $request->admin_id,
                    'privilege_id' => $request->privilege_id
                ])->delete();
            }
        });

        return response()->json(['success' => true]);
    }
}
