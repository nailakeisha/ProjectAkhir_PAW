js

assets
js
admin-marketplace.js

// marketplace-admin.js - JavaScript untuk Admin Marketplace NISEVA AGRO

// Sample admin product data
const adminProducts = [
  {
    id: 1,
    name: "Bibit Padi Unggul",
    description: "Bibit padi unggul dengan hasil panen maksimal, tahan hama dan penyakit",
    price: 50000,
    stock: 100,
    image: "https://tse2.mm.bing.net/th/id/OIP.QgC2fruskE2Lx-2z8UQs-AHaHa?pid=Api&P=0&h=180",
    category: "Bibit Tanaman",
    seller_name: "Budi Santoso",
    seller_phone: "628123456789",
    seller_email: "budi@email.com",
    seller_location: "Karawang, Jawa Barat",
    rating: 4.5,
    reviews: 24,
    created_at: "2024-01-15",
    status: "active",
    sales: 45
  },
  {
    id: 2,
    name: "Pupuk Organik Cair",
    description: "Pupuk organik cair untuk meningkatkan kesuburan tanah dan pertumbuhan tanaman",
    price: 75000,
    stock: 50,
    image: "https://lzd-img-global.slatic.net/g/p/ae7d2d75882018e60832e0c1a3e3b61b.jpg_720x720q80.jpg_.webp",
    category: "Pupuk Organik",
    seller_name: "Sari Dewi",
    seller_phone: "628987654321",
    seller_email: "sari@email.com",
    seller_location: "Bogor, Jawa Barat",
    rating: 4.8,
    reviews: 31,
    created_at: "2024-01-10",
    status: "active",
    sales: 28
  },
  {
    id: 3,
    name: "Traktor Mini",
    description: "Traktor mini untuk lahan pertanian kecil dan menengah, mudah dioperasikan",
    price: 12500000,
    stock: 5,
    image: "https://image.ceneostatic.pl/data/products/109749301/i-mini-traktor-traktorek-kubota-5001-nowy-20000netto.jpg",
    category: "Alat Pertanian",
    seller_name: "Budi Santoso",
    seller_phone: "628123456789",
    seller_email: "budi@email.com",
    seller_location: "Karawang, Jawa Barat",
    rating: 4.3,
    reviews: 8,
    created_at: "2024-01-05",
    status: "active",
    sales: 3
  },
  {
    id: 4,
    name: "Benih Jagung Manis",
    description: "Benih jagung manis hibrida dengan rasa manis alami. Hasil panen melimpah dengan umur panen 65-70 hari.",
    price: 35000,
    stock: 200,
    image: "https://tse4.mm.bing.net/th/id/OIP.XE0Wmh2QnHzFHSfRxUv-jgHaHa?pid=Api&P=0&h=180",
    category: "Bibit Tanaman",
    seller_name: "Agus Setiawan",
    seller_phone: "628555123456",
    seller_email: "agus@email.com",
    seller_location: "Magelang, Jawa Tengah",
    rating: 4.6,
    reviews: 42,
    created_at: "2024-01-12",
    status: "pending",
    sales: 0
  },
  {
    id: 5,
    name: "Alat Penyiram Otomatis",
    description: "Sistem penyiraman otomatis dengan timer digital untuk efisiensi waktu dan air. Cocok untuk greenhouse dan kebun.",
    price: 450000,
    stock: 25,
    image: "https://cf.shopee.co.id/file/bccb478ccd517bf1204cff96f2299de0",
    category: "Alat Pertanian",
    seller_name: "CV Agro Teknik",
    seller_phone: "628112233445",
    seller_email: "agroteknik@email.com",
    seller_location: "Surabaya, Jawa Timur",
    rating: 4.4,
    reviews: 17,
    created_at: "2024-01-08",
    status: "active",
    sales: 12
  },
  {
    id: 6,
    name: "Pupuk Kompos 5kg",
    description: "Pupuk kompos organik dari kotoran sapi yang sudah difermentasi. Memperbaiki struktur tanah dan meningkatkan kesuburan.",
    price: 35000,
    stock: 150,
    image: "https://tse1.mm.bing.net/th/id/OIP.sJ42iNUhTT4h8q-JMym5DwHaHc?pid=Api&P=0&h=180",
    category: "Pupuk Organik",
    seller_name: "Petani Organik",
    seller_phone: "628777888999",
    seller_email: "organik@email.com",
    seller_location: "Yogyakarta",
    rating: 4.7,
    reviews: 56,
    created_at: "2024-01-03",
    status: "inactive",
    sales: 89
  },
  {
    id: 7,
    name: "Beras Organik Premium 5kg",
    description: "Beras organik hasil panen petani lokal dengan proses penggilingan tradisional. Tanpa bahan kimia dan pengawet.",
    price: 125000,
    stock: 80,
    image: "https://tse2.mm.bing.net/th/id/OIP.Jn6i8MSVfAhUaUQLR-zzxQHaHa?pid=Api&P=0&h=180",
    category: "Hasil Panen",
    seller_name: "Koperasi Tani Maju",
    seller_phone: "628112233456",
    seller_email: "koptan@email.com",
    seller_location: "Klaten, Jawa Tengah",
    rating: 4.9,
    reviews: 89,
    created_at: "2024-01-20",
    status: "active",
    sales: 67
  },
  {
    id: 8,
    name: "Alat Panen Padi Modern",
    description: "Alat panen padi multifungsi yang dapat memotong, merontokkan, dan mengemas gabah sekaligus.",
    price: 18500000,
    stock: 3,
    image: "https://tse2.mm.bing.net/th/id/OIP.QlSlWYn9L0bC5fBIdvLKjwHaFj?pid=Api&P=0&h=180",
    category: "Alat Pertanian",
    seller_name: "CV Agro Teknik",
    seller_phone: "628112233445",
    seller_email: "agroteknik@email.com",
    seller_location: "Surabaya, Jawa Timur",
    rating: 4.2,
    reviews: 5,
    created_at: "2024-01-18",
    status: "active",
    sales: 1
  }
];

