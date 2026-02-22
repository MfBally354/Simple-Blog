<?php
// File: includes/functions.php

require_once __DIR__ . '/config.php';

// ─── ARTICLE FUNCTIONS ───────────────────────────────────────────────────────

function getArticles($limit = null, $offset = 0, $status = 'published') {
    $db = getDB();
    $sql = "SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.status = ?
            ORDER BY a.created_at DESC";
    if ($limit) $sql .= " LIMIT $limit OFFSET $offset";
    $stmt = $db->prepare($sql);
    $stmt->execute([$status]);
    return $stmt->fetchAll();
}

function getArticleBySlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, c.name as category_name, c.slug as category_slug
                           FROM articles a
                           LEFT JOIN categories c ON a.category_id = c.id
                           WHERE a.slug = ? AND a.status = 'published'");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getArticleById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, c.name as category_name
                           FROM articles a
                           LEFT JOIN categories c ON a.category_id = c.id
                           WHERE a.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getArticlesByCategory($categorySlug, $limit = null, $offset = 0) {
    $db = getDB();
    $sql = "SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE c.slug = ? AND a.status = 'published'
            ORDER BY a.created_at DESC";
    if ($limit) $sql .= " LIMIT $limit OFFSET $offset";
    $stmt = $db->prepare($sql);
    $stmt->execute([$categorySlug]);
    return $stmt->fetchAll();
}

function searchArticles($query, $limit = null, $offset = 0) {
    $db = getDB();
    $like = '%' . $query . '%';
    $sql = "SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published' AND (a.title LIKE ? OR a.content LIKE ? OR a.excerpt LIKE ?)
            ORDER BY a.created_at DESC";
    if ($limit) $sql .= " LIMIT $limit OFFSET $offset";
    $stmt = $db->prepare($sql);
    $stmt->execute([$like, $like, $like]);
    return $stmt->fetchAll();
}

function countArticles($status = 'published') {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM articles WHERE status = ?");
    $stmt->execute([$status]);
    return $stmt->fetchColumn();
}

function countArticlesByCategory($categorySlug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM articles a
                           JOIN categories c ON a.category_id = c.id
                           WHERE c.slug = ? AND a.status = 'published'");
    $stmt->execute([$categorySlug]);
    return $stmt->fetchColumn();
}

function countSearchResults($query) {
    $db = getDB();
    $like = '%' . $query . '%';
    $stmt = $db->prepare("SELECT COUNT(*) FROM articles a
                           WHERE a.status = 'published' AND (a.title LIKE ? OR a.content LIKE ? OR a.excerpt LIKE ?)");
    $stmt->execute([$like, $like, $like]);
    return $stmt->fetchColumn();
}

function incrementViews($id) {
    $db = getDB();
    $db->prepare("UPDATE articles SET views = views + 1 WHERE id = ?")->execute([$id]);
}

function createArticle($data) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO articles (category_id, title, slug, excerpt, content, image, status)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['category_id'] ?: null,
        $data['title'],
        $data['slug'],
        $data['excerpt'],
        $data['content'],
        $data['image'] ?? null,
        $data['status']
    ]);
    return $db->lastInsertId();
}

function updateArticle($id, $data) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE articles SET category_id=?, title=?, slug=?, excerpt=?, content=?, image=?, status=?, updated_at=NOW()
                           WHERE id=?");
    return $stmt->execute([
        $data['category_id'] ?: null,
        $data['title'],
        $data['slug'],
        $data['excerpt'],
        $data['content'],
        $data['image'] ?? null,
        $data['status'],
        $id
    ]);
}

function deleteArticle($id) {
    $db = getDB();
    $article = getArticleById($id);
    if ($article && $article['image']) {
        $imagePath = UPLOAD_DIR . $article['image'];
        if (file_exists($imagePath)) unlink($imagePath);
    }
    $db->prepare("DELETE FROM articles WHERE id = ?")->execute([$id]);
}

// ─── CATEGORY FUNCTIONS ───────────────────────────────────────────────────────

