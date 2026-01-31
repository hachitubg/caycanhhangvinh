<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Quản Lý Hình Ảnh";

// Create uploads directory if not exists
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $result = $conn->query("SELECT image FROM media WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = UPLOAD_PATH . $row['image'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $conn->query("DELETE FROM media WHERE id = $id");
    }
    header('Location: images.php');
    exit;
}

// Handle upload
$upload_message = '';
$upload_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $type = isset($_POST['type']) ? trim($_POST['type']) : 'banner';
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if (empty($title)) {
        $upload_error = 'Vui lòng nhập tiêu đề hình ảnh';
    } elseif ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $upload_error = 'Định dạng file không hợp lệ. Chỉ chấp nhận: JPG, PNG, GIF, WEBP';
        } else {
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $file_path = UPLOAD_PATH . $file_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                $type_escaped = $conn->real_escape_string($type);
                $title_escaped = $conn->real_escape_string($title);
                $desc_escaped = $conn->real_escape_string($description);
                
                $query = "INSERT INTO media (title, description, image, type, status) 
                         VALUES ('$title_escaped', '$desc_escaped', '$file_name', '$type_escaped', 1)";
                
                if ($conn->query($query)) {
                    $upload_message = 'Tải hình ảnh thành công!';
                } else {
                    $upload_error = 'Lỗi khi lưu thông tin hình ảnh: ' . $conn->error;
                    unlink($file_path);
                }
            } else {
                $upload_error = 'Lỗi khi tải hình ảnh. Vui lòng kiểm tra quyền thư mục';
            }
        }
    } else {
        $upload_error = 'Vui lòng chọn file hình ảnh';
    }
}

// Get filter type
$filter_type = isset($_GET['type']) ? $_GET['type'] : 'all';

// Get media list
$query = "SELECT * FROM media";
if ($filter_type !== 'all') {
    $filter_type_escaped = $conn->real_escape_string($filter_type);
    $query .= " WHERE type = '$filter_type_escaped'";
}
$query .= " ORDER BY created_at DESC";
$result = $conn->query($query);
$media_list = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Hình Ảnh - Admin</title>
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>img/hangvinh_icon.png">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>img/hangvinh_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .image-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .image-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f5f5f5;
        }

        .image-info {
            padding: 15px;
        }

        .image-title {
            font-weight: 600;
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .image-type {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .image-type.banner {
            background: #e3f2fd;
            color: #1976d2;
        }

        .image-type.carousel {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .image-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .image-actions a, .image-actions button {
            flex: 1;
            padding: 6px 8px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .upload-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #e0e0e0;
        }

        .upload-form {
            display: grid;
            gap: 15px;
        }

        .form-group {
            display: grid;
            gap: 5px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4caf50;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
        }

        .upload-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 4px;
            display: none;
            margin-top: 10px;
        }

        .upload-preview.show {
            display: block;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-buttons a {
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .filter-buttons a.active {
            background: #4caf50;
            color: white;
            border-color: #4caf50;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #c8e6c9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .alert-error {
            background: #ffcdd2;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
    </style>
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
                    <a href="<?php echo ADMIN_URL; ?>products.php">
                        <i class="fas fa-leaf"></i>
                        Cây Cảnh
                    </a>
                </li>
                <li class="active">
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
                    <h1>Quản Lý Hình Ảnh</h1>
                </div>
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div>Admin</div>
                </div>
            </div>

            <!-- Upload Section -->
            <div class="upload-section">
                <h2 style="margin: 0 0 20px 0; color: #333;">
                    <i class="fas fa-cloud-upload-alt"></i> Tải Hình Ảnh Mới
                </h2>

                <?php if (!empty($upload_message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $upload_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($upload_error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $upload_error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="title">Tiêu Đề *</label>
                            <input type="text" id="title" name="title" placeholder="Nhập tiêu đề hình ảnh" required>
                        </div>

                        <div class="form-group">
                            <label for="type">Loại Hình Ảnh *</label>
                            <select id="type" name="type" required>
                                <option value="banner">Banner</option>
                                <option value="carousel">Carousel Item</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô Tả</label>
                        <textarea id="description" name="description" placeholder="Nhập mô tả hình ảnh (tùy chọn)" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Chọn Hình Ảnh *</label>
                        <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage()">
                        <img id="preview" class="upload-preview" alt="Preview">
                        <small style="color: #999;">Định dạng: JPG, PNG, GIF, WEBP. Dung lượng tối đa: 5MB</small>
                    </div>

                    <button type="submit" class="btn btn-primary" style="align-self: start;">
                        <i class="fas fa-upload"></i> Tải Lên
                    </button>
                </form>
            </div>

            <!-- Filter Section -->
            <div class="filter-buttons">
                <a href="images.php?type=all" class="<?php echo $filter_type === 'all' ? 'active' : ''; ?>">
                    <i class="fas fa-th"></i> Tất Cả
                </a>
                <a href="images.php?type=banner" class="<?php echo $filter_type === 'banner' ? 'active' : ''; ?>">
                    <i class="fas fa-image"></i> Banner
                </a>
                <a href="images.php?type=carousel" class="<?php echo $filter_type === 'carousel' ? 'active' : ''; ?>">
                    <i class="fas fa-compact-disc"></i> Carousel
                </a>
            </div>

            <!-- Media List -->
            <?php if (count($media_list) > 0): ?>
                <div class="images-grid">
                    <?php foreach ($media_list as $media): ?>
                        <div class="image-card">
                            <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo htmlspecialchars($media['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($media['title']); ?>" 
                                 class="image-preview">
                            <div class="image-info">
                                <p class="image-title" title="<?php echo htmlspecialchars($media['title']); ?>">
                                    <?php echo htmlspecialchars($media['title']); ?>
                                </p>
                                <span class="image-type <?php echo $media['type']; ?>">
                                    <?php echo ucfirst($media['type']); ?>
                                </span>
                                <p style="font-size: 12px; color: #999; margin: 5px 0;">
                                    Ngày: <?php echo date('d/m/Y', strtotime($media['created_at'])); ?>
                                </p>
                                <div class="image-actions">
                                    <a href="images.php?delete=<?php echo $media['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Xóa hình ảnh này?');">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <p>Chưa có hình ảnh nào</p>
                    <small>Tải lên hình ảnh banner hoặc carousel để bắt đầu</small>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="js/admin.js"></script>
    <script>
        function previewImage() {
            const input = document.getElementById('image');
            const preview = document.getElementById('preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.add('show');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.remove('show');
            }
        }
    </script>
</body>
</html>
