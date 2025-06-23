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
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('especificaciones');
        });

        Schema::create('especificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave');
            $table->string('valor');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especificaciones');

        Schema::table('productos', function (Blueprint $table) {
            $table->json('especificaciones')->nullable(); // o string/text, según como era antes
        });
    }
};
