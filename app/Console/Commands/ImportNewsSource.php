<?php

namespace App\Console\Commands;

use App\Services\Importers\GuardianDataImporter;
use App\Services\Importers\NewsApiOrgImporter;
use Illuminate\Console\Command;

class ImportNewsSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:news-sources {origin} {--category=} {--lang=en} {--country=us}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import news sources';

    private $importers = [
        NewsApiOrgImporter::ORIGIN => NewsApiOrgImporter::class,
        GuardianDataImporter::ORIGIN => GuardianDataImporter::class,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $origin = $this->argument('origin');
        if (!in_array($origin, $this->importers)) {
            $this->error("No importer found for origin `{$origin}`.");
            $origin = $this->choice(
                'Which origin you want to import sources from?',
                $this->importers
            );
        }

        $this->info("Importing all sources from  API.");

        (new $this->importers[$origin])->fetchAndSaveSources(
            $this->option('category'),
            $this->option('lang'),
            $this->option('country')
        );
    }
}
