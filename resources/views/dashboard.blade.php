@extends('layouts.app')

@section('judul', 'Statistik Program')

@section('konten')

<div class="mb-4">
    <h1 class="h4 mb-1">Ringkasan Program</h1>
    <p class="mb-0 small" style="color: var(--redup);">
        Gambaran menyeluruh {{ number_format($ringkasan['startup'], 0, ',', '.') }} startup binaan
    </p>
</div>

{{-- ======================== KARTU STATISTIK ======================== --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="statistik reveal" style="--aksen: var(--biru); --tunda: 0ms;">
            <div class="statistik-label">Startup binaan</div>
            <div class="statistik-angka">{{ number_format($ringkasan['startup'], 0, ',', '.') }}</div>
            <div class="statistik-catatan">{{ $ringkasan['bidang'] }} bidang usaha</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="statistik reveal" style="--aksen: var(--teal); --tunda: 80ms;">
            <div class="statistik-label">Total omset awal</div>
            <div class="statistik-angka">
                @php $miliar = $ringkasan['omset'] / 1_000_000_000; @endphp
                Rp {{ $miliar >= 1 ? number_format($miliar, 1, ',', '.') . ' M' : number_format($ringkasan['omset'] / 1_000_000, 0, ',', '.') . ' Jt' }}
            </div>
            <div class="statistik-catatan">Sebelum pendampingan</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="statistik reveal" style="--aksen: var(--cyan); --tunda: 160ms;">
            <div class="statistik-label">Tenaga kerja terserap</div>
            <div class="statistik-angka">
                {{ number_format($ringkasan['tenaga_l'] + $ringkasan['tenaga_p'], 0, ',', '.') }}
            </div>
            <div class="statistik-catatan">
                {{ round($ringkasan['tenaga_p'] / max(1, $ringkasan['tenaga_l'] + $ringkasan['tenaga_p']) * 100) }}% perempuan
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="statistik reveal" style="--aksen: var(--amber); --tunda: 240ms;">
            <div class="statistik-label">Invensi IPB</div>
            <div class="statistik-angka">{{ $ringkasan['invensi_ipb'] }}</div>
            <div class="statistik-catatan">
                {{ round($ringkasan['invensi_ipb'] / max(1, $ringkasan['startup']) * 100) }}% dari seluruh startup
            </div>
        </div>
    </div>
</div>

{{-- ======================== PERINGATAN DATA MONITORING ======================== --}}
@if ($ringkasan['monitoring'] === 0)
    <div class="panel reveal p-3 mb-4 d-flex gap-3 align-items-start" style="border-left: 3px solid var(--amber);">
        <i class="bi bi-info-circle mt-1" style="color: var(--amber);"></i>
        <div>
            <div class="fw-semibold small mb-1">Data pemantauan belum tersedia</div>
            <p class="small mb-0" style="color: var(--redup);">
                Angka di halaman ini berasal dari data pendaftaran, yaitu kondisi
                <em>sebelum</em> pendampingan. Grafik perbandingan sebelum-sesudah akan
                muncul setelah kinerja startup dicatat melalui modul pemantauan.
            </p>
        </div>
    </div>
@endif

{{-- ======================== GRAFIK BARIS 1 ======================== --}}
<div class="row g-3 mb-3">

    <div class="col-lg-7">
        <div class="panel reveal p-4 h-100" style="--tunda: 0ms;">
            <h2 class="h6 mb-1">Sebaran bidang usaha</h2>
            <p class="small mb-3" style="color: var(--redup);">Jumlah startup per bidang</p>
            <div style="height: 300px;"><canvas id="grafikBidang"></canvas></div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="panel reveal p-4 h-100" style="--tunda: 100ms;">
            <h2 class="h6 mb-1">Sebaran wilayah</h2>
            <p class="small mb-3" style="color: var(--redup);">10 kota/kabupaten teratas</p>
            <div style="height: 300px;"><canvas id="grafikWilayah"></canvas></div>
        </div>
    </div>
</div>

{{-- ======================== GRAFIK BARIS 2 ======================== --}}
<div class="row g-3 mb-3">

    <div class="col-lg-4">
        <div class="panel reveal p-4 h-100" style="--tunda: 0ms;">
            <h2 class="h6 mb-1">Komposisi tenaga kerja</h2>
            <p class="small mb-3" style="color: var(--redup);">
                Total {{ number_format($ringkasan['tenaga_l'] + $ringkasan['tenaga_p'], 0, ',', '.') }} orang
            </p>
            <div style="height: 230px;"><canvas id="grafikTenaga"></canvas></div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel reveal p-4 h-100" style="--tunda: 100ms;">
            <h2 class="h6 mb-1">Jenis kelamin CEO</h2>
            <p class="small mb-3" style="color: var(--redup);">
                {{ $ringkasan['ceo_p'] }} startup dipimpin perempuan
            </p>
            <div style="height: 230px;"><canvas id="grafikCeo"></canvas></div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel reveal p-4 h-100" style="--tunda: 200ms;">
            <h2 class="h6 mb-1">Asal invensi produk</h2>
            <p class="small mb-3" style="color: var(--redup);">Sumber inovasi yang dikembangkan</p>
            <div style="height: 230px;"><canvas id="grafikInvensi"></canvas></div>
        </div>
    </div>
</div>

{{-- ======================== PERBANDINGAN BATCH ======================== --}}
<div class="row g-3 mb-3">

    <div class="col-lg-7">
        <div class="panel reveal p-4 h-100" style="--tunda: 0ms;">
            <h2 class="h6 mb-1">Perbandingan antar batch</h2>
            <p class="small mb-3" style="color: var(--redup);">Jumlah startup dan tenaga kerja per batch</p>
            <div style="height: 280px;"><canvas id="grafikBatch"></canvas></div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="panel reveal p-4 h-100" style="--tunda: 100ms;">
            <h2 class="h6 mb-1">Jangkauan pasar</h2>
            <p class="small mb-3" style="color: var(--redup);">Cakupan penjualan saat mendaftar</p>
            <div style="height: 280px;"><canvas id="grafikPasar"></canvas></div>
        </div>
    </div>
</div>

{{-- ======================== OMSET TERATAS ======================== --}}
<div class="panel reveal p-4">
    <h2 class="h6 mb-1">Omset awal tertinggi</h2>
    <p class="small mb-3" style="color: var(--redup);">8 startup dengan omset terbesar saat mendaftar</p>

    @php $maks = $omsetTeratas->max('omset_awal') ?: 1; @endphp

    @foreach ($omsetTeratas as $item)
        <a href="{{ route('startup.show', $item) }}"
           class="d-block text-decoration-none py-2 {{ ! $loop->last ? 'border-bottom' : '' }}"
           style="border-color: var(--garis) !important; color: inherit;">
            <div class="d-flex justify-content-between align-items-baseline gap-3 mb-1">
                <span class="small fw-semibold">{{ $item->nama_startup }}</span>
                <span class="small fw-semibold" style="font-variant-numeric: tabular-nums;">
                    Rp {{ number_format($item->omset_awal, 0, ',', '.') }}
                </span>
            </div>
            <div class="bilah">
                <span style="width: {{ max(2, round($item->omset_awal / $maks * 100)) }}%"></span>
            </div>
        </a>
    @endforeach
</div>

@endsection

@push('skrip')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function () {
    // ---------------------------------------------------- Pengaturan umum
    const navy  = '#0B2545';
    const biru  = '#12459B';
    const cyan  = '#22A6C9';
    const teal  = '#0E9B8A';
    const amber = '#D98B2B';
    const redup = '#64748B';
    const garis = '#E2E7F0';

    Chart.defaults.font.family = "'Inter', Arial, sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = redup;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.boxWidth = 8;

    const rapiAngka = (n) => new Intl.NumberFormat('id-ID').format(n);

    // -------------------------------------------------- Sebaran bidang usaha
    const bidang = @json($bidangUsaha);

    new Chart(document.getElementById('grafikBidang'), {
        type: 'bar',
        data: {
            labels: Object.keys(bidang),
            datasets: [{
                label: 'Startup',
                data: Object.values(bidang),
                backgroundColor: biru,
                borderRadius: 4,
                barThickness: 16,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: garis } },
                y: { grid: { display: false } },
            },
        },
    });

    // ------------------------------------------------------ Sebaran wilayah
    const wilayah = @json($wilayah);

    new Chart(document.getElementById('grafikWilayah'), {
        type: 'bar',
        data: {
            labels: Object.keys(wilayah),
            datasets: [{
                label: 'Startup',
                data: Object.values(wilayah),
                backgroundColor: cyan,
                borderRadius: 4,
                barThickness: 16,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: garis } },
                y: { grid: { display: false } },
            },
        },
    });

    // ------------------------------------------- Komposisi tenaga kerja (L/P)
    const tenaga = @json($genderTenaga);

    new Chart(document.getElementById('grafikTenaga'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(tenaga),
            datasets: [{
                data: Object.values(tenaga),
                backgroundColor: [biru, cyan],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: (k) => ` ${k.label}: ${rapiAngka(k.parsed)} orang`,
                    },
                },
            },
        },
    });

    // ------------------------------------------------------ Gender CEO
    const ceo = @json($genderCeo);

    new Chart(document.getElementById('grafikCeo'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(ceo),
            datasets: [{
                data: Object.values(ceo),
                backgroundColor: [navy, teal],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: { label: (k) => ` ${k.label}: ${k.parsed} startup` },
                },
            },
        },
    });

    // ------------------------------------------------------ Asal invensi
    const invensi = @json($asalInvensi);

    new Chart(document.getElementById('grafikInvensi'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(invensi),
            datasets: [{
                data: Object.values(invensi),
                backgroundColor: [amber, biru, teal],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: { label: (k) => ` ${k.label}: ${k.parsed} startup` },
                },
            },
        },
    });

    // -------------------------------------------------- Perbandingan batch
    const batch = @json($batch);

    new Chart(document.getElementById('grafikBatch'), {
        type: 'bar',
        data: {
            labels: batch.map(b => b.batch),
            datasets: [
                {
                    label: 'Jumlah startup',
                    data: batch.map(b => b.jumlah),
                    backgroundColor: biru,
                    borderRadius: 4,
                    yAxisID: 'y',
                },
                {
                    label: 'Tenaga kerja',
                    data: batch.map(b => b.tenaga),
                    backgroundColor: cyan,
                    borderRadius: 4,
                    yAxisID: 'y1',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                x:  { grid: { display: false } },
                y:  {
                    beginAtZero: true,
                    position: 'left',
                    title: { display: true, text: 'Startup' },
                    ticks: { precision: 0 },
                    grid: { color: garis },
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: { display: true, text: 'Tenaga kerja' },
                    ticks: { precision: 0 },
                    grid: { display: false },
                },
            },
        },
    });

    // ------------------------------------------------------ Jangkauan pasar
    const pasar = @json($jangkauanPasar);

    new Chart(document.getElementById('grafikPasar'), {
        type: 'bar',
        data: {
            labels: Object.keys(pasar),
            datasets: [{
                label: 'Startup',
                data: Object.values(pasar),
                backgroundColor: teal,
                borderRadius: 4,
                barThickness: 32,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: garis } },
            },
        },
    });
})();
</script>
@endpush
