<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $year = now()->year;

        $holidays = [

            // Fixed Holidays
            ['name' => 'New Year', 'date' => "$year-01-01"],
            ['name' => 'Mapinduzi Day', 'date' => "$year-01-12"],
            ['name' => 'Karume Day', 'date' => "$year-04-07"],
            ['name' => 'Union Day', 'date' => "$year-04-26"],
            ['name' => 'Workers Day', 'date' => "$year-05-01"],
            ['name' => 'Saba Saba', 'date' => "$year-07-07"],
            ['name' => 'Nyerere Day', 'date' => "$year-10-14"],
            ['name' => 'Independence Day', 'date' => "$year-12-09"],
            ['name' => 'Christmas Day', 'date' => "$year-12-25"],
            ['name' => 'Boxing Day', 'date' => "$year-12-26"],

            
            ['name' => 'Good Friday', 'date' => "$year-04-03"],
            ['name' => 'Easter Monday', 'date' => "$year-04-06"],
            ['name' => 'Eid El-Fitr', 'date' => "$year-03-20"],
            ['name' => 'Eid El-Hajj', 'date' => "$year-05-27"],
            ['name' => 'Maulid Day', 'date' => "$year-09-15"],
        ];

        foreach ($holidays as $holiday) {
            Holiday::firstOrCreate(
                [
                    'name' => $holiday['name']
                ],
                ['date' => $holiday['date']],
                
            );
        }
    }
}