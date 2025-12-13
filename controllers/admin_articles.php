<?php
/**
 * Admin Articles Controller
 * Location: controller/admin_articles.php
 */

session_start();

// Cek autentikasi (sesuaikan dengan sistem Anda)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/login.php');
    exit;
}

$current_user_id = $_SESSION['user_id'] ?? 1;

// Include database connection (sesuaikan path)
require_once '../backend/config.php';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM articles WHERE article_id = ?");
    $stmt->execute([$delete_id]);
    header('Location: admin_articles.php');
    exit;
}

// Handle status toggle
if (isset($_GET['toggle_status'])) {
    $article_id = intval($_GET['toggle_status']);
    $stmt = $pdo->prepare("SELECT status FROM articles WHERE article_id = ?");
    $stmt->execute([$article_id]);
    $current_status = $stmt->fetchColumn();
    $new_status = ($current_status === 'published') ? 'draft' : 'published';
    $stmt = $pdo->prepare("UPDATE articles SET status = ? WHERE article_id = ?");
    $stmt->execute([$new_status, $article_id]);
    header('Location: admin_articles.php');
    exit;
}

// Get statistics
$stats = [
    'total_articles' => 3,
    'published_articles' => 2,
    'draft_articles' => 1,
    'archived_articles' => 0
];

// Untuk production, uncomment ini:
// try {
//     $stmt = $pdo->query("SELECT COUNT(*) FROM articles");
//     $stats['total_articles'] = $stmt->fetchColumn();
//     
//     $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'published'");
//     $stats['published_articles'] = $stmt->fetchColumn();
//     
//     $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'draft'");
//     $stats['draft_articles'] = $stmt->fetchColumn();
//     
//     $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'archived'");
//     $stats['archived_articles'] = $stmt->fetchColumn();
// } catch (PDOException $e) {
//     $stats = ['total_articles' => 0, 'published_articles' => 0, 'draft_articles' => 0, 'archived_articles' => 0];
// }

// Get articles
$articles = [];

// Untuk production, uncomment ini:
// try {
//     $query = "SELECT a.*, u.full_name as author_name, u.user_id 
//               FROM articles a 
//               JOIN users u ON a.user_id = u.user_id 
//               ORDER BY a.created_at DESC";
//     
//     $stmt = $pdo->prepare($query);
//     $stmt->execute();
//     $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
// } catch (PDOException $e) {
//     $articles = [];
// }

// Sample data untuk development
if (empty($articles)) {
    $articles = [
        [
            'article_id' => 1,
            'title' => 'Panduan Pertanian Modern untuk Pemula',
            'excerpt' => 'Pelajari teknik dasar pertanian modern yang dapat meningkatkan hasil panen hingga 40%',
            'content' => 'Konten lengkap artikel...',
            'category' => 'teknologi',
            'status' => 'published',
            'user_id' => $current_user_id,
            'author_name' => 'Administrator',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            'image_url' => '../public/folderimage/default-article.jpg'
        ],
        [
            'article_id' => 2,
            'title' => 'Strategi Pemasaran Hasil Pertanian',
            'excerpt' => 'Cara menjual produk pertanian dengan harga kompetitif di pasar digital',
            'content' => 'Konten lengkap artikel...',
            'category' => 'tips',
            'status' => 'draft',
            'user_id' => $current_user_id,
            'author_name' => 'Administrator',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'image_url' => '../public/folderimage/default-article.jpg'
        ],
        [
            'article_id' => 3,
            'title' => 'Budidaya Organik: Tanpa Pestisida Kimia',
            'excerpt' => 'Teknik pertanian organik yang ramah lingkungan dan sehat',
            'content' => 'Konten lengkap artikel...',
            'category' => 'edukasi',
            'status' => 'published',
            'user_id' => 2,
            'author_name' => 'Petani Ahli',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            'image_url' => '../public/folderimage/default-article.jpg'
        ]
    ];
}

