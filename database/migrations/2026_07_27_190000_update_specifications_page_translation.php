<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('pages')->where('id', 56)->update([
            'title_en' => 'Specifications',
            'slug_en' => 'specifications',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pages')->where('id', 56)->update([
            'title_en' => 'مواصفات',
            'slug_en' => 'مواصفات',
        ]);
    }
};
