<?php

namespace App\Repositories\V1\Auth;

use App\Http\Contains\HttpStatusCode;
use App\Models\User;
use App\Repositories\V1\Contracts\AuthRepositoryInterface;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    protected ApiResponseService $apiResponse;

    public function __construct(ApiResponseService $apiResponse) {
        $this->apiResponse = $apiResponse;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Iniciar sesión y establecer sesión de usuario en Sanctum",
     *     tags={"Autenticación"},
     *     description="Este endpoint permite a los usuarios autenticarse utilizando Sanctum basado en sesiones. 
     *                  Asegúrate de obtener el token CSRF desde `/sanctum/csrf-cookie` antes de hacer esta solicitud.
     *                  La autenticación se maneja a través de cookies de sesión, no de tokens.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autenticación exitosa. La sesión se gestiona mediante cookies.",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", description="Información del usuario autenticado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas"
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Falta el token CSRF. Asegúrate de llamar primero a `/sanctum/csrf-cookie`."
     *     )
     * )
     */
    public function getLogin(array $data)
    {
        try {
            if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                return $this->apiResponse->unauthorizedResponse('Las credenciales proporcionadas no son correctas.');
            }            

            $user = Auth::user();

            return $this->apiResponse->successResponse([
                'user' => $user,
            ], 'Inicio de sesión exitoso', HttpStatusCode::OK);

        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse(
                'Hubo un problema al procesar la solicitud: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Cerrar sesión y destruir la sesión del usuario",
     *     tags={"Autenticación"},
     *     security={{"cookieAuth":{}}}, 
     *     @OA\Response(
     *         response=200,
     *         description="Sesión finalizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cierre de sesión exitoso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No autorizado para cerrar sesión")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al cerrar sesión")
     *         )
     *     )
     * )
     */
    public function getLogout()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->apiResponse->errorResponse(
                    'No tienes permiso para cerrar sesión.',
                    HttpStatusCode::UNAUTHORIZED
                );
            }

            // Cerrar sesión eliminando la sesión del usuario
            Auth::guard('web')->logout();

            // Invalidar la sesión actual
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return $this->apiResponse->successResponse(
                [],
                'Cierre de sesión exitoso',
                HttpStatusCode::OK
            );

        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse(
                'Error al cerrar sesión: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }
}
