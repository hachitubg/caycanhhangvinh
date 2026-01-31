<?php 
include 'includes/config.php';
include 'template/header_other.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? sanitize($_POST['subject']) : '';
    $message_content = isset($_POST['message']) ? sanitize($_POST['message']) : '';
    
    // Validation
    $errors = [];
    if (empty($name)) $errors[] = 'Tên không được để trống';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ';
    if (empty($phone)) $errors[] = 'Số điện thoại không được để trống';
    if (empty($subject)) $errors[] = 'Tiêu đề không được để trống';
    if (empty($message_content)) $errors[] = 'Tin nhắn không được để trống';
    
    if (empty($errors)) {
        // Send email
        $to = 'caycanhhangvinh@gmail.com';
        $email_subject = 'Liên hệ từ: ' . $name;
        
        $email_body = "
        <html>
        <body>
            <h3>Liên hệ từ khách hàng</h3>
            <p><strong>Tên:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Số điện thoại:</strong> {$phone}</p>
            <p><strong>Tiêu đề:</strong> {$subject}</p>
            <p><strong>Nội dung:</strong></p>
            <p>" . nl2br($message_content) . "</p>
        </body>
        </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: " . $email . "\r\n";
        
        if (mail($to, $email_subject, $email_body, $headers)) {
            $message = 'Cảm ơn bạn! Chúng tôi sẽ liên hệ lại sớm nhất.';
            $message_type = 'success';
            // Clear form
            $_POST = array();
        } else {
            $message = 'Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại.';
            $message_type = 'danger';
        }
    } else {
        $message = 'Lỗi: ' . implode('<br>', $errors);
        $message_type = 'danger';
    }
}
?>

<!-- Contact Start -->
<div class="container-fluid contact py-5">
    <div class="container py-5">
        <div class="p-5 bg-light rounded">
            <div class="row g-4">
                <div class="col-12">
                    <div class="text-center mx-auto" style="max-width: 700px;">
                        <h1 class="text-primary">Liên Hệ Với Chúng Tôi</h1>
                        <p class="mb-4">Có câu hỏi hoặc cần tư vấn? Vui lòng điền form dưới đây. Chúng tôi sẽ phản hồi trong 24 giờ.</p>
                    </div>
                </div>

                <!-- Alert Message -->
                <?php if ($message): ?>
                <div class="col-12">
                    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php endif; ?>

                <div class="col-lg-12">
                    <div class="h-100 rounded">
                        <iframe class="rounded w-100" 
                        style="height: 400px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.3700408898247!2d105.77697!3d21.028426!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab1a8a1a1a1d%3A0x1a1a1a1a1a1a1a1a!2zM8OAwiBN4bEWIEzDom1o!5e0!3m2!1svi!2s!4v1694259649153" 
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="col-lg-7">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <input type="text" 
                                   name="name" 
                                   class="w-100 form-control border-0 py-3" 
                                   placeholder="Tên Của Bạn"
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                   required>
                        </div>
                        <div class="mb-3">
                            <input type="email" 
                                   name="email" 
                                   class="w-100 form-control border-0 py-3" 
                                   placeholder="Email Của Bạn"
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                   required>
                        </div>
                        <div class="mb-3">
                            <input type="tel" 
                                   name="phone" 
                                   class="w-100 form-control border-0 py-3" 
                                   placeholder="Số Điện Thoại"
                                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                   required>
                        </div>
                        <div class="mb-3">
                            <input type="text" 
                                   name="subject" 
                                   class="w-100 form-control border-0 py-3" 
                                   placeholder="Tiêu Đề"
                                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"
                                   required>
                        </div>
                        <div class="mb-3">
                            <textarea name="message" 
                                      class="w-100 form-control border-0 mb-4" 
                                      rows="5" 
                                      cols="10" 
                                      placeholder="Nội Dung Tin Nhắn"
                                      required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        <button type="submit" class="w-100 btn btn-primary py-3">Gửi Tin Nhắn</button>
                    </form>
                </div>

                <div class="col-lg-5">
                    <div class="d-flex p-4 rounded mb-4 bg-white">
                        <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                        <div>
                            <h4>Địa Chỉ</h4>
                            <p class="mb-2">300 Mỹ Đình, Hà Nội, Việt Nam</p>
                        </div>
                    </div>
                    <div class="d-flex p-4 rounded mb-4 bg-white">
                        <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                        <div>
                            <h4>Email</h4>
                            <p class="mb-2">
                                <a href="mailto:caycanhhangvinh@gmail.com" class="text-decoration-none">
                                    caycanhhangvinh@gmail.com
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex p-4 rounded bg-white">
                        <i class="fa fa-phone-alt fa-2x text-primary me-4"></i>
                        <div>
                            <h4>Số Điện Thoại</h4>
                            <p class="mb-2">
                                <a href="tel:+84123456789" class="text-decoration-none">
                                    +84 (0)123 456 789
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->

<?php include 'template/footer.php'; ?>