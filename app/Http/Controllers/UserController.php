<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user.
     */
    public function index()
    {
        // Menggunakan latest() agar user baru muncul di atas
        $users = User::latest()->get();
        return view('hcm.users.index', compact('users'));
    }

    /**
     * Menyimpan user baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:hcm,am,direktur',
            // Gunakan confirmed jika ada input password_confirmation di form
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        AuditLog::create([
            'user_name' => Auth::user()->name ?? 'System',
            'action' => "Menambahkan user baru: {$user->name} sebagai " . strtoupper($user->role)
        ]);

        return redirect()->route('hcm.users')->with('success', 'User baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit user (PENTING: Agar tidak error saat klik edit)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('hcm.users.edit', compact('user'));
    }

    /**
     * Mengupdate data user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role' => 'required|in:hcm,am,direktur',
        ]);

        // Update data dasar
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Jika password diisi, baru kita update
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Password::defaults()]
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        AuditLog::create([
            'user_name' => Auth::user()->name ?? 'System',
            'action' => "Memperbarui profil user: {$user->name}"
        ]);

        return redirect()->route('hcm.users')->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Menghapus user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Security: Jangan biarkan hapus diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Keamanan Sistem: Anda tidak diperbolehkan menghapus akun sendiri!');
        }

        $userName = $user->name;
        $user->delete();

        AuditLog::create([
            'user_name' => Auth::user()->name ?? 'System',
            'action' => "Menghapus akses user: {$userName}"
        ]);

        return redirect()->route('hcm.users')->with('success', "User {$userName} berhasil dihapus dari sistem.");
    }
}
