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
        $isPut = $this->isMethod('put');
        $required = $isPut ? 'required' : 'sometimes';
        $productoId = $this->route('id');

        return [
            'nombre' => [$required, 'string', 'max:255', Rule::unique('productos', 'nombre')->ignore($productoId)],
            'link' => [$required, 'string', 'max:255', Rule::unique('productos', 'link')->ignore($productoId)],
            'titulo' => [$required, 'string', 'max:255'],
            'subtitulo' => [$required, 'string', 'max:255'],
            'stock' => [$required, 'integer', 'max:1000', 'min:0'],
            'precio' => [$required, 'numeric', 'min:0'],
            'seccion' => [$required, 'string', 'max:255'],
            'descripcion' => [$required, 'string', 'max:65535'],
            'meta_data' => [$required, 'array'],
            'meta_data.meta_titulo' => ['sometimes', 'string', 'max:65535'],
            'meta_data.meta_descripcion' => ['sometimes', 'string', 'max:65535'],
            'especificaciones' => [$required, 'string', 'max:65535'],

            'imagenes' => ['sometimes', 'array'],
            'imagenes.*' => ['file', 'image', 'max:2048'],
            'textos_alt' => ['sometimes', 'array'],
            'textos_alt.*' => ['string', 'max:255'],
            'relacionados' => ['sometimes', 'array'],
            'relacionados.*' => ['integer', 'exists:productos,id'],
        ];
    }
}
