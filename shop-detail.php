<?php 
include 'includes/config.php';
include 'template/header_other.php';

// Lấy slug từ URL path (/shop-detail/slug-name/)
$slug = '';
if (isset($_GET['slug'])) {
    $slug = sanitize($_GET['slug']);
} elseif (isset($_GET['id'])) {
    $slug = (int)$_GET['id'];
} else {
    // Parse từ REQUEST_URI khi rewrite bởi Nginx
    $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // Remove base path if exists
    $base_path = BASE_PATH;
    if (strpos($request_uri, $base_path) === 0) {
        $request_uri = substr($request_uri, strlen($base_path));
    }
    
    // Extract slug từ /shop-detail/slug-name/ hoặc /shop-detail/slug-name
    if (preg_match('#^shop-detail/([^/]+)/?$#', $request_uri, $matches)) {
        $slug = trim($matches[1], '/');
    }
}

// Nếu là ID cũ hoặc rỗng, redirect
if (is_numeric($slug)) {
    $product_id = $slug;
    $prod = $conn->query("SELECT slug FROM products WHERE id = $product_id LIMIT 1");
    if ($prod && $prod->num_rows > 0) {
        $p = $prod->fetch_assoc();
        header("Location: <?php echo BASE_URL; ?>shop-detail/" . urlencode($p['slug']) . "/");
        exit;
    } else {
        header("Location: <?php echo BASE_URL; ?>");
        exit;
    }
}

if (empty($slug)) {
    header("Location: <?php echo BASE_URL; ?>");
    exit;
}

// Decode slug nếu cần
$slug = urldecode($slug);

// Lấy thông tin sản phẩm từ slug
$product_query = $conn->query("SELECT p.*, c.name as category_name, c.slug as category_slug 
                              FROM products p 
                              JOIN categories c ON p.category_id = c.id 
                              WHERE p.slug = '$slug' AND p.status = 1");

if (!$product_query || $product_query->num_rows == 0) {
    header("Location: <?php echo BASE_URL; ?>");
    exit;
}

$product = $product_query->fetch_assoc();
$product_id = $product['id'];

// Lấy tất cả ảnh sản phẩm
$images_query = $conn->query("SELECT * FROM product_images WHERE product_id = $product_id ORDER BY sort_order ASC");
$images = [];
if ($images_query && $images_query->num_rows > 0) {
    while ($img = $images_query->fetch_assoc()) {
        $images[] = $img;
    }
}

// Lấy các sản phẩm liên quan (cùng loại)
$related_query = $conn->query("SELECT p.*, c.name as category_name 
                              FROM products p 
                              JOIN categories c ON p.category_id = c.id 
                              WHERE p.category_id = " . $product['category_id'] . " 
                              AND p.id != $product_id 
                              AND p.status = 1 
                              ORDER BY RAND() LIMIT 4");

// Calculate discount percentage
$discount_percent = 0;
if ($product['discount_price']) {
    $discount_percent = getDiscountPercentage($product['price'], $product['discount_price']);
}
?>

<style>
/* Product Detail Custom Styles */
.product-detail-section {
    background: #f8f9fa;
    padding: 40px 0;
}

.product-image-gallery {
    position: sticky;
    top: 120px;
}

.main-image-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
}

.main-image-container img {
    width: 100%;
    height: 450px;
    object-fit: contain;
    padding: 20px;
    display: block;
}

.thumbnail-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 12px;
}

.thumbnail-item {
    cursor: pointer;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
}

.thumbnail-item:hover,
.thumbnail-item.active {
    border-color: #28a745;
    box-shadow: 0 3px 12px rgba(40, 167, 69, 0.2);
}

.thumbnail-item img {
    width: 100%;
    height: 80px;
    object-fit: cover;
}

/* Product Info Card */
.product-info-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    height: fit-content;
}

.product-title-detail {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.4;
    margin-bottom: 20px;
    word-break: break-word;
}

