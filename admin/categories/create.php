<?php
// File: admin/articles/create.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$errors = [];
$categories = getCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['title'] ?? '');
    $content    = $_POST['content'] ?? '';
    $excerpt    = trim($_POST['excerpt'] ?? '');
    $categoryId = $_POST['category_id'] ?? '';
    $status     = $_POST['status'] ?? 'draft';
    $slug       = trim($_POST['slug'] ?? '') ?: createSlug($title);
    $slug       = uniqueSlug($slug, 'articles');

    if (!$title)   $errors[] = 'Judul tidak boleh kosong.';
    if (!$content) $errors[] = 'Konten tidak boleh kosong.';

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $upload = uploadImage($_FILES['image']);
        if (isset($upload['error'])) {
            $errors[] = $upload['error'];
        } else {
            $imageName = $upload['filename'];
        }
    }

    if (empty($errors)) {
        createArticle([
            'category_id' => $categoryId ?: null,
            'title'       => $title,
            'slug'        => $slug,
            'excerpt'     => $excerpt,
            'content'     => $content,
            'image'       => $imageName,
            'status'      => $status,
        ]);
        setFlash('success', 'Artikel berhasil dibuat!');
        redirect(SITE_URL . '/admin/articles/');
    }
}

$pageTitle = 'Buat Artikel Baru';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/admin/">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/admin/articles/">Artikel</a></li>
            <li class="breadcrumb-item active">Buat Baru</li>
        </ol>
    </nav>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($errors as $e): ?>
        <li><?= sanitize($e) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Artikel *</label>
                        <input type="text" name="title" class="form-control form-control-lg" required value="<?= sanitize($_POST['title'] ?? '') ?>" placeholder="Masukkan judul artikel..." id="titleInput">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted small"><?= SITE_URL ?>/article.php?slug=</span>
                            <input type="text" name="slug" class="form-control" id="slugInput" value="<?= sanitize($_POST['slug'] ?? '') ?>" placeholder="auto-generate dari judul">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ringkasan (Excerpt)</label>
                        <textarea name="excerpt" class="form-control" rows="3" placeholder="Ringkasan singkat artikel untuk preview..."><?= sanitize($_POST['excerpt'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Konten Artikel *</label>
                        <textarea name="content" id="editor" class="form-control" rows="15" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Publish -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">Publikasi</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" <?= ($_POST['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= ($_POST['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan Artikel</button>
                        <a href="<?= SITE_URL ?>/admin/articles/" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>

            <!-- Category -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">Kategori</div>
                <div class="card-body">
                    <select name="category_id" class="form-select">
                        <option value="">-- Tanpa Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= sanitize($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <a href="<?= SITE_URL ?>/admin/categories/create.php" class="btn btn-sm btn-link p-0 mt-2">+ Tambah kategori baru</a>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">Gambar Utama</div>
                <div class="card-body">
                    <div id="imagePreview" class="mb-3 d-none">
                        <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height:200px; object-fit:cover; width:100%;">
                    </div>
                    <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                    <small class="text-muted">Max 2MB, format: JPG, PNG, GIF, WebP</small>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#editor',
    height: 450,
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; }'
});

// Auto slug from title
document.getElementById('titleInput').addEventListener('input', function() {
    const slug = this.value.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s-]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('slugInput').value = slug;
});

// Image preview
document.getElementById('imageInput').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>