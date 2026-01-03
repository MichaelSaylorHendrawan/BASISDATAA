<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $total = 500000; // 500k members

        $branches = DB::table('branches')->pluck('branch_id')->toArray();
        $tiers = DB::table('membership_tiers')->pluck('tier_id')->toArray();

        if (empty($branches) || empty($tiers)) {
            $this->command->error('You must seed branches and membership_tiers first.');
            return;
        }

        $this->command->info("Generating $total members to CSV (storage/app/topgolf_members.csv)...");

        $csvPath = storage_path('app/topgolf_members.csv');
        $fp = fopen($csvPath, 'w');

        $faker = Faker::create();

        // Write rows directly to CSV to avoid memory pressure
        for ($i = 1; $i <= $total; $i++) {
            $branch_id = $branches[array_rand($branches)];
            $tier_id = $tiers[array_rand($tiers)];
            $first = $faker->firstName();
            $last = $faker->lastName();
            // deterministic unique email to avoid unique conflicts and avoid Faker->unique memory usage
            $email = "member{$i}@topgolf.com";
            $phone = preg_replace('/[^0-9+]/', '', $faker->phoneNumber());
            $phone = substr($phone, 0, 20);
            $join_date = $faker->dateBetween('-3 years', 'now')->format('Y-m-d');
            $status = ['Active', 'Inactive', 'Suspended'][array_rand([0,1,2])];

            fputcsv($fp, [$branch_id, $tier_id, $first, $last, $email, $phone, $join_date, $status]);

            // occasional progress log (every batch-sized chunk)
            if ($i % 10000 === 0) {
                $this->command->info("...written $i rows");
            }
        }

        fclose($fp);

        $this->command->info('CSV generation completed. Attempting bulk import via LOAD DATA LOCAL INFILE (fast) ...');

        $connection = DB::connection()->getPDO();
        $escaped = str_replace("\\", "\\\\", $csvPath); // escape backslashes on Windows

        try {
            // Use LOCAL so the client sends the file (works for many dev setups)
            $sql = "LOAD DATA LOCAL INFILE '" . addslashes($escaped) . "' INTO TABLE members FIELDS TERMINATED BY ',' ENCLOSED BY '" . "'" . " LINES TERMINATED BY '\n' (branch_id, tier_id, first_name, last_name, email, phone, join_date, status);";

            DB::unprepared($sql);

            $this->command->info('Bulk import completed via LOAD DATA LOCAL INFILE.');
        } catch (\Throwable $e) {
            // Fallback: batch insert from CSV in chunks
            $this->command->warn('LOAD DATA failed: ' . $e->getMessage());
            $this->command->info('Falling back to chunked inserts (this may take a while)...');

            $handle = fopen($csvPath, 'r');
            $batch = [];
            $batchSize = 10000;
            $count = 0;

            while (($row = fgetcsv($handle)) !== false) {
                [$branch_id, $tier_id, $first, $last, $email, $phone, $join_date, $status] = $row;
                $batch[] = [
                    'branch_id' => $branch_id,
                    'tier_id' => $tier_id,
                    'first_name' => $first,
                    'last_name' => $last,
                    'email' => $email,
                    'phone' => $phone,
                    'join_date' => $join_date,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= $batchSize) {
                    DB::table('members')->insert($batch);
                    $count += count($batch);
                    $batch = [];
                    $this->command->info("Inserted $count rows...");
                }
            }

            if (!empty($batch)) {
                DB::table('members')->insert($batch);
                $count += count($batch);
                $this->command->info("Inserted $count rows. (final)");
            }

            fclose($handle);
        }

        // optional: remove the CSV file after import
        // @unlink($csvPath);

        $this->command->info('Member seeding finished.');
    }
}
