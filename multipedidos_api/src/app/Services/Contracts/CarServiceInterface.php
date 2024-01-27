<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface CarServiceInterface
{
    public function getAll() : Collection;
    public function getById(int $id) : ?Model;
    public function create(array $data) : Model;
    public function update(array $data, int $id) : Model;
    public function delete(int $id) : bool;
}
