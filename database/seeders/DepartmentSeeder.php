<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $departments = [
            ['deptName' => 'DEPARTMENT OF ART AND SOCIAL SCIENCE','dept_code' => 'D001'],
            ['deptName' => 'DEPARTMENT 2','dept_code' => 'D002'],
            ['deptName' => 'DEPARTMNET OF PEMBA','dept_code' => 'D003'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['deptName' => $dept['deptName']],
                ['dept_code' => $dept['dept_code']],
            );
        }
    }
}
