<?php
// process_signup.php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi
    $errors = [];

    // Cek apakah email sudah terdaftar
    $check_email = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($check_email);
    
    if ($result->num_rows > 0) {
        $errors[] = "Email sudah terdaftar!";
    }

    // Validasi password
    if ($password !== $confirm_password) {
        $errors[] = "Password dan konfirmasi password tidak cocok!";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password minimal 8 karakter!";
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query insert
        $sql = "INSERT INTO users (first_name, last_name, email, phone, password) 
                VALUES ('$first_name', '$last_name', '$email', '$phone', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
            header("Location: index.php?tab=login");
            exit();
        } else {
            $errors[] = "Error: " . $conn->error;
        }
    }

    // Jika ada error, simpan di session dan redirect kembali
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: index.php?tab=signup");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>