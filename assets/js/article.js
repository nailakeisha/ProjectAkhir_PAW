// Mobile menu toggle
function toggleMobileMenu() {
    const navMenu = document.getElementById('navMenu');
    const overlay = document.getElementById('mobileOverlay');
    navMenu.classList.toggle('active');
    overlay.classList.toggle('active');
}

// Close menu when clicking on a link
document.querySelectorAll('.nav-center a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            toggleMobileMenu();
        }
    });
});

// Change navbar background on scroll
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.style.background = 'rgba(255, 255, 255, 0.98)';
        navbar.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.15)';
    } else {
        navbar.style.background = 'rgba(255, 255, 255, 0.95)';
        navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
    }
});

// Smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Counter Animation
class CounterAnimation {
    constructor() {
        this.counters = document.querySelectorAll('.stat-number');
        this.hasAnimated = false;
        this.init();
    }
    
    init() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.hasAnimated) {
                    this.startCounting();
                    this.hasAnimated = true;
                }
            });
        }, { 
            threshold: 0.5,
            rootMargin: '0px 0px -50px 0px'
        });
        
        const statsCard = document.querySelector('.stats-card');
        if (statsCard) {
            observer.observe(statsCard);
        }
    }
    
    startCounting() {
        this.counters.forEach((counter, index) => {
            setTimeout(() => {
                this.animateCounter(counter);
            }, index * 300);
        });
    }
    
    animateCounter(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const suffix = element.getAttribute('data-suffix') || '';
        let current = 0;
        const duration = 2000;
        const increment = target / (duration / 16);
        
        element.classList.add('animating');
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target + suffix;
                element.classList.remove('animating');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current) + suffix;
            }
        }, 16);
    }
}

// Initialize counter
document.addEventListener('DOMContentLoaded', () => {
    new CounterAnimation();
});

// Fallback counter trigger on scroll
window.addEventListener('scroll', function() {
    const statsCard = document.querySelector('.stats-card');
    if (statsCard) {
        const rect = statsCard.getBoundingClientRect();
        const isVisible = (
            rect.top >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)
        );
        
        if (isVisible) {
            const counters = document.querySelectorAll('.stat-number');
            counters.forEach(counter => {
                if (counter.textContent === '0' || counter.textContent === '0+' || counter.textContent === '0%') {
                    const target = parseInt(counter.getAttribute('data-target'));
                    const suffix = counter.getAttribute('data-suffix') || '';
                    let current = 0;
                    const duration = 2000;
                    const increment = target / (duration / 16);
                    
                    counter.classList.add('animating');
                    
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            counter.textContent = target + suffix;
                            counter.classList.remove('animating');
                            clearInterval(timer);
                        } else {
                            counter.textContent = Math.floor(current) + suffix;
                        }
                    }, 16);
                }
            });
        }
    }
});

