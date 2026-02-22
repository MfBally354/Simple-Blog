<?php
// File: admin/comments/delete.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    deleteComment($id);
    setFlash('success', 'Komentar berhasil dihapus.');
}
redirect(SITE_URL . '/admin/comments/');