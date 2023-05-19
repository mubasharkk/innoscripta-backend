<?php

namespace App\Services;

use App\Models\NewsArticle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ArticleService
{
    public function findById($id): ?NewsArticle
    {
        return NewsArticle::find($id);
    }

    public function get(
        ?string $source = null,
        ?string $language = null,
        ?string $category = null,
        $pageSize = 10
    ): LengthAwarePaginator {
        return NewsArticle::limit($pageSize)
            ->select('news_articles.*', 'news_sources.language', 'news_sources.country')
            ->leftJoin('news_sources', 'news_sources.slug', 'news_articles.source_slug')
            ->where(array_filter([
                'language'    => $language,
                'source_slug' => $source,
                'category'    => $category
            ]))
            ->orderBy('published_at', 'DESC')
            ->paginate();
    }

    public function getAuthors(string $origin, ?string $source): \Illuminate\Support\Collection
    {
        return NewsArticle::select('author')
            ->where(array_filter([
                'origin'      => $origin,
                'source_slug' => $source
            ]))
            ->groupBy('author')->get()->map(function ($item) {
                return $item['author'];
            });
    }
}
