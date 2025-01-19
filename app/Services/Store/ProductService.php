<?php

namespace App\Services\Store;

use App\DTOs\Store\CreateProductDto;
use App\Models\Image;
use App\Models\Product;
use App\Repositories\Store\Contracts\ProductImageRepositoryInterface;
use App\Services\Common\CRUDService;
use App\Repositories\Store\Contracts\ProductRepositoryInterface;
use App\Services\Common\Contracts\ImageServiceInterface;
use App\Services\Store\Contracts\ProductServiceInterface;

class ProductService extends CRUDService implements ProductServiceInterface
{
    public function __construct(
        private readonly ImageServiceInterface $imageService,
        private readonly ProductImageRepositoryInterface $imageRepository,
        ProductRepositoryInterface $productRepository,
    ) {
        parent::__construct($productRepository);
    }

    public function fetchWithStores()
    {
        $products = $this->repository->scope(fn($query) => $query->with(['store', 'images'])->get());
        return $products;
    }

    public function fetchActive()
    {
        $products = $this->repository->fetchActive();
        return $products;
    }

    public function createWithImages(CreateProductDto $dto)
    {
        $product = $this->repository->create($dto);
        $savedImages = $this->imageService->uploadImage($dto->get('images'), '/products');
        $dto->set('images', $savedImages);
        $this->imageRepository->createMany($dto->getProductImagesDto($product->id));
        return $product;
    }
}
