-- Database Creation
CREATE DATABASE IF NOT EXISTS destello_oro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE destello_oro;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Will store plain text for now to match current app, but hashing is recommended later
    role ENUM('admin', 'worker') NOT NULL,
    email VARCHAR(150),
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_token_expiry DATETIME DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    lastname VARCHAR(100),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table (Inventory)
CREATE TABLE IF NOT EXISTS products (
    reference VARCHAR(50) PRIMARY KEY, -- 'id' from JS (e.g., REF001)
    name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    purchase_price DECIMAL(15, 2) NOT NULL,
    wholesale_price DECIMAL(15, 2) NOT NULL,
    retail_price DECIMAL(15, 2) NOT NULL,
    supplier VARCHAR(150),
    added_by VARCHAR(50), -- username
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sales Header Table
CREATE TABLE IF NOT EXISTS sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(20) UNIQUE, -- Custom invoice ID if needed
    customer_name VARCHAR(150),
    customer_id VARCHAR(50),
    customer_phone VARCHAR(20),
    customer_email VARCHAR(150),
    customer_address TEXT,
    customer_city VARCHAR(100),
    total DECIMAL(15, 2) NOT NULL,
    discount DECIMAL(15, 2) DEFAULT 0,
    delivery_cost DECIMAL(15, 2) DEFAULT 0,
    payment_method VARCHAR(50),
    delivery_type VARCHAR(50),
    sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT, -- Link to users table
    username VARCHAR(50), -- Store username directly for history preservation
    status ENUM('completed', 'pending', 'cancelled') DEFAULT 'completed',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Sale Items (Products in a sale)
CREATE TABLE IF NOT EXISTS sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    product_ref VARCHAR(50),
    product_name VARCHAR(255), -- Store name in case product is deleted
    quantity INT NOT NULL,
    unit_price DECIMAL(15, 2) NOT NULL,
    subtotal DECIMAL(15, 2) NOT NULL,
    sale_type ENUM('retail', 'wholesale') DEFAULT 'retail',
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE
    -- No foreign key to products to allow keeping history of deleted products
);

-- Expenses Table
CREATE TABLE IF NOT EXISTS expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description TEXT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    expense_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    username VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Warranties Table
CREATE TABLE IF NOT EXISTS warranties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT, -- Optional link to local sale PK
    original_invoice_id VARCHAR(50), -- Text ID from user input
    customer_name VARCHAR(150),
    product_ref VARCHAR(50),
    product_name VARCHAR(255),
    reason VARCHAR(50),
    notes TEXT,
    
    -- Replacement info
    product_type ENUM('same', 'different') DEFAULT 'same',
    new_product_ref VARCHAR(50),
    new_product_name VARCHAR(255),
    additional_value DECIMAL(15, 2) DEFAULT 0,
    shipping_value DECIMAL(15, 2) DEFAULT 0,
    total_cost DECIMAL(15, 2) DEFAULT 0,
    
    status ENUM('pending', 'in_process', 'completed', 'cancelled') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT
);

-- Restock History
CREATE TABLE IF NOT EXISTS restocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_ref VARCHAR(50),
    product_name VARCHAR(255),
    quantity INT NOT NULL,
    restock_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    username VARCHAR(50)
);

-- Settings / Counters (for manual admin counters)
CREATE TABLE IF NOT EXISTS app_settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT
);

-- Insert Default Users (valores de ejemplo, sustituye en tu entorno real)
INSERT INTO users (username, password, role, email, name, lastname, phone) VALUES 
('admin', 'admin123', 'admin', 'admin@example.com', 'Administrador', 'Principal', '0000000000'),
('trabajador', 'trabajador123', 'worker', 'worker@example.com', 'Vendedor', 'Principal', '0000000000');

-- Insert Default Settings
INSERT INTO app_settings (setting_key, setting_value) VALUES 
('manual_sales_count', '0'),
('manual_warranty_count', '0');
