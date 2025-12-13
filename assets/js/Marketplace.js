const products = [
  {
    product_id: 1,
    product_name: 'Bibit Padi Unggul',
    description: 'Bibit padi unggul dengan hasil panen maksimal, tahan hama dan penyakit',
    price: 50000,
    stock_quantity: 100,
    image_path: 'https://tse2.mm.bing.net/th/id/OIP.QgC2fruskE2Lx-2z8UQs-AHaHa?pid=Api&P=0&h=180',
    category_name: 'Bibit Tanaman',
    seller_name: 'Budi Santoso',
    contact_phone: '628123456789',
    seller_location: 'Karawang, Jawa Barat',
    rating: 4.5,
    reviews: 24,
    created_at: '2024-01-15',
    is_my_product: false
  },
  {
    product_id: 2,
    product_name: 'Pupuk Organik Cair',
    description: 'Pupuk organik cair untuk meningkatkan kesuburan tanah dan pertumbuhan tanaman',
    price: 75000,
    stock_quantity: 50,
    image_path: 'https://lzd-img-global.slatic.net/g/p/ae7d2d75882018e60832e0c1a3e3b61b.jpg_720x720q80.jpg_.webp',
    category_name: 'Pupuk Organik',
    seller_name: 'Sari Dewi',
    contact_phone: '628987654321',
    seller_location: 'Bogor, Jawa Barat',
    rating: 4.8,
    reviews: 31,
    created_at: '2024-01-10',
    is_my_product: false
  },
  {
    product_id: 3,
    product_name: 'Traktor Mini',
    description: 'Traktor mini untuk lahan pertanian kecil dan menengah, mudah dioperasikan',
    price: 12500000,
    stock_quantity: 5,
    image_path: 'https://image.ceneostatic.pl/data/products/109749301/i-mini-traktor-traktorek-kubota-5001-nowy-20000netto.jpg',
    category_name: 'Alat Pertanian',
    seller_name: 'Budi Santoso',
    contact_phone: '628123456789',
    seller_location: 'Karawang, Jawa Barat',
    rating: 4.3,
    reviews: 8,
    created_at: '2024-01-05',
    is_my_product: false
  },
  {
    product_id: 4,
    product_name: 'Benih Jagung Manis',
    description: 'Benih jagung manis hibrida dengan rasa manis alami. Hasil panen melimpah dengan umur panen 65-70 hari.',
    price: 35000,
    stock_quantity: 200,
    image_path: 'https://tse4.mm.bing.net/th/id/OIP.XE0Wmh2QnHzFHSfRxUv-jgHaHa?pid=Api&P=0&h=180',
    category_name: 'Bibit Tanaman',
    seller_name: 'Agus Setiawan',
    contact_phone: '628555123456',
    seller_location: 'Magelang, Jawa Tengah',
    rating: 4.6,
    reviews: 42,
    created_at: '2024-01-12',
    is_my_product: false
  },
  {
    product_id: 5,
    product_name: 'Alat Penyiram Otomatis',
    description: 'Sistem penyiraman otomatis dengan timer digital untuk efisiensi waktu dan air. Cocok untuk greenhouse dan kebun.',
    price: 450000,
    stock_quantity: 25,
    image_path: 'https://cf.shopee.co.id/file/bccb478ccd517bf1204cff96f2299de0',
    category_name: 'Alat Pertanian',
    seller_name: 'CV Agro Teknik',
    contact_phone: '628112233445',
    seller_location: 'Surabaya, Jawa Timur',
    rating: 4.4,
    reviews: 17,
    created_at: '2024-01-08',
    is_my_product: false
  },
  {
    product_id: 6,
    product_name: 'Pupuk Kompos 5kg',
    description: 'Pupuk kompos organik dari kotoran sapi yang sudah difermentasi. Memperbaiki struktur tanah dan meningkatkan kesuburan.',
    price: 35000,
    stock_quantity: 150,
    image_path: 'https://tse1.mm.bing.net/th/id/OIP.sJ42iNUhTT4h8q-JMym5DwHaHc?pid=Api&P=0&h=180',
    category_name: 'Pupuk Organik',
    seller_name: 'Petani Organik',
    contact_phone: '628777888999',
    seller_location: 'Yogyakarta',
    rating: 4.7,
    reviews: 56,
    created_at: '2024-01-03',
    is_my_product: false
  },
  {
    product_id: 7,
    product_name: 'Beras Organik Premium 5kg',
    description: 'Beras organik hasil panen petani lokal dengan proses penggilingan tradisional. Tanpa bahan kimia dan pengawet.',
    price: 125000,
    stock_quantity: 80,
    image_path: 'https://tse2.mm.bing.net/th/id/OIP.Jn6i8MSVfAhUaUQLR-zzxQHaHa?pid=Api&P=0&h=180',
    category_name: 'Hasil Panen',
    seller_name: 'Koperasi Tani Maju',
    contact_phone: '628112233456',
    seller_location: 'Klaten, Jawa Tengah',
    rating: 4.9,
    reviews: 89,
    created_at: '2024-01-20',
    is_my_product: false
  },
  {
    product_id: 8,
    product_name: 'Alat Panen Padi Modern',
    description: 'Alat panen padi multifungsi yang dapat memotong, merontokkan, dan mengemas gabah sekaligus.',
    price: 18500000,
    stock_quantity: 3,
    image_path: 'https://tse2.mm.bing.net/th/id/OIP.QlSlWYn9L0bC5fBIdvLKjwHaFj?pid=Api&P=0&h=180',
    category_name: 'Alat Pertanian',
    seller_name: 'CV Agro Teknik',
    contact_phone: '628112233445',
    seller_location: 'Surabaya, Jawa Timur',
    rating: 4.2,
    reviews: 5,
    created_at: '2024-01-18',
    is_my_product: false
  }
];

