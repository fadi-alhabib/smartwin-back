<?php

namespace App\Services\Ads\Contracts;

use App\Services\Common\Contracts\CRUDServiceInterface;

interface AdsServiceInterface extends CRUDServiceInterface
{
    public function getRandomActive(?int $limit = 5);
}
