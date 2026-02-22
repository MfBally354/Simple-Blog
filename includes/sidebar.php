<?php
// File: includes/sidebar.php
?>
<!-- Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fas fa-search text-primary me-2"></i>Cari Artikel</h6>
        <form action="<?= SITE_URL ?>/search.php" method="GET">
            <div class="input-group">
                <input type="search" name="q" class="form-control" placeholder="Kata kunci..." value="<?= isset($_GET['q']) ? sanitize($_GET['q']) : '' ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
</div>

<!-- Categories -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fas fa-tags text-primary me-2"></i>Kategori</h6>
        <ul class="list-unstyled mb-0">
            <?php foreach (getCategories() as $cat): ?>
            <li class="mb-2">
                <a href="<?= SITE_URL ?>/category.php?slug=<?= $cat['slug'] ?>" class="d-flex justify-content-between align-items-center text-decoration-none text-dark p-2 rounded hover-bg">
                    <span><i class="fas fa-folder text-warning me-2"></i><?= sanitize($cat['name']) ?></span>
                    <span class="badge bg-primary rounded-pill"><?= $cat['article_count'] ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<!-- Popular Articles -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fas fa-fire text-danger me-2"></i>Artikel Populer</h6>
        <?php foreach (getPopularArticles(5) as $i => $art): ?>
        <div class="d-flex align-items-start mb-3">
            <span class="badge bg-primary me-2 mt-1"><?= $i + 1 ?></span>
            <div>
                <a href="<?= SITE_URL ?>/article.php?slug=<?= $art['slug'] ?>" class="text-dark text-decoration-none small fw-semibold d-block mb-1">
                    <?= sanitize($art['title']) ?>
                </a>
                <small class="text-muted"><i class="fas fa-eye me-1"></i><?= number_format($art['views']) ?> views</small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Recent Articles -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fas fa-clock text-success me-2"></i>Artikel Terbaru</h6>
        <?php foreach (getRecentArticles(5) as $art): ?>
        <div class="d-flex align-items-center mb-3">
            <?php if ($art['image']): ?>
            <img src="<?= UPLOAD_URL . sanitize($art['image']) ?>" alt="" class="rounded me-2 flex-shrink-0" width="50" height="50" style="object-fit:cover;">
            <?php else: ?>
            <div class="bg-light rounded me-2 flex-shrink-0 d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                <i class="fas fa-image text-secondary small"></i>
            </div>
            <?php endif; ?>
            <div>
                <a href="<?= SITE_URL ?>/article.php?slug=<?= $art['slug'] ?>" class="text-dark text-decoration-none small fw-semibold d-block">
                    <?= sanitize($art['title']) ?>
                </a>
                <small class="text-muted"><?= formatDate($art['created_at']) ?></small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>