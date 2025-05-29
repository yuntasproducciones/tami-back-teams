<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreProductoRequest extends FormRequest
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
            //En el front stock y precio se mandan como string por la clase FormData
            //Quiero aclarar que no importa que se mande un string desde el front
            // aquí se valida que sea un integer (1, 2, 3, 4) o numeric (299.99, 1.50)
            'nombre' => 'required|string|max:255',
            'link' => 'nullable|string|max:255|unique:productos,link',
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'required|string|max:255',
            'lema' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'imagen_principal' => 'required|file|image',
            'precio' => 'required|numeric|min:0.01|max:99999999.99',
            'stock' => 'required|integer|min:0',
            'especificaciones' => 'array',
            'dimensiones' => 'array',
            //Valida que las en el ARRAY llamado imagenes, todos los elementos tengan una key llamada url_imagen
            //y que sea un archivo de tipo imagen (file|image)
            'imagenes.*.url_imagen' => 'required|file|image',
            //Valida que relacionados sea un ARRAY
            'relacionados' => 'array',
            //Valida que todos los elementos del array sean del tipo integer
            'relacionados.*' => 'integer|exists:productos,id',
            'seccion' => 'required|string',
            'mensaje_correo' => 'nullable|string|max:500',
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
