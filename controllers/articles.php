<?php
session_start();
require_once '../database.php';

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

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel - NISEVA Agro</title>
    <link rel="stylesheet" href="../assets/css/article.css">
</head>

<body>

    <div class="hero-section">
        <div class="navbar-wrapper">
            <nav class="navbar">
                <div class="logo">
                    <div class="logo-icon">
                        <img src="../gambar/logo_nsv.png" alt="Logo Niseva" />
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

                    <!-- SEBELUM LOGIN: tidak ada username / avatar / tombol add -->
                </div>
            </nav>
        </div>

        <div class="hero-content">
            <h1>Artikel Pertanian</h1>
            <p>Berbagai Solusi Pertanian Berkelanjutan</p>
        </div>
    </div>

    <div class="container">
        <div class="blog-section">
            <div class="blog-header">
                <div class="blog-title-section">
                    <h2>Artikel Terbaru</h2>
                    <p>Temukan inspirasi dan pengetahuan terbaru seputar pertanian</p>
                </div>
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
                <div style="padding: 2rem; background:white; border-radius:12px; text-align:center;">
                    <h3>Belum ada artikel tersedia</h3>
                    <p>Silakan kembali lagi nanti.</p>
                </div>
            <?php endif; ?>
        </div>

        <aside class="sidebar">
            <div class="stats-card">
                <h2>Mendorong Inovasi Pertanian Berkelanjutan...</h2>
                <p>Memberdayakan petani dengan teknologi modern ...</p>

                <div class="stats-grid">
                    <div class="stat-item"><div class="stat-number">10+</div><div class="stat-label">Tahun pengalaman</div></div>
                    <div class="stat-item"><div class="stat-number">2000+</div><div class="stat-label">Petani terbantu</div></div>
                    <div class="stat-item"><div class="stat-number">40%</div><div class="stat-label">Efisiensi sumber daya</div></div>
                    <div class="stat-item"><div class="stat-number">99%</div><div class="stat-label">Keandalan panen</div></div>
                </div>
            </div>
        </aside>
    </div>

    <footer class="footer">
        <div class="footer-bottom">
            <p>&copy; 2025 NISEVA Agro. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
