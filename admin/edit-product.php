<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

$message = '';
$message_type = '';
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get product info
if ($product_id <= 0) {
    header('Location: products.php');
    exit;
}

$product = null;
$result = $conn->query("SELECT * FROM products WHERE id = $product_id");
if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    header('Location: products.php');
    exit;
}

// Handle delete image
if (isset($_GET['delete_image']) && (int)$_GET['delete_image'] > 0) {
    $image_id = (int)$_GET['delete_image'];
    
    $img_result = $conn->query("SELECT image FROM product_images WHERE id = $image_id AND product_id = $product_id");
    if ($img_result && $img_result->num_rows > 0) {
        $img_row = $img_result->fetch_assoc();
        deleteFile($img_row['image']);
        
        if ($conn->query("DELETE FROM product_images WHERE id = $image_id")) {
            $message = "Xóa hình ảnh thành công!";
            $message_type = "success";
        }
    }
}

// Handle form submission (update product)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $slug = isset($_POST['slug']) && !empty($_POST['slug']) ? sanitize($_POST['slug']) : generateSlug($name);
    $short_description = sanitize($_POST['short_description'] ?? '');
    $description = $_POST['description'] ?? ''; // Don't sanitize - TinyMCE content with HTML
    $price = (float)($_POST['price'] ?? 0);
    $discount_price = isset($_POST['discount_price']) && $_POST['discount_price'] ? (float)$_POST['discount_price'] : null;
    $status = isset($_POST['status']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    if (!$name) {
        $message = "Tên cây cảnh không được để trống!";
        $message_type = "danger";
    } elseif ($category_id <= 0) {
        $message = "Vui lòng chọn loại cây cảnh!";
        $message_type = "danger";
    } elseif (!isValidSlug($slug)) {
        $message = "Slug không hợp lệ!";
        $message_type = "danger";
    } elseif ($price <= 0) {
        $message = "Giá tiền phải lớn hơn 0!";
        $message_type = "danger";
    } else {
        // Check slug uniqueness
        $check_query = $conn->query("SELECT id FROM products WHERE slug = '$slug' AND id != $product_id");
        if ($check_query->num_rows > 0) {
            $message = "Slug này đã tồn tại!";
            $message_type = "danger";
        } else {
            // Escape description for database
            $description_escaped = $conn->real_escape_string($description);
            
            $discount_value = $discount_price ? "'$discount_price'" : "NULL";
            $query = "UPDATE products SET 
                     category_id = $category_id, 
                     name = '$name', 
                     slug = '$slug', 
                     short_description = '$short_description', 
                     description = '$description_escaped', 
                     price = $price, 
                     discount_price = $discount_value,
                     is_featured = $is_featured,
                     status = $status 
                     WHERE id = $product_id";
            
            if ($conn->query($query)) {
                // Update product info
                $product = $conn->query("SELECT * FROM products WHERE id = $product_id")->fetch_assoc();
                
                // Handle new image uploads
                $uploaded_count = 0;
                if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
                    // Get current max sort_order
                    $max_order = $conn->query("SELECT MAX(sort_order) as max_order FROM product_images WHERE product_id = $product_id");
                    $max_row = $max_order->fetch_assoc();
                    $next_order = ($max_row['max_order'] ?? -1) + 1;
                    
                    for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                        if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                            $file = [
                                'name' => $_FILES['images']['name'][$i],
                                'tmp_name' => $_FILES['images']['tmp_name'][$i],
                                'error' => $_FILES['images']['error'][$i]
                            ];
                            
                            $filename = uploadFile($file);
                            if ($filename) {
                                $conn->query("INSERT INTO product_images (product_id, image, sort_order) 
                                           VALUES ($product_id, '$filename', " . ($next_order + $i) . ")");
                                $uploaded_count++;
                            }
                        }
                    }
                }
                
                $message = "Cập nhật cây cảnh thành công!" . ($uploaded_count > 0 ? " ($uploaded_count hình ảnh)" : "");
                $message_type = "success";
            } else {
                $message = "Lỗi: " . $conn->error;
                $message_type = "danger";
            }
        }
    }
}

// Get categories for dropdown
$categories_result = $conn->query("SELECT id, name FROM categories WHERE status = 1 ORDER BY name");

