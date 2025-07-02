@extends('layouts.admin-app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <!-- Header Section -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 gap-sm-3">
                <h4 class="mb-0">Daftar Akun Karyawan</h4>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus d-inline d-sm-none"></i>
                    <span class="d-none d-sm-inline">+ Tambah Karyawan</span>
                    <span class="d-inline d-sm-none">Tambah</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <!-- Desktop Table (Hidden on mobile) -->
            <div class="d-none d-lg-block">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->jabatan }}</td>
                                <td>
                                    <span class="badge {{ $user->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tablet Table (Hidden on mobile and large screens) -->
            <div class="d-none d-md-block d-lg-none">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Karyawan</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $user->nama }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                    <div class="text-muted small">@{{ $user->username }}</div>
                                </td>
                                <td>{{ $user->jabatan }}</td>
                                <td>
                                    <span class="badge {{ $user->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Cards (Visible only on mobile) -->
            <div class="d-block d-md-none">
                @foreach ($users as $user)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="card-title mb-1 fw-bold">{{ $user->nama }}</h6>
                                <p class="card-text mb-1 text-muted small">
                                    <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                                </p>
                                <p class="card-text mb-1 text-muted small">
                                    <i class="fas fa-user me-1"></i>@{{ $user->username }}
                                </p>
                                <p class="card-text mb-1">
                                    <i class="fas fa-briefcase me-1"></i>{{ $user->jabatan }}
                                </p>
                                <span class="badge {{ $user->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                            <div class="col-4 text-end">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row mt-3 mt-md-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
@media (max-width: 767.98px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }

    .card {
        border-radius: 8px;
    }

    .card-body {
        padding: 1rem;
    }
}

@media (min-width: 768px) and (max-width: 991.98px) {
    .table td {
        font-size: 0.9rem;
    }
}

@media (min-width: 992px) {
    .table td {
        vertical-align: middle;
    }
}

/* Custom pagination styling for mobile */
@media (max-width: 576px) {
    .pagination {
        font-size: 0.8rem;
    }

    .page-link {
        padding: 0.25rem 0.5rem;
    }
}
</style>
@endsection
