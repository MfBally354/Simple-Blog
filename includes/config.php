<?php
// File: includes/config.php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blog_sederhana');

// Site Configuration
define('SITE_NAME', 'Blog Sederhana');
define('SITE_URL', 'http://localhost/blog');
define('SITE_DESCRIPTION', 'Blog sederhana dengan PHP Native dan MySQL');

// Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/../assets/images/articles/');
define('UPLOAD_URL', SITE_URL . '/assets/images/articles/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Pagination
define('POSTS_PER_PAGE', 6);

// Connect to database
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:20px;background:#fee;border:1px solid #f00;border-radius:5px;">
                <h3>Database Connection Error</h3>
                <p>Tidak dapat terhubung ke database. Pastikan konfigurasi di <code>includes/config.php</code> sudah benar.</p>
                <small>' . htmlspecialchars($e->getMessage()) . '</small>
            </div>');
        }
    }
    return $pdo;
}