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
use App\Models\Producto;

/**
 * @OA\Tag(
 *     name="Blogs",
 *     description="API para gestión de blogs"
 * )
 */

class BlogController extends Controller
{
    protected ApiResponseService $apiResponse;
    
    public function __construct(ApiResponseService $apiResponse) {
        $this->apiResponse = $apiResponse;
    }

/**
 * Obtener listado de blogs
 * 
 * @OA\Get(
 *     path="/api/v1/blogs",
 *     summary="Muestra un listado de todos los blogs",
 *     description="Retorna un array con todos los blogs y sus relaciones",
 *     operationId="indexBlogs",
 *     tags={"Blogs"},
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="producto_id", type="integer", example=1),
 *                     @OA\Property(property="titulo", type="string", example="Producto Premium"),
 *                     @OA\Property(property="link", type="string", example="https://example.com/blog/1"),
 *                     @OA\Property(property="parrafo", type="string", example="La mejor calidad"),
 *                     @OA\Property(property="descripcion", type="string", example="Un producto elaborado por los mejores especialistas del país."),
 *                     @OA\Property(property="imagenPrincipal", type="string", example="https://example.com/imagen.jpg"),
 *                     @OA\Property(property="tituloBlog", type="string", example="Título del Blog"),
 *                     @OA\Property(property="subTituloBlog", type="string", example="Subtítulo del Blog"),
 *                     @OA\Property(
 *                         property="imagenesBlog",
 *                         type="array",
 *                         @OA\Items(
 *                             @OA\Property(property="url", type="string", example="https://example.com/imagen1.jpg"),
 *                             @OA\Property(property="parrafo", type="string", example="Descripción de la imagen")
 *                         )
 *                     ),
 *                     @OA\Property(property="video_id", type="string", example="dQw4w9WgXcQ"),
 *                     @OA\Property(property="videoBlog", type="string", example="https://example.com/video.mp4"),
 *                     @OA\Property(property="tituloVideoBlog", type="string", example="Título del video"),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-01T14:30:00Z")
 *                 )
 *             ),
 *             @OA\Property(property="message", type="string", example="Blogs obtenidos exitosamente")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error del servidor"
 *     )
 * )
 */

    public function index()
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto'])->get();

            $showBlog = $blog->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'titulo' => $blog->titulo,
                    'producto_id' => $blog->producto_id,
                    'link' => $blog->link,
                    'subtitulo1' => $blog->subtitulo1,
                    'subtitulo2' => $blog->subtitulo2,
                    'subtitulo3' => $blog->subtitulo3,
                    'video_id   ' => $this->obtenerIdVideoYoutube($blog->video_url),
                    'video_url' => $blog->video_url,
                    'video_titulo' => $blog->video_titulo,
                    'imagenes' => $blog->imagenes->map(function ($imagen) {
                        return [
                            'ruta_imagen' => $imagen->ruta_imagen,
                            'texto_alt' => $imagen->texto_alt,
                        ];
                    }),
                    'parrafos' => $blog->parrafos->map(function ($parrafo) {
                        return [
                            'parrafo' => $parrafo->parrafo,
                        ];
                    }),
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


