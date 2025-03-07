<?php

namespace App\Repositories\Auth;
use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
    public function getLogin(array $data);
    public function getLogout();
}
