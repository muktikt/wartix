<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RetryFailedJobs extends Command
{
    protected $signature   = 'wartix:retry-failed';
    protected $description = 'Retry all failed jobs';

    public function handle(): void
    {
        $this->info('Retrying failed jobs...');
        Artisan::call('queue:retry', ['id' => ['all']]);
        $this->info('Done!');
    }
}