    /**
     * Crear un nuevo blog
     * 
     * @OA\Post(
     *     path="/api/v1/blogs",
     *     summary="Crear un nuevo blog",
     *     description="Almacena un nuevo blog y retorna los datos creados",
     *     operationId="storeBlog",
     *     tags={"Blogs"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={
     *                     "producto_id",  
     *                     "titulo", 
     *                     "link", 
     *                     "parrafo", 
     *                     "descripcion", 
     *                     "imagen_principal", 
     *                     "titulo_blog", 
     *                     "subtitulo_beneficio", 
     *                     "url_video", 
     *                     "titulo_video"
     *                 },
     *                 @OA\Property(
     *                     property="titulo",
     *                     type="string",
     *                     example="Título del blog"
     *                 ),
     *                  @OA\Property(
     *                     property="link",
     *                     type="string",
     *                     example="Link a blog..."
     *                 ),
     *                 @OA\Property(
     *                     property="parrafo",
     *                     type="string",
     *                     example="Contenido del blog..."
     *                 ),
     *                 @OA\Property(
     *                     property="descripcion",
     *                     type="string",
     *                     example="Descripción del blog..."
     *                 ),
     *                 @OA\Property(
     *                     property="imagen_principal",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de imagen principal del blog"
     *                 ),
     *                 @OA\Property(
     *                     property="titulo_blog",
     *                     type="string",
     *                     example="Título del detalle del blog"
     *                 ),
     *                 @OA\Property(
     *                     property="subtitulo_beneficio",
     *                     type="string",
     *                     example="Subtítulo de beneficios"
     *                 ),
     *                 @OA\Property(
     *                     property="url_video",
     *                     type="string",
     *                     example="https://example.com/video.mp4"
     *                 ),
     *                 @OA\Property(
     *                     property="titulo_video",
     *                     type="string",
     *                     example="Título del video"
     *                 ),
     *                 @OA\Property(
     *                     property="imagenes",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="imagen",
     *                             type="string",
     *                             example="https://example.com/imagen-adicional.jpg",
     *                             format="binary",
     *                             description="Archivo de imagen adicional"
     *                         ),
     *                         @OA\Property(
     *                             property="parrafo",
     *                             type="string",
     *                             description="Descripción de la imagen adicional",
     *                             example="Parrafo de la imagen adicional"
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Blog creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Blog creado exitosamente")
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

    public function store(PostStoreBlog $request)
    {
        $datosValidados = $request->validated();
        DB::beginTransaction();
        try {
            $blog = Blog::create([
                "titulo" => $datosValidados["titulo"],
                "producto_id" => $datosValidados["producto_id"],
                "link" => $datosValidados["link"],
                "subtitulo1" => $datosValidados["subtitulo1"],
                "subtitulo2" => $datosValidados["subtitulo2"],
                "subtitulo3" => $datosValidados["subtitulo3"],
                "video_url" => $datosValidados["video_url"],
                "video_titulo" => $datosValidados["video_titulo"],
            ]);
            $imagenes = $request->file("imagenes");
            $contador = 0;
            foreach($imagenes as $item) {
                $nombreImagen = $item->hashName();
                Storage::putFileAs("imagenes/", $item, $nombreImagen);
                $blog->imagenes()->createMany([
                    ["ruta_imagen"=>"storage/imagenes/".$nombreImagen, "texto_alt"=>$datosValidados["textos_alt"][$contador]]
                ]);
                $contador++;
            }
            foreach($datosValidados["parrafos"] as $item) {
                $blog->parrafos()->createMany([
                    ["parrafo" =>$item]
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
     * Mostrar un blog específico
     * 
     * @OA\Get(
     *     path="/api/v1/blogs/{id}",
     *     summary="Muestra un blog específico",
     *     description="Retorna los datos de un blog según su ID",
     *     operationId="showBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del blog",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="producto_id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Título del blog"),
     *                 @OA\Property(property="link", type="string", example="Link a blog..."),
     *                 @OA\Property(property="parrafo", type="string", example="Contenido del blog..."),
     *                 @OA\Property(property="descripcion", type="string", example="Descripcion del blog..."),
     *                 @OA\Property(property="imagen_principal", type="string", example="https://example.com/imagen-principal.jpg"),
     *                 @OA\Property(property="titulo_blog", type="string", example="Título del detalle del blog"),
     *                 @OA\Property(property="subtitulo_beneficio", type="string", example="Subtítulo de beneficios"),
     *                 @OA\Property(property="imagenes", type="array", 
     *                     @OA\Items(
     *                         @OA\Property(property="url_imagen", type="string", example="https://example.com/imagen1.jpg"),
     *                         @OA\Property(property="parrafo_imagen", type="string", example="Descripción de la imagen")
     *                     )
     *                 ),
     *                 @OA\Property(property="url_video", type="string", example="https://example.com/video.mp4"),
     *                 @OA\Property(property="titulo_video", type="string", example="Título del video"),
     *                 @OA\Property(property="created_at", type="string")
     *             ),
     *             @OA\Property(property="message", type="string", example="Blog encontrado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */

