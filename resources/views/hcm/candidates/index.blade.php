@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Daftar Kandidat Pelamar</h3>
            <p class="text-muted small mb-0">Manajemen data pelamar PT JagooIT</p>
        </div>
        <a href="{{ route('hcm.candidates.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i> Tambah Kandidat
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- FORM FILTER DENGAN TOMBOL RESET --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('hcm.candidates') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-start-0 ps-0"
                               placeholder="Cari nama atau email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="position" class="form-select bg-light">
                        <option value="">Semua Posisi</option>
                        @foreach($positions as $pos)
                            {{-- Gunakan $pos->position sesuai dengan query distinct di Controller --}}
                            <option value="{{ $pos->position }}" {{ request('position') == $pos->position ? 'selected' : '' }}>
                                {{ $pos->position }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select bg-light">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Baru (Pending)</option>
                        <option value="evaluated" {{ request('status') == 'evaluated' ? 'selected' : '' }}>Selesai Aptitude</option>
                        <option value="am_done" {{ request('status') == 'am_done' ? 'selected' : '' }}>Selesai Teknis</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap Ranking (SAW)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dark fw-bold w-100">Filter</button>
                        <a href="{{ route('hcm.candidates') }}" class="btn btn-light border fw-bold" title="Reset Filter">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow: visible;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Nama Lengkap</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Posisi</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Kontak</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Status</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Skor Aptitude</th>
                            <th class="py-3 text-center text-uppercase small fw-bold text-muted">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applicants as $applicant)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $applicant->full_name }}</div>
                                <div class="text-muted small">{{ $applicant->email }}</div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                    {{ $applicant->position }}
                                </span>
                            </td>
                            <td>
                                <div class="small text-dark"><i class="bi bi-telephone me-1 text-muted"></i> {{ $applicant->phone }}</div>
                            </td>
                            <td>
                                @if($applicant->status == 'pending')
                                    <span class="badge rounded-pill bg-warning text-dark px-3">Baru</span>
                                @elseif($applicant->status == 'evaluated')
                                    <span class="badge rounded-pill bg-info px-3">Evaluasi AM</span>
                                @elseif($applicant->status == 'am_done')
                                    <span class="badge rounded-pill bg-primary px-3">Review Direktur</span>
                                @elseif($applicant->status == 'ready')
                                    <span class="badge rounded-pill bg-success px-3">Siap Ranking</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary px-3">{{ ucfirst($applicant->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($applicant->aptitude_score > 0)
                                    <div class="fw-bold text-primary">
                                        {{ $applicant->aptitude_score }} <small class="text-muted">/ 100</small>
                                    </div>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-2 small">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle border shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li>
                                            <a class="dropdown-item small" href="{{ route('hcm.candidates.show', $applicant->id) }}">
                                                <i class="bi bi-eye me-2 text-muted"></i> Detail Profil
                                            </a>
                                        </li>

                                        @if($applicant->status == 'pending')
                                        <li>
                                            <a class="dropdown-item small text-primary" href="{{ route('hcm.aptitude') }}">
                                                <i class="bi bi-pencil-square me-2"></i> Beri Nilai Aptitude
                                            </a>
                                        </li>
                                        @endif

                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('hcm.candidates.destroy', $applicant->id) }}" method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ $applicant->full_name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item small text-danger">
                                                    <i class="bi bi-trash3 me-2"></i> Hapus Pelamar
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                Belum ada data kandidat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if(method_exists($applicants, 'links'))
    <div class="mt-4 d-flex justify-content-center">
        {{ $applicants->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
