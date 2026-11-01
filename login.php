<?php
require_once __DIR__ . '/includes/init.php';

if (!empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = t('err_required');
    } else {
        $stmt = $conn->prepare('SELECT id, password, nama_lengkap FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama_lengkap'];
            header('Location: index.php');
            exit;
        } else {
            $error = t('login_failed');
        }
    }
}
require __DIR__ . '/includes/header.php';
?>
<div class="card narrow">
  <h2><?= e(t('login')) ?></h2>
  <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
  <form method="POST" action="login.php">
    <label><?= e(t('username')) ?></label>
    <input type="text" name="username" value="<?= e($_POST['username'] ?? '') ?>" autofocus>
    <label><?= e(t('password')) ?></label>
    <input type="password" name="password">
    <button class="btn" type="submit"><?= e(t('login')) ?></button>
  </form>
  <p class="hint">Demo: admin / admin123</p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