// Sample sellers data
const sellers = [
  {
    id: 1,
    name: "Budi Santoso",
    email: "budi@email.com",
    phone: "628123456789",
    location: "Karawang, Jawa Barat",
    products: 15,
    join_date: "2024-01-15",
    status: "verified",
    rating: 4.5,
    total_sales: 48
  },
  {
    id: 2,
    name: "Sari Dewi",
    email: "sari@email.com",
    phone: "628987654321",
    location: "Bogor, Jawa Barat",
    products: 8,
    join_date: "2024-01-10",
    status: "verified",
    rating: 4.8,
    total_sales: 28
  },
  {
    id: 3,
    name: "Agus Setiawan",
    email: "agus@email.com",
    phone: "628555123456",
    location: "Magelang, Jawa Tengah",
    products: 5,
    join_date: "2024-01-12",
    status: "pending",
    rating: 4.6,
    total_sales: 0
  },
  {
    id: 4,
    name: "CV Agro Teknik",
    email: "agroteknik@email.com",
    phone: "628112233445",
    location: "Surabaya, Jawa Timur",
    products: 12,
    join_date: "2024-01-08",
    status: "verified",
    rating: 4.3,
    total_sales: 13
  },
  {
    id: 5,
    name: "Petani Organik",
    email: "organik@email.com",
    phone: "628777888999",
    location: "Yogyakarta",
    products: 6,
    join_date: "2024-01-03",
    status: "suspended",
    rating: 4.7,
    total_sales: 89
  }
];

// Categories for admin
const adminCategories = [
  { id: 1, name: "Bibit Tanaman", icon: "🌱", productCount: 45, description: "Berbagai jenis bibit tanaman pertanian" },
  { id: 2, name: "Pupuk Organik", icon: "🧪", productCount: 32, description: "Pupuk organik untuk kesuburan tanah" },
  { id: 3, name: "Alat Pertanian", icon: "🔧", productCount: 28, description: "Alat-alat modern untuk pertanian" },
  { id: 4, name: "Hasil Panen", icon: "🌾", productCount: 67, description: "Hasil panen berkualitas dari petani" },
  { id: 5, name: "Obat Tanaman", icon: "💊", productCount: 24, description: "Pestisida dan obat tanaman" },
  { id: 6, name: "Benih Buah", icon: "🍓", productCount: 38, description: "Benih buah-buahan segar" },
  { id: 7, name: "Perlengkapan Petani", icon: "👷", productCount: 19, description: "Perlengkapan petani sehari-hari" },
  { id: 8, name: "Paket Usaha Tani", icon: "📦", productCount: 12, description: "Paket lengkap untuk usaha tani" }
];

// Pending products for verification
const pendingProducts = [
  {
    id: 1,
    name: "Benih Jagung Manis",
    seller: "Agus Setiawan",
    category: "Bibit Tanaman",
    price: 35000,
    date: "2024-01-20",
    image: "https://tse4.mm.bing.net/th/id/OIP.XE0Wmh2QnHzFHSfRxUv-jgHaHa?pid=Api&P=0&h=180",
    description: "Benih jagung manis hibrida dengan rasa manis alami"
  },
  {
    id: 2,
    name: "Pestisida Organik",
    seller: "CV Tani Sehat",
    category: "Obat Tanaman",
    price: 45000,
    date: "2024-01-19",
    image: "https://tse1.mm.bing.net/th/id/OIP.9mOjmZPcK9lZqoMqVdI-xQHaHa?pid=Api&P=0&h=180",
    description: "Pestisida organik ramah lingkungan untuk tanaman"
  },
  {
    id: 3,
    name: "Bibit Cabai Rawit",
    seller: "Petani Lokal",
    category: "Bibit Tanaman",
    price: 25000,
    date: "2024-01-18",
    image: "https://tse2.mm.bing.net/th/id/OIP.0Z7qV7Q6m6X6X6X6X6X6X6HaHa?pid=Api&P=0&h=180",
    description: "Bibit cabai rawit unggul tahan penyakit"
  }
];

// State variables
let currentTab = 'verification';
let currentProductId = null;
let currentSellerId = null;
let currentCategoryId = null;
let currentAction = null;

// Dashboard statistics
const dashboardStats = {
  totalProducts: 245,
  totalSellers: 89,
  pendingVerifications: 12,
  monthlyRevenue: 158000000,
  activeProducts: 220,
  activeSellers: 78,
  totalTransactions: 1245,
  totalRevenue: 1580000000
};

// Format currency
function formatCurrency(amount) {
  return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
}

function formatNumber(amount) {
  return new Intl.NumberFormat('id-ID').format(amount);
}

// Format date
function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  });
}

// Get status badge class
function getStatusClass(status) {
  switch(status) {
    case 'active':
    case 'verified': return 'status-active';
    case 'pending': return 'status-pending';
    case 'inactive':
    case 'suspended': return 'status-inactive';
    default: return 'status-pending';
  }
}

// Get status text
function getStatusText(status) {
  switch(status) {
    case 'active': return 'Aktif';
    case 'verified': return 'Terverifikasi';
    case 'pending': return 'Pending';
    case 'inactive': return 'Nonaktif';
    case 'suspended': return 'Ditangguhkan';
    default: return 'Pending';
  }
}

// Update dashboard statistics
function updateStats() {
  // Calculate real-time stats from data
  const totalProducts = adminProducts.length;
  const activeProducts = adminProducts.filter(p => p.status === 'active').length;
  const pendingProductsCount = pendingProducts.length;
  const inactiveProducts = adminProducts.filter(p => p.status === 'inactive').length;
  
  const totalSellers = sellers.length;
  const activeSellers = sellers.filter(s => s.status === 'verified').length;
  const pendingSellers = sellers.filter(s => s.status === 'pending').length;
  
  const totalRevenue = adminProducts.reduce((sum, product) => sum + (product.price * product.sales), 0);
  const totalTransactions = adminProducts.reduce((sum, product) => sum + product.sales, 0);
  
  // Update dashboard cards
  const totalProductsEl = document.getElementById('totalProducts');
  const totalSellersEl = document.getElementById('totalSellers');
  const pendingVerificationsEl = document.getElementById('pendingVerifications');
  const monthlyRevenueEl = document.getElementById('monthlyRevenue');
  
  if (totalProductsEl) totalProductsEl.textContent = formatNumber(totalProducts);
  if (totalSellersEl) totalSellersEl.textContent = formatNumber(totalSellers);
  if (pendingVerificationsEl) pendingVerificationsEl.textContent = formatNumber(pendingProductsCount);
  if (monthlyRevenueEl) monthlyRevenueEl.textContent = formatCurrency(totalRevenue);
  
  // Update stats object
  dashboardStats.totalProducts = totalProducts;
  dashboardStats.totalSellers = totalSellers;
  dashboardStats.pendingVerifications = pendingProductsCount;
  dashboardStats.monthlyRevenue = totalRevenue;
  dashboardStats.activeProducts = activeProducts;
  dashboardStats.activeSellers = activeSellers;
  dashboardStats.totalTransactions = totalTransactions;
  dashboardStats.totalRevenue = totalRevenue;
}

