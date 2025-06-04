<?php

namespace App\Repositories\Ads;


use App\Models\Advertisement;
use App\Repositories\Ads\Contracts\AdsRepositoryInterface;
use App\Repositories\Common\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentAdsRepository extends BaseRepository implements AdsRepositoryInterface
{
    public function __construct(Advertisement $model)
    {
        parent::__construct($model);
    }

    // gets random active records based on a limit  
    public function getRandomActive(?int $limit = 5): Collection
    {
        return $this->model->where('is_active', true)->where('is_img', true)->inRandomOrder()->limit($limit)->get();
    }
}
