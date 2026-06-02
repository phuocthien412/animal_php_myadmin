<?php
/**
 * Reusable File Uploader Component
 * 
 * @param string $id Unique input ID
 * @param string $name Form field name (e.g. 'avatar_file' or 'list_images[]')
 * @param string $label Visible section header label
 * @param string $currentValue Filename of the current image/media (optional)
 * @param string $imageFolder Relative folder path under images/ (e.g. 'Animal/Avatar' or 'ClassAnimal')
 * @param string $accept Accepted mime types (default: 'image/*')
 * @param bool $isMultiple Allow selecting multiple files (default: false)
 * @param bool $required Is this field required (default: false)
 */
function renderFileUploader($id, $name, $label, $currentValue = '', $imageFolder = '', $accept = 'image/*', $isMultiple = false, $required = false) {
    global $base;
    $previewContainerId = $id . 'PreviewContainer';
    $multipleAttr = $isMultiple ? 'multiple' : '';
    $requiredAttr = $required ? 'required' : '';
    $isMultipleVal = $isMultiple ? 'true' : 'false';
    
    // Check if media is video
    $isVideo = false;
    if (!empty($currentValue)) {
        $ext = strtolower(pathinfo($currentValue, PATHINFO_EXTENSION));
        $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
    }
    
    // Icon based on type
    $icon = 'fa-image';
    if ($accept === 'image/*') {
        if (strpos(strtolower($id), 'qr') !== false) {
            $icon = 'fa-qrcode';
        } elseif ($isMultiple) {
            $icon = 'fa-images';
        }
    } elseif (strpos($accept, 'video') !== false) {
        $icon = 'fa-video';
    } else {
        $icon = 'fa-file-upload';
    }
    ?>
    <div class="mb-4 p-3 rounded-3" style="background: rgba(248, 249, 250, 0.7); border: 1px dashed rgba(0, 123, 255, 0.25);">
        <label class="form-label font-weight-bold d-block mb-3" style="color: var(--slate-dark);">
            <i class="fa-solid <?= $icon ?> text-primary me-2"></i><?= htmlspecialchars($label) ?>
        </label>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <!-- Current file thumbnail -->
            <?php if(!empty($currentValue) && !empty($imageFolder)): ?>
                <div class="text-center">
                    <div class="small text-muted mb-1"><?= __('uploader_current') ?? 'Hiện tại' ?></div>
                    <?php if($isVideo): ?>
                        <video src="<?= $base ?>/images/<?= htmlspecialchars($imageFolder) ?>/<?= htmlspecialchars($currentValue) ?>" style="width: 100px; height: 100px; border-radius: 12px; border: 2px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); object-fit: cover;" muted playsinline class="no-zoom"></video>
                    <?php else: ?>
                        <img src="<?= $base ?>/images/<?= htmlspecialchars($imageFolder) ?>/<?= htmlspecialchars($currentValue) ?>" style="width: 100px; height: 100px; border-radius: 12px; border: 2px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); object-fit: cover;">
                    <?php endif; ?>
                </div>
                <div class="text-muted"><i class="fa-solid fa-arrow-right fa-lg"></i></div>
                <input type="hidden" name="current_<?= htmlspecialchars($id) ?>" value="<?= htmlspecialchars($currentValue) ?>">
            <?php endif; ?>
            
            <!-- Upload action -->
            <div class="flex-grow-1">
                <div class="small text-muted mb-1"><?= $isMultiple ? (__('uploader_add_new') ?? 'Thêm hình ảnh mới') : (__('uploader_replace') ?? 'Chọn tệp thay thế') ?></div>
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold" onclick="document.getElementById('<?= htmlspecialchars($id) ?>').click()">
                    <i class="fa-solid fa-upload me-1"></i> <?= __('uploader_choose') ?? 'Chọn file' ?>
                </button>
                <input type="file" id="<?= htmlspecialchars($id) ?>" name="<?= htmlspecialchars($name) ?>" <?= $multipleAttr ?> <?= $requiredAttr ?> class="d-none" accept="<?= htmlspecialchars($accept) ?>" onclick="this.value = null" onchange="validateAndPreviewFile(this, '<?= htmlspecialchars($previewContainerId) ?>', <?= $isMultipleVal ?>)">
                <div class="text-muted mt-2" style="font-size: 11px;">
                    <?= $isMultiple ? (__('uploader_multiple_hint') ?? 'Có thể chọn nhiều tệp tin. ') : (__('uploader_single_hint') ?? 'Hỗ trợ tệp tin. ') ?><?= __('uploader_max_size') ?? 'Tối đa 10MB.' ?>
                </div>
            </div>
            
            <!-- Preview of newly selected files -->
            <div id="<?= htmlspecialchars($previewContainerId) ?>" class="upload-preview-container m-0"></div>
        </div>
    </div>
    <?php
}
?>
