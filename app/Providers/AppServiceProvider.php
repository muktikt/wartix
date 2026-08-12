<?php
namespace App\Providers;

use App\Support\ExtensionMimeTypeDetector;
use App\Support\ExtensionMimeTypeGuesser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\Mime\MimeTypes;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Server ini menonaktifkan ekstensi PHP fileinfo secara permanen
        // (--disable-fileinfo saat compile PHP), jadi kita ganti MIME type
        // guesser/detector bawaan dengan versi custom berbasis ekstensi file,
        // baik untuk validasi form (Symfony Mime) maupun penyimpanan file (Flysystem).

        // 1. Untuk validasi form (rule 'extensions:' & helper terkait)
        MimeTypes::getDefault()->registerGuesser(new ExtensionMimeTypeGuesser());

        // 2. Untuk proses simpan file ke disk (local & public disk)
        foreach (['local', 'public'] as $diskName) {
            Storage::extend($diskName, function ($app, $config) {
                $adapter = new LocalFilesystemAdapter(
                    $config['root'] ?? storage_path('app'),
                    null,
                    LOCK_EX,
                    LocalFilesystemAdapter::DISALLOW_LINKS,
                    new ExtensionMimeTypeDetector()
                );

                return new FilesystemAdapter(
                    new Filesystem($adapter, $config),
                    $adapter,
                    $config
                );
            });
        }

        RateLimiter::for('order-by-email', function (Request $request) {
            return Limit::perHour(3)
                ->by($request->input('email', $request->ip()))
                ->response(function (Request $request) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Terlalu banyak order. Coba lagi dalam 1 jam.'
                        ], 429);
                    }
                    return back()->withInput()->withErrors([
                        'email' => 'Terlalu banyak order untuk email ini. Coba lagi dalam 1 jam.'
                    ]);
                });
        });
    }
}