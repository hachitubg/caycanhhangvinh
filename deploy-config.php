<?php
/**
 * Deployment Configuration Helper
 * Sử dụng file này để cấu hình project cho các môi trường khác nhau
 * (localhost, development host, production host)
 */

// ============================================================================
// HƯỚNG DẪN SỬ DỤNG:
// ============================================================================
// 1. Chỉnh sửa các biến dưới đây theo môi trường của bạn
// 2. Lưu file
// 3. Chạy file này qua browser: http://yourdomain.com/deploy-config.php
// 4. Nó sẽ tự động cập nhật includes/config.php
// ============================================================================

// Xác định môi trường hiện tại
$environment = 'production'; // Thay đổi thành: 'localhost', 'development', 'production'

// Cấu hình cho từng môi trường
$configs = [
    'localhost' => [
        'DB_HOST' => 'localhost',
        'DB_USER' => 'root',
        'DB_PASS' => '',
        'DB_NAME' => 'caycanhhangvinh',
        'BASE_PATH' => '/caycanhhangvinh/',
        'DEBUG' => true
    ],
    'development' => [
        'DB_HOST' => 'your_dev_host',
        'DB_USER' => 'dev_username',
        'DB_PASS' => 'dev_password',
        'DB_NAME' => 'dev_database',
        'BASE_PATH' => '/caycanhhangvinh/',
        'DEBUG' => true
    ],
    'production' => [
        'DB_HOST' => 'your_production_host',  // VD: localhost hoặc MySQL hostname từ hosting
        'DB_USER' => 'your_production_user',   // VD: caycanhhangvinh_user
        'DB_PASS' => 'your_production_pass',   // VD: strong_password_here
        'DB_NAME' => 'your_production_db',     // VD: caycanhhangvinh_db
        'BASE_PATH' => '/',                    // Nếu nằm ở thư mục gốc, dùng '/'
        'DEBUG' => false
    ]
];

// Lấy config cho environment hiện tại
if (!isset($configs[$environment])) {
    die("❌ Environment '{$environment}' không được hỗ trợ!");
}

$config = $configs[$environment];

// Tạo nội dung config.php mới
$config_content = <<<'PHP'
<?php
// Database Configuration
define('DB_HOST', '[DB_HOST]');
define('DB_USER', '[DB_USER]');
define('DB_PASS', '[DB_PASS]');
define('DB_NAME', '[DB_NAME]');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");

// Global variables - Dynamic Base Path
// Get the base path dynamically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_path = '[BASE_PATH]'; // Change this based on your deployment

define('BASE_URL', $protocol . '://' . $host . $base_path);
define('ADMIN_URL', BASE_URL . 'admin/');
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . $base_path . 'admin/uploads/');
define('UPLOAD_URL', BASE_URL . 'admin/uploads/');

// Include functions
require_once dirname(__FILE__) . '/functions.php';

PHP;

// Thay thế các placeholder với giá trị thực
$config_content = str_replace('[DB_HOST]', $config['DB_HOST'], $config_content);
$config_content = str_replace('[DB_USER]', $config['DB_USER'], $config_content);
$config_content = str_replace('[DB_PASS]', $config['DB_PASS'], $config_content);
$config_content = str_replace('[DB_NAME]', $config['DB_NAME'], $config_content);
$config_content = str_replace('[BASE_PATH]', $config['BASE_PATH'], $config_content);

// Đường dẫn file config
$config_file = __DIR__ . '/includes/config.php';

// Backup file cũ
if (file_exists($config_file)) {
    $backup_file = $config_file . '.backup_' . date('Y-m-d_H-i-s');
    copy($config_file, $backup_file);
    echo "<p style='color: green;'>✓ File cũ được backup tại: {$backup_file}</p>";
}

// Ghi file config mới
if (file_put_contents($config_file, $config_content)) {
    echo "<h2 style='color: green;'>✓ Cập nhật config thành công!</h2>";
    echo "<p>Environment: <strong>{$environment}</strong></p>";
    echo "<p>Database: <strong>{$config['DB_NAME']}</strong></p>";
    echo "<p>Base Path: <strong>{$config['BASE_PATH']}</strong></p>";
    echo "<p style='color: #666; margin-top: 20px;'>File cấu hình đã được cập nhật. Bạn có thể xóa file này (deploy-config.php) hoặc mở khoá nó.</p>";
} else {
    echo "<h2 style='color: red;'>❌ Không thể cập nhật file config!</h2>";
    echo "<p>Kiểm tra quyền ghi thư mục /includes/</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Deployment Config Helper</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .container {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
        }
        .config-info {
            background: white;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #28a745;
        }
        code {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Deployment Configuration Helper</h1>
        
        <div class="config-info">
            <h3>Cấu Hình Hiện Tại:</h3>
            <table style="width: 100%;">
                <tr>
                    <td><strong>Environment:</strong></td>
                    <td><code><?php echo $environment; ?></code></td>
                </tr>
                <tr>
                    <td><strong>Database Host:</strong></td>
                    <td><code><?php echo $config['DB_HOST']; ?></code></td>
                </tr>
                <tr>
                    <td><strong>Database User:</strong></td>
                    <td><code><?php echo $config['DB_USER']; ?></code></td>
                </tr>
                <tr>
                    <td><strong>Database Name:</strong></td>
                    <td><code><?php echo $config['DB_NAME']; ?></code></td>
                </tr>
                <tr>
                    <td><strong>Base Path:</strong></td>
                    <td><code><?php echo $config['BASE_PATH']; ?></code></td>
                </tr>
            </table>
        </div>

        <div class="warning">
            <strong>⚠️ Cảnh Báo Bảo Mật:</strong>
            <p>Sau khi cấu hình xong, hãy xóa file này (deploy-config.php) khỏi server vì nó chứa thông tin cấu hình.</p>
            <p>Hoặc bạn có thể đổi tên nó hoặc di chuyển vào thư mục không công khai.</p>
        </div>
    </div>
</body>
</html>
