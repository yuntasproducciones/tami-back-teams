<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePermissionRequest;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    /**
     * Obtener listado de permisos
     * 
     * @OA\Get(
     *     path="/api/v1/permisos",
     *     summary="Muestra un listado de todos los permisos",
     *     description="Retorna un array con todos los permisos",
     *     operationId="indexPermisos",
     *     tags={"Roles y Permisos"},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titulo", type="string", example="Producto Premium"),
     *                     @OA\Property(property="parrafo", type="string", example="La mejor calidad"),
     *                     @OA\Property(property="descripcion", type="string", example="Un producto elaborado por los mejores especialistas del país."),
     *                     @OA\Property(property="imagenPrincipal", type="string", example="https://example.com/imagen.jpg"),
     *                     @OA\Property(property="tituloBlog", type="string", example="Título del Blog"),
     *                     @OA\Property(property="subTituloBlog", type="string", example="Subtítulo del Blog"),
     *                     @OA\Property(
     *                         property="imagenesBlog",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="url", type="string", example="https://example.com/imagen1.jpg"),
     *                             @OA\Property(property="parrafo", type="string", example="Descripción de la imagen")
     *                         )
     *                     ),
     *                     @OA\Property(property="video_id", type="string", example="CSqLojbONfw"),
     *                     @OA\Property(property="videoBlog", type="string", example="https://youtu.be/CSqLojbONfw"),
     *                     @OA\Property(property="tituloVideoBlog", type="string", example="Título del video"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-01T14:30:00Z")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Permisos obtenidos exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function index()
    {
        //
        return Permission::paginate(10);
    }

    /**
     * Crear un nuevo permiso
     * 
     * @OA\Post(
     *     path="/api/v1/permisos",
     *     summary="Crear un nuevo permiso",
     *     description="Almacena un nuevo permiso",
     *     operationId="storePermiso",
     *     tags={"Roles y Permisos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "nombre", 
     *                 },
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string",
     *                     example="CREAR BLOGS"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permiso creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Permiso creado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function store(StorePermissionRequest $request)
    {
        //
        $datosValidados = $request->validated();
        $permiso = new Permission();
        $permiso->name = $datosValidados["nombre"];
        $permiso->guard_name = "sanctum";
        $permiso->save();
        $respuesta = new Response(json_encode(["message"=>"Permiso creado exitosamente"]), 201);
        $respuesta->withHeaders(["Content-Type"=>"application/json", "Accept"=>"application/json"]);
        return $respuesta;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $datosValidados = $request->validated();
        $permiso = new Permission();
        $permiso->name = $datosValidados["nombre"];
        $permiso->guard_name = "sanctum";
        $permiso->save();
        $respuesta = new Response(json_encode(["message"=>"Permiso creado exitosamente"]), 201);
        $respuesta->withHeaders(["Content-Type"=>"application/json", "Accept"=>"application/json"]);
        return $respuesta;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
