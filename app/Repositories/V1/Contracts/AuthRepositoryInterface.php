<?php

namespace App\Repositories\V1\Contracts;

interface AuthRepositoryInterface
{
    public function getLogin(array $data);
    public function getLogout();
}
