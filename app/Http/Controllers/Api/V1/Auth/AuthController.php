<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostAuth\PostAuth;
use App\Repositories\V1\Contracts\AuthRepositoryInterface;

class AuthController extends Controller
{
    protected $authRepository;
    
    public function __construct(AuthRepositoryInterface $authRepository) {
        $this->authRepository = $authRepository;
    }
    
    public function login(PostAuth $request)
    {
        return $this->authRepository->getLogin($request->validated());
    }

    public function logout()
    {
        return $this->authRepository->getLogout();
    }
}