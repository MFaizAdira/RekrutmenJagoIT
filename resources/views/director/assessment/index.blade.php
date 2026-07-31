@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-person-check-fill me-2 text-success"></i>Final Assessment & Scoring (C4)
            </h3>
            <p class="text-muted mb-0">Lengkapi kriteria penilaian akhir untuk kalkulasi metode SAW.</p>
        </div>
        <div>
            <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill fw-bold">
                Status: Menunggu Validasi Direktur
            </span>
        </div>
    </div>

    @if($applicants->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="bi bi-clipboard-check display-4 text-muted mb-3"></i>
                <h5 class="text-dark">Antrean Penilaian Kosong</h5>
                <p class="text-muted">Belum ada kandidat yang dikirim oleh Account Manager untuk tahap final.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-stars me-2"></i>Kandidat Siap Evaluasi</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="ps-4">DATA PELAMAR</th>
                            <th class="text-center">HASIL TEKNIS (C3)</th>
                            <th style="width: 20%;">CATATAN AM</th>
                            <th class="text-center">INPUT KRITERIA FINAL (SAW)</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applicants as $app)
                        <tr>
                            <form action="{{ route('director.assessment.update', $app->id) }}" method="POST">
                                @csrf
                                {{-- Info Dasar --}}
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $app->full_name }}</div>
                                    <span class="badge bg-outline-secondary small" style="font-size: 0.7rem;">{{ $app->position }}</span>
                                </td>

                                {{-- Menampilkan Skor dari AM (C3) --}}
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="mb-1">
                                            <span class="badge bg-light text-dark border" title="Skill 1">{{ $app->score_1 }}</span>
                                            <span class="badge bg-light text-dark border" title="Skill 2">{{ $app->score_2 }}</span>
                                            <span class="badge bg-light text-dark border" title="Skill 3">{{ $app->score_3 }}</span>
                                        </div>
                                        <div class="fw-bold text-primary">
                                            Rata-rata: {{ number_format($app->technical_score, 1) }}
                                        </div>
                                    </div>
                                </td>

                                {{-- Menampilkan Catatan AM --}}
                                <td>
                                    <div class="p-2 bg-light rounded border small" style="min-height: 50px;">
                                        <i class="bi bi-quote text-secondary"></i>
                                        <em class="text-muted">{{ $app->am_notes ?? 'Tidak ada catatan review.' }}</em>
                                    </div>
                                </td>

                                {{-- Input Kriteria Final sesuai Method SAW --}}
                                <td>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="small fw-bold d-block mb-1">C2 (Exp)</label>
                                            <input type="number" name="experience_score" class="form-control form-control-sm border-primary" placeholder="0-100" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small fw-bold d-block mb-1">C4 (Prof)</label>
                                            <input type="number" name="interview_score" class="form-control form-control-sm border-primary" placeholder="0-100" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small fw-bold d-block mb-1">C5 (Salary)</label>
                                            <input type="number" name="salary_expectation" class="form-control form-control-sm border-primary" placeholder="Rp" min="0" required>
                                        </div>
                                    </div>
                                </td>

                                {{-- Aksi Selesaikan --}}
                                <td class="text-center pe-4">
                                    <button type="submit" class="btn btn-success btn-sm px-4 rounded-pill shadow-sm fw-bold">
                                        <i class="bi bi-check-all me-1"></i> Finalize
                                    </button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .bg-outline-secondary { border: 1px solid #6c757d; color: #6c757d; }
    .table thead th { border-top: none; font-size: 0.75rem; letter-spacing: 0.05em; text-transform: uppercase; }
    .form-control-sm:focus { border-color: #198754; box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1); }
</style>
@endsection
