<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in (bạn có thể thêm authentication sau)
// Redirect to login nếu chưa đăng nhập

$page_title = "Dashboard";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Hằng Vinh</title>
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
                    <a href="<?php echo ADMIN_URL; ?>" class="active">
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
                    <h1>Dashboard</h1>
                </div>
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div>Admin</div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <!-- Total Categories -->
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <p style="margin: 0; color: var(--secondary-color); font-size: 14px;">Tổng Loại Cây</p>
                                <h2 style="margin: 10px 0 0 0; color: var(--dark-color);">
                                    <?php
                                    $result = $conn->query("SELECT COUNT(*) as total FROM categories WHERE status = 1");
                                    $row = $result->fetch_assoc();
                                    echo $row['total'];
                                    ?>
                                </h2>
                            </div>
                            <div style="font-size: 40px; color: var(--primary-color); opacity: 0.2;">
                                <i class="fas fa-folder"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Products -->
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <p style="margin: 0; color: var(--secondary-color); font-size: 14px;">Tổng Cây Cảnh</p>
                                <h2 style="margin: 10px 0 0 0; color: var(--dark-color);">
                                    <?php
                                    $result = $conn->query("SELECT COUNT(*) as total FROM products WHERE status = 1");
                                    $row = $result->fetch_assoc();
                                    echo $row['total'];
                                    ?>
                                </h2>
                            </div>
                            <div style="font-size: 40px; color: var(--info-color); opacity: 0.2;">
                                <i class="fas fa-leaf"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Images -->
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <p style="margin: 0; color: var(--secondary-color); font-size: 14px;">Tổng Hình Ảnh</p>
                                <h2 style="margin: 10px 0 0 0; color: var(--dark-color);">
                                    <?php
                                    $result = $conn->query("SELECT COUNT(*) as total FROM product_images");
                                    $row = $result->fetch_assoc();
                                    echo $row['total'];
                                    ?>
                                </h2>
                            </div>
                            <div style="font-size: 40px; color: var(--warning-color); opacity: 0.2;">
                                <i class="fas fa-images"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="card" style="margin-top: 30px;">
                <div class="card-header">
                    <h2>Cây Cảnh Mới Nhất</h2>
                    <a href="products.php" class="btn btn-primary btn-sm">Xem Tất Cả</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên Cây</th>
                                    <th>Loại</th>
                                    <th>Giá</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT p.*, c.name as category_name FROM products p 
                                         JOIN categories c ON p.category_id = c.id 
                                         ORDER BY p.created_at DESC LIMIT 5";
                                $result = $conn->query($query);
                                
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                            <td><?php echo formatPrice($row['price']); ?> VNĐ</td>
                                            <td>
                                                <span class="badge <?php echo $row['status'] ? 'badge-success' : 'badge-danger'; ?>">
                                                    <?php echo $row['status'] ? 'Hiển Thị' : 'Ẩn'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="<?php echo ADMIN_URL; ?>edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?php echo ADMIN_URL; ?>products.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa?');">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">Chưa có cây cảnh nào</td></tr>';
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
