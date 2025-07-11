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
        Schema::table('blogs', function (Blueprint $table) {
            $table->text('titulo')->change();
            $table->text('subtitulo1')->change();
            $table->text('subtitulo2')->change();
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('titulo', 255)->change(); 
            $table->string('subtitulo1', 255)->change();
            $table->string('subtitulo2', 255)->change();
        });
    }
};
