// Toggle Mobile Menu
function toggleMobileMenu() {
  const navMenu = document.getElementById('navMenu');
  navMenu.classList.toggle('active');
}

// Sample data untuk admin
let investments = [
  {
    id: 1,
    investorName: "Ahmad Santoso",
    investorEmail: "ahmad.santoso@email.com",
    projectId: 1,
    projectTitle: "Budidaya Padi Organik di Karawang",
    amount: 1000000,
    date: "2024-01-15",
    status: "pending" // pending, verified, rejected
  },
  {
    id: 2,
    investorName: "Siti Rahayu",
    investorEmail: "siti.rahayu@email.com",
    projectId: 2,
    projectTitle: "Perkebunan Kopi Arabika Temanggung",
    amount: 500000,
    date: "2024-01-14",
    status: "pending"
  },
  {
    id: 3,
    investorName: "Budi Pratama",
    investorEmail: "budi.pratama@email.com",
    projectId: 1,
    projectTitle: "Budidaya Padi Organik di Karawang",
    amount: 2000000,
    date: "2024-01-13",
    status: "verified"
  },
  {
    id: 4,
    investorName: "Maya Sari",
    investorEmail: "maya.sari@email.com",
    projectId: 3,
    projectTitle: "Budidaya Sayuran Hidroponik Bandung",
    amount: 1500000,
    date: "2024-01-12",
    status: "rejected",
    rejectionReason: "Bukti pembayaran tidak jelas"
  }
];

let projects = [
  {
    id: 1,
    title: "Budidaya Padi Organik di Karawang",
    description: "Pengembangan budidaya padi organik dengan teknologi modern untuk meningkatkan hasil panen dan kualitas beras organik premium.",
    location: "Karawang, Jawa Barat",
    interest: 15,
    target: 300000000,
    collected: 225000000,
    progress: 75,
    tenure: 8,
    category: "Padi",
    status: "active"
  },
  {
    id: 2,
    title: "Perkebunan Kopi Arabika Temanggung",
    description: "Ekspansi perkebunan kopi arabika untuk meningkatkan produksi dan kualitas ekspor dengan sistem budidaya berkelanjutan.",
    location: "Temanggung, Jawa Tengah",
    interest: 14,
    target: 300000000,
    collected: 189000000,
    progress: 63,
    tenure: 12,
    category: "Kopi",
    status: "active"
  },
  {
    id: 3,
    title: "Budidaya Sayuran Hidroponik Bandung",
    description: "Pengembangan sistem hidroponik modern untuk produksi sayuran organik berkualitas tinggi dengan efisiensi air maksimal.",
    location: "Bandung, Jawa Barat",
    interest: 13,
    target: 200000000,
    collected: 162000000,
    progress: 81,
    tenure: 10,
    category: "Sayuran",
    status: "active"
  }
];

// DOM elements
const totalInvestmentsEl = document.getElementById('totalInvestments');
const pendingPaymentsEl = document.getElementById('pendingPayments');
const activeProjectsEl = document.getElementById('activeProjects');
const totalInvestorsEl = document.getElementById('totalInvestors');
const paymentsCountEl = document.getElementById('paymentsCount');
const paymentsTableEl = document.getElementById('paymentsTable');
const investmentsTableEl = document.getElementById('investmentsTable');
const adminProjectsGridEl = document.getElementById('adminProjectsGrid');
const tabBtns = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');
const investmentFilterEl = document.getElementById('investmentFilter');
const addProjectBtn = document.getElementById('addProjectBtn');

// Modal elements
const paymentModal = document.getElementById('paymentModal');
const paymentModalClose = document.getElementById('paymentModalClose');
const paymentModalBody = document.getElementById('paymentModalBody');
const statusModal = document.getElementById('statusModal');
const statusModalClose = document.getElementById('statusModalClose');
const statusModalBody = document.getElementById('statusModalBody');
const projectModal = document.getElementById('projectModal');
const projectModalClose = document.getElementById('projectModalClose');
const projectModalTitle = document.getElementById('projectModalTitle');
const projectModalBody = document.getElementById('projectModalBody');
const deleteModal = document.getElementById('deleteModal');
const deleteModalClose = document.getElementById('deleteModalClose');
const deleteMessage = document.getElementById('deleteMessage');
const cancelDelete = document.getElementById('cancelDelete');
const confirmDelete = document.getElementById('confirmDelete');

