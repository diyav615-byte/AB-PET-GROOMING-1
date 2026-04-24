-- Database Migration: Improved Schema
-- Run this script to update the database schema

USE ab_pet_grooming;

-- ============================================
-- CUSTOMERS TABLE - Add missing columns & index
-- ============================================
ALTER TABLE customers 
    ADD COLUMN IF NOT EXISTS status ENUM('active', 'inactive') DEFAULT 'active' AFTER city,
    ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 AFTER status,
    ADD COLUMN IF NOT EXISTS source VARCHAR(50) DEFAULT 'website' AFTER is_active,
    ADD INDEX IF NOT EXISTS idx_customers_status (is_active),
    ADD INDEX IF NOT EXISTS idx_customers_city (city);

-- ============================================
-- PETS TABLE - Add missing columns & index
-- ============================================
ALTER TABLE pets 
    ADD COLUMN IF NOT EXISTS type VARCHAR(20) AFTER customer_id AFTER customer_id,
    ADD COLUMN IF NOT EXISTS size VARCHAR(20) AFTER type AFTER type,
    ADD COLUMN IF NOT EXISTS special_notes TEXT AFTER vaccinated,
    ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 AFTER special_notes,
    ADD INDEX IF NOT EXISTS idx_pets_type (type),
    ADD INDEX IF NOT EXISTS idx_pets_customer (customer_id);

-- ============================================
-- SERVICES TABLE - Add more columns
-- ============================================
ALTER TABLE services 
    ADD COLUMN IF NOT EXISTS name VARCHAR(100) NOT NULL AFTER id,
    ADD COLUMN IF NOT EXISTS duration_minutes INT DEFAULT 60 AFTER name,
    ADD COLUMN IF NOT EXISTS price_min DECIMAL(10,2) AFTER duration_minutes,
    ADD COLUMN IF NOT EXISTS price_max DECIMAL(10,2) AFTER price_min,
    ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 AFTER category,
    ADD COLUMN IF NOT EXISTS display_order INT DEFAULT 0 AFTER is_active,
    MODIFY COLUMN price VARCHAR(50) AFTER display_order;

-- ============================================
-- BOOKINGS TABLE - Add more columns & indexes
-- ============================================
ALTER TABLE bookings 
    ADD COLUMN IF NOT EXISTS customer_name VARCHAR(100) AFTER customer_id,
    ADD COLUMN IF NOT EXISTS customer_phone VARCHAR(20) AFTER customer_name,
    ADD COLUMN IF NOT EXISTS pet_name VARCHAR(100) AFTER pet_id,
    ADD COLUMN IF NOT EXISTS pet_type VARCHAR(20) AFTER pet_name,
    ADD COLUMN IF NOT EXISTS service_name VARCHAR(100) AFTER service_id,
    ADD COLUMN IF NOT EXISTS addons JSON AFTER service_name,
    ADD COLUMN IF NOT EXISTS created_by INT AFTER notes,
    ADD COLUMN IF NOT EXISTS updated_by INT AFTER created_by,
    ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 AFTER updated_by,
    ADD COLUMN IF NOT EXISTS cancelled_reason TEXT AFTER is_active,
    ADD INDEX IF NOT EXISTS idx_bookings_date (booking_date),
    ADD INDEX IF NOT EXISTS idx_bookings_status (status),
    ADD INDEX IF NOT EXISTS idx_bookings_customer_phone (customer_phone);

-- ============================================
-- APPOINTMENTS TABLE - Improve structure
-- ============================================
-- Note: Separate from bookings, keep as appointment requests
ALTER TABLE appointments 
    ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1,
    ADD COLUMN IF NOT EXISTS status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    ADD INDEX IF NOT EXISTS idx_appointments_date (appointment_date);

-- ============================================
-- BOARDING TABLE - Add more columns
-- ============================================
ALTER TABLE boarding 
    ADD COLUMN IF NOT EXISTS customer_name VARCHAR(100),
    ADD COLUMN IF NOT EXISTS pet_name VARCHAR(100),
    ADD COLUMN IF NOT EXISTS pet_type VARCHAR(20),
    ADD COLUMN IF NOT EXISTS owner_phone VARCHAR(20),
    ADD COLUMN IF NOT EXISTS created_by INT,
    ADD COLUMN IF NOT EXISTS updated_by INT,
    ADD INDEX IF NOT EXISTS idx_boarding_status (status),
    ADD INDEX IF NOT EXISTS idx_boarding_dates (check_in_date, check_out_date);

-- ============================================
-- REVIEWS TABLE - Improve structure
-- ============================================
ALTER TABLE reviews 
    ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 AFTER status,
    ADD COLUMN IF NOT EXISTS admin_response TEXT AFTER is_active,
    ADD COLUMN IF NOT EXISTS responded_by INT AFTER admin_response,
    ADD INDEX IF NOT EXISTS idx_reviews_status (status);

-- ============================================
-- CONTACT_MESSAGES TABLE - Improve structure
-- ============================================
ALTER TABLE contact_messages 
    ADD COLUMN IF NOT EXISTS is_read TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1,
    ADD COLUMN IF NOT EXISTS responded_by INT,
    ADD COLUMN IF NOT EXISTS response_notes TEXT,
    ADD INDEX IF NOT EXISTS idx_contact_read (is_read);

-- ============================================
-- ADMIN_USERS - Add role column
-- ============================================
ALTER TABLE admin_users 
    ADD COLUMN IF NOT EXISTS role ENUM('super_admin', 'admin', 'staff') DEFAULT 'staff' AFTER email,
    ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 AFTER role,
    ADD COLUMN IF NOT EXISTS last_login DATETIME AFTER is_active;

-- ============================================
-- ACTIVITY LOGS TABLE - New table
-- ============================================
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT,
    old_data JSON,
    new_data JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_activity_admin (admin_user_id),
    INDEX idx_activity_entity (entity_type, entity_id),
    INDEX idx_activity_created (created_at)
);

-- ============================================
-- SETTINGS TABLE - New table for site settings
-- ============================================
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type VARCHAR(20) DEFAULT 'string',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_type) VALUES 
    ('site_name', 'AB Pet Grooming', 'string'),
    ('site_email', 'admin@petgrooming.com', 'string'),
    ('site_phone', '+918828719786', 'string'),
    ('daily_slot_limit', '10', 'number'),
    ('opening_hours', '10:30 AM - 7:00 PM', 'string'),
    ('working_days', 'Monday - Saturday', 'string')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- ============================================
-- BOOKING STATUS HISTORY TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS booking_status_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    old_status VARCHAR(20),
    new_status VARCHAR(20) NOT NULL,
    changed_by INT NOT NULL,
    reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_history_booking (booking_id),
    INDEX idx_history_created (created_at)
);

SELECT 'Database migration completed successfully!' AS result;