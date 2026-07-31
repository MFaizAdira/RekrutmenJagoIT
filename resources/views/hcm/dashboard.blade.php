@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Dashboard HCM</h3>
    <p class="text-muted small">Selamat datang kembali! Berikut ringkasan aktivitas rekrutmen PT JagooIT.</p>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="small opacity-75">Total Pelamar</div>
                <h2 class="fw-bold mb-0">{{ $totalCandidates }}</h2>
                <i class="bi bi-people position-absolute end-0 bottom-0 me-3 mb-3 fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-dark">
            <div class="card-body">
                <div class="small opacity-75">Perlu Review</div>
                <h2 class="fw-bold mb-0">{{ $pendingReview }}</h2>
                <i class="bi bi-hourglass-split position-absolute end-0 bottom-0 me-3 mb-3 fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="small opacity-75">Data Posisi</div>
                <h2 class="fw-bold mb-0">{{ \App\Models\Position::count() }}</h2>
                <i class="bi bi-briefcase position-absolute end-0 bottom-0 me-3 mb-3 fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-dark text-white">
            <div class="card-body">
                <div class="small opacity-75">Total User Sistem</div>
                <h2 class="fw-bold mb-0">{{ $totalUsers }}</h2>
                <i class="bi bi-shield-check position-absolute end-0 bottom-0 me-3 mb-3 fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Aktivitas Sistem Terbaru</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @foreach($recentLogs as $log)
                            <tr>
                                <td class="ps-4" style="width: 50px;">
                                    <div class="avatar-xs bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="bi bi-activity text-primary"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ $log->user_name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $log->action }}</div>
                                </td>
                                <td class="text-end pe-4 text-muted small">
                                    {{ $log->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 text-center py-3">
                <a href="{{ route('hcm.logs') }}" class="small fw-bold text-decoration-none">Lihat Semua Log <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4 bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-3 small text-uppercase">Aksi Cepat</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('hcm.candidates.create') }}" class="btn btn-white border shadow-sm btn-sm text-start">
                        <i class="bi bi-plus-circle me-2 text-primary"></i> Tambah Pelamar Baru
                    </a>
                    <a href="{{ route('hcm.aptitude') }}" class="btn btn-white border shadow-sm btn-sm text-start">
                        <i class="bi bi-pencil-square me-2 text-success"></i> Input Nilai Aptitude
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Kandidat Skor Tertinggi</h6>
            </div>
            <div class="card-body">
                @foreach($topCandidates as $top)
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-dark">{{ $top->full_name }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ $top->position }}</div>
                    </div>
                    <div class="badge bg-primary rounded-pill">{{ $top->aptitude_score }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
