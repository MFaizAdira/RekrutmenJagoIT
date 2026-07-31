<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Ambil role user & bersihkan (huruf kecil)
        $userRole = strtolower(auth()->user()->role);
        $allowedRoles = array_map('strtolower', $roles);

        // 3. Pengecekan
        if (!in_array($userRole, $allowedRoles)) {
            // JANGAN REDIRECT ke login/dashboard (ini penyebab loop)
            // Cukup tampilkan error 403 agar kita tahu role-nya tidak cocok
            abort(403, 'Akses Ditolak. Role Anda: ' . $userRole . '. Role yang dibutuhkan: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}
