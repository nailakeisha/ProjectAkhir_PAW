// Sample project data
const projects = [
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
    status: "Penggalangan Dana"
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
    status: "Penggalangan Dana"
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
    status: "Penggalangan Dana"
  },
  {
    id: 4,
    title: "Peternakan Ayam Kampung Organik",
    description: "Pengembangan peternakan ayam kampung dengan sistem organik dan pakan alami untuk menghasilkan daging berkualitas premium.",
    location: "Bogor, Jawa Barat",
    interest: 12,
    target: 300000000,
    collected: 84000000,
    progress: 28,
    tenure: 10,
    category: "Peternakan",
    status: "Penggalangan Dana"
  },
  {
    id: 5,
    title: "Budidaya Ikan Nila Sistem Bioflok",
    description: "Pengembangan budidaya ikan nila dengan sistem bioflok inovatif untuk efisiensi pakan dan air yang optimal.",
    location: "Sukabumi, Jawa Barat",
    interest: 14,
    target: 300000000,
    collected: 189000000,
    progress: 63,
    tenure: 7,
    category: "Perikanan",
    status: "Sedang Berjalan"
  },
  {
    id: 6,
    title: "Perkebunan Jeruk Pamelo Batu",
    description: "Ekspansi perkebunan jeruk pamelo dengan sistem irigasi tetes modern untuk efisiensi air dan hasil maksimal.",
    location: "Batu, Jawa Timur",
    interest: 16,
    target: 300000000,
    collected: 243000000,
    progress: 81,
    tenure: 18,
    category: "Buah-buahan",
    status: "Sedang Berjalan"
  },
  {
    id: 7,
    title: "Kebun Stroberi Organik Lembang",
    description: "Pengembangan kebun stroberi organik dengan teknologi greenhouse untuk hasil panen yang konsisten sepanjang tahun.",
    location: "Lembang, Jawa Barat",
    interest: 17,
    target: 250000000,
    collected: 250000000,
    progress: 100,
    tenure: 9,
    category: "Buah-buahan",
    status: "Selesai"
  },
  {
    id: 8,
    title: "Tambak Udang Vaname Modern",
    description: "Pengembangan tambak udang vaname dengan sistem sirkulasi air tertutup untuk mengurangi risiko penyakit.",
    location: "Situbondo, Jawa Timur",
    interest: 15,
    target: 400000000,
    collected: 320000000,
    progress: 80,
    tenure: 11,
    category: "Perikanan",
    status: "Sedang Berjalan"
  }
];

// DOM elements
const projectsGrid = document.getElementById('projectsGrid');
const projectModal = document.getElementById('projectModal');
const modalClose = document.getElementById('modalClose');
const modalProjectTitle = document.getElementById('modalProjectTitle');
const modalBody = document.getElementById('modalBody');
const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const statusFilter = document.getElementById('statusFilter');
const searchButton = document.getElementById('searchButton');
const resultsCount = document.getElementById('resultsCount');
const statusTabs = document.querySelectorAll('.status-tab');
const investmentModal = document.getElementById('investmentModal');
const investmentModalClose = document.getElementById('investmentModalClose');
const investmentBody = document.getElementById('investmentBody');
const paymentModal = document.getElementById('paymentModal');
const paymentModalClose = document.getElementById('paymentModalClose');
const paymentBody = document.getElementById('paymentBody');
const verificationModal = document.getElementById('verificationModal');
const verificationModalClose = document.getElementById('verificationModalClose');
const closeVerificationModal = document.getElementById('closeVerificationModal');

// Current filtered projects
let filteredProjects = [...projects];
let currentProject = null;
let currentInvestmentAmount = 100000;

// Format currency to Indonesian Rupiah
function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount);
}

// Filter projects based on search criteria
function filterProjects() {
  const searchTerm = searchInput.value.toLowerCase();
  const category = categoryFilter.value;
  const status = statusFilter.value;

  filteredProjects = projects.filter(project => {
    const matchesSearch = !searchTerm || 
      project.title.toLowerCase().includes(searchTerm) ||
      project.description.toLowerCase().includes(searchTerm) ||
      project.location.toLowerCase().includes(searchTerm) ||
      project.category.toLowerCase().includes(searchTerm);
    
    const matchesCategory = !category || project.category === category;
    const matchesStatus = !status || project.status === status;
    
    return matchesSearch && matchesCategory && matchesStatus;
  });

  renderProjects();
  updateResultsCount();
}

