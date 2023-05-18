<?php

namespace App\Console\Commands;

use App\Models\NewsSource;
use App\Services\DTos\Source;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use jcobhams\NewsApi\NewsApi;

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
        $config = config('news-api.news-api-org');
        $newsApi = new NewsApi($config['apiKey']);
        try {
            $data = $newsApi->getSources(
                $this->option('category'),
                $this->option('lang'),
                $this->option('country')
            );
            if ($data->status == 'ok' && !empty($data->sources)) {
                $results = collect();
                foreach ($data->sources as $item) {
                    $results->push(
                        new Source($item->id,
                            $item->name,
                            $item->category,
                            $item->language,
                            $item->country,
                            $item->description
                        )
                    );
                }

                $this->insertData($results);
            }
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }

    private function insertData(Collection $collection)
    {
        NewsSource::insertOrIgnore($collection->toArray());
    }
}
