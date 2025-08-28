<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

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
        $permissions = Permission::all();
        return response()->json([
            'success' => true,
            'data' => $permissions,
            'message' => 'Permisos obtenidos exitosamente'
        ]);
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
        $request->validate([
            'name' => 'required|unique:permissions,name|max:255',
            'guard_name' => 'required|string|max:255',
        ]);

        $permission = Permission::create(['name' => $request->name, 'guard_name' => 'sanctum']);
        return response()->json([
            'success' => true,
            'data' => $permission,
            'message' => 'Permiso creado exitosamente'
        ], 201);

    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $permission,
            'message' => 'Permiso obtenido exitosamente'
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$id.'|max:255',
            'guard_name' => 'required|string|max:255',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return response()->json([
            'success' => true,
            'data' => $permission,
            'message' => 'Permiso actualizado exitosamente'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permiso eliminado exitosamente'
        ]);
    }

    public function assignPermissionToRole(Request $request){
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->syncPermissions($request->permissions);

        $role->load('permissions');

        return response()->json([
            'success' => true,
            'message' => 'Permisos asignados al rol exitosamente',
            'data' => [
                /* 'role' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                ],
                'permissions' => $role->permissions->map(function($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'guard_name' => $permission->guard_name,
                    ];
                }) */
               'role' => new RoleResource($role),
               'permissions' => PermissionResource::collection($role->permissions)
            ]
        ], 200);
    }

    public function removePermissionFromRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->revokePermissionTo($request->permissions);

        return response()->json([
            'success' => true,
            'message' => 'Permisos eliminados del rol exitosamente'
        ], 200);
    }

    public function getRolePermissions($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);

        /* $permissions = $role->permissions->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
            ];
        }); */

        return response()->json([
            'success' => true,
            'role_id' => $role->id,
            'role_name' => $role->name,
            //'permissions' => $permissions,
            'permissions' => PermissionResource::collection($role->permissions),
            'message' => 'Permisos del rol obtenidos exitosamente'
        ]);
    }

}
