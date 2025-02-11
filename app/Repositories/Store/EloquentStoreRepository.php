<?php

namespace App\Repositories\Store;

use App\Models\Store;
use App\Repositories\Common\BaseRepository;
use App\Repositories\Store\Contracts\StoreRepositoryInterface;

class EloquentStoreRepository extends BaseRepository implements StoreRepositoryInterface
{
    public function __construct(Store $model)
    {
        parent::__construct($model);
    }

    public function fetchActive()
    {
        return $this->findBy('is_active', true);
    }
    public function getMyStore(int $userId)
    {
        return $this->findOneBy('user_id', $userId);
    }
}