.price-section {
    padding: 0;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.price-row {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.price-group {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.current-price {
    font-size: 2rem;
    font-weight: 700;
    color: #dc3545;
}

.original-price {
    font-size: 1rem;
    color: #999;
    text-decoration: line-through;
}

.discount-badge {
    background: #dc3545;
    color: white;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 4px;
}

.short-desc {
    color: #333;
    line-height: 1.7;
    font-size: 15px;
    margin-bottom: 25px;
}

.btn-contact {
    width: 100%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 16px;
    font-size: 16px;
    font-weight: 700;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
}

.btn-contact:active {
    transform: translateY(0);
}

/* Description Tab */
.product-tabs {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    margin-top: 40px;
}

.tab-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.description-content {
    font-size: 15px;
    line-height: 1.8;
    color: #333;
}

.description-content p {
    margin-bottom: 16px;
}

.description-content h1,
.description-content h2,
.description-content h3,
.description-content h4,
.description-content h5,
.description-content h6 {
    color: #1a1a1a;
    font-weight: 700;
    margin-top: 20px;
    margin-bottom: 15px;
}

.description-content h1 { font-size: 26px; }
.description-content h2 { font-size: 22px; }
.description-content h3 { font-size: 18px; }

.description-content ul,
.description-content ol {
    margin-left: 20px;
    margin-bottom: 16px;
    line-height: 2;
}

.description-content li {
    margin-bottom: 8px;
}

.description-content strong {
    font-weight: 700;
    color: #1a1a1a;
}

.description-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
}

.description-content blockquote {
    border-left: 4px solid #28a745;
    padding-left: 20px;
    margin-left: 0;
    margin-bottom: 16px;
    color: #666;
    font-style: italic;
    background: #f9f9f9;
    padding: 15px 20px;
    border-radius: 4px;
}

.description-content table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
    border-radius: 8px;
    overflow: hidden;
}

.description-content table th,
.description-content table td {
    border: 1px solid #e9ecef;
    padding: 12px;
    text-align: left;
}

.description-content table th {
    background: #f8f9fa;
    font-weight: 700;
}

/* Contact Modal */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 20px;
    padding: 40px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    text-align: center;
    margin-bottom: 30px;
}

.modal-header h2 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.modal-header p {
    color: #6c757d;
    margin: 0;
    font-size: 13px;
}

.contact-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    font-size: 13px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item i {
    font-size: 16px;
    color: #28a745;
    width: 24px;
    text-align: center;
    flex-shrink: 0;
}

.info-item span {
    color: #333;
    line-height: 1.3;
}

.contact-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 12px;
}

.contact-btn {
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    color: white;
}

.contact-btn i {
    font-size: 20px;
}

.btn-zalo {
    background: linear-gradient(135deg, #0084FF 0%, #0066CC 100%);
}

.btn-zalo:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 132, 255, 0.3);
}

.btn-facebook {
    background: linear-gradient(135deg, #1877F2 0%, #0A66C2 100%);
}

.btn-facebook:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(24, 119, 242, 0.3);
}

.btn-phone {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.btn-phone:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
}

.close-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    background: none;
    border: none;
    font-size: 28px;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.3s ease;
}

.close-btn:hover {
    color: #1a1a1a;
    transform: rotate(90deg);
}

/* Related Products */
.related-products-section {
    margin-top: 60px;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 30px;
}

.product-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.product-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    transform: translateY(-5px);
}

.product-image-wrapper {
    position: relative;
    overflow: hidden;
    background: #f5f5f5;
    height: 250px;
}

.product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image-wrapper img {
    transform: scale(1.1);
}

.product-info {
    padding: 20px;
}

.product-name {
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 10px;
    font-size: 15px;
    line-height: 1.4;
}

.product-name a {
    color: #1a1a1a;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-name a:hover {
    color: #28a745;
}

.product-price {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 12px;
}

.product-current-price {
    font-size: 14px;
    font-weight: 700;
    color: #dc3545;
}

.product-original-price {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
}

.product-btn {
    width: 100%;
    background: #28a745;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.product-btn:hover {
    background: #20c997;
}

/* Breadcrumb */
.breadcrumb {
    background: transparent !important;
    padding: 0 !important;
    margin-bottom: 30px;
}

.breadcrumb-item {
    color: #6c757d;
    font-size: 14px;
}

.breadcrumb-item a {
    color: #28a745;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: #20c997;
}

