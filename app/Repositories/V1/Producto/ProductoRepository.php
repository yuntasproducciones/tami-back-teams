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

/**
 * @OA\Tag(
 *     name="Productos",
 *     description="API Endpoints de productos"
 * )
 */
class ProductoRepository implements ProductoRepositoryInterface
{
    protected ApiResponseService $apiResponse;
    protected $imgurService;

    public function __construct(ApiResponseService $apiResponse, ImgurService $imgurService) {
        $this->apiResponse = $apiResponse;
        $this->imgurService = $imgurService;
    }

    /**
     * Obtener listado de productos
     * 
     * @OA\Get(
     *     path="/api/v1/productos",
     *     summary="Muestra un listado de todos los productos",
     *     description="Retorna un array con todos los productos y sus relaciones",
     *     operationId="indexProductos",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Producto Premium"),
     *                     @OA\Property(property="title", type="string", example="Producto Premium"),
     *                     @OA\Property(property="subtitle", type="string", example="La mejor calidad"),
     *                     @OA\Property(property="tagline", type="string", example="Innovación y calidad"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="specs", type="object"),
     *                     @OA\Property(property="dimensions", type="object"),
     *                     @OA\Property(property="relatedProducts", type="array", @OA\Items(type="integer")),
     *                     @OA\Property(property="images", type="array", @OA\Items(type="string")),
     *                     @OA\Property(property="image", type="string"),
     *                     @OA\Property(property="nombreProducto", type="string"),
     *                     @OA\Property(property="stockProducto", type="integer"),
     *                     @OA\Property(property="precioProducto", type="number", format="float"),
     *                     @OA\Property(property="seccion", type="string"),
     *                     @OA\Property(property="created_at", type="string")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Productos obtenidos exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function getAll()
    {
        try {
            $productos = Producto::with(['dimensiones', 'imagenes', 'productosRelacionados'])->get();

            $formattedProductos = $productos->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'name' => $producto->nombre,
                    'title' => $producto->titulo,
                    'subtitle' => $producto->subtitulo,
                    'tagline' => $producto->lema,
                    'description' => $producto->descripcion,
                    'specs' => $producto->especificaciones,
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

    /**
     * Crear un nuevo producto
     * 
     * @OA\Post(
     *     path="/api/v1/productos",
     *     summary="Crea un nuevo producto",
     *     description="Almacena un nuevo producto y retorna los datos creados",
     *     operationId="storeProducto",
     *     tags={"Productos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "titulo", "descripcion", "imagen_principal", "stock", "precio", "seccion"},
     *             @OA\Property(property="nombre", type="string", example="Producto XYZ"),
     *             @OA\Property(property="titulo", type="string", example="Producto Premium XYZ"),
     *             @OA\Property(property="subtitulo", type="string", example="La mejor calidad"),
     *             @OA\Property(property="lema", type="string", example="Innovación y calidad"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción detallada del producto"),
     *             @OA\Property(property="imagen_principal", type="string", example="https://placehold.co/100x150/blue/white?text=XYZ"),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="precio", type="number", format="float", example=199.99),
     *             @OA\Property(property="seccion", type="string", example="electrónica"),
     *             @OA\Property(property="especificaciones", type="object",
     *                 example={"color": "rojo", "material": "aluminio"}
     *             ),
     *             @OA\Property(property="dimensiones", type="object",
     *                 example={"alto": "10cm", "ancho": "20cm", "largo": "30cm"}
     *             ),
    *             @OA\Property(property="imagenes", type="array", @OA\Items(type="string"), example={"https://placehold.co/100x150/blue/white?text=Product_X", "https://placehold.co/100x150/blue/white?text=Product_Y"}),
     *             @OA\Property(property="relacionados", type="array", @OA\Items(type="integer"), example={1,2,3})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Producto creado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */

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
                ['dimensiones', 'imagenes', 'relacionados'])));

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

    /**
     * Mostrar un producto específico
     * 
     * @OA\Get(
     *     path="/api/v1/productos/{id}",
     *     summary="Muestra un producto específico",
     *     description="Retorna los datos de un producto según su ID",
     *     operationId="showProducto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Producto Premium"),
     *                 @OA\Property(property="title", type="string", example="Producto Premium"),
     *                 @OA\Property(property="subtitle", type="string", example="La mejor calidad"),
     *                 @OA\Property(property="tagline", type="string", example="Innovación y calidad"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="specs", type="object"),
     *                 @OA\Property(property="dimensions", type="object"),
     *                 @OA\Property(property="relatedProducts", type="array", @OA\Items(type="integer")),
     *                 @OA\Property(property="images", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="image", type="string"),
     *                 @OA\Property(property="nombreProducto", type="string"),
     *                 @OA\Property(property="stockProducto", type="integer"),
     *                 @OA\Property(property="precioProducto", type="number", format="float"),
     *                 @OA\Property(property="seccion", type="string"),
     *                 @OA\Property(property="mensaje_correo", type="string")
     *             ),
     *             @OA\Property(property="message", type="string", example="Producto encontrado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function find($id)
    {
        try {
            $producto = Producto::with(['dimensiones', 'imagenes', 'productosRelacionados'])->findOrFail($id);

            $formattedProducto = [
                'id' => $producto->id,
                'name' => $producto->nombre,
                'title' => $producto->titulo,
                'subtitle' => $producto->subtitulo,
                'tagline' => $producto->lema,
                'description' => $producto->descripcion,
                'specs' => $producto->especificaciones,
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

    /**
     * Actualizar un producto específico
     * 
     * @OA\Put(
     *     path="/api/v1/productos/{id}",
     *     summary="Actualiza un producto específico",
     *     description="Actualiza los datos de un producto existente según su ID",
     *     operationId="updateProducto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Producto XYZ Actualizado"),
     *             @OA\Property(property="titulo", type="string", example="Producto Premium XYZ V2"),
     *             @OA\Property(property="subtitulo", type="string", example="La mejor calidad actualizada"),
     *             @OA\Property(property="lema", type="string", example="Innovación y calidad mejorada"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción actualizada del producto"),
     *             @OA\Property(property="imagen_principal", type="string", example="https://ejemplo.com/imagen_nueva.jpg"),
     *             @OA\Property(property="stock", type="integer", example=150),
     *             @OA\Property(property="precio", type="number", format="float", example=249.99),
     *             @OA\Property(property="seccion", type="string", example="electrónica premium"),
     *             @OA\Property(property="especificaciones", type="object",
     *                 example={"color": "negro", "material": "titanio"}
     *             ),
     *             @OA\Property(property="dimensiones", type="object",
     *                 example={"alto": "12cm", "ancho": "22cm", "largo": "32cm"}
     *             ),
     *             @OA\Property(property="imagenes", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="relacionados", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Producto actualizado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
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
                'especificaciones' => $data['especificaciones'] ?? $producto->especificaciones,
            ]);

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

    /**
     * Eliminar un producto específico
     * 
     * @OA\Delete(
     *     path="/api/v1/productos/{id}",
     *     summary="Elimina un producto específico",
     *     description="Elimina un producto existente según su ID",
     *     operationId="destroyProducto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Producto eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
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
