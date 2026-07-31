@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 d-print-none">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold text-dark">
                    <i class="bi bi-trophy me-2 text-warning"></i>Hasil Ranking Seleksi (Metode SAW)
                </h3>
                <p class="text-muted mb-0">Rekomendasi urutan kandidat berdasarkan perhitungan otomatis Simple Additive Weighting.</p>
            </div>
            <button onclick="window.print()" class="btn btn-danger shadow-sm">
                <i class="bi bi-file-pdf me-2"></i>Cetak Laporan PDF
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="ps-4 py-3">Ranking</th>
                            <th class="py-3">Nama Kandidat</th>
                            <th class="py-3">Posisi</th>
                            <th class="text-center py-3">Skor Akhir (V)</th>
                            <th class="text-center py-3">Status Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rankingResults as $index => $result)
                        @php
                            // SOLUSI ERROR: Cek apakah data array atau object secara otomatis
                            $nama  = is_array($result) ? $result['full_name'] : $result->full_name;
                            $email = is_array($result) ? $result['email'] : $result->email;
                            $pos   = is_array($result) ? $result['position'] : $result->position;

                            // Ambil skor (mencoba v_score dulu, lalu total_score)
                            $skorRaw = is_array($result)
                                ? ($result['v_score'] ?? $result['total_score'] ?? 0)
                                : ($result->v_score ?? $result->total_score ?? 0);
                            $v_score = (float) $skorRaw;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                @if($loop->first && $v_score > 0)
                                    <span class="badge bg-warning text-dark px-3 py-2 shadow-sm">
                                        <i class="bi bi-star-fill me-1"></i> Juara 1
                                    </span>
                                @else
                                    <span class="fw-bold text-muted ps-2">#{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $nama }}</div>
                                <small class="text-muted">{{ $email }}</small>
                            </td>
                            <td>{{ $pos }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-white fs-6 px-3 py-2">
                                    {{ number_format($v_score, 2) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($v_score >= 80)
                                    <span class="badge bg-success rounded-pill px-3">Sangat Direkomendasikan</span>
                                @elseif($v_score >= 60)
                                    <span class="badge bg-primary rounded-pill px-3">Direkomendasikan</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3">Tidak Direkomendasikan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Belum ada data pelamar dengan status 'ready'.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="alert alert-light border mt-4 shadow-sm">
        <h6 class="fw-bold text-dark"><i class="bi bi-info-circle-fill text-primary me-2"></i>Informasi Perhitungan:</h6>
        <p class="small text-muted mb-0">
            Penilaian menggunakan pembobotan kriteria:
            <strong>C1 (Aptitude): 30%</strong>, <strong>C2 (Experience): 10%</strong>,
            <strong>C3 (Technical): 30%</strong>, <strong>C4 (Professional): 20%</strong>,
            dan <strong>C5 (Salary): 10% (Cost)</strong>.
        </p>
    </div>
</div>

<style>
    @media print {
        .d-print-none, .sidebar, .navbar { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .bg-primary { background-color: #0d6efd !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection
