<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StartupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // ---------- Pengelompokan program ----------
            'batch'           => ['nullable', 'string', 'max:20'],
            'tahun_program'   => ['nullable', 'digits:4', 'integer'],
            'skema_program'   => ['nullable', 'string', 'max:50'],

            // ---------- Identitas startup & CEO ----------
            'nama_startup'        => ['required', 'string', 'max:200'],
            'nama_ceo'            => ['required', 'string', 'max:150'],
            'tanggal_lahir_ceo'   => ['nullable', 'date'],
            'jenis_kelamin_ceo'   => ['required', 'in:L,P'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
            'asal_sekolah'        => ['nullable', 'string', 'max:200'],
            'jurusan'             => ['nullable', 'string', 'max:200'],
            'semester'            => ['nullable', 'string', 'max:20'],
            'tahun_lulus'         => ['nullable', 'string', 'max:10'],

            // ---------- Kontak & alamat ----------
            'alamat_rumah' => ['nullable', 'string'],
            'alamat_usaha' => ['nullable', 'string'],
            'kecamatan'    => ['nullable', 'string', 'max:100'],
            'kota'         => ['nullable', 'string', 'max:100'],
            'provinsi'     => ['nullable', 'string', 'max:100'],
            'no_wa'        => ['nullable', 'string', 'max:40'],
            'email'        => ['nullable', 'email', 'max:150'],
            'website'      => ['nullable', 'string', 'max:255'],
            'instagram'    => ['nullable', 'string', 'max:150'],

            // ---------- Usaha & produk ----------
            'bidang_usaha_id'  => ['nullable', 'exists:bidang_usaha,id'],
            'mulai_usaha'      => ['nullable', 'date'],
            'nama_produk'      => ['nullable', 'string', 'max:255'],
            'deskripsi_produk' => ['nullable', 'string'],
            'judul_proposal'   => ['nullable', 'string'],

            // ---------- Asal invensi ----------
            'asal_invensi'          => ['required', 'in:IPB,Mandiri,Kombinasi'],
            'keterangan_invensi'    => ['nullable', 'string'],
            'nama_dosen_pembimbing' => ['nullable', 'string', 'max:255'],

            // ---------- Tenaga kerja (baseline) ----------
            'tenaga_kerja_l' => ['nullable', 'integer', 'min:0'],
            'tenaga_kerja_p' => ['nullable', 'integer', 'min:0'],

            // ---------- Modal ----------
            'modal_awal'   => ['nullable', 'string', 'max:30'],
            'sumber_modal' => ['nullable', 'string', 'max:100'],

            // ---------- Produksi & pasar ----------
            'kapasitas_produksi' => ['nullable', 'string'],
            'harga_produk'       => ['nullable', 'string'],
            'jangkauan_pasar'    => ['nullable', 'string', 'max:100'],

            // ---------- Omset baseline & pembanding ----------
            'omset_awal'               => ['nullable', 'string', 'max:30'],
            'periode_omset_awal'       => ['nullable', 'string', 'max:50'],
            'omset_pembanding'         => ['nullable', 'string', 'max:30'],
            'periode_omset_pembanding' => ['nullable', 'string', 'max:50'],
            'bulan_periode_awal'       => ['nullable', 'integer', 'min:0', 'max:255'],
            'bulan_periode_pembanding' => ['nullable', 'integer', 'min:0', 'max:255'],

            // ---------- Narasi ----------
            'permasalahan_utama'   => ['nullable', 'string'],
            'rencana_pengembangan' => ['nullable', 'string'],

            'status' => ['required', 'in:pendaftar,aktif,lulus,nonaktif'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_startup'         => 'nama startup',
            'nama_ceo'             => 'nama CEO',
            'jenis_kelamin_ceo'    => 'jenis kelamin CEO',
            'tanggal_lahir_ceo'    => 'tanggal lahir CEO',
            'bidang_usaha_id'      => 'bidang usaha',
            'asal_invensi'         => 'asal invensi',
            'tenaga_kerja_l'       => 'tenaga kerja laki-laki',
            'tenaga_kerja_p'       => 'tenaga kerja perempuan',
            'modal_awal'           => 'modal awal',
            'omset_awal'           => 'omset awal',
            'omset_pembanding'     => 'omset pembanding',
            'periode_omset_awal'   => 'periode omset awal',
            'jangkauan_pasar'      => 'jangkauan pasar',
        ];
    }
}
