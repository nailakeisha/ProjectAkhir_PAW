controller

<?php
class AdminMarketplace_Controller {
    
    public function __construct() {
        // Constructor jika diperlukan
    }
    
    public function index() {
        // Method untuk menampilkan halaman admin marketplace
        $data = [
            'stats' => $this->getDashboardStats(),
            'recent_products' => $this->getRecentProducts(),
            'recent_sellers' => $this->getRecentSellers(),
            'categories' => $this->getCategoriesData()
        ];
        
        return $data;
    }
    
    public function getDashboardData() {
        // Method untuk mendapatkan data dashboard
        $response = [
            'stats' => $this->getDashboardStats(),
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
                                 strpos(strtolower($product['description']), $search_lower) !== false ||
                                 strpos(strtolower($product['seller_name']), $search_lower) !== false;
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
            $new_id = count($existing_products) + 1;
            
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
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Produk berhasil diperbarui',
                'product_id' => $product_id,
                'data' => $data
            ]);
        }
    }
    
    public function deleteProduct($product_id) {
        // Method untuk menghapus produk
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Di sini seharusnya ada kode untuk menghapus dari database
            
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
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Status produk berhasil diperbarui',
                'product_id' => $product_id,
                'status' => $status
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
            
            $valid_statuses = ['active', 'pending', 'inactive'];
            if (!in_array($status, $valid_statuses)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Status tidak valid']);
                return;
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Status penjual berhasil diperbarui',
                'seller_id' => $seller_id,
                'status' => $status
            ]);
        }
    }
    
    public function getCategories() {
        // Method untuk mendapatkan semua kategori
        $categories = $this->getCategoriesData();
        
        header('Content-Type: application/json');
        echo json_encode($categories);
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
        echo json_encode($reports);
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
    private function getDashboardStats() {
        $products = $this->getProductsData();
        $sellers = $this->getSellersData();
        
        $total_products = count($products);
        $total_sellers = count($sellers);
        $active_products = count(array_filter($products, function($p) { return $p['status'] === 'active'; }));
        $active_sellers = count(array_filter($sellers, function($s) { return $s['status'] === 'active'; }));
        $pending_products = count(array_filter($products, function($p) { return $p['status'] === 'pending'; }));
        $pending_sellers = count(array_filter($sellers, function($s) { return $s['status'] === 'pending'; }));
        
        $total_revenue = array_sum(array_column($products, 'price'));
        $total_sales = array_sum(array_column($products, 'sales'));
        
        return [
            'total_products' => $total_products,
            'total_sellers' => $total_sellers,
            'active_products' => $active_products,
            'active_sellers' => $active_sellers,
            'pending_products' => $pending_products,
            'pending_sellers' => $pending_sellers,
            'total_revenue' => $total_revenue,
            'total_sales' => $total_sales,
            'avg_product_price' => $total_products > 0 ? round($total_revenue / $total_products) : 0,
            'avg_seller_products' => $total_sellers > 0 ? round($total_products / $total_sellers) : 0
        ];
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
        
        $seller_products = array_filter($products, function($product) use ($seller_id) {
            return $product['seller_name'] === $seller_id; // This should be seller_id matching in real implementation
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
            'active_sellers' => count(array_filter($sellers, function($s) { return $s['status'] === 'active'; })),
            'pending_sellers' => count(array_filter($sellers, function($s) { return $s['status'] === 'pending'; })),
            'inactive_sellers' => count(array_filter($sellers, function($s) { return $s['status'] === 'inactive'; })),
            'top_sellers' => $this->getTopSellers()
        ];
    }
    
    private function getCategoriesReport($type, $start_date, $end_date) {
        return [
            'total_categories' => 8,
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
                'description' => 'Benih jagung manis hibrida dengan rasa manis alami. Hasil panen melimpah dengan umur panen 65-70 hari.',
                'price' => 35000,
                'stock' => 200,
                'image' => 'https://tse4.mm.bing.net/th/id/OIP.XE0Wmh2QnHzFHSfRxUv-jgHaHa?pid=Api&P=0&h=180',
                'category' => 'Bibit Tanaman',
                'seller_name' => 'Agus Setiawan',
                'seller_phone' => '628555123456',
                'seller_email' => 'agus@email.com',
                'seller_location' => 'Magelang, Jawa Tengah',
                'rating' => 4.6,
                'reviews' => 42,
                'sales' => 0,
                'status' => 'pending',
                'created_at' => '2024-01-12 11:45:00',
                'updated_at' => '2024-01-12 11:45:00'
            ],
            [
                'id' => 5,
                'name' => 'Alat Penyiram Otomatis',
                'description' => 'Sistem penyiraman otomatis dengan timer digital untuk efisiensi waktu dan air. Cocok untuk greenhouse dan kebun.',
                'price' => 450000,
                'stock' => 25,
                'image' => 'https://cf.shopee.co.id/file/bccb478ccd517bf1204cff96f2299de0',
                'category' => 'Alat Pertanian',
                'seller_name' => 'CV Agro Teknik',
                'seller_phone' => '628112233445',
                'seller_email' => 'agroteknik@email.com',
                'seller_location' => 'Surabaya, Jawa Timur',
                'rating' => 4.4,
                'reviews' => 17,
                'sales' => 12,
                'status' => 'active',
                'created_at' => '2024-01-08 13:30:00',
                'updated_at' => '2024-01-08 13:30:00'
            ],
            [
                'id' => 6,
                'name' => 'Pupuk Kompos 5kg',
                'description' => 'Pupuk kompos organik dari kotoran sapi yang sudah difermentasi. Memperbaiki struktur tanah dan meningkatkan kesuburan.',
                'price' => 35000,
                'stock' => 150,
                'image' => 'https://tse1.mm.bing.net/th/id/OIP.sJ42iNUhTT4h8q-JMym5DwHaHc?pid=Api&P=0&h=180',
                'category' => 'Pupuk Organik',
                'seller_name' => 'Petani Organik',
                'seller_phone' => '628777888999',
                'seller_email' => 'organik@email.com',
                'seller_location' => 'Yogyakarta',
                'rating' => 4.7,
                'reviews' => 56,
                'sales' => 89,
                'status' => 'inactive',
                'created_at' => '2024-01-03 09:00:00',
                'updated_at' => '2024-01-03 09:00:00'
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
                'status' => 'active',
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
                'status' => 'active',
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
                'status' => 'active',
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
                'status' => 'inactive',
                'rating' => 4.7,
                'total_sales' => 89
            ]
        ];
    }
    
    private function getCategoriesData() {
        return [
            ['id' => 1, 'name' => 'Bibit Tanaman', 'count' => 45, 'status' => 'active'],
            ['id' => 2, 'name' => 'Pupuk Organik', 'count' => 32, 'status' => 'active'],
            ['id' => 3, 'name' => 'Alat Pertanian', 'count' => 28, 'status' => 'active'],
            ['id' => 4, 'name' => 'Hasil Panen', 'count' => 67, 'status' => 'active'],
            ['id' => 5, 'name' => 'Obat Tanaman', 'count' => 24, 'status' => 'pending'],
            ['id' => 6, 'name' => 'Benih Buah', 'count' => 38, 'status' => 'active'],
            ['id' => 7, 'name' => 'Perlengkapan Petani', 'count' => 19, 'status' => 'active'],
            ['id' => 8, 'name' => 'Paket Usaha Tani', 'count' => 12, 'status' => 'inactive']
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
            'Perlengkapan Petani' => 'https://images.unsplash.com/photo-1585734264571-61c9389c4b69?w=500&h=300&fit=crop',
            'Paket Usaha Tani' => 'https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=500&h=300&fit=crop'
        ];
        
        return $default_images[$category] ?? 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=500&h=300&fit=crop';
    }
}