// Get product images
$images_result = $conn->query("SELECT * FROM product_images WHERE product_id = $product_id ORDER BY sort_order");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Cây Cảnh - Admin</title>
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>img/hangvinh_icon.png">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>img/hangvinh_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="<?php echo BASE_URL; ?>img/hangvinh_icon.png" alt="Hằng Vinh" style="height: 50px; margin-bottom: 10px;">
                <h2><i class="fas fa-leaf"></i> Hằng Vinh</h2>
                <p>Admin Panel</p>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="<?php echo ADMIN_URL; ?>">
                        <i class="fas fa-chart-line"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>categories.php">
                        <i class="fas fa-folder"></i>
                        Loại Cây Cảnh
                    </a>
                </li>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>products.php" class="active">
                        <i class="fas fa-leaf"></i>
                        Cây Cảnh
                    </a>
                </li>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>images.php">
                        <i class="fas fa-images"></i>
                        Quản Lý Hình Ảnh
                    </a>
                </li>
                <li style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <a href="/caycanhhangvinh/">
                        <i class="fas fa-home"></i>
                        Trang Chủ
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header-top">
                <div>
                    <h1>Sửa Cây Cảnh: <?php echo htmlspecialchars($product['name']); ?></h1>
                </div>
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div>Admin</div>
                </div>
            </div>

            <!-- Message Alert -->
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <span><?php echo $message; ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <!-- Basic Info -->
                <div class="card">
                    <div class="card-header">
                        <h2>Thông Tin Cây Cảnh</h2>
                        <a href="<?php echo ADMIN_URL; ?>products.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Quay Lại
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Tên Cây Cảnh <span style="color: red;">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" required
                                       value="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="category_id">Loại Cây <span style="color: red;">*</span></label>
                                <select id="category_id" name="category_id" class="form-control" required>
                                    <option value="">-- Chọn Loại --</option>
                                    <?php
                                    if ($categories_result && $categories_result->num_rows > 0) {
                                        while ($cat = $categories_result->fetch_assoc()) {
                                            $selected = ($cat['id'] == $product['category_id']) ? 'selected' : '';
                                            echo '<option value="' . $cat['id'] . '" ' . $selected . '>' . htmlspecialchars($cat['name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug <span style="color: red;">*</span></label>
                            <input type="text" id="slug" name="slug" class="form-control" required
                                   value="<?php echo htmlspecialchars($product['slug']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="short_description">Mô Tả Ngắn</label>
                            <textarea id="short_description" name="short_description" class="form-control" rows="2"
                                      placeholder="Mô tả ngắn gọn"><?php echo htmlspecialchars($product['short_description']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô Tả Chi Tiết</label>
                            <input type="hidden" id="description" name="description" value="<?php echo $product['description']; ?>">
                            <div id="editor-container" style="height: 300px; background: white; border: 1px solid #ddd; border-radius: 4px;"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Giá Tiền (VNĐ) <span style="color: red;">*</span></label>
                                <input type="number" id="price" name="price" class="form-control" step="1000" required
                                       value="<?php echo $product['price']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="discount_price">Giá Khuyến Mãi (VNĐ)</label>
                                <input type="number" id="discount_price" name="discount_price" class="form-control" step="1000"
                                       value="<?php echo $product['discount_price'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_featured" value="1" <?php echo $product['is_featured'] ? 'checked' : ''; ?>>
                                <span style="margin-left: 8px;"><i class="fas fa-star"></i> Sản Phẩm Nổi Bật</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="status" value="1" <?php echo $product['status'] ? 'checked' : ''; ?>>
                                <span style="margin-left: 8px;">Hiển Thị Sản Phẩm</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Images Management -->
                <div class="card" style="margin-top: 20px;">
                    <div class="card-header">
                        <h2>Quản Lý Hình Ảnh</h2>
                    </div>
                    <div class="card-body">
                        <!-- Current Images -->
                        <?php if ($images_result && $images_result->num_rows > 0): ?>
                        <div style="margin-bottom: 30px;">
                            <h3 style="font-size: 16px; margin-bottom: 15px;">Hình Ảnh Hiện Tại</h3>
                            <div class="image-preview-container">
                                <?php while ($image = $images_result->fetch_assoc()): ?>
                                <div class="image-preview">
                                    <img src="<?php echo UPLOAD_URL . htmlspecialchars($image['image']); ?>" alt="">
                                    <button type="button" class="image-delete-btn" 
                                            onclick="deleteImage(<?php echo $image['id']; ?>)"
                                            title="Xóa">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Add New Images -->
                        <div>
                            <h3 style="font-size: 16px; margin-bottom: 15px;">Thêm Hình Ảnh Mới</h3>
                            <div class="file-upload" onclick="document.getElementById('images').click();">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Kéo thả hoặc click để chọn ảnh</p>
                                <small>Hỗ trợ nhiều ảnh</small>
                            </div>
                            <input type="file" id="images" name="images[]" style="display: none;" accept="image/*" multiple>
                            <div id="image-preview" class="image-preview-container" style="display: none; margin-top: 15px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" style="width: 200px;">
                        <i class="fas fa-save"></i>
                        Cập Nhật
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="js/admin.js"></script>
    <script>
    // Initialize Quill Editor
    const quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Load existing content if any
    const existingContent = document.getElementById('description').value;
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }

    // Handle form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        document.getElementById('description').value = quill.root.innerHTML;
    });

    document.getElementById('name').addEventListener('input', function(e) {
        const slug = document.getElementById('slug');
        const original_slug = '<?php echo htmlspecialchars($product['slug']); ?>';
        if (slug.value === original_slug || !slug.value) {
            slug.value = e.target.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });

    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        Array.from(e.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = document.createElement('div');
                img.className = 'image-preview';
                img.innerHTML = `<img src="${event.target.result}" alt="">
                                <div style="position: absolute; top: 5px; left: 5px; background: var(--secondary-color); color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px;">
                                    Ảnh ${index + 1}
                                </div>`;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });

        preview.style.display = 'grid';
    });
    </script>
</body>
</html>
