<?php
class AdminMarketplace_Controller {
    
    public function __construct() {
        // Constructor jika diperlukan
        session_start();
        
        // Cek autentikasi admin (sesuaikan dengan sistem login Anda)
        if (!$this->checkAdminAuth()) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
            exit;
        }
    }
    
    public function index() {
        // Method untuk menampilkan halaman admin marketplace
        $data = [
            'stats' => $this->getDashboardStats(),
            'pending_products' => $this->getPendingProducts(),
            'recent_products' => $this->getRecentProducts(),
            'recent_sellers' => $this->getRecentSellers(),
            'categories' => $this->getCategoriesData()
        ];
        
        return $data;
    }
    
    public function getDashboardData() {
        // Method untuk mendapatkan data dashboard
        $response = [
            'success' => true,
            'stats' => $this->getDashboardStats(),
            'pending_products' => $this->getPendingProducts(),
            'recent_products' => $this->getRecentProducts(),
            'recent_sellers' => $this->getRecentSellers(),
            'monthly_sales' => $this->getMonthlySales(),
            'top_products' => $this->getTopProducts()
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    public function getProducts() {
        // Method untuk mendapatkan semua produk
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $products = $this->getProductsData();
        
        // Filter products
        $filtered_products = array_filter($products, function($product) use ($status, $category, $search) {
            $matches = true;
            
            if ($status && $product['status'] !== $status) {
                $matches = false;
            }
            
            if ($category && $product['category'] !== $category) {
                $matches = false;
            }
            
            if ($search) {
                $search_lower = strtolower($search);
                $matches_search = strpos(strtolower($product['name']), $search_lower) !== false ||
                                 strpos(strtolower($product['seller_name']), $search_lower) !== false ||
                                 strpos(strtolower($product['category']), $search_lower) !== false;
                if (!$matches_search) {
                    $matches = false;
                }
            }
            
            return $matches;
        });
        
        // Pagination
        $total = count($filtered_products);
        $total_pages = ceil($total / $limit);
        $offset = ($page - 1) * $limit;
        $paginated_products = array_slice(array_values($filtered_products), $offset, $limit);
        
        $response = [
            'success' => true,
            'products' => $paginated_products,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'total_pages' => $total_pages
            ]
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    public function getProductDetail($product_id) {
        // Method untuk mendapatkan detail produk
        $products = $this->getProductsData();
        $pending_products = $this->getPendingProductsData();
        
        // Cari di regular products
        foreach ($products as $product) {
            if ($product['id'] == $product_id) {
                $response = [
                    'success' => true,
                    'product' => $product,
                    'sales_history' => $this->getProductSalesHistory($product_id),
                    'similar_products' => $this->getSimilarProducts($product['category'], $product_id)
                ];
                
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }
        }
        
        // Cari di pending products
        foreach ($pending_products as $product) {
            if ($product['id'] == $product_id) {
                $response = [
                    'success' => true,
                    'product' => $product,
                    'is_pending' => true
                ];
                
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }
        }
        
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Produk tidak ditemukan']);
    }
    
    public function addProduct() {
        // Method untuk menambahkan produk baru
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            // Validasi data
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => trim($_POST['price'] ?? ''),
                'stock' => trim($_POST['stock'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'seller_name' => trim($_POST['seller_name'] ?? ''),
                'seller_phone' => trim($_POST['seller_phone'] ?? ''),
                'seller_email' => trim($_POST['seller_email'] ?? ''),
                'seller_location' => trim($_POST['seller_location'] ?? ''),
                'image' => trim($_POST['image'] ?? ''),
                'status' => $_POST['status'] ?? 'pending'
            ];
            
            // Validasi wajib diisi
            if (empty($data['name'])) {
                $errors[] = 'Nama produk harus diisi';
            }
            
            if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
                $errors[] = 'Harga harus angka positif';
            }
            
            if (empty($data['stock']) || !is_numeric($data['stock']) || $data['stock'] < 0) {
                $errors[] = 'Stok harus angka positif';
            }
            
            if (empty($data['category'])) {
                $errors[] = 'Kategori harus dipilih';
            }
            
            if (empty($data['seller_name'])) {
                $errors[] = 'Nama penjual harus diisi';
            }
            
            if (!empty($errors)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            // Format data
            $data['price'] = (int) $data['price'];
            $data['stock'] = (int) $data['stock'];
            
            // Jika tidak ada gambar, gunakan default
            if (empty($data['image'])) {
                $data['image'] = $this->getDefaultImageByCategory($data['category']);
            }
            
            // Generate ID baru
            $existing_products = $this->getProductsData();
            $new_id = count($existing_products) > 0 ? max(array_column($existing_products, 'id')) + 1 : 1;
            
            // Buat produk baru
            $new_product = [
                'id' => $new_id,
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                'image' => $data['image'],
                'category' => $data['category'],
                'seller_name' => $data['seller_name'],
                'seller_phone' => $data['seller_phone'],
                'seller_email' => $data['seller_email'],
                'seller_location' => $data['seller_location'],
                'rating' => 0,
                'reviews' => 0,
                'sales' => 0,
                'status' => $data['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Produk berhasil ditambahkan',
                'product' => $new_product
            ]);
        }
    }
    
    public function updateProduct($product_id) {
        // Method untuk mengupdate produk
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            // Validasi data
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => trim($_POST['price'] ?? ''),
                'stock' => trim($_POST['stock'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'seller_name' => trim($_POST['seller_name'] ?? ''),
                'seller_phone' => trim($_POST['seller_phone'] ?? ''),
                'seller_email' => trim($_POST['seller_email'] ?? ''),
                'seller_location' => trim($_POST['seller_location'] ?? ''),
                'image' => trim($_POST['image'] ?? ''),
                'status' => $_POST['status'] ?? '',
                'rating' => isset($_POST['rating']) ? floatval($_POST['rating']) : null,
                'reviews' => isset($_POST['reviews']) ? intval($_POST['reviews']) : null,
                'sales' => isset($_POST['sales']) ? intval($_POST['sales']) : null
            ];
            
            // Validasi wajib diisi
            if (empty($data['name'])) {
                $errors[] = 'Nama produk harus diisi';
            }
            
            if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
                $errors[] = 'Harga harus angka positif';
            }
            
            if (empty($data['stock']) || !is_numeric($data['stock']) || $data['stock'] < 0) {
                $errors[] = 'Stok harus angka positif';
            }
            
            if (!empty($errors)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            // Format data
            $data['price'] = (int) $data['price'];
            $data['stock'] = (int) $data['stock'];
            
            // Di sini seharusnya ada kode untuk update ke database
            $product = $this->findProductById($product_id);
            
            if (!$product) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Produk tidak ditemukan']);
                return;
            }
            
            // Simulate update
            $updated_product = array_merge($product, $data);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Produk berhasil diperbarui',
                'product' => $updated_product
            ]);
        }
    }
    
    public function deleteProduct($product_id) {
        // Method untuk menghapus produk
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Di sini seharusnya ada kode untuk menghapus dari database
            $product = $this->findProductById($product_id);
            
            if (!$product) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Produk tidak ditemukan']);
                return;
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Produk berhasil dihapus',
                'product_id' => $product_id
            ]);
        }
    }
    
    public function updateProductStatus() {
        // Method untuk mengupdate status produk
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'] ?? '';
            $status = $_POST['status'] ?? '';
            
            if (empty($product_id) || empty($status)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'ID produk dan status harus diisi']);
                return;
            }
            
            $valid_statuses = ['active', 'pending', 'inactive'];
            if (!in_array($status, $valid_statuses)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Status tidak valid']);
                return;
            }
            
            $product = $this->findProductById($product_id);
            
            if (!$product) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Produk tidak ditemukan']);
                return;
            }
            
            // Simulate status update
            $product['status'] = $status;
            $product['updated_at'] = date('Y-m-d H:i:s');
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Status produk berhasil diperbarui',
                'product' => $product
            ]);
        }
    }
    
    public function verifyProduct() {
        // Method untuk memverifikasi produk pending
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'] ?? '';
            
            if (empty($product_id)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'ID produk harus diisi']);
                return;
            }
            
            $pending_products = $this->getPendingProductsData();
            $pending_index = array_search($product_id, array_column($pending_products, 'id'));
            
            if ($pending_index === false) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Produk pending tidak ditemukan']);
                return;
            }
            
            $product = $pending_products[$pending_index];
            
            // Move from pending to regular products
            $products = $this->getProductsData();
            $new_id = count($products) > 0 ? max(array_column($products, 'id')) + 1 : 1;
            
            $verified_product = [
                'id' => $new_id,
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => 100, // Default stock
                'image' => $product['image'],
                'category' => $product['category'],
                'seller_name' => $product['seller'],
                'seller_phone' => $this->getSellerPhone($product['seller']),
                'seller_email' => $this->getSellerEmail($product['seller']),
                'seller_location' => $this->getSellerLocation($product['seller']),
                'rating' => 0,
                'reviews' => 0,
                'sales' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Produk berhasil diverifikasi',
                'product' => $verified_product
            ]);
        }
    }
    
    public function rejectProduct() {
        // Method untuk menolak produk pending
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'] ?? '';
            
            if (empty($product_id)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'ID produk harus diisi']);
                return;
            }
            
            // Di sini seharusnya ada kode untuk menghapus dari pending products
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Produk berhasil ditolak',
                'product_id' => $product_id
            ]);
        }
    }
    
    public function getSellers() {
        // Method untuk mendapatkan semua penjual
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $sellers = $this->getSellersData();
        
        // Filter sellers
        $filtered_sellers = array_filter($sellers, function($seller) use ($status, $search) {
            $matches = true;
            
            if ($status && $seller['status'] !== $status) {
                $matches = false;
            }
            
            if ($search) {
                $search_lower = strtolower($search);
                $matches_search = strpos(strtolower($seller['name']), $search_lower) !== false ||
                                 strpos(strtolower($seller['email']), $search_lower) !== false ||
                                 strpos(strtolower($seller['location']), $search_lower) !== false;
                if (!$matches_search) {
                    $matches = false;
                }
            }
            
            return $matches;
        });
        
        // Pagination
        $total = count($filtered_sellers);
        $total_pages = ceil($total / $limit);
        $offset = ($page - 1) * $limit;
        $paginated_sellers = array_slice(array_values($filtered_sellers), $offset, $limit);
        
        $response = [
            'success' => true,
            'sellers' => $paginated_sellers,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'total_pages' => $total_pages
            ]
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    public function getSellerDetail($seller_id) {
        // Method untuk mendapatkan detail penjual
        $sellers = $this->getSellersData();
        
        foreach ($sellers as $seller) {
            if ($seller['id'] == $seller_id) {
                $response = [
                    'success' => true,
                    'seller' => $seller,
                    'products' => $this->getSellerProducts($seller_id),
                    'sales_history' => $this->getSellerSalesHistory($seller_id)
                ];
                
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }
        }
        
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Penjual tidak ditemukan']);
    }
    
    public function updateSellerStatus() {
        // Method untuk mengupdate status penjual
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $seller_id = $_POST['seller_id'] ?? '';
            $status = $_POST['status'] ?? '';
            
            if (empty($seller_id) || empty($status)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'ID penjual dan status harus diisi']);
                return;
            }
            
            $valid_statuses = ['verified', 'pending', 'suspended'];
            if (!in_array($status, $valid_statuses)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Status tidak valid']);
                return;
            }
            
            $seller = $this->findSellerById($seller_id);
            
            if (!$seller) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Penjual tidak ditemukan']);
                return;
            }
            
            // Simulate status update
            $seller['status'] = $status;
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Status penjual berhasil diperbarui',
                'seller' => $seller
            ]);
        }
    }
    
    public function addSeller() {
        // Method untuk menambahkan penjual baru
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            // Validasi data
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'status' => $_POST['status'] ?? 'pending'
            ];
            
            // Validasi wajib diisi
            if (empty($data['name'])) {
                $errors[] = 'Nama penjual harus diisi';
            }
            
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email harus valid';
            }
            
            if (empty($data['phone'])) {
                $errors[] = 'Telepon harus diisi';
            }
            
            if (!empty($errors)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            // Generate ID baru
            $existing_sellers = $this->getSellersData();
            $new_id = count($existing_sellers) > 0 ? max(array_column($existing_sellers, 'id')) + 1 : 1;
            
            // Buat penjual baru
            $new_seller = [
                'id' => $new_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'location' => $data['location'],
                'products' => 0,
                'join_date' => date('Y-m-d'),
                'status' => $data['status'],
                'rating' => 0,
                'total_sales' => 0
            ];
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Penjual berhasil ditambahkan',
                'seller' => $new_seller
            ]);
        }
    }
    
    public function updateSeller($seller_id) {
        // Method untuk mengupdate penjual
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            // Validasi data
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'status' => $_POST['status'] ?? ''
            ];
            
            // Validasi wajib diisi
            if (empty($data['name'])) {
                $errors[] = 'Nama penjual harus diisi';
            }
            
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email harus valid';
            }
            
            if (!empty($errors)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            $seller = $this->findSellerById($seller_id);
            
            if (!$seller) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Penjual tidak ditemukan']);
                return;
            }
            
            // Simulate update
            $updated_seller = array_merge($seller, $data);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Penjual berhasil diperbarui',
                'seller' => $updated_seller
            ]);
        }
    }
    
    public function deleteSeller($seller_id) {
        // Method untuk menghapus penjual
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $seller = $this->findSellerById($seller_id);
            
            if (!$seller) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Penjual tidak ditemukan']);
                return;
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Penjual berhasil dihapus',
                'seller_id' => $seller_id
            ]);
        }
    }
    
    public function getCategories() {
        // Method untuk mendapatkan semua kategori
        $categories = $this->getCategoriesData();
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'categories' => $categories
        ]);
    }
    
    public function addCategory() {
        // Method untuk menambahkan kategori baru
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            // Validasi data
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'icon' => trim($_POST['icon'] ?? '📦'),
                'description' => trim($_POST['description'] ?? ''),
                'productCount' => isset($_POST['productCount']) ? intval($_POST['productCount']) : 0
            ];
            
            // Validasi wajib diisi
            if (empty($data['name'])) {
                $errors[] = 'Nama kategori harus diisi';
            }
            
            if (empty($data['description'])) {
                $errors[] = 'Deskripsi harus diisi';
            }
            
            if (!empty($errors)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            // Generate ID baru
            $existing_categories = $this->getCategoriesData();
            $new_id = count($existing_categories) > 0 ? max(array_column($existing_categories, 'id')) + 1 : 1;
            
            // Buat kategori baru
            $new_category = [
                'id' => $new_id,
                'name' => $data['name'],
                'icon' => $data['icon'],
                'productCount' => $data['productCount'],
                'description' => $data['description']
            ];
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Kategori berhasil ditambahkan',
                'category' => $new_category
            ]);
        }
    }
    
    public function updateCategory($category_id) {
        // Method untuk mengupdate kategori
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            // Validasi data
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'icon' => trim($_POST['icon'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'productCount' => isset($_POST['productCount']) ? intval($_POST['productCount']) : null
            ];
            
            // Validasi wajib diisi
            if (empty($data['name'])) {
                $errors[] = 'Nama kategori harus diisi';
            }
            
            if (!empty($errors)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            $category = $this->findCategoryById($category_id);
            
            if (!$category) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Kategori tidak ditemukan']);
                return;
            }
            
            // Simulate update
            $updated_category = array_merge($category, $data);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Kategori berhasil diperbarui',
                'category' => $updated_category
            ]);
        }
    }
    
    public function deleteCategory($category_id) {
        // Method untuk menghapus kategori
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = $this->findCategoryById($category_id);
            
            if (!$category) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Kategori tidak ditemukan']);
                return;
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Kategori berhasil dihapus',
                'category_id' => $category_id
            ]);
        }
    }
    
    public function getReports() {
        // Method untuk mendapatkan laporan
        $type = isset($_GET['type']) ? $_GET['type'] : 'monthly';
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
        
        $reports = [
            'sales_report' => $this->getSalesReport($type, $start_date, $end_date),
            'products_report' => $this->getProductsReport($type, $start_date, $end_date),
            'sellers_report' => $this->getSellersReport($type, $start_date, $end_date),
            'categories_report' => $this->getCategoriesReport($type, $start_date, $end_date)
        ];
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'reports' => $reports
        ]);
    }
    
    public function exportData() {
        // Method untuk ekspor data
        $type = isset($_GET['type']) ? $_GET['type'] : 'products';
        
        switch ($type) {
            case 'products':
                $data = $this->getProductsData();
                $filename = 'products_export_' . date('Y-m-d') . '.json';
                break;
            case 'sellers':
                $data = $this->getSellersData();
                $filename = 'sellers_export_' . date('Y-m-d') . '.json';
                break;
            case 'categories':
                $data = $this->getCategoriesData();
                $filename = 'categories_export_' . date('Y-m-d') . '.json';
                break;
            default:
                $data = $this->getProductsData();
                $filename = 'export_' . date('Y-m-d') . '.json';
        }
        
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    

    // Helper methods
    private function checkAdminAuth() {
        // Cek apakah user adalah admin
        // Sesuaikan dengan sistem autentikasi Anda
        return isset($_SESSION['admin_id']) && $_SESSION['admin_role'] === 'admin';
    }
    
    private function getDashboardStats() {
        $products = $this->getProductsData();
        $sellers = $this->getSellersData();
        $pending_products = $this->getPendingProductsData();
        
        $total_products = count($products);
        $total_sellers = count($sellers);
        $pending_verifications = count($pending_products);
        $active_products = count(array_filter($products, function($p) { return $p['status'] === 'active'; }));
        $verified_sellers = count(array_filter($sellers, function($s) { return $s['status'] === 'verified'; }));
        
        $total_revenue = array_sum(array_map(function($p) { return $p['price'] * $p['sales']; }, $products));
        
        return [
            'totalProducts' => $total_products,
            'totalSellers' => $total_sellers,
            'pendingVerifications' => $pending_verifications,
            'monthlyRevenue' => $total_revenue,
            'activeProducts' => $active_products,
            'verifiedSellers' => $verified_sellers,
            'totalRevenue' => $total_revenue,
            'avgProductPrice' => $total_products > 0 ? round(array_sum(array_column($products, 'price')) / $total_products) : 0
        ];
    }
    
    private function getPendingProducts() {
        return $this->getPendingProductsData();
    }
    
    private function getRecentProducts($limit = 5) {
        $products = $this->getProductsData();
        
        // Sort by created_at descending
        usort($products, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return array_slice($products, 0, $limit);
    }
    
    private function getRecentSellers($limit = 5) {
        $sellers = $this->getSellersData();
        
        // Sort by join_date descending
        usort($sellers, function($a, $b) {
            return strtotime($b['join_date']) - strtotime($a['join_date']);
        });
        
        return array_slice($sellers, 0, $limit);
    }
    
    private function getMonthlySales() {
        // Sample monthly sales data
        return [
            ['month' => 'Jan', 'sales' => 120, 'revenue' => 12000000],
            ['month' => 'Feb', 'sales' => 150, 'revenue' => 15000000],
            ['month' => 'Mar', 'sales' => 180, 'revenue' => 18000000],
            ['month' => 'Apr', 'sales' => 200, 'revenue' => 20000000],
            ['month' => 'May', 'sales' => 220, 'revenue' => 22000000],
            ['month' => 'Jun', 'sales' => 250, 'revenue' => 25000000]
        ];
    }
    
    private function getTopProducts($limit = 5) {
        $products = $this->getProductsData();
        
        // Sort by sales descending
        usort($products, function($a, $b) {
            return $b['sales'] - $a['sales'];
        });
        
        return array_slice($products, 0, $limit);
    }
    
    private function getProductSalesHistory($product_id) {
        // Sample sales history
        return [
            ['date' => '2024-01-15', 'quantity' => 5, 'amount' => 250000],
            ['date' => '2024-01-20', 'quantity' => 3, 'amount' => 150000],
            ['date' => '2024-01-25', 'quantity' => 7, 'amount' => 350000],
            ['date' => '2024-02-01', 'quantity' => 4, 'amount' => 200000],
            ['date' => '2024-02-05', 'quantity' => 6, 'amount' => 300000]
        ];
    }
    
    private function getSimilarProducts($category, $exclude_id, $limit = 3) {
        $products = $this->getProductsData();
        
        $similar = array_filter($products, function($product) use ($category, $exclude_id) {
            return $product['category'] === $category && $product['id'] != $exclude_id;
        });
        
        return array_slice(array_values($similar), 0, $limit);
    }
    
    private function getSellerProducts($seller_id, $limit = 5) {
        $products = $this->getProductsData();
        $seller = $this->findSellerById($seller_id);
        
        if (!$seller) {
            return [];
        }
        
        $seller_products = array_filter($products, function($product) use ($seller) {
            return $product['seller_name'] === $seller['name'];
        });
        
        return array_slice(array_values($seller_products), 0, $limit);
    }
    
    private function getSellerSalesHistory($seller_id) {
        // Sample sales history for seller
        return [
            ['date' => '2024-01', 'sales' => 15, 'revenue' => 7500000],
            ['date' => '2024-02', 'sales' => 18, 'revenue' => 9000000],
            ['date' => '2024-03', 'sales' => 22, 'revenue' => 11000000],
            ['date' => '2024-04', 'sales' => 25, 'revenue' => 12500000]
        ];
    }
    
    private function getSalesReport($type, $start_date, $end_date) {
        // Sample sales report
        return [
            'total_sales' => 1245,
            'total_revenue' => 1580000000,
            'average_order_value' => 1269076,
            'growth_rate' => 15.5,
            'data' => $this->getMonthlySales()
        ];
    }
    
    private function getProductsReport($type, $start_date, $end_date) {
        $products = $this->getProductsData();
        
        return [
            'total_products' => count($products),
            'active_products' => count(array_filter($products, function($p) { return $p['status'] === 'active'; })),
            'pending_products' => count(array_filter($products, function($p) { return $p['status'] === 'pending'; })),
            'inactive_products' => count(array_filter($products, function($p) { return $p['status'] === 'inactive'; })),
            'top_categories' => $this->getTopCategories()
        ];
    }
    
    private function getSellersReport($type, $start_date, $end_date) {
        $sellers = $this->getSellersData();
        
        return [
            'total_sellers' => count($sellers),
            'verified_sellers' => count(array_filter($sellers, function($s) { return $s['status'] === 'verified'; })),
            'pending_sellers' => count(array_filter($sellers, function($s) { return $s['status'] === 'pending'; })),
            'suspended_sellers' => count(array_filter($sellers, function($s) { return $s['status'] === 'suspended'; })),
            'top_sellers' => $this->getTopSellers()
        ];
    }
    
    private function getCategoriesReport($type, $start_date, $end_date) {
        return [
            'total_categories' => count($this->getCategoriesData()),
            'top_categories' => $this->getTopCategories()
        ];
    }
    
    private function getTopCategories() {
        $products = $this->getProductsData();
        
        $category_counts = [];
        foreach ($products as $product) {
            $category = $product['category'];
            if (!isset($category_counts[$category])) {
                $category_counts[$category] = 0;
            }
            $category_counts[$category]++;
        }
        
        arsort($category_counts);
        
        $top_categories = [];
        foreach ($category_counts as $category => $count) {
            $top_categories[] = [
                'category' => $category,
                'count' => $count,
                'percentage' => round(($count / count($products)) * 100, 1)
            ];
        }
        
        return array_slice($top_categories, 0, 5);
    }
    
    private function getTopSellers() {
        $sellers = $this->getSellersData();
        
        usort($sellers, function($a, $b) {
            return $b['total_sales'] - $a['total_sales'];
        });
        
        return array_slice($sellers, 0, 5);
    }
    
    private function findProductById($product_id) {
        $products = $this->getProductsData();
        
        foreach ($products as $product) {
            if ($product['id'] == $product_id) {
                return $product;
            }
        }
        
        return null;
    }
    
    private function findSellerById($seller_id) {
        $sellers = $this->getSellersData();
        
        foreach ($sellers as $seller) {
            if ($seller['id'] == $seller_id) {
                return $seller;
            }
        }
        
        return null;
    }
    
    private function findCategoryById($category_id) {
        $categories = $this->getCategoriesData();
        
        foreach ($categories as $category) {
            if ($category['id'] == $category_id) {
                return $category;
            }
        }
        
        return null;
    }
    
    private function getSellerPhone($seller_name) {
        $sellers = $this->getSellersData();
        
        foreach ($sellers as $seller) {
            if ($seller['name'] === $seller_name) {
                return $seller['phone'];
            }
        }
        
        return '628123456789';
    }
    
    private function getSellerEmail($seller_name) {
        $sellers = $this->getSellersData();
        
        foreach ($sellers as $seller) {
            if ($seller['name'] === $seller_name) {
                return $seller['email'];
            }
        }
        
        return 'seller@email.com';
    }
    
    private function getSellerLocation($seller_name) {
        $sellers = $this->getSellersData();
        
        foreach ($sellers as $seller) {
            if ($seller['name'] === $seller_name) {
                return $seller['location'];
            }
        }
        
        return 'Lokasi Penjual';
    }
    
    private function getProductsData() {
        return [
            [
                'id' => 1,
                'name' => 'Bibit Padi Unggul',
                'description' => 'Bibit padi unggul dengan hasil panen maksimal, tahan hama dan penyakit',
                'price' => 50000,
                'stock' => 100,
                'image' => 'https://tse2.mm.bing.net/th/id/OIP.QgC2fruskE2Lx-2z8UQs-AHaHa?pid=Api&P=0&h=180',
                'category' => 'Bibit Tanaman',
                'seller_name' => 'Budi Santoso',
                'seller_phone' => '628123456789',
                'seller_email' => 'budi@email.com',
                'seller_location' => 'Karawang, Jawa Barat',
                'rating' => 4.5,
                'reviews' => 24,
                'sales' => 45,
                'status' => 'active',
                'created_at' => '2024-01-15 10:30:00',
                'updated_at' => '2024-01-15 10:30:00'
            ],
            [
                'id' => 2,
                'name' => 'Pupuk Organik Cair',
                'description' => 'Pupuk organik cair untuk meningkatkan kesuburan tanah dan pertumbuhan tanaman',
                'price' => 75000,
                'stock' => 50,
                'image' => 'https://lzd-img-global.slatic.net/g/p/ae7d2d75882018e60832e0c1a3e3b61b.jpg_720x720q80.jpg_.webp',
                'category' => 'Pupuk Organik',
                'seller_name' => 'Sari Dewi',
                'seller_phone' => '628987654321',
                'seller_email' => 'sari@email.com',
                'seller_location' => 'Bogor, Jawa Barat',
                'rating' => 4.8,
                'reviews' => 31,
                'sales' => 28,
                'status' => 'active',
                'created_at' => '2024-01-10 14:20:00',
                'updated_at' => '2024-01-10 14:20:00'
            ],
            [
                'id' => 3,
                'name' => 'Traktor Mini',
                'description' => 'Traktor mini untuk lahan pertanian kecil dan menengah, mudah dioperasikan',
                'price' => 12500000,
                'stock' => 5,
                'image' => 'https://image.ceneostatic.pl/data/products/109749301/i-mini-traktor-traktorek-kubota-5001-nowy-20000netto.jpg',
                'category' => 'Alat Pertanian',
                'seller_name' => 'Budi Santoso',
                'seller_phone' => '628123456789',
                'seller_email' => 'budi@email.com',
                'seller_location' => 'Karawang, Jawa Barat',
                'rating' => 4.3,
                'reviews' => 8,
                'sales' => 3,
                'status' => 'active',
                'created_at' => '2024-01-05 09:15:00',
                'updated_at' => '2024-01-05 09:15:00'
            ],
            [
                'id' => 4,
                'name' => 'Benih Jagung Manis',
                'description' => 'Benih jagung manis hibrida dengan rasa manis alami',
                'price' => 35000,
                'stock' => 200,
                'image' => 'https://tse4.mm.bing.net/th/id/OIP.XE0Wmh2QnHzFHSfRxUv-jgHaHa?pid=Api&P=0&h=180',
                'category' => 'Bibit Tanaman',
                'seller_name' => 'Agus Setiawan',
                'seller_phone' => '628555123456',
                'seller_email' => 'agus@email.com',
                'seller_location' => 'Magelang, Jawa Tengah',
                'rating' => 0,
                'reviews' => 0,
                'sales' => 0,
                'status' => 'pending',
                'created_at' => '2024-01-20 11:00:00',
                'updated_at' => '2024-01-20 11:00:00'
            ]
        ];
    }
    
    private function getPendingProductsData() {
        return [
            [
                'id' => 1,
                'name' => 'Benih Jagung Manis',
                'description' => 'Benih jagung manis hibrida dengan rasa manis alami',
                'price' => 35000,
                'image' => 'https://tse4.mm.bing.net/th/id/OIP.XE0Wmh2QnHzFHSfRxUv-jgHaHa?pid=Api&P=0&h=180',
                'category' => 'Bibit Tanaman',
                'seller' => 'Agus Setiawan',
                'date' => '2024-01-20'
            ],
            [
                'id' => 2,
                'name' => 'Pestisida Organik',
                'description' => 'Pestisida organik ramah lingkungan untuk tanaman',
                'price' => 45000,
                'image' => 'https://tse1.mm.bing.net/th/id/OIP.9mOjmZPcK9lZqoMqVdI-xQHaHa?pid=Api&P=0&h=180',
                'category' => 'Obat Tanaman',
                'seller' => 'CV Tani Sehat',
                'date' => '2024-01-19'
            ],
            [
                'id' => 3,
                'name' => 'Bibit Cabai Rawit',
                'description' => 'Bibit cabai rawit unggul tahan penyakit',
                'price' => 25000,
                'image' => 'https://tse2.mm.bing.net/th/id/OIP.0Z7qV7Q6m6X6X6X6X6X6X6HaHa?pid=Api&P=0&h=180',
                'category' => 'Bibit Tanaman',
                'seller' => 'Petani Lokal',
                'date' => '2024-01-18'
            ]
        ];
    }
    
    private function getSellersData() {
        return [
            [
                'id' => 1,
                'name' => 'Budi Santoso',
                'email' => 'budi@email.com',
                'phone' => '628123456789',
                'location' => 'Karawang, Jawa Barat',
                'products' => 15,
                'join_date' => '2024-01-15',
                'status' => 'verified',
                'rating' => 4.5,
                'total_sales' => 48
            ],
            [
                'id' => 2,
                'name' => 'Sari Dewi',
                'email' => 'sari@email.com',
                'phone' => '628987654321',
                'location' => 'Bogor, Jawa Barat',
                'products' => 8,
                'join_date' => '2024-01-10',
                'status' => 'verified',
                'rating' => 4.8,
                'total_sales' => 28
            ],
            [
                'id' => 3,
                'name' => 'Agus Setiawan',
                'email' => 'agus@email.com',
                'phone' => '628555123456',
                'location' => 'Magelang, Jawa Tengah',
                'products' => 5,
                'join_date' => '2024-01-12',
                'status' => 'pending',
                'rating' => 4.6,
                'total_sales' => 0
            ],
            [
                'id' => 4,
                'name' => 'CV Agro Teknik',
                'email' => 'agroteknik@email.com',
                'phone' => '628112233445',
                'location' => 'Surabaya, Jawa Timur',
                'products' => 12,
                'join_date' => '2024-01-08',
                'status' => 'verified',
                'rating' => 4.3,
                'total_sales' => 13
            ],
            [
                'id' => 5,
                'name' => 'Petani Organik',
                'email' => 'organik@email.com',
                'phone' => '628777888999',
                'location' => 'Yogyakarta',
                'products' => 6,
                'join_date' => '2024-01-03',
                'status' => 'suspended',
                'rating' => 4.7,
                'total_sales' => 89
            ]
        ];
    }
    
    private function getCategoriesData() {
        return [
            [
                'id' => 1,
                'name' => 'Bibit Tanaman',
                'icon' => '🌱',
                'productCount' => 45,
                'description' => 'Berbagai jenis bibit tanaman pertanian'
            ],
            [
                'id' => 2,
                'name' => 'Pupuk Organik',
                'icon' => '🧪',
                'productCount' => 32,
                'description' => 'Pupuk organik untuk kesuburan tanah'
            ],
            [
                'id' => 3,
                'name' => 'Alat Pertanian',
                'icon' => '🔧',
                'productCount' => 28,
                'description' => 'Alat-alat modern untuk pertanian'
            ],
            [
                'id' => 4,
                'name' => 'Hasil Panen',
                'icon' => '🌾',
                'productCount' => 67,
                'description' => 'Hasil panen berkualitas dari petani'
            ],
            [
                'id' => 5,
                'name' => 'Obat Tanaman',
                'icon' => '💊',
                'productCount' => 24,
                'description' => 'Pestisida dan obat tanaman'
            ],
            [
                'id' => 6,
                'name' => 'Benih Buah',
                'icon' => '🍓',
                'productCount' => 38,
                'description' => 'Benih buah-buahan segar'
            ],
            [
                'id' => 7,
                'name' => 'Perlengkapan Petani',
                'icon' => '👷',
                'productCount' => 19,
                'description' => 'Perlengkapan petani sehari-hari'
            ]
        ];
    }
    
    private function getDefaultImageByCategory($category) {
        $default_images = [
            'Bibit Tanaman' => 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=500&h=300&fit=crop',
            'Pupuk Organik' => 'https://images.unsplash.com/photo-1585771724684-382b024b4e76?w=500&h=300&fit=crop',
            'Alat Pertanian' => 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=500&h=300&fit=crop',
            'Hasil Panen' => 'https://images.unsplash.com/photo-1428660386617-8d277e7deaf2?w=500&h=300&fit=crop',
            'Obat Tanaman' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500&h=300&fit=crop',
            'Benih Buah' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&h=300&fit=crop',
            'Perlengkapan Petani' => 'https://images.unsplash.com/photo-1585734264571-61c9389c4b69?w=500&h=300&fit=crop'
        ];
        
        return $default_images[$category] ?? 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=500&h=300&fit=crop';
    }
}