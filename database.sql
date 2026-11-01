-- Katalog Buku - skrip ekspor database
CREATE DATABASE IF NOT EXISTS katalog_buku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE katalog_buku;

DROP TABLE IF EXISTS buku;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE buku (
  id INT AUTO_INCREMENT PRIMARY KEY,
  isbn VARCHAR(20) NOT NULL UNIQUE,
  judul VARCHAR(150) NOT NULL,
  penulis VARCHAR(100) NOT NULL,
  penerbit VARCHAR(100) NOT NULL,
  tahun_terbit YEAR NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Akun default: admin / admin123 (bcrypt)
INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2b$10$z6Iy9FpKgb6SzTqQwVwBSuCD1yfBLPZNBXXweH57/KBesYDOnMvyC', 'Administrator');

INSERT INTO buku (isbn, judul, penulis, penerbit, tahun_terbit, kategori, stok) VALUES
('9786020332956','Bumi Manusia','Pramoedya Ananta Toer','Lentera Dipantara',2005,'Novel',5),
('9786020318950','Laskar Pelangi','Andrea Hirata','Bentang Pustaka',2005,'Novel',8),
('9780132350884','Clean Code','Robert C. Martin','Prentice Hall',2008,'Teknologi',3);
