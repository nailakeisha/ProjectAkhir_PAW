<?php
session_start();
require_once 'database.php';

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$error = '';

// Inisialisasi variabel untuk form
$formData = [
    'title' => '',
    'content' => '',
    'excerpt' => '',
    'category' => '',
    'image_path' => ''
];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $excerpt = trim($_POST['excerpt']);
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
    
    // Simpan data form untuk ditampilkan kembali
    $formData = [
        'title' => $title,
        'content' => $content,
        'excerpt' => $excerpt,
        'category' => $category,
        'image_path' => $image_path
    ];
    
    // Validasi
    $errors = [];
    
    if(empty($title)) {
        $errors[] = "Judul artikel wajib diisi!";
    } elseif(strlen($title) > 255) {
        $errors[] = "Judul artikel maksimal 255 karakter!";
    }
    
    if(empty($content)) {
        $errors[] = "Konten artikel wajib diisi!";
    }
    
    if(empty($excerpt)) {
        $errors[] = "Ringkasan artikel wajib diisi!";
    } elseif(strlen($excerpt) > 300) {
        $errors[] = "Ringkasan maksimal 300 karakter!";
    }
    
    if(empty($category)) {
        $errors[] = "Kategori wajib dipilih!";
    }
    
    // Validasi URL gambar jika diisi
    if(!empty($image_path) && !filter_var($image_path, FILTER_VALIDATE_URL)) {
        $errors[] = "URL gambar tidak valid!";
    }
    
    // Handle upload gambar jika ada
    $uploadedImagePath = '';
    if(isset($_FILES['articleImage']) && $_FILES['articleImage']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        $fileType = mime_content_type($_FILES['articleImage']['tmp_name']);
        $fileSize = $_FILES['articleImage']['size'];
        
        if(!in_array($fileType, $allowedTypes)) {
            $errors[] = "Format file tidak didukung. Hanya JPG, PNG, GIF, dan WebP yang diperbolehkan.";
        } elseif($fileSize > $maxSize) {
            $errors[] = "Ukuran file terlalu besar. Maksimal 5MB.";
        } else {
            // Generate unique filename
            $ext = pathinfo($_FILES['articleImage']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $ext;
            $uploadDir = 'uploads/articles/';
            
            // Create directory if not exists
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $destination = $uploadDir . $filename;
            
            if(move_uploaded_file($_FILES['articleImage']['tmp_name'], $destination)) {
                $uploadedImagePath = $destination;
            } else {
                $errors[] = "Gagal mengupload gambar.";
            }
        }
    }
    
    // Jika tidak ada error, simpan ke database
    if(empty($errors)) {
        try {
            // Gunakan uploaded image path jika ada, jika tidak gunakan URL
            $finalImagePath = !empty($uploadedImagePath) ? $uploadedImagePath : $image_path;
            
            $stmt = $pdo->prepare("INSERT INTO articles (user_id, title, content, excerpt, category, image_path, status, created_at, updated_at) 
                                   VALUES (?, ?, ?, ?, ?, ?, 'published', NOW(), NOW())");
            $stmt->execute([$user_id, $title, $content, $excerpt, $category, $finalImagePath]);
            
            $message = "Artikel berhasil dibuat!";
            
            // Reset form data
            $formData = [
                'title' => '',
                'content' => '',
                'excerpt' => '',
                'category' => '',
                'image_path' => ''
            ];
            
        } catch (PDOException $e) {
            $error = "Gagal membuat artikel: " . $e->getMessage();
            error_log("Database error: " . $e->getMessage());
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

// Ambil nama lengkap user untuk ditampilkan
$userFullName = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'User';
$userAvatar = isset($_SESSION['full_name']) ? strtoupper(substr($_SESSION['full_name'], 0, 1)) : 'U';

// Siapkan data untuk ditampilkan di HTML
$displayData = [
    'user_full_name' => htmlspecialchars($userFullName, ENT_QUOTES, 'UTF-8'),
    'user_avatar' => $userAvatar,
    'form_title' => htmlspecialchars($formData['title'], ENT_QUOTES, 'UTF-8'),
    'form_content' => htmlspecialchars($formData['content'], ENT_QUOTES, 'UTF-8'),
    'form_excerpt' => htmlspecialchars($formData['excerpt'], ENT_QUOTES, 'UTF-8'),
    'form_category' => htmlspecialchars($formData['category'], ENT_QUQUOTES, 'UTF-8'),
    'form_image_path' => htmlspecialchars($formData['image_path'], ENT_QUOTES, 'UTF-8'),
    'current_date' => date('F d, Y'),
    'message' => $message,
    'error' => $error
];

// Include HTML template
include 'admin_add_article.html';
?>