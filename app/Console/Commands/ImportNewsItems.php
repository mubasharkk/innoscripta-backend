<?php

namespace App\Console\Commands;

use App\Dto\News\Item;
use App\Models\NewsItem;
use Carbon\Carbon;
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
        $this->dataFromNewsApiOrg();
    }

    public function dataFromNewsApiOrg()
    {
        $config = config('news-api.news-api-org');
        $newsApi = new NewsApi($config['apiKey']);
        $response = $newsApi->getEverything(null, $this->argument('source'));
        if ($response->status == 'ok' && !empty($response->articles)) {
            $results = collect();

            foreach ($response->articles as $item) {
                $results->push(
                    new Item(
                        $item->title,
                        nl2br($item->description),
                        $item->source?->id,
                        nl2br($item->content),
                        Carbon::createFromFormat(Carbon::ATOM, $item->publishedAt),
                        $item->author,
                        $item->url,
                        $item->urlToImage
                    )
                );
            }

            $this->insertData($results);
        }
    }

    private function insertData(Collection $collection)
    {
        NewsItem::insertOrIgnore(array_map(function ($source) {
            return array_merge($source, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }, $collection->toArray()));
    }
}
