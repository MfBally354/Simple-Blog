<?php
// File: includes/footer.php
?>
<!-- Footer -->
<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-blog me-2"></i><?= SITE_NAME ?></h5>
                <p class="text-muted"><?= SITE_DESCRIPTION ?></p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-muted fs-5"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-muted fs-5"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-muted fs-5"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-muted fs-5"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold mb-3">Kategori</h6>
                <ul class="list-unstyled">
                    <?php foreach (getCategories() as $cat): ?>
                    <li class="mb-1">
                        <a href="<?= SITE_URL ?>/category.php?slug=<?= $cat['slug'] ?>" class="text-muted text-decoration-none">
                            <i class="fas fa-angle-right me-1"></i><?= sanitize($cat['name']) ?>
                            <span class="badge bg-secondary ms-1"><?= $cat['article_count'] ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold mb-3">Artikel Terbaru</h6>
                <?php foreach (getRecentArticles(3) as $recent): ?>
                <div class="d-flex mb-2">
                    <i class="fas fa-file-alt text-primary me-2 mt-1"></i>
                    <a href="<?= SITE_URL ?>/article.php?slug=<?= $recent['slug'] ?>" class="text-muted text-decoration-none small">
                        <?= sanitize($recent['title']) ?>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <hr class="my-4 border-secondary">
        <div class="text-center text-muted small">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Dibuat dengan <i class="fas fa-heart text-danger"></i> menggunakan PHP & MySQL.</p>
        </div>
    </div>
</footer>

<script src="<?= SITE_URL ?>/assets/js/bootstrap.min.js"></script>
<script src="<?= SITE_URL ?>/assets/js/script.js"></script>
</body>
</html>