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
            $table->string('nombre')->nullable()->change();
            $table->string('titulo')->nullable()->change();
            $table->string('stock')->nullable()->change();
            $table->string('precio')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('nombre')->nullable(false)->change();
            $table->string('titulo')->nullable(false)->change();
            $table->string('stock')->nullable()->change();
            $table->string('precio')->nullable()->change();
        });
    }
};

