<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Sistem Inklusi') &middot; LPA2I</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:      #0B2545;
            --biru:      #12459B;
            --biru-muda: #E8EFFA;
            --cyan:      #22A6C9;
            --teal:      #0E9B8A;
            --amber:     #D98B2B;
            --emas:      #F5B335;
            --emas-tua:  #C98D18;
            --latar:     #F4F6FA;
            --garis:     #E2E7F0;
            --redup:     #64748B;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: var(--latar);
            color: #101828;
            font-weight: 500;
        }

        /* Bobot mengikuti UKURAN visual (class .h1-.h6), bukan nama tag —
           di halaman ini banyak dipakai <h2 class="h6"> untuk judul panel
           kecil, jadi disamakan supaya bobotnya konsisten dengan ukurannya. */
        h1, .h1, h2, .h2, h3, .h3, h4, .h4, .tampilan {
            font-family: 'Inter', Arial, sans-serif;
            letter-spacing: -.02em;
            font-weight: 700;
        }

        h5, .h5, h6, .h6 {
            font-family: 'Inter', Arial, sans-serif;
            letter-spacing: -.01em;
            font-weight: 600;
        }

        /* ---------------------------------------------------- Kop halaman */
        .kop {
            background: linear-gradient(115deg, var(--navy) 0%, var(--biru) 62%, #1A63C4 100%);
            color: #fff;
            box-shadow: 0 1px 0 rgba(255,255,255,.08), 0 10px 28px rgba(11,37,69,.16);
            position: relative;
            z-index: 10;
        }

        .kop .navbar-brand { color: #fff; font-weight: 800; letter-spacing: -.02em; }

        .merek-ikon {
            display: inline-grid;
            place-items: center;
            width: 32px;
            height: 32px;
            flex: 0 0 32px;
            border-radius: .55rem;
            background: linear-gradient(135deg, var(--emas), var(--emas-tua));
            color: var(--navy);
            font-size: 1rem;
        }

        .lencana-kop {
            background: rgba(255,255,255,.14);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: .25rem .7rem;
        }

        /* Tautan navigasi utama, dengan garis bawah animasi saat aktif/hover */
        .kop .nav-link {
            position: relative;
            color: rgba(255,255,255,.72);
            font-weight: 500;
            padding-top: .55rem;
            padding-bottom: .55rem;
            transition: color .15s ease;
        }

        .kop .nav-link i { font-size: .82rem; margin-right: .4rem; opacity: .8; }

        .kop .nav-link::after {
            content: '';
            position: absolute;
            left: .5rem;
            right: .5rem;
            bottom: .1rem;
            height: 2px;
            border-radius: 999px;
            background: var(--emas);
            transform: scaleX(0);
            transition: transform .2s ease;
        }

        .kop .nav-link:hover,
        .kop .nav-link.active { color: #fff; }
        .kop .nav-link:hover::after,
        .kop .nav-link.active::after { transform: scaleX(1); }

        /* Pemisah sebelum area khusus admin: mendatar di ponsel, tegak di desktop */
        .pemisah-nav {
            background: rgba(255,255,255,.18);
            width: 100%;
            height: 1px;
            margin: .5rem 0;
        }

        @media (min-width: 992px) {
            .pemisah-nav { width: 1px; height: 22px; margin: 0 .6rem; }
        }

        /* Tombol "Kelola Data": pil tegas supaya area admin terasa terpisah */
        .kop .btn-nav-admin {
            display: inline-flex;
            align-items: center;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.24);
            border-radius: 999px;
            color: #fff;
            font-weight: 600;
            font-size: .86rem;
            padding: .4rem .9rem;
            transition: background .15s ease, border-color .15s ease;
        }

        .kop .btn-nav-admin:hover,
        .kop .btn-nav-admin.active {
            background: rgba(255,255,255,.24);
            border-color: rgba(255,255,255,.4);
            color: #fff;
        }

        .kop .btn-keluar {
            display: inline-flex;
            align-items: center;
            border: 0;
            background: transparent;
            color: rgba(255,255,255,.68);
            font-weight: 500;
            font-size: .86rem;
            padding: .4rem .5rem;
            transition: color .15s ease;
        }

        .kop .btn-keluar:hover { color: #fff; }

        .kop .btn-nav-admin::after,
        .kop .btn-keluar::after { content: none; }

        @media (prefers-reduced-motion: reduce) {
            .kop .nav-link,
            .kop .nav-link::after,
            .kop .btn-nav-admin,
            .kop .btn-keluar { transition: none; }
        }

        /* ------------------------------------------------ Kartu statistik */
        .statistik {
            background: #fff;
            border: 1px solid var(--garis);
            border-radius: .875rem;
            padding: 1.1rem 1.25rem;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .statistik::before {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 3px;
            background: var(--aksen, var(--biru));
        }

        .statistik-label {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--redup);
        }

        .statistik-angka {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1.15;
            color: var(--navy);
            font-variant-numeric: tabular-nums;
            margin-top: .3rem;
        }

        .statistik-catatan { font-size: .78rem; color: var(--redup); }

        /* ---------------------------------------------------- Panel filter */
        .panel {
            background: #fff;
            border: 1px solid var(--garis);
            border-radius: .875rem;
        }

        .label-filter {
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--redup);
            margin-bottom: .3rem;
        }

        .form-control, .form-select { border-color: var(--garis); font-size: .9rem; }

        .form-control:focus, .form-select:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 .2rem rgba(34,166,201,.15);
        }

        .btn-utama {
            background: var(--biru);
            border-color: var(--biru);
            color: #fff;
            font-weight: 600;
        }

        .btn-utama:hover { background: #0E3878; border-color: #0E3878; color: #fff; }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: var(--biru-muda);
            color: var(--biru);
            border-radius: 999px;
            padding: .2rem .7rem;
            font-size: .78rem;
            font-weight: 600;
        }

        /* ------------------------------------------------- Kartu startup */
        .kartu-startup {
            background: #fff;
            border: 1px solid var(--garis);
            border-radius: .875rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }

        .kartu-startup:hover {
            transform: translateY(-2px);
            border-color: var(--biru);
            box-shadow: 0 8px 24px rgba(11,37,69,.09);
        }

        .kartu-kepala {
            display: flex;
            gap: .85rem;
            align-items: flex-start;
            padding: 1.1rem 1.15rem .75rem;
        }

        .logo-kotak {
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            border-radius: .6rem;
            border: 1px solid var(--garis);
            background: var(--biru-muda);
            object-fit: contain;
            display: grid;
            place-items: center;
            font-family: 'Inter', Arial, sans-serif;
            font-weight: 800;
            color: var(--biru);
            font-size: 1rem;
            overflow: hidden;
        }

        .nama-startup {
            font-family: 'Inter', Arial, sans-serif;
            font-weight: 700;
            font-size: .98rem;
            line-height: 1.3;
            color: var(--navy);
        }

        .baris-meta { font-size: .8rem; color: var(--redup); }

        .kartu-isi { padding: 0 1.15rem .9rem; flex: 1; }

        .label-kecil {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--redup);
        }

        .nilai-omset {
            font-family: 'Inter', Arial, sans-serif;
            font-weight: 800;
            font-size: 1.05rem;
            color: var(--navy);
            font-variant-numeric: tabular-nums;
        }

        /* Bilah omset: panjangnya sebanding dengan omset tertinggi
           pada hasil pencarian saat ini. */
        .bilah {
            height: 5px;
            border-radius: 999px;
            background: #EDF1F7;
            overflow: hidden;
            margin-top: .45rem;
        }

        .bilah span {
            display: block;
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--cyan), var(--teal));
        }

        .kartu-kaki {
            border-top: 1px solid var(--garis);
            padding: .7rem 1.15rem;
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            align-items: center;
            background: #FBFCFE;
        }

        .tag {
            font-size: .72rem;
            font-weight: 600;
            border-radius: 999px;
            padding: .18rem .6rem;
            background: var(--biru-muda);
            color: var(--biru);
        }

        .tag-ipb    { background: #FBF0E0; color: var(--amber); }
        .tag-netral { background: #EEF1F6; color: var(--redup); }

        .page-link { color: var(--biru); border-color: var(--garis); }
        .page-item.active .page-link { background: var(--biru); border-color: var(--biru); }
        .pagination svg { width: 1rem; height: 1rem; }

        /* ------------------------------------------------ Halaman detail */
        .logo-besar {
            width: 132px;
            height: 132px;
            flex: 0 0 132px;
            border-radius: .9rem;
            border: 1px solid var(--garis);
            background: #fff;
            object-fit: contain;
            padding: .5rem;
            font-family: 'Inter', Arial, sans-serif;
            font-weight: 800;
            font-size: 2.6rem;
            color: var(--biru);
            overflow: hidden;
        }

        @media (max-width: 575px) {
            .logo-besar {
                width: 96px;
                height: 96px;
                flex: 0 0 96px;
                font-size: 2rem;
            }
        }

        /* Potret CEO */
        .bingkai-potret {
            display: block;
            border-radius: .75rem;
            overflow: hidden;
            border: 1px solid var(--garis);
            background: var(--biru-muda);
        }

        .bingkai-potret img {
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
            display: block;
            transition: transform .25s ease;
        }

        .bingkai-potret:hover img { transform: scale(1.03); }

        /* Galeri foto produk */
        .bingkai-foto {
            position: relative;
            display: block;
            border-radius: .75rem;
            overflow: hidden;
            border: 1px solid var(--garis);
            background: var(--biru-muda);
        }

        .bingkai-foto img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            display: block;
            transition: transform .25s ease;
        }

        .bingkai-foto:hover img { transform: scale(1.04); }

        .lapisan-foto {
            position: absolute;
            inset: auto 0 0 0;
            padding: .55rem .75rem;
            background: linear-gradient(transparent, rgba(11,37,69,.82));
            color: #fff;
            font-size: .76rem;
            font-weight: 600;
            opacity: 0;
            transition: opacity .2s ease;
        }

        .bingkai-foto:hover .lapisan-foto,
        .bingkai-foto:focus-visible .lapisan-foto { opacity: 1; }

        .kotak-kosong {
            background: #F8FAFD;
            border: 1px dashed var(--garis);
            border-radius: .75rem;
            padding: 1.1rem 1.25rem;
        }

        .kotak-banding {
            background: #F8FAFD;
            border: 1px solid var(--garis);
            border-radius: .75rem;
            padding: 1rem 1.1rem;
            height: 100%;
        }

        .angka-banding {
            font-family: 'Inter', Arial, sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--navy);
            font-variant-numeric: tabular-nums;
        }

        .teks-naik  { color: var(--teal); }
        .teks-turun { color: #C0392B; }

        .daftar-data dt {
            font-weight: 500;
            color: var(--redup);
            font-size: .85rem;
            padding-bottom: .55rem;
        }

        .daftar-data dd {
            font-size: .88rem;
            padding-bottom: .55rem;
            margin-bottom: 0;
        }

        .daftar-data.kecil dt,
        .daftar-data.kecil dd { font-size: .82rem; padding-bottom: .4rem; }

        .teks-narasi {
            font-size: .88rem;
            line-height: 1.65;
            white-space: pre-line;
            color: #344054;
        }

        .baris-linimasa {
            display: flex;
            gap: .75rem;
            padding: .6rem 0 .6rem .25rem;
            position: relative;
        }

        .baris-linimasa:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 4px;
            top: 1.5rem;
            bottom: -.4rem;
            width: 1px;
            background: var(--garis);
        }

        .titik-linimasa {
            width: 9px;
            height: 9px;
            flex: 0 0 9px;
            border-radius: 50%;
            background: var(--cyan);
            margin-top: .38rem;
        }

        .inisial {
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            border-radius: 50%;
            background: var(--biru-muda);
            color: var(--biru);
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: .8rem;
        }

        .tag-tautan { text-decoration: none; }
        .tag-tautan:hover { background: #D5E2F7; color: var(--biru); }

        /* Peta lokasi usaha */
        .peta {
            border-radius: .75rem;
            overflow: hidden;
            border: 1px solid var(--garis);
            background: var(--biru-muda);
            line-height: 0;
        }

        .peta iframe {
            width: 100%;
            height: 220px;
            border: 0;
            display: block;
        }

        /* Tabel riwayat pemantauan */
        .tabel-rapi thead th {
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--redup);
            border-bottom: 1px solid var(--garis);
            white-space: nowrap;
        }

        .tabel-rapi td { font-size: .85rem; border-color: var(--garis); }

        /* Lencana panjang periode */
        .lencana-periode {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .04em;
            border-radius: 999px;
            padding: .12rem .5rem;
            background: var(--biru-muda);
            color: var(--biru);
        }

        .lencana-sebagian { background: #FBF0E0; color: var(--amber); }

        .catatan-metode {
            background: #FDF8EF;
            border: 1px solid #F0E0C4;
            border-radius: .6rem;
            padding: .75rem .9rem;
            font-size: .8rem;
            line-height: 1.6;
            color: #6B5330;
        }

        /* Modal */
        .modal-content { border: 0; border-radius: .9rem; }
        .modal-header, .modal-footer { border-color: var(--garis); }

        /* ================================================================
           HALAMAN BERANDA
           ================================================================ */

        .kapital-kecil {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--biru);
        }

        .btn-emas {
            background: var(--emas);
            border-color: var(--emas);
            color: #3D2B00;
            font-weight: 700;
        }

        .btn-emas:hover { background: var(--emas-tua); border-color: var(--emas-tua); color: #2B1E00; }

        .btn-hero-samar {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.34);
            color: #fff;
            font-weight: 600;
        }

        .btn-hero-samar:hover { background: rgba(255,255,255,.2); color: #fff; }

        /* ---------------------------------------------------------- Hero */
        .hero {
            position: relative;
            min-height: 420px;
            display: flex;
            align-items: center;
            padding: 4rem 0;
            background: var(--navy);
            overflow: hidden;
        }

        .hero-gambar {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-selubung {
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg,
                        rgba(11,37,69,.94) 0%,
                        rgba(18,69,155,.82) 55%,
                        rgba(26,99,196,.62) 100%);
        }

        /* Saat foto hero belum tersedia: pola garis halus sebagai penanda */
        .hero.tanpa-gambar .hero-selubung {
            background:
                repeating-linear-gradient(45deg,
                    rgba(255,255,255,.045) 0 12px,
                    transparent 12px 24px),
                linear-gradient(105deg, var(--navy) 0%, var(--biru) 60%, #1A63C4 100%);
        }

        .hero-isi { position: relative; color: #fff; max-width: 660px; }

        .lencana-hero {
            display: inline-block;
            background: rgba(255,255,255,.14);
            border: 1px solid rgba(255,255,255,.24);
            border-radius: 999px;
            padding: .3rem .85rem;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .04em;
            margin-bottom: 1rem;
        }

        .hero-judul {
            font-family: 'Inter', Arial, sans-serif;
            font-size: clamp(2.1rem, 5vw, 3.2rem);
            font-weight: 800;
            letter-spacing: -.03em;
            line-height: 1.08;
            margin-bottom: .75rem;
        }

        .hero-teks {
            font-size: 1.02rem;
            line-height: 1.65;
            color: rgba(255,255,255,.84);
            margin-bottom: 0;
        }

        /* Penanda konten yang belum diisi */
        .penanda-konten {
            margin-top: 1.75rem;
            font-size: .74rem;
            letter-spacing: .03em;
            color: rgba(255,255,255,.5);
            border-left: 2px solid rgba(255,255,255,.28);
            padding-left: .65rem;
        }

        .penanda-konten.tengah {
            border-left: 0;
            border-top: 1px dashed var(--garis);
            padding: .75rem 0 0;
            color: var(--redup);
            text-align: center;
        }

        .hero:not(.tanpa-gambar) .penanda-konten { display: none; }

        /* -------------------------------------------------- Pita angka */
        .pita-angka {
            background: #fff;
            border: 1px solid var(--garis);
            border-radius: .875rem;
            box-shadow: 0 10px 30px rgba(11,37,69,.07);
            margin-top: -2.75rem;
            position: relative;
            overflow: hidden;
        }

        .sel-angka { padding: 1.4rem .75rem; }

        .sel-angka + .sel-angka { border-left: 1px solid var(--garis); }

        @media (max-width: 991px) {
            .sel-angka:nth-child(odd) { border-left: 0; }
            .sel-angka:nth-child(n+3) { border-top: 1px solid var(--garis); }
        }

        .angka-besar {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }

        .angka-label {
            font-size: .76rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--redup);
            margin-top: .25rem;
        }

        /* --------------------------------------- Penampung teks & gambar */
        .penampung-teks {
            background: #FBFCFE;
            border: 1px dashed var(--garis);
            border-radius: .6rem;
            padding: 1rem 1.1rem;
            font-size: 1rem;
            line-height: 1.7;
            color: #344054;
        }

        .bingkai-konten {
            position: relative;
            border-radius: .875rem;
            overflow: hidden;
            border: 1px solid var(--garis);
            background: #fff;
        }

        .bingkai-konten img { width: 100%; height: 100%; object-fit: cover; display: block; }

        .bingkai-konten figcaption { display: none; }

        /* Tampilan saat berkas gambar belum ada */
        .bingkai-konten.kosong {
            border-style: dashed;
            background:
                repeating-linear-gradient(45deg,
                    #F2F5FA 0 10px,
                    #FAFBFD 10px 20px);
            aspect-ratio: 9 / 7;
            display: grid;
            place-items: center;
        }

        .bingkai-konten.rasio-4-3.kosong { aspect-ratio: 4 / 3; }

        .bingkai-konten.kosong figcaption {
            display: block;
            font-size: .74rem;
            font-weight: 600;
            color: var(--redup);
            text-align: center;
            padding: 0 1rem;
        }

        .latar-lembut { background: #EEF2F8; }

        /* ------------------------------------------------ Kartu tahapan */
        .kartu-tahap {
            background: #fff;
            border: 1px solid var(--garis);
            border-radius: .875rem;
            padding: 1.4rem 1.25rem 1.25rem;
            position: relative;
        }

        .nomor-tahap {
            position: absolute;
            top: 1rem;
            right: 1.1rem;
            font-family: 'Inter', Arial, sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #EDF1F7;
            line-height: 1;
        }

        .ikon-tahap {
            display: block;
            font-size: 1.5rem;
            color: var(--biru);
            margin-bottom: .85rem;
        }

        /* ------------------------------------------------ Pita ajakan */
        .pita-ajakan {
            background: linear-gradient(105deg, var(--navy) 0%, var(--biru) 70%, #1A63C4 100%);
            border-radius: .875rem;
            padding: 2rem 2.25rem;
            color: #fff;
        }

        /* Kartu di daftar bisa diklik */
        .tautan-kartu { text-decoration: none; color: inherit; display: block; height: 100%; }
        .tautan-kartu:focus-visible .kartu-startup { outline: 2px solid var(--cyan); outline-offset: 2px; }

        /* ---------------------------------------- Animasi saat digulir (scroll) */
        .reveal {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity .6s ease, transform .6s ease;
            transition-delay: var(--tunda, 0ms);
        }

        .reveal.tampak { opacity: 1; transform: none; }

        .reveal.tanpa-transisi { transition: none; }

        @media (prefers-reduced-motion: reduce) {
            .kartu-startup { transition: none; }
            .kartu-startup:hover { transform: none; }
            .bingkai-foto img,
            .bingkai-potret img { transition: none; }
            .bingkai-foto:hover img,
            .bingkai-potret:hover img { transform: none; }

            .reveal { opacity: 1; transform: none; transition: none; }
        }
    </style>
    <noscript><style>.reveal { opacity: 1 !important; transform: none !important; }</style></noscript>
</head>
<body>

<nav class="navbar navbar-expand-lg kop">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('beranda') }}">
            <span class="merek-ikon"><i class="bi bi-diagram-3-fill"></i></span>
            Sistem Inklusi
            <span class="lencana-kop d-none d-sm-inline">LPA2I</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuUtama">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}"
                       href="{{ route('beranda') }}"><i class="bi bi-house"></i>Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('statistik') ? 'active' : '' }}"
                       href="{{ route('statistik') }}"><i class="bi bi-bar-chart"></i>Statistik</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('startup.*') ? 'active' : '' }}"
                       href="{{ route('startup.index') }}"><i class="bi bi-building"></i>Data Startup</a>
                </li>
                @auth
                    <li class="pemisah-nav" aria-hidden="true"></li>
                    <li class="nav-item">
                        <a class="nav-link btn-nav-admin {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                           href="{{ route('admin.startup.index') }}">
                            <i class="bi bi-speedometer2"></i>Kelola Data
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn-keluar">
                                <i class="bi bi-box-arrow-right"></i>Keluar
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main>
    @hasSection('penuh')
        @yield('penuh')
    @else
        <div class="container py-4">
            @include('partials.flash')
            @yield('konten')
        </div>
    @endif
