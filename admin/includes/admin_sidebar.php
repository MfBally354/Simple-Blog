<?php
// File: admin/includes/admin_sidebar.php
$currentFile = basename($_SERVER['PHP_SELF']);
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));

function sidebarLink($url, $icon, $label, $currentDir, $targetDir) {
    $active = ($currentDir === $targetDir) ? 'active' : '';
    echo "<a href=\"$url\" class=\"list-group-item list-group-item-action $active py-3\">
            <i class=\"$icon me-2\"></i> $label
          </a>";
}
?>
<div id="sidebar-wrapper" class="border-end bg-white">
    <div class="sidebar-heading bg-primary text-white p-3 d-flex align-items-center gap-2">
        <i class="fas fa-blog fa-lg"></i>
        <span class="fw-bold"><?= SITE_NAME ?></span>
    </div>
    <div class="list-group list-group-flush">
        <a href="<?= SITE_URL ?>/admin/" class="list-group-item list-group-item-action <?= $currentFile === 'index.php' && $currentDir === 'admin' ? 'active' : '' ?> py-3">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
        <div class="sidebar-section-title px-3 py-2 text-uppercase text-muted small fw-bold mt-2">Konten</div>
        <a href="<?= SITE_URL ?>/admin/articles/" class="list-group-item list-group-item-action <?= $currentDir === 'articles' ? 'active' : '' ?> py-3">
            <i class="fas fa-newspaper me-2"></i> Artikel
        </a>
        <a href="<?= SITE_URL ?>/admin/categories/" class="list-group-item list-group-item-action <?= $currentDir === 'categories' ? 'active' : '' ?> py-3">
            <i class="fas fa-tags me-2"></i> Kategori
        </a>
        <a href="<?= SITE_URL ?>/admin/comments/" class="list-group-item list-group-item-action <?= $currentDir === 'comments' ? 'active' : '' ?> py-3">
            <i class="fas fa-comments me-2"></i> Komentar
            <?php $pendingComments = countComments('pending'); if ($pendingComments > 0): ?>
            <span class="badge bg-danger float-end"><?= $pendingComments ?></span>
            <?php endif; ?>
        </a>
        <div class="sidebar-section-title px-3 py-2 text-uppercase text-muted small fw-bold mt-2">Akun</div>
        <a href="<?= SITE_URL ?>" target="_blank" class="list-group-item list-group-item-action py-3">
            <i class="fas fa-external-link-alt me-2"></i> Lihat Blog
        </a>
        <a href="<?= SITE_URL ?>/admin/logout.php" class="list-group-item list-group-item-action text-danger py-3">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>
</div>