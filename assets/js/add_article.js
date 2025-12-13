function toggleMobileMenu() {
    const navMenu = document.getElementById('navMenu');
    const overlay = document.getElementById('mobileOverlay');
    const menuBtn = document.getElementById('mobileMenuBtn');
    
    navMenu.classList.toggle('active');
    overlay.classList.toggle('active');
    
    // Toggle icon between hamburger and close
    if (navMenu.classList.contains('active')) {
        menuBtn.innerHTML = '✕';
        document.body.style.overflow = 'hidden'; // Prevent scrolling when menu is open
    } else {
        menuBtn.innerHTML = '☰';
        document.body.style.overflow = ''; // Restore scrolling
    }
}

// Close menu when clicking on a link or overlay
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-center a');
    const overlay = document.getElementById('mobileOverlay');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                toggleMobileMenu();
            }
        });
    });

    // Close menu when clicking overlay
    overlay.addEventListener('click', function() {
        if (window.innerWidth <= 768 && navMenu.classList.contains('active')) {
            toggleMobileMenu();
        }
    });

    // Close menu when pressing escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && window.innerWidth <= 768 && navMenu.classList.contains('active')) {
            toggleMobileMenu();
        }
    });
});

// Close menu on window resize
window.addEventListener('resize', function() {
    const navMenu = document.getElementById('navMenu');
    const menuBtn = document.getElementById('mobileMenuBtn');
    
    if (window.innerWidth > 768 && navMenu.classList.contains('active')) {
        navMenu.classList.remove('active');
        document.getElementById('mobileOverlay').classList.remove('active');
        menuBtn.innerHTML = '☰';
        document.body.style.overflow = '';
    }
});