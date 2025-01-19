<?php

namespace App\DTOs\Store;

use App\DTOs\Common\BaseDTO;


class CreateProductDto extends BaseDTO
{

    /**
     * @param string $name
     * @param string $description
     * @param float $price
     * @param int|null store_id
     * @param array $images
     */
    public function __construct(
        public string $name,
        public string $description,
        public float $price,
        public array $images,
        public ?int $store_id = null,
    ) {}

    /**
     * Converts the raw image data into an array of ProductImageDto objects.
     *
     * @param int $productId The product ID to associate with the images.
     * @return ProductImageDto[]
     */
    public function getProductImagesDto(int $productId): array
    {
        $productImagesDto = [];

        foreach ($this->images as $image) {
            // Create a new ProductImageDto for each image
            $productImagesDto[] = new ProductImageDto($image, $productId);
        }

        return $productImagesDto;
    }
}