// Process articles data
foreach ($articles as &$article) {
    // Status icon dan text
    switch($article['status']) {
        case 'published':
            $article['status_icon'] = 'fa-eye';
            $article['status_text'] = 'Published';
            break;
        case 'draft':
            $article['status_icon'] = 'fa-edit';
            $article['status_text'] = 'Draft';
            break;
        case 'archived':
            $article['status_icon'] = 'fa-archive';
            $article['status_text'] = 'Archived';
            break;
        default:
            $article['status_icon'] = 'fa-question';
            $article['status_text'] = $article['status'];
    }
    
    // Format tanggal
    $article['created_date'] = date('d M Y', strtotime($article['created_at']));
    $article['created_datetime'] = date('d M Y H:i', strtotime($article['created_at']));
    
    // Inisial author
    $article['author_initial'] = strtoupper(substr($article['author_name'], 0, 1));
    
    // Apakah artikel milik admin saat ini
    $article['is_mine'] = ($article['user_id'] == $current_user_id);
    
    // Default image jika tidak ada
    if (empty($article['image_url'])) {
        $article['image_url'] = '../public/folderimage/default-article.jpg';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Artikel - Niseva Agro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Path dari controller/ ke assets/css/ -->
    <link rel="stylesheet" href="../assets/css/admin_articles.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-wrapper">
        <nav class="navbar">
            <a href="../public/home.php" class="logo">
                <div class="logo-icon">
                    <!-- Path dari controller/ ke public/folderimage/ -->
                    <img src="../public/folderimage/logo.png" alt="Logo Niseva">
                </div>
                <span>NISEVA</span>
            </a>

            <ul class="nav-center" id="navMenu">
                <li><a href="../public/home.php">Dashboard</a></li>
                <li><a href="../public/investasi.html">Investasi</a></li>
                <li><a href="../public/marketplace.html">Belanja</a></li>
                <li><a href="admin_articles.php" class="active">Artikel</a></li>
            </ul>

            <div class="nav-right">
                <div class="user-profile">
                    <a href="../public/profile.html">
                        <span>Admin</span>
                        <div class="logo-icon">AD</div>
                    </a>
                </div>
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Manajemen Artikel</h1>
                    <p class="page-subtitle">Kelola semua artikel platform NISEVA Agro</p>
                </div>
                <a href="../public/admin_add_article.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Artikel
                </a>
            </div>

            <!-- Stats Section -->
            <section class="stats-section">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_articles']; ?></div>
                    <div class="stat-label">Total Artikel</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['published_articles']; ?></div>
                    <div class="stat-label">Published</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['draft_articles']; ?></div>
                    <div class="stat-label">Draft</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['archived_articles']; ?></div>
                    <div class="stat-label">Archived</div>
                </div>
            </section>

            <!-- Tabs Navigation -->
            <section class="tabs-section">
                <div class="tabs">
                    <button class="tab-btn active" data-tab="all-articles">Semua Artikel</button>
                    <button class="tab-btn" data-tab="my-articles">Artikel Saya</button>
                </div>
            </section>

            <!-- All Articles Tab -->
            <section class="tab-content active" id="all-articles-tab">
                <div class="section-header">
                    <h2 class="section-title">Semua Artikel</h2>
                    <div class="results-count">Menampilkan <?php echo count($articles); ?> artikel</div>
                </div>

                <!-- Filters -->
                <div class="filter-group">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" placeholder="Cari artikel...">
                    </div>
                    <select class="filter-select" id="categoryFilter">
                        <option value="">Semua Kategori</option>
                        <option value="teknologi">Teknologi</option>
                        <option value="tips">Tips & Trik</option>
                        <option value="berita">Berita</option>
                        <option value="edukasi">Edukasi</option>
                    </select>
                    <select class="filter-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <!-- Articles Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ARTIKEL</th>
                                <th>KATEGORI</th>
                                <th>STATUS</th>
                                <th>TANGGAL</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="articlesTable">
                            <?php if (count($articles) > 0): ?>
                                <?php foreach ($articles as $article): ?>
                                <tr data-category="<?php echo $article['category']; ?>" 
                                    data-status="<?php echo $article['status']; ?>"
                                    data-title="<?php echo strtolower(htmlspecialchars($article['title'])); ?>"
                                    data-author-id="<?php echo $article['user_id']; ?>"
                                    data-is-mine="<?php echo $article['is_mine'] ? 'true' : 'false'; ?>">
                                    <td>
                                        <div class="article-info">
                                            <div class="article-title"><?php echo htmlspecialchars($article['title']); ?></div>
                                            <div class="article-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></div>
                                            <div class="article-meta">
                                                <div class="article-author">
                                                    <div class="author-avatar"><?php echo $article['author_initial']; ?></div>
                                                    <span><?php echo htmlspecialchars($article['author_name']); ?></span>
                                                    <?php if ($article['is_mine']): ?>
                                                        <span class="badge badge-primary">
                                                            <i class="fas fa-user"></i> Anda
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="article-date">
                                                    <i class="far fa-calendar"></i> <?php echo $article['created_date']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="category-badge"><?php echo ucfirst($article['category']); ?></span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $article['status']; ?>">
                                            <i class="fas <?php echo $article['status_icon']; ?>"></i>
                                            <?php echo $article['status_text']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $article['created_datetime']; ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="../public/admin_edit_article.php?id=<?php echo $article['article_id']; ?>" 
                                               class="btn btn-info btn-icon btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-warning btn-icon btn-sm toggle-status-btn" 
                                                    data-id="<?php echo $article['article_id']; ?>"
                                                    data-current-status="<?php echo $article['status']; ?>"
                                                    title="<?php echo $article['status'] === 'published' ? 'Ubah ke Draft' : 'Publikasikan'; ?>">
                                                <i class="fas <?php echo $article['status'] === 'published' ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                                            </button>
                                            <button class="btn btn-danger btn-icon btn-sm delete-btn" 
                                                    data-id="<?php echo $article['article_id']; ?>"
                                                    data-title="<?php echo htmlspecialchars($article['title']); ?>"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="no-data">
                                            <i class="fas fa-newspaper"></i>
                                            <h3>Belum ada artikel</h3>
                                            <p>Mulai dengan menambahkan artikel pertama untuk platform NISEVA Agro</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- My Articles Tab -->
            <section class="tab-content" id="my-articles-tab">
                <div class="section-header">
                    <h2 class="section-title">Artikel Saya</h2>
                    <div class="results-count" id="myArticlesCount">Menampilkan 0 artikel</div>
                </div>

                <div class="filter-group">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="mySearchInput" placeholder="Cari artikel saya...">
                    </div>
                    <select class="filter-select" id="myStatusFilter">
                        <option value="">Semua Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <div id="myArticlesGrid" class="articles-grid">
                    <!-- Grid diisi oleh JavaScript -->
                </div>
            </section>
        </div>
    </main>

    <!-- Modals -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><i class="fas fa-trash"></i> Konfirmasi Hapus</h2>
                <button class="modal-close" id="deleteModalClose">&times;</button>
            </div>
            <div class="modal-body">
                <p class="modal-message" id="deleteMessage">Apakah Anda yakin ingin menghapus artikel ini?</p>
                <div class="modal-actions">
                    <button class="btn btn-outline" id="cancelDelete">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="statusModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><i class="fas fa-exchange-alt"></i> Ubah Status</h2>
                <button class="modal-close" id="statusModalClose">&times;</button>
            </div>
            <div class="modal-body">
                <p class="modal-message" id="statusMessage">Ubah status artikel ini?</p>
                <div class="modal-actions">
                    <button class="btn btn-outline" id="cancelStatus">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button class="btn btn-primary" id="confirmStatus">
                        <i class="fas fa-check"></i> Ya, Ubah
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pass PHP current_user_id to JavaScript
        const currentUserId = <?php echo $current_user_id; ?>;
    </script>
    <!-- Path dari controller/ ke assets/js/ -->
    <script src="../assets/js/admin_articles.js"></script>
</body>
</html>