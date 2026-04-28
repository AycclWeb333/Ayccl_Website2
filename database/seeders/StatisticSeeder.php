<?php

namespace Database\Seeders;

use App\Models\Statistic;
use Illuminate\Database\Seeder;

class StatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stats = [
            [
                'number' => 1.5,
                'unit_ar' => 'مليون طن',
                'unit_en' => 'M Tons',
                'description_ar' => 'طاقة إنتاجية سنوياً',
                'description_en' => 'Annual Capacity',
                'order' => 1,
            ],
            [
                'number' => 250,
                'unit_ar' => 'مليون دولار',
                'unit_en' => 'M Dollars',
                'description_ar' => 'حجم الاستثمار',
                'description_en' => 'Investment Size',
                'order' => 2,
            ],
            [
                'number' => 100,
                'unit_ar' => '%',
                'unit_en' => '%',
                'description_ar' => 'تكنولوجيا ألمانية (نظام الروبوت)',
                'description_en' => 'German Tech (Robot System)',
                'order' => 3,
            ],
            [
                'number' => 7,
                'unit_ar' => 'أنواع من الأسمنت',
                'unit_en' => 'Cement Types',
                'description_ar' => 'المتخصص',
                'description_en' => 'Specialized',
                'order' => 4,
            ],
            [
                'number' => 7,
                'unit_ar' => 'شهادات عالمية',
                'unit_en' => 'Global Cert.',
                'description_ar' => '',
                'description_en' => '',
                'order' => 5,
            ],
        ];

        foreach ($stats as $stat) {
            Statistic::create($stat);
        }
    }
}
