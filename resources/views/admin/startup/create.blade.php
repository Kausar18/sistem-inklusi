@extends('layouts.app')

@section('judul', 'Tambah Startup')

@section('konten')

<div class="mb-4">
    <a href="{{ route('admin.startup.index') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none small mb-2"
       style="color: var(--redup);">
        <i class="bi bi-arrow-left"></i> Kembali ke daftar
    </a>
    <h1 class="h4 mb-1">Tambah Startup</h1>
    <p class="mb-0 small" style="color: var(--redup);">
        Anggota tim, legalitas, dokumentasi, dan target output bisa dilengkapi setelah profil dasar tersimpan.
    </p>
</div>

<form method="POST" action="{{ route('admin.startup.store') }}">
    @csrf

    @include('admin.startup._form')

    <button type="submit" class="btn btn-utama px-4">Simpan &amp; lanjutkan</button>
    <a href="{{ route('admin.startup.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
</form>

@endsection
