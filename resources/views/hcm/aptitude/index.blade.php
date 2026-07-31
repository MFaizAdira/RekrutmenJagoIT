@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Penilaian Tahap 1: Aptitude Test</h3>
    <p class="text-muted small">Berikan nilai hasil tes kognitif untuk melanjutkan kandidat ke tahap Tes Teknis (Account Manager).</p>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-list-stars me-2"></i> Antrean Penilaian</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small fw-bold text-muted" style="width: 30%;">Kandidat</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Posisi</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted" style="width: 40%;">Input Skor (0 - 100)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applicants as $applicant)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $applicant->full_name }}</div>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary small" style="font-size: 0.7rem;">ID: #{{ str_pad($applicant->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $applicant->position }}</span>
                                </td>
                                <td>
                                    <form action="{{ route('hcm.aptitude.update', $applicant->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf
                                        <div style="width: 120px;">
                                            <input type="number" name="score" class="form-control form-control-sm border-primary"
                                                   placeholder="Skor" min="0" max="100" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm px-3">
                                            <i class="bi bi-send-fill me-1"></i> Submit Nilai
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-check2-all fs-1 d-block mb-2 text-success"></i>
                                        <p class="mb-0">Semua kandidat sudah dinilai atau belum ada pelamar baru.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-4">
        <div class="card border-0 bg-light p-3">
            <div class="d-flex align-items-center text-muted small">
                <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                <span><strong>Info:</strong> Setelah nilai Aptitude dikirim, status kandidat otomatis berubah menjadi <strong>"Tes Teknis"</strong> dan data akan muncul di halaman Account Manager.</span>
            </div>
        </div>
    </div>
</div>
@endsection
