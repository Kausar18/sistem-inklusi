@extends('layouts.app')

@section('judul', 'Kelola Data Startup')

@section('konten')

<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
        <h1 class="h4 mb-1">Kelola Data Startup</h1>
        <p class="mb-0 small" style="color: var(--redup);">
            {{ $startups->total() }} startup terdaftar
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.bidang-usaha.index') }}" class="btn btn-outline-secondary btn-sm">
            Kelola bidang usaha
        </a>
        <a href="{{ route('admin.import.create') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Import Excel
        </a>
        <a href="{{ route('admin.startup.create') }}" class="btn btn-utama btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah startup
        </a>
    </div>
</div>

{{-- ======================== FILTER ======================== --}}
<div class="panel mb-4 p-3">
    <form method="GET" action="{{ route('admin.startup.index') }}" class="row g-3">
        <div class="col-lg-5">
            <label class="label-filter d-block" for="q">Cari</label>
            <input type="text" class="form-control" id="q" name="q"
                   value="{{ request('q') }}" placeholder="Nama startup, CEO, atau produk">
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="label-filter d-block" for="bidang">Bidang usaha</label>
            <select class="form-select" id="bidang" name="bidang">
                <option value="">Semua bidang</option>
                @foreach ($daftarBidang as $bidang)
                    <option value="{{ $bidang->id }}" @selected((string) request('bidang') === (string) $bidang->id)>
                        {{ $bidang->nama_bidang }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="label-filter d-block" for="batch">Batch</label>
            <select class="form-select" id="batch" name="batch">
                <option value="">Semua batch</option>
                @foreach ($daftarBatch as $batch)
                    <option value="{{ $batch }}" @selected(request('batch') === $batch)>{{ $batch }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-1 d-flex align-items-end">
            <button type="submit" class="btn btn-utama w-100">Cari</button>
        </div>
    </form>
</div>

{{-- ======================== TABEL ======================== --}}
<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0 tabel-rapi">
            <thead>
                <tr>
                    <th>Nama startup</th>
                    <th>CEO</th>
                    <th>Bidang usaha</th>
                    <th>Batch</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($startups as $startup)
                    <tr>
                        <td class="fw-semibold">{{ $startup->nama_startup }}</td>
                        <td>{{ $startup->nama_ceo }}</td>
                        <td>{{ $startup->bidangUsaha?->nama_bidang ?? '—' }}</td>
                        <td>{{ $startup->batch ?? '—' }}</td>
                        <td><span class="tag tag-netral">{{ ucfirst($startup->status) }}</span></td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('startup.show', $startup) }}" class="btn btn-sm btn-link p-0 me-3"
                               title="Lihat halaman publik">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.startup.edit', $startup) }}" class="btn btn-sm btn-link p-0 me-3"
                               title="Kelola">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.startup.destroy', $startup) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus startup &quot;{{ $startup->nama_startup }}&quot; beserta seluruh data terkaitnya? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-link text-danger p-0" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 small" style="color: var(--redup);">
                            Tidak ada startup yang cocok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($startups->hasPages())
    <div class="mt-4">
        {{ $startups->links() }}
    </div>
@endif

@endsection
