<?php

namespace App\Http\Controllers\Api\V1\Productos;

use App\Http\Requests\Producto\StoreProductoRequest;
use App\Http\Requests\Producto\UpdateProductoRequest;
use App\Http\Controllers\Controller;
use App\Repositories\V1\Contracts\ProductoRepositoryInterface;

class ProductoController extends Controller
{
    protected $productoRepository;
    
    public function __construct(ProductoRepositoryInterface $productoRepository) {
        $this->productoRepository = $productoRepository;
    }

    public function index()
    {
        return $this->productoRepository->getAll();
    }

    public function store(StoreProductoRequest $request)
    {
        return $this->productoRepository->create($request->validated());
    }

    public function show($id)
    {
        return $this->productoRepository->find($id);
    }

    public function update(UpdateProductoRequest $request, $id)
    {
        return $this->productoRepository->update($request->validated(), $id);
    }

    public function destroy($id)
    {
        return $this->productoRepository->delete($id);
    }
}
