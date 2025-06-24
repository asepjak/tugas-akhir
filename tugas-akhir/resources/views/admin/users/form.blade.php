<div class="mb-3">
    <label>Nama</label>
    <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
        value="{{ old('nama', $user->nama ?? '') }}">
    @if ($errors->has('nama'))
        <div class="invalid-feedback">{{ $errors->first('nama') }}</div>
    @endif
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
        value="{{ old('email', $user->email ?? '') }}">
    @if ($errors->has('email'))
        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
    @endif
</div>

<div class="mb-3">
    <label>Username</label>
    <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
        value="{{ old('username', $user->username ?? '') }}">
    @if ($errors->has('username'))
        <div class="invalid-feedback">{{ $errors->first('username') }}</div>
    @endif
</div>

<div class="mb-3">
    <label>No HP</label>
    <input type="text" name="no_hp" class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
        value="{{ old('no_hp', $user->no_hp ?? '') }}">
    @if ($errors->has('no_hp'))
        <div class="invalid-feedback">{{ $errors->first('no_hp') }}</div>
    @endif
</div>

<div class="mb-3">
    <label>Alamat</label>
    <input type="text" name="alamat" class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}"
        value="{{ old('alamat', $user->alamat ?? '') }}">
    @if ($errors->has('alamat'))
        <div class="invalid-feedback">{{ $errors->first('alamat') }}</div>
    @endif
</div>

<div class="mb-3">
    <label>Jabatan</label>
    <select name="jabatan" class="form-select {{ $errors->has('jabatan') ? 'is-invalid' : '' }}">
        <option value="">-- Pilih Jabatan --</option>
        @foreach(['Admin', 'Staff', 'Manajer'] as $j)
            <option value="{{ $j }}" {{ old('jabatan', $user->jabatan ?? '') == $j ? 'selected' : '' }}>{{ $j }}</option>
        @endforeach
    </select>
    @if ($errors->has('jabatan'))
        <div class="invalid-feedback">{{ $errors->first('jabatan') }}</div>
    @endif
</div>

<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}">
        <option value="">-- Pilih Status --</option>
        @foreach(['Aktif', 'Tidak Aktif'] as $s)
            <option value="{{ $s }}" {{ old('status', $user->status ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>
    @if ($errors->has('status'))
        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
    @endif
</div>

<div class="mb-3">
    <label>Foto (Opsional)</label>
    <input type="file" name="foto" class="form-control {{ $errors->has('foto') ? 'is-invalid' : '' }}">
    @if ($errors->has('foto'))
        <div class="invalid-feedback">{{ $errors->first('foto') }}</div>
    @endif
</div>

{{-- Password --}}
@if (!isset($user))
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
        @if ($errors->has('password'))
            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
        @endif
    </div>
    <div class="mb-3">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
@else
    <div class="mb-3">
        <label>Password Baru (opsional)</label>
        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
        @if ($errors->has('password'))
            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
        @endif
    </div>
    <div class="mb-3">
        <label>Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
@endif
