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

    #[Get('/')]
    public function index()
    {
        $ads = $this->adsService->all();
        return $this->success(data: $ads);
    }
}
