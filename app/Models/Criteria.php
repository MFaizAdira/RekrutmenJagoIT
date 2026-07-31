<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'criterias';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'code',
        'name',
        'weight',
        'type'
    ];
}
