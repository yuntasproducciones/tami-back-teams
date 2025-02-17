<?php

namespace App\Http\Controllers\Api\V1\UsuariosRegistro;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostUsuarios_Registro\PostUsuarios_Registro;
use App\Models\Usuarios_Registro;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BasicController;
use App\Http\Contains\HttpStatusCode;

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

    public function update(Request $request, Usuarios_Registro $usuarios_Registro)
    {
        
    }

    public function destroy(Usuarios_Registro $usuarios_Registro)
    {
        
    }
}
