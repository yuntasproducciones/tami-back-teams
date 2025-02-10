<?php

namespace App\Http\Requests\PostProducts;

use Illuminate\Foundation\Http\FormRequest;

class PostProductos extends FormRequest
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
            'imagen_principal' => 'required|string',
            'imagen_sec_1' => 'required|string',
            'imagen_sec_2' => 'required|string',
            'imagen_sec_3' => 'required|string',
            'descripcion' => 'required|string|max:150',
            'cat_id' => 'required|numeric|exists:categorias,cat_id'
        ];
    }
}
