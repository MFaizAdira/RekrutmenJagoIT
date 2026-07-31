<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Criteria;
use Illuminate\Http\Request;

class DirectorController extends Controller
{
    public function ranking()
    {
        // 1. Ambil semua kriteria dan bobotnya
        $criterias = Criteria::all();

        // 2. Ambil pelamar yang sudah dinilai lengkap (status ready)
        $applicants = Applicant::where('status', 'ready')->get();

        if ($applicants->isEmpty()) {
            return view('director.ranking', ['results' => [], 'applicants' => []]);
        }

        // 3. Cari Nilai MAX dan MIN untuk Normalisasi
        // C1, C2, C3, C4 adalah Benefit (Cari Max)
        // C5 (Gaji) adalah Cost (Cari Min)
        $maxC1 = $applicants->max('aptitude_score') ?: 1;
        $maxC2 = $applicants->max('experience_score') ?: 1;
        $maxC3 = $applicants->max('technical_score') ?: 1;
        $maxC4 = $applicants->max('interview_score') ?: 1;
        $minC5 = $applicants->min('salary_expectation') ?: 1;

        $results = [];

        foreach ($applicants as $applicant) {
            // LANGKAH 1: Normalisasi (R)
            $r1 = $applicant->aptitude_score / $maxC1;
            $r2 = $applicant->experience_score / $maxC2;
            $r3 = $applicant->technical_score / $maxC3;
            $r4 = $applicant->interview_score / $maxC4;
            // Cost: Nilai Min / Nilai Pelamar
            $r5 = ($applicant->salary_expectation > 0) ? ($minC5 / $applicant->salary_expectation) : 0;

            // LANGKAH 2: Perangkingan (V) - Perkalian dengan Bobot
            // Kita ambil bobot dari database berdasarkan kode kriteria
            $w1 = $criterias->where('code', 'C1')->first()->weight ?? 0;
            $w2 = $criterias->where('code', 'C2')->first()->weight ?? 0;
            $w3 = $criterias->where('code', 'C3')->first()->weight ?? 0;
            $w4 = $criterias->where('code', 'C4')->first()->weight ?? 0;
            $w5 = $criterias->where('code', 'C5')->first()->weight ?? 0;

            $v = ($r1 * $w1) + ($r2 * $w2) + ($r3 * $w3) + ($r4 * $w4) + ($r5 * $w5);

            $results[] = [
                'id' => $applicant->id,
                'name' => $applicant->full_name,
                'position' => $applicant->position,
                'scores' => [$r1, $r2, $r3, $r4, $r5], // Untuk tampilan tabel normalisasi
                'total' => round($v, 4) // Nilai Akhir
            ];
        }

        // Urutkan berdasarkan nilai total tertinggi
        usort($results, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return view('director.ranking', compact('results'));
    }
}
