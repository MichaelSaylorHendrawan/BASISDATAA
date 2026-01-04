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
            // JAKARTA & SEKITARNYA
            ['branch_name' => 'Topgolf Premier SCBD', 'city' => 'Jakarta Selatan', 'address' => 'LOT 8 Foundry No. 8, Senayan'],
            ['branch_name' => 'Topgolf Bellezza', 'city' => 'Jakarta Selatan', 'address' => 'The Bellezza Shopping Arcade, Jl. Arteri Permata Hijau No.34'],
            ['branch_name' => 'Topgolf Gading', 'city' => 'Jakarta Utara', 'address' => 'Inkopal Driving Range Balai Samudera'],
            ['branch_name' => 'Topgolf The Range PIK', 'city' => 'Jakarta Utara', 'address' => 'Jl. Marina Raya Pantai Indah Kapuk'],
            ['branch_name' => 'Topgolf Rawamangun', 'city' => 'Jakarta Timur', 'address' => 'Jakarta Golf Club (JGC) Driving Range'],
            ['branch_name' => 'Topgolf Cilandak', 'city' => 'Jakarta Selatan', 'address' => 'Sarana Latihan Golf Cilandak (PUSKOPALMAR)'],
            ['branch_name' => 'Topgolf Kemayoran', 'city' => 'Jakarta Utara', 'address' => 'Bandar Golf Kemayoran, Jl. Trembesi Blok D 3-4'],
            ['branch_name' => 'Topgolf Albatross', 'city' => 'Tangerang', 'address' => 'Jl. Raya Pagedangan No.2, Banten'],

            // JAWA BARAT
            ['branch_name' => 'Topgolf Bandung', 'city' => 'Bandung', 'address' => 'Driving Range Siliwangi Golf, Jl. Lombok No. 10'],
            ['branch_name' => 'Topgolf Dago Heritage', 'city' => 'Bandung', 'address' => 'Jl. Raya Golf Dago No.78'],

            // JAWA TIMUR
            ['branch_name' => 'Topgolf Surabaya', 'city' => 'Surabaya', 'address' => 'Jl. Mayjend Sungkono No. 37 A'],
            ['branch_name' => 'Topgolf Surabaya Timur', 'city' => 'Surabaya', 'address' => 'Kenjeran Indah Utara, Kalijudan'],

            // BALI & LAINNYA
            ['branch_name' => 'Topgolf Bali', 'city' => 'Denpasar', 'address' => 'Jl. Bypass Ngurah Rai No.88, Kesiman Kertalangu'],
            ['branch_name' => 'Topgolf Medan', 'city' => 'Medan', 'address' => 'Jl. Pancing No.29a, Indra Kasih'],
            ['branch_name' => 'Topgolf Makassar', 'city' => 'Makassar', 'address' => 'G Swing Driving Range, Jl. Manunggal 22 No.22'],
            ['branch_name' => 'Topgolf Balikpapan', 'city' => 'Balikpapan', 'address' => 'Jl. Jend. Sudirman No. 379'],
        ];

        DB::table('branches')->insert($branches);
    }
}