<?php
// File: admin/comments/index.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$statusFilter = $_GET['status'] ?? 'all';
$comments = getAllComments($statusFilter === 'all' ? null : $statusFilter);

$pageTitle = 'Manajemen Komentar';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="d-flex gap-2 mb-4">
    <a href="?status=all" class="btn btn-sm <?= $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' ?>">Semua</a>
    <a href="?status=pending" class="btn btn-sm <?= $statusFilter === 'pending' ? 'btn-warning' : 'btn-outline-secondary' ?>">
        Menunggu <?php $p = countComments('pending'); if ($p): ?><span class="badge bg-danger ms-1"><?= $p ?></span><?php endif; ?>
    </a>
    <a href="?status=approved" class="btn btn-sm <?= $statusFilter === 'approved' ? 'btn-success' : 'btn-outline-secondary' ?>">Disetujui</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Komentar</th>
                        <th>Artikel</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($comments)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada komentar.</td></tr>
                    <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                    <tr class="<?= $comment['status'] === 'pending' ? 'table-warning' : '' ?>">
                        <td>
                            <div class="fw-semibold small"><?= sanitize($comment['name']) ?></div>
                            <div class="text-muted x-small" style="font-size:11px"><?= sanitize($comment['email']) ?></div>
                        </td>
                        <td class="small" style="max-width:250px;">
                            <div style="max-height:60px;overflow:hidden;"><?= sanitize(truncate($comment['comment'], 100)) ?></div>
                        </td>
                        <td class="small">
                            <a href="<?= SITE_URL ?>/article.php?slug=<?= $comment['article_slug'] ?>" target="_blank" class="text-decoration-none text-primary">
                                <?= sanitize(truncate($comment['article_title'], 40)) ?>
                            </a>
                        </td>
                        <td>
                            <?php if ($comment['status'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark">Menunggu</span>
                            <?php else: ?>
                            <span class="badge bg-success">Disetujui</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted"><?= formatDate($comment['created_at']) ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <?php if ($comment['status'] === 'pending'): ?>
                                <a href="<?= SITE_URL ?>/admin/comments/approve.php?id=<?= $comment['id'] ?>" class="btn btn-sm btn-outline-success py-1 px-2" title="Setujui">
                                    <i class="fas fa-check"></i>
                                </a>
                                <?php endif; ?>
                                <a href="<?= SITE_URL ?>/admin/comments/delete.php?id=<?= $comment['id'] ?>" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus" onclick="return confirm('Yakin hapus komentar ini?')">
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