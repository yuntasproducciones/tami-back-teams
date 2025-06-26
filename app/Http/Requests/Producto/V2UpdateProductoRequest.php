<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class V2UpdateProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productoId = $this->route('id');

        return [
            //
            'nombre' => ['string','max:255',Rule::unique('productos', 'nombre')->ignore($productoId)],
            'link' => ['string','max:255',Rule::unique('productos', 'link')->ignore($productoId)],
            'titulo' => "string|max:255",
            'subtitulo' => "string|max:255",
            'stock' => "integer|max:1000|min:0",
            'precio' => "string|max:100000|min:0", // Considera cambiar a 'numeric' si es un valor decimal
            'seccion' => "string|max:255",
            'lema' => "string|max:255",
            'descripcion' => "string|max:65535",
            'meta_data' => 'array',
            'meta_data.meta_titulo' => 'string|max:255',
            'meta_data.meta_descripcion' => 'string|max:65535',
            'especificaciones' => "string|max:65535",
            'imagenes' => "array|min:1|max:10",
            'imagenes.*' => "file|image|max:2048",
            'textos_alt' => "array|min:1|max:10",
            'textos_alt.*' => "string|max:255",
            'relacionados' => "sometimes|array",
            'relacionados.*' => "integer|exists:productos,id",
        ];
    }
}