@extends('layouts.app')

@section('judul', 'Setup Awal')

@section('konten')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="panel p-4 p-md-5" style="border-color: #F0C4C4;">
            <h1 class="h4 mb-1 text-danger">Hapus Semua Data Startup</h1>
            <p class="small mb-4" style="color: var(--redup);">
                Halaman sementara — dipakai untuk membersihkan data yang salah import,
                lalu dihapus lagi dari kode setelah dipakai. Tindakan ini TIDAK BISA DIBATALKAN.
            </p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
            @endif

            <form method="POST">
                @csrf
                <div class="mb-4">
                    <label class="label-filter">Ketik <strong>HAPUS</strong> untuk konfirmasi</label>
                    <input type="text" name="konfirmasi" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-outline-danger w-100">Hapus Semua Data Startup</button>
            </form>
        </div>
    </div>
</div>
@endsection
