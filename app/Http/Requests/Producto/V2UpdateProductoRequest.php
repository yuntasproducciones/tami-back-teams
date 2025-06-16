<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // ¡IMPORTA ESTA CLASE!

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
        // Obtén el ID del producto que se está actualizando.
        // En tu controlador `update(V2UpdateProductoRequest $request, string $id)`,
        // el ID se pasa como el segundo parámetro `$id`.
        // Dentro del Form Request, puedes acceder a él vía $this->route('id').
        $productoId = $this->route('id');

        return [
            // Regla para 'nombre': debe ser único en la tabla 'productos',
            // PERO ignora el registro cuyo ID es $productoId (el que estamos actualizando).
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('productos', 'nombre')->ignore($productoId),
            ],
            // Regla para 'link': debe ser único en la tabla 'productos',
            // PERO ignora el registro cuyo ID es $productoId.
            'link' => [
                'required',
                'string',
                'max:255',
                Rule::unique('productos', 'link')->ignore($productoId),
            ],
            'titulo' => "required|string|max:255",
            'subtitulo' => "required|string|max:255",
            'stock' => "required|integer|max:1000|min:0",
            'precio' => "required|string|max:100000|min:0", // Considera cambiar a 'numeric' si es un valor decimal
            'seccion' => "required|string|max:255",
            'lema' => "required|string|max:255",
            'descripcion' => "required|string|max:65535",
            'especificaciones' => "required|string|max:65535",
            'imagenes' => "required|array|min:1|max:10",
            'imagenes.*' => "file|image|max:2048",
            'textos_alt' => "required|array|min:1|max:10",
            'textos_alt.*' => "string|max:255",
            'relacionados' => "sometimes|array",
            'relacionados.*' => "integer|exists:productos,id",
        ];
    }
}