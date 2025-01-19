<?php

namespace App\Services\Store\Contracts;

use App\DTOs\Store\CreateProductDto;
use App\Services\Common\Contracts\CRUDServiceInterface;

interface ProductServiceInterface extends CRUDServiceInterface
{
    public function fetchActive();
    public function createWithImages(CreateProductDto $dto);
    public function fetchWithStores();
}
