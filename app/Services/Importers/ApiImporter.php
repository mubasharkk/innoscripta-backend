<?php

namespace App\Services\Importers;

interface ApiImporter
{
    public function fetchAndSaveSources(?string $category, ?string $language, ?string $country);

    public function fetchAndSaveNewsItems(
        string $source,
        ?string $domain = null,
        ?string $language = 'en',
        int $page = 1
    );
}
