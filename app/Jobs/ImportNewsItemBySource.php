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
    private string $origin;

    /**
     * Create a new job instance.
     */
    public function __construct(string $origin, string $source)
    {
        $this->source = $source;
        $this->origin = $origin;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call("import:news-items {$this->origin} {$this->source}");
    }
}
