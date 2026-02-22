<?php
// File: admin/comments/approve.php
require_once dirname(dirname(__DIR__)) . '/includes/session.php';
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    approveComment($id);
    setFlash('success', 'Komentar berhasil disetujui.');
}
redirect(SITE_URL . '/admin/comments/');