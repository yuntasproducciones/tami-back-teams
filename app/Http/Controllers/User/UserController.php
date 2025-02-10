<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\PostUser\PostUser;
use App\Http\Requests\PostUser\PostUserUpdate;
use App\Traits\HttpResponseHelper;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(PostUser $request)
    {
        try{
            $userData = $request->all();
            $userData['password'] = Hash::make($request['password']);

            $user = User::create($userData);
    
            $user->assignRole('USER');
    
            return HttpResponseHelper::make()
                ->successfulResponse('Usuario creado correctamente')
                ->send();
    
        } catch (\Exception $e) {
            return HttpResponseHelper::make()
                ->internalErrorResponse('Ocurrio un problema al procesar la solicitud. ' . $e->getMessage())
                ->send();
        }
    }

    public function listUsers()
    {
        try {
            $userList = User::select('users.user_id','users.name','users.apellido','users.email',
            'users.usuario','roles.name as role')->join('model_has_roles', 
            'users.user_id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id',
             '=', 'roles.id')->get();

             return HttpResponseHelper::make()
             ->successfulResponse(
                 $userList->isEmpty() ? "No hay usuarios disponibles." : "Usuarios listados correctamente.", 
                 $userList
             )->send();

        } catch(\Exception $e) {
            return HttpResponseHelper::make()
            ->internalErrorResponse("Ocurrio un problema al listar los usuarios." . $e->getMessage())
            ->send();
        }
    }

    public function deleteUser($user)
    {
        try {
            $userId = User::findOrFail($user);
            $userId->delete();

            return HttpResponseHelper::make()
            ->successfulResponse('Usuario eliminado.')
            ->send();

        } catch(\Exception $e) {
            return HttpResponseHelper::make()
            ->internalErrorResponse("Ocurrio un problema con la eliminacion del usuario." . $e->getMessage())
            ->send();
        }
    }

    public function updateUser(PostUserUpdate $request, User $user)
    {
        try {
            $user->update($request->all());

            return HttpResponseHelper::make()
                ->successfulResponse(
                    $user->wasChanged() 
                        ? "Usuario actualizado correctamente." 
                        : "No hubo cambios en los datos del usuario."
                )
                ->send();

        } catch (\Exception $e) {
            return HttpResponseHelper::make()
                ->internalErrorResponse("Ocurrió un problema al actualizar al usuario. " . $e->getMessage())
                ->send();
        }
    }

}
