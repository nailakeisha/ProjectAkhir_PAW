<?php
session_start(); 

// Cek apakah user sudah login - dikomen dulu
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Data statis untuk sementara
$user_name = "Guest User";
$user_email = "guest@example.com";
$whatsapp_number = "6281234567890"; // Nomor WhatsApp default

// Data produk statis dengan nomor WhatsApp
$products = [
    [
        'name' => 'Traktor Mini 4WD',
        'description' => 'Traktor mini dengan penggerak 4 roda untuk lahan pertanian menengah',
        'price' => '45000000',
        'image_url' => 'folderimage/tractor.jpg', 
        'category' => 'Alat Berat',
        'rating' => 4.8,
        'reviews' => 124,
        'whatsapp' => '6281111111111' // Nomor WhatsApp penjual 1
    ],
    [
        'name' => 'Sensor Kelembaban Tanah',
        'description' => 'Sensor IoT untuk memantau kelembaban tanah secara real-time',
        'price' => '850000',
        'image_url' => 'folderimage/sensor.jpg',
        'category' => 'Teknologi',
        'rating' => 4.6,
        'reviews' => 89,
        'whatsapp' => '6282222222222' // Nomor WhatsApp penjual 2
    ],
    [
        'name' => 'Pompa Irigasi Solar Cell',
        'description' => 'Pompa air bertenaga surya untuk sistem irigasi hemat energi',
        'price' => '3500000',
        'image_url' => 'folderimage/pompa.jpg',
        'category' => 'Irigasi',
        'rating' => 4.9,
        'reviews' => 67,
        'whatsapp' => '6283333333333' // Nomor WhatsApp penjual 3
    ]
];

// Data investasi statis - DIPERBAIKI
$investments = [
    [
        'title' => 'Budidaya Padi Organik di Karawang',
        'description' => 'Pengembangan budidaya padi organik dengan teknologi modern untuk meningkatkan hasil panen dan kualitas beras organik premium.',
        'location' => 'Karawang, Jawa Barat',
        'interest_rate' => '15',
        'progress_percent' => '75',
        'collected_amount' => '225000000',
        'total_amount' => '300000000',
        'tenor' => '8',
        'status' => 'Penggalangan Dana',
        'id' => 1
    ],
    [
        'title' => 'Perkebunan Kopi Arabika Temanggung',
        'description' => 'Ekspansi perkebunan kopi arabika untuk meningkatkan produksi dan kualitas ekspor dengan sistem budidaya berkelanjutan.',
        'location' => 'Temanggung, Jawa Tengah',
        'interest_rate' => '14',
        'progress_percent' => '63',
        'collected_amount' => '189000000',
        'total_amount' => '300000000',
        'tenor' => '12',
        'status' => 'Penggalangan Dana',
        'id' => 2
    ],
    [
        'title' => 'Perkebunan Teh Organik',
        'description' => 'Pengembangan perkebunan teh organik dengan teknik budidaya tradisional untuk menghasilkan teh berkualitas ekspor.',
        'location' => 'Lembang, Jawa Barat',
        'interest_rate' => '16',
        'progress_percent' => '42',
        'collected_amount' => '168000000',
        'total_amount' => '400000000',
        'tenor' => '10',
        'status' => 'Penggalangan Dana',
        'id' => 3
    ]
];

// Data artikel statis
$articles = [
    [
        'title' => 'Revolusi Smart Farming di Indonesia',
        'excerpt' => 'Bagaimana teknologi IoT dan AI mengubah wajah pertanian tradisional menjadi modern dan efisien.',
        'author' => 'Tim Niseva',
        'image_url' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
        'created_at' => '2024-03-15',
        'id' => 1
    ],
    [
        'title' => '5 Strategi Pertanian Berkelanjutan',
        'excerpt' => 'Panduan praktis menerapkan pertanian berkelanjutan untuk menjaga kesuburan tanah jangka panjang.',
        'author' => 'Dr. Agus Santoso',
        'image_url' => 'folderimage/investasi1.jpg',
        'created_at' => '2024-03-10',
        'id' => 2
    ],
    [
        'title' => 'Vertical Farming: Solusi Urban Agriculture',
        'excerpt' => 'Memanfaatkan ruang terbatas untuk bercocok tanam dengan teknologi vertical farming yang efisien.',
        'author' => 'Sarah Wijaya',
        'image_url' => 'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
        'created_at' => '2024-03-05',
        'id' => 3
    ]
];

