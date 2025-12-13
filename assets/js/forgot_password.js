// DOM Elements
const step1 = document.getElementById('step1');
const step2 = document.getElementById('step2');
const step3 = document.getElementById('step3');
const step1Content = document.getElementById('step1-content');
const step2Content = document.getElementById('step2-content');
const step3Content = document.getElementById('step3-content');
const message = document.getElementById('message');
const sendCodeBtn = document.getElementById('sendCodeBtn');
const verifyCodeBtn = document.getElementById('verifyCodeBtn');
const resetPasswordBtn = document.getElementById('resetPasswordBtn');
const resendLink = document.getElementById('resendLink');
const countdown = document.getElementById('countdown');
const backToStep1 = document.getElementById('backToStep1');
const backToStep2 = document.getElementById('backToStep2');

// Variables
let verificationCode = '';
let countdownTimer;
let countdownSeconds = 60;
let userEmail = '';

// Password Reset API Class
class PasswordResetAPI {
    static async sendVerificationCode(email) {
        try {
            const response = await fetch('../controllers/PassworsReset_controller.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'send_code',
                    email: email
                })
            });
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            return { success: false, message: 'Terjadi kesalahan jaringan' };
        }
    }

    static async verifyCode(email, code) {
        try {
            const response = await fetch('../backend/controllers/PasswordResetController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'verify_code',
                    email: email,
                    code: code
                })
            });
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            return { success: false, message: 'Terjadi kesalahan jaringan' };
        }
    }

    static async resetPassword(email, newPassword, token) {
        try {
            const response = await fetch('../backend/controllers/PasswordResetController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'reset_password',
                    email: email,
                    password: newPassword,
                    token: token
                })
            });
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            return { success: false, message: 'Terjadi kesalahan jaringan' };
        }
    }
}

// Utility Functions
class PasswordResetUtils {
    // Show message function
    static showMessage(text, type) {
        message.textContent = text;
        message.className = 'message ' + type;
        message.style.display = 'block';
        
        // Auto hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                message.style.display = 'none';
            }, 5000);
        }
    }

    // Generate random verification code (for demo purposes)
    static generateVerificationCode() {
        return Math.floor(100000 + Math.random() * 900000).toString();
    }

    // Start countdown for resend code
    static startCountdown() {
        countdownSeconds = 60;
        resendLink.style.pointerEvents = 'none';
        resendLink.style.opacity = '0.5';
        
        countdownTimer = setInterval(() => {
            countdownSeconds--;
            countdown.textContent = `(dapat dikirim ulang dalam ${countdownSeconds} detik)`;
            
            if (countdownSeconds <= 0) {
                clearInterval(countdownTimer);
                resendLink.style.pointerEvents = 'auto';
                resendLink.style.opacity = '1';
                countdown.textContent = '';
            }
        }, 1000);
    }

    // Validate email format
    static isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Validate password strength
    static isStrongPassword(password) {
        // At least 8 characters, with at least one uppercase, one lowercase, one number and one special character
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return passwordRegex.test(password);
    }

    // Set loading state for button
    static setButtonLoading(button, isLoading, originalText) {
        if (isLoading) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        } else {
            button.disabled = false;
            button.textContent = originalText;
        }
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Send verification code
    sendCodeBtn.addEventListener('click', handleSendCode);
    
    // Verify code
    verifyCodeBtn.addEventListener('click', handleVerifyCode);
    
    // Reset password
    resetPasswordBtn.addEventListener('click', handleResetPassword);
    
    // Resend code
    resendLink.addEventListener('click', handleResendCode);
    
    // Back navigation
    backToStep1.addEventListener('click', handleBackToStep1);
    backToStep2.addEventListener('click', handleBackToStep2);
    
    // Enter key support
    setupEnterKeySupport();
});

// Event Handlers
async function handleSendCode() {
    const email = document.getElementById('email').value.trim();
    
    if (!email) {
        PasswordResetUtils.showMessage('Harap masukkan alamat email', 'error');
        return;
    }
    
    if (!PasswordResetUtils.isValidEmail(email)) {
        PasswordResetUtils.showMessage('Format email tidak valid', 'error');
        return;
    }
    
    // Show loading state
    const originalText = sendCodeBtn.textContent;
    PasswordResetUtils.setButtonLoading(sendCodeBtn, true, originalText);
    
    try {
        const result = await PasswordResetAPI.sendVerificationCode(email);
        
        if (result.success) {
            // Store email for later use
            userEmail = email;
            
            // Move to step 2
            step1.classList.remove('active');
            step1.classList.add('completed');
            step2.classList.add('active');
            step1Content.classList.remove('active');
            step2Content.classList.add('active');
            
            PasswordResetUtils.showMessage(result.message || 'Kode verifikasi telah dikirim ke email Anda', 'success');
            PasswordResetUtils.startCountdown();
        } else {
            PasswordResetUtils.showMessage(result.message || 'Terjadi kesalahan saat mengirim kode', 'error');
        }
    } catch (error) {
        PasswordResetUtils.showMessage('Terjadi kesalahan saat mengirim kode. Silakan coba lagi.', 'error');
    } finally {
        // Reset button state
        PasswordResetUtils.setButtonLoading(sendCodeBtn, false, originalText);
    }
}

