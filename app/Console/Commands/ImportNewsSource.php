<?php

namespace App\Console\Commands;

use App\Services\Importers\NewsApiOrgImporter;
use Illuminate\Console\Command;

class ImportNewsSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:news-sources {--category=} {--lang=en} {--country=us}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import news sources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->dataFromNewsApiOrg();
    }

    public function dataFromNewsApiOrg()
    {
        $this->info("Importing all sources from newsapi.org API.");

        (new NewsApiOrgImporter())->fetchAndSaveSources(
            $this->option('category'),
            $this->option('lang'),
            $this->option('country')
        );
    }
}
