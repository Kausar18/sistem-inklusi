@extends('layouts.app')

@section('judul', 'Beranda')

{{--
    CATATAN UNTUK PENGISIAN KONTEN
    ------------------------------
    Semua teks dan gambar di halaman ini masih berupa penampung sementara.
    Cara menggantinya:

    1. GAMBAR - taruh berkas di folder public/images/ dengan nama berikut:
         hero.jpg           1600 x 700 px   foto utama
         tentang.jpg         900 x 700 px   foto pendukung bagian "Tentang"
         kegiatan-1.jpg  s/d kegiatan-6.jpg 800 x 600 px  galeri kegiatan
       Berkas yang belum ada otomatis diganti kotak penampung,
       jadi boleh diisi bertahap.

    2. TEKS - ubah langsung pada bagian yang diberi tanda ISI DI SINI.
--}}

@section('konten')
@endsection

@section('penuh')

{{-- ======================== HERO ======================== --}}
<section class="hero">
    <img src="{{ asset('images/hero.jpg') }}" alt="" class="hero-gambar"
         onerror="this.closest('.hero').classList.add('tanpa-gambar'); this.remove()">

    <div class="hero-selubung"></div>

    <div class="container hero-isi">

        {{-- ISI DI SINI: judul utama program --}}
        <h1 class="hero-judul">Sistem Inkubasi</h1>

        {{-- ISI DI SINI: satu paragraf pengantar, 2-3 kalimat --}}
        <p class="hero-teks">
            Platform pendataan dan pemantauan startup binaan LPA2I. Menghimpun profil,
            proses pendampingan, serta perkembangan kinerja usaha dalam satu tempat.
        </p>

        <div class="d-flex flex-wrap gap-2 mt-4">
            <a href="{{ route('startup.index') }}" class="btn btn-emas px-4">
                Jelajahi startup binaan
            </a>
            <a href="{{ route('statistik') }}" class="btn btn-hero-samar px-4">
                Lihat statistik program
            </a>
        </div>

        <div class="penanda-konten">Foto hero belum diunggah &middot; public/images/hero.jpg &middot; 1600&times;700 px</div>
    </div>
</section>

