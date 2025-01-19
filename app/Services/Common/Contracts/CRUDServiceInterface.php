<?php

namespace App\Services\Common\Contracts;

use App\DTOs\Common\BaseDTO;

interface CRUDServiceInterface
{
    public function all();

    public function find(int $id);

    public function create(BaseDTO $dto);

    public function createMany(array $dtos);

    public function update(mixed $record, BaseDTO $dto);

    public function delete(mixed $record);
}
