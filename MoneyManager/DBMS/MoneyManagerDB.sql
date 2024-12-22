CREATE DATABASE IF NOT EXISTS money_management;
USE money_management;

-- Create transactions table
CREATE TABLE transactions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    category VARCHAR(255) NOT NULL,
    note TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create users table
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    UNIQUE (username),
    UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
