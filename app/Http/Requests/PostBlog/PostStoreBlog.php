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
            'titulo' => 'required|string|max:120',
            'parrafo' => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
            'imagen_principal' => 'required|file|image', 
            'titulo_blog' => 'required|string|max:80',
            'subtitulo_beneficio' => 'required|string|max:80',
            'url_video' => 'required|string|url',
            'titulo_video' => 'required|string|max:40',
            'imagenes.*.url_imagen' => 'required|file|image',
            'imagenes.*.parrafo_imagen' => 'required|string|max:65535',
        ];
    }   
}
