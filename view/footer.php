<?php
require_once __DIR__ . '/../config/env.php';
?>
<footer class="text-white pt-5 pb-4" style="background-color: #2c3e50; font-family: 'Be Vietnam Pro', sans-serif;">
    <div class="container text-md-left">
        <div class="row text-md-left">
            <!-- Company Name & Slogan -->
            <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning" style="font-weight: 800;">NEKOPARA</h5>
                <p>Khám phá thế giới động vật tuyệt vời. Cùng nhau xây dựng cộng đồng yêu thiên nhiên, chia sẻ kiến thức và bảo vệ môi trường.</p>
                <div style="margin-top: 20px;">
                    <img src="<?= $base ?>/images/Footer/wwflogo.png" alt="WWF Logo" style="height: 60px; margin-right: 15px;">
                    <img src="<?= $base ?>/images/Footer/nekoparalogo.png" alt="Nekopara Logo" style="height: 60px;">
                </div>
            </div>

            <!-- Links -->
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning" style="font-weight: 800;">Khám Phá</h5>
                <p><a href="<?= $base ?>/Home" class="text-white" style="text-decoration: none;">Trang chủ</a></p>
                <p><a href="<?= $base ?>/ClassAnimal" class="text-white" style="text-decoration: none;">Lớp động vật</a></p>
                <p><a href="<?= $base ?>/FindAnimal" class="text-white" style="text-decoration: none;">Tìm kiếm bằng ảnh</a></p>
                <p><a href="<?= $base ?>/Posts" class="text-white" style="text-decoration: none;">Cộng đồng</a></p>
            </div>

            <!-- Contact -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning" style="font-weight: 800;">Liên Hệ</h5>
                <p>
                    <i class="fa-solid fa-envelope mr-3"></i> nekopara@gmail.com
                </p>
                <p>
                    <i class="fa-brands fa-facebook mr-3"></i> facebook.com/NEKOPARA
                </p>
            </div>
        </div>

        <hr class="mb-4" style="border-top: 1px solid rgba(255,255,255,0.2);">

        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p class="text-center text-md-start mb-0">
                    © 2026 All Rights Reserved | 
                    <strong class="text-warning">Nekopara.com</strong>
                </p>
            </div>
            <div class="col-md-5 col-lg-4">
                <div class="text-center text-md-end">
                    <ul class="list-unstyled list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="#" class="btn-floating btn-sm text-white" style="font-size: 23px;"><i class="fa-brands fa-facebook"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="btn-floating btn-sm text-white" style="font-size: 23px;"><i class="fa-brands fa-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="btn-floating btn-sm text-white" style="font-size: 23px;"><i class="fa-brands fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