// Data statistik
$stats = [
    'total_farmers' => 1250,
    'productivity_increase' => 45,
    'provinces_reached' => 18
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Niseva Agro - Solusi Pertanian Modern</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Reset dan Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary: #2e7d32;
      --primary-light: #4caf50;
      --primary-dark: #1b5e20;
      --secondary: #ff9800;
      --accent: #2196f3;
      --text: #333;
      --text-light: #666;
      --light: #f5f5f5;
      --white: #fff;
      --gradient: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
      --shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
      --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.18);
      --radius: 16px;
    }

    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      line-height: 1.6;
      color: var(--text);
      background-color: var(--light);
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .section-header {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-badge {
      display: inline-block;
      background: var(--gradient);
      color: var(--white);
      padding: 16px 40px;
      border-radius: 40px;
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 16px;
      letter-spacing: 0.5px;
    }

    .section-title {
      font-size: 2.8rem;
      margin-bottom: 16px;
      color: var(--text);
      font-weight: 700;
    }

    .section-subtitle {
      font-size: 1.2rem;
      color: var(--text-light);
      max-width: 600px;
      margin: 0 auto;
      line-height: 1.8;
    }

    .highlight {
      background: linear-gradient(120deg, var(--primary) 0%, var(--accent) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 14px 32px;
      border-radius: var(--radius);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      cursor: pointer;
      border: none;
      font-size: 16px;
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .btn:hover::before {
      left: 100%;
    }

    .btn-primary {
      background: var(--gradient);
      color: var(--white);
      box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(46, 125, 50, 0.4);
    }

    .btn-secondary {
      background-color: transparent;
      color: var(--primary);
      border: 2px solid var(--primary);
    }

    .btn-secondary:hover {
      background-color: var(--primary);
      color: var(--white);
      transform: translateY(-2px);
    }

    .btn-outline {
      background-color: transparent;
      color: var(--primary);
      border: 2px solid var(--primary);
    }

    .btn-outline:hover {
      background-color: var(--primary);
      color: var(--white);
    }

    /* WhatsApp Button Styling */
    .btn-whatsapp {
      background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
      color: var(--white);
      box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
      border: none;
    }

    .btn-whatsapp:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
      background: linear-gradient(135deg, #128C7E 0%, #075E54 100%);
    }

    .btn-whatsapp i {
      font-size: 1.1rem;
    }

    /* Navbar Container */
    .navbar-wrapper {
      padding: 1rem 2rem;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      background: transparent;
    }

    .navbar {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 50px;
      padding: 0.8rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      max-width: 1200px;
      margin: 0 auto;
    }

    /* Logo Section */
    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 1.3rem;
      font-weight: 700;
      color: #1a1a1a;
      flex-shrink: 0;
    }

   .logo-icon {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  background: transparent; /* Menghilangkan background */
}

.logo-icon img {
  width: 400px;  /* Membesarkan ukuran gambar logo */
  height: 400px; /* Membesarkan ukuran gambar logo */
  object-fit: cover; /* Menjaga gambar tetap teratur dan tidak terdistorsi */
}


    /* Navigation Links */
    .nav-center {
      display: flex;
      list-style: none;
      gap: 2rem;
      align-items: center;
      margin: 0 auto;
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }

    .nav-center li {
      position: relative;
    }

    .nav-center a {
      text-decoration: none;
      color: #333;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: color 0.3s;
      display: flex;
      align-items: center;
      gap: 5px;
      padding: 8px 16px;
      border-radius: 20px;
    }

    .nav-center a:hover,
    .nav-center a.active {
      color: #4CAF50;
      background: rgba(76, 175, 80, 0.1);
    }

    /* Right Section */
    .nav-right {
      display: flex;
      align-items: center;
      gap: 1rem;
      flex-shrink: 0;
    }

    .search-icon {
      cursor: pointer;
      color: #333;
      transition: color 0.3s;
      padding: 8px;
    }

    .search-icon:hover {
      color: #4CAF50;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      padding: 6px 12px;
      border-radius: 20px;
      transition: background-color 0.3s;
    }

    .user-profile:hover {
      background: rgba(0, 0, 0, 0.05);
    }

    .user-profile span {
      font-weight: 600;
      color: #333;
      font-size: 0.9rem;
    }

    .mobile-menu-btn {
      display: none;
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: #333;
      padding: 8px;
    }

    /* Hero Section */
    .hero-section {
      position: relative;
      height: 100vh;
      display: flex;
      align-items: center;
      background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.4) 100%), url('folderimage/dashboardhome.jpg');
      background-size: cover;
      background-position: center;
      color: var(--white);
      margin-top: 0;
    }

    .hero-content {
      position: relative;
      z-index: 1;
      max-width: 800px;
      text-align: center;
      margin: 0 auto;
      padding-top: 80px;
    }

    .hero-content h1 {
      font-size: 3.5rem;
      margin-bottom: 20px;
      line-height: 1.1;
      font-weight: 800;
    }

    .hero-content p {
      font-size: 1.2rem;
      margin-bottom: 40px;
      opacity: 0.9;
      line-height: 1.6;
    }

    .hero-buttons {
      display: flex;
      gap: 20px;
      margin-bottom: 50px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .hero-stats {
      display: flex;
      gap: 40px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .stat {
      text-align: center;
      min-width: 120px;
    }

    .stat-number {
      display: block;
      font-size: 2.8rem;
      font-weight: 800;
      margin-bottom: 5px;
    }

    .stat-label {
      font-size: 0.9rem;
      opacity: 0.9;
      font-weight: 500;
    }

    /* About Section */
    .about {
      padding: 100px 0;
      background-color: var(--white);
    }

    .about-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center;
    }

    .about-description {
      margin-bottom: 30px;
      font-size: 1.1rem;
      color: var(--text-light);
      line-height: 1.8;
    }

    .features {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .feature {
      display: flex;
      align-items: flex-start;
      gap: 20px;
      padding: 20px;
      border-radius: var(--radius);
      transition: all 0.3s ease;
    }

    .feature:hover {
      background-color: rgba(46, 125, 50, 0.05);
      transform: translateX(10px);
    }

    .feature i {
      background: var(--gradient);
      color: var(--white);
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      flex-shrink: 0;
    }

    .feature h4 {
      margin-bottom: 8px;
      color: var(--text);
      font-size: 1.2rem;
    }

    .feature p {
      color: var(--text-light);
      line-height: 1.6;
      font-size: 0.95rem;
    }

    .about-visual {
      position: relative;
    }

    .about-image {
      position: relative;
      height: 450px;
      background: url('folderimage/pemandangan.jpg');
      background-size: cover;
      background-position: center;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .floating-card {
      position: absolute;
      background-color: var(--white);
      padding: 15px 20px;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 600;
      animation: float 3s ease-in-out infinite;
    }

    .card-1 {
      top: 20px;
      left: -20px;
      animation-delay: 0s;
    }

    .card-2 {
      bottom: 20px;
      right: -20px;
      animation-delay: 1.5s;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }

    .floating-card i {
      background: var(--gradient);
      color: var(--white);
      width: 35px;
      height: 35px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
    }

    .about-stats {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-top: 40px;
    }

    .stat-item {
      text-align: center;
      padding: 25px 15px;
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      transition: transform 0.3s ease;
    }

    .stat-item:hover {
      transform: translateY(-5px);
    }

    .stat-item .stat-number {
      font-size: 2.2rem;
      color: var(--primary);
      margin-bottom: 8px;
    }

    .stat-item .stat-label {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    /* Marketplace Preview Section */
    .marketplace-preview {
      padding: 50px 0;
      background-color: var(--light);
    }

    .marketplace-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 30px;
      margin-bottom: 40px;
    }

    .product-card {
      background-color: var(--white);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      position: relative;
    }

    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-hover);
    }

    .product-img {
      height: 200px;
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .product-badge {
      position: absolute;
      top: 12px;
      left: 12px;
      background: var(--gradient);
      color: var(--white);
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .product-content {
      padding: 20px;
    }

    .product-title {
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: var(--text);
      font-weight: 600;
    }

    .product-description {
      color: var(--text-light);
      margin-bottom: 15px;
      font-size: 0.9rem;
      line-height: 1.6;
    }

    .product-price {
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 15px;
    }

    .product-meta {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      font-size: 0.85rem;
      color: var(--text-light);
    }

    .product-rating {
      color: var(--secondary);
      font-weight: 600;
    }

    .product-actions {
      display: flex;
      gap: 10px;
    }

    .btn-cart {
      flex: 1;
      background: var(--gradient);
      color: var(--white);
      text-align: center;
      justify-content: center;
      padding: 12px 20px;
      font-size: 0.9rem;
    }

    .btn-wishlist {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background-color: var(--light);
      color: var(--text);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      font-size: 0.9rem;
    }

    .btn-wishlist:hover {
      background-color: #ffebee;
      color: #e53935;
    }

    .section-cta {
      text-align: center;
      margin-top: 40px;
    }

    /* Investment Preview Section - DIPERBAIKI */
    .investment-preview {
      padding: 50px 0;
      background-color: var(--white);
    }

    .investment-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 25px;
      margin-bottom: 40px;
    }

    .investment-card {
      background-color: var(--white);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      border: none;
      transition: all 0.3s ease;
      position: relative;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .investment-card:hover {
      transform: translateY(-6px);
      box-shadow: var(--shadow-hover);
    }

    .investment-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      background: var(--gradient);
      color: var(--white);
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 700;
      z-index: 2;
    }

    .investment-header {
      padding: 25px 25px 15px;
      border-bottom: 1px solid #f0f0f0;
      flex-grow: 1;
    }

    .investment-title {
      font-size: 1.2rem;
      margin-bottom: 12px;
      color: var(--text);
      font-weight: 700;
      line-height: 1.4;
    }

    .investment-description {
      color: var(--text-light);
      margin-bottom: 15px;
      font-size: 0.9rem;
      line-height: 1.6;
      min-height: 60px;
    }

    .investment-location {
      display: flex;
      align-items: center;
      gap: 6px;
      color: var(--text-light);
      font-size: 0.85rem;
    }

    .investment-location i {
      color: var(--primary);
    }

    .investment-body {
      padding: 20px 25px;
      flex-grow: 1;
    }

    .investment-progress {
      margin-bottom: 20px;
    }

    .progress-bar {
      height: 8px;
      background-color: #e0e0e0;
      border-radius: 4px;
      overflow: hidden;
      margin-bottom: 10px;
    }

    .progress-fill {
      height: 100%;
      background: var(--gradient);
      border-radius: 4px;
      transition: width 1s ease-in-out;
    }

    .progress-text {
      display: flex;
      justify-content: space-between;
      font-size: 0.85rem;
      color: var(--text-light);
    }

    .progress-percent {
      font-weight: 700;
      color: var(--primary);
    }

    .investment-amount {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 15px;
      text-align: center;
    }

    .investment-details {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-bottom: 20px;
    }

    .detail-item {
      text-align: center;
      padding: 15px 10px;
      background-color: var(--light);
      border-radius: var(--radius);
      transition: all 0.3s ease;
    }

    .detail-item:hover {
      background-color: rgba(46, 125, 50, 0.1);
      transform: translateY(-3px);
    }

    .detail-label {
      font-size: 0.8rem;
      color: var(--text-light);
      margin-bottom: 6px;
    }

    .detail-value {
      font-size: 1rem;
      font-weight: 700;
      color: var(--text);
    }

    .investment-footer {
      padding: 15px 25px;
      background-color: var(--light);
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: auto;
      flex-shrink: 0;
    }

    .investment-total {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--text);
    }

    /* Articles Section */
    .articles {
      padding: 100px 0;
      background-color: var(--light);
    }

    .articles-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
    }

    .article-card {
      background-color: var(--white);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .article-card:hover {
      transform: translateY(-6px);
      box-shadow: var(--shadow-hover);
    }

    .article-badge {
      position: absolute;
      top: 12px;
      left: 12px;
      background: var(--gradient);
      color: var(--white);
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .article-img {
      height: 200px;
      background-size: cover;
      background-position: center;
      position: relative;
      flex-shrink: 0;
    }

    .article-content {
      padding: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .article-content h3 {
      margin-bottom: 12px;
      color: var(--text);
      font-size: 1.2rem;
      font-weight: 700;
      line-height: 1.4;
    }

    .article-content p {
      margin-bottom: 15px;
      color: var(--text-light);
      line-height: 1.6;
      font-size: 0.9rem;
      flex-grow: 1;
    }

    .article-meta {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      font-size: 0.8rem;
      color: var(--text-light);
    }

    .author, .date {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .article-link {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: gap 0.3s;
      font-size: 0.9rem;
    }

    .article-link:hover {
      gap: 12px;
    }

    /* Footer */
    
/* Footer */
.footer {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    color: var(--white);
    padding: 80px 0 20px;
}

.footer-content {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 50px;
    margin-bottom: 60px;
}

.footer-logo {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
}

.footer-description {
    margin-bottom: 30px;
    opacity: 0.8;
    line-height: 1.7;
    font-size: 1rem;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    color: var(--white);
    transition: all 0.3s ease;
}

.social-link:hover {
    background-color: var(--primary);
    transform: translateY(-3px);
}

.footer-section h4 {
    margin-bottom: 25px;
    font-size: 1.3rem;
    position: relative;
    padding-bottom: 12px;
}

.footer-section h4::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background: var(--gradient);
}

.footer-links {
    list-style: none;
}

.footer-links li {
    margin-bottom: 15px;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-links a:hover {
    color: var(--primary);
    transform: translateX(5px);
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.contact-item i {
    color: var(--primary);
    margin-top: 3px;
    font-size: 1.1rem;
}

.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 25px;
    text-align: center;
}

.copyright {
    opacity: 0.7;
    font-size: 0.95rem;
}

.copyright i {
    color: #e53935;
}

.containerft {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}
    /* Responsive Improvements */
    @media (max-width: 1200px) {
      .navbar {
        max-width: 95%;
      }
    }

    @media (max-width: 992px) {
      .about-content {
        grid-template-columns: 1fr;
        gap: 40px;
      }

      .footer-content {
        grid-template-columns: 1fr 1fr;
        gap: 30px;
      }

      .nav-center {
        gap: 1.5rem;
      }

      .nav-center a {
        font-size: 0.85rem;
        padding: 6px 12px;
      }

      .hero-content h1 {
        font-size: 3rem;
      }

      .section-title {
        font-size: 2.4rem;
      }
      
      .floating-card {
        padding: 12px 15px;
        font-size: 0.9rem;
      }
      
      .card-1 {
        top: 15px;
        left: -15px;
      }
      
      .card-2 {
        bottom: 15px;
        right: -15px;
      }
    }

    @media (max-width: 768px) {
      .navbar-wrapper {
        padding: 0.8rem 1rem;
      }

      .navbar {
        padding: 0.6rem 1.5rem;
        border-radius: 40px;
      }

      .nav-center {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--white);
        flex-direction: column;
        padding: 1.5rem;
        box-shadow: var(--shadow);
        border-radius: var(--radius);
        margin: 10px;
        z-index: 1000;
      }

      .nav-center.active {
        display: flex;
      }

      .nav-center li {
        width: 100%;
        text-align: center;
      }

      .nav-center a {
        justify-content: center;
        padding: 12px 20px;
        border-radius: var(--radius);
      }

      .mobile-menu-btn {
        display: block;
      }

      .hero-content {
        padding: 20px;
      }

      .hero-content h1 {
        font-size: 2.2rem;
        line-height: 1.2;
      }

      .hero-content p {
        font-size: 1rem;
        padding: 0 10px;
      }

      .hero-buttons {
        flex-direction: column;
        align-items: center;
        gap: 15px;
      }

      .hero-buttons .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
      }

      .hero-stats {
        flex-direction: column;
        gap: 25px;
      }

      .marketplace-grid, .investment-grid, .articles-grid {
        grid-template-columns: 1fr;
      }

      .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
      }

      .about-image {
        height: 350px;
      }

      .about-stats {
        grid-template-columns: 1fr 1fr;
        gap: 15px;
      }

      .section-badge {
        padding: 12px 30px;
        font-size: 18px;
      }

      .section-title {
        font-size: 2rem;
      }

      .investment-grid {
        grid-template-columns: 1fr;
      }

      .investment-card {
        max-width: 100%;
      }
      
      .floating-card {
        position: relative;
        max-width: 200px;
        margin: 10px;
        animation: none;
      }
      
      .card-1, .card-2 {
        position: static;
        margin: 10px 0;
      }
    }

    @media (max-width: 576px) {
      .hero-section {
        height: auto;
        min-height: 100vh;
        padding: 100px 0 50px;
      }

      .hero-content h1 {
        font-size: 1.8rem;
      }

      .stat-number {
        font-size: 2.2rem;
      }

      .hero-stats .stat {
        min-width: 100px;
      }

      .section-badge {
        padding: 10px 25px;
        font-size: 16px;
      }

      .section-title {
        font-size: 1.8rem;
      }

      .about-stats {
        grid-template-columns: 1fr;
      }

      .floating-card {
        position: relative;
        top: auto;
        left: auto;
        bottom: auto;
        right: auto;
        max-width: 100%;
        margin: 10px 0;
        animation: none;
      }

      .about-visual {
        display: flex;
        flex-direction: column;
      }

      .about-image {
        height: 250px;
        margin-bottom: 20px;
      }

      .product-actions {
        flex-direction: column;
      }

      .btn-cart {
        width: 100%;
      }

      .investment-footer {
        flex-direction: column;
        gap: 15px;
      }

      .investment-footer .btn {
        width: 100%;
        text-align: center;
        justify-content: center;
      }

      .footer {
        padding: 40px 0 20px;
      }

      .social-links {
        justify-content: center;
      }

      .contact-item {
        font-size: 0.85rem;
      }
      
      .marketplace-preview {
        padding: 50px 20px;
      }
    }

    @media (max-width: 400px) {
      .navbar {
        padding: 0.6rem 1rem;
      }

      .logo span {
        display: none;
      }

      .hero-content h1 {
        font-size: 1.6rem;
      }

      .section-title {
        font-size: 1.6rem;
      }

      .section-badge {
        padding: 8px 20px;
        font-size: 14px;
      }

      .marketplace-grid,
      .investment-grid,
      .articles-grid {
        grid-template-columns: 1fr;
      }
      
      .stat-number {
        font-size: 1.8rem;
      }
    }
  </style>
</head>

<body>

<!-- Header with New Navbar -->
<div class="hero-section">
  <!-- Navbar -->
  <div class="navbar-wrapper">
    <nav class="navbar">
      <!-- Logo -->
<div class="logo">
  <div class="logo-icon"><img src="public/folderimage/logo.jpg" style="width: 45px; height: 45px; border-radius: 50%;">
  </div>
  <span>NISEVA</span>
</div>


      <!-- Center Navigation -->
      <ul class="nav-center" id="navMenu">
        <li><a href="#Dashboard" class="active">Dashboard</a></li>
        <li><a href="investasi.html">Investasi</a></li>
        <li><a href="marketplace.html">Belanja</a></li>
        <li><a href="article.html">Artikel</a></li>
      </ul>

      <!-- Right Section -->
      <div class="nav-right">
        <div class="search-icon">
            <path d="m21 21-4.35-4.35"></path>
          </svg>
        </div>
        <div class="user-profile">
          <span><?php echo htmlspecialchars($user_name); ?></span>
          <div class="logo-icon" style="width: 35px; height: 35px; font-size: 0.7rem;">
            GU
          </div>
        </div>
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
      </div>
    </nav>
  </div>

  <!-- Hero Content -->
  <div class="hero-content">
    <h1>Berkontribusi untuk Masa Depan Pertanian Indonesia</h1>
    <p></p>
    <div class="hero-buttons">
      <a href="#marketplace.html" class="btn btn-primary">Jelajahi Marketplace</a>
      <a href="#investasi.html" class="btn btn-secondary">Lihat Peluang Investasi</a>
    </div>
    <div class="hero-stats">
      <div class="stat">
        <span class="stat-number"><?php echo $stats['total_farmers']; ?></span>
        <span class="stat-label">Petani Bergabung</span>
      </div>
      <div class="stat">
        <span class="stat-number"><?php echo $stats['productivity_increase']; ?>%</span>
        <span class="stat-label">Peningkatan Hasil</span>
      </div>
      <div class="stat">
        <spzan class="stat-number"><?php echo $stats['provinces_reached']; ?></span>
        <span class="stat-label">Provinsi Terjangkau</span>
      </div>
    </div>
  </div>
</div>

<!-- About -->
<section class="about" id="about">
  <div class="container">
    <div class="about-content">
      <div class="about-text">
        <span class="section-badge">Tentang Kami</span>
        <h2 class="section-title">Membangun Pertanian <span class="highlight">Berkelanjutan</span></h2>
        <p class="about-description">
          Niseva Agro hadir sebagai solusi inovatif dalam dunia pertanian modern. Kami menggabungkan teknologi terkini dengan kearifan lokal untuk menciptakan sistem pertanian yang efisien, berkelanjutan, dan menguntungkan.
        </p>
        
        <div class="features">
          <div class="feature">
            <i class="fas fa-leaf"></i>
            <div>
              <h4>Ramah Lingkungan</h4>
              <p>Teknologi yang mendukung kelestarian alam dan ekosistem pertanian</p>
            </div>
          </div>
          <div class="feature">
            <i class="fas fa-chart-line"></i>
            <div>
              <h4>Hasil Maksimal</h4>
              <p>Optimasi produktivitas lahan pertanian dengan teknologi presisi</p>
            </div>
          </div>
          <div class="feature">
            <i class="fas fa-handshake"></i>
            <div>
              <h4>Kemitraan</h4>
              <p>Kolaborasi erat dengan petani lokal untuk pengembangan berkelanjutan</p>
            </div>
          </div>
        </div>
      </div>

      <div class="about-visual">
        <div class="about-image">
          <div class="floating-card card-1">
            <i class="fas fa-trophy"></i>
            <span>Inovator Terbaik 2024</span>
          </div>
          <div class="floating-card card-2">
            <i class="fas fa-users"></i>
            <span>5000+ Petani</span>
          </div>
        </div>
        
        <div class="about-stats">
          <div class="stat-item">
            <span class="stat-number">20+</span>
            <span class="stat-label">Tahun Pengalaman</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">45%</span>
            <span class="stat-label">Penghematan Air</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">2000+</span>
            <span class="stat-label">Klien Puas</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">99%</span>
            <span class="stat-label">Keberhasilan</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Marketplace Preview -->
<section class="marketplace-preview" id="marketplace">
  <div class="container">
    <div class="section-header">
      <span class="section-badge">Marketplace</span>
      <h2 class="section-title">Alat & Perlengkapan <span class="highlight">Pertanian</span></h2>
      <p class="section-subtitle">Temukan berbagai alat pertanian modern dengan kualitas terbaik untuk meningkatkan produktivitas Anda</p>
    </div>

    <div class="marketplace-grid">
      <?php foreach ($products as $product): ?>
        <div class="product-card">
          <div class="product-img" style="background-image:url('<?php echo $product['image_url']; ?>')">
            <div class="product-badge"><?php echo $product['category']; ?></div>
          </div>
          <div class="product-content">
            <h3 class="product-title"><?php echo $product['name']; ?></h3>
            <p class="product-description"><?php echo $product['description']; ?></p>
            <div class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></div>
            <div class="product-meta">
              <div class="product-rating">
                <i class="fas fa-star"></i> <?php echo $product['rating']; ?> (<?php echo $product['reviews']; ?> ulasan)
              </div>
            </div>
            <div class="product-actions">
              <?php
                // Siapkan pesan WhatsApp
                $product_name = urlencode($product['name']);
                $product_price = number_format($product['price'], 0, ',', '.');
                $category = urlencode($product['category']);
                $whatsapp_number = $product['whatsapp']; // Menggunakan nomor WhatsApp dari data produk
                
                // Buat pesan WhatsApp
                $whatsapp_message = "Halo, saya tertarik dengan produk berikut di Niseva Agro:%0A%0A";
                $whatsapp_message .= "*Produk:* " . $product_name . "%0A";
                $whatsapp_message .= "*Kategori:* " . $category . "%0A";
                $whatsapp_message .= "*Harga:* Rp " . $product_price . "%0A%0A";
                $whatsapp_message .= "Bisa info lebih lanjut tentang produk ini?";
                
                // Buat URL WhatsApp
                $whatsapp_url = "https://wa.me/" . $whatsapp_number . "?text=" . $whatsapp_message;
              ?>
              <a href="<?php echo $whatsapp_url; ?>" 
                 target="_blank" 
                 class="btn btn-whatsapp">
                <i class="fab fa-whatsapp"></i> Chat via WhatsApp
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <div class="section-cta">
      <a href="marketplace.html" class="btn btn-primary">Lihat Selengkapnya <i class="fas fa-arrow-right"></i></a>
    </div>
  </div>
</section>

<!-- Investment Preview -->
<section class="investment-preview" id="investasi">
  <div class="container">
    <div class="section-header">
      <span class="section-badge">Investasi Pertanian</span>
      <h2 class="section-title">Peluang <span class="highlight">Investasi Menguntungkan</span></h2>
      <p class="section-subtitle">Berinvestasi dalam proyek pertanian berkelanjutan dengan return yang menarik</p>
    </div>

    <div class="investment-grid">
      <?php foreach ($investments as $investment): ?>
        <div class="investment-card">
          <div class="investment-badge">Bunga <?php echo $investment['interest_rate']; ?>%</div>
          <div class="investment-header">
            <h3 class="investment-title"><?php echo $investment['title']; ?></h3>
            <p class="investment-description"><?php echo $investment['description']; ?></p>
            <div class="investment-location">
              <i class="fas fa-map-marker-alt"></i>
              <span><?php echo $investment['location']; ?></span>
            </div>
          </div>
          <div class="investment-body">
            <div class="investment-progress">
              <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $investment['progress_percent']; ?>%"></div>
              </div>
              <div class="progress-text">
                <span>Terkumpul: <span class="progress-percent"><?php echo $investment['progress_percent']; ?>%</span></span>
                <span>Rp <?php echo number_format($investment['collected_amount'], 0, ',', '.'); ?></span>
              </div>
            </div>
            <div class="investment-details">
              <div class="detail-item">
                <div class="detail-label">Tenor</div>
                <div class="detail-value"><?php echo $investment['tenor']; ?> Bulan</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value"><?php echo $investment['status']; ?></div>
              </div>
            </div>
          </div>
          <div class="investment-footer">
            <div class="investment-total">Rp <?php echo number_format($investment['total_amount'], 0, ',', '.'); ?></div>
          <a href="investasi.html?id=<?php echo $investment['id']; ?>" class="btn btn-outline">Lihat Detail</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <div class="section-cta">
      <a href="investasi.html" class="btn btn-primary">Lihat Selengkapnya <i class="fas fa-arrow-right"></i></a>
    </div>
  </div>
</section>

<!-- Artikel -->
<section class="articles" id="articles">
  <div class="container">
    <div class="section-header">
      <span class="section-badge">Artikel & Berita</span>
      <h2 class="section-title">Wawasan <span class="highlight">Pertanian</span></h2>
      <p class="section-subtitle">Update terbaru seputar teknologi pertanian, tips, dan informasi terkini</p>
    </div>

    <div class="articles-grid">
      <?php foreach ($articles as $article): ?>
        <div class="article-card">
          <div class="article-img" style="background-image:url('<?php echo $article['image_url']; ?>')"></div>
          <div class="article-content">
            <h3><?php echo $article['title']; ?></h3>
            <p><?php echo $article['excerpt']; ?></p>
            <div class="article-meta">
              <div class="author">
                <i class="fas fa-user"></i>
                <span><?php echo $article['author']; ?></span>
              </div>
              <div class="date">
                <i class="fas fa-calendar"></i>
                <span><?php echo $article['created_at']; ?></span>
              </div>
            </div>
           <a href="article_detail.html?id=<?php echo $article['id']; ?>" class="article-link">
    Baca Selengkapnya <i class="fas fa-arrow-right"></i>
</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="section-cta">
      <a href="articlel.html" class="btn btn-primary">Lihat Selengkapnya <i class="fas fa-arrow-right"></i></a>
    </div>

  </div>
</section>


<!-- Footer -->
<footer class="footer" id="contact">
  <div class="containerft">
    <div class="footer-content">
      <div class="footer-section">
        <div class="footer-logo">
          <div class="logo-icon" style="width: 40px; height: 40px; margin-right: 10px;">NSV</div>
          <span style="font-size: 1.3rem; font-weight: bold;">Niseva Agro</span>
        </div>
        <p class="footer-description">
          Memimpin revolusi pertanian Indonesia melalui inovasi teknologi dan komitmen terhadap keberlanjutan.
        </p>
        <div class="social-links">
          <a href="https://www.facebook.com/" target="_blank" class="social-link" title="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="https://twitter.com/" target="_blank" class="social-link" title="Twitter">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="https://www.instagram.com/" target="_blank" class="social-link" title="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://www.linkedin.com/" target="_blank" class="social-link" title="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
          </a>
          <a href="https://www.youtube.com/" target="_blank" class="social-link" title="YouTube">
            <i class="fab fa-youtube"></i>
          </a>
          <a href="https://wa.me/<?php echo $whatsapp_number; ?>" target="_blank" class="social-link" title="WhatsApp" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);">
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
      </div>

      <div class="footer-section">
        <h4>Layanan</h4>
        <ul class="footer-links">
          <li><a href="#marketplace"><i class="fas fa-chevron-right"></i> Marketplace</a></li>
          <li><a href="#investasi"><i class="fas fa-chevron-right"></i> Investasi Pertanian</a></li>
          <li><a href="#articles"><i class="fas fa-chevron-right"></i> Artikel </a></li>
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
            <span>Jl. Sigura-gura, Malang</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-phone"></i>
            <span>+62 21 1234 5678</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <span>contac@nisevaagro.com</span>
          </div>
          <div class="contact-item">
            <i class="fab fa-whatsapp"></i>
            <span>
              <a href="https://wa.me/<?php echo $whatsapp_number; ?>" 
                 target="_blank" 
                 style="color: rgba(255, 255, 255, 0.7); text-decoration: none;">
                +62 812-3456-7890
              </a>
            </span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="footer-bottom">
      <p class="copyright">
        © 2024 Niseva Agro. All Rights Reserved. | Developed with <i class="fas fa-heart"></i> Naila Sefina Nikita
      </p>
    </div>
  </div>
</footer>
<script>
  // Mobile Menu Toggle
  function toggleMobileMenu() {
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.toggle('active');
  }

  // Close mobile menu when clicking outside
  document.addEventListener('click', function(event) {
    const navMenu = document.getElementById('navMenu');
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    
    if (!navMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
      if (window.innerWidth <= 768) {
        navMenu.classList.remove('active');
      }
    }
  });

  // Handle window resize
  window.addEventListener('resize', function() {
    const navMenu = document.getElementById('navMenu');
    if (window.innerWidth > 768) {
      navMenu.style.display = 'flex';
      navMenu.classList.remove('active');
    } else {
      navMenu.style.display = 'none';
    }
  });

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 80,
          behavior: 'smooth'
        });
        
        // Close mobile menu if open
        if (window.innerWidth <= 768) {
          document.getElementById('navMenu').classList.remove('active');
        }
      }
    });
  });

  // Initialize on page load
  window.addEventListener('load', function() {
    if (window.innerWidth > 768) {
      document.getElementById('navMenu').style.display = 'flex';
    }
  });
</script>
</body>
</html>