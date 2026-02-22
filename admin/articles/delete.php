<?php
// File: admin/articles/delete.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id && getArticleById($id)) {
    deleteArticle($id);
    setFlash('success', 'Artikel berhasil dihapus.');
} else {
    setFlash('error', 'Artikel tidak ditemukan.');
}
redirect(SITE_URL . '/admin/articles/');