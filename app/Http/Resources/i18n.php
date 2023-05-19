<?php

namespace App\Http\Resources;

use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\Language;

trait i18n
{
    public function locale(Language $lang): array
    {
        return [
            'code'        => $lang->code,
            'name'        => $lang->name,
            'name_native' => $lang->name_native,
        ];
    }

    public function location(Country $country): array
    {
        return [
            'code' => $country->iso2,
            'name' => $country->name,
        ];
    }
}
