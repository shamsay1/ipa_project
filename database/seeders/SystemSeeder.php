<?php

namespace Database\Seeders;

use App\Models\SystemTimetable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $system = SystemTimetable::first();
        if(!$system){
            SystemTimetable::create([
                'status' => 'not_created',
            ]);
        }
    }
}
