<?php

namespace App\Http\Requests\PostBlog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBlog extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambia según tu lógica de permisos
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $blogId = $this->route('id'); 

        return [
            'producto_id' => 'required|integer|exists:productos,id',
            'titulo' => 'required|string|max:120',
            'link' => 'required|string|max:120|unique:blogs,link,' . $blogId,
            'parrafo' => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
            'imagen_principal' => 'required|file|image',  
            'titulo_blog' => 'required|string|max:80',
            'subtitulo_beneficio' => 'required|string|max:80',
            'url_video' => 'required|string|url',
            'titulo_video' => 'required|string|max:40',
            'imagenes' => 'required|array',
            'imagenes.*.imagen' => 'required_with:imagenes|file|image',
            'imagenes.*.parrafo' => 'required_with:imagenes|string|max:65535',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
