<?php

namespace App\Http\Controllers\Api\V1\Cliente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\StoreClienteRequest;
use App\Http\Requests\Cliente\UpdateClienteRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Cliente;
use Illuminate\Validation\ValidationException;
use App\Traits\HttpResponses;

class ClienteController extends Controller
{
    use HttpResponses;

    public function index()
    {
        try {
            $clientes = Cliente::paginate(10);

            $message = $clientes->isEmpty()
                ? 'No hay clientes para listar.'
                : 'Clientes listados correctamente.';

            return $this->success($clientes, $message, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->error(null, 'Ocurrió un problema al listar los clientes. ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreClienteRequest $request)
    {
        try {
            $cliente = Cliente::create($request->validated());

            return $this->success($cliente, 'Cliente registrado exitosamente.', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->error(null, 'Ocurrió un problema al registrar el cliente. ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);

            return $this->success($cliente, 'Cliente encontrado.', Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return $this->error(null, 'Cliente no encontrado.', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->error(null, 'Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateClienteRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $cliente = Cliente::findOrFail($id);
            $cliente->update($data);

            return $this->success($cliente, 'Cliente actualizado exitosamente.', Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return $this->error(null, 'Cliente no encontrado.', Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 'Error de validación.', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return $this->error(null, 'Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            return $this->success(null, 'Cliente eliminado exitosamente.', Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return $this->error(null, 'Cliente no encontrado.', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->error(null, 'Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
