@extends('layouts.app')

@section('judul', 'Pilih Sheet')

@section('konten')

<div class="mb-4">
    <a href="{{ route('admin.import.create') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none small mb-2"
       style="color: var(--redup);">
        <i class="bi bi-arrow-left"></i> Batal, kembali
    </a>
    <h1 class="h4 mb-1">Pilih Sheet yang Benar</h1>
    <p class="mb-0" style="color: #344054; text-align: justify;">
        Berkas <strong>{{ $namaAsli }}</strong> punya lebih dari satu sheet. Pilih sheet yang berisi data yang sudah
        difilter/final — biasanya BUKAN sheet mentahan respons formulir.
    </p>
</div>

<div class="panel p-4">
    <form method="POST" action="{{ route('admin.import.proses') }}">
        @csrf
        <input type="hidden" name="path_sementara" value="{{ $pathSementara }}">
        <input type="hidden" name="nama_asli" value="{{ $namaAsli }}">
        <input type="hidden" name="batch" value="{{ $batch }}">
        <input type="hidden" name="tahun" value="{{ $tahun }}">

        <div class="mb-4">
            <label class="label-filter d-block mb-2">Sheet</label>
            @foreach ($daftarSheet as $nama)
                <div class="form-check mb-2">
                    <input type="radio" name="sheet" value="{{ $nama }}" id="sheet-{{ $loop->index }}"
                           class="form-check-input" required>
                    <label for="sheet-{{ $loop->index }}" class="form-check-label fw-semibold">{{ $nama }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-utama px-4">Import sheet ini</button>
    </form>
</div>

@endsection
