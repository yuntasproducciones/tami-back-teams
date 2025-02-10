<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacto', function(Blueprint $table){
            $table->increments('contacto_id');
            $table->string('nombre', length:100);
            $table->string('apellido', length:100);
            $table->string('telefono', length:20);
            $table->string('email', length:100);
            $table->string('seccion', length:100);
            $table->timestamp('fecha_creacion')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacto');
    }
};