// Update results count
function updateResultsCount() {
  const count = filteredProjects.length;
  resultsCount.textContent = `Menampilkan ${count} proyek`;
}

// Render project cards
function renderProjects() {
  projectsGrid.innerHTML = '';
  
  if (filteredProjects.length === 0) {
    projectsGrid.innerHTML = `
      <div class="no-results">
        <i class="fas fa-search"></i>
        <h3>Tidak ada proyek yang ditemukan</h3>
        <p>Coba ubah kata kunci pencarian atau filter yang Anda gunakan</p>
      </div>
    `;
    return;
  }
  
  filteredProjects.forEach(project => {
    const projectCard = document.createElement('div');
    projectCard.className = 'project-card';
    projectCard.setAttribute('data-id', project.id);
    
    // Determine button text and class based on project status
    let buttonText, buttonClass;
    if (project.status === "Selesai") {
      buttonText = "Proyek Selesai";
      buttonClass = "btn btn-disabled";
    } else {
      buttonText = "Lihat Detail";
      buttonClass = "btn btn-outline";
    }
    
    projectCard.innerHTML = `
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
            <div class="project-stat-value">${project.status}</div>
            <div class="project-stat-label">Status</div>
          </div>
        </div>
        <div class="project-footer">
          <div class="project-amount">${formatCurrency(project.target)}</div>
          <button class="${buttonClass}">${buttonText}</button>
        </div>
      </div>
    `;
    
    projectsGrid.appendChild(projectCard);
    
    // Add click event to project card only if not completed
    if (project.status !== "Selesai") {
      projectCard.addEventListener('click', () => {
        openProjectModal(project.id);
      });
    }
  });
}

// Open project detail modal
function openProjectModal(projectId) {
  const project = projects.find(p => p.id === projectId);
  
  if (!project) return;
  
  currentProject = project;
  modalProjectTitle.textContent = project.title;
  
  modalBody.innerHTML = `
    <div class="project-description">
      <p>${project.description}</p>
    </div>
    <div class="project-location">
      <i class="fas fa-map-marker-alt"></i>
      <span>${project.location}</span>
    </div>
    
    <div class="project-stats" style="margin: 1.5rem 0; display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
      <div class="project-stat">
        <div class="project-stat-value">${project.progress}%</div>
        <div class="project-stat-label">Terkumpul</div>
        <div>${formatCurrency(project.collected)}</div>
      </div>
      <div class="project-stat">
        <div class="project-stat-value">${project.tenure} Bulan</div>
        <div class="project-stat-label">Tenor</div>
      </div>
      <div class="project-stat">
        <div class="project-stat-value">${project.interest}%</div>
        <div class="project-stat-label">Bunga per Tahun</div>
      </div>
    </div>
    
    <div style="margin: 1.5rem 0;">
      <h3>Jumlah Investasi</h3>
      <div class="investment-options">
        <div class="investment-option active" data-amount="100000">
          <div>Rp 100rb</div>
        </div>
        <div class="investment-option" data-amount="500000">
          <div>Rp 500rb</div>
        </div>
        <div class="investment-option" data-amount="1000000">
          <div>Rp 1jt</div>
        </div>
        <div class="investment-option" data-amount="5000000">
          <div>Rp 5jt</div>
        </div>
      </div>
      <p style="margin-top: 1rem; font-size: 0.9rem;">Minimal investasi: Rp 100.000</p>
    </div>
    
    <div class="return-calculator">
      <h3>Perkiraan Return</h3>
      <div class="return-row">
        <span>Investasi Awal</span>
        <span id="investmentAmount">Rp 100.000</span>
      </div>
      <div class="return-row">
        <span>Bunga (${project.interest}% p.a.)</span>
        <span id="interestAmount">Rp ${Math.round(100000 * project.interest / 100 * project.tenure / 12).toLocaleString('id-ID')}</span>
      </div>
      <div class="return-row" style="font-weight: bold; border-top: 1px solid #ccc; padding-top: 0.5rem;">
        <span>Total Pengembalian</span>
        <span id="totalReturn">Rp ${Math.round(100000 + (100000 * project.interest / 100 * project.tenure / 12)).toLocaleString('id-ID')}</span>
      </div>
      <p style="margin-top: 1rem; font-size: 0.9rem; text-align: center;">
        Perhitungan untuk tenor ${project.tenure} bulan
      </p>
    </div>
    
    <div style="text-align: center; margin-top: 2rem;">
      <button class="btn btn-primary" id="investButton" style="padding: 15px 40px; font-size: 1.1rem;">Danai Proyek Ini</button>
    </div>
  `;
  
  // Add event listeners to investment options
  const investmentOptions = modalBody.querySelectorAll('.investment-option');
  investmentOptions.forEach(option => {
    option.addEventListener('click', () => {
      investmentOptions.forEach(opt => opt.classList.remove('active'));
      option.classList.add('active');
      
      const amount = parseInt(option.getAttribute('data-amount'));
      currentInvestmentAmount = amount;
      updateReturnCalculation(amount, project.interest, project.tenure);
    });
  });
  
  // Add event listener to invest button
  const investButton = document.getElementById('investButton');
  investButton.addEventListener('click', () => {
    openInvestmentForm();
  });
  
  projectModal.style.display = 'block';
}

