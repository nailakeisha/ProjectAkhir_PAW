<?php
class Investasi_Controller {
    private $db;
    
    public function __construct() {
        // Inisialisasi koneksi database (sesuaikan dengan konfigurasi Anda)
        $this->db = new PDO('mysql:host=localhost;dbname=niseva_agro', 'username', 'password');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    // Mendapatkan semua proyek investasi
    public function getProjects() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM projects WHERE status != 'Selesai' ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error
            error_log("Error getting projects: " . $e->getMessage());
            return [];
        }
    }
    
    // Mendapatkan proyek berdasarkan ID
    public function getProjectById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting project by id: " . $e->getMessage());
            return null;
        }
    }
    
    // Filter proyek berdasarkan kriteria
    public function filterProjects($category = null, $status = null, $search = null) {
        try {
            $sql = "SELECT * FROM projects WHERE 1=1";
            $params = [];
            
            if ($category) {
                $sql .= " AND category = :category";
                $params[':category'] = $category;
            }
            
            if ($status) {
                $sql .= " AND status = :status";
                $params[':status'] = $status;
            }
            
            if ($search) {
                $sql .= " AND (title LIKE :search OR description LIKE :search OR location LIKE :search)";
                $params[':search'] = "%$search%";
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error filtering projects: " . $e->getMessage());
            return [];
        }
    }
    
    // Menyimpan data investasi
    public function saveInvestment($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO investments 
                (project_id, full_name, email, phone, address, id_number, birth_date, investment_amount, status, created_at) 
                VALUES 
                (:project_id, :full_name, :email, :phone, :address, :id_number, :birth_date, :investment_amount, 'pending', NOW())
            ");
            
            $stmt->bindParam(':project_id', $data['project_id']);
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':id_number', $data['id_number']);
            $stmt->bindParam(':birth_date', $data['birth_date']);
            $stmt->bindParam(':investment_amount', $data['investment_amount']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving investment: " . $e->getMessage());
            return false;
        }
    }
    
    // Update status proyek setelah investasi
    public function updateProjectCollectedAmount($project_id, $amount) {
        try {
            $stmt = $this->db->prepare("
                UPDATE projects 
                SET collected = collected + :amount,
                    progress = ROUND(((collected + :amount) * 100 / target), 2)
                WHERE id = :project_id
            ");
            
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':project_id', $project_id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating project collected amount: " . $e->getMessage());
            return false;
        }
    }
    
    // Mendapatkan statistik
    public function getStats() {
        try {
            $stats = [];
            
            // Total proyek aktif
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM projects WHERE status != 'Selesai'");
            $stmt->execute();
            $stats['active_projects'] = $stmt->fetchColumn();
            
            // Total dana tersalurkan
            $stmt = $this->db->prepare("SELECT SUM(collected) as total FROM projects");
            $stmt->execute();
            $stats['total_collected'] = $stmt->fetchColumn() ?? 0;
            
            // Rata-rata bunga
            $stmt = $this->db->prepare("SELECT AVG(interest) as avg_interest FROM projects");
            $stmt->execute();
            $stats['avg_interest'] = round($stmt->fetchColumn() ?? 0, 2);
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting stats: " . $e->getMessage());
            return [
                'active_projects' => 0,
                'total_collected' => 0,
                'avg_interest' => 0
            ];
        }
    }
    
    // Verifikasi pembayaran
    public function verifyPayment($investment_id, $payment_proof = null) {
        try {
            $stmt = $this->db->prepare("
                UPDATE investments 
                SET status = 'verified',
                    payment_proof = :payment_proof,
                    verified_at = NOW()
                WHERE id = :investment_id
            ");
            
            $stmt->bindParam(':investment_id', $investment_id);
            $stmt->bindParam(':payment_proof', $payment_proof);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error verifying payment: " . $e->getMessage());
            return false;
        }
    }
    
    // Mendapatkan riwayat investasi user
    public function getUserInvestments($email) {
        try {
            $stmt = $this->db->prepare("
                SELECT i.*, p.title as project_title, p.interest, p.tenure 
                FROM investments i
                JOIN projects p ON i.project_id = p.id
                WHERE i.email = :email
                ORDER BY i.created_at DESC
            ");
            
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user investments: " . $e->getMessage());
            return [];
        }
    }
}
?>