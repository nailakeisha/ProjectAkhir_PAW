// assets/js/admin_articles.js

// Global variables
let articles = [];
let stats = {};
let currentUserId = 1;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Data sudah di-load dari inline script di HTML
    // Sekarang kita inisialisasi semua fungsi
    
    renderStats();
    renderArticlesTable();
    setupFilters();
    setupEventListeners();
    setupTabs();
    
    // Update counts
    updateArticlesCount();
});

// Render stats to HTML
function renderStats() {
    const statsSection = document.getElementById('statsSection');
    if (!statsSection || !stats) return;
    
    statsSection.innerHTML = `
        <div class="stat-card">
            <div class="stat-number">${stats.total_articles || 0}</div>
            <div class="stat-label">Total Artikel</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${stats.published_articles || 0}</div>
            <div class="stat-label">Published</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${stats.draft_articles || 0}</div>
            <div class="stat-label">Draft</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${stats.archived_articles || 0}</div>
            <div class="stat-label">Archived</div>
        </div>
    `;
}

// Render articles table
function renderArticlesTable() {
    const tableBody = document.getElementById('articlesTable');
    if (!tableBody) return;
    
    if (!articles || articles.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="5">
                    <div class="no-data">
                        <i class="fas fa-newspaper"></i>
                        <h3>Belum ada artikel</h3>
                        <p>Mulai dengan menambahkan artikel pertama untuk platform NISEVA Agro</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    articles.forEach(article => {
        const isMine = article.user_id == currentUserId;
        
        html += `
            <tr data-category="${article.category}" 
                data-status="${article.status}"
                data-title="${article.title.toLowerCase()}"
                data-author-id="${article.user_id}"
                data-is-mine="${isMine}"
                data-article-id="${article.article_id}">
                <td>
                    <div class="article-info">
                        <div class="article-title">${escapeHtml(article.title)}</div>
                        <div class="article-excerpt">${escapeHtml(article.excerpt)}</div>
                        <div class="article-meta">
                            <div class="article-author">
                                <div class="author-avatar">${article.author_initial}</div>
                                <span>${escapeHtml(article.author_name)}</span>
                                ${isMine ? '<span class="badge badge-primary"><i class="fas fa-user"></i> Anda</span>' : ''}
                            </div>
                            <div class="article-date">
                                <i class="far fa-calendar"></i> ${article.created_date}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="category-badge">${capitalizeFirst(article.category)}</span>
                </td>
                <td>
                    <span class="status-badge status-${article.status}">
                        <i class="fas ${article.status_icon || 'fa-question'}"></i>
                        ${article.status_text || article.status}
                    </span>
                </td>
                <td>${article.created_datetime}</td>
                <td>
                    <div class="action-buttons">
                        <a href="admin_edit_article.php?id=${article.article_id}" 
                           class="btn btn-info btn-icon btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-warning btn-icon btn-sm toggle-status-btn" 
                                data-id="${article.article_id}"
                                data-current-status="${article.status}"
                                title="${article.status === 'published' ? 'Ubah ke Draft' : 'Publikasikan'}">
                            <i class="fas ${article.status === 'published' ? 'fa-eye-slash' : 'fa-eye'}"></i>
                        </button>
                        <button class="btn btn-danger btn-icon btn-sm delete-btn" 
                                data-id="${article.article_id}"
                                data-title="${escapeHtml(article.title)}"
                                title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
}

// Setup filters
function setupFilters() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    function filterTable() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const category = categoryFilter ? categoryFilter.value : '';
        const status = statusFilter ? statusFilter.value : '';
        
        const rows = document.querySelectorAll('#articlesTable tr[data-category]');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const title = row.getAttribute('data-title');
            const rowCategory = row.getAttribute('data-category');
            const rowStatus = row.getAttribute('data-status');
            
            const matchesSearch = !searchTerm || title.includes(searchTerm);
            const matchesCategory = !category || rowCategory === category;
            const matchesStatus = !status || rowStatus === status;
            
            if (matchesSearch && matchesCategory && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        updateArticlesCount(visibleCount);
    }
    
    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (categoryFilter) categoryFilter.addEventListener('change', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
}

// Setup tabs
function setupTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Update active tab
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Show selected tab content
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === tabId + '-tab') {
                    content.classList.add('active');
                }
            });
            
            // Load my articles grid if needed
            if (tabId === 'my-articles') {
                renderMyArticlesGrid();
            }
        });
    });
}

// Setup event listeners for buttons
function setupEventListeners() {
    // Delete buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const button = e.target.closest('.delete-btn');
            const articleId = button.getAttribute('data-id');
            const articleTitle = button.getAttribute('data-title');
            
            showDeleteModal(articleId, articleTitle);
        }
        
        // Status toggle buttons
        if (e.target.closest('.toggle-status-btn')) {
            const button = e.target.closest('.toggle-status-btn');
            const articleId = button.getAttribute('data-id');
            const currentStatus = button.getAttribute('data-current-status');
            
            showStatusModal(articleId, currentStatus);
        }
    });
}

// Show delete modal
function showDeleteModal(articleId, articleTitle) {
    const deleteMessage = document.getElementById('deleteMessage');
    if (deleteMessage) {
        deleteMessage.textContent = `Apakah Anda yakin ingin menghapus artikel "${articleTitle}"?`;
    }
    
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'flex';
        
        // Setup confirm delete
        const confirmBtn = document.getElementById('confirmDelete');
        if (confirmBtn) {
            confirmBtn.onclick = function() {
                deleteArticle(articleId);
            };
        }
    }
}

// Show status change modal
function showStatusModal(articleId, currentStatus) {
    const newStatus = currentStatus === 'published' ? 'draft' : 'published';
    const statusMessage = document.getElementById('statusMessage');
    
    if (statusMessage) {
        statusMessage.textContent = `Ubah status artikel dari "${getStatusText(currentStatus)}" ke "${getStatusText(newStatus)}"?`;
    }
    
    const modal = document.getElementById('statusModal');
    if (modal) {
        modal.style.display = 'flex';
        
        // Setup confirm status change
        const confirmBtn = document.getElementById('confirmStatus');
        if (confirmBtn) {
            confirmBtn.onclick = function() {
                toggleArticleStatus(articleId, currentStatus);
            };
        }
    }
}

// AJAX function to delete article
async function deleteArticle(articleId) {
    try {
        const response = await fetch(`../controller/admin_articles.php?action=delete&id=${articleId}`);
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            // Remove article from table
            const row = document.querySelector(`tr[data-article-id="${articleId}"]`);
            if (row) {
                row.remove();
                updateArticlesCount();
                // Reload stats
                loadData();
            }
        }
    } catch (error) {
        console.error('Error deleting article:', error);
        alert('Gagal menghapus artikel');
    }
    
    // Close modal
    const modal = document.getElementById('deleteModal');
    if (modal) modal.style.display = 'none';
}

// AJAX function to toggle article status
async function toggleArticleStatus(articleId, currentStatus) {
    try {
        const response = await fetch(`../controller/admin_articles.php?action=toggle_status&id=${articleId}&current_status=${currentStatus}`);
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            // Update UI
            const row = document.querySelector(`tr[data-article-id="${articleId}"]`);
            if (row) {
                const statusBadge = row.querySelector('.status-badge');
                const statusIcon = row.querySelector('.status-badge i');
                const toggleBtn = row.querySelector('.toggle-status-btn');
                const toggleIcon = row.querySelector('.toggle-status-btn i');
                
                if (statusBadge) {
                    statusBadge.className = `status-badge status-${data.new_status}`;
                    statusBadge.innerHTML = `<i class="fas ${getStatusIcon(data.new_status)}"></i> ${getStatusText(data.new_status)}`;
                }
                
                if (toggleBtn) {
                    toggleBtn.setAttribute('data-current-status', data.new_status);
                    toggleBtn.setAttribute('title', data.new_status === 'published' ? 'Ubah ke Draft' : 'Publikasikan');
                    
                    if (toggleIcon) {
                        toggleIcon.className = `fas ${data.new_status === 'published' ? 'fa-eye-slash' : 'fa-eye'}`;
                    }
                }
            }
            
            // Reload stats
            loadData();
        }
    } catch (error) {
        console.error('Error toggling article status:', error);
        alert('Gagal mengubah status artikel');
    }
    
    // Close modal
    const modal = document.getElementById('statusModal');
    if (modal) modal.style.display = 'none';
}

// Load data from PHP controller
async function loadData() {
    try {
        const response = await fetch('../controller/admin_articles.php?action=get_data');
        const data = await response.json();
        
        if (data.success) {
            articles = data.articles || [];
            stats = data.stats || {};
            currentUserId = data.current_user_id || 1;
            
            renderStats();
            renderArticlesTable();
            updateArticlesCount();
        }
    } catch (error) {
        console.error('Error loading data:', error);
    }
}

// Update articles count
function updateArticlesCount(count = null) {
    const articlesCount = document.getElementById('articlesCount');
    if (articlesCount) {
        if (count !== null) {
            articlesCount.textContent = `Menampilkan ${count} artikel`;
        } else {
            const visibleRows = document.querySelectorAll('#articlesTable tr[data-category]:not([style*="display: none"])');
            articlesCount.textContent = `Menampilkan ${visibleRows.length} artikel`;
        }
    }
}

// Helper functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function capitalizeFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function getStatusIcon(status) {
    switch(status) {
        case 'published': return 'fa-eye';
        case 'draft': return 'fa-edit';
        case 'archived': return 'fa-archive';
        default: return 'fa-question';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'published': return 'Published';
        case 'draft': return 'Draft';
        case 'archived': return 'Archived';
        default: return status;
    }
}

// Mobile menu toggle
function toggleMobileMenu() {
    const navMenu = document.getElementById('navMenu');
    if (navMenu) {
        navMenu.classList.toggle('show');
    }
}

// Make function globally available
window.toggleMobileMenu = toggleMobileMenu;