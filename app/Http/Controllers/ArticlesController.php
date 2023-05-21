<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Carbon\Carbon;
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
            'origin'   => 'nullable|string|exists:news_sources,origin',
            'fields'   => 'nullable|string',
            'locale'   => 'nullable|string|size:2|in:en,de',
            'source'   => 'nullable|string|exists:news_sources,slug',
            'category' => 'nullable|string|exists:news_sources,category',
            'author'   => 'nullable|string|exists:news_articles,author',
            'keyword'  => 'nullable|string|max:120',
            'fromDate' => 'nullable|date|date_format:Y-m-d',
            'tillDate' => 'nullable|date|date_format:Y-m-d',
            'page'     => 'int',
        ]);

        return ArticleResource::collection(
            $this->service->get(
                $request->get('origin'),
                $request->get('source'),
                $request->get('locale', 'en'),
                $request->get('category'),
                $request->get('author'),
                $request->get('keyword'),
                $request->get('fromDate') ? Carbon::createFromFormat('Y-m-d', $request->get('fromDate')) : null,
                $request->get('tillDate') ? Carbon::createFromFormat('Y-m-d', $request->get('tillDate')) : null,
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
