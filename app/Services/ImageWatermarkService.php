<?php
namespace App\Services;

use App\Models\SuccessLog;
use App\Models\Event;
use App\Services\MaskService;
use Illuminate\Support\Facades\Log;

class ImageWatermarkService
{
    private int $width  = 1080;

    public function generateRekapImage(Event $event): ?string
    {
        try {
            $logs = SuccessLog::where('event_id', $event->id)
                ->where('status', 'success')
                ->with(['salePhase', 'ticketCategory'])
                ->orderBy('created_at', 'desc')
                ->get();

            $tempPath = storage_path('app/temp/rekap_' . $event->id . '_' . time() . '.jpg');

            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }

            return $this->generateWithGd($event, $logs, $tempPath);

        } catch (\Exception $e) {
            Log::error('generateRekapImage failed: ' . $e->getMessage());
            return null;
        }
    }

    private function generateWithGd(Event $event, $logs, string $outputPath): string
    {
        $w         = $this->width;
        $logCount  = max(count($logs), 1);
        $rowHeight = 56;
        $headerH   = 200;
        $footerH   = 120;
        $h         = $headerH + ($logCount * $rowHeight) + $footerH + 40;
        $h         = max($h, 700);

        // Canvas
        $img = imagecreatetruecolor($w, $h);
        imagealphablending($img, true);
        imagesavealpha($img, true);

        // ── WARNA ────────────────────────────────────────────
        $bg         = imagecolorallocate($img, 26, 26, 46);       // #1A1A2E dark purple
        $white      = imagecolorallocate($img, 255, 255, 255);
        $gray1      = imagecolorallocate($img, 148, 163, 184);
        $gray2      = imagecolorallocate($img, 51, 51, 80);
        $green      = imagecolorallocate($img, 34, 197, 94);
        $greenBg    = imagecolorallocate($img, 15, 40, 25);
        $indigo     = imagecolorallocate($img, 99, 102, 241);
        $purple     = imagecolorallocate($img, 124, 58, 237);
        $rowBg      = imagecolorallocate($img, 35, 35, 65);
        $headerBg   = imagecolorallocate($img, 20, 20, 38);

        imagefill($img, 0, 0, $bg);

        // ── WATERMARK LOGO ──────────────────────────────────
        $logoPath = public_path('images/logo-w.png');
        if (file_exists($logoPath)) {
            $logo = imagecreatefrompng($logoPath);
            imagealphablending($logo, true);
            imagesavealpha($logo, true);

            $logoOrigW = imagesx($logo);
            $logoOrigH = imagesy($logo);

            // Ukuran watermark 28% dari lebar canvas
            $logoW = (int)($w * 0.28);
            $logoH = (int)($logoOrigH * ($logoW / $logoOrigW));

            $logoResized = imagecreatetruecolor($logoW, $logoH);
            imagealphablending($logoResized, false);
            imagesavealpha($logoResized, true);

            $transparent = imagecolorallocatealpha($logoResized, 0, 0, 0, 127);
            imagefilledrectangle($logoResized, 0, 0, $logoW, $logoH, $transparent);
            imagealphablending($logoResized, true);

            imagecopyresampled(
                $logoResized, $logo,
                0, 0, 0, 0,
                $logoW, $logoH,
                $logoOrigW, $logoOrigH
            );

            // Grid 3 kolom
            $cols   = 3;
            $colW   = (int)($w / $cols);
            $gapV   = (int)($logoH * 1.2);
            $rows   = (int)ceil($h / $gapV) + 1;

            for ($r = 0; $r < $rows; $r++) {
                for ($c = 0; $c < $cols; $c++) {
                    $x = (int)($c * $colW + ($colW - $logoW) / 2);
                    $y = (int)($r * $gapV + 20);

                    // Opacity 15% — pakai imagecopymerge
                    imagecopymerge($img, $logoResized, $x, $y, 0, 0, $logoW, $logoH, 15);
                }
            }

            imagedestroy($logo);
            imagedestroy($logoResized);
        }

        // ── HEADER ──────────────────────────────────────────
        imagefilledrectangle($img, 0, 0, $w, $headerH, $headerBg);

        // Gradient line bawah header (biru → ungu)
        for ($i = 0; $i < $w; $i++) {
            $r = (int)(99 + ($i / $w) * (124 - 99));
            $g = (int)(102 + ($i / $w) * (58 - 102));
            $b = (int)(241 + ($i / $w) * (237 - 241));
            $lineColor = imagecolorallocate($img, $r, $g, $b);
            imageline($img, $i, $headerH - 2, $i, $headerH, $lineColor);
        }

        // Font paths
        $fontBold = public_path('fonts/Inter-Bold.ttf');
        $fontReg  = public_path('fonts/Inter-Regular.ttf');
        $useFont  = file_exists($fontBold) && file_exists($fontReg);

        // Logo di header (kecil)
        $headerLogoPath = public_path('images/logo-w.png');
        if (file_exists($headerLogoPath)) {
            $hLogo   = imagecreatefrompng($headerLogoPath);
            imagealphablending($hLogo, true);
            $hLogoW  = 180;
            $hLogoH  = (int)(imagesy($hLogo) * ($hLogoW / imagesx($hLogo)));
            $hLogoR  = imagecreatetruecolor($hLogoW, $hLogoH);
            imagealphablending($hLogoR, false);
            imagesavealpha($hLogoR, true);
            $tp = imagecolorallocatealpha($hLogoR, 0, 0, 0, 127);
            imagefilledrectangle($hLogoR, 0, 0, $hLogoW, $hLogoH, $tp);
            imagealphablending($hLogoR, true);
            imagecopyresampled($hLogoR, $hLogo, 0, 0, 0, 0, $hLogoW, $hLogoH, imagesx($hLogo), imagesy($hLogo));
            imagecopy($img, $hLogoR, 40, (int)(($headerH - $hLogoH) / 2), 0, 0, $hLogoW, $hLogoH);
            imagedestroy($hLogo);
            imagedestroy($hLogoR);
        }

        // Event title di header (kanan)
        $eventTitle = mb_strtoupper($event->title);
        if ($useFont) {
            // Event title
            $bbox = imagettfbbox(15, 0, $fontBold, $eventTitle);
            $textW = abs($bbox[4] - $bbox[0]);
            $textX = $w - $textW - 40;
            imagettftext($img, 15, 0, $textX, 70, $white, $fontBold, $eventTitle);
            // Sub
            $sub  = 'Realtime Success Monitor';
            $bbox2 = imagettfbbox(11, 0, $fontReg, $sub);
            $subW  = abs($bbox2[4] - $bbox2[0]);
            $subX  = $w - $subW - 40;
            imagettftext($img, 11, 0, $subX, 95, $gray1, $fontReg, $sub);
            // Tanggal
            $date  = now()->format('d M Y');
            $bbox3 = imagettfbbox(10, 0, $fontReg, $date);
            $dateW = abs($bbox3[4] - $bbox3[0]);
            $dateX = $w - $dateW - 40;
            imagettftext($img, 10, 0, $dateX, 115, $gray2, $fontReg, $date);
        } else {
            imagestring($img, 4, $w - 380, 55, $eventTitle, $white);
            imagestring($img, 2, $w - 320, 80, 'Realtime Success Monitor', $gray1);
        }

        // ── SECTION LABEL ──────────────────────────────────
        $labelY = $headerH + 24;
        if ($useFont) {
            imagettftext($img, 9, 0, 40, $labelY, $gray1, $fontReg, 'SUCCESS LOG');
        } else {
            imagestring($img, 1, 40, $labelY - 10, 'SUCCESS LOG', $gray1);
        }

        // ── LOG ROWS ──────────────────────────────────────
        $startY = $labelY + 14;

        foreach ($logs as $i => $log) {
            $y    = $startY + ($i * $rowHeight);
            $rowY2= $y + $rowHeight - 6;

            // Row background
            $this->imagefilledroundedrect($img, 36, $y, $w - 36, $rowY2, 8, $rowBg);

            // Badge SUCCESS
            $badgeBg = imagecolorallocate($img, 15, 50, 30);
            $this->imagefilledroundedrect($img, 48, $y + 12, 132, $y + 34, 4, $badgeBg);

            if ($useFont) {
                imagettftext($img, 9, 0, 55, $y + 28, $green, $fontBold, 'SUCCESS');
            } else {
                imagestring($img, 1, 55, $y + 16, 'SUCCESS', $green);
            }

            // Sep
            $sepColor = imagecolorallocate($img, 60, 60, 90);
            imageline($img, 142, $y + 12, 142, $y + 34, $sepColor);

            // Email
            $email = MaskService::email($log->email ?? 'us***@example.com');
            if ($useFont) {
                imagettftext($img, 11, 0, 154, $y + 29, $white, $fontReg, $email);
            } else {
                imagestring($img, 2, 154, $y + 16, $email, $white);
            }

            imageline($img, 360, $y + 12, 360, $y + 34, $sepColor);

            // Phase
            $phase = mb_substr($log->salePhase->name ?? '-', 0, 18);
            if ($useFont) {
                imagettftext($img, 11, 0, 372, $y + 29, $gray1, $fontReg, $phase);
            } else {
                imagestring($img, 2, 372, $y + 16, $phase, $gray1);
            }

            imageline($img, 590, $y + 12, 590, $y + 34, $sepColor);

            // Category
            $cat = mb_substr($log->ticketCategory->name ?? '-', 0, 12);
            if ($useFont) {
                imagettftext($img, 11, 0, 602, $y + 29, $gray1, $fontReg, $cat);
            } else {
                imagestring($img, 2, 602, $y + 16, $cat, $gray1);
            }

            imageline($img, 760, $y + 12, 760, $y + 34, $sepColor);

            // Qty
            $qty = 'x' . $log->qty;
            if ($useFont) {
                imagettftext($img, 13, 0, 774, $y + 30, $green, $fontBold, $qty);
            } else {
                imagestring($img, 3, 774, $y + 16, $qty, $green);
            }
        }

        // ── BOTTOM BAR ──────────────────────────────────────
        $bottomY = $startY + ($logCount * $rowHeight) + 16;

        // Gradient line
        for ($i = 0; $i < $w; $i++) {
            $r = (int)(99 + ($i / $w) * (124 - 99));
            $g = (int)(102 + ($i / $w) * (58 - 102));
            $b = (int)(241 + ($i / $w) * (237 - 241));
            $lc = imagecolorallocate($img, $r, $g, $b);
            imageline($img, $i, $bottomY, $i, $bottomY + 2, $lc);
        }

        // Total box
        $totalBg = imagecolorallocate($img, 30, 30, 60);
        $this->imagefilledroundedrect($img, 36, $bottomY + 12, $w - 36, $bottomY + 70, 10, $totalBg);

        $totalSuccess = count($logs);

        if ($useFont) {
            imagettftext($img, 28, 0, 56, $bottomY + 52, $white, $fontBold, (string)$totalSuccess);
            $numW = imagettfbbox(28, 0, $fontBold, (string)$totalSuccess);
            $labelX = 56 + abs($numW[4] - $numW[0]) + 12;
            imagettftext($img, 12, 0, $labelX, $bottomY + 52, $gray1, $fontReg, 'total success');

            // Kanan: privacy note
            $note = 'Data tersensor untuk privasi';
            $nbox = imagettfbbox(10, 0, $fontReg, $note);
            $nW   = abs($nbox[4] - $nbox[0]);
            imagettftext($img, 10, 0, $w - $nW - 56, $bottomY + 52, $gray2, $fontReg, $note);
        } else {
            imagestring($img, 5, 56, $bottomY + 28, $totalSuccess . ' total success', $white);
        }

        // ── FOOTER ──────────────────────────────────────────
        $footerY = $bottomY + 90;
        if ($useFont) {
            $footerText = 'wartix.id — ticket assistance platform';
            $fbox = imagettfbbox(10, 0, $fontReg, $footerText);
            $fW   = abs($fbox[4] - $fbox[0]);
            $fX   = (int)(($w - $fW) / 2);
            imagettftext($img, 10, 0, $fX, $footerY, $gray2, $fontReg, $footerText);
        } else {
            imagestring($img, 1, (int)(($w - 200) / 2), $footerY, 'wartix.id', $gray2);
        }

        // Simpan
        imagejpeg($img, $outputPath, 92);
        imagedestroy($img);

        return $outputPath;
    }

    private function imagefilledroundedrect(
        $img, int $x1, int $y1, int $x2, int $y2, int $r, $color
    ): void {
        imagefilledrectangle($img, $x1 + $r, $y1, $x2 - $r, $y2, $color);
        imagefilledrectangle($img, $x1, $y1 + $r, $x2, $y2 - $r, $color);
        imagefilledellipse($img, $x1 + $r, $y1 + $r, $r * 2, $r * 2, $color);
        imagefilledellipse($img, $x2 - $r, $y1 + $r, $r * 2, $r * 2, $color);
        imagefilledellipse($img, $x1 + $r, $y2 - $r, $r * 2, $r * 2, $color);
        imagefilledellipse($img, $x2 - $r, $y2 - $r, $r * 2, $r * 2, $color);
    }

    public function cleanup(string $path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }
}