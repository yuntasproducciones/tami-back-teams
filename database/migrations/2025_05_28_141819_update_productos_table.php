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
         if (Schema::hasColumn('productos', 'imagen_principal')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn('imagen_principal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('productos', function (Blueprint $table) {
            $table->string('imagen_principal')->nullable();
        });
    }
};
