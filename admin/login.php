<?php
// File: admin/login.php
require_once dirname(__DIR__) . '/includes/session.php';
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

if (isLoggedIn()) redirect(SITE_URL . '/admin/');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_name'] = $user['full_name'] ?: $user['username'];
            setFlash('success', 'Selamat datang, ' . $user['full_name'] . '!');
            redirect(SITE_URL . '/admin/');
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Semua field harus diisi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display:flex; align-items:center; }
        .login-card { border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                            <i class="fas fa-lock fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Admin Panel</h4>
                        <small class="text-muted"><?= SITE_NAME ?></small>
                    </div>

                    <?php if ($error): ?>
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i><?= sanitize($error) ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control" required autofocus value="<?= sanitize($_POST['username'] ?? '') ?>" placeholder="Username">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="password" class="form-control" required placeholder="Password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="<?= SITE_URL ?>" class="text-muted small"><i class="fas fa-home me-1"></i>Kembali ke Blog</a>
                    </div>
                    <hr class="my-3">
                    <p class="text-center text-muted small mb-0">Default: <code>admin</code> / <code>admin123</code></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/bootstrap.min.js"></script>
</body>
</html>