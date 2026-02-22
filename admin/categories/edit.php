<?php
// File: admin/categories/edit.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$category = getCategoryById($id);
if (!$category) {
    setFlash('error', 'Kategori tidak ditemukan.');
    redirect(SITE_URL . '/admin/categories/');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $slug        = trim($_POST['slug'] ?? '') ?: createSlug($name);
    $slug        = uniqueSlug($slug, 'categories', $id);

    if (!$name) $errors[] = 'Nama kategori tidak boleh kosong.';

    if (empty($errors)) {
        updateCategory($id, ['name' => $name, 'slug' => $slug, 'description' => $description]);
        setFlash('success', 'Kategori berhasil diupdate!');
        redirect(SITE_URL . '/admin/categories/');
    }
}

$pageTitle = 'Edit Kategori';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <?php if ($errors): ?>
        <div class="alert alert-danger"><?php foreach ($errors as $e) echo sanitize($e); ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold py-3">
                <i class="fas fa-edit text-primary me-2"></i>Edit Kategori
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori *</label>
                        <input type="text" name="name" class="form-control" required value="<?= sanitize($_POST['name'] ?? $category['name']) ?>" id="nameInput">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" class="form-control" id="slugInput" value="<?= sanitize($_POST['slug'] ?? $category['slug']) ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"><?= sanitize($_POST['description'] ?? $category['description']) ?></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                        <a href="<?= SITE_URL ?>/admin/categories/" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>