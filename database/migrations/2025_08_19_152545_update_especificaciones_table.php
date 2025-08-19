<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::table('especificaciones', function (Blueprint $table) {
            $table->string('clave')->nullable()->change();
            $table->string('valor')->nullable()->change();
            $table->string('texto')->nullable()->after('valor');

        });
    }

   
    public function down()
    {
         Schema::table('especificaciones', function (Blueprint $table) {
            $table->string('clave')->nullable(false)->change();
            $table->string('valor')->nullable(false)->change();
        });
    }
};
