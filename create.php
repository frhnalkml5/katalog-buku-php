<?php
require_once __DIR__ . '/includes/init.php';
require_login();

$data = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $errors['global'] = t('err_csrf');
    } else {
        list($data, $errors) = validate_buku($_POST);

        if (empty($errors)) {
            // Cek ISBN unik
            $cek = $conn->prepare('SELECT id FROM buku WHERE isbn = ?');
            $cek->bind_param('s', $data['isbn']);
            $cek->execute();
            if ($cek->get_result()->num_rows > 0) {
                $errors['isbn'] = t('err_dup');
            } else {
                $stmt = $conn->prepare('INSERT INTO buku (isbn, judul, penulis, penerbit, tahun_terbit, kategori, stok) VALUES (?,?,?,?,?,?,?)');
                $thn = (int)$data['tahun_terbit'];
                $stok = (int)$data['stok'];
                $stmt->bind_param('ssssisi', $data['isbn'], $data['judul'], $data['penulis'], $data['penerbit'], $thn, $data['kategori'], $stok);
                $stmt->execute();
                $_SESSION['flash'] = 'saved';
                header('Location: index.php');
                exit;
            }
        }
    }
}

$title = t('add_book');
$action = 'create.php';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/form_buku.php';
require __DIR__ . '/includes/footer.php';
