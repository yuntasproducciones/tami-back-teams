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
        Schema::table('dimensions', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'valor']);

            $table->unsignedBigInteger('id_producto')->change();
            $table->unique('id_producto');
            
            $table->string('alto')->nullable()->after('id_producto');
            $table->string('largo')->nullable()->after('alto');
            $table->string('ancho')->nullable()->after('largo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dimensions', function (Blueprint $table) {
            $table->dropUnique(['id_producto']);

            $table->dropColumn(['alto', 'largo', 'ancho']);
            $table->string('tipo')->nullable()->after('id_producto');
            $table->string('valor')->nullable()->after('tipo');
        });
    }
};
