<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostBlog\PostStoreBlog;
use App\Http\Requests\PostBlog\UpdateBlog;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Storage;
use App\Models\Blog;
use App\Http\Contains\HttpStatusCode;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    protected ApiResponseService $apiResponse;

    public function __construct(ApiResponseService $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }


    /**
     * @OA\Get(
     *     path="/api/v1/blogs",
     *     summary="Listar blogs",
     *     tags={"Blogs"},
     *     description="Obtiene todos los blogs con sus imágenes, párrafos, producto y etiqueta asociados.",
     *     @OA\Response(
     *         response=200,
     *         description="Blogs obtenidos exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Cómo cuidar tu piel en invierno"),
     *                 @OA\Property(property="nombre_producto", type="string", nullable=true, example="Crema hidratante"),
     *                 @OA\Property(property="link", type="string", example="como-cuidar-tu-piel-invierno"),
     *                 @OA\Property(property="subtitulo1", type="string", example="Protección contra el frío extremo"),
     *                 @OA\Property(property="subtitulo2", type="string", example="Rutina de hidratación recomendada"),
     *                 @OA\Property(property="video_id", type="string", nullable=true, example="abcd1234"),
     *                 @OA\Property(property="video_url", type="string", format="uri", example="https://www.youtube.com/watch?v=abcd1234"),
     *                 @OA\Property(property="video_titulo", type="string", example="Guía completa de cuidado de la piel en invierno"),
     *                 @OA\Property(property="miniatura", type="string", example="storage/miniaturas/imagen.jpg"),
     *                 @OA\Property(
     *                     property="imagenes",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="ruta_imagen", type="string", example="storage/blogs/img1.jpg"),
     *                         @OA\Property(property="text_alt", type="string", example="Persona aplicando crema hidratante")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="parrafos",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="parrafo", type="string", example="Durante el invierno la piel tiende a resecarse...")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="etiqueta",
     *                     type="object",
     *                     nullable=true,
     *                     @OA\Property(property="meta_titulo", type="string", example="Consejos para cuidar tu piel"),
     *                     @OA\Property(property="meta_descripcion", type="string", example="Descubre cómo proteger tu piel del frío")
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-19T12:34:56Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-19T12:34:56Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error interno del servidor")
     * )
     */

    public function index()
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])->get();

            $showBlog = $blog->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'titulo' => $blog->titulo,
                    'nombre_producto' => $blog->producto ? $blog->producto->nombre : null,
                    'link' => $blog->link,
                    'subtitulo1' => $blog->subtitulo1,
                    'subtitulo2' => $blog->subtitulo2,
                    'video_id' => $this->obtenerIdVideoYoutube($blog->video_url),
                    'video_url' => $blog->video_url,
                    'video_titulo' => $blog->video_titulo,
                    'miniatura' => $blog->miniatura,
                    'imagenes' => $blog->imagenes->map(function ($imagen) {
                        return [
                            'ruta_imagen' => $imagen->ruta_imagen,
                            'text_alt' => $imagen->text_alt,
                        ];
                    }),
                    'parrafos' => $blog->parrafos->map(function ($parrafo) {
                        return [
                            'parrafo' => $parrafo->parrafo,
                        ];
                    }),
                    'etiqueta' => $blog->etiqueta ? [
                        'meta_titulo' => $blog->etiqueta->meta_titulo,
                        'meta_descripcion' => $blog->etiqueta->meta_descripcion,
                    ] : null,
                    'created_at' => $blog->created_at,
                    'updated_at' => $blog->updated_at
                ];
            });

            return $this->apiResponse->successResponse(
                $showBlog,
                'Blogs obtenidos exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse(
                'Error al obtener los blogs: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

    private function guardarImagen($archivo)
    {
        $nombre = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
        $archivo->storeAs("imagenes", $nombre, "public");
        return "/storage/imagenes/" . $nombre;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/blogs",
     *     summary="Crear un nuevo blog",
     *     tags={"Blogs"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="titulo",
     *                     type="string",
     *                     example="Cómo cuidar tu piel en verano"
     *                 ),
     *                 @OA\Property(
     *                     property="producto_id",
     *                     type="integer",
     *                     example=5
     *                 ),
     *                 @OA\Property(
     *                     property="link",
     *                     type="string",
     *                     example="como-cuidar-tu-piel-verano"
     *                 ),
     *                 @OA\Property(
     *                     property="subtitulo1",
     *                     type="string",
     *                     example="La importancia de la protección solar"
     *                 ),
     *                 @OA\Property(
     *                     property="subtitulo2",
     *                     type="string",
     *                     example="Consejos prácticos para tu rutina diaria"
     *                 ),
     *                 @OA\Property(
     *                     property="video_url",
     *                     type="string",
     *                     format="uri",
     *                     example="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
     *                 ),
     *                 @OA\Property(
     *                     property="video_titulo",
     *                     type="string",
     *                     example="Guía completa de protección solar"
     *                 ),
     *                 @OA\Property(
     *                     property="meta_titulo",
     *                     type="string",
     *                     example="Tips para cuidar la piel en verano"
     *                 ),
     *                 @OA\Property(
     *                     property="meta_descripcion",
     *                     type="string",
     *                     example="Aprende a proteger tu piel del sol con estos consejos prácticos y efectivos."
     *                 ),
     *                 @OA\Property(
     *                     property="miniatura",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen de portada del blog"
     *                 ),
     *                 @OA\Property(
     *                     property="imagenes",
     *                     description="Archivos de imágenes adicionales",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="text_alt",
     *                     description="Texto alternativo para cada imagen (alineado por índice con 'imagenes')",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         example="Persona aplicando protector solar"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="parrafos",
     *                     description="Contenido de cada párrafo",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         example="El uso de protector solar es fundamental para prevenir el envejecimiento prematuro."
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Blog creado con éxito"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */

    public function store(PostStoreBlog $request)
    {
        $datosValidados = $request->validated();
        DB::beginTransaction();

        try {
            if (!$request->hasFile('miniatura')) {
                throw new \Exception('No se recibió miniatura como archivo');
            }

            $imagenPrincipal = $request->file("miniatura");
            $rutaImagenPrincipal = $this->guardarImagen($imagenPrincipal);

            $blog = Blog::create([
                "titulo" => $datosValidados["titulo"],
                "producto_id" => $datosValidados["producto_id"],
                "link" => $datosValidados["link"],
                "subtitulo1" => $datosValidados["subtitulo1"],
                "subtitulo2" => $datosValidados["subtitulo2"],
                "video_url" => $datosValidados["video_url"],
                "video_titulo" => $datosValidados["video_titulo"],
                "miniatura" => $rutaImagenPrincipal,
            ]);

            if (isset($datosValidados['meta_titulo']) || isset($datosValidados['meta_descripcion'])) {
                $blog->etiqueta()->create([
                    'meta_titulo' => $datosValidados['meta_titulo'] ?? null,
                    'meta_descripcion' => $datosValidados['meta_descripcion'] ?? null,
                ]);
            }

            // Guardar imágenes solo si se envían
            if (isset($datosValidados['imagenes'])) {
                $imagenes = $request->file("imagenes", []);
                $altTexts = $datosValidados["text_alt"] ?? [];
                foreach ($imagenes as $i => $imagen) {
                    $ruta = $this->guardarImagen($imagen);
                    $blog->imagenes()->create([
                        "ruta_imagen" => $ruta,
                        "text_alt" => $altTexts[$i] ?? null
                    ]);
                }
            }

            foreach ($datosValidados["parrafos"] as $item) {
                $blog->parrafos()->createMany([
                    ["parrafo" => $item]
                ]);
            }

            DB::commit();
            return $this->apiResponse->successResponse($blog->fresh(), 'Blog creado con éxito.', HttpStatusCode::CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->errorResponse(
                'Error al crear el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/blogs/{id}",
     *     summary="Obtener un blog por ID",
     *     tags={"Blogs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del blog a obtener",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog obtenido exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Blog obtenido exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Título del blog"),
     *                 @OA\Property(property="nombre_producto", type="string", example="Producto asociado"),
     *                 @OA\Property(property="link", type="string", example="mi-blog"),
     *                 @OA\Property(property="subtitulo1", type="string", example="Subtítulo 1"),
     *                 @OA\Property(property="subtitulo2", type="string", example="Subtítulo 2"),
     *                 @OA\Property(property="video_id", type="string", example="dQw4w9WgXcQ"),
     *                 @OA\Property(property="video_url", type="string", example="https://youtube.com/watch?v=dQw4w9WgXcQ"),
     *                 @OA\Property(property="video_titulo", type="string", example="Título del video"),
     *                 @OA\Property(property="miniatura", type="string", example="miniatura.jpg"),
     *                 @OA\Property(
     *                     property="imagenes",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="ruta_imagen", type="string", example="uploads/imagen1.jpg"),
     *                         @OA\Property(property="texto_alt", type="string", example="Descripción SEO")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="parrafos",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="parrafo", type="string", example="Este es un párrafo del blog")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="etiqueta",
     *                     type="object",
     *                     nullable=true,
     *                     @OA\Property(property="meta_titulo", type="string", example="SEO título"),
     *                     @OA\Property(property="meta_descripcion", type="string", example="Descripción para SEO")
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-19T12:34:56.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-19T13:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al obtener el blog: No encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al obtener el blog: ...")
     *         )
     *     )
     * )
     */

    public function show(int $id)
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])
                ->findOrFail($id);

            $showBlog = [
                'id' => $blog->id,
                'titulo' => $blog->titulo,
                'nombre_producto' => $blog->producto ? $blog->producto->nombre : null,
                'link' => $blog->link,
                'subtitulo1' => $blog->subtitulo1,
                'subtitulo2' => $blog->subtitulo2,
                'video_id' => $this->obtenerIdVideoYoutube($blog->video_url),
                'video_url' => $blog->video_url,
                'video_titulo' => $blog->video_titulo,
                'miniatura' => $blog->miniatura,
                'imagenes' => $blog->imagenes->map(function ($imagen) {
                    return [
                        'ruta_imagen' => $imagen->ruta_imagen,
                        'texto_alt' => $imagen->text_alt,
                    ];
                }),
                'parrafos' => $blog->parrafos->map(function ($parrafo) {
                    return [
                        'parrafo' => $parrafo->parrafo,
                    ];
                }),
                'etiqueta' => $blog->etiqueta ? [
                    'meta_titulo' => $blog->etiqueta->meta_titulo,
                    'meta_descripcion' => $blog->etiqueta->meta_descripcion,
                ] : null,
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at
            ];

            return $this->apiResponse->successResponse(
                $showBlog,
                'Blog obtenido exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse(
                'Error al obtener el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * @OA\Get(
     *     path="/api/blogs/link/{link}",
     *     summary="Obtener un blog por link",
     *     description="Devuelve los datos de un blog, incluyendo imágenes, párrafos, producto relacionado y etiquetas SEO",
     *     tags={"Blogs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="link",
     *         in="path",
     *         required=true,
     *         description="Link único del blog",
     *         @OA\Schema(type="string", example="mi-blog-de-ejemplo")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog obtenido exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Blog obtenido exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Título del blog"),
     *                 @OA\Property(property="nombre_producto", type="string", example="Producto relacionado"),
     *                 @OA\Property(property="link", type="string", example="mi-blog-de-ejemplo"),
     *                 @OA\Property(property="subtitulo1", type="string", example="Primer subtítulo"),
     *                 @OA\Property(property="subtitulo2", type="string", example="Segundo subtítulo"),
     *                 @OA\Property(property="video_id", type="string", example="dQw4w9WgXcQ"),
     *                 @OA\Property(property="video_url", type="string", example="https://www.youtube.com/watch?v=dQw4w9WgXcQ"),
     *                 @OA\Property(property="video_titulo", type="string", example="Título del video"),
     *                 @OA\Property(property="miniatura", type="string", example="miniaturas/blog1.jpg"),
     *                 @OA\Property(
     *                     property="imagenes",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="ruta_imagen", type="string", example="imagenes/blog1_img1.jpg"),
     *                         @OA\Property(property="text_alt", type="string", example="Descripción de la imagen")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="parrafos",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="parrafo", type="string", example="Contenido del párrafo 1...")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="etiqueta",
     *                     type="object",
     *                     nullable=true,
     *                     @OA\Property(property="meta_titulo", type="string", example="Meta título SEO"),
     *                     @OA\Property(property="meta_descripcion", type="string", example="Meta descripción SEO")
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-19T12:34:56.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-19T12:34:56.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No se encontró el blog")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al obtener el blog")
     *         )
     *     )
     * )
     */

    public function showLink(string $link)
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])
                ->where('link', $link)
                ->firstOrFail();

            $showBlog = [
                'id' => $blog->id,
                'titulo' => $blog->titulo,
                'nombre_producto' => $blog->producto ? $blog->producto->nombre : null,
                'link' => $blog->link,
                'subtitulo1' => $blog->subtitulo1,
                'subtitulo2' => $blog->subtitulo2,
                'video_id' => $this->obtenerIdVideoYoutube($blog->video_url),
                'video_url' => $blog->video_url,
                'video_titulo' => $blog->video_titulo,
                'miniatura' => $blog->miniatura,
                'imagenes' => $blog->imagenes->map(function ($imagen) {
                    return [
                        'ruta_imagen' => $imagen->ruta_imagen,
                        'texto_alt' => $imagen->text_alt,
                    ];
                }),
                'parrafos' => $blog->parrafos->map(function ($parrafo) {
                    return [
                        'parrafo' => $parrafo->parrafo,
                    ];
                }),
                'etiqueta' => $blog->etiqueta ? [
                    'meta_titulo' => $blog->etiqueta->meta_titulo,
                    'meta_descripcion' => $blog->etiqueta->meta_descripcion,
                ] : null,
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at
            ];

            return $this->apiResponse->successResponse(
                $showBlog,
                'Blog obtenido exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse(
                'Error al obtener el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * @OA\Post(
     *     path="/api/blogs/{id}",
     *     summary="Actualizar un blog",
     *     tags={"Blogs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del blog a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"titulo"},
     *                 @OA\Property(
     *                     property="titulo",
     *                     type="string",
     *                     example="Nuevo título del blog"
     *                 ),
     *                 @OA\Property(
     *                     property="producto_id",
     *                     type="integer",
     *                     example=5
     *                 ),
     *                 @OA\Property(
     *                     property="link",
     *                     type="string",
     *                     example="nuevo-link-blog"
     *                 ),
     *                 @OA\Property(
     *                     property="subtitulo1",
     *                     type="string",
     *                     example="Subtítulo actualizado 1"
     *                 ),
     *                 @OA\Property(
     *                     property="subtitulo2",
     *                     type="string",
     *                     example="Subtítulo actualizado 2"
     *                 ),
     *                 @OA\Property(
     *                     property="video_url",
     *                     type="string",
     *                     example="https://www.youtube.com/watch?v=abcd1234"
     *                 ),
     *                 @OA\Property(
     *                     property="video_titulo",
     *                     type="string",
     *                     example="Título del video"
     *                 ),
     *                 @OA\Property(
     *                     property="miniatura",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen miniatura"
     *                 ),
     *                 @OA\Property(
     *                     property="imagenes[]",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary"),
     *                     description="Imágenes relacionadas al blog"
     *                 ),
     *                 @OA\Property(
     *                     property="text_alt[]",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     description="Textos alternativos de las imágenes"
     *                 ),
     *                 @OA\Property(
     *                     property="parrafos[]",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     description="Párrafos del blog"
     *                 ),
     *                 @OA\Property(
     *                     property="meta_titulo",
     *                     type="string",
     *                     example="Meta título actualizado"
     *                 ),
     *                 @OA\Property(
     *                     property="meta_descripcion",
     *                     type="string",
     *                     example="Meta descripción actualizada"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog actualizado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error en el servidor"
     *     )
     * )
     */

    public function update(UpdateBlog $request, $id)
    {
        $datosValidados = $request->validated();
        DB::beginTransaction();
        $blog = Blog::findOrFail($id);

        try {
            $camposActualizar = [];

            foreach (
                [
                    "titulo",
                    "producto_id",
                    "link",
                    "subtitulo1",
                    "subtitulo2",
                    "video_url",
                    "video_titulo"
                ] as $campo
            ) {
                if ($request->has($campo)) {
                    $camposActualizar[$campo] = $datosValidados[$campo];
                }
            }

            if ($request->hasFile('miniatura')) {
                if ($blog->miniatura) {
                    $rutaAnterior = str_replace('/storage/', '', $blog->miniatura);
                    Storage::disk('public')->delete($rutaAnterior);
                }
                $camposActualizar['miniatura'] = $this->guardarImagen($request->file('miniatura'));
            } elseif ($request->has('miniatura') && $datosValidados['miniatura'] === null) {
                if ($blog->miniatura) {
                    $rutaAnterior = str_replace('/storage/', '', $blog->miniatura);
                    Storage::disk('public')->delete($rutaAnterior);
                }
                $camposActualizar['miniatura'] = null;
            }

            $blog->update($camposActualizar);

            if (isset($datosValidados['meta_titulo']) || isset($datosValidados['meta_descripcion'])) {
                $blog->etiqueta()->updateOrCreate(
                    ['blog_id' => $blog->id],
                    [
                        'meta_titulo' => $datosValidados['meta_titulo'] ?? null,
                        'meta_descripcion' => $datosValidados['meta_descripcion'] ?? null,
                    ]
                );
            } else if ($blog->etiqueta && (!isset($datosValidados['meta_titulo']) && !isset($datosValidados['meta_descripcion']))) {
                $blog->etiqueta()->delete();
            }


            if ($request->has('imagenes')) {
                $rutasImagenesAntiguas = [];
                foreach ($blog->imagenes as $imagen) {
                    array_push($rutasImagenesAntiguas, str_replace('storage/', '', $imagen['ruta_imagen']));
                }
                Storage::disk('public')->delete($rutasImagenesAntiguas);
                $blog->imagenes()->delete();

                $imagenes = $request->file("imagenes", []);
                $altTexts = $datosValidados["text_alt"] ?? [];

                foreach ($imagenes as $i => $imagen) {
                    $ruta = $this->guardarImagen($imagen);
                    $blog->imagenes()->create([
                        "ruta_imagen" => $ruta,
                        "text_alt" => $altTexts[$i] ?? null
                    ]);
                }
            }

            if ($request->has('parrafos')) {
                $blog->parrafos()->delete();
                foreach ($datosValidados["parrafos"] as $item) {
                    $blog->parrafos()->create([
                        "parrafo" => $item
                    ]);
                }
            }

            DB::commit();
            return $this->apiResponse->successResponse($blog->fresh(), 'Blog actualizado exitosamente', HttpStatusCode::OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->errorResponse('Error al actualizar el blog: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/blogs/{id}",
     *     summary="Eliminar un blog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del blog a eliminar",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Blog eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No se encontró el blog con el ID proporcionado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al intentar eliminar el blog",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al eliminar el blog: error inesperado")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $blog = Blog::findOrFail($id);

            $rutasImagenes = [];
            foreach ($blog->imagenes as $imagen) {
                $relativePath = str_replace('storage/', '', $imagen->ruta_imagen);
                array_push($rutasImagenes, $relativePath);
            }

            $blog->imagenes()->delete();
            $blog->parrafos()->delete();
            $blog->etiqueta()->delete();

            if (!empty($rutasImagenes)) {
                Storage::delete($rutasImagenes);
            }

            $blog->delete();

            DB::commit();

            return $this->apiResponse->successResponse(
                null,
                'Blog eliminado exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->errorResponse(
                'Error al eliminar el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

    private function obtenerIdVideoYoutube($url)
    {
        $pattern = '%(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|v/|shorts/))([^\s&?]+)%';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
