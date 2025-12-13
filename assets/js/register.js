// ===== DOM Elements =====
const signupForm = document.getElementById('signup-form');
const passwordInput = document.getElementById('signup-password');
const confirmPasswordInput = document.getElementById('confirm-password');
const strengthFill = document.getElementById('strength-fill');
const strengthText = document.getElementById('strength-text');
const passwordMatch = document.getElementById('password-match');

// Password requirements elements
const reqLength = document.getElementById('req-length');
const reqUppercase = document.getElementById('req-uppercase');
const reqNumber = document.getElementById('req-number');
const reqSpecial = document.getElementById('req-special');

// ===== Password Strength Checker =====
function checkPasswordStrength(password) {
    let strength = 0;
    let messages = [];
    let className = 'weak';

    // Check length
    if (password.length >= 8) {
        strength += 25;
        reqLength.classList.add('valid');
        reqLength.classList.remove('invalid');
    } else {
        reqLength.classList.remove('valid');
        reqLength.classList.add('invalid');
    }

    // Check uppercase letters
    if (/[A-Z]/.test(password)) {
        strength += 25;
        reqUppercase.classList.add('valid');
        reqUppercase.classList.remove('invalid');
    } else {
        reqUppercase.classList.remove('valid');
        reqUppercase.classList.add('invalid');
    }

    // Check numbers
    if (/[0-9]/.test(password)) {
        strength += 25;
        reqNumber.classList.add('valid');
        reqNumber.classList.remove('invalid');
    } else {
        reqNumber.classList.remove('valid');
        reqNumber.classList.add('invalid');
    }

    // Check special characters
    if (/[^A-Za-z0-9]/.test(password)) {
        strength += 25;
        reqSpecial.classList.add('valid');
        reqSpecial.classList.remove('invalid');
    } else {
        reqSpecial.classList.remove('valid');
        reqSpecial.classList.add('invalid');
    }

    // Update strength bar and text
    strengthFill.style.width = strength + '%';
    
    if (strength < 50) {
        strengthFill.className = 'strength-fill weak';
        strengthText.textContent = 'Lemah';
        strengthText.style.color = '#E53E3E';
    } else if (strength < 75) {
        strengthFill.className = 'strength-fill fair';
        strengthText.textContent = 'Cukup';
        strengthText.style.color = '#D69E2E';
    } else if (strength < 100) {
        strengthFill.className = 'strength-fill good';
        strengthText.textContent = 'Baik';
        strengthText.style.color = '#38B2AC';
    } else {
        strengthFill.className = 'strength-fill strong';
        strengthText.textContent = 'Kuat';
        strengthText.style.color = '#38A169';
    }

    return strength;
}

// ===== Password Match Checker =====
function checkPasswordMatch() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    
    if (!password || !confirmPassword) {
        passwordMatch.textContent = '';
        passwordMatch.className = 'password-match';
        return false;
    }
    
    if (password === confirmPassword) {
        passwordMatch.textContent = '✓ Password cocok';
        passwordMatch.className = 'password-match match';
        confirmPasswordInput.classList.add('valid');
        confirmPasswordInput.classList.remove('invalid');
        return true;
    } else {
        passwordMatch.textContent = '✗ Password tidak cocok';
        passwordMatch.className = 'password-match mismatch';
        confirmPasswordInput.classList.remove('valid');
        confirmPasswordInput.classList.add('invalid');
        return false;
    }
}

// ===== Form Validation =====
function validateForm() {
    let isValid = true;
    const formElements = signupForm.elements;
    
    // Reset all validation states
    Array.from(formElements).forEach(element => {
        if (element.classList) {
            element.classList.remove('invalid', 'shake');
        }
    });
    
    // Check required fields
    const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'password', 'confirm_password'];
    requiredFields.forEach(fieldName => {
        const field = document.getElementsByName(fieldName)[0];
        if (field && !field.value.trim()) {
            field.classList.add('invalid', 'shake');
            isValid = false;
            
            // Remove shake animation after it completes
            setTimeout(() => {
                field.classList.remove('shake');
            }, 500);
        }
    });
    
    // Check password strength
    const password = passwordInput.value;
    if (password) {
        const strength = checkPasswordStrength(password);
        if (strength < 75) {
            passwordInput.classList.add('invalid');
            isValid = false;
        } else {
            passwordInput.classList.add('valid');
        }
    }
    
    // Check password match
    if (!checkPasswordMatch()) {
        isValid = false;
    }
    
    // Check terms agreement
    const termsCheckbox = document.getElementById('agree-terms');
    if (!termsCheckbox.checked) {
        termsCheckbox.parentElement.classList.add('shake');
        isValid = false;
        
        setTimeout(() => {
            termsCheckbox.parentElement.classList.remove('shake');
        }, 500);
    }
    
    // Check phone number format
    const phoneInput = document.getElementById('signup-phone');
    const phoneRegex = /^[0-9]{10,13}$/;
    if (phoneInput.value && !phoneRegex.test(phoneInput.value.replace(/\D/g, ''))) {
        phoneInput.classList.add('invalid', 'shake');
        isValid = false;
        
        setTimeout(() => {
            phoneInput.classList.remove('shake');
        }, 500);
    }
    
    // Check email format
    const emailInput = document.getElementById('signup-email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailInput.value && !emailRegex.test(emailInput.value)) {
        emailInput.classList.add('invalid', 'shake');
        isValid = false;
        
        setTimeout(() => {
            emailInput.classList.remove('shake');
        }, 500);
    }
    
    return isValid;
}