// Open investment form modal
function openInvestmentForm() {
  if (!currentProject) return;
  
  investmentBody.innerHTML = `
    <div class="form-group">
      <h3>Formulir Investasi</h3>
      <p>Silakan lengkapi data diri Anda untuk melanjutkan investasi</p>
    </div>
    
    <div class="form-group">
      <label class="form-label" for="fullName">Nama Lengkap</label>
      <input type="text" id="fullName" class="form-control" placeholder="Masukkan nama lengkap">
    </div>
    
    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" class="form-control" placeholder="Masukkan alamat email">
      </div>
      
      <div class="form-group">
        <label class="form-label" for="phone">Nomor Telepon</label>
        <input type="tel" id="phone" class="form-control" placeholder="Masukkan nomor telepon">
      </div>
    </div>
    
    <div class="form-group">
      <label class="form-label" for="address">Alamat</label>
      <textarea id="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
    </div>
    
    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="idNumber">Nomor KTP</label>
        <input type="text" id="idNumber" class="form-control" placeholder="Masukkan nomor KTP">
      </div>
      
      <div class="form-group">
        <label class="form-label" for="birthDate">Tanggal Lahir</label>
        <input type="date" id="birthDate" class="form-control">
      </div>
    </div>
    
    <div class="form-group">
      <label class="form-label">Jumlah Investasi</label>
      <div class="investment-options">
        <div class="investment-option ${currentInvestmentAmount === 100000 ? 'active' : ''}" data-amount="100000">
          <div>Rp 100rb</div>
        </div>
        <div class="investment-option ${currentInvestmentAmount === 500000 ? 'active' : ''}" data-amount="500000">
          <div>Rp 500rb</div>
        </div>
        <div class="investment-option ${currentInvestmentAmount === 1000000 ? 'active' : ''}" data-amount="1000000">
          <div>Rp 1jt</div>
        </div>
        <div class="investment-option ${currentInvestmentAmount === 5000000 ? 'active' : ''}" data-amount="5000000">
          <div>Rp 5jt</div>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <label class="form-label" for="customAmount">Atau masukkan jumlah investasi</label>
      <input type="number" id="customAmount" class="form-control" placeholder="Masukkan jumlah investasi (min. 100000)" min="100000" value="${currentInvestmentAmount}">
    </div>
    
    <div class="return-calculator">
      <h3>Ringkasan Investasi</h3>
      <div class="return-row">
        <span>Proyek:</span>
        <span>${currentProject.title}</span>
      </div>
      <div class="return-row">
        <span>Jumlah Investasi:</span>
        <span id="summaryAmount">${formatCurrency(currentInvestmentAmount)}</span>
      </div>
      <div class="return-row">
        <span>Bunga Tahunan:</span>
        <span>${currentProject.interest}%</span>
      </div>
      <div class="return-row">
        <span>Tenor:</span>
        <span>${currentProject.tenure} Bulan</span>
      </div>
      <div class="return-row" style="font-weight: bold; border-top: 1px solid #ccc; padding-top: 0.5rem;">
        <span>Perkiraan Pengembalian:</span>
        <span id="summaryReturn">${formatCurrency(Math.round(currentInvestmentAmount + (currentInvestmentAmount * currentProject.interest / 100 * currentProject.tenure / 12)))}</span>
      </div>
    </div>
    
    <div class="form-group" style="margin-top: 1.5rem;">
      <div style="display: flex; align-items: start; gap: 10px;">
        <input type="checkbox" id="agreeTerms" style="margin-top: 3px;">
        <label for="agreeTerms">Saya setuju dengan <a href="#" style="color: var(--primary);">Syarat dan Ketentuan</a> serta <a href="#" style="color: var(--primary);">Kebijakan Privasi</a> yang berlaku</label>
      </div>
    </div>
    
    <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
      <button class="btn btn-outline" id="cancelInvestment">Batal</button>
      <button class="btn btn-primary" id="submitInvestment">Lanjutkan Pembayaran</button>
    </div>
  `;
  
  // Add event listeners to investment options in form
  const investmentOptions = investmentBody.querySelectorAll('.investment-option');
  investmentOptions.forEach(option => {
    option.addEventListener('click', () => {
      investmentOptions.forEach(opt => opt.classList.remove('active'));
      option.classList.add('active');
      
      const amount = parseInt(option.getAttribute('data-amount'));
      currentInvestmentAmount = amount;
      document.getElementById('customAmount').value = amount;
      updateInvestmentSummary();
    });
  });
  
  // Add event listener to custom amount input
  const customAmountInput = document.getElementById('customAmount');
  customAmountInput.addEventListener('input', () => {
    const amount = parseInt(customAmountInput.value) || 0;
    if (amount >= 100000) {
      currentInvestmentAmount = amount;
      updateInvestmentSummary();
      
      // Update active option
      investmentOptions.forEach(option => {
        option.classList.remove('active');
        if (parseInt(option.getAttribute('data-amount')) === amount) {
          option.classList.add('active');
        }
      });
    }
  });
  
  // Add event listeners to form buttons
  document.getElementById('cancelInvestment').addEventListener('click', () => {
    investmentModal.style.display = 'none';
  });
  
  document.getElementById('submitInvestment').addEventListener('click', () => {
    if (validateInvestmentForm()) {
      investmentModal.style.display = 'none';
      openPaymentModal();
    }
  });
  
  investmentModal.style.display = 'block';
}

