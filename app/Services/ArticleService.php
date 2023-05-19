<?php

namespace App\Services;

use App\Models\NewsItem;
use Illuminate\Database\Eloquent\Collection;

class ArticleService
{
    public function findById(int $id)
    {
    }

    public function get(string $language, $pageSize = 10): Collection
    {
        return NewsItem::limit($pageSize)
            ->select('news_items.*', 'news_sources.language', 'news_sources.country')
            ->leftJoin('news_sources', 'news_sources.slug', 'news_items.source_slug')
            ->where([
                'language' => $language
            ])
            ->orderBy('published_at', 'DESC')
            ->paginate();
    }
}
