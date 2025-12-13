function toggleMobileMenu() {
    const navMenu = document.getElementById('navMenu');
    const overlay = document.getElementById('mobileOverlay');
    const menuBtn = document.querySelector('.mobile-menu-btn');
    
    navMenu.classList.toggle('active');
    overlay.classList.toggle('active');
    
    // Toggle icon between hamburger and close
    if (navMenu.classList.contains('active')) {
        menuBtn.innerHTML = '✕';
    } else {
        menuBtn.innerHTML = '☰';
    }
}

// Close menu when clicking on a link
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-center a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                toggleMobileMenu();
            }
        });
    });
});

// Character count
const excerptInput = document.getElementById('excerpt');
const excerptCount = document.getElementById('excerpt-count');
const contentInput = document.getElementById('content');
const contentCount = document.getElementById('content-count');

excerptInput.addEventListener('input', function() {
    excerptCount.textContent = this.value.length;
});

contentInput.addEventListener('input', function() {
    contentCount.textContent = this.value.length;
});

// Initialize counts
excerptCount.textContent = excerptInput.value.length;
contentCount.textContent = contentInput.value.length;

// File upload functionality
const fileInput = document.getElementById('articleImage');
const fileUploadArea = document.getElementById('fileUploadArea');
const fileInfo = document.getElementById('fileInfo');
const previewSection = document.getElementById('previewSection');
const previewImage = document.getElementById('previewImage');
const previewTitle = document.getElementById('previewTitle');
const previewContent = document.getElementById('previewContent');
const titleInput = document.getElementById('title');
const contentTextarea = document.getElementById('content');

// Drag and drop functionality
fileUploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    fileUploadArea.style.borderColor = '#4CAF50';
    fileUploadArea.style.backgroundColor = '#f0f9f0';
});

fileUploadArea.addEventListener('dragleave', () => {
    fileUploadArea.style.borderColor = '#ddd';
    fileUploadArea.style.backgroundColor = '';
});

fileUploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    fileUploadArea.style.borderColor = '#ddd';
    fileUploadArea.style.backgroundColor = '';
    
    if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        updateFileInfo();
        previewUploadedImage();
    }
});

fileInput.addEventListener('change', function() {
    updateFileInfo();
    previewUploadedImage();
});

function updateFileInfo() {
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        fileInfo.innerHTML = `File terpilih: ${file.name} (${fileSize} MB)`;
    } else {
        fileInfo.innerHTML = 'Format yang didukung: JPG, PNG, GIF. Maksimal ukuran: 5MB';
    }
}

function previewUploadedImage() {
    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewSection.style.display = 'block';
            updatePreviewContent();
        }
        
        reader.readAsDataURL(fileInput.files[0]);
    }
}

function updatePreviewContent() {
    const articleTitle = titleInput.value || 'Judul Artikel';
    const articleContent = contentTextarea.value || 'Isi artikel akan ditampilkan di sini...';
    
    previewTitle.textContent = articleTitle;
    
    // Truncate content for preview
    const truncatedContent = articleContent.length > 200 
        ? articleContent.substring(0, 200) + '...' 
        : articleContent;
    previewContent.textContent = truncatedContent;
}

// Update preview when form inputs change
titleInput.addEventListener('input', updatePreviewContent);
contentTextarea.addEventListener('input', updatePreviewContent);