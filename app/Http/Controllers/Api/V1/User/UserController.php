<?php

namespace App\Http\Controllers\Api\V1\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostUser\PostUser;
use App\Http\Requests\PostUser\PostUserUpdate;
use App\Repositories\V1\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;
    
    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index() {
        return $this->userRepository->getAll();
    }
    
    public function store(PostUser $request) {
        return $this->userRepository->create($request->validated());
    }
    
    public function show($id) {
        return $this->userRepository->find($id);
    }

    public function update(PostUserUpdate $request, $id) {
        return $this->userRepository->update($request->validated(), $id);
    }
    
    public function destroy($id) {
        return $this->userRepository->delete($id);
    }    
}

