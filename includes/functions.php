<?php

// Generate slug from text
function generateSlug($text) {
    // Chuyển sang chữ thường
    $text = strtolower($text);
    
    // Loại bỏ dấu tiếng Việt
    $text = str_replace(
        ['á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'í', 'ì', 'ỉ', 'ĩ', 'ị', 'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'đ'],
        ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y', 'd'],
        $text
    );
    
    // Loại bỏ ký tự đặc biệt, giữ lại chữ, số, dấu gạch ngang
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    
    // Loại bỏ các dấu gạch ngang liên tiếp
    $text = preg_replace('/-+/', '-', $text);
    
    // Loại bỏ dấu gạch ngang ở đầu và cuối
    $text = trim($text, '-');
    
    return $text;
}

// Get file extension
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

// Upload file
function uploadFile($file, $allowed_types = ['jpg', 'jpeg', 'png', 'gif']) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $ext = getFileExtension($file['name']);
    
    if (!in_array($ext, $allowed_types)) {
        return false;
    }
    
    // Generate unique filename
    $filename = time() . '_' . uniqid() . '.' . $ext;
    $target_path = UPLOAD_PATH . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return $filename;
    }
    
    return false;
}

// Delete file
function deleteFile($filename) {
    $file_path = UPLOAD_PATH . $filename;
    if (file_exists($file_path)) {
        return unlink($file_path);
    }
    return false;
}

// Format price - Remove decimal if .00
function formatPrice($price) {
    $formatted = number_format($price, 0, '.', ',');
    return $formatted;
}

// Get discount percentage
function getDiscountPercentage($original_price, $discount_price) {
    if (!$discount_price || $discount_price >= $original_price) {
        return 0;
    }
    return round((($original_price - $discount_price) / $original_price) * 100);
}

// Sanitize input
function sanitize($input) {
    global $conn;
    return $conn->real_escape_string(trim($input));
}

// Validate URL slug
function isValidSlug($slug) {
    return preg_match('/^[a-z0-9-]+$/', $slug) && strlen($slug) > 0;
}

// Get image URL with fallback
function getImageUrl($image_filename, $default = 'https://static.vecteezy.com/system/resources/previews/022/059/000/non_2x/no-image-available-icon-vector.jpg') {
    if (empty($image_filename)) {
        return $default;
    }
    
    $image_path = UPLOAD_PATH . $image_filename;
    
    // Check if file exists, if not return default
    if (!file_exists($image_path)) {
        return $default;
    }
    
    return UPLOAD_URL . htmlspecialchars($image_filename);
}
