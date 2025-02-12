<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\BasicController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostAuth\PostAuth;

use App\Http\Contains\HttpStatusCode;

class AuthController extends BasicController
{
    public function login(PostAuth $request)
    {
        try {
            
            if (!Auth::attempt($request->all())) {
                return $this->unauthorizedResponse('Las credenciales proporcionadas no son correctas.');
            }            

            $user = User::Where('email', $request->email)->firstOrFail();
    
            $token = $user->createToken('token')->plainTextToken;
    
            return $this->successResponse([
                'token' => $token,
                'user' => $user
            ], 'Inicio de sesión exitoso.', HttpStatusCode::OK);
            
        } catch (\Exception $e) {
            return $this->errorResponse('Hubo un problema al procesar la solicitud.',
             HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(PostAuth $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->successResponse(null, 'Cierre de sesión exitoso.',
            HttpStatusCode::OK);

        } catch (\Exception $e) {
            return $this->errorResponse('Hubo un problema al procesar la solicitud. 
            Por favor, intente nuevamente.', HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

}
