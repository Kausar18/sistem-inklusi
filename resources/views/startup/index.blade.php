@extends('layouts.app')

@section('judul', 'Data Startup')

@section('konten')

{{-- ======================== JUDUL HALAMAN ======================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
        <h1 class="h4 mb-1">Data Startup Binaan</h1>
        <p class="mb-0 small" style="color: var(--redup);">
            @if ($adaFilter)
                {{ number_format($statistik['jumlah'], 0, ',', '.') }} startup cocok dengan filter,
                dari total {{ number_format($totalSemua, 0, ',', '.') }} terdaftar
            @else
                {{ number_format($totalSemua, 0, ',', '.') }} startup terdaftar dalam program
            @endif
        </p>
    </div>
</div>

{{-- ======================== KARTU STATISTIK ======================== --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="statistik masuk-halaman" style="--aksen: var(--biru); --tunda: 0ms;">
            <div class="statistik-label">Startup</div>
            <div class="statistik-angka">{{ number_format($statistik['jumlah'], 0, ',', '.') }}</div>
            <div class="statistik-catatan">{{ $statistik['invensi_ipb'] }} melibatkan invensi IPB</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="statistik masuk-halaman" style="--aksen: var(--teal); --tunda: 60ms;">
            <div class="statistik-label">Total omset awal</div>
            <div class="statistik-angka">
                @php $miliar = $statistik['omset'] / 1_000_000_000; @endphp
                Rp {{ $miliar >= 1 ? number_format($miliar, 1, ',', '.') . ' M' : number_format($statistik['omset'] / 1_000_000, 0, ',', '.') . ' Jt' }}
            </div>
            <div class="statistik-catatan">Akumulasi seluruh startup</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="statistik masuk-halaman" style="--aksen: var(--cyan); --tunda: 120ms;">
            <div class="statistik-label">Tenaga kerja</div>
            <div class="statistik-angka">
                {{ number_format($statistik['tenaga_l'] + $statistik['tenaga_p'], 0, ',', '.') }}
            </div>
            <div class="statistik-catatan">
                {{ $statistik['tenaga_l'] }} laki-laki &middot; {{ $statistik['tenaga_p'] }} perempuan
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="statistik masuk-halaman" style="--aksen: var(--amber); --tunda: 180ms;">
            <div class="statistik-label">Sebaran wilayah</div>
            <div class="statistik-angka">{{ $statistik['wilayah'] }}</div>
            <div class="statistik-catatan">Kota/kabupaten terdata</div>
        </div>
    </div>
</div>

{{-- ======================== FILTER ======================== --}}
<div class="panel mb-4">
    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="border-color: var(--garis) !important;">
        <button class="btn btn-sm btn-link text-decoration-none px-0 fw-semibold d-flex align-items-center gap-2"
                type="button" data-bs-toggle="collapse" data-bs-target="#panelFilter"
                style="color: var(--navy);">
            <i class="bi bi-sliders"></i> Pencarian &amp; filter
        </button>

        @if ($adaFilter)
            <a href="{{ route('startup.index') }}" class="btn btn-sm btn-link text-decoration-none small">
                Atur ulang
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('startup.index') }}"
          class="collapse {{ $adaFilter ? 'show' : '' }} p-3" id="panelFilter">
        <div class="row g-3">

            <div class="col-lg-4">
                <label class="label-filter d-block" for="q">Cari</label>
                <input type="text" class="form-control" id="q" name="q"
                       value="{{ request('q') }}" placeholder="Nama startup, CEO, atau produk">
            </div>

            <div class="col-lg-2 col-md-4">
                <label class="label-filter d-block" for="kota">Wilayah</label>
                <select class="form-select" id="kota" name="kota">
                    <option value="">Semua wilayah</option>
                    @foreach ($daftarKota as $kota)
                        <option value="{{ $kota }}" @selected(request('kota') === $kota)>{{ $kota }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3 col-md-4">
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

            <div class="col-lg-3 col-md-4">
                <label class="label-filter d-block" for="batch">Batch</label>
                <select class="form-select" id="batch" name="batch">
                    <option value="">Semua batch</option>
                    @foreach ($daftarBatch as $batch)
                        <option value="{{ $batch }}" @selected(request('batch') === $batch)>{{ $batch }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="label-filter d-block" for="omset_min">Omset minimal (Rp)</label>
                <input type="text" class="form-control" id="omset_min" name="omset_min"
                       value="{{ request('omset_min') }}" placeholder="100000000" inputmode="numeric">
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="label-filter d-block" for="omset_max">Omset maksimal (Rp)</label>
                <input type="text" class="form-control" id="omset_max" name="omset_max"
                       value="{{ request('omset_max') }}" placeholder="1000000000" inputmode="numeric">
            </div>

            <div class="col-lg-2 col-md-4">
                <label class="label-filter d-block" for="invensi">Asal invensi</label>
                <select class="form-select" id="invensi" name="invensi">
                    <option value="">Semua</option>
                    <option value="IPB" @selected(request('invensi') === 'IPB')>IPB</option>
                    <option value="Mandiri" @selected(request('invensi') === 'Mandiri')>Mandiri</option>
                    <option value="Kombinasi" @selected(request('invensi') === 'Kombinasi')>Kombinasi</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-4">
                <label class="label-filter d-block" for="gender">CEO</label>
                <select class="form-select" id="gender" name="gender">
                    <option value="">Semua</option>
                    <option value="L" @selected(request('gender') === 'L')>Laki-laki</option>
                    <option value="P" @selected(request('gender') === 'P')>Perempuan</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-4">
                <label class="label-filter d-block" for="urut">Urutkan</label>
                <select class="form-select" id="urut" name="urut">
                    <option value="">Nama (A-Z)</option>
                    <option value="omset_tertinggi" @selected(request('urut') === 'omset_tertinggi')>Omset tertinggi</option>
                    <option value="omset_terendah" @selected(request('urut') === 'omset_terendah')>Omset terendah</option>
                    <option value="tenaga_kerja" @selected(request('urut') === 'tenaga_kerja')>Tenaga kerja terbanyak</option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-utama px-4">Terapkan filter</button>
            </div>
        </div>
    </form>
</div>

{{-- ======================== FILTER AKTIF ======================== --}}
@if ($adaFilter)
    <div class="d-flex flex-wrap gap-2 mb-3">
        @if (request('q'))       <span class="chip">Cari: {{ request('q') }}</span> @endif
        @if (request('kota'))    <span class="chip">Wilayah: {{ request('kota') }}</span> @endif
        @if (request('batch'))   <span class="chip">{{ request('batch') }}</span> @endif
        @if (request('invensi')) <span class="chip">Invensi: {{ request('invensi') }}</span> @endif
        @if (request('gender'))  <span class="chip">CEO: {{ request('gender') === 'L' ? 'Laki-laki' : 'Perempuan' }}</span> @endif
        @if (request('omset_min')) <span class="chip">Omset &ge; Rp {{ number_format((float) preg_replace('/\D/', '', request('omset_min')), 0, ',', '.') }}</span> @endif
        @if (request('omset_max')) <span class="chip">Omset &le; Rp {{ number_format((float) preg_replace('/\D/', '', request('omset_max')), 0, ',', '.') }}</span> @endif
    </div>
@endif

{{-- ======================== DAFTAR KARTU ======================== --}}
@if ($startups->isEmpty())
    <div class="panel text-center py-5">
        <i class="bi bi-search d-block mb-2" style="font-size: 1.75rem; color: var(--redup);"></i>
        <p class="fw-semibold mb-1">Tidak ada startup yang cocok</p>
        <p class="small mb-3" style="color: var(--redup);">Coba ubah kata kunci atau kosongkan sebagian filter.</p>
        <a href="{{ route('startup.index') }}" class="btn btn-utama btn-sm px-3">Tampilkan semua</a>
    </div>
@else
    <div class="row g-3">
        @foreach ($startups as $startup)
            @php
                $logo = $startup->dokumentasi->first();
                $persen = $statistik['omset_maks'] > 0
                    ? max(2, round(($startup->omset_awal / $statistik['omset_maks']) * 100))
                    : 0;
            @endphp

            <div class="col-md-6 col-xl-4">
                <a href="{{ route('startup.show', $startup) }}" class="tautan-kartu">
                <article class="kartu-startup reveal" style="--tunda: {{ ($loop->index % 3) * 80 }}ms">

                    <div class="kartu-kepala">
                        @if ($logo && $logo->url_gambar)
                            <img src="{{ $logo->url_gambar }}" alt="" class="logo-kotak"
                                 referrerpolicy="no-referrer"
                                 onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'logo-kotak',textContent:@js(mb_substr($startup->nama_startup, 0, 1))}))">
                        @else
                            <div class="logo-kotak">{{ mb_substr($startup->nama_startup, 0, 1) }}</div>
                        @endif

                        <div class="flex-grow-1 min-width-0">
                            <div class="nama-startup">{{ $startup->nama_startup }}</div>
                            <div class="baris-meta">
                                {{ $startup->nama_ceo }}
                                @if ($startup->kota)
                                    &middot; {{ $startup->kota }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="kartu-isi">
                        @if ($startup->nama_produk)
                            <p class="small mb-3" style="color: var(--redup);">
                                {{ Str::limit($startup->nama_produk, 80) }}
                            </p>
                        @endif

                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <div class="label-kecil">Omset {{ $startup->periode_omset_awal ?? 'awal' }}</div>
                                <div class="nilai-omset">
                                    @if ($startup->omset_awal > 0)
                                        Rp {{ number_format($startup->omset_awal, 0, ',', '.') }}
                                    @else
                                        <span class="small fw-normal" style="color: var(--redup);">Belum ada data</span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="label-kecil">Tenaga kerja</div>
                                <div class="nilai-omset">{{ $startup->total_tenaga_kerja }}</div>
                            </div>
                        </div>

                        @if ($startup->omset_awal > 0)
                            <div class="bilah" title="Perbandingan omset terhadap startup terbesar pada hasil ini">
                                <span style="width: {{ $persen }}%"></span>
                            </div>
                        @endif
                    </div>

                    <div class="kartu-kaki">
                        @if ($startup->bidangUsaha)
                            <span class="tag">{{ $startup->bidangUsaha->nama_bidang }}</span>
                        @endif

                        @if (in_array($startup->asal_invensi, ['IPB', 'Kombinasi']))
                            <span class="tag tag-ipb">Invensi {{ $startup->asal_invensi }}</span>
                        @endif

                        @if ($startup->batch || $startup->tahun_program)
                            <span class="tag tag-netral">
                                {{ $startup->batch }}{{ $startup->batch && $startup->tahun_program ? ' · ' : '' }}{{ $startup->tahun_program }}
                            </span>
                        @endif
                    </div>

                </article>
                </a>
            </div>
        @endforeach
    </div>

    @if ($startups->hasPages())
        <div class="mt-4">
            {{ $startups->links() }}
        </div>
    @endif
@endif

@endsection
