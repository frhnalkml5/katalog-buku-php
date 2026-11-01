<?php
require_once __DIR__ . '/includes/init.php';
require_login();

$q = clean($_GET['q'] ?? '');
if ($q !== '') {
    $like = '%' . $q . '%';
    $stmt = $conn->prepare('SELECT * FROM buku WHERE judul LIKE ? OR penulis LIKE ? ORDER BY id DESC');
    $stmt->bind_param('ss', $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query('SELECT * FROM buku ORDER BY id DESC');
}

$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

require __DIR__ . '/includes/header.php';
?>
<div class="card">
  <div class="row">
    <h2><?= e(t('book_list')) ?></h2>
    <a class="btn" href="create.php">+ <?= e(t('add_book')) ?></a>
  </div>

  <?php if ($flash): ?><div class="alert ok"><?= e(t($flash)) ?></div><?php endif; ?>

  <form method="GET" class="search">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="<?= e(t('search')) ?>">
    <button class="btn small" type="submit">&#128269;</button>
  </form>

  <div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?= e(t('no')) ?></th><th><?= e(t('isbn')) ?></th><th><?= e(t('title')) ?></th>
        <th><?= e(t('author')) ?></th><th><?= e(t('publisher')) ?></th><th><?= e(t('year')) ?></th>
        <th><?= e(t('category')) ?></th><th><?= e(t('stock')) ?></th><th><?= e(t('action')) ?></th>
      </tr>
    </thead>
    <tbody>
    <?php if ($result->num_rows === 0): ?>
      <tr><td colspan="9" class="center"><?= e(t('empty')) ?></td></tr>
    <?php else: $no = 1; while ($r = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= e($r['isbn']) ?></td>
        <td><?= e($r['judul']) ?></td>
        <td><?= e($r['penulis']) ?></td>
        <td><?= e($r['penerbit']) ?></td>
        <td><?= e($r['tahun_terbit']) ?></td>
        <td><?= e($r['kategori']) ?></td>
        <td><?= e($r['stok']) ?></td>
        <td class="actions">
          <a class="btn small" href="edit.php?id=<?= (int)$r['id'] ?>"><?= e(t('edit')) ?></a>
          <form method="POST" action="delete.php" onsubmit="return confirm('<?= e(t('confirm_delete')) ?>');">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <button class="btn small danger" type="submit"><?= e(t('delete')) ?></button>
          </form>
        </td>
      </tr>
    <?php endwhile; endif; ?>
    </tbody>
  </table>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
