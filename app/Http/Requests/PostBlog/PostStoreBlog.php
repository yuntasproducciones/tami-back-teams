<?php

namespace App\Http\Requests\PostBlog;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreBlog extends FormRequest
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
    public function rules()
    {
        return [
            'titulo' => 'required|string|max:255',
            'parrafo' => 'required|string',
            'descripcion' => 'required|string',
            'imagen_principal' => 'required|file|image', 
            'titulo_blog' => 'required|string',
            'subtitulo_beneficio' => 'required|string',
            'url_video' => 'required|string|url',
            'titulo_video' => 'required|string',
            'imagenes.*.url_imagen' => 'required|file|image',
            'imagenes.*.parrafo_imagen' => 'required|string',
        ];
    }   
}
