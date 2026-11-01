<?php
require_once __DIR__ . '/includes/init.php';
require_login();

$id = filter_var($_GET['id'] ?? ($_POST['id'] ?? null), FILTER_VALIDATE_INT);
if (!$id) { header('Location: index.php'); exit; }

$errors = [];
$stmt = $conn->prepare('SELECT * FROM buku WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    $_SESSION['flash'] = 'not_found';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $errors['global'] = t('err_csrf');
    } else {
        list($data, $errors) = validate_buku($_POST);

        if (empty($errors)) {
            // ISBN tidak boleh sama dengan buku lain
            $cek = $conn->prepare('SELECT id FROM buku WHERE isbn = ? AND id <> ?');
            $cek->bind_param('si', $data['isbn'], $id);
            $cek->execute();
            if ($cek->get_result()->num_rows > 0) {
                $errors['isbn'] = t('err_dup');
            } else {
                $up = $conn->prepare('UPDATE buku SET isbn=?, judul=?, penulis=?, penerbit=?, tahun_terbit=?, kategori=?, stok=? WHERE id=?');
                $thn = (int)$data['tahun_terbit'];
                $stok = (int)$data['stok'];
                $up->bind_param('ssssisii', $data['isbn'], $data['judul'], $data['penulis'], $data['penerbit'], $thn, $data['kategori'], $stok, $id);
                $up->execute();
                $_SESSION['flash'] = 'updated';
                header('Location: index.php');
                exit;
            }
        }
    }
}

$title = t('edit_book');
$action = 'edit.php?id=' . $id;
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/form_buku.php';
require __DIR__ . '/includes/footer.php';
