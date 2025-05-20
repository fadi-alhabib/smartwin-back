<?php

namespace App\Repositories\Rooms;

use App\Models\Room;
use App\Repositories\Common\BaseRepository;
use App\Repositories\Rooms\Contracts\RoomRepositoryInterface;

class EloquentRoomRepository extends BaseRepository implements RoomRepositoryInterface
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    public function orderByOnline()
    {
        return $this->model->where("online", true)->get();
    }
}
