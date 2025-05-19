<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostBlog\PostStoreBlog;
use App\Repositories\V1\Contracts\BlogRepositoryInterface;
use App\Services\ApiResponseService;
use App\Services\ImgurService;
use App\Models\Blog;
use App\Http\Contains\HttpStatusCode;

class BlogController extends Controller
{
    protected $blogRepository;
    protected ApiResponseService $apiResponse;
    protected $imgurService;
    
    public function __construct(BlogRepositoryInterface $blogRepository, ApiResponseService $apiResponse, ImgurService $imgurService) {
        $this->blogRepository = $blogRepository;
        $this->apiResponse = $apiResponse;
        $this->imgurService = $imgurService;
    }

    public function index()
    {
        try{
            $blog = Blog::with(['imagenes', 'video', 'detalle'])->get();

            $showBlog = $blog->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'titulo' => $blog->titulo,
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
                    'video_id' => $this->obtenerIdVideoYoutube(optional($blog->video)->url_video),
                    'videoBlog' => optional($blog->video)->url_video, 
                    'tituloVideoBlog' => optional($blog->video)->titulo_video,
                    'created_at' => $blog->created_at
                ];
            });

            return $this->apiResponse->successResponse($showBlog, 'Blogs obtenidos exitosamente', 
            HttpStatusCode::OK);
        }
        catch(\Exception $e)
        {
            return $this->apiResponse->errorResponse('Error al obtener los blogs: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function store(PostStoreBlog $request)
    {
        return $this->blogRepository->create($request->validated());
    }

    public function show($id)
    {
        try {
            $blog = Blog::with(['imagenes', 'video', 'detalle'])->findOrFail($id);

            $showBlog = [
                'id' => $blog->id,
                'titulo' => $blog->titulo,
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

            return $this->apiResponse->successResponse($showBlog, 'Blog obtenido exitosamente',
            HttpStatusCode::OK);

        } catch(\Exception $e) {
            return $this->apiResponse->errorResponse('Error al obtener el blog: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function update(PostStoreBlog $request, $blog)
    {
        return $this->blogRepository->update($request->validated(), $blog);
    }

    public function destroy($id)
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

     private function obtenerIdVideoYoutube($url)
    {
        $pattern = '%(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|v/|shorts/))([^\s&?]+)%';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
