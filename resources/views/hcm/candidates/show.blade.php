@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        {{-- Menggunakan url()->previous() agar kembali ke halaman log jika diakses dari log --}}
        <a href="{{ url()->previous() }}" class="btn btn-link text-decoration-none p-0 text-muted">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <h3 class="fw-bold text-dark mt-2">Detail Profil Kandidat</h3>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Profil Singkat -->
            <div class="card border-0 shadow-sm text-center p-4 mb-4">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-person-fill fs-1"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $applicant->full_name }}</h5>
                <p class="text-muted small mb-3">{{ $applicant->position }}</p>

                @php
                    $status = strtolower($applicant->status ?? 'pending');
                    $badgeClass = match($status) {
                        'accepted', 'hired' => 'bg-success',
                        'pending'           => 'bg-warning text-dark',
                        'rejected'          => 'bg-danger',
                        'evaluated', 'am_done', 'ready' => 'bg-info text-white',
                        default             => 'bg-secondary'
                    };
                @endphp
                <span class="badge {{ $badgeClass }} py-2 px-3 rounded-pill">
                    Status: {{ strtoupper($status) }}
                </span>
            </div>

            <!-- Kontak -->
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-3">Informasi Kontak</h6>
                <div class="d-flex mb-3">
                    <i class="bi bi-envelope text-primary me-3"></i>
                    <div>
                        <small class="text-muted d-block">Email</small>
                        <span class="fw-semibold text-break">{{ $applicant->email }}</span>
                    </div>
                </div>
                <div class="d-flex">
                    <i class="bi bi-whatsapp text-success me-3"></i>
                    <div>
                        <small class="text-muted d-block">WhatsApp</small>
                        <span class="fw-semibold">{{ $applicant->phone }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Nilai SAW -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h6 class="fw-bold mb-4">Riwayat Penilaian SAW</h6>
                <div class="row text-center g-3">
                    <div class="col-6 col-md-3 border-end">
                        <small class="text-muted d-block mb-1">Aptitude (C1)</small>
                        @if($applicant->aptitude_score)
                            <h4 class="fw-bold text-primary mb-0">{{ $applicant->aptitude_score }}</h4>
                        @else
                            <span class="text-danger small">Belum Tes</span>
                        @endif
                    </div>
                    <div class="col-6 col-md-3 border-end">
                        <small class="text-muted d-block mb-1">Teknis (C3)</small>
                        @if($applicant->technical_score)
                            <h4 class="fw-bold text-primary mb-0">{{ number_format($applicant->technical_score, 1) }}</h4>
                        @else
                            <span class="text-muted small italic">Tahap AM</span>
                        @endif
                    </div>
                    <div class="col-6 col-md-3 border-end">
                        <small class="text-muted d-block mb-1">Pengalaman (C2)</small>
                        <h4 class="fw-bold mb-0">{{ $applicant->experience_score ?? '-' }}</h4>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block mb-1">Gaji (C5)</small>
                        <h5 class="fw-bold mb-0 text-truncate">
                            {{ $applicant->salary_expectation ? number_format($applicant->salary_expectation / 1000000, 1) . ' Jt' : '-' }}
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Catatan & Aksi -->
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-3">Catatan Tambahan</h6>
                <p class="text-muted italic small mb-4">
                    {{ $applicant->am_notes ?? 'Belum ada catatan dari Account Manager.' }}
                </p>

                <hr>

                <h6 class="fw-bold mb-3">Tindakan Cepat</h6>
                <div class="d-flex flex-wrap gap-2">
                    @if($status == 'pending')
                        <a href="{{ route('hcm.candidates') }}" class="btn btn-primary px-4">
                            <i class="bi bi-pencil-square me-2"></i> Input Nilai Aptitude
                        </a>
                    @endif
                    <button class="btn btn-outline-secondary px-4" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i> Cetak Profil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