// Click entire card to go to detail page
document.querySelectorAll('.blog-card').forEach(card => {
    card.addEventListener('click', function(e) {
        if (e.target.tagName === 'A' || e.target.closest('a')) {
            return;
        }
        const link = this.querySelector('.blog-title a');
        if (link) {
            window.location.href = link.href;
        }
    });
});

        // Data artikel statis (sama seperti contoh di PHP)
        const sampleArticles = [
            {
                article_id: 1,
                category: "TEKNOLOGI",
                title: "Pertanian Otonom 2025: Traktor Pintar dan Drone Jadi Petani Baru Dunia",
                excerpt: "Pertanian dunia kini tidak lagi bergantung penuh pada tenaga manusia. Dengan munculnya pertanian otonom 2025, ladang-ladang mulai dikelola oleh robot, drone, dan kecerdasan buatan (AI)...",
                author_name: "Agus Farmawan",
                created_at: "2024-12-15"
            },
            {
                article_id: 2,
                category: "ORGANIK",
                title: "Cara Membuat Pupuk Organik dari Limbah Rumah Tangga",
                excerpt: "Memanfaatkan sisa sayuran dan buah-buahan untuk membuat kompos berkualitas tinggi dengan biaya minimal...",
                author_name: "Sri Wahyuni",
                created_at: "2024-12-10"
            },
            {
                article_id: 3,
                category: "BUDIDAYA",
                title: "Panduan Lengkap Budidaya Cabai di Musim Hujan",
                excerpt: "Teknik-teknik khusus untuk mencegah kerusakan tanaman cabai akibat curah hujan tinggi dan kelembaban berlebih...",
                author_name: "Bambang Setyawan",
                created_at: "2024-12-05"
            },
            {
                article_id: 4,
                category: "EKONOMI",
                title: "Strategi Pemasaran Hasil Pertanian di Era Digital",
                excerpt: "Memaksimalkan platform e-commerce dan media sosial untuk menjangkau pasar yang lebih luas dengan biaya efektif...",
                author_name: "Rina Dewi",
                created_at: "2024-12-01"
            },
            {
                article_id: 5,
                category: "IRIGASI",
                title: "Sistem Irigasi Tetes: Solusi Hemat Air untuk Lahan Kering",
                excerpt: "Implementasi sistem irigasi presisi yang dapat menghemat penggunaan air hingga 60% dengan hasil yang optimal...",
                author_name: "Tri Handoko",
                created_at: "2024-11-25"
            }
        ];

        // Fungsi untuk mengatur tampilan berdasarkan status login
        function setupLoginView(isLoggedIn, userData = null) {
            const userNameEl = document.getElementById('user-name');
            const userAvatarEl = document.getElementById('user-avatar');
            const addArticleBtn = document.getElementById('add-article-btn');
            const sidebarLoggedin = document.getElementById('sidebar-loggedin');
            const sidebarGuest = document.getElementById('sidebar-guest');
            const firstArticleBtn = document.getElementById('first-article-btn');
            
            if (isLoggedIn && userData) {
                // Tampilkan untuk user yang login
                userNameEl.style.display = 'inline';
                userNameEl.textContent = userData.full_name;
                
                userAvatarEl.style.display = 'inline-flex';
                userAvatarEl.textContent = userData.full_name.charAt(0).toUpperCase();
                
                addArticleBtn.style.display = 'inline-flex';
                sidebarLoggedin.style.display = 'block';
                sidebarGuest.style.display = 'none';
                firstArticleBtn.style.display = 'inline-flex';
            } else {
                // Tampilkan untuk guest
                userNameEl.style.display = 'none';
                userAvatarEl.style.display = 'none';
                addArticleBtn.style.display = 'none';
                sidebarLoggedin.style.display = 'none';
                sidebarGuest.style.display = 'block';
                firstArticleBtn.style.display = 'none';
            }
        }

        // Fungsi untuk format tanggal
        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        // Fungsi animasi counter stats
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200;
            
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    const suffix = counter.getAttribute('data-suffix');
                    const count = parseInt(counter.innerText);
                    const increment = Math.ceil(target / speed);
                    
                    if (count < target) {
                        counter.innerText = count + increment;
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target + suffix;
                    }
                };
                
                updateCount();
            });
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Contoh: Cek session dari localStorage (ganti dengan API call sesuai kebutuhan)
            const userSession = localStorage.getItem('user_session');
            const isLoggedIn = userSession !== null;
            
            if (isLoggedIn) {
                // Contoh data user (ganti dengan data dari API)
                const userData = {
                    full_name: "Agus Farmawan",
                    email: "agus@example.com"
                };
                setupLoginView(true, userData);
            } else {
                setupLoginView(false);
            }
            
            // Jalankan animasi counter
            animateCounters();
            
            // Log untuk debugging
            console.log('Artikel loaded:', sampleArticles.length, 'articles');
        });

        // Fungsi untuk toggle mobile menu (jika sudah ada di article.js)
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            if (navMenu && mobileOverlay) {
                navMenu.classList.toggle('active');
                mobileOverlay.classList.toggle('active');
            }
        }

    window.addEventListener('load', function() {
        const footer = document.querySelector('.footer');
        const footerContent = document.querySelector('.footer-content');
        const footerBottom = document.querySelector('.footer-bottom');
        
        console.log('Footer total height:', footer.offsetHeight + 'px');
        console.log('Footer content height:', footerContent.offsetHeight + 'px');
        console.log('Footer bottom height:', footerBottom.offsetHeight + 'px');
        console.log('Footer padding:', 
            'top: ' + parseInt(getComputedStyle(footer).paddingTop) + 'px',
            'bottom: ' + parseInt(getComputedStyle(footer).paddingBottom) + 'px'
        );
    });