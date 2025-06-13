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
            $table->foreignId("blog_id")->constrained("blogs")->onDelete("cascade");
        });
        $datos = DB::select("SELECT imagen_blogs.parrafo_imagen, imagen_blogs.id_blog, imagen_blogs.url_imagen FROM imagen_blogs");
        foreach ($datos as $item) {
            DB::insert("INSERT INTO blog_parrafos (parrafo, blog_id) VALUES (?, ?)", [$item->parrafo_imagen, $item->id_blog]);
            DB::insert("INSERT INTO blog_imagenes (ruta_imagen, blog_id) VALUES (?, ?)", [$item->url_imagen, $item->id_blog]);
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
    }
};
