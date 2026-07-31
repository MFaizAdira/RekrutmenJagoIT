@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('hcm.users') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar User
    </a>
    <h3 class="fw-bold text-dark mt-2">Edit Pengguna</h3>
    <p class="text-muted small">Perbarui informasi akun atau hak akses pengguna sistem.</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('hcm.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Hak Akses (Role)</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="hcm" {{ $user->role == 'hcm' ? 'selected' : '' }}>HCM / Admin</option>
                            <option value="am" {{ $user->role == 'am' ? 'selected' : '' }}>Account Manager (AM)</option>
                            <option value="direktur" {{ $user->role == 'direktur' ? 'selected' : '' }}>Direktur</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <div class="alert alert-info border-0 shadow-sm small mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Kosongkan password jika tidak ingin mengubahnya.
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Minimal 6 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Ulangi password baru">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="bi bi-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 bg-light shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold"><i class="bi bi-shield-lock me-2 text-primary"></i>Informasi Keamanan</h6>
                <p class="text-muted small mb-0">
                    Pastikan email yang digunakan aktif. Hak akses menentukan menu apa saja yang bisa dilihat oleh pengguna ini di sistem PT JagooIT.
                </p>
            </div>
        </div>
        <div class="card border-0 shadow-sm border-start border-4 border-warning">
            <div class="card-body small">
                <strong>Catatan:</strong> Perubahan data akan langsung tercatat dalam <strong>Audit Log</strong> sistem untuk keperluan monitoring.
            </div>
        </div>
    </div>
</div>
@endsection
