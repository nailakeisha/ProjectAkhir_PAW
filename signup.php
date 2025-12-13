<!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niseva Agro - Daftar</title>
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
        .signup-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
            box-shadow: 0 0 40px var(--shadow);
        }

        /* Kolom Kiri - Gambar dan Informasi */
        .info-column {
            flex: 1.2;
            background: linear-gradient(rgba(26, 93, 26, 0.85), rgba(46, 139, 87, 0.9)), 
                        url('https://images.unsplash.com/photo-1625246333195-78d9c38ad449?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
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

        /* Kolom Kanan - Form Sign Up */
        .form-column {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: var(--cream);
            overflow-y: auto;
        }

        .signup-box {
            background-color: var(--text-light);
            border-radius: 20px;
            box-shadow: 0 15px 40px var(--shadow);
            padding: 3rem;
            width: 100%;
            max-width: 500px;
            border: 1px solid rgba(46, 139, 87, 0.1);
        }

        .signup-header {
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .signup-header h2 {
            color: var(--dark-green);
            font-size: 2.2rem;
            margin-bottom: 0.8rem;
            font-weight: 700;
        }

        .signup-header p {
            color: #666;
            font-size: 1.1rem;
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

        .signup-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.95rem;
        }

        .signup-footer a {
            color: var(--medium-green);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.3s;
            margin-left: 0.3rem;
        }

        .signup-footer a:hover {
            color: var(--dark-green);
            text-decoration: underline;
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

        .signup-box {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .signup-container {
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
            
            .signup-box {
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
            
            .signup-box {
                padding: 1.5rem;
            }
            
            .signup-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <!-- Kolom Kiri - Informasi -->
        <div class="info-column">
            <div class="info-content">
                <div class="logo">
                    <img src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80" alt="Profile" class="logo-img">
                    <span>Niseva Agro</span>
                </div>
                
                <h1>Mulai Perjalanan Pertanian Anda</h1>
                <p>Bergabung dengan ribuan petani lainnya dan dapatkan akses ke teknologi pertanian modern, pendanaan, dan komunitas yang mendukung.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Akses ke teknologi pertanian terkini</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Komunitas petani yang aktif</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Pendanaan dan bimbingan ahli</span>
                    </div>
                </div>
                
                <div class="stats">
                    <div class="stat-item">
                        <span class="stat-value">5K+</span>
                        <span class="stat-label">Petani Terdaftar</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">98%</span>
                        <span class="stat-label">Kepuasan Pengguna</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Kolom Kanan - Form Sign Up -->
        <div class="form-column">
            <div class="signup-box">
                <div class="signup-header">
                    <h2>Buat Akun Baru</h2>
                    <p>Isi data diri Anda untuk bergabung dengan Niseva Agro</p>
                </div>
                
                <form id="signup-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first-name">Nama Depan</label>
                            <div class="input-with-icon">
                                <input type="text" id="first-name" class="form-control" placeholder="Nama depan" required>
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Nama Belakang</label>
                            <div class="input-with-icon">
                                <input type="text" id="last-name" class="form-control" placeholder="Nama belakang" required>
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="signup-email">Email</label>
                        <div class="input-with-icon">
                            <input type="email" id="signup-email" class="form-control" placeholder="nama@email.com" required>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="signup-phone">Nomor Telepon</label>
                        <div class="input-with-icon">
                            <input type="tel" id="signup-phone" class="form-control" placeholder="08xxxxxxxxxx" required>
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <div class="input-with-icon">
                            <input type="password" id="signup-password" class="form-control" placeholder="Minimal 8 karakter" required>
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
                            <input type="password" id="confirm-password" class="form-control" placeholder="Ulangi password" required>
                            <i class="fas fa-lock"></i>
                        </div>
                        <div id="password-match" style="font-size: 0.85rem; margin-top: 0.3rem;"></div>
                    </div>

                    <div class="terms-agreement">
                        <input type="checkbox" id="agree-terms" required>
                        <label for="agree-terms">
                            Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a> Niseva Agro
                        </label>
                    </div>

                    <button type="submit" class="btn">Daftar Sekarang</button>
                    
                    <div class="signup-footer">
                        <p>Sudah punya akun? <a href="login.html" id="login-link">Masuk di sini</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Password strength indicator
        const passwordInput = document.getElementById('signup-password');
        const strengthFill = document.getElementById('strength-fill');
        const strengthText = document.getElementById('strength-text');
        const confirmPassword = document.getElementById('confirm-password');
        const passwordMatch = document.getElementById('password-match');

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

        // Password confirmation check
        confirmPassword.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                passwordMatch.textContent = 'Password tidak cocok';
                passwordMatch.style.color = '#E53E3E';
            } else {
                passwordMatch.textContent = 'Password cocok';
                passwordMatch.style.color = '#38A169';
            }
        });

        // Form submission
        document.getElementById('signup-form').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const firstName = document.getElementById('first-name').value;
            const lastName = document.getElementById('last-name').value;
            const email = document.getElementById('signup-email').value;
            const phone = document.getElementById('signup-phone').value;
            const password = document.getElementById('signup-password').value;
            const confirmPass = document.getElementById('confirm-password').value;
            const agreeTerms = document.getElementById('agree-terms').checked;
            
            // Validasi
            if (!firstName || !lastName || !email || !phone || !password || !confirmPass) {
                alert('Harap isi semua field!');
                return;
            }
            
            if (password !== confirmPass) {
                alert('Password dan konfirmasi password tidak cocok!');
                return;
            }
            
            if (!agreeTerms) {
                alert('Anda harus menyetujui Syarat & Ketentuan!');
                return;
            }
            
            // Simulasi pendaftaran berhasil
            alert(`Pendaftaran berhasil! Selamat datang ${firstName} ${lastName} di Niseva Agro.`);
            // Di aplikasi nyata, di sini akan ada proses pendaftaran ke server
        });

        // Pastikan ikon tetap terlihat saat input aktif
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('i').style.color = 'var(--dark-green)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('i').style.color = 'var(--medium-green)';
            });
        });
    </script>
</body>
</html> -->