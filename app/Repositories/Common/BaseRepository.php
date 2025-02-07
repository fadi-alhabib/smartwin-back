<?php

namespace App\Repositories\Common;

use App\DTOs\Common\BaseDTO;
use App\Repositories\Common\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(BaseDTO $dto)
    {
        return $this->model->create($dto->toArray());
    }

    public function createMany(array $dtos)
    {
        $data = array_map(function (BaseDTO $dto) {
            return $dto->toArray();
        }, $dtos);

        return $this->model->insert($data);
    }


    public function update(mixed $record, BaseDTO $dto)
    {
        $record->update($dto->toArray());

        return $record;
    }

    public function delete(mixed $record)
    {
        $record->delete();
        return;
    }

    public function findBy(string $column, $value)
    {
        return $this->model->where($column, $value)->get();
    }
    public function findOneBy(string $column, $value)
    {
        return $this->model->where($column, $value)->first();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model->paginate($perPage);
    }

    public function scope(callable $callback)
    {
        return $callback($this->model->newQuery());
    }
}
