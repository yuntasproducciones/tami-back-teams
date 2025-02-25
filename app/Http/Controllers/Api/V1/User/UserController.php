<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\PostUser\PostUser;
use App\Http\Requests\PostUser\PostUserUpdate;
use App\Http\Controllers\Api\V1\BasicController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends BasicController
{
    public function store(PostUser $request)
    {
        try {
            DB::beginTransaction();
            
            $userData = $request->validated();
            $userData['password'] = Hash::make($request['password']);

            $user = User::create($userData);
            $user->assignRole('USER');
            
            DB::commit();

            return $this->successResponse($user, 'Usuario creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->internalServerErrorResponse('Error al crear usuario: ' . $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $userList = User::select('users.user_id', 'users.name', 
                'users.email', 'users.celular', 'users.fecha', 
                'roles.name as role')
                ->join('model_has_roles', 'users.user_id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->get();

            $message = $userList->isEmpty() ? "No hay usuarios disponibles." : "Usuarios listados correctamente.";
            return $this->successResponse($userList, $message);

        } catch(\Exception $e) {
            return $this->internalServerErrorResponse("Ocurrió un problema al listar los usuarios: " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return $this->successResponse($user, 'Usuario eliminado correctamente.');

        } catch(\Exception $e) {
            return $this->internalServerErrorResponse("Ocurrió un problema con la eliminación del usuario: " . $e->getMessage());
        }
    }

    public function update(PostUserUpdate $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->validated());

            $message = $user->wasChanged() 
                ? "Usuario actualizado correctamente." 
                : "No hubo cambios en los datos del usuario.";
                
            return $this->successResponse($user, $message);

        } catch (\Exception $e) {
            return $this->internalServerErrorResponse("Ocurrió un problema al actualizar al usuario: " . $e->getMessage());
        }
    }
}
