<?php

namespace App\Repositories\Common\Contracts;

use App\DTOs\Common\BaseDTO;

interface BaseRepositoryInterface
{
    public function all();

    public function find(int $id);

    public function create(BaseDTO $dto);

    public function createMany(array $dtos);

    public function update(mixed $record, BaseDTO $dto);

    public function delete(mixed $record);

    public function findBy(string $column, $value);

    public function paginate(int $perPage = 15);

    public function scope(callable $callback);
}
