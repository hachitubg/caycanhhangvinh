<?php
// Simulate the request
$_SERVER['REQUEST_URI'] = '/shop/cay-trau-ba-xanh/';
$_SERVER['HTTP_HOST'] = '103.200.20.160:8080';

include 'includes/config.php';

// Get the REQUEST_URI and clean it
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "request_uri after parse_url: $request_uri\n";

// Remove base path to get the route
$route = str_replace(BASE_PATH, '', $request_uri);
$route = trim($route, '/');

echo "BASE_PATH: " . BASE_PATH . "\n";
echo "Route after removing BASE_PATH: $route\n";

// Remove query string if exists
if (strpos($route, '?') !== false) {
    $route = explode('?', $route)[0];
}

// Split route into parts
$route_parts = array_filter(explode('/', $route));
echo "Route parts: " . implode(', ', $route_parts) . "\n";

// Get first and second part (if exists)
$first_part = isset($route_parts[0]) ? strtolower($route_parts[0]) : '';
$second_part = isset($route_parts[1]) ? $route_parts[1] : '';

echo "First part: $first_part\n";
echo "Second part: $second_part\n";

// Now test the database query
if ($first_part === 'shop' && !empty($second_part)) {
    $_GET['slug'] = $second_part;
    
    $slug = sanitize($_GET['slug']);
    echo "\nSlug to search: $slug\n";
    
    $product_query = $conn->query("SELECT p.*, c.name as category_name FROM products p 
                                  JOIN categories c ON p.category_id = c.id 
                                  WHERE p.slug = '$slug' AND p.status = 1");
    
    if (!$product_query) {
        echo "Query error: " . $conn->error . "\n";
    } elseif ($product_query->num_rows == 0) {
        echo "No product found!\n";
    } else {
        $product = $product_query->fetch_assoc();
        echo "Product found: " . $product['name'] . "\n";
    }
}
?>
