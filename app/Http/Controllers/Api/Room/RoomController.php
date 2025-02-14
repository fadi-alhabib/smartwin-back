<?php

namespace App\Http\Controllers\Api\Room;

use App\DTOs\Room\CreateRoomDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Room\CreateRoomRequest;
use App\Http\Resources\Room\RoomResource;
use App\Models\Room;
use App\Services\Rooms\Contracts\RoomServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Pusher\Pusher;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/rooms')]
class RoomController extends Controller
{
    public function __construct(private readonly RoomServiceInterface $roomService, private readonly Pusher $pusher) {}

    #[Get(uri: '/', middleware: ['auth:sanctum'])]
    public function index()
    {
        $rooms = $this->roomService->orderByOnline();
        return $this->success(data: RoomResource::collection($rooms));
    }



    #[Get(uri: '/me', middleware: ['auth:sanctum'])]
    public function me(Request $request)
    {
        $user_id = Auth::user()->id;
        $room = $this->roomService->getMyRoom($user_id);
        if (!$room) {
            return $this->failed('No Room Found', 404);
        }
        $room->online = true;
        $room->save();
        return $this->success(data: new RoomResource($room));
    }

    #[Get(uri: '{room}/offline', middleware: ['auth:sanctum'])]
    public function goOffline(Request $request, Room $room)
    {
        $userId = $request->user()->id;
        if ($room->host_id !== $userId) {
            $this->failed('you are not the host', 403);
        }
        $room->online = false;

        $room->save();

        $this->pusher->trigger('room.' . $room->id, 'room.offline', []);

        return $this->success(data: null, message: "connection closed", statusCode: 200);
    }

    #[Get(uri: '/{room}', middleware: ['auth:sanctum'])]
    public function show(Request $request, Room $room)
    {
        return $this->success(data: new RoomResource($room));
    }

    #[Post(uri: '/', middleware: ['auth:sanctum'])]
    public function store(CreateRoomRequest $request)
    {
        $dto = CreateRoomDto::fromRequest($request);
        $user = Auth::guard('sanctum')->user();
        $dto->set('host_id', $user->id);
        $room = $this->roomService->createWithImage($dto);
        return $this->success(data: new RoomResource($room), statusCode: 201);
    }
}
