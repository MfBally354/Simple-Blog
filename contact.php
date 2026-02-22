<?php
// File: contact.php
require_once 'includes/session.php';
require_once 'includes/config.php';
require_once 'includes/functions.php';

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$subject || !$message) {
        $error = 'Semua field harus diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        // Di sini bisa ditambahkan pengiriman email dengan mail() atau library
        $success = 'Pesan kamu berhasil dikirim! Kami akan merespons secepatnya.';
    }
}

$pageTitle = 'Kontak';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <h2 class="fw-bold mb-4">Hubungi Kami</h2>
            <p class="text-muted mb-4">Punya pertanyaan atau saran? Kami senang mendengarnya!</p>

            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width:44px;height:44px;">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Email</small>
                    <strong>admin@blog.com</strong>
                </div>
            </div>
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width:44px;height:44px;">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Lokasi</small>
                    <strong>Indonesia</strong>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Kirim Pesan</h4>

                    <?php if ($success): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><?= sanitize($success) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= sanitize($error) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama *</label>
                                <input type="text" name="name" class="form-control" required value="<?= sanitize($_POST['name'] ?? '') ?>" placeholder="Nama lengkap">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email *</label>
                                <input type="email" name="email" class="form-control" required value="<?= sanitize($_POST['email'] ?? '') ?>" placeholder="email@contoh.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Subjek *</label>
                                <input type="text" name="subject" class="form-control" required value="<?= sanitize($_POST['subject'] ?? '') ?>" placeholder="Topik pesan">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pesan *</label>
                                <textarea name="message" class="form-control" rows="5" required placeholder="Tulis pesan kamu..."><?= sanitize($_POST['message'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>