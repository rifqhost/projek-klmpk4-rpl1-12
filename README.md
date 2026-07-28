# CBT Sekolah

Aplikasi ujian sekolah berbasis PHP 8 Native, MySQL, Bootstrap 5, dan AJAX.

## Menjalankan

1. Salin folder proyek ke `xampp/htdocs/`.
2. Aktifkan Apache dan MySQL di XAMPP.
3. Buka `http://localhost/nama-folder/`.
4. Database `cbt_sekolah`, tabel, relasi, dan data awal dibuat otomatis saat halaman pertama dibuka.

Login awal: `admin@school.test` / `admin123`.

Jika MySQL memakai password lain, ubah `DB_USER` dan `DB_PASS` di `config/config.php`.

## Catatan operasional

Admin menambahkan pengguna guru/siswa, data kelas/mapel, bank soal, ujian, lalu jadwal. Siswa dapat masuk memakai token hanya pada waktu jadwal aktif. Nilai pilihan ganda dihitung otomatis; ujian dengan essay berstatus `submitted` sampai guru melengkapi koreksi.
