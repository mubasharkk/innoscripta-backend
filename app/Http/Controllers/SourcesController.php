<?php

namespace App\Http\Controllers;

use App\Http\Resources\SourceResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class SourcesController extends Controller
{
    public function index(Request $request, ArticleService $service)
    {
        $request->validate([
            'origin'  => 'string|exists:news_sources,origin',
            'country' => 'string|exists:news_sources,country|size:2',
            'locale'  => 'string|exists:news_sources,language|size:2',
        ]);

        return SourceResource::collection(
            $service->getSources(
                $request->get('origin'),
                $request->get('locale'),
                $request->get('country')
            )
        );
    }
}
