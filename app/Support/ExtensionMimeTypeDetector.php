<?php

namespace App\Support;

use League\MimeTypeDetection\MimeTypeDetector;

class ExtensionMimeTypeDetector implements MimeTypeDetector
{
    protected array $map = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'pdf' => 'application/pdf',
        'txt' => 'text/plain',
        'csv' => 'text/csv',
        'zip' => 'application/zip',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public function detectMimeType(string $path, $contents): ?string
    {
        return $this->guessFromExtension($path);
    }

    public function detectMimeTypeFromBuffer(string $contents): ?string
    {
        return null;
    }

    public function detectMimeTypeFromFile(string $path): ?string
    {
        return $this->guessFromExtension($path);
    }

    public function detectMimeTypeFromPath(string $path): ?string
    {
        return $this->guessFromExtension($path);
    }

    protected function guessFromExtension(string $path): ?string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return $this->map[$ext] ?? 'application/octet-stream';
    }
}