<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

$message = '';
$message_type = '';
$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$edit_mode = false;
$category_data = null;

// Handle delete
if (isset($_GET['delete']) && (int)$_GET['delete'] > 0) {
    $delete_id = (int)$_GET['delete'];
    
    // Get image first
    $img_query = $conn->query("SELECT image FROM categories WHERE id = $delete_id");
    if ($img_query && $img_query->num_rows > 0) {
        $img_row = $img_query->fetch_assoc();
        if ($img_row['image']) {
            deleteFile($img_row['image']);
        }
    }
    
    // Delete category
    if ($conn->query("DELETE FROM categories WHERE id = $delete_id")) {
        $message = "Xóa loại cây cảnh thành công!";
        $message_type = "success";
    } else {
        $message = "Lỗi khi xóa loại cây cảnh!";
        $message_type = "danger";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $slug = isset($_POST['slug']) && !empty($_POST['slug']) ? sanitize($_POST['slug']) : generateSlug($name);
    $description = sanitize($_POST['description'] ?? '');
    
    if (!$name) {
        $message = "Tên loại cây cảnh không được để trống!";
        $message_type = "danger";
    } elseif (!isValidSlug($slug)) {
        $message = "Slug không hợp lệ! Chỉ được chứa chữ thường, số và dấu gạch ngang.";
        $message_type = "danger";
    } else {
        // Check if editing
        if ($category_id > 0) {
            // Update mode
            $image = null;
            
            // Handle file upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Get old image
                $old_query = $conn->query("SELECT image FROM categories WHERE id = $category_id");
                $old_row = $old_query->fetch_assoc();
                
                // Upload new image
                $image = uploadFile($_FILES['image']);
                if ($image) {
                    // Delete old image
                    if ($old_row['image']) {
                        deleteFile($old_row['image']);
                    }
                }
            }
            
            // Check slug uniqueness
            $check_query = $conn->query("SELECT id FROM categories WHERE slug = '$slug' AND id != $category_id");
            if ($check_query->num_rows > 0) {
                $message = "Slug này đã tồn tại!";
                $message_type = "danger";
            } else {
                $image_update = $image ? ", image = '$image'" : '';
                $query = "UPDATE categories SET name = '$name', slug = '$slug', description = '$description' $image_update WHERE id = $category_id";
                
                if ($conn->query($query)) {
                    $message = "Cập nhật loại cây cảnh thành công!";
                    $message_type = "success";
                    $edit_mode = false;
                    $category_id = 0;
                } else {
                    $message = "Lỗi: " . $conn->error;
                    $message_type = "danger";
                }
            }
        } else {
            // Insert mode
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = uploadFile($_FILES['image']);
                if (!$image) {
                    $message = "Lỗi tải hình ảnh!";
                    $message_type = "danger";
                    $image = '';
                }
            }
            
            // Check slug uniqueness
            $check_query = $conn->query("SELECT id FROM categories WHERE slug = '$slug'");
            if ($check_query->num_rows > 0) {
                $message = "Slug này đã tồn tại!";
                $message_type = "danger";
            } else {
                $query = "INSERT INTO categories (name, slug, description, image) VALUES ('$name', '$slug', '$description', '$image')";
                
                if ($conn->query($query)) {
                    $message = "Thêm loại cây cảnh thành công!";
                    $message_type = "success";
                } else {
                    $message = "Lỗi: " . $conn->error;
                    $message_type = "danger";
                }
            }
        }
    }
}

