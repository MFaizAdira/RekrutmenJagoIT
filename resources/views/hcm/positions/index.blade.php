@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Daftar Posisi Jabatan</h4>
            <p class="text-muted small">Manajemen posisi pekerjaan melalui data pelamar sistem</p>
        </div>
        <!-- Tombol Tambah Jabatan -->
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJabatan">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jabatan
        </button>
    </div>

    <!-- Notifikasi Sukses/Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-briefcase me-2"></i> Master Data Posisi</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">No</th>
                            <th>Nama Posisi</th>
                            <th class="text-center">Jumlah Pelamar Aktif</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($positions as $index => $pos)
                        <tr>
                            <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $pos->position }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    // Menghitung hanya pelamar asli, bukan data placeholder sistem
                                    $count = \App\Models\Applicant::where('position', $pos->position)
                                             ->where('status', '!=', 'placeholder')
                                             ->count();
                                @endphp
                                <span class="badge {{ $count > 0 ? 'bg-info-subtle text-info' : 'bg-light text-muted' }} border px-3 py-2">
                                    {{ $count }} Orang
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Link Detail: Diarahkan ke pencarian berdasarkan posisi -->
                                    <a href="{{ route('hcm.candidates') }}?search={{ urlencode($pos->position) }}"
                                       class="btn btn-sm btn-outline-primary px-3" title="Lihat Pelamar">
                                        <i class="bi bi-people me-1"></i> Lihat Pelamar
                                    </a>

                                    <!-- Tombol Hapus: Menghapus semua data dengan posisi ini -->
                                    <form action="{{ route('hcm.positions.destroy', urlencode($pos->position)) }}"
                                          method="POST"
                                          onsubmit="return confirm('Peringatan: Menghapus jabatan ini akan menghapus SEMUA data terkait posisi ini. Lanjutkan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Jabatan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                Belum ada data posisi ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Jabatan -->
<div class="modal fade" id="modalTambahJabatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Input Jabatan Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hcm.positions.store') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Jabatan</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" placeholder="Contoh: Mobile Developer" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="alert alert-info border-0 small mb-0">
                        <i class="bi bi-info-circle me-1"></i> Jabatan akan didaftarkan ke sistem melalui data referensi agar dapat dipilih saat menginput pelamar baru.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Jabatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
