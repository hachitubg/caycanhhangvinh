-- =====================================================
-- DEMO DATA - Cây Cảnh Hằng Vinh
-- 20 Cây + 4 Loại Cây phổ biến ở Việt Nam
-- =====================================================

-- LOẠI CÂY CẢNH (4 CATEGORIES)
INSERT INTO `categories` (`name`, `slug`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
('Cây Lá Cảnh', 'cay-la-canh', 'Các loại cây có lá đẹp, giúp trang trí không gian sống. Dễ chăm sóc, thích hợp cho người mới bắt đầu.', 'demo-ca-la-canh.jpg', 1, NOW(), NOW()),
('Cây Hoa Cảnh', 'cay-hoa-canh', 'Cây có hoa đẹp, thơm, nhiều màu sắc. Tạo điểm nhấn cho không gian và mang lại sức sống.', 'demo-cay-hoa-canh.jpg', 1, NOW(), NOW()),
('Cây Xương Rồng & Succulents', 'cay-xuong-rong', 'Các loại cây sống được lâu, chịu khô hạn, ít cần nước. Dễ chăm sóc, tiết kiệm chi phí.', 'demo-xuong-rong.jpg', 1, NOW(), NOW()),
('Cây Lệch Thân', 'cay-lech-than', 'Cây có hình dáng độc đáo, thân gỗ đẹp. Tạo điểm nhấn tinh tế cho không gian nhà bạn.', 'demo-lech-than.jpg', 1, NOW(), NOW());

-- CÂY CẢI (20 PRODUCTS)
-- LOẠI 1: CÂY LÁ CẢNH (ID: 1)
INSERT INTO `products` (`category_id`, `name`, `slug`, `short_description`, `description`, `price`, `discount_price`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cây Trầu Bà Xanh', 'cay-trau-ba-xanh', 'Lá xanh mướt, thích hợp trồng trong nhà', 'Trầu Bà là cây lá cảnh cổ điển, dễ trồng, dung nạp với ánh sáng yếu. Lá xanh bóng, giúp trang trí và lọc không khí rất tốt. Thích hợp cho phòng khách, phòng ngủ, văn phòng.', 150000, 120000, 1, 1, NOW(), NOW()),
(1, 'Cây Philodendron', 'cay-philodendron', 'Lá hình trái tim, tăm tinh tế', 'Philodendron là cây lá cảnh dễ chăm sóc, phát triển nhanh. Có thể trồng leo tường hoặc để bàn. Lá hình trái tim, tươi xanh quanh năm. Ít cần nước, tiết kiệm thời gian chăm sóc.', 180000, 145000, 1, 1, NOW(), NOW()),
(1, 'Cây Cà Phê Lá Vàng', 'cay-ca-phe-la-vang', 'Lá vàng nổi bật, cây nhỏ gọn', 'Cây Cà Phê Lá Vàng là loại biến thể của cây cà phê thường. Lá có vệt vàng rất đẹp, tạo điểm nhấn cho không gian. Cây nhỏ gọn, thích hợp bàn làm việc, kệ cây.', 200000, 160000, 0, 1, NOW(), NOW()),
(1, 'Cây Dứa Lá Vàng', 'cay-dua-la-vang', 'Lá sọc vàng, thơi thớt độc đáo', 'Dứa Lá Vàng với những lá sọc vàng nổi bật. Cây nhỏ, thích hợp trồng chậu để bàn. Cần ánh sáng tốt, để nơi thoáng mát. Rất bắt mắt và tạo nét quyến rũ cho không gian.', 220000, 175000, 0, 1, NOW(), NOW()),
(1, 'Cây Cung Nguyệt Thảo', 'cay-cung-nguyet-thao', 'Lá hình cung nguyệt, lá tím đỏ', 'Cây Cung Nguyệt Thảo (Wandering Jew) có lá tím đỏ rất nổi bật khi để ánh sáng. Cây thích hợp để bàn, treo tường. Dễ trồng, sinh sôi nhanh, thích hợp cho cả người mới bắt đầu.', 120000, 100000, 0, 1, NOW(), NOW()),

-- LOẠI 2: CÂY HOA CẢNH (ID: 2)
(2, 'Cây Hoa Lan Hồ Điệp', 'cay-hoa-lan-ho-diep', 'Hoa màu tím, hoa nở liên tục', 'Lan Hồ Điệp là loài hoa cảnh cao cấp, hoa nở liên tục khoảng 2-3 tháng. Hoa màu tím, hồng, trắng rất đẹp. Thích hợp để trên bàn, kệ để làm quà tặng. Cần chăm sóc khéo léo về nước và ánh sáng.', 350000, 280000, 1, 1, NOW(), NOW()),
(2, 'Cây Hoa Hướng Dương Mini', 'cay-hoa-huong-duong-mini', 'Hoa vàng rực, tạo niềm vui', 'Hoa Hướng Dương Mini là cây hoa vàng rực, nở quanh năm. Cây nhỏ gọn, cây cảnh độc đáo, tạo không khí vui vẻ. Dễ chăm sóc, thích nước nhưng phải thoát nước tốt. Thích ánh sáng mạnh.', 160000, 130000, 1, 1, NOW(), NOW()),
(2, 'Cây Hoa Mã Đơn', 'cay-hoa-ma-don', 'Hoa to, đầy màu sắc, thơm nồng', 'Mã Đơn là cây hoa sang trọng, hoa to, đầy màu sắc. Nở vào mùa xuân, hoa thơm nồng rất nhất. Cây cần chăm sóc kỹ, để ánh sáng tốt, đất thoát nước. Rất phù hợp làm quà tặng.', 400000, 320000, 1, 1, NOW(), NOW()),
(2, 'Cây Cúc Tết', 'cay-cuc-tet', 'Hoa nhiều màu, nở dịp Tết Nguyên Đán', 'Cúc Tết là cây hoa cảnh truyền thống ở Việt Nam. Hoa nở vào dịp Tết, nhiều màu (vàng, hồng, trắng, tím). Cây dễ trồng, cần nước vừa, để nơi thoáng mát. Biểu tượng của mùa xuân.', 80000, 65000, 0, 1, NOW(), NOW()),
(2, 'Cây Hoa Hồng Nội', 'cay-hoa-hong-noi', 'Hoa hồng sang trọng, cực dễ chăm sóc', 'Hoa Hồng Nội được nuôi lại để thích hợp trồng trong nhà. Hoa đẹp như hoa hồng thật, dễ chăm sóc hơn. Có nhiều màu: đỏ, hồng, trắng. Cây cần ánh sáng tốt và thoát nước.', 200000, 160000, 0, 1, NOW(), NOW()),

-- LOẠI 3: CÂY XƯƠNG RỒNG & SUCCULENTS (ID: 3)
(3, 'Cây Xương Rồng Hình Nến', 'cay-xuong-rong-hinh-nen', 'Thân xanh mướt, hình lạ mắt', 'Xương Rồng Hình Nến (Cereus) có thân xanh, cây cao và gọn. Dễ trồng, ít cần nước, chịu nắng. Cây phát triển chậm nhưng bền bỉ rất lâu. Thích hợp trồng trong nhà, văn phòng.', 130000, 105000, 1, 1, NOW(), NOW()),
(3, 'Cây Xương Rồng Bóp', 'cay-xuong-rong-bop', 'Lá mịn, xanh, hình bóp thú vị', 'Xương Rồng Bóp là cây xương rồng nhỏ gọn, hình dáng thú vị như bóp. Cực dễ trồng, ít cần nước. Thích hợp trồng chậu để bàn, kệ. Rất bền bỉ, chỉ cần nước khoảng 1-2 tuần/lần.', 100000, 80000, 0, 1, NOW(), NOW()),
(3, 'Cây Succulent Echeveria', 'cay-succulent-echeveria', 'Lá tròn, màu hồng tím, rất dễ trồng', 'Echeveria là loài succulent phổ biến, lá tròn xếp thành hình bông hoa. Màu sắc đẹp (xanh, hồng, tím). Cực dễ trồng, ít cần nước, chịu nắng. Thích hợp cho người mới bắt đầu.', 70000, 55000, 0, 1, NOW(), NOW()),
(3, 'Cây Jade (Cau Xanh)', 'cay-jade-cau-xanh', 'Lá dày xanh, thân gỗ đẹp', 'Cây Jade (Cây Cau Xanh) có lá dày, xanh bóng. Thân phát triển thành gỗ rất đẹp theo thời gian. Cực dễ trồng, ít cần nước, chịu nắng tốt. Biểu tượng của sự giàu có, thịnh vượng.', 180000, 145000, 1, 1, NOW(), NOW()),
(3, 'Cây Aloe Vera', 'cay-aloe-vera', 'Gel chữa bỏng, ít cần chăm sóc', 'Aloe Vera là cây succulent dùng để chữa bỏng, sưng viêm. Cây dễ trồng, ít cần nước, chịu nắng. Lá dày chứa gel dịu mát rất tốt cho da. Thích hợp trồng trong phòng bếp.', 90000, 72000, 0, 1, NOW(), NOW()),

-- LOẠI 4: CÂY LỆCH THÂN (ID: 4)
(4, 'Cây Vạn Niên Thanh', 'cay-van-nien-thanh', 'Thân sơn cả, vẫn sống xanh tươi', 'Vạn Niên Thanh là cây lệch thân, thân có vẻ nhẵn đẹp. Cây dễ chăm sóc, lá xanh mướt quanh năm. Thân gỗ tạo nên vẻ tự nhiên, hoang dã. Thích hợp trồng trong nhà, tạo không gian xanh tươi.', 250000, 200000, 1, 1, NOW(), NOW()),
(4, 'Cây Điều Hỏa (Croton)', 'cay-dieu-hoa-croton', 'Lá nhiều màu: vàng, đỏ, cam', 'Điều Hỏa (Croton) là cây lệch thân lá rất đẹp với màu sắc rực rỡ: vàng, đỏ, cam. Cây cần ánh sáng tốt để lá nổi màu. Thích nước vừa, đất thoát nước tốt. Tạo điểm nhấn nổi bật.', 280000, 225000, 0, 1, NOW(), NOW()),
(4, 'Cây Phát Lộc (Dracaena)', 'cay-phat-loc-dracaena', 'Thân gỗ vàng, lá sọc vàng', 'Phát Lộc (Dracaena) là cây thân gỗ độc đáo, lá sọc vàng rất bắt mắt. Cây ứng dụng Phong Thủy, biểu tượng của may mắn. Dễ trồng, ít cần nước. Thích hợp đặt ở cửa hoặc góc phòng.', 320000, 255000, 0, 1, NOW(), NOW()),
(4, 'Cây Hạt Dẻ (Pachira)', 'cay-hat-de-pachira', 'Thân gỗ đẹp, lá năm lá', 'Hạt Dẻ (Pachira) là cây lệch thân được quấn thành tết rất đẹp. Lá năm lá theo phong thủy là biểu tượng của sự giàu có. Cây cần ánh sáng vừa, nước vừa. Rất phổ biến trong nhà hàng, văn phòng.', 350000, 280000, 0, 1, NOW(), NOW());

-- PRODUCT IMAGES (HÌNH ẢNH CHO CÁC SẢN PHẨM)
-- Cây Trầu Bà Xanh (Product ID: 1)
INSERT INTO `product_images` (`product_id`, `image`, `alt_text`, `is_featured`, `sort_order`, `created_at`) VALUES
(1, 'trau-ba-xanh-1.jpg', 'Trầu Bà Xanh - Ảnh chính', 1, 1, NOW()),
(1, 'trau-ba-xanh-2.jpg', 'Trầu Bà Xanh - Chi tiết', 0, 2, NOW()),

-- Cây Philodendron (Product ID: 2)
(2, 'philodendron-1.jpg', 'Philodendron - Ảnh chính', 1, 1, NOW()),
(2, 'philodendron-2.jpg', 'Philodendron - Chi tiết', 0, 2, NOW()),

-- Cây Cà Phê Lá Vàng (Product ID: 3)
(3, 'ca-phe-la-vang-1.jpg', 'Cà Phê Lá Vàng - Ảnh chính', 1, 1, NOW()),
(3, 'ca-phe-la-vang-2.jpg', 'Cà Phê Lá Vàng - Chi tiết', 0, 2, NOW()),

-- Cây Dứa Lá Vàng (Product ID: 4)
(4, 'dua-la-vang-1.jpg', 'Dứa Lá Vàng - Ảnh chính', 1, 1, NOW()),
(4, 'dua-la-vang-2.jpg', 'Dứa Lá Vàng - Chi tiết', 0, 2, NOW()),

-- Cây Cung Nguyệt Thảo (Product ID: 5)
(5, 'cung-nguyet-thao-1.jpg', 'Cung Nguyệt Thảo - Ảnh chính', 1, 1, NOW()),
(5, 'cung-nguyet-thao-2.jpg', 'Cung Nguyệt Thảo - Chi tiết', 0, 2, NOW()),

-- Cây Hoa Lan Hồ Điệp (Product ID: 6)
(6, 'lan-ho-diep-1.jpg', 'Lan Hồ Điệp - Ảnh chính', 1, 1, NOW()),
(6, 'lan-ho-diep-2.jpg', 'Lan Hồ Điệp - Chi tiết', 0, 2, NOW()),

-- Cây Hoa Hướng Dương Mini (Product ID: 7)
(7, 'huong-duong-mini-1.jpg', 'Hướng Dương Mini - Ảnh chính', 1, 1, NOW()),
(7, 'huong-duong-mini-2.jpg', 'Hướng Dương Mini - Chi tiết', 0, 2, NOW()),

-- Cây Hoa Mã Đơn (Product ID: 8)
(8, 'ma-don-1.jpg', 'Mã Đơn - Ảnh chính', 1, 1, NOW()),
(8, 'ma-don-2.jpg', 'Mã Đơn - Chi tiết', 0, 2, NOW()),

-- Cây Cúc Tết (Product ID: 9)
(9, 'cuc-tet-1.jpg', 'Cúc Tết - Ảnh chính', 1, 1, NOW()),
(9, 'cuc-tet-2.jpg', 'Cúc Tết - Chi tiết', 0, 2, NOW()),

-- Cây Hoa Hồng Nội (Product ID: 10)
(10, 'hong-noi-1.jpg', 'Hoa Hồng Nội - Ảnh chính', 1, 1, NOW()),
(10, 'hong-noi-2.jpg', 'Hoa Hồng Nội - Chi tiết', 0, 2, NOW()),

-- Cây Xương Rồng Hình Nến (Product ID: 11)
(11, 'xuong-rong-nen-1.jpg', 'Xương Rồng Hình Nến - Ảnh chính', 1, 1, NOW()),
(11, 'xuong-rong-nen-2.jpg', 'Xương Rồng Hình Nến - Chi tiết', 0, 2, NOW()),

-- Cây Xương Rồng Bóp (Product ID: 12)
(12, 'xuong-rong-bop-1.jpg', 'Xương Rồng Bóp - Ảnh chính', 1, 1, NOW()),
(12, 'xuong-rong-bop-2.jpg', 'Xương Rồng Bóp - Chi tiết', 0, 2, NOW()),

-- Cây Succulent Echeveria (Product ID: 13)
(13, 'echeveria-1.jpg', 'Echeveria - Ảnh chính', 1, 1, NOW()),
(13, 'echeveria-2.jpg', 'Echeveria - Chi tiết', 0, 2, NOW()),

-- Cây Jade (Product ID: 14)
(14, 'jade-1.jpg', 'Jade (Cau Xanh) - Ảnh chính', 1, 1, NOW()),
(14, 'jade-2.jpg', 'Jade (Cau Xanh) - Chi tiết', 0, 2, NOW()),

-- Cây Aloe Vera (Product ID: 15)
(15, 'aloe-vera-1.jpg', 'Aloe Vera - Ảnh chính', 1, 1, NOW()),
(15, 'aloe-vera-2.jpg', 'Aloe Vera - Chi tiết', 0, 2, NOW()),

-- Cây Vạn Niên Thanh (Product ID: 16)
(16, 'van-nien-thanh-1.jpg', 'Vạn Niên Thanh - Ảnh chính', 1, 1, NOW()),
(16, 'van-nien-thanh-2.jpg', 'Vạn Niên Thanh - Chi tiết', 0, 2, NOW()),

-- Cây Điều Hỏa (Product ID: 17)
(17, 'dieu-hoa-1.jpg', 'Điều Hỏa (Croton) - Ảnh chính', 1, 1, NOW()),
(17, 'dieu-hoa-2.jpg', 'Điều Hỏa (Croton) - Chi tiết', 0, 2, NOW()),

-- Cây Phát Lộc (Product ID: 18)
(18, 'phat-loc-1.jpg', 'Phát Lộc (Dracaena) - Ảnh chính', 1, 1, NOW()),
(18, 'phat-loc-2.jpg', 'Phát Lộc (Dracaena) - Chi tiết', 0, 2, NOW()),

-- Cây Hạt Dẻ (Product ID: 19)
(19, 'hat-de-1.jpg', 'Hạt Dẻ (Pachira) - Ảnh chính', 1, 1, NOW()),
(19, 'hat-de-2.jpg', 'Hạt Dẻ (Pachira) - Chi tiết', 0, 2, NOW());

-- =====================================================
-- TỔNG KẾT:
-- - 4 loại cây cảnh (categories)
-- - 20 cây cảnh (products)
-- - 40 hình ảnh (product_images - 2 ảnh/sản phẩm)
-- =====================================================
