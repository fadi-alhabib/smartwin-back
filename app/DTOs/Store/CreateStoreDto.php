<?php

namespace App\DTOs\Store;

use App\DTOs\Common\BaseDTO;
use Illuminate\Http\UploadedFile;

class CreateStoreDto extends BaseDTO
{

    public function __construct(
        public string $name,
        public string $type,
        public string $country,
        public string $address,
        public string $phone,
        public UploadedFile | string | null $image = null,
        public ?int $user_id = null,
    ) {}
}
