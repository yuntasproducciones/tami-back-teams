<?php

namespace App\Http\Controllers\Api\V1\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{

    public function index()
    {
        return Cliente::paginate(10);
    }

    public function store(Request $request)
    {
        //
        $request->validate([
            "nombre" => "required",
            "correo" => "required|unique:App\Models\Cliente,email",
            "celular" => "required|unique:App\Models\Cliente,celular",
        ]);
        $cliente = new Cliente();
        $cliente->name = $request->input("nombre");
        $cliente->email = $request->input("correo");
        $cliente->celular = $request->input("celular");
        $cliente->save();

        $mensaje = ["message" => "Cliente creado exitosamente"];
        return new Response(json_encode($mensaje), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $cliente = Cliente::find($id);
        if ($cliente == false) {
            $mensaje = ["message" => "El cliente no existe"];
            return new Response(json_encode($mensaje), 404);
        }
        return $cliente;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $cliente = Cliente::find($id);
        if ($cliente == false) {
            $mensaje = ["message" => "El cliente no existe"];
            return new Response(json_encode($mensaje), 404);
        }
        $request->validate([
            "nombre" => "required",
            "correo" => [
                "required",
                "email:rfc,strict,filter",
                Rule::unique('clientes', 'email')->ignore($cliente->id)
            ],
            "celular" => [
                "required",
                Rule::unique('clientes', 'celular')->ignore($cliente->id),
            ],
        ]);
        $cliente->name = $request->input("nombre");
        $cliente->email = $request->input("correo");
        $cliente->celular = $request->input("celular");
        $cliente->save();

        $mensaje = ["message" => "Cliente actualizado exitosamente"];
        return new Response(json_encode($mensaje), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $cliente = Cliente::find($id);
        if ($cliente == false) {
            $mensaje = ["message" => "El cliente no existe"];
            return new Response(json_encode($mensaje), 404);
        }
        $cliente->delete();
        $mensaje = ["message" => "Cliente eliminado exitosamente"];
        return new Response(json_encode($mensaje), 200);
    }
}