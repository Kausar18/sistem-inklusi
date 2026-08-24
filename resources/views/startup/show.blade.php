@extends('layouts.app')

@section('judul', $startup->nama_startup)

@section('konten')

{{-- ======================== NAVIGASI BALIK ======================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <a href="{{ url()->previous() === url()->current() ? route('startup.index') : url()->previous() }}"
       class="d-inline-flex align-items-center gap-2 text-decoration-none small"
       style="color: var(--redup);">
        <i class="bi bi-arrow-left"></i> Kembali ke daftar
    </a>

    @auth
        <a href="{{ route('admin.startup.edit', $startup) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-pencil-square me-1"></i> Kelola profil ini
        </a>
    @endauth
</div>


{{-- ======================== KEPALA PROFIL ======================== --}}
<div class="panel p-4 mb-4">
    <div class="d-flex flex-wrap gap-3 align-items-start">

        @if ($logo)
            <img src="{{ $logo->gambar(600) }}" alt="Logo {{ $startup->nama_startup }}"
                 class="logo-besar" referrerpolicy="no-referrer"
                 onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'logo-besar d-grid',style:'place-items:center',textContent:@js(mb_substr($startup->nama_startup, 0, 1))}))">
        @else
            <div class="logo-besar d-grid" style="place-items:center">{{ mb_substr($startup->nama_startup, 0, 1) }}</div>
        @endif

        <div class="flex-grow-1" style="min-width: 260px;">
            <h1 class="h4 mb-1">{{ $startup->nama_startup }}</h1>

            <p class="mb-2 small" style="color: var(--redup);">
                {{ $startup->nama_ceo }}
                <span class="mx-1">&middot;</span>
                {{ $startup->jenis_kelamin_ceo === 'L' ? 'Laki-laki' : 'Perempuan' }}
                @if ($startup->kota)
                    <span class="mx-1">&middot;</span> {{ $startup->kota }}
                @endif
            </p>

            <div class="d-flex flex-wrap gap-2">
                @if ($startup->bidangUsaha)
                    <span class="tag">{{ $startup->bidangUsaha->nama_bidang }}</span>
                @endif
                @if (in_array($startup->asal_invensi, ['IPB', 'Kombinasi']))
                    <span class="tag tag-ipb">Invensi {{ $startup->asal_invensi }}</span>
                @endif
                @if ($startup->batch)
                    <span class="tag tag-netral">{{ $startup->batch }}</span>
                @endif
                @if ($startup->skema_program)
                    <span class="tag tag-netral">{{ $startup->skema_program }}</span>
                @endif
            </div>
        </div>

        {{-- Kontak cepat --}}
        <div class="d-flex flex-column gap-2">
            <a href="{{ route('startup.infografis', $startup) }}" target="_blank"
               class="btn btn-sm btn-utama text-start">
                <i class="bi bi-file-earmark-richtext me-1"></i> Infografis profil
            </a>
            @if ($startup->no_wa)
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $startup->no_wa)) }}"
                   target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary text-start">
                    <i class="bi bi-whatsapp me-1"></i> {{ $startup->no_wa }}
                </a>
            @endif
            @if ($startup->email)
                <a href="mailto:{{ $startup->email }}" class="btn btn-sm btn-outline-secondary text-start">
                    <i class="bi bi-envelope me-1"></i> {{ $startup->email }}
                </a>
            @endif
            @if ($startup->website)
                <a href="{{ $startup->website }}" target="_blank" rel="noopener"
                   class="btn btn-sm btn-outline-secondary text-start">
                    <i class="bi bi-globe me-1"></i> Website
                </a>
            @endif
        </div>
    </div>
</div>

