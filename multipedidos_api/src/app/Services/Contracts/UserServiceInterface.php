<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserServiceInterface
{
    public function getAll() : Collection;
    public function getByIdPaginated(int $id, $perPage) : Model|LengthAwarePaginator|null;
    public function attachCar(array $data, int $id) : void;
    public function detachCar(array $data, int $id) : void;
    public function create(array $data) : Model;
    public function update(array $data, int $id) : Model;
    public function delete(int $id) : bool;
}
