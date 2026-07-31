<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'action',
        'full_name', // Tambahkan ini
        'email',     // Tambahkan ini
        'position',  // Tambahkan ini
        'status',    // Tambahkan ini
    ];
}
