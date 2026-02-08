<?php

class ImageService
{

    public static function processUpload(
        array $file,
        string $outputDir,
        array $sizes = [300, 600, 1200],
        int $qualityJpeg = 82
    ): array {

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'Image exceeds maximum upload size',
                UPLOAD_ERR_FORM_SIZE => 'Image exceeds form maximum size',
                UPLOAD_ERR_PARTIAL => 'Image was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No image file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
            ];
            $errorMsg = $errorMessages[$file['error']] ?? 'Unknown upload error (code: ' . $file['error'] . ')';
            throw new Exception($errorMsg);
        }
        
        $tmp = $file['tmp_name'];
        $info = getimagesize($tmp);
        if (!$info) throw new Exception('Invalid or corrupted image file');

        $mime = $info['mime'];

        // Check if GD functions exist for this image type
        if ($mime === 'image/webp' && !function_exists('imagecreatefromwebp')) {
            throw new Exception('WebP images are not supported on this server. Please upload JPG or PNG images.');
        }
        if ($mime === 'image/png' && !function_exists('imagecreatefrompng')) {
            throw new Exception('PNG images are not supported on this server. Please upload JPG images.');
        }

        $src = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($tmp),
            'image/png'  => imagecreatefrompng($tmp),
            'image/webp' => imagecreatefromwebp($tmp),
            default      => throw new Exception('Unsupported image type: ' . $mime . '. Please upload JPG, PNG, or WebP images.'),
        };

        if (!$src) {
            throw new Exception('Failed to process image. The file may be corrupted.');
        }

        if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);

        $base = bin2hex(random_bytes(10));
        $results = [];

        foreach ($sizes as $w) {
            $results[$w] = self::resizeAndSave($src, $w, $outputDir, $base, $qualityJpeg);
        }

        imagedestroy($src);
        return $results;
    }

    private static function resizeAndSave($src, int $newW, string $dir, string $base, int $qualityJpeg): string {

        $oldW = imagesx($src);
        $oldH = imagesy($src);

        if ($oldW <= 0 || $oldH <= 0) throw new Exception('Bad image dimensions');

        $newH = (int) round(($newW / $oldW) * $oldH);

        $dst = imagecreatetruecolor($newW, $newH);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $oldW, $oldH);

        $name = "{$base}_{$newW}.jpg";
        $path = rtrim($dir, '/') . '/' . $name;

        imagejpeg($dst, $path, $qualityJpeg);
        imagedestroy($dst);

        return $name;

    }
}
