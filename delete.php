<?php
require_once __DIR__ . '/includes/init.php';
require_login();

// Hapus hanya lewat POST + token CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $conn->prepare('DELETE FROM buku WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $_SESSION['flash'] = 'deleted';
    }
}
header('Location: index.php');
exit;
