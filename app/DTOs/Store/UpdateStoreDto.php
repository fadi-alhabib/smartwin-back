<?php

namespace App\DTOs\Store;

use App\DTOs\Common\BaseDTO;

class UpdateStoreDto extends BaseDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $type = null,
        public ?string $country = null,
        public ?string $address = null,
        public ?string $phone = null,
        // TODO::
        // public UploadedFile | string | null $image = null,

    ) {}
}
