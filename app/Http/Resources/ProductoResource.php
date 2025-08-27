<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'nombre' => $this->nombre,
            'link' => $this->link,
            'subtitulo' => $this->subtitulo,
            'stock' => $this->stock,
            'precio' => $this->precio,
            'seccion' => $this->seccion,
            'descripcion' => $this->descripcion,
            'especificaciones' => $this->especificaciones ?? [],
            'dimensiones' => $this->dimensiones ? [
                'alto' => $this->dimensiones->alto,
                'largo' => $this->dimensiones->largo,
                'ancho' => $this->dimensiones->ancho,
            ] : null,
            'imagenes' => ImagenResource::collection($this->imagenes),
            'productos_relacionados' => ProductoRelacionadoResource::collection($this->productosRelacionados),
            'etiqueta' => $this->etiqueta ? [
                'meta_titulo' => $this->etiqueta->meta_titulo,
                'meta_descripcion' => $this->etiqueta->meta_descripcion,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
