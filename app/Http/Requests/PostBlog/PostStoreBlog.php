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
    public function rules(): array
    {
        return [
            //Blog
            'titulo' => 'required|string|max:120',
            'parrafo' => 'required|string|max:100',
            'imagen_principal' => 'required|string|max:255',

            //Imagenes
            'imagenes' => 'array',
                'imagenes.*.url_imagen' => 'required|string|max:255',
                'imagenes.*.parrafo_imagen' => 'required|string|max:40',

            //Detalle del Blog
            'titulo_blog' => 'required|string|max:40',
            'subtitulo_beneficio' => 'required|string|max:70',

            //Video
            'url_video' => 'required|string|max:255',
            'titulo_video' => 'required|string|max:40',
        ];
    }
}
