<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function(Blueprint $table){
            $table->increments('prod_id');
            $table->binary('imagen_principal');
            $table->binary('imagen_sec_1');
            $table->binary('imagen_sec_2');
            $table->binary('imagen_sec_3');
            $table->text('descripcion');
            $table->unsignedInteger('cat_id');

            $table->foreign('cat_id')->references('cat_id')->on('categorias')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
