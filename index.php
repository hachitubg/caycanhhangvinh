<?php 
include 'includes/config.php';
include 'template/header.php';

// Lấy tất cả loại cây từ database
$categories = [];
$category_result = $conn->query("SELECT * FROM categories WHERE status = 1 ORDER BY created_at DESC");
if ($category_result && $category_result->num_rows > 0) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!-- Fruits Shop Start-->
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
    <div class="container py-5">
        <!-- Section Header -->
        <div class="mb-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div>
                        <span class="badge bg-success mb-3" style="font-size: 13px; padding: 8px 15px;">
                            <i class="fas fa-fire me-2"></i>YÊU THÍCH NHẤT
                        </span>
                        <h2 class="display-6 fw-bold text-dark mb-3">
                            Những Cây Cảnh Bán Chạy
                        </h2>
                        <p class="lead text-muted" style="font-size: 16px;">
                            Những sản phẩm được khách hàng yêu thích và lựa chọn nhiều nhất
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="<?php echo BASE_URL; ?>shop" class="btn btn-success btn-lg">
                        Khám Phá Thêm <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Featured Products Carousel -->
        <div class="featured-carousel">
            <div class="owl-carousel vegetable-carousel owl-theme justify-content-center">
                <?php
                // Lấy 9 cây nổi bật nhất từ database (có is_featured = 1)
                $featured = $conn->query("SELECT p.*, c.name as category_name FROM products p 
                                        JOIN categories c ON p.category_id = c.id 
                                        WHERE p.status = 1 AND p.is_featured = 1
                                        ORDER BY p.created_at DESC LIMIT 9");
                
                if ($featured && $featured->num_rows > 0) {
                    while ($product = $featured->fetch_assoc()) {
                        $img_query = $conn->query("SELECT image FROM product_images WHERE product_id = " . $product['id'] . " AND is_featured = 1 LIMIT 1");
                        $image = ($img_query && $img_query->num_rows > 0) ? $img_query->fetch_assoc()['image'] : '';
                        $image_url = getImageUrl($image);
                        $discount_percent = 0;
                        if ($product['discount_price']) {
                            $discount_percent = getDiscountPercentage($product['price'], $product['discount_price']);
                        }
                        ?>
                <div class="featured-product-item">
                    <div class="product-card h-100" style="border-color: #28a745;">
                        <!-- Image Container -->
                        <div class="product-image-wrapper position-relative">
                            <img src="<?php echo $image_url; ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 250px; object-fit: cover;">
                            
                            <!-- Category Badge -->
                            <div class="position-absolute" style="top: 12px; left: 12px;">
                                <span class="badge bg-info" style="font-size: 11px; padding: 5px 10px;">
                                    <i class="fas fa-heart me-1"></i><?php echo htmlspecialchars($product['category_name']); ?>
                                </span>
                            </div>

                            <!-- Discount Badge -->
                            <?php if ($discount_percent > 0): ?>
                            <div class="position-absolute" style="top: 12px; right: 12px;">
                                <span class="badge bg-danger" style="font-size: 13px; padding: 8px 14px; font-weight: bold;">
                                    <i class="fas fa-fire me-1"></i>-<?php echo $discount_percent; ?>%
                                </span>
                            </div>
                            <?php endif; ?>

                            <!-- Overlay -->
                            <div class="product-overlay">
                                <a href="<?php echo BASE_URL; ?>shop/<?php echo htmlspecialchars($product['slug']); ?>/" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-2"></i>Chi Tiết
                                </a>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4 bg-white">
                            <h5 class="product-name mb-2">
                                <a href="<?php echo BASE_URL; ?>shop/<?php echo htmlspecialchars($product['slug']); ?>/" class="text-dark text-decoration-none">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h5>
                            
                            <p class="product-description text-muted mb-3" style="font-size: 14px; line-height: 1.5;">
                                <?php echo htmlspecialchars(substr($product['short_description'], 0, 60)) . '...'; ?>
                            </p>

                            <!-- Price Section -->
                            <div class="price-section mb-3">
                                <?php 
                                if ($product['discount_price']) {
                                    ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="price-current fw-bold text-danger" style="font-size: 18px;">
                                            <?php echo formatPrice($product['discount_price']); ?> VNĐ
                                        </span>
                                        <span class="price-original text-muted" style="font-size: 13px; text-decoration: line-through;">
                                            <?php echo formatPrice($product['price']); ?>
                                        </span>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <span class="price-current fw-bold text-success" style="font-size: 18px;">
                                        <?php echo formatPrice($product['price']); ?> VNĐ
                                    </span>
                                    <?php
                                }
                                ?>
                            </div>

                            <!-- Rating -->
                            <div class="rating mb-3">
                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                <i class="fas fa-star-half-alt text-warning" style="font-size: 12px;"></i>
                                <small class="text-muted ms-2">(15)</small>
                            </div>

                            <!-- CTA Button -->
                            <a href="<?php echo BASE_URL; ?>shop/<?php echo htmlspecialchars($product['slug']); ?>/" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-shopping-bag me-2"></i>Mua Ngay
                            </a>
                        </div>
                    </div>
                </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Fruits Shop End-->

<!-- Featured Section Start-->
<div class="container-fluid py-5" style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.02) 100%);">
    <div class="container py-5">
        <!-- Section Header -->
        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div>
                    <span class="badge bg-success mb-3" style="font-size: 13px; padding: 8px 15px;">
                        <i class="fas fa-leaf me-2"></i>DANH MỤC SẢN PHẨM
                    </span>
                    <h2 class="display-5 fw-bold text-dark mb-3">
                        Khám Phá Bộ Sưu Tập Cây Cảnh
                    </h2>
                    <p class="lead text-muted" style="font-size: 16px;">
                        Những cây cảnh được chọn lọc kỹ càng, mang đến vẻ đẹp tự nhiên cho không gian sống của bạn
                    </p>
                </div>
            </div>
            <div class="col-lg-4 text-end">
                <a href="<?php echo BASE_URL; ?>shop" class="btn btn-primary btn-lg">
                    Xem Tất Cả <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        <!-- Category Filter Tabs -->
        <div class="category-filter mb-5">
            <ul class="nav nav-pills justify-content-center flex-wrap gap-2 pb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="category-btn nav-link active" id="tab-all-btn" data-bs-toggle="pill" href="#tab-all" role="tab" aria-controls="tab-all" aria-selected="true">
                        <i class="fas fa-th me-2"></i>Tất Cả
                    </a>
                </li>
                <?php 
                foreach ($categories as $cat): 
                ?>
                <li class="nav-item" role="presentation">
                    <a class="category-btn nav-link" id="tab-<?php echo $cat['id']; ?>-btn" data-bs-toggle="pill" href="#tab-<?php echo $cat['id']; ?>" role="tab" aria-controls="tab-<?php echo $cat['id']; ?>" aria-selected="false">
                        <i class="fas fa-leaf me-2"></i><?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="tab-content">
                <!-- Tab: All Products -->
                <div id="tab-all" class="tab-pane fade show p-0 active" role="tabpanel" aria-labelledby="tab-all-btn">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="row g-4">
                                <?php
                                // Lấy tất cả cây cảnh - Giới hạn 12 sản phẩm cho trang chủ
                                $all_products = $conn->query("SELECT p.*, c.name as category_name FROM products p 
                                                              JOIN categories c ON p.category_id = c.id 
                                                              WHERE p.status = 1 
                                                              ORDER BY p.created_at DESC LIMIT 12");
                                
                                if ($all_products && $all_products->num_rows > 0) {
                                    while ($product = $all_products->fetch_assoc()) {
                                        // Lấy ảnh chính
                                        $img_query = $conn->query("SELECT image FROM product_images WHERE product_id = " . $product['id'] . " AND is_featured = 1 LIMIT 1");
                                        $image = ($img_query && $img_query->num_rows > 0) ? $img_query->fetch_assoc()['image'] : '';
                                        $image_url = getImageUrl($image);
                                        $discount_percent = 0;
                                        if ($product['discount_price']) {
                                            $discount_percent = getDiscountPercentage($product['price'], $product['discount_price']);
                                        }
                                        ?>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="product-card h-100">
                                        <!-- Image Container -->
                                        <div class="product-image-wrapper position-relative">
                                            <img src="<?php echo $image_url; ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 250px; object-fit: cover;">
                                            
                                            <!-- Category Badge -->
                                            <div class="position-absolute" style="top: 12px; left: 12px;">
                                                <span class="badge bg-success" style="font-size: 12px; padding: 6px 12px;">
                                                    <?php echo htmlspecialchars($product['category_name']); ?>
                                                </span>
                                            </div>

                                            <!-- Discount Badge -->
                                            <?php if ($discount_percent > 0): ?>
                                            <div class="position-absolute" style="top: 12px; right: 12px;">
                                                <span class="badge bg-danger" style="font-size: 13px; padding: 8px 14px; font-weight: bold;">
                                                    -<?php echo $discount_percent; ?>%
                                                </span>
                                            </div>
                                            <?php endif; ?>

                                            <!-- Overlay -->
                                            <div class="product-overlay">
                                                <a href="<?php echo BASE_URL; ?>shop/<?php echo htmlspecialchars($product['slug']); ?>/" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye me-2"></i>Xem Chi Tiết
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Product Info -->
                                        <div class="p-4 bg-white">
                                            <h5 class="product-name mb-2">
                                                <a href="<?php echo BASE_URL; ?>shop/<?php echo htmlspecialchars($product['slug']); ?>/" class="text-dark text-decoration-none">
                                                    <?php echo htmlspecialchars($product['name']); ?>
                                                </a>
                                            </h5>
                                            
                                            <p class="product-description text-muted mb-3" style="font-size: 14px; line-height: 1.5;">
                                                <?php echo htmlspecialchars(substr($product['short_description'], 0, 60)) . '...'; ?>
                                            </p>

                                            <!-- Price Section -->
                                            <div class="price-section mb-3">
                                                <?php 
                                                if ($product['discount_price']) {
                                                    ?>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="price-current fw-bold text-danger" style="font-size: 18px;">
                                                            <?php echo formatPrice($product['discount_price']); ?> VNĐ
                                                        </span>
                                                        <span class="price-original text-muted" style="font-size: 13px; text-decoration: line-through;">
                                                            <?php echo formatPrice($product['price']); ?>
                                                        </span>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <span class="price-current fw-bold text-primary" style="font-size: 18px;">
                                                        <?php echo formatPrice($product['price']); ?> VNĐ
                                                    </span>
                                                    <?php
                                                }
                                                ?>
                                            </div>

                                            <!-- Rating -->
                                            <div class="rating mb-3">
                                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                                <i class="fas fa-star-half-alt text-warning" style="font-size: 12px;"></i>
                                                <small class="text-muted ms-2">(12)</small>
                                            </div>

                                            <!-- CTA Button -->
                                            <a href="<?php echo BASE_URL; ?>shop/<?php echo htmlspecialchars($product['slug']); ?>/" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-shopping-cart me-2"></i>Chi Tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs by Category -->
                <?php
                foreach ($categories as $category):
                    // Lấy sản phẩm trong từng loại
                    $cat_products = $conn->query("SELECT * FROM products WHERE category_id = " . $category['id'] . " AND status = 1 ORDER BY created_at DESC");
                ?>
                <div id="tab-<?php echo $category['id']; ?>" class="tab-pane fade show p-0" role="tabpanel" aria-labelledby="tab-<?php echo $category['id']; ?>-btn">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="row g-4">
                                <?php
                                if ($cat_products && $cat_products->num_rows > 0) {
                                    while ($product = $cat_products->fetch_assoc()) {
                                        $img_query = $conn->query("SELECT image FROM product_images WHERE product_id = " . $product['id'] . " AND is_featured = 1 LIMIT 1");
                                        $image = ($img_query && $img_query->num_rows > 0) ? $img_query->fetch_assoc()['image'] : '';
                                        $image_url = getImageUrl($image);
                                        $discount_percent = 0;
                                        if ($product['discount_price']) {
                                            $discount_percent = getDiscountPercentage($product['price'], $product['discount_price']);
                                        }
                                        ?>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="product-card h-100">
                                        <!-- Image Container -->
                                        <div class="product-image-wrapper position-relative">
                                            <img src="<?php echo $image_url; ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 250px; object-fit: cover;">
                                            
                                            <!-- Category Badge -->
                                            <div class="position-absolute" style="top: 12px; left: 12px;">
                                                <span class="badge bg-success" style="font-size: 12px; padding: 6px 12px;">
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </span>
                                            </div>

                                            <!-- Discount Badge -->
                                            <?php if ($discount_percent > 0): ?>
                                            <div class="position-absolute" style="top: 12px; right: 12px;">
                                                <span class="badge bg-danger" style="font-size: 13px; padding: 8px 14px; font-weight: bold;">
                                                    -<?php echo $discount_percent; ?>%
                                                </span>
                                            </div>
                                            <?php endif; ?>

                                            <!-- Overlay -->
                                            <div class="product-overlay">
                                                <a href="<?php echo BASE_URL; ?>shop/<?php echo htmlspecialchars($product['slug']); ?>/" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye me-2"></i>Xem Chi Tiết
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Product Info -->
                                        <div class="p-4 bg-white">
                                            <h5 class="product-name mb-2">
                                                <a href="<?php echo BASE_URL; ?>shop/<?php echo htmlspecialchars($product['slug']); ?>/" class="text-dark text-decoration-none">
                                                    <?php echo htmlspecialchars($product['name']); ?>
                                                </a>
                                            </h5>
                                            
                                            <p class="product-description text-muted mb-3" style="font-size: 14px; line-height: 1.5;">
                                                <?php echo htmlspecialchars(substr($product['short_description'], 0, 60)) . '...'; ?>
                                            </p>

                                            <!-- Price Section -->
                                            <div class="price-section mb-3">
                                                <?php 
                                                if ($product['discount_price']) {
                                                    ?>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="price-current fw-bold text-danger" style="font-size: 18px;">
                                                            <?php echo formatPrice($product['discount_price']); ?> VNĐ
                                                        </span>
                                                        <span class="price-original text-muted" style="font-size: 13px; text-decoration: line-through;">
                                                            <?php echo formatPrice($product['price']); ?>
                                                        </span>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <span class="price-current fw-bold text-primary" style="font-size: 18px;">
                                                        <?php echo formatPrice($product['price']); ?> VNĐ
                                                    </span>
                                                    <?php
                                                }
                                                ?>
                                            </div>

                                            <!-- Rating -->
                                            <div class="rating mb-3">
                                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                                <i class="fas fa-star-half-alt text-warning" style="font-size: 12px;"></i>
                                                <small class="text-muted ms-2">(12)</small>
                                            </div>

                                            <!-- CTA Button -->
                                            <a href="<?php echo BASE_URL; ?>shop/<?php echo htmlspecialchars($product['slug']); ?>/" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-shopping-cart me-2"></i>Chi Tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                } else {
                                    echo '<div class="col-12"><p class="text-center text-muted py-5"><i class="fas fa-inbox me-2"></i>Chưa có sản phẩm trong loại này</p></div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                endforeach;
                ?>
            </div>
        </div> 
    </div>
</div>
<!-- Featured Section End-->
 
<?php include 'template/footer.php'; ?>
