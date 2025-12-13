<?php
/**
 * Admin Investasi Controller
 * Controller untuk mengelola halaman admin investasi
 * 
 * @author Niseva Agro Team
 * @version 2.0
 */

class AdminInvestasi_Controller {
    
    private $db;
    private $investmentsTable = 'investments';
    private $projectsTable = 'projects';
    
    public function __construct() {
        session_start();
        $this->checkAdminAuth();
        // Uncomment jika menggunakan database
        // $this->db = new Database();
    }
    
    /**
     * Cek autentikasi admin
     */
    private function checkAdminAuth() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: /admin/login');
            exit;
        }
    }
    
    /**
     * Tampilkan halaman utama admin investasi
     */
    public function index() {
        // Set headers untuk mencegah caching
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        
        // Load halaman admin
        require_once __DIR__ . '/../public/admin-investasi.html';
    }
    
    /**
     * API: Get all investments
     * GET /admin/investasi/api/investments
     */
    public function getInvestments() {
        header('Content-Type: application/json');
        
        try {
            // Filter berdasarkan status jika ada
            $status = $_GET['status'] ?? null;
            
            // Dalam implementasi real, query dari database
            // $query = "SELECT i.*, p.title as project_title 
            //           FROM {$this->investmentsTable} i 
            //           LEFT JOIN {$this->projectsTable} p ON i.project_id = p.id";
            // if ($status) {
            //     $query .= " WHERE i.status = ?";
            // }
            // $investments = $this->db->query($query, [$status])->fetchAll();
            
            // Sample data untuk development
            $investments = $this->getSampleInvestments();
            
            if ($status) {
                $investments = array_filter($investments, function($inv) use ($status) {
                    return $inv['status'] === $status;
                });
                $investments = array_values($investments); // Re-index array
            }
            
            echo json_encode([
                'success' => true,
                'data' => $investments,
                'count' => count($investments)
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error fetching investments: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * API: Get all projects
     * GET /admin/investasi/api/projects
     */
    public function getProjects() {
        header('Content-Type: application/json');
        
        try {
            // Dalam implementasi real, query dari database
            // $query = "SELECT * FROM {$this->projectsTable} ORDER BY id DESC";
            // $projects = $this->db->query($query)->fetchAll();
            
            // Sample data untuk development
            $projects = $this->getSampleProjects();
            
            echo json_encode([
                'success' => true,
                'data' => $projects,
                'count' => count($projects)
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error fetching projects: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * API: Get statistics
     * GET /admin/investasi/api/statistics
     */
    public function getStatistics() {
        header('Content-Type: application/json');
        
        try {
            // Dalam implementasi real, query dari database
            // $totalInvestments = $this->db->query("SELECT COUNT(*) as count FROM {$this->investmentsTable}")->fetch()['count'];
            // $pendingPayments = $this->db->query("SELECT COUNT(*) as count FROM {$this->investmentsTable} WHERE status = 'pending'")->fetch()['count'];
            // $activeProjects = $this->db->query("SELECT COUNT(*) as count FROM {$this->projectsTable} WHERE status = 'active'")->fetch()['count'];
            // $totalInvestors = $this->db->query("SELECT COUNT(DISTINCT investor_email) as count FROM {$this->investmentsTable}")->fetch()['count'];
            
            $investments = $this->getSampleInvestments();
            $projects = $this->getSampleProjects();
            
            $stats = [
                'total_investments' => count($investments),
                'pending_payments' => count(array_filter($investments, fn($i) => $i['status'] === 'pending')),
                'active_projects' => count($projects),
                'total_investors' => count(array_unique(array_column($investments, 'investor_email')))
            ];
            
            echo json_encode([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * API: Verify payment
     * POST /admin/investasi/api/verify-payment
     */
    public function verifyPayment() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $investmentId = $data['investment_id'] ?? null;
            
            if (!$investmentId) {
                throw new Exception('Investment ID is required');
            }
            
            // Validasi investment exists
            // $investment = $this->db->query(
            //     "SELECT * FROM {$this->investmentsTable} WHERE id = ?", 
            //     [$investmentId]
            // )->fetch();
            
            // if (!$investment) {
            //     throw new Exception('Investment not found');
            // }
            
            // Update status menjadi verified
            // $this->db->query(
            //     "UPDATE {$this->investmentsTable} SET status = 'verified', verified_at = NOW(), verified_by = ? WHERE id = ?",
            //     [$_SESSION['admin_id'], $investmentId]
            // );
            
            // Update collected amount di project
            // $this->db->query(
            //     "UPDATE {$this->projectsTable} 
            //      SET collected = collected + ?, 
            //          progress = ROUND((collected / target) * 100)
            //      WHERE id = ?",
            //     [$investment['amount'], $investment['project_id']]
            // );
            
            // Log activity
            $this->logActivity('verify_payment', $investmentId, 'Payment verified');
            
            echo json_encode([
                'success' => true,
                'message' => 'Pembayaran berhasil diverifikasi'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * API: Reject payment
     * POST /admin/investasi/api/reject-payment
     */
    public function rejectPayment() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $investmentId = $data['investment_id'] ?? null;
            $reason = $data['reason'] ?? 'Tidak ada alasan';
            
            if (!$investmentId) {
                throw new Exception('Investment ID is required');
            }
            
            // Update status menjadi rejected
            // $this->db->query(
            //     "UPDATE {$this->investmentsTable} 
            //      SET status = 'rejected', 
            //          rejection_reason = ?,
            //          rejected_at = NOW(), 
            //          rejected_by = ? 
            //      WHERE id = ?",
            //     [$reason, $_SESSION['admin_id'], $investmentId]
            // );
            
            // Log activity
            $this->logActivity('reject_payment', $investmentId, 'Payment rejected: ' . $reason);
            
            echo json_encode([
                'success' => true,
                'message' => 'Pembayaran berhasil ditolak'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * API: Update investment status
     * POST /admin/investasi/api/update-status
     */
    public function updateInvestmentStatus() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $investmentId = $data['investment_id'] ?? null;
            $newStatus = $data['status'] ?? null;
            
            if (!$investmentId || !$newStatus) {
                throw new Exception('Investment ID and status are required');
            }
            
            // Validasi status
            $allowedStatuses = ['pending', 'verified', 'rejected'];
            if (!in_array($newStatus, $allowedStatuses)) {
                throw new Exception('Invalid status');
            }
            
            // Update status
            // $this->db->query(
            //     "UPDATE {$this->investmentsTable} 
            //      SET status = ?, updated_at = NOW() 
            //      WHERE id = ?",
            //     [$newStatus, $investmentId]
            // );
            
            // Log activity
            $this->logActivity('update_status', $investmentId, "Status changed to {$newStatus}");
            
            echo json_encode([
                'success' => true,
                'message' => 'Status investasi berhasil diubah'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * API: Delete investment
     * DELETE /admin/investasi/api/investment/{id}
     */
    public function deleteInvestment() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $investmentId = $data['investment_id'] ?? null;
            
            if (!$investmentId) {
                throw new Exception('Investment ID is required');
            }
            
            // Soft delete (recommended) atau hard delete
            // Soft delete:
            // $this->db->query(
            //     "UPDATE {$this->investmentsTable} SET deleted_at = NOW() WHERE id = ?",
            //     [$investmentId]
            // );
            
            // Hard delete:
            // $this->db->query(
            //     "DELETE FROM {$this->investmentsTable} WHERE id = ?",
            //     [$investmentId]
            // );
            
            // Log activity
            $this->logActivity('delete_investment', $investmentId, 'Investment deleted');
            
            echo json_encode([
                'success' => true,
                'message' => 'Investasi berhasil dihapus'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * API: Save project (add or update)
     * POST /admin/investasi/api/project
     */
    public function saveProject() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validasi data
            $required = ['title', 'description', 'location', 'category', 'interest', 'target', 'tenure'];
            foreach ($required as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    throw new Exception("Field {$field} is required");
                }
            }
            
            $projectId = $data['project_id'] ?? null;
            
            $projectData = [
                'title' => $data['title'],
                'description' => $data['description'],
                'location' => $data['location'],
                'category' => $data['category'],
                'interest' => floatval($data['interest']),
                'target' => floatval($data['target']),
                'tenure' => intval($data['tenure'])
            ];
            
            if ($projectId) {
                // Update existing project
                // $this->db->query(
                //     "UPDATE {$this->projectsTable} 
                //      SET title = ?, description = ?, location = ?, category = ?, 
                //          interest = ?, target = ?, tenure = ?, updated_at = NOW()
                //      WHERE id = ?",
                //     [
                //         $projectData['title'],
                //         $projectData['description'],
                //         $projectData['location'],
                //         $projectData['category'],
                //         $projectData['interest'],
                //         $projectData['target'],
                //         $projectData['tenure'],
                //         $projectId
                //     ]
                // );
                
                $message = 'Proyek berhasil diupdate';
                $this->logActivity('update_project', $projectId, 'Project updated');
                
            } else {
                // Add new project
                // $projectData['collected'] = 0;
                // $projectData['progress'] = 0;
                // $projectData['status'] = 'active';
                // $projectData['created_by'] = $_SESSION['admin_id'];
                
                // $this->db->query(
                //     "INSERT INTO {$this->projectsTable} 
                //      (title, description, location, category, interest, target, tenure, 
                //       collected, progress, status, created_by, created_at) 
                //      VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0, 'active', ?, NOW())",
                //     [
                //         $projectData['title'],
                //         $projectData['description'],
                //         $projectData['location'],
                //         $projectData['category'],
                //         $projectData['interest'],
                //         $projectData['target'],
                //         $projectData['tenure'],
                //         $_SESSION['admin_id']
                //     ]
                // );
                
                $message = 'Proyek berhasil ditambahkan';
                $this->logActivity('add_project', null, 'New project added: ' . $projectData['title']);
            }
            
            echo json_encode([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * API: Delete project
     * DELETE /admin/investasi/api/project/{id}
     */
    public function deleteProject() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $projectId = $data['project_id'] ?? null;
            
            if (!$projectId) {
                throw new Exception('Project ID is required');
            }
            
            // Cek apakah ada investasi terkait
            // $investmentCount = $this->db->query(
            //     "SELECT COUNT(*) as count FROM {$this->investmentsTable} WHERE project_id = ?",
            //     [$projectId]
            // )->fetch()['count'];
            
            // if ($investmentCount > 0) {
            //     throw new Exception('Cannot delete project with existing investments');
            // }
            
            // Soft delete
            // $this->db->query(
            //     "UPDATE {$this->projectsTable} SET status = 'deleted', deleted_at = NOW() WHERE id = ?",
            //     [$projectId]
            // );
            
            // Log activity
            $this->logActivity('delete_project', $projectId, 'Project deleted');
            
            echo json_encode([
                'success' => true,
                'message' => 'Proyek berhasil dihapus'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Log admin activity
     */
    private function logActivity($action, $targetId, $description) {
        // Log ke database atau file
        // $this->db->query(
        //     "INSERT INTO admin_activity_logs (admin_id, action, target_id, description, ip_address, created_at) 
        //      VALUES (?, ?, ?, ?, ?, NOW())",
        //     [$_SESSION['admin_id'], $action, $targetId, $description, $_SERVER['REMOTE_ADDR']]
        // );
        
        // Atau log ke file
        error_log(sprintf(
            "[%s] Admin %s - Action: %s, Target: %s, Description: %s",
            date('Y-m-d H:i:s'),
            $_SESSION['admin_email'] ?? 'unknown',
            $action,
            $targetId,
            $description
        ));
    }
    
    /**
     * Sample data untuk development
     */
    private function getSampleInvestments() {
        return [
            [
                'id' => 1,
                'investor_name' => 'Ahmad Santoso',
                'investor_email' => 'ahmad.santoso@email.com',
                'project_id' => 1,
                'project_title' => 'Budidaya Padi Organik di Karawang',
                'amount' => 1000000,
                'date' => '2024-01-15 10:30:00',
                'status' => 'pending'
            ],
            [
                'id' => 2,
                'investor_name' => 'Siti Rahayu',
                'investor_email' => 'siti.rahayu@email.com',
                'project_id' => 2,
                'project_title' => 'Perkebunan Kopi Arabika Temanggung',
                'amount' => 500000,
                'date' => '2024-01-14 14:20:00',
                'status' => 'pending'
            ],
            [
                'id' => 3,
                'investor_name' => 'Budi Pratama',
                'investor_email' => 'budi.pratama@email.com',
                'project_id' => 1,
                'project_title' => 'Budidaya Padi Organik di Karawang',
                'amount' => 2000000,
                'date' => '2024-01-13 09:15:00',
                'status' => 'verified'
            ],
            [
                'id' => 4,
                'investor_name' => 'Maya Sari',
                'investor_email' => 'maya.sari@email.com',
                'project_id' => 3,
                'project_title' => 'Budidaya Sayuran Hidroponik Bandung',
                'amount' => 1500000,
                'date' => '2024-01-12 16:45:00',
                'status' => 'rejected',
                'rejection_reason' => 'Bukti pembayaran tidak jelas'
            ]
        ];
    }
    
    /**
     * Sample data untuk development
     */
    private function getSampleProjects() {
        return [
            [
                'id' => 1,
                'title' => 'Budidaya Padi Organik di Karawang',
                'description' => 'Pengembangan budidaya padi organik dengan teknologi modern untuk meningkatkan hasil panen dan kualitas beras organik premium.',
                'location' => 'Karawang, Jawa Barat',
                'interest' => 15,
                'target' => 300000000,
                'collected' => 225000000,
                'progress' => 75,
                'tenure' => 8,
                'category' => 'Padi',
                'status' => 'active'
            ],
            [
                'id' => 2,
                'title' => 'Perkebunan Kopi Arabika Temanggung',
                'description' => 'Ekspansi perkebunan kopi arabika untuk meningkatkan produksi dan kualitas ekspor dengan sistem budidaya berkelanjutan.',
                'location' => 'Temanggung, Jawa Tengah',
                'interest' => 14,
                'target' => 300000000,
                'collected' => 189000000,
                'progress' => 63,
                'tenure' => 12,
                'category' => 'Kopi',
                'status' => 'active'
            ],
            [
                'id' => 3,
                'title' => 'Budidaya Sayuran Hidroponik Bandung',
                'description' => 'Pengembangan sistem hidroponik modern untuk produksi sayuran organik berkualitas tinggi dengan efisiensi air maksimal.',
                'location' => 'Bandung, Jawa Barat',
                'interest' => 13,
                'target' => 200000000,
                'collected' => 162000000,
                'progress' => 81,
                'tenure' => 10,
                'category' => 'Sayuran',
                'status' => 'active'
            ]
        ];
    }
}

// Router sederhana untuk API endpoints
if (isset($_GET['action'])) {
    $controller = new AdminInvestasi_Controller();
    $action = $_GET['action'];
    
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Action not found'
        ]);
    }
}
?>