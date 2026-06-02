</main><!-- end .admin-main -->

<!-- Reusable Lightbox Modal for Zooming Images/Videos -->
<div id="globalLightbox" class="lightbox-modal" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightboxImg">
    <video class="lightbox-content" id="lightboxVideo" controls muted playsinline style="display:none;"></video>
    <div id="lightboxCaption" class="lightbox-caption"></div>
</div>

<!-- Reusable Custom Confirmation Modal -->
<div id="globalConfirmModal" class="confirm-modal">
    <div class="confirm-modal-content">
        <div class="confirm-modal-header">
            <div class="confirm-modal-icon" id="confirmModalIcon">
                <i class="fa-solid fa-circle-question text-primary"></i>
            </div>
            <h5 class="confirm-modal-title" id="confirmModalTitle"><?= __('admin_confirm_action') ?? 'Xác nhận hành động' ?></h5>
        </div>
        <p class="confirm-modal-message" id="confirmModalMessage"><?= __('admin_confirm_default_msg') ?? 'Bạn có chắc chắn muốn thực hiện hành động này không?' ?></p>
        <div class="confirm-modal-footer">
            <button type="button" class="btn btn-cancel" id="confirmModalCancelBtn"><?= __('btn_cancel') ?? 'Hủy' ?></button>
            <button type="button" class="btn btn-action" id="confirmModalConfirmBtn"><?= __('btn_agree') ?? 'Đồng ý' ?></button>
        </div>
    </div>
</div>

<!-- Global Toast Container -->
<div id="globalToastContainer" class="shadcn-toast-container"></div>

<style>
/* Shadcn Toast CSS */
.shadcn-toast-container {
  position: fixed;
  top: 5rem;
  right: 1.5rem;
  z-index: 10001;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  pointer-events: none;
}
.shadcn-toast {
  pointer-events: auto;
  position: relative;
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  padding: 1rem 1.25rem;
  min-width: 300px;
  max-width: 420px;
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  overflow: hidden;
  animation: slideInToast 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.shadcn-toast.toast-exiting {
  animation: fadeOutToast 0.3s ease-out forwards;
}
@keyframes slideInToast {
  from { transform: translateX(100%); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}
@keyframes fadeOutToast {
  from { transform: translateX(0); opacity: 1; }
  to { transform: translateX(100%); opacity: 0; }
}
.shadcn-toast-icon {
  font-size: 1.125rem;
  flex-shrink: 0;
  margin-top: 0.125rem;
}
.shadcn-toast-content {
  flex-grow: 1;
}
.shadcn-toast-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #0f172a;
  margin-bottom: 0.25rem;
}
.shadcn-toast-message {
  font-size: 0.875rem;
  color: #64748b;
  margin: 0;
  line-height: 1.4;
}
.shadcn-toast-close {
  background: transparent;
  border: none;
  color: #94a3b8;
  cursor: pointer;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.15s;
}
.shadcn-toast-close:hover {
  color: #0f172a;
}
.shadcn-toast-progress-bg {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  width: 100%;
  background: #f1f5f9;
}
.shadcn-toast-progress-bar {
  height: 100%;
  width: 100%;
  transform-origin: left;
  background: #0f172a;
}
.shadcn-toast.success .shadcn-toast-progress-bar {
  background: #10b981;
}
.shadcn-toast.success .shadcn-toast-icon {
  color: #10b981;
}
.shadcn-toast.danger .shadcn-toast-progress-bar {
  background: #ef4444;
}
.shadcn-toast.danger .shadcn-toast-icon {
  color: #ef4444;
}
.shadcn-toast.warning .shadcn-toast-progress-bar {
  background: #f59e0b;
}
.shadcn-toast.warning .shadcn-toast-icon {
  color: #f59e0b;
}

.lightbox-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  padding-top: 50px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(15, 23, 42, 0.9);
  backdrop-filter: blur(10px);
}
.lightbox-content {
  margin: auto;
  display: block;
  max-width: 85%;
  max-height: 80vh;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
  animation-name: zoomIn;
  animation-duration: 0.3s;
}
@keyframes zoomIn {
  from {transform: scale(0.85); opacity: 0;}
  to {transform: scale(1); opacity: 1;}
}
.lightbox-close {
  position: absolute;
  top: 20px;
  right: 35px;
  color: #fff;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
  cursor: pointer;
  z-index: 10000;
}
.lightbox-close:hover {
  color: var(--accent-teal);
}
.lightbox-caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 15px 0;
  font-size: 14px;
}

