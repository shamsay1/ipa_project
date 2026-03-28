<?php

namespace Database\Seeders;

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
        $admin = Teacher::where("email","shamsay70@gmail.com")->first();
        if(!$admin){
            Teacher::create([
                "firstname" => "SHAMIS",
                "lastname" => "ALI",
                "gender" => "Male",
                "mobile" => "0795371212",
                "email" => "shamsay70@gmail.com",
                "password" => Hash::make("shamsay321!"),
                "user_level" => "admin",
                "teacher_code" => "T00001",
                "deptId" => "5",
            ]);
        }
    }
}
