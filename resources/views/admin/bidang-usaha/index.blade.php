@extends('layouts.app')

@section('judul', 'Kelola Bidang Usaha')

@section('konten')

<div class="mb-4">
    <a href="{{ route('admin.startup.index') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none small mb-2"
       style="color: var(--redup);">
        <i class="bi bi-arrow-left"></i> Kembali ke daftar startup
    </a>
    <h1 class="h4 mb-1">Kelola Bidang Usaha</h1>
    <p class="mb-0 small" style="color: var(--redup);">
        {{ $daftarBidang->count() }} bidang usaha terdaftar
    </p>
</div>

<div class="panel p-4">
    @forelse ($daftarBidang as $bidang)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: var(--garis) !important;">
            <form method="POST" action="{{ route('admin.bidang-usaha.update', $bidang) }}"
                  class="d-flex align-items-center gap-2 flex-grow-1 me-3">
                @csrf
                @method('PUT')
                <input type="text" class="form-control form-control-sm" name="nama_bidang" value="{{ $bidang->nama_bidang }}">
                <button type="submit" class="btn btn-sm btn-outline-secondary text-nowrap">Simpan</button>
            </form>

            <div class="d-flex align-items-center gap-3">
                <span class="small" style="color: var(--redup);">{{ $bidang->startups_count }} startup</span>
                <form method="POST" action="{{ route('admin.bidang-usaha.destroy', $bidang) }}"
                      onsubmit="return confirm('Hapus bidang usaha &quot;{{ $bidang->nama_bidang }}&quot;?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </div>
    @empty
        <p class="small mb-3" style="color: var(--redup);">Belum ada bidang usaha.</p>
    @endforelse

    <form method="POST" action="{{ route('admin.bidang-usaha.store') }}" id="form-tambah-bidang" class="row g-2 mt-3">
        @csrf
        <div class="col-md-8">
            <input type="text" class="form-control form-control-sm" name="nama_bidang" placeholder="Nama bidang usaha baru" required>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-utama btn-sm w-100">Tambah bidang usaha</button>
        </div>
    </form>
</div>

@endsection
