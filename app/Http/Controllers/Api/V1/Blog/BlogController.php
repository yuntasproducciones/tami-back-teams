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
    public function index()
    {
        try {
            $blogs = Blog::with(['imagenes', 'video', 'detalle', 'producto'])->get();
            $formattedBlogs = $blogs->map(fn($blog) => $this->formatBlogResponse($blog));

            return $this->apiResponse->successResponse(
                $formattedBlogs,
                'Blogs obtenidos exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error al obtener los blogs');
        }
    }

    public function store(PostStoreBlog $request)
    {
        $data = $request->validated();
        
        DB::beginTransaction();
        try {

            $data['imagen_principal'] = $this->guardarImagen($data['imagen_principal']);

            $blog = $this->createBlog($data);

            // Procesar imágenes
            $this->createBlogRelations($blog, $data);

            DB::commit();
            return $this->apiResponse->successResponse(
                $blog->fresh(), 
                'Blog creado con éxito.', 
                HttpStatusCode::CREATED
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, 'Error al crear el blog');
        }
    }

    public function show($id)
    {
        try {
            $blog = Blog::with(['imagenes', 'detalle', 'video'])->findOrFail($id);
            $formattedBlog = $this->formatBlogDetailResponse($blog);

            return $this->apiResponse->successResponse(
                $formattedBlog, 
                'Blog obtenido exitosamente', 
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error al obtener el blog');
        }
    }

    public function showLink($link)
    {
        try {
            $blog = Blog::with(['imagenes', 'detalle', 'video'])
                ->where('link', $link)
                ->firstOrFail();
                
            $formattedBlog = $this->formatBlogDetailResponse($blog);

            return $this->apiResponse->successResponse(
                $formattedBlog,
                'Blog obtenido exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error al obtener el blog');
        }
    }


    public function update(UpdateBlog $request, $id)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $blog = Blog::findOrFail($id);
        
            $data['imagen_principal'] = $this->guardarImagen($data['imagen_principal']);

            // Actualizar blog principal
            $this->updateBlog($blog, $data);

            // Actualizar relaciones
            $this->updateBlogRelations($blog, $data);

            $formattedBlog = $this->formatBlogDetailResponse($blog);

            return $this->apiResponse->successResponse(
                $formattedBlog,
                'Blog actualizado exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, 'Error al actualizar el blog');
        }
    }
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
            return $this->handleException($e, 'Error al eliminar el blog');
        }
    }

