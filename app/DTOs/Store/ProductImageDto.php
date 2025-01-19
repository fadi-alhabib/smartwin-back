<?php

namespace App\DTOs\Store;

use App\DTOs\Common\BaseDTO;

class ProductImageDto extends BaseDTO
{

    /**
     * @param string $image
     * @param int $product_id
     */
    public function __construct(public string $image, public int $product_id) {}
}
