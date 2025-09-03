<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:clientes,email,' . $this->route('id') . '|max:100',
            'celular' => 'sometimes|regex:/^\+\d{1,3}\s?\d{1,15}(?:[-\s]?\d+)*$/'
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'El nombre no debe exceder los 100 caracteres.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'email.max' => 'El correo no debe exceder los 100 caracteres.',
            // 'celular.regex' => 'El celular debe tener exactamente 9 dígitos numéricos.'
            'celular.regex' => 'El formato del teléfono no es válido. Ejemplo: +51 999-999-999',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }
}
