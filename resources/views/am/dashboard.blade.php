@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-dark">Dashboard Account Manager</h3>
            <p class="text-muted">Selamat datang, {{ Auth::user()->name }}. Kelola penilaian teknis kandidat di sini.</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        {{-- Card: Menunggu Penilaian --}}
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-1" style="opacity: 0.8;">Perlu Dinilai</h6>
                            <h2 class="mb-0 fw-bold">{{ $pendingTechnical }}</h2>
                        </div>
                        <i class="bi bi-hourglass-split display-4 opacity-50"></i>
                    </div>
                    <hr class="my-3 opacity-25">
                    <a href="{{ route('am.assessment') }}" class="text-white text-decoration-none small fw-bold">
                        Lihat Antrean <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Card: Total Selesai Dinilai --}}
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-1" style="opacity: 0.8;">Selesai Dinilai</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalAssessed }}</h2>
                        </div>
                        <i class="bi bi-check-circle-fill display-4 opacity-50"></i>
                    </div>
                    <hr class="my-3 opacity-25">
                    <div class="small fw-bold">Kandidat Terproses</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Informasi Alur Kerja (Sangat penting untuk Sidang TA) --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0">Instruksi Penilaian Teknis (C3)</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <span class="badge bg-primary-soft text-primary p-2 rounded-circle">
                                <i class="bi bi-1-circle fs-4"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="fw-bold">Pilih Kandidat</h6>
                            <p class="text-muted small">Klik menu "Penilaian Teknis" untuk melihat daftar kandidat yang telah lolos tes Aptitude dari HCM.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <span class="badge bg-primary-soft text-primary p-2 rounded-circle">
                                <i class="bi bi-2-circle fs-4"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="fw-bold">Berikan Skor 0-100</h6>
                            <p class="text-muted small">Masukkan 3 nilai komponen teknis (Coding, Logika, Framework). Sistem akan menghitung rata-ratanya sebagai nilai C3.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3">
                            <span class="badge bg-primary-soft text-primary p-2 rounded-circle">
                                <i class="bi bi-3-circle fs-4"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="fw-bold">Kirim ke Direktur</h6>
                            <p class="text-muted small">Setelah disimpan, status kandidat akan berubah menjadi 'am_done' dan masuk ke antrean Direktur untuk penilaian akhir.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tips Cepat --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-lightbulb text-warning me-2"></i>Tips Account Manager</h6>
                    <ul class="small text-muted ps-3 mb-0">
                        <li class="mb-2">Gunakan kolom **Am Notes** untuk memberikan catatan kualitatif yang membantu Direktur mengambil keputusan.</li>
                        <li class="mb-2">Pastikan semua skor diisi dengan angka valid (0-100) agar tidak terjadi error pada perhitungan SAW.</li>
                        <li>Nilai C3 memiliki bobot **30%** dalam total perhitungan akhir.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); }
</style>
@endsection
