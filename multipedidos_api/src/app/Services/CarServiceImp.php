<?php

namespace App\Services;

use App\Repositories\Contracts\CarRepositoryInterface;
use App\Services\Contracts\CarServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class CarServiceImp implements CarServiceInterface
{
    public function __construct(
        private readonly CarRepositoryInterface $carRepository
    ) {
    }

    public function getAll() : Collection
    {
        return $this->carRepository->all();
    }

    public function getById(int $id) : ?Model
    {
        return $this->carRepository->findById($id);
    }

    public function create(array $data) : Model
    {
        return $this->carRepository->create($data);
    }

    public function update(array $data, int $id) : Model
    {
        if (! $car = $this->carRepository->update($data, $id))
            throw new ModelNotFoundException("Carro não encontrado");

        return $this->carRepository->findById($id);
    }

    public function delete(int $id) : bool
    {
        if (! $car = $this->carRepository->delete($id))
            throw new ModelNotFoundException("Carro não encontrado");

        return $car;
    }
}
