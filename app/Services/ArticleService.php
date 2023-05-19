<?php

namespace App\Services;

use App\Models\NewsItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleService
{
    public function findById(int $id): NewsItem
    {
        return NewsItem::find($id);
    }

    public function get(?string $source = null, ?string $language = null, $pageSize = 10): LengthAwarePaginator
    {
        return NewsItem::limit($pageSize)
            ->select('news_items.*', 'news_sources.language', 'news_sources.country')
            ->leftJoin('news_sources', 'news_sources.slug', 'news_items.source_slug')
            ->where(array_filter([
                'language'    => $language,
                'source_slug' => $source
            ]))
            ->orderBy('published_at', 'DESC')
            ->paginate();
    }
}
