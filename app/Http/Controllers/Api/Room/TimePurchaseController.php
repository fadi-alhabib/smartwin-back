<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\TimePurchase;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/rooms/{room}/time-purchase')]
class TimePurchaseController extends Controller
{
    #[Post('/', middleware: ["auth:sanctum"])]
    public function buyTime(Room $room, Request $request)
    {
        $additionalMinutes = (int) $request->input('minutes') * 60;
        $price = (int) $request->input('price');

        // Validate input
        if ($additionalMinutes <= 0) {
            return response()->json(['error' => 'Invalid time purchase'], 400);
        }

        $user = $request->user();
        if ($user->points < $price) {
            return response()->json(['error' => 'ليس لديك نقاط كافية'], 400);
        }

        try {
            // Deduct points from the user
            $user->points -= $price;
            $user->save();

            // Update room’s available time.
            // (Note: Adjust logic here if you need to add to the current available time.)
            $room->update(['available_time' => $additionalMinutes]);

            // Record the time purchase
            TimePurchase::create([
                'room_id' => $room->id,
                'additional_minutes' => $additionalMinutes,
            ]);

            return response()->json(['message' => 'Time purchased successfully'], 200);
        } catch (\Exception $e) {
            // Catch any unexpected errors and return a JSON error response.
            return response()->json(['error' => 'Failed to process purchase: ' . $e->getMessage()], 500);
        }
    }
}
