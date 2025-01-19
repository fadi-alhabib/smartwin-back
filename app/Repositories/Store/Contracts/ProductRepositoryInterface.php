<?php

namespace App\Repositories\Store\Contracts;

use App\Repositories\Common\Contracts\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function fetchActive();
}