{{-- ======================== PERBANDINGAN BEFORE-AFTER ======================== --}}
<div class="panel p-4 mb-4" id="kinerja">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h2 class="h6 mb-0">Perkembangan kinerja</h2>
        <div class="d-flex align-items-center gap-3">
            @if ($terbaru)
                <span class="small" style="color: var(--redup);">
                    Pemantauan terakhir: {{ $terbaru->tanggal->translatedFormat('d F Y') }}
                </span>
            @endif
            @auth
                <button class="btn btn-utama btn-sm" data-bs-toggle="modal" data-bs-target="#formMonitoring">
                    <i class="bi bi-plus-lg me-1"></i> Catat pemantauan
                </button>
            @endauth
        </div>
    </div>

    @if (! $terbaru)
        <div class="kotak-kosong">
            <p class="fw-semibold mb-1">Belum ada data pemantauan</p>
            <p class="small mb-0" style="color: var(--redup);">
                Data awal sudah tercatat. Klik <strong>Catat pemantauan</strong> untuk
                mengisi kinerja terkini; perbandingan sebelum-sesudah akan langsung muncul di sini.
            </p>
        </div>
    @else
        <div class="row g-3">
            @foreach ($perbandingan as $item)
                @php
                    $before = $item['before'];
                    $after  = $item['after'];
                    $adaKeduanya = $before > 0 && $after !== null;
                    $selisih = $adaKeduanya ? $after - $before : null;
                    $persen  = $adaKeduanya ? round((($after - $before) / $before) * 100, 1) : null;
                    $naik    = $selisih !== null && $selisih >= 0;
                    $format  = fn ($n) => $item['format'] === 'rupiah'
                        ? 'Rp ' . number_format($n, 0, ',', '.')
                        : number_format($n, 0, ',', '.');
                @endphp

                <div class="col-md-6">
                    <div class="kotak-banding">
                        <div class="label-kecil mb-2">{{ $item['label'] }}</div>

                        <div class="d-flex align-items-end justify-content-between gap-3">
                            <div>
                                <div class="label-kecil">Sebelum</div>
                                <div class="angka-banding">{{ $format($before) }}</div>
                            </div>

                            <i class="bi bi-arrow-right mb-2" style="color: var(--redup);"></i>

                            <div class="text-end">
                                <div class="label-kecil">Sesudah</div>
                                <div class="angka-banding">
                                    {{ $after !== null ? $format($after) : '—' }}
                                </div>
                            </div>
                        </div>

                        @if ($persen !== null)
                            <div class="mt-2 fw-semibold small {{ $naik ? 'teks-naik' : 'teks-turun' }}">
                                <i class="bi bi-arrow-{{ $naik ? 'up' : 'down' }}-right"></i>
                                {{ $naik ? '+' : '' }}{{ number_format($persen, 1, ',', '.') }}%
                                <span class="fw-normal" style="color: var(--redup);">
                                    ({{ $naik ? '+' : '' }}{{ $format($selisih) }})
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- Indikator kualitatif --}}
            @foreach ([
                ['Jumlah mitra',      $terbaru->jumlah_mitra],
                ['Wilayah penjualan', $terbaru->wilayah_penjualan ?: $startup->jangkauan_pasar],
                ['Izin edar',         $terbaru->izin_edar],
            ] as [$label, $nilai])
                @if (filled($nilai))
                    <div class="col-md-4">
                        <div class="kotak-banding">
                            <div class="label-kecil mb-1">{{ $label }}</div>
                            <div class="fw-semibold">{{ $nilai }}</div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

