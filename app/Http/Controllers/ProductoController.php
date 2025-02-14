<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with(['especificaciones', 'dimensiones', 'imagenes', 'productosRelacionados'])->get();

        $formattedProductos = $productos->map(function ($producto) {
            return [
                'id' => $producto->id,
                'title' => $producto->titulo,
                'subtitle' => $producto->subtitulo,
                'tagline' => $producto->lema,
                'description' => $producto->descripcion,
                'specs' => $producto->especificaciones->pluck('valor', 'clave'),
                'dimensions' => $producto->dimensiones->pluck('valor', 'tipo'),
                'relatedProducts' => $producto->productosRelacionados->pluck('id'),
                'images' => $producto->imagenes->pluck('url_imagen'),
                'image' => $producto->imagen_principal,
                'nombreProducto' => $producto->nombre,
                'stockProducto' => $producto->stock,
                'precioProducto' => $producto->precio,
                'seccion' => $producto->seccion,
            ];
        });

        return response()->json($formattedProductos);
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
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::with(['especificaciones', 'dimensiones', 'imagenes', 'productosRelacionados'])->findOrFail($id);

        $formattedProducto = [
            'id' => $producto->id,
            'title' => $producto->titulo,
            'subtitle' => $producto->subtitulo,
            'tagline' => $producto->lema,
            'description' => $producto->descripcion,
            'specs' => $producto->especificaciones->pluck('valor', 'clave'),
            'dimensions' => $producto->dimensiones->pluck('valor', 'tipo'),
            'relatedProducts' => $producto->productosRelacionados->pluck('id'),
            'images' => $producto->imagenes->pluck('url_imagen'),
            'image' => $producto->imagen_principal,
            'nombreProducto' => $producto->nombre,
            'stockProducto' => $producto->stock,
            'precioProducto' => $producto->precio,
            'seccion' => $producto->seccion,
        ];

        return response()->json($formattedProducto);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
