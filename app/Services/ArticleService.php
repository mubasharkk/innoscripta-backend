<?php

namespace App\Services;

use App\Models\NewsArticle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleService
{
    public function findById(int $id): NewsArticle
    {
        return NewsArticle::find($id);
    }

    public function get(?string $source = null, ?string $language = null, $pageSize = 10): LengthAwarePaginator
    {
        return NewsArticle::limit($pageSize)
            ->select('news_articles.*', 'news_sources.language', 'news_sources.country')
            ->leftJoin('news_sources', 'news_sources.slug', 'news_articles.source_slug')
            ->where(array_filter([
                'language'    => $language,
                'source_slug' => $source
            ]))
            ->orderBy('published_at', 'DESC')
            ->paginate();
    }
}
