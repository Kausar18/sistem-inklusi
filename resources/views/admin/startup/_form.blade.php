{{-- Dipakai bersama oleh create.blade.php dan edit.blade.php --}}

{{-- ---------------------------------------------- Pengelompokan program --}}
<div class="panel p-4 mb-3">
    <h2 class="h6 mb-3">Pengelompokan program</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="label-filter d-block" for="batch">Batch</label>
            <input type="text" class="form-control" id="batch" name="batch"
                   value="{{ old('batch', $startup->batch) }}" placeholder="mis. Batch 2">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="tahun_program">Tahun program</label>
            <input type="number" class="form-control" id="tahun_program" name="tahun_program"
                   value="{{ old('tahun_program', $startup->tahun_program) }}" placeholder="2026">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="skema_program">Skema program</label>
            <input type="text" class="form-control" id="skema_program" name="skema_program"
                   value="{{ old('skema_program', $startup->skema_program) }}"
                   placeholder="Pra-Akselerasi / Inkubasi / Akselerasi">
        </div>
    </div>
</div>

{{-- ---------------------------------------------- Identitas startup & CEO --}}
<div class="panel p-4 mb-3">
    <h2 class="h6 mb-3">Identitas startup &amp; CEO</h2>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="label-filter d-block" for="nama_startup">Nama startup <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nama_startup" name="nama_startup"
                   value="{{ old('nama_startup', $startup->nama_startup) }}" required>
        </div>
        <div class="col-md-6">
            <label class="label-filter d-block" for="nama_ceo">Nama CEO <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nama_ceo" name="nama_ceo"
                   value="{{ old('nama_ceo', $startup->nama_ceo) }}" required>
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="tanggal_lahir_ceo">Tanggal lahir CEO</label>
            <input type="date" class="form-control" id="tanggal_lahir_ceo" name="tanggal_lahir_ceo"
                   value="{{ old('tanggal_lahir_ceo', optional($startup->tanggal_lahir_ceo)->toDateString()) }}">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="jenis_kelamin_ceo">Jenis kelamin CEO <span class="text-danger">*</span></label>
            <select class="form-select" id="jenis_kelamin_ceo" name="jenis_kelamin_ceo" required>
                <option value="">— Pilih —</option>
                <option value="L" @selected(old('jenis_kelamin_ceo', $startup->jenis_kelamin_ceo) === 'L')>Laki-laki</option>
                <option value="P" @selected(old('jenis_kelamin_ceo', $startup->jenis_kelamin_ceo) === 'P')>Perempuan</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="pendidikan_terakhir">Pendidikan terakhir</label>
            <input type="text" class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir"
                   value="{{ old('pendidikan_terakhir', $startup->pendidikan_terakhir) }}">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="asal_sekolah">Asal sekolah/kampus</label>
            <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah"
                   value="{{ old('asal_sekolah', $startup->asal_sekolah) }}">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="jurusan">Jurusan</label>
            <input type="text" class="form-control" id="jurusan" name="jurusan"
                   value="{{ old('jurusan', $startup->jurusan) }}">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="semester">Semester (jika belum lulus)</label>
            <input type="text" class="form-control" id="semester" name="semester"
                   value="{{ old('semester', $startup->semester) }}">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="tahun_lulus">Tahun lulus (jika sudah lulus)</label>
            <input type="text" class="form-control" id="tahun_lulus" name="tahun_lulus"
                   value="{{ old('tahun_lulus', $startup->tahun_lulus) }}">
        </div>
    </div>
</div>

