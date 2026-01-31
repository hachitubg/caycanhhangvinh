<?php 
include 'includes/config.php';
include 'template/header_other.php';

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';

// Get all categories for sidebar
$categories_result = $conn->query("SELECT id, name, (SELECT COUNT(*) FROM products WHERE category_id = categories.id AND status = 1) as product_count 
                                 FROM categories WHERE status = 1 ORDER BY name");

// Build query safely
$where_clause = "WHERE p.status = 1";

if (!empty($search)) {
    $search_safe = $conn->real_escape_string($search);
    $where_clause .= " AND (p.name LIKE '%$search_safe%' OR p.short_description LIKE '%$search_safe%' OR p.description LIKE '%$search_safe%')";
}

if ($category_id > 0) {
    $where_clause .= " AND p.category_id = $category_id";
}

// Order by
$order_by = "ORDER BY p.created_at DESC";
if ($sort === 'price_low') {
    $order_by = "ORDER BY p.price ASC";
} elseif ($sort === 'price_high') {
    $order_by = "ORDER BY p.price DESC";
} elseif ($sort === 'popular') {
    $order_by = "ORDER BY p.id DESC";
}

// Pagination
$per_page = 12;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

// Get total products
$count_query = $conn->query("SELECT COUNT(*) as total FROM products p $where_clause");
$count_row = $count_query->fetch_assoc();
$total_products = $count_row['total'];
$total_pages = ceil($total_products / $per_page);

// Get products
$products_query = $conn->query("SELECT p.*, c.name as category_name FROM products p 
                              JOIN categories c ON p.category_id = c.id 
                              $where_clause 
                              $order_by 
                              LIMIT $per_page OFFSET $offset");
?>

<link rel="stylesheet" href="css/shop.css">

<!-- Shop Section Start -->
<div class="shop-section">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <span class="section-badge">
                <i class="fas fa-leaf"></i>
                GIAN HÀNG CẢY CẢNH
            </span>
            <h1 class="section-title mt-3">
                Khám Phá Bộ Sưu Tập Cây Cảnh
            </h1>
            <p class="section-subtitle">
                Tìm kiếm và lọc những cây cảnh yêu thích của bạn
            </p>
        </div>

        <div class="row g-4">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <!-- Search Box -->
                    <div class="filter-card">
                        <div class="filter-header">
                            <h6>
                                <i class="fas fa-search text-success"></i>
                                Tìm Kiếm
                            </h6>
                        </div>
                        <div class="search-box">
                            <form method="GET" action="<?php echo BASE_PATH; ?>shop">
                                <input type="hidden" name="category" value="<?php echo $category_id; ?>">
                                <input type="hidden" name="sort" value="<?php echo $sort; ?>">
                                <div class="search-input-wrapper">
                                    <i class="fas fa-search"></i>
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="Tìm kiếm sản phẩm..." 
                                           value="<?php echo htmlspecialchars($search); ?>">
                                    <button type="submit" class="search-btn">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="filter-card">
                        <div class="filter-header">
                            <h6>
                                <i class="fas fa-sort text-success"></i>
                                Sắp Xếp
                            </h6>
                        </div>
                        <div class="search-box">
                            <form method="GET" action="<?php echo BASE_PATH; ?>shop">
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                                <input type="hidden" name="category" value="<?php echo $category_id; ?>">
                                <input type="hidden" name="page" value="1">
                                <select name="sort" class="sort-select w-100" onchange="this.form.submit()">
                                    <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>
                                        Mới Nhất
                                    </option>
                                    <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>
                                        Giá: Thấp → Cao
                                    </option>
                                    <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>
                                        Giá: Cao → Thấp
                                    </option>
                                    <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>
                                        Phổ Biến
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Category Filter - Desktop -->
                    <div class="filter-card category-desktop">
                        <div class="filter-header">
                            <h6>
                                <i class="fas fa-filter text-success"></i>
                                Loại Cây Cảnh
                            </h6>
                        </div>
                        <div class="category-list">
                            <a href="<?php echo BASE_PATH; ?>shop?category=0<?php echo $search ? '&search=' . urlencode($search) : ''; ?>&sort=<?php echo $sort; ?>" 
                               class="category-item <?php echo $category_id == 0 ? 'active' : ''; ?>">
                                <span>
                                    <i class="fas fa-th me-2"></i>
                                    Tất Cả
                                </span>
                                <span class="category-badge"><?php echo $total_products; ?></span>
                            </a>
                            <?php 
                            $categories_result = $conn->query("SELECT id, name, (SELECT COUNT(*) FROM products WHERE category_id = categories.id AND status = 1) as product_count 
                                                             FROM categories WHERE status = 1 ORDER BY name");
                            while ($cat = $categories_result->fetch_assoc()): ?>
                            <a href="<?php echo BASE_PATH; ?>shop?category=<?php echo $cat['id']; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>&sort=<?php echo $sort; ?>" 
                               class="category-item <?php echo $category_id == $cat['id'] ? 'active' : ''; ?>">
                                <span>
                                    <i class="fas fa-leaf me-2"></i>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </span>
                                <span class="category-badge"><?php echo $cat['product_count']; ?></span>
                            </a>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Category Filter - Mobile Dropdown -->
                    <div class="filter-card category-mobile">
                        <div class="filter-header">
                            <h6>
                                <i class="fas fa-filter text-success"></i>
                                Loại Cây Cảnh
                            </h6>
                        </div>
                        <div class="search-box">
                            <form method="GET" action="<?php echo BASE_PATH; ?>shop">
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                                <input type="hidden" name="sort" value="<?php echo $sort; ?>">
                                <select name="category" class="sort-select w-100" onchange="this.form.submit()">
                                    <option value="0" <?php echo $category_id == 0 ? 'selected' : ''; ?>>
                                        Tất Cả (<?php echo $total_products; ?>)
                                    </option>
                                    <?php 
                                    $categories_result_mobile = $conn->query("SELECT id, name, (SELECT COUNT(*) FROM products WHERE category_id = categories.id AND status = 1) as product_count 
                                                                     FROM categories WHERE status = 1 ORDER BY name");
                                    while ($cat = $categories_result_mobile->fetch_assoc()): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo $category_id == $cat['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?> (<?php echo $cat['product_count']; ?>)
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <?php if ($products_query && $products_query->num_rows > 0): ?>
                <!-- Products Header -->
                <div class="products-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="result-count">
                            Hiển thị <strong><?php echo min($per_page, $total_products - $offset); ?></strong> trong tổng số <strong><?php echo $total_products; ?></strong> sản phẩm
                        </div>
                        <div class="text-muted">
                            <i class="fas fa-th-large me-2"></i>
                            Trang <?php echo $page; ?>/<?php echo $total_pages; ?>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row g-4">
                    <?php while ($product = $products_query->fetch_assoc()): 
                        $img_query = $conn->query("SELECT image FROM product_images WHERE product_id = " . $product['id'] . " AND is_featured = 1 LIMIT 1");
                        $image = ($img_query && $img_query->num_rows > 0) ? $img_query->fetch_assoc()['image'] : '';
                        $image_url = getImageUrl($image);
                        $discount_percent = 0;
                        if ($product['discount_price']) {
                            $discount_percent = getDiscountPercentage($product['price'], $product['discount_price']);
                        }
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="product-card">
                            <!-- Image Container -->
                            <div class="product-image-wrapper">
                                <img src="<?php echo $image_url; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                
                                <!-- Category Badge -->
                                <div class="position-absolute" style="top: 15px; left: 15px;">
                                    <span class="badge bg-success" style="font-size: 12px; padding: 8px 14px; border-radius: 50px;">
                                        <i class="fas fa-leaf me-1"></i>
                                        <?php echo htmlspecialchars($product['category_name']); ?>
                                    </span>
                                </div>

                                <!-- Discount Badge -->
                                <?php if ($discount_percent > 0): ?>
                                <div class="position-absolute" style="top: 15px; right: 15px;">
                                    <span class="badge bg-danger" style="font-size: 14px; padding: 10px 16px; font-weight: bold; border-radius: 50px;">
                                        <i class="fas fa-fire me-1"></i>-<?php echo $discount_percent; ?>%
                                    </span>
                                </div>
                                <?php endif; ?>

                                <!-- Overlay -->
                                <div class="product-overlay">
                                    <a href="<?php echo BASE_URL; ?>shop-detail/<?php echo htmlspecialchars($product['slug']); ?>/" 
                                       class="btn btn-primary" 
                                       style="border-radius: 50px; padding: 10px 20px; font-size: 14px;">
                                        <i class="fas fa-eye me-2"></i>Xem Chi Tiết
                                    </a>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4" style="flex: 1; display: flex; flex-direction: column;">
                                <h5 class="product-name">
                                    <a href="<?php echo BASE_URL; ?>shop-detail/<?php echo htmlspecialchars($product['slug']); ?>/">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>
                                </h5>
                                
                                <p class="product-description">
                                    <?php echo htmlspecialchars(substr($product['short_description'], 0, 70)) . '...'; ?>
                                </p>

                                <!-- Price Section -->
                                <div class="mb-3">
                                    <?php 
                                    if ($product['discount_price']) {
                                        ?>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="price-current text-danger">
                                                <?php echo formatPrice($product['discount_price']); ?> VNĐ
                                            </span>
                                            <span class="price-original">
                                                <?php echo formatPrice($product['price']); ?>
                                            </span>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="price-current text-success">
                                            <?php echo formatPrice($product['price']); ?> VNĐ
                                        </span>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <!-- Rating -->
                                <div class="mb-3">
                                    <i class="fas fa-star text-warning" style="font-size: 13px;"></i>
                                    <i class="fas fa-star text-warning" style="font-size: 13px;"></i>
                                    <i class="fas fa-star text-warning" style="font-size: 13px;"></i>
                                    <i class="fas fa-star text-warning" style="font-size: 13px;"></i>
                                    <i class="fas fa-star-half-alt text-warning" style="font-size: 13px;"></i>
                                    <small class="text-muted ms-2">(<?php echo rand(10, 99); ?>)</small>
                                </div>

                                <!-- CTA Button -->
                                <a href="<?php echo BASE_URL; ?>shop-detail/<?php echo htmlspecialchars($product['slug']); ?>/" 
                                   class="btn btn-success btn-sm w-100 mt-auto" 
                                   style="border-radius: 50px; padding: 10px 16px; font-weight: 600; font-size: 14px;">
                                    <i class="fas fa-shopping-bag me-1"></i>Mua Ngay
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination-wrapper">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center flex-wrap">
                            <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo BASE_PATH; ?>shop?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?>&sort=<?php echo $sort; ?>" aria-label="Previous">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            $start = max(1, $page - 2);
                            $end = min($total_pages, $page + 2);
                            
                            // Show first page
                            if ($start > 1) {
                                echo '<li class="page-item"><a class="page-link" href="' . BASE_PATH . 'shop?page=1' . ($search ? '&search=' . urlencode($search) : '') . ($category_id ? '&category=' . $category_id : '') . '&sort=' . $sort . '">1</a></li>';
                                if ($start > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }
                            
                            for ($i = $start; $i <= $end; $i++): 
                            ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo BASE_PATH; ?>shop?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?>&sort=<?php echo $sort; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php
                            // Show last page
                            if ($end < $total_pages) {
                                if ($end < $total_pages - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="' . BASE_PATH . 'shop?page=' . $total_pages . ($search ? '&search=' . urlencode($search) : '') . ($category_id ? '&category=' . $category_id : '') . '&sort=' . $sort . '">' . $total_pages . '</a></li>';
                            }
                            ?>
                            
                            <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo BASE_PATH; ?>shop?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?>&sort=<?php echo $sort; ?>" aria-label="Next">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>

                <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>Không Tìm Thấy Sản Phẩm</h5>
                    <p>Vui lòng thử tìm kiếm với từ khóa khác hoặc chọn loại cây khác</p>
                    <a href="<?php echo BASE_PATH; ?>shop" class="btn btn-success mt-3" style="border-radius: 50px; padding: 12px 30px;">
                        <i class="fas fa-redo me-2"></i>Xem Tất Cả Sản Phẩm
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Shop Section End -->

<?php include 'template/footer.php'; ?>