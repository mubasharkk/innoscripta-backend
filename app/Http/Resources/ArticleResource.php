<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $fields = explode(',', $request->get('fields'));
        return [
            'id'          => $this->id,
            'origin'      => $this->origin,
            'source'      => $this->when(!in_array('source', $fields), new SourceResource($this->source)),
            'title'       => $this->title,
            'teaser'      => $this->description,
            'body'        => $this->when(in_array('body', $fields), $this->content),
            'author'      => $this->author,
            'publishedAt' => $this->published_at,
            'url'         => $this->url,
            'image'       => $this->image_url,
        ];
    }
}
