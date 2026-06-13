<?php
namespace App\Console\Commands;

use App\Models\Event;
use App\Services\ImageWatermarkService;
use Illuminate\Console\Command;

class TestRekapImage extends Command
{
    protected $signature   = 'wartix:test-rekap {event_id}';
    protected $description = 'Test generate rekap image';

    public function handle(ImageWatermarkService $service): void
    {
        $event = Event::with(['salePhases', 'ticketCategories'])
            ->find($this->argument('event_id'));

        if (!$event) {
            $this->error('Event not found!');
            return;
        }

        $this->info('Generating rekap image...');
        $path = $service->generateRekapImage($event);

        if ($path && file_exists($path)) {
            $this->info('Success! File saved to: ' . $path);
            $this->info('File size: ' . round(filesize($path) / 1024) . ' KB');
        } else {
            $this->error('Failed to generate image!');
        }
    }
}