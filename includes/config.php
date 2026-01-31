<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'dongson_user');
define('DB_PASS', 'Dongson@2024#VPS');
define('DB_NAME', 'caycanhhangvinh');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");

// Global variables - Dynamic Base Path
// Detect environment (localhost or production)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

// Auto-detect base path based on environment
// Check REAL hostname without port
$hostname = explode(':', $host)[0];
if ($hostname === 'localhost' || $hostname === '127.0.0.1') {
    // Development/Localhost - include folder name
    $base_path = '/caycanhhangvinh/';
} else {
    // Production - root path (always /)
    $base_path = '/';
}

define('BASE_URL', $protocol . '://' . $host . $base_path);
define('BASE_PATH', $base_path);
define('ADMIN_URL', BASE_URL . 'admin/');
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/admin/uploads/');
define('UPLOAD_URL', BASE_URL . 'admin/uploads/');

// Include functions
require_once dirname(__FILE__) . '/functions.php';
