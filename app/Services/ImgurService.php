<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImgurService
{
    protected $clientId;

    public function __construct()
    {
        $this->clientId = config('services.imgur.client_id');
    }

    /**
     * Sube una imagen a Imgur y devuelve la URL de la imagen.
     *
     * @param string $imagePath Ruta del archivo de imagen a subir.
     * @return string|null URL de la imagen en Imgur o null si falla la subida.
     */
    public function uploadImage($imagePath)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Client-ID ' . $this->clientId,
            ])->attach(
                'image', fopen($imagePath, 'r')
            )->post('https://api.imgur.com/3/image');

            $result = $response->json();

            if ($response->successful() && isset($result['data']['link'])) {
                return $result['data']['link'];
            } else {
                Log::error('Error al subir imagen a Imgur: ' . json_encode($result));
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Excepción al subir imagen a Imgur: ' . $e->getMessage());
            return null;
        }
    }
}