</main>

<footer class="py-4 mt-2">
    <div class="container text-center small" style="color: var(--redup);">
        Sistem Inklusi &middot; Lembaga Pengembangan Agribisnis dan Inkubator Inovasi
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    const elemen = document.querySelectorAll('.reveal');
    if (!elemen.length) return;

    const kurangGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (kurangGerak) {
        elemen.forEach((el) => el.classList.add('tampak'));
        return;
    }

    const sudahTerlihat = (el) => {
        const rect = el.getBoundingClientRect();
        return rect.top < window.innerHeight && rect.bottom > 0;
    };

    // Elemen yang sudah kelihatan saat halaman dibuka langsung ditampilkan
    // tanpa animasi — supaya tidak semuanya "muncul" bersamaan begitu
    // halaman dipencet. Hanya elemen yang masih di luar layar yang akan
    // dianimasikan ketika nanti masuk viewport karena digulir.
    elemen.forEach((el) => {
        if (sudahTerlihat(el)) el.classList.add('tanpa-transisi', 'tampak');
    });

    const belumTampak = () => Array.from(elemen).filter((el) => !el.classList.contains('tampak'));

    if ('IntersectionObserver' in window) {
        const pengamat = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('tampak');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

        belumTampak().forEach((el) => pengamat.observe(el));
    }

    // Jaring pengaman: kalau ada lompatan gulir yang melewati sebuah
    // elemen dalam satu kali tanpa pernah tertangkap IntersectionObserver
    // (mis. tombol End, tautan jangkar), cek ulang secara manual saat
    // scroll/resize supaya elemen itu tidak tersembunyi selamanya.
    let terjadwal = false;
    const periksaManual = () => {
        terjadwal = false;
        belumTampak().forEach((el) => {
            if (sudahTerlihat(el)) el.classList.add('tampak');
        });
    };
    const jadwalkanPeriksa = () => {
        if (terjadwal) return;
        terjadwal = true;
        requestAnimationFrame(periksaManual);
    };

    window.addEventListener('scroll', jadwalkanPeriksa, { passive: true });
    window.addEventListener('resize', jadwalkanPeriksa);
})();
</script>
@stack('skrip')
</body>
</html>
