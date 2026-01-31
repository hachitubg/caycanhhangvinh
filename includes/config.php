<?php
// Database Configuration - Auto-detect environment
if (!defined('DB_HOST')) {
    // Get HTTP_HOST safely (may not exist in CLI mode)
    $http_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $hostname = explode(':', $http_host)[0];

    // Set credentials based on environment
    if ($hostname === 'localhost' || $hostname === '127.0.0.1') {
        // Development/Localhost credentials
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('DB_NAME', 'caycanhhangvinh');
    } else {
        // Production/Host credentials
        define('DB_HOST', 'localhost');
        define('DB_USER', 'dongson_user');
        define('DB_PASS', 'Dongson@2024#VPS');
        define('DB_NAME', 'caycanhhangvinh');
    }

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
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Auto-detect base path based on environment
    // Check REAL hostname without port
    $hostname = explode(':', $host)[0];
    
    // For production: always use root path (/)
    // The website is deployed to the root of the web server
    $base_path = '/';

    define('BASE_URL', $protocol . '://' . $host . $base_path);
    define('BASE_PATH', $base_path);
    define('ADMIN_URL', BASE_URL . 'admin/');
    define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/admin/uploads/');
    define('UPLOAD_URL', BASE_URL . 'admin/uploads/');

    // Include functions
    require_once dirname(__FILE__) . '/functions.php';
}
