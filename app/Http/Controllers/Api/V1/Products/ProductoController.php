<?php

namespace App\Http\Controllers\Api\V1\Products;

use App\Models\Producto;
use App\Http\Requests\Producto\StoreProductoRequest;
use App\Http\Requests\Producto\UpdateProductoRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\V1\BasicController;
use App\Http\Contains\HttpStatusCode;

class ProductoController extends BasicController
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

        return $this->successResponse($formattedProductos, 'Productos obtenidos exitosamente');
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
    public function store(StoreProductoRequest $request)
    {
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
                'seccion' => $request->seccion,
                'mensaje_correo' => $request->mensaje_correo
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
            return $this->successResponse($producto, 'Producto creado exitosamente', HttpStatusCode::CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al crear el producto: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
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
                'mensaje_correo' => $producto->mensaje_correo
            ];

            return $this->successResponse($formattedProducto, 'Producto encontrado exitosamente');
        } catch (\Exception $e) {
            return $this->notFoundResponse('Producto no encontrado');
        }
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
    public function update(UpdateProductoRequest $request, $id)
    {
        try {
            $producto = Producto::findOrFail($id);
        } catch (\Exception $e) {
            return $this->notFoundResponse('Producto no encontrado');
        }

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
            return $this->successResponse($producto, 'Producto actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al actualizar el producto: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
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
            return $this->successResponse(null, 'Producto eliminado exitosamente');
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return $this->notFoundResponse('Producto no encontrado');
            }
            return $this->errorResponse('Error al eliminar el producto: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
