<?php

namespace App\Http\Controllers\Api\Ads;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ads\AdResource;
use App\Models\Room;
use App\Services\Ads\Contracts\AdsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/ads')]
class AdsController extends Controller
{
    public function __construct(private readonly AdsServiceInterface $adsService) {}
    #[Get(uri: '/home', middleware: ['auth:sanctum'])]
    public function index(Request $request)
    {
        // TODO::UPDATE AVAILABLE TIME DAILY
        $ads = $this->adsService->getRandomActive();
        $user = $request->user();
        $room = Room::where('host_id', $user->id)->first();
        return $this->success(data: ['ads' => AdResource::collection($ads), 'points' => $user->points, 'available_time' => $room->available_time, 'room_id' => $room->id]);
    }
}
