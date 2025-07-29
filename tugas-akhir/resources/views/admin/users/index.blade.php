@extends('layouts.admin-app')

@section('title', 'Manajemen User')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-uppercase text-center mb-4">Manajemen User</h4>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabel Desktop -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Token</th> <!-- kolom token opsional -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>{{ $user->reset_token ?? '-' }}</td>
                        <td class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.users.resetToken', $user->id) }}" method="POST" onsubmit="return confirm('Reset token untuk {{ $user->name }}?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-key"></i> Reset Token
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Tidak ada data user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tampilan Mobile -->
    <div class="d-md-none">
        @forelse ($users as $user)
            <div class="card mb-3">
                <div class="card-body row">
                    <div class="col-8">
                        <h5 class="card-title">{{ $user->name }}</h5>
                        <p class="card-text mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                        <p class="card-text mb-1"><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                        <p class="card-text mb-1"><strong>Token:</strong> {{ $user->reset_token ?? '-' }}</p>
                    </div>
                    <div class="col-4 text-end">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.users.resetToken', $user->id) }}" method="POST" onsubmit="return confirm('Reset token untuk {{ $user->name }}?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger mt-1">
                                <i class="fas fa-key"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Tidak ada data user.</p>
        @endforelse
    </div>
</div>
@endsection
