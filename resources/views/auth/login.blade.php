@extends('layouts.app')

@section('judul', 'Masuk')

@section('konten')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="panel p-4 p-md-5">
            <h1 class="h4 mb-1">Masuk</h1>
            <p class="small mb-4" style="color: var(--redup);">
                Khusus tim pengelola Sistem Inklusi.
            </p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="label-filter">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="label-filter">Kata sandi</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="ingat" id="ingat" class="form-check-input" value="1">
                    <label for="ingat" class="form-check-label small">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-utama w-100">Masuk</button>
            </form>
        </div>
    </div>
</div>
@endsection
