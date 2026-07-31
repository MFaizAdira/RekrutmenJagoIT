<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
{
    $data = [
        ['code' => 'C1', 'name' => 'Nilai Aptitude', 'weight' => 0.25, 'type' => 'benefit'],
        ['code' => 'C2', 'name' => 'Pengalaman Kerja', 'weight' => 0.20, 'type' => 'benefit'],
        ['code' => 'C3', 'name' => 'Tes Teknis (Coding)', 'weight' => 0.30, 'type' => 'benefit'],
        ['code' => 'C4', 'name' => 'Wawancara', 'weight' => 0.15, 'type' => 'benefit'],
        ['code' => 'C5', 'name' => 'Ekspektasi Gaji', 'weight' => 0.10, 'type' => 'cost'],
    ];

    foreach ($data as $item) {
        // Jika kode sudah ada, update datanya. Jika belum, buat baru.
        \App\Models\Criteria::updateOrCreate(['code' => $item['code']], $item);
    }
}
}
