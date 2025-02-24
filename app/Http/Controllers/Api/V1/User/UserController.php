<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\PostUser\PostUser;
use App\Http\Requests\PostUser\PostUserUpdate;
use App\Traits\HttpResponseHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use HttpResponseHelper;
    public function register(PostUser $request)

    {
        try {
            DB::beginTransaction();
            
            $userData = $request->validated();
            $userData['password'] = Hash::make($request['password']);

            $user = User::create($userData);
            $user->assignRole('USER');
            
            DB::commit();

            return $this->successfulResponse('Usuario creado correctamente', $user)->send();

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->internalErrorResponse('Error al crear usuario: ' . $e->getMessage())->send();
        }
    }

    public function listUsers()
    {
        try {
            $userList = User::select('users.user_id', 'users.name', 
                'users.email', 'users.celular', 'users.fecha', 
                'roles.name as role')
                ->join('model_has_roles', 'users.user_id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->get();

            return $this->successfulResponse(
                $userList->isEmpty() ? "No hay usuarios disponibles." : "Usuarios listados correctamente.",
                $userList
            )->send();

        } catch(\Exception $e) {
            return $this->internalErrorResponse("Ocurrió un problema al listar los usuarios." . $e->getMessage())->send();
        }
    }

    public function deleteUser($user)
    {
        try {
            $userId = User::findOrFail($user);
            $userId->delete();

            return $this->successfulResponse('Usuario eliminado.')->send();

        } catch(\Exception $e) {
            return $this->internalErrorResponse("Ocurrió un problema con la eliminación del usuario." . $e->getMessage())->send();
        }
    }

    public function updateUser(PostUserUpdate $request, User $user)
    {
        try {
            $user->update($request->validated());

            return $this->successfulResponse(
                $user->wasChanged() 
                    ? "Usuario actualizado correctamente." 
                    : "No hubo cambios en los datos del usuario."
            )->send();

        } catch (\Exception $e) {
            return $this->internalErrorResponse("Ocurrió un problema al actualizar al usuario. " . $e->getMessage())->send();
        }
    }
}
