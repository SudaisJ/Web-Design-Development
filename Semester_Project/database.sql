-- Create the database
CREATE DATABASE IF NOT EXISTS library_portal;
USE library_portal;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create books table
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(150) NOT NULL,
    category VARCHAR(100) DEFAULT 'General',
    isbn VARCHAR(50) NOT NULL UNIQUE,
    cover_image VARCHAR(255) DEFAULT 'default_cover.png',
    published_year INT NOT NULL,
    quantity INT DEFAULT 1,
    status ENUM('Available', 'Borrowed') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a default admin user
-- Password is 'admin123'
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@library.com', '$2y$12$50XNGV2R/mX3fdp9souQUOICCgsh4oU0SK40jH5PQ/TnF3sNEl0au', 'admin')
ON DUPLICATE KEY UPDATE username='admin';