// State variables
let currentInvestment = null;
let currentProject = null;
let deleteType = null;
let deleteId = null;

// Format currency to Indonesian Rupiah
function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount);
}

// Format date
function formatDate(dateString) {
  const options = { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  };
  return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Update statistics
function updateStatistics() {
  const totalInvestments = investments.length;
  const pendingPayments = investments.filter(inv => inv.status === 'pending').length;
  const activeProjects = projects.length;
  const totalInvestors = new Set(investments.map(inv => inv.investorEmail)).size;

  totalInvestmentsEl.textContent = totalInvestments;
  pendingPaymentsEl.textContent = pendingPayments;
  activeProjectsEl.textContent = activeProjects;
  totalInvestorsEl.textContent = totalInvestors;
}

// Render payments table
function renderPaymentsTable() {
  const pendingInvestments = investments.filter(inv => inv.status === 'pending');
  paymentsCountEl.textContent = `Menampilkan ${pendingInvestments.length} pembayaran`;

  if (pendingInvestments.length === 0) {
    paymentsTableEl.innerHTML = `
      <tr>
        <td colspan="6" class="no-data">
          <i class="fas fa-receipt"></i>
          <h3>Tidak ada pembayaran menunggu</h3>
          <p>Semua pembayaran telah diverifikasi</p>
        </td>
      </tr>
    `;
    return;
  }

  paymentsTableEl.innerHTML = pendingInvestments.map(investment => `
    <tr>
      <td>#${investment.id}</td>
      <td>
        <div><strong>${investment.investorName}</strong></div>
        <div style="font-size: 0.8rem; color: var(--text);">${investment.investorEmail}</div>
      </td>
      <td>${investment.projectTitle}</td>
      <td><strong>${formatCurrency(investment.amount)}</strong></td>
      <td>${formatDate(investment.date)}</td>
      <td>
        <div class="action-buttons">
          <button class="btn btn-primary btn-sm" onclick="openPaymentModal(${investment.id})">
            <i class="fas fa-check"></i> Verifikasi
          </button>
        </div>
      </td>
    </tr>
  `).join('');
}

// Render investments table
function renderInvestmentsTable() {
  const filterValue = investmentFilterEl.value;
  let filteredInvestments = investments;

  if (filterValue) {
    filteredInvestments = investments.filter(inv => inv.status === filterValue);
  }

  if (filteredInvestments.length === 0) {
    investmentsTableEl.innerHTML = `
      <tr>
        <td colspan="7" class="no-data">
          <i class="fas fa-search"></i>
          <h3>Tidak ada data investasi</h3>
          <p>Coba ubah filter yang Anda gunakan</p>
        </td>
      </tr>
    `;
    return;
  }

  investmentsTableEl.innerHTML = filteredInvestments.map(investment => {
    let statusBadge = '';
    let statusText = '';
    
    switch(investment.status) {
      case 'pending':
        statusBadge = 'status-pending';
        statusText = 'Menunggu';
        break;
      case 'verified':
        statusBadge = 'status-verified';
        statusText = 'Terverifikasi';
        break;
      case 'rejected':
        statusBadge = 'status-rejected';
        statusText = 'Ditolak';
        break;
    }

    return `
      <tr>
        <td>#${investment.id}</td>
        <td>
          <div><strong>${investment.investorName}</strong></div>
          <div style="font-size: 0.8rem; color: var(--text);">${investment.investorEmail}</div>
        </td>
        <td>${investment.projectTitle}</td>
        <td><strong>${formatCurrency(investment.amount)}</strong></td>
        <td>${formatDate(investment.date)}</td>
        <td><span class="status-badge ${statusBadge}">${statusText}</span></td>
        <td>
          <div class="action-buttons">
            <button class="btn btn-outline btn-sm" onclick="openStatusModal(${investment.id})">
              <i class="fas fa-edit"></i> Ubah Status
            </button>
            <button class="btn btn-danger btn-sm" onclick="openDeleteModal('investment', ${investment.id})">
              <i class="fas fa-trash"></i> Hapus
            </button>
          </div>
        </td>
      </tr>
    `;
  }).join('');
}

// Render projects grid
function renderProjectsGrid() {
  if (projects.length === 0) {
    adminProjectsGridEl.innerHTML = `
      <div class="no-data" style="grid-column: 1 / -1;">
        <i class="fas fa-seedling"></i>
        <h3>Belum ada proyek</h3>
        <p>Tambahkan proyek pertama Anda untuk memulai</p>
      </div>
    `;
    return;
  }

  adminProjectsGridEl.innerHTML = projects.map(project => `
    <div class="project-card">
      <div class="project-header">
        <h3 class="project-title">${project.title}</h3>
        <span class="project-badge">Bunga ${project.interest}%</span>
      </div>
      <div class="project-content">
        <p class="project-description">${project.description}</p>
        <div class="project-location">
          <i class="fas fa-map-marker-alt"></i>
          <span>${project.location}</span>
        </div>
        <div class="progress-container">
          <div class="progress-info">
            <span>Terkumpul: ${project.progress}%</span>
            <span>${formatCurrency(project.collected)}</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" style="width: ${project.progress}%"></div>
          </div>
        </div>
        <div class="project-stats">
          <div class="project-stat">
            <div class="project-stat-value">${project.tenure} Bulan</div>
            <div class="project-stat-label">Tenor</div>
          </div>
          <div class="project-stat">
            <div class="project-stat-value">${project.category}</div>
            <div class="project-stat-label">Kategori</div>
          </div>
        </div>
        <div class="project-footer">
          <div class="project-amount">${formatCurrency(project.target)}</div>
          <div class="action-buttons">
            <button class="btn btn-outline btn-sm" onclick="editProject(${project.id})">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button class="btn btn-danger btn-sm" onclick="openDeleteModal('project', ${project.id})">
              <i class="fas fa-trash"></i> Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
  `).join('');
}

// Open payment verification modal
function openPaymentModal(investmentId) {
  currentInvestment = investments.find(inv => inv.id === investmentId);
  
  if (!currentInvestment) return;

  paymentModalBody.innerHTML = `
    <div class="form-group">
      <h3>Detail Pembayaran</h3>
      <div style="background: var(--light-gray); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
          <div>
            <strong>Investor:</strong><br>
            ${currentInvestment.investorName}<br>
            <small>${currentInvestment.investorEmail}</small>
          </div>
          <div>
            <strong>Proyek:</strong><br>
            ${currentInvestment.projectTitle}
          </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div>
            <strong>Jumlah:</strong><br>
            ${formatCurrency(currentInvestment.amount)}
          </div>
          <div>
            <strong>Tanggal:</strong><br>
            ${formatDate(currentInvestment.date)}
          </div>
        </div>
      </div>
    </div>

    <div class="modal-actions">
      <button class="btn btn-outline" id="rejectPayment">Tolak Pembayaran</button>
      <button class="btn btn-primary" id="verifyPayment">Verifikasi Pembayaran</button>
    </div>
  `;

  // Add event listeners to modal buttons
  document.getElementById('rejectPayment').addEventListener('click', () => {
    if (confirm('Apakah Anda yakin ingin menolak pembayaran ini?')) {
      rejectPayment();
    }
  });

  document.getElementById('verifyPayment').addEventListener('click', () => {
    if (confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?')) {
      verifyPayment();
    }
  });

  paymentModal.style.display = 'block';
}

// Verify payment
function verifyPayment() {
  if (!currentInvestment) return;

  // Update investment status
  const investmentIndex = investments.findIndex(inv => inv.id === currentInvestment.id);
  if (investmentIndex !== -1) {
    investments[investmentIndex].status = 'verified';
    
    // Update project collected amount
    const projectIndex = projects.findIndex(proj => proj.id === currentInvestment.projectId);
    if (projectIndex !== -1) {
      projects[projectIndex].collected += currentInvestment.amount;
      projects[projectIndex].progress = Math.round((projects[projectIndex].collected / projects[projectIndex].target) * 100);
    }
  }

  updateStatistics();
  renderPaymentsTable();
  renderInvestmentsTable();
  renderProjectsGrid();
  
  paymentModal.style.display = 'none';
  alert('Pembayaran berhasil diverifikasi!');
}

// Reject payment
function rejectPayment() {
  if (!currentInvestment) return;

  // Update investment status
  const investmentIndex = investments.findIndex(inv => inv.id === currentInvestment.id);
  if (investmentIndex !== -1) {
    investments[investmentIndex].status = 'rejected';
  }

  updateStatistics();
  renderPaymentsTable();
  renderInvestmentsTable();
  
  paymentModal.style.display = 'none';
  alert('Pembayaran telah ditolak!');
}

// Open status change modal
function openStatusModal(investmentId) {
  currentInvestment = investments.find(inv => inv.id === investmentId);
  
  if (!currentInvestment) return;

  statusModalBody.innerHTML = `
    <div class="form-group">
      <h3>Ubah Status Investasi</h3>
      <div style="background: var(--light-gray); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
          <div>
            <strong>Investor:</strong><br>
            ${currentInvestment.investorName}<br>
            <small>${currentInvestment.investorEmail}</small>
          </div>
          <div>
            <strong>Proyek:</strong><br>
            ${currentInvestment.projectTitle}
          </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div>
            <strong>Jumlah:</strong><br>
            ${formatCurrency(currentInvestment.amount)}
          </div>
          <div>
            <strong>Status Saat Ini:</strong><br>
            <span class="status-badge ${getStatusBadgeClass(currentInvestment.status)}">
              ${getStatusText(currentInvestment.status)}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="newStatus">Status Baru</label>
      <select id="newStatus" class="form-control">
        <option value="pending" ${currentInvestment.status === 'pending' ? 'selected' : ''}>Menunggu</option>
        <option value="verified" ${currentInvestment.status === 'verified' ? 'selected' : ''}>Terverifikasi</option>
        <option value="rejected" ${currentInvestment.status === 'rejected' ? 'selected' : ''}>Ditolak</option>
      </select>
    </div>

    <div class="modal-actions">
      <button class="btn btn-outline" id="cancelStatus">Batal</button>
      <button class="btn btn-primary" id="saveStatus">Simpan Status</button>
    </div>
  `;

  // Add event listeners
  document.getElementById('cancelStatus').addEventListener('click', () => {
    statusModal.style.display = 'none';
  });

  document.getElementById('saveStatus').addEventListener('click', () => {
    const newStatus = document.getElementById('newStatus').value;
    updateInvestmentStatus(newStatus);
  });

  statusModal.style.display = 'block';
}

// Update investment status
function updateInvestmentStatus(newStatus) {
  if (!currentInvestment) return;

  const investmentIndex = investments.findIndex(inv => inv.id === currentInvestment.id);
  if (investmentIndex !== -1) {
    const oldStatus = investments[investmentIndex].status;
    investments[investmentIndex].status = newStatus;

    // Jika status berubah dari pending ke verified, update collected amount
    if (oldStatus !== 'verified' && newStatus === 'verified') {
      const projectIndex = projects.findIndex(proj => proj.id === currentInvestment.projectId);
      if (projectIndex !== -1) {
        projects[projectIndex].collected += currentInvestment.amount;
        projects[projectIndex].progress = Math.round((projects[projectIndex].collected / projects[projectIndex].target) * 100);
      }
    }
    // Jika status berubah dari verified ke status lain, kurangi collected amount
    else if (oldStatus === 'verified' && newStatus !== 'verified') {
      const projectIndex = projects.findIndex(proj => proj.id === currentInvestment.projectId);
      if (projectIndex !== -1) {
        projects[projectIndex].collected -= currentInvestment.amount;
        projects[projectIndex].progress = Math.round((projects[projectIndex].collected / projects[projectIndex].target) * 100);
      }
    }
  }

  updateStatistics();
  renderPaymentsTable();
  renderInvestmentsTable();
  renderProjectsGrid();
  
  statusModal.style.display = 'none';
  alert('Status investasi berhasil diubah!');
}

// Helper functions for status
function getStatusBadgeClass(status) {
  switch(status) {
    case 'pending': return 'status-pending';
    case 'verified': return 'status-verified';
    case 'rejected': return 'status-rejected';
    default: return '';
  }
}

function getStatusText(status) {
  switch(status) {
    case 'pending': return 'Menunggu';
    case 'verified': return 'Terverifikasi';
    case 'rejected': return 'Ditolak';
    default: return '';
  }
}

// Open add project modal
function openAddProjectModal() {
  projectModalTitle.textContent = 'Tambah Proyek Baru';
  currentProject = null;

  projectModalBody.innerHTML = `
    <form id="projectForm">
      <div class="form-group">
        <label class="form-label" for="projectTitle">Judul Proyek</label>
        <input type="text" id="projectTitle" class="form-control" placeholder="Contoh: Budidaya Padi Organik di Karawang" required>
      </div>
      
      <div class="form-group">
        <label class="form-label" for="projectDescription">Deskripsi Proyek</label>
        <textarea id="projectDescription" class="form-control" rows="4" placeholder="Jelaskan detail proyek investasi Anda" required></textarea>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="projectLocation">Lokasi Proyek</label>
          <input type="text" id="projectLocation" class="form-control" placeholder="Contoh: Karawang, Jawa Barat" required>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="projectCategory">Kategori</label>
          <select id="projectCategory" class="form-control" required>
            <option value="">Pilih Kategori</option>
            <option value="Padi">Padi</option>
            <option value="Kopi">Kopi</option>
            <option value="Sayuran">Sayuran</option>
            <option value="Buah-buahan">Buah-buahan</option>
            <option value="Peternakan">Peternakan</option>
            <option value="Perikanan">Perikanan</option>
          </select>
        </div>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="projectInterest">Bunga Tahunan (%)</label>
          <input type="number" id="projectInterest" class="form-control" placeholder="Contoh: 15" min="5" max="25" required>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="projectTenure">Tenor (Bulan)</label>
          <input type="number" id="projectTenure" class="form-control" placeholder="Contoh: 12" min="3" max="36" required>
        </div>
      </div>
      
      <div class="form-group">
        <label class="form-label" for="projectTarget">Target Dana (Rp)</label>
        <input type="number" id="projectTarget" class="form-control" placeholder="Contoh: 300000000" min="10000000" required>
      </div>
      
      <div class="modal-actions">
        <button type="button" class="btn btn-outline" id="cancelProject">Batal</button>
        <button type="submit" class="btn btn-primary" id="saveProject">Simpan Proyek</button>
      </div>
    </form>
  `;

  // Add event listeners
  document.getElementById('cancelProject').addEventListener('click', () => {
    projectModal.style.display = 'none';
  });

  document.getElementById('projectForm').addEventListener('submit', (e) => {
    e.preventDefault();
    saveProject();
  });

  projectModal.style.display = 'block';
}

// Edit project
function editProject(projectId) {
  currentProject = projects.find(proj => proj.id === projectId);
  
  if (!currentProject) return;

  projectModalTitle.textContent = 'Edit Proyek';
  
  projectModalBody.innerHTML = `
    <form id="projectForm">
      <div class="form-group">
        <label class="form-label" for="projectTitle">Judul Proyek</label>
        <input type="text" id="projectTitle" class="form-control" value="${currentProject.title}" required>
      </div>
      
      <div class="form-group">
        <label class="form-label" for="projectDescription">Deskripsi Proyek</label>
        <textarea id="projectDescription" class="form-control" rows="4" required>${currentProject.description}</textarea>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="projectLocation">Lokasi Proyek</label>
          <input type="text" id="projectLocation" class="form-control" value="${currentProject.location}" required>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="projectCategory">Kategori</label>
          <select id="projectCategory" class="form-control" required>
            <option value="">Pilih Kategori</option>
            <option value="Padi" ${currentProject.category === 'Padi' ? 'selected' : ''}>Padi</option>
            <option value="Kopi" ${currentProject.category === 'Kopi' ? 'selected' : ''}>Kopi</option>
            <option value="Sayuran" ${currentProject.category === 'Sayuran' ? 'selected' : ''}>Sayuran</option>
            <option value="Buah-buahan" ${currentProject.category === 'Buah-buahan' ? 'selected' : ''}>Buah-buahan</option>
            <option value="Peternakan" ${currentProject.category === 'Peternakan' ? 'selected' : ''}>Peternakan</option>
            <option value="Perikanan" ${currentProject.category === 'Perikanan' ? 'selected' : ''}>Perikanan</option>
          </select>
        </div>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="projectInterest">Bunga Tahunan (%)</label>
          <input type="number" id="projectInterest" class="form-control" value="${currentProject.interest}" min="5" max="25" required>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="projectTenure">Tenor (Bulan)</label>
          <input type="number" id="projectTenure" class="form-control" value="${currentProject.tenure}" min="3" max="36" required>
        </div>
      </div>
      
      <div class="form-group">
        <label class="form-label" for="projectTarget">Target Dana (Rp)</label>
        <input type="number" id="projectTarget" class="form-control" value="${currentProject.target}" min="10000000" required>
      </div>
      
      <div class="modal-actions">
        <button type="button" class="btn btn-outline" id="cancelProject">Batal</button>
        <button type="submit" class="btn btn-primary" id="saveProject">Update Proyek</button>
      </div>
    </form>
  `;

  // Add event listeners
  document.getElementById('cancelProject').addEventListener('click', () => {
    projectModal.style.display = 'none';
  });

  document.getElementById('projectForm').addEventListener('submit', (e) => {
    e.preventDefault();
    saveProject();
  });

  projectModal.style.display = 'block';
}

// Save project
function saveProject() {
  const formData = {
    title: document.getElementById('projectTitle').value,
    description: document.getElementById('projectDescription').value,
    location: document.getElementById('projectLocation').value,
    category: document.getElementById('projectCategory').value,
    interest: parseInt(document.getElementById('projectInterest').value),
    tenure: parseInt(document.getElementById('projectTenure').value),
    target: parseInt(document.getElementById('projectTarget').value)
  };

  // Validasi
  if (!formData.title || !formData.description || !formData.location || !formData.category) {
    alert('Harap lengkapi semua field yang diperlukan.');
    return;
  }

  if (currentProject) {
    // Update existing project
    const projectIndex = projects.findIndex(proj => proj.id === currentProject.id);
    if (projectIndex !== -1) {
      projects[projectIndex] = { ...projects[projectIndex], ...formData };
    }
  } else {
    // Add new project
    const newProject = {
      id: Math.max(...projects.map(p => p.id)) + 1,
      ...formData,
      collected: 0,
      progress: 0,
      status: 'active'
    };
    projects.unshift(newProject);
  }

  renderProjectsGrid();
  projectModal.style.display = 'none';
  alert(`Proyek berhasil ${currentProject ? 'diupdate' : 'ditambahkan'}!`);
}

// Open delete confirmation modal
function openDeleteModal(type, id) {
  deleteType = type;
  deleteId = id;

  if (type === 'investment') {
    const investment = investments.find(inv => inv.id === id);
    deleteMessage.textContent = `Apakah Anda yakin ingin menghapus investasi dari ${investment.investorName} sebesar ${formatCurrency(investment.amount)}?`;
  } else if (type === 'project') {
    const project = projects.find(proj => proj.id === id);
    deleteMessage.textContent = `Apakah Anda yakin ingin menghapus proyek "${project.title}"? Tindakan ini tidak dapat dibatalkan.`;
  }

  deleteModal.style.display = 'block';
}

// Confirm delete
function confirmDeleteAction() {
  if (deleteType === 'investment') {
    investments = investments.filter(inv => inv.id !== deleteId);
    renderPaymentsTable();
    renderInvestmentsTable();
  } else if (deleteType === 'project') {
    projects = projects.filter(proj => proj.id !== deleteId);
    renderProjectsGrid();
  }

  updateStatistics();
  deleteModal.style.display = 'none';
  alert('Item berhasil dihapus!');
}

// Tab switching functionality
tabBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const tabId = btn.getAttribute('data-tab');
    
    // Update active tab button
    tabBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    // Show corresponding tab content
    tabContents.forEach(content => content.classList.remove('active'));
    document.getElementById(`${tabId}-tab`).classList.add('active');
  });
});

// Close modals
paymentModalClose.addEventListener('click', () => {
  paymentModal.style.display = 'none';
});

statusModalClose.addEventListener('click', () => {
  statusModal.style.display = 'none';
});

projectModalClose.addEventListener('click', () => {
  projectModal.style.display = 'none';
});

deleteModalClose.addEventListener('click', () => {
  deleteModal.style.display = 'none';
});

cancelDelete.addEventListener('click', () => {
  deleteModal.style.display = 'none';
});

confirmDelete.addEventListener('click', confirmDeleteAction);

// Close modals when clicking outside
window.addEventListener('click', (e) => {
  if (e.target === paymentModal) paymentModal.style.display = 'none';
  if (e.target === statusModal) statusModal.style.display = 'none';
  if (e.target === projectModal) projectModal.style.display = 'none';
  if (e.target === deleteModal) deleteModal.style.display = 'none';
});

// Event listeners
addProjectBtn.addEventListener('click', openAddProjectModal);
investmentFilterEl.addEventListener('change', renderInvestmentsTable);

// Initialize the page
document.addEventListener('DOMContentLoaded', () => {
  updateStatistics();
  renderPaymentsTable();
  renderInvestmentsTable();
  renderProjectsGrid();
});