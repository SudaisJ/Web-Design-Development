-- Create the database
CREATE DATABASE IF NOT EXISTS library_portal;
USE library_portal;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'default_avatar.png',
    role ENUM('admin', 'student', 'faculty', 'staff') DEFAULT 'student',
    is_approved TINYINT(1) DEFAULT 0,
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
INSERT INTO users (username, email, password, role, is_approved) 
VALUES ('admin', 'admin@library.com', '$2y$12$50XNGV2R/mX3fdp9souQUOICCgsh4oU0SK40jH5PQ/TnF3sNEl0au', 'admin', 1)
ON DUPLICATE KEY UPDATE username='admin';

-- Create borrowings table
CREATE TABLE IF NOT EXISTS borrowings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_date DATE,
    return_date DATE NULL,
    fine_amount DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('borrowed', 'returned') DEFAULT 'borrowed',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- Create waitlist table
CREATE TABLE IF NOT EXISTS waitlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);