{{-- ---------------------------------------------------- Kontak & alamat --}}
<div class="panel p-4 mb-3">
    <h2 class="h6 mb-3">Kontak &amp; alamat</h2>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="label-filter d-block" for="alamat_rumah">Alamat rumah CEO</label>
            <textarea class="form-control" id="alamat_rumah" name="alamat_rumah" rows="2">{{ old('alamat_rumah', $startup->alamat_rumah) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="label-filter d-block" for="alamat_usaha">Alamat usaha</label>
            <textarea class="form-control" id="alamat_usaha" name="alamat_usaha" rows="2">{{ old('alamat_usaha', $startup->alamat_usaha) }}</textarea>
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="kecamatan">Kecamatan</label>
            <input type="text" class="form-control" id="kecamatan" name="kecamatan"
                   value="{{ old('kecamatan', $startup->kecamatan) }}">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="kota">Kota/kabupaten</label>
            <input type="text" class="form-control" id="kota" name="kota"
                   value="{{ old('kota', $startup->kota) }}">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="provinsi">Provinsi</label>
            <input type="text" class="form-control" id="provinsi" name="provinsi"
                   value="{{ old('provinsi', $startup->provinsi) }}">
        </div>
        <div class="col-md-3">
            <label class="label-filter d-block" for="no_wa">No. WhatsApp</label>
            <input type="text" class="form-control" id="no_wa" name="no_wa"
                   value="{{ old('no_wa', $startup->no_wa) }}">
        </div>
        <div class="col-md-3">
            <label class="label-filter d-block" for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="{{ old('email', $startup->email) }}">
        </div>
        <div class="col-md-3">
            <label class="label-filter d-block" for="website">Website</label>
            <input type="text" class="form-control" id="website" name="website"
                   value="{{ old('website', $startup->website) }}">
        </div>
        <div class="col-md-3">
            <label class="label-filter d-block" for="instagram">Instagram</label>
            <input type="text" class="form-control" id="instagram" name="instagram"
                   value="{{ old('instagram', $startup->instagram) }}">
        </div>
    </div>
</div>

{{-- --------------------------------------------------- Usaha & produk --}}
<div class="panel p-4 mb-3">
    <h2 class="h6 mb-3">Usaha &amp; produk</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="label-filter d-block" for="bidang_usaha_id">Bidang usaha</label>
            <select class="form-select" id="bidang_usaha_id" name="bidang_usaha_id">
                <option value="">— Belum ditentukan —</option>
                @foreach ($daftarBidang as $bidang)
                    <option value="{{ $bidang->id }}"
                        @selected((string) old('bidang_usaha_id', $startup->bidang_usaha_id) === (string) $bidang->id)>
                        {{ $bidang->nama_bidang }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="mulai_usaha">Mulai usaha</label>
            <input type="date" class="form-control" id="mulai_usaha" name="mulai_usaha"
                   value="{{ old('mulai_usaha', optional($startup->mulai_usaha)->toDateString()) }}">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="nama_produk">Nama produk</label>
            <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                   value="{{ old('nama_produk', $startup->nama_produk) }}">
        </div>
        <div class="col-md-6">
            <label class="label-filter d-block" for="deskripsi_produk">Deskripsi produk</label>
            <textarea class="form-control" id="deskripsi_produk" name="deskripsi_produk" rows="3">{{ old('deskripsi_produk', $startup->deskripsi_produk) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="label-filter d-block" for="judul_proposal">Judul proposal</label>
            <textarea class="form-control" id="judul_proposal" name="judul_proposal" rows="3">{{ old('judul_proposal', $startup->judul_proposal) }}</textarea>
        </div>
    </div>
</div>

{{-- --------------------------------------------------------- Asal invensi --}}
<div class="panel p-4 mb-3">
    <h2 class="h6 mb-3">Asal invensi</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="label-filter d-block" for="asal_invensi">Asal invensi <span class="text-danger">*</span></label>
            <select class="form-select" id="asal_invensi" name="asal_invensi" required>
                @foreach (['Mandiri', 'IPB', 'Kombinasi'] as $opsi)
                    <option value="{{ $opsi }}" @selected(old('asal_invensi', $startup->asal_invensi ?: 'Mandiri') === $opsi)>{{ $opsi }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="nama_dosen_pembimbing">Nama dosen pembimbing</label>
            <input type="text" class="form-control" id="nama_dosen_pembimbing" name="nama_dosen_pembimbing"
                   value="{{ old('nama_dosen_pembimbing', $startup->nama_dosen_pembimbing) }}">
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="keterangan_invensi">Keterangan (jawaban asli)</label>
            <textarea class="form-control" id="keterangan_invensi" name="keterangan_invensi" rows="1">{{ old('keterangan_invensi', $startup->keterangan_invensi) }}</textarea>
        </div>
    </div>
</div>

{{-- --------------------------------------------- Tenaga kerja (baseline) --}}
<div class="panel p-4 mb-3">
    <h2 class="h6 mb-3">Tenaga kerja (sebelum pendampingan)</h2>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="label-filter d-block" for="tenaga_kerja_l">Laki-laki</label>
            <input type="number" class="form-control" id="tenaga_kerja_l" name="tenaga_kerja_l" min="0"
                   value="{{ old('tenaga_kerja_l', $startup->tenaga_kerja_l ?? 0) }}">
        </div>
        <div class="col-md-6">
            <label class="label-filter d-block" for="tenaga_kerja_p">Perempuan</label>
            <input type="number" class="form-control" id="tenaga_kerja_p" name="tenaga_kerja_p" min="0"
                   value="{{ old('tenaga_kerja_p', $startup->tenaga_kerja_p ?? 0) }}">
        </div>
    </div>
</div>

{{-- ------------------------------------------------------------- Modal --}}
<div class="panel p-4 mb-3">
    <h2 class="h6 mb-3">Modal</h2>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="label-filter d-block" for="modal_awal">Modal awal (Rp)</label>
            <input type="text" class="form-control" id="modal_awal" name="modal_awal" inputmode="numeric"
                   value="{{ old('modal_awal', $startup->modal_awal_teks ?? $startup->modal_awal) }}"
                   placeholder="150000000">
        </div>
        <div class="col-md-6">
            <label class="label-filter d-block" for="sumber_modal">Sumber modal</label>
            <input type="text" class="form-control" id="sumber_modal" name="sumber_modal"
                   value="{{ old('sumber_modal', $startup->sumber_modal) }}">
        </div>
    </div>
</div>

{{-- --------------------------------------------------- Produksi & pasar --}}
<div class="panel p-4 mb-3">
    <h2 class="h6 mb-3">Produksi &amp; pasar</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="label-filter d-block" for="kapasitas_produksi">Kapasitas produksi</label>
            <textarea class="form-control" id="kapasitas_produksi" name="kapasitas_produksi" rows="2">{{ old('kapasitas_produksi', $startup->kapasitas_produksi) }}</textarea>
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="harga_produk">Harga produk</label>
            <textarea class="form-control" id="harga_produk" name="harga_produk" rows="2">{{ old('harga_produk', $startup->harga_produk) }}</textarea>
        </div>
        <div class="col-md-4">
            <label class="label-filter d-block" for="jangkauan_pasar">Jangkauan pasar</label>
            <input type="text" class="form-control" id="jangkauan_pasar" name="jangkauan_pasar"
                   value="{{ old('jangkauan_pasar', $startup->jangkauan_pasar) }}" placeholder="mis. Nasional">
        </div>
    </div>
</div>

{{-- ------------------------------------------ Omset baseline & pembanding --}}
<div class="panel p-4 mb-3">
    <h2 class="h6 mb-3">Omset baseline &amp; pembanding</h2>
    <p class="small mb-3" style="color: var(--redup);">
        Dua titik omset dari data pendaftaran — dipakai untuk perbandingan awal,
        bukan angka pemantauan berkala (itu dicatat lewat "Catat pemantauan" di halaman publik).
    </p>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="label-filter d-block" for="omset_awal">Omset awal (Rp)</label>
            <input type="text" class="form-control" id="omset_awal" name="omset_awal" inputmode="numeric"
                   value="{{ old('omset_awal', $startup->omset_awal_teks ?? $startup->omset_awal) }}">
        </div>
        <div class="col-md-6">
            <label class="label-filter d-block" for="periode_omset_awal">Periode omset awal</label>
            <input type="text" class="form-control" id="periode_omset_awal" name="periode_omset_awal"
                   value="{{ old('periode_omset_awal', $startup->periode_omset_awal) }}" placeholder="mis. Tahun 2025">
        </div>
        <div class="col-md-3">
            <label class="label-filter d-block" for="bulan_periode_awal">Panjang periode (bulan)</label>
            <input type="number" class="form-control" id="bulan_periode_awal" name="bulan_periode_awal" min="0"
                   value="{{ old('bulan_periode_awal', $startup->bulan_periode_awal) }}">
        </div>
        <div class="col-md-6">
            <label class="label-filter d-block" for="omset_pembanding">Omset pembanding (Rp)</label>
            <input type="text" class="form-control" id="omset_pembanding" name="omset_pembanding" inputmode="numeric"
                   value="{{ old('omset_pembanding', $startup->omset_pembanding_teks ?? $startup->omset_pembanding) }}">
        </div>
        <div class="col-md-6">
            <label class="label-filter d-block" for="periode_omset_pembanding">Periode omset pembanding</label>
            <input type="text" class="form-control" id="periode_omset_pembanding" name="periode_omset_pembanding"
                   value="{{ old('periode_omset_pembanding', $startup->periode_omset_pembanding) }}"
                   placeholder="mis. Jan-Mar 2026">
        </div>
        <div class="col-md-3">
            <label class="label-filter d-block" for="bulan_periode_pembanding">Panjang periode (bulan)</label>
            <input type="number" class="form-control" id="bulan_periode_pembanding" name="bulan_periode_pembanding" min="0"
                   value="{{ old('bulan_periode_pembanding', $startup->bulan_periode_pembanding) }}">
        </div>
    </div>
</div>

{{-- --------------------------------------------------------------- Narasi --}}
<div class="panel p-4 mb-3">
    <h2 class="h6 mb-3">Narasi</h2>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="label-filter d-block" for="permasalahan_utama">Permasalahan utama</label>
            <textarea class="form-control" id="permasalahan_utama" name="permasalahan_utama" rows="3">{{ old('permasalahan_utama', $startup->permasalahan_utama) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="label-filter d-block" for="rencana_pengembangan">Rencana pengembangan</label>
            <textarea class="form-control" id="rencana_pengembangan" name="rencana_pengembangan" rows="3">{{ old('rencana_pengembangan', $startup->rencana_pengembangan) }}</textarea>
        </div>
    </div>
</div>

{{-- --------------------------------------------------------------- Status --}}
<div class="panel p-4 mb-4">
    <h2 class="h6 mb-3">Status</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="label-filter d-block" for="status">Status <span class="text-danger">*</span></label>
            <select class="form-select" id="status" name="status" required>
                @foreach (['pendaftar' => 'Pendaftar', 'aktif' => 'Aktif', 'lulus' => 'Lulus', 'nonaktif' => 'Nonaktif'] as $nilai => $label)
                    <option value="{{ $nilai }}" @selected(old('status', $startup->status ?: 'aktif') === $nilai)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
