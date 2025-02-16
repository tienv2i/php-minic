<?php
function handleFileUpload($uploaded_dir) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['file'])) {
        return ['error' => 'Invalid request'];
    }

    $file = $_FILES['file'];
    $targetPath = rtrim($uploaded_dir, '/') . '/' . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => 'File uploaded successfully', 'file' => basename($file['name'])];
    } else {
        return ['error' => 'Upload failed'];
    }
}

function getUploadedFiles($uploaded_dir) {
    return array_diff(scandir($uploaded_dir), ['.', '..']);
}
?>
