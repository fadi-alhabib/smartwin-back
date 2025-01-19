<?php

namespace App\Repositories\Store;

use App\Models\Image;
use App\Repositories\Common\BaseRepository;
use App\Repositories\Store\Contracts\ProductImageRepositoryInterface;

class EloquentProductImageRepository extends BaseRepository implements ProductImageRepositoryInterface
{
    public function __construct(Image $model)
    {
        parent::__construct($model);
    }
}
