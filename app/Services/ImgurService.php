<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
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
     * Sube una imagen a Imgur desde un archivo subido.
     *
     * @param UploadedFile $imageFile Archivo de imagen subido
     * @return string|null URL de la imagen en Imgur o null si falla
     * @throws \InvalidArgumentException Si no se recibe un UploadedFile
     */
    public function uploadImage(UploadedFile $imageFile)
    {
        try {
            if (!$imageFile->isValid() || !str_starts_with($imageFile->getMimeType(), 'image/')) {
                throw new \InvalidArgumentException('El archivo no es una imagen válida');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Client-ID ' . $this->clientId,
            ])->attach(
                'image',
                fopen($imageFile->getPathname(), 'r'),
                $imageFile->getClientOriginalName()
            )->post('https://api.imgur.com/3/image');

            if ($response->failed()) {
                Log::error("Error de Imgur: " . $response->body());
                throw new \Exception("Error al subir la imagen a Imgur");
            }

            return $response->json()['data']['link'] ?? null;
        } catch (\Exception $e) {
            Log::error("Imgur Upload Error: " . $e->getMessage());
            return null;
        }
    }
}
