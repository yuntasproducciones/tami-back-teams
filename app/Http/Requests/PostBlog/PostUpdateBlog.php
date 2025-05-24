<?php

namespace App\Http\Requests\PostBlog;

use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\Constraint\IsTrue;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
class PostUpdateBlog extends FormRequest
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
            'producto_id' => 'required|integer|exists:productos,id',
            'titulo' => 'required|string|max:120',
            'parrafo' => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
            'imagen_principal' => 'sometimes|file|image',  // No requerido en update
            'titulo_blog' => 'required|string|max:80',
            'subtitulo_beneficio' => 'required|string|max:80',
            'url_video' => 'required|string|url',
            'titulo_video' => 'required|string|max:40',
            'imagenes.*.url_imagen' => 'sometimes|file|image', // Opcional en update
            'imagenes.*.parrafo_imagen' => 'sometimes|string|max:65535',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
