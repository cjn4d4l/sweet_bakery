<?php
declare(strict_types=1);

/**
 * Validate and store a menu photo. Only real JPEG, PNG, and WebP image files
 * are accepted; the server gives each file a random name rather than trusting
 * the original upload name.
 */
function storeMenuImage(array $upload): string
{
    if (!isset($upload['error']) || is_array($upload['error'])) {
        throw new InvalidArgumentException('The selected image upload is invalid.');
    }

    if ($upload['error'] === UPLOAD_ERR_NO_FILE) {
        throw new InvalidArgumentException('Choose an image file to upload.');
    }

    if ($upload['error'] !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException('The image could not be uploaded. Please try again.');
    }

    if (!isset($upload['size']) || $upload['size'] > 5 * 1024 * 1024) {
        throw new InvalidArgumentException('Images must be 5 MB or smaller.');
    }

    if (empty($upload['tmp_name']) || !is_uploaded_file($upload['tmp_name'])) {
        throw new InvalidArgumentException('The selected image upload is invalid.');
    }

    $imageInfo = getimagesize($upload['tmp_name']);
    if ($imageInfo === false) {
        throw new InvalidArgumentException('The selected file is not a valid image.');
    }

    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($fileInfo === false) {
        throw new RuntimeException('The server cannot validate image file types because PHP Fileinfo is unavailable.');
    }

    $mimeType = finfo_file($fileInfo, $upload['tmp_name']);
    finfo_close($fileInfo);

    $extensionsByMimeType = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (
        !is_string($mimeType)
        || !isset($extensionsByMimeType[$mimeType])
        || $imageInfo['mime'] !== $mimeType
    ) {
        throw new InvalidArgumentException('Use a JPEG, PNG, or WebP image.');
    }

    $uploadDirectory = __DIR__ . '/uploads/menu';
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
        throw new RuntimeException('The menu image folder could not be created.');
    }

    $fileName = bin2hex(random_bytes(16)) . '.' . $extensionsByMimeType[$mimeType];
    if (!move_uploaded_file($upload['tmp_name'], $uploadDirectory . DIRECTORY_SEPARATOR . $fileName)) {
        throw new RuntimeException('The server could not save the uploaded image.');
    }

    return 'admin/uploads/menu/' . $fileName;
}

/**
 * Remove only files inside the generated menu-upload folder. Seed images in
 * the shared images folder are intentionally kept when a product is deleted.
 */
function removeUploadedMenuImage(string $imagePath): void
{
    $prefix = 'admin/uploads/menu/';
    if (strncmp($imagePath, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $fileName = substr($imagePath, strlen($prefix));
    if ($fileName === '' || basename($fileName) !== $fileName) {
        return;
    }

    $filePath = __DIR__ . '/uploads/menu/' . $fileName;
    if (is_file($filePath) && !unlink($filePath)) {
        error_log('Unable to remove old menu image: ' . $filePath);
    }
}
