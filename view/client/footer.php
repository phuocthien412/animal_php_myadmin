<?php
require_once __DIR__ . '/../../config/env.php';
?>
<!-- Client Footer Styles -->
<link rel="stylesheet" href="<?= $base ?>/css/client/footer.css">

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
                    <a href="mailto:minhngothien1@gmail.com?subject=Soan%20thu" class="footer-link footer-contact-text" style="color:inherit; text-decoration:none;">minhngothien1@gmail.com</a>
                </p>
                <p class="footer-contact-item d-flex align-items-center">
                    <i class="fa-brands fa-facebook me-3" style="color: #7f00ff; font-size: 1.1rem;"></i>
                    <a href="https://www.facebook.com/thien.ngo.256980" target="_blank" rel="noopener noreferrer" class="footer-link footer-contact-text" style="color:inherit; text-decoration:none;">facebook.com/Thien Ngo</a>
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
        </div>
    </div>
</footer>

<script>
// --- Reusable Draggable Class (Can make any fixed/absolute positioned element draggable) ---
class Draggable {
    constructor(element, options = {}) {
        if (!element) return;
        this.element = element;
        this.options = Object.assign({
            onDragStart: null,
            onDrag: null,
            onDragEnd: null,
            boundary: true // Keep element within window boundaries
        }, options);

        this.isDragging = false;
        this.hasDragged = false;
        this.startX = 0;
        this.startY = 0;
        this.initialX = 0;
        this.initialY = 0;

        this.init();
    }

    init() {
        // Apply default drag styles
        this.element.style.cursor = 'grab';
        this.element.style.userSelect = 'none';
        this.element.style.webkitUserSelect = 'none';
        
        // Prevent native HTML5 image dragging for internal images
        const imgs = this.element.querySelectorAll('img');
        imgs.forEach(img => {
            img.setAttribute('draggable', 'false');
            img.style.userSelect = 'none';
            img.style.webkitUserSelect = 'none';
        });

        // Event listener bindings
        this.dragStartHandler = this.dragStart.bind(this);
        this.dragHandler = this.drag.bind(this);
        this.dragEndHandler = this.dragEnd.bind(this);

        this.element.addEventListener('mousedown', this.dragStartHandler);
        this.element.addEventListener('touchstart', this.dragStartHandler, { passive: true });

        // Intercept click event if a drag operation occurred
        this.element.addEventListener('click', (e) => {
            if (this.hasDragged) {
                e.stopPropagation();
                e.preventDefault();
            }
        }, true);
    }

    dragStart(e) {
        // Ignore dragging if clicking a link or button inside the widget
        if (e.target.tagName.toLowerCase() === 'a' || e.target.closest('a') || e.target.tagName.toLowerCase() === 'button' || e.target.closest('button')) {
            return;
        }

        if (e.type === 'touchstart') {
            this.startX = e.touches[0].clientX;
            this.startY = e.touches[0].clientY;
        } else {
            this.startX = e.clientX;
            this.startY = e.clientY;
            e.preventDefault(); // Prevents cursor text-selection side-effects
        }

        const rect = this.element.getBoundingClientRect();
        this.initialX = rect.left;
        this.initialY = rect.top;

        this.isDragging = true;
        this.hasDragged = false;
        this.element.style.cursor = 'grabbing';
        this.element.style.transition = 'none'; // Disable transition during drag

        // Force fixed positioning to make dragging responsive and viewport-relative
        this.element.style.right = 'auto';
        this.element.style.bottom = 'auto';
        this.element.style.left = this.initialX + 'px';
        this.element.style.top = this.initialY + 'px';

        this.mousemoveBound = this.drag.bind(this);
        this.mouseupBound = this.dragEnd.bind(this);

        document.addEventListener('mousemove', this.mousemoveBound, { passive: false });
        document.addEventListener('mouseup', this.mouseupBound);
        document.addEventListener('touchmove', this.mousemoveBound, { passive: false });
        document.addEventListener('touchend', this.mouseupBound);

        if (this.options.onDragStart) this.options.onDragStart(this.element);
    }

    drag(e) {
        if (!this.isDragging) return;

        let currentX, currentY;
        if (e.type === 'touchmove') {
            currentX = e.touches[0].clientX;
            currentY = e.touches[0].clientY;
        } else {
            currentX = e.clientX;
            currentY = e.clientY;
        }

        const dx = currentX - this.startX;
        const dy = currentY - this.startY;

        // Set threshold to distinguish drag from clicks
        if (Math.abs(dx) > 5 || Math.abs(dy) > 5) {
            this.hasDragged = true;
            // Prevent default behavior to avoid scrolling on mobile during drag
            if (e.cancelable) e.preventDefault();
        }

        let newX = this.initialX + dx;
        let newY = this.initialY + dy;

        if (this.options.boundary) {
            const maxX = window.innerWidth - this.element.offsetWidth;
            const maxY = window.innerHeight - this.element.offsetHeight;
            newX = Math.max(0, Math.min(newX, maxX));
            newY = Math.max(0, Math.min(newY, maxY));
        }

        this.element.style.left = newX + 'px';
        this.element.style.top = newY + 'px';

        if (this.options.onDrag) this.options.onDrag(this.element, newX, newY);
    }

    dragEnd() {
        if (!this.isDragging) return;
        this.isDragging = false;
        this.element.style.cursor = 'grab';
        this.element.style.transition = 'transform 0.2s ease';

        document.removeEventListener('mousemove', this.mousemoveBound);
        document.removeEventListener('mouseup', this.mouseupBound);
        document.removeEventListener('touchmove', this.mousemoveBound);
        document.removeEventListener('touchend', this.mouseupBound);

        if (this.options.onDragEnd) this.options.onDragEnd(this.element);
    }
}

// Global helper function to make any element draggable
window.makeDraggable = function(element, options) {
    return new Draggable(element, options);
};

// Initialize draggable helper assistant widget on load
document.addEventListener('DOMContentLoaded', () => {
    const assistantBtn = document.getElementById('startIntro');
    if (assistantBtn) {
        window.makeDraggable(assistantBtn);
    }
});
</script>
