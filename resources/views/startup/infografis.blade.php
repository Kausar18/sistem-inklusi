<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Infografis {{ $startup->nama_startup }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:      #0B2545;
            --biru:      #12459B;
            --biru-muda: #E8EFFA;
            --cyan:      #22A6C9;
            --teal:      #0E9B8A;
            --amber:     #D98B2B;
            --garis:     #DCE3EE;
            --redup:     #64748B;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 24px 16px 60px;
            background: #EEF1F6;
            font-family: 'Inter', system-ui, sans-serif;
            color: #101828;
        }

        /* ------------------------------------------------ Lembar A4 */
        .lembar {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 10px 40px rgba(11,37,69,.14);
            display: flex;
            flex-direction: column;
        }

        /* ------------------------------------------------ Kepala */
        .kepala {
            background: linear-gradient(115deg, var(--navy) 0%, var(--biru) 65%, #1A63C4 100%);
            color: #fff;
            padding: 18mm 14mm 12mm;
            position: relative;
        }

        .kepala::after {
            content: '';
            position: absolute;
            inset: auto 0 0 0;
            height: 4px;
            background: linear-gradient(90deg, var(--cyan), var(--teal), var(--amber));
        }

        .eyebrow {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: rgba(255,255,255,.7);
            margin-bottom: 10px;
        }

        .baris-kepala { display: flex; gap: 16px; align-items: center; }

        .logo {
            width: 84px;
            height: 84px;
            flex: 0 0 84px;
            border-radius: 12px;
            background: #fff;
            padding: 6px;
            object-fit: contain;
            display: grid;
            place-items: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 34px;
            color: var(--biru);
            overflow: hidden;
        }

        .nama {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 26px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -.02em;
            margin: 0 0 6px;
        }

        .subnama { font-size: 12px; color: rgba(255,255,255,.8); margin: 0; }

        .pil {
            display: inline-block;
            border: 1px solid rgba(255,255,255,.32);
            background: rgba(255,255,255,.12);
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: 600;
            margin: 8px 5px 0 0;
        }

        /* ------------------------------------------------ Isi */
        .isi { padding: 10mm 14mm 12mm; flex: 1; }

        .judul-bagian {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--biru);
            padding-bottom: 5px;
            border-bottom: 2px solid var(--biru-muda);
            margin: 0 0 10px;
        }

        .bagian { margin-bottom: 9mm; }

        /* Angka kunci */
        .angka-kunci {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .kotak-angka {
            border: 1px solid var(--garis);
            border-radius: 10px;
            padding: 10px 12px;
            border-top: 3px solid var(--aksen, var(--biru));
        }

        .kotak-angka .label {
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--redup);
        }

        .kotak-angka .nilai {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.2;
            margin-top: 3px;
            font-variant-numeric: tabular-nums;
        }

        .kotak-angka .catatan { font-size: 9px; color: var(--redup); margin-top: 2px; }

        /* Tabel data */
        .data { width: 100%; border-collapse: collapse; }

        .data th, .data td {
            text-align: left;
            vertical-align: top;
            padding: 5px 0;
            font-size: 10.5px;
            border-bottom: 1px solid #F1F4F9;
        }

        .data th { width: 34%; font-weight: 500; color: var(--redup); }

        .dua-kolom { display: grid; grid-template-columns: 1fr 1fr; gap: 0 10mm; }

        /* Galeri */
        .galeri { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }

        .galeri img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--garis);
            display: block;
        }

        .narasi {
            font-size: 10.5px;
            line-height: 1.6;
            color: #344054;
            margin: 0;
            white-space: pre-line;
        }

        .tag-kecil {
            display: inline-block;
            background: var(--biru-muda);
            color: var(--biru);
            border-radius: 999px;
            padding: 3px 9px;
            font-size: 9.5px;
            font-weight: 600;
            margin: 0 4px 4px 0;
        }

        .tag-kecil.tanah { background: #FBF0E0; color: var(--amber); }

        /* Kaki */
        .kaki {
            border-top: 1px solid var(--garis);
            padding: 6mm 14mm;
            font-size: 9px;
            color: var(--redup);
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        /* ------------------------------------------------ Alat cetak */
        .bilah-alat {
            position: fixed;
            right: 20px;
            bottom: 20px;
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .bilah-alat button,
        .bilah-alat a {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid var(--garis);
            background: #fff;
            color: var(--navy);
            border-radius: 8px;
            padding: 9px 16px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(11,37,69,.12);
        }

        .bilah-alat .utama { background: var(--biru); color: #fff; border-color: var(--biru); }

        @media print {
            body { background: #fff; padding: 0; }
            .lembar { box-shadow: none; width: auto; min-height: auto; }
            .bilah-alat { display: none; }
            @page { size: A4; margin: 0; }
        }

        @media (max-width: 820px) {
            .lembar { width: 100%; }
            .angka-kunci { grid-template-columns: repeat(2, 1fr); }
            .dua-kolom, .galeri { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="bilah-alat">
    <a href="{{ route('startup.show', $startup) }}">Kembali</a>
    <button class="utama" onclick="window.print()">Cetak / Simpan PDF</button>
</div>

<div class="lembar">

    {{-- ==================== KEPALA ==================== --}}
    <header class="kepala">
        <div class="eyebrow">Profil Startup Binaan &middot; LPA2I</div>

        <div class="baris-kepala">
            @if ($logo)
                <img src="{{ $logo->gambar(400) }}" alt="" class="logo" referrerpolicy="no-referrer"
                     onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'logo',textContent:@js(mb_substr($startup->nama_startup, 0, 1))}))">
            @else
                <div class="logo">{{ mb_substr($startup->nama_startup, 0, 1) }}</div>
            @endif

            <div>
                <h1 class="nama">{{ $startup->nama_startup }}</h1>
                <p class="subnama">
                    {{ $startup->nama_ceo }}
                    @if ($startup->kota) &middot; {{ $startup->kota }} @endif
                </p>

                <div>
                    @if ($startup->bidangUsaha)
                        <span class="pil">{{ $startup->bidangUsaha->nama_bidang }}</span>
                    @endif
                    @if ($startup->skema_program)
                        <span class="pil">{{ $startup->skema_program }}</span>
                    @endif
                    @if ($startup->batch)
                        <span class="pil">{{ $startup->batch }}</span>
                    @endif
                    @if (in_array($startup->asal_invensi, ['IPB', 'Kombinasi']))
                        <span class="pil">Invensi {{ $startup->asal_invensi }}</span>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="isi">

        {{-- ==================== ANGKA KUNCI ==================== --}}
        <section class="bagian">
            <div class="angka-kunci">
                <div class="kotak-angka" style="--aksen: var(--teal);">
                    <div class="label">Omzet {{ $startup->periode_omset_awal ?? 'awal' }}</div>
                    <div class="nilai">
                        @if ($startup->omset_awal > 0)
                            {{ $ringkas($startup->omset_awal) }}
                        @else
                            &mdash;
                        @endif
                    </div>
                    <div class="catatan">
                        {{ $startup->omset_awal > 0 ? 'Rp ' . number_format($startup->omset_awal, 0, ',', '.') : 'Belum ada data' }}
                    </div>
                </div>

                <div class="kotak-angka" style="--aksen: var(--biru);">
                    <div class="label">Tenaga kerja</div>
                    <div class="nilai">{{ $startup->total_tenaga_kerja }}</div>
                    <div class="catatan">
                        {{ $startup->tenaga_kerja_l }} laki-laki &middot; {{ $startup->tenaga_kerja_p }} perempuan
                    </div>
                </div>

                <div class="kotak-angka" style="--aksen: var(--cyan);">
                    <div class="label">Jangkauan pasar</div>
                    <div class="nilai" style="font-size: 13px;">
                        {{ $startup->jangkauan_pasar ?? '—' }}
                    </div>
                    <div class="catatan">Saat pendaftaran</div>
                </div>

                <div class="kotak-angka" style="--aksen: var(--amber);">
                    <div class="label">Legalitas</div>
                    <div class="nilai">{{ $startup->legalitas->count() }}</div>
                    <div class="catatan">
                        {{ $startup->legalitas->where('tipe', 'usaha')->count() }} usaha &middot;
                        {{ $startup->legalitas->where('tipe', 'produk')->count() }} produk
                    </div>
                </div>
            </div>
        </section>

        {{-- ==================== PRODUK ==================== --}}
        <section class="bagian">
            <h2 class="judul-bagian">Produk &amp; Usaha</h2>

            <div class="dua-kolom">
                <table class="data">
                    <tr>
                        <th>Nama produk</th>
                        <td>{{ $startup->nama_produk ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Mulai usaha</th>
                        <td>{{ $startup->mulai_usaha?->translatedFormat('F Y') ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Kapasitas produksi</th>
                        <td>{{ Str::limit($startup->kapasitas_produksi ?? '—', 90) }}</td>
                    </tr>
                </table>

                <table class="data">
                    <tr>
                        <th>Harga produk</th>
                        <td>{{ Str::limit($startup->harga_produk ?? '—', 90) }}</td>
                    </tr>
                    <tr>
                        <th>Modal awal</th>
                        <td>
                            {{ $startup->modal_awal > 0 ? 'Rp ' . number_format($startup->modal_awal, 0, ',', '.') : ($startup->modal_awal_teks ?? '—') }}
                            @if ($startup->sumber_modal)
                                <span style="color: var(--redup);">({{ $startup->sumber_modal }})</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Asal invensi</th>
                        <td>{{ $startup->asal_invensi }}</td>
                    </tr>
                </table>
            </div>

            @if ($startup->deskripsi_produk)
                <p class="narasi" style="margin-top: 8px;">
                    {{ Str::limit($startup->deskripsi_produk, 620) }}
                </p>
            @endif
        </section>

        {{-- ==================== FOTO PRODUK ==================== --}}
        @if ($fotoProduk->isNotEmpty())
            <section class="bagian">
                <h2 class="judul-bagian">Dokumentasi Produk</h2>
                <div class="galeri">
                    @foreach ($fotoProduk->take(3) as $foto)
                        <img src="{{ $foto->gambar(600) }}" alt="" loading="lazy"
                             referrerpolicy="no-referrer" onerror="this.remove()">
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ==================== TIM & LEGALITAS ==================== --}}
        <section class="bagian">
            <div class="dua-kolom">
                <div>
                    <h2 class="judul-bagian">Tim Inti</h2>
                    <table class="data">
                        @forelse ($startup->anggotaTim->take(6) as $anggota)
                            <tr>
                                <th style="width: 55%">{{ $anggota->nama }}</th>
                                <td>
                                    {{ $anggota->jabatan ?? '—' }}
                                    @if ($anggota->jenis_kelamin)
                                        <span style="color: var(--redup);">({{ $anggota->jenis_kelamin }})</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" style="color: var(--redup);">Data tim belum tercatat.</td></tr>
                        @endforelse
                    </table>
                </div>

                <div>
                    <h2 class="judul-bagian">Legalitas</h2>
                    @forelse ($startup->legalitas as $item)
                        <span class="tag-kecil {{ $item->tipe === 'produk' ? 'tanah' : '' }}">
                            {{ $item->nama }}
                        </span>
                    @empty
                        <p class="narasi" style="color: var(--redup);">Belum ada legalitas tercatat.</p>
                    @endforelse

                    @if ($startup->nama_dosen_pembimbing)
                        <h2 class="judul-bagian" style="margin-top: 12px;">Pembimbing Invensi</h2>
                        <p class="narasi">{{ $startup->nama_dosen_pembimbing }}</p>
                    @endif
                </div>
            </div>
        </section>

        {{-- ==================== RENCANA ==================== --}}
        @if ($startup->rencana_pengembangan)
            <section class="bagian">
                <h2 class="judul-bagian">Rencana Pengembangan</h2>
                <p class="narasi">{{ Str::limit($startup->rencana_pengembangan, 700) }}</p>
            </section>
        @endif

    </div>

    {{-- ==================== KAKI ==================== --}}
    <footer class="kaki">
        <span>
            @if ($startup->no_wa) {{ $startup->no_wa }} @endif
            @if ($startup->email) &middot; {{ $startup->email }} @endif
            @if ($startup->website) &middot; {{ $startup->website }} @endif
        </span>
        <span>Data per {{ now()->translatedFormat('d F Y') }}</span>
    </footer>

</div>

</body>
</html>
