# Katalog Buku (PHP Murni + MySQL)

Sistem informasi manajemen data buku: CRUD, login berbasis `$_SESSION`, dan dual bahasa (ID/EN).

## Cara Menjalankan
1. Pasang XAMPP/Laragon (PHP >= 7.4, MySQL/MariaDB).
2. Salin folder ini ke `htdocs/katalog_buku`.
3. Impor `database.sql` lewat phpMyAdmin (atau `mysql -u root < database.sql`).
4. Sesuaikan kredensial di `includes/db.php` bila perlu.
5. Buka `http://localhost/katalog_buku/login.php`.
6. Akun demo: **admin / admin123**.

## Struktur
- `login.php`, `logout.php` – autentikasi (session, `password_verify`)
- `index.php` – Read (tabel + pencarian)
- `create.php`, `edit.php`, `delete.php` – Create, Update, Delete
- `set_lang.php` – ganti bahasa (disimpan di `$_SESSION['lang']`)
- `includes/` – koneksi DB, init (session, bahasa, validasi, CSRF), template
- `lang/` – kamus teks `id.php` dan `en.php`

## Fitur Keamanan
Prepared statement (anti SQL injection), `htmlspecialchars` untuk output, `strip_tags`/`filter_var` untuk input,
`password_hash` bcrypt, `session_regenerate_id`, token CSRF, hapus via POST.
