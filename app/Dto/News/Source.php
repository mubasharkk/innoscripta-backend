<?php

namespace App\Dto\News;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Source implements Arrayable
{
    private $slug;
    private $name;
    private $category;
    private $language;
    private $description;
    private $country;
    private $url;

    public function __construct(
        string $slug,
        string $name,
        ?string $category = null,
        ?string $language = null,
        ?string $country = null,
        ?string $description = null,
        ?string $url = null
    ) {
        $this->slug = $slug;
        $this->name = $name;
        $this->category = $category;
        $this->language = $language;
        $this->description = $description;
        $this->country = $country;
        $this->url = $url;
    }

    public function toArray()
    {
        return [
            'slug'        => $this->slug,
            'name'        => $this->name,
            'description' => $this->description,
            'category'    => $this->category,
            'language'    => $this->language,
            'country'     => $this->country,
            'url'         => $this->url,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ];
    }
}
