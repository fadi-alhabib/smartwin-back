<?php

namespace App\DTOs\Room;

use App\DTOs\Common\BaseDTO;
use Illuminate\Http\UploadedFile;

class CreateRoomDto extends BaseDTO
{
    public function __construct(
        public string $name,
        public UploadedFile | string | null $image,
        public ?string $host_id = null,
    ) {}
}
