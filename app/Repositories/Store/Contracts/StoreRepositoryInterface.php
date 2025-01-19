<?php

namespace App\Repositories\Store\Contracts;

use App\Repositories\Common\Contracts\BaseRepositoryInterface;

interface StoreRepositoryInterface extends BaseRepositoryInterface
{
    public function fetchActive();
}