// Render verification table
function renderVerificationTable() {
  const tableBody = document.getElementById('verificationTable');
  const countElement = document.getElementById('verificationCount');
  
  if (!tableBody) return;
  
  tableBody.innerHTML = '';
  
  if (pendingProducts.length === 0) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-light);">
          <i class="fas fa-check-circle" style="font-size: 2rem; margin-bottom: 1rem; display: block; color: var(--success);"></i>
          Tidak ada produk yang menunggu verifikasi
        </td>
      </tr>
    `;
    if (countElement) countElement.textContent = 'Menampilkan 0 produk';
    return;
  }
  
  pendingProducts.forEach((product, index) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${index + 1}</td>
      <td>
        <img src="${product.image}" alt="${product.name}" 
             class="product-image-small"
             onerror="this.src='https://images.unsplash.com/photo-1560493676-04071c5f467b?w=100&h=100&fit=crop'">
      </td>
      <td style="font-weight: 500;">${product.name}</td>
      <td>${product.seller}</td>
      <td>${product.category}</td>
      <td>${formatCurrency(product.price)}</td>
      <td>${formatDate(product.date)}</td>
      <td>
        <div class="action-buttons">
          <button class="btn-icon btn-view" onclick="viewVerification(${product.id})" title="Lihat">
            <i class="fas fa-eye"></i>
          </button>
          <button class="btn-icon btn-verify" onclick="verifyProduct(${product.id})" title="Verifikasi">
            <i class="fas fa-check"></i>
          </button>
          <button class="btn-icon btn-delete" onclick="rejectProduct(${product.id})" title="Tolak">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </td>
    `;
    tableBody.appendChild(row);
  });
  
  if (countElement) countElement.textContent = `Menampilkan ${pendingProducts.length} produk`;
}

// Render products table
function renderProductsTable() {
  const tableBody = document.getElementById('productsTable');
  const filter = document.getElementById('productFilter')?.value || '';
  
  if (!tableBody) return;
  
  let products = adminProducts;
  
  if (filter) {
    products = products.filter(p => p.status === filter);
  }
  
  tableBody.innerHTML = '';
  
  if (products.length === 0) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="9" style="text-align: center; padding: 2rem; color: var(--text-light);">
          <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block; color: var(--primary);"></i>
          Tidak ada produk yang ditemukan
        </td>
      </tr>
    `;
    return;
  }
  
  products.forEach((product, index) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${index + 1}</td>
      <td>
        <img src="${product.image}" alt="${product.name}" 
             class="product-image-small"
             onerror="this.src='https://images.unsplash.com/photo-1560493676-04071c5f467b?w=100&h=100&fit=crop'">
      </td>
      <td style="font-weight: 500;">${product.name}</td>
      <td>${product.seller_name}</td>
      <td>${product.category}</td>
      <td>${formatCurrency(product.price)}</td>
      <td>${product.stock}</td>
      <td><span class="status-badge ${getStatusClass(product.status)}">${getStatusText(product.status)}</span></td>
      <td>
        <div class="action-buttons">
          <button class="btn-icon btn-view" onclick="viewProduct(${product.id})" title="Lihat">
            <i class="fas fa-eye"></i>
          </button>
          <button class="btn-icon btn-edit" onclick="editProduct(${product.id})" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn-icon btn-delete" onclick="deleteProduct(${product.id})" title="Hapus">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    `;
    tableBody.appendChild(row);
  });
}

// Render sellers table
function renderSellersTable() {
  const tableBody = document.getElementById('sellersTable');
  const filter = document.getElementById('sellerFilter')?.value || '';
  
  if (!tableBody) return;
  
  let filteredSellers = sellers;
  
  if (filter) {
    filteredSellers = filteredSellers.filter(s => {
      if (filter === 'verified') return s.status === 'verified';
      if (filter === 'pending') return s.status === 'pending';
      if (filter === 'suspended') return s.status === 'suspended';
      return true;
    });
  }
  
  tableBody.innerHTML = '';
  
  if (filteredSellers.length === 0) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-light);">
          <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 1rem; display: block; color: var(--primary);"></i>
          Tidak ada penjual yang ditemukan
        </td>
      </tr>
    `;
    return;
  }
  
  filteredSellers.forEach((seller, index) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${index + 1}</td>
      <td style="font-weight: 500;">${seller.name}</td>
      <td>${seller.email}</td>
      <td>${seller.phone}</td>
      <td>${seller.products} produk</td>
      <td>${formatDate(seller.join_date)}</td>
      <td><span class="status-badge ${getStatusClass(seller.status)}">${getStatusText(seller.status)}</span></td>
      <td>
        <div class="action-buttons">
          <button class="btn-icon btn-view" onclick="viewSeller(${seller.id})" title="Lihat">
            <i class="fas fa-eye"></i>
          </button>
          <button class="btn-icon btn-edit" onclick="editSeller(${seller.id})" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn-icon btn-delete" onclick="deleteSeller(${seller.id})" title="Hapus">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    `;
    tableBody.appendChild(row);
  });
}

// Render categories grid
function renderCategoriesGrid() {
  const grid = document.getElementById('categoriesGrid');
  
  if (!grid) return;
  
  grid.innerHTML = '';
  
  adminCategories.forEach(category => {
    const card = document.createElement('div');
    card.className = 'project-card';
    card.innerHTML = `
      <div class="project-content">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
          <div style="font-size: 2rem;">${category.icon}</div>
          <div>
            <div class="project-category">KATEGORI</div>
            <h3 class="project-title">${category.name}</h3>
          </div>
        </div>
        <p class="project-description">${category.description}</p>
        <div class="project-meta">
          <div class="project-stock">${category.productCount} Produk</div>
        </div>
        <div class="project-actions">
          <button class="btn btn-outline" onclick="editCategory(${category.id})">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-danger" onclick="deleteCategory(${category.id})">
            <i class="fas fa-trash"></i> Hapus
          </button>
        </div>
      </div>
    `;
    grid.appendChild(card);
  });
}

