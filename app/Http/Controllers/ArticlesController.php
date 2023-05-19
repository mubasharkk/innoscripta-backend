<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
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
        $data = $request->validate([
            'page'   => 'int',
            'fields' => 'string',
            'locale' => 'string|size:2|in:en,de',
            'source' => 'string|exists:news_sources,slug',
        ]);

        return ArticleResource::collection(
            $this->service->get(
                $data['source'] ?? null,
                $data['locale'] ?? 'en',
                $data['page'] ?? 25
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return new ArticleResource(
            $this->service->findById($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
