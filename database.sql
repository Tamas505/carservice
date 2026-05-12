CREATE DATABASE IF NOT EXISTS carservice
CHARACTER SET utf8mb4
COLLATE utf8mb4_hungarian_ci;

USE carservice;

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(255) NOT NULL,
    appointment_date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (appointment_date)
);