// Switch tabs
function switchTab(tabId) {
  // Update active tab button
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active');
    if (btn.dataset.tab === tabId) {
      btn.classList.add('active');
    }
  });
  
  // Update active tab content
  document.querySelectorAll('.tab-content').forEach(content => {
    content.classList.remove('active');
    if (content.id === `${tabId}-tab`) {
      content.classList.add('active');
    }
  });
  
  currentTab = tabId;
}

// View verification details
function viewVerification(productId) {
  const product = pendingProducts.find(p => p.id === productId);
  if (!product) return;
  
  document.getElementById('verificationModalBody').innerHTML = `
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
      <div>
        <img src="${product.image}" alt="${product.name}" 
             style="width: 100%; border-radius: var(--radius);">
      </div>
      <div>
        <h3 style="margin-bottom: 0.5rem; color: var(--primary);">${product.name}</h3>
        <p style="color: var(--text-light); margin-bottom: 1rem;">${product.description}</p>
        <div style="background: var(--light); padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span>Penjual:</span>
            <strong>${product.seller}</strong>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span>Kategori:</span>
            <strong>${product.category}</strong>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span>Harga:</span>
            <strong>${formatCurrency(product.price)}</strong>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span>Tanggal Upload:</span>
            <strong>${formatDate(product.date)}</strong>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="closeModal('verificationModal')">Tutup</button>
      <button class="btn btn-danger" onclick="rejectProduct(${product.id})">
        <i class="fas fa-times"></i> Tolak
      </button>
      <button class="btn btn-primary" onclick="verifyProduct(${product.id})">
        <i class="fas fa-check"></i> Verifikasi
      </button>
    </div>
  `;
  
  document.getElementById('verificationModal').classList.add('active');
}

