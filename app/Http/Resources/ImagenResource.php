<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImagenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'url_imagen' => $this->url_imagen,
            'texto_alt_SEO' => $this->texto_alt_SEO,
        ];
    }
}
