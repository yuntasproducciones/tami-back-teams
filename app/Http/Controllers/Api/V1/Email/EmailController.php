<?php

namespace App\Http\Controllers\Api\V1\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Contains\HttpStatusCode;
use App\Mail\ProductInfoMail;
use App\Mail\SimpleMail;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Email",
 *     description="API Endpoints de Email"
 * )
 */

class EmailController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/v1/emails",
     *     summary="Enviar correo electrónico",
     *     description="Envía un correo electrónico con asunto y mensaje a una dirección especificada",
     *     operationId="sendEmail",
     *     tags={"Email"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"to", "subject", "message"},
     *             @OA\Property(property="destinatario", type="string", format="email", example="destinatario@gmail.com", description="Correo del destinatario"),
     *             @OA\Property(property="asunto", type="string", example="Asunto del correo", description="Asunto del correo"),
     *             @OA\Property(property="mensaje", type="string", example="Este es el contenido del correo", description="Contenido del mensaje")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Correo enviado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Correo enviado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación de datos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "to": {"The to field is required."},
     *                     "subject": {"The subject field is required."},
     *                     "message": {"The message field is required."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al enviar el correo")
     *         )
     *     )
     * )
     */

    public function sendEmail(Request $request)
    {
        $request->validate([
            'destinatario' => 'required|email',
            'asunto' => 'required|string',
            'mensaje' => 'required|string',
        ]);

        try {
            Mail::to($request->destinatario)->send(new SimpleMail($request->only('asunto', 'mensaje')));
            return response()->json(['message' => 'Correo enviado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sendEmailByProductLink(Request $request)
    {
        $views = [
            // Decoración
            'mesa-led-bar-alta'           => 'emails.decoracion.mesa-led-bar-alta-circular',
            'mesa-led-bar-alta-cuadrada'  => 'emails.decoracion.mesa-led-bar',
            'silla-bar-led-alta'          => 'emails.decoracion.silla-led-bar-taburete',
            'sillas-cuadradas-o-de-cubo'  => 'emails.decoracion.sillas-cuadradas-cubo-led',

            // Maquinaria
            'maquina-de-embalaje-de-te'       => 'emails.maquinaria.maquina-embalaje-te',
            'maquina-selladora-de-bolsas-para-liquidos' => 'emails.maquinaria.maquina-selladora-bolsas',
            'maquina-selladora-de-bolsas-para-solidos'  => 'emails.maquinaria.maquina-selladora-solidos',
            'selladora-de-vaso-manual'        => 'emails.maquinaria.maquina-selladora-vasos',

            // Negocios
            'selladora-por-induccion'     => 'emails.negocios.maquina-sellado-por-induccion',
            'purificador-de-agua'         => 'emails.negocios.purificador-de-agua',
            'soldadora-lingba'            => 'emails.negocios.soldadora-lingba',
            'soldadora-spark-mig-250'     => 'emails.negocios.soldadora-spark',
            'ventilador-holografico'      => 'emails.negocios.ventilador-holografico',
        ];

        $link = $request->link;

        if (!isset($views[$link])) {
            abort(404, "No hay plantilla de email para el producto {$link}");
        }

        try {
            $view = $views[$link];

            Mail::to($request->email)->send(
                new ProductInfoMail($request->only('name'), $view)
            );

            return response()->json([
                'status'  => 'success',
                'message' => 'Correo enviado correctamente'
            ], 200);

        } catch (Exception $e) {
            // Log interno para que no pase desapercibido
            Log::error('Error al enviar correo de producto', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $request->email,
                'link'  => $link,
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Hubo un problema al enviar el correo. Intente más tarde.'
            ], 500);
        }
    }

}