// View product details
function viewProduct(productId) {
  const product = adminProducts.find(p => p.id === productId);
  if (!product) return;
  
  const modalHTML = `
    <div class="modal active" id="viewProductModal">
      <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
          <h2 class="modal-title">Detail Produk</h2>
          <button class="modal-close" onclick="closeModal('viewProductModal')">&times;</button>
        </div>
        <div class="modal-body">
          <div class="product-detail-admin" style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem;">
            <div>
              <img src="${product.image}" alt="${product.name}" 
                   style="width: 100%; border-radius: var(--radius); margin-bottom: 1rem;"
                   onerror="this.src='https://images.unsplash.com/photo-1560493676-04071c5f467b?w=500&h=300&fit=crop'">
              <div style="background: var(--light); padding: 1rem; border-radius: var(--radius);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                  <span style="font-weight: 500;">ID Produk:</span>
                  <span>#${product.id}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                  <span style="font-weight: 500;">Status:</span>
                  <span class="status-badge ${getStatusClass(product.status)}">${getStatusText(product.status)}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                  <span style="font-weight: 500;">Penjualan:</span>
                  <span>${product.sales} unit</span>
                </div>
              </div>
            </div>
            
            <div>
              <div style="margin-bottom: 1.5rem;">
                <div class="product-category">${product.category}</div>
                <h3 style="margin: 0.5rem 0; font-size: 1.5rem;">${product.name}</h3>
                <div style="font-size: 1.8rem; font-weight: bold; color: var(--primary);">
                  ${formatCurrency(product.price)}
                </div>
              </div>
              
              <div style="margin-bottom: 1.5rem;">
                <h4 style="margin-bottom: 0.5rem;">Deskripsi:</h4>
                <p style="color: var(--text-light); line-height: 1.6;">${product.description}</p>
              </div>
              
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: var(--light); padding: 1rem; border-radius: var(--radius);">
                  <div style="font-size: 0.9rem; color: var(--text-light);">Stok</div>
                  <div style="font-size: 1.5rem; font-weight: bold;">${product.stock}</div>
                </div>
                <div style="background: var(--light); padding: 1rem; border-radius: var(--radius);">
                  <div style="font-size: 0.9rem; color: var(--text-light);">Rating</div>
                  <div style="font-size: 1.5rem; font-weight: bold;">${product.rating}/5</div>
                  <div style="font-size: 0.8rem; color: var(--text-light);">${product.reviews} ulasan</div>
                </div>
              </div>
              
              <div style="margin-bottom: 1.5rem;">
                <h4 style="margin-bottom: 0.5rem;">Informasi Penjual:</h4>
                <div style="background: var(--light); padding: 1rem; border-radius: var(--radius);">
                  <div style="font-weight: 500; margin-bottom: 0.5rem;">${product.seller_name}</div>
                  <div style="margin-bottom: 0.3rem;">
                    <i class="fas fa-envelope"></i> ${product.seller_email}
                  </div>
                  <div style="margin-bottom: 0.3rem;">
                    <i class="fas fa-phone"></i> ${product.seller_phone}
                  </div>
                  <div>
                    <i class="fas fa-map-marker-alt"></i> ${product.seller_location}
                  </div>
                </div>
              </div>
              
              <div style="display: flex; gap: 1rem;">
                <button class="btn btn-primary" style="flex: 1;" onclick="editProduct(${product.id}); closeModal('viewProductModal')">
                  <i class="fas fa-edit"></i> Edit Produk
                </button>
                <button class="btn btn-outline" style="flex: 1;" onclick="closeModal('viewProductModal')">
                  Tutup
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
  
  const modalContainer = document.createElement('div');
  modalContainer.innerHTML = modalHTML;
  document.body.appendChild(modalContainer.firstElementChild);
}

// View seller details
function viewSeller(sellerId) {
  const seller = sellers.find(s => s.id === sellerId);
  if (!seller) return;
  
  const modalHTML = `
    <div class="modal active" id="viewSellerModal">
      <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
          <h2 class="modal-title">Detail Penjual</h2>
          <button class="modal-close" onclick="closeModal('viewSellerModal')">&times;</button>
        </div>
        <div class="modal-body">
          <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 100px; height: 100px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; margin: 0 auto 1rem;">
              ${seller.name.charAt(0)}
            </div>
            <h3 style="margin-bottom: 0.5rem;">${seller.name}</h3>
            <span class="status-badge ${getStatusClass(seller.status)}">${getStatusText(seller.status)}</span>
          </div>
          
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="background: var(--light); padding: 1rem; border-radius: var(--radius); text-align: center;">
              <div style="font-size: 0.9rem; color: var(--text-light);">Produk</div>
              <div style="font-size: 1.5rem; font-weight: bold;">${seller.products}</div>
            </div>
            <div style="background: var(--light); padding: 1rem; border-radius: var(--radius); text-align: center;">
              <div style="font-size: 0.9rem; color: var(--text-light);">Penjualan</div>
              <div style="font-size: 1.5rem; font-weight: bold;">${seller.total_sales}</div>
            </div>
            <div style="background: var(--light); padding: 1rem; border-radius: var(--radius); text-align: center;">
              <div style="font-size: 0.9rem; color: var(--text-light);">Rating</div>
              <div style="font-size: 1.5rem; font-weight: bold;">${seller.rating}/5</div>
            </div>
            <div style="background: var(--light); padding: 1rem; border-radius: var(--radius); text-align: center;">
              <div style="font-size: 0.9rem; color: var(--text-light);">Bergabung</div>
              <div style="font-size: 1rem; font-weight: bold;">${formatDate(seller.join_date)}</div>
            </div>
          </div>
          
          <div style="background: var(--light); padding: 1rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
            <h4 style="margin-bottom: 0.5rem;">Kontak:</h4>
            <div style="margin-bottom: 0.3rem;">
              <i class="fas fa-envelope"></i> ${seller.email}
            </div>
            <div style="margin-bottom: 0.3rem;">
              <i class="fas fa-phone"></i> ${seller.phone}
            </div>
            <div>
              <i class="fas fa-map-marker-alt"></i> ${seller.location}
            </div>
          </div>
          
          <div style="display: flex; gap: 1rem;">
            <button class="btn btn-primary" style="flex: 1;" onclick="editSeller(${seller.id}); closeModal('viewSellerModal')">
              <i class="fas fa-edit"></i> Edit Penjual
            </button>
            <button class="btn btn-outline" style="flex: 1;" onclick="closeModal('viewSellerModal')">
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  `;
  
  const modalContainer = document.createElement('div');
  modalContainer.innerHTML = modalHTML;
  document.body.appendChild(modalContainer.firstElementChild);
}

// Verify product
function verifyProduct(productId) {
  currentProductId = productId;
  currentAction = 'verify';
  
  document.getElementById('confirmMessage').textContent = 'Apakah Anda yakin ingin memverifikasi produk ini?';
  document.getElementById('confirmModalTitle').textContent = 'Verifikasi Produk';
  document.getElementById('confirmModal').classList.add('active');
}

// Reject product
function rejectProduct(productId) {
  currentProductId = productId;
  currentAction = 'reject';
  
  document.getElementById('confirmMessage').textContent = 'Apakah Anda yakin ingin menolak produk ini?';
  document.getElementById('confirmModalTitle').textContent = 'Tolak Produk';
  document.getElementById('confirmModal').classList.add('active');
}

// Edit product
function editProduct(productId) {
  const product = adminProducts.find(p => p.id === productId);
  if (!product) return;
  
  currentProductId = productId;
  
  document.getElementById('productModalTitle').textContent = 'Edit Produk';
  document.getElementById('productModalBody').innerHTML = `
    <form id="productForm" onsubmit="saveProduct(event)">
      <div class="form-row">
        <div class="form-group">
          <label for="productName">Nama Produk *</label>
          <input type="text" id="productName" name="productName" value="${product.name}" required>
        </div>
        <div class="form-group">
          <label for="productCategory">Kategori *</label>
          <select id="productCategory" name="productCategory" required>
            <option value="Bibit Tanaman" ${product.category === 'Bibit Tanaman' ? 'selected' : ''}>Bibit Tanaman</option>
            <option value="Pupuk Organik" ${product.category === 'Pupuk Organik' ? 'selected' : ''}>Pupuk Organik</option>
            <option value="Alat Pertanian" ${product.category === 'Alat Pertanian' ? 'selected' : ''}>Alat Pertanian</option>
            <option value="Hasil Panen" ${product.category === 'Hasil Panen' ? 'selected' : ''}>Hasil Panen</option>
            <option value="Obat Tanaman" ${product.category === 'Obat Tanaman' ? 'selected' : ''}>Obat Tanaman</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="productPrice">Harga (Rp) *</label>
          <input type="number" id="productPrice" name="productPrice" value="${product.price}" required>
        </div>
        <div class="form-group">
          <label for="productStock">Stok *</label>
          <input type="number" id="productStock" name="productStock" value="${product.stock}" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="productSeller">Penjual *</label>
          <select id="productSeller" name="productSeller" required>
            ${sellers.map(seller => `
              <option value="${seller.name}" ${product.seller_name === seller.name ? 'selected' : ''}>${seller.name}</option>
            `).join('')}
          </select>
        </div>
        <div class="form-group">
          <label for="productStatus">Status *</label>
          <select id="productStatus" name="productStatus" required>
            <option value="active" ${product.status === 'active' ? 'selected' : ''}>Aktif</option>
            <option value="pending" ${product.status === 'pending' ? 'selected' : ''}>Pending</option>
            <option value="inactive" ${product.status === 'inactive' ? 'selected' : ''}>Nonaktif</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="productDescription">Deskripsi *</label>
        <textarea id="productDescription" name="productDescription" rows="3" required>${product.description}</textarea>
      </div>

      <div class="form-group">
        <label for="productImage">URL Gambar</label>
        <input type="url" id="productImage" name="productImage" value="${product.image}">
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-outline" onclick="closeModal('productModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  `;
  
  document.getElementById('productModal').classList.add('active');
}

// Add new product
function addNewProduct() {
  currentProductId = null;
  
  document.getElementById('productModalTitle').textContent = 'Tambah Produk Baru';
  document.getElementById('productModalBody').innerHTML = `
    <form id="productForm" onsubmit="saveProduct(event)">
      <div class="form-row">
        <div class="form-group">
          <label for="productName">Nama Produk *</label>
          <input type="text" id="productName" name="productName" required>
        </div>
        <div class="form-group">
          <label for="productCategory">Kategori *</label>
          <select id="productCategory" name="productCategory" required>
            <option value="">Pilih Kategori</option>
            <option value="Bibit Tanaman">Bibit Tanaman</option>
            <option value="Pupuk Organik">Pupuk Organik</option>
            <option value="Alat Pertanian">Alat Pertanian</option>
            <option value="Hasil Panen">Hasil Panen</option>
            <option value="Obat Tanaman">Obat Tanaman</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="productPrice">Harga (Rp) *</label>
          <input type="number" id="productPrice" name="productPrice" required>
        </div>
        <div class="form-group">
          <label for="productStock">Stok *</label>
          <input type="number" id="productStock" name="productStock" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="productSeller">Penjual *</label>
          <select id="productSeller" name="productSeller" required>
            <option value="">Pilih Penjual</option>
            ${sellers.map(seller => `
              <option value="${seller.name}">${seller.name}</option>
            `).join('')}
          </select>
        </div>
        <div class="form-group">
          <label for="productStatus">Status *</label>
          <select id="productStatus" name="productStatus" required>
            <option value="active">Aktif</option>
            <option value="pending">Pending</option>
            <option value="inactive">Nonaktif</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="productDescription">Deskripsi *</label>
        <textarea id="productDescription" name="productDescription" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label for="productImage">URL Gambar</label>
        <input type="url" id="productImage" name="productImage" placeholder="https://example.com/image.jpg">
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-outline" onclick="closeModal('productModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Tambah Produk</button>
      </div>
    </form>
  `;
  
  document.getElementById('productModal').classList.add('active');
}

// Save product
function saveProduct(event) {
  event.preventDefault();
  const formData = new FormData(event.target);
  
  const productData = {
    name: formData.get('productName'),
    category: formData.get('productCategory'),
    price: parseInt(formData.get('productPrice')),
    stock: parseInt(formData.get('productStock')),
    seller_name: formData.get('productSeller'),
    status: formData.get('productStatus'),
    description: formData.get('productDescription'),
    image: formData.get('productImage') || 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=500&h=300&fit=crop'
  };
  
  if (currentProductId) {
    // Update existing product
    const index = adminProducts.findIndex(p => p.id === currentProductId);
    if (index !== -1) {
      const seller = sellers.find(s => s.name === productData.seller_name);
      adminProducts[index] = {
        ...adminProducts[index],
        ...productData,
        seller_email: seller?.email || 'seller@email.com',
        seller_phone: seller?.phone || '628123456789',
        seller_location: seller?.location || 'Lokasi Penjual'
      };
      showNotification('Produk berhasil diperbarui', 'success');
    }
  } else {
    // Add new product
    const seller = sellers.find(s => s.name === productData.seller_name);
    const newProduct = {
      id: adminProducts.length > 0 ? Math.max(...adminProducts.map(p => p.id)) + 1 : 1,
      ...productData,
      rating: 0,
      reviews: 0,
      sales: 0,
      created_at: new Date().toISOString().split('T')[0],
      seller_email: seller?.email || 'seller@email.com',
      seller_phone: seller?.phone || '628123456789',
      seller_location: seller?.location || 'Lokasi Penjual'
    };
    
    adminProducts.unshift(newProduct);
    showNotification('Produk berhasil ditambahkan', 'success');
  }
  
  closeModal('productModal');
  renderProductsTable();
  updateStats();
}

// Edit seller
function editSeller(sellerId) {
  const seller = sellers.find(s => s.id === sellerId);
  if (!seller) return;
  
  currentSellerId = sellerId;
  
  document.getElementById('sellerModalTitle').textContent = 'Edit Penjual';
  document.getElementById('sellerModalBody').innerHTML = `
    <form id="sellerForm" onsubmit="saveSeller(event)">
      <div class="form-row">
        <div class="form-group">
          <label for="sellerName">Nama *</label>
          <input type="text" id="sellerName" name="sellerName" value="${seller.name}" required>
        </div>
        <div class="form-group">
          <label for="sellerEmail">Email *</label>
          <input type="email" id="sellerEmail" name="sellerEmail" value="${seller.email}" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="sellerPhone">Telepon *</label>
          <input type="tel" id="sellerPhone" name="sellerPhone" value="${seller.phone}" required>
        </div>
        <div class="form-group">
          <label for="sellerStatus">Status *</label>
          <select id="sellerStatus" name="sellerStatus" required>
            <option value="verified" ${seller.status === 'verified' ? 'selected' : ''}>Terverifikasi</option>
            <option value="pending" ${seller.status === 'pending' ? 'selected' : ''}>Pending</option>
            <option value="suspended" ${seller.status === 'suspended' ? 'selected' : ''}>Ditangguhkan</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="sellerLocation">Lokasi *</label>
        <input type="text" id="sellerLocation" name="sellerLocation" value="${seller.location}" required>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-outline" onclick="closeModal('sellerModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  `;
  
  document.getElementById('sellerModal').classList.add('active');
}

// Add new seller
function addNewSeller() {
  currentSellerId = null;
  
  document.getElementById('sellerModalTitle').textContent = 'Tambah Penjual Baru';
  document.getElementById('sellerModalBody').innerHTML = `
    <form id="sellerForm" onsubmit="saveSeller(event)">
      <div class="form-row">
        <div class="form-group">
          <label for="sellerName">Nama *</label>
          <input type="text" id="sellerName" name="sellerName" required>
        </div>
        <div class="form-group">
          <label for="sellerEmail">Email *</label>
          <input type="email" id="sellerEmail" name="sellerEmail" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="sellerPhone">Telepon *</label>
          <input type="tel" id="sellerPhone" name="sellerPhone" required>
        </div>
        <div class="form-group">
          <label for="sellerStatus">Status *</label>
          <select id="sellerStatus" name="sellerStatus" required>
            <option value="pending">Pending</option>
            <option value="verified">Terverifikasi</option>
            <option value="suspended">Ditangguhkan</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="sellerLocation">Lokasi *</label>
        <input type="text" id="sellerLocation" name="sellerLocation" required>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-outline" onclick="closeModal('sellerModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Tambah Penjual</button>
      </div>
    </form>
  `;
  
  document.getElementById('sellerModal').classList.add('active');
}

// Save seller
function saveSeller(event) {
  event.preventDefault();
  const formData = new FormData(event.target);
  
  const sellerData = {
    name: formData.get('sellerName'),
    email: formData.get('sellerEmail'),
    phone: formData.get('sellerPhone'),
    status: formData.get('sellerStatus'),
    location: formData.get('sellerLocation')
  };
  
  if (currentSellerId) {
    // Update existing seller
    const index = sellers.findIndex(s => s.id === currentSellerId);
    if (index !== -1) {
      sellers[index] = {
        ...sellers[index],
        ...sellerData
      };
      showNotification('Penjual berhasil diperbarui', 'success');
    }
  } else {
    // Add new seller
    const newSeller = {
      id: sellers.length > 0 ? Math.max(...sellers.map(s => s.id)) + 1 : 1,
      ...sellerData,
      products: 0,
      join_date: new Date().toISOString().split('T')[0],
      rating: 0,
      total_sales: 0
    };
    
    sellers.push(newSeller);
    showNotification('Penjual berhasil ditambahkan', 'success');
  }
  
  closeModal('sellerModal');
  renderSellersTable();
  updateStats();
}

// Edit category
function editCategory(categoryId) {
  const category = adminCategories.find(c => c.id === categoryId);
  if (!category) return;
  
  currentCategoryId = categoryId;
  
  document.getElementById('categoryModalTitle').textContent = 'Edit Kategori';
  document.getElementById('categoryModalBody').innerHTML = `
    <form id="categoryForm" onsubmit="saveCategory(event)">
      <div class="form-row">
        <div class="form-group">
          <label for="categoryName">Nama Kategori *</label>
          <input type="text" id="categoryName" name="categoryName" value="${category.name}" required>
        </div>
        <div class="form-group">
          <label for="categoryIcon">Icon</label>
          <input type="text" id="categoryIcon" name="categoryIcon" value="${category.icon}">
        </div>
      </div>

      <div class="form-group">
        <label for="categoryDescription">Deskripsi *</label>
        <textarea id="categoryDescription" name="categoryDescription" rows="3" required>${category.description}</textarea>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-outline" onclick="closeModal('categoryModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  `;
  
  document.getElementById('categoryModal').classList.add('active');
}

// Add new category
function addNewCategory() {
  currentCategoryId = null;
  
  document.getElementById('categoryModalTitle').textContent = 'Tambah Kategori Baru';
  document.getElementById('categoryModalBody').innerHTML = `
    <form id="categoryForm" onsubmit="saveCategory(event)">
      <div class="form-row">
        <div class="form-group">
          <label for="categoryName">Nama Kategori *</label>
          <input type="text" id="categoryName" name="categoryName" required>
        </div>
        <div class="form-group">
          <label for="categoryIcon">Icon</label>
          <input type="text" id="categoryIcon" name="categoryIcon" placeholder="🌱">
        </div>
      </div>

      <div class="form-group">
        <label for="categoryDescription">Deskripsi *</label>
        <textarea id="categoryDescription" name="categoryDescription" rows="3" required></textarea>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn btn-outline" onclick="closeModal('categoryModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Tambah Kategori</button>
      </div>
    </form>
  `;
  
  document.getElementById('categoryModal').classList.add('active');
}

// Save category
function saveCategory(event) {
  event.preventDefault();
  const formData = new FormData(event.target);
  
  const categoryData = {
    name: formData.get('categoryName'),
    icon: formData.get('categoryIcon') || '📦',
    description: formData.get('categoryDescription'),
    productCount: Math.floor(Math.random() * 50) + 10 // Random product count for demo
  };
  
  if (currentCategoryId) {
    // Update existing category
    const index = adminCategories.findIndex(c => c.id === currentCategoryId);
    if (index !== -1) {
      adminCategories[index] = {
        ...adminCategories[index],
        ...categoryData
      };
      showNotification('Kategori berhasil diperbarui', 'success');
    }
  } else {
    // Add new category
    const newCategory = {
      id: adminCategories.length > 0 ? Math.max(...adminCategories.map(c => c.id)) + 1 : 1,
      ...categoryData
    };
    
    adminCategories.push(newCategory);
    showNotification('Kategori berhasil ditambahkan', 'success');
  }
  
  closeModal('categoryModal');
  renderCategoriesGrid();
}

// Delete functions
function deleteProduct(productId) {
  currentProductId = productId;
  currentAction = 'deleteProduct';
  
  document.getElementById('deleteMessage').textContent = 'Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.';
  document.getElementById('deleteModal').classList.add('active');
}

function deleteSeller(sellerId) {
  currentSellerId = sellerId;
  currentAction = 'deleteSeller';
  
  document.getElementById('deleteMessage').textContent = 'Apakah Anda yakin ingin menghapus penjual ini? Semua produk yang terkait juga akan dihapus.';
  document.getElementById('deleteModal').classList.add('active');
}

function deleteCategory(categoryId) {
  currentCategoryId = categoryId;
  currentAction = 'deleteCategory';
  
  document.getElementById('deleteMessage').textContent = 'Apakah Anda yakin ingin menghapus kategori ini? Semua produk dalam kategori ini akan dipindahkan ke kategori lain.';
  document.getElementById('deleteModal').classList.add('active');
}

// Close modal
function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('active');
  }
}

