CREATE DATABASE IF NOT EXISTS `agjensioni-turistik`;
USE `agjensioni-turistik`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS packages;
DROP TABLE IF EXISTS destinations;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destination_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    duration_days INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (destination_id) REFERENCES destinations(id)
) ENGINE=InnoDB;

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destination_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    guests INT NOT NULL,
    arrivals DATE NOT NULL,
    leaving DATE NOT NULL,
    status ENUM('pending', 'approved', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (destination_id) REFERENCES destinations(id)
) ENGINE=InnoDB;

INSERT INTO destinations (name, country, price) VALUES
('India', 'India', 650.00),
('Switzerland', 'Switzerland', 1200.00),
('Latvia', 'Latvia', 500.00),
('France', 'France', 900.00),
('Japan', 'Japan', 1500.00),
('Australia', 'Australia', 1800.00);

INSERT INTO packages (destination_id, title, description, duration_days, price) VALUES
(1, 'India Adventure', 'Cultural trip with guided tours.', 7, 650.00),
(2, 'Swiss Alps Tour', 'Mountain and lake experience.', 5, 1200.00),
(3, 'Latvia City Break', 'Short city trip package.', 3, 500.00),
(4, 'Paris Experience', 'France package with city tours.', 4, 900.00),
(5, 'Japan Discovery', 'Tokyo and Kyoto travel package.', 8, 1500.00),
(6, 'Australia Escape', 'Sydney and coastal travel package.', 10, 1800.00);

