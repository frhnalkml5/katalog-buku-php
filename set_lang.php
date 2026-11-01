<?php
require_once __DIR__ . '/includes/init.php';
// Percabangan switch untuk memilih bahasa
$lang = $_GET['lang'] ?? 'id';
switch ($lang) {
    case 'en': $_SESSION['lang'] = 'en'; break;
    case 'id':
    default:   $_SESSION['lang'] = 'id'; break;
}
$back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
// Cegah open redirect: hanya izinkan halaman di host yang sama
$host = parse_url($back, PHP_URL_HOST);
if ($host && $host !== $_SERVER['HTTP_HOST']) { $back = 'index.php'; }
header('Location: ' . $back);
exit;
