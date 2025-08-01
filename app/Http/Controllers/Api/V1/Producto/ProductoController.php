<?php

namespace App\Http\Controllers\Api\V1\Producto;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Producto\V2StoreProductoRequest;
use App\Http\Requests\Producto\V2UpdateProductoRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Contains\HttpStatusCode;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Obtener listado de productos
     * 
     * @OA\Get(
     *     path="/api/v2/productos",
     *     summary="Muestra un listado de todos los productos",
     *     description="Retorna un array con todos los productos y sus relaciones",
     *     operationId="indexProductos2",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titulo", type="string", example="Producto premium"),
     *                     @OA\Property(property="nombre", type="string", example="Laptop"),
     *                     @OA\Property(property="link", type="string", example="Link producto"),
     *                     @OA\Property(property="subtitulo", type="string", example="Innovación y calidad"),
     *                     @OA\Property(property="stock", type="integer"),
     *                     @OA\Property(property="precio", type="number"),
     *                     @OA\Property(property="seccion", type="string"),
     *                     @OA\Property(property="lema", type="string"),
     *                     @OA\Property(property="descripcion", type="string"),
     *                     @OA\Property(property="especificaciones", type="string"),
     *                     @OA\Property(property="mensaje_correo", type="string"),
     *                     @OA\Property(property="created_at", type="string"),
     *                     @OA\Property(property="updated_at", type="string"),
     *                     @OA\Property(property="imagenes", type="object")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Productos obtenidos exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * ),
     * security={}
     */
    public function index()
    {
        //
        $productos = Producto::with(['imagenes'])
            ->orderBy('created_at')
            ->get();

        // Para decodificar especificaciones
        $productos->transform(function ($producto) {
            $producto->especificaciones = json_decode($producto->especificaciones, true) ?? [];
            $producto->unsetRelation('producto_Relacionado');
            return $producto;
        });

        return response()->json($productos);
    }

    public function paginate()
    {
        //
        $productos = Producto::with('imagenes', 'productosRelacionados')->paginate(10);
        // Para decodificar especificaciones
        $productos->transform(function ($producto) {
            $producto->especificaciones = json_decode($producto->especificaciones, true) ?? [];
            return $producto;
        });

        return response()->json($productos);
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
    /**
 * Crear un nuevo producto
 * 
 * @OA\Post(
 *     path="/api/v2/productos",
 *     summary="Crear un nuevo producto (no funciona en Swagger)",
 *     description="Almacena un nuevo producto, guarda la imagen en el servidor. Si lo intentas usar en Swagger no funcionará, pero si lo pruebas desde Postman si funciona. Los campos a enviar ya sea o desde Postman o desde un frontend son los mismos listados a continuación.",
 *     operationId="storeProducto2",
 *     tags={"Productos"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={
 *                     "nombre", "titulo", "subtitulo", "stock", "precio", 
 *                     "seccion", "lema", "descripcion", "especificaciones",
 *                     "imagenes", "textos_alt", "mensaje_correo"
 *                 },
 *                 @OA\Property(property="nombre", type="string", example="Selladora"),
 *                 @OA\Property(property="titulo", type="string", example="Titulo increíble"),
 *                 @OA\Property(property="subtitulo", type="string", example="Subtitulo increíble"),
 *                 @OA\Property(property="stock", type="integer", example=20),
 *                 @OA\Property(property="precio", type="number", format="float", example=100.50),
 *                 @OA\Property(property="seccion", type="string", example="Decoración"),
 *                 @OA\Property(property="lema", type="string", example="Lema increíble"),
 *                 @OA\Property(property="descripcion", type="string", example="Descripción increíble"),
 *                 @OA\Property(property="especificaciones", type="string", example="Especificaciones increíbles"),
 *                 
 *                 @OA\Property(
 *                     property="imagenes",
 *                     type="array",
 *                     @OA\Items(type="string", format="binary"),
 *                     description="Array de imágenes a subir"
 *                 ),
 *                 
 *                 @OA\Property(
 *                     property="textos_alt",
 *                     type="array",
 *                     @OA\Items(type="string", example="Texto ALT para la imagen"),
 *                     description="Array de textos alternativos para las imágenes"
 *                 ),
 *                 
 *                 @OA\Property(property="mensaje_correo", type="string", example="Mensaje increíble")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Producto creado exitosamente",
 *         @OA\JsonContent(
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
    private function guardarImagen($archivo)
    {
        $nombre = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
        $archivo->storeAs("imagenes", $nombre, "public");
        return "/storage/imagenes/" . $nombre;
    }

    public function store(V2StoreProductoRequest $request)
    {
        $datosValidados = $request->validated();

        $imagenes = $datosValidados["imagenes"] ?? [];
        $textos = $datosValidados["textos_alt"] ?? [];

        $imagenesProcesadas = [];
        foreach ($imagenes as $i => $img) {
            $url = $this->guardarImagen($img);
            $imagenesProcesadas[] = [
                "url_imagen" => $url,
                "texto_alt_SEO" => $textos[$i]
            ];
        }

        $producto = Producto::create([
            "nombre" => $datosValidados["nombre"] ?? null,
            "link" => $datosValidados["link"] ?? null, 
            "titulo" => $datosValidados["titulo"] ?? null,
            "subtitulo" => $datosValidados["subtitulo"] ?? null,
            "stock" => $datosValidados["stock"] ?? null,
            "precio" => $datosValidados["precio"] ?? null,
            "seccion" => $datosValidados["seccion"] ?? null,
            "descripcion" => $datosValidados["descripcion"] ?? null,
            "meta_data" => $datosValidados["meta_data"] ?? [],
        ]);

        $producto->productosRelacionados()->sync($datosValidados['relacionados'] ?? []);
        $producto->imagenes()->createMany($imagenesProcesadas);

        // Aquí solo este bloque basta
        $especificaciones = json_decode($datosValidados['especificaciones'] ?? null, true);

        if (is_array($especificaciones)) {
            foreach ($especificaciones as $clave => $valor) {
                $producto->especificaciones()->create([
                    'clave' => $clave,
                    'valor' => $valor,
                ]);
            }
        }

        return response()->json(["message" => "Producto insertado exitosamente"], 201);
    }


    /**
     * Obtener un producto por su ID
     * 
     * @OA\Get(
     *     path="/api/v2/productos/{id}",
     *     summary="Muestra un producto por su ID",
     *     description="Retorna los datos completos de un producto según su ID",
     *     operationId="getProductoByIdV2",
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
     *         description="Producto obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombreProducto", type="string", example="Producto A"),
     *                 @OA\Property(property="title", type="string", example="Producto Premium"),
     *                 @OA\Property(property="subtitulo", type="string", example="Calidad superior"),
     *                 @OA\Property(property="tagline", type="string", example="Innovación y excelencia"),
     *                 @OA\Property(property="descripcion", type="string", example="Este producto destaca por su..."),
     *                 @OA\Property(property="especificaciones", type="string"),
     *                 @OA\Property(property="dimensiones", type="object"),
     *                 @OA\Property(property="relatedProducts", type="array", @OA\Items(type="integer")),
     *                 @OA\Property(property="images", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url_imagen", type="string", format="url", example="/storage/imagenes/neon.jpg"),
     *                         @OA\Property(property="texto_alt_SEO", type="string", example="Neón rojo personalizado")
     *                     )
     *                 ),
     *                 @OA\Property(property="image", type="string", format="url", example="/storage/imagenes/principal.jpg"),
     *                 @OA\Property(property="stockProducto", type="integer", example=10),
     *                 @OA\Property(property="precioProducto", type="number", format="float", example=99.99),
     *                 @OA\Property(property="seccion", type="string", example="Electrónica")
     *             ),
     *             @OA\Property(property="message", type="string", example="Producto encontrado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Producto no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Hubo un error en el servidor")
     *         )
     *     ),
     *     security={}
     * )
     */

    public function show(string $id)
    {
        try {
            $producto = Producto::with(['imagenes', 'productosRelacionados'])->find($id);

            if ($producto === null) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $imagenes = $producto->imagenes->map(function ($imagen) {
                return [
                    'url_imagen' => $imagen->url_imagen,
                    'texto_alt_SEO' => $imagen->texto_alt_SEO,
                ];
            });

            $formattedProducto = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'link' => $producto->link,
                'titulo' => $producto->titulo,
                'subtitulo' => $producto->subtitulo,
                'descripcion' => $producto->descripcion,
                'meta_data' => $producto->meta_data ?? [],
                'especificaciones' => $producto->especificaciones ?? [],
                'productos_relacionados' => $producto->productosRelacionados,
                'imagenes' => $imagenes->toArray(),
                'stock' => $producto->stock,
                'precio' => $producto->precio,
                'seccion' => $producto->seccion,
            ];

            return response()->json([
                'message' => 'Producto encontrado exitosamente',
                'data' => $formattedProducto
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Hubo un error en el servidor'
            ], 500);
        }
    }

    /**
     * Obtener un producto por su enlace único
     * 
     * @OA\Get(
     *     path="/api/v2/productos/link/{link}",
     *     summary="Muestra un producto usando su enlace único",
     *     description="Retorna los datos de un producto identificado por su campo 'link'",
     *     operationId="getProductoByLink",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="link",
     *         in="path",
     *         description="Enlace único del producto",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombreProducto", type="string", example="Producto A"),
     *                 @OA\Property(property="title", type="string", example="Producto Premium"),
     *                 @OA\Property(property="subtitulo", type="string", example="Calidad superior"),
     *                 @OA\Property(property="tagline", type="string", example="Innovación y excelencia"),
     *                 @OA\Property(property="descripcion", type="string", example="Este producto destaca por su..."),
     *                 @OA\Property(property="especificaciones", type="string"),
     *                 @OA\Property(property="dimensiones", type="object",
     *                     example={"alto":"10cm","ancho":"5cm"}
     *                 ),
     *                 @OA\Property(property="relatedProducts", type="array",
     *                     @OA\Items(type="integer"),
     *                     example={2,3,5}
     *                 ),
     *                 @OA\Property(property="images", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url_imagen", type="string", format="url", example="/storage/imagenes/neon.jpg"),
     *                         @OA\Property(property="texto_alt_SEO", type="string", example="Neón rojo personalizado")
     *                     )
     *                 ),
     *                 @OA\Property(property="image", type="string", format="url", example="/storage/imagenes/principal.jpg"),
     *                 @OA\Property(property="stockProducto", type="integer", example=10),
     *                 @OA\Property(property="precioProducto", type="number", format="float", example=99.99),
     *                 @OA\Property(property="seccion", type="string", example="Electrónica")
     *             ),
     *             @OA\Property(property="message", type="string", example="Producto encontrado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Hubo un error en el servidor")
     *         )
     *     ),
     *     security={}
     * )
     */

    public function showByLink($link)
    {
        try {
            $producto = Producto::with(['imagenes', 'productosRelacionados.imagenes'])
                ->where('link', $link)
                ->first();

            if ($producto === null) {
                return response()->json(["message" => "Producto no encontrado"], 404);
            }

            $imagenes = $producto->imagenes->map(function ($imagen) {
                return [
                    'url_imagen' => $imagen->url_imagen,
                    'texto_alt_SEO' => $imagen->texto_alt_SEO,
                ];
            });

            $formattedProducto = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'link' => $producto->link,
                'titulo' => $producto->titulo,
                'subtitulo' => $producto->subtitulo,
                'descripcion' => $producto->descripcion,
                'meta_data' => $producto->meta_data ?? [],
                'especificaciones' => $producto->especificaciones ?? [],
                'productos_relacionados' => $producto->productosRelacionados,
                'imagenes' => $imagenes->toArray(),
                'stock' => $producto->stock,
                'precio' => $producto->precio,
                'seccion' => $producto->seccion,
            ];

            return response()->json([
                'message' => 'Producto encontrado exitosamente',
                'data' => $formattedProducto
            ], 200);

        } catch (\Exception $e) {
            return response()->json(["message" => "Hubo un error en el servidor"], 500);
        }
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
    /**
     * Actualizar un producto específico
     * 
     * @OA\Post(
     *     path="/api/v2/productos/{id}",
     *     summary="Actualiza un producto específico (no funciona en Swagger)",
     *     description="Actualiza producto, elimina todas las antiguas imagenes y guarda las nuevas imagen en el servidor. Si lo intentas usar en Swagger no funcionará, pero si lo pruebas desde Postman si funciona. Los campos a enviar ya sea o desde Postman o desde un frontend son los mismos listados a continuación.",
     *     operationId="updateProducto2",
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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={
     *                     "nombre", "titulo", "subtitulo", "stock", "precio", 
     *                     "seccion", "lema", "descripcion", "especificaciones",
     *                      "imagenes", "textos_alt", "mensaje_correo", "_method"
     *                 },
     *                 @OA\Property(property="nombre", type="string", example="Selladora"),
     *                 @OA\Property(property="titulo", type="string", example="Titulo increíble"),
     *                 @OA\Property(property="subtitulo", type="string", example="Subtitulo increíble"),
     *                 @OA\Property(property="stock", type="integer", example=20),
     *                 @OA\Property(property="precio", type="number", format="float", example=100.50),
     *                 @OA\Property(property="seccion", type="string", example="Decoración"),
     *                 @OA\Property(property="lema", type="string", example="Lema increíble"),
     *                 @OA\Property(property="descripcion", type="string", example="Descripción increíble"),
     *                 @OA\Property(property="especificaciones", type="string", example="Especificaciones increíbles"),
     *                 
     *                 @OA\Property(
     *                     property="imagenes",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary"),
     *                     description="Array de imágenes a subir"
     *                 ),
     *                 
     *                 @OA\Property(
     *                     property="textos_alt",
     *                     type="array",
     *                     @OA\Items(type="string", example="Texto ALT para la imagen"),
     *                     description="Array de textos alternativos para las imágenes"
     *                 ),
     *                 
     *                 @OA\Property(property="mensaje_correo", type="string", example="Mensaje increíble"),
     *                 @OA\Property(property="_method", type="string", example="PUT")
     *             )
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
    public function update(V2UpdateProductoRequest $request, string $id)
    {
        Log::info('PATCH Producto Request received:', ['request_all' => $request->all(), 'id' => $id]);
        $datosValidados = $request->validated();
        Log::info('Validated data:', ['datos_validados' => $datosValidados]);

        DB::beginTransaction();
        try {
            $producto = Producto::findOrFail($id);

            // Construir solo los campos que se van a actualizar
            $camposActualizar = [];
            foreach([
                "nombre", "link", "titulo", "subtitulo", "stock", "precio",
                "seccion", "descripcion", "meta_data"
            ] as $campo) {
                if (array_key_exists($campo, $datosValidados)) {
                    $camposActualizar[$campo] = $datosValidados[$campo];
                }
            }
            Log::info('Fields to update:', ['campos_actualizar' => $camposActualizar]);
            $producto->update($camposActualizar);

            // Eliminar imágenes antiguas si se envían nuevas
            if (isset($datosValidados['imagenes'])) {
                $rutasImagenesAntiguas = [];
                foreach ($producto->imagenes as $imagen) {
                    array_push($rutasImagenesAntiguas, str_replace('/storage/', '', $imagen['url_imagen']));
                }
                Storage::disk('public')->delete($rutasImagenesAntiguas);
                $producto->imagenes()->delete();

                $imagenes = $request->file("imagenes", []);
                $altTexts = $datosValidados["textos_alt"] ?? [];

                foreach ($imagenes as $i => $imagen) {
                    $ruta = $this->guardarImagen($imagen);
                    $producto->imagenes()->create([
                        "url_imagen" => $ruta,
                        "texto_alt_SEO" => $altTexts[$i] ?? null
                    ]);
                }
            }

            // Actualizar especificaciones
            if (isset($datosValidados['especificaciones'])) {
                $producto->especificaciones()->delete();
                $especificaciones = json_decode($datosValidados['especificaciones'] ?? '[]', true);
                if (is_array($especificaciones)) {
                    foreach ($especificaciones as $clave => $valor) {
                        $producto->especificaciones()->create([
                            'clave' => $clave,
                            'valor' => $valor,
                        ]);
                    }
                }
            }

            // Sincronizar productos relacionados
            if (isset($datosValidados['relacionados'])) {
                $producto->productosRelacionados()->sync($datosValidados['relacionados'] ?? []);
            }

            DB::commit();
            return response()->json(["message" => "Producto actualizado exitosamente"], HttpStatusCode::OK->value);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al actualizar el producto: ' . $e->getMessage()],  HttpStatusCode::INTERNAL_SERVER_ERROR->value);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Eliminar un producto específico
     * 
     * @OA\Delete(
     *     path="/api/v2/productos/{id}",
     *     summary="Elimina un producto específico",
     *     description="Elimina un producto existente según su ID",
     *     operationId="destroyProducto2",
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
    public function destroy(string $id)
    {
        //
        DB::beginTransaction();
        try {
             $producto = Producto::with(['imagenes', 'especificaciones'])->find($id);
            if ($producto == null) {
                return response()->json(["message" => "Producto no encontrado"], status: 404);
            }
            $imagenesArray = $producto->imagenes->toArray();
            $productoImagenes = array_map(function ($x) {
                $archivo = str_ireplace("/storage/imagenes/", "", $x["url_imagen"]);
                return $archivo;
            }, $imagenesArray);
            foreach ($productoImagenes as $imagen) {
                Storage::delete("imagenes/" . $imagen);
            }

            $producto->especificaciones()->delete(); 

            $producto->delete();
            DB::commit();
            return response()->json(["message" => "Producto eliminado exitosamente"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message"=>"Hubo un error en el servidor"], 500);
        }
    }
}
