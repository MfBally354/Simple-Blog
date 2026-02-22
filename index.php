<?php
// File: index.php
require_once 'includes/header.php';

$pageTitle = 'Beranda';
$currentPage_num = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($currentPage_num - 1) * POSTS_PER_PAGE;
$totalArticles = countArticles();
$articles = getArticles(POSTS_PER_PAGE, $offset);
$featuredArticle = $articles[0] ?? null;
?>

<!-- Hero Banner -->
<?php if ($featuredArticle): ?>
<section class="hero-section position-relative text-white mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 420px; display:flex; align-items:center;">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-warning text-dark mb-3 fs-6"><?= sanitize($featuredArticle['category_name'] ?? 'Umum') ?></span>
                <h1 class="display-5 fw-bold mb-3">
                    <a href="<?= SITE_URL ?>/article.php?slug=<?= $featuredArticle['slug'] ?>" class="text-white text-decoration-none">
                        <?= sanitize($featuredArticle['title']) ?>
                    </a>
                </h1>
                <p class="lead mb-4 opacity-90"><?= truncate($featuredArticle['excerpt'] ?: $featuredArticle['content'], 180) ?></p>
                <div class="d-flex align-items-center gap-3">
                    <a href="<?= SITE_URL ?>/article.php?slug=<?= $featuredArticle['slug'] ?>" class="btn btn-warning fw-bold px-4">
                        Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                    <small class="opacity-75"><i class="fas fa-calendar me-1"></i><?= formatDate($featuredArticle['created_at']) ?></small>
                </div>
            </div>
            <?php if ($featuredArticle['image']): ?>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <img src="<?= UPLOAD_URL . sanitize($featuredArticle['image']) ?>" alt="<?= sanitize($featuredArticle['title']) ?>" class="img-fluid rounded-3 shadow-lg" style="max-height:280px; object-fit:cover; width:100%;">
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<div class="container">
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold h4 mb-0"><i class="fas fa-newspaper text-primary me-2"></i>Artikel Terbaru</h2>
                <span class="text-muted small"><?= $totalArticles ?> artikel</span>
            </div>

            <?php if (empty($articles)): ?>
            <div class="alert alert-info">Belum ada artikel. <a href="<?= SITE_URL ?>/admin">Buat artikel pertama!</a></div>
            <?php else: ?>
            <div class="row g-4">
                <?php foreach ($articles as $i => $article): ?>
                <?php if ($i === 0 && $currentPage_num === 1) continue; // Skip featured ?>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm article-card">
                        <div class="position-relative overflow-hidden" style="height: 200px;">
                            <?php if ($article['image']): ?>
                            <img src="<?= UPLOAD_URL . sanitize($article['image']) ?>" alt="<?= sanitize($article['title']) ?>" class="card-img-top h-100 w-100" style="object-fit:cover;">
                            <?php else: ?>
                            <div class="bg-gradient h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
                                <i class="fas fa-image text-secondary fa-3x"></i>
                            </div>
                            <?php endif; ?>
                            <?php if ($article['category_name']): ?>
                            <span class="badge bg-primary position-absolute top-0 start-0 m-2"><?= sanitize($article['category_name']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <h6 class="card-title fw-bold mb-2">
                                <a href="<?= SITE_URL ?>/article.php?slug=<?= $article['slug'] ?>" class="text-decoration-none text-dark stretched-link">
                                    <?= sanitize($article['title']) ?>
                                </a>
                            </h6>
                            <p class="card-text text-muted small flex-grow-1 mb-2"><?= truncate($article['excerpt'] ?: $article['content'], 100) ?></p>
                            <div class="d-flex justify-content-between align-items-center text-muted small mt-auto">
                                <span><i class="fas fa-calendar me-1"></i><?= formatDate($article['created_at']) ?></span>
                                <span><i class="fas fa-eye me-1"></i><?= number_format($article['views']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-5">
                <?= pagination($totalArticles, $currentPage_num, POSTS_PER_PAGE, SITE_URL . '/index.php') ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <?php include 'includes/sidebar.php'; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>