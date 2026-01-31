    <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
            <div class="container py-5">
                <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <a href="<?php echo BASE_URL; ?>">
                                <h1 class="text-primary mb-0">Hằng Vinh</h1>
                                <p class="text-secondary mb-0">Cây cảnh Hằng Vinh</p>
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <div class="position-relative mx-auto">
                                <input class="form-control border-0 w-100 py-3 px-4 rounded-pill" type="number" placeholder="Your Email">
                                <button type="submit" class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white" style="top: 0; right: 0;">Subscribe Now</button>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex justify-content-end pt-3">
                                <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Thông tin liên hệ</h4>
                            <p>Địa chỉ: 300 Mỹ Đình, Hà Nội</p>
                            <p>Email: caycanhhangvinh@gmail.com</p>
                            <p>Số điện thoại: +0123 4567 8910</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->



        <!-- Contact Icons -->
        <div style="position: fixed; right: 20px; bottom: 20px; display: flex; flex-direction: column; gap: 10px; z-index: 9999;">
            <a href="https://zalo.me/" target="_blank" class="btn btn-primary border-3 border-primary rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold;" title="Zalo">
                Z
            </a>
            <a href="https://www.facebook.com/" target="_blank" class="btn btn-primary border-3 border-primary rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 24px;" title="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="tel:+0123456789" class="btn btn-primary border-3 border-primary rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 24px;" title="Gọi điện">
                <i class="fas fa-phone"></i>
            </a>
        </div>

        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 24px; position: fixed; left: 20px; bottom: 20px; z-index: 9999;"><i class="fa fa-arrow-up"></i></a>   

        <style>
            @media (max-width: 768px) {
                .btn-primary.border-3.border-primary.rounded-circle {
                    width: 40px !important;
                    height: 40px !important;
                    font-size: 18px !important;
                }
            }
        </style>   

        
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