// Categories mapping
const categoryNames = {
  '1': 'Bibit Tanaman',
  '2': 'Pupuk Organik',
  '3': 'Alat Pertanian',
  '4': 'Hasil Panen',
  '5': 'Obat Tanaman'
};

// Data produk saya (disimpan di localStorage)
let myProducts = JSON.parse(localStorage.getItem('myProducts')) || [];

// Variabel global
let filteredProducts = [...products];
let newProductData = null;
let editingProductId = null;
let searchTimeout = null;

// Format price
function formatPrice(price) {
  return new Intl.NumberFormat('id-ID').format(price);
}

// Get initials from name
function getInitials(name) {
  if (!name) return 'GU';
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}

// Get star rating HTML
function getStarRating(rating) {
  const fullStars = Math.floor(rating);
  const halfStar = rating % 1 >= 0.5;
  const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
  
  return '★'.repeat(fullStars) + (halfStar ? '½' : '') + '☆'.repeat(emptyStars);
}

// Get stock status class
function getStockStatus(stock) {
  if (stock > 50) return 'in-stock';
  if (stock > 0) return 'limited-stock';
  return 'out-of-stock';
}

// Get stock status text
function getStockText(stock) {
  if (stock > 50) return 'Tersedia';
  if (stock > 0) return 'Terbatas';
  return 'Habis';
}

// Fungsi untuk switch tab
function switchTab(tabName) {
  // Update tab buttons
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  event.target.classList.add('active');
  
  // Show/hide sections
  if (tabName === 'allProducts') {
    document.getElementById('allProducts').style.display = 'block';
    document.getElementById('myProducts').classList.remove('active');
    document.getElementById('filterSection').style.display = 'flex';
    filterProducts();
  } else {
    document.getElementById('allProducts').style.display = 'none';
    document.getElementById('myProducts').classList.add('active');
    document.getElementById('filterSection').style.display = 'none';
    loadMyProducts();
  }
}

