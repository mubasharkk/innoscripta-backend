<?php

namespace App\Services\DTos;

use Illuminate\Contracts\Support\Arrayable;

class Source implements Arrayable
{
    private $slug;
    private $name;
    private $category;
    private $language;
    private $description;
    private $country;

    public function __construct(
        string $slug,
        string $name,
        ?string $category = null,
        ?string $language = 'en',
        ?string $country = 'us',
        ?string $description = ''
    ) {
        $this->slug = $slug;
        $this->name = $name;
        $this->category = $category;
        $this->language = $language;
        $this->description = $description;
        $this->country = $country;
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
        ];
    }
}
