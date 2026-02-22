<?php
// File: includes/header.php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$categories = getCategories();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? sanitize($pageTitle) . ' - ' : '' ?><?= SITE_NAME ?></title>
    <meta name="description" content="<?= isset($pageDesc) ? sanitize($pageDesc) : SITE_DESCRIPTION ?>">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= SITE_URL ?>">
            <i class="fas fa-blog me-2"></i><?= SITE_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="<?= SITE_URL ?>">Beranda</a>
                </li>
                <?php foreach ($categories as $cat): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= SITE_URL ?>/category.php?slug=<?= $cat['slug'] ?>">
                        <?= sanitize($cat['name']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'about.php' ? 'active' : '' ?>" href="<?= SITE_URL ?>/about.php">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'contact.php' ? 'active' : '' ?>" href="<?= SITE_URL ?>/contact.php">Kontak</a>
                </li>
            </ul>
            <form class="d-flex" action="<?= SITE_URL ?>/search.php" method="GET">
                <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Cari artikel..." value="<?= isset($_GET['q']) ? sanitize($_GET['q']) : '' ?>">
                <button class="btn btn-outline-light btn-sm" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
</nav>