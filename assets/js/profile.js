// Mobile Menu Toggle
function toggleMobileMenu() {
  const navMenu = document.getElementById('navMenu');
  if (navMenu.style.display === 'flex') {
    navMenu.style.display = 'none';
  } else {
    navMenu.style.display = 'flex';
  }
}

// Reset Form
function resetForm() {
  const form = document.getElementById('profileForm');
  form.reset();
}

// Form interactions
document.addEventListener('DOMContentLoaded', function() {
  // Input focus animations
  const inputs = document.querySelectorAll('input, select, textarea');
  inputs.forEach(input => {
    input.addEventListener('focus', function() {
      this.parentElement.style.transform = 'scale(1.02)';
    });
    
    input.addEventListener('blur', function() {
      this.parentElement.style.transform = 'scale(1)';
    });
  });

  // Investment card hover effects
  const investmentCards = document.querySelectorAll('.investment-card');
  investmentCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-8px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0) scale(1)';
    });
  });

  // Check for messages in URL parameters
  const urlParams = new URLSearchParams(window.location.search);
  const success = urlParams.get('success');
  const error = urlParams.get('error');

  if (success) {
    showMessage('Profile berhasil diperbarui!', 'success');
    // Remove URL parameters
    window.history.replaceState({}, document.title, window.location.pathname);
  }

  if (error) {
    showMessage(decodeURIComponent(error), 'error');
    // Remove URL parameters
    window.history.replaceState({}, document.title, window.location.pathname);
  }

  // Form submission handler
  const profileForm = document.getElementById('profileForm');
  if (profileForm) {
    profileForm.addEventListener('submit', function(e) {
      // Validasi form sebelum submit
      const nama = document.getElementById('nama').value.trim();
      const email = document.getElementById('email').value.trim();
      const telepon = document.getElementById('telepon').value.trim();

      if (!nama || !email || !telepon) {
        e.preventDefault();
        showMessage('Semua field yang wajib harus diisi!', 'error');
        return false;
      }

      // Validasi email format
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        e.preventDefault();
        showMessage('Format email tidak valid!', 'error');
        return false;
      }

      // Update sidebar name when form is submitted
      const sidebarName = document.getElementById('sidebarName');
      const userName = document.getElementById('userName');
      if (sidebarName) sidebarName.textContent = nama;
      if (userName) userName.textContent = nama;
    });
  }

  // Animate progress bars
  animateProgressBars();
});

// Show message function
function showMessage(message, type) {
  const messageContainer = document.getElementById('messageContainer');
  if (!messageContainer) return;

  const messageDiv = document.createElement('div');
  messageDiv.className = `message message-${type}`;
  
  const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
  messageDiv.innerHTML = `
    <i class="fas ${icon}"></i>
    <span>${message}</span>
  `;

  messageContainer.innerHTML = '';
  messageContainer.appendChild(messageDiv);

  // Auto hide after 5 seconds
  setTimeout(() => {
    messageDiv.style.opacity = '0';
    setTimeout(() => {
      messageDiv.remove();
    }, 300);
  }, 5000);
}

// Animate progress bars
function animateProgressBars() {
  const progressBars = document.querySelectorAll('.progress-fill');
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const width = entry.target.style.width;
        entry.target.style.width = '0%';
        setTimeout(() => {
          entry.target.style.width = width;
        }, 100);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  progressBars.forEach(bar => {
    observer.observe(bar);
  });
}

// Smooth scroll to section
function scrollToSection(sectionId) {
  const section = document.getElementById(sectionId);
  if (section) {
    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}

// Handle window resize for responsive behavior
let resizeTimer;
window.addEventListener('resize', function() {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(function() {
    const navMenu = document.getElementById('navMenu');
    if (window.innerWidth > 768) {
      navMenu.style.display = 'flex';
    } else {
      navMenu.style.display = 'none';
    }
  }, 250);
});