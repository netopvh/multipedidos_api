<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserServiceImp implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function getAll() : Collection
    {
        return $this->userRepository->allWithRelations('cars');
    }

    public function getByIdPaginated(int $id, $perPage) : Model|LengthAwarePaginator|null
    {
        $user = $this->userRepository->findById($id);
        if ($user) {
            $cars = $user->cars()->paginate($perPage);
            $user->cars = $cars;
        }

        return $user;
    }

    public function attachCar(array $data, int $id) : void
    {
        $user = $this->userRepository->findById($id);

        $user->cars()->where('car_id', $data['car_id'])->exists() ?
            throw new \Exception("Carro já associado ao usuário") :
            $user->cars()->attach($data['car_id']);
    }

    public function detachCar(array $data, int $id) : void
    {
        $user = $this->userRepository->findById($id);

        $user->cars()->where('car_id', $data['car_id'])->exists() ?
            $user->cars()->detach($data['car_id']) :
            throw new \Exception("Carro não associado ao usuário");
    }

    public function create(array $data) : Model
    {
        return $this->userRepository->create($data);
    }

    public function update(array $data, int $id) : Model
    {
        if (! $user = $this->userRepository->update($data, $id))
            throw new ModelNotFoundException("Usuário não encontrado");

        return $this->userRepository->findById($id);
    }

    public function delete(int $id) : bool
    {
        $user = $this->userRepository->findById($id);
        if (! $user)
            throw new ModelNotFoundException("Usuário não encontrado");

        $user->cars()->sync([]);
        $user->delete();

        return true;
    }
}
