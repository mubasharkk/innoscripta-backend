<?php

namespace App\Services\Importers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait SaveSourceToDB
{
    private function insertData(Model $model, Collection $collection)
    {
        $model::insertOrIgnore(array_map(function ($source) {
            return array_merge($source, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }, $collection->toArray()));
    }

}
