<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $slug = isset($_POST['slug']) && !empty($_POST['slug']) ? sanitize($_POST['slug']) : generateSlug($name);
    $short_description = sanitize($_POST['short_description'] ?? '');
    $description = $_POST['description'] ?? ''; // Don't sanitize - TinyMCE content with HTML
    $price = (float)($_POST['price'] ?? 0);
    $discount_price = isset($_POST['discount_price']) && $_POST['discount_price'] ? (float)$_POST['discount_price'] : null;
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
        // Escape description for database
        $description_escaped = $conn->real_escape_string($description);
        
        // Insert product
        $discount_value = $discount_price ? "'$discount_price'" : "NULL";
        $query = "INSERT INTO products (category_id, name, slug, short_description, description, price, discount_price, is_featured) 
                 VALUES ($category_id, '$name', '$slug', '$short_description', '$description_escaped', $price, $discount_value, $is_featured)";
        
        if ($conn->query($query)) {
            $new_product_id = $conn->insert_id;
            
            // Handle multiple image uploads
            $uploaded_count = 0;
            if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
                for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $_FILES['images']['name'][$i],
                            'tmp_name' => $_FILES['images']['tmp_name'][$i],
                            'error' => $_FILES['images']['error'][$i]
                        ];
                        
                        $filename = uploadFile($file);
                        if ($filename) {
                            $is_featured = ($i === 0) ? 1 : 0;
                            $conn->query("INSERT INTO product_images (product_id, image, sort_order, is_featured) 
                                        VALUES ($new_product_id, '$filename', $i, $is_featured)");
                            $uploaded_count++;
                        }
                    }
                }
            }
            
            $message = "Thêm cây cảnh thành công!" . ($uploaded_count > 0 ? " ($uploaded_count hình ảnh)" : "");
            $message_type = "success";
            // Clear form after success
            $_POST = [];
        } else {
            $message = "Lỗi: " . $conn->error;
            $message_type = "danger";
        }
    }
}

// Get categories for dropdown
$categories_result = $conn->query("SELECT id, name FROM categories WHERE status = 1 ORDER BY name");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Cây Cảnh - Admin</title>
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
                    <h1>Thêm Cây Cảnh Mới</h1>
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

            <!-- Form Add Product -->
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>Nhập Thông Tin Cây Cảnh</h2>
                    <a href="<?php echo ADMIN_URL; ?>products.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Quay Lại
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Tên Cây Cảnh <span style="color: red;">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" required
                                       placeholder="Ví dụ: Cây Hạnh Phúc"
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="category_id">Loại Cây <span style="color: red;">*</span></label>
                                <select id="category_id" name="category_id" class="form-control" required>
                                    <option value="">-- Chọn Loại --</option>
                                    <?php
                                    if ($categories_result && $categories_result->num_rows > 0) {
                                        while ($cat = $categories_result->fetch_assoc()) {
                                            $selected = (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : '';
                                            echo '<option value="' . $cat['id'] . '" ' . $selected . '>' . htmlspecialchars($cat['name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control"
                                   placeholder="Tự động sinh nếu để trống"
                                   value="<?php echo isset($_POST['slug']) ? htmlspecialchars($_POST['slug']) : ''; ?>">
                            <small style="color: var(--secondary-color); display: block; margin-top: 5px;">
                                Tự động sinh ra từ tên nếu không nhập
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="short_description">Mô Tả Ngắn</label>
                            <textarea id="short_description" name="short_description" class="form-control" rows="2"
                                      placeholder="Mô tả ngắn gọn (một dòng)"><?php echo isset($_POST['short_description']) ? htmlspecialchars($_POST['short_description']) : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô Tả Chi Tiết</label>
                            <input type="hidden" id="description" name="description" value="<?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?>">
                            <div id="editor-container" style="height: 300px; background: white; border: 1px solid #ddd; border-radius: 4px;"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Giá Tiền (VNĐ) <span style="color: red;">*</span></label>
                                <input type="number" id="price" name="price" class="form-control" step="1000" required
                                       placeholder="50000"
                                       value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="discount_price">Giá Khuyến Mãi (VNĐ)</label>
                                <input type="number" id="discount_price" name="discount_price" class="form-control" step="1000"
                                       placeholder="0 (nếu không có khuyến mãi)"
                                       value="<?php echo isset($_POST['discount_price']) ? htmlspecialchars($_POST['discount_price']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_featured" value="1" 
                                       <?php echo (isset($_POST['is_featured'])) ? 'checked' : ''; ?>>
                                <span style="margin-left: 8px;"><i class="fas fa-star"></i> Sản Phẩm Nổi Bật</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="images">Hình Ảnh <span style="color: red;">*</span></label>
                            <div class="file-upload" onclick="document.getElementById('images').click();">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Kéo thả hoặc click để chọn ảnh</p>
                                <small>Hỗ trợ nhiều ảnh. Ảnh đầu tiên sẽ là ảnh chính</small>
                            </div>
                            <input type="file" id="images" name="images[]" style="display: none;" accept="image/*" multiple required>
                        </div>

                        <div id="image-preview" class="image-preview-container" style="display: none; margin-top: 15px;"></div>

                        <div style="display: flex; gap: 10px; margin-top: 30px;">
                            <button type="submit" class="btn btn-primary" style="flex: 1;">
                                <i class="fas fa-save"></i>
                                Thêm Cây Cảnh
                            </button>
                            <a href="<?php echo ADMIN_URL; ?>products.php" class="btn btn-secondary" style="flex: 1;">
                                <i class="fas fa-times"></i>
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
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
        if (!slug.value) {
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
                                    ${index === 0 ? 'Ảnh chính' : 'Ảnh ' + (index + 1)}
                                </div>`;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });

        preview.style.display = 'grid';
    });

    // Drag and drop
    const fileUpload = document.querySelector('.file-upload');
    const fileInput = document.getElementById('images');

    fileUpload.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileUpload.style.backgroundColor = '#f0f0f0';
        fileUpload.style.borderColor = 'var(--primary-color)';
    });

    fileUpload.addEventListener('dragleave', () => {
        fileUpload.style.backgroundColor = '';
        fileUpload.style.borderColor = '';
    });

    fileUpload.addEventListener('drop', (e) => {
        e.preventDefault();
        fileUpload.style.backgroundColor = '';
        fileUpload.style.borderColor = '';
        fileInput.files = e.dataTransfer.files;
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    });
    </script>
</body>
</html>
