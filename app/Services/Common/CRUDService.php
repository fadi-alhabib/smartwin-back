<?php

namespace App\Services\Common;

use App\DTOs\Common\BaseDTO;
use App\Services\Common\Contracts\CRUDServiceInterface;

abstract class CRUDService implements CRUDServiceInterface
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create(BaseDTO $dto)
    {
        return $this->repository->create($dto);
    }

    public function createMany(array $dtos)
    {
        return $this->repository->createMany($dtos);
    }

    public function update(mixed $record, BaseDTO $dto)
    {
        return $this->repository->update($record, $dto);
    }

    public function delete(mixed $record)
    {
        return $this->repository->delete($record);
    }
}
