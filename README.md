# Website Perpustakaan MTsN Nagekeo

Sistem perpustakaan berbasis PHP + MySQL (Laragon) dengan template SB Admin 2.

## Setup

1. Pastikan Laragon (Apache + MySQL) sudah running.
2. Import database:

```bash
C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root < database\eperpus_a.sql
```

Atau lewat phpMyAdmin: buat DB `eperpus_a`, lalu import file `database/eperpus_a.sql`.

3. Buka: `http://Website_perpus-main.test` atau `http://localhost/Website_perpus-main`

## Akun demo

| Username | Password  | Level    |
|----------|-----------|----------|
| admin    | admin123  | admin    |
| petugas  | admin123  | petugas  |
| siswa1   | admin123  | peminjam |
| siswa2   | admin123  | peminjam |

## Fitur

- Login / logout / register anggota
- Dashboard statistik real-time
- CRUD Kategori, Buku, Anggota
- Peminjaman & pengembalian (stok otomatis)
- Ulasan buku + rating
