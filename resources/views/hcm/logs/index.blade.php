@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Audit Logs</h4>
            <p class="text-muted small">Riwayat pembaruan data pelamar di sistem</p>
        </div>
        <button class="btn btn-primary btn-sm" onclick="window.location.reload()">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
        </button>
    </div>

    {{-- Menampilkan pesan error jika data pelamar sudah dihapus --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card p-0 border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Log Aktivitas Terakhir</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Waktu Update</th>
                            <th>Aktivitas</th>
                            <th>Nama Pelamar</th>
                            <th>Posisi</th>
                            <th>Status Terakhir</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="ps-4">
                                <span class="d-block fw-semibold text-dark">{{ $log->created_at->format('d M Y') }}</span>
                                <small class="text-muted">{{ $log->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $log->user_name }}</div>
                                <small class="text-dark">{{ $log->action }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $log->full_name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $log->email ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="text-secondary">{{ $log->position ?? '-' }}</span>
                            </td>
                            <td>
                                @php
                                    $status = strtolower($log->status ?? '');
                                    $badgeClass = match($status) {
                                        'accepted', 'hired' => 'bg-success',
                                        'pending'           => 'bg-warning text-dark',
                                        'rejected'          => 'bg-danger',
                                        'evaluated', 'technical', 'am_done', 'ready' => 'bg-info text-white',
                                        default             => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2">
                                    {{ strtoupper($log->status ?? 'SYSTEM') }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{-- Tombol hanya aktif jika data email tersedia dan bukan log sistem murni --}}
                                @if($log->email && $log->email != '-' && $log->full_name != 'N/A')
                                    <a href="{{ route('hcm.hcm.logs.show', $log->id) }}>" class="btn btn-sm btn-light border shadow-sm" title="Lihat Profil Pelamar">
                                        <i class="bi bi-eye text-primary"></i>
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-light border disabled" title="Detail tidak tersedia">
                                        <i class="bi bi-eye text-muted"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada aktivitas tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Pagination jika data banyak --}}
        @if(method_exists($logs, 'links'))
            <div class="card-footer bg-white py-3">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
