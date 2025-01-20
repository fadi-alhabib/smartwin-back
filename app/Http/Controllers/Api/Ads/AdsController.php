<?php

namespace App\Http\Controllers\Api\Ads;

use App\Http\Controllers\Controller;
use App\Services\Ads\Contracts\AdsServiceInterface;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/ads')]
class AdsController extends Controller
{
    public function __construct(private readonly AdsServiceInterface $adsService) {}
    #[Get('/home')]
    public function index(Request $request)
    {
        // TODO::UPDATE AVAILABLE TIME DAILY
        $ads = $this->adsService->all();
        $user = $request->user();

        return $this->success(data: ['ads' => $ads, 'points' => $user->points, 'available_time' => $user->room->available_time]);
    }
}
