<?php

namespace App\Services\Store;

use App\DTOs\Store\CreateStoreDto;
use App\Services\Common\CRUDService;
use App\Repositories\Store\Contracts\StoreRepositoryInterface;
use App\Services\Common\Contracts\ImageServiceInterface;
use App\Services\Store\Contracts\StoreServiceInterface;

class StoreService extends CRUDService implements StoreServiceInterface
{
    public function __construct(private readonly ImageServiceInterface $imageService, StoreRepositoryInterface $storeRepository)
    {
        $this->repository = $storeRepository;
    }

    public function fetchActive()
    {
        $stores = $this->repository->fetchActive();
        return $stores;
    }

    public function createWithImage(CreateStoreDto $dto)
    {
        $savedImage = $this->imageService->uploadImage($dto->get('image'), '/stores');
        $dto->set('image', $savedImage);
        $store = $this->repository->create($dto);
        return $store;
    }
}
