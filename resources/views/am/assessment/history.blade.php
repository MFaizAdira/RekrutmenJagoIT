@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold text-dark">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Penilaian AM
                </h3>
                <p class="text-muted mb-0">Daftar kandidat yang telah selesai diproses penilaian teknisnya.</p>
            </div>
            <a href="{{ route('am.assessment') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Antrean
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted">
                        <th class="ps-4 py-3">TANGGAL</th>
                        <th>KANDIDAT</th>
                        <th class="text-center">S1</th>
                        <th class="text-center">S2</th>
                        <th class="text-center">S3</th>
                        <th class="text-center bg-primary text-white">C3 (AVG)</th>
                        <th>REVIEW AM</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $item)
                    <tr>
                        <td class="ps-4 small">{{ $item->updated_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="fw-bold">{{ $item->full_name }}</div>
                            <small class="text-muted">{{ $item->position }}</small>
                        </td>
                        {{-- Memanggil nama kolom sesuai database Anda --}}
                        <td class="text-center">{{ $item->score_1 ?? '-' }}</td>
                        <td class="text-center">{{ $item->score_2 ?? '-' }}</td>
                        <td class="text-center">{{ $item->score_3 ?? '-' }}</td>
                        <td class="text-center fw-bold text-primary">
                            {{ number_format($item->technical_score, 2) }}
                        </td>
                        <td class="small text-muted">
                            {{ Str::limit($item->am_notes, 50) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            Belum ada riwayat penilaian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
