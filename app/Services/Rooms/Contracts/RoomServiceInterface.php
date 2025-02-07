<?php

namespace App\Services\Rooms\Contracts;

use App\DTOs\Room\CreateRoomDto;
use App\Services\Common\Contracts\CRUDServiceInterface;


interface RoomServiceInterface extends  CRUDServiceInterface
{
    public function orderByOnline();

    public function createWithImage(CreateRoomDto $dto);

    public function getMyRoom(int $user_id);
}
