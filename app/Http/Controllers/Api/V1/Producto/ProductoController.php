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
     * @OA\Get(
     *     path="/api/v1/productos",
     *     summary="Listar productos",
     *     description="Obtiene la lista de productos con sus imágenes, especificaciones, productos relacionados y etiquetas.",
     *     operationId="getProductos",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Laptop Gamer"),
     *                 @OA\Property(property="nombre", type="string", example="Laptop XYZ"),
     *                 @OA\Property(property="link", type="string", example="/productos/laptop-xyz"),
     *                 @OA\Property(property="subtitulo", type="string", example="Potencia y velocidad"),
     *                 @OA\Property(property="stock", type="integer", example=12),
     *                 @OA\Property(property="precio", type="number", format="float", example=2499.99),
     *                 @OA\Property(property="seccion", type="string", example="Tecnología"),
     *                 @OA\Property(property="descripcion", type="string", example="Laptop de alto rendimiento para gaming."),
     *                 @OA\Property(
     *                     property="especificaciones",
     *                     type="object",
     *                     example={
     *                         "procesador": "Intel i7",
     *                         "ram": "16GB"
     *                     }
     *                 ),
     *                 @OA\Property(
     *                     property="imagenes",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="url_imagen", type="string", example="https://example.com/img1.jpg"),
     *                         @OA\Property(property="texto_alt_SEO", type="string", example="Imagen de laptop gamer")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="productos_relacionados",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="nombre", type="string", example="Mouse Gamer"),
     *                         @OA\Property(property="link", type="string", example="/productos/mouse-gamer"),
     *                         @OA\Property(property="titulo", type="string", example="Mouse RGB"),
     *                         @OA\Property(property="subtitulo", type="string", example="Alta precisión"),
     *                         @OA\Property(property="stock", type="integer", example=50),
     *                         @OA\Property(property="precio", type="number", format="float", example=49.99),
     *                         @OA\Property(property="seccion", type="string", example="Accesorios"),
     *                         @OA\Property(property="descripcion", type="string", example="Mouse gamer con retroiluminación RGB."),
     *                         @OA\Property(
     *                             property="imagenes",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(property="url_imagen", type="string", example="https://example.com/mouse1.jpg"),
     *                                 @OA\Property(property="texto_alt_SEO", type="string", example="Mouse gamer RGB")
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="etiqueta",
     *                     type="object",
     *                     nullable=true,
     *                     @OA\Property(property="meta_titulo", type="string", example="Meta título del producto"),
     *                     @OA\Property(property="meta_descripcion", type="string", example="Meta descripción del producto")
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-12T10:15:30Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-12T10:15:30Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los productos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al obtener los productos: error de base de datos")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $productos = Producto::with(['imagenes', 'especificaciones', 'productosRelacionados.imagenes', 'etiqueta'])
                ->orderBy('created_at')
                ->get();

            $showProductos = $productos->map(function ($producto) {
                $especificacionesFormateadas = [];
                foreach ($producto->especificaciones as $especificacion) {
                    $especificacionesFormateadas[$especificacion->clave] = $especificacion->valor;
                }

                Log::info('Producto ID: ' . $producto->id . ' - Etiqueta antes del mapeo: ', ['etiqueta' => $producto->etiqueta]);
                return [
                    'id' => $producto->id,
                    'titulo' => $producto->titulo,
                    'nombre' => $producto->nombre,
                    'link' => $producto->link,
                    'subtitulo' => $producto->subtitulo,
                    'stock' => $producto->stock,
                    'precio' => $producto->precio,
                    'seccion' => $producto->seccion,
                    'descripcion' => $producto->descripcion,
                    'especificaciones' => $especificacionesFormateadas,
                    'imagenes' => $producto->imagenes->map(function ($imagen) {
                        return [
                            'url_imagen' => $imagen->url_imagen,
                            'texto_alt_SEO' => $imagen->texto_alt_SEO,
                        ];
                    }),
                    'productos_relacionados' => $producto->productosRelacionados->map(function ($relacionado) {
                        return [
                            'id' => $relacionado->id,
                            'nombre' => $relacionado->nombre,
                            'link' => $relacionado->link,
                            'titulo' => $relacionado->titulo,
                            'subtitulo' => $relacionado->subtitulo,
                            'stock' => $relacionado->stock,
                            'precio' => $relacionado->precio,
                            'seccion' => $relacionado->seccion,
                            'descripcion' => $relacionado->descripcion,
                            'imagenes' => $relacionado->imagenes->map(function ($imagen) {
                                return [
                                    'url_imagen' => $imagen->url_imagen,
                                    'texto_alt_SEO' => $imagen->texto_alt_SEO,
                                ];
                            }),
                        ];
                    }),
                    'etiqueta' => $producto->etiqueta ? [
                        'meta_titulo' => $producto->etiqueta->meta_titulo,
                        'meta_descripcion' => $producto->etiqueta->meta_descripcion,
                    ] : null,
                    'created_at' => $producto->created_at,
                    'updated_at' => $producto->updated_at
                ];
            });

            return response()->json($showProductos);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los productos: ' . $e->getMessage()
            ], HttpStatusCode::INTERNAL_SERVER_ERROR->value);
        }
    }

    public function paginate()
    {
        //
        $productos = Producto::with('imagenes', 'productosRelacionados')->get();
        // Para decodificar especificaciones
        $productos->transform(function ($producto) {
            $producto->especificaciones = json_decode($producto->especificaciones, true) ?? [];
            return $producto;
        });

        return response()->json($productos);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/productos",
     *     summary="Crear un nuevo producto",
     *     description="Crea un nuevo producto con imágenes, etiquetas, productos relacionados y especificaciones (en formato JSON).",
     *     tags={"Productos"},
     *     security={{"sanctum": {}}}, 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre", "precio", "imagenes[]"},
     *                 
     *                 @OA\Property(property="nombre", type="string", example="Camiseta deportiva"),
     *                 @OA\Property(property="link", type="string", example="camiseta-deportiva"),
     *                 @OA\Property(property="titulo", type="string", example="Camiseta DryFit Hombre"),
     *                 @OA\Property(property="subtitulo", type="string", example="Ideal para entrenamiento"),
     *                 @OA\Property(property="stock", type="integer", example=50),
     *                 @OA\Property(property="precio", type="number", format="float", example=89.90),
     *                 @OA\Property(property="seccion", type="string", example="Ropa Deportiva"),
     *                 @OA\Property(property="descripcion", type="string", example="Camiseta ligera y transpirable."),
     * 
     *                 @OA\Property(
     *                     property="etiquetas[meta_titulo]",
     *                     type="string",
     *                     example="Camiseta deportiva hombre"
     *                 ),
     *                 @OA\Property(
     *                     property="etiquetas[meta_descripcion]",
     *                     type="string",
     *                     example="Compra la mejor camiseta deportiva para hombre."
     *                 ),
     * 
     *                 @OA\Property(
     *                     property="relacionados[]",
     *                     type="array",
     *                     @OA\Items(type="integer", example=2)
     *                 ),
     * 
     *                 @OA\Property(
     *                     property="imagenes[]",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary")
     *                 ),
     * 
     *                 @OA\Property(
     *                     property="textos_alt[]",
     *                     type="array",
     *                     @OA\Items(type="string", example="Camiseta azul vista frontal")
     *                 ),
     * 
     *                 @OA\Property(
     *                     property="especificaciones",
     *                     type="string",
     *                     description="JSON con pares clave-valor de especificaciones",
     *                     example="{""Color"":""Azul"", ""Talla"":""M"", ""Material"":""Poliéster""}"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto insertado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto insertado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */

    private function guardarImagen($archivo)
    {
        $nombre = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
        $archivo->storeAs("imagenes", $nombre, "public");
        return "/storage/imagenes/" . $nombre;
    }

    public function store(v2StoreProductoRequest $request)
    {
        $datosValidados = $request->validated();

        $imagenes = $datosValidados["imagenes"] ?? [];
        $textos = $datosValidados["textos_alt"] ?? [];

        $imagenesProcesadas = [];
        foreach ($imagenes as $i => $img) {
            $url = $this->guardarImagen($img);
            $imagenesProcesadas[] = [
                "url_imagen" => $url,
                "texto_alt_SEO" => $textos[$i] ?? null
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
        ]);

        if (isset($datosValidados['meta_titulo']) || isset($datosValidados['meta_descripcion'])) {
            $producto->etiqueta()->create([
                'meta_titulo' => $datosValidados['meta_titulo'] ?? null,
                'meta_descripcion' => $datosValidados['meta_descripcion'] ?? null,
            ]);
        }

        $producto->productosRelacionados()->sync($datosValidados['relacionados'] ?? []);
        $producto->imagenes()->createMany($imagenesProcesadas);

        // Manejo de especificaciones clave:valor - solo texto

        $especificaciones = json_decode($datosValidados['especificaciones'] ?? '[]', true);

        if (is_array($especificaciones)) {
            foreach ($especificaciones as $clave => $valor) {
                // 1 Caso: Solo texto 
                if (is_int($clave)) {
                    $producto->especificaciones()->create([
                        'clave' => null,
                        'valor' => null,
                        'texto' => $valor,
                    ]);

                    // 2 Caso: Clave - Valor
                } else {
                    $producto->especificaciones()->create([
                        'clave' => $clave,
                        'valor' => $valor,
                        'texto' => null,
                    ]);
                }
            }
        }

        return response()->json([
            "message" => "Producto insertado exitosamente",
            "data" => $producto->load([
                "imagenes",
                "etiqueta",
                "productosRelacionados",
                "especificaciones"
            ])
        ], 201);
    }


    /**
     * Obtener un producto por su ID
     * 
     * @OA\Get(
     *     path="/api/v1/productos/{id}",
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
     *                 @OA\Property(property="seccion", type="string", example="Electrónica"),
     *                 @OA\Property(property="etiqueta", type="object", nullable=true,
     *                     @OA\Property(property="meta_titulo", type="string", example="Meta título del producto"),
     *                     @OA\Property(property="meta_descripcion", type="string", example="Meta descripción del producto")
     *                 )
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
            $producto = Producto::with(['imagenes', 'productosRelacionados', 'etiqueta'])->find($id);

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
                'especificaciones' => $producto->especificaciones ?? [],
                'productos_relacionados' => $producto->productosRelacionados,
                'imagenes' => $imagenes->toArray(),
                'stock' => $producto->stock,
                'precio' => $producto->precio,
                'seccion' => $producto->seccion,
                'etiqueta' => $producto->etiqueta ? [
                    'meta_titulo' => $producto->etiqueta->meta_titulo,
                    'meta_descripcion' => $producto->etiqueta->meta_descripcion,
                ] : null,
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
     *     path="/api/v1/productos/link/{link}",
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
     *                 @OA\Property(property="seccion", type="string", example="Electrónica"),
     *                 @OA\Property(property="etiqueta", type="object", nullable=true,
     *                     @OA\Property(property="meta_titulo", type="string", example="Meta título del producto"),
     *                     @OA\Property(property="meta_descripcion", type="string", example="Meta descripción del producto")
     *                 )
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
            $producto = Producto::with(['imagenes', 'productosRelacionados.imagenes', 'etiqueta'])
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
                'especificaciones' => $producto->especificaciones ?? [],
                'productos_relacionados' => $producto->productosRelacionados,
                'imagenes' => $imagenes->toArray(),
                'stock' => $producto->stock,
                'precio' => $producto->precio,
                'seccion' => $producto->seccion,
                'etiqueta' => $producto->etiqueta ? [
                    'meta_titulo' => $producto->etiqueta->meta_titulo,
                    'meta_descripcion' => $producto->etiqueta->meta_descripcion,
                ] : null,
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
     *     path="/api/v1/productos/{id}",
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
     *                     "seccion", "descripcion", "especificaciones",
     *                      "imagenes", "textos_alt", "mensaje_correo", "_method"
     *                 },
     *                 @OA\Property(property="nombre", type="string", example="Selladora"),
     *                 @OA\Property(property="titulo", type="string", example="Titulo increíble"),
     *                 @OA\Property(property="subtitulo", type="string", example="Subtitulo increíble"),
     *                 @OA\Property(property="stock", type="integer", example=20),
     *                 @OA\Property(property="precio", type="number", format="float", example=100.50),
     *                 @OA\Property(property="seccion", type="string", example="Decoración"),
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
     *                 @OA\Property(property="_method", type="string", example="PUT"),
     *                 @OA\Property(property="meta_titulo", type="string", example="Meta título del producto"),
     *                 @OA\Property(property="meta_descripcion", type="string", example="Meta descripción del producto")
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
            foreach (
                [
                    "nombre",
                    "link",
                    "titulo",
                    "subtitulo",
                    "stock",
                    "precio",
                    "seccion",
                    "descripcion"
                ] as $campo
            ) {
                if (array_key_exists($campo, $datosValidados)) {
                    $camposActualizar[$campo] = $datosValidados[$campo];
                }
            }
            Log::info('Fields to update:', ['campos_actualizar' => $camposActualizar]);
            $producto->update($camposActualizar);

            if (isset($datosValidados['meta_titulo']) || isset($datosValidados['meta_descripcion'])) {
                $producto->etiqueta()->updateOrCreate(
                    ['producto_id' => $producto->id],
                    [
                        'meta_titulo' => $datosValidados['meta_titulo'] ?? null,
                        'meta_descripcion' => $datosValidados['meta_descripcion'] ?? null,
                    ]
                );
            } else if ($producto->etiqueta && (!isset($datosValidados['meta_titulo']) && !isset($datosValidados['meta_descripcion']))) {
                $producto->etiqueta()->delete();
            }

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
     *     path="/api/v1/productos/{id}",
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
            $producto = Producto::with(['imagenes', 'especificaciones', 'etiqueta'])->find($id);
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
            $producto->etiqueta()->delete();

            $producto->delete();
            DB::commit();
            return response()->json(["message" => "Producto eliminado exitosamente"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Hubo un error en el servidor"], 500);
        }
    }
}
