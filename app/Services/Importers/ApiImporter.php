<?php

namespace App\Services\Importers;

use Illuminate\Support\Collection;

interface ApiImporter
{
    public function fetchAndSaveSources(?string $category, ?string $langauge, ?string $country);

//    public function fetchNewsItems(): bool;
}
