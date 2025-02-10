<?php

namespace App\Http\Requests\PostProductsDetails;

use Illuminate\Foundation\Http\FormRequest;

class PostDetalles_Productos extends FormRequest
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
            'prod_id' => 'integer|exists:productos,prod_id',
            'titulo' => 'required|string|max:20',
            'subtitulo' => 'required|string|max:100',
            'descripcion' => 'required|string|max:400',
            'longitud' => 'required|numeric|between:0,99999999.99',
            'ancho' => 'required|numeric|between:0,99999999.99',
            'altura' => 'required|numeric|between:0,99999999.99',
        ];
    }
}
