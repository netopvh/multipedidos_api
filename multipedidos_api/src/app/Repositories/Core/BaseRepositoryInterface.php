<?php

namespace App\Repositories\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function all() : Collection;
    public function allWithRelations(array|string $relations) : Collection;
    public function findById(int $id) : ?Model;
    public function findWithRelations(int $id, array|string $relations) : ?Model;
    public function create(array $data) : Model;
    public function update(array $data, int $id) : bool;
    public function delete(int $id) : bool;
    public function paginate(int $perPage = 10) : LengthAwarePaginator|Collection;
    public function where(string $column, string $value) : self;
    public function whereIn(string $column, array $value) : self;
    public function whereHas(array|string $relation, \Closure $closure) : self;
    public function with(array|string $relations) : self;
    public function withCount(array|string $relations) : self;
    public function orderBy(string $column, string $direction = 'asc') : self;
    public function first() : ?Model;
    public function firstOrFail() : Model|ModelNotFoundException;
    public function firstOrCreate(array $data) : Model;
    public function updateOrCreate(array $data, array $values) : Model;
    public function count() : int;
}
