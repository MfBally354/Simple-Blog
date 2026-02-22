<?php
// File: admin/articles/edit.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$article = getArticleById($id);
if (!$article) {
    setFlash('error', 'Artikel tidak ditemukan.');
    redirect(SITE_URL . '/admin/articles/');
}

$errors = [];
$categories = getCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['title'] ?? '');
    $content    = $_POST['content'] ?? '';
    $excerpt    = trim($_POST['excerpt'] ?? '');
    $categoryId = $_POST['category_id'] ?? '';
    $status     = $_POST['status'] ?? 'draft';
    $slug       = trim($_POST['slug'] ?? '') ?: createSlug($title);
    $slug       = uniqueSlug($slug, 'articles', $id);

    if (!$title)   $errors[] = 'Judul tidak boleh kosong.';
    if (!$content) $errors[] = 'Konten tidak boleh kosong.';

    $imageName = $article['image'];
    if (!empty($_FILES['image']['name'])) {
        $upload = uploadImage($_FILES['image']);
        if (isset($upload['error'])) {
            $errors[] = $upload['error'];
        } else {
            // Delete old image
            if ($imageName && file_exists(UPLOAD_DIR . $imageName)) {
                unlink(UPLOAD_DIR . $imageName);
            }
            $imageName = $upload['filename'];
        }
    }

    // Remove image
    if (isset($_POST['remove_image']) && $imageName) {
        if (file_exists(UPLOAD_DIR . $imageName)) unlink(UPLOAD_DIR . $imageName);
        $imageName = null;
    }

    if (empty($errors)) {
        updateArticle($id, [
            'category_id' => $categoryId ?: null,
            'title'       => $title,
            'slug'        => $slug,
            'excerpt'     => $excerpt,
            'content'     => $content,
            'image'       => $imageName,
            'status'      => $status,
        ]);
        setFlash('success', 'Artikel berhasil diupdate!');
        redirect(SITE_URL . '/admin/articles/');
    }
}

$pageTitle = 'Edit Artikel';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/admin/">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/admin/articles/">Artikel</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
    <a href="<?= SITE_URL ?>/article.php?slug=<?= $article['slug'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-eye me-1"></i>Lihat Artikel
    </a>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger">
    <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= sanitize($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Artikel *</label>
                        <input type="text" name="title" class="form-control form-control-lg" required value="<?= sanitize($_POST['title'] ?? $article['title']) ?>" id="titleInput">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted small"><?= SITE_URL ?>/article.php?slug=</span>
                            <input type="text" name="slug" class="form-control" id="slugInput" value="<?= sanitize($_POST['slug'] ?? $article['slug']) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ringkasan (Excerpt)</label>
                        <textarea name="excerpt" class="form-control" rows="3"><?= sanitize($_POST['excerpt'] ?? $article['excerpt']) ?></textarea>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Konten Artikel *</label>
                        <textarea name="content" id="editor" class="form-control" rows="15" required><?= htmlspecialchars($_POST['content'] ?? $article['content']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">Publikasi</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" <?= ($_POST['status'] ?? $article['status']) === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= ($_POST['status'] ?? $article['status']) === 'published' ? 'selected' : '' ?>>Published</option>
                        </select>
                    </div>
                    <small class="text-muted d-block mb-3">Dibuat: <?= formatDate($article['created_at']) ?></small>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Artikel</button>
                        <a href="<?= SITE_URL ?>/admin/articles/" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">Kategori</div>
                <div class="card-body">
                    <select name="category_id" class="form-select">
                        <option value="">-- Tanpa Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($_POST['category_id'] ?? $article['category_id']) == $cat['id'] ? 'selected' : '' ?>>
                            <?= sanitize($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">Gambar Utama</div>
                <div class="card-body">
                    <?php if ($article['image']): ?>
                    <div class="mb-3" id="currentImageSection">
                        <img src="<?= UPLOAD_URL . sanitize($article['image']) ?>" alt="Current" class="img-fluid rounded mb-2" style="max-height:180px; object-fit:cover; width:100%;">
                        <label class="d-flex align-items-center gap-2 text-danger small">
                            <input type="checkbox" name="remove_image" value="1"> Hapus gambar ini
                        </label>
                    </div>
                    <?php endif; ?>
                    <div id="imagePreview" class="mb-3 d-none">
                        <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height:180px; object-fit:cover; width:100%;">
                    </div>
                    <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                    <small class="text-muted">Max 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#editor',
    height: 450,
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; }'
});

document.getElementById('imageInput').addEventListener('change', function() {
    if (this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
        };
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>