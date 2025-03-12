<?php

namespace database\seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        Setting::updateOrCreate(['key' => 'current_term'], ['value' => '2024-2025, 2nd Semester']);
    }
}


