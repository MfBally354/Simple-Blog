<?php
// File: admin/categories/delete.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id && getCategoryById($id)) {
    deleteCategory($id);
    setFlash('success', 'Kategori berhasil dihapus.');
} else {
    setFlash('error', 'Kategori tidak ditemukan.');
}
redirect(SITE_URL . '/admin/categories/');