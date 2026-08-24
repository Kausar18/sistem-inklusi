@extends('layouts.app')

@section('judul', 'Import Data Excel')

@section('konten')

<div class="mb-4">
    <a href="{{ route('admin.startup.index') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none small mb-2"
       style="color: var(--redup);">
        <i class="bi bi-arrow-left"></i> Kembali ke daftar
    </a>
    <h1 class="h4 mb-1">Import Data Excel</h1>
    <p class="mb-0 small" style="color: var(--redup);">
        Upload berkas Excel "Rekap Database Tenant" untuk menambahkan startup secara massal.
        Sheet pertama dalam berkas yang akan dibaca.
    </p>
</div>

@if (session('catatanGagal') && count(session('catatanGagal')))
    <div class="panel p-4 mb-4" style="border-color: #F0C4C4;">
        <h2 class="h6 mb-2">Baris yang gagal diimpor</h2>
        <ul class="small mb-0 ps-3">
            @foreach (session('catatanGagal') as $catatan)
                <li>{{ $catatan }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel p-4">
    <form method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="label-filter d-block">Berkas Excel (.xlsx / .xls)</label>
            <input type="file" name="berkas" class="form-control" accept=".xlsx,.xls" required>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="label-filter d-block">Label batch</label>
                <input type="text" name="batch" class="form-control" placeholder="mis. Batch 1">
            </div>
            <div class="col-md-6">
                <label class="label-filter d-block">Tahun program</label>
                <input type="number" name="tahun" class="form-control" placeholder="2026">
            </div>
        </div>

        <button type="submit" class="btn btn-utama px-4">Import</button>
    </form>
</div>

@endsection