// Validate investment form
function validateInvestmentForm() {
  const fullName = document.getElementById('fullName').value;
  const email = document.getElementById('email').value;
  const phone = document.getElementById('phone').value;
  const address = document.getElementById('address').value;
  const idNumber = document.getElementById('idNumber').value;
  const birthDate = document.getElementById('birthDate').value;
  const agreeTerms = document.getElementById('agreeTerms').checked;
  
  if (!fullName || !email || !phone || !address || !idNumber || !birthDate) {
    alert('Harap lengkapi semua data yang diperlukan.');
    return false;
  }
  
  if (!agreeTerms) {
    alert('Anda harus menyetujui Syarat dan Ketentuan untuk melanjutkan.');
    return false;
  }
  
  // Simple email validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    alert('Format email tidak valid.');
    return false;
  }
  
  return true;
}

// Update investment summary
function updateInvestmentSummary() {
  document.getElementById('summaryAmount').textContent = formatCurrency(currentInvestmentAmount);
  document.getElementById('summaryReturn').textContent = formatCurrency(
    Math.round(currentInvestmentAmount + (currentInvestmentAmount * currentProject.interest / 100 * currentProject.tenure / 12))
  );
}

// Update return calculation based on investment amount
function updateReturnCalculation(amount, interest, tenure) {
  const interestAmount = Math.round(amount * interest / 100 * tenure / 12);
  const totalReturn = amount + interestAmount;
  
  document.getElementById('investmentAmount').textContent = formatCurrency(amount);
  document.getElementById('interestAmount').textContent = formatCurrency(interestAmount);
  document.getElementById('totalReturn').textContent = formatCurrency(totalReturn);
}

