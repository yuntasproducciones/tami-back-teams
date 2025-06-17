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
        });
        Schema::create("blog_imagenes", function(Blueprint $table){
            $table->id();
            $table->text("ruta_imagen");
            $table->string("texto_alt")->nullable();
            $table->foreignId("blog_id")->constrained("blogs")->onDelete("cascade");
        });
        $datos = DB::select("SELECT imagen_blogs.parrafo_imagen, imagen_blogs.id_blog, imagen_blogs.url_imagen FROM imagen_blogs");
        foreach ($datos as $item) {
            DB::insert("INSERT INTO blog_parrafos (parrafo, blog_id) VALUES (?, ?)", [$item->parrafo_imagen, $item->id_blog]);
            DB::insert("INSERT INTO blog_imagenes (ruta_imagen, blog_id) VALUES (?, ?)", [$item->url_imagen, $item->id_blog]);
        }
        // a partir de aca me manda error, el problema radica en el campo imagen_principal que cuando lo intento poner nullable no me deja y se cancela toda la operacion, creo que la solucion es cambiarlo desde otra migracion pero ya no me deja tiempo
        Schema::table("blogs", function(Blueprint $table){
            $table->string("video_url")->nullable();
            $table->string("video_titulo")->nullable();
            error_log(Schema::hasColumn('blogs', 'video_url'));
            error_log(Schema::hasColumn('blogs', 'video_titulo'));
            
            $table->renameColumn("parrafo", "subtitulo1");
            $table->renameColumn("descripcion", "subtitulo2");
            $table->string("subtitulo3");
            error_log("1");
            
            error_log("2");
            
            error_log("3");
            
            error_log("4");
            $table->string("imagen_principal")->nullable();
        });
        $datos = DB::select("SELECT video_blogs.url_video, video_blogs.titulo_video FROM video_blogs");
        error_log("5");
        foreach ($datos as $item) {
            DB::update("UPDATE blogs SET video_url = ?, video_titulo = ? WHERE id = ?", [$item->url_video, $item->video_titulo, $item->id_blog]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists("blog_parrafos");
        Schema::dropIfExists("blog_imagenes");
        Schema::table("blogs", function(Blueprint $table){
            $table->renameColumn("subtitulo1", "parrafo");
            $table->renameColumn("subtitulo2", "descripcion");
            $table->dropColumn("subtitulo3");
            $table->dropColumn("video_url");
            $table->dropColumn("video_titulo");
        });
    }
};
