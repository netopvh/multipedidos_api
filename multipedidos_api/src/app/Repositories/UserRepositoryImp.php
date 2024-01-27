<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Core\BaseRepositoryImp;

class UserRepositoryImp extends BaseRepositoryImp implements UserRepositoryInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

}
