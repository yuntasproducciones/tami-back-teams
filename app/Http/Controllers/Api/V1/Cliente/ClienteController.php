<?php

namespace App\Http\Controllers\Api\V1\Cliente;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BasicController;
use App\Http\Contains\HttpStatusCode;
use App\Http\Requests\Cliente\StoreClienteRequest;
use App\Http\Requests\Cliente\UpdateClienteRequest;

class ClienteController extends BasicController
{
    /**
     * Display a listing of clientes.
     */
    public function index()
    {
        try {
            $clientes = Cliente::all();

            return $this->successResponse(
                [$clientes->isEmpty() ? 'No hay usuarios para listar.' : 
                'Usuarios listados correctamente.' => $clientes, HttpStatusCode::OK]
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrio un problema al listar los usuarios. ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created cliente in storage.
     */
    public function store(StoreClienteRequest $request)
    {
        try {
            Cliente::create($request->all());

            return $this->successResponse(null
            , 'Usuario registrado exitosamente.', HttpStatusCode::OK);
            
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrio un problema al procesar la solicitud. '. $e->getMessage()
            , HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $cliente = Cliente::find($id);

            return $this->successResponse(
                $cliente ? 'Usuario encontrado.' : 'Usuario no encontrado.', 
                $cliente, HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified cliente in storage.
     */
    public function update(UpdateClienteRequest $request, $id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->update($request->validated());

            $message = $cliente->wasChanged() 
                ? 'Se actualizaron los campos correctamente.' 
                : 'No se actualizaron los campos';

            $statusCode = $cliente->wasChanged() 
                ? HttpStatusCode::OK 
                : HttpStatusCode::NO_CONTENT;

            return $this->successResponse(null, $message, $statusCode);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified cliente from storage.
     */
    public function destroy($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            
            if (!$cliente) {
                return $this->errorResponse('Usuario no encontrado.', 
                HttpStatusCode::NOT_FOUND);
            }

            if (!$cliente->delete()) {
                return $this->errorResponse('No se pudo eliminar el usuario.', 
                HttpStatusCode::INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse(null, 'Se eliminó correctamente el usuario.', 
            HttpStatusCode::OK);
        } catch(\Exception $e) {
            return $this->errorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), 
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
