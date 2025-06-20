<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         Schema::table('blogs', function (Blueprint $table) {
            $table->string('url_video')->after('imagen_principal');
            $table->string('titulo_video', 40)->after('imagen_principal');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table){
            $table->dropColumn('url_video');
            $table->dropColumn('titulo_video');
        });
    }
};