function getCategories() {
    $db = getDB();
    return $db->query("SELECT c.*, COUNT(a.id) as article_count
                        FROM categories c
                        LEFT JOIN articles a ON c.id = a.category_id AND a.status = 'published'
                        GROUP BY c.id
                        ORDER BY c.name ASC")->fetchAll();
}

function getCategoryBySlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM categories WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getCategoryById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createCategory($data) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
    $stmt->execute([$data['name'], $data['slug'], $data['description']]);
    return $db->lastInsertId();
}

function updateCategory($id, $data) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE categories SET name=?, slug=?, description=? WHERE id=?");
    return $stmt->execute([$data['name'], $data['slug'], $data['description'], $id]);
}

function deleteCategory($id) {
    $db = getDB();
    $db->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
}

// ─── COMMENT FUNCTIONS ────────────────────────────────────────────────────────

function getCommentsByArticle($articleId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM comments WHERE article_id = ? AND status = 'approved' ORDER BY created_at ASC");
    $stmt->execute([$articleId]);
    return $stmt->fetchAll();
}

function getAllComments($status = null) {
    $db = getDB();
    if ($status) {
        $stmt = $db->prepare("SELECT c.*, a.title as article_title, a.slug as article_slug
                               FROM comments c JOIN articles a ON c.article_id = a.id
                               WHERE c.status = ? ORDER BY c.created_at DESC");
        $stmt->execute([$status]);
    } else {
        $stmt = $db->query("SELECT c.*, a.title as article_title, a.slug as article_slug
                             FROM comments c JOIN articles a ON c.article_id = a.id
                             ORDER BY c.created_at DESC");
    }
    return $stmt->fetchAll();
}

function countComments($status = 'pending') {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM comments WHERE status = ?");
    $stmt->execute([$status]);
    return $stmt->fetchColumn();
}

function createComment($data) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO comments (article_id, name, email, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$data['article_id'], $data['name'], $data['email'], $data['comment']]);
    return $db->lastInsertId();
}

function approveComment($id) {
    $db = getDB();
    $db->prepare("UPDATE comments SET status = 'approved' WHERE id = ?")->execute([$id]);
}

function deleteComment($id) {
    $db = getDB();
    $db->prepare("DELETE FROM comments WHERE id = ?")->execute([$id]);
}

// ─── HELPER FUNCTIONS ─────────────────────────────────────────────────────────

function createSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
}

function uniqueSlug($slug, $table, $excludeId = null) {
    $db = getDB();
    $original = $slug;
    $count = 1;
    while (true) {
        if ($excludeId) {
            $stmt = $db->prepare("SELECT id FROM $table WHERE slug = ? AND id != ?");
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $db->prepare("SELECT id FROM $table WHERE slug = ?");
            $stmt->execute([$slug]);
        }
        if (!$stmt->fetch()) break;
        $slug = $original . '-' . $count++;
    }
    return $slug;
}

function uploadImage($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > MAX_FILE_SIZE) return ['error' => 'Ukuran file terlalu besar (maks 2MB)'];
    if (!in_array($file['type'], ALLOWED_TYPES)) return ['error' => 'Format file tidak didukung'];

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . $ext;
    $destination = UPLOAD_DIR . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $filename];
    }
    return ['error' => 'Gagal mengupload gambar'];
}

function formatDate($date, $format = 'd M Y') {
    $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $d = date('d', strtotime($date));
    $m = $months[(int)date('m', strtotime($date)) - 1];
    $y = date('Y', strtotime($date));
    return "$d $m $y";
}

function truncate($text, $length = 150) {
    $text = strip_tags($text);
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect(SITE_URL . '/admin/login.php');
    }
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function getRecentArticles($limit = 5) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, title, slug, image, created_at FROM articles WHERE status = 'published' ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getPopularArticles($limit = 5) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, title, slug, image, views FROM articles WHERE status = 'published' ORDER BY views DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function pagination($totalItems, $currentPage, $perPage, $baseUrl) {
    $totalPages = ceil($totalItems / $perPage);
    if ($totalPages <= 1) return '';

    $html = '<nav><ul class="pagination justify-content-center">';

    if ($currentPage > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">&laquo;</a></li>';
    }

    for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++) {
        $active = $i == $currentPage ? ' active' : '';
        $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
    }

    if ($currentPage < $totalPages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">&raquo;</a></li>';
    }

    $html .= '</ul></nav>';
    return $html;
}