<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS restaurant_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error;
}

// Select the database
$conn->select_db("restaurant_db");

// Drop tables if they exist
$conn->query("DROP TABLE IF EXISTS order_items");
$conn->query("DROP TABLE IF EXISTS orders");
$conn->query("DROP TABLE IF EXISTS customers");
$conn->query("DROP TABLE IF EXISTS menu_items");

// Create tables
$sql = "
CREATE TABLE IF NOT EXISTS customers (
    customers_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS orders (
    orders_id INT AUTO_INCREMENT PRIMARY KEY,
    customers_id INT,
    order_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (customers_id) REFERENCES customers(customers_id)
);

CREATE TABLE IF NOT EXISTS menu_items (
    menu_items_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS order_items (
    order_items_id INT AUTO_INCREMENT PRIMARY KEY,
    orders_id INT,
    menu_items_id INT,
    quantity INT NOT NULL,
    FOREIGN KEY (orders_id) REFERENCES orders(orders_id),
    FOREIGN KEY (menu_items_id) REFERENCES menu_items(menu_items_id)
);
";

if ($conn->multi_query($sql) === TRUE) {
    echo "Tables created successfully\n";
} else {
    echo "Error creating tables: " . $conn->error;
}

$conn->close();
?>
