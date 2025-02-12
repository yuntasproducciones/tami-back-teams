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
        Schema::create('seccion', function(Blueprint $table) {
            $table->increments('sec_id');
            $table->string('seccion',100);
            $table->unsignedInteger('id_coment');

            $table->foreign('id_coment')->references('id_coment')->on('comentarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccion');
    }
};
