<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('pages')->where('id', 41)->update([
            'title' => 'ميزات العاملين',
            'slug' => 'ميزات-العاملين',
        ]);

        DB::table('pages')->where('id', 45)->update([
            'title' => 'كوادر تصنع النجاح',
            'slug' => 'كوادر-تصنع-النجاح',
        ]);

        DB::table('pages')->where('id', 23)->update([
            'title' => 'الأهداف',
            'slug' => 'الأهداف',
            'title_en' => 'Objectives',
            'slug_en' => 'objectives',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pages')->where('id', 41)->update([
            'title' => 'مميزات العاملين',
            'slug' => 'مميزات-العاملين',
        ]);

        DB::table('pages')->where('id', 45)->update([
            'title' => 'القوى العاملة',
            'slug' => 'القوى-العاملة',
        ]);

        DB::table('pages')->where('id', 23)->update([
            'title' => 'الرؤية والرسالة',
            'slug' => 'الرؤية-والرسالة',
            'title_en' => 'Vision and Mission',
            'slug_en' => 'vision-and-mission',
        ]);
    }
};
