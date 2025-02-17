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
        Schema::create('usuarios_registro', function(Blueprint $table) {
            $table->increments('usuRegis_id');
            $table->string('name',100);
            $table->string('email', 100)->unique();
            $table->string('celular', 9);
            $table->timestamp('fecha')->useCurrent();
            $table->unsignedInteger('sec_id');

            $table->foreign('sec_id')->references('sec_id')->on('seccion')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