// Fungsi untuk memuat produk saya
function loadMyProducts() {
  const myProductsGrid = document.getElementById('myProductsGrid');
  const noProductsMessage = document.getElementById('noProductsMessage');
  const totalMyProducts = document.getElementById('totalMyProducts');
  
  // Update total produk
  if (totalMyProducts) {
    totalMyProducts.textContent = myProducts.length;
  }
  
  if (myProducts.length === 0) {
    if (myProductsGrid) myProductsGrid.innerHTML = '';
    if (noProductsMessage) noProductsMessage.style.display = 'block';
    return;
  }
  
  if (noProductsMessage) {
    noProductsMessage.style.display = 'none';
  }
  
  if (myProductsGrid) {
    // Tampilkan produk saya
    myProductsGrid.innerHTML = myProducts.map(product => `
      <div class="my-product-card">
        <img src="${product.image_path}" alt="${product.product_name}" class="my-product-image" 
             onerror="this.src='https://images.unsplash.com/photo-1560493676-04071c5f467b?w=500&h=300&fit=crop'">
        <div class="my-product-content">
          <div class="my-product-category">${product.category_name}</div>
          <h3 class="my-product-title">${product.product_name}</h3>
          <p class="my-product-description">${product.description}</p>
          <div class="my-product-meta">
            <div class="my-product-price">Rp ${formatPrice(product.price)}</div>
            <div class="my-product-stock">
              Stok: ${product.stock_quantity}
            </div>
          </div>
          <div class="my-product-actions">
            <button class="action-btn edit-btn" onclick="editProduct(${product.product_id})">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button class="action-btn delete-btn" onclick="deleteProduct(${product.product_id})">
              <i class="fas fa-trash"></i> Hapus
            </button>
          </div>
        </div>
      </div>
    `).join('');
  }
}

// Fungsi untuk view produk saya dari preview modal
function viewMyProducts() {
  switchTab('myProducts');
  closePreviewModal();
}

// Contact seller via WhatsApp
function contactSeller(phone, productName) {
  const message = `Halo, saya tertarik dengan produk "${productName}" yang dijual di NISEVA AGRO. Bisakah saya mendapatkan informasi lebih lanjut?`;
  const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
  window.open(url, '_blank');
}

// Share product
function shareProduct(productId, productName) {
  const product = [...products, ...myProducts].find(p => p.product_id === productId);
  if (!product) return;
  
  const shareUrl = `${window.location.origin}${window.location.pathname}?product=${productId}`;
  const shareText = `Lihat produk "${productName}" di Belanja Peralatan NISEVA AGRO: ${shareUrl}`;
  
  if (navigator.share) {
    navigator.share({
      title: productName,
      text: `Lihat produk "${productName}" di Belanja Peralatan NISEVA AGRO`,
      url: shareUrl
    }).catch(() => {
      copyToClipboard(shareText, 'Link produk berhasil disalin ke clipboard!');
    });
  } else {
    copyToClipboard(shareText, 'Link produk berhasil disalin ke clipboard!');
  }
}

