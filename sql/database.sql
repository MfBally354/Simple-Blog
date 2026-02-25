-- File: sql/database.sql
-- Blog Sederhana Database Schema d

CREATE DATABASE IF NOT EXISTS blog_sederhana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blog_sederhana;

-- Tabel users (admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel articles
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    image VARCHAR(255),
    status ENUM('draft', 'published') DEFAULT 'draft',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Tabel comments
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    comment TEXT NOT NULL,
    status ENUM('pending', 'approved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, email, full_name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@blog.com', 'Administrator');

-- Insert sample categories
INSERT INTO categories (name, slug, description) VALUES
('Teknologi', 'teknologi', 'Artikel seputar teknologi terkini'),
('Tutorial', 'tutorial', 'Panduan dan tutorial bermanfaat'),
('Lifestyle', 'lifestyle', 'Tips gaya hidup sehat dan produktif'),
('Berita', 'berita', 'Berita terkini seputar dunia');

-- Insert sample articles
INSERT INTO articles (category_id, title, slug, excerpt, content, status) VALUES
(1, 'Mengenal Kecerdasan Buatan (AI)', 'mengenal-kecerdasan-buatan-ai', 
'Artikel ini membahas dasar-dasar kecerdasan buatan dan bagaimana AI mengubah dunia kita.',
'<p>Kecerdasan Buatan atau <strong>Artificial Intelligence (AI)</strong> adalah simulasi proses kecerdasan manusia oleh mesin, terutama sistem komputer.</p><p>AI telah berkembang pesat dalam beberapa tahun terakhir dan mulai diterapkan di berbagai bidang kehidupan, mulai dari kesehatan, transportasi, hingga pendidikan.</p><p>Beberapa contoh penerapan AI yang sudah kita gunakan sehari-hari antara lain asisten virtual seperti Siri dan Google Assistant, rekomendasi konten di platform streaming, hingga filter spam di email.</p>',
'published'),
(2, 'Cara Membuat Blog dengan PHP', 'cara-membuat-blog-dengan-php',
'Pelajari cara membuat blog sederhana menggunakan PHP Native dan MySQL dari nol.',
'<p>Membuat blog dengan PHP adalah salah satu proyek terbaik untuk belajar web development. Dalam tutorial ini, kita akan membuat blog sederhana dari nol.</p><p>Yang dibutuhkan:</p><ul><li>PHP 7.4+</li><li>MySQL</li><li>Web Server (Apache/Nginx)</li></ul><p>Langkah pertama adalah menyiapkan database dan struktur folder proyek kita.</p>',
'published'),
(3, 'Tips Produktif Bekerja dari Rumah', 'tips-produktif-bekerja-dari-rumah',
'Bekerja dari rumah bisa lebih produktif dengan tips dan trik yang tepat.',
'<p>Work from home atau bekerja dari rumah telah menjadi gaya hidup baru bagi banyak orang. Namun, banyak yang merasa kurang produktif saat bekerja di rumah.</p><p>Berikut beberapa tips untuk meningkatkan produktivitas:</p><ol><li>Buat jadwal kerja yang konsisten</li><li>Siapkan ruang kerja yang nyaman</li><li>Istirahat secara teratur</li><li>Minimalkan distraksi</li></ol>',
'published');

