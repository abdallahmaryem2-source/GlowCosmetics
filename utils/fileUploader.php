<?php
// utils/fileUploader.php
class FileUploader {
    private array $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private int   $maxSize      = 2097152; // 2 MB

    public function __construct(private string $uploadDir) {
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }
    }

    public function upload(array $file): ?string {
        if ($file['error'] !== UPLOAD_ERR_OK)         return null;
        if ($file['size'] > $this->maxSize)            return null;
        if (!in_array($file['type'], $this->allowedTypes)) return null;

        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

        if (move_uploaded_file($file['tmp_name'], $this->uploadDir . $name)) {
            return $name;
        }
        return null;
    }
}