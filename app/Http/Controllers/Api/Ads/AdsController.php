<?php

namespace App\Http\Controllers\Api\Ads;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ads\AdResource;
use App\Models\Advertisement;
use App\Models\Room;
use App\Services\Ads\Contracts\AdsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

        if ($room && $room->available_time == 0 && $room->consumed_at && Carbon::parse($room->consumed_at)->diffInHours(now()) >= 24) {
            $room->available_time = 12;
            $room->save();
        }
        return $this->success(data: ['ads' => AdResource::collection($ads), 'points' => $user->points, 'available_time' => $room?->available_time, 'room_id' => $room?->id]);
    }

    #[Get(uri: '/question')]
    public function questionAds(Request $request)
    {
        $ads = Advertisement::where("home_ad", false)->get();
        return $this->success(data: AdResource::collection($ads));
    }
}
