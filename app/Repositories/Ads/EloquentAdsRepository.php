<?php

namespace App\Repositories\Ads;


use App\Models\Advertisement;
use App\Repositories\Ads\Contracts\AdsRepositoryInterface;
use App\Repositories\Common\BaseRepository;

class EloquentAdsRepository extends BaseRepository implements AdsRepositoryInterface
{
    public function __construct(Advertisement $model)
    {
        parent::__construct($model);
    }
}
