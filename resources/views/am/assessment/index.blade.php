@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-shield-check me-2 text-primary"></i>Penilaian Technical Test (C3)
            </h3>
            <p class="text-muted mb-0">Evaluasi kemampuan teknis kandidat berdasarkan parameter posisi.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill">
                Tahap: Account Manager Review
            </span>
        </div>
    </div>

    @if($applicants->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="bi bi-person-x display-4 text-muted mb-3"></i>
                <h5 class="text-dark">Tidak ada antrean penilaian</h5>
                <p class="text-muted">Semua kandidat saat ini telah dinilai atau belum mencapai tahap ini.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold text-dark mb-0">Daftar Kandidat Aktif</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="ps-4 py-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">DATA PELAMAR</th>
                            <th class="text-center py-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">SKOR TEKNIS (0-100)</th>
                            <th class="py-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">CATATAN REVIEW</th>
                            <th class="text-center py-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applicants as $app)
                        @php
                            // LOGIKA DINAMIS: Menyesuaikan label dengan posisi pelamar
                            $labels = [
                                'Backend Developer'    => ['Logic', 'DB', 'Server'],
                                'Frontend Developer'   => ['Logic', 'Style', 'API'],
                                'UI/UX Desaigner'      => ['Visual', 'Research', 'Proto'],
                                'Full Stack Developer' => ['Backend', 'Frontend', 'DB'],
                            ];
                            $currentLabel = $labels[$app->position] ?? ['Skill 1', 'Skill 2', 'Skill 3'];
                        @endphp
                        <tr>
                            <form action="{{ route('am.assessment.update', $app->id) }}" method="POST">
                                @csrf
                                {{-- Info Pelamar --}}
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold">
                                            {{ strtoupper(substr($app->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0">{{ $app->full_name }}</div>
                                            <small class="badge bg-light text-secondary border">{{ $app->position }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Input Skor Dinamis --}}
                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        @foreach($currentLabel as $index => $labelName)
                                        <div class="score-input-group">
                                            <label class="d-block text-center small fw-bold text-primary mb-1" style="font-size: 0.65rem;">
                                                {{ strtoupper($labelName) }}
                                            </label>
                                            <input type="number" name="score_{{ $index + 1 }}"
                                                   class="form-control form-control-sm text-center border-dashed"
                                                   style="width: 65px; border-radius: 8px;"
                                                   min="0" max="100" placeholder="0" required>
                                        </div>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Catatan --}}
                                <td>
                                    <textarea name="am_notes" class="form-control form-control-sm border-dashed"
                                              rows="2" style="border-radius: 8px; font-size: 0.85rem;"
                                              placeholder="Berikan review singkat..."></textarea>
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center pe-4">
                                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm fw-semibold rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> Submit
                                    </button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 p-3 bg-light rounded-3 border">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill text-primary me-3 fs-4"></i>
                <div class="small text-muted">
                    <strong>Informasi Perhitungan:</strong> Parameter input (Logic/DB/Visual/dll) menyesuaikan dengan posisi yang dilamar. Nilai akhir <strong>C3</strong> akan dihitung dari rata-rata ketiga input tersebut.
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); }
    .avatar-sm { width: 35px; height: 35px; font-size: 0.9rem; }
    .border-dashed { border-style: dashed !important; border-width: 1.5px; border-color: #dee2e6 !important; }
    .form-control:focus { box-shadow: none; border-color: #0d6efd !important; border-style: solid !important; }
    .table-hover tbody tr:hover { background-color: #fbfcfe; transition: background-color 0.2s ease; }
    .btn-primary { transition: all 0.3s ease; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2) !important; }
</style>
@endsection
