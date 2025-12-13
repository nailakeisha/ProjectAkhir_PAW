<?php
include 'database.php';


if (!isset($_GET['id'])) {
die("ID artikel tidak ditemukan.");
}


$id = $_GET['id'];
$query = "SELECT * FROM articles WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();


if (!$article) {
die("Artikel tidak ditemukan.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$title = $_POST['title'];
$content = $_POST['content'];


$updateQuery = "UPDATE articles SET title = ?, content = ? WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("ssi", $title, $content, $id);


if ($updateStmt->execute()) {
header("Location: admin_dashboard.php?status=updated");
exit();
} else {
echo "Gagal mengupdate artikel.";
}
}
?>