private function formatBlogResponse($blog): array
{
    // Debug: Verificar si existe video y su URL
    $videoUrl = optional($blog->video)->url_video;
    $videoId = $this->obtenerIdVideoYoutube($videoUrl);
    
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
        'imagenesBlog' => $blog->imagenes->map(fn($imagen) => [
            'url' => $imagen->url_imagen,
            'parrafo' => $imagen->parrafo_imagen,
        ]),
        'video_id' => $videoId,
        'videoBlog' => $videoUrl,
        'tituloVideoBlog' => optional($blog->video)->titulo_video,
        'created_at' => $blog->created_at,
        
        // Debug temporal - eliminar después
        'debug_video_exists' => $blog->video ? true : false,
        'debug_video_url' => $videoUrl,
        'debug_video_id_processed' => $videoId,
    ];
}

    private function formatBlogDetailResponse($blog): array
    {
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
            'imagenesBlog' => $blog->imagenes->pluck('url_imagen'),
            'parrafoImagenesBlog' => $blog->imagenes->pluck('parrafo_imagen'),
            'video_id' => $this->obtenerIdVideoYoutube(optional($blog->video)->url_video),
            'videoBlog' => optional($blog->video)->url_video,
            'tituloVideoBlog' => optional($blog->video)->titulo_video,
            'created_at' => $blog->created_at,
        ];
    }

    private function guardarImagen($image): string
    {
        Storage::putFileAs("public/imagenes", $image, $image->hashName());
        return "/storage/imagenes/" . $image->hashName();
    }

    private function createBlog(array $data): Blog
    {
        return Blog::create(array_diff_key($data, array_flip([
            'imagenes', 'video', 'detalle', 'titulo_blog', 'subtitulo_beneficio',
            'url_video', 'titulo_video'
        ])));
    }

    private function updateBlog(Blog $blog, array $data): void
    {
        $blog->update([
            'producto_id' => $data['producto_id'],
            'titulo' => $data['titulo'],
            'link' => $data['link'],
            'parrafo' => $data['parrafo'],
            'descripcion' => $data['descripcion'],
            'imagen_principal' => $data['imagen_principal'],
            'updated_at' => now(),
        ]);
    }

    private function createBlogRelations(Blog $blog, array $data): void
    {
        $this->createBlogDetail($blog, $data);
        $this->createBlogVideo($blog, $data);
        $this->createBlogImages($blog, $data);
    }

    private function updateBlogRelations(Blog $blog, array $data): void
    {
        $this->updateBlogDetail($blog, $data);
        $this->updateBlogVideo($blog, $data);
        $this->updateBlogImages($blog, $data);
    }

    private function createBlogDetail(Blog $blog, array $data): void
    {
        $blog->detalle()->create([
            'id_blog' => $blog->id,
            'titulo_blog' => $data['titulo_blog'],
            'subtitulo_beneficio' => $data['subtitulo_beneficio'],
        ]);
    }

    private function updateBlogDetail(Blog $blog, array $data): void
    {
        $detalle = $blog->detalle()->first();
        
        if ($detalle) {
            $detalle->update([
                'titulo_blog' => $data['titulo_blog'],
                'subtitulo_beneficio' => $data['subtitulo_beneficio'],
            ]);
        } else {
            $this->createBlogDetail($blog, $data);
        }
    }

    private function createBlogVideo(Blog $blog, array $data): void
    {
        $blog->video()->create([
            'id_blog' => $blog->id,
            'url_video' => $data['url_video'],
            'titulo_video' => $data['titulo_video'],
        ]);
    }

    private function updateBlogVideo(Blog $blog, array $data): void
    {
        $video = $blog->video()->first();
        
        if ($video) {
            $video->update([
                'url_video' => $data['url_video'],
                'titulo_video' => $data['titulo_video'],
            ]);
        } else {
            $this->createBlogVideo($blog, $data);
        }
    }

    private function createBlogImages(Blog $blog, array $data): void
    {
        foreach ($data['imagenes'] as $item) {
            $uploadedImageUrl = $this->guardarImagen($item['imagen']);

            $blog->imagenes()->create([
                'url_imagen' => $uploadedImageUrl,
                'parrafo_imagen' => $item['parrafo'] ?? '',
                'id_blog' => $blog->id,
            ]);
        }
    }

    private function updateBlogImages(Blog $blog, array $data): void
    {
        $blog->imagenes()->delete(); // Eliminar anteriores
        $this->createBlogImages($blog, $data);
    }
    private function handleException(\Exception $e, string $message): \Illuminate\Http\JsonResponse
    {
        return $this->apiResponse->errorResponse(
            $message . ': ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR
        );
    }

private function obtenerIdVideoYoutube($url): ?string
{
    if (!$url || empty(trim($url))) {
        return null;
    }
    
    // Limpiar la URL
    $url = trim($url);
    
    // Patrones para diferentes formatos de YouTube
    $patterns = [
        // youtube.com/watch?v=ID
        '/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/',
        // youtu.be/ID
        '/(?:youtu\.be\/)([a-zA-Z0-9_-]{11})/',
        // youtube.com/embed/ID
        '/(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
        // youtube.com/v/ID
        '/(?:youtube\.com\/v\/)([a-zA-Z0-9_-]{11})/',
        // youtube.com/shorts/ID
        '/(?:youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/',
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
    }
    
    return null;
}
}