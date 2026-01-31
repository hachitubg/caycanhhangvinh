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
                            <?php
                            $current_page = basename($_SERVER['PHP_SELF']);
                            $shop_active = (strpos($_SERVER['REQUEST_URI'], '/shop') !== false && $current_page !== 'index.php') ? 'active' : '';
                            $contact_active = (strpos($_SERVER['REQUEST_URI'], '/contact') !== false) ? 'active' : '';
                            $home_active = ($current_page === 'index.php' && strpos($_SERVER['REQUEST_URI'], '/shop') === false && strpos($_SERVER['REQUEST_URI'], '/contact') === false && strpos($_SERVER['REQUEST_URI'], '/shop-detail') === false) ? 'active' : '';
                            ?>
                            <a href="<?php echo BASE_URL; ?>" class="nav-item nav-link <?php echo $home_active; ?>">Trang chủ</a>
                            <a href="<?php echo BASE_URL; ?>shop" class="nav-item nav-link <?php echo $shop_active; ?>">Cửa hàng</a>
                            <a href="<?php echo BASE_URL; ?>contact" class="nav-item nav-link <?php echo $contact_active; ?>">Liên hệ</a>
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
                        <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
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


        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <?php
                // Get current page filename
                $current_page = basename($_SERVER['PHP_SELF']);
                
                // Define page titles and breadcrumbs
                $page_config = array(
                    'contact.php' => array(
                        'title' => 'Liên hệ',
                        'breadcrumb' => 'Liên hệ'
                    ),
                    'shop.php' => array(
                        'title' => 'Cửa hàng',
                        'breadcrumb' => 'Cửa hàng'
                    ),
                    'shop-detail.html' => array(
                        'title' => 'Chi tiết sản phẩm',
                        'breadcrumb' => 'Chi tiết sản phẩm'
                    ),
                    'shop-detail.php' => array(
                        'title' => 'Chi tiết sản phẩm',
                        'breadcrumb' => 'Chi tiết sản phẩm'
                    )
                );
                
                // Get config for current page, default to page filename if not found
                $page_title = isset($page_config[$current_page]) ? $page_config[$current_page]['title'] : ucfirst(str_replace(array('.php', '.html', '-'), array('', '', ' '), $current_page));
                $breadcrumb_text = isset($page_config[$current_page]) ? $page_config[$current_page]['breadcrumb'] : $page_title;
            ?>
            <h1 class="text-center text-white display-6"><?php echo $page_title; ?></h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
                <li class="breadcrumb-item active text-white"><?php echo $breadcrumb_text; ?></li>
            </ol>
        </div>
        <!-- Single Page Header End -->
        