// Show notification
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.innerHTML = `
    <div class="notification-content">
      <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
      <span>${message}</span>
    </div>
    <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
  `;
  
  // Add styles if not already added
  if (!document.getElementById('admin-notification-styles')) {
    const styles = document.createElement('style');
    styles.id = 'admin-notification-styles';
    styles.textContent = `
      .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: var(--radius);
        background: var(--white);
        box-shadow: var(--shadow-hover);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 1rem;
        max-width: 400px;
        animation: slideIn 0.3s ease;
      }
      .notification-success {
        border-left: 4px solid #4CAF50;
      }
      .notification-error {
        border-left: 4px solid #f44336;
      }
      .notification-info {
        border-left: 4px solid var(--primary);
      }
      .notification-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }
      .notification-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: var(--text-light);
      }
      @keyframes slideIn {
        from {
          transform: translateX(100%);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }
    `;
    document.head.appendChild(styles);
  }
  
  document.body.appendChild(notification);
  
  // Auto remove after 5 seconds
  setTimeout(() => {
    if (notification.parentElement) {
      notification.remove();
    }
  }, 5000);
}

// Initialize admin page
document.addEventListener('DOMContentLoaded', () => {
  // Update stats
  updateStats();
  
  // Render initial tables
  renderVerificationTable();
  renderProductsTable();
  renderSellersTable();
  renderCategoriesGrid();
  
  // Tab click handlers
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      switchTab(btn.dataset.tab);
    });
  });
  
  // Filter change handlers
  document.getElementById('productFilter')?.addEventListener('change', renderProductsTable);
  document.getElementById('sellerFilter')?.addEventListener('change', renderSellersTable);
  
  // Button click handlers
  document.getElementById('addProductBtn')?.addEventListener('click', addNewProduct);
  document.getElementById('addSellerBtn')?.addEventListener('click', addNewSeller);
  document.getElementById('addCategoryBtn')?.addEventListener('click', addNewCategory);
  
  // Modal close handlers
  document.getElementById('verificationModalClose')?.addEventListener('click', () => closeModal('verificationModal'));
  document.getElementById('productModalClose')?.addEventListener('click', () => closeModal('productModal'));
  document.getElementById('sellerModalClose')?.addEventListener('click', () => closeModal('sellerModal'));
  document.getElementById('categoryModalClose')?.addEventListener('click', () => closeModal('categoryModal'));
  document.getElementById('confirmModalClose')?.addEventListener('click', () => closeModal('confirmModal'));
  document.getElementById('deleteModalClose')?.addEventListener('click', () => closeModal('deleteModal'));
  document.getElementById('cancelConfirm')?.addEventListener('click', () => closeModal('confirmModal'));
  document.getElementById('cancelDelete')?.addEventListener('click', () => closeModal('deleteModal'));
  
  // Confirm action handler
  document.getElementById('submitConfirm')?.addEventListener('click', () => {
    if (currentAction === 'verify') {
      // Move product from pending to adminProducts
      const productIndex = pendingProducts.findIndex(p => p.id === currentProductId);
      if (productIndex !== -1) {
        const product = pendingProducts[productIndex];
        const seller = sellers.find(s => s.name === product.seller);
        
        adminProducts.unshift({
          id: adminProducts.length > 0 ? Math.max(...adminProducts.map(p => p.id)) + 1 : 1,
          name: product.name,
          description: product.description,
          price: product.price,
          stock: 100,
          image: product.image,
          category: product.category,
          seller_name: product.seller,
          seller_email: seller?.email || 'seller@email.com',
          seller_phone: seller?.phone || '628123456789',
          seller_location: seller?.location || 'Lokasi Penjual',
          rating: 0,
          reviews: 0,
          sales: 0,
          created_at: new Date().toISOString().split('T')[0],
          status: 'active'
        });
        
        pendingProducts.splice(productIndex, 1);
        showNotification('Produk berhasil diverifikasi!', 'success');
      }
    } else if (currentAction === 'reject') {
      // Remove product from pending
      const productIndex = pendingProducts.findIndex(p => p.id === currentProductId);
      if (productIndex !== -1) {
        pendingProducts.splice(productIndex, 1);
        showNotification('Produk berhasil ditolak!', 'success');
      }
    }
    
    closeModal('confirmModal');
    renderVerificationTable();
    updateStats();
  });
  
  // Delete confirm handler
  document.getElementById('confirmDelete')?.addEventListener('click', () => {
    switch(currentTab) {
      case 'products':
      case 'verification':
        if (currentAction === 'deleteProduct') {
          const productIndex = adminProducts.findIndex(p => p.id === currentProductId);
          if (productIndex !== -1) {
            adminProducts.splice(productIndex, 1);
            showNotification('Produk berhasil dihapus!', 'success');
          }
        }
        break;
      case 'sellers':
        if (currentAction === 'deleteSeller') {
          const sellerIndex = sellers.findIndex(s => s.id === currentSellerId);
          if (sellerIndex !== -1) {
            // Also remove products from this seller
            const sellerName = sellers[sellerIndex].name;
            for (let i = adminProducts.length - 1; i >= 0; i--) {
              if (adminProducts[i].seller_name === sellerName) {
                adminProducts.splice(i, 1);
              }
            }
            
            sellers.splice(sellerIndex, 1);
            showNotification('Penjual berhasil dihapus!', 'success');
          }
        }
        break;
      case 'categories':
        if (currentAction === 'deleteCategory') {
          const categoryIndex = adminCategories.findIndex(c => c.id === currentCategoryId);
          if (categoryIndex !== -1) {
            const categoryName = adminCategories[categoryIndex].name;
            
            // Move products to default category
            adminProducts.forEach(product => {
              if (product.category === categoryName) {
                product.category = 'Bibit Tanaman'; // Default category
              }
            });
            
            adminCategories.splice(categoryIndex, 1);
            showNotification('Kategori berhasil dihapus!', 'success');
          }
        }
        break;
    }
    
    closeModal('deleteModal');
    
    // Refresh the appropriate view
    switch(currentTab) {
      case 'products':
        renderProductsTable();
        break;
      case 'verification':
        renderVerificationTable();
        break;
      case 'sellers':
        renderSellersTable();
        break;
      case 'categories':
        renderCategoriesGrid();
        break;
    }
    
    updateStats();
  });
  
  // Close modals when clicking outside
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.remove('active');
      }
    });
  });
});

// Export functions for global use
window.switchTab = switchTab;
window.viewVerification = viewVerification;
window.verifyProduct = verifyProduct;
window.rejectProduct = rejectProduct;
window.viewProduct = viewProduct;
window.editProduct = editProduct;
window.addNewProduct = addNewProduct;
window.deleteProduct = deleteProduct;
window.viewSeller = viewSeller;
window.editSeller = editSeller;
window.addNewSeller = addNewSeller;
window.deleteSeller = deleteSeller;
window.editCategory = editCategory;
window.addNewCategory = addNewCategory;
window.deleteCategory = deleteCategory;
window.closeModal = closeModal;
window.saveProduct = saveProduct;
window.saveSeller = saveSeller;
window.saveCategory = saveCategory;
