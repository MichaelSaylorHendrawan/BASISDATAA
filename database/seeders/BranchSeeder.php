<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            ['branch_name' => 'Jakarta', 'city' => 'Jakarta', 'address' => 'Jl. Jendral Sudirman 1'],
            ['branch_name' => 'Surabaya', 'city' => 'Surabaya', 'address' => 'Jl. Basuki Rahmat 10'],
            ['branch_name' => 'Bali', 'city' => 'Denpasar', 'address' => 'Jl. Sunset Road 5'],
            ['branch_name' => 'Medan', 'city' => 'Medan', 'address' => 'Jl. Gatot Subroto 2'],
            ['branch_name' => 'Makassar', 'city' => 'Makassar', 'address' => 'Jl. Pettarani 8'],
        ];

        DB::table('branches')->insert($branches);
    }
}
