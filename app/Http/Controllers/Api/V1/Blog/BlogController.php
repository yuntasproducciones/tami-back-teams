<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Contains\HttpStatusCode;
use App\Http\Requests\PostBlog\PostStoreBlog;
use App\Models\DetalleBlog;
use App\Models\ImagenBlog;
use App\Models\VideoBlog;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index()
    {
        try {
            $blog = Blog::with(['imagenes','video','detalle'])->get();

            $showBlog = $blog->map(function($blog) {
                return [
                    'id' => $blog->id,
                    'titulo' => $blog->titulo,
                    'parrafo' => $blog->parrafo,
                    'imagenPrincipal' => $blog->imagen_principal,
                    'tituloBlog' => $blog->detalle->pluck('titulo_blog', 'id_blog'),
                    'subTituloBlog' => $blog->detalle->pluck('subtitulo_beneficio', 'id_blog'),
                    'imagenesBlog' => $blog->imagenes->pluck('url_imagen','id_blog'),
                    'parrafoImagenesBlog' => $blog->imagenes->pluck('parrafo_imagen','id_blog'),
                    'videoBlog' => $blog->video->pluck('url_video','id_blog'),
                    'tituloVideoBlog' => $blog->video->pluck('titulo_video','id_blog')
                ];
            });

            return $this->successResponse($showBlog, 'Blogs obtenidos exitosamente', 
            HttpStatusCode::OK);

        } catch(\Exception $e) {
            return $this->errorResponse('Error al obtener los blogs: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function store(PostStoreBlog $request)
    {
        try {
            DB::beginTransaction(); 

            $blog = Blog::create($request->only('titulo', 'parrafo', 'imagen_principal'));

            if ($request->has('imagenes')) {
                $imagenes = collect($request->imagenes)->map(fn($imagen) => [
                    'url' => $imagen['url_imagen'],
                    'parrafo_imagen' => $imagen['parrafo_imagen'],
                    'id_blog' => $blog->id
                ])->toArray();

                ImagenBlog::insert($imagenes);
            }

            DetalleBlog::create(array_merge(
                $request->only('titulo_blog', 'subtitulo_beneficio'),
                ['id_blog' => $blog->id]
            ));

            VideoBlog::create(array_merge(
                $request->only('url_video', 'titulo_video'),
                ['id_blog' => $blog->id]
            ));

            DB::commit(); 

            return $this->successResponse($blog, 'Blog creado con éxito.', HttpStatusCode::CREATED);

        } catch (\Exception $e) {
            DB::rollBack(); 

            return $this->errorResponse('Error al crear el blog: ' . $e->getMessage(), 
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function show(PostStoreBlog $blog)
    {
        try {
            $blog = Blog::findOrFail($blog->id);

            return $this->successResponse($blog, 'Blog obtenido exitosamente',
            HttpStatusCode::OK);

        } catch(\Exception $e) {
            return $this->errorResponse('Error al obtener el blog: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function update(PostStoreBlog $request, Blog $blog)
    {
        try {
            DB::beginTransaction();

            $blog = Blog::findOrFail($blog->id);
            $blog->update($request->only('titulo', 'parrafo', 'imagen_principal'));

            if ($request->has('imagenes')) {
                $blog->imagenes()->delete();

                $imagenes = collect($request->imagenes)->map(fn($imagen) => [
                    'url' => $imagen['url_imagen'],
                    'parrafo_imagen' => $imagen['parrafo_imagen'],
                    'id_blog' => $blog->id
                ])->toArray();

                ImagenBlog::insert($imagenes);
            }

            $detalle = DetalleBlog::where('id_blog', $blog->id)->first()->update(
                $request->only('titulo_blog', 'subtitulo_beneficio'));
 
            $video = VideoBlog::where('id_blog', $blog->id)->first()->update(
                $request->only('url_video', 'titulo_video'));

            $confirmUpdate = $blog->wasChanged() || $detalle > 0 || $video > 0;

            DB::commit(); 

            return $this->successResponse(
                $confirmUpdate ? 'Blog actualizado exitosamente' : 'No se realizaron cambios:\n' . 
                "Blog: " . $blog . 
                "\nDetalleBlog: " .$detalle .
                "\Video: " . $video,
                $confirmUpdate ? HttpStatusCode::OK : HttpStatusCode::NOT_IMPLEMENTED
            );
            
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al actualizar el blog: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
            
        }
    }

    public function destroy(Blog $blog)
    {
        try {
            $blog = Blog::findOrFail($blog->id);
            $blog->delete();

            return $this->successResponse($blog, 'Blog eliminado exitosamente',
            HttpStatusCode::OK);

        } catch(\Exception $e) {
            return $this->errorResponse('Error al eliminar el blog: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
