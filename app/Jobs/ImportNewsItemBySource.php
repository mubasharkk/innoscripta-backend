<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ImportNewsItemBySource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $source;

    /**
     * Create a new job instance.
     */
    public function __construct(string $source)
    {
        $this->source = $source;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call("import:news-items {$this->source}");
    }
}
