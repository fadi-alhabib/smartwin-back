<?php

namespace App\Repositories\Rooms\Contracts;

use App\Repositories\Common\Contracts\BaseRepositoryInterface;

interface RoomRepositoryInterface extends BaseRepositoryInterface
{
    public function orderByOnline();
}
