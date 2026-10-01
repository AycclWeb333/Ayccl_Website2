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
        // 1. تحديث اسم صفحة الجوائز والشهادات (ID 26)
        DB::table('pages')->where('id', 26)->update([
            'title' => 'الجوائز والشهادات',
        ]);

        // 2. تحديث اسم صفحة المسؤولية الإجتماعية (ID 25)
        DB::table('pages')->where('id', 25)->update([
            'title' => 'المسؤولية الإجتماعية',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pages')->where('id', 26)->update([
            'title' => 'الجوائز والشهائد',
        ]);

        DB::table('pages')->where('id', 25)->update([
            'title' => 'المسئولية الإجتماعية',
        ]);
    }
};
