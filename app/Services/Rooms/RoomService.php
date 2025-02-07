<?php

namespace App\Services\Rooms;

use App\DTOs\Room\CreateRoomDto;
use App\Repositories\Rooms\Contracts\RoomRepositoryInterface;
use App\Services\Common\Contracts\ImageServiceInterface;
use App\Services\Common\CRUDService;
use App\Services\Rooms\Contracts\RoomServiceInterface;

class RoomService extends CRUDService implements RoomServiceInterface
{
    public function __construct(private readonly ImageServiceInterface $imageService, RoomRepositoryInterface $roomRepository,)
    {
        $this->repository = $roomRepository;
    }

    public function orderByOnline()
    {
        return $this->repository->orderByOnline();
    }

    public function createWithImage(CreateRoomDto $dto)
    {
        $savedImage = $this->imageService->uploadImage($dto->get('image'), '/rooms');
        $dto->set('image', $savedImage);
        $room = $this->repository->create($dto);
        return $room;
    }

    public function getMyRoom(int $user_id)
    {
        return $this->repository->findOneBy('host_id', $user_id);
    }
}