.breadcrumb-item.active {
    color: #1a1a1a;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 991px) {
    .product-image-gallery {
        position: relative;
        top: 0;
        margin-bottom: 30px;
    }
    
    .product-title-detail {
        font-size: 1.6rem;
    }
    
    .current-price {
        font-size: 2rem;
    }
    
    .main-image-container img {
        height: 350px;
    }
    
    .contact-buttons {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .product-detail-section {
        padding: 25px 0;
    }
    
    .product-info-card {
        padding: 20px;
    }
    
    .product-tabs {
        padding: 20px;
    }
    
    .modal-content {
        padding: 30px;
    }
}
</style>

<!-- Product Detail Section Start -->
<div class="product-detail-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" style="margin-bottom: 40px;">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>" class="text-success text-decoration-none fw-semibold"><i class="fas fa-home me-2"></i>Trang Chủ</a></li>
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>shop" class="text-success text-decoration-none fw-semibold">Cây Cảnh</a></li>
                <li class="breadcrumb-item active fw-semibold" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>
        
        <div class="row g-5">
            <!-- Product Images Gallery -->
            <div class="col-lg-6">
                <div class="product-image-gallery">
                    <!-- Main Image -->
                    <div class="main-image-container">
                        <?php if (!empty($images)): ?>
                        <img id="mainProductImage" src="<?php echo getImageUrl($images[0]['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php else: ?>
                        <img src="https://via.placeholder.com/500x500?text=No+Image" alt="No image">
                        <?php endif; ?>
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    <?php if (count($images) > 1): ?>
                    <div class="thumbnail-gallery">
                        <?php foreach ($images as $index => $img): ?>
                        <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeMainImage('<?php echo getImageUrl($img['image']); ?>', this)">
                            <img src="<?php echo getImageUrl($img['image']); ?>" alt="<?php echo htmlspecialchars($img['alt_text']); ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Information -->
            <div class="col-lg-6">
                <div class="product-info-card">
                    <!-- Product Title -->
                    <h1 class="product-title-detail">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h1>

                    <!-- Short Description -->
                    <p class="short-desc">
                        <?php echo htmlspecialchars($product['short_description']); ?>
                    </p>

                    <!-- Price Display -->
                    <div class="price-section">
                        <?php if ($product['discount_price']): ?>
                            <span class="current-price"><?php echo formatPrice($product['discount_price']); ?> VNĐ</span>
                            <span class="original-price"><?php echo formatPrice($product['price']); ?> VNĐ</span>
                            <span class="discount-badge">-<?php echo $discount_percent; ?>%</span>
                        <?php else: ?>
                            <span class="current-price"><?php echo formatPrice($product['price']); ?> VNĐ</span>
                        <?php endif; ?>
                    </div>

                    <!-- Buy Button -->
                    <button class="btn-contact" onclick="openContactModal()">
                        <i class="fas fa-shopping-bag me-2"></i>Mua Sản Phẩm
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="product-tabs">
            <h3 class="tab-title">
                <i class="fas fa-align-left me-2" style="color: #28a745;"></i>Mô Tả Chi Tiết
            </h3>
            <div class="description-content">
                <?php echo $product['description']; ?>
            </div>
        </div>

        <!-- Related Products -->
        <?php if ($related_query && $related_query->num_rows > 0): ?>
        <div class="related-products-section">
            <h3 class="section-title">Sản Phẩm Liên Quan</h3>
            <div class="row g-4">
                <?php
                while ($related = $related_query->fetch_assoc()) {
                    $rel_img_query = $conn->query("SELECT image FROM product_images WHERE product_id = " . $related['id'] . " AND is_featured = 1 LIMIT 1");
                    $rel_image = ($rel_img_query && $rel_img_query->num_rows > 0) ? $rel_img_query->fetch_assoc()['image'] : '';
                    $rel_discount_percent = 0;
                    if ($related['discount_price']) {
                        $rel_discount_percent = getDiscountPercentage($related['price'], $related['discount_price']);
                    }
                ?>
                <div class="col-md-6 col-lg-3">
                    <div class="product-card h-100">
                        <!-- Image Container -->
                        <div class="product-image-wrapper position-relative">
                            <img src="<?php echo getImageUrl($rel_image); ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($related['name']); ?>" style="height: 250px; object-fit: cover;">
                            
                            <!-- Category Badge -->
                            <div class="position-absolute" style="top: 12px; left: 12px;">
                                <span class="badge bg-success" style="font-size: 11px; padding: 6px 12px;">
                                    <?php echo htmlspecialchars($related['category_name']); ?>
                                </span>
                            </div>

                            <!-- Discount Badge -->
                            <?php if ($rel_discount_percent > 0): ?>
                            <div class="position-absolute" style="top: 12px; right: 12px;">
                                <span class="badge bg-danger" style="font-size: 12px; padding: 6px 12px; font-weight: bold;">
                                    -<?php echo $rel_discount_percent; ?>%
                                </span>
                            </div>
                            <?php endif; ?>

                            <!-- Overlay -->
                            <div class="product-overlay">
                                <a href="<?php echo BASE_URL; ?>shop-detail/<?php echo htmlspecialchars($related['slug']); ?>/" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-2"></i>Xem Chi Tiết
                                </a>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4 bg-white">
                            <h5 class="product-name mb-2">
                                <a href="<?php echo BASE_URL; ?>shop-detail/<?php echo htmlspecialchars($related['slug']); ?>/" class="text-dark text-decoration-none">
                                    <?php echo htmlspecialchars($related['name']); ?>
                                </a>
                            </h5>
                            
                            <p class="product-description text-muted mb-3" style="font-size: 14px;">
                                <?php echo htmlspecialchars(substr($related['short_description'], 0, 50)) . '...'; ?>
                            </p>

                            <!-- Price Section -->
                            <div style="margin-bottom: 12px;">
                                <?php 
                                if ($related['discount_price']) {
                                    ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="product-current-price">
                                            <?php echo formatPrice($related['discount_price']); ?> VNĐ
                                        </span>
                                        <span class="product-original-price">
                                            <?php echo formatPrice($related['price']); ?>
                                        </span>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <span class="product-current-price">
                                        <?php echo formatPrice($related['price']); ?> VNĐ
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
                                <small class="text-muted ms-2">(8)</small>
                            </div>

                            <!-- CTA Button -->
                            <a href="<?php echo BASE_URL; ?>shop-detail/<?php echo htmlspecialchars($related['slug']); ?>/" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-shopping-cart me-2"></i>Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<!-- Product Detail Section End -->

<!-- Contact Modal -->
<div class="modal-overlay" id="contactModal">
    <div class="modal-content">
        <button class="close-btn" onclick="closeContactModal()">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="modal-header">
            <h2>Liên Hệ Với Chúng Tôi</h2>
            <p>Để đặt mua sản phẩm này</p>
        </div>

        <div class="contact-info">
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span><strong>Địa Chỉ:</strong><br>123 Nguyễn Huệ, Quận 1, TP.HCM</span>
            </div>
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <span><strong>Điện Thoại:</strong><br>0123 456 789</span>
            </div>
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <span><strong>Email:</strong><br>contact@example.com</span>
            </div>
        </div>

        <p style="text-align: center; color: #6c757d; margin-bottom: 20px; font-size: 14px;">
            Liên hệ với chúng tôi qua các kênh dưới đây
        </p>

        <div class="contact-buttons">
            <a href="https://zalo.me/0123456789" target="_blank" class="contact-btn btn-zalo">
                <i class="fas fa-comment-dots"></i>
                <span>Zalo</span>
            </a>
            <a href="https://m.me/yourfacebookpage" target="_blank" class="contact-btn btn-facebook">
                <i class="fab fa-facebook-f"></i>
                <span>Facebook</span>
            </a>
            <a href="tel:0123456789" class="contact-btn btn-phone">
                <i class="fas fa-phone-alt"></i>
                <span>Gọi Ngay</span>
            </a>
        </div>
    </div>
</div>

<script>
function changeMainImage(imageSrc, thumbnailElement) {
    // Update main image
    document.getElementById('mainProductImage').src = imageSrc;
    
    // Update active state on thumbnails
    document.querySelectorAll('.thumbnail-item').forEach(item => {
        item.classList.remove('active');
    });
    thumbnailElement.classList.add('active');
}

// Contact Modal Functions
function openContactModal() {
    const modal = document.getElementById('contactModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeContactModal() {
    const modal = document.getElementById('contactModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('contactModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeContactModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeContactModal();
    }
});
</script>

<?php include 'template/footer.php'; ?>