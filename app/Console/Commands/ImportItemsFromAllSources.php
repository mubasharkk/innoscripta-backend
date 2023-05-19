<?php

namespace App\Console\Commands;

use App\Jobs\ImportNewsItemBySource;
use App\Models\NewsSource;
use App\Services\Importers\NewsApiOrgImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class ImportItemsFromAllSources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:news-items-from-sources {origin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $importers = [
        NewsApiOrgImporter::ORIGIN
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $origin = $this->argument('origin');
        if (!in_array($origin, $this->importers)) {
            $this->error("No importer found for origin `{$origin}`.");
            $origin = $this->choice('Which origin you want to import sources from?', $this->importers);
        }

        $sources = NewsSource::where(['origin' => $origin])->get();
        foreach ($sources as $source) {
            Queue::push(new ImportNewsItemBySource($origin, $source->slug), [], 'source-news-item');
            $this->info("Job create to import items for `{$source->slug}` source from {$origin}");
        }
    }
}
