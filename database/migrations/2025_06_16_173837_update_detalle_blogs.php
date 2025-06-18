<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        $blogs = DB::table('blogs')->select('id')->get();

        foreach ($blogs as $blog) {
            $subtitulo = DB::table('detalle_blogs')
                ->where('id_blog', $blog->id)
                ->value('subtitulo_beneficio'); 

            DB::table('blogs')->where('id', $blog->id)
                ->update(['subtitulo3' => $subtitulo]);
        }
    }

    public function down(): void
    {
 
    }
};

