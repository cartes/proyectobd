<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizationService
{
    // Image size configurations
    private const SIZES = [
        'thumbnail' => ['width' => 150, 'height' => 150, 'quality' => 85],
        'medium' => ['width' => 600, 'height' => 600, 'quality' => 85],
        'large' => ['width' => 1200, 'height' => 1200, 'quality' => 90],
    ];

    /**
     * Optimize and store uploaded image with multiple sizes
     *
     * @param  UploadedFile  $file  The uploaded image file
     * @param  string  $basePath  Base storage path (e.g., 'profile-photos/user-hash')
     * @return array Array with paths: ['original', 'thumbnail', 'medium', 'large', 'file_size']
     */
    public function optimizeAndStore(UploadedFile $file, string $basePath): array
    {
        $extension = $file->extension();
        $filename = Str::uuid();

        // Store original file
        $originalPath = $file->storeAs($basePath, "{$filename}.{$extension}", 'public');
        $fullPath = Storage::disk('public')->path($originalPath);

        // Get file size
        $fileSize = $file->getSize();

        // Generate optimized versions
        $paths = [
            'original' => $originalPath,
            'file_size' => $fileSize,
        ];

        foreach (self::SIZES as $sizeName => $config) {
            $optimizedPath = $this->generateOptimizedVersion(
                $fullPath,
                $basePath,
                $filename,
                $sizeName,
                $config
            );

            $paths[$sizeName.'_path'] = $optimizedPath;
        }

        return $paths;
    }

    /**
     * Generate optimized version of image
     *
     * @param  string  $sourcePath  Full path to source image
     * @param  string  $basePath  Base storage path
     * @param  string  $filename  Base filename (without extension)
     * @param  string  $sizeName  Size name (thumbnail, medium, large)
     * @param  array  $config  Configuration array with width, height, quality
     * @return string Relative path to optimized image
     */
    private function generateOptimizedVersion(
        string $sourcePath,
        string $basePath,
        string $filename,
        string $sizeName,
        array $config
    ): string {
        // Load source image
        $sourceImage = $this->loadImage($sourcePath);
        if (! $sourceImage) {
            throw new \Exception("Failed to load image: {$sourcePath}");
        }

        // Get original dimensions
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // Calculate new dimensions (maintain aspect ratio, fit within bounds)
        $dimensions = $this->calculateDimensions(
            $originalWidth,
            $originalHeight,
            $config['width'],
            $config['height']
        );

        // Create resized image
        $resizedImage = imagecreatetruecolor($dimensions['width'], $dimensions['height']);

        // Preserve transparency for PNG
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);

        // Resize
        imagecopyresampled(
            $resizedImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $dimensions['width'],
            $dimensions['height'],
            $originalWidth,
            $originalHeight
        );

        // Save optimized version
        $optimizedFilename = "{$filename}_{$sizeName}.webp";
        $optimizedPath = $basePath.'/'.$optimizedFilename;
        $fullOptimizedPath = Storage::disk('public')->path($optimizedPath);

        // Ensure directory exists
        $directory = dirname($fullOptimizedPath);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Save as WebP for better compression
        imagewebp($resizedImage, $fullOptimizedPath, $config['quality']);

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        return $optimizedPath;
    }

    /**
     * Load image from file based on type
     */
    private function loadImage(string $path)
    {
        $imageInfo = getimagesize($path);
        if (! $imageInfo) {
            return false;
        }

        $mimeType = $imageInfo['mime'];

        return match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            'image/gif' => imagecreatefromgif($path),
            default => false,
        };
    }

    /**
     * Calculate new dimensions maintaining aspect ratio
     */
    private function calculateDimensions(
        int $originalWidth,
        int $originalHeight,
        int $maxWidth,
        int $maxHeight
    ): array {
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);

        // Don't upscale images
        if ($ratio > 1) {
            $ratio = 1;
        }

        return [
            'width' => (int) round($originalWidth * $ratio),
            'height' => (int) round($originalHeight * $ratio),
        ];
    }

    /**
     * Delete all versions of an image
     *
     * @param  string  $photoPath  Path to original photo
     */
    public function deleteImageVersions(string $photoPath): void
    {
        $disk = Storage::disk('public');

        // Delete original
        if ($disk->exists($photoPath)) {
            $disk->delete($photoPath);
        }

        // Delete optimized versions
        $pathInfo = pathinfo($photoPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];

        foreach (array_keys(self::SIZES) as $sizeName) {
            $optimizedPath = "{$directory}/{$filename}_{$sizeName}.webp";
            if ($disk->exists($optimizedPath)) {
                $disk->delete($optimizedPath);
            }
        }
    }
}
