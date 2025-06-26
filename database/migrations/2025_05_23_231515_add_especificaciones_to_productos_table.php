<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
<<<<<<< Updated upstream:database/migrations/2025_05_23_231515_add_especificaciones_to_productos_table.php
        Schema::table('productos', function (Blueprint $table) {
            $table->json('especificaciones')->nullable()->after('seccion');
=======
        // Quita STRICT_TRANS_TABLES del sql_mode para evitar el error
        DB::statement('SET SESSION sql_mode = REPLACE(@@sql_mode, "STRICT_TRANS_TABLES", "")');

        Schema::table('blogs', function (Blueprint $table) {
            $table->text('imagen_principal')->nullable()->change();
>>>>>>> Stashed changes:database/migrations/2025_06_21_144357_make_imagen_principal_nullable_in_blogs_table.php
        });

        // Opcionalmente, puedes volver a activar el modo estricto aquí si lo necesitas
        // DB::statement('SET SESSION sql_mode = CONCAT(@@sql_mode, ",STRICT_TRANS_TABLES")');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('especificaciones');
        });
    }
};
