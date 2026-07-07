# Portal Toko — Cara Menjalankan di Lokal

Aplikasi ini dibuat dengan **CodeIgniter 4** (PHP). Berikut cara menjalankannya
di komputer sendiri, beserta catatan perubahan yang sudah diterapkan.

---

## 1. Login default

| Username | Password |
|----------|----------|
| `admin`  | `admin123` |

(Bisa ditambah lewat halaman **Register**.)

---

## 2. Menjalankan dengan Docker (disarankan)

Pastikan **Docker Desktop** sudah terpasang, lalu dari dalam folder proyek:

```bash
docker compose up -d --build
```

Tunggu hingga semua container jalan, lalu buka:

```
http://localhost:8080
```

Yang berjalan:
- **web**  → Nginx di port `8080`
- **app**  → PHP 8.3-FPM
- **db**   → MariaDB 10.11 (port host `3307`)

Database **otomatis dibuat & diisi** dari file di folder `database/`
(`01-schema.sql` lalu `02-seed.sql`) saat container `db` pertama kali dibuat.

Menghentikan / menghapus:

```bash
docker compose down          # stop
docker compose down -v       # stop + hapus data database (reset total)
```

> Catatan: file `.env` sudah disetel `database.default.hostname = db`
> (nama service database di Docker). Tidak perlu diubah untuk mode Docker.

---

## 3. Menjalankan tanpa Docker (XAMPP / Laragon)

1. Jalankan MySQL/MariaDB (mis. lewat XAMPP).
2. Import dua file ini (urut) lewat phpMyAdmin atau command line:
   ```bash
   mysql -u root -p < database/01-schema.sql
   mysql -u root -p < database/02-seed.sql
   ```
   Ini membuat database `jwiid_dev` (master + login) dan `D` (data toko).
3. Edit `.env`, ganti baris hostname menjadi:
   ```
   database.default.hostname = localhost
   ```
   sesuaikan juga `username` / `password` MySQL Anda bila berbeda.
4. Dari folder proyek, jalankan server bawaan CI4:
   ```bash
   php spark serve
   ```
5. Buka `http://localhost:8080`.

> Folder `vendor/` sudah disertakan, jadi tidak wajib `composer install`.

---

## 4. Perubahan yang sudah diterapkan

### a. Penamaan tabel `D.midi`, `D.lawson`, `D.alfamart`
Permintaan agar tabel `midi`, `lawson`, `alfamart` menjadi
`D.midi`, `D.lawson`, `D.alfamart` diterapkan dengan **membuat database
terpisah bernama `D`** yang menampung ketiga tabel toko tersebut. Di MySQL,
notasi `D.midi` berarti *database `D`, tabel `midi`* — rapi dan valid.

- Master data (`md_*`) dan `login` tetap di database `jwiid_dev`.
- Model sudah diubah: `protected $table = 'D.midi'` (dst).
- Query peta (`getMapData`) juga sudah memakai `D.midi` (dst).

> Jika yang Anda maksud sebenarnya **nama tabel literal** `D.midi`
> (bukan database `D`), beri tahu saya — tinggal sedikit penyesuaian.

### b. Pencegahan data ganda (duplikat)
Pada form **Alfamidi, Lawson, dan Alfamart**, saat menyimpan/memperbarui:
jika **Kode Toko** sudah ada di database, data **tidak disimpan** dan muncul
**notifikasi merah** dengan gaya yang sama seperti notifikasi hijau
"Berhasil" saat login — hanya warnanya merah dan bertuliskan **"Gagal"**.

Contoh pesan:
> *Data Toko Midi dengan kode toko "MIDI-001" sudah ada. Data tidak boleh ganda.*

Kunci pengecekan duplikat = kolom `kode_toko`.
