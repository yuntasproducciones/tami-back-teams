<?php

namespace App\Repositories\Blog;

use App\Http\Contains\HttpStatusCode;
use App\Models\Blog;
use App\Models\DetalleBlog;
use App\Models\ImagenBlog;
use App\Models\VideoBlog;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\DB;;

/**
     * @OA\Tag(
     *     name="Blogs",
     *     description="API para gestión de blogs"
     * )
*/
class BlogRepository implements BlogRepositoryInterface
{
    protected ApiResponseService $apiResponse;

    public function __construct(ApiResponseService $apiResponse) {
        $this->apiResponse = $apiResponse;
    }

    /**
     * Obtener listado de blog
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
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titulo", type="string", example="Producto Premium"),
     *                     @OA\Property(property="parrafo", type="string", example="La mejor calidad"),
     *                     @OA\Property(property="imagenPrincipal", type="string", example="https://example.com/imagen.jpg"),
     *                     @OA\Property(property="tituloBlog", type="string", example="Título del Blog"),
     *                     @OA\Property(property="subTituloBlog", type="string", example="Subtítulo del Blog"),
     *                     @OA\Property(
     *                         property="imagenesBlog",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="url_imagen", type="string", example="https://example.com/imagen1.jpg"),
     *                             @OA\Property(property="parrafo_imagen", type="string", example="Descripción de la imagen")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="parrafoImagenesBlog",
     *                         type="array",
     *                         @OA\Items(type="string", example="Descripción adicional de la imagen")
     *                     ),
     *                     @OA\Property(property="videoBlog", type="string", example="https://example.com/video.mp4"),
     *                     @OA\Property(property="tituloVideoBlog", type="string", example="Título del video")
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
    public function getAll()
    {
        try {
            $blog = Blog::with(['imagenes', 'video', 'detalle'])->get();

            $showBlog = $blog->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'titulo' => $blog->titulo,
                    'parrafo' => $blog->parrafo,
                    'imagenPrincipal' => $blog->imagen_principal,
                    'tituloBlog' => optional($blog->detalle)->titulo_blog, 
                    'subTituloBlog' => optional($blog->detalle)->subtitulo_beneficio,
                    'imagenesBlog' => optional($blog->imagenes->pluck('url_imagen')), 
                    'parrafoImagenesBlog' => optional($blog->imagenes->pluck('parrafo_imagen')),
                    'videoBlog' => optional($blog->video)->url_video, 
                    'tituloVideoBlog' => optional($blog->video)->titulo_video,
                ];
            });

            return $this->apiResponse->successResponse($showBlog, 'Blogs obtenidos exitosamente', 
            HttpStatusCode::OK);
        
        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse('Error al obtener los blogs: ' . $e->getMessage(),
             HttpStatusCode::INTERNAL_SERVER_ERROR);
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
     *         @OA\JsonContent(
     *             required={"titulo", "parrafo", "imagen_principal", "titulo_blog", "subtitulo_beneficio", "url_video", "titulo_video"},
     *             @OA\Property(property="titulo", type="string", example="Título del blog"),
     *             @OA\Property(property="parrafo", type="string", example="Contenido del blog..."),
     *             @OA\Property(property="imagen_principal", type="string", example="https://example.com/imagen-principal.jpg"),
     *             @OA\Property(property="titulo_blog", type="string", example="Título del detalle del blog"),
     *             @OA\Property(property="subtitulo_beneficio", type="string", example="Subtítulo de beneficios"),
     *             @OA\Property(
     *                 property="imagenes",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="url_imagen", type="string", example="https://example.com/imagen1.jpg"),
     *                     @OA\Property(property="parrafo_imagen", type="string", example="Descripción de la imagen")
     *                 )
     *             ),
     *             @OA\Property(property="url_video", type="string", example="https://example.com/video.mp4"),
     *             @OA\Property(property="titulo_video", type="string", example="Título del video")
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
    public function create(array $data)
    {
        try {
            DB::beginTransaction(); 

            $blog = Blog::create([
                'titulo' => $data['titulo'],
                'parrafo' => $data['parrafo'],
                'imagen_principal' => $data['imagen_principal'],
            ]);

            if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
                $imagenes = collect($data['imagenes'])->map(fn($imagen) => [
                    'url_imagen' => $imagen['url_imagen'],
                    'parrafo_imagen' => $imagen['parrafo_imagen'],
                    'id_blog' => $blog->id,
                ])->toArray();

                ImagenBlog::insert($imagenes);
            }

            DetalleBlog::create([
                'titulo_blog' => $data['titulo_blog'],
                'subtitulo_beneficio' => $data['subtitulo_beneficio'],
                'id_blog' => $blog->id,
            ]);

            VideoBlog::create([
                'url_video' => $data['url_video'],
                'titulo_video' => $data['titulo_video'],
                'id_blog' => $blog->id,
            ]);

            DB::commit(); 

            return $this->apiResponse->successResponse($blog, 'Blog creado con éxito.',
             HttpStatusCode::CREATED);

        } catch (\Exception $e) {
            DB::rollBack(); 

            return $this->apiResponse->errorResponse('Error al crear el blog: ' . $e->getMessage(), 
                HttpStatusCode::INTERNAL_SERVER_ERROR);
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
     *                 @OA\Property(property="titulo", type="string", example="Título del blog"),
     *                 @OA\Property(property="parrafo", type="string", example="Contenido del blog..."),
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
     *                 @OA\Property(property="titulo_video", type="string", example="Título del video")
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
    public function find($id)
    {
        try {
            $blog = Blog::with(['imagenes', 'video', 'detalle'])->findOrFail($id);

            $showBlog = [
                'id' => $blog->id,
                'titulo' => $blog->titulo,
                'parrafo' => $blog->parrafo,
                'imagenPrincipal' => $blog->imagen_principal,
                'tituloBlog' => optional($blog->detalle)->titulo_blog, 
                'subTituloBlog' => optional($blog->detalle)->subtitulo_beneficio,
                'imagenesBlog' => $blog->imagenes->pluck('url_imagen'), 
                'parrafoImagenesBlog' => $blog->imagenes->pluck('parrafo_imagen'),
                'videoBlog' => optional($blog->video)->url_video, 
                'tituloVideoBlog' => optional($blog->video)->titulo_video,
            ];

            return $this->apiResponse->successResponse($showBlog, 'Blog obtenido exitosamente',
            HttpStatusCode::OK);

        } catch(\Exception $e) {
            return $this->apiResponse->errorResponse('Error al obtener el blog: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Actualizar un blog específico
     * 
     * @OA\Put(
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
     *             @OA\Property(property="parrafo", type="string", example="Contenido actualizado del blog..."),
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
    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el blog
            $blog = Blog::findOrFail($id);
            $blog->update([
                'titulo' => $data['titulo'],
                'parrafo' => $data['parrafo'],
                'imagen_principal' => $data['imagen_principal'],
            ]);

            // Manejo de imágenes
            if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
                $blog->imagenes()->delete(); // Eliminar imágenes anteriores

                $imagenes = collect($data['imagenes'])->map(fn($imagen) => [
                    'url_imagen' => $imagen['url_imagen'],
                    'parrafo_imagen' => $imagen['parrafo_imagen'],
                    'id_blog' => $blog->id
                ])->toArray();

                ImagenBlog::insert($imagenes);
            }

            // Manejo de detalles del blog
            $detalle = DetalleBlog::where('id_blog', $blog->id)->first();
            if ($detalle) {
                $detalle->update([
                    'titulo_blog' => $data['titulo_blog'],
                    'subtitulo_beneficio' => $data['subtitulo_beneficio'],
                ]);
            } else {
                DetalleBlog::create([
                    'id_blog' => $blog->id,
                    'titulo_blog' => $data['titulo_blog'],
                    'subtitulo_beneficio' => $data['subtitulo_beneficio'],
                ]);
            }

            // Manejo de video del blog
            $video = VideoBlog::where('id_blog', $blog->id)->first();
            if ($video) {
                $video->update([
                    'url_video' => $data['url_video'],
                    'titulo_video' => $data['titulo_video'],
                ]);
            } else {
                VideoBlog::create([
                    'id_blog' => $blog->id,
                    'url_video' => $data['url_video'],
                    'titulo_video' => $data['titulo_video'],
                ]);
            }

            DB::commit(); 

            return $this->apiResponse->successResponse(
                null,
                'Blog actualizado exitosamente',
                HttpStatusCode::OK
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->errorResponse(
                'Error al actualizar el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
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
    public function delete($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $blog->delete();

            return $this->apiResponse->successResponse($blog, 'Blog eliminado exitosamente',
            HttpStatusCode::OK);

        } catch(\Exception $e) {
            return $this->apiResponse->errorResponse('Error al eliminar el blog: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
