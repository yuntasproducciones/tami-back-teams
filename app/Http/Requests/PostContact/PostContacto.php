<?php

namespace App\Http\Requests\PostContact;

use Illuminate\Foundation\Http\FormRequest;

class PostContacto extends FormRequest
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
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|regex:/^9\d{8}$/',
            'email' => 'required|email|max:100',
            'seccion' => 'required|string|max:100', 
            'fecha_creacion' => 'date'
        ];
    }
}
