<?php

namespace App\Services\Importers;

use App\Dto\News\Item;
use App\Dto\News\Source;
use App\Models\NewsItem;
use App\Models\NewsSource;
use Carbon\Carbon;
use jcobhams\NewsApi\NewsApi;

class NewsApiOrgImporter implements ApiImporter
{
    use SaveSourceToDB;

    private NewsApi $api;
    private mixed $config;

    public const ORIGIN = 'news-api-org';

    public function __construct()
    {
        $this->config = config('news-api.news-api-org');
        $this->api = new NewsApi($this->config['apiKey']);
    }

    public function fetchAndSaveSources(
        ?string $category = null,
        ?string $langauge = null,
        ?string $country = null
    ) {
        $response = $this->api->getSources($category, $langauge, $country);
        if ($response->status == 'ok' && !empty($response->sources)) {
            $results = collect();
            foreach ($response->sources as $item) {
                $results->push(
                    new Source(
                        self::ORIGIN,
                        $item->id,
                        $item->name,
                        $item->category,
                        $item->language,
                        $item->country,
                        $item->description,
                        $item->url
                    )
                );
            }

            $this->insertData(new NewsSource, $results);
        }
    }

    public function fetchAndSaveNewsItems(string $source, ?string $domain = null, ?string $language = 'en', int $page = 1)
    {
        $response = $this->api->getEverything(
            null,
            $source,
            $domain,
            null,
            null,
            null,
            $language,
            'publishedAt',
            null,
            $page
        );

        if ($response->status == 'ok' && !empty($response->articles)) {
            $results = collect();
            foreach ($response->articles as $item) {
                $results->push(
                    new Item(
                        self::ORIGIN,
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

            $this->insertData(new NewsItem, $results);
        }
    }
}
