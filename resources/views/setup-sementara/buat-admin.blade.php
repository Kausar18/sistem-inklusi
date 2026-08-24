@extends('layouts.app')

@section('judul', 'Setup Awal')

@section('konten')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="panel p-4 p-md-5">
            <h1 class="h4 mb-1">Buat Akun Admin Pertama</h1>
            <p class="small mb-4" style="color: var(--redup);">
                Halaman sementara — hapus route ini setelah dipakai.
            </p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST">
                @csrf

                <div class="mb-3">
                    <label class="label-filter">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="label-filter">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-4">
                    <label class="label-filter">Kata sandi (min. 8 karakter)</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-utama w-100">Buat Akun</button>
            </form>
        </div>
    </div>
</div>
@endsection
