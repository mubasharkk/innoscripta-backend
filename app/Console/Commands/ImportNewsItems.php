<?php

namespace App\Console\Commands;

use App\Services\Importers\NewsApiOrgImporter;
use Illuminate\Console\Command;

class ImportNewsItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:news-items {origin} {source} {--domain=} {--page=1} {--language=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import news items and articles for a particular source';

    private $importers = [
        NewsApiOrgImporter::ORIGIN => NewsApiOrgImporter::class,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->argument('source');
        $origin = $this->argument('origin');

        $this->info("Importing articles from {$origin} API for source `{$source}`.");

        (new $this->importers[$origin])->fetchAndSaveNewsItems(
            $source,
            $this->option('domain'),
            $this->option('language'),
            $this->option('page')
        );
    }
}
