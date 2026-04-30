<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            ["branch_name" => "Unguja"],
            ["branch_name" => "Pemba"],
        ];
        foreach($branches as $branch){
            Branch::firstOrCreate(
                 [
                    'branch_name' => $branch['branch_name']
                ],
            );
        }
    }
}