{{-- ============ PERBANDINGAN OMZET ANTAR PERIODE (data pendaftaran) ============ --}}
@if ($startup->omset_awal > 0 || $startup->omset_pembanding > 0)
    <div class="panel p-4 mb-4">
        <h2 class="h6 mb-1">Omzet menurut data pendaftaran</h2>
        <p class="small mb-3" style="color: var(--redup);">
            Dua nilai berikut berasal dari formulir pendaftaran, bukan dari pemantauan.
        </p>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="kotak-banding h-100">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="label-kecil">{{ $startup->periode_omset_awal ?? 'Periode awal' }}</div>
                        <span class="lencana-periode">12 bulan</span>
                    </div>
                    <div class="angka-banding mt-2">
                        {{ $startup->omset_awal > 0 ? 'Rp ' . number_format($startup->omset_awal, 0, ',', '.') : '—' }}
                    </div>
                    @if ($startup->omset_awal_teks)
                        <div class="small mt-1" style="color: var(--redup);">
                            Tertulis: {{ $startup->omset_awal_teks }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="kotak-banding h-100">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="label-kecil">{{ $startup->periode_omset_pembanding ?? 'Periode berjalan' }}</div>
                        <span class="lencana-periode lencana-sebagian">3 bulan</span>
                    </div>
                    <div class="angka-banding mt-2">
                        {{ $startup->omset_pembanding > 0 ? 'Rp ' . number_format($startup->omset_pembanding, 0, ',', '.') : '—' }}
                    </div>
                    @if ($startup->omset_pembanding_teks)
                        <div class="small mt-1" style="color: var(--redup);">
                            Tertulis: {{ $startup->omset_pembanding_teks }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="catatan-metode mt-3">
            <i class="bi bi-exclamation-circle me-1"></i>
            <strong>Kedua angka ini tidak sebanding.</strong>
            Nilai pertama mencakup satu tahun penuh (12 bulan), sedangkan nilai kedua
            baru mencakup tiga bulan pertama tahun 2026. Selisih di antara keduanya
            karena itu <em>bukan</em> penurunan atau kenaikan kinerja, dan persentase
            perubahan sengaja tidak dihitung. Perbandingan kinerja yang sahih memakai
            data pemantauan pada panel di atas.
        </div>
    </div>
@endif

{{-- ======================== RIWAYAT PEMANTAUAN ======================== --}}
@if ($startup->monitoring->isNotEmpty())
    <div class="panel p-4 mb-4">
        <h2 class="h6 mb-3">Riwayat pemantauan</h2>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0 tabel-rapi">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Periode</th>
                        <th class="text-end">Omzet</th>
                        <th class="text-center">Tenaga kerja</th>
                        <th class="text-center">Mitra</th>
                        <th>Wilayah penjualan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($startup->monitoring as $catatan)
                        <tr>
                            <td class="text-nowrap">{{ $catatan->tanggal->translatedFormat('d M Y') }}</td>
                            <td>{{ $catatan->periode ?? '—' }}</td>
                            <td class="text-end" style="font-variant-numeric: tabular-nums;">
                                {{ $catatan->omzet !== null ? 'Rp ' . number_format($catatan->omzet, 0, ',', '.') : '—' }}
                            </td>
                            <td class="text-center">
                                @if ($catatan->tenaga_kerja_l !== null || $catatan->tenaga_kerja_p !== null)
                                    {{ $catatan->total_tenaga_kerja }}
                                    <span class="small" style="color: var(--redup);">
                                        ({{ $catatan->tenaga_kerja_l ?? 0 }}L/{{ $catatan->tenaga_kerja_p ?? 0 }}P)
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">{{ $catatan->jumlah_mitra ?? '—' }}</td>
                            <td>{{ $catatan->wilayah_penjualan ?? '—' }}</td>
                            <td class="text-end">
                                @auth
                                    <form method="POST"
                                          action="{{ route('monitoring.destroy', [$startup, $catatan]) }}"
                                          onsubmit="return confirm('Hapus catatan pemantauan tanggal {{ $catatan->tanggal->translatedFormat('d M Y') }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-link text-danger p-0" title="Hapus catatan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endauth
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="row g-4">

    {{-- ==================== KOLOM KIRI ==================== --}}
    <div class="col-lg-8">

        {{-- Produk --}}
        <div class="panel p-4 mb-4">
            <h2 class="h6 mb-3">Produk &amp; usaha</h2>

            <dl class="row mb-0 daftar-data">
                <dt class="col-sm-4">Nama produk/jasa</dt>
                <dd class="col-sm-8">{{ $startup->nama_produk ?? '—' }}</dd>

                @if ($startup->judul_proposal)
                    <dt class="col-sm-4">Judul proposal</dt>
                    <dd class="col-sm-8">{{ $startup->judul_proposal }}</dd>
                @endif

                <dt class="col-sm-4">Mulai usaha</dt>
                <dd class="col-sm-8">
                    {{ $startup->mulai_usaha?->translatedFormat('d F Y') ?? '—' }}
                </dd>

                <dt class="col-sm-4">Kapasitas produksi</dt>
                <dd class="col-sm-8">{{ $startup->kapasitas_produksi ?? '—' }}</dd>

                <dt class="col-sm-4">Harga produk</dt>
                <dd class="col-sm-8">{{ $startup->harga_produk ?? '—' }}</dd>

                <dt class="col-sm-4">Jangkauan pasar</dt>
                <dd class="col-sm-8">{{ $startup->jangkauan_pasar ?? '—' }}</dd>

                <dt class="col-sm-4">Modal awal</dt>
                <dd class="col-sm-8">
                    @if ($startup->modal_awal > 0)
                        Rp {{ number_format($startup->modal_awal, 0, ',', '.') }}
                        @if ($startup->sumber_modal)
                            <span class="small" style="color: var(--redup);">&middot; {{ $startup->sumber_modal }}</span>
                        @endif
                    @else
                        {{ $startup->modal_awal_teks ?? '—' }}
                    @endif
                </dd>

                <dt class="col-sm-4">Asal invensi</dt>
                <dd class="col-sm-8">
                    {{ $startup->asal_invensi }}
                    @if ($startup->nama_dosen_pembimbing)
                        <div class="small" style="color: var(--redup);">
                            {{ $startup->nama_dosen_pembimbing }}
                        </div>
                    @endif
                </dd>
            </dl>

            @if ($startup->deskripsi_produk)
                <hr class="my-3" style="border-color: var(--garis);">
                <div class="label-kecil mb-2">Deskripsi</div>
                <p class="mb-0 teks-narasi">{{ $startup->deskripsi_produk }}</p>
            @endif
        </div>

        {{-- Galeri foto produk --}}
        @if ($fotoProduk->isNotEmpty())
            <div class="panel p-4 mb-4">
                <h2 class="h6 mb-3">Foto produk</h2>

                <div class="row g-3">
                    @foreach ($fotoProduk as $foto)
                        <div class="col-sm-6">
                            <a href="{{ $foto->file }}" target="_blank" rel="noopener" class="bingkai-foto">
                                <img src="{{ $foto->gambar(800) }}"
                                     alt="Foto produk {{ $startup->nama_startup }}"
                                     loading="lazy" referrerpolicy="no-referrer"
                                     onerror="this.closest('.col-sm-6').remove()">
                                <span class="lapisan-foto">
                                    <i class="bi bi-arrows-fullscreen"></i> Lihat ukuran penuh
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Narasi --}}
        @if ($startup->permasalahan_utama || $startup->rencana_pengembangan)
            <div class="panel p-4 mb-4">
                <h2 class="h6 mb-3">Permasalahan &amp; rencana</h2>

                @if ($startup->permasalahan_utama)
                    <div class="label-kecil mb-2">Permasalahan utama</div>
                    <p class="teks-narasi">{{ $startup->permasalahan_utama }}</p>
                @endif

                @if ($startup->rencana_pengembangan)
                    <div class="label-kecil mb-2 mt-3">Rencana pengembangan</div>
                    <p class="teks-narasi mb-0">{{ $startup->rencana_pengembangan }}</p>
                @endif
            </div>
        @endif

        {{-- Riwayat pendampingan --}}
        <div class="panel p-4 mb-4" id="pendampingan">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h2 class="h6 mb-0">Riwayat pendampingan</h2>
                @auth
                    <button class="btn btn-utama btn-sm" data-bs-toggle="modal" data-bs-target="#formPendampingan">
                        <i class="bi bi-plus-lg me-1"></i> Tambah kegiatan
                    </button>
                @endauth
            </div>

            @forelse ($startup->pendampingan as $kegiatan)
                <div class="baris-linimasa">
                    <div class="titik-linimasa"></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="fw-semibold small">
                                {{ ucwords(str_replace('_', ' ', $kegiatan->jenis)) }}
                            </div>
                            @auth
                                <form method="POST"
                                      action="{{ route('pendampingan.destroy', [$startup, $kegiatan]) }}"
                                      onsubmit="return confirm('Hapus kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-link text-danger p-0" title="Hapus kegiatan">
                                        <i class="bi bi-trash small"></i>
                                    </button>
                                </form>
                            @endauth
                        </div>
                        <div class="small" style="color: var(--redup);">
                            {{ $kegiatan->tanggal?->translatedFormat('d F Y') ?? 'Tanggal belum dicatat' }}
                            @if ($kegiatan->pendamping) &middot; {{ $kegiatan->pendamping }} @endif
                            @if ($kegiatan->lokasi) &middot; {{ $kegiatan->lokasi }} @endif
                        </div>
                        @if ($kegiatan->catatan)
                            <p class="small mb-0 mt-1">{{ $kegiatan->catatan }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="kotak-kosong">
                    <p class="small mb-0" style="color: var(--redup);">
                        @auth
                            Belum ada kegiatan pendampingan yang tercatat. Klik
                            <strong>Tambah kegiatan</strong> untuk mencatat training,
                            mentoring, atau business matching.
                        @else
                            Belum ada kegiatan pendampingan yang tercatat.
                        @endauth
                    </p>
                </div>
            @endforelse
        </div>

    </div>

    {{-- ==================== KOLOM KANAN ==================== --}}
    <div class="col-lg-4">

        {{-- Profil CEO --}}
        <div class="panel p-4 mb-4">
            <h2 class="h6 mb-3">Profil CEO</h2>

            @if ($fotoCeo)
                <a href="{{ $fotoCeo->file }}" target="_blank" rel="noopener" class="d-block mb-3 bingkai-potret">
                    <img src="{{ $fotoCeo->gambar(600) }}" alt="Foto {{ $startup->nama_ceo }}"
                         loading="lazy" referrerpolicy="no-referrer"
                         onerror="this.closest('.bingkai-potret').remove()">
                </a>
            @endif

            <dl class="row mb-0 daftar-data kecil">
                <dt class="col-5">Nama</dt>
                <dd class="col-7">{{ $startup->nama_ceo }}</dd>

                <dt class="col-5">Pendidikan</dt>
                <dd class="col-7">{{ $startup->pendidikan_terakhir ?? '—' }}</dd>

                <dt class="col-5">Asal sekolah</dt>
                <dd class="col-7">{{ $startup->asal_sekolah ?? '—' }}</dd>

                @if ($startup->jurusan)
                    <dt class="col-5">Jurusan</dt>
                    <dd class="col-7">{{ $startup->jurusan }}</dd>
                @endif

                <dt class="col-5">Tahun lulus</dt>
                <dd class="col-7">{{ $startup->tahun_lulus ?? ($startup->semester ? 'Semester ' . $startup->semester : '—') }}</dd>
            </dl>
        </div>

        {{-- Tim inti --}}
        <div class="panel p-4 mb-4">
            <h2 class="h6 mb-3">
                Tim inti
                <span class="fw-normal small" style="color: var(--redup);">
                    ({{ $startup->anggotaTim->count() }} orang)
                </span>
            </h2>

            @forelse ($startup->anggotaTim as $anggota)
                <div class="d-flex align-items-center gap-2 py-2 {{ ! $loop->last ? 'border-bottom' : '' }}"
                     style="border-color: var(--garis) !important;">
                    <div class="inisial">{{ mb_substr($anggota->nama, 0, 1) }}</div>
                    <div class="flex-grow-1">
                        <div class="small fw-semibold">{{ $anggota->nama }}</div>
                        <div class="small" style="color: var(--redup);">
                            {{ $anggota->jabatan ?? '—' }}
                            @if ($anggota->jenis_kelamin)
                                &middot; {{ $anggota->jenis_kelamin }}
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="small mb-0" style="color: var(--redup);">Data tim belum tercatat.</p>
            @endforelse

            <hr class="my-3" style="border-color: var(--garis);">

            <div class="d-flex justify-content-between small">
                <span style="color: var(--redup);">Total tenaga kerja</span>
                <span class="fw-semibold">
                    {{ $startup->total_tenaga_kerja }}
                    <span class="fw-normal" style="color: var(--redup);">
                        ({{ $startup->tenaga_kerja_l }}L / {{ $startup->tenaga_kerja_p }}P)
                    </span>
                </span>
            </div>
        </div>

        {{-- Legalitas --}}
        <div class="panel p-4 mb-4">
            <h2 class="h6 mb-3">Legalitas</h2>

            @forelse ($startup->legalitas->groupBy('tipe') as $tipe => $daftar)
                <div class="label-kecil mb-2 {{ ! $loop->first ? 'mt-3' : '' }}">
                    Legalitas {{ $tipe }}
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($daftar as $item)
                        @if ($item->file)
                            <a href="{{ $item->file }}" target="_blank" rel="noopener" class="tag tag-tautan">
                                {{ $item->nama }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        @else
                            <span class="tag">{{ $item->nama }}</span>
                        @endif
                    @endforeach
                </div>
            @empty
                <p class="small mb-0" style="color: var(--redup);">Belum ada legalitas tercatat.</p>
            @endforelse
        </div>

        {{-- Berkas --}}
        <div class="panel p-4 mb-4">
            <h2 class="h6 mb-3">Berkas &amp; dokumentasi</h2>

            @forelse ($berkas as $file)
                <a href="{{ $file->file }}" target="_blank" rel="noopener"
                   class="d-flex align-items-center gap-2 py-2 text-decoration-none {{ ! $loop->last ? 'border-bottom' : '' }}"
                   style="border-color: var(--garis) !important; color: var(--navy);">
                    <i class="bi bi-file-earmark-text" style="color: var(--biru);"></i>
                    <span class="small flex-grow-1">
                        {{ ucwords(str_replace('_', ' ', $file->kategori)) }}
                    </span>
                    <i class="bi bi-box-arrow-up-right small" style="color: var(--redup);"></i>
                </a>
            @empty
                <p class="small mb-0" style="color: var(--redup);">Belum ada berkas terunggah.</p>
            @endforelse
        </div>

        {{-- Alamat & peta --}}
        <div class="panel p-4">
            <h2 class="h6 mb-3">Lokasi usaha</h2>

            @php
                // Peta memakai teks alamat apa adanya. Jika alamat usaha kosong,
                // gunakan kota sebagai perkiraan lokasi.
                $alamatPeta = $startup->alamat_usaha ?: $startup->kota;
            @endphp

            @if ($alamatPeta)
                <div class="peta mb-3">
                    <iframe
                        src="https://maps.google.com/maps?q={{ urlencode($alamatPeta) }}&z=14&output=embed"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta lokasi {{ $startup->nama_startup }}"
                        allowfullscreen></iframe>
                </div>

                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($alamatPeta) }}"
                   target="_blank" rel="noopener"
                   class="btn btn-sm btn-outline-secondary w-100 mb-3">
                    <i class="bi bi-geo-alt me-1"></i> Buka di Google Maps
                </a>
            @else
                <div class="kotak-kosong mb-3">
                    <p class="small mb-0" style="color: var(--redup);">
                        Alamat usaha belum terisi, sehingga peta belum bisa ditampilkan.
                    </p>
                </div>
            @endif

            <div class="label-kecil mb-1">Alamat usaha</div>
            <p class="small mb-3">{{ $startup->alamat_usaha ?? '—' }}</p>

            <div class="label-kecil mb-1">Alamat rumah CEO</div>
            <p class="small mb-0">{{ $startup->alamat_rumah ?? '—' }}</p>
        </div>

    </div>
