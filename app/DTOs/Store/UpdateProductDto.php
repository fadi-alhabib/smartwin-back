<?php

namespace App\DTOs\Store;

use App\DTOs\Common\BaseDTO;


class UpdateProductDto extends BaseDTO
{

    /**
     * @param string $name
     * @param string $description
     * @param float $price
     */
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public ?float $price = null,

    ) {}
}