/* Custom Confirm Modal styling (Shadcn UI style) */
.confirm-modal {
  display: none;
  position: fixed;
  z-index: 10000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(4px);
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.15s ease-out;
}
.confirm-modal.is-open {
  display: flex;
}
.confirm-modal-content {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  width: 90%;
  max-width: 450px;
  padding: 1.5rem;
  text-align: left;
  transform: scale(0.95);
  animation: zoomInConfirm 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes zoomInConfirm {
  to { transform: scale(1); }
}
.confirm-modal-header {
  display: flex;
  align-items: center;
  margin-bottom: 0.5rem;
}
.confirm-modal-icon {
  font-size: 20px;
  margin-right: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.confirm-modal-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0;
}
.confirm-modal-message {
  font-size: 0.875rem;
  color: #64748b;
  margin-bottom: 1.5rem;
  line-height: 1.5;
  padding-left: 32px; /* indent to align with text if icon is present */
}
.confirm-modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}
.confirm-modal-footer .btn {
  font-size: 0.875rem;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  transition: all 0.15s;
}
.confirm-modal-footer .btn-cancel {
  background: transparent;
  color: #0f172a;
  border: 1px solid #e2e8f0;
}
.confirm-modal-footer .btn-cancel:hover {
  background: #f1f5f9;
}
.confirm-modal-footer .btn-action {
  border: none;
}
</style>

<script>
// --- Global File Upload Preview Function (shared across all admin pages) ---
function validateAndPreviewFile(input, previewContainerId, isMultiple = false) {
    const maxBytes = 10 * 1024 * 1024; // 10MB
    const container = document.getElementById(previewContainerId);
    if (!container) return;
    if (!input.files || input.files.length === 0) return;
    if (!isMultiple) container.innerHTML = '';

    Array.from(input.files).forEach(file => {
        if (file.size > maxBytes) {
            alert(`Tệp "${file.name}" vượt quá giới hạn 10MB (thực tế: ${(file.size/(1024*1024)).toFixed(2)}MB). Vui lòng chọn tệp nhỏ hơn.`);
            input.value = '';
            if (!isMultiple) container.innerHTML = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const card = document.createElement('div');
            card.className = 'upload-preview-card';
            Object.assign(card.style, {
                position:'relative', width:'120px', height:'120px',
                borderRadius:'10px', border:'1px solid #cbd5e1',
                overflow:'hidden', boxShadow:'0 4px 10px rgba(0,0,0,0.08)',
                background:'#fff', display:'inline-block',
                marginRight:'12px', marginTop:'8px', verticalAlign:'top'
            });
            let mediaEl;
            if (file.type.startsWith('video/')) {
                mediaEl = document.createElement('video');
                mediaEl.src = e.target.result;
                mediaEl.muted = true; mediaEl.playsInline = true;
                mediaEl.autoplay = false; mediaEl.controls = true;
            } else {
                mediaEl = document.createElement('img');
                mediaEl.src = e.target.result;
                mediaEl.alt = file.name;
            }
            Object.assign(mediaEl.style, {width:'100%', height:'100%', objectFit:'cover'});
            const removeBtn = document.createElement('button');
            removeBtn.className = 'remove-btn';
            removeBtn.type = 'button';
            removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            Object.assign(removeBtn.style, {
                position:'absolute', top:'6px', right:'6px',
                width:'22px', height:'22px', borderRadius:'50%',
                background:'rgba(15,23,42,0.85)', color:'#fff',
                border:'none', cursor:'pointer', display:'flex',
                alignItems:'center', justifyContent:'center',
                fontSize:'10px', zIndex:'10'
            });
            removeBtn.onclick = function() {
                card.remove();
                if (!isMultiple) {
                    input.value = '';
                } else {
                    const dt = new DataTransfer();
                    Array.from(input.files).forEach(f => { if (f !== file) dt.items.add(f); });
                    input.files = dt.files;
                }
            };
            const nameBar = document.createElement('div');
            nameBar.className = 'file-name';
            nameBar.textContent = file.name;
            Object.assign(nameBar.style, {
                position:'absolute', bottom:'0', left:'0', right:'0',
                background:'rgba(15,23,42,0.85)', color:'#fff',
                fontSize:'9.5px', padding:'3px 6px',
                whiteSpace:'nowrap', overflow:'hidden', textOverflow:'ellipsis'
            });
            card.appendChild(mediaEl);
            card.appendChild(removeBtn);
            card.appendChild(nameBar);
            container.appendChild(card);
        };
        reader.readAsDataURL(file);
    });
}

