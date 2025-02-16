<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Validator;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/product-ratings')]
class ProductRatingController extends Controller
{
    /**
     * Store a newly created rating in storage.
     */
    #[Post('/', middleware: 'auth:sanctum')]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = $request->user();
        $rating = ProductRating::create([...$request->all(), 'user_id' => $user->id]);

        return response()->json(['message' => 'Rating submitted successfully', 'rating' => $rating], 201);
    }

    /**
     * Display the specified product's ratings.
     */
    #[Get('/{product_id}')]
    public function show($product_id)
    {
        $ratings = ProductRating::where('product_id', $product_id)->with('user')->get();
        return response()->json($ratings);
    }

    /**
     * Update the specified rating in storage.
     */
    #[Patch('/{id}')]
    public function update(Request $request, $id)
    {
        $rating = ProductRating::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'rating' => 'integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rating->update($request->only(['rating', 'review']));

        return response()->json(['message' => 'Rating updated successfully', 'rating' => $rating]);
    }

    /**
     * Remove the specified rating from storage.
     */
    #[Delete('/{id}')]
    public function destroy($id)
    {
        $rating = ProductRating::findOrFail($id);
        $rating->delete();

        return response()->json(['message' => 'Rating deleted successfully']);
    }
}
