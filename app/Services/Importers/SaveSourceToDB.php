<?php

namespace App\Services\Importers;

use App\Models\NewsSource;
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait SaveSourceToDB
{
    private function insertData(Collection $collection)
    {
        NewsSource::insertOrIgnore(array_map(function ($source) {
            return array_merge($source, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }, $collection->toArray()));
    }

}
