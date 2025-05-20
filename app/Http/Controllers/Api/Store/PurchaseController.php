<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/purchase')]
class PurchaseController extends Controller
{

    #[Post('/', middleware: "auth:sanctum")]
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',

            'quantity'   => 'sometimes|integer|min:1',
        ]);

        try {
            $purchase = DB::transaction(function () use ($request, $data) {

                $userId = auth('sanctum')->user()->id;
                $user = User::find($userId);


                $product = Product::findOrFail($data['product_id']);
                $store   = Store::findOrFail($product->store_id);


                $quantity = $request->input('quantity', 1);


                $totalPoints = $product->price * $quantity;


                if ($user->points < $totalPoints) {
                    throw new \Exception("لا يوجد لديك نقاط كافية");
                }


                $user->points -= $totalPoints;
                $user->save();


                $store->points += $totalPoints;
                $store->save();


                $purchaseData = [
                    'user_id'      => $user->id,
                    'product_id'   => $product->id,
                    'store_id'     => $store->id,
                    'quantity'     => $quantity,
                    'points_spent' => $totalPoints,

                ];


                return Purchase::create($purchaseData);
            });

            return response()->json($purchase, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        $purchase = Purchase::findOrFail($id);
        return response()->json($purchase);
    }


    #[Patch('/{id}/status', middleware: "auth:sanctum")]
    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:completed,cancelled',
        ]);


        $purchase = Purchase::findOrFail($id);


        $store = Store::findOrFail($purchase->store_id);


        $authUser = auth('sanctum')->user();
        if ($store->user_id !== $authUser->id) {
            return response()->json(['error' => 'Unauthorized. Only the store owner can update the purchase status.'], 403);
        }

        try {
            DB::transaction(function () use ($purchase, $store, $data) {

                if ($data['status'] === 'cancelled' && $purchase->status !== 'cancelled') {
                    $user = User::findOrFail($purchase->user_id);


                    $user->points += $purchase->points_spent;
                    $user->save();


                    $store->points = max(0, $store->points - $purchase->points_spent);
                    $store->save();


                    $purchase->status = 'cancelled';
                    $purchase->save();
                }



                if ($data['status'] === 'completed' && $purchase->status !== 'completed') {
                    $purchase->status = 'completed';
                    $purchase->save();
                }
            });

            return response()->json($purchase);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    #[Get('/store', middleware: "auth:sanctum")]
    public function getStorePurchases(Request $request)
    {
        $authUser = auth('sanctum')->user();

        $store = Store::where('user_id', $authUser->id)->first();
        if (!$store) {
            return response()->json(['error' => 'Store not found for this user.'], 404);
        }

        $query = Purchase::where('store_id', $store->id);

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        // Eager load the related product (with images) and user for each purchase.
        $purchases = $query->with(['product.images', 'user'])->get();

        // Map each purchase to return product details along with the buyer's name, phone,
        // and the first image of the product.
        $results = $purchases->map(function ($purchase) {
            return [
                'id'          => $purchase->id,
                'product'     => $purchase->product,
                'buyer_name'  => $purchase->user->full_name,
                'buyer_phone' => $purchase->user->phone,
                'first_image' => optional($purchase->product->images->first())->image,
            ];
        });

        return response()->json($results);
    }
}
