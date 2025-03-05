<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Contains\HttpStatusCode;
use App\Http\Controllers\Api\V1\BasicController;
use App\Http\Requests\PostBlog\PostStoreBlog;
use App\Models\DetalleBlog;
use App\Models\ImagenBlog;
use App\Models\VideoBlog;
use Illuminate\Support\Facades\DB;

class BlogController extends BasicController
{
    public function index()
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
                    'imagenesBlog' => $blog->imagenes->pluck('url_imagen'), 
                    'parrafoImagenesBlog' => $blog->imagenes->pluck('parrafo_imagen'),
                    'videoBlog' => optional($blog->video)->url_video, 
                    'tituloVideoBlog' => optional($blog->video)->titulo_video,
                ];
            });

            return $this->successResponse($showBlog, 'Blogs obtenidos exitosamente', 
            HttpStatusCode::OK);
        
        } catch (\Exception $e) {
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
                    'url_imagen' => $imagen['url_imagen'],
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

    public function show($id)
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

            return $this->successResponse($showBlog, 'Blog obtenido exitosamente',
            HttpStatusCode::OK);

        } catch(\Exception $e) {
            return $this->errorResponse('Error al obtener el blog: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function update(PostStoreBlog $request, $blog)
    {
        try {
            DB::beginTransaction();

            $blog = Blog::findOrFail($blog);
            $blog->update($request->only('titulo', 'parrafo', 'imagen_principal'));

            if ($request->has('imagenes')) {
                $blog->imagenes()->delete();

                $imagenes = collect($request->imagenes)->map(fn($imagen) => [
                    'url_imagen' => $imagen['url_imagen'],
                    'parrafo_imagen' => $imagen['parrafo_imagen'],
                    'id_blog' => $blog->id
                ])->toArray();

                ImagenBlog::insert($imagenes);
            }

            $detalle = DetalleBlog::where('id_blog', $blog->id)->first();
            $detalle ? $detalle->update($request->only(
                'titulo_blog', 'subtitulo_beneficio'))
            : DetalleBlog::create([
                'id_blog' => $blog->id,
                'titulo_blog' => $request->titulo_blog,
                'subtitulo_beneficio' => $request->subtitulo_beneficio
            ]);

            $video = VideoBlog::where('id_blog', $blog->id)->first();

            $video ? $video->update($request->only('url_video', 'titulo_video')) 
            : VideoBlog::create([
                'id_blog' => $blog->id,
                'url_video' => $request->url_video,
                'titulo_video' => $request->titulo_video
            ]);

            DB::commit(); 

            return $this->successResponse(
                null,
                'Blog actualizado exitosamente',
                HttpStatusCode::OK
            );
            
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al actualizar el blog: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
            
        }
    }

    public function destroy($blog)
    {
        try {
            $blog = Blog::findOrFail($blog);
            $blog->delete();

            return $this->successResponse($blog, 'Blog eliminado exitosamente',
            HttpStatusCode::OK);

        } catch(\Exception $e) {
            return $this->errorResponse('Error al eliminar el blog: ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
