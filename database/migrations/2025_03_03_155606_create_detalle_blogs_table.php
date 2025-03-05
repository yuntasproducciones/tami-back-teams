<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalle_blogs', function (Blueprint $table) {
            $table->id();
            $table->string('titulo_blog', 40);
            $table->string('subtitulo_beneficio', 70);
            $table->unsignedBigInteger('id_blog');

            $table->foreign('id_blog')->references('id')->on('blogs')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_blogs');
    }
};
