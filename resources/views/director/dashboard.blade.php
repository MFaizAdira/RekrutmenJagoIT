@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold text-dark mb-1">Executive Dashboard</h3>
                <p class="text-muted small">Ringkasan pengambilan keputusan rekrutmen PT JagooIT.</p>
            </div>
            <div class="text-end">
                <div class="badge bg-white text-dark shadow-sm p-2 px-3 border">
                    <i class="bi bi-calendar3 me-2 text-primary"></i> 29 April 2026
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1 fw-bold">TOTAL PELAMAR</p>
                            <h2 class="fw-bold mb-0">{{ $totalCandidates }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-people text-primary fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1 fw-bold">POSISI TERSEDIA</p>
                            <h2 class="fw-bold mb-0">{{ $totalPositions }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-briefcase text-success fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1 fw-bold">SIAP DIRANKING</p>
                            <h2 class="fw-bold mb-0 text-warning">{{ $readyToRank }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-star text-warning fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center d-grid">
                    <a href="{{ route('director.ranking') }}" class="btn btn-primary d-flex align-items-center justify-content-center fw-bold">
                        <i class="bi bi-calculator me-2"></i> LIHAT RANKING SAW
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0">Rekomendasi Kandidat Teratas (Berdasarkan Aptitude)</h6>
                </div>
                <div class="card-body p-0 text-center">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small text-uppercase">
                                <tr>
                                    <th class="ps-4">Kandidat</th>
                                    <th>Posisi</th>
                                    <th>Skor Aptitude</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topRanked as $rank)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3 text-secondary">
                                                <i class="bi bi-person"></i>
                                            </div>
                                            <div class="text-start">
                                                <div class="fw-bold small text-dark">{{ $rank->full_name }}</div>
                                                <div class="text-muted small" style="font-size: 0.7rem;">{{ $rank->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small">{{ $rank->position }}</td>
                                    <td><span class="badge bg-info bg-opacity-10 text-info px-3">{{ $rank->aptitude_score }}</span></td>
                                    <td>
                                        @if($rank->status == 'ready')
                                            <span class="badge bg-success small">Ready for Decision</span>
                                        @else
                                            <span class="badge bg-light text-dark border small">Processing</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-5 text-muted small">Belum ada data pelamar yang siap diproses.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white border-0 shadow-sm mb-4">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-shield-check fs-1 text-primary mb-3"></i>
                    <h5 class="fw-bold">Decision Support System</h5>
                    <p class="small text-muted mb-0">
                        Sistem ini menggunakan metode <strong>Simple Additive Weighting (SAW)</strong> untuk memberikan rekomendasi objektif berdasarkan kriteria yang telah ditentukan.
                    </p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Panduan Singkat</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-3 d-flex">
                            <i class="bi bi-1-circle text-primary me-2"></i>
                            <span>Cek menu <strong>Ranking SAW</strong> untuk melihat hasil akhir.</span>
                        </li>
                        <li class="mb-3 d-flex">
                            <i class="bi bi-2-circle text-primary me-2"></i>
                            <span>Klik <strong>Cetak Laporan</strong> di halaman ranking untuk arsip.</span>
                        </li>
                        <li class="d-flex">
                            <i class="bi bi-3-circle text-primary me-2"></i>
                            <span>Gunakan filter posisi untuk mempersempit hasil.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
</style>
@endsection
