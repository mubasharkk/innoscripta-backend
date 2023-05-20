<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    private ArticleService $service;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the articles.
     */
    public function index(Request $request)
    {
        $request->validate([
            'origin'   => 'string|exists:news_sources,origin',
            'page'     => 'int',
            'fields'   => 'string',
            'locale'   => 'string|size:2|in:en,de',
            'source'   => 'string|exists:news_sources,slug',
            'category' => 'string|exists:news_sources,category',
            'author'   => 'string|exists:news_articles,author',
            'keyword'  => 'string',
        ]);

        return ArticleResource::collection(
            $this->service->get(
                $request->get('origin'),
                $request->get('source'),
                $request->get('locale', 'en'),
                $request->get('category'),
                $request->get('page', 25)
            )
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $request->merge(['fields' => 'body']);

        if ($article = $this->service->findById($id)) {
            return new ArticleResource($article);
        } else {
            throw new ModelNotFoundException("Article `$id` not found.");
        }
    }
}
