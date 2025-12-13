<?php
session_start();
require_once 'database.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $excerpt = trim($_POST['excerpt']);
    $category = $_POST['category'];
    $image_path = trim($_POST['image_path']);
    
    if(empty($title) || empty($content) || empty($excerpt) || empty($category)) {
        $error = "Semua field wajib diisi!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO articles (user_id, title, content, excerpt, category, image_path, status) 
                                   VALUES (?, ?, ?, ?, ?, ?, 'published')");
            $stmt->execute([$user_id, $title, $content, $excerpt, $category, $image_path]);
            $message = "Artikel berhasil dibuat!";
            $_POST = array();
        } catch (PDOException $e) {
            $error = "Gagal membuat artikel: " . $e->getMessage();
        }
    }
}

// Data untuk ditampilkan di HTML
$user_name = htmlspecialchars($_SESSION['full_name']);
$user_initial = strtoupper(substr($_SESSION['full_name'], 0, 1));
$current_date = date('F d, Y');

// Data dari form untuk prefilling (jika ada)
$form_data = [
    'title' => isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '',
    'content' => isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '',
    'excerpt' => isset($_POST['excerpt']) ? htmlspecialchars($_POST['excerpt']) : '',
    'category' => isset($_POST['category']) ? $_POST['category'] : '',
    'image_path' => isset($_POST['image_path']) ? htmlspecialchars($_POST['image_path']) : ''
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Artikel - NISEVA</title>
    <link rel="stylesheet" href="assets/css/add_article.css">
</head>
<body>
    <!-- Header with Navbar -->
    <div class="hero-section">
        <div class="navbar-wrapper">
            <nav class="navbar">
                <a href="index.php" class="logo">
                    <div class="logo-icon">
                        <img src="gambar/logo_nsv.png" alt="Logo Niseva" />
                    </div>
                    <span>NISEVA</span>
                </a>

                <ul class="nav-center" id="navMenu">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="#Investasi">Investasi</a></li>
                    <li><a href="#Belanja">Belanja</a></li>
                    <li><a href="articles.php" class="active">Artikel</a></li>
                </ul>

                <div class="nav-right">
                    <div class="search-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </div>
                    <span><?php echo $user_name; ?></span>
                    <div class="author-avatar-small"><?php echo $user_initial; ?></div>
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
                </div>
            </nav>
        </div>

        <div class="hero-content">
            <h1>Upload Artikel Anda</h1>
            <p>Isi form di bawah ini untuk membuat artikel anda</p>
        </div>
    </div>

    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>

    <!-- Upload Form -->
    <div class="article-container">
        <section class="upload-section">
            <h2>Upload Artikel</h2>

            <?php if($message): ?>
                <div class="upload-success" id="uploadSuccess">
                    <h3>✓ Artikel Berhasil Diupload!</h3>
                    <p><?php echo $message; ?></p>
                    <a href="articles.php" class="btn-view">Lihat Semua Artikel</a>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="upload-error">
                    <h3>⚠ Error</h3>
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form class="upload-form" method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="authorName">Nama Penulis</label>
                        <input type="text" id="authorName" name="authorName" 
                               value="<?php echo $user_name; ?>" 
                               readonly
                               style="background-color: #f5f5f5;">
                    </div>
                    <div class="form-group">
                        <label for="category">Kategori *</label>
                        <select id="category" name="category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="technology" <?php echo ($form_data['category'] == 'technology') ? 'selected' : ''; ?>>Teknologi Pertanian</option>
                            <option value="sustainability" <?php echo ($form_data['category'] == 'sustainability') ? 'selected' : ''; ?>>Pertanian Berkelanjutan</option>
                            <option value="organic" <?php echo ($form_data['category'] == 'organic') ? 'selected' : ''; ?>>Pertanian Organik</option>
                            <option value="innovation" <?php echo ($form_data['category'] == 'innovation') ? 'selected' : ''; ?>>Inovasi Pertanian</option>
                            <option value="research" <?php echo ($form_data['category'] == 'research') ? 'selected' : ''; ?>>Penelitian</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="title">Judul Artikel *</label>
                    <input type="text" id="title" name="title" required 
                           placeholder="Judul artikel yang menarik"
                           value="<?php echo $form_data['title']; ?>">
                </div>

                <div class="form-group">
                    <label for="excerpt">Ringkasan Artikel *</label>
                    <textarea id="excerpt" name="excerpt" required 
                              placeholder="Tulis ringkasan singkat artikel (150-300 karakter)"
                              maxlength="300"><?php echo $form_data['excerpt']; ?></textarea>
                    <div class="char-count"><span id="excerpt-count">0</span>/300</div>
                </div>

                <div class="form-group">
                    <label>Gambar Utama Artikel</label>
                    <div class="file-upload" id="fileUploadArea">
                        <input type="file" id="articleImage" name="articleImage" accept="image/*">
                        <label for="articleImage" class="file-upload-label">
                            <div class="upload-icon">📁</div>
                            <div>
                                <strong>Klik untuk mengunggah gambar</strong>
                                <p>atau seret dan lepas file di sini</p>
                            </div>
                        </label>
                    </div>
                    <div class="file-info" id="fileInfo">Format yang didukung: JPG, PNG, GIF. Maksimal ukuran: 5MB</div>
                    
                    <!-- URL Gambar Alternatif -->
                    <div style="margin-top: 1rem;">
                        <label for="image_path">Atau masukkan URL gambar:</label>
                        <input type="text" id="image_path" name="image_path" 
                               placeholder="https://example.com/image.jpg"
                               value="<?php echo $form_data['image_path']; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="content">Isi Artikel *</label>
                    <textarea id="content" name="content" required 
                              placeholder="Tulis artikel Anda di sini..."
                              rows="10"><?php echo $form_data['content']; ?></textarea>
                    <div class="char-count"><span id="content-count">0</span> karakter</div>
                </div>

                <button type="submit" class="btn-submit">📝 Upload Artikel</button>
            </form>

            <!-- Preview Section -->
            <div class="preview-section" id="previewSection" style="display: none;">
                <h3>Preview Artikel Anda</h3>
                <div class="preview-article">
                    <img id="previewImage" src="" alt="Preview Gambar" class="preview-image">
                    <div class="preview-content">
                        <h2 id="previewTitle" class="preview-title"></h2>
                        <div class="preview-meta">
                            <div class="author-avatar-small" id="previewAvatar"><?php echo $user_initial; ?></div>
                            <span id="previewAuthor"><?php echo $user_name; ?></span>
                            <span>•</span>
                            <span id="previewDate"><?php echo $current_date; ?></span>
                        </div>
                        <div class="preview-body">
                            <p id="previewContent"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Tentang NISEVA</h4>
                <ul class="footer-links">
                    <li><a href="home.php">Tentang Kami</a></li>
                    <li><a href="investasi.html">Visi & Misi</a></li>
                    <li><a href="marketplace.html">Tim Kami</a></li>
                    <li><a href="article.html">Karir</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Layanan</h4>
                <ul class="footer-links">
                    <li><a href="#">Investasi Pertanian</a></li>
                    <li><a href="#">Belanja Produk</a></li>
                    <li><a href="articles.php">Artikel & Edukasi</a></li>
                    <li><a href="#">Konsultasi</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Bantuan</h4>
                <ul class="footer-links">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Pusat Bantuan</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Kontak</h4>
                <ul class="footer-links">
                    <li><a href="mailto:info@niseva.com">info@niseva.com</a></li>
                    <li><a href="tel:+622112345678">+62 21 1234 5678</a></li>
                    <li><a>Jakarta, Indonesia</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 NISEVA Agro. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/add_article.js"></script>
</body>
</html>