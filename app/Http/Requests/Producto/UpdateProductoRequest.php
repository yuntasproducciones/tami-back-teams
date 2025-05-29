<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
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
            'nombre' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255|unique:productos,link,' . $this->route('id'),
            'titulo' => 'nullable|string|max:255',
            'subtitulo' => 'nullable|string|max:255',
            'lema' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'imagen_principal' => 'required|file|image',
            'precio' => 'nullable|numeric|min:0.01|max:99999999.99',
            'stock' => 'nullable|integer|min:0',
            'especificaciones' => 'nullable|array',
            'dimensiones' => 'nullable|array',
            'imagenes.*.url_imagen' => 'nullable|file|image',
            'imagenes' => 'array',
            'relacionados' => 'nullable|array',
            'relacionados.*' => 'nullable|integer|exists:productos,id',
            'seccion' => 'nullable|string',
            'mensaje_correo' => 'nullable|string|max:500',
        ];
    }
}
