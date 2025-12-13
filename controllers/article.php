<?php
session_start();
require_once 'database.php';

// Cek apakah user sudah login
$userLoggedIn = isset($_SESSION['user_id']);
$userName = $userLoggedIn ? $_SESSION['full_name'] : '';
$userInitial = $userLoggedIn ? strtoupper(substr($userName, 0, 1)) : '';

// Query artikel
try {
    $stmt = $pdo->prepare("
        SELECT a.*, u.full_name AS author_name 
        FROM articles a
        JOIN users u ON a.user_id = u.user_id
        WHERE a.status = 'published'
        ORDER BY a.created_at DESC
    ");
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $articles = [];
}

// Stats data
$stats = [
    ['value' => 10, 'suffix' => '+', 'label' => 'Tahun pengalaman di bidang pertanian'],
    ['value' => 2000, 'suffix' => '+', 'label' => 'Petani & mitra yang terbantu'],
    ['value' => 40, 'suffix' => '%', 'label' => 'Peningkatan efisiensi sumber daya'],
    ['value' => 99, 'suffix' => '%', 'label' => 'Keandalan hasil panen']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel - NISEVA Agro</title>
    <link rel="stylesheet" href="../assets/css/articles.css">
</head>

<body>

    <div class="hero-section">
        <div class="navbar-wrapper">
            <nav class="navbar">
                <div class="logo">
                    <div class="logo-icon">
                       <img src="gambar/logo_nsv.png">

                    </div>
                    <span>NISEVA</span>
                </div>

                <ul class="nav-center" id="navMenu">
                    <li><a href="../dashboard.php">Dashboard</a></li>
                    <li><a href="#Investasi">Investasi</a></li>
                    <li><a href="#Belanja">Belanja</a></li>
                    <li><a href="article.php" class="active">Artikel</a></li>
                </ul>

                <div class="nav-right">
                    <div class="search-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </div>

                    <?php if($userLoggedIn): ?>
                        <span><?php echo htmlspecialchars($userName); ?></span>
                        <div class="author-avatar"><?php echo $userInitial; ?></div>
                    <?php endif; ?>
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
                </div>
            </nav>
        </div>

        <div class="hero-content">
            <h1>Artikel Pertanian</h1>
            <p>Berbagai Solusi Pertanian Berkelanjutan</p>
        </div>
    </div>

    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>

    <div class="container">
        <div class="blog-section">
            <!-- Blog Header dengan Tombol Tambah Artikel -->
            <div class="blog-header">
                <div class="blog-title-section">
                    <h2>Artikel Terbaru</h2>
                    <p>Temukan inspirasi dan pengetahuan terbaru seputar pertanian</p>
                </div>
                
                <?php if($userLoggedIn): ?>
                    <a href="add_article.php" class="btn-add-article">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Tambah Artikel
                    </a>
                <?php endif; ?>
            </div>

            <!-- Daftar Artikel -->
            <?php if (count($articles) > 0): ?>
                <?php foreach ($articles as $article): ?>
                    <div class="blog-card" onclick="window.location.href='article_detail.php?id=<?= $article['article_id'] ?>'">
                        <div class="blog-content">
                            <div class="blog-category"><?= strtoupper($article['category']) ?></div>
                            <h2 class="blog-title">
                                <a href="article_detail.php?id=<?= $article['article_id'] ?>">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>
                            </h2>
                            <p class="blog-excerpt"><?= htmlspecialchars($article['excerpt']) ?></p>
                            <div class="blog-meta">
                                <div class="author-avatar-small">
                                    <?= strtoupper(substr($article['author_name'], 0, 1)) ?>
                                </div>
                                <span><?= htmlspecialchars($article['author_name']) ?></span>
                                <span>/</span>
                                <span><?= date('F d, Y', strtotime($article['created_at'])) ?></span>
                            </div>
                            <a href="article_detail.php?id=<?= $article['article_id'] ?>" class="read-more">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div style="text-align: center; padding: 3rem; background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <h3 style="color: #666; margin-bottom: 1rem;">Belum ada artikel tersedia</h3>
                    <p style="color: #999;">Silakan kembali lagi nanti untuk membaca artikel terbaru kami.</p>
                    <?php if($userLoggedIn): ?>
                        <a href="add_article.php" class="btn-add-article" style="margin-top: 1rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            Jadilah yang pertama menulis artikel
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <aside class="sidebar">
            <div class="stats-card">
                <h2>Mendorong Inovasi Pertanian Berkelanjutan untuk Masa Depan yang Lebih Hijau dan Produktif</h2>
                <p>Memberdayakan petani dengan teknologi pertanian modern yang mampu menekan biaya operasional, meningkatkan efisiensi, serta mendukung pertumbuhan berkelanjutan demi kesejahteraan petani dan generasi mendatang.</p>
                <div class="stats-grid">
                    <?php foreach ($stats as $stat_item): ?>
                    <div class="stat-item">
                        <div class="stat-number" 
                             data-target="<?php echo $stat_item['value']; ?>" 
                             data-suffix="<?php echo $stat_item['suffix']; ?>">
                            0
                        </div>
                        <div class="stat-label"><?php echo $stat_item['label']; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Call to Action untuk Tambah Artikel -->
            <?php if($userLoggedIn): ?>
            <div style="background: white; border-radius: 15px; padding: 2rem; margin-top: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
                <h3 style="color: #2E7D32; margin-bottom: 1rem;">Bagikan Pengetahuan Anda</h3>
                <p style="color: #666; margin-bottom: 1.5rem;">Punya pengalaman atau pengetahuan tentang pertanian? Bagikan dengan komunitas!</p>
                <a href="add_article.php" class="btn-add-article" style="width: 100%; justify-content: center;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Tulis Artikel Baru
                </a>
            </div>
            <?php else: ?>
            <div style="background: white; border-radius: 15px; padding: 2rem; margin-top: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
                <h3 style="color: #2E7D32; margin-bottom: 1rem;">Ingin Menulis Artikel?</h3>
                <p style="color: #666; margin-bottom: 1.5rem;">Login terlebih dahulu untuk berbagi pengetahuan dengan komunitas.</p>
                <a href="login.php" style="background: #4CAF50; color: white; padding: 0.8rem 1.5rem; border-radius: 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    Login Sekarang
                </a>
            </div>
            <?php endif; ?>
        </aside>
    </div>

   
<!-- Footer -->
<footer class="footer" id="contact">
  <div class="container">
    <div class="footer-content">
      <div class="footer-section">
        <div class="footer-logo">
          <div class="logo-icon">
            <img src="gambar/logo_nsv.png" alt="Logo Niseva" />
          </div>
          <span>Niseva Agro</span>
        </div>
        <p class="footer-description">
          Memimpin revolusi pertanian Indonesia melalui inovasi teknologi dan komitmen terhadap keberlanjutan.
        </p>
        <div class="social-links">
          <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
          <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
          <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
        </div>
      </div>

      <div class="footer-section">
        <h4>Layanan</h4>
        <ul class="footer-links">
          <li><a href="products.php"><i class="fas fa-chevron-right"></i> Marketplace</a></li>
          <li><a href="investments.php"><i class="fas fa-chevron-right"></i> Investasi Pertanian</a></li>
          <li><a href="article.html"><i class="fas fa-chevron-right"></i> Artikel & Edukasi</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>Perusahaan</h4>
        <ul class="footer-links">
          <li><a href="#about"><i class="fas fa-chevron-right"></i> Tentang Kami</a></li>
          <li><a href="#articles"><i class="fas fa-chevron-right"></i> Artikel</a></li>
          <li><a href="#"><i class="fas fa-chevron-right"></i> Karir</a></li>
          <li><a href="#"><i class="fas fa-chevron-right"></i> Blog</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>Kontak</h4>
        <div class="contact-info">
          <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Jl. Pertanian Modern No. 123, Jakarta</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-phone"></i>
            <span>+62 21 1234 5678</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <span>info@nisevaagro.com</span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="footer-bottom">
      <p class="copyright">
        © 2024 Niseva Agro. All Rights Reserved. | Developed with <i class="fas fa-heart"></i> for Indonesian Agriculture
      </p>
    </div>
  </div>
</footer>

    <script src="assets/js/article.js"></script>
    <script>
        // Animate stats counter
        document.addEventListener('DOMContentLoaded', function() {
            const statNumbers = document.querySelectorAll('.stat-number');
            
            statNumbers.forEach(stat => {
                const target = parseInt(stat.getAttribute('data-target'));
                const suffix = stat.getAttribute('data-suffix');
                let current = 0;
                const increment = target / 100;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    stat.textContent = Math.floor(current) + suffix;
                }, 20);
            });
        });
        
        // Toggle mobile menu function
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            if (navMenu && mobileOverlay) {
                navMenu.classList.toggle('active');
                mobileOverlay.classList.toggle('active');
            }
        }
    </script>
</body>
</html>