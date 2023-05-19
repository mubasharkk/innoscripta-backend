<?php

namespace App\Dto\News;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Item implements Arrayable
{
    private $title;
    private $description;
    private $content;
    private $author;
    private $url;
    private $imageUrl;
    private $sourceSlug;
    private Carbon $publishedAt;
    private string $origin;

    public function __construct(
        string $origin,
        string $title,
        string $description,
        string $sourceSlug,
        ?string $content = null,
        ?Carbon $publishedAt = null,
        ?string $author = null,
        ?string $url = null,
        ?string $imageUrl = null
    ) {
        $this->origin = $origin;
        $this->title = $title;
        $this->description = $description;
        $this->sourceSlug = $sourceSlug;
        $this->content = $content;
        $this->author = $author;
        $this->url = $url;
        $this->imageUrl = $imageUrl;
        $this->publishedAt = $publishedAt ?? Carbon::now();
    }

    public function toArray()
    {
        return [
            'origin'       => $this->origin,
            'title'       => $this->title,
            'description' => $this->description ?? '',
            'content'     => $this->content ?? '',
            'source_slug' => $this->sourceSlug,
            'author'      => $this->author ?? '',
            'url'         => $this->url ?? '',
            'image_url'   => $this->imageUrl ?? '',
            'published_at' => $this->publishedAt
        ];
    }
}
