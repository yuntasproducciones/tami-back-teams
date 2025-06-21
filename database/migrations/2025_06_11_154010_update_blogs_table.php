<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create("blog_parrafos", function(Blueprint $table){
            $table->id();
            $table->text("parrafo");
            $table->foreignId("blog_id")->constrained("blogs")->onDelete("cascade");
            $table->timestamps();
        });
        Schema::create("blog_imagenes", function(Blueprint $table){
            $table->id();
            $table->text("ruta_imagen");
            $table->string("texto_alt")->nullable();
            $table->foreignId("blog_id")->constrained("blogs")->onDelete("cascade");
            $table->timestamps();
        });
        Schema::table("blogs", function(Blueprint $table){
            if (Schema::hasColumn("blogs","imagen_principal")) {$table->string("imagen_principal")->nullable()->change();}
            if (Schema::hasColumn("blogs","parrafo")) {$table->renameColumn("parrafo", "subtitulo1");}
            if (Schema::hasColumn("blogs","descripcion")) {$table->renameColumn("descripcion", "subtitulo2");}
            if (!Schema::hasColumn("blogs","subtitulo3")) {$table->string("subtitulo3")->after("subtitulo2");}
            if (!Schema::hasColumn("blogs","video_url")) {$table->string("video_url")->after("subtitulo3");}
            if (!Schema::hasColumn("blogs","video_titulo")) {$table->string("video_titulo")->after("video_url");}
        });
        $datos = DB::select("SELECT imagen_blogs.parrafo_imagen, imagen_blogs.id_blog, imagen_blogs.url_imagen FROM imagen_blogs RIGHT JOIN blogs ON blogs.id = imagen_blogs.id_blog");
        foreach ($datos as $item) {
            DB::insert("INSERT INTO blog_parrafos (parrafo, blog_id) VALUES (?, ?)", [$item->parrafo_imagen, $item->id_blog]);
            DB::insert("INSERT INTO blog_imagenes (ruta_imagen, blog_id) VALUES (?, ?)", [$item->url_imagen, $item->id_blog]);
        }
        $datos = DB::select("SELECT video_blogs.url_video, video_blogs.titulo_video, video_blogs.id_blog FROM video_blogs RIGHT JOIN blogs ON blogs.id = video_blogs.id_blog");
        foreach ($datos as $item) {
            DB::update("UPDATE blogs SET video_url = ?, video_titulo = ? WHERE id = ?", [$item->url_video, $item->titulo_video, $item->id_blog]);
        }
        $datos = DB::select("SELECT detalle_blogs.subtitulo_beneficio, detalle_blogs.id_blog FROM detalle_blogs RIGHT JOIN blogs ON blogs.id = detalle_blogs.id_blog");
        foreach ($datos as $item) {
            DB::update("UPDATE blogs SET subtitulo3 = ? WHERE id = ?", [$item->subtitulo_beneficio, $item->id_blog]);
        }
        Schema::table("blogs", function(Blueprint $table){
            $table->dropColumn("imagen_principal");
        });
        Schema::dropIfExists("video_blogs");
        Schema::dropIfExists("detalle_blogs");
        Schema::dropIfExists("imagen_blogs");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
