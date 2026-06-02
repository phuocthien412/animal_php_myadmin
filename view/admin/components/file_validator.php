<?php
/**
 * Reusable File Validator Component
 */
function validateUploadedFiles($files, $maxSize = 10485760, $returnError = false) {
    if (empty($files)) return true;

    $maxSizeMB = $maxSize / 1024 / 1024;

    foreach ($files as $fileArray) {
        if (!isset($fileArray['name'])) continue;

        // Xử lý mảng (ví dụ upload nhiều file: list_images[])
        if (is_array($fileArray['name'])) {
            $count = count($fileArray['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($fileArray['error'][$i] === UPLOAD_ERR_OK && $fileArray['size'][$i] > $maxSize) {
                    $errorMsg = sprintf(__('msg_files_size_exceeded'), $maxSizeMB);
                    if ($returnError) return $errorMsg;
                    die($errorMsg);
                }
            }
        } 
        // Xử lý file đơn
        else {
            if ($fileArray['error'] === UPLOAD_ERR_OK && $fileArray['size'] > $maxSize) {
                $errorMsg = sprintf(__('msg_file_size_exceeded'), $maxSizeMB);
                if ($returnError) return $errorMsg;
                die($errorMsg);
            }
        }
    }
    return true;
}
?>
