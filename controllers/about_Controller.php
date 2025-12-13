<?php

require_once '../config.php';

class AboutController {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    /**
     * Get team members data
     */
    public function getTeam() {
        try {
            // In a real application, this would query the database
            // For now, we'll return mock data
            $teamData = [
                [
                    'id' => 1,
                    'name' => 'Ahmad Wijaya',
                    'role' => 'CEO & Founder',
                    'bio' => 'Ahli pertanian dengan 15 tahun pengalaman dalam transformasi digital sektor agrikultur.',
                    'image' => 'folderimage/team1.jpg',
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'ahmad@nisevaagro.com',
                    'order_index' => 1
                ],
                [
                    'id' => 2,
                    'name' => 'Sari Dewi',
                    'role' => 'CTO',
                    'bio' => 'Spesialis IoT dan AI dengan passion dalam mengembangkan solusi teknologi untuk pertanian.',
                    'image' => 'folderimage/team2.jpg',
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'sari@nisevaagro.com',
                    'order_index' => 2
                ],
                [
                    'id' => 3,
                    'name' => 'Budi Santoso',
                    'role' => 'Head of Agriculture',
                    'bio' => 'Pakar agronomi dengan fokus pada implementasi praktik pertanian berkelanjutan.',
                    'image' => 'folderimage/team3.jpg',
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'budi@nisevaagro.com',
                    'order_index' => 3
                ]
            ];
            
            return [
                'success' => true,
                'team' => $teamData
            ];
            
        } catch (Exception $e) {
            error_log("Get team error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengambil data tim'
            ];
        }
    }
    
    /**
     * Get company statistics
     */
    public function getStats() {
        try {
            // In a real application, this would query the database
            $statsData = [
                [
                    'id' => 1,
                    'number' => '10000',
                    'label' => 'Petani Terdaftar',
                    'icon' => 'fas fa-users'
                ],
                [
                    'id' => 2,
                    'number' => '50',
                    'label' => 'Kota Terjangkau',
                    'icon' => 'fas fa-map-marker-alt'
                ],
                [
                    'id' => 3,
                    'number' => '15',
                    'label' => 'Provinsi',
                    'icon' => 'fas fa-globe-asia'
                ],
                [
                    'id' => 4,
                    'number' => '98',
                    'label' => 'Kepuasan Petani',
                    'icon' => 'fas fa-heart'
                ]
            ];
            
            return [
                'success' => true,
                'stats' => $statsData
            ];
            
        } catch (Exception $e) {
            error_log("Get stats error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengambil data statistik'
            ];
        }
    }
    
    /**
     * Handle contact form submission
     */
    public function handleContact($data) {
        try {
            // Validate required fields
            $required = ['name', 'email', 'subject', 'message'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    return [
                        'success' => false,
                        'message' => 'Semua field harus diisi'
                    ];
                }
            }
            
            // Validate email
            if (!isValidEmail($data['email'])) {
                return [
                    'success' => false,
                    'message' => 'Format email tidak valid'
                ];
            }
            
            // Sanitize data
            $name = sanitizeInput($data['name']);
            $email = sanitizeInput($data['email']);
            $subject = sanitizeInput($data['subject']);
            $message = sanitizeInput($data['message']);
            
            // In a real application, you would:
            // 1. Save to database
            // 2. Send email notification
            // 3. Maybe send auto-reply
            
            // Simulate processing delay
            sleep(1);
            
            return [
                'success' => true,
                'message' => 'Pesan Anda telah berhasil dikirim. Kami akan menghubungi Anda segera.'
            ];
            
        } catch (Exception $e) {
            error_log("Contact form error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim pesan'
            ];
        }
    }
    
    /**
     * Get company information
     */
    public function getCompanyInfo() {
        try {
            $companyInfo = [
                'name' => 'Niseva Agro',
                'description' => 'Memimpin transformasi digital pertanian Indonesia melalui inovasi teknologi dan komitmen terhadap keberlanjutan.',
                'mission' => 'Menjadi pelopor dalam revolusi pertanian digital Indonesia dengan menyediakan solusi teknologi yang terjangkau, berkelanjutan, dan mudah diadopsi oleh petani dari berbagai kalangan.',
                'vision' => 'Menjadikan Indonesia sebagai pusat inovasi pertanian modern terdepan di Asia Tenggara pada tahun 2030, dengan sistem pertanian yang cerdas, efisien, dan ramah lingkungan.',
                'address' => 'Jl. Pertanian No. 123, Jakarta',
                'phone' => '+62 21 1234 5678',
                'email' => 'info@nisevaagro.com',
                'founded' => '2015'
            ];
            
            return [
                'success' => true,
                'company' => $companyInfo
            ];
            
        } catch (Exception $e) {
            error_log("Get company info error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengambil informasi perusahaan'
            ];
        }
    }
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $controller = new AboutController();
    
    switch ($action) {
        case 'getTeam':
            $result = $controller->getTeam();
            sendResponse($result['success'], $result['message'] ?? '', $result['team'] ?? []);
            break;
            
        case 'getStats':
            $result = $controller->getStats();
            sendResponse($result['success'], $result['message'] ?? '', $result['stats'] ?? []);
            break;
            
        case 'getCompanyInfo':
            $result = $controller->getCompanyInfo();
            sendResponse($result['success'], $result['message'] ?? '', $result['company'] ?? []);
            break;
            
        default:
            sendResponse(false, 'Aksi tidak valid');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $controller = new AboutController();
    
    switch ($action) {
        case 'contact':
            $result = $controller->handleContact($_POST);
            sendResponse($result['success'], $result['message']);
            break;
            
        default:
            sendResponse(false, 'Aksi tidak valid');
    }
}

?>