CREATE TABLE Categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(200) UNIQUE,
    phone_number VARCHAR(20),
    profile_image VARCHAR(500),
    user_type ENUM('buyer', 'seller', 'admin') DEFAULT 'buyer',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE Products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    category_id INT NOT NULL,
    seller_id INT NOT NULL,
    image_path VARCHAR(500),
    contact_phone VARCHAR(20) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (category_id) REFERENCES Categories(category_id),
    FOREIGN KEY (seller_id) REFERENCES Users(user_id),
    INDEX idx_category (category_id),
    INDEX idx_seller (seller_id),
    INDEX idx_active (is_active)
);

CREATE TABLE Transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    buyer_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (product_id) REFERENCES Products(product_id),
    FOREIGN KEY (buyer_id) REFERENCES Users(user_id)
); 

CREATE TABLE ProductViews (
    view_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    user_id INT,
    ip_address VARCHAR(45),
    view_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (product_id) REFERENCES Products(product_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

DELIMITER $$

CREATE PROCEDURE sp_GetProducts(
    IN p_search_term VARCHAR(200),
    IN p_category_id INT,
    IN p_min_price DECIMAL(15,2),
    IN p_max_price DECIMAL(15,2),
    IN p_limit INT,
    IN p_offset INT
)
BEGIN
    SELECT 
        p.product_id,
        p.product_name,
        p.description,
        p.price,
        p.stock_quantity,
        p.image_path,
        p.contact_phone,
        p.created_at,
        c.category_id,
        c.category_name,
        u.user_id as seller_id,
        u.full_name as seller_name,
        u.phone_number as seller_phone
    FROM Products p
    JOIN Categories c ON p.category_id = c.category_id
    JOIN Users u ON p.seller_id = u.user_id
    WHERE p.is_active = TRUE
        AND (p_search_term IS NULL OR p.product_name LIKE CONCAT('%', p_search_term, '%') 
             OR p.description LIKE CONCAT('%', p_search_term, '%'))
        AND (p_category_id IS NULL OR p.category_id = p_category_id)
        AND (p_min_price IS NULL OR p.price >= p_min_price)
        AND (p_max_price IS NULL OR p.price <= p_max_price)
    ORDER BY p.created_at DESC
    LIMIT p_limit OFFSET p_offset;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_UpdateProduct(
    IN p_product_id INT,
    IN p_product_name VARCHAR(200),
    IN p_description TEXT,
    IN p_price DECIMAL(15,2),
    IN p_stock_quantity INT,
    IN p_category_id INT,
    IN p_image_path VARCHAR(500),
    IN p_contact_phone VARCHAR(20)
)
BEGIN
    UPDATE Products
    SET 
        product_name = p_product_name,
        description = p_description,
        price = p_price,
        stock_quantity = p_stock_quantity,
        category_id = p_category_id,
        image_path = p_image_path,
        contact_phone = p_contact_phone,
        updated_at = CURRENT_TIMESTAMP
    WHERE product_id = p_product_id;
    
    SELECT ROW_COUNT() as rows_affected;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_GetUserProducts(
    IN p_user_id INT
)
BEGIN
    SELECT 
        p.product_id,
        p.product_name,
        p.description,
        p.price,
        p.stock_quantity,
        p.image_path,
        p.contact_phone,
        p.created_at,
        p.is_active,
        c.category_id,
        c.category_name
    FROM Products p
    JOIN Categories c ON p.category_id = c.category_id
    WHERE p.seller_id = p_user_id
    ORDER BY p.created_at DESC;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_DeleteProduct(
    IN p_product_id INT,
    IN p_user_id INT
)
BEGIN
    DECLARE v_is_owner BOOLEAN;
    
    -- Check if user is the owner
    SELECT COUNT(*) > 0 INTO v_is_owner
    FROM Products 
    WHERE product_id = p_product_id AND seller_id = p_user_id;
    
    IF v_is_owner THEN
        -- Soft delete (set inactive)
        UPDATE Products 
        SET is_active = FALSE 
        WHERE product_id = p_product_id;
        
        SELECT 1 as success, 'Product deleted successfully' as message;
    ELSE
        SELECT 0 as success, 'You are not authorized to delete this product' as message;
    END IF;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_GetProductDetails(
    IN p_product_id INT,
    IN p_user_ip VARCHAR(45)
)
BEGIN
    -- Increment view count
    UPDATE Products 
    SET views_count = views_count + 1 
    WHERE product_id = p_product_id;
    
    -- Record view
    INSERT INTO ProductViews (product_id, ip_address) 
    VALUES (p_product_id, p_user_ip);
    
    -- Get product details
    SELECT 
        p.product_id,
        p.product_name,
        p.description,
        p.price,
        p.stock_quantity,
        p.image_path,
        p.contact_phone,
        p.views_count,
        p.created_at,
        c.category_id,
        c.category_name,
        u.user_id as seller_id,
        u.full_name as seller_name,
        u.profile_image as seller_image
    FROM Products p
    JOIN Categories c ON p.category_id = c.category_id
    JOIN Users u ON p.seller_id = u.user_id
    WHERE p.product_id = p_product_id 
        AND p.is_active = TRUE;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_GetMarketplaceStats()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM Products WHERE is_active = TRUE) as total_products,
        (SELECT COUNT(DISTINCT seller_id) FROM Products WHERE is_active = TRUE) as total_sellers,
        (SELECT COUNT(*) FROM Users WHERE user_type = 'seller') as total_registered_sellers,
        (SELECT AVG(price) FROM Products WHERE is_active = TRUE) as average_price,
        (SELECT COUNT(*) FROM ProductViews WHERE view_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as weekly_views;
END$$

DELIMITER ;

-- Insert default categories
INSERT INTO Categories (category_id, category_name, description) VALUES
(1, 'Bibit Tanaman', 'Berbagai jenis bibit tanaman pertanian'),
(2, 'Pupuk Organik', 'Pupuk alami untuk kesuburan tanah'),
(3, 'Alat Pertanian', 'Peralatan dan mesin pertanian'),
(4, 'Hasil Panen', 'Produk hasil panen pertanian'),
(5, 'Obat Tanaman', 'Pestisida dan obat tanaman');

-- Insert default user (guest/admin)
INSERT INTO Users (user_id, username, full_name, user_type) VALUES
(1, 'admin', 'Administrator', 'admin'),
(2, 'guest', 'Guest User', 'buyer');

CREATE TABLE IF NOT EXISTS articles (
    article_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT NOT NULL,
    category ENUM(
        'Teknologi Pertanian',
        'Pertanian Berkelanjutan',
        'Pertanian Organik',
        'Inovasi Pertanian',
        'Penelitian'
    ) NOT NULL,
    image_path VARCHAR(255) NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'published',
    view_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_articles_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO articles (
    user_id, title, content, excerpt, category, image_path
) VALUES (
    1,
    'Pertanian Otonom 2025: Traktor Pintar dan Drone Jadi Petani Baru Dunia',
    'Pertanian dunia kini tidak lagi bergantung penuh pada tenaga manusia...',
    'Pertanian dunia kini tidak lagi bergantung penuh pada tenaga manusia.',
    'Teknologi Pertanian',
    'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800'
);


DELIMITER $$

CREATE PROCEDURE sp_create_article(
    IN p_user_id INT,
    IN p_title VARCHAR(255),
    IN p_content LONGTEXT,
    IN p_excerpt TEXT,
    IN p_category ENUM(
        'Teknologi Pertanian',
        'Pertanian Berkelanjutan',
        'Pertanian Organik',
        'Inovasi Pertanian',
        'Penelitian'
    ),
    IN p_image_path VARCHAR(255)
)
BEGIN
    INSERT INTO articles (
        user_id,
        title,
        content,
        excerpt,
        category,
        image_path,
        status
    ) VALUES (
        p_user_id,
        p_title,
        p_content,
        p_excerpt,
        p_category,
        p_image_path,
        'published'
    );

    SELECT LAST_INSERT_ID() AS article_id;
END $$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE sp_update_article(
    IN p_article_id INT,
    IN p_title VARCHAR(255),
    IN p_content LONGTEXT,
    IN p_excerpt TEXT,
    IN p_category ENUM(
        'Teknologi Pertanian',
        'Pertanian Berkelanjutan',
        'Pertanian Organik',
        'Inovasi Pertanian',
        'Penelitian'
    ),
    IN p_image_path VARCHAR(255),
    IN p_status ENUM('draft','published','archived')
)
BEGIN
    UPDATE articles
    SET
        title = p_title,
        content = p_content,
        excerpt = p_excerpt,
        category = p_category,
        image_path = p_image_path,
        status = p_status
    WHERE article_id = p_article_id;
END $$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE sp_delete_article(
    IN p_article_id INT
)
BEGIN
    DELETE FROM articles WHERE article_id = p_article_id;
END $$

DELIMITER ;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS projects;
CREATE TABLE projects (
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    interest DECIMAL(5,2) NOT NULL COMMENT 'Bunga per tahun (%)',
    target DECIMAL(15,2) NOT NULL COMMENT 'Target dana',
    collected DECIMAL(15,2) DEFAULT 0 COMMENT 'Dana terkumpul',
    progress DECIMAL(5,2) DEFAULT 0 COMMENT 'Progress (%)',
    tenure INT(11) NOT NULL COMMENT 'Tenor dalam bulan',
    status VARCHAR(50) DEFAULT 'Penggalangan Dana' COMMENT 'Penggalangan Dana, Sedang Berjalan, Selesai',
    image_url VARCHAR(255) DEFAULT NULL,
    start_date DATE DEFAULT NULL,
    end_date DATE DEFAULT NULL,
    created_by INT(11) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY idx_status (status),
    KEY idx_category (category),
    KEY idx_created_by (created_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO projects (title, description, location, category, interest, target, collected, progress, tenure, status, start_date, end_date) VALUES
('Budidaya Padi Organik di Karawang', 'Pengembangan budidaya padi organik dengan teknologi modern untuk meningkatkan hasil panen dan kualitas beras organik premium.', 'Karawang, Jawa Barat', 'Padi', 15.00, 300000000.00, 225000000.00, 75.00, 8, 'Penggalangan Dana', '2024-01-15', '2024-09-15'),
('Perkebunan Kopi Arabika Temanggung', 'Ekspansi perkebunan kopi arabika untuk meningkatkan produksi dan kualitas ekspor dengan sistem budidaya berkelanjutan.', 'Temanggung, Jawa Tengah', 'Kopi', 14.00, 300000000.00, 189000000.00, 63.00, 12, 'Penggalangan Dana', '2024-02-01', '2025-02-01'),
('Budidaya Sayuran Hidroponik Bandung', 'Pengembangan sistem hidroponik modern untuk produksi sayuran organik berkualitas tinggi dengan efisiensi air maksimal.', 'Bandung, Jawa Barat', 'Sayuran', 13.00, 200000000.00, 162000000.00, 81.00, 10, 'Penggalangan Dana', '2024-01-20', '2024-11-20'),
('Peternakan Ayam Kampung Organik', 'Pengembangan peternakan ayam kampung dengan sistem organik dan pakan alami untuk menghasilkan daging berkualitas premium.', 'Bogor, Jawa Barat', 'Peternakan', 12.00, 300000000.00, 84000000.00, 28.00, 10, 'Penggalangan Dana', '2024-02-10', '2024-12-10'),
('Budidaya Ikan Nila Sistem Bioflok', 'Pengembangan budidaya ikan nila dengan sistem bioflok inovatif untuk efisiensi pakan dan air yang optimal.', 'Sukabumi, Jawa Barat', 'Perikanan', 14.00, 300000000.00, 189000000.00, 63.00, 7, 'Sedang Berjalan', '2023-12-01', '2024-07-01'),
('Perkebunan Jeruk Pamelo Batu', 'Ekspansi perkebunan jeruk pamelo dengan sistem irigasi tetes modern untuk efisiensi air dan hasil maksimal.', 'Batu, Jawa Timur', 'Buah-buahan', 16.00, 300000000.00, 243000000.00, 81.00, 18, 'Sedang Berjalan', '2023-10-01', '2025-04-01'),
('Kebun Stroberi Organik Lembang', 'Pengembangan kebun stroberi organik dengan teknologi greenhouse untuk hasil panen yang konsisten sepanjang tahun.', 'Lembang, Jawa Barat', 'Buah-buahan', 17.00, 250000000.00, 250000000.00, 100.00, 9, 'Selesai', '2023-08-01', '2024-05-01'),
('Tambak Udang Vaname Modern', 'Pengembangan tambak udang vaname dengan sistem sirkulasi air tertutup untuk mengurangi risiko penyakit.', 'Situbondo, Jawa Timur', 'Perikanan', 15.00, 400000000.00, 320000000.00, 80.00, 11, 'Sedang Berjalan', '2023-11-01', '2024-10-01');


DROP TABLE IF EXISTS investments;
CREATE TABLE investments (
    id INT(11) NOT NULL AUTO_INCREMENT,
    project_id INT(11) NOT NULL,
    user_id INT(11) DEFAULT NULL,
    investor_name VARCHAR(255) NOT NULL COMMENT 'Full Name dari form',
    investor_email VARCHAR(255) NOT NULL,
    investor_phone VARCHAR(20) NOT NULL COMMENT 'Phone dari form',
    investor_address TEXT COMMENT 'Address dari form',
    id_number VARCHAR(50) COMMENT 'NIK/KTP - idNumber dari form',
    birth_date DATE COMMENT 'birthDate dari form',
    investment_amount DECIMAL(15,2) NOT NULL COMMENT 'Jumlah investasi',
    payment_method VARCHAR(50) DEFAULT 'QRIS',
    payment_proof VARCHAR(255) DEFAULT NULL,
    status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    rejection_reason TEXT DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    verified_at TIMESTAMP NULL DEFAULT NULL,
    verified_by INT(11) DEFAULT NULL,
    rejected_at TIMESTAMP NULL DEFAULT NULL,
    rejected_by INT(11) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_project_id (project_id),
    KEY idx_user_id (user_id),
    KEY idx_status (status),
    KEY idx_investor_email (investor_email),
    CONSTRAINT fk_investments_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    CONSTRAINT fk_investments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO investments (project_id, investor_name, investor_email, investor_phone, investor_address, id_number, birth_date, investment_amount, payment_method, status, created_at) VALUES
(1, 'Ahmad Santoso', 'ahmad.santoso@email.com', '081234567891', 'Jl. Merdeka No. 10, Jakarta', '3201012345670001', '1990-05-15', 1000000.00, 'QRIS', 'pending', '2024-01-15 10:30:00'),
(2, 'Siti Rahayu', 'siti.rahayu@email.com', '082345678902', 'Jl. Gatot Subroto No. 25, Bandung', '3273024567890002', '1988-08-20', 500000.00, 'QRIS', 'pending', '2024-01-14 14:20:00'),
(1, 'Budi Pratama', 'budi.pratama@email.com', '083456789013', 'Jl. Sudirman No. 15, Surabaya', '3578035678900003', '1992-03-10', 2000000.00, 'QRIS', 'verified', '2024-01-13 09:15:00'),
(3, 'Maya Sari', 'maya.sari@email.com', '084567890124', 'Jl. Ahmad Yani No. 30, Semarang', '3374046789010004', '1995-11-25', 1500000.00, 'QRIS', 'rejected', '2024-01-12 16:45:00'),
(5, 'Rudi Hermawan', 'rudi.hermawan@email.com', '085678901235', 'Jl. Diponegoro No. 5, Yogyakarta', '3471057890120005', '1987-07-08', 800000.00, 'QRIS', 'verified', '2024-01-11 11:20:00'),
(2, 'Dewi Lestari', 'dewi.lestari@email.com', '086789012346', 'Jl. Pahlawan No. 12, Malang', '3507068901230006', '1993-02-14', 1200000.00, 'QRIS', 'verified', '2024-01-10 13:30:00'),
(4, 'Agus Setiawan', 'agus.setiawan@email.com', '087890123457', 'Jl. Veteran No. 20, Bogor', '3201079012340007', '1991-09-05', 700000.00, 'QRIS', 'pending', '2024-01-09 10:00:00'),
(3, 'Rina Wati', 'rina.wati@email.com', '088901234568', 'Jl. Kartini No. 8, Tangerang', '3603080123450008', '1994-12-18', 900000.00, 'QRIS', 'verified', '2024-01-08 15:15:00');


DROP TABLE IF EXISTS investment_returns;
CREATE TABLE investment_returns (
    id INT(11) NOT NULL AUTO_INCREMENT,
    investment_id INT(11) NOT NULL,
    return_date DATE NOT NULL,
    return_amount DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    payment_proof VARCHAR(255) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    paid_at TIMESTAMP NULL DEFAULT NULL,
    paid_by INT(11) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_investment_id (investment_id),
    KEY idx_status (status),
    KEY idx_return_date (return_date),
    CONSTRAINT fk_returns_investment FOREIGN KEY (investment_id) REFERENCES investments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS admin_activity_logs;
CREATE TABLE admin_activity_logs (
    id INT(11) NOT NULL AUTO_INCREMENT,
    admin_id INT(11) NOT NULL,
    action VARCHAR(100) NOT NULL,
    target_type VARCHAR(50) COMMENT 'investment, project, etc',
    target_id INT(11) DEFAULT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_admin_id (admin_id),
    KEY idx_action (action),
    KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS project_documents;
CREATE TABLE project_documents (
    id INT(11) NOT NULL AUTO_INCREMENT,
    project_id INT(11) NOT NULL,
    document_type VARCHAR(50) NOT NULL COMMENT 'proposal, feasibility, contract, etc',
    document_name VARCHAR(255) NOT NULL,
    document_path VARCHAR(255) NOT NULL,
    file_size INT(11) COMMENT 'dalam bytes',
    uploaded_by INT(11) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_project_id (project_id),
    CONSTRAINT fk_documents_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS project_updates;
CREATE TABLE project_updates (
    id INT(11) NOT NULL AUTO_INCREMENT,
    project_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    posted_by INT(11) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_project_id (project_id),
    CONSTRAINT fk_updates_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;


SELECT '=== DATABASE SETUP COMPLETED ===' AS Status;

SELECT 'Projects Table:' AS Info;
SELECT COUNT(*) as total_projects FROM projects;

SELECT 'Investments Table:' AS Info;
SELECT COUNT(*) as total_investments FROM investments;

SELECT 'Testing Statistics:' AS Info;
CALL sp_get_investment_stats();
CALL sp_get_project_stats();

SELECT 'Testing Return Calculation:' AS Info;
CALL sp_calculate_returns(1000000, 15, 8);

