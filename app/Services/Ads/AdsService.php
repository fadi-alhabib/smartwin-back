<?php

namespace App\Services\Ads;

use App\Repositories\Ads\Contracts\AdsRepositoryInterface;
use App\Services\Ads\Contracts\AdsServiceInterface;
use App\Services\Common\CRUDService;

class AdsService extends CRUDService implements AdsServiceInterface
{
    public function __construct(AdsRepositoryInterface $adsRepository)
    {
        $this->repository = $adsRepository;
    }

    public function getRandomActive(?int $limit = 5)
    {
        return $this->repository->getRandomActive($limit);
    }
}