// Fungsi untuk filter produk
function filterProducts() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase();
  const categoryFilter = document.getElementById('categoryFilter').value;
  const productsGrid = document.getElementById('productsGrid');
  
  if (!productsGrid) return;
  
  // Gabungkan semua produk (produk default + produk saya)
  const allProducts = [...products, ...myProducts];
  
  // Filter produk
  filteredProducts = allProducts.filter(product => {
    const matchesSearch = !searchTerm || 
      product.product_name.toLowerCase().includes(searchTerm) || 
      product.description.toLowerCase().includes(searchTerm);
    
    const matchesCategory = !categoryFilter || 
      product.category_name === categoryNames[categoryFilter];
    
    return matchesSearch && matchesCategory;
  });
  
  // Update tampilan
  if (filteredProducts.length === 0) {
    productsGrid.innerHTML = `
      <div style="text-align: center; padding: 3rem; color: var(--text-light); background: var(--white); border-radius: var(--radius);">
        <h3>Tidak ada produk ditemukan</h3>
        <p>Coba ubah filter pencarian Anda</p>
      </div>
    `;
  } else {
    productsGrid.innerHTML = filteredProducts.map(product => `
      <div class="product-card">
        <img src="${product.image_path}" alt="${product.product_name}" class="product-image" 
             onerror="this.src='https://images.unsplash.com/photo-1560493676-04071c5f467b?w=500&h=300&fit=crop'">
        <div class="product-content">
          <div>
            <div class="product-category">${product.category_name}</div>
            <h2 class="product-title">${product.product_name}</h2>
            <p class="product-description">${product.description}</p>
            <div class="product-meta">
              <div class="product-price">Rp ${formatPrice(product.price)}</div>
              <div class="product-stock ${getStockStatus(product.stock_quantity)}">
                ${getStockText(product.stock_quantity)}: ${product.stock_quantity}
              </div>
            </div>
            ${product.rating ? `
            <div class="product-rating" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
              <span class="stars" style="color: #FFD700;">${getStarRating(product.rating)}</span>
              <span class="rating-text" style="color: var(--text-light); font-size: 0.9rem;">${product.rating} (${product.reviews || 0} ulasan)</span>
            </div>
            ` : ''}
          </div>
          <div>
            <div class="seller-info">
              <div class="seller-avatar">${getInitials(product.seller_name)}</div>
              <div class="seller-details">
                <h4>${product.seller_name}</h4>
                <p><i class="fas fa-phone"></i> ${product.contact_phone}</p>
                ${product.seller_location ? `<small style="color: var(--text-light);"><i class="fas fa-map-marker-alt"></i> ${product.seller_location}</small>` : ''}
              </div>
            </div>
            <button class="whatsapp-btn" onclick="contactSeller('${product.contact_phone}', '${product.product_name.replace(/'/g, "\\'")}')">
              <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
            </button>
            <div class="product-actions" style="display: flex; gap: 0.5rem; margin-top: 1rem;">
              <button class="btn-secondary" onclick="viewProductDetail(${product.product_id})" 
                      style="flex: 1; padding: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="fas fa-eye"></i> Detail
              </button>
              <button class="btn-secondary" onclick="shareProduct(${product.product_id}, '${product.product_name.replace(/'/g, "\\'")}')" 
                      style="flex: 1; padding: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="fas fa-share"></i> Bagikan
              </button>
            </div>
          </div>
        </div>
      </div>
    `).join('');
  }
}

