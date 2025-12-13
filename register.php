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

// Proses registrasi
$signup_success = "";
$signup_error = "";
$form_data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    // Mengambil data dari form
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $agree_terms = isset($_POST['agree_terms']);

    // Simpan data form untuk auto-fill jika error
    $form_data = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone
    ];

    // Validasi
    $errors = [];

    // Cek apakah email sudah terdaftar
    $check_email = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors[] = "Email sudah terdaftar!";
    }

    // Validasi password
    if ($password !== $confirm_password) {
        $errors[] = "Password dan konfirmasi password tidak cocok!";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password minimal 8 karakter!";
    }

    if (!$agree_terms) {
        $errors[] = "Anda harus menyetujui Syarat & Ketentuan!";
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query insert untuk menambahkan pengguna baru
        $sql = "INSERT INTO users (first_name, last_name, email, phone, password) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $hashed_password);

        if ($stmt->execute()) {
            $signup_success = "Pendaftaran berhasil!";
            
            // Reset form data
            $form_data = [];
            
            // Set session untuk auto login setelah registrasi
            $_SESSION['temp_email'] = $email;
            $_SESSION['temp_password'] = $password;
            
            // Redirect ke halaman sukses atau login
            header("Refresh: 3; url=login.php");
        } else {
            $errors[] = "Terjadi kesalahan saat pendaftaran. Silakan coba lagi.";
        }
        $stmt->close();
    }

    // Jika ada error, gabungkan pesan error
    if (!empty($errors)) {
        $signup_error = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niseva Agro - Daftar Akun Baru</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
    <div class="auth-container">
        <!-- Kolom Kiri - Informasi -->
        <div class="info-column">
            <div class="info-content">
                <div class="logo">
                    <img src="assets/images/logo.png" alt="Niseva Agro Logo" class="logo-img">
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
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span>Keamanan data terjamin</span>
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
                    <div class="stat-item">
                        <span class="stat-value">98%</span>
                        <span class="stat-label">Kepuasan Pengguna</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Kolom Kanan - Form Registrasi -->
        <div class="form-column">
            <div class="auth-box">
                <div class="auth-header">
                    <h2>Buat Akun Baru</h2>
                    <p>Isi data diri Anda untuk bergabung dengan Niseva Agro</p>
                </div>

                <?php if (!empty($signup_error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $signup_error; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($signup_success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $signup_success; ?>
                        <p class="redirect-message">Anda akan diarahkan ke halaman login dalam 3 detik...</p>
                    </div>
                <?php endif; ?>

                <form class="auth-form" id="signup-form" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first-name">Nama Depan <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <input type="text" id="first-name" name="first_name" class="form-control" placeholder="Masukkan nama depan" required value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="form-hint">Contoh: Budi</div>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Nama Belakang <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <input type="text" id="last-name" name="last_name" class="form-control" placeholder="Masukkan nama belakang" required value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="form-hint">Contoh: Santoso</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="signup-email">Email <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <input type="email" id="signup-email" name="email" class="form-control" placeholder="nama@email.com" required value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="form-hint">Gunakan email aktif untuk verifikasi</div>
                    </div>

                    <div class="form-group">
                        <label for="signup-phone">Nomor Telepon <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <input type="tel" id="signup-phone" name="phone" class="form-control" placeholder="08xxxxxxxxxx" required pattern="[0-9]{10,13}" value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="form-hint">Format: 08xxxxxxxxxx (10-13 digit)</div>
                    </div>

                    <div class="form-group">
                        <label for="signup-password">Password <span class="required">*</span></label>
                        <div class="input-with-icon password-toggle">
                            <input type="password" id="signup-password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                            <i class="fas fa-lock"></i>
                            <button type="button" class="toggle-password" data-target="signup-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <span id="strength-text">Kekuatan password</span>
                            <div class="strength-bar">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                            <ul class="password-requirements">
                                <li id="req-length"><i class="fas fa-circle"></i> Minimal 8 karakter</li>
                                <li id="req-uppercase"><i class="fas fa-circle"></i> Huruf kapital</li>
                                <li id="req-number"><i class="fas fa-circle"></i> Angka</li>
                                <li id="req-special"><i class="fas fa-circle"></i> Karakter khusus</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Konfirmasi Password <span class="required">*</span></label>
                        <div class="input-with-icon password-toggle">
                            <input type="password" id="confirm-password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                            <i class="fas fa-lock"></i>
                            <button type="button" class="toggle-password" data-target="confirm-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="password-match" class="password-match"></div>
                    </div>

                    <div class="terms-agreement">
                        <input type="checkbox" id="agree-terms" name="agree_terms" required <?php echo isset($_POST['agree_terms']) ? 'checked' : ''; ?>>
                        <label for="agree-terms">
                            Saya setuju dengan <a href="terms.php" target="_blank">Syarat & Ketentuan</a> dan 
                            <a href="privacy.php" target="_blank">Kebijakan Privasi</a> Niseva Agro
                        </label>
                    </div>

                    <button type="submit" name="signup" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </button>

                    <div class="auth-footer">
                        <p>Sudah punya akun? <a href="login.php" class="login-link">Masuk di sini</a></p>
                        <p class="back-home">
                            <a href="index.php"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/register.js"></script>
</body>
</html>