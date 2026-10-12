-- Database Perpustakaan MTsN Nagekeo
-- Import: mysql -u root < database/eperpus_a.sql

CREATE DATABASE IF NOT EXISTS eperpus_a CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE eperpus_a;

DROP TABLE IF EXISTS ulasan;
DROP TABLE IF EXISTS peminjaman;
DROP TABLE IF EXISTS buku;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS user;

CREATE TABLE user (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) DEFAULT NULL,
  alamat TEXT DEFAULT NULL,
  level ENUM('admin','petugas','peminjam') NOT NULL DEFAULT 'peminjam',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE kategori (
  id_kategori INT AUTO_INCREMENT PRIMARY KEY,
  kategori VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE buku (
  id_buku INT AUTO_INCREMENT PRIMARY KEY,
  id_kategori INT NOT NULL,
  judul VARCHAR(200) NOT NULL,
  penulis VARCHAR(100) NOT NULL,
  penerbit VARCHAR(100) DEFAULT NULL,
  tahun_terbit YEAR DEFAULT NULL,
  stok INT NOT NULL DEFAULT 0,
  deskripsi TEXT DEFAULT NULL,
  FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE peminjaman (
  id_peminjaman INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_buku INT NOT NULL,
  tanggal_peminjaman DATE NOT NULL,
  tanggal_pengembalian DATE DEFAULT NULL,
  status ENUM('dipinjam','dikembalikan') NOT NULL DEFAULT 'dipinjam',
  FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE ulasan (
  id_ulasan INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_buku INT NOT NULL,
  ulasan TEXT NOT NULL,
  rating TINYINT NOT NULL DEFAULT 5,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- password semua akun demo: admin123
INSERT INTO user (username, password, nama, email, alamat, level) VALUES
('admin', '$2y$10$r3hW3IIZRpJcUafrUdYrneSeLWqopgzcFBaIsUDEvDvlCe./x82aO', 'Administrator', 'admin@perpus.local', 'MTsN Nagekeo', 'admin'),
('petugas', '$2y$10$r3hW3IIZRpJcUafrUdYrneSeLWqopgzcFBaIsUDEvDvlCe./x82aO', 'Petugas Perpus', 'petugas@perpus.local', 'MTsN Nagekeo', 'petugas'),
('siswa1', '$2y$10$r3hW3IIZRpJcUafrUdYrneSeLWqopgzcFBaIsUDEvDvlCe./x82aO', 'Ahmad Rizki', 'ahmad@siswa.local', 'Nagekeo', 'peminjam'),
('siswa2', '$2y$10$r3hW3IIZRpJcUafrUdYrneSeLWqopgzcFBaIsUDEvDvlCe./x82aO', 'Siti Nurhaliza', 'siti@siswa.local', 'Nagekeo', 'peminjam');

INSERT INTO kategori (kategori) VALUES
('Fiksi'),
('Non Fiksi'),
('Pelajaran'),
('Agama'),
('Sejarah'),
('Teknologi');

INSERT INTO buku (id_kategori, judul, penulis, penerbit, tahun_terbit, stok, deskripsi) VALUES
(1, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 5, 'Novel tentang perjuangan anak-anak Belitung.'),
(1, 'Negeri 5 Menara', 'Ahmad Fuadi', 'Gramedia', 2009, 4, 'Kisah santri dan mimpi besar.'),
(3, 'Matematika Kelas 8', 'Tim MGMP', 'Erlangga', 2020, 10, 'Buku pelajaran matematika.'),
(4, 'Fiqih Dasar', 'Ust. Hasan', 'Al-Hidayah', 2018, 6, 'Dasar-dasar fiqih untuk pelajar.'),
(5, 'Sejarah Indonesia', 'Tim Nasional', 'Kemdikbud', 2019, 8, 'Ringkasan sejarah Indonesia.'),
(6, 'Pengantar Pemrograman', 'Budi Santoso', 'Informatika', 2022, 7, 'Dasar coding untuk pemula.');

INSERT INTO peminjaman (id_user, id_buku, tanggal_peminjaman, tanggal_pengembalian, status) VALUES
(3, 1, CURDATE() - INTERVAL 3 DAY, NULL, 'dipinjam'),
(4, 2, CURDATE() - INTERVAL 10 DAY, CURDATE() - INTERVAL 2 DAY, 'dikembalikan'),
(3, 3, CURDATE() - INTERVAL 1 DAY, NULL, 'dipinjam');

UPDATE buku SET stok = stok - 1 WHERE id_buku IN (1, 3);

INSERT INTO ulasan (id_user, id_buku, ulasan, rating) VALUES
(4, 2, 'Ceritanya sangat menginspirasi!', 5),
(3, 1, 'Bagus dibaca, bagus untuk siswa.', 4);
