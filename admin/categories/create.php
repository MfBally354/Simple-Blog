<?php
// File: admin/categories/create.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $slug        = trim($_POST['slug'] ?? '') ?: createSlug($name);
    $slug        = uniqueSlug($slug, 'categories');

    if (!$name) $errors[] = 'Nama kategori tidak boleh kosong.';

    if (empty($errors)) {
        createCategory(['name' => $name, 'slug' => $slug, 'description' => $description]);
        setFlash('success', 'Kategori berhasil dibuat!');
        redirect(SITE_URL . '/admin/categories/');
    }
}

$pageTitle = 'Buat Kategori Baru';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <?php if ($errors): ?>
        <div class="alert alert-danger"><?php foreach ($errors as $e) echo sanitize($e); ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold py-3">
                <i class="fas fa-tag text-primary me-2"></i>Buat Kategori Baru
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori *</label>
                        <input type="text" name="name" class="form-control" required value="<?= sanitize($_POST['name'] ?? '') ?>" placeholder="Nama kategori" id="nameInput">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" class="form-control" id="slugInput" value="<?= sanitize($_POST['slug'] ?? '') ?>" placeholder="auto-generate dari nama">
                        <small class="text-muted">URL: category.php?slug=<span id="slugPreview">...</span></small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat kategori..."><?= sanitize($_POST['description'] ?? '') ?></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                        <a href="<?= SITE_URL ?>/admin/categories/" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('nameInput').addEventListener('input', function() {
    const slug = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/[\s-]+/g, '-').replace(/^-+|-+$/g, '');
    document.getElementById('slugInput').value = slug;
    document.getElementById('slugPreview').textContent = slug;
});
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>