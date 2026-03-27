<?php
// database/seeders/CitySeeder.php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CitySeeder extends Seeder {
    public function run(): void {
        // PENGATURAN MEMORI & WAKTU [Sangat Penting untuk File Besar]
        ini_set('memory_limit', '-1'); 
        set_time_limit(0);

        $path = public_path('kota.js');
        
        if (!File::exists($path)) {
            $this->command->error("File tidak ditemukan di: $path");
            return;
        }

        $this->command->info('Sedang membaca file KABKOT... Ini akan memakan waktu karena data sangat detail.');
        
        $json = File::get($path);
        $data = json_decode($json, true);

        if (isset($data['features'])) {
            $features = $data['features'];
            $bar = $this->command->getOutput()->createProgressBar(count($features));

            foreach ($features as $feature) {
                $properties = $feature['properties'] ?? [];
                
                // Biasanya dalam GeoJSON Kab/Kot: 
                // NAME_2 = Nama Kabupaten/Kota, NAME_1 = Nama Provinsi
                $cityName = ($properties['WADMKK'] ?? null) ?: ($properties['NAMOBJ'] ?? null) ?: 'Unknown City';
                $province = Province::where('name',$properties['WADMPR'])->first()->id ?? null;

                DB::table('cities')->insert([
                    'name'          => $cityName,
                    'province_id' => $province,
                    'geometry'      => json_encode($feature['geometry']),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                $bar->advance();
            }
            $bar->finish();
            $this->command->info("\nData Kabupaten/Kota berhasil diimpor!");
        }
    }
}