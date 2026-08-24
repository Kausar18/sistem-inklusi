PANDUAN PENGISIAN GAMBAR BERANDA
================================

Letakkan berkas gambar di folder ini (public/images/) dengan nama
persis seperti di bawah. Berkas yang belum tersedia otomatis
ditampilkan sebagai kotak penampung, jadi boleh diisi bertahap.

  hero.jpg          1600 x 700 px   Foto utama pada bagian paling atas.
                                    Sebaiknya foto lanskap dengan ruang
                                    kosong di sisi kiri, karena tulisan
                                    diletakkan di sana.

  tentang.jpg        900 x 700 px   Foto pendukung bagian "Tentang program".

  kegiatan-1.jpg     800 x 600 px   Galeri kegiatan.
  kegiatan-2.jpg     800 x 600 px
  kegiatan-3.jpg     800 x 600 px
  kegiatan-4.jpg     800 x 600 px
  kegiatan-5.jpg     800 x 600 px
  kegiatan-6.jpg     800 x 600 px

Catatan:
- Format .jpg. Bila memakai .png, ubah pula nama berkas pada
  resources/views/beranda.blade.php.
- Ukuran berkas sebaiknya di bawah 500 KB per gambar agar halaman
  tetap ringan.
- Setelah menambah gambar, cukup muat ulang halaman. Tidak perlu
  menjalankan perintah apa pun.

NASKAH TEKS
-----------
Bagian teks yang masih berupa contoh ditandai dengan komentar
"ISI DI SINI" pada berkas resources/views/beranda.blade.php:

  1. Judul utama dan paragraf pengantar (bagian hero)
  2. Judul dan 2-3 paragraf "Tentang program"
  3. Penjelasan empat tahapan pendampingan
