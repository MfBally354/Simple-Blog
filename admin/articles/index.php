<?php
// File: admin/articles/index.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$db = getDB();
$statusFilter = $_GET['status'] ?? 'all';

if ($statusFilter === 'all') {
    $articles = $db->query("SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC")->fetchAll();
} else {
    $stmt = $db->prepare("SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.status = ? ORDER BY a.created_at DESC");
    $stmt->execute([$statusFilter]);
    $articles = $stmt->fetchAll();
}

$pageTitle = 'Manajemen Artikel';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2">
        <a href="?status=all" class="btn btn-sm <?= $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' ?>">Semua (<?= countArticles('published') + countArticles('draft') ?>)</a>
        <a href="?status=published" class="btn btn-sm <?= $statusFilter === 'published' ? 'btn-success' : 'btn-outline-secondary' ?>">Published (<?= countArticles('published') ?>)</a>
        <a href="?status=draft" class="btn btn-sm <?= $statusFilter === 'draft' ? 'btn-secondary' : 'btn-outline-secondary' ?>">Draft (<?= countArticles('draft') ?>)</a>
    </div>
    <a href="<?= SITE_URL ?>/admin/articles/create.php" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Artikel Baru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Tanggal</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($articles)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada artikel.</td></tr>
                    <?php else: ?>
                    <?php foreach ($articles as $i => $a): ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td>
                            <div class="fw-semibold"><?= sanitize(truncate($a['title'], 50)) ?></div>
                            <small class="text-muted"><?= sanitize($a['slug']) ?></small>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= sanitize($a['category_name'] ?? 'Tanpa Kategori') ?></span></td>
                        <td>
                            <?php if ($a['status'] === 'published'): ?>
                            <span class="badge bg-success">Published</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($a['views']) ?></td>
                        <td><small class="text-muted"><?= formatDate($a['created_at']) ?></small></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?= SITE_URL ?>/article.php?slug=<?= $a['slug'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= SITE_URL ?>/admin/articles/edit.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary py-1 px-2" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= SITE_URL ?>/admin/articles/delete.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus" onclick="return confirm('Yakin hapus artikel ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>