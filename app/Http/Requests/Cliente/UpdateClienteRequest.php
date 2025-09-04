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
        $isPut = $this->isMethod('put');
        $required = $isPut ? 'required' : 'sometimes';
        $clienteId = $this->route('id');

        return [
            'name' => [$required,'string','max:100'],
            'email' => [$required, 'email', 'unique:clientes,email,' . $clienteId . 'max:100'],
            'celular' => [$required, 'regex:/^[0-9]{9}$/']
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'El nombre no debe exceder los 100 caracteres.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'email.max' => 'El correo no debe exceder los 100 caracteres.',
            'celular.regex' => 'El celular debe tener exactamente 9 dígitos numéricos.'
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