// Open payment modal
function openPaymentModal() {
  if (!currentProject) return;
  
  paymentBody.innerHTML = `
    <div class="payment-section">
      <h3>Lanjutkan Pembayaran</h3>
      <p>Silakan selesaikan pembayaran Anda menggunakan QRIS</p>
      
      <div class="payment-amount">
        ${formatCurrency(currentInvestmentAmount)}
      </div>
      
      <div class="qris-code">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=NisevaAgro-${currentProject.id}-${currentInvestmentAmount}" alt="QR Code Pembayaran">
      </div>
      
      <div class="payment-details">
        <div class="payment-detail-row">
          <span>Proyek:</span>
          <span>${currentProject.title}</span>
        </div>
        <div class="payment-detail-row">
          <span>Jumlah Investasi:</span>
          <span>${formatCurrency(currentInvestmentAmount)}</span>
        </div>
        <div class="payment-detail-row">
          <span>Bunga Tahunan:</span>
          <span>${currentProject.interest}%</span>
        </div>
        <div class="payment-detail-row">
          <span>Tenor:</span>
          <span>${currentProject.tenure} Bulan</span>
        </div>
      </div>
      
      <p style="margin-bottom: 1.5rem; font-size: 0.9rem;">
        Scan QR code di atas dengan aplikasi e-wallet atau mobile banking Anda untuk menyelesaikan pembayaran.
      </p>
      
      <div style="display: flex; gap: 1rem; justify-content: center;">
        <button class="btn btn-outline" id="cancelPayment">Batal</button>
        <button class="btn btn-primary" id="confirmPayment">Konfirmasi Pembayaran</button>
      </div>
    </div>
  `;
  
  // Add event listeners to payment buttons
  document.getElementById('cancelPayment').addEventListener('click', () => {
    paymentModal.style.display = 'none';
  });
  
  document.getElementById('confirmPayment').addEventListener('click', () => {
    paymentModal.style.display = 'none';
    verificationModal.style.display = 'block';
  });
  
  paymentModal.style.display = 'block';
}

// Close modals
modalClose.addEventListener('click', () => {
  projectModal.style.display = 'none';
});

investmentModalClose.addEventListener('click', () => {
  investmentModal.style.display = 'none';
});

paymentModalClose.addEventListener('click', () => {
  paymentModal.style.display = 'none';
});

verificationModalClose.addEventListener('click', () => {
  verificationModal.style.display = 'none';
});

closeVerificationModal.addEventListener('click', () => {
  verificationModal.style.display = 'none';
});

// Close modals when clicking outside
window.addEventListener('click', (e) => {
  if (e.target === projectModal) {
    projectModal.style.display = 'none';
  }
  if (e.target === investmentModal) {
    investmentModal.style.display = 'none';
  }
  if (e.target === paymentModal) {
    paymentModal.style.display = 'none';
  }
  if (e.target === verificationModal) {
    verificationModal.style.display = 'none';
  }
});

// Event listeners for search and filters
searchButton.addEventListener('click', filterProjects);
searchInput.addEventListener('keyup', (e) => {
  if (e.key === 'Enter') {
    filterProjects();
  }
});
categoryFilter.addEventListener('change', filterProjects);
statusFilter.addEventListener('change', filterProjects);

// Event listeners for status tabs
statusTabs.forEach(tab => {
  tab.addEventListener('click', () => {
    statusTabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    
    const status = tab.getAttribute('data-status');
    statusFilter.value = status;
    filterProjects();
  });
});

// Initialize the page
document.addEventListener('DOMContentLoaded', () => {
  renderProjects();
  updateResultsCount();
});

