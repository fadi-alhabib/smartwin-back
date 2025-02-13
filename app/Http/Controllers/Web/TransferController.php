<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Transfer;
use App\Models\TransferRequest;
use App\Models\Point;

class TransferController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function points()
    {
        return view('transfer.points');
    }


    public function update_point(Request $request)
    {
        Point::where('id', 1)->UPDATE([
            'equal' => $request->equal
        ]);


        return back();
    }


    public function transfer()
    {
        $transfers = Transfer::all();

        return view('transfer.transfer', ['transfers' => $transfers]);
    }

    public function transfer_request()
    {
        $transfers = TransferRequest::join('users', 'users.id', '=', 'transfer_requests.user_id')->get(['first_name', 'last_name', 'username', 'email', 'country', 'transfer_requests.id', 'transfer_requests.points', 'amount', 'transfer_requests.created_at']);

        return view('transfer.transfer-request', ['transfers' => $transfers]);
    }

    public function destroy_transfer($id)
    {
        Transfer::where('id', $id)->delete();

        return redirect('/transfer');
    }

    public function destroy_transfer_request($id)
    {
        TransferRequest::where('id', $id)->delete();

        return redirect('/transferRequest');
    }

    public function transfer_done($id)
    {
        $transfer_request = TransferRequest::where('transfer_requests.id', $id)->join('users', 'users.id', '=', 'transfer_requests.user_id')->get(['user_id', 'users.first_name', 'users.last_name', 'users.email', 'users.country', 'transfer_requests.points', 'amount']);


        Transfer::create([
            'user_id',
            $transfer_request[0]->user_id,
            'username' => $transfer_request[0]->first_name . ' ' . $transfer_request[0]->last_name,
            'phone' => $transfer_request[0]->email,
            'country' => $transfer_request[0]->country,
            'points' => $transfer_request[0]->points,
            'amount' => $transfer_request[0]->amount
        ]);

        TransferRequest::where('id', $id)->delete();


        return redirect('/transferRequest');
    }
}
