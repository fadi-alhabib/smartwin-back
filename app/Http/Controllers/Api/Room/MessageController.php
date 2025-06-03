<?php

namespace App\Http\Controllers\Api\Room;

use App\Events\Room\C4\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Room\Messages\CreateMessageRequest;
use App\Http\Resources\Room\Messages\MessageResource;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/messages/rooms')]
class MessageController extends Controller
{
    public function __construct(private readonly Pusher $pusher) {}

    #[Post(uri: '/{room}', middleware: ['auth:sanctum'])]
    public function store(Room $room, CreateMessageRequest $request)
    {
        $message = Message::create([
            'room_id' => $room->id,
            'user_id' => $request->user()->id,
            'message' => $request->message,
        ]);

        $this->pusher->trigger('room.' . $room->id, 'message.sent', new MessageResource($message),);
        // broadcast(new MessageSent($message))->toOthers();

        return response()->json(new MessageResource($message));
    }
    #[Get(uri: '/active-users', middleware: "auth:sanctum")]
    public function getActiveUsers(Request $request)
    {
        $roomId = Room::where('host_id', $request->user()->id)->first()->id;
        $users = User::whereNot('id', $request->user()->id)->whereHas('messages', function ($query) use ($roomId) {
            $query->where('room_id', $roomId)
                ->where('created_at', '>=', Carbon::now()->subMinutes(1));
        })->get();
        return $this->success($users);
    }
    #[Get(uri: '/rooms/{room}', middleware: ['auth:sanctum'])]
    public function index(Room $room)
    {
        $messages = $room->messages()->with('user')->get();
        return $this->success(data: MessageResource::collection($messages));
    }
}
