<?php

namespace App\Services\Importers;

use App\Dto\News\Source;
use App\Models\NewsSource;
use GuzzleHttp\Client;
use Illuminate\Http\Response;

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
        return $this->client->get($uri, [
            'query' => array_merge(['api-key' => $this->config['apiKey']], $params),
        ]);
    }

    public function fetchAndSaveSources(?string $category, ?string $langauge, ?string $country)
    {
        $response = $this->sendRequest('sections');
        if ($response->getStatusCode() == Response::HTTP_OK) {
            $data = \json_decode($response->getBody()->getContents())->response;
            $results = collect();
            foreach ($data->results as $item) {
                $results->push(
                    new Source(
                        self::ORIGIN,
                        self::ORIGIN.":".$item->id,
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
    }

    public function fetchAndSaveNewsItems(
        string $source,
        ?string $domain = null,
        ?string $language = 'en',
        int $page = 1
    ) {

    }
}