// --- Global Image Zoom Lightbox Logic ---
document.addEventListener('click', function(e) {
    const target = e.target;
    if (target.tagName === 'IMG' || target.tagName === 'VIDEO') {
        // Exclude sidebar, topbar and video controls clicks
        if (target.closest('.admin-sidebar') || target.closest('.admin-topbar') || target.classList.contains('no-zoom')) {
            return;
        }
        // Exclude specific elements like custom buttons or icons
        if (target.closest('.remove-btn') || target.closest('.dropdown-menu')) return;
        
        openLightbox(target);
    }
});

function openLightbox(element) {
    const lightbox = document.getElementById('globalLightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxVideo = document.getElementById('lightboxVideo');
    const lightboxCaption = document.getElementById('lightboxCaption');
    
    if (element.tagName === 'VIDEO') {
        lightboxImg.style.display = 'none';
        lightboxVideo.style.display = 'block';
        lightboxVideo.src = element.src;
        lightboxVideo.play();
    } else {
        lightboxVideo.style.display = 'none';
        lightboxImg.style.display = 'block';
        lightboxImg.src = element.src;
    }
    
    lightboxCaption.textContent = element.alt || element.getAttribute('data-caption') || '';
    lightbox.style.display = 'block';
}

function closeLightbox() {
    const lightbox = document.getElementById('globalLightbox');
    const lightboxVideo = document.getElementById('lightboxVideo');
    lightboxVideo.pause();
    lightboxVideo.src = '';
    lightbox.style.display = 'none';
}

// --- Global Shadcn Toast Logic ---
window.showToast = function(message, type = 'success') {
    const container = document.getElementById('globalToastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `shadcn-toast ${type}`;
    
    let iconClass = 'fa-circle-check';
    let title = '<?= __('toast_success') ?? 'Thành công' ?>';
    
    if (type === 'danger') {
        iconClass = 'fa-triangle-exclamation';
        title = '<?= __('toast_error') ?? 'Lỗi' ?>';
    } else if (type === 'warning') {
        iconClass = 'fa-circle-exclamation';
        title = '<?= __('toast_warning') ?? 'Cảnh báo' ?>';
    }
    
    toast.innerHTML = `
        <div class="shadcn-toast-icon">
            <i class="fa-solid ${iconClass}"></i>
        </div>
        <div class="shadcn-toast-content">
            <div class="shadcn-toast-title">${title}</div>
            <p class="shadcn-toast-message">${message}</p>
        </div>
        <button class="shadcn-toast-close" type="button">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="shadcn-toast-progress-bg">
            <div class="shadcn-toast-progress-bar"></div>
        </div>
    `;
    
    container.appendChild(toast);
    
    const progressBar = toast.querySelector('.shadcn-toast-progress-bar');
    const duration = 4000;
    
    // Animate progress bar
    progressBar.animate([
        { transform: 'scaleX(1)' },
        { transform: 'scaleX(0)' }
    ], {
        duration: duration,
        easing: 'linear',
        fill: 'forwards'
    });
    
    let timeoutId;
    
    const removeToast = () => {
        toast.classList.add('toast-exiting');
        toast.addEventListener('animationend', () => {
            toast.remove();
        });
    };
    
    toast.querySelector('.shadcn-toast-close').addEventListener('click', () => {
        clearTimeout(timeoutId);
        removeToast();
    });
    
    timeoutId = setTimeout(removeToast, duration);
};

// --- Global Reusable Confirm Modal Logic ---
window.showConfirm = function(options = {}) {
    return new Promise((resolve) => {
        const modal = document.getElementById('globalConfirmModal');
        const titleEl = document.getElementById('confirmModalTitle');
        const msgEl = document.getElementById('confirmModalMessage');
        const iconEl = document.getElementById('confirmModalIcon');
        const confirmBtn = document.getElementById('confirmModalConfirmBtn');
        const cancelBtn = document.getElementById('confirmModalCancelBtn');
        
        titleEl.textContent = options.title || 'Xác nhận hành động';
        msgEl.textContent = options.message || 'Bạn có chắc chắn muốn thực hiện hành động này không?';
        
        let iconHtml = '<i class="fa-solid fa-circle-question" style="color: #0f172a"></i>';
        let confirmBtnClass = 'btn-action text-white';
        let confirmBtnStyle = 'background-color: #0f172a;';
        
        if (options.type === 'danger') {
            iconHtml = '<i class="fa-solid fa-triangle-exclamation text-danger"></i>';
            confirmBtnClass = 'btn-action btn-danger text-white';
            confirmBtnStyle = '';
        } else if (options.type === 'success') {
            iconHtml = '<i class="fa-solid fa-circle-check text-success"></i>';
            confirmBtnClass = 'btn-action btn-success text-white';
            confirmBtnStyle = '';
        } else if (options.type === 'warning') {
            iconHtml = '<i class="fa-solid fa-circle-exclamation text-warning"></i>';
            confirmBtnClass = 'btn-action btn-warning text-dark';
            confirmBtnStyle = '';
        }
        
        iconEl.innerHTML = iconHtml;
        confirmBtn.className = `btn ${confirmBtnClass}`;
        confirmBtn.style.cssText = confirmBtnStyle;
        confirmBtn.textContent = options.confirmText || 'Đồng ý';
        cancelBtn.textContent = options.cancelText || 'Hủy';
        
        // Use class toggle instead of [hidden] to properly show/hide
        modal.classList.add('is-open');
        
        const cleanup = (result) => {
            modal.classList.remove('is-open');
            confirmBtn.onclick = null;
            cancelBtn.onclick = null;
            resolve(result);
        };
        
        confirmBtn.onclick = () => cleanup(true);
        cancelBtn.onclick = () => cleanup(false);
        modal.onclick = (e) => {
            if (e.target === modal) cleanup(false);
        };
    });
};

// Auto-bind to data-confirm buttons or forms
document.addEventListener('click', function(e) {
    const confirmBtn = e.target.closest('[data-confirm]');
    if (confirmBtn) {
        // Nếu là nút submit trong form, bỏ qua để HTML5 validation chạy và nhường cho sự kiện 'submit' bên dưới
        if (confirmBtn.tagName === 'BUTTON' && confirmBtn.type === 'submit') {
            return;
        }
        
        e.preventDefault();
        const message = confirmBtn.getAttribute('data-confirm');
        const title = confirmBtn.getAttribute('data-confirm-title') || '<?= __('admin_confirm') ?? 'Xác nhận' ?>';
        const type = confirmBtn.getAttribute('data-confirm-type') || 'warning';
        const href = confirmBtn.getAttribute('href');
        
        showConfirm({
            title: title,
            message: message,
            type: type,
            confirmText: '<?= __('btn_agree') ?? 'Đồng ý' ?>',
            cancelText: '<?= __('btn_cancel') ?? 'Hủy' ?>'
        }).then(confirmed => {
            if (confirmed && href) {
                window.location.href = href;
            }
        });
    }
});

// Xử lý riêng cho form submit để không bị bỏ qua HTML5 Validation (như required, max, min...)
document.addEventListener('submit', function(e) {
    // e.submitter là nút submit đã được bấm
    if (e.submitter && e.submitter.hasAttribute('data-confirm')) {
        e.preventDefault(); // Chặn form submit ngay lập tức sau khi HTML5 validation đã pass
        
        const message = e.submitter.getAttribute('data-confirm');
        const title = e.submitter.getAttribute('data-confirm-title') || '<?= __('admin_confirm') ?? 'Xác nhận' ?>';
        const type = e.submitter.getAttribute('data-confirm-type') || 'warning';
        
        showConfirm({
            title: title,
            message: message,
            type: type,
            confirmText: '<?= __('btn_agree') ?? 'Đồng ý' ?>',
            cancelText: '<?= __('btn_cancel') ?? 'Hủy' ?>'
        }).then(confirmed => {
            if (confirmed) {
                e.target.submit(); // Submit form thực sự
            }
        });
    }
});
</script>

<?php
// Tự động kích hoạt Toast nếu có thông báo thành công hoặc lỗi từ URL / Session
$toastMessage = '';
$toastType = '';

if (isset($_GET['success']) && !empty($_GET['success'])) {
    $toastMessage = $_GET['success'];
    $toastType = 'success';
} elseif (isset($_GET['error']) && !empty($_GET['error'])) {
    $toastMessage = $_GET['error'];
    $toastType = 'danger';
} elseif (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
    $toastMessage = $_SESSION['success'];
    $toastType = 'success';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    $toastMessage = $_SESSION['error'];
    $toastType = 'danger';
    unset($_SESSION['error']);
}

if ($toastMessage !== ''):
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Escape JS string
    const msg = <?php echo json_encode((string)$toastMessage); ?>;
    const type = '<?php echo $toastType; ?>';
    if (window.showToast) {
        window.showToast(msg, type);
    }
    
    // Clean URL to prevent showing toast again on page reload
    if (window.history.replaceState) {
        const url = new URL(window.location.href);
        if (url.searchParams.has('success') || url.searchParams.has('error')) {
            url.searchParams.delete('success');
            url.searchParams.delete('error');
            window.history.replaceState({ path: url.href }, '', url.href);
        }
    }
});
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
