<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        // 1. PENGATURAN AGRESIF UNTUK RAM
        DB::connection()->disableQueryLog(); // Matikan log query agar RAM tidak bengkak
        set_time_limit(0);
        ini_set('memory_limit', '1G'); // Usahakan naikkan ke 1GB jika bisa

        $total = 500000;
        $csvPath = storage_path('app/topgolf_members.csv');

        // Jika file CSV belum ada, buat dulu (logika Anda sudah benar di sini)
        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan!");
            return;
        }

        $this->command->info('Memulai insertOrIgnore dari CSV (Batch Mode)...');

        $handle = fopen($csvPath, 'r');
        $batch = [];
        $batchSize = 1000; // Kecilkan batch agar memori stabil
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            // Pastikan data valid
            if (count($row) < 8) continue; 

            $batch[] = [
                'branch_id'  => $row[0],
                'tier_id'    => $row[1],
                'first_name' => $row[2],
                'last_name'  => $row[3],
                'email'      => $row[4],
                'phone'      => $row[5],
                'join_date'  => $row[6],
                'status'     => $row[7],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                // Gunakan insertOrIgnore agar data duplikat dilewati otomatis
                DB::table('members')->insertOrIgnore($batch);
                
                $count += count($batch);
                if ($count % 10000 === 0) {
                    $this->command->info("Telah memasukkan $count data...");
                }

                // KUNCI UTAMA: Kosongkan array dan bebaskan memori
                unset($batch);
                $batch = []; 
            }
        }

        // Insert sisa data yang belum ter-batch
        if (!empty($batch)) {
            DB::table('members')->insertOrIgnore($batch);
            $count += count($batch);
        }

        fclose($handle);
        $this->command->info("Selesai! Total $count baris diproses.");
    }
}