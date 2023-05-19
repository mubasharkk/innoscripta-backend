<?php

namespace App\Console\Commands;

use App\Jobs\ImportNewsItemBySource;
use App\Models\NewsSource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class ImportItemsFromAllSources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:news-items-from-sources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sources = NewsSource::all();
        foreach ($sources as $source) {
            Queue::push(new ImportNewsItemBySource($source->slug), [], 'source-news-item');
            $this->info("Job create to import items for `{$source->slug}` source from news-api.org");
        }
    }
}
