<?php
// process_login.php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Validasi
    $errors = [];

    if (empty($email) || empty($password)) {
        $errors[] = "Email dan password harus diisi!";
    }

    // Cek user di database
    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Login berhasil
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                // Redirect ke home.php
                header("Location: home.php");
                exit();
            } else {
                $errors[] = "Password salah!";
            }
        } else {
            $errors[] = "Email tidak ditemukan!";
        }
    }

    // Jika ada error
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['login_email'] = $email;
        header("Location: index.php?tab=login");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>