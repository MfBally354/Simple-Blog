#!/bin/bash

# setup.sh - Script untuk membuat struktur folder dan file untuk Blog Sederhana
# Jalankan dengan: chmod +x setup.sh && ./setup.sh

echo "🚀 Memulai setup Blog Sederhana..."
echo "================================"

# Fungsi untuk membuat folder
create_directory() {
    if [ ! -d "$1" ]; then
        mkdir -p "$1"
        echo "✅ Membuat folder: $1"
    else
        echo "📁 Folder sudah ada: $1"
    fi
}

# Fungsi untuk membuat file dengan konten dasar
create_file() {
    if [ ! -f "$1" ]; then
        touch "$1"
        echo "✅ Membuat file: $1"
        
        # Menambahkan konten dasar untuk file PHP
        case "$1" in
            *.php)
                echo "<?php" > "$1"
                echo "// File: $1" >> "$1"
                echo "?>" >> "$1"
                ;;
            *.css)
                echo "/* File: $1 */" > "$1"
                echo "" >> "$1"
                ;;
            *.js)
                echo "// File: $1" > "$1"
                echo "" >> "$1"
                ;;
            *.sql)
                echo "-- File: $1" > "$1"
                echo "" >> "$1"
                ;;
            *.htaccess)
                echo "# File: $1" > "$1"
                echo "" >> "$1"
                ;;
        esac
    else
        echo "📄 File sudah ada: $1"
    fi
}

echo ""
echo "📁 Membuat struktur folder..."
echo "----------------------------"

# Membuat folder utama
create_directory "assets"
create_directory "assets/css"
create_directory "assets/js"
create_directory "assets/images"
create_directory "assets/images/articles"
create_directory "assets/images/uploads"
create_directory "assets/plugins"
create_directory "assets/plugins/tinymce"
create_directory "assets/plugins/fontawesome"

create_directory "includes"

create_directory "admin"
create_directory "admin/articles"
create_directory "admin/categories"
create_directory "admin/comments"
create_directory "admin/includes"

create_directory "sql"

echo ""
echo "📝 Membuat file-file utama..."
echo "----------------------------"

# File di root directory
create_file "index.php"
create_file "article.php"
create_file "category.php"
create_file "search.php"
create_file "about.php"
create_file "contact.php"
create_file ".htaccess"

# File di folder assets/css
create_file "assets/css/style.css"
create_file "assets/css/admin.css"
create_file "assets/css/bootstrap.min.css"

# File di folder assets/js
create_file "assets/js/script.js"
create_file "assets/js/admin.js"
create_file "assets/js/bootstrap.min.js"

# File di folder includes
create_file "includes/config.php"
create_file "includes/functions.php"
create_file "includes/session.php"
create_file "includes/header.php"
create_file "includes/footer.php"

# File di folder admin (root)
create_file "admin/index.php"
create_file "admin/login.php"
create_file "admin/logout.php"

# File di folder admin/articles
create_file "admin/articles/index.php"
create_file "admin/articles/create.php"
create_file "admin/articles/edit.php"
create_file "admin/articles/delete.php"

# File di folder admin/categories
create_file "admin/categories/index.php"
create_file "admin/categories/create.php"
create_file "admin/categories/edit.php"
create_file "admin/categories/delete.php"

# File di folder admin/comments
create_file "admin/comments/index.php"
create_file "admin/comments/approve.php"
create_file "admin/comments/delete.php"

# File di folder admin/includes
create_file "admin/includes/admin_header.php"
create_file "admin/includes/admin_sidebar.php"
create_file "admin/includes/admin_footer.php"

# File SQL
create_file "sql/database.sql"

echo ""
echo "📊 Membuat struktur folder tambahan..."
echo "-------------------------------------"

# Membuat folder untuk upload dengan permission yang sesuai
if [ ! -d "uploads" ]; then
    mkdir -p "uploads"
    chmod 777 "uploads"
    echo "✅ Membuat folder: uploads (dengan permission 777)"
else
    echo "📁 Folder sudah ada: uploads"
fi

if [ ! -d "assets/images/articles" ]; then
    mkdir -p "assets/images/articles"
    chmod 777 "assets/images/articles"
    echo "✅ Membuat folder: assets/images/articles (dengan permission 777)"
fi

echo ""
echo "📋 Membuat file README.md..."
echo "--------------------------"

# Membuat file README.md dengan informasi proyek
cat > "README.md" << 'EOF'
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
- Web server (Apache/Nginx)

## 🚀 Cara Install
1. Clone repositori ini
2. Import file `sql/database.sql` ke phpMyAdmin
3. Konfigurasi database di `includes/config.php`
4. Jalankan di browser

## 📁 Struktur Folder