// Load edit data
if ($action === 'edit' && $category_id > 0) {
    $result = $conn->query("SELECT * FROM categories WHERE id = $category_id");
    if ($result && $result->num_rows > 0) {
        $category_data = $result->fetch_assoc();
        $edit_mode = true;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Loại Cây Cảnh - Admin</title>
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>img/hangvinh_icon.png">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>img/hangvinh_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
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
                    <a href="<?php echo ADMIN_URL; ?>categories.php" class="active">
                        <i class="fas fa-folder"></i>
                        Loại Cây Cảnh
                    </a>
                </li>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>products.php">
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
                    <h1><?php echo $edit_mode ? 'Sửa Loại Cây Cảnh' : 'Quản Lý Loại Cây Cảnh'; ?></h1>
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

            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
                <!-- Form -->
                <div class="card">
                    <div class="card-header">
                        <h2><?php echo $edit_mode ? 'Chỉnh Sửa' : 'Thêm Mới'; ?></h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Tên Loại <span style="color: red;">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" required
                                       value="<?php echo $category_data ? htmlspecialchars($category_data['name']) : ''; ?>"
                                       placeholder="Ví dụ: Cây Lá Xanh">
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug <span style="color: red;">*</span></label>
                                <input type="text" id="slug" name="slug" class="form-control"
                                       value="<?php echo $category_data ? htmlspecialchars($category_data['slug']) : ''; ?>"
                                       placeholder="Ví dụ: cay-la-xanh">
                                <small style="color: var(--secondary-color); display: block; margin-top: 5px;">
                                    Tự động sinh ra từ tên nếu để trống
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="description">Mô Tả Ngắn</label>
                                <textarea id="description" name="description" class="form-control" rows="4"
                                          placeholder="Mô tả ngắn về loại cây cảnh"><?php echo $category_data ? htmlspecialchars($category_data['description']) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="image">Hình Ảnh</label>
                                <div class="file-upload" onclick="document.getElementById('image').click();">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Kéo thả hoặc click để chọn ảnh</p>
                                    <small>JPG, PNG, GIF (Max: 5MB)</small>
                                </div>
                                <input type="file" id="image" name="image" style="display: none;" accept="image/*">
                            </div>

                            <!-- Image Preview -->
                            <?php if ($category_data && $category_data['image']): ?>
                            <div class="image-preview-container">
                                <div class="image-preview">
                                    <img src="<?php echo UPLOAD_URL . htmlspecialchars($category_data['image']); ?>" alt="">
                                </div>
                            </div>
                            <?php endif; ?>

                            <div id="image-preview" class="image-preview-container" style="display: none; margin-top: 15px;"></div>

                            <div class="btn-group" style="margin-top: 20px;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    <?php echo $edit_mode ? 'Cập Nhật' : 'Thêm Mới'; ?>
                                </button>
                                <?php if ($edit_mode): ?>
                                <a href="<?php echo ADMIN_URL; ?>categories.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                    Hủy
                                </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Categories List -->
                <div class="card">
                    <div class="card-header">
                        <h2>Danh Sách Loại Cây Cảnh</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">Ảnh</th>
                                        <th>Tên Loại</th>
                                        <th>Slug</th>
                                        <th style="width: 100px;">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT * FROM categories ORDER BY created_at DESC");
                                    
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php if ($row['image']): ?>
                                                    <img src="<?php echo UPLOAD_URL . htmlspecialchars($row['image']); ?>" 
                                                         alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                    <?php else: ?>
                                                    <div style="width: 40px; height: 40px; background: var(--light-color); border-radius: 4px;"></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td><code><?php echo htmlspecialchars($row['slug']); ?></code></td>
                                                <td>
                                                    <div class="table-actions">
                                                        <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm" title="Sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" 
                                                           onclick="return confirm('Bạn chắc chắn muốn xóa? Tất cả cây cảnh trong loại này cũng sẽ bị xóa!');"
                                                           title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="4" class="text-center">Chưa có loại cây cảnh nào</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/admin.js"></script>
    <script>
    document.getElementById('name').addEventListener('input', function(e) {
        const slug = document.getElementById('slug');
        if (!slug.value) {
            // Auto generate slug if empty
            slug.value = e.target.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });

    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        Array.from(e.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = document.createElement('div');
                img.className = 'image-preview';
                img.innerHTML = `<img src="${event.target.result}" alt="">`;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });

        preview.style.display = 'grid';
    });
    </script>
</body>
</html>
