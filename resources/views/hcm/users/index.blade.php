@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Manajemen Akses Pengguna</h3>
    <p class="text-muted small">Kelola akun staf yang memiliki akses ke Sistem Pendukung Keputusan PT JagooIT.</p>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i> Tambah User Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('hcm.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control bg-light @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Email</label>
                        <input type="email" name="email" class="form-control bg-light @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@jagooit.com" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Password</label>
                        <input type="password" name="password" class="form-control bg-light @error('password') is-invalid @enderror" placeholder="Min. 6 Karakter" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control bg-light" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Role / Jabatan</label>
                        <select name="role" class="form-select bg-light" required>
                            <option value="hcm" {{ old('role') == 'hcm' ? 'selected' : '' }}>HCM (Admin SDM)</option>
                            <option value="am" {{ old('role') == 'am' ? 'selected' : '' }}>Account Manager (AM)</option>
                            <option value="direktur" {{ old('role') == 'direktur' ? 'selected' : '' }}>Direktur</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                        <i class="bi bi-save me-2"></i> Simpan User
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0 text-nowrap">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">User</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted text-center">Role</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Status</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $badgeClass = [
                                            'hcm' => 'bg-danger text-danger',
                                            'direktur' => 'bg-primary text-primary',
                                            'am' => 'bg-info text-info'
                                        ][$user->role] ?? 'bg-secondary text-secondary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} bg-opacity-10 px-3 py-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                        {{ $user->role == 'hcm' ? 'Admin HCM' : ($user->role == 'am' ? 'Acc. Manager' : 'Direktur') }}
                                    </span>
                                </td>
                                <td class="small">
                                    <span class="text-muted"><i class="bi bi-clock me-1"></i> {{ $user->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <a class="dropdown-item small py-2" href="#" data-bs-toggle="modal" data-bs-target="#editUser{{ $user->id }}">
                                                    <i class="bi bi-pencil me-2 text-warning"></i> Edit User
                                                </a>
                                            </li>
                                            @if($user->id !== Auth::id())
                                            <li><hr class="dropdown-divider opacity-50"></li>
                                            <li>
                                                <form action="{{ route('hcm.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akses {{ $user->name }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item small text-danger py-2">
                                                        <i class="bi bi-trash3 me-2"></i> Hapus Akses
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header border-bottom-0 p-4">
                                            <h5 class="modal-title fw-bold">Update Data Pengguna</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('hcm.users.update', $user->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4 pt-0">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Nama Lengkap</label>
                                                    <input type="text" name="name" class="form-control bg-light" value="{{ $user->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Email</label>
                                                    <input type="email" name="email" class="form-control bg-light" value="{{ $user->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Role / Jabatan</label>
                                                    <select name="role" class="form-select bg-light" required>
                                                        <option value="hcm" {{ $user->role == 'hcm' ? 'selected' : '' }}>HCM (Admin SDM)</option>
                                                        <option value="am" {{ $user->role == 'am' ? 'selected' : '' }}>Account Manager (AM)</option>
                                                        <option value="direktur" {{ $user->role == 'direktur' ? 'selected' : '' }}>Direktur</option>
                                                    </select>
                                                </div>
                                                <div class="p-3 bg-light rounded-3 mb-0">
                                                    <label class="form-label small fw-bold text-primary mb-1">Ganti Password (Opsional)</label>
                                                    <p class="text-muted small mb-3" style="font-size: 0.75rem;">Isi jika ingin mereset password user ini.</p>
                                                    <input type="password" name="password" class="form-control mb-2" placeholder="Password baru">
                                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 p-4 pt-0">
                                                <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