{{-- ======================== ANGKA RINGKAS ======================== --}}
<section class="container">
    <div class="pita-angka reveal">
        <div class="row g-0 text-center">
            @foreach ([
                ['nilai' => $angka['startup'], 'label' => 'Startup binaan'],
                ['nilai' => $angka['tenaga'],  'label' => 'Tenaga kerja terserap'],
                ['nilai' => $angka['wilayah'], 'label' => 'Kota/kabupaten'],
                ['nilai' => $angka['bidang'],  'label' => 'Bidang usaha'],
            ] as $item)
                <div class="col-6 col-lg-3 sel-angka">
                    <div class="angka-besar" data-hitung="{{ $item['nilai'] }}">0</div>
                    <div class="angka-label">{{ $item['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================== TENTANG PROGRAM ======================== --}}
<section class="container py-5">
    <div class="row g-4 g-lg-5 align-items-end">
        <div class="col-lg-7 reveal" style="--tunda: 0ms;">
            <span class="kapital-kecil">Tentang program</span>

            {{-- ISI DI SINI: judul bagian --}}
            <h2 class="h3 mt-2 mb-3">Menumbuhkan usaha rintisan berbasis inovasi</h2>

            {{--
                DRAF sementara (belum diverifikasi) — disusun dari fakta yang
                sudah ada di sistem (nama lembaga, kategori invensi IPB,
                struktur batch & tahapan pendampingan). Mohon dikoreksi
                pengelola program sebelum publikasi final, lalu hapus
                komentar ini dan class "penampung-teks" (kotak putus-putus)
                begitu naskahnya sudah final.
            --}}
            <div class="penampung-teks">
                <p class="mb-2">
                    Lembaga Pengembangan Agribisnis dan Inkubator Inovasi (LPA2I) mendampingi
                    pertumbuhan startup berbasis inovasi, khususnya di bidang agribisnis.
                    Program ini menjembatani inovasi hasil riset — termasuk yang lahir dari
                    lingkungan IPB — maupun rintisan usaha mandiri, agar dapat berkembang
                    menjadi usaha yang mapan dan berkelanjutan.
                </p>
                <p class="mb-2">
                    Startup yang bergabung mendapat pendampingan menyeluruh: penguatan
                    kapasitas dasar usaha, mentoring berkala bersama tim pendamping dan
                    pakar, hingga business matching untuk memperluas akses pasar dan mitra
                    usaha. Seluruh proses berjalan dalam skema batch, sehingga perkembangan
                    tiap startup dapat dipantau dari waktu ke waktu.
                </p>
                <p class="mb-0">
                    Sistem Inkubasi ini sendiri merupakan wujud transparansi program —
                    menghimpun profil, proses pendampingan, dan capaian kinerja seluruh
                    startup binaan dalam satu tempat yang dapat diakses publik.
                </p>
            </div>
        </div>

        <div class="col-lg-5 reveal" style="--tunda: 120ms;">
            <figure class="bingkai-konten mb-0">
                <img src="{{ asset('images/tentang.jpg') }}" alt=""
                     onerror="this.closest('.bingkai-konten').classList.add('kosong'); this.remove()">
                <figcaption>Foto pendukung &middot; public/images/tentang.jpg &middot; 900&times;700 px</figcaption>
            </figure>
        </div>
    </div>
</section>

{{-- ======================== TAHAPAN PROGRAM ======================== --}}
<section class="latar-lembut py-5">
    <div class="container">
        <div class="text-center mb-4 reveal">
            <span class="kapital-kecil">Tahapan</span>
            <h2 class="h3 mt-2 mb-2">Alur pendampingan</h2>
            <p class="mx-auto mb-0" style="max-width: 560px; color: var(--redup);">
                Setiap startup binaan melalui rangkaian tahapan berikut.
            </p>
        </div>

        <div class="row g-3">
            @foreach ([
                ['ikon' => 'clipboard-check',   'judul' => 'Pendaftaran',              'teks' => 'Startup mendaftar dan menyerahkan profil usaha beserta proposal.'],
                ['ikon' => 'funnel',            'judul' => 'Seleksi',                  'teks' => 'Kurasi dan penilaian proposal untuk menentukan startup yang lolos ke tahap berikutnya.'],
                ['ikon' => 'easel3',            'judul' => 'Workshop Action Plan',     'teks' => 'Menyusun rencana aksi dan target pengembangan usaha bersama fasilitator.'],
                ['ikon' => 'rocket-takeoff',    'judul' => 'Bootcamp',                 'teks' => 'Pembekalan intensif kemampuan dasar usaha dan pengembangan produk.'],
                ['ikon' => 'person-video3',     'judul' => 'Coaching',                 'teks' => 'Pendampingan personal untuk mempertajam strategi dan menyelesaikan kendala usaha.'],
                ['ikon' => 'people',            'judul' => 'Mentoring',                'teks' => 'Pendampingan berkala bersama tim pendamping dan pakar.'],
                ['ikon' => 'shop-window',       'judul' => 'Business Matching dan Expo', 'teks' => 'Mempertemukan startup dengan mitra usaha, calon pembeli, dan investor melalui pameran.'],
            ] as $i => $tahap)
                <div class="col-md-6 col-xl-3">
                    <div class="kartu-tahap reveal h-100" style="--tunda: {{ ($i % 4) * 80 }}ms;">
                        <div class="nomor-tahap">{{ $i + 1 }}</div>
                        <i class="bi bi-{{ $tahap['ikon'] }} ikon-tahap"></i>
                        <h3 class="h6 mb-2">{{ $tahap['judul'] }}</h3>
                        {{-- ISI DI SINI: penjelasan tiap tahap, 1-2 kalimat --}}
                        <p class="mb-0" style="color: #344054;">{{ $tahap['teks'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="penanda-konten tengah mt-4">
            Naskah tahapan masih berupa contoh &middot; mohon disesuaikan dengan ketentuan program
        </p>
    </div>
</section>

{{-- ======================== GALERI KEGIATAN ======================== --}}
<section class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4 reveal">
        <div>
            <span class="kapital-kecil">Dokumentasi</span>
            <h2 class="h3 mt-2 mb-0">Kegiatan program</h2>
        </div>
    </div>

    <div class="row g-3">
        @for ($i = 1; $i <= 6; $i++)
            <div class="col-6 col-lg-4">
                <figure class="bingkai-konten reveal rasio-4-3 mb-0" style="--tunda: {{ ($i - 1) * 60 }}ms;">
                    <img src="{{ asset('images/kegiatan-' . $i . '.jpg') }}" alt=""
                         loading="lazy"
                         onerror="this.closest('.bingkai-konten').classList.add('kosong'); this.remove()">
                    <figcaption>kegiatan-{{ $i }}.jpg &middot; 800&times;600 px</figcaption>
                </figure>
            </div>
        @endfor
    </div>
</section>

{{-- ======================== AJAKAN ======================== --}}
<section class="container pb-5">
    <div class="pita-ajakan reveal">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <h2 class="h4 mb-2">Telusuri data startup binaan</h2>
                <p class="mb-0" style="color: rgba(255,255,255,.78);">
                    Cari berdasarkan wilayah, bidang usaha, omset, maupun batch program.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('startup.index') }}" class="btn btn-emas px-4">
                    Buka data startup
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('skrip')
<script>
(function () {
    const elemen = document.querySelectorAll('[data-hitung]');
    if (!elemen.length) return;

    const formatAngka = (n) => new Intl.NumberFormat('id-ID').format(Math.round(n));
    const kurangGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const animasikan = (el) => {
        const target = parseInt(el.dataset.hitung, 10) || 0;

        if (kurangGerak) {
            el.textContent = formatAngka(target);
            return;
        }

        const durasi = 1400;
        const mulai = performance.now();

        const langkah = (waktuSekarang) => {
            const progres = Math.min((waktuSekarang - mulai) / durasi, 1);
            const easeOut = 1 - Math.pow(1 - progres, 3);
            el.textContent = formatAngka(target * easeOut);

            if (progres < 1) requestAnimationFrame(langkah);
        };

        requestAnimationFrame(langkah);
    };

    if ('IntersectionObserver' in window) {
        const pengamat = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animasikan(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });

        elemen.forEach((el) => pengamat.observe(el));
    } else {
        elemen.forEach(animasikan);
    }
})();
</script>
@endpush
