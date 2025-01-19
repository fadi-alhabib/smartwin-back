<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\TimePurchase;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimePurchaseController extends Controller
{
    public function buyTime(Room $room, Request $request)
    {
        $additionalMinutes = $request->input('minutes');

        if ($additionalMinutes <= 0) {
            return response()->json(['error' => 'Invalid time purchase'], 400);
        }

        $endTime = Carbon::createFromTimeString($room->end_time);
        $newEndTime = $endTime->addMinutes($additionalMinutes);
        $room->update(['end_time' => $newEndTime->toTimeString()]);

        TimePurchase::create([
            'room_id' => $room->id,
            // 'user_id' => $request->user()->id,
            'additional_minutes' => $additionalMinutes,
        ]);

        return response()->json(['message' => 'Time purchased successfully', 'new_end_time' => $newEndTime->toTimeString()]);
    }
}
