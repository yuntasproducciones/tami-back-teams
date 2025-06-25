<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class V2StoreProductoRequest extends FormRequest
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
        return [
            //
            'titulo' => "string|max:255",
            'nombre' => "string|max:255|unique:productos,nombre",
            'link' => 'string|unique:productos,link|max:255',
            'subtitulo' => "string|max:255",
            'stock' => "integer|max:1000|min:0",
            'precio' => "string|max:100000|min:0",
            'seccion' => "string|max:255",
            'lema' => "string|max:255",
            'descripcion' => "string|max:65535",
            'meta_data' => 'required|array',
            'meta_data.meta_titulo' => 'required|string|max:255',
            'meta_data.meta_descripcion' => 'required|string|max:65535',
            'especificaciones' => "string|max:65535",
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
