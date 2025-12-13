<?php
// update_profile.php
session_start();

// Konfigurasi database (sesuaikan dengan setting Anda)
$host = 'localhost';
$database = 'niseva';
$username = 'root';
$password = '';

// Koneksi database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Proses update profile jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $nama = htmlspecialchars(trim($_POST['nama']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telepon = htmlspecialchars(trim($_POST['telepon']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));
    $provinsi = htmlspecialchars(trim($_POST['provinsi']));
    $kota = htmlspecialchars(trim($_POST['kota']));
    $user_id = htmlspecialchars(trim($_POST['user_id'])); // ID tidak bisa diubah

    // Validasi data
    $errors = [];

    if (empty($nama)) {
        $errors[] = "Nama lengkap harus diisi";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }

    if (empty($telepon)) {
        $errors[] = "Nomor telepon harus diisi";
    }

    if (empty($alamat)) {
        $errors[] = "Alamat harus diisi";
    }

    // Jika tidak ada error, update data
    if (empty($errors)) {
        try {
            // Gunakan prepared statements untuk keamanan
            $sql = "UPDATE users SET 
                    nama = :nama, 
                    email = :email, 
                    telepon = :telepon, 
                    alamat = :alamat, 
                    provinsi = :provinsi, 
                    kota = :kota, 
                    updated_at = NOW() 
                    WHERE user_id = :user_id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nama' => $nama,
                ':email' => $email,
                ':telepon' => $telepon,
                ':alamat' => $alamat,
                ':provinsi' => $provinsi,
                ':kota' => $kota,
                ':user_id' => $user_id
            ]);

            // Cek apakah ada baris yang diupdate
            if ($stmt->rowCount() > 0) {
                // Set session success message
                $_SESSION['success_message'] = "Profile berhasil diperbarui!";
                
                // Update session data jika menggunakan session untuk menyimpan info user
                $_SESSION['user_name'] = $nama;
                $_SESSION['user_email'] = $email;
                
                // Redirect kembali ke halaman profile dengan parameter success
                header("Location: ../public/profile.html?success=1");
                exit();
            } else {
                // Tidak ada perubahan data
                $_SESSION['info_message'] = "Tidak ada perubahan data.";
                header("Location: ../public/profile.html?success=1");
                exit();
            }

        } catch(PDOException $e) {
            $errors[] = "Terjadi kesalahan saat menyimpan data: " . $e->getMessage();
            
            // Log error untuk debugging (dalam production, simpan ke file log)
            error_log("Database Error: " . $e->getMessage());
            
            // Redirect dengan error message
            $errorMessage = urlencode("Terjadi kesalahan sistem. Silakan coba lagi.");
            header("Location: ../public/profile.html?error=" . $errorMessage);
            exit();
        }
    }

    // Jika ada errors, redirect dengan error message
    if (!empty($errors)) {
        $errorMessage = urlencode(implode(", ", $errors));
        header("Location: ../public/profile.html?error=" . $errorMessage);
        exit();
    }
} else {
    // Jika bukan POST request, redirect ke profile
    header("Location: ../public/profile.html");
    exit();
}
?>