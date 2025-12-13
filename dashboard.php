<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Artikel - NISEVA</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <h3>Dashboard Artikel - NISEVA</h3>
        <div class="user-info">
            Hello, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b> |
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div style="margin-bottom: 20px;">
            <a href="add_article.html" class="btn-logout" style="background-color: #28a745; text-decoration: none; padding: 10px 15px;">
                + Tambah Artikel Baru
            </a>
            <a href="articles.php" class="btn-logout" style="background-color: #007bff; text-decoration: none; padding: 10px 15px; margin-left: 10px;">
                📝 Lihat Semua Artikel
            </a>
        </div>

        <h2>Daftar Artikel Terbaru</h2>

        <div class="search-box" style="margin-bottom: 20px;">
            <input type="text" id="searchInput" placeholder="Cari artikel..." 
                style="padding: 10px; width: 100%; font-size: 16px; border: 2px solid #ddd; border-radius: 5px;">
        </div>

        <div class="table-wrapper">
            <table id="articleTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>

            <div class="pagination-container" 
                style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                <span id="pageInfo">Halaman 1</span>
                <div>
                    <button id="btnPrev" style="width: auto; padding: 5px 15px; background: #6c757d;">Previous</button>
                    <button id="btnNext" style="width: auto; padding: 5px 15px;">Next</button>
                </div>
            </div>
        </div>

        <div id="tableLoader" class="loader"></div>
    </div>

    <script src="assets/js/dashboard.js"></script>
</body>
</html>