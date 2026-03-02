# Blog Sederhana

Proyek blog sederhana dengan PHP Native dan MySQL.

## 📋 Fitur
- Manajemen artikel (CRUD)
- Manajemen kategori
- Sistem komentar
- Admin panel
- WYSIWYG editor

## 🛠️ Requirements
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache2/Nginx)

## 🚀 Cara Install
1. Clone repositori ini `git clone https://github.com/MfBally354/Simple-Blog.git`
2. Import file `sql/database.sql` ke phpMyAdmin
3. Konfigurasi database di `includes/config.php`
4. Jalankan di browser anda

## 📁 Struktur Folder
├── about.php
├── admin
│   ├── articles
│   │   ├── create.php
│   │   ├── delete.php
│   │   ├── edit.php
│   │   └── index.php
│   ├── categories
│   │   ├── create.php
│   │   ├── delete.php
│   │   ├── edit.php
│   │   └── index.php
│   ├── comments
│   │   ├── approve.php
│   │   ├── delete.php
│   │   └── index.php
│   ├── includes
│   │   ├── admin_footer.php
│   │   ├── admin_header.php
│   │   └── admin_sidebar.php
│   ├── index.php
│   ├── login.php
│   └── logout.php
├── article.php
├── assets
│   ├── css
│   │   ├── admin.css
│   │   ├── bootstrap.min.css
│   │   └── style.css
│   └── js
│       ├── admin.js
│       ├── bootstrap.min.js
│       └── script.js
├── category.php
├── contact.php
├── git.py
├── includes
│   ├── config.php
│   ├── footer.php
│   ├── functions.php
│   ├── header.php
│   ├── session.php
│   └── sidebar.php
├── index.php
├── README.md
├── search.php
├── setup.sh
└── sql
    └── database.sql