<?php
// Include config to get BASE_URL
include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Hằng Vinh - Cây cảnh Hằng Vinh</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>img/hangvinh_icon.png">
        <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>img/hangvinh_icon.png">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="<?php echo BASE_URL; ?>lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="<?php echo BASE_URL; ?>lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="<?php echo BASE_URL; ?>css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="<?php echo BASE_URL; ?>css/style.css" rel="stylesheet">

        <!-- Custom Product Card Styling -->
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/header.css">

    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar start -->
        <div class="container-fluid fixed-top">
            <div class="container topbar bg-primary d-none d-lg-block">
                <div class="d-flex justify-content-between">
                    <div class="top-info ps-2">
                        <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">300 Mỹ Đình, Hà Nội</a></small>
                        <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">caycanhhangvinh@gmail.com</a></small>
                    </div>
                    <div class="top-link pe-2">
                        <a href="#" class="text-white"><small class="text-white mx-2">Zalo</small>/</a>
                        <a href="#" class="text-white"><small class="text-white mx-2">Facebook</small>/</a>
                        <a href="#" class="text-white"><small class="text-white ms-2">Số điện thoại</small></a>
                    </div>
                </div>
            </div>
            <div class="container px-0">
                <nav class="navbar navbar-light bg-white navbar-expand-xl">
                    <a href="<?php echo BASE_URL; ?>" class="navbar-brand d-flex align-items-center">
                        <img src="<?php echo BASE_URL; ?>img/hangvinh_icon.png" alt="Hằng Vinh" style="height: 60px; margin-right: 15px;">
                        <h1 class="text-primary display-6 mb-0">Hằng Vinh</h1>
                    </a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                        <div class="navbar-nav mx-auto">
                            <a href="<?php echo BASE_URL; ?>" class="nav-item nav-link">Trang chủ</a>
                            <a href="<?php echo BASE_URL; ?>shop" class="nav-item nav-link">Cửa hàng</a>
                            <a href="<?php echo BASE_URL; ?>contact" class="nav-item nav-link">Liên hệ</a>
                        </div>
                        <div class="d-flex m-3 me-0">
                            <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->

        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tìm kiếm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->

        <!-- Breadcrumb Start -->
        <div class="container-fluid bg-light py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row gy-3 gx-4 align-items-center">
                            <div class="col-md-6">
                                <h1 class="mb-0">404 - Trang Không Tìm Thấy</h1>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <a href="<?php echo BASE_URL; ?>" class="btn btn-primary px-4 py-2">
                                    <i class="fa fa-home me-2"></i> Quay Về Trang Chủ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Breadcrumb End -->

        <!-- 404 Section Start -->
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="mb-4">
                        <h2 class="display-1 fw-bold text-danger mb-3">404</h2>
                        <h3 class="h2 fw-semibold mb-3">Trang Không Tìm Thấy</h3>
                        <p class="lead text-muted mb-4">
                            Xin lỗi, trang bạn tìm kiếm không tồn tại hoặc đã bị xóa. 
                            Vui lòng kiểm tra lại URL hoặc quay về trang chủ.
                        </p>
                    </div>
                    <div class="mb-5">
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-home me-2"></i> Trang Chủ
                        </a>
                        <a href="<?php echo BASE_URL; ?>shop" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i> Cửa Hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- 404 Section End -->

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
            <div class="container py-5">
                <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <a href="<?php echo BASE_URL; ?>">
                                <h1 class="text-primary mb-0">Hằng Vinh</h1>
                                <p class="text-secondary">Cây Cảnh Hằng Vinh</p>
                            </a>
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-primary me-3" style="font-size: 32px;"></i>
                                <div>
                                    <p class="mb-2">Địa chỉ</p>
                                    <h6 class="text-white mb-0">300 Mỹ Đình, Hà Nội</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-primary me-3" style="font-size: 32px;"></i>
                                <div>
                                    <p class="mb-2">Email</p>
                                    <h6 class="text-white mb-0">caycanhhangvinh@gmail.com</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone-alt text-primary me-3" style="font-size: 32px;"></i>
                                <div>
                                    <p class="mb-2">Số điện thoại</p>
                                    <h6 class="text-white mb-0">+84 xxx xxx xxx</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Về Chúng Tôi</h5>
                        <a class="btn btn-link text-white-50" href="#"><i class="fas fa-chevron-right me-2"></i>Về Hằng Vinh</a>
                        <a class="btn btn-link text-white-50" href="#"><i class="fas fa-chevron-right me-2"></i>Liên Hệ Chúng Tôi</a>
                    </div>
                </div>
            </div>
            <div class="container-fluid copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-white"><a href="#"><i class="fas fa-copyright text-primary me-2"></i>Hằng Vinh</a>, 2024. All right reserved.</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up text-white"></i></a>

        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo BASE_URL; ?>lib/easing/easing.min.js"></script>
        <script src="<?php echo BASE_URL; ?>lib/waypoints/waypoints.min.js"></script>
        <script src="<?php echo BASE_URL; ?>lib/lightbox/js/lightbox.min.js"></script>
        <script src="<?php echo BASE_URL; ?>lib/owlcarousel/owl.carousel.min.js"></script>

        <!-- Template Javascript -->
        <script src="<?php echo BASE_URL; ?>js/main.js"></script>

    </body>

</html>
