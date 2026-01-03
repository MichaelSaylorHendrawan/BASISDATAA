<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            ['tier_name' => 'Classic', 'monthly_fee' => 49.00, 'guest_allowance' => 1],
            ['tier_name' => 'Premium', 'monthly_fee' => 89.00, 'guest_allowance' => 2],
            ['tier_name' => 'Elite', 'monthly_fee' => 149.00, 'guest_allowance' => 4],
        ];

        DB::table('membership_tiers')->insert($tiers);
    }
}
