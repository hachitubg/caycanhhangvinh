<?php
// Database connection should be included by the calling file
// This file uses the global $conn variable
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
                            <?php
                            // Get current page/route
                            $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                            $route = str_replace(BASE_PATH, '', $request_uri);
                            $route = trim($route, '/');
                            if (strpos($route, '?') !== false) {
                                $route = explode('?', $route)[0];
                            }
                            $route_parts = array_filter(explode('/', $route));
                            $first_part = isset($route_parts[0]) ? strtolower($route_parts[0]) : '';
                            
                            $home_active = empty($first_part) ? 'active' : '';
                            $shop_active = ($first_part === 'shop') ? 'active' : '';
                            $contact_active = ($first_part === 'contact') ? 'active' : '';
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


        <!-- Hero Start -->
        <div class="container-fluid py-5 mb-5 hero-header">
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-md-12 col-lg-7">
                        <h4 class="mb-3 text-secondary">Cây cảnh đẳng cấp</h4>
                        <h1 class="mb-5 display-3 text-primary">Cây cảnh Hằng Vinh</h1>
                    </div>
                    <div class="col-md-12 col-lg-5">
                        <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                            <div class="carousel-inner" role="listbox">
                                <?php
                                // Get carousel items from database
                                $carousel_query = "SELECT * FROM media WHERE type = 'carousel' AND status = 1 ORDER BY created_at DESC LIMIT 10";
                                $carousel_result = $conn->query($carousel_query);
                                $carousel_items = $carousel_result ? $carousel_result->fetch_all(MYSQLI_ASSOC) : [];
                                
                                if (count($carousel_items) > 0) {
                                    foreach ($carousel_items as $index => $item) {
                                        $active_class = $index === 0 ? 'active' : '';
                                        $image_url = BASE_URL . 'admin/uploads/' . htmlspecialchars($item['image']);
                                        $title = htmlspecialchars($item['title']);
                                        ?>
                                        <div class="carousel-item <?php echo $active_class; ?> rounded">
                                            <img src="<?php echo $image_url; ?>" class="img-fluid w-100 h-100 bg-secondary rounded" alt="<?php echo $title; ?>">
                                            <a href="#" class="btn px-4 py-2 text-white rounded"><?php echo $title; ?></a>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    // Fallback to default images if no carousel items in database
                                    ?>
                                    <div class="carousel-item active rounded">
                                        <img src="img/hero-img-1.png" class="img-fluid w-100 h-100 bg-secondary rounded" alt="First slide">
                                        <a href="#" class="btn px-4 py-2 text-white rounded">Cây Cảnh</a>
                                    </div>
                                    <div class="carousel-item rounded">
                                        <img src="img/hero-img-2.jpg" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                                        <a href="#" class="btn px-4 py-2 text-white rounded">Cây Trang Trí</a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero End -->
        