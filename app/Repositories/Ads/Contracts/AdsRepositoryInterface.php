<?php

namespace App\Repositories\Ads\Contracts;

use App\Repositories\Common\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface AdsRepositoryInterface extends BaseRepositoryInterface
{

    public function getRandomActive(?int $count = 5): Collection;
}
