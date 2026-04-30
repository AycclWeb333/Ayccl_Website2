<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [

            // --- Email (User Credentials) ---
            ['para' => 'mail_mailer', 'para_en' =>       'mail_mailer', 'value' => 'smtp'],
            ['para' => 'mail_host', 'para_en' =>         'mail_host', 'value' => 'smtp.gmail.com'],
            ['para' => 'mail_port', 'para_en' =>         'mail_port', 'value' => '587'],
            ['para' => 'mail_username', 'para_en' =>     'mail_username', 'value' => 'n7122@gmail.com'],
            ['para' => 'mail_password', 'para_en' =>     'mail_password', 'value' => '777nnkk11#'],
            ['para' => 'mail_encryption', 'para_en' =>   'mail_encryption', 'value' => 'tls'],
            ['para' => 'mail_from_address', 'para_en' => 'mail_from_address', 'value' => 'n7122@gmail.com'],
            ['para' => 'mail_from_name', 'para_en' =>    'mail_from_name', 'value' => 'AYCCL Website Notification'],

            // --- Specific Recipients (Leave empty to use default above) ---
            ['para' => 'mail_receive_visit', 'para_en' => 'mail_receive_visit', 'value' => ''], 
            ['para' => 'mail_receive_training', 'para_en' => 'mail_receive_training', 'value' => ''],
            ['para' => 'mail_receive_job', 'para_en' => 'mail_receive_job', 'value' => ''],
        ];

        DB::table('settings')->insert($settings);
    }
}