// Router/Endpoint Handler untuk Admin
if (isset($_GET['action'])) {
    $controller = new AdminMarketplace_Controller();
    $action = $_GET['action'];
    
    try {
        switch ($action) {
            case 'getDashboardData':
                $controller->getDashboardData();
                break;
                
            case 'getProducts':
                $controller->getProducts();
                break;
                
            case 'getProductDetail':
                $product_id = $_GET['id'] ?? '';
                $controller->getProductDetail($product_id);
                break;
                
            case 'addProduct':
                $controller->addProduct();
                break;
                
            case 'updateProduct':
                $product_id = $_GET['id'] ?? '';
                $controller->updateProduct($product_id);
                break;
                
            case 'deleteProduct':
                $product_id = $_GET['id'] ?? '';
                $controller->deleteProduct($product_id);
                break;
                
            case 'updateProductStatus':
                $controller->updateProductStatus();
                break;
                
            case 'getSellers':
                $controller->getSellers();
                break;
                
            case 'getSellerDetail':
                $seller_id = $_GET['id'] ?? '';
                $controller->getSellerDetail($seller_id);
                break;
                
            case 'updateSellerStatus':
                $controller->updateSellerStatus();
                break;
                
            case 'getCategories':
                $controller->getCategories();
                break;
                
            case 'getReports':
                $controller->getReports();
                break;
                
            case 'exportData':
                $controller->exportData();
                break;
                
            default:
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Action tidak valid']);
                break;
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}
?>