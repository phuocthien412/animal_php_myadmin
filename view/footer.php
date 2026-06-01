<?php
require_once __DIR__ . '/../config/env.php';
?>
<!-- Premium Senior Dev Custom CSS Style for Footer -->
<style>
    .premium-footer {
        background: radial-gradient(circle at 50% 0%, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.98) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        font-family: 'Be Vietnam Pro', sans-serif;
        position: relative;
        overflow: hidden;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Animated RGB moving top border */
    .rgb-border {
        position: absolute;
        top: 0;
        left: 0;
        height: 3px;
        width: 100%;
        background: linear-gradient(90deg, #ff007f, #7f00ff, #00f0ff, #00ff7f, #ff007f);
        background-size: 300% 100%;
        animation: rgbGlow 6s linear infinite;
        z-index: 10;
        box-shadow: 0 0 12px rgba(127, 0, 255, 0.6);
    }

    @keyframes rgbGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    /* Subtle background grid pattern */
    .footer-grid-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: linear-gradient(rgba(255, 255, 255, 0.006) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255, 255, 255, 0.006) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }

    /* Premium Logo Hover Lift */
    .footer-logo {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.4));
    }
    .footer-logo:hover {
        transform: translateY(-4px) scale(1.06);
        filter: drop-shadow(0 8px 20px rgba(0, 240, 255, 0.5));
    }

    /* Advanced Link Underline Animation */
    .footer-link {
        color: rgba(241, 245, 249, 0.75) !important;
        text-decoration: none !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        display: inline-block;
        padding-bottom: 2px;
    }

    .footer-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 0;
        background: linear-gradient(90deg, #00f0ff, #7f00ff);
        transition: width 0.3s ease;
    }

    .footer-link:hover {
        color: #ffffff !important;
        text-shadow: 0 0 8px rgba(0, 240, 255, 0.3);
        transform: translateX(4px);
    }

    .footer-link:hover::after {
        width: 100%;
    }

    /* Contact Details Hover Text Glow */
    .footer-contact-item {
        color: rgba(241, 245, 249, 0.85);
        transition: all 0.3s ease;
    }
    .footer-contact-item:hover {
        color: #00f0ff;
        text-shadow: 0 0 8px rgba(0, 240, 255, 0.3);
    }

    /* 3D Social Media Buttons */
    .social-icon-btn {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(241, 245, 249, 0.75) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .social-icon-btn:hover {
        background: linear-gradient(135deg, #00f0ff, #7f00ff);
        border-color: transparent;
        color: #ffffff !important;
        transform: translateY(-6px) scale(1.12);
        box-shadow: 0 8px 20px rgba(127, 0, 255, 0.45);
    }

    /* Glowing Text Badge */
    .glowing-brand {
        background: linear-gradient(90deg, #00f0ff, #7f00ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 900;
        letter-spacing: 1px;
        text-shadow: 0 0 20px rgba(0, 240, 255, 0.1);
    }
</style>

<footer class="premium-footer text-white pt-5 pb-4">
    <!-- RGB border top -->
    <div class="rgb-border"></div>
    <!-- Grid Overlay -->
    <div class="footer-grid-overlay"></div>

    <div class="container text-md-left position-relative" style="z-index: 2;">
        <div class="row text-md-left">
            <!-- Company Name & Slogan -->
            <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                <h4 class="text-uppercase mb-4 glowing-brand" style="font-weight: 800;">NEKOPARA</h4>
                <p class="text-secondary" style="font-size: 0.95rem; line-height: 1.6; color: rgba(241, 245, 249, 0.7) !important;">
                    <?= __('footer_slogan') ?>
                </p>
                <div class="d-flex align-items-center mt-4">
                    <img src="<?= $base ?>/images/Footer/wwflogo.png" alt="WWF Logo" class="footer-logo" style="height: 60px; margin-right: 20px;">
                    <img src="<?= $base ?>/images/Footer/nekoparalogo.png" alt="Nekopara Logo" class="footer-logo" style="height: 60px;">
                </div>
            </div>

            <!-- Links -->
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold" style="font-weight: 800; color: #00f0ff; letter-spacing: 0.5px;">
                    <?= __('footer_explore') ?>
                </h5>
                <p><a href="<?= $base ?>/Home" class="footer-link"><?= __('home') ?></a></p>
                <p><a href="<?= $base ?>/ClassAnimal" class="footer-link"><?= __('animal_classes') ?></a></p>
                <p><a href="<?= $base ?>/FindAnimal" class="footer-link"><?= __('image_search') ?></a></p>
                <p><a href="<?= $base ?>/Posts" class="footer-link"><?= __('community') ?></a></p>
            </div>

            <!-- Contact -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold" style="font-weight: 800; color: #7f00ff; letter-spacing: 0.5px;">
                    <?= __('footer_contact') ?>
                </h5>
                <p class="footer-contact-item d-flex align-items-center mb-3">
                    <i class="fa-solid fa-envelope me-3" style="color: #00f0ff; font-size: 1.1rem;"></i>
                    <span>nekopara@gmail.com</span>
                </p>
                <p class="footer-contact-item d-flex align-items-center">
                    <i class="fa-brands fa-facebook me-3" style="color: #7f00ff; font-size: 1.1rem;"></i>
                    <span>facebook.com/NEKOPARA</span>
                </p>
            </div>
        </div>

        <hr class="my-4" style="border-top: 1px solid rgba(255,255,255,0.08);">

        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p class="text-center text-md-start mb-0 text-secondary" style="font-size: 0.9rem;">
                    © 2026 <?= __('footer_rights') ?> | 
                    <a href="<?= $base ?>/Home" class="text-decoration-none" style="background: linear-gradient(90deg, #00f0ff, #7f00ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;">Nekopara.com</a>
                </p>
            </div>
            <div class="col-md-5 col-lg-4">
                <div class="text-center text-md-end mt-3 mt-md-0">
                    <ul class="list-unstyled list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="#" class="social-icon-btn"><i class="fa-brands fa-facebook-f"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="social-icon-btn"><i class="fa-brands fa-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="social-icon-btn"><i class="fa-brands fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
