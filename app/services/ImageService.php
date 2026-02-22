<?php

/**
 * ImageService - GD-based image processing with smart format detection, transparency preservation & crop support
 * 
 * USAGE EXAMPLES:
 * 
 * // Example 1: Product photo upload with crop and WebP + JPG fallback
 * $result = ImageService::processUpload(
 *     $_FILES['product_image'],
 *     'public/uploads/products',
 *     [300, 600, 1200],
 *     [
 *         'mode' => 'photo',
 *         'crop' => ['x' => 100, 'y' => 50, 'w' => 800, 'h' => 600],
 *         'crop_mode' => 'px',
 *         'quality_webp' => 82,
 *         'quality_jpeg' => 82,
 *         'jpeg_fallback' => true
 *     ]
 * );
 * // Returns: ['original_mime'=>'image/jpeg', 'has_alpha'=>false, 'mode'=>'photo', 
 * //           'sizes'=>[300=>['webp'=>'abc_300.webp','jpg'=>'abc_300.jpg'], ...]]
 * 
 * // Example 2: Logo upload with transparency preservation (auto-detects alpha)
 * $result = ImageService::processUpload(
 *     $_FILES['logo'],
 *     'public/uploads/logos',
 *     [150, 300],
 *     [
 *         'mode' => 'logo',
 *         'quality_webp' => 100, // Lossless for logos
 *         'png_level' => 6
 *     ]
 * );
 * // Returns: ['original_mime'=>'image/png', 'has_alpha'=>true, 'mode'=>'logo',
 * //           'sizes'=>[150=>['webp'=>'def_150.webp'], 300=>['webp'=>'def_300.webp']]]
 */
class ImageService
{
    /**
     * Process uploaded image with smart format detection, optional crop, and multiple size generation
     * 
     * @param array $file $_FILES array element
     * @param string $outputDir Target directory for saved images
     * @param array $sizes Target widths (e.g., [300, 600, 1200])
     * @param array $options Processing options:
     *   - 'mode' => 'auto'|'photo'|'logo' (default: 'auto')
     *   - 'crop' => ['x'=>int, 'y'=>int, 'w'=>int, 'h'=>int] (optional)
     *   - 'crop_mode' => 'px' (pixel coordinates, default)
     *   - 'quality_webp' => int (0-100, default: 82 for photos, 100 for logos)
     *   - 'quality_jpeg' => int (0-100, default: 82)
     *   - 'png_level' => int (0-9, default: 6)
     *   - 'jpeg_fallback' => bool (generate JPG alongside WebP, default: false)
     * 
     * @return array Result structure:
     *   [
     *     'original_mime' => 'image/png',
     *     'has_alpha' => true,
     *     'mode' => 'logo',
     *     'sizes' => [
     *       300 => ['webp' => 'file_300.webp', 'png' => 'file_300.png'],
     *       600 => ['webp' => 'file_600.webp']
     *     ]
     *   ]
     * 
     * @throws Exception With user-friendly error messages
     */
    public static function processUpload(
        array $file,
        string $outputDir,
        array $sizes = [300, 600, 1200],
        array $options = []
    ): array {
        // Validate upload
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
            $errorMsg = $errorMessages[$file['error']] ?? 'Unknown upload error';
            throw new Exception($errorMsg);
        }
        
        // File size check (5MB maximum)
        $maxSize = 5 * 1024 * 1024;
        if (isset($file['size']) && $file['size'] > $maxSize) {
            throw new Exception('Image file size must be under 5MB. Current size: ' . round($file['size'] / 1024 / 1024, 2) . 'MB');
        }
        
        $tmp = $file['tmp_name'];
        $info = getimagesize($tmp);
        if (!$info) {
            throw new Exception('Invalid or corrupted image file');
        }

        $mime = $info['mime'];

        // Validate GD support for this image type
        self::validateGDSupport($mime);

