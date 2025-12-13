<?php

require_once '../config/database.php';

class PasswordResetController {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
        if (!$this->db) {
            sendResponse(false, 'Database connection failed');
        }
    }
    
    /**
     * Send verification code to user's email
     */
    public function sendVerificationCode($email) {
        // Validate email
        if (!isValidEmail($email)) {
            return ['success' => false, 'message' => 'Format email tidak valid'];
        }
        
        // Check if user exists
        $user = $this->getUserByEmail($email);
        if (!$user) {
            // For security reasons, don't reveal if email exists or not
            return ['success' => true, 'message' => 'Jika email terdaftar, kode verifikasi akan dikirim'];
        }
        
        // Generate verification code
        $verificationCode = generateVerificationCode();
        $expiresAt = date('Y-m-d H:i:s', time() + VERIFICATION_CODE_EXPIRY);
        
        // Store verification code in database
        $stmt = $this->db->prepare("
            INSERT INTO password_reset_requests (email, verification_code, expires_at, created_at) 
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
            verification_code = ?, expires_at = ?, created_at = NOW()
        ");
        
        $stmt->bind_param("sssss", $email, $verificationCode, $expiresAt, $verificationCode, $expiresAt);
        
        if ($stmt->execute()) {
            // Send email (in production, implement proper email sending)
            $emailSent = $this->sendVerificationEmail($email, $verificationCode);
            
            if ($emailSent) {
                return ['success' => true, 'message' => 'Kode verifikasi telah dikirim ke email Anda'];
            } else {
                return ['success' => false, 'message' => 'Gagal mengirim email. Silakan coba lagi.'];
            }
        } else {
            error_log("Send verification code error: " . $stmt->error);
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'];
        }
    }
    
    /**
     * Verify the code entered by user
     */
    public function verifyCode($email, $code) {
        // Clean inputs
        $email = sanitizeInput($email);
        $code = sanitizeInput($code);
        
        $stmt = $this->db->prepare("
            SELECT id, expires_at 
            FROM password_reset_requests 
            WHERE email = ? AND verification_code = ? AND used = 0
        ");
        
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Kode verifikasi tidak valid'];
        }
        
        $request = $result->fetch_assoc();
        
        // Check if code has expired
        if (strtotime($request['expires_at']) < time()) {
            return ['success' => false, 'message' => 'Kode verifikasi telah kadaluarsa'];
        }
        
        // Generate reset token
        $resetToken = generateToken();
        $tokenExpires = date('Y-m-d H:i:s', time() + PASSWORD_RESET_TOKEN_EXPIRY);
        
        // Mark code as used and store reset token
        $stmt = $this->db->prepare("
            UPDATE password_reset_requests 
            SET used = 1, reset_token = ?, token_expires_at = ? 
            WHERE id = ?
        ");
        
        $stmt->bind_param("ssi", $resetToken, $tokenExpires, $request['id']);
        
        if ($stmt->execute()) {
            return [
                'success' => true, 
                'message' => 'Kode verifikasi berhasil',
                'token' => $resetToken
            ];
        } else {
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }
    
    /**
     * Reset user's password
     */
    public function resetPassword($email, $newPassword, $token) {
        // Validate token
        $validation = $this->validateResetToken($email, $token);
        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }
        
        // Validate password strength
        if (!$this->isStrongPassword($newPassword)) {
            return ['success' => false, 'message' => 'Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, dan karakter khusus'];
        }
        
        // Hash the new password
        $hashedPassword = hashPassword($newPassword);
        
        // Update user's password
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        
        if ($stmt->execute()) {
            // Invalidate all reset tokens for this email
            $this->invalidateResetTokens($email);
            
            return ['success' => true, 'message' => 'Password berhasil direset'];
        } else {
            error_log("Reset password error: " . $stmt->error);
            return ['success' => false, 'message' => 'Gagal mereset password. Silakan coba lagi.'];
        }
    }
    
    /**
     * Get user by email
     */
    private function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT id, email FROM users WHERE email = ? AND status = 'active'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    /**
     * Validate reset token
     */
    private function validateResetToken($email, $token) {
        $stmt = $this->db->prepare("
            SELECT id, token_expires_at 
            FROM password_reset_requests 
            WHERE email = ? AND reset_token = ? AND used = 1
        ");
        
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['valid' => false, 'message' => 'Token reset tidak valid'];
        }
        
        $request = $result->fetch_assoc();
        
        // Check if token has expired
        if (strtotime($request['token_expires_at']) < time()) {
            return ['valid' => false, 'message' => 'Token reset telah kadaluarsa'];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Invalidate all reset tokens for an email
     */
    private function invalidateResetTokens($email) {
        $stmt = $this->db->prepare("
            UPDATE password_reset_requests 
            SET reset_token = NULL, token_expires_at = NULL 
            WHERE email = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
    }
    
    /**
     * Validate password strength
     */
    private function isStrongPassword($password) {
        // At least 8 characters, with at least one uppercase, one lowercase, one number and one special character
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($passwordRegex, $password);
    }
    
    /**
     * Send verification email
     */
    private function sendVerificationEmail($email, $code) {
        // In production, implement proper email sending using PHPMailer or similar
        $subject = "Kode Verifikasi Reset Password - Niseva Agro";
        $message = "
        <html>
        <head>
            <title>Reset Password</title>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .code { font-size: 24px; font-weight: bold; color: #2E7D32; padding: 10px; background: #f5f5f5; text-align: center; margin: 20px 0; }
                .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Reset Password Niseva Agro</h2>
                <p>Anda telah meminta untuk mereset password Anda.</p>
                <p>Gunakan kode verifikasi berikut:</p>
                <div class='code'>{$code}</div>
                <p>Kode ini akan kadaluarsa dalam 15 menit.</p>
                <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
                <div class='footer'>
                    <p>Hormat kami,<br>Tim Niseva Agro</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . FROM_NAME . " <" . FROM_EMAIL . ">" . "\r\n";
        
        // For demo purposes, we'll just log the email
        error_log("Verification email to {$email}: {$code}");
        
        // In production, uncomment the line below
        // return mail($email, $subject, $message, $headers);
        
        return true; // Simulate success for demo
    }
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    
    $controller = new PasswordResetController();
    
    switch ($action) {
        case 'send_code':
            $email = $input['email'] ?? '';
            $result = $controller->sendVerificationCode($email);
            sendResponse($result['success'], $result['message']);
            break;
            
        case 'verify_code':
            $email = $input['email'] ?? '';
            $code = $input['code'] ?? '';
            $result = $controller->verifyCode($email, $code);
            sendResponse($result['success'], $result['message'], ['token' => $result['token'] ?? '']);
            break;
            
        case 'reset_password':
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';
            $token = $input['token'] ?? '';
            $result = $controller->resetPassword($email, $password, $token);
            sendResponse($result['success'], $result['message']);
            break;
            
        default:
            sendResponse(false, 'Aksi tidak valid');
    }
}

?>