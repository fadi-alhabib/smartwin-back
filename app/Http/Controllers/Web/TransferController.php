<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transfer;
use App\Models\Point;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display the conversion rate settings.
     */
    public function points()
    {
        return view('transfer.points');
    }

    // /**
    //  * Update the conversion rate (if using a Point model/table).
    //  */
    // public function update_point(Request $request)
    // {
    //     Point::where('id', 1)->update([
    //         'equal' => $request->equal,
    //     ]);

    //     return back();
    // }

    /**
     * Display all transfers.
     */
    public function transfer()
    {
        // Retrieve completed transfers (where done is true) along with user details
        $transfers = Transfer::where("done", true)
            ->join('users', 'users.id', '=', 'transfers.user_id')
            ->get([
                'transfers.points',
                'transfers.created_at',
                'transfers.phone',
                'transfers.country',
                'users.full_name',
                'users.email'
            ]);


        return view('transfer.transfer', compact('transfers'));
    }


    /**
     * Display pending transfers (transfers not yet marked as done).
     * Uses the old view name "transfer.transfer-request" for consistency.
     */
    public function pendingTransfers()
    {
        $transfers = Transfer::where('done', false)
            ->join('users', 'users.id', '=', 'transfers.user_id')
            ->get([
                'users.full_name',
                'users.email',
                'users.phone',
                'users.country',
                'transfers.id',
                'transfers.points',
                'transfers.created_at'
            ]);

        return view('transfer.transfer-request', ['transfers' => $transfers]);
    }

    /**
     * Delete a transfer record.
     */
    public function destroy_transfer($id)
    {
        Transfer::where('id', $id)->delete();
        return redirect('/transfer');
    }

    /**
     * Mark a pending transfer as done.
     * This method is now named "transfer_done" to match the route used in your view.
     */
    public function transfer_done($id)
    {
        $transfer = Transfer::find($id);

        if (!$transfer) {
            return redirect()->back()->withErrors('Transfer not found.');
        }

        $transfer->update(['done' => true]);

        return redirect('/transferRequest');
    }
}
