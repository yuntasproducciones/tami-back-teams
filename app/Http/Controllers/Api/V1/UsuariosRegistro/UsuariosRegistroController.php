<?php

namespace App\Http\Controllers\Api\V1\UsuariosRegistro;

use App\Http\Requests\PostUsuarios_Registro\PostUsuarios_Registro;
use App\Models\Usuarios_Registro;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BasicController;
use App\Http\Contains\HttpStatusCode;
use App\Http\Requests\PostUsuarios_Registro\PostUpdateUsuarios;

class UsuariosRegistroController extends BasicController
{
    public function registroUsuarios(PostUsuarios_Registro $request)
    {
        try {
            Usuarios_Registro::create($request->all());

            return $this->successResponse(null
            , 'Usuario registrado exitosamente.', HttpStatusCode::OK);
            
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrio un problema al procesar la solicitud. '. $e->getMessage()
            , HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function showRegistroUsuarios()
    {
        try {
            $userRegister = Usuarios_Registro::all();

            return $this->successResponse(
                [$userRegister->isEmpty() ? 'No hay usuarios para listar.' : 
                'Usuarios listados correctamente.' => $userRegister, HttpStatusCode::OK]
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrio un problema al listar los usuarios. ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateRegistroUser(PostUpdateUsuarios $request, Usuarios_Registro $usuarios_Registro)
    {
        try {
            $usuarios_Registro->update($request->all());

            $message = $usuarios_Registro->wasChanged() 
                ? 'Se actualizaron los campos correctamente.' 
                : 'No se actualizaron los campos';

            $statusCode = $usuarios_Registro->wasChanged() 
                ? HttpStatusCode::OK 
                : HttpStatusCode::NO_CONTENT;

            return $this->successResponse(null, $message, $statusCode);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function destroyRegistroUser(Usuarios_Registro $usuarios_Registro)
    {
        try {
            $usuarios_Registro->delete();

            return $this->successResponse(null, 'Se elimino correctamente el usuario.', 
            HttpStatusCode::OK);
        } catch(\Exception $e) {
            return $this->errorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