        // Load source image
        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($tmp),
            'image/png'  => @imagecreatefrompng($tmp),
            'image/webp' => @imagecreatefromwebp($tmp),
            default      => throw new Exception('Unsupported image type. Please upload JPG, PNG, or WebP images.'),
        };

        if (!$src) {
            throw new Exception('Failed to process image. The file may be corrupted.');
        }

        // Detect alpha transparency
        $hasAlpha = self::detectAlpha($src, $mime);

        // Determine processing mode
        $mode = $options['mode'] ?? 'auto';
        if ($mode === 'auto') {
            $mode = $hasAlpha ? 'logo' : 'photo';
        }

        // Apply crop if requested
        if (isset($options['crop']) && is_array($options['crop'])) {
            $cropped = self::applyCrop($src, $options['crop'], $hasAlpha);
            imagedestroy($src);
            $src = $cropped;
        }

        // Ensure output directory exists
        if (!is_dir($outputDir)) {
            if (!@mkdir($outputDir, 0777, true)) {
                imagedestroy($src);
                throw new Exception('Failed to create upload directory');
            }
        }

        $base = bin2hex(random_bytes(10));
        $originalWidth = imagesx($src);
        $sizeResults = [];

        // Process each target size (skip if upscaling would occur)
        foreach ($sizes as $targetWidth) {
            if ($targetWidth >= $originalWidth) {
                continue; // Skip sizes larger than original (avoid upscaling)
            }

            $variants = self::resizeAndSaveMultiFormat(
                $src,
                $targetWidth,
                $outputDir,
                $base,
                $hasAlpha,
                $mode,
                $options
            );

            if (!empty($variants)) {
                $sizeResults[$targetWidth] = $variants;
            }
        }

        // If no sizes generated (original smaller than all targets), save original size
        if (empty($sizeResults)) {
            $variants = self::resizeAndSaveMultiFormat(
                $src,
                $originalWidth,
                $outputDir,
                $base,
                $hasAlpha,
                $mode,
                $options
            );
            if (!empty($variants)) {
                $sizeResults[$originalWidth] = $variants;
            }
        }

        imagedestroy($src);

        return [
            'original_mime' => $mime,
            'has_alpha' => $hasAlpha,
            'mode' => $mode,
            'sizes' => $sizeResults
        ];
    }

    /**
     * Validate GD support for specific mime type
     */
    private static function validateGDSupport(string $mime): void
    {
        if ($mime === 'image/webp' && !function_exists('imagecreatefromwebp')) {
            throw new Exception('WebP images are not supported on this server. Please upload JPG or PNG images.');
        }
        if ($mime === 'image/png' && !function_exists('imagecreatefrompng')) {
            throw new Exception('PNG images are not supported on this server. Please upload JPG images.');
        }
        if ($mime === 'image/jpeg' && !function_exists('imagecreatefromjpeg')) {
            throw new Exception('JPEG images are not supported on this server.');
        }
    }

    /**
     * Detect if image has alpha transparency
     */
    private static function detectAlpha($image, string $mime): bool
    {
        // JPEG never has alpha
        if ($mime === 'image/jpeg') {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Sample pixels to detect alpha (check corners and center for performance)
        $samplePoints = [
            [0, 0],
            [$width - 1, 0],
            [0, $height - 1],
            [$width - 1, $height - 1],
            [(int)($width / 2), (int)($height / 2)]
        ];

        foreach ($samplePoints as [$x, $y]) {
            $rgba = imagecolorat($image, $x, $y);
            $alpha = ($rgba & 0x7F000000) >> 24;
            
            if ($alpha > 0) { // GD alpha: 0=opaque, 127=transparent
                return true;
            }
        }

        return false;
    }

    /**
     * Apply crop to image resource with validation
     */
    private static function applyCrop($src, array $crop, bool $hasAlpha)
    {
        $srcWidth = imagesx($src);
        $srcHeight = imagesy($src);

        // Validate crop parameters
        $x = (int)($crop['x'] ?? 0);
        $y = (int)($crop['y'] ?? 0);
        $w = (int)($crop['w'] ?? $srcWidth);
        $h = (int)($crop['h'] ?? $srcHeight);

        if ($w <= 0 || $h <= 0) {
            throw new Exception('Crop dimensions must be greater than zero');
        }

        if ($x < 0 || $y < 0 || $x + $w > $srcWidth || $y + $h > $srcHeight) {
            throw new Exception('Crop area is outside image boundaries');
        }

        // Use imagecrop if available (PHP 5.5+)
        if (function_exists('imagecrop')) {
            $cropped = @imagecrop($src, ['x' => $x, 'y' => $y, 'width' => $w, 'height' => $h]);
            
            if (!$cropped) {
                throw new Exception('Failed to crop image');
            }

            // Preserve alpha if needed
            if ($hasAlpha) {
                imagealphablending($cropped, false);
                imagesavealpha($cropped, true);
            }

            return $cropped;
        }

        // Fallback: manual crop using imagecopy
        $cropped = imagecreatetruecolor($w, $h);

        if ($hasAlpha) {
            imagealphablending($cropped, false);
            imagesavealpha($cropped, true);
            $transparent = imagecolorallocatealpha($cropped, 0, 0, 0, 127);
            imagefill($cropped, 0, 0, $transparent);
        }

        if (!imagecopy($cropped, $src, 0, 0, $x, $y, $w, $h)) {
            imagedestroy($cropped);
            throw new Exception('Failed to crop image');
        }

        return $cropped;
    }

    /**
     * Resize image and save in appropriate format(s) based on alpha and mode
     */
    private static function resizeAndSaveMultiFormat(
        $src,
        int $targetWidth,
        string $dir,
        string $base,
        bool $hasAlpha,
        string $mode,
        array $options
    ): array {
        $srcWidth = imagesx($src);
        $srcHeight = imagesy($src);

        if ($srcWidth <= 0 || $srcHeight <= 0) {
            throw new Exception('Invalid image dimensions');
        }

        // Calculate proportional height
        $targetHeight = (int)round(($targetWidth / $srcWidth) * $srcHeight);

        // Create destination image
        $dst = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve alpha if present
        if ($hasAlpha) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $transparent);
        }

        // Resample
        if (!imagecopyresampled($dst, $src, 0, 0, 0, 0, $targetWidth, $targetHeight, $srcWidth, $srcHeight)) {
            imagedestroy($dst);
            throw new Exception('Failed to resize image');
        }

        // Determine output formats and quality settings
        $variants = [];
        $dir = rtrim($dir, '/\\');

        // Quality defaults
        $qualityWebp = $options['quality_webp'] ?? ($mode === 'logo' ? 100 : 82);
        $qualityJpeg = $options['quality_jpeg'] ?? 82;
        $pngLevel = $options['png_level'] ?? 6;
        $jpegFallback = $options['jpeg_fallback'] ?? false;

        // Output strategy based on alpha and mode
        if ($hasAlpha || $mode === 'logo') {
            // Has transparency OR logo mode: prefer lossless formats
            
            // Try WebP lossless first
            if (function_exists('imagewebp')) {
                $webpName = "{$base}_{$targetWidth}.webp";
                $webpPath = "{$dir}/{$webpName}";
                
                if (@imagewebp($dst, $webpPath, $qualityWebp)) {
                    $variants['webp'] = $webpName;
                } else {
                    imagedestroy($dst);
                    throw new Exception('Failed to save WebP image');
                }
            }
            
            // PNG fallback (always generate if WebP not available OR as additional format)
            if (!isset($variants['webp']) || $mode === 'logo') {
                if (function_exists('imagepng')) {
                    $pngName = "{$base}_{$targetWidth}.png";
                    $pngPath = "{$dir}/{$pngName}";
                    
                    if (@imagepng($dst, $pngPath, $pngLevel)) {
                        $variants['png'] = $pngName;
                    } else {
                        imagedestroy($dst);
                        throw new Exception('Failed to save PNG image');
                    }
                }
            }
        } else {
            // Photo mode (no alpha): prefer lossy compression
            
            // Try WebP lossy first
            if (function_exists('imagewebp')) {
                $webpName = "{$base}_{$targetWidth}.webp";
                $webpPath = "{$dir}/{$webpName}";
                
                if (@imagewebp($dst, $webpPath, $qualityWebp)) {
                    $variants['webp'] = $webpName;
                } else {
                    imagedestroy($dst);
                    throw new Exception('Failed to save WebP image');
                }
            }
            
            // JPEG (fallback if no WebP OR if explicitly requested)
            if (!isset($variants['webp']) || $jpegFallback) {
                if (function_exists('imagejpeg')) {
                    $jpegName = "{$base}_{$targetWidth}.jpg";
                    $jpegPath = "{$dir}/{$jpegName}";
                    
                    if (@imagejpeg($dst, $jpegPath, $qualityJpeg)) {
                        $variants['jpg'] = $jpegName;
                    } else {
                        imagedestroy($dst);
                        throw new Exception('Failed to save JPEG image');
                    }
                }
            }
        }

        imagedestroy($dst);

        if (empty($variants)) {
            throw new Exception('No output format could be generated');
        }

        return $variants;
    }

    /**
     * Get user-friendly error message from exception
     * (Prevents leaking server paths)
     */
    public static function getUserMessage(\Throwable $e): string
    {
        return $e->getMessage();
    }

    /**
     * Legacy method for backward compatibility (calls new processUpload with simple options)
     * Auto-detects if image is a logo (has alpha) and uses appropriate mode
     * 
     * @deprecated Use processUpload() with options array for full control
     */
    public static function processUploadLegacy(
        array $file,
        string $outputDir,
        array $sizes = [300, 600, 1200],
        int $qualityJpeg = 82
    ): array {
        // Auto mode: will detect alpha and choose logo/photo mode automatically
        $result = self::processUpload($file, $outputDir, $sizes, [
            'mode' => 'auto',
            'quality_jpeg' => $qualityJpeg,
            'jpeg_fallback' => true
        ]);

        // Convert to legacy format (just filenames array)
        $legacy = [];
        foreach ($result['sizes'] as $width => $variants) {
            // Return first available variant (prefer jpg for legacy compatibility, but use webp/png if that's what was generated)
            $legacy[$width] = $variants['jpg'] ?? $variants['webp'] ?? $variants['png'] ?? '';
        }

        return $legacy;
    }
}
