@extends('layouts.app')

@section('judul', 'Kelola ' . $startup->nama_startup)

@section('konten')

<div class="mb-4">
    <a href="{{ route('admin.startup.index') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none small mb-2"
       style="color: var(--redup);">
        <i class="bi bi-arrow-left"></i> Kembali ke daftar
    </a>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h1 class="h4 mb-1">{{ $startup->nama_startup }}</h1>
            <p class="mb-0 small" style="color: var(--redup);">Kelola profil, tim, legalitas, dokumentasi, dan target output</p>
        </div>
        <a href="{{ route('startup.show', $startup) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
            <i class="bi bi-box-arrow-up-right me-1"></i> Lihat halaman publik
        </a>
    </div>
</div>

{{-- ======================== PROFIL DASAR ======================== --}}
<form method="POST" action="{{ route('admin.startup.update', $startup) }}" class="mb-4">
    @csrf
    @method('PUT')

    @include('admin.startup._form')

    <button type="submit" class="btn btn-utama px-4">Simpan perubahan</button>
</form>

{{-- ======================== ANGGOTA TIM ======================== --}}
<div class="panel p-4 mb-4" id="tim">
    <h2 class="h6 mb-3">Anggota tim</h2>

    @forelse ($startup->anggotaTim as $anggota)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: var(--garis) !important;">
            <div>
                <span class="fw-semibold small">{{ $anggota->nama }}</span>
                @if ($anggota->jabatan) <span class="small" style="color: var(--redup);"> &middot; {{ $anggota->jabatan }}</span> @endif
                @if ($anggota->jenis_kelamin) <span class="small" style="color: var(--redup);"> &middot; {{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span> @endif
            </div>
            <form method="POST" action="{{ route('admin.startup.anggota-tim.destroy', [$startup, $anggota]) }}"
                  onsubmit="return confirm('Hapus anggota ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    @empty
        <p class="small mb-3" style="color: var(--redup);">Belum ada anggota tim tercatat.</p>
    @endforelse

    <form method="POST" action="{{ route('admin.startup.anggota-tim.store', $startup) }}" class="row g-2 mt-3">
        @csrf
        <div class="col-md-4">
            <input type="text" class="form-control form-control-sm" name="nama" placeholder="Nama" required>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" name="jabatan" placeholder="Jabatan">
        </div>
        <div class="col-md-3">
            <select class="form-select form-select-sm" name="jenis_kelamin">
                <option value="">Jenis kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-utama btn-sm w-100">Tambah</button>
        </div>
    </form>
</div>

{{-- ======================== LEGALITAS ======================== --}}
<div class="panel p-4 mb-4" id="legalitas">
    <h2 class="h6 mb-3">Legalitas</h2>

    @forelse ($startup->legalitas as $item)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: var(--garis) !important;">
            <div>
                <span class="tag tag-netral">{{ ucfirst($item->tipe) }}</span>
                <span class="fw-semibold small ms-2">{{ $item->nama }}</span>
                @if ($item->file)
                    <a href="{{ $item->is_external ? $item->file : asset('storage/' . $item->file) }}"
                       target="_blank" class="small ms-2">Lihat berkas</a>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.startup.legalitas.destroy', [$startup, $item]) }}"
                  onsubmit="return confirm('Hapus legalitas ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    @empty
        <p class="small mb-3" style="color: var(--redup);">Belum ada legalitas tercatat.</p>
    @endforelse

    <form method="POST" action="{{ route('admin.startup.legalitas.store', $startup) }}"
          enctype="multipart/form-data" class="row g-2 mt-3">
        @csrf
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="tipe" required>
                <option value="usaha">Usaha</option>
                <option value="produk">Produk</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" name="nama"
                   placeholder="PT / NIB / BPOM / Halal" required>
        </div>
        <div class="col-md-3">
            <input type="url" class="form-control form-control-sm" name="link" placeholder="Link (opsional)">
        </div>
        <div class="col-md-3">
            <input type="file" class="form-control form-control-sm" name="berkas">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-utama btn-sm w-100">Tambah</button>
        </div>
        <div class="col-12">
            <p class="small mb-0" style="color: var(--redup);">Isi salah satu: link atau unggah berkas.</p>
        </div>
    </form>
</div>

{{-- ======================== DOKUMENTASI ======================== --}}
<div class="panel p-4 mb-4" id="dokumentasi">
    <h2 class="h6 mb-3">Dokumentasi</h2>

    @forelse ($startup->dokumentasi as $item)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: var(--garis) !important;">
            <div>
                <span class="tag tag-netral">{{ ucwords(str_replace('_', ' ', $item->kategori)) }}</span>
                <span class="fw-semibold small ms-2">{{ $item->judul ?? '(tanpa judul)' }}</span>
                @if ($item->file)
                    <a href="{{ $item->is_external ? $item->file : asset('storage/' . $item->file) }}"
                       target="_blank" class="small ms-2">Lihat berkas</a>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.startup.dokumentasi.destroy', [$startup, $item]) }}"
                  onsubmit="return confirm('Hapus dokumentasi ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    @empty
        <p class="small mb-3" style="color: var(--redup);">Belum ada dokumentasi tercatat.</p>
    @endforelse

    <form method="POST" action="{{ route('admin.startup.dokumentasi.store', $startup) }}"
          enctype="multipart/form-data" class="row g-2 mt-3">
        @csrf
        <div class="col-md-3">
            <select class="form-select form-select-sm" name="kategori" required>
                <option value="foto_ceo">Foto CEO</option>
                <option value="logo_startup">Logo startup</option>
                <option value="foto_produk">Foto produk</option>
                <option value="company_profile">Company profile</option>
                <option value="bmc">BMC</option>
                <option value="infografis">Infografis</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" name="judul" placeholder="Judul (opsional)">
        </div>
        <div class="col-md-2">
            <input type="url" class="form-control form-control-sm" name="link" placeholder="Link (opsional)">
        </div>
        <div class="col-md-3">
            <input type="file" class="form-control form-control-sm" name="berkas">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-utama btn-sm w-100">Tambah</button>
        </div>
        <div class="col-12">
            <p class="small mb-0" style="color: var(--redup);">Isi salah satu: link atau unggah berkas.</p>
        </div>
    </form>
</div>

{{-- ======================== TARGET OUTPUT ======================== --}}
<div class="panel p-4 mb-4" id="target">
    <h2 class="h6 mb-3">Target output</h2>

    @forelse ($startup->targetOutput as $item)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: var(--garis) !important;">
            <div>
                <span class="fw-semibold small">{{ $item->nama_target }}</span>
                <span class="tag tag-netral ms-2">{{ ucwords(str_replace('_', ' ', $item->status)) }}</span>
                @if ($item->keterangan)
                    <div class="small" style="color: var(--redup);">{{ $item->keterangan }}</div>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.startup.target-output.destroy', [$startup, $item]) }}"
                  onsubmit="return confirm('Hapus target ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    @empty
        <p class="small mb-3" style="color: var(--redup);">Belum ada target output tercatat.</p>
    @endforelse

    <form method="POST" action="{{ route('admin.startup.target-output.store', $startup) }}" class="row g-2 mt-3">
        @csrf
        <div class="col-md-5">
            <input type="text" class="form-control form-control-sm" name="nama_target" placeholder="Nama target" required>
        </div>
        <div class="col-md-3">
            <select class="form-select form-select-sm" name="status" required>
                <option value="belum_tercapai">Belum tercapai</option>
                <option value="proses">Proses</option>
                <option value="tercapai">Tercapai</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" name="keterangan" placeholder="Keterangan (opsional)">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-utama btn-sm w-100">Tambah</button>
        </div>
    </form>
</div>

{{-- ======================== HAPUS STARTUP ======================== --}}
<div class="panel p-4 mb-4" style="border-color: #F0C4C4;">
    <h2 class="h6 mb-2 text-danger">Zona berbahaya</h2>
    <p class="small mb-3" style="color: var(--redup);">
        Menghapus startup akan ikut menghapus seluruh anggota tim, legalitas, dokumentasi,
        pendampingan, pemantauan, dan target output miliknya. Tindakan ini tidak bisa dibatalkan.
    </p>
    <form method="POST" action="{{ route('admin.startup.destroy', $startup) }}"
          onsubmit="return confirm('Hapus startup &quot;{{ $startup->nama_startup }}&quot; beserta seluruh data terkaitnya?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus startup ini</button>
    </form>
</div>

@endsection
