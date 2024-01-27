<?php

namespace App\Repositories\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class BaseRepositoryImp implements BaseRepositoryInterface
{
    protected $model;
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function all() : Collection
    {
        return $this->model->all();
    }

    public function allWithRelations(array|string $relations) : Collection
    {
        return $this->model->with($relations)->get();
    }

    public function findById(int $id) : ?Model
    {
        return $this->model->find($id);
    }

    public function findWithRelations(int $id, array|string $relations) : ?Model
    {
        return $this->model->with($relations)->find($id);
    }

    public function create(array $data) : Model
    {
        $this->model->fill($data);
        $this->model->save();

        return $this->model;
    }
    public function update(array $data, int $id) : bool
    {
        $record = $this->findById($id);

        if ($record) {
            return $record->update($data);
        }

        return false;
    }
    public function delete(int $id) : bool
    {
        $record = $this->findById($id);

        if ($record) {
            return $record->delete();
        }

        return false;
    }
    public function paginate(int $perPage = 10) : LengthAwarePaginator|Collection
    {
        return $this->model->paginate($perPage);
    }
    public function where(string $column, string $value) : self
    {
        $this->model->where($column, $value);

        return $this;
    }
    public function whereIn(string $column, array $value) : self
    {
        $this->model->whereIn($column, $value);

        return $this;
    }
    public function whereHas(array|string $relation, \Closure $closure) : self
    {
        $this->model->whereHas($relation, $closure);

        return $this;
    }
    public function with(array|string $relations) : self
    {
        $this->model->with($relations);

        return $this;
    }
    public function withCount(array|string $relations) : self
    {
        $this->model->withCount($relations);

        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc') : self
    {
        $this->model->orderBy($column, $direction);

        return $this;
    }

    public function first() : ?Model
    {
        return $this->model->first();
    }
    public function firstOrFail() : Model|ModelNotFoundException
    {
        return $this->model->firstOrFail();
    }
    public function firstOrCreate(array $data) : Model
    {
        return $this->model->firstOrCreate($data);
    }

    public function updateOrCreate(array $data, array $values) : Model
    {
        return $this->model->updateOrCreate($data, $values);
    }
    public function count() : int
    {
        return $this->model->count();
    }
}
