<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    public function index(ArticleService $service, Request $request)
    {
        $request->validate([
            'origin'   => 'string|exists:news_articles,origin',
            'source'   => 'string|exists:news_sources,slug',
            'locale' => 'string|exists:news_sources,language',
        ]);

        return [
            'data' => $service->getAuthors(
                $request->get('origin'),
                $request->get('source'),
                $request->get('locale')
            )
        ];
    }
}
