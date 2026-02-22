<?php
// File: admin/index.php
require_once dirname(__DIR__) . '/includes/session.php';
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';
requireLogin();

$db = getDB();
$totalArticles    = countArticles('published') + countArticles('draft');
$publishedCount   = countArticles('published');
$draftCount       = countArticles('draft');
$totalCategories  = count(getCategories());
$pendingComments  = countComments('pending');
$approvedComments = countComments('approved');

$recentArticles = getArticles(5, 0, 'published');
$recentDrafts   = getArticles(5, 0, 'draft');

$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/admin_header.php';
?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3 flex-shrink-0">
                    <i class="fas fa-newspaper fa-2x"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?= $totalArticles ?></h3>
                    <small class="text-muted">Total Artikel</small>
                    <div class="d-flex gap-2 mt-1">
                        <span class="badge bg-success"><?= $publishedCount ?> Published</span>
                        <span class="badge bg-secondary"><?= $draftCount ?> Draft</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3 flex-shrink-0">
                    <i class="fas fa-tags fa-2x"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?= $totalCategories ?></h3>
                    <small class="text-muted">Kategori</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3 flex-shrink-0">
                    <i class="fas fa-comments fa-2x"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?= $approvedComments + $pendingComments ?></h3>
                    <small class="text-muted">Total Komentar</small>
                    <?php if ($pendingComments > 0): ?>
                    <div class="mt-1"><span class="badge bg-warning text-dark"><?= $pendingComments ?> menunggu</span></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 me-3 flex-shrink-0">
                    <i class="fas fa-eye fa-2x"></i>
                </div>
                <div>
                    <?php $totalViews = $db->query("SELECT SUM(views) FROM articles")->fetchColumn(); ?>
                    <h3 class="fw-bold mb-0"><?= number_format($totalViews) ?></h3>
                    <small class="text-muted">Total Views</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= SITE_URL ?>/admin/articles/create.php" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Artikel Baru
                    </a>
                    <a href="<?= SITE_URL ?>/admin/categories/create.php" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>Kategori Baru
                    </a>
                    <?php if ($pendingComments > 0): ?>
                    <a href="<?= SITE_URL ?>/admin/comments/" class="btn btn-warning">
                        <i class="fas fa-bell me-1"></i><?= $pendingComments ?> Komentar Menunggu
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Articles Table -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="fas fa-newspaper text-primary me-2"></i>Artikel Terbaru</h6>
                <a href="<?= SITE_URL ?>/admin/articles/" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Views</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentArticles as $a): ?>
                            <tr>
                                <td class="fw-semibold"><?= sanitize(truncate($a['title'], 40)) ?></td>
                                <td><span class="badge bg-light text-dark"><?= sanitize($a['category_name'] ?? '-') ?></span></td>
                                <td><span class="badge bg-success">Published</span></td>
                                <td><?= number_format($a['views']) ?></td>
                                <td>
                                    <a href="<?= SITE_URL ?>/admin/articles/edit.php?id=<?= $a['id'] ?>" class="btn btn-xs btn-outline-primary btn-sm py-0 px-2">Edit</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-tags text-success me-2"></i>Kategori</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach (getCategories() as $cat): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span><?= sanitize($cat['name']) ?></span>
                        <span class="badge bg-primary rounded-pill"><?= $cat['article_count'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>