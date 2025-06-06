<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemConfigSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('system_config')->updateOrInsert(
            ['name' => 'available_parking_slots'],
            [
                'value' => 7,
            
            ]
        );
    }
}