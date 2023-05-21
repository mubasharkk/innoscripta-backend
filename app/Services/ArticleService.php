<?php

namespace App\Services;

use App\Models\NewsArticle;
use App\Models\NewsSource;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ArticleService
{
    public function findById($id): ?NewsArticle
    {
        return NewsArticle::find($id);
    }

    public function get(
        ?string $origin,
        ?string $source = null,
        ?string $language = null,
        ?string $category = null,
        ?string $author = null,
        ?string $keyword = null,
        ?Carbon $fromDate = null,
        ?Carbon $tillDate = null,
        $pageSize = 10
    ): LengthAwarePaginator {
        $query = NewsArticle::limit($pageSize)
            ->select('news_articles.*', 'news_sources.language', 'news_sources.country')
            ->leftJoin('news_sources', 'news_sources.slug', 'news_articles.source_slug')
            ->where(array_filter([
                'news_articles.origin' => $origin,
                'language'             => $language,
                'source_slug'          => $source,
                'category'             => $category,
                'author'               => $author,
            ]))
            ->orderBy('published_at', 'DESC');

        if ($keyword) {
            $query->where('title', 'LIKE', '%'.$keyword.'%');
        }

        if ($fromDate) {
            $query->whereBetween('published_at', [$fromDate, $tillDate ?? Carbon::now()]);
        }

        return $query->paginate();
    }

    public function getAuthors(?string $origin, ?string $source, ?string $language): \Illuminate\Support\Collection
    {
        return NewsArticle::select(\DB::raw('DISTINCT author'))
            ->leftJoin('news_sources', 'news_sources.slug', 'news_articles.source_slug')
            ->where(array_filter([
                'news_articles.origin' => $origin,
                'source_slug'          => $source,
                'language'             => $language
            ]))
            ->where('author', '!=', '')
            ->orderBy('author', 'ASC')
            ->groupBy('author')->get()->map(function ($item) {
                return $item['author'];
            });
    }

    public function getSources(?string $origin, ?string $language, ?string $country)
    {
        return NewsSource::where(array_filter([
            'origin'   => $origin,
            'language' => $language,
            'country'  => $country,
        ]))->get();
    }
}
