<?php
/**
 * Main Router - Entry Point for all requests
 * Xử lý URL routing để load file PHP tương ứng
 */

include 'includes/config.php';

// Get the REQUEST_URI and clean it
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path to get the route
$route = str_replace(BASE_PATH, '', $request_uri);
$route = trim($route, '/');

// Remove query string if exists
if (strpos($route, '?') !== false) {
    $route = explode('?', $route)[0];
}

// Split route into parts - array_filter removes empty parts and array_values re-indexes
$route_parts = array_values(array_filter(explode('/', $route)));

// Get first and second part (if exists)
$first_part = isset($route_parts[0]) ? strtolower($route_parts[0]) : '';
$second_part = isset($route_parts[1]) ? $route_parts[1] : ''; // Keep case for product slug

// DEBUG: Uncomment to see routing info
// echo "<!-- DEBUG: Route: $route | Parts: " . implode(', ', $route_parts) . " | First: $first_part | Second: $second_part -->\n";

// Default to home
if (empty($route) || $route === 'index.php') {
    include 'index.php';
    exit;
}

// DEBUG: Log routing info
error_log("DEBUG Router: route=$route, first_part=$first_part, second_part=$second_part, REQUEST_URI=" . $_SERVER['REQUEST_URI']);

// Handle routes
switch ($first_part) {
    case 'shop':
        if (!empty($second_part)) {
            // shop-detail route: /shop/{product-slug}/
            // Pass the slug to shop-detail.php via GET
            $_GET['slug'] = $second_part;
            error_log("DEBUG Router: Setting slug=$second_part");
            include 'shop-detail.php';
        } else {
            // shop listing route: /shop
            include 'shop.php';
        }
        exit;
        
    case 'contact':
        include 'contact.php';
        exit;
        
    case 'admin':
        if (!empty($second_part)) {
            $admin_file = 'admin/' . $second_part . '.php';
            if (file_exists($admin_file)) {
                include $admin_file;
                exit;
            }
        } else {
            if (file_exists('admin/index.php')) {
                include 'admin/index.php';
                exit;
            }
        }
        break;
        
    default:
        // Try to include the file directly if it exists
        $file_path = $first_part . '.php';
        if (file_exists($file_path)) {
            include $file_path;
            exit;
        }
        break;
}

// If no route matches, show 404
http_response_code(404);
include '404.php';
?>
