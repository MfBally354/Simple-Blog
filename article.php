<?php
// File: article.php
require_once 'includes/session.php';
require_once 'includes/config.php';
require_once 'includes/functions.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
if (!$slug) {
    redirect(SITE_URL);
}

$article = getArticleBySlug($slug);
if (!$article) {
    http_response_code(404);
    $pageTitle = 'Artikel Tidak Ditemukan';
    require_once 'includes/header.php';
    echo '<div class="container py-5 text-center"><h2>404 - Artikel Tidak Ditemukan</h2><a href="' . SITE_URL . '" class="btn btn-primary mt-3">Kembali ke Beranda</a></div>';
    require_once 'includes/footer.php';
    exit;
}

incrementViews($article['id']);
$comments = getCommentsByArticle($article['id']);

// Handle comment submission
$commentError = '';
$commentSuccess = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if (!$name || !$email || !$comment) {
        $commentError = 'Semua field harus diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $commentError = 'Format email tidak valid.';
    } elseif (strlen($comment) < 10) {
        $commentError = 'Komentar terlalu pendek (min 10 karakter).';
    } else {
        createComment(['article_id' => $article['id'], 'name' => $name, 'email' => $email, 'comment' => $comment]);
        $commentSuccess = 'Komentar kamu berhasil dikirim dan menunggu persetujuan admin.';
    }
}

$pageTitle = $article['title'];
$pageDesc  = truncate($article['excerpt'] ?: $article['content'], 160);
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE_URL ?>">Beranda</a></li>
                    <?php if ($article['category_name']): ?>
                    <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/category.php?slug=<?= $article['category_slug'] ?>"><?= sanitize($article['category_name']) ?></a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= sanitize($article['title']) ?></li>
                </ol>
            </nav>

            <!-- Article -->
            <article class="bg-white rounded-3 shadow-sm p-4">
                <?php if ($article['category_name']): ?>
                <span class="badge bg-primary mb-2"><?= sanitize($article['category_name']) ?></span>
                <?php endif; ?>
                <h1 class="fw-bold mb-3"><?= sanitize($article['title']) ?></h1>

                <div class="d-flex align-items-center gap-3 text-muted small mb-4 pb-3 border-bottom">
                    <span><i class="fas fa-calendar me-1"></i><?= formatDate($article['created_at']) ?></span>
                    <span><i class="fas fa-eye me-1"></i><?= number_format($article['views']) ?> views</span>
                    <span><i class="fas fa-comments me-1"></i><?= count($comments) ?> komentar</span>
                </div>

                <?php if ($article['image']): ?>
                <img src="<?= UPLOAD_URL . sanitize($article['image']) ?>" alt="<?= sanitize($article['title']) ?>" class="img-fluid rounded-3 mb-4 w-100" style="max-height:400px; object-fit:cover;">
                <?php endif; ?>

                <?php if ($article['excerpt']): ?>
                <div class="lead text-muted mb-4 p-3 bg-light rounded border-start border-primary border-3">
                    <?= sanitize($article['excerpt']) ?>
                </div>
                <?php endif; ?>

                <div class="article-content">
                    <?= $article['content'] ?>
                </div>

                <!-- Share Buttons -->
                <div class="mt-4 pt-3 border-top">
                    <span class="fw-semibold me-2">Bagikan:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL . '/article.php?slug=' . $article['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                        <i class="fab fa-facebook me-1"></i>Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode(SITE_URL . '/article.php?slug=' . $article['slug']) ?>&text=<?= urlencode($article['title']) ?>" target="_blank" class="btn btn-sm btn-outline-info me-1">
                        <i class="fab fa-twitter me-1"></i>Twitter
                    </a>
                    <a href="https://wa.me/?text=<?= urlencode($article['title'] . ' ' . SITE_URL . '/article.php?slug=' . $article['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="fab fa-whatsapp me-1"></i>WhatsApp
                    </a>
                </div>
            </article>

            <!-- Comments Section -->
            <div class="bg-white rounded-3 shadow-sm p-4 mt-4">
                <h4 class="fw-bold mb-4"><i class="fas fa-comments text-primary me-2"></i>Komentar (<?= count($comments) ?>)</h4>

                <?php if (empty($comments)): ?>
                <p class="text-muted">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0 me-3">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($comment['name']) ?>&background=random&size=48" alt="<?= sanitize($comment['name']) ?>" class="rounded-circle" width="48" height="48">
                    </div>
                    <div class="flex-grow-1 bg-light p-3 rounded">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="text-dark"><?= sanitize($comment['name']) ?></strong>
                            <small class="text-muted"><?= formatDate($comment['created_at']) ?></small>
                        </div>
                        <p class="mb-0"><?= nl2br(sanitize($comment['comment'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>

                <!-- Comment Form -->
                <h5 class="fw-bold mt-4 mb-3">Tinggalkan Komentar</h5>
                <?php if ($commentError): ?>
                <div class="alert alert-danger"><?= sanitize($commentError) ?></div>
                <?php endif; ?>
                <?php if ($commentSuccess): ?>
                <div class="alert alert-success"><?= sanitize($commentSuccess) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama *</label>
                            <input type="text" name="name" class="form-control" required value="<?= sanitize($_POST['name'] ?? '') ?>" placeholder="Nama kamu">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required value="<?= sanitize($_POST['email'] ?? '') ?>" placeholder="email@contoh.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Komentar *</label>
                            <textarea name="comment" class="form-control" rows="4" required placeholder="Tulis komentar kamu..."><?= sanitize($_POST['comment'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="submit_comment" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>Kirim Komentar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <?php include 'includes/sidebar.php'; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>