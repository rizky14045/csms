<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tambahkan pengaturan memory dan waktu eksekusi di sini
        ini_set('memory_limit', '1024M'); // Naikkan ke 1GB atau '-1' untuk tanpa batas
        set_time_limit(0);               // Set ke 0 agar tidak ada batas waktu (timeout)

        // 2. Ambil file dari folder public
        $path = public_path('provinsi.js');
        
        if (!File::exists($path)) {
            $this->command->error("File tidak ditemukan di: $path");
            return;
        }

        $this->command->info('Sedang membaca file... ini mungkin memakan waktu.');
        
        $json = File::get($path);
        $data = json_decode($json, true);

        if (isset($data['features'])) {
            $features = $data['features'];
            $bar = $this->command->getOutput()->createProgressBar(count($features));

            foreach ($features as $feature) {
                $properties = $feature['properties'] ?? [];
                
                // Sesuaikan key 'NAME_1' atau 'PROVINSI' dengan isi file Anda
                $name = $properties['WADMPR'] ?? 'Unknown';

                DB::table('provinces')->insert([
                    'name'       => $name,
                    'geometry'   => json_encode($feature['geometry']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $bar->advance();
            }
            $bar->finish();
            $this->command->info("\nData provinsi berhasil diimpor!");
        }
    }
}