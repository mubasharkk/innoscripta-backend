<?php

namespace App\Services\Importers;

use App\Dto\News\Source;
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

            $this->insertData($results);
        }
    }
}
