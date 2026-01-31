<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

$message = '';
$message_type = '';
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle delete product
if (isset($_GET['delete']) && (int)$_GET['delete'] > 0) {
    $delete_id = (int)$_GET['delete'];
    
    // Get all images
    $img_query = $conn->query("SELECT image FROM product_images WHERE product_id = $delete_id");
    if ($img_query) {
        while ($img_row = $img_query->fetch_assoc()) {
            deleteFile($img_row['image']);
        }
    }
    
    // Delete product
    if ($conn->query("DELETE FROM products WHERE id = $delete_id")) {
        $message = "Xóa cây cảnh thành công!";
        $message_type = "success";
    } else {
        $message = "Lỗi khi xóa cây cảnh!";
        $message_type = "danger";
    }
}

// No form submission handling here - form is in add-product.php

// Get categories for dropdown
$categories_result = $conn->query("SELECT id, name FROM categories WHERE status = 1 ORDER BY name");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Cây Cảnh - Admin</title>
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
                    <h1>Quản Lý Cây Cảnh</h1>
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

            <!-- Products List -->
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>Danh Sách Cây Cảnh</h2>
                    <a href="<?php echo ADMIN_URL; ?>add-product.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Thêm Cây Cảnh Mới
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên Cây</th>
                                    <th>Loại</th>
                                    <th>Giá</th>
                                    <th>Nổi Bật</th>
                                    <th>Hiển Thị</th>
                                    <th>Hình Ảnh</th>
                                    <th style="width: 120px;">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT p.*, c.name as category_name FROM products p 
                                                       JOIN categories c ON p.category_id = c.id 
                                                       ORDER BY p.created_at DESC");
                                
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // Get first image
                                        $img_query = $conn->query("SELECT image FROM product_images WHERE product_id = " . $row['id'] . " ORDER BY sort_order LIMIT 1");
                                        $has_image = $img_query && $img_query->num_rows > 0;
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                                                <br>
                                                <small style="color: var(--secondary-color);">
                                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small><?php echo htmlspecialchars($row['slug']); ?></small>
                                            </td>
                                            <td>
                                                <strong><?php echo formatPrice($row['price']); ?> VNĐ</strong>
                                                <?php if ($row['discount_price']): ?>
                                                <br>
                                                <small style="text-decoration: line-through; color: var(--secondary-color);">
                                                    <?php echo formatPrice($row['discount_price']); ?>
                                                </small>
                                                <br>
                                                <span class="badge badge-warning">-<?php echo getDiscountPercentage($row['price'], $row['discount_price']); ?>%</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row['is_featured']): ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-star"></i> Nổi Bật
                                                </span>
                                                <?php else: ?>
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-minus-circle"></i> Bình Thường
                                                </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row['status']): ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-eye"></i> Hiển Thị
                                                </span>
                                                <?php else: ?>
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-eye-slash"></i> Ẩn
                                                </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($has_image): ?>
                                                <img src="<?php echo UPLOAD_URL . htmlspecialchars($img_query->fetch_assoc()['image']); ?>" 
                                                     alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                <?php else: ?>
                                                <span style="color: var(--secondary-color); font-size: 12px;">Không có ảnh</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="table-actions" style="display: flex; gap: 5px;">
                                                    <a href="<?php echo ADMIN_URL; ?>edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" 
                                                       onclick="return confirm('Bạn chắc chắn muốn xóa?');"
                                                       title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center">Chưa có cây cảnh nào</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/admin.js"></script>
</body>
</html>
