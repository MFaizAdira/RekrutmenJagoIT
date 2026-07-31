<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'email', 'phone', 'position', 'status',
        'aptitude_score', 'experience_score', 'technical_score',
        'interview_score', 'salary_expectation', 'am_notes',
        'score_1', 'score_2', 'score_3', 'final_score',
    ];

    /**
     * PERBAIKAN: Jangan gunakan Accessor getPositionAttribute
     * karena akan merusak semua tampilan {{ $applicant->position }}.
     * Kita hapus fungsi tersebut dan gunakan solusi di View saja.
     */
}
