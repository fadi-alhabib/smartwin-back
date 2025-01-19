<?php

namespace App\Repositories\Store;

use App\Models\Product;
use App\Repositories\Common\BaseRepository;
use App\Repositories\Store\Contracts\ProductRepositoryInterface;

class EloquentProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function fetchActive()
    {
        return $this->model->with(['store', 'images'])->whereHas('store', function ($query) {
            $query->where('is_active', true);
        })->get();
    }
}
