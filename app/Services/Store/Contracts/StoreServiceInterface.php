<?php

namespace App\Services\Store\Contracts;

use App\DTOs\Store\CreateStoreDto;
use App\Services\Common\Contracts\CRUDServiceInterface;

interface StoreServiceInterface extends CRUDServiceInterface
{
    public function fetchActive();
    public function createWithImage(CreateStoreDto $dto);
}
