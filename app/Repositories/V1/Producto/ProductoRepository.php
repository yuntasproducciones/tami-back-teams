<?php

namespace App\Repositories\V1\Producto;

use App\Http\Contains\HttpStatusCode;
use App\Models\Producto;
use App\Repositories\V1\Contracts\ProductoRepositoryInterface;
use App\Services\ApiResponseService;
use App\Services\ImgurService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/*
 * Summary of ProductoRepository

class ProductoRepository implements ProductoRepositoryInterface
{
    protected ApiResponseService $apiResponse;
    protected $imgurService;

    public function __construct(ApiResponseService $apiResponse, ImgurService $imgurService) {
        $this->apiResponse = $apiResponse;
        $this->imgurService = $imgurService;
    }
    public function getAll()
    {
        try {
            $productos = Producto::with(['especificaciones', 'dimensiones', 'imagenes', 'productosRelacionados'])->get();

            $formattedProductos = $productos->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'name' => $producto->nombre,
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
                    'created_at' => $producto->created_at,
                ];
            });

            return $this->apiResponse->successResponse($formattedProductos, 
            'Productos obtenidos exitosamente', HttpStatusCode::OK);

        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse($e->getMessage(), 
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


     //A quien lea esto, te recomiendo ver los demás archivos relacionados con productos
     //me sirvió bastante para entender esta vaina
     // ProductoRepository, ProductoController, Producto/StoreProductoRequest
     // Models/Dimension, Models/ImagenProducto
     // Models/Producto, Models/ProductoRelacionados
     //Y routes/api
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            //Aquí se cambia el tipo de String a integer o float
            $data['stock'] = (int) $data['stock'];
            $data['precio'] = (float) $data['precio'];
            //Lo puse por si acaso, pero no estoy seguro que sea necesario
            //si existe un campo mensaje_correo se guardará, si no se guarda null
            $data['mensaje_correo'] = $data['mensaje_correo'] ?? null;

             // 🟡 Validar y subir imagen principal si existe
            if (!empty($data['imagen_principal']) && $data['imagen_principal'] instanceof \Illuminate\Http\UploadedFile) {
                $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($data['imagen_principal']->getMimeType(), $validMimeTypes)) {
                    throw new \Exception("El archivo de imagen principal no es válido.");
                }
                // Subir imagen principal a Imgur
                $uploadedMainImageUrl = $this->imgurService->uploadImage($data['imagen_principal']);
                if (!$uploadedMainImageUrl) {
                    throw new \Exception("Falló la subida de la imagen principal.");
                }
                // Reemplazar el valor en el array original
                $data['imagen_principal'] = $uploadedMainImageUrl;
            }

            //Crea el producto
            $producto = Producto::create(array_diff_key($data, array_flip(
                //Separa los campos que no son necesarios para crear el producto
                //Las tablas que estan enlazadas a producto
                ['especificaciones', 'dimensiones', 'imagenes', 'relacionados'])));


            //Crea las especificaciones y las enlaza con el idProducto (tabla especificaciones)
            if (!empty($data['especificaciones']) && is_array($data['especificaciones'])) {
                foreach ($data['especificaciones'] as $clave => $valor) {
                    $producto->especificaciones()->create([
                        'clave' => $clave,
                        'valor' => $valor
                    ]);
                }
            }
            //Crea las dimensiones y las enlaza con el idProducto (tabla dimensiones)
            if (!empty($data['dimensiones']) && is_array($data['dimensiones'])) {
                foreach ($data['dimensiones'] as $tipo => $valor) {
                    $producto->dimensiones()->create([
                        'tipo' => $tipo,
                        'valor' => $valor
                    ]);
                }
            }

            //Crea las imagenes y las enlaza con el idProducto (tabla imagen_productos)
            if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
                foreach ($data['imagenes'] as $item) {
                    //Valida que haya un campo url_imagen y que contenga un archivo
                    if (isset($item['url_imagen']) && $item['url_imagen'] instanceof \Illuminate\Http\UploadedFile) {
                        //Valida que sea un tipo aceptable, imgur no acepta webp ❌
                        $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!in_array($item['url_imagen']->getMimeType(), $validMimeTypes)) {
                            throw new \Exception("El archivo de imagen no cumple con los tipos especificados.\n SOLO SE ADMITEN IMAGENES: jpeg, png, jpg");
                        }
            
                        // Subir la imagen a Imgur
                        $uploadedImageUrl = $this->imgurService->uploadImage($item['url_imagen']);
                        if (!$uploadedImageUrl) {
                            throw new \Exception("Falló la subida de las imagenes.\n $item");
                        }
            
                        // Crear la relación con las imágenes
                        $producto->imagenes()->create([
                            'id_producto' => $producto->id,  // Vincular al blog creado
                            'url_imagen' => $uploadedImageUrl,  // URL de la imagen subida
                        ]);
                    } else {
                        throw new \Exception("Formato inválido para imagenes de producto.");
                    }
                }
            }

            if (!empty($data['relacionados']) && is_array($data['relacionados'])) {
                // Convertir los valores de relacionados a enteros
                $data['relacionados'] = array_map('intval', $data['relacionados']);
                // Adjuntar los productos relacionados
                $producto->productosRelacionados()->attach($data['relacionados']);
            }

            DB::commit();
            return $this->apiResponse->successResponse(
                $producto, 
                'Producto creado exitosamente', 
                HttpStatusCode::CREATED
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->errorResponse(
                'Error al crear el producto: ' . $e->getMessage(), 
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

    public function find($id)
    {
        try {
            $producto = Producto::with(['especificaciones', 'dimensiones', 'imagenes', 'productosRelacionados'])->findOrFail($id);

            $formattedProducto = [
                'id' => $producto->id,
                'name' => $producto->nombre,
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

            return $this->apiResponse->successResponse($formattedProducto, 'Producto encontrado exitosamente',
            HttpStatusCode::OK);
        
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse->notFoundResponse('Producto no encontrado ' . $e->getMessage());

        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse('ocurrio un problema ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

   
    public function update(array $data, $id)
    {
        try {
            $producto = Producto::findOrFail($id);
        } catch (\Exception $e) {
            return $this->apiResponse->notFoundResponse(
                'Producto no encontrado'
            );
        }

        DB::beginTransaction();
        try {
            $producto->update([
                'nombre' => $data['nombre'] ?? $producto->nombre,
                'titulo' => $data['titulo'] ?? $producto->titulo,
                'subtitulo' => $data['subtitulo'] ?? $producto->subtitulo,
                'lema' => $data['lema'] ?? $producto->lema,
                'descripcion' => $data['descripcion'] ?? $producto->descripcion,
                'imagen_principal' => $data['imagen_principal'] ?? $producto->imagen_principal,
                'stock' => $data['stock'] ?? $producto->stock,
                'precio' => $data['precio'] ?? $producto->precio,
                'seccion' => $data['seccion'] ?? $producto->seccion,
            ]);

            if (!empty($data['especificaciones']) && is_array($data['especificaciones'])) {
                $producto->especificaciones()->delete();
                foreach ($data['especificaciones'] as $clave => $valor) {
                    $producto->especificaciones()->create([
                        'clave' => $clave,
                        'valor' => $valor
                    ]);
                }
            }

            if (!empty($data['dimensiones']) && is_array($data['dimensiones'])) {
                $producto->dimensiones()->delete();
                foreach ($data['dimensiones'] as $tipo => $valor) {
                    $producto->dimensiones()->create([
                        'tipo' => $tipo,
                        'valor' => $valor
                    ]);
                }
            }

            if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
                $producto->imagenes()->delete();
                foreach ($data['imagenes'] as $url) {
                    $producto->imagenes()->create([
                        'url_imagen' => $url
                    ]);
                }
            }

            if (!empty($data['relacionados']) && is_array($data['relacionados'])) {
                $producto->productosRelacionados()->sync($data['relacionados']);
            }

            DB::commit();

            return $this->apiResponse->successResponse(
                $producto,
                'Producto actualizado exitosamente',
                HttpStatusCode::OK
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->errorResponse(
                'Error al actualizar el producto: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

public function delete($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();
            
            return $this->apiResponse->successResponse(null, 'Producto eliminado exitosamente',
        HttpStatusCode::OK);
        
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse->notFoundResponse('Producto no encontrado ' . $e->getMessage());

        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse('Error al eliminar el producto: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}

*/