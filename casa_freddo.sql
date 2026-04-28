-- Casa Freddo Database Setup
-- Run this file to create the database, tables, and sample data

-- Create database
CREATE DATABASE IF NOT EXISTS casa_freddo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE casa_freddo;

-- Users table (for admin login)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert default admin user (password: admin123)
-- Password is hashed using PHP's password_hash function
INSERT IGNORE INTO users (id, username, password) VALUES 
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert default categories
INSERT INTO categories (name, description) VALUES
('Classic', 'Timeless flavors loved by all'),
('Premium', 'Luxurious and indulgent creations'),
('Seasonal', 'Limited edition flavors for every season');

-- Menu items table
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category_id INT NOT NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insert sample menu items
INSERT INTO menu_items (name, description, price, image, category_id, is_featured) VALUES
('Vanilla Bean Dream', 'Creamy Madagascar vanilla gelato with real vanilla bean specks', 180.00, 'uploads/vanilla.jpg', 1, TRUE),
('Stracciatella', 'Silky milk gelato with delicate chocolate shavings', 190.00, 'uploads/stracciatella.jpg', 1, TRUE),
('Pistachio Gold', 'Rich Sicilian pistachio gelato, nutty and smooth', 220.00, 'uploads/pistachio.jpg', 2, TRUE),
('Dark Chocolate Truffle', 'Intense Belgian chocolate with truffle pieces', 240.00, 'uploads/chocolate.jpg', 2, FALSE),
('Mango Tango', 'Alphonso mango sorbet bursting with tropical flavor', 170.00, 'uploads/mango.jpg', 1, FALSE),
('Salted Caramel Bliss', 'Buttery caramel with a hint of sea salt', 210.00, 'uploads/caramel.jpg', 2, TRUE),
('Lemon Basil', 'Zesty lemon with fresh basil infusion', 185.00, 'uploads/lemon.jpg', 3, FALSE),
('Rose Petal', 'Delicate rose-flavored gelato with edible petals', 230.00, 'uploads/rose.jpg', 3, TRUE),
('Affogato Deluxe', 'Vanilla gelato drowned in hot espresso shot', 250.00, 'uploads/affogato.jpg', 2, FALSE),
('Coconut Paradise', 'Creamy coconut gelato with toasted coconut flakes', 195.00, 'uploads/coconut.jpg', 1, FALSE);

-- Messages table (contact form submissions)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert a sample message
INSERT INTO messages (name, email, message) VALUES
('Rahul Sharma', 'rahul@example.com', 'I absolutely love your gelato! When will you open a branch in Delhi?');

