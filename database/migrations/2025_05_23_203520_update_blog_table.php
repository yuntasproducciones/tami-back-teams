<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('link')->nullable()->unique()->after('titulo');
            $table->renameColumn('parrafo', 'subtitulo1');
            $table->renameColumn('descripcion', 'subtitulo2');
        });
    }


    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('link');
            $table->renameColumn('subtitulo1', 'parrafo');
            $table->renameColumn('subtitulo2', 'descripcion');
        });
    }
};
