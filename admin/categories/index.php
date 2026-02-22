<?php
// File: admin/categories/index.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$categories = getCategories();
$pageTitle = 'Manajemen Kategori';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted"><?= count($categories) ?> kategori</span>
    <a href="<?= SITE_URL ?>/admin/categories/create.php" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Kategori Baru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Artikel</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                    <?php else: ?>
                    <?php foreach ($categories as $i => $cat): ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td class="fw-semibold"><?= sanitize($cat['name']) ?></td>
                        <td><code><?= sanitize($cat['slug']) ?></code></td>
                        <td class="text-muted small"><?= sanitize(truncate($cat['description'] ?? '', 80)) ?></td>
                        <td><span class="badge bg-primary"><?= $cat['article_count'] ?></span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?= SITE_URL ?>/admin/categories/edit.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-primary py-1 px-2"><i class="fas fa-edit"></i></a>
                                <a href="<?= SITE_URL ?>/admin/categories/delete.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger py-1 px-2" onclick="return confirm('Yakin hapus kategori ini? Artikel yang ada tidak akan terhapus.')"><i class="fas fa-trash"></i></a>
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