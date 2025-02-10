<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCategory\PostCategorias;
use App\Models\Productos;
use Illuminate\Http\Request;
use App\Http\Requests\PostProducts\PostProductos;
use App\Http\Requests\PostProductsDetails\PostDetalles_Productos;
use App\Models\Categorias;
use App\Models\Detalles_Productos;
use App\Models\Productos_Categorias;
use App\Traits\HttpResponseHelper;

class ProductosController extends Controller
{

    public function createProduct(Request $request, PostProductos $requestProduct, PostDetalles_Productos $requestDetails)
    {
        try{
        // Validacion de id categoria 
        $request->validate([
            'cat_id' => 'required|integer',  
        ]);

        $producto = Productos::create($requestProduct->all());
        $producto_id = $producto->prod_id;

        // Asignar el prod_id del producto recién creado a los detalles Producto
        $detalleProductoData = $requestDetails->all();
        $detalleProductoData['prod_id'] = $producto_id;
        Detalles_Productos::create($detalleProductoData);

        $categoria_id = $request->input('cat_id');

        // Relacionar producto con la categoría en la tabla intermedia
        // Productos_Categorias::create([
        //     'prod_id' => $producto_id,
        //     'cat_id' => $categoria_id
        // ]);

        return HttpResponseHelper::make()
            ->successfulResponse('Producto y detalles creados correctamente')
            ->send();

        }catch(\Exception $e){
            return HttpResponseHelper::make()
                ->internalErrorResponse('Ocurrio un problema al procesar la solicitud.'.
                 $e->getMessage())
                ->send();
        }
    }

    public function showAll(Productos $productos)
    {
        try {
            $productos = Productos::with('productos_categorias')->get();
            
            if ($productos->isEmpty()) {
                return HttpResponseHelper::make()
                    ->successfulResponse('No se encontraron productos.')
                    ->send();
            }

            // Respuesta filtrada
            $productosTransformados = $productos->map(function ($producto) {
                return [
                    'categoria' => $producto->productos_categorias->pluck('nombre')->first(),
                    'prod_id' => $producto->prod_id,
                    'imagen' => $producto->imagen,
                    'descripcion' => $producto->descripcion,
                ];
            });

            return HttpResponseHelper::make()
                ->successfulResponse('Productos obtenidos correctamente.')
                ->setData($productosTransformados)
                ->send();

        } catch (\Exception $e) {
            return HttpResponseHelper::make()
                ->internalErrorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage())
                ->send();
        }
    }

    public function updateProduct(Request $request, $prod_id, PostProductos $requestProduct, PostDetalles_Productos $requestDetails)
    {
        try {
        // Validación de id categoria
        $request->validate([
            'cat_id' => 'required|integer',
        ]);

        $producto = Productos::findOrFail($prod_id);
        $producto->update($requestProduct->all());

        // Buscar los detalles del producto asociados al prod_id
        $detalleProducto = Detalles_Productos::where('prod_id', $prod_id)->firstOrFail();
        $detalleProducto->update($requestDetails->all());

        $categoria_id = $request->input('cat_id');

        // Verificar si existe la relación entre el producto y la categoría
        // $productoCategoria = Productos_Categorias::where('prod_id', $prod_id)->first();

        // if ($productoCategoria) {
        //     $productoCategoria->update([
        //         'cat_id' => $categoria_id
        //     ]);
        // } else {
        //     return HttpResponseHelper::make()
        //         ->internalErrorResponse('No existe una relación entre el producto y la categoría. No se puede actualizar.')
        //         ->send();
        // }

        return HttpResponseHelper::make()
            ->successfulResponse('Producto y detalles actualizados correctamente')
            ->send();
    
        } catch (\Exception $e) {
            return HttpResponseHelper::make()
                ->internalErrorResponse('Ocurrió un problema al procesar la solicitud.' . $e->getMessage())
                ->send();
        }
    }

    public function destroyProduct(Productos $productos)
    {
        try {
            $productos->delete();
    
            return HttpResponseHelper::make()
                ->successfulResponse('Se ha eliminado el producto correctamente')
                ->send();
    
        } catch (\Exception $e) {
            return HttpResponseHelper::make()
                ->internalErrorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage())
                ->send();
        }
    }
}
