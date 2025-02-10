<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostAuth\PostAuth;
use App\Traits\HttpResponseHelper;

class AuthController extends Controller
{
    public function login(PostAuth $request)
    {
        try {
            if (!Auth::attempt($request->all())) {
                return HttpResponseHelper::make()
                    ->internalErrorResponse('Las credenciales proporcionadas no son correctas.')
                    ->send();
            }

            $user = User::Where('email', $request->email)->firstOrFail();
    
            $token = $user->createToken('token')->plainTextToken;
    
            return HttpResponseHelper::make()
                ->successfulResponse('Inicio de sesion exitoso.', [
                    'token' => $token,
                    'user' => $user])
                ->send();
    
        } catch (\Exception $e) {
            return HttpResponseHelper::make()
                ->internalErrorResponse('Hubo un problema al procesar la solicitud. Por favor, intente nuevamente.')
                ->send();
        }
    }

    public function logout(PostAuth $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return HttpResponseHelper::make()
                ->successfulResponse('Cierre de sesion exitoso.')
                ->send();
        } catch (\Exception $e) {
            return HttpResponseHelper::make()
                ->internalErrorResponse('Hubo un problema al procesar la solicitud. Por favor, intente nuevamente.')
                ->send();
        }
    }
}
