<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostBlog\PostStoreBlog;
use App\Http\Requests\PostBlog\UpdateBlog;
use App\Services\ApiResponseService;
use App\Models\ImagenBlog;
use App\Services\ImgurService;
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
    protected $imgurService;
    
    public function __construct(ApiResponseService $apiResponse, ImgurService $imgurService) {
        $this->apiResponse = $apiResponse;
        $this->imgurService = $imgurService;
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
            $blog = Blog::with(['imagenes', 'video', 'detalle', 'producto'])->get();

            $showBlog = $blog->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'producto_id' => $blog->producto_id,
                    'titulo' => $blog->titulo,
                    'link' => $blog->link,
                    'parrafo' => $blog->parrafo,
                    'descripcion' => $blog->descripcion,
                    'imagenPrincipal' => $blog->imagen_principal,
                    'tituloBlog' => optional($blog->detalle)->titulo_blog,
                    'subTituloBlog' => optional($blog->detalle)->subtitulo_beneficio,
                    'imagenesBlog' => $blog->imagenes->map(function ($imagen) {
                        return [
                            'url' => $imagen->url_imagen,
                            'parrafo' => $imagen->parrafo_imagen,
                        ];
                    }),
                    'video_id   ' => $this->obtenerIdVideoYoutube(optional($blog->video)->url_video),
                    'videoBlog' => optional($blog->video)->url_video,
                    'tituloVideoBlog' => optional($blog->video)->titulo_video,
                    'created_at' => $blog->created_at
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
        $data = $request->validated();
        DB::beginTransaction();
        try {

            $data = $request->validated();

            //Validar que el producto existe
            $request->validate(
                [
                    'producto_id' => ['required', 'integer', 'exists:productos,id'],
                ]
            );


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

            // Crear el blog (excluyendo relaciones)
            $blog = Blog::create(array_diff_key($data, array_flip([
                'imagenes',
                'video',
                'detalle'
            ])));

            // Relación: detalle del blog
            if (!empty($data['titulo_blog']) || !empty($data['subtitulo_beneficio'])) {
                $blog->detalle()->create([
                    'id_blog' => $blog->id,  // Vincular al blog creado
                    'titulo_blog' => $data['titulo_blog'] ?? null,
                    'subtitulo_beneficio' => $data['subtitulo_beneficio'] ?? null,
                ]);
            }

            // Relación: video
            if (!empty($data['url_video']) || !empty($data['titulo_video'])) {
                $blog->video()->create([
                    'id_blog' => $blog->id,  // Vincular al blog creado
                    'url_video' => $data['url_video'] ?? null,
                    'titulo_video' => $data['titulo_video'] ?? null,
                ]);
            }
            // Relación: imágenes adicionales
            if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
                foreach ($data['imagenes'] as $index => $item) {
                    if (isset($item['imagen']) && $item['imagen'] instanceof \Illuminate\Http\UploadedFile) {
                        $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!in_array($item['imagen']->getMimeType(), $validMimeTypes)) {
                            throw new \Exception("El archivo de imagen adicional en la posición $index no es válido.");
                        }

                        $uploadedImageUrl = $this->imgurService->uploadImage($item['imagen']);
                        if (!$uploadedImageUrl) {
                            throw new \Exception("Falló la subida de la imagen adicional en la posición $index.");
                        }

                        $blog->imagenes()->create([
                            'url_imagen' => $uploadedImageUrl,
                            'parrafo_imagen' => $item['parrafo'] ?? '',
                            'id_blog' => $blog->id,
                        ]);
                    } else {
                        throw new \Exception("Falta imagen válida en el índice $index.");
                    }
                }
            } else {
                throw new \Exception("Array de imágenes vacío o mal estructurado.");
            }


            // ✅ Las relaciones ya están cargadas al momento de la creación, no es necesario cargar de nuevo
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

    public function show($id)
    {
        try {
            $blog = Blog::with(['imagenes', 'detalle', 'video'])->findOrFail($id);

            $showBlog = [
                'id' => $blog->id,
                'producto_id' => $blog->producto_id,
                'titulo' => $blog->titulo,
                'link' => $blog->link,
                'parrafo' => $blog->parrafo,
                'descripcion' => $blog->descripcion,
                'imagenPrincipal' => $blog->imagen_principal,
                'tituloBlog' => optional($blog->detalle)->titulo_blog, 
                'subTituloBlog' => optional($blog->detalle)->subtitulo_beneficio,
                'imagenesBlog' => $blog->imagenes->pluck('url_imagen'), 
                'parrafoImagenesBlog' => $blog->imagenes->pluck('parrafo_imagen'),
                'video_id' => $this->obtenerIdVideoYoutube(optional($blog->video)->url_video),
                'videoBlog' => optional($blog->video)->url_video, 
                'tituloVideoBlog' => optional($blog->video)->titulo_video,
                'created_at' => $blog->created_at,
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

    public function showLink($link)
    {
        try {
            $blog = Blog::with(['imagenes', 'detalle', 'video'])
                        ->where('link', $link)
                        ->firstOrFail();

            $showBlog = [
                'id' => $blog->id,
                'producto_id' => $blog->producto_id,
                'titulo' => $blog->titulo,
                'link' => $blog->link,
                'parrafo' => $blog->parrafo,
                'descripcion' => $blog->descripcion,
                'imagenPrincipal' => $blog->imagen_principal,
                'tituloBlog' => optional($blog->detalle)->titulo_blog, 
                'subTituloBlog' => optional($blog->detalle)->subtitulo_beneficio,
                'imagenesBlog' => $blog->imagenes->pluck('url_imagen'), 
                'parrafoImagenesBlog' => $blog->imagenes->pluck('parrafo_imagen'),
                'video_id' => $this->obtenerIdVideoYoutube(optional($blog->video)->url_video),
                'videoBlog' => optional($blog->video)->url_video, 
                'tituloVideoBlog' => optional($blog->video)->titulo_video,
                'created_at' => $blog->created_at,
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

    public function update(UpdateBlog $request, $id)
    {
        $data = $request->validated();

        try {
            $blog = Blog::findOrFail($id);

            $producto = Producto::find($data['producto_id']);
            if (!$producto) {
                throw new \Exception("El producto con ID {$data['producto_id']} no existe.");
            }

            // Validar y subir imagen principal si viene en el request
            if (!empty($data['imagen_principal']) && $data['imagen_principal'] instanceof \Illuminate\Http\UploadedFile) {
                $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($data['imagen_principal']->getMimeType(), $validMimeTypes)) {
                    throw new \Exception("El archivo de imagen principal no es válido.");
                }
                $uploadedMainImageUrl = $this->imgurService->uploadImage($data['imagen_principal']);
                if (!$uploadedMainImageUrl) {
                    throw new \Exception("Falló la subida de la imagen principal.");
                }
                $data['imagen_principal'] = $uploadedMainImageUrl;
            }

            $blog->update([
                'producto_id' => $data['producto_id'],
                'titulo' => $data['titulo'],
                'link' => $data['link'],
                'parrafo' => $data['parrafo'],
                'descripcion' => $data['descripcion'],
                'imagen_principal' => $data['imagen_principal'] ?? $blog->imagen_principal,
                'updated_at' => now(),
            ]);

            // Imágenes adicionales
            if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
                $blog->imagenes()->delete(); // Eliminar imágenes anteriores

                $imagenes = collect($data['imagenes'])->map(fn($imagen) => [
                    'url_imagen' => $imagen['imagen'],
                    'parrafo_imagen' => $imagen['parrafo'],
                    'id_blog' => $blog->id,
                ])->toArray();

                ImagenBlog::insert($imagenes);
            }

            // Manejo detalle blog
            $detalle = $blog->detalle()->first();
            if ($detalle) {
                $detalle->update([
                    'titulo_blog' => $data['titulo_blog'],
                    'subtitulo_beneficio' => $data['subtitulo_beneficio'],
                ]);
            } else {
                $blog->detalle()->create([
                    'titulo_blog' => $data['titulo_blog'],
                    'subtitulo_beneficio' => $data['subtitulo_beneficio'],
                ]);
            }

            // Manejo video blog
            $video = $blog->video()->first();
            if ($video) {
                $video->update([
                    'url_video' => $data['url_video'],
                    'titulo_video' => $data['titulo_video'],
                ]);
            } else {
                $blog->video()->create([
                    'url_video' => $data['url_video'],
                    'titulo_video' => $data['titulo_video'],
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

    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);
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
