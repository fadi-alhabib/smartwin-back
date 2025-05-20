<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PointsTransfer;
use App\Models\User;
use App\Models\Store;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;

#[Prefix('api/points-transfers')]
#[Middleware('auth:sanctum')]
class PointsTransferController extends Controller
{
    /**
     * Create a points transfer request from the user.
     *
     * This method uses the authenticated user's ID,
     * sets both store_id and admin_id to null, takes points from the request,
     * sets the type to "admin" and accepted status to false.
     */
    #[Post('user-transfer')]
    public function createUserTransfer(Request $request)
    {
        // Validate the incoming request data.
        $request->validate([
            'points' => 'required|integer|min:1',
        ]);

        // Get the authenticated user.
        $authUser = auth('sanctum')->user();

        // Create a new points transfer with type "admin".
        // type defaults to admin
        $pointsTransfer = PointsTransfer::create([
            'user_id'  => $authUser->id,
            'points'   => $request->points,
        ]);

        return response()->json([
            'message'  => 'تمت العملية بنجاح',
            'transfer' => $pointsTransfer,
        ], 201);
    }

    /**
     * Create a points transfer initiated from the store.
     *
     * This method retrieves the authenticated user (store owner) and their associated store,
     * then creates a transfer to a specified user with type "store" and accepted status true.
     * It also subtracts the points from the store and adds them to the recipient user.
     */
    #[Post('store-transfer')]
    public function createStoreTransfer(Request $request)
    {
        // Validate the incoming request data.
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'points'  => 'required|integer|min:1',
        ]);

        // Get the authenticated user (store owner).
        $storeOwner = auth('sanctum')->user();

        // Retrieve the store associated with the authenticated user.
        // Ensure a relationship exists on the User model (e.g., public function store() { return $this->hasOne(Store::class); }).
        $store = $storeOwner->store;
        if (!$store) {
            return response()->json(['error' => 'No store associated with this user.'], 404);
        }

        // Retrieve the recipient user from the request.
        $recipientUser = User::find($request->user_id);
        if (!$recipientUser) {
            return response()->json(['error' => 'Recipient user not found.'], 404);
        }

        // Check that the store has enough points to transfer.
        if ($store->points < $request->points) {
            return response()->json(['error' => 'Store does not have enough points.'], 400);
        }

        // Create the points transfer with type "store".
        $pointsTransfer = PointsTransfer::create([
            'user_id'  => $recipientUser->id,
            'store_id' => $store->id,
            'points'   => $request->points,
            'type'     => 'store',
            'accepted' => true
        ]);

        // Adjust the store's points: subtract the transferred points.
        $store->points -= $request->points;
        $store->save();

        // Add the points to the recipient user's account.
        $recipientUser->points += $request->points;
        $recipientUser->save();

        return response()->json([
            'message'  => 'تمت العملية بنجاح',
            'transfer' => $pointsTransfer,
        ], 201);
    }
}
