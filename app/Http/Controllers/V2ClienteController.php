<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class V2ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Cliente::paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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