</div>

@auth
{{-- ======================== MODAL: CATAT PEMANTAUAN ======================== --}}
<div class="modal fade" id="formMonitoring" tabindex="-1" aria-labelledby="judulMonitoring" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content" method="POST" action="{{ route('monitoring.store', $startup) }}">
            @csrf

            <div class="modal-header">
                <div>
                    <h3 class="h6 mb-1" id="judulMonitoring">Catat pemantauan kinerja</h3>
                    <p class="small mb-0" style="color: var(--redup);">
                        {{ $startup->nama_startup }} &middot; catatan terbaru dipakai sebagai titik "sesudah"
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="m_tanggal">Tanggal pencatatan</label>
                        <input type="date" class="form-control" id="m_tanggal" name="tanggal"
                               value="{{ old('tanggal', now()->toDateString()) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="m_periode">Periode</label>
                        <input type="text" class="form-control" id="m_periode" name="periode"
                               value="{{ old('periode') }}" placeholder="mis. Triwulan 2 2026">
                    </div>

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="m_omzet">Omzet periode ini (Rp)</label>
                        <input type="text" class="form-control" id="m_omzet" name="omzet"
                               value="{{ old('omzet') }}" placeholder="1200000000" inputmode="numeric">
                        <div class="form-text small">
                            Sebelumnya: {{ $startup->omset_awal > 0 ? 'Rp ' . number_format($startup->omset_awal, 0, ',', '.') : 'belum ada data' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="m_mitra">Jumlah mitra</label>
                        <input type="number" class="form-control" id="m_mitra" name="jumlah_mitra"
                               value="{{ old('jumlah_mitra') }}" min="0" placeholder="0">
                    </div>

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="m_tk_l">Tenaga kerja laki-laki</label>
                        <input type="number" class="form-control" id="m_tk_l" name="tenaga_kerja_l"
                               value="{{ old('tenaga_kerja_l', $startup->tenaga_kerja_l) }}" min="0">
                    </div>

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="m_tk_p">Tenaga kerja perempuan</label>
                        <input type="number" class="form-control" id="m_tk_p" name="tenaga_kerja_p"
                               value="{{ old('tenaga_kerja_p', $startup->tenaga_kerja_p) }}" min="0">
                    </div>

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="m_wilayah">Wilayah penjualan</label>
                        <input type="text" class="form-control" id="m_wilayah" name="wilayah_penjualan"
                               value="{{ old('wilayah_penjualan', $startup->jangkauan_pasar) }}"
                               placeholder="mis. Nasional">
                    </div>

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="m_izin">Izin edar</label>
                        <input type="text" class="form-control" id="m_izin" name="izin_edar"
                               value="{{ old('izin_edar') }}" placeholder="mis. PIRT, Halal, BPOM">
                    </div>

                    <div class="col-12">
                        <label class="label-filter d-block" for="m_catatan">Catatan</label>
                        <textarea class="form-control" id="m_catatan" name="catatan" rows="3"
                                  placeholder="Perkembangan lain yang perlu dicatat">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-utama px-4">Simpan pemantauan</button>
            </div>
        </form>
    </div>
</div>

{{-- ======================== MODAL: TAMBAH PENDAMPINGAN ======================== --}}
<div class="modal fade" id="formPendampingan" tabindex="-1" aria-labelledby="judulPendampingan" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('pendampingan.store', $startup) }}">
            @csrf

            <div class="modal-header">
                <div>
                    <h3 class="h6 mb-1" id="judulPendampingan">Tambah kegiatan pendampingan</h3>
                    <p class="small mb-0" style="color: var(--redup);">{{ $startup->nama_startup }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="p_jenis">Jenis kegiatan</label>
                        <select class="form-select" id="p_jenis" name="jenis" required>
                            <option value="training">Training</option>
                            <option value="mentoring">Mentoring</option>
                            <option value="business_matching">Business matching</option>
                            <option value="form_pendaftaran">Form pendaftaran</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="p_tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="p_tanggal" name="tanggal"
                               value="{{ old('tanggal', now()->toDateString()) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="p_pendamping">Nama pendamping</label>
                        <input type="text" class="form-control" id="p_pendamping" name="pendamping"
                               value="{{ old('pendamping') }}" placeholder="Nama tim pendamping">
                    </div>

                    <div class="col-md-6">
                        <label class="label-filter d-block" for="p_lokasi">Lokasi</label>
                        <input type="text" class="form-control" id="p_lokasi" name="lokasi"
                               value="{{ old('lokasi') }}" placeholder="mis. Gedung TBI IPB / Daring">
                    </div>

                    <div class="col-12">
                        <label class="label-filter d-block" for="p_catatan">Catatan kegiatan</label>
                        <textarea class="form-control" id="p_catatan" name="catatan" rows="3"
                                  placeholder="Materi yang dibahas, hasil, atau tindak lanjut">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-utama px-4">Simpan kegiatan</button>
            </div>
        </form>
    </div>
</div>
@endauth

@endsection
