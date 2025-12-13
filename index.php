<?php
session_start();

// config.php
$host = "localhost";
$username = "root";
$password = "";
$database = "niseva_agro";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Inisialisasi variabel
$login_error = "";
$signup_success = "";
$signup_error = "";
$last_email = "";

// Cek login via cookie (auto login) - Dijalankan sebelum pemrosesan form
if (!isset($_SESSION['logged_in']) && isset($_COOKIE['remember_token']) && isset($_COOKIE['user_email'])) {
    $token = $_COOKIE['remember_token'];
    $email = $_COOKIE['user_email'];
    
    $sql = "SELECT * FROM users WHERE email = ? AND remember_token = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            
            $stmt->close();
            
            // Redirect ke home.php
            header("Location: home.php");
            exit();
        }
        $stmt->close();
    }
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    // Validasi
    if (empty($email) || empty($password)) {
        $login_error = "Email dan password harus diisi!";
    } else {
        // Cek user di database dengan prepared statement
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                
                // Verifikasi password
                if (password_verify($password, $user['password'])) {
                    // Login berhasil
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['logged_in'] = true;

                    // SET COOKIES jika remember me dicentang
                    if ($remember_me) {
                        // Buat token random untuk cookie
                        $token = bin2hex(random_bytes(32));
                        $expiry = time() + (30 * 24 * 60 * 60); // 30 hari
                        
                        // Simpan token ke database
                        $update_token = "UPDATE users SET remember_token = ? WHERE id = ?";
                        $stmt_token = $conn->prepare($update_token);
                        
                        if ($stmt_token) {
                            $stmt_token->bind_param("si", $token, $user['id']);
                            $stmt_token->execute();
                            $stmt_token->close();
                        }
                        
                        // Set cookie
                        setcookie('remember_token', $token, $expiry, "/", "", false, true);
                        setcookie('user_email', $user['email'], $expiry, "/", "", false, true);
                    }

                    $stmt->close();
                    
                    // Redirect ke home.php
                    header("Location: home.php");
                    exit();
                } else {
                    $login_error = "Password salah!";
                }
            } else {
                $login_error = "Email tidak ditemukan!";
            }
            $stmt->close();
        } else {
            $login_error = "Terjadi kesalahan sistem. Silakan coba lagi.";
        }
    }
}

// Proses signup
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    // Mengambil data dari form
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $agree_terms = isset($_POST['agree_terms']);

    // Validasi
    $errors = [];

    // Validasi field tidak kosong
    if (empty($first_name)) {
        $errors[] = "Nama depan harus diisi!";
    }
    
    if (empty($last_name)) {
        $errors[] = "Nama belakang harus diisi!";
    }
    
    if (empty($email)) {
        $errors[] = "Email harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid!";
    }
    
    if (empty($phone)) {
        $errors[] = "Nomor telepon harus diisi!";
    }

    // Cek apakah email sudah terdaftar menggunakan prepared statement
    if (empty($errors)) {
        $check_email = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_email);
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors[] = "Email sudah terdaftar!";
            }
            $stmt->close();
        }
    }

    // Validasi password
    if (empty($password)) {
        $errors[] = "Password harus diisi!";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password minimal 8 karakter!";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Password dan konfirmasi password tidak cocok!";
    }

    if (!$agree_terms) {
        $errors[] = "Anda harus menyetujui Syarat & Ketentuan!";
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query insert untuk menambahkan pengguna baru
        $sql = "INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                $signup_success = "Pendaftaran berhasil! Silakan login dengan akun yang baru dibuat.";
                
                // Set cookie untuk auto-fill email di form login
                setcookie('last_registered_email', $email, time() + (60 * 60), "/", "", false, true); // 1 jam
                
                // Simpan email untuk auto-fill
                $last_email = $email;
            } else {
                $errors[] = "Terjadi kesalahan saat pendaftaran: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Terjadi kesalahan sistem. Silakan coba lagi.";
        }
    }

    // Jika ada error, gabungkan pesan error
    if (!empty($errors)) {
        $signup_error = implode("<br>", $errors);
    }
}