async function handleVerifyCode() {
    const code = document.getElementById('code').value.trim();
    
    if (!code) {
        PasswordResetUtils.showMessage('Harap masukkan kode verifikasi', 'error');
        return;
    }
    
    // Show loading state
    const originalText = verifyCodeBtn.textContent;
    PasswordResetUtils.setButtonLoading(verifyCodeBtn, true, originalText);
    
    try {
        const result = await PasswordResetAPI.verifyCode(userEmail, code);
        
        if (result.success) {
            // Store verification token for password reset
            window.verificationToken = result.token;
            
            // Move to step 3
            step2.classList.remove('active');
            step2.classList.add('completed');
            step3.classList.add('active');
            step2Content.classList.remove('active');
            step3Content.classList.add('active');
            
            PasswordResetUtils.showMessage(result.message || 'Kode verifikasi berhasil. Silakan buat password baru.', 'success');
            clearInterval(countdownTimer);
        } else {
            PasswordResetUtils.showMessage(result.message || 'Kode verifikasi tidak valid', 'error');
        }
    } catch (error) {
        PasswordResetUtils.showMessage('Terjadi kesalahan saat memverifikasi kode', 'error');
    } finally {
        PasswordResetUtils.setButtonLoading(verifyCodeBtn, false, originalText);
    }
}

async function handleResetPassword() {
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (!newPassword || !confirmPassword) {
        PasswordResetUtils.showMessage('Harap isi semua field password', 'error');
        return;
    }
    
    if (!PasswordResetUtils.isStrongPassword(newPassword)) {
        PasswordResetUtils.showMessage('Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, dan karakter khusus', 'error');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        PasswordResetUtils.showMessage('Konfirmasi password tidak cocok', 'error');
        return;
    }
    
    // Show loading state
    const originalText = resetPasswordBtn.textContent;
    PasswordResetUtils.setButtonLoading(resetPasswordBtn, true, originalText);
    
    try {
        const result = await PasswordResetAPI.resetPassword(userEmail, newPassword, window.verificationToken);
        
        if (result.success) {
            PasswordResetUtils.showMessage(result.message || 'Password berhasil direset! Anda akan diarahkan ke halaman login.', 'success');
            
            // Redirect to login page after 3 seconds
            setTimeout(() => {
                window.location.href = 'login.html';
            }, 3000);
        } else {
            PasswordResetUtils.showMessage(result.message || 'Gagal mereset password', 'error');
        }
    } catch (error) {
        PasswordResetUtils.showMessage('Terjadi kesalahan saat mereset password', 'error');
    } finally {
        PasswordResetUtils.setButtonLoading(resetPasswordBtn, false, originalText);
    }
}

async function handleResendCode() {
    if (!userEmail) return;
    
    // Show loading state
    const originalText = resendLink.textContent;
    resendLink.textContent = 'Mengirim ulang...';
    resendLink.style.pointerEvents = 'none';
    
    try {
        const result = await PasswordResetAPI.sendVerificationCode(userEmail);
        
        if (result.success) {
            PasswordResetUtils.showMessage(result.message || 'Kode verifikasi baru telah dikirim ke email Anda', 'success');
            PasswordResetUtils.startCountdown();
        } else {
            PasswordResetUtils.showMessage(result.message || 'Gagal mengirim kode ulang', 'error');
        }
    } catch (error) {
        PasswordResetUtils.showMessage('Terjadi kesalahan saat mengirim kode ulang', 'error');
    } finally {
        resendLink.textContent = originalText;
        resendLink.style.pointerEvents = 'auto';
    }
}

function handleBackToStep1() {
    step2.classList.remove('active');
    step1.classList.add('active');
    step2Content.classList.remove('active');
    step1Content.classList.add('active');
    clearInterval(countdownTimer);
    resendLink.style.pointerEvents = 'auto';
    resendLink.style.opacity = '1';
    countdown.textContent = '';
}

function handleBackToStep2() {
    step3.classList.remove('active');
    step2.classList.add('active');
    step3Content.classList.remove('active');
    step2Content.classList.add('active');
}

function setupEnterKeySupport() {
    document.getElementById('email').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            handleSendCode();
        }
    });

    document.getElementById('code').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            handleVerifyCode();
        }
    });

    document.getElementById('confirmPassword').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            handleResetPassword();
        }
    });
}

// Export for testing purposes
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { PasswordResetAPI, PasswordResetUtils };
}