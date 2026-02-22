<?php
// File: about.php
$pageTitle = 'Tentang Kami';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width:100px;height:100px;">
                    <i class="fas fa-blog fa-3x"></i>
                </div>
                <h1 class="fw-bold"><?= SITE_NAME ?></h1>
                <p class="lead text-muted"><?= SITE_DESCRIPTION ?></p>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Tentang Blog Ini</h4>
                    <p>Selamat datang di <strong><?= SITE_NAME ?></strong>! Blog ini dibuat sebagai platform berbagi informasi, pengetahuan, dan pengalaman seputar dunia teknologi, tutorial, dan berbagai topik menarik lainnya.</p>
                    <p>Kami berkomitmen untuk menyajikan konten yang berkualitas, informatif, dan mudah dipahami oleh semua kalangan pembaca.</p>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <?php
                $stats = [
                    ['icon' => 'fas fa-newspaper', 'color' => 'primary', 'label' => 'Artikel', 'value' => countArticles()],
                    ['icon' => 'fas fa-tags', 'color' => 'success', 'label' => 'Kategori', 'value' => count(getCategories())],
                    ['icon' => 'fas fa-comments', 'color' => 'warning', 'label' => 'Komentar', 'value' => countComments('approved')],
                ];
                foreach ($stats as $s): ?>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="text-<?= $s['color'] ?> mb-2"><i class="<?= $s['icon'] ?> fa-2x"></i></div>
                        <h3 class="fw-bold mb-1"><?= $s['value'] ?></h3>
                        <p class="text-muted mb-0"><?= $s['label'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3"><i class="fas fa-envelope text-primary me-2"></i>Hubungi Kami</h4>
                    <p>Jika kamu memiliki pertanyaan, saran, atau ingin berkolaborasi, jangan ragu untuk menghubungi kami.</p>
                    <a href="<?= SITE_URL ?>/contact.php" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Kirim Pesan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>