// Auto-fill email dari cookie jika ada (dan tidak ada error signup)
if (empty($last_email) && isset($_COOKIE['last_registered_email'])) {
    $last_email = $_COOKIE['last_registered_email'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niseva Agro - Login & Daftar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Variabel Warna */
        :root {
            --cream: #F8F9FA;
            --light-green: #E8F5E8;
            --medium-green: #2E8B57;
            --dark-green: #1A5D1A;
            --accent: #FF6B35;
            --text-dark: #2D3748;
            --text-light: #ffffff;
            --shadow: rgba(0, 0, 0, 0.15);
        }

        /* Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--light-green) 0%, var(--cream) 100%);
            color: var(--text-dark);
            line-height: 1.6;
            display: flex;
            min-height: 100vh;
        }

        /* Layout Dua Kolom */
        .auth-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
            box-shadow: 0 0 40px var(--shadow);
        }

        /* Kolom Kiri - Gambar dan Informasi */
        .info-column {
            flex: 1.2;
            background: linear-gradient(rgba(26, 93, 26, 0.85), rgba(46, 139, 87, 0.9)), 
                        url('folderimage/login.jpg') no-repeat center center/cover;
            color: var(--text-light);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .info-column::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
            opacity: 0.1;
            z-index: 0;
        }

        .info-content {
            position: relative;
            z-index: 1;
        }

        .logo {
            font-size: 2.8rem;
            font-weight: bold;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--text-light);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .info-column h1 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            line-height: 1.3;
            font-weight: 700;
        }

        .info-column p {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
            max-width: 500px;
        }

        .features {
            margin: 2.5rem 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.2rem;
            font-size: 1.1rem;
            padding: 0.8rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .feature-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.2rem;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(255, 107, 53, 0.4);
        }

        .stats {
            display: flex;
            margin-top: 3rem;
            padding-top: 2.5rem;
            border-top: 2px solid rgba(255, 255, 255, 0.3);
        }

        .stat-item {
            flex: 1;
            text-align: center;
            padding: 1rem;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: bold;
            display: block;
            color: var(--accent);
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }

        /* Kolom Kanan - Form Auth */
        .form-column {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: var(--cream);
            overflow-y: auto;
        }

        .auth-box {
            background-color: var(--text-light);
            border-radius: 20px;
            box-shadow: 0 15px 40px var(--shadow);
            padding: 3rem;
            width: 100%;
            max-width: 500px;
            border: 1px solid rgba(46, 139, 87, 0.1);
        }

        .auth-header {
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .auth-header h2 {
            color: var(--dark-green);
            font-size: 2.2rem;
            margin-bottom: 0.8rem;
            font-weight: 700;
        }

        .auth-header p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Tabs */
        .auth-tabs {
            display: flex;
            margin-bottom: 2rem;
            border-bottom: 2px solid #E2E8F0;
        }

        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            color: #718096;
        }

        .auth-tab.active {
            color: var(--medium-green);
            border-bottom: 3px solid var(--medium-green);
        }

        .auth-tab:hover {
            color: var(--dark-green);
        }

        /* Form Styles */
        .auth-form {
            display: none;
        }

        .auth-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--medium-green);
            font-size: 1rem;
            z-index: 2;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            border: 2px solid #E2E8F0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: var(--text-light);
            position: relative;
            z-index: 1;
        }

        .form-control:focus {
            border-color: var(--medium-green);
            outline: none;
            box-shadow: 0 0 0 3px rgba(46, 139, 87, 0.15);
        }

        .form-control:focus + i {
            color: var(--dark-green);
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }

        .strength-bar {
            height: 4px;
            background: #E2E8F0;
            border-radius: 2px;
            margin-top: 0.3rem;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: #E53E3E;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .strength-fill.medium {
            background: #D69E2E;
        }

        .strength-fill.strong {
            background: #38A169;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 0.8rem;
            accent-color: var(--medium-green);
            transform: scale(1.1);
        }

        .remember-me label {
            font-weight: 500;
            cursor: pointer;
        }

        .forgot-password {
            color: var(--medium-green);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .forgot-password:hover {
            color: var(--dark-green);
            text-decoration: underline;
        }

        .terms-agreement {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .terms-agreement input {
            margin-right: 0.8rem;
            accent-color: var(--medium-green);
            transform: scale(1.1);
            margin-top: 0.2rem;
        }

        .terms-agreement label {
            font-weight: 500;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .terms-agreement a {
            color: var(--medium-green);
            text-decoration: none;
            font-weight: 600;
        }

        .terms-agreement a:hover {
            text-decoration: underline;
        }

        /* Button tanpa gradasi */
        .btn {
            display: inline-block;
            background-color: var(--medium-green);
            color: var(--text-light);
            padding: 1.1rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 15px rgba(46, 139, 87, 0.4);
            letter-spacing: 0.5px;
        }

        .btn:hover {
            background-color: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 139, 87, 0.5);
        }

        .btn:active {
            transform: translateY(0);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.95rem;
        }

        .auth-footer a {
            color: var(--medium-green);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.3s;
            margin-left: 0.3rem;
        }

        .auth-footer a:hover {
            color: var(--dark-green);
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-error {
            background-color: #fed7d7;
            color: #c53030;
            border: 1px solid #feb2b2;
        }

        .alert-success {
            background-color: #c6f6d5;
            color: #276749;
            border: 1px solid #9ae6b4;
        }

        /* Animasi */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-box {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .auth-container {
                flex-direction: column;
            }
            
            .info-column {
                padding: 2.5rem;
                text-align: center;
            }
            
            .stats {
                justify-content: center;
            }
            
            .stat-item {
                flex: none;
                margin: 0 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .info-column, .form-column {
                padding: 2rem;
            }
            
            .auth-box {
                padding: 2rem;
            }
            
            .logo {
                font-size: 2.2rem;
            }
            
            .info-column h1 {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .info-column, .form-column {
                padding: 1.5rem;
            }
            
            .auth-box {
                padding: 1.5rem;
            }
            
            .auth-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Kolom Kiri - Informasi -->
        <div class="info-column">
            <div class="info-content">
                <div class="logo">
                    <img src="folderimage/logo.png" alt="Profile" class="logo-img">
                    <span>Niseva Agro</span>
                </div>
                
                <h1>Bergabung dengan Komunitas Petani Muda</h1>
                <p>Akses ribuan proyek pertanian menarik dan mulai berinvestasi dengan modal kecil. Bersama wujudkan pertanian Indonesia yang lebih baik.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Akses ke proyek pertanian terverifikasi</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span>Return investasi hingga 14.5% per tahun</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span>Dampingan dari ahli pertanian</span>
                    </div>
                </div>
                
                <div class="stats">
                    <div class="stat-item">
                        <span class="stat-value">Rp 5M+</span>
                        <span class="stat-label">Dana Tersalurkan</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">2K+</span>
                        <span class="stat-label">Petani Terdaftar</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Kolom Kanan - Form Auth -->
        <div class="form-column">
            <div class="auth-box">
                <div class="auth-tabs">
                    <div class="auth-tab <?php echo (!isset($_POST['signup']) || !empty($signup_success)) ? 'active' : ''; ?>" data-tab="login">Masuk</div>
                    <div class="auth-tab <?php echo (isset($_POST['signup']) && empty($signup_success)) ? 'active' : ''; ?>" data-tab="signup">Daftar</div>
                </div>

                <!-- Form Login -->
                <form class="auth-form <?php echo (!isset($_POST['signup']) || !empty($signup_success)) ? 'active' : ''; ?>" id="login-form" method="POST">
                    <div class="auth-header">
                        <h2>Selamat Datang Kembali</h2>
                        <p>Masuk ke akun Anda untuk melanjutkan</p>
                    </div>

                    <?php if (!empty($login_error)): ?>
                        <div class="alert alert-error">
                            <?php echo htmlspecialchars($login_error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($signup_success)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($signup_success); ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <div class="input-with-icon">
                            <input type="email" id="login-email" name="email" class="form-control" placeholder="nama@email.com" required value="<?php echo isset($_POST['login']) && isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ($last_email ? htmlspecialchars($last_email) : ''); ?>">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <div class="input-with-icon">
                            <input type="password" id="login-password" name="password" class="form-control" placeholder="Masukkan password" required>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember-me" name="remember_me" <?php echo isset($_COOKIE['remember_token']) ? 'checked' : ''; ?>>
                            <label for="remember-me">Ingat saya</label>
                        </div>
                        <a href="forgotpassword.php" class="forgot-password">Lupa password?</a>
                    </div>

                    <button type="submit" name="login" class="btn">Masuk</button>

                    <div class="auth-footer">
                        <p>Belum punya akun? <a href="#" class="switch-tab" data-tab="signup">Daftar di sini</a></p>
                    </div>
                </form>

                <!-- Form Sign Up -->
                <form class="auth-form <?php echo (isset($_POST['signup']) && empty($signup_success)) ? 'active' : ''; ?>" id="signup-form" method="POST">
                    <div class="auth-header">
                        <h2>Buat Akun Baru</h2>
                        <p>Isi data diri Anda untuk bergabung dengan Niseva Agro</p>
                    </div>

                    <?php if (!empty($signup_error)): ?>
                        <div class="alert alert-error">
                            <?php echo $signup_error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first-name">Nama Depan</label>
                            <div class="input-with-icon">
                                <input type="text" id="first-name" name="first_name" class="form-control" placeholder="Nama depan" required value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Nama Belakang</label>
                            <div class="input-with-icon">
                                <input type="text" id="last-name" name="last_name" class="form-control" placeholder="Nama belakang" required value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="signup-email">Email</label>
                        <div class="input-with-icon">
                            <input type="email" id="signup-email" name="email" class="form-control" placeholder="nama@email.com" required value="<?php echo isset($_POST['signup']) && isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="signup-phone">Nomor Telepon</label>
                        <div class="input-with-icon">
                            <input type="tel" id="signup-phone" name="phone" class="form-control" placeholder="08xxxxxxxxxx" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <div class="input-with-icon">
                            <input type="password" id="signup-password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="password-strength">
                            <span id="strength-text">Kekuatan password</span>
                            <div class="strength-bar">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Konfirmasi Password</label>
                        <div class="input-with-icon">
                            <input type="password" id="confirm-password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                            <i class="fas fa-lock"></i>
                        </div>
                        <div id="password-match" style="font-size: 0.85rem; margin-top: 0.3rem;"></div>
                    </div>

                    <div class="terms-agreement">
                        <input type="checkbox" id="agree-terms" name="agree_terms" required <?php echo isset($_POST['agree_terms']) ? 'checked' : ''; ?>>
                        <label for="agree-terms">
                            Saya setuju dengan <a href="terms.php">Syarat & Ketentuan</a> dan <a href="privacy.php">Kebijakan Privasi</a> Niseva Agro
                        </label>
                    </div>

                    <button type="submit" name="signup" class="btn">Daftar Sekarang</button>

                    <div class="auth-footer">
                        <p>Sudah punya akun? <a href="#" class="switch-tab" data-tab="login">Masuk di sini</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tab System
        const authTabs = document.querySelectorAll('.auth-tab');
        const authForms = document.querySelectorAll('.auth-form');
        const switchLinks = document.querySelectorAll('.switch-tab');

        function switchTab(tabName) {
            // Update active tab
            authTabs.forEach(tab => {
                tab.classList.toggle('active', tab.getAttribute('data-tab') === tabName);
            });

            // Show corresponding form
            authForms.forEach(form => {
                form.classList.toggle('active', form.id === `${tabName}-form`);
            });
        }

        authTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                switchTab(tab.getAttribute('data-tab'));
            });
        });

        switchLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                switchTab(link.getAttribute('data-tab'));
            });
        });

        // Password strength indicator
        const passwordInput = document.getElementById('signup-password');
        const strengthFill = document.getElementById('strength-fill');
        const strengthText = document.getElementById('strength-text');
        const confirmPassword = document.getElementById('confirm-password');
        const passwordMatch = document.getElementById('password-match');

        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length >= 8) strength += 25;
                if (/[A-Z]/.test(password)) strength += 25;
                if (/[0-9]/.test(password)) strength += 25;
                if (/[^A-Za-z0-9]/.test(password)) strength += 25;
                
                strengthFill.style.width = strength + '%';
                
                if (strength < 50) {
                    strengthFill.className = 'strength-fill';
                    strengthText.textContent = 'Password lemah';
                    strengthText.style.color = '#E53E3E';
                } else if (strength < 75) {
                    strengthFill.className = 'strength-fill medium';
                    strengthText.textContent = 'Password cukup';
                    strengthText.style.color = '#D69E2E';
                } else {
                    strengthFill.className = 'strength-fill strong';
                    strengthText.textContent = 'Password kuat';
                    strengthText.style.color = '#38A169';
                }
            });
        }

        // Password confirmation check
        if (confirmPassword) {
            confirmPassword.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    passwordMatch.textContent = 'Password tidak cocok';
                    passwordMatch.style.color = '#E53E3E';
                } else {
                    passwordMatch.textContent = 'Password cocok';
                    passwordMatch.style.color = '#38A169';
                }
            });
        }

        // Pastikan ikon tetap terlihat saat input aktif
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                const icon = this.parentElement.querySelector('i');
                if (icon) {
                    icon.style.color = 'var(--dark-green)';
                }
            });
            
            input.addEventListener('blur', function() {
                const icon = this.parentElement.querySelector('i');
                if (icon) {
                    icon.style.color = 'var(--medium-green)';
                }
            });
        });
    </script>
</body>
</html>