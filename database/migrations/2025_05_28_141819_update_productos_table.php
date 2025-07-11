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
         if (Schema::hasColumn('productos', 'miniatura')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn('miniatura');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('productos', function (Blueprint $table) {
            $table->string('miniatura')->nullable();
        });
    }
};
