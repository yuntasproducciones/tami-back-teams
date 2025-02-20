<?php

namespace App\Http\Controllers\Api\V1\Products;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
        $request->validate([
            'nombre' => 'required|string',
            'titulo' => 'required|string',
            'imagen_principal' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'especificaciones' => 'array',
            'dimensiones' => 'array',
            'imagenes' => 'array',
            'relacionados' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $producto = Producto::create([
                'nombre' => $request->nombre,
                'titulo' => $request->titulo,
                'subtitulo' => $request->subtitulo,
                'lema' => $request->lema,
                'descripcion' => $request->descripcion,
                'imagen_principal' => $request->imagen_principal,
                'stock' => $request->stock,
                'precio' => $request->precio,
                'seccion' => $request->seccion
            ]);

            if ($request->has('especificaciones')) {
                foreach ($request->especificaciones as $clave => $valor) {
                    $producto->especificaciones()->create([
                        'clave' => $clave,
                        'valor' => $valor
                    ]);
                }
            }

            if ($request->has('dimensiones')) {
                foreach ($request->dimensiones as $tipo => $valor) {
                    $producto->dimensiones()->create([
                        'tipo' => $tipo,
                        'valor' => $valor
                    ]);
                }
            }

            if ($request->has('imagenes')) {
                foreach ($request->imagenes as $url) {
                    $producto->imagenes()->create([
                        'url_imagen' => $url
                    ]);
                }
            }

            if ($request->has('relacionados')) {
                foreach ($request->relacionados as $idRelacionado) {
                    $producto->productosRelacionados()->attach($idRelacionado);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Producto creado exitosamente', 'producto' => $producto], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el producto: ' . $e->getMessage()], 500);
        }
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
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string',
            'titulo' => 'required|string',
            'imagen_principal' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $producto->update([
                'nombre' => $request->nombre,
                'titulo' => $request->titulo,
                'subtitulo' => $request->subtitulo,
                'lema' => $request->lema,
                'descripcion' => $request->descripcion,
                'imagen_principal' => $request->imagen_principal,
                'stock' => $request->stock,
                'precio' => $request->precio,
                'seccion' => $request->seccion
            ]);

            if ($request->has('especificaciones')) {
                $producto->especificaciones()->delete();
                foreach ($request->especificaciones as $clave => $valor) {
                    $producto->especificaciones()->create([
                        'clave' => $clave,
                        'valor' => $valor
                    ]);
                }
            }

            if ($request->has('dimensiones')) {
                $producto->dimensiones()->delete();
                foreach ($request->dimensiones as $tipo => $valor) {
                    $producto->dimensiones()->create([
                        'tipo' => $tipo,
                        'valor' => $valor
                    ]);
                }
            }

            if ($request->has('imagenes')) {
                $producto->imagenes()->delete();
                foreach ($request->imagenes as $url) {
                    $producto->imagenes()->create([
                        'url_imagen' => $url
                    ]);
                }
            }

            if ($request->has('relacionados')) {
                $producto->productosRelacionados()->sync($request->relacionados);
            }

            DB::commit();
            return response()->json(['message' => 'Producto actualizado exitosamente', 'producto' => $producto]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el producto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();
            return response()->json(['message' => 'Producto eliminado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el producto: ' . $e->getMessage()], 500);
        }
    }
}
