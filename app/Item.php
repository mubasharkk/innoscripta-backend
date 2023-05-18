<?php

namespace App\DTOs\News;

use Illuminate\Contracts\Support\Arrayable;

class Item implements Arrayable
{
    private $title;
    private $description;
    private $content;
    private $author;
    private $url;
    private $imageUrl;

    public function __construct(
        string $title,
        string $description,
        ?string $content = null,
        ?string $author = null,
        ?string $url = null,
        ?string $imageUrl = null
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->author = $author;
        $this->url = $url;
        $this->imageUrl = $imageUrl;
    }

    public function toArray()
    {
        return [
            'title'       => $this->title,
            'description' => $this->description ?? '',
            'content'     => $this->content ?? '',
            'author'      => $this->author ?? '',
            'url'         => $this->url ?? '',
            'imageUrl'    => $this->imageUrl ?? '',
        ];
    }
}
