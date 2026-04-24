-- Create Database
CREATE DATABASE IF NOT EXISTS pet_grooming_admin;
USE pet_grooming_admin;

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Customers Table
CREATE TABLE IF NOT EXISTS customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Pets Table
CREATE TABLE IF NOT EXISTS pets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    breed VARCHAR(100),
    age INT,
    weight DECIMAL(5,2),
    color VARCHAR(100),
    medical_notes TEXT,
    allergies TEXT,
    vaccinated BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration INT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50),
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    pet_id INT NOT NULL,
    service_id INT,
    booking_date DATE NOT NULL,
    start_time TIME,
    end_time TIME,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    total_price DECIMAL(10,2),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id)
);

-- Boarding Table
CREATE TABLE IF NOT EXISTS boarding (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    pet_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_in_time TIME,
    check_out_date DATE,
    check_out_time TIME,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    total_price DECIMAL(10,2),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE
);

-- Gallery Table
CREATE TABLE IF NOT EXISTS gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100),
    image_url TEXT NOT NULL,
    image_key VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Reviews Table
CREATE TABLE IF NOT EXISTS reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    booking_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$YourHashedPasswordHere', 'admin@petgrooming.com');

-- Insert sample data
INSERT INTO customers (name, email, phone, city) VALUES 
('John Doe', 'john@example.com', '555-1234', 'New York'),
('Jane Smith', 'jane@example.com', '555-5678', 'Los Angeles'),
('Mike Johnson', 'mike@example.com', '555-9012', 'Chicago');

INSERT INTO services (name, description, duration, price, category) VALUES 
('Full Grooming', 'Complete grooming service including bath, trim, and styling', 60, 49.99, 'Grooming'),
('Bath Only', 'Professional bath and dry service', 30, 25.00, 'Grooming'),
('Nail Trim', 'Professional nail trimming and care', 15, 15.00, 'Grooming'),
('Boarding', 'Pet boarding service with daily care', 1440, 35.00, 'Boarding');
