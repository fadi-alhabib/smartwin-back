<?php

namespace App\Http\Controllers\Api\Room;

use App\Events\Room\C4\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Room;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Room $room, Request $request)
    {
        $message = Message::create([
            'room_id' => $room->id,
            'user_id' => $request->user()->id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message));

        return response()->json($message);
    }

    public function index(Room $room)
    {
        return response()->json($room->messages);
    }
}
