<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_productos', function(Blueprint $table){
            $table->increments('detProd_id');
            $table->unsignedInteger('prod_id');
            $table->string('titulo', length:20);
            $table->string('subtitulo', length:100);
            $table->text('descripcion');
            $table->decimal('longitud', 10, 2);
            $table->decimal('ancho', 10, 2);
            $table->decimal('altura', 10, 2);

            $table->foreign('prod_id')->references('prod_id')->on('productos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_productos');
    }
};