// View product detail
function viewProductDetail(productId) {
  const allProducts = [...products, ...myProducts];
  const product = allProducts.find(p => p.product_id === productId);
  
  if (!product) return;
  
  // Create modal HTML
  const modalHTML = `
    <div class="modal active" id="productDetailModal">
      <div class="modal-content" style="max-width: 800px;">
        <button class="close-modal" onclick="closeProductModal()">&times;</button>
        <div class="product-detail" style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem;">
          <div class="product-detail-images">
            <img src="${product.image_path}" alt="${product.product_name}" 
                 onerror="this.src='https://images.unsplash.com/photo-1560493676-04071c5f467b?w=500&h=300&fit=crop'"
                 style="width: 100%; height: 300px; object-fit: cover; border-radius: var(--radius);">
          </div>
          <div class="product-detail-info">
            <div class="product-category">${product.category_name}</div>
            <h2 style="margin-bottom: 1rem;">${product.product_name}</h2>
            <div class="product-price-large" style="font-size: 2rem; font-weight: bold; color: var(--primary); margin-bottom: 1rem;">
              Rp ${formatPrice(product.price)}
            </div>
            <div class="product-stock-status ${getStockStatus(product.stock_quantity)}" 
                 style="background: var(--light); padding: 0.5rem 1rem; border-radius: 20px; display: inline-block; margin-bottom: 1rem;">
              ${getStockText(product.stock_quantity)} - Stok: ${product.stock_quantity}
            </div>
            <div class="product-description-full" style="margin-bottom: 1.5rem;">
              <h4 style="margin-bottom: 0.5rem;">Deskripsi Produk:</h4>
              <p style="color: var(--text-light); line-height: 1.6;">${product.description}</p>
            </div>
            <div class="seller-info-detailed" style="margin-bottom: 1.5rem;">
              <h4 style="margin-bottom: 0.5rem;">Informasi Penjual:</h4>
              <div class="seller-detail" style="display: flex; align-items: center; gap: 1rem;">
                <div class="seller-avatar">${getInitials(product.seller_name)}</div>
                <div>
                  <h5 style="margin-bottom: 0.2rem;">${product.seller_name}</h5>
                  <p style="margin-bottom: 0.2rem;"><i class="fas fa-phone"></i> ${product.contact_phone}</p>
                  ${product.seller_location ? `<p style="margin-bottom: 0;"><i class="fas fa-map-marker-alt"></i> ${product.seller_location}</p>` : ''}
                </div>
              </div>
            </div>
            ${product.rating ? `
            <div class="product-rating-detail" style="margin-bottom: 2rem;">
              <h4 style="margin-bottom: 0.5rem;">Rating & Ulasan:</h4>
              <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 1.5rem; color: #FFD700;">${getStarRating(product.rating)}</div>
                <div>
                  <div style="font-weight: bold; font-size: 1.2rem;">${product.rating}/5</div>
                  <div style="color: var(--text-light);">${product.reviews} ulasan</div>
                </div>
              </div>
            </div>
            ` : ''}
            <div class="product-actions-detailed" style="display: flex; gap: 1rem;">
              <button class="whatsapp-btn" onclick="contactSeller('${product.contact_phone}', '${product.product_name.replace(/'/g, "\\'")}')" 
                      style="flex: 2;">
                <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
              </button>
              <button class="btn-secondary" onclick="shareProduct(${product.product_id}, '${product.product_name.replace(/'/g, "\\'")}')" 
                      style="flex: 1;">
                <i class="fas fa-share"></i> Bagikan
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // Add modal to body
  const modalContainer = document.createElement('div');
  modalContainer.innerHTML = modalHTML;
  document.body.appendChild(modalContainer.firstElementChild);
}

// Close product detail modal
function closeProductModal() {
  const modal = document.getElementById('productDetailModal');
  if (modal) {
    modal.remove();
  }
}

// Copy text to clipboard
function copyToClipboard(text, successMessage) {
  const tempInput = document.createElement('input');
  tempInput.value = text;
  document.body.appendChild(tempInput);
  tempInput.select();
  tempInput.setSelectionRange(0, 99999);
  
  try {
    document.execCommand('copy');
    showNotification(successMessage, 'success');
  } catch (err) {
    console.error('Gagal menyalin: ', err);
    showNotification('Gagal menyalin ke clipboard. Silakan salin manual: ' + text, 'error');
  }
  
  document.body.removeChild(tempInput);
}

// Handle form submission
function handleSubmit(event) {
  event.preventDefault();
  
  const form = event.target;
  const formData = new FormData(form);
  
  // Validasi sederhana
  let isValid = true;
  const requiredFields = ['product_name', 'category_id', 'price', 'stock_quantity', 'description', 'contact_phone'];
  
  requiredFields.forEach(field => {
    if (!formData.get(field)) {
      isValid = false;
    }
  });
  
  const errorMessage = document.getElementById('errorMessage');
  if (!isValid) {
    if (errorMessage) {
      errorMessage.style.display = 'block';
    }
    return;
  }
  
  // Validasi nomor telepon
  const phone = formData.get('contact_phone');
  if (!phone.startsWith('628')) {
    if (errorMessage) {
      errorMessage.innerHTML = '<i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i> Nomor WhatsApp harus diawali dengan 628';
      errorMessage.style.display = 'block';
    }
    return;
  }
  
  // Get category name
  const categoryId = formData.get('category_id');
  const categoryName = categoryNames[categoryId] || 'Bibit Tanaman';
  
  // Generate new product ID untuk produk saya
  const newProductId = myProducts.length > 0 ? Math.max(...myProducts.map(p => p.product_id)) + 1 : 1000;
  
  // Create new product
  const newProduct = {
    product_id: newProductId,
    product_name: formData.get('product_name'),
    description: formData.get('description'),
    price: parseInt(formData.get('price')),
    stock_quantity: parseInt(formData.get('stock_quantity')),
    image_path: formData.get('image_path') || 'https://images.unsplash.com/photo-1586771107445-d3ca888129ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
    category_name: categoryName,
    seller_name: 'Guest User',
    contact_phone: phone,
    seller_location: 'Lokasi Anda',
    rating: 0,
    reviews: 0,
    created_at: new Date().toISOString().split('T')[0],
    is_my_product: true
  };
  
  // Tambahkan ke myProducts
  myProducts.unshift(newProduct);
  
  // Simpan ke localStorage
  localStorage.setItem('myProducts', JSON.stringify(myProducts));
  
  // Simpan data produk baru untuk preview
  newProductData = newProduct;
  
  // Tutup modal form
  closeSellModal();
  
  // Tampilkan preview modal
  setTimeout(() => {
    showProductPreview(newProduct);
  }, 500);
}

// Show product preview modal
function showProductPreview(productData) {
  const previewModal = document.getElementById('previewModal');
  const previewProductName = document.getElementById('previewProductName');
  const previewCategory = document.getElementById('previewCategory');
  const previewPrice = document.getElementById('previewPrice');
  const previewStock = document.getElementById('previewStock');
  const previewContact = document.getElementById('previewContact');
  const previewDescription = document.getElementById('previewDescription');
  const previewProductImage = document.getElementById('previewProductImage');
  const shareWhatsAppBtn = document.getElementById('shareWhatsAppBtn');
  
  if (!previewModal || !previewProductName) return;
  
  // Set nilai preview
  previewProductName.textContent = productData.product_name;
  previewCategory.textContent = productData.category_name;
  previewPrice.textContent = `Rp ${formatPrice(productData.price)}`;
  previewStock.textContent = `${productData.stock_quantity} unit`;
  previewContact.textContent = productData.contact_phone;
  previewDescription.textContent = productData.description;
  previewProductImage.src = productData.image_path;
  
  // Update WhatsApp share button
  if (shareWhatsAppBtn) {
    shareWhatsAppBtn.onclick = function() {
      shareProductViaWhatsApp(productData);
    };
  }
  
  // Tampilkan modal preview
  previewModal.classList.add('active');
}

// Share product via WhatsApp from preview
function shareProductViaWhatsApp(productData) {
  const message = `🔥 *IKLAN PRODUK BARU DI NISEVA AGRO* 🔥

📦 *${productData.product_name}*
🏷️ *Kategori:* ${productData.category_name}
💰 *Harga:* Rp ${formatPrice(productData.price)}
📊 *Stok:* ${productData.stock_quantity} unit
📞 *Hubungi:* ${productData.contact_phone}

📝 *Deskripsi:*
${productData.description}

🎯 *Jual produk pertanian Anda juga di Belanja Peralatan NISEVA AGRO!*
👉 ${window.location.origin}`;
  
  const url = `https://wa.me/?text=${encodeURIComponent(message)}`;
  window.open(url, '_blank');
}

