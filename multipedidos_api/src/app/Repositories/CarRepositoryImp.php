<?php

namespace App\Repositories;

use App\Models\Car;
use App\Repositories\Contracts\CarRepositoryInterface;
use App\Repositories\Core\BaseRepositoryImp;
use Illuminate\Database\Eloquent\Model;

class CarRepositoryImp extends BaseRepositoryImp implements CarRepositoryInterface
{
    public function __construct(Car $model)
    {
        parent::__construct($model);
    }
}
