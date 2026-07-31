@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('hcm.candidates') }}" class="btn btn-link text-decoration-none p-0 text-muted">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        <h3 class="fw-bold text-dark mt-2">Tambah Kandidat Baru</h3>
        <p class="text-muted small">Silakan isi data diri pelamar sesuai dengan berkas lamaran yang diterima.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <form action="{{ route('hcm.candidates.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        {{-- Nama Lengkap --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold small text-muted">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="full_name" class="form-control bg-light border-start-0 @error('full_name') is-invalid @enderror" placeholder="Contoh: Ahmad Fauzi" value="{{ old('full_name') }}" required>
                            </div>
                            @error('full_name') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" placeholder="email@contoh.com" value="{{ old('email') }}" required>
                            </div>
                            @error('email') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Nomor WhatsApp/HP</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp text-muted"></i></span>
                                <input type="text" name="phone" class="form-control bg-light border-start-0 @error('phone') is-invalid @enderror" placeholder="0812xxxx" value="{{ old('phone') }}" required>
                            </div>
                            @error('phone') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Posisi yang Dilamar --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold small text-muted">Posisi yang Dilamar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-briefcase text-muted"></i></span>
                                <select name="position" class="form-select bg-light border-start-0 @error('position') is-invalid @enderror" required>
                                    <option value="" selected disabled>Pilih Posisi...</option>
                                    {{-- PERBAIKAN: Mengambil data dari variabel $positions yang dikirim Controller --}}
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->position }}" {{ old('position') == $pos->position ? 'selected' : '' }}>
                                            {{ $pos->position }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Notifikasi jika posisi kosong --}}
                            @if($positions->isEmpty())
                                <div class="small text-warning mt-2">
                                    <i class="bi bi-exclamation-triangle"></i> Belum ada posisi yang terdaftar di sistem.
                                    <a href="{{ route('hcm.positions') }}" class="text-primary fw-bold text-decoration-none">Tambah posisi baru di sini.</a>
                                </div>
                            @endif
                            @error('position') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm fw-bold">
                                <i class="bi bi-check-lg me-2"></i> Simpan Data Kandidat
                            </button>
                            <button type="reset" class="btn btn-light px-4 py-2 text-muted fw-bold">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Panel Informasi Samping --}}
        <div class="col-lg-4">
            <div class="card border-0 bg-primary bg-opacity-10 p-4 text-primary h-100">
                <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i> Petunjuk Input</h6>
                <ul class="small mb-0 mt-3 ps-3">
                    <li class="mb-2">Pastikan alamat email unik (belum pernah terdaftar sebelumnya).</li>
                    <li class="mb-2">Gunakan format nomor HP yang aktif untuk koordinasi lebih lanjut.</li>
                    <li class="mb-2">Status kandidat otomatis akan menjadi <span class="badge bg-warning text-dark">Pending</span> setelah disimpan.</li>
                    <li>Posisi diambil dari data Jabatan yang sudah Anda daftarkan di menu Master Posisi.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
