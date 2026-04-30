<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch1 = 1;
        $branch2 = 2;
        // Admin 1
        $admin1 = Teacher::where("email", "shamsay70@gmail.com")->first();
        if (!$admin1) {
            Teacher::create([
                "firstname" => "SHAMIS",
                "middlename" => "NASSOR",
                "lastname" => "ALI",
                "gender" => "Male",
                "mobile" => "0795371212",
                "email" => "shamsay70@gmail.com",
                "password" => Hash::make("shamsay321!"),
                "user_level" => "admin",
                "teacher_code" => "T00001",
                "deptId" => 1,
                "branch_id" => $branch1, 
            ]);
        }

        // Admin 2
        $admin2 = Teacher::where("email", "pemba@gmail.com")->first();
        if (!$admin2) {
            Teacher::create([
                "firstname" => "AHMED",
                "middlename" => "MOHD",
                "lastname" => "JUMA",
                "gender" => "Male",
                "mobile" => "0712345678",
                "email" => "pemba@gmail.com",
                "password" => Hash::make("password123"),
                "user_level" => "admin",
                "teacher_code" => "T00002",
                "deptId" => 3,
                "branch_id" => $branch2, 
            ]);
        }
    }
}
