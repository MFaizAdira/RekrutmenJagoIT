<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Applicant::create([
        'full_name' => 'Budi Santoso',
        'email' => 'budi@gmail.com',
        'phone' => '08123456789',
        'position' => 'Full Stack Developer',
        'status' => 'pending'
    ]);

    \App\Models\Applicant::create([
        'full_name' => 'Siti Aminah',
        'email' => 'siti@gmail.com',
        'phone' => '08987654321',
        'position' => 'UI/UX Designer',
        'status' => 'pending'
    ]);
}
}