// Copy iklan link
function copyIklanLink() {
  if (!newProductData) return;
  
  const link = `${window.location.origin}/produk/${newProductData.product_name.toLowerCase().replace(/\s+/g, '-')}`;
  copyToClipboard(link, 'Link iklan berhasil disalin ke clipboard!');
}

// Close preview modal
function closePreviewModal() {
  const previewModal = document.getElementById('previewModal');
  if (!previewModal) return;
  
  previewModal.classList.remove('active');
  
  // Refresh tampilan produk
  filterProducts();
  loadMyProducts();
  newProductData = null;
}

// Show notification
function showNotification(message, type = 'info') {
  // Create notification element
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.innerHTML = `
    <div class="notification-content">
      <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
      <span>${message}</span>
    </div>
    <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
  `;
  
  // Add styles if not already added
  if (!document.getElementById('notification-styles')) {
    const styles = document.createElement('style');
    styles.id = 'notification-styles';
    styles.textContent = `
      .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: var(--radius);
        background: white;
        box-shadow: var(--shadow-hover);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 1rem;
        max-width: 400px;
        animation: slideIn 0.3s ease;
        border-left: 4px solid ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : 'var(--primary)'};
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

// Fungsi untuk edit produk
function editProduct(productId) {
  const product = myProducts.find(p => p.product_id === productId);
  if (!product) return;
  
  editingProductId = productId;
  
  // Isi form edit dengan data produk
  document.getElementById('edit_product_id').value = product.product_id;
  document.getElementById('edit_product_name').value = product.product_name;
  document.getElementById('edit_description').value = product.description;
  document.getElementById('edit_price').value = product.price;
  document.getElementById('edit_stock_quantity').value = product.stock_quantity;
  document.getElementById('edit_image_path').value = product.image_path;
  document.getElementById('edit_contact_phone').value = product.contact_phone;
  
  // Set kategori (konversi dari nama ke id)
  const categoryIds = {
    'Bibit Tanaman': '1',
    'Pupuk Organik': '2',
    'Alat Pertanian': '3',
    'Hasil Panen': '4',
    'Obat Tanaman': '5'
  };
  const editCategorySelect = document.getElementById('edit_category_id');
  if (editCategorySelect) {
    editCategorySelect.value = categoryIds[product.category_name] || '';
  }
  
  // Tampilkan modal edit
  const editModal = document.getElementById('editModal');
  if (editModal) {
    editModal.classList.add('active');
  }
}

// Fungsi untuk menangani submit form edit
function handleEditSubmit(event) {
  event.preventDefault();
  
  const form = event.target;
  const formData = new FormData(form);
  
  // Validasi sederhana
  let isValid = true;
  const requiredFields = ['edit_product_name', 'edit_category_id', 'edit_price', 'edit_stock_quantity', 'edit_description', 'edit_contact_phone'];
  
  requiredFields.forEach(field => {
    if (!formData.get(field)) {
      isValid = false;
    }
  });
  
  const editErrorMessage = document.getElementById('editErrorMessage');
  if (!isValid) {
    if (editErrorMessage) {
      editErrorMessage.style.display = 'block';
    }
    return;
  }
  
  // Update produk di myProducts
  const productIndex = myProducts.findIndex(p => p.product_id === editingProductId);
  if (productIndex !== -1) {
    myProducts[productIndex] = {
      ...myProducts[productIndex],
      product_name: formData.get('edit_product_name'),
      category_name: categoryNames[formData.get('edit_category_id')] || 'Lainnya',
      price: parseInt(formData.get('edit_price')),
      stock_quantity: parseInt(formData.get('edit_stock_quantity')),
      description: formData.get('edit_description'),
      image_path: formData.get('edit_image_path') || 'https://images.unsplash.com/photo-1586771107445-d3ca888129ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
      contact_phone: formData.get('edit_contact_phone')
    };
    
    // Simpan ke localStorage
    localStorage.setItem('myProducts', JSON.stringify(myProducts));
    
    // Refresh tampilan
    loadMyProducts();
    filterProducts();
    
    // Tutup modal
    closeEditModal();
    
    // Tampilkan notifikasi
    showNotification('Produk berhasil diperbarui!', 'success');
  }
}

// Fungsi untuk delete produk
function deleteProduct(productId) {
  if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
    // Hapus dari myProducts
    myProducts = myProducts.filter(p => p.product_id !== productId);
    
    // Simpan ke localStorage
    localStorage.setItem('myProducts', JSON.stringify(myProducts));
    
    // Refresh tampilan
    loadMyProducts();
    filterProducts();
    
    // Tampilkan notifikasi
    showNotification('Produk berhasil dihapus!', 'success');
  }
}

// Fungsi untuk menutup modal edit
function closeEditModal() {
  const editModal = document.getElementById('editModal');
  const editForm = document.getElementById('editForm');
  const editErrorMessage = document.getElementById('editErrorMessage');
  
  if (editModal) editModal.classList.remove('active');
  if (editForm) editForm.reset();
  if (editErrorMessage) editErrorMessage.style.display = 'none';
  editingProductId = null;
}

// Modal functions
function openSellModal() {
  const sellModal = document.getElementById('sellModal');
  const errorMessage = document.getElementById('errorMessage');
  
  if (sellModal) {
    sellModal.classList.add('active');
    // Reset error message
    if (errorMessage) {
      errorMessage.style.display = 'none';
      errorMessage.innerHTML = '<i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i> Harap isi semua field yang wajib diisi!';
    }
  }
}

function closeSellModal() {
  const sellModal = document.getElementById('sellModal');
  const sellForm = document.getElementById('sellForm');
  const errorMessage = document.getElementById('errorMessage');
  
  if (sellModal) sellModal.classList.remove('active');
  if (sellForm) sellForm.reset();
  if (errorMessage) errorMessage.style.display = 'none';
}

// Mobile menu
function toggleMobileMenu() {
  const navMenu = document.getElementById('navMenu');
  if (navMenu) {
    navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex';
  }
}

// Setup event listeners
function setupEventListeners() {
  // Search input with debounce
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', function(e) {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(filterProducts, 500);
    });
  }
  
  // Category filter
  const categoryFilter = document.getElementById('categoryFilter');
  if (categoryFilter) {
    categoryFilter.addEventListener('change', filterProducts);
  }
  
  // Form submission
  const sellForm = document.getElementById('sellForm');
  if (sellForm) {
    sellForm.addEventListener('submit', handleSubmit);
  }
  
  // Edit form submission
  const editForm = document.getElementById('editForm');
  if (editForm) {
    editForm.addEventListener('submit', handleEditSubmit);
  }
  
  // Close modal buttons
  const closeModalButtons = document.querySelectorAll('.close-modal');
  closeModalButtons.forEach(button => {
    button.addEventListener('click', function() {
      if (this.closest('#sellModal')) {
        closeSellModal();
      } else if (this.closest('#editModal')) {
        closeEditModal();
      } else if (this.closest('#previewModal')) {
        closePreviewModal();
      }
    });
  });
  
  // Mobile menu button
  const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
  if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', toggleMobileMenu);
  }
  
  // Close mobile menu when clicking outside
  document.addEventListener('click', function(e) {
    const navMenu = document.getElementById('navMenu');
    if (navMenu && navMenu.style.display === 'flex' && 
        !navMenu.contains(e.target) && 
        !mobileMenuBtn.contains(e.target)) {
      if (window.innerWidth <= 768) {
        navMenu.style.display = 'none';
      }
    }
  });
  
  // Close modals when clicking outside
  const modals = ['sellModal', 'previewModal', 'editModal'];
  modals.forEach(modalId => {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === this) {
          if (modalId === 'sellModal') closeSellModal();
          else if (modalId === 'previewModal') closePreviewModal();
          else if (modalId === 'editModal') closeEditModal();
        }
      });
    }
  });
  
  // Handle window resize
  window.addEventListener('resize', function() {
    const navMenu = document.getElementById('navMenu');
    if (navMenu) {
      if (window.innerWidth > 768) {
        navMenu.style.display = 'flex';
      } else {
        navMenu.style.display = 'none';
      }
    }
  });
}

// Initialize the page
document.addEventListener('DOMContentLoaded', () => {
  // Populate category filter
  const categoryFilter = document.getElementById('categoryFilter');
  if (categoryFilter) {
    categoryFilter.innerHTML = '<option value="">Semua Kategori</option>';
    
    Object.entries(categoryNames).forEach(([id, name]) => {
      const option = document.createElement('option');
      option.value = id;
      option.textContent = name;
      categoryFilter.appendChild(option);
    });
  }
  
  // Initialize
  filterProducts();
  loadMyProducts();
  setupEventListeners();
  
  // Check for product ID in URL
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get('product');
  if (productId) {
    viewProductDetail(parseInt(productId));
  }
});

// Export functions for global use
window.contactSeller = contactSeller;
window.viewProductDetail = viewProductDetail;
window.shareProduct = shareProduct;
window.openSellModal = openSellModal;
window.closeSellModal = closeSellModal;
window.closeProductModal = closeProductModal;
window.toggleMobileMenu = toggleMobileMenu;
window.copyIklanLink = copyIklanLink;
window.closePreviewModal = closePreviewModal;
window.shareProductViaWhatsApp = shareProductViaWhatsApp;
window.switchTab = switchTab;
window.viewMyProducts = viewMyProducts;
window.editProduct = editProduct;
window.deleteProduct = deleteProduct;
window.closeEditModal = closeEditModal;
window.handleEditSubmit = handleEditSubmit;