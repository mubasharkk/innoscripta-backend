<?php

namespace App\Console\Commands;

use App\Models\NewsItem;
use App\Models\NewsSource;
use App\Services\DTOs\News\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use jcobhams\NewsApi\NewsApi;

class ImportNewsItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:news-items {source} {domains=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import news items and articles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }

    public function dataFromNewsApiOrg()
    {
        $config = config('news-api.news-api-org');
        $newsApi = new NewsApi($config['apiKey']);
        $data = $newsApi->getSources(
            $this->option('category'),
            $this->option('lang'),
            $this->option('country')
        );
        if ($data->status == 'ok' && !empty($data->sources)) {
            $results = collect();
            foreach ($data->sources as $item) {
                $results->push(
                    new Item($item->id,
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
    }

    private function insertData(Collection $collection)
    {
        NewsItem::insert($collection->toArray());
    }
}
