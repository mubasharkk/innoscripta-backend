<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SourceResource extends JsonResource
{
    use i18n;

    public function toArray(Request $request): array
    {
        return [
            'slug'        => $this->slug,
            'title'       => $this->name,
            'description' => $this->description,
            'category'    => $this->category,
            'country'     => $this->location($this->countryData),
            'language'    => $this->locale($this->languageData),
            'url'         => $this->url,
        ];
    }
}
