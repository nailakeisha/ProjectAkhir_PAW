<?php
session_start();
require_once 'database.php';

// Get article ID
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get article details
try {
    $stmt = $pdo->prepare("SELECT a.*, u.full_name as author_name 
                          FROM articles a 
                          JOIN users u ON a.user_id = u.user_id 
                          WHERE a.article_id = ? AND a.status = 'published'");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$article) {
        header("Location: articles.php");
        exit();
    }

    // Update view count
    $stmt = $pdo->prepare("UPDATE articles SET view_count = view_count + 1 WHERE article_id = ?");
    $stmt->execute([$article_id]);

    // Get related articles
    $stmt = $pdo->prepare("SELECT a.*, u.full_name as author_name 
                          FROM articles a 
                          JOIN users u ON a.user_id = u.user_id 
                          WHERE a.category = ? AND a.article_id != ? AND a.status = 'published' 
                          LIMIT 4");
    $stmt->execute([$article['category'], $article_id]);
    $related_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    header("Location: articles.php");
    exit();
}
?>
<?php include 'article_detail.html'; ?>