// ===== Toggle Password Visibility =====
function setupPasswordToggle() {
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                icon.className = 'fas fa-eye-slash';
                this.setAttribute('aria-label', 'Sembunyikan password');
            } else {
                targetInput.type = 'password';
                icon.className = 'fas fa-eye';
                this.setAttribute('aria-label', 'Tampilkan password');
            }
        });
    });
}

// ===== Input Formatting =====
function setupInputFormatting() {
    // Phone number formatting
    const phoneInput = document.getElementById('signup-phone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 13) {
            value = value.substring(0, 13);
        }
        e.target.value = value;
    });
    
    // Name formatting (capitalize first letter)
    const nameInputs = ['first-name', 'last-name'];
    nameInputs.forEach(id => {
        const input = document.getElementById(id);
        input.addEventListener('blur', function() {
            if (this.value) {
                this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();
            }
        });
    });
    
    // Email lowercase
    const emailInput = document.getElementById('signup-email');
    emailInput.addEventListener('blur', function() {
        if (this.value) {
            this.value = this.value.toLowerCase();
        }
    });
}

// ===== Form Submission =====
function handleFormSubmit(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        // Show error message
        const alertBox = document.querySelector('.alert-error');
        if (!alertBox) {
            const formHeader = document.querySelector('.auth-header');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-error';
            errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Harap perbaiki kesalahan pada form sebelum melanjutkan.';
            formHeader.parentNode.insertBefore(errorDiv, formHeader.nextSibling);
            
            // Scroll to error
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Remove error after 5 seconds
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.remove();
                }
            }, 5000);
        }
        
        return false;
    }
    
    // Disable submit button to prevent double submission
    const submitBtn = signupForm.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    
    // Show loading state
    signupForm.classList.add('loading');
    
    // Submit form after validation
    setTimeout(() => {
        signupForm.submit();
    }, 1000);
}

// ===== Initialize =====
document.addEventListener('DOMContentLoaded', function() {
    // Setup event listeners
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
    }
    
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }
    
    // Setup password toggle
    setupPasswordToggle();
    
    // Setup input formatting
    setupInputFormatting();
    
    // Handle form submission
    if (signupForm) {
        signupForm.addEventListener('submit', handleFormSubmit);
    }
    
    // Auto-focus first input
    const firstInput = document.getElementById('first-name');
    if (firstInput) {
        setTimeout(() => {
            firstInput.focus();
        }, 300);
    }
    
    // Initialize password strength display
    if (passwordInput.value) {
        checkPasswordStrength(passwordInput.value);
    }
    
    // Initialize password match display
    if (confirmPasswordInput.value) {
        checkPasswordMatch();
    }
    
    // Add real-time validation
    const allInputs = signupForm.querySelectorAll('input');
    allInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.classList.add('valid');
                this.classList.remove('invalid');
            }
        });
    });
});

// ===== Utility Functions =====
function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Add to document
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

// ===== Additional CSS for Toast =====
const toastCSS = `
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: 8px;
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 10px;
    transform: translateX(150%);
    transition: transform 0.3s ease;
    z-index: 1000;
    max-width: 350px;
}

.toast.show {
    transform: translateX(0);
}

.toast-success {
    border-left: 4px solid #38A169;
    color: #276749;
}

.toast-error {
    border-left: 4px solid #E53E3E;
    color: #C53030;
}

.toast i {
    font-size: 1.2rem;
}
`;

// Add toast CSS to document
const style = document.createElement('style');
style.textContent = toastCSS;
document.head.appendChild(style);