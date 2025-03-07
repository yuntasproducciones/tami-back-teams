<?php

namespace App\Http\Controllers\Api\V1\Cliente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\StoreClienteRequest;
use App\Http\Requests\Cliente\UpdateClienteRequest;
use App\Repositories\V1\Contracts\ClienteRepositoryInterface;

class ClienteController extends Controller
{
    protected $clienteRepository;
    
    public function __construct(ClienteRepositoryInterface $clienteRepository) {
        $this->clienteRepository = $clienteRepository;
    }
    
    public function index()
    {
        return $this->clienteRepository->getAll();
    }

    public function store(StoreClienteRequest $request)
    {
        return $this->clienteRepository->create($request->validated());
    }

    public function show($id)
    {
        return $this->clienteRepository->find($id);
    }

    public function update(UpdateClienteRequest $request, $id)
    {
        return $this->clienteRepository->update($request->validated(), $id);
    }

    public function destroy($id)
    {
        return $this->clienteRepository->delete($id);
    }
}
