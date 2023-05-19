<?php

namespace App\Services\Importers;

use App\Dto\News\Item;
use App\Dto\News\Source;
use App\Models\NewsArticle;
use App\Models\NewsSource;
use Carbon\Carbon;
use GuzzleHttp\Client;

class GuardianDataImporter implements ApiImporter
{
    use SaveSourceToDB;

    public const ORIGIN = 'the-guardian';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://content.guardianapis.com',
        ]);
        $this->config = config('news-api.guardian');
    }

    private function sendRequest(string $uri, array $params = [])
    {
        $response = $this->client->get($uri, [
            'query' => array_merge(['api-key' => $this->config['apiKey']], $params),
        ]);

        // addition response handling can be added here
        return \json_decode($response->getBody()->getContents())->response;
    }

    public function fetchAndSaveSources(?string $category, ?string $langauge, ?string $country)
    {
        $data = $this->sendRequest('sections');
        $results = collect();
        foreach ($data->results as $item) {
            $results->push(
                new Source(
                    self::ORIGIN,
                    $this->sectionId($item->id),
                    $item->webTitle,
                    null,
                    $langauge ?? 'en',
                    $country ?? 'us',
                    null,
                    $item->webUrl
                )
            );
        }

        $this->insertData(new NewsSource, $results);
    }

    public function fetchAndSaveNewsItems(
        string $source,
        ?string $domain = null,
        ?string $language = 'en',
        int $page = 1
    ) {
        $data = $this->sendRequest('search', [
            'sectionId'   => str_replace(self::ORIGIN.':', '', $source),
            'show-fields' => 'trailText,thumbnail,short-url,lastModified,body',
            'show-tags'   => 'contributor',
            'lang'        => $language,
            'page'        => $page,
            'page-size'   => 50
        ]);

        $results = collect();
        foreach ($data->results as $item) {
            $contributors = array_map(function ($tag) {
                return $tag->webTitle;
            }, $item->tags);

            $results->push(
                new Item(
                    self::ORIGIN,
                    $item->webTitle,
                    $item->fields->trailText,
                    $this->sectionId($item->sectionId),
                    $item->fields->body,
                    Carbon::createFromFormat(Carbon::ATOM, $item->fields->lastModified),
                    implode(' & ', $contributors),
                    $item->fields->shortUrl,
                    $item->fields->thumbnail
                )
            );
        }

        $this->insertData(new NewsArticle, $results);
    }

    private function sectionId(string $id): string
    {
        return self::ORIGIN.":".$id;
    }
}