    public function show(Blog $blog)
    {
        try {
            $blog->load(['imagenes', 'parrafos', 'producto']);

            $showBlog = [
                'id' => $blog->id,
                'titulo' => $blog->titulo,
                'producto_id' => $blog->producto_id,
                'link' => $blog->link,
                'subtitulo1' => $blog->subtitulo1,
                'subtitulo2' => $blog->subtitulo2,
                'subtitulo3' => $blog->subtitulo3,
                'video_id   ' => $this->obtenerIdVideoYoutube($blog->video_url),
                'video_url' => $blog->video_url,
                'video_titulo' => $blog->video_titulo,
                'imagenes' => $blog->imagenes->map(function ($imagen) {
                    return [
                        'ruta_imagen' => $imagen->ruta_imagen,
                        'texto_alt' => $imagen->texto_alt,
                    ];
                }),
                'parrafos' => $blog->parrafos->map(function ($parrafo) {
                    return [
                        'parrafo' => $parrafo->parrafo,
                    ];
                }),
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at
            ];

            return $this->apiResponse->successResponse($showBlog, 'Blog obtenido exitosamente', HttpStatusCode::OK);

        } catch(\Exception $e) {
            return $this->apiResponse->errorResponse('Error al obtener el blog: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

/**
 * Mostrar un blog por su link
 * 
 * @OA\Get(
 *     path="/api/v1/blogs/link/{link}",
 *     summary="Muestra un blog por su link",
 *     description="Retorna los datos de un blog, incluyendo detalles, imágenes y video, según su campo link",
 *     operationId="showBlogByLink",
 *     tags={"Blogs"},
 *     @OA\Parameter(
 *         name="link",
 *         in="path",
 *         description="Link único del blog",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Blog obtenido exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="producto_id", type="integer", example=5),
 *                 @OA\Property(property="titulo", type="string", example="Título del blog"),
 *                 @OA\Property(property="link", type="string", example="mi-blog-unico"),
 *                 @OA\Property(property="parrafo", type="string", example="Contenido introductorio del blog."),
 *                 @OA\Property(property="descripcion", type="string", example="Descripción completa del blog."),
 *                 @OA\Property(property="imagenPrincipal", type="string", example="https://example.com/imagen-principal.jpg"),
 *                 @OA\Property(property="tituloBlog", type="string", example="Título del detalle del blog"),
 *                 @OA\Property(property="subTituloBlog", type="string", example="Subtítulo de beneficios"),
 *                 @OA\Property(property="imagenesBlog", type="array", 
 *                     @OA\Items(type="string", example="https://example.com/imagen1.jpg")
 *                 ),
 *                 @OA\Property(property="parrafoImagenesBlog", type="array", 
 *                     @OA\Items(type="string", example="Texto descriptivo de la imagen")
 *                 ),
 *                 @OA\Property(property="video_id", type="string", example="dQw4w9WgXcQ"),
 *                 @OA\Property(property="videoBlog", type="string", example="https://www.youtube.com/watch?v=dQw4w9WgXcQ"),
 *                 @OA\Property(property="tituloVideoBlog", type="string", example="Título del video"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T12:00:00Z")
 *             ),
 *             @OA\Property(property="message", type="string", example="Blog obtenido exitosamente")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Blog no encontrado"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error al obtener el blog"
 *     )
 * )
 */

    public function showLink(string $link)
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto'])
                        ->where('link', $link)
                        ->firstOrFail();

            $showBlog = [
                'id' => $blog->id,
                'titulo' => $blog->titulo,
                'producto_id' => $blog->producto_id,
                'link' => $blog->link,
                'subtitulo1' => $blog->subtitulo1,
                'subtitulo2' => $blog->subtitulo2,
                'subtitulo3' => $blog->subtitulo3,
                'video_id   ' => $this->obtenerIdVideoYoutube($blog->video_url),
                'video_url' => $blog->video_url,
                'video_titulo' => $blog->video_titulo,
                'imagenes' => $blog->imagenes->map(function ($imagen) {
                    return [
                        'ruta_imagen' => $imagen->ruta_imagen,
                        'texto_alt' => $imagen->texto_alt,
                    ];
                }),
                'parrafos' => $blog->parrafos->map(function ($parrafo) {
                    return [
                        'parrafo' => $parrafo->parrafo,
                    ];
                }),
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
     * Actualizar un blog específico
     * 
     * @OA\Post(
     *     path="/api/v1/blogs/{id}",
     *     summary="Actualiza un blog específico",
     *     description="Actualiza los datos de un blog existente según su ID",
     *     operationId="updateBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del blog a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="titulo", type="string", example="Título actualizado del blog"),
     *             @OA\Property(property="link", type="string", example="Link a blog..."),
     *             @OA\Property(property="parrafo", type="string", example="Contenido actualizado del blog..."),
     *             @OA\Property(property="descripcion", type="string", example="Descripcion actualizado del blog..."),
     *             @OA\Property(property="imagen_principal", type="string", example="https://example.com/nueva-imagen.jpg"),
     *             @OA\Property(property="titulo_blog", type="string", example="Título del detalle actualizado"),
     *             @OA\Property(property="subtitulo_beneficio", type="string", example="Subtítulo de beneficios actualizado"),
     *             @OA\Property(property="imagenes", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="url_imagen", type="string", example="https://example.com/nueva-imagen1.jpg"),
     *                     @OA\Property(property="parrafo_imagen", type="string", example="Descripción de la imagen actualizada")
     *                 )
     *             ),
     *             @OA\Property(property="url_video", type="string", example="https://example.com/nuevo-video.mp4"),
     *             @OA\Property(property="titulo_video", type="string", example="Título del video actualizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Blog actualizado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado"
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

    public function update(UpdateBlog $request, Blog $blog)
    {
        $datosValidados = $request->validated();
        DB::beginTransaction();
        try {
            $blog->update([
                "titulo" => $datosValidados["titulo"],
                "producto_id" => $datosValidados["producto_id"],
                "link" => $datosValidados["link"],
                "subtitulo1" => $datosValidados["subtitulo1"],
                "subtitulo2" => $datosValidados["subtitulo2"],
                "subtitulo3" => $datosValidados["subtitulo3"],
                "video_url" => $datosValidados["video_url"],
                "video_titulo" => $datosValidados["video_titulo"],
            ]);
            $rutasImagenes = [];
            foreach($blog->imagenes as $item) {
                array_push($rutasImagenes, str_replace("storage/", "", $item["ruta_imagen"]));
            }
            Storage::delete($rutasImagenes);
            $blog->imagenes()->delete();
            $blog->parrafos()->delete();
            $imagenes = $request->file("imagenes");
            $contador = 0;
            foreach($imagenes as $item) {
                $nombreImagen = $item->hashName();
                Storage::putFileAs("imagenes/", $item, $nombreImagen);
                $blog->imagenes()->createMany([
                    ["ruta_imagen"=>"storage/imagenes/".$nombreImagen, "texto_alt"=>$datosValidados["textos_alt"][$contador]]
                ]);
                $contador++;
            }
            foreach($datosValidados["parrafos"] as $item) {
                $blog->parrafos()->createMany([
                    ["parrafo" =>$item]
                ]);
            }

            DB::commit();
            return $this->apiResponse->successResponse(null, 'Blog actualizado exitosamente', HttpStatusCode::OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->errorResponse('Error al actualizar el blog: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Eliminar un blog específico
     * 
     * @OA\Delete(
     *     path="/api/v1/blogs/{id}",
     *     summary="Elimina un blog específico",
     *     description="Elimina un blog existente según su ID",
     *     operationId="destroyBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del blog a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Blog eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */

    public function destroy(Blog $blog)
    {
        try {
            $rutasImagenes = [];
            foreach($blog->imagenes as $item) {
                array_push($rutasImagenes, str_replace("storage/", "", $item["ruta_imagen"]));
            }
            Storage::delete($rutasImagenes);
            $blog->delete();

            return $this->apiResponse->successResponse(
                $blog,
                'Blog eliminado exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
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
