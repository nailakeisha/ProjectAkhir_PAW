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

// Auto-generate excerpt from content
document.getElementById('content').addEventListener('input', function() {
    const excerptField = document.getElementById('excerpt');
    if (excerptField.value === '') {
        const content = this.value;
        const plainText = content.replace(/<[^>]*>/g, '');
        const excerpt = plainText.substring(0, 150);
        if (excerpt.length > 0) {
            excerptField.value = excerpt + (plainText.length > 150 ? '...' : '');
            updateCharCounter('excerpt', excerptField.value.length);
        }
    }
    updateCharCounter('content', this.value.length);
});

// Character counter for title
document.getElementById('title').addEventListener('input', function() {
    updateCharCounter('title', this.value.length);
});

// Character counter for excerpt
document.getElementById('excerpt').addEventListener('input', function() {
    updateCharCounter('excerpt', this.value.length);
});

function updateCharCounter(fieldId, charCount) {
    const counter = document.getElementById(`${fieldId}-counter`);
    if (counter) {
        if (fieldId === 'content') {
            counter.textContent = `${charCount} karakter`;
            if (charCount > 5000) {
                counter.style.color = '#f44336';
            } else if (charCount > 3000) {
                counter.style.color = '#FF9800';
            } else {
                counter.style.color = '#4CAF50';
            }
        } else {
            counter.textContent = `${charCount}/255 karakter`;
            if (charCount > 200) {
                counter.style.color = '#f44336';
            } else if (charCount > 150) {
                counter.style.color = '#FF9800';
            } else {
                counter.style.color = '#4CAF50';
            }
        }
    }
}

// Initialize character counters on page load
document.addEventListener('DOMContentLoaded', function() {
    const titleLength = document.getElementById('title').value.length;
    const excerptLength = document.getElementById('excerpt').value.length;
    const contentLength = document.getElementById('content').value.length;
    
    updateCharCounter('title', titleLength);
    updateCharCounter('excerpt', excerptLength);
    updateCharCounter('content', contentLength);
});

// Form validation
document.getElementById('editArticleForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    const category = document.getElementById('category').value;
    
    if (!title) {
        e.preventDefault();
        showAlert('Judul artikel harus diisi!', 'error');
        document.getElementById('title').focus();
        return;
    }
    
    if (!category) {
        e.preventDefault();
        showAlert('Kategori harus dipilih!', 'error');
        document.getElementById('category').focus();
        return;
    }
    
    if (!content) {
        e.preventDefault();
        showAlert('Konten artikel harus diisi!', 'error');
        document.getElementById('content').focus();
        return;
    }
    
    if (title.length > 255) {
        e.preventDefault();
        showAlert('Judul artikel terlalu panjang! Maksimal 255 karakter.', 'error');
        document.getElementById('title').focus();
        return;
    }
    
    if (excerpt.length > 255) {
        e.preventDefault();
        showAlert('Ringkasan artikel terlalu panjang! Maksimal 255 karakter.', 'error');
        document.getElementById('excerpt').focus();
        return;
    }
});

// Alert function
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `message ${type}`;
    alertDiv.textContent = message;
    alertDiv.style.marginBottom = '1rem';
    
    const form = document.getElementById('editArticleForm');
    form.parentNode.insertBefore(alertDiv, form);
    
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        alertDiv.style.transition = 'opacity 0.5s';
        setTimeout(() => alertDiv.remove(), 500);
    }, 5000);
}

// Preview functionality
function previewArticle() {
    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;
    const excerpt = document.getElementById('excerpt').value;
    const category = document.getElementById('category').value;
    
    if (!title || !content) {
        showAlert('Judul dan konten harus diisi untuk preview!', 'error');
        return;
    }
    
    const previewContent = document.getElementById('previewContent');
    previewContent.innerHTML = `
        <div class="category">${category ? category.toUpperCase() : 'UNCATEGORIZED'}</div>
        <h1>${title}</h1>
        ${excerpt ? `<div class="excerpt">${excerpt}</div>` : ''}
        <div class="content">${formatContent(content)}</div>
    `;
    
    document.getElementById('previewModal').style.display = 'block';
}

function closePreview() {
    document.getElementById('previewModal').style.display = 'none';
}

function formatContent(content) {
    // Basic content formatting for preview
    return content
        .split('\n')
        .map(paragraph => {
            if (paragraph.trim() === '') return '';
            return `<p>${paragraph}</p>`;
        })
        .join('');
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('previewModal');
    if (e.target === modal) {
        closePreview();
    }
});

// Auto-save draft for edit
let autoSaveTimer;
const formFields = ['title', 'content', 'excerpt', 'category'];
let originalData = {};

// Store original data
formFields.forEach(field => {
    const element = document.getElementById(field);
    if (element) {
        originalData[field] = element.value;
    }
});

formFields.forEach(field => {
    const element = document.getElementById(field);
    if (element) {
        element.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(saveDraft, 2000);
        });
    }
});

function saveDraft() {
    const articleId = new URLSearchParams(window.location.search).get('id');
    const draft = {
        id: articleId,
        title: document.getElementById('title').value,
        content: document.getElementById('content').value,
        excerpt: document.getElementById('excerpt').value,
        category: document.getElementById('category').value,
        timestamp: new Date().toISOString()
    };
    
    // Only save if there are changes
    const hasChanges = formFields.some(field => 
        draft[field] !== originalData[field]
    );
    
    if (hasChanges && (draft.title || draft.content)) {
        localStorage.setItem(`articleDraft_${articleId}`, JSON.stringify(draft));
        console.log('Draft perubahan disimpan');
    }
}

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const articleId = new URLSearchParams(window.location.search).get('id');
    const draft = localStorage.getItem(`articleDraft_${articleId}`);
    
    if (draft) {
        const draftData = JSON.parse(draft);
        const timeAgo = getTimeAgo(new Date(draftData.timestamp));
        
        if (confirm(`Draft perubahan dari ${timeAgo} ditemukan. Muat draft tersebut?`)) {
            document.getElementById('title').value = draftData.title || '';
            document.getElementById('content').value = draftData.content || '';
            document.getElementById('excerpt').value = draftData.excerpt || '';
            document.getElementById('category').value = draftData.category || '';
            
            // Update character counters
            updateCharCounter('title', draftData.title.length);
            updateCharCounter('excerpt', draftData.excerpt.length);
            updateCharCounter('content', draftData.content.length);
        }
    }
});

// Clear draft when form is submitted
document.getElementById('editArticleForm').addEventListener('submit', function() {
    const articleId = new URLSearchParams(window.location.search).get('id');
    localStorage.removeItem(`articleDraft_${articleId}`);
});

// Utility function to get time ago
function getTimeAgo(date) {
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    
    if (diffMins < 1) return 'beberapa detik yang lalu';
    if (diffMins < 60) return `${diffMins} menit yang lalu`;
    if (diffHours < 24) return `${diffHours} jam yang lalu`;
    return `${Math.floor(diffHours / 24)} hari yang lalu`;
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('editArticleForm').dispatchEvent(new Event('submit'));
    }
    
    // Escape to close preview
    if (e.key === 'Escape') {
        closePreview();
    }
});