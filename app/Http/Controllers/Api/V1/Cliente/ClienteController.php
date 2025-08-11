<?php

namespace App\Http\Controllers\Api\V1\Cliente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\StoreClienteRequest;
use App\Http\Requests\Cliente\UpdateClienteRequest;
use App\Models\Cliente;
use App\Services\ApiResponseService;
use Illuminate\Http\Response;
use App\Http\Contains\HttpStatusCode;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    protected ApiResponseService $apiResponse;

    public function __construct(ApiResponseService $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/clientes",
     *     summary="Listar clientes",
     *     description="Obtiene la lista de todos los clientes registrados con sus datos básicos.",
     *     operationId="getClientes",
     *     tags={"Clientes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de clientes obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Clientes obtenidos exitosamente"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Juan Pérez"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         format="email",
     *                         example="juan.perez@example.com"
     *                     ),
     *                     @OA\Property(
     *                         property="celular",
     *                         type="string",
     *                         example="+51 987654321"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Error al obtener los clientes: mensaje del error"
     *             )
     *         )
     *     )
     * )
     */

    public function index()
    {
        try{
            $cliente = Cliente::get();

            $showClient = $cliente->map(function ($cliente){
                return [
                    'id' => $cliente->id,
                    'name' => $cliente->name,
                    'email' => $cliente->email,
                    'celular' => $cliente->celular,
                ];
            });

            return $this->apiResponse->successResponse(
                $showClient,
                'Clientes obtenidos exitosamente',
                HttpStatusCode::OK
            );
        }
        catch(\Exception $e){
            return response()->json(['error' => 'Error al obtener los clientes: ' . $e->getMessage()], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
        
    }

    public function store(StoreClienteRequest $request)
    {
        $datosValidados = $request->validated();
        DB::beginTransaction();

        try
        {
            $cliente = Cliente::create(
            [
                'name' => $datosValidados['name'],
                'email' => $datosValidados['email'],
                'celular' => $datosValidados['celular']
            ]
            );

            DB::commit();
            return $this->apiResponse->successResponse($cliente->fresh(), 'Cliente creado con éxito.', HttpStatusCode::CREATED);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el cliente: ' . $e->getMessage()], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $cliente = Cliente::find($id);

            $showClient = [
                'id' => $cliente->id,
                'name' => $cliente->name,
                'email' => $cliente->email,
                'celular' => $cliente->celular,
            ];

            return $this->apiResponse->successResponse(
                $showClient,
                'Cliente obtenido exitosamente',
                HttpStatusCode::OK
            );
        }
        catch(\Exception $e){
            return response()->json(['error' => 'Error al obtener el cliente: ' . $e->getMessage()], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, string $id)
    {
        $datosValidados = $request->validated();
        DB::beginTransaction();

        try
        {
            $cliente = Cliente::find($id);
            if ($cliente == false) {
                return response()->json(['error' => 'Cliente no encontrado'], HttpStatusCode::NOT_FOUND);
            }

            $cliente->update([
                'name' => $datosValidados['name'],
                'email' => $datosValidados['email'],
                'celular' => $datosValidados['celular']
            ]);

            DB::commit();
            return $this->apiResponse->successResponse($cliente->fresh(), 'Cliente actualizado con éxito.', HttpStatusCode::OK);
        }
        catch (\Exception $e) { 
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el cliente: ' . $e->getMessage()], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try{
            $cliente = Cliente::find($id);
            if ($cliente == false) {
                return response()->json(['error' => 'Cliente no encontrado'], HttpStatusCode::NOT_FOUND);
            }

            $cliente->delete();

            DB::commit();

            return $this->apiResponse->successResponse(
                null,
                'Cliente eliminado exitosamente',
                HttpStatusCode::OK
            );
        }
        catch(\Exception $e){
            return response()->json(['error' => 'Error al eliminar el cliente: ' . $e->getMessage()], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}