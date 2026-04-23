<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
{
    DB::table('buildings')->insert([
        [
            'building_name' => 'FLOOR 1',
            'building_code' => 'F/001',
            'created_at' => now(),
            'updated_at' => now(),

        ],
        [
            'building_name' => 'FLOOR 2',
            'buidling_code' => 'F/002',
            'created_at' => now(),
            'updated_at' => now(),

        ],
        [
            'building_name' => 'FLOOR 3',
            'building_code' => 'F/003',
            'created_at' => now(),
            'updated_at' => now(),

        ],
    ]);
}
}
