<?php
// File: search.php
require_once 'includes/session.php';
require_once 'includes/config.php';
require_once 'includes/functions.php';

$query = trim($_GET['q'] ?? '');
$currentPage_num = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($currentPage_num - 1) * POSTS_PER_PAGE;
$articles = [];
$total = 0;

if ($query) {
    $total = countSearchResults($query);
    $articles = searchArticles($query, POSTS_PER_PAGE, $offset);
}

$pageTitle = $query ? 'Hasil pencarian: ' . $query : 'Pencarian';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="mb-4">
                <h2 class="fw-bold h4">
                    <i class="fas fa-search text-primary me-2"></i>
                    <?php if ($query): ?>
                        Hasil pencarian: "<span class="text-primary"><?= sanitize($query) ?></span>"
                    <?php else: ?>
                        Cari Artikel
                    <?php endif; ?>
                </h2>
                <?php if ($query): ?>
                <p class="text-muted">Ditemukan <?= $total ?> artikel</p>
                <?php endif; ?>
            </div>

            <!-- Search Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?= SITE_URL ?>/search.php" method="GET">
                        <div class="input-group">
                            <input type="search" name="q" class="form-control form-control-lg" placeholder="Cari artikel..." value="<?= sanitize($query) ?>" required>
                            <button class="btn btn-primary btn-lg" type="submit"><i class="fas fa-search me-1"></i>Cari</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($query && empty($articles)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>Tidak ada artikel yang cocok dengan "<strong><?= sanitize($query) ?></strong>". Coba kata kunci lain.
            </div>
            <?php elseif ($articles): ?>
            <div class="row g-4">
                <?php foreach ($articles as $article): ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="row g-0">
                            <?php if ($article['image']): ?>
                            <div class="col-md-3">
                                <img src="<?= UPLOAD_URL . sanitize($article['image']) ?>" alt="<?= sanitize($article['title']) ?>" class="img-fluid rounded-start h-100" style="object-fit:cover; max-height:150px;">
                            </div>
                            <?php endif; ?>
                            <div class="<?= $article['image'] ? 'col-md-9' : 'col-12' ?>">
                                <div class="card-body">
                                    <?php if ($article['category_name']): ?>
                                    <span class="badge bg-primary mb-1"><?= sanitize($article['category_name']) ?></span>
                                    <?php endif; ?>
                                    <h6 class="fw-bold mb-2">
                                        <a href="<?= SITE_URL ?>/article.php?slug=<?= $article['slug'] ?>" class="text-dark text-decoration-none">
                                            <?= sanitize($article['title']) ?>
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-2"><?= truncate($article['excerpt'] ?: $article['content'], 120) ?></p>
                                    <div class="d-flex gap-3 text-muted small">
                                        <span><i class="fas fa-calendar me-1"></i><?= formatDate($article['created_at']) ?></span>
                                        <span><i class="fas fa-eye me-1"></i><?= number_format($article['views']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4">
                <?= pagination($total, $currentPage_num, POSTS_PER_PAGE, SITE_URL . '/search.php?q=' . urlencode($query) . '&') ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-4">
            <?php include 'includes/sidebar.php'; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>