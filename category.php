<?php
// File: category.php
require_once 'includes/session.php';
require_once 'includes/config.php';
require_once 'includes/functions.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
if (!$slug) redirect(SITE_URL);

$category = getCategoryBySlug($slug);
if (!$category) {
    http_response_code(404);
    $pageTitle = 'Kategori Tidak Ditemukan';
    require_once 'includes/header.php';
    echo '<div class="container py-5 text-center"><h2>Kategori tidak ditemukan</h2><a href="' . SITE_URL . '" class="btn btn-primary mt-3">Kembali</a></div>';
    require_once 'includes/footer.php';
    exit;
}

$currentPage_num = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($currentPage_num - 1) * POSTS_PER_PAGE;
$total = countArticlesByCategory($slug);
$articles = getArticlesByCategory($slug, POSTS_PER_PAGE, $offset);

$pageTitle = 'Kategori: ' . $category['name'];
require_once 'includes/header.php';
?>

<div class="bg-primary text-white py-4 mb-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>" class="text-white-50">Beranda</a></li>
                <li class="breadcrumb-item active text-white"><?= sanitize($category['name']) ?></li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold mb-1"><i class="fas fa-folder-open me-2"></i><?= sanitize($category['name']) ?></h1>
        <?php if ($category['description']): ?>
        <p class="mb-0 opacity-75"><?= sanitize($category['description']) ?></p>
        <?php endif; ?>
        <small class="opacity-75"><?= $total ?> artikel</small>
    </div>
</div>

<div class="container">
    <div class="row g-4">
        <div class="col-lg-8">
            <?php if (empty($articles)): ?>
            <div class="alert alert-info">Belum ada artikel dalam kategori ini.</div>
            <?php else: ?>
            <div class="row g-4">
                <?php foreach ($articles as $article): ?>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm article-card">
                        <div class="overflow-hidden" style="height:200px;">
                            <?php if ($article['image']): ?>
                            <img src="<?= UPLOAD_URL . sanitize($article['image']) ?>" alt="<?= sanitize($article['title']) ?>" class="card-img-top h-100 w-100" style="object-fit:cover;">
                            <?php else: ?>
                            <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-image text-secondary fa-3x"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold">
                                <a href="<?= SITE_URL ?>/article.php?slug=<?= $article['slug'] ?>" class="text-dark text-decoration-none stretched-link">
                                    <?= sanitize($article['title']) ?>
                                </a>
                            </h6>
                            <p class="text-muted small"><?= truncate($article['excerpt'] ?: $article['content'], 100) ?></p>
                            <div class="d-flex justify-content-between text-muted small">
                                <span><i class="fas fa-calendar me-1"></i><?= formatDate($article['created_at']) ?></span>
                                <span><i class="fas fa-eye me-1"></i><?= number_format($article['views']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-5">
                <?= pagination($total, $currentPage_num, POSTS_PER_PAGE, SITE_URL . '/category.php?slug=' . $slug . '&') ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-4">
            <?php include 'includes/sidebar.php'; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>