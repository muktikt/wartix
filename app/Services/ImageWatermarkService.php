<?php
namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;

class ImageWatermarkService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function addWatermark(string $imagePath): string
    {
        $image     = $this->manager->read($imagePath);
        $width     = $image->width();
        $height    = $image->height();
        $logoPath  = public_path('images/logo-w.png');

        if (!file_exists($logoPath)) {
            Log::warning('Watermark logo not found');
            return $imagePath;
        }

        // Ukuran watermark — 25% dari lebar gambar
        $wmWidth  = (int) ($width * 0.25);
        $logo     = $this->manager->read($logoPath)->scale(width: $wmWidth);
        $logoW    = $logo->width();
        $logoH    = $logo->height();

        // Posisi watermark: grid 3x3
        $positions = [
            // Row 1
            [(int)($width * 0.15), (int)($height * 0.20)],
            [(int)($width * 0.50), (int)($height * 0.20)],
            [(int)($width * 0.80), (int)($height * 0.20)],
            // Row 2
            [(int)($width * 0.25), (int)($height * 0.50)],
            [(int)($width * 0.65), (int)($height * 0.50)],
            // Row 3
            [(int)($width * 0.15), (int)($height * 0.80)],
            [(int)($width * 0.50), (int)($height * 0.80)],
            [(int)($width * 0.80), (int)($height * 0.80)],
        ];

        foreach ($positions as [$x, $y]) {
            // Pastikan tidak keluar batas gambar
            $x = min($x, $width - $logoW);
            $y = min($y, $height - $logoH);
            $x = max(0, $x);
            $y = max(0, $y);

            // Tempel watermark dengan opacity 35%
            $image->place(
                $logo,
                'top-left',
                $x,
                $y,
                35 // opacity
            );
        }

        // Simpan ke temp file
        $outputPath = storage_path('app/temp/watermarked_' . basename($imagePath));

        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0755, true);
        }

        $image->save($outputPath);

        return $outputPath;
    }

    public function generateRekapImage(array $data): string
    {
        // Ukuran canvas rekap
        $width   = 1080;
        $height  = 1350;

        // Buat canvas putih
        $image   = $this->manager->create($width, $height)->fill('#0F172A');

        // Tambah watermark logo dulu di background
        $this->addWatermarkToCanvas($image, $width, $height);

        // Simpan
        $outputPath = storage_path('app/temp/rekap_' . time() . '.jpg');

        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0755, true);
        }

        $image->save($outputPath, quality: 90);

        return $outputPath;
    }

    private function addWatermarkToCanvas($image, int $width, int $height): void
    {
        $logoPath = public_path('images/logo-w.png');
        if (!file_exists($logoPath)) return;

        $wmWidth = (int) ($width * 0.22);
        $logo    = $this->manager->read($logoPath)->scale(width: $wmWidth);
        $logoW   = $logo->width();
        $logoH   = $logo->height();

        $positions = [
            [(int)($width * 0.12), (int)($height * 0.15)],
            [(int)($width * 0.55), (int)($height * 0.15)],
            [(int)($width * 0.78), (int)($height * 0.15)],
            [(int)($width * 0.20), (int)($height * 0.45)],
            [(int)($width * 0.60), (int)($height * 0.45)],
            [(int)($width * 0.12), (int)($height * 0.72)],
            [(int)($width * 0.55), (int)($height * 0.72)],
            [(int)($width * 0.78), (int)($height * 0.72)],
        ];

        foreach ($positions as [$x, $y]) {
            $x = max(0, min($x, $width - $logoW));
            $y = max(0, min($y, $height - $logoH));

            $image->place($logo, 'top-left', $x, $y, 20);
